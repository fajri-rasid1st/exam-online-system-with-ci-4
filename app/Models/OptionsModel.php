<?php

namespace App\Models;

use CodeIgniter\Model;

class OptionsModel extends Model
{
    protected $table = 'option_table';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id',
        'question_id',
        'option_char',
        'title',
    ];
}
