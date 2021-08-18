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
		$data = $this->safetyModel->getAllTyck();
		$x = (count($data) != 0);
		return view('templates/dashboard', ['x' => $x]);
	}
}
