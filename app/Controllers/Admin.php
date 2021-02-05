<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\UsersEnrollModel;
use App\Models\UsersAnswerModel;
use App\Models\UsersResultModel;
use App\Models\UsersEnrollExamModel;
use App\Models\ExamsModel;
use App\Models\QuestionsModel;
use App\Models\QuestionsExamModel;
use App\Models\OptionsModel;
use App\Models\ResultsExamModel;
use monken\TablesIgniter;

class Admin extends BaseController
{
    protected $db;
    protected $usersModel;
    protected $usersEnrollModel;
    protected $usersAnswerModel;
    protected $usersResultModel;
    protected $usersEnrollExamModel;
    protected $examsModel;
    protected $questionsModel;
    protected $questionsExamModel;
    protected $optionsModel;
    protected $resultsExamModel;
    protected $data_table;

    // constructor for connect to database
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->usersModel = new UsersModel();
        $this->usersEnrollModel = new UsersEnrollModel();
        $this->usersAnswerModel = new UsersAnswerModel();
        $this->usersResultModel = new UsersResultModel();
        $this->usersEnrollExamModel = new UsersEnrollExamModel();
        $this->examsModel = new ExamsModel();
        $this->questionsModel = new QuestionsModel();
        $this->questionsExamModel = new QuestionsExamModel();
        $this->optionsModel = new OptionsModel();
        $this->resultsExamModel = new ResultsExamModel();
        $this->data_table = new TablesIgniter();
    }

    // function to fetch all users
    public function fetch_all_users()
    {
        if (!in_groups('admin')) return redirect()->back();

        $this->data_table->setTable($this->usersModel->noticeTable())
            ->setDefaultOrder('id', 'DESC')
            ->setSearch(['fullname', 'username', 'email'])
            ->setOrder(['id', 'fullname', 'username', 'email'])
            ->setOutput(
                [
                    'id',
                    'fullname',
                    'username',
                    'email',
                    $this->usersModel->actionButton(),
                ]
            );

        return $this->data_table->getDatatable();
    }

    // function to fetch single user
    public function fetch_single_user()
    {
        if ($this->request->getVar('id')) {
            $user_data = $this->usersModel->find($this->request->getVar('id'));

            echo json_encode($user_data);
        }
    }

    // function to return index view (table user)
    public function index()
    {
        $data['title'] = 'Manage Users';

        return view('admin/index', $data);
    }

    // function for seeing user detail
    public function detail($id = 0)
    {
        if (!in_groups('admin') || !is_numeric($id)) return redirect()->back();

        $query = $this->db->query(
            'SELECT email,
                    username,
                    fullname,
                    phone_number,
                    address,
                    gender,
                    profile_pict,
                    name AS role
            FROM users
                INNER JOIN auth_groups_users ON users.id = auth_groups_users.user_id
                INNER JOIN auth_groups ON auth_groups_users.group_id = auth_groups.id
            WHERE users.id =' . $id
        );

        $result = $query->getRow();

        $data = [
            'title' => 'User Profile Detail',
            'user'  => $result,
        ];

        return empty($data['user']) ? redirect()->to('/admin') : view('admin/detail', $data);
    }

    // function to update user
    public function update()
    {
        if (!in_groups('admin')) return redirect()->back();

        if ($this->request->getVar('edit')) {
            helper(['form', 'url']);
            $invalidUsername = '';
            $invalidEmail = '';
            $invalidFullname = '';
            $invalidPhonenumber = '';
            $invalidGender = '';
            $invalidAddress = '';
            $message = '';
            $success = 'no';
            $error = 'no';

            $user_data = $this->usersModel->find($this->request->getVar("hidden-id"));

            // cek username user
            if ($user_data["username"] == $this->request->getVar("username")) {
                $username_rules = 'required|alpha_numeric|min_length[3]|max_length[30]';
            } else {
                $username_rules = 'required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[30]';
            }

            // cek email user
            if ($user_data["email"] == $this->request->getVar("email")) {
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
            ])) {
                $error = 'yes';

                $validation = \Config\Services::validation();

                $invalidUsername = $validation->getError('username') ? $validation->getError('username') : '';
                $invalidEmail = $validation->getError('email') ? $validation->getError('email') : '';
                $invalidFullname = $validation->getError('fullname') ? $validation->getError('fullname') : '';
                $invalidPhonenumber = $validation->getError('phone_number') ? $validation->getError('phone_number') : '';
                $invalidGender = $validation->getError('gender') ? $validation->getError('gender') : '';
                $invalidAddress = $validation->getError('address') ? $validation->getError('address') : '';
            } else {
                $success = 'yes';

                $id = $this->request->getVar("hidden-id");

                $data = [
                    'username'     => $this->request->getVar('username'),
                    'email'        => $this->request->getVar('email'),
                    'fullname'     => $this->request->getVar('fullname'),
                    'phone_number' => $this->request->getVar('phone_number'),
                    'gender'       => $this->request->getVar('gender'),
                    'address'      => $this->request->getVar('address'),
                ];

                $this->usersModel->update($id, $data);

                $message = 'User data has been edited.';
            }

            $output = [
                'invalidUsername'    => $invalidUsername,
                'invalidEmail'       => $invalidEmail,
                'invalidFullname'    => $invalidFullname,
                'invalidPhonenumber' => $invalidPhonenumber,
                'invalidGender'      => $invalidGender,
                'invalidAddress'     => $invalidAddress,
                'error'              => $error,
                'success'            => $success,
                'message'            => $message,
            ];

            echo json_encode($output);
        }
    }
    // function to delete user
    public function delete()
    {
        if (!in_groups('admin')) return redirect()->back();

        if ($this->request->getVar('id')) {
            // user id
            $id = $this->request->getVar('id');
            // find user by id
            $user = $this->usersModel->find($id);
            // delete user profile pict, if
            if ($user['profile_pict'] != 'default.png') {
                unlink('img/profile/' . $user['profile_pict']);
            }
            // delete user data
            $this->usersModel->delete($id);
            // set flash message
            echo 'User data has been deleted.';
        }
    }

    // -------------------------------------------------------------

    // function to fetch all exam
    public function fetch_all_exams()
    {
        if (!in_groups('admin')) return redirect()->back();

        $this->data_table->setTable($this->examsModel->noticeTable())
            ->setDefaultOrder('id', 'DESC')
            ->setSearch(['title', 'implement_date', 'status'])
            ->setOrder(['id', 'title', 'implement_date', 'status'])
            ->setOutput(
                [
                    $this->examsModel->rowResult('id'),
                    $this->examsModel->rowResult('title'),
                    $this->examsModel->rowResult('implement_date'),
                    $this->examsModel->rowResult('status'),
                    $this->examsModel->userEnrolledButton(),
                    $this->examsModel->usersScoreButton(),
                    $this->examsModel->questionButton(),
                    $this->examsModel->rowResult('answer_topic'),
                    $this->examsModel->actionButton(),
                ]
            );

        return $this->data_table->getDatatable();
    }

    // function to fetch single exam find by id
    public function fetch_single_exam()
    {
        if ($this->request->getVar('id')) {
            $exam_data = $this->examsModel->find($this->request->getVar('id'));

            echo json_encode($exam_data);
        }
    }

    // function to fetch single exam find by code
    public function get_current_exam()
    {
        if ($this->request->getVar('code')) {
            $exam_data = $this->examsModel
                ->where(['code' => $this->request->getVar('code')])
                ->first();

            $user_id = $this->request->getVar('user');

            if ($user_id) {
                $exam_data["user_result"] = $this->usersModel->find($user_id)["fullname"];
            }

            echo json_encode($exam_data);
        }
    }

    // function to return exam view (table exam)
    public function exam()
    {
        $data['title'] = 'Manage Exams';

        return view('admin/exam', $data);
    }

    // function for seeing exam detail
    public function exam_detail($id = 0)
    {
        if (!in_groups('admin') || !is_numeric($id)) return redirect()->back();

        $exam = $this->examsModel->find($id);
        $exam_status = $this->examsModel->listStatus();

        $data = [
            'title' => 'Exam Detail',
            'exam' => $exam,
            'exam_status' => $exam_status,
        ];

        return view('admin/exam_detail', $data);
    }

    // function for create/update exam
    public function exam_action()
    {
        if (!in_groups('admin')) return redirect()->back();

        if ($this->request->getVar('action')) {
            helper(['form', 'url']);
            $invalidTitle = '';
            $invalidSchedule = '';
            $message = '';
            $success = 'no';
            $error = 'no';
            $title_rules = 'required|is_unique[exam_table.title]|min_length[3]|max_length[255]';
            $schedule_rules = 'required|is_unique[exam_table.implement_date]';

            if ($this->request->getVar("exam-id")) {
                // this is current exam edited
                $exam_data = $this->examsModel->find($this->request->getVar("exam-id"));
                // cek title exam
                if ($exam_data["title"] == $this->request->getVar("title")) {
                    $title_rules = 'required|min_length[3]|max_length[255]';
                }
                // cek schedule exam
                if ($exam_data["implement_date"] == $this->request->getVar("schedule")) {
                    $schedule_rules = 'required|min_length[3]|max_length[255]';
                }
            }

            if (!$this->validate([
                'title'        => $title_rules,
                'schedule'     => $schedule_rules,
                'duration'     => 'required',
                'question'     => 'required',
                'right-answer' => 'required',
                'wrong-answer' => 'required',
                'empty-answer' => 'required',
            ])) {
                $error = 'yes';

                $validation = \Config\Services::validation();

                $invalidTitle = $validation->getError('title') ? $validation->getError('title') : '';
                $invalidSchedule = $validation->getError('schedule') ? $validation->getError('schedule') : '';
            } else {
                $success = 'yes';

                if ($this->request->getVar('action') == 'create') {
                    // create exam
                    $this->examsModel->save([
                        'title'                  => $this->request->getVar('title'),
                        'implement_date'         => $this->request->getVar('schedule'),
                        'duration'               => $this->request->getVar('duration'),
                        'total_question'         => $this->request->getVar('question'),
                        'score_per_right_answer' => $this->request->getVar('right-answer'),
                        'score_per_wrong_answer' => $this->request->getVar('wrong-answer'),
                        'score_per_empty_answer' => $this->request->getVar('empty-answer'),
                        'status'                 => $this->examsModel->listStatus()[0],
                        'user_id'                => user_id(),
                        'code'                   => md5(rand()),
                    ]);

                    // create view for this exam
                    $this->db->query(
                        'CREATE VIEW questions_for_exam_' . $this->examsModel->getInsertID() .
                            ' AS SELECT * FROM question_table
                        WHERE exam_id = ' . $this->examsModel->getInsertID()
                    );

                    // send message alert
                    $message = 'Examination has been created.';
                } else if ($this->request->getVar('action') == 'edit') {
                    // get exam id 
                    $id = $this->request->getVar("exam-id");

                    // get value updated exam data
                    $data = [
                        'title'                  => $this->request->getVar('title'),
                        'implement_date'         => $this->request->getVar('schedule'),
                        'duration'               => $this->request->getVar('duration'),
                        'total_question'         => $this->request->getVar('question'),
                        'score_per_right_answer' => $this->request->getVar('right-answer'),
                        'score_per_wrong_answer' => $this->request->getVar('wrong-answer'),
                        'score_per_empty_answer' => $this->request->getVar('empty-answer'),
                    ];

                    // update exam data
                    $this->examsModel->update($id, $data);

                    // send message alert
                    $message = 'Examination data has been edited.';
                }
            }

            $output = [
                'invalidTitle'    => $invalidTitle,
                'invalidSchedule' => $invalidSchedule,
                'error'           => $error,
                'success'         => $success,
                'message'         => $message,
            ];

            echo json_encode($output);
        }
    }

    // function to delete exam
    public function exam_delete()
    {
        if (!in_groups('admin')) return redirect()->back();

        $exam_id = $this->request->getVar('id');

        if ($exam_id) {
            // get exam by id
            $exam = $this->examsModel->find($exam_id);

            // delete pdf exam answer topic file
            if ($exam["answer_topic"]) unlink('docs/' . $exam["answer_topic"]);

            // get all questions from this exam
            $questions = $this->questionsModel->where(["exam_id" => $exam_id])->findAll();

            // delete files image question
            foreach ($questions as $question) {
                if ($question["image"]) {
                    unlink('img/exam/' . $question["image"]);
                }
            }

            // delete exam data
            $this->examsModel->delete($exam_id);

            // drop exam questions view
            $this->db->query('DROP VIEW questions_for_exam_' . $exam_id);

            // drop users enrolled exam view
            $this->db->query('DROP VIEW users_for_exam_' . $exam_id);

            // drop exam result view
            $this->db->query('DROP VIEW exam_result_' . $exam_id);

            // find all user
            $query = $this->db->query(
                'SELECT id
                FROM exam_system.users
                        INNER JOIN auth_groups_users agu on users.id = agu.user_id
                WHERE agu.group_id = 2'
            );

            // get result from query
            $users = $query->getResultArray();

            // delete all user exam result view
            foreach ($users as $user) {
                $this->db->query(
                    'DROP VIEW IF EXISTS result_exam_user_' . $exam_id  . '_' . $user['id']
                );
            }

            // set flash message
            echo 'Exam data has been deleted.';
        }
    }

    // function to upload answer topic
    public function upload_answer_topic($exam_id = 0)
    {
        if (!in_groups('admin')) return redirect()->back();

        if (!$this->validate([
            'answer_topic' => 'uploaded[answer_topic]|max_size[answer_topic,5048]|ext_in[answer_topic,pdf,doc,docx]'
        ])) {
            return redirect()->back()->with("error", "Unknown error, please try again.");
        }

        // uploaded file
        $file_pdf = $this->request->getFile("answer_topic");
        // generate a random name
        $file_name = $file_pdf->getRandomName();
        // move file to img folder
        $file_pdf->move('docs', $file_name);

        $this->examsModel->save([
            'id'           => $exam_id,
            'answer_topic' => $file_name,
        ]);

        return redirect()->back()->with("message", "File has been uploaded.");
    }

    // function to download answer topic
    public function download_answer_topic()
    {
        $exam = $this->examsModel->find($this->request->getVar('id'));

        if (!in_groups('admin') || !$exam['answer_topic']) return redirect()->to('/exam');

        $title = str_replace(' ', '_', $exam['title']);

        $title .= '.' . explode('.', $exam['answer_topic'])[1];

        return $this->response
            ->download('docs/' . $exam['answer_topic'], null)
            ->setFileName('kunci_jawaban_' . $title);
    }

    // function to delete answer topic
    public function delete_answer_topic()
    {
        if (!in_groups('admin')) return redirect()->back();

        $exam = $this->examsModel->find($this->request->getVar('id'));

        $this->examsModel->save([
            'id'           => $exam['id'],
            'answer_topic' => null,
        ]);

        unlink('docs/' . $exam['answer_topic']);

        echo "File has been deleted.";
    }

    // function to lock exam
    public function lock_exam()
    {
        if ($this->request->getVar("action") == "lock_exam") {
            $exam = $this->request->getVar("exam");

            $data["status"] = $this->examsModel->listStatus()[1];

            if ($exam["status"] == $this->examsModel->listStatus()[0]) {
                $this->examsModel->update($exam["id"], $data);

                // create users enrolled exam view
                $this->db->query(
                    'CREATE VIEW users_for_exam_' . $exam['id'] . ' AS
                    SELECT ueet.id AS id,
                        profile_pict,
                        fullname,
                        email,
                        phone_number,
                        attendance_status,
                        ueet.user_id,
                        exam_id,
                        code AS exam_code
                    FROM users
                            INNER JOIN user_exam_enroll_table ueet on users.id = ueet.user_id
                            INNER JOIN exam_table et on ueet.exam_id = et.id
                    WHERE exam_id = ' . $exam['id']
                );

                // create exam result for all users
                $this->db->query(
                    'CREATE VIEW exam_result_' . $exam['id'] . ' AS
                    SELECT user_exam_answer_table.user_id AS id,
                        fullname,
                        email,
                        gender,
                        phone_number,
                        profile_pict,
                        SUM(scores) AS scores
                    FROM user_exam_answer_table
                            INNER JOIN users u on user_exam_answer_table.user_id = u.id
                    WHERE exam_id = ' . $exam['id'] .
                        ' GROUP BY user_id'
                );

                echo json_encode(true);
                return 1;
            }

            echo json_encode(false);
            return 0;
        }
    }

    // -------------------------------------------------------------

    // function to fetch all question according to exam id
    public function fetch_all_questions()
    {
        $exam_id = $this->request->getVar('id');

        if ($exam_id) {
            $this->data_table->setTable($this->questionsExamModel->noticeTable($exam_id))
                ->setDefaultOrder('id', 'DESC')
                ->setSearch(['title', 'options', 'types'])
                ->setOrder(['id', 'title', 'options', 'types'])
                ->setOutput([
                    $this->questionsExamModel->rowResult('id'),
                    $this->questionsExamModel->rowResult('title'),
                    $this->questionsExamModel->rowResult('options'),
                    $this->questionsExamModel->rowResult('types'),
                    $this->questionsExamModel->rowResult('image'),
                    $this->questionsExamModel->actionButton(),
                ]);

            return $this->data_table->getDatatable();
        }
    }

    // function to fetch single question
    public function fetch_single_question()
    {
        if ($this->request->getVar('id')) {
            $question_data = $this->questionsModel->find($this->request->getVar('id'));

            $option_data = $this->optionsModel
                ->where(['question_id' => $this->request->getVar('id')])
                ->orderBy('id', 'ASC')
                ->findAll();

            $output = [
                'questionTitle' => $question_data['title'],
                'answer'        => strtolower($question_data['options']),
                'type'          => $question_data['types']
            ];

            $i = 0;

            $options = $this->questionsModel->listQuestionEnum('options');

            foreach ($options as $option) {
                $output['option' . $option] = $option_data[$i++]['title'];
            }

            echo json_encode($output);
        }
    }

    // function to return question view (table question)
    public function question()
    {
        if ($this->request->getGet('code')) {
            $disable_btn = null;

            $exam = $this->examsModel
                ->where(['code' => $this->request->getGet('code')])
                ->first();

            if ($exam['status'] == $this->examsModel->listStatus()[1]) {
                $disable_btn = 'disabled';
            }

            $data = [
                'title'       => 'Manage Questions',
                'disable_btn' => $disable_btn,
            ];

            return view('admin/question', $data);
        }
    }

    // function to check if exam is full or not
    public function is_allowed_add_question()
    {
        if ($this->request->getVar('id')) {
            $exam_id = $this->request->getVar('id');

            $exam = $this->examsModel->find($exam_id);

            $current_question = $this->questionsModel
                ->where(['exam_id' => $exam_id])
                ->countAllResults();

            if (
                $current_question < $exam["total_question"] &&
                !$this->examsModel->isExamStarted($exam_id)
            ) {
                // return true
                echo json_encode(true);
            } else {
                // change exam status first
                $this->examsModel->changeExamStatus($exam);
                // return false
                echo json_encode(false);
            }
        }
    }

    // function to create/update question
    public function question_action()
    {
        if (!in_groups('admin')) return redirect()->back();

        if ($this->request->getVar('action')) {
            helper(['form', 'url']);
            $invalidQuestion = '';
            $invalidOptionA = '';
            $invalidOptionB = '';
            $invalidOptionC = '';
            $invalidOptionD = '';
            $invalidOptionE = '';
            $invalidAnswer = '';
            $invalidType = '';
            $message = '';
            $success = 'no';
            $error = 'no';

            if (!$this->validate([
                'question-title' => 'required|max_length[20000]',
                'option-a'       => 'required|max_length[255]',
                'option-b'       => 'required|max_length[255]',
                'option-c'       => 'required|max_length[255]',
                'option-d'       => 'required|max_length[255]',
                'option-e'       => 'required|max_length[255]',
                'answer'         => 'required',
                'type'           => 'required',
            ])) {
                $error = 'yes';

                $validation = \Config\Services::validation();

                $invalidQuestion = $validation->getError('question-title') ? $validation->getError('question-title') : '';
                $invalidOptionA = $validation->getError('option-a') ? $validation->getError('option-a') : '';
                $invalidOptionB = $validation->getError('option-b') ? $validation->getError('option-b') : '';
                $invalidOptionC = $validation->getError('option-c') ? $validation->getError('option-c') : '';
                $invalidOptionD = $validation->getError('option-d') ? $validation->getError('option-d') : '';
                $invalidOptionE = $validation->getError('option-e') ? $validation->getError('option-e') : '';
                $invalidAnswer = $validation->getError('answer') ? $validation->getError('answer') : '';
                $invalidType = $validation->getError('type') ? $validation->getError('type') : '';
            } else {
                $success = 'yes';

                // get list option at question_table
                $options = $this->questionsModel->listQuestionEnum('options');

                if ($this->request->getVar('action') == 'create') {
                    // insert question into question_table
                    $this->questionsModel->save([
                        'title'   => $this->request->getVar('question-title'),
                        'options' => $this->request->getVar('answer'),
                        'types'   => $this->request->getVar('type'),
                        'exam_id' => $this->request->getVar('exam-id'),
                    ]);

                    // get last ID of question inserted row
                    $last_question_id = $this->questionsModel->getInsertID();

                    // insert each options to option_table
                    foreach ($options as $option_insert) {
                        // insert option data
                        $this->optionsModel->save([
                            'question_id' => $last_question_id,
                            'option_char' => $option_insert,
                            'title'       => $this->request->getVar('option-' . strtolower($option_insert))
                        ]);
                    }

                    // send message alert
                    $message = 'Question has been created.';
                } else if ($this->request->getVar('action') == 'edit') {
                    // get question id 
                    $question_id = $this->request->getVar('question-id');

                    // get value updated question data
                    $data_question = [
                        'title'   => $this->request->getVar('question-title'),
                        'options' => $this->request->getVar('answer'),
                        'types'   => $this->request->getVar('type'),
                    ];

                    // update question data
                    $this->questionsModel->update($question_id, $data_question);

                    foreach ($options as $option_update) {
                        // get option id
                        $option_id = $this->optionsModel->where([
                            'question_id' => $question_id,
                            'option_char' => $option_update,
                        ])->first()['id'];

                        // get value updated option data
                        $data_option['title'] = $this->request->getVar('option-' . strtolower($option_update));

                        // update option data
                        $this->optionsModel->update($option_id, $data_option);
                    }

                    // send message alert
                    $message = 'Question data has been edited.';
                }
            }

            $output = [
                'invalidQuestion' => $invalidQuestion,
                'invalidOptionA'  => $invalidOptionA,
                'invalidOptionB'  => $invalidOptionB,
                'invalidOptionC'  => $invalidOptionC,
                'invalidOptionD'  => $invalidOptionD,
                'invalidOptionE'  => $invalidOptionE,
                'invalidAnswer'   => $invalidAnswer,
                'invalidType'     => $invalidType,
                'error'           => $error,
                'success'         => $success,
                'message'         => $message,
            ];

            echo json_encode($output);
        }
    }

    // function to delete question
    public function question_delete()
    {
        if (!in_groups('admin')) return redirect()->back();

        if ($this->request->getVar('id')) {
            // get question id
            $question_id = $this->request->getVar('id');
            // get list option at question_table
            $options = $this->questionsModel->listQuestionEnum('options');
            // delete each options data at option_table
            foreach ($options as $option_delete) {
                // get option id 
                $option_id = $this->optionsModel->where([
                    'question_id' => $question_id,
                    'option_char' => $option_delete,
                ])->first()['id'];
                // delete option data
                $this->optionsModel->delete($option_id);
            }
            // find question image
            $image = $this->questionsModel->find($question_id)["image"];
            // delete question image
            if ($image) {
                unlink('img/exam/' . $image);
            }
            // delete question data
            $this->questionsModel->delete($question_id);
            // set flash message
            echo 'Question data has been deleted.';
        }
    }

    // function to upload question image
    public function upload_question_image($question_id = 0)
    {
        if (!in_groups('admin')) return redirect()->back();

        $question = $this->questionsModel->find($question_id);

        if ($this->examsModel->isExamStarted($question["exam_id"])) {
            return redirect()->back()->with("error", "The exam of this question has been completed or is on progress.");
        }

        if (!$this->validate([
            'question_image' => 'uploaded[question_image]|max_size[question_image,2048]|is_image[question_image]|mime_in[question_image,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->back()->with("error", "Unknown error, please try again.");
        }

        // uploaded file
        $file_img = $this->request->getFile("question_image");
        // generate a random name
        $file_name = $file_img->getRandomName();
        // move file to img folder
        $file_img->move('img/exam', $file_name);

        $this->questionsModel->save([
            'id'    => $question_id,
            'image' => $file_name,
        ]);

        return redirect()->back()->with("message", "File image has been uploaded.");
    }

    // function to delete question image
    public function delete_question_image()
    {
        if (!in_groups('admin')) return redirect()->back();

        $question = $this->questionsModel->find($this->request->getVar('id'));

        $this->questionsModel->save([
            'id'    => $question['id'],
            'image' => null,
        ]);

        unlink('img/exam/' . $question['image']);

        echo "File image has been deleted.";
    }

    // -------------------------------------------------------------

    // function to fetch all users that has been enrolled existing exam
    public function fetch_all_users_enroll()
    {
        $exam_id = $this->request->getVar('id');

        if ($exam_id) {
            $this->data_table->setTable($this->usersEnrollExamModel->noticeTable($exam_id))
                ->setDefaultOrder('id', 'ASC')
                ->setSearch(['fullname', 'email', 'phone_number', 'attendance_status'])
                ->setOrder([
                    'id',
                    'profile_pict',
                    'fullname',
                    'email',
                    'phone_number',
                    'attendance_status'
                ])
                ->setOutput([
                    $this->usersEnrollExamModel->rowResult('id'),
                    $this->usersEnrollExamModel->rowResult('profile_pict'),
                    $this->usersEnrollExamModel->rowResult('fullname'),
                    $this->usersEnrollExamModel->rowResult('email'),
                    $this->usersEnrollExamModel->rowResult('phone_number'),
                    $this->usersEnrollExamModel->rowResult('attendance_status'),
                    $this->usersEnrollExamModel->userExamResultButton(),
                    $this->usersEnrollExamModel->actionButton(),
                ]);

            return $this->data_table->getDatatable();
        }
    }

    // function to return user enrolled view (table user enrolled)
    public function user_enroll()
    {
        if ($this->request->getGet('code')) {
            $exam = $this->examsModel
                ->where(['code' => $this->request->getGet('code')])
                ->first();

            if (!$exam) return redirect()->back();

            if ($exam['status'] == $this->examsModel->listStatus()[0]) {
                return redirect()
                    ->back()
                    ->with('warning', 'Anda harus mengunci exam terlebih dahulu.');
            }

            $data['title'] = 'User Enroll List';

            return view('admin/user_enroll', $data);
        }
    }

    // function to delete user from enrolled exam
    public function user_enroll_delete()
    {
        if (!in_groups('admin')) return redirect()->back();

        if ($this->request->getVar('action') == 'cancel_exam') {
            $exam_id = $this->request->getVar('exam_id');
            $user_id = $this->request->getVar('user_id');

            $this->usersEnrollModel->delete($this->request->getVar('enroll_id'));

            $user_exam_answers = $this->usersAnswerModel
                ->where([
                    'exam_id' => $exam_id,
                    'user_id' => $user_id,
                ])->findAll();

            foreach ($user_exam_answers as $answer) {
                $this->usersAnswerModel->delete($answer['id']);
            }

            // drop user exam result
            $this->db->query(
                'DROP VIEW IF EXISTS result_exam_user_' . $exam_id  . '_' . $user_id
            );

            echo 'User berhasil dikeluarkan.';
        }
    }

    // -------------------------------------------------------------

    // function to fetch an user exam result from specific exam
    public function fetch_user_exam_result()
    {
        $exam_id = $this->request->getVar('exam_id');
        $user_id = $this->request->getVar('user_id');

        if ($exam_id && $user_id) {
            $this->data_table
                ->setTable($this->usersResultModel->noticeTable($exam_id, $user_id))
                ->setDefaultOrder('id', 'ASC')
                ->setSearch(['title', 'types', 'user_answer_option', 'options', 'scores'])
                ->setOrder([
                    'image',
                    'title',
                    'types',
                    'user_answer_option',
                    'options',
                    '',
                    'scores'
                ])
                ->setOutput([
                    $this->usersResultModel->rowResult('image'),
                    $this->usersResultModel->rowResult('title'),
                    $this->usersResultModel->rowResult('types'),
                    $this->usersResultModel->rowResult('user_answer_option'),
                    $this->usersResultModel->rowResult('options'),
                    $this->usersResultModel->resultStatus(),
                    $this->usersResultModel->rowResult('scores'),
                ]);

            return $this->data_table->getDatatable();
        }
    }

    // function to return user exam result view (table user exam result)
    public function user_exam_result()
    {
        $exam_code = $this->request->getGet('code');
        $user_id = $this->request->getGet('user');

        if ($exam_code && $user_id) {
            $exam = $this->examsModel
                ->where(['code' => $exam_code])
                ->first();

            if (!$exam) return redirect()->back();

            $is_enrolled = $this->usersEnrollModel
                ->where([
                    'user_id' => $user_id,
                    'exam_id' => $exam['id'],
                ])->first();

            if (!$is_enrolled) return redirect()->back();

            if ($exam['status'] != $this->examsModel->listStatus()[3]) {
                return redirect()
                    ->back()
                    ->with('warning', 'Tidak dapat melihat hasil. Exam belum selesai.');
            }

            $data['title'] = 'User Exam Result';

            return view('admin/user_exam_result', $data);
        }
    }

    // -------------------------------------------------------------

    // function to fetch all users exam result from specific exam
    public function fetch_admin_exam_result()
    {
        $exam_id = $this->request->getVar('exam_id');

        if ($exam_id) {
            $this->data_table->setTable($this->resultsExamModel->noticeTable($exam_id))
                ->setDefaultOrder('id', 'ASC')
                ->setSearch(['fullname', 'email', 'gender', 'phone_number', 'scores'])
                ->setOrder([
                    'id',
                    'profile_pict',
                    'fullname',
                    'email',
                    'phone_number',
                    'gender',
                    'scores'
                ])
                ->setOutput([
                    $this->resultsExamModel->rowResult('id'),
                    $this->resultsExamModel->rowResult('profile_pict'),
                    $this->resultsExamModel->rowResult('fullname'),
                    $this->resultsExamModel->rowResult('email'),
                    $this->resultsExamModel->rowResult('phone_number'),
                    $this->resultsExamModel->rowResult('gender'),
                    $this->resultsExamModel->rowResult('scores'),
                ]);

            return $this->data_table->getDatatable();
        }
    }

    // function to return exam result view (table exam result)
    public function admin_exam_result()
    {
        if ($this->request->getGet('code')) {
            $exam = $this->examsModel
                ->where(['code' => $this->request->getGet('code')])
                ->first();

            if (!$exam) return redirect()->back();

            if ($exam['status'] != $this->examsModel->listStatus()[3]) {
                return redirect()
                    ->back()
                    ->with('uncompleted', 'Tidak dapat melihat score. Exam belum selesai.');
            }

            $data['title'] = 'Exam Result';

            return view('admin/admin_exam_result', $data);
        }
    }
}
