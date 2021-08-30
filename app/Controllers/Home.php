<?php

namespace App\Controllers;

use App\Models\Tyck\Safety_model;

class Home extends BaseController
{
	protected $safetyModel;
	public function __construct()
	{
		$this->safetyModel = new Safety_model();
	}

	public function index()
	{
		$roleID = (int)session()->get('roleID') ?? 0;
		// dd($roleID);
		if ($roleID == 14) {
			return view('templates/dashboard');
		} else if ($roleID == 16) {
			$data = $this->safetyModel->getAllTyck();
			$x = (count($data) != 0);
			return view('templates/tyck_dashboard', ['x' => $x]);
		} else if ($roleID == 1) {
			$data = $this->safetyModel->getAllTyck();
			$x = (count($data) != 0);
			return view('templates/dashboard', ['x' => $x]);
		}
	}
}
