<?php

namespace App\Models;

use CodeIgniter\Model;

class ResultsExamModel extends Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $useTimestamps = true;

    // function to create table
    public function noticeTable($exam_id = 0)
    {
        $this->table = 'exam_result_' . $exam_id;

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
            } else if ($column == 'scores') {
                return '
                    <div class="text-center m-0">
                        <h1 class="display-4">' . $row[$column] . '</h1>
                    </div>
                    ';
            }

            return $row[$column];
        };

        return $row_result;
    }
}
