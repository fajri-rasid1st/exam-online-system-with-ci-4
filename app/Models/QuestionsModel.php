<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionsModel extends Model
{
    protected $table = 'question_table';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id',
        'exam_id',
        'title',
        'options',
        'types',
        'image',
    ];

    // function to return enum field at question_table
    public function listQuestionEnum($field)
    {
        // query for show column according to field at parameter
        $query = $this->db->query("SHOW COLUMNS FROM question_table WHERE FIELD = '" . $field . "'");
        // get type
        $type = $query->getRow()->Type;
        // perform a regular expression match
        preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
        // result as array
        $result = explode("','", $matches[1]);
        // return result
        return $result;
    }
}
