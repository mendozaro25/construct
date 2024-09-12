<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Errores extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "ERROR 403";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function error403() {
		$this->load->view($this->name()."/403", $this->data);
	}
}
