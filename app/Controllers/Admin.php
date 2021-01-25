<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Models\ExamsModel;
use App\Models\QuestionsModel;
use App\Models\QuestionsExamModel;
use App\Models\OptionsModel;
use monken\TablesIgniter;

class Admin extends BaseController
{
    protected $db;
    protected $usersModel;
    protected $examsModel;
    protected $questionsModel;
    protected $questionsExamModel;
    protected $optionsModel;
    protected $data_table;

    // constructor for connect to database
    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->usersModel = new UsersModel();
        $this->examsModel = new ExamsModel();
        $this->questionsModel = new QuestionsModel();
        $this->questionsExamModel = new QuestionsExamModel();
        $this->optionsModel = new OptionsModel();
        $this->data_table = new TablesIgniter();
    }

    // function to fetch all users from database
    public function fetch_all_users()
    {
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

    // function to fetch single user from database
    public function fetch_single_user()
    {
        if ($this->request->getVar('id')) {
            $user_data = $this->usersModel->where(['id' => $this->request->getVar('id')])->first();

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
        if (!in_groups('admin') || !is_numeric($id)) {
            return redirect()->back();
        }

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

    // function to delete user
    public function delete()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

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

    // function to update user
    public function update()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

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

            $user_data = $this->usersModel->where(['id' => $this->request->getVar("hidden-id")])->first();

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

    // -------------------------------------------------------------

    // function to fetch all exam from database
    public function fetch_all_exams()
    {
        $this->data_table->setTable($this->examsModel->noticeTable())
            ->setDefaultOrder('id', 'DESC')
            ->setSearch(['title', 'implement_date', 'duration', 'total_question', 'status'])
            ->setOrder(['id', 'title', 'implement_date', 'duration', 'total_question', 'status'])
            ->setOutput(
                [
                    $this->examsModel->rowResult('id'),
                    $this->examsModel->rowResult('title'),
                    $this->examsModel->rowResult('implement_date'),
                    $this->examsModel->rowResult('status'),
                    $this->examsModel->questionButton(),
                    $this->examsModel->actionButton(),
                ]
            );

        return $this->data_table->getDatatable();
    }

    // function to fetch single exam from database
    public function fetch_single_exam()
    {
        if ($this->request->getVar('id')) {
            $exam_data = $this->examsModel->where(['id' => $this->request->getVar('id')])->first();

            echo json_encode($exam_data);
        }
    }

    // function to return exam view (table exam)
    public function exam()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

        $data['title'] = 'Manage Exams';

        return view('admin/exam', $data);
    }

    // function for seeing exam detail
    public function exam_detail($id = 0)
    {
        if (!in_groups('admin') || !is_numeric($id)) {
            return redirect()->back();
        }

        $exam = $this->examsModel->find($id);
        $exam_status = $this->examsModel->listStatus();

        $data = [
            'title' => 'Exam Detail',
            'exam' => $exam,
            'exam_status' => $exam_status,
        ];

        return view('admin/exam_detail', $data);
    }

    // function to delete exam
    public function exam_delete()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

        if ($this->request->getVar('id')) {
            // delete exam data
            $this->examsModel->delete($this->request->getVar('id'));
            // drop exam view
            $this->db->query('DROP VIEW questions_for_exam_' . $this->request->getVar('id'));
            // set flash message
            echo 'Exam data has been deleted.';
        }
    }

    // function for create/update exam
    public function exam_action()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

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
                $exam_data = $this->examsModel->where(['id' => $this->request->getVar("exam-id")])->first();
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
                        'CREATE VIEW questions_for_exam_' . $this->examsModel->getInsertID() . '
                        AS SELECT * FROM question_table
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

    // -------------------------------------------------------------

    // function to send current exam from server to admin
    public function get_current_exam()
    {
        if ($this->request->getVar('code')) {
            $exam = $this->examsModel->where(['code' => $this->request->getVar('code')])->first();

            echo json_encode($exam);
        }
    }

    // function to fetch all question according to exam id from database
    public function fetch_all_questions()
    {
        if ($this->request->getVar('id')) {
            $exam_id = $this->request->getVar('id');
        }

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

    public function fetch_single_question()
    {
        if ($this->request->getVar('id')) {
            $question_data = $this->questionsModel
                ->where(['id' => $this->request->getVar('id')])
                ->first();

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

            $options = $this->questionsModel->listOption();

            foreach ($options as $option) {
                $output['option' . $option] = $option_data[$i++]['title'];
            }

            echo json_encode($output);
        }
    }


    // function to return question view (table question)
    public function question()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

        $data['title'] = 'Manage Questions';

        return view('admin/question', $data);
    }

    // function to check if exam is full or not
    public function is_allowed_add_question()
    {
        if ($this->request->getVar('id')) {
            $exam_id = $this->request->getVar('id');
        }

        $current_question = $this->questionsModel->where(['exam_id' => $exam_id])->countAllResults();

        $total_question = $this->examsModel->find($exam_id)["total_question"];

        if ($current_question < $total_question) {
            // return true
            echo json_encode(true);
        } else {
            // return false
            echo json_encode(false);
        }
    }

    // function to delete question
    public function question_delete()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

        if ($this->request->getVar('id')) {
            // get question id
            $question_id = $this->request->getVar('id');
            // get list option at question_table
            $options = $this->questionsModel->listOption();
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
            // delete question data
            $this->questionsModel->delete($question_id);
            // set flash message
            echo 'Question data has been deleted.';
        }
    }

    // function for create/update question
    public function question_action()
    {
        if (!in_groups('admin')) {
            return redirect()->back();
        }

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
                $options = $this->questionsModel->listOption();

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
}
