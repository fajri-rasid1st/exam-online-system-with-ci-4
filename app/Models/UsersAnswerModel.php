<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersAnswerModel extends Model
{
    protected $table = 'user_exam_answer_table';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id',
        'user_id',
        'exam_id',
        'question_id',
        'user_answer_option',
        'scores',
    ];
}
