<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersEnrollModel extends Model
{
    protected $table = 'user_exam_enroll_table';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id',
        'user_id',
        'exam_id',
        'attendance_status',
    ];
}
