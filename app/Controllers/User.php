<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\ExamsModel;
use App\Models\QuestionsModel;
use App\Models\OptionsModel;
use App\Models\UsersEnrollModel;
use App\Models\UsersAnswerModel;

class User extends BaseController
{
    protected $db;
    protected $usersModel;
    protected $examsModel;
    protected $optionsModel;
    protected $usersEnrollModel;
    protected $usersAnswerModel;
    protected $questionsModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->usersModel = new UsersModel();
        $this->examsModel = new ExamsModel();
        $this->optionsModel = new OptionsModel();
        $this->questionsModel = new QuestionsModel();
        $this->usersEnrollModel = new UsersEnrollModel();
        $this->usersAnswerModel = new UsersAnswerModel();
    }

    public function index()
    {
        $data["title"] = "My Profile";

        return view('user/index', $data);
    }

    public function update($id = 0)
    {
        if ($id != user()->id) return redirect()->to("/user");

        $user_data = $this->usersModel->find($id);
        $validation = \Config\Services::validation();

        $data = [
            'title'      => 'Edit Profile',
            'user'       => $user_data,
            'validation' => $validation,
        ];

        return view('user/update', $data);
    }

    public function attempt_update()
    {
        // cek username user
        if (user()->username == $this->request->getVar("username")) {
            $username_rules = 'required|alpha_numeric|min_length[3]|max_length[30]';
        } else {
            $username_rules = 'required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[30]';
        }

        // cek email user
        if (user()->email == $this->request->getVar("email")) {
            $email_rules = 'required|valid_email|max_length[255]';
        } else {
            $email_rules = 'required|is_unique[users.email]|valid_email|max_length[255]';
        }

        if (!$this->validate([
            'username'     => $username_rules,
            'email'        => $email_rules,
            'fullname'     => 'required|min_length[3]|max_length[255]',
            'phone_number' => 'required|max_length[30]',
            'gender'       => 'required',
            'address'      => 'required|max_length[255]',
            'profile_pict' => [
                'rules'  => 'max_size[profile_pict,1024]|is_image[profile_pict]|mime_in[profile_pict,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'File size is to large (max: 1024kb).',
                    'is_image' => 'File extension not supported.',
                    'mime_in'  => 'File extension not supported.',
                ],
            ],
        ])) {
            return redirect()->to("/user/" . $this->request->getVar("id"))->withInput();
        }

        // uploaded file
        $file_img = $this->request->getFile("profile_pict");
        // old file
        $old_img = $this->request->getVar('oldpict');

        // check neither user upload file or not
        if ($file_img->getError() == 4) {
            $file_name = $this->request->getVar('oldpict');
        } else {
            // generate a random name
            $file_name = $file_img->getRandomName();
            // move file to img folder
            $file_img->move('img/profile', $file_name);
            // delete old file from img folder, if
            if ($old_img != 'default.png') {
                unlink('img/profile/' . $old_img);
            }
        }

        $this->usersModel->save([
            'id' => $this->request->getVar("id"),
            'username' => $this->request->getVar("username"),
            'email' => $this->request->getVar("email"),
            'fullname' => $this->request->getVar("fullname"),
            'phone_number' => $this->request->getVar("phone_number"),
            'gender' => $this->request->getVar("gender"),
            'address' => $this->request->getVar("address"),
            'profile_pict' => $file_name,
        ]);

        session()->setFlashData("message", "Profile data has been updated.");

        return redirect()->to("/user");
    }

    public function update_password($id = 0)
    {
        if ($id != user()->id) return redirect()->to("/user");

        $validation = \Config\Services::validation();

        $data = [
            'title'      => 'Change Password',
            'validation' => $validation,
        ];

        return view('user/password', $data);
    }

    public function attempt_password()
    {
        $users = model(UserModel::class);

        $user = $users->where('email', $this->request->getVar('email'))->first();

        if (is_null($user) || $user->email != user()->email) {
            return redirect()->back()->withInput()->with('error', 'Your email address is invalid');
        }

        // Save the reset hash
        $user->generateResetHash();
        $users->save($user);
        $resetter = service('resetter');
        $sent = $resetter->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.unknownError'));
        }

        return redirect()->back()->withInput()->with('info', lang('Auth.forgotEmailSent'));
    }

    public function attempt_reset()
    {
        if ($this->request->getVar("id")) {
            $users = model(UserModel::class);

            // First things first - log the reset attempt.
            $users->logResetAttempt(
                $this->request->getVar('confirm-email'),
                $this->request->getVar('token'),
                $this->request->getIPAddress(),
                (string)$this->request->getUserAgent(),
            );

            if (!$this->validate([
                'token'           => 'required',
                'confirm-email'   => 'required|valid_email',
                'new-password'    => 'required|strong_password',
                'repeat-password' => 'required|matches[new-password]',
            ])) {
                return redirect()->to("/update_password/" . $this->request->getVar("id"))->withInput();
            }

            $user = $users->where('email', $this->request->getVar('confirm-email'))
                ->where('reset_hash', $this->request->getVar('token'))
                ->first();

            if (is_null($user) || $user->email != user()->email) {
                return redirect()->back()->withInput()->with('error', 'Your confirm email address or token is invalid');
            }

            // Check if reset token still valid
            if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
                return redirect()->back()->withInput()->with('error', lang('Auth.resetTokenExpired'));
            }

            // Success! Save the new password, and cleanup the reset hash.
            $user->password         = $this->request->getVar('new-password');
            $user->reset_hash       = null;
            $user->reset_at         = date('Y-m-d H:i:s');
            $user->reset_expires    = null;
            $user->force_pass_reset = false;
            $users->save($user);

            return redirect()->to('/user')->with('message', 'Your password has been change.');
        }
    }

    public function exam_detail($exam_id = 0, $color = '')
    {
        if (!is_numeric($exam_id) || empty($color)) return redirect()->back();

        $exam = $this->examsModel->find($exam_id);

        $enrolled = $this->usersEnrollModel->where([
            'user_id' => user()->id,
            'exam_id' => $exam_id,
        ])->first();

        $data = [
            'title'    => 'Exam Detail',
            'exam'     => $exam,
            'color'    => $color,
            'enrolled' => $enrolled,
        ];

        return view('user/exam_detail', $data);
    }

    public function enroll_exam()
    {
        if ($this->request->getVar("action") == "enroll_exam") {
            // first thing, user must complete personal data
            if (
                empty(user()->fullname) ||
                empty(user()->phone_number) ||
                empty(user()->gender) ||
                empty(user()->address)
            ) {
                echo json_encode(false);
                return 0;
            }

            // insert into user_exam_enroll_table
            $this->usersEnrollModel->save([
                "user_id" => user()->id,
                "exam_id" => $this->request->getVar("exam_id"),
            ]);

            // get questions
            $questions = $this->questionsModel
                ->where(['exam_id' => $this->request->getVar("exam_id")])
                ->findAll();

            // get exam
            $exam = $this->examsModel->find($this->request->getVar("exam_id"));

            // insert into user_exam_answer_table
            foreach ($questions as $question) {
                $this->usersAnswerModel->save([
                    'user_id'            => user()->id,
                    'exam_id'            => $exam["id"],
                    'question_id'        => $question["id"],
                    'scores'             => $exam["score_per_empty_answer"],
                    'user_answer_option' => "0",
                ]);
            }

            // create view exam result for this user
            $this->db->query(
                'CREATE VIEW result_exam_user_' . $exam["id"] . '_' . user()->id .
                    ' AS
                SELECT question_table.id AS id,
                    question_table.exam_id AS exam_id,
                    image,
                    title,
                    types,
                    user_answer_option,
                    options,
                    scores
                FROM question_table
                        INNER JOIN user_exam_answer_table ueat on question_table.id = ueat.question_id
                WHERE question_table.exam_id = ' . $exam["id"] .
                    ' AND user_id = ' . user()->id .
                    ' ORDER BY question_table.id'
            );

            echo json_encode(true);
            return 1;
        }
    }

    public function cancel_exam()
    {
        if ($this->request->getVar("action") == "cancel_exam") {
            $exam_id = $this->request->getVar('exam_id');

            $user_exam_enroll = $this->usersEnrollModel
                ->where([
                    'user_id' => user()->id,
                    'exam_id' => $exam_id,
                ])->first();

            $this->usersEnrollModel->delete($user_exam_enroll['id']);

            $user_exam_answers = $this->usersAnswerModel
                ->where([
                    'user_id' => user()->id,
                    'exam_id' => $exam_id,
                ])->findAll();

            foreach ($user_exam_answers as $answer) {
                $this->usersAnswerModel->delete($answer['id']);
            }

            // drop user exam result
            $this->db->query(
                'DROP VIEW IF EXISTS result_exam_user_' . $exam_id  . '_' . user()->id
            );

            echo 'Anda telah dikeluarkan dari exam ini.';
        }
    }

    public function exam_list()
    {
        if (!in_groups('user')) return redirect()->back();

        $query = $this->db->query(
            'SELECT
                user_exam_enroll_table.exam_id AS id,
                title,
                implement_date,
                duration,
                total_question,
                score_per_right_answer,
                score_per_wrong_answer,
                score_per_empty_answer,
                status,
                code
            FROM user_exam_enroll_table
                INNER JOIN exam_table ON user_exam_enroll_table.exam_id = exam_table.id
            WHERE user_exam_enroll_table.user_id = ' . user()->id . '
                ORDER BY implement_date ASC'
        );

        $exams = $query->getResultArray();

        foreach ($exams as $exam) {
            $this->examsModel->changeExamStatus($exam);
        }

        $data = [
            'title'       => 'My Exam List',
            'exams'       => $exams,
            'exam_status' => $this->examsModel->listStatus(),
        ];

        return view('user/exam_list', $data);
    }

    public function exam_view($user_id = 0)
    {
        if (
            !in_groups('user') ||
            !is_numeric($user_id) ||
            user()->id != $user_id
        ) return redirect()->back();

        if ($this->request->getGet('code')) {
            date_default_timezone_set('Asia/Makassar');

            $exam = $this->examsModel
                ->where(['code' => $this->request->getGet('code')])
                ->first();

            if (!$exam) return redirect()->back();

            $is_enrolled = $this->usersEnrollModel
                ->where(['user_id' => user()->id, 'exam_id' => $exam['id']])
                ->first();

            if (!$is_enrolled || $exam['status'] != $this->examsModel->listStatus()[2]) {
                return redirect()->back();
            }

            $exam_end_time = strtotime($exam['implement_date'] . '+' . $exam['duration'] . 'minutes');

            $remaining_seconds = ($exam_end_time - time()) + 60;

            $data['attendance_status'] = 'Attend';

            $this->usersEnrollModel->update($is_enrolled['id'], $data);

            $data = [
                'title'               => $exam['title'],
                'exam_time_remaining' => $remaining_seconds,
            ];

            return view('user/exam_view', $data);
        }

        return redirect()->back();
    }

    public function load_question()
    {
        if ($this->request->getVar("action") == "load_question") {
            $exam = $this->examsModel
                ->where(['code' => $this->request->getVar("exam_code")])
                ->first();

            if (empty($this->request->getVar("question_id"))) {
                $question = $this->questionsModel
                    ->where(['exam_id' => $exam['id']])
                    ->orderBy('id', 'ASC')
                    ->first();
            } else {
                $question = $this->questionsModel
                    ->find($this->request->getVar("question_id"));
            }

            $opt = $this->optionsModel
                ->where(["question_id" => $question["id"]])
                ->orderBy('option_char', 'ASC')
                ->findAll();

            $prev_question = $this->db->query(
                'SELECT * FROM question_table
                    WHERE id < ' . $question["id"] . ' AND exam_id = ' . $exam["id"] .
                    ' ORDER BY id DESC LIMIT 1'
            );
            $next_question = $this->db->query(
                'SELECT * FROM question_table
                    WHERE id > ' . $question["id"] . ' AND exam_id = ' . $exam["id"] .
                    ' ORDER BY id ASC LIMIT 1'
            );

            $prev_question_id = empty($prev_question->getRowArray()) ? null : $prev_question->getRowArray()["id"];
            $next_question_id = empty($next_question->getRowArray()) ? null : $next_question->getRowArray()["id"];

            $prev_btn_disable =  $prev_question_id ? '' : 'disabled';
            $next_btn_disable = $next_question_id ? '' : 'disabled';

            $user_answer = $this->usersAnswerModel
                ->where([
                    "user_id"     => user()->id,
                    "exam_id"     => $exam["id"],
                    "question_id" => $question["id"],
                ])->first()["user_answer_option"];

            $output = '
                <div class="row d-flex flex-column justify-content-center align-items-center mb-4">
                    <div class="col-md">
                        <h6 class="mb-4" style="font-weight: 600;">Tipe Soal : ' . $question["types"] . '</h6>
                        <h6 class="m-0">' . $question["title"] . '</h6>
                    </div>
                    ';

            if (!empty($question["image"])) {
                $output .= '
                    <div class="col-md mb-2 mt-4 text-center">
                        <img class="image-view img-thumbnail" src="' . base_url("img/exam/" . $question["image"]) . '" data-action="zoom">
                    </div>
                    ';
            }

            $output .= '
                </div>
                    <div class="row d-flex flex-column mb-4">
                        <div class="col-md mb-2">
                            <div class="option-container">
                                <input type="radio" class="radio-option" name="options" id="' . $opt[0]["id"] . '" data-questionid="' . $opt[0]["question_id"] . '" value="A" ' . ($user_answer == "A" ? "checked" : "") . '>
                                <label class="opt-label m-0" for="' . $opt[0]["id"] . '">' . $opt[0]["title"] . '</label>
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <div class="option-container">
                                <input type="radio" class="radio-option" name="options" id="' . $opt[1]["id"] . '" data-questionid="' . $opt[0]["question_id"] . '" value="B" ' . ($user_answer == "B" ? "checked" : "") . '>
                                <label class="opt-label m-0" for="' . $opt[1]["id"] . '">' . $opt[1]["title"] . '</label>
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <div class="option-container">
                                <input type="radio" class="radio-option" name="options" id="' . $opt[2]["id"] . '" data-questionid="' . $opt[0]["question_id"] . '" value="C" ' . ($user_answer == "C" ? "checked" : "") . '>
                                <label class="opt-label m-0" for="' . $opt[2]["id"] . '">' . $opt[2]["title"] . '</label>
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <div class="option-container">
                                <input type="radio" class="radio-option" name="options" id="' . $opt[3]["id"] . '" data-questionid="' . $opt[0]["question_id"] . '" value="D" ' . ($user_answer == "D" ? "checked" : "") . '>
                                <label class="opt-label m-0" for="' . $opt[3]["id"] . '">' . $opt[3]["title"] . '</label>
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <div class="option-container">
                                <input type="radio" class="radio-option" name="options" id="' . $opt[4]["id"] . '" data-questionid="' . $opt[0]["question_id"] . '" value="E" ' . ($user_answer == "E" ? "checked" : "") . '>
                                <label class="opt-label m-0" for="' . $opt[4]["id"] . '">' . $opt[4]["title"] . '</label>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="option-container">
                                <input type="radio" class="radio-option" name="options" id="blank" data-questionid="' . $opt[0]["question_id"] . '" value="0" ' . ($user_answer == "0" ? "checked" : "") . '>
                                <label class="opt-label m-0" for="blank">[Jawaban kosong]</label>
                            </div>
                        </div>
                    </div>
                    <div class="row m-0 d-flex justify-content-between">
                        <div class="prev">
                            <button class="btn btn-secondary btn-prev" data-id="' . $prev_question_id . '" '  . $prev_btn_disable . '>
                                Kembali
                            </button>
                        </div>
                        <div class="next">
                            <button class="btn btn-dark btn-next" data-id="' . $next_question_id . '" ' . $next_btn_disable . '>
                                Lanjut
                            </button>
                        </div>
                    </div>
                    ';

            echo $output;
        }
    }

    public function question_nav()
    {
        if ($this->request->getVar("action") == "question_nav") {
            $exam = $this->examsModel
                ->where(['code' => $this->request->getVar("exam_code")])
                ->first();

            $questions = $this->questionsModel
                ->where(["exam_id" => $exam["id"]])
                ->orderBy("id", "ASC")
                ->findAll();

            $i = 1;
            $output = '';

            foreach ($questions as $question) {
                $output .= '
                    <div class="nav-box mx-1">
                        <button type="button" id="question-nav" class="btn btn-primary" data-id="' . $question["id"] . '">
                            ' . $i++ . ' 
                        </button>
                    </div>           
                ';
            }

            echo $output;
        }
    }

    public function user_answer()
    {
        if ($this->request->getVar("action") == "user_answer") {
            $exam = $this->examsModel
                ->where(['code' => $this->request->getVar("exam_code")])
                ->first();

            $question = $this->questionsModel->find($this->request->getVar("question_id"));

            $scores = 0;

            $user_question_answer = $this->request->getVar("answer_option");

            if ($user_question_answer == "0") {
                $scores += ($exam["score_per_empty_answer"] + 0);
            } else {
                if ($question["options"] == $user_question_answer) {
                    $scores += ($exam["score_per_right_answer"] + 0);
                } else {
                    $scores += ($exam["score_per_wrong_answer"] + 0);
                }
            }

            $data = [
                "user_answer_option" => $user_question_answer,
                "scores"             => $scores,
            ];

            $user_answer_row = $this->usersAnswerModel
                ->where([
                    "user_id"     => user()->id,
                    "exam_id"     => $exam["id"],
                    "question_id" => $question["id"],
                ])->first();

            $this->usersAnswerModel->update($user_answer_row["id"], $data);
        }
    }

    public function exam_result($user_id = 0)
    {
        if (
            !in_groups("user") ||
            !is_numeric($user_id) ||
            user()->id != $user_id
        ) return redirect()->back();

        if ($this->request->getGet("code")) {
            $exam = $this->examsModel
                ->where(["code" => $this->request->getGet("code")])
                ->first();

            if (!$exam) return redirect()->back();

            $is_enrolled = $this->usersEnrollModel
                ->where(["user_id" => user()->id, "exam_id" => $exam["id"]])
                ->first();

            if (!$is_enrolled || $exam["status"] != $this->examsModel->listStatus()[3]) {
                return redirect()->back();
            }

            $exam_table_fields = $this->db->getFieldNames("exam_table");

            $answers_info = [];

            for ($i = 6; $i < 9; $i++) {
                $query = $this->db->query(
                    'SELECT types,
                        COUNT(*)    AS total_question
                    FROM question_table
                            INNER JOIN user_exam_answer_table ueat on question_table.id = ueat.question_id
                    WHERE question_table.exam_id = ' . $exam["id"] .
                        ' AND ueat.user_id = ' . user()->id .
                        ' AND scores = ' . $exam[$exam_table_fields[$i]] .
                        ' GROUP BY types'
                );

                array_push($answers_info, $query->getResultArray());
            }

            $query = $this->db->query(
                'SELECT types,
                    COUNT(*)    AS total_question,
                    SUM(scores) AS scores
                FROM question_table
                        INNER JOIN user_exam_answer_table ueat ON question_table.id = ueat.question_id
                WHERE question_table.exam_id = ' . $exam["id"] .
                    ' AND ueat.user_id = ' . user()->id .
                    ' GROUP BY types'
            );

            $exam_result = $query->getResultArray();

            $data = [
                'title'       => 'Exam Result',
                'exam'        => $exam,
                'exam_result' => $exam_result,
                'answer_info' => $answers_info,
                'attendance'  => $is_enrolled['attendance_status'],

            ];

            return view('user/exam_result', $data);
        }

        return redirect()->back();
    }

    public function download_answer_topic()
    {
        $exam_code = $this->request->getVar('exam_code');

        if ($exam_code) {
            $exam = $this->examsModel->where(['code' => $exam_code])->first();

            if (!in_groups('user') || !$exam) return redirect()->back();

            if (!$exam['answer_topic']) return 'Belum ada pembahasan soal untuk exam ini.';

            $title = str_replace(' ', '_', $exam['title']);

            $title .= '.' . explode('.', $exam['answer_topic'])[1];

            return $this->response
                ->download('docs/' . $exam['answer_topic'], null)
                ->setFileName('kunci_jawaban_' . $title);
        }

        return redirect()->back();
    }
}
