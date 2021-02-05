<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\QuestionsModel;

class ExamsModel extends Model
{
    protected $table = 'exam_table';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $questionsModel;
    protected $allowedFields = [
        'title',
        'implement_date',
        'duration',
        'total_question',
        'score_per_right_answer',
        'score_per_wrong_answer',
        'score_per_empty_answer',
        'user_id',
        'status',
        'answer_topic',
        'code',
    ];

    // function to create table
    public function noticeTable()
    {
        return $this->db->table($this->table);
    }

    // function to return list of exam status
    public function listStatus()
    {
        // query for show column where field is 'status'
        $query = $this->db->query("SHOW COLUMNS FROM exam_table WHERE FIELD = 'status'");
        // get type
        $type = $query->getRow()->Type;
        // perform a regular expression match
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        // result as array
        $result = explode("','", $matches[1]);
        // return result
        return $result;
    }

    // function to return row result
    public function rowResult($column)
    {
        $row_result = function ($row) use ($column) {
            $status = $this->listStatus();

            if ($column == 'status') {
                if ($row[$column] == $status[0]) {
                    return "<div><span class='py-1 badge badge-secondary'>$row[$column]</span></div>";
                } else if ($row[$column] == $status[1]) {
                    return "<div><span class='py-1 badge badge-primary'>$row[$column]</span></div>";
                } else if ($row[$column] == $status[2]) {
                    return "<div><span class='py-1 badge badge-success'>$row[$column]</span></div>";
                } else {
                    return "<div><span class='py-1 badge badge-dark'>$row[$column]</span></div>";
                }
            } else if ($column == 'answer_topic') {
                if (empty($row[$column])) {
                    return '
                        <form action="' . base_url('admin/upload_answer_topic/' . $row['id']) . '" method="POST" enctype="multipart/form-data">
                            <div class="d-flex flex-column justify-content-center">
                                <div class="form-upload mb-2">
                                    <input type="file" id="answer-topic-' . $row['id'] . '" name="answer_topic">
                                    <label for="answer-topic-' . $row['id'] . '" class="btn m-0 p-0">
                                        <i class="fas fa-file-upload fa-2x" style="color: #4E73DF;"></i>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-primary btn-block btn-icon-action badge py-1 px-2">Upload</button>
                            </div>
                        </form>
                        ';
                }

                return '
                    <div class="d-flex flex-column justify-content-center">
                        <div class="text-center mb-2">
                            <button type="button" id="admin-answer-topic" class="btn btn-sm btn-primary btn-icon-action" data-id="' . $row['id'] . '">
                                    <i class="fas fa-file-pdf fa-2x"></i>
                            </button>
                        </div>
                        <button type="button" id="delete-answer-topic" class="btn btn-sm btn-primary btn-block btn-icon-action badge py-1 px-2" data-id="' . $row['id'] . '">Delete</button>
                    </div>
                    ';
            }

            return $row[$column];
        };

        return $row_result;
    }

    // function to return action button at exam table
    public function actionButton()
    {
        $button = function ($row) {
            // determine if exam can be edited or not
            $editable = $this->isExamStarted($row["id"]) ? 'disable' : null;

            return '
                <a href="' . base_url('exam/' . $row["id"]) . '" role="button" class="btn btn-sm btn-info btn-icon-action mb-1">
                    <span class="icon text-white-50">
                        <i class="fas fa-info mx-1"></i>
                    </span>
                </a>
                &nbsp;
                <button type="button" class="btn btn-sm btn-warning btn-icon-action mb-1" id="btn-exam-edit" data-id="' . $row["id"] . '" data-editable="' . $editable . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                </button>
                &nbsp;
                <button type="button" class="btn btn-sm btn-danger btn-icon-action mb-1" id="btn-exam-delete" data-id="' . $row["id"] . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-trash mx-1"></i>
                    </span>
                </button>
                ';
        };

        return $button;
    }

    // function to check is exam has been started or not
    public function isExamStarted($exam_id = 0)
    {
        date_default_timezone_set("Asia/Makassar");

        $current_datetime = date("Y-m-d H:i:s", time());
        $exam_datetime = $this->find($exam_id)["implement_date"];

        return $current_datetime > $exam_datetime  ? true : false;
    }

    // function to change exam status
    public function changeExamStatus($exam)
    {
        date_default_timezone_set("Asia/Makassar");

        $this->questionsModel = new QuestionsModel();

        $current_question = $this->questionsModel
            ->where(['exam_id' => $exam['id']])
            ->countAllResults();

        $total_question = $exam['total_question'];

        if ($this->isExamStarted($exam['id'])) {
            $current_datetime = date("Y-m-d H:i:s", time());
            $exam_datetime = $exam['implement_date'];

            $delta_datetime = strtotime($current_datetime) - strtotime($exam_datetime);
            $exam_duration = ($exam['duration'] + 0) * 60;

            $data['status'] = $delta_datetime < $exam_duration ? $this->listStatus()[2] : $this->listStatus()[3];

            $this->update($exam['id'], $data);
        }

        return "Filled " . $current_question . " of " . $total_question;
    }

    // function to return user enrolled button at exam table
    public function userEnrolledButton()
    {
        $button = function ($row) {
            return '
                <a href="' . base_url('user_enroll?page=user_enroll&code=' . $row['code']) . '" role="button" class="btn btn-sm btn-info btn-block">
                    Show Enroll
                </a>
                ';
        };

        return $button;
    }

    // function to return user enrolled button at exam table
    public function usersScoreButton()
    {
        $button = function ($row) {
            return '
                <a href="' . base_url('admin_exam_result?page=admin_exam_result&code=' . $row['code']) . '" role="button" class="btn btn-sm btn-warning btn-block">
                    Show Score
                </a>
                ';
        };

        return $button;
    }

    // function to return question button at exam table
    public function questionButton()
    {
        $button = function ($row) {
            $btn_text = $this->changeExamStatus($row);

            return '
                <a href="' . base_url('question?page=admin_exam_view&code=' . $row['code']) . '" role="button" class="btn btn-sm btn-danger btn-block">
                    ' . $btn_text . '
                </a>
                ';
        };

        return $button;
    }
}
