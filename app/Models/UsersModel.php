<?php

namespace App\Models;

use CodeIgniter\Model;

class UsersModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id',
        'email',
        'username',
        'fullname',
        'phone_number',
        'gender',
        'address',
        'profile_pict'
    ];

    public function noticeTable()
    {
        return $this->db->table($this->table);
    }

    public function actionButton()
    {
        $button = function ($row) {
            return '
                <a href="' . base_url('admin/' . $row["id"]) . '" role="button" class="btn btn-sm btn-info btn-icon-action mb-1">
                    <span class="icon text-white-50">
                        <i class="fas fa-info mx-1"></i>
                    </span>
                </a>
                &nbsp;
                <button type="button" class="btn btn-sm btn-warning btn-icon-action mb-1" id="btn-edit" data-id="' . $row["id"] . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-edit"></i>
                    </span>
                </button>
                &nbsp;
                <button type="button" class="btn btn-sm btn-danger btn-icon-action mb-1" id="btn-delete" data-id="' . $row["id"] . '">
                    <span class="icon text-white-50">
                        <i class="fas fa-trash mx-1"></i>
                    </span>
                </button>
                ';
        };

        return $button;
    }
}
