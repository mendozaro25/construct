<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Areas extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Areas";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function area() {
        $this->data["uri"] = [
            "title"         => "Areas",
            "list"          => base_url().$this->name()."/area_list",
            "create"        => base_url().$this->name()."/area_create",
            "save"          => base_url().$this->name()."/area_save",
            "remove"        => base_url().$this->name()."/area_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function area_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'nombre',
			2 => 'descripcion',
			3 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    a.id, 
					a.nombre,
					a.descripcion,
                    k1.valor as status 
                from area a
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = a.status
                where 1 = 1 and a.id > 0";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( a.id LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR a.nombre LIKE '".$requestData['search']['value']."%') ";
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

    public function area_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("area", ["id" => $id ])->row_array());

		$this->load->view($this->name() . "/create", $this->data);
	}

    public function area_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$nombre = $this->input->post("nombre");
		$descripcion = $this->input->post("descripcion");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());

        $data = [
            "nombre"			        => $nombre,
            "descripcion"			    => $descripcion,
            "created_user_id"			=> $this->session->userdata("userID"),
            "created_datetime"			=> $tm,
            "status"			        => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {
					
                $data["updated_user_id"] = $this->session->userdata("userID");
                $data["updated_datetime"] = $tm;

				$this->Constant_model->updateData("area", $data, $id);

            } else {

				$personal_id = $this->Constant_model->insertDataReturnLastId("area", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function area_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("area", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("area", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
}
