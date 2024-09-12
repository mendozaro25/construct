<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obras extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Obras";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function obra() {
        $this->data["uri"] = [
            "title"         => "Obras",
            "list"          => base_url().$this->name()."/obra_list",
            "create"        => base_url().$this->name()."/obra_create",
            "save"          => base_url().$this->name()."/obra_save",
            "remove"        => base_url().$this->name()."/obra_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function obra_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'nombre',
			2 => 'ubicacion',
			3 => 'fecha_inicio',
			4 => 'fecha_final',
			5 => 'costo_obra',
			6 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    o.id, 
                    o.nombre, 
                    o.ubicacion, 
                    o.fecha_inicio, 
                    o.fecha_final, 
                    FORMAT(costo_obra, 2) as costo_obra, 
                    k1.valor as status 
                from obra o
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = o.status
                where 1 = 1";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( o.id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR o.nombre LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR o.status LIKE '".$requestData['search']['value']."%') ";
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

    public function obra_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("obra", ["id" => $id ])->row_array());
		
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function obra_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$nombre = $this->input->post("nombre");
		$ubicacion = $this->input->post("ubicacion");
		$descripcion = $this->input->post("descripcion");
		$fecha_inicio = $this->input->post("fecha_inicio");
		$fecha_final = $this->input->post("fecha_final");
		$costo_obra = $this->input->post("costo_obra");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());

        $data = [
            "nombre"			        => $nombre,
            "ubicacion"			        => $ubicacion,
            "descripcion"			    => $descripcion,
            "fecha_inicio"			    => $fecha_inicio,
            "fecha_final"			    => $fecha_final,
            "created_user_id"			=> $this->session->userdata("userID"),
            "created_datetime"			=> $tm,
            "status"			        => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {
					
                $data["updated_user_id"] = $this->session->userdata("userID");
                $data["updated_datetime"] = $tm;

				$this->Constant_model->updateData("obra", $data, $id);

            } else {

				$obra_id = $this->Constant_model->insertDataReturnLastId("obra", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function obra_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("obra", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("obra", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
}
