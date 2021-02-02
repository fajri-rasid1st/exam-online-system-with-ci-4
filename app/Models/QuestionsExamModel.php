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
            // instance exam model class
            $this->examsModel = new ExamsModel();
            // determine question can be edited or not
            $editable = $this->examsModel->isExamStarted($row["exam_id"]) ? 'disable' : null;

            if ($column == 'image') {
                if (empty($row[$column])) {
                    return '
                        <form action="' . base_url('admin/attempt_question_image/' . $row['id']) . '" method="POST" enctype="multipart/form-data">
                            <div class="d-flex flex-column justify-content-center img-container">
                                <div class="form-upload mb-2">
                                    <input type="file" id="image-' . $row['id'] . '" name="question_image">
                                    <label for="image-' . $row['id'] . '" class="btn m-0 p-0">
                                        <i class="fas fa-upload fa-2x" style="color: #36B9CC;"></i>
                                    </label>
                                </div>
                                <button type="submit" class="btn btn-sm btn-info btn-icon-action badge py-1 px-2">Upload</button>
                            </div>
                        </form>
                        ';
                }

                return '
                    <div class="d-flex flex-column justify-content-center img-container">
                        <div class="mb-2">
                            <img class="img-thumbnail" id="question-img" src="' . base_url("img/exam/" . $row[$column]) . '" alt="' . $row[$column] . '" data-action="zoom">
                        </div>
                        <button type="button" class="btn btn-sm btn-info btn-icon-action badge py-1 px-2" id="btn-quest-img-del" data-id="' . $row['id'] . '" data-editable="' . $editable . '">Delete</button>
                    </div>
                    ';
            }

            return $row[$column];
        };

        return $row_result;
    }

    // function to return action button
    public function actionButton()
    {
        $button = function ($row) {
            // instance exam model class
            $this->examsModel = new ExamsModel();
            // find exam
            $exam = $this->examsModel->find($row["exam_id"]);
            // determine question can be deleted or not
            $deletable = $exam["status"] == $this->examsModel->listStatus()[1] ? "disabled" : null;
            // determine question can be edited or not
            $editable = $this->examsModel->isExamStarted($row["exam_id"]) ? "disable" : null;

            return '
                <button type="button" class="btn btn-sm btn-warning btn-icon-action mb-1" id="btn-question-edit" data-id="' . $row["id"] . '" title="edit" data-editable="' . $editable . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                </button>
                &nbsp;
                <button type="button" class="btn btn-sm btn-danger btn-icon-action mb-1" id="btn-question-delete" data-id="' . $row["id"] . '" title="delete" data-editable="' . $editable . '" ' . $deletable . '>
                    <span class="icon text-white-50">
                        <i class="fas fa-trash mx-1"></i>
                    </span>
                </button>
                ';
        };

        return $button;
    }
}
