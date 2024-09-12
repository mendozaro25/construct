<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Me extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Mi perfil";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function me_view() {
		$data = [];

		$id = $this->session->userdata("userID");

		$this->data = array_merge($data, gw("users", ["id" => $id ])->row_array());
        $this->data["page_title"] .= "Mi Perfil";
        $this->data["title"] .= "Mi Perfil";
		$this->load->view($this->name()."/me", $this->data);
	}

	public function me_save() {
		$ret["success"] = false;

		$id = $this->input->post("id");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$email = $this->input->post("email");
		$dni = $this->input->post("dni");
		$phone = $this->input->post("phone");
		$name = $this->input->post("name");
		$lastname = $this->input->post("lastname");
        $tm = date('Y-m-d H:i:s', time());

        if ($file = uploadImage("photo", $id, UPLOAD_PATH_USERS, true )){
			$__files = $file;
			$this->db->trans_start();						
			$data["photo"] = $__files;
			$this->Constant_model->updateData("users", $data, $id);
			$this->db->trans_complete();
			$ret["success"] = true;
		}

        $data = [
            "username"			        => $username,
            "email"			        	=> $email,
            "dni"			            => $dni,
            "phone"			            => $phone,
            "name"			            => $name,
            "lastname"			        => $lastname,
        ];

        if ($password)
        	$data["password"] = password_hash($password, PASSWORD_BCRYPT);

		try {
			$this->db->trans_start();
            $data["user_id"] = $this->session->userdata("userID");
            $data["updated_at"] = $tm;
            $this->Constant_model->updateData("users", $data, $id);
			$this->db->trans_complete();

			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
        
        redirect(base_url()."me/me_view");
	}
}
