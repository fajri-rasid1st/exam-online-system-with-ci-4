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

            // check all possible status
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
                <a href="' . base_url('exam/' . $row["id"]) . '" role="button" class="btn btn-sm btn-info btn-icon-action" title="detail">
                    <span class="icon text-white-50">
                        <i class="fas fa-info mx-1"></i>
                    </span>
                </a>
                &nbsp;
                <button type="button" class="btn btn-sm btn-warning btn-icon-action" id="btn-exam-edit" data-id="' . $row["id"] . '" title="edit" data-editable="' . $editable . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                </button>
                &nbsp;
                <button type="button" class="btn btn-sm btn-danger btn-icon-action" id="btn-exam-delete" data-id="' . $row["id"] . '" title="delete" data-editable="' . $editable . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-trash mx-1"></i>
                    </span>
                </button>';
        };

        return $button;
    }

    // function to check is exam has been started or not
    public function isExamStarted($exam_id = 0)
    {
        date_default_timezone_set("Asia/Makassar");

        $current_datetime = date("Y-m-d H:i:s", time());
        $exam_datetime = $this->find($exam_id)["implement_date"];

        if ($exam_datetime < $current_datetime) {
            return true;
        }

        return false;
    }

    // function to return question button at exam table
    public function questionButton()
    {
        $button = function ($row) {
            // instance exam model class
            $this->questionsModel = new QuestionsModel();
            // get current question of exam
            $current_question = $this->questionsModel->where(['exam_id' => $row['id']])->countAllResults();
            // get total question of exam
            $total_question = $row['total_question'];

            if ($current_question < $total_question) {
                // get value updated exam status
                $data['status'] = $this->listStatus()[0];
                // update exam status
                $this->update($row['id'], $data);
            } else {
                // get value updated exam status
                $data['status'] = $this->listStatus()[1];
                // update exam status
                $this->update($row['id'], $data);
            }

            return '
                <a href="' . base_url('question?code=' . $row['code']) . '" class="btn btn-sm btn-primary">
                    Filled with ' . $current_question . ' of ' . $total_question . '
                </a>';
        };

        return $button;
    }
}
