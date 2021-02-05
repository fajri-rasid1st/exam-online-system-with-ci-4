<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ExamsModel;

class UsersEnrollExamModel extends Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $examsModel;

    // function to create table
    public function noticeTable($exam_id = 0)
    {
        $this->table = 'users_for_exam_' . $exam_id;

        return $this->db->table($this->table);
    }

    // function to return row result
    public function rowResult($column)
    {
        $row_result = function ($row) use ($column) {
            if ($column == 'profile_pict') {
                return '
                    <div class="img-container">
                        <div class="m-0">
                            <img class="img-thumbnail" src="' . base_url("img/profile/" . $row[$column]) . '" alt="' . $row[$column] . '" data-action="zoom">
                        </div>
                    </div>
                    ';
            }

            return $row[$column];
        };

        return $row_result;
    }

    // function to return result button
    public function userExamResultButton()
    {
        $button = function ($row) {
            return '
                <a
                    href="' . base_url('user_exam_result?page=user_exam_result&user=' . $row['user_id'] . '&code=' . $row['exam_code']) . '"
                    role="button"
                    class="btn btn-sm btn-info btn-block"
                >
                    Show Result
                </a>
                ';
        };

        return $button;
    }

    // function to return action button
    public function actionButton()
    {
        $button = function ($row) {
            // instance exam model class
            $this->examsModel = new ExamsModel();
            // determine question can be deleted or not
            $deletable = $this->examsModel->isExamStarted($row["exam_id"]) ? "disabled" : null;

            return '
                <button
                    type="button"
                    class="btn btn-sm btn-danger btn-icon-action"
                    id="user-enroll-delete"
                    data-id="' . $row["id"] . '"
                    data-examid="' . $row["exam_id"] . '"
                    data-userid="' . $row["user_id"] . '"
                    data-deletable="' . $deletable . '"
                >
                    <span class="icon text-white-50">
                        <i class="fas fa-trash"></i>
                    </span>
                </button>
                ';
        };

        return $button;
    }
}
