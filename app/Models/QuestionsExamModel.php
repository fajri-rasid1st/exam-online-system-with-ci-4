<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ExamsModel;

class QuestionsExamModel extends Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $examsModel;

    // function to create table
    public function noticeTable($exam_id = 0)
    {
        $this->table = 'questions_for_exam_' . $exam_id;

        return $this->db->table($this->table);
    }

    // function to return row result
    public function rowResult($column)
    {
        $row_result = function ($row) use ($column) {
            if ($column == 'image') {
                if (empty($row[$column])) {
                    return 'No Image Preview';
                }

                return '<img src="' . base_url("img/exam/" . $row[$column]) . '" alt="' . $row[$column] . '" width="120">';
            }

            return $row[$column];
        };

        return $row_result;
    }

    // function to return action button at question table
    public function actionButton()
    {
        $button = function ($row) {
            // instance exam model class
            $this->examsModel = new ExamsModel();
            // determine question can be edited or not
            $editable = $this->examsModel->isExamStarted($row["exam_id"]) ? 'disable' : null;

            return '
                <button type="button" class="btn btn-sm btn-warning btn-icon-action" id="btn-question-edit" data-id="' . $row["id"] . '" title="edit" data-editable="' . $editable . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                </button>
                &nbsp;
                <button type="button" class="btn btn-sm btn-danger btn-icon-action" id="btn-question-delete" data-id="' . $row["id"] . '" title="delete" data-editable="' . $editable . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-trash mx-1"></i>
                    </span>
                </button>';
        };

        return $button;
    }
}
