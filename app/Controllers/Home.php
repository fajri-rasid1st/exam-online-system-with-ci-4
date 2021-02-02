<?php

namespace App\Controllers;

use App\Models\ExamsModel;

class Home extends BaseController
{
	protected $examsModel;

	// constructor for connect to database
	public function __construct()
	{
		$this->db = \Config\Database::connect();
		$this->examsModel = new ExamsModel();
	}

	// view home page
	public function index()
	{
		$exams = $this->examsModel->findAll();
		$exam_status = $this->examsModel->listStatus();

		foreach ($exams as $exam) {
			$this->examsModel->changeExamStatus($exam);
		}

		$exams_created = $this->examsModel
			->where(['status' => $exam_status[1]])
			->orderBy('implement_date', 'ASC')
			->findAll();

		$data = [
			"title" => "Home",
			"exams" => $exams_created,
		];

		return view('index', $data);
	}
}
