<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->data["page_title"] = "Tablero";

		$this->load->view('dashboard', $this->data);
	}
}
