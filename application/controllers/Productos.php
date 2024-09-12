<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Productos";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function producto() {
        $this->data["uri"] = [
            "title"         => "Productos",
            "list"          => base_url().$this->name()."/producto_list",
            "create"        => base_url().$this->name()."/producto_create",
            "save"          => base_url().$this->name()."/producto_save",
            "remove"        => base_url().$this->name()."/producto_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function producto_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'categoria',
			2 => 'unidad_medida',
			3 => 'producto',
			4 => 'precio_unitario',
			5 => 'descripcion',
			6 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    p.id,
                    u1.simbolo as unidad_medida,
                    p.categoria, 
                    p.nombre as producto, 
                    FORMAT(p.precio_unitario, 2) as precio_unitario,
                    p.descripcion,
                    k1.valor as status
                from producto p
                inner join unidad_medida u1 on u1.id = p.unidad_medida_id
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = p.status
                where 1 = 1";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( p.id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR u1.simbolo LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR p.categoria LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR p.nombre LIKE '".$requestData['search']['value']."%') ";
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

    public function producto_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("producto", ["id" => $id ])->row_array());
        
		$noArray = [];
		if ($tmp = $this->db->query("select distinct categoria from producto")->result_array()) {
			foreach ($tmp as $i) {
				if (strlen(trim($i["categoria"])) >0 && !in_array(trim($i["categoria"]), $noArray))
					$noArray = array_merge($noArray, [$i["categoria"]]);
			}
		}
		$this->data = array_merge($this->data, ["noArray" => $noArray ]);
		
        $this->data["undmedidas"] = gw("unidad_medida", ["status" => RECORD_STATUS_ACTIVE ])->result_array(); 	
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function producto_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$categoria = $this->input->post("categoria");
		$unidad_medida_id = $this->input->post("unidad_medida_id");
		$nombre = $this->input->post("nombre");
		$precio_unitario = $this->input->post("precio_unitario");
		$descripcion = $this->input->post("descripcion");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());


        $data = [
            "unidad_medida_id"			=> $unidad_medida_id,
            "categoria"			    	=> $categoria,
            "nombre"			        => $nombre,
            "precio_unitario"			=> $precio_unitario,
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

				$this->Constant_model->updateData("producto", $data, $id);

            } else {

				$producto_id = $this->Constant_model->insertDataReturnLastId("producto", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function producto_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("unidad_medida", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("unidad_medida", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
}
