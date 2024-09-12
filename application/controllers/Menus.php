<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Menus";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function menu() {
        $this->data["uri"] = [
            "title"         => "Menus",
            "list"          => base_url().$this->name()."/menu_list",
            "create"        => base_url().$this->name()."/menu_create",
            "save"          => base_url().$this->name()."/menu_save",
            "remove"        => base_url().$this->name()."/menu_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function menu_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'display_name',
			2 => 'pariente',
			3 => 'controller',
			4 => 'main_method',
			5 => 'icon',
			6 => 'order',
			7 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    m.id, 
					m.display_name,
                    k2.display_name as pariente,
                    m.controller,
                    m.main_method,
                    m.icon,
                    m.order,
                    k1.valor as status 
                from menu m
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = m.status
                left join menu k2 on k2.id = m.parent_id
                where 1 = 1";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( m.id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR k2.display_name LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR m.display_name LIKE '".$requestData['search']['value']."%') ";
			}

			$rs = $this ->db->query($sql);
			$totalFiltered = $rs->num_rows();

			$sql.=" ORDER BY ". $columns[$requestData['order'][0]['column']] ."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

			$rs = $this ->db->query($sql)->result_array();
		}

		echo json_encode([
			    "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
			    "recordsTotal"    => intval( $totalData ),  // total number of records
			    "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			    "data"            => $rs   // total data array
		    ]);
		exit;
	}

    public function menu_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("menu", ["id" => $id ])->row_array());
        
        $this->data["parientes"] = gw("menu", ["status" => RECORD_STATUS_ACTIVE ])->result_array();

		$this->load->view($this->name() . "/create", $this->data);
	}

    public function menu_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$display_name = $this->input->post("display_name");
		$parent_id = $this->input->post("parent_id");
		$controller = $this->input->post("controller");
		$main_method = $this->input->post("main_method");
		$icon = $this->input->post("icon");
		$order = $this->input->post("order");
		$status = $this->input->post("status");

        if($parent_id == 0){
            $data["parent_id"] = null;
        } else {
            $data["parent_id"] = $parent_id;
        }

        if($icon == 1){
            $data["icon"] = null;
        } else {
            $data["icon"] = $icon;
        }
        #   die(var_dump($data["icon"]));

        $data = [
            "display_name"			=> $display_name,
            "controller"			=> $controller,
            "main_method"			=> $main_method,
            "order"			        => $order,
            "status"			    => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {

                if($parent_id == 0){
                    $data["parent_id"] = null;
                } else {
                    $data["parent_id"] = $parent_id;
                }
        
                if($icon == 1){
                    $data["icon"] = null;
                } else {
                    $data["icon"] = $icon;
                }

				$this->Constant_model->updateData("menu", $data, $id);

            } else {

				$menu_id = $this->Constant_model->insertDataReturnLastId("menu", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function menu_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("menu", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("menu", ["status" => 0 ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
}
