<?php

namespace App\Controllers;

use App\Models\UsersModel;

class User extends BaseController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = new UsersModel();
    }

    public function index()
    {
        $data["title"] = "My Profile";

        return view('user/index', $data);
    }

    public function update($id = 0)
    {
        if ($id != user()->id) {
            return redirect()->to("/user");
        }

        $user_data = $this->usersModel->where(['id' => $id])->first();
        $validation = \Config\Services::validation();

        $data = [
            'title' => 'Edit Profile',
            'user' => $user_data,
            'validation' => $validation,
        ];

        return view('user/update', $data);
    }

    public function attempt_update()
    {
        // cek username user
        if (user()->username == $this->request->getVar("username")) {
            $username_rules = 'required|alpha_numeric|min_length[3]|max_length[30]';
        } else {
            $username_rules = 'required|is_unique[users.username]|alpha_numeric|min_length[3]|max_length[30]';
        }

        // cek email user
        if (user()->email == $this->request->getVar("email")) {
            $email_rules = 'required|valid_email|max_length[255]';
        } else {
            $email_rules = 'required|is_unique[users.email]|valid_email|max_length[255]';
        }

        if (!$this->validate([
            'username'     => $username_rules,
            'email'        => $email_rules,
            'fullname'     => 'required|min_length[3]|max_length[255]',
            'phone_number' => 'required|max_length[30]',
            'gender'       => 'required',
            'address'      => 'required|max_length[255]',
            'profile_pict' => [
                'rules'  => 'max_size[profile_pict,1024]|is_image[profile_pict]|mime_in[profile_pict,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'File size is to large (max: 1024kb).',
                    'is_image' => 'File extension not supported.',
                    'mime_in'  => 'File extension not supported.',
                ],
            ],
        ])) {
            return redirect()->to("/user/" . $this->request->getVar("id"))->withInput();
        }

        // uploaded file
        $file_img = $this->request->getFile("profile_pict");
        // old file
        $old_img = $this->request->getVar('oldpict');

        // check neither user upload file or not
        if ($file_img->getError() == 4) {
            $file_name = $this->request->getVar('oldpict');
        } else {
            // generate a random name
            $file_name = $file_img->getRandomName();
            // move file to img folder
            $file_img->move('img/profile', $file_name);
            // delete old file from img folder, if
            if ($old_img != 'default.png') {
                unlink('img/profile/' . $old_img);
            }
        }

        $this->usersModel->save([
            'id' => $this->request->getVar("id"),
            'username' => $this->request->getVar("username"),
            'email' => $this->request->getVar("email"),
            'fullname' => $this->request->getVar("fullname"),
            'phone_number' => $this->request->getVar("phone_number"),
            'gender' => $this->request->getVar("gender"),
            'address' => $this->request->getVar("address"),
            'profile_pict' => $file_name,
        ]);

        session()->setFlashData("message", "Profile data has been updated.");

        return redirect()->to("/user");
    }

    public function update_password($id = 0)
    {
        if ($id != user()->id) {
            return redirect()->to("/user");
        }

        $validation = \Config\Services::validation();

        $data = [
            'title' => 'Change Password',
            'validation' => $validation,
        ];

        return view('user/password', $data);
    }

    public function attempt_password()
    {
        $users = model(UserModel::class);

        $user = $users->where('email', $this->request->getVar('email'))->first();

        if (is_null($user) || $user->email != user()->email) {
            return redirect()->back()->withInput()->with('error', 'Your email address is invalid');
        }

        // Save the reset hash
        $user->generateResetHash();
        $users->save($user);

        $resetter = service('resetter');
        $sent = $resetter->send($user);

        if (!$sent) {
            return redirect()->back()->withInput()->with('error', $resetter->error() ?? lang('Auth.unknownError'));
        }

        return redirect()->back()->withInput()->with('info', lang('Auth.forgotEmailSent'));
    }

    public function attempt_reset()
    {
        $users = model(UserModel::class);

        // First things first - log the reset attempt.
        $users->logResetAttempt(
            $this->request->getVar('confirm-email'),
            $this->request->getVar('token'),
            $this->request->getIPAddress(),
            (string)$this->request->getUserAgent(),
        );

        if (!$this->validate([
            'token'           => 'required',
            'confirm-email'   => 'required|valid_email',
            'new-password'    => 'required|strong_password',
            'repeat-password' => 'required|matches[new-password]',
        ])) {
            return redirect()->to("/update_password/" . $this->request->getVar("id"))->withInput();
        }

        $user = $users->where('email', $this->request->getVar('confirm-email'))
            ->where('reset_hash', $this->request->getVar('token'))
            ->first();

        if (is_null($user) || $user->email != user()->email) {
            return redirect()->back()->withInput()->with('error', 'Your confirm email address or token is invalid');
        }

        // Reset token still valid?
        if (!empty($user->reset_expires) && time() > $user->reset_expires->getTimestamp()) {
            return redirect()->back()->withInput()->with('error', lang('Auth.resetTokenExpired'));
        }

        // Success! Save the new password, and cleanup the reset hash.
        $user->password         = $this->request->getVar('new-password');
        $user->reset_hash       = null;
        $user->reset_at         = date('Y-m-d H:i:s');
        $user->reset_expires    = null;
        $user->force_pass_reset = false;
        $users->save($user);

        return redirect()->to('/user')->with('message', 'Your password has been change.');
    }
}
