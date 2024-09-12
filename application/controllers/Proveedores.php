<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Proveedores extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Proveedores";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function proveedor() {
        $this->data["uri"] = [
            "title"         => "Proveedores",
            "list"          => base_url().$this->name()."/proveedor_list",
            "create"        => base_url().$this->name()."/proveedor_create",
            "save"          => base_url().$this->name()."/proveedor_save",
            "remove"        => base_url().$this->name()."/proveedor_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function proveedor_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'tipo_documento',
			2 => 'num_documento',
			3 => 'proveedor',
			4 => 'tipo_proveedor',
			5 => 'direccion',
			6 => 'telefono',
			7 => 'correo',
			8 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    p.id,
					k2.valor as tipo_documento,
					p.num_documento,
					p.nombre as proveedor,
					k3.valor as tipo_proveedor,
					p.direccion,
					p.telefono,
					p.correo,
                    k1.valor as status 
                from proveedor p
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = p.status
                inner join constante k2 on k2.idconstante = ".ID_CONST_REG_TDOC." and k2.codigo = p.tipo_documento
                inner join constante k3 on k3.idconstante = ".ID_CONST_REG_TPROVEEDOR." and k3.codigo = p.tipo_proveedor
                where 1 = 1";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( p.id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR k2.valor LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR k3.valor LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR p.correo LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR p.num_documento LIKE '".$requestData['search']['value']."%'";
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

    public function proveedor_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("proveedor", ["id" => $id ])->row_array());
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function proveedor_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$tipo_documento = $this->input->post("tipo_documento");
		$num_documento = $this->input->post("num_documento");
		$nombre = $this->input->post("nombre");
		$tipo_proveedor = $this->input->post("tipo_proveedor");
		$direccion = $this->input->post("direccion");
		$telefono = $this->input->post("telefono");
		$correo = $this->input->post("correo");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());


        $data = [
            "tipo_documento"			=> $tipo_documento,
            "num_documento"				=> $num_documento,
            "nombre"			        => $nombre,
            "tipo_proveedor"			=> $tipo_proveedor,
            "direccion"			    	=> $direccion,
            "telefono"			    	=> $telefono,
            "correo"			    	=> $correo,
            "created_user_id"			=> $this->session->userdata("userID"),
            "created_datetime"			=> $tm,
            "status"			        => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {              
					
                $data["updated_user_id"] = $this->session->userdata("userID");
                $data["updated_datetime"] = $tm;

				$this->Constant_model->updateData("proveedor", $data, $id);

            } else {

				$producto_id = $this->Constant_model->insertDataReturnLastId("proveedor", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function proveedor_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("proveedor", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("proveedor", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
}
