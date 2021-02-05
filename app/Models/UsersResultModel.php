<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\ExamsModel;
use App\Models\OptionsModel;

class UsersResultModel extends Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $examsModel;
    protected $optionsModel;

    // function to create table
    public function noticeTable($exam_id = 0, $user_id = 0)
    {
        $this->table = 'result_exam_user_' . $exam_id  . '_' . $user_id;

        return $this->db->table($this->table);
    }

    // function to return row result
    public function rowResult($column)
    {
        $row_result = function ($row) use ($column) {
            $this->optionsModel = new OptionsModel();

            if ($column == 'image') {
                if (empty($row[$column])) {
                    return '
                        <div class="img-container">
                            <div class="m-0">
                                <img class="img-thumbnail" src="' . base_url("img/exam/no-image-icon.jpg") . '" alt="' . $row[$column] . '" data-action="zoom">
                            </div>
                        </div>
                        ';
                }

                return '
                    <div class="img-container">
                        <div class="m-0">
                            <img class="img-thumbnail" src="' . base_url("img/exam/" . $row[$column]) . '" alt="' . $row[$column] . '" data-action="zoom">
                        </div>
                    </div>
                    ';
            } else if ($column == 'user_answer_option') {
                $user_answer_option_title = $this->optionsModel
                    ->where([
                        'question_id' => $row['id'],
                        'option_char' => $row[$column],
                    ])->first();

                if ($user_answer_option_title) {
                    return "($row[$column])" . " " . $user_answer_option_title["title"];
                }
            } else if ($column == 'options') {
                $right_answer_option_title = $this->optionsModel
                    ->where([
                        'question_id' => $row['id'],
                        'option_char' => $row[$column],
                    ])->first()['title'];

                return "($row[$column])" . " " . $right_answer_option_title;
            }

            return $row[$column];
        };

        return $row_result;
    }

    // function to return row result
    public function resultStatus()
    {
        $result_status = function ($row) {
            $this->examsModel = new ExamsModel();

            $exam = $this->examsModel->find($row["exam_id"]);

            if ($row["scores"] == $exam["score_per_right_answer"]) {
                return '
                    <div class="d-flex flex-column justify-content-center">
                        <div class="text-center mb-2">
                            <i class="fas fa-check-circle fa-2x" style="color: #198754;"></i>
                        </div>
                        <div class="m-0 text-center">Correct</div>
                    </div>
                    ';
            } else if ($row["scores"] == $exam["score_per_wrong_answer"]) {
                return '
                    <div class="d-flex flex-column justify-content-center">
                        <div class="text-center mb-2">
                            <i class="fas fa-times-circle fa-2x" style="color: #DC3545;"></i>
                        </div>
                        <div class="m-0 text-center">Wrong</div>
                    </div>
                    ';
            }

            return '
                <div class="d-flex flex-column justify-content-center">
                    <div class="text-center mb-2">
                        <i class="fas fa-meh-blank fa-2x"></i>
                    </div>
                    <div class="m-0 text-center">Empty</div>
                </div>
                ';
        };

        return $result_status;
    }
}
