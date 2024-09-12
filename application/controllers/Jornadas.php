<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jornadas extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Jornadas";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function jornada() {
        $this->data["uri"] = [
            "title"         => "Jornadas",
            "list"          => base_url().$this->name()."/jornada_list",
            "create"        => base_url().$this->name()."/jornada_create",
            "save"          => base_url().$this->name()."/jornada_save",
            "remove"        => base_url().$this->name()."/jornada_delete",
            "jornadas"        => base_url().$this->name()."/list_jornadas",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

	// Obra

    public function jornada_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'obra',
			2 => 'costo_obra',
			3 => 'fecha_inicio',
			4 => 'fecha_final',
			5 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
					dj.obra_id as id,
					o.nombre as obra,
					FORMAT(o.costo_obra, 2) as costo_obra,
					o.fecha_inicio,
					o.fecha_final,
					k1.valor as status 
				from detalle_jornada dj
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = dj.status
				inner join obra o on o.id = dj.obra_id
                where dj.jornada_id is null and dj.personal_id is null and o.status = ".RECORD_STATUS_ACTIVE;

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( dj.obra_id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR o.nombre LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR dj.status LIKE '".$requestData['search']['value']."%') ";
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

    public function jornada_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("detalle_jornada", ["id" => $id ])->row_array());
		
		$this->data["obras"] = gw("obra", ["status" => RECORD_STATUS_ACTIVE ])->result_array();    
		
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function jornada_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$obra_id = $this->input->post("obra_id");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());

        $data = [
            "obra_id"			        => $obra_id,
            "created_user_id"			=> $this->session->userdata("userID"),
            "created_datetime"			=> $tm,
            "status"			        => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {
					
                $data["updated_user_id"] = $this->session->userdata("userID");
                $data["updated_datetime"] = $tm;

				$this->Constant_model->updateData("detalle_jornada", $data, $id);

            } else {
				
				$detalle_jornada_id = $this->Constant_model->insertDataReturnLastId("detalle_jornada", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    /*public function jornada_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		
		$sql = 
			"select 
				dj.*
			from detalle_jornada dj
			inner join obra o on o.id = dj.obra_id
			where o.id = $id and dj.jornada_id is null and dj.personal_id is null";
		$data =  $this->db->query($sql)->result_array();
		$detalle_jornada_id = $data[0]["id"];

		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("detalle_jornada", "id", $detalle_jornada_id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("detalle_jornada", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $detalle_jornada_id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
	*/

	// Detalle Jornadas

	public function list_jornadas() {
		$ret["success"] = false;	
		$obra_id = $this->input->get("num");

		$sql = 
			"select 
				dj.id,
				dj.obra_id,
				o.nombre,
				o.fecha_inicio		
			from detalle_jornada dj
			inner join obra o on o.id = dj.obra_id
			where o.id=?";
        $this->data = $this->db->query($sql, [$obra_id])->row_array();

		$sql_pg =
		"select 
			dj.personal_id as person_reg
		from detalle_jornada dj
		where dj.obra_id = $obra_id and dj.personal_id > 0 and dj.status = ".RECORD_STATUS_ACTIVE."
		group by dj.personal_id";
		$result =  $this->db->query($sql_pg)->result_array();
		$this->data["person_reg"] = count($result);

		$sql_stp =
		"select 
			format(sum(dj.sueldo_personal_semana), 2) as sueldo_personal_semana
		from detalle_jornada dj
		where dj.obra_id = $obra_id and dj.jornada_id is not null and dj.status = ".RECORD_STATUS_ACTIVE;
		$this->data["result_"] =  $this->db->query($sql_stp)->row_array();

		$sql_pact = 
			"select 
				max(date_format(j.fecha_inicio, '%e %M %Y')) as periodo_actual
			from jornada j
			inner join detalle_jornada dj on dj.jornada_id = j.id
			inner join obra o on o.id = dj.obra_id
			where o.id = $obra_id and dj.jornada_id is not null and dj.status = ".RECORD_STATUS_ACTIVE;
		$this->data["result__"] =  $this->db->query($sql_pact)->row_array();

		$this->data["page_title"] = "Jornadas";			
		$this->load->view($this->name() . "/list_jornadas", $this->data);
	}

	public function jornadas_personal_list() {
		$requestData = $this->input->post();
		$obra_id = $this->input->get("obra_id");
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'obra',
			2 => 'descripcion',
			3 => 'fecha_inicio',
			4 => 'fecha_final',
			5 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
					j.id,
					o.nombre as obra,
					j.descripcion,
					j.fecha_inicio,
					j.fecha_final,
					k1.valor as status 
				from detalle_jornada dj
				inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = dj.status
				inner join obra o on o.id = dj.obra_id
				inner join jornada j on j.id = dj.jornada_id
				where o.id = $obra_id and jornada_id is not null and dj.status = ".RECORD_STATUS_ACTIVE;

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;
		$sql_gb = " group by j.id, k1.valor";
		$sql1 = "";

		if ($totalData) {

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql1.=" AND ( j.id LIKE '".$requestData['search']['value']."%' ";
                $sql1.=" OR o.nombre LIKE '".$requestData['search']['value']."%'";
				$sql1.=" OR j.descripcion LIKE '".$requestData['search']['value']."%') ";
			}

			$sql = $sql. $sql1 .$sql_gb ;
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

	public function jornadas_personal_create(){
		$data = [];		
		$obra_id = $this->input->get("obra_id");

		if ($jornada_id = $this->input->post("id")){

			$sql__ = 
				"select 
					count(dj.id) as personal_count
				from detalle_jornada dj
				where dj.jornada_id = $jornada_id and dj.status = ".RECORD_STATUS_ACTIVE;
			$this->data["pcount"] =  $this->db->query($sql__)->row_array();

			$sql = 
				"select 
					j.id as jornada_id,
					j.descripcion as jornada_nombre,
					j.fecha_inicio,
					j.fecha_final
				from jornada j
				where j.id = $jornada_id and j.status = ".RECORD_STATUS_ACTIVE;
			$this->data["rs"] =  $this->db->query($sql)->result_array();

			$sql_ = 
				"select 
					dj.id as detalle_jornada_id,
					p.id as personal_id,
					concat(p.nombre,' ',p.apellidos) as nombre_personal,
					a.nombre as area,
					e.nombre as especialidad
				from detalle_jornada dj
				inner join personal p on p.id = dj.personal_id
				inner join area a on a.id = p.area_id
				inner join especialidad e on e.id = p.especialidad_id
				where dj.jornada_id = $jornada_id";
			$this->data["data_pers_djorn"] = $this->db->query($sql_)->result_array();

		} else {
	
			$sql_ = 
				"select 
					date_add(max(j.fecha_final), interval 1 day) as fecha_inicio_actual
				from detalle_jornada dj
				inner join obra o ON o.id = dj.obra_id
				inner join jornada j ON j.id = dj.jornada_id
				where o.id = $obra_id and dj.jornada_id is not null and dj.status = ".RECORD_STATUS_ACTIVE;
			$this->data["fecha_inicio_actual"] =  $this->db->query($sql_)->result_array();

		}

		$sql = 
			"select 
				o.*
			from obra o
			where o.id = $obra_id and o.status = ".RECORD_STATUS_ACTIVE;
		$this->data["rs_"] =  $this->db->query($sql)->result_array();

		$personal = gw("personal", ["status" => RECORD_STATUS_ACTIVE])->result_array();
		$personalData = [];
		foreach ($personal as $person) {
			$personalData[] = [
				'id' => $person['id'],
				'text' => $person['nombre'].' '.$person['apellidos'],
				'especialidad' => gw("especialidad", ["id" => $person['especialidad_id']])->row()->nombre,
				'area' => gw("area", ["id" => $person['area_id']])->row()->nombre
			];
		}	
		$this->data["personales"] = $personalData;  
		
		$this->load->view($this->name() . "/create_jornadas", $this->data);
	}

	public function jornadas_personal_save() {
		$ret["success"] = false;

		date_default_timezone_set('America/Lima');
		$jornada_id = $this->input->post("jornada_id");
		#	die(var_dump($jornada_id));

		$descripcion = $this->input->post("descripcion");
		$status = $this->input->post("status");
		$obra_id = $this->input->post("obra_id");
		$fecha_inicio = $this->input->post("fecha_inicio");
		$fecha_final = $this->input->post("fecha_final");

		$tm = date('Y-m-d H:i:s', time());
		$personalCount = $this->input->post("personal_count");
		$items = $_POST["items"];

		$data_jornada = [
			"descripcion" 			=> $descripcion,
			"fecha_inicio"		 	=> $fecha_inicio,
			"fecha_final" 			=> $fecha_final,
			"created_user_id" 		=> $this->session->userdata("userID"),
			"created_datetime" 		=> $tm,
			"status" 				=> RECORD_STATUS_ACTIVE,
		];

		$detalle_jornada_data = [];
		$detalle_jornada_ids = []; 
	
		// Generar el array de días de la semana en español
		$diasSemana = [];
		$currentDate = new DateTime($fecha_inicio);
		$endDate = new DateTime($fecha_final);
		$esSpanish = new IntlDateFormatter('es', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'EEEE');
		while ($currentDate <= $endDate) {
			$diasSemana[] = mb_convert_case($esSpanish->format($currentDate), MB_CASE_UPPER, "UTF-8");
			$currentDate->modify('+1 day');
		}

		try {
			$this->db->trans_start();

			if ($jornada_id > 0) {

				$data_jornada["updated_datetime"] = $tm;					
                $data_jornada["updated_user_id"] = $this->session->userdata("userID");
				$this->Constant_model->updateData("jornada", $data_jornada, $jornada_id);

				$this->Constant_model->deleteByColumn("detalle_jornada", "jornada_id", $jornada_id);

				for ($i = 0; $i < $personalCount; $i++) {
					$personal_id = $items["personal_id"][$i];
					
					$detalle_jornada_data = [
						"jornada_id"        => $jornada_id,
						"personal_id"       => $personal_id,
						"obra_id"           => $obra_id,
						"created_user_id"   => $this->session->userdata("userID"),
						"created_datetime"  => $tm,
						"status"            => RECORD_STATUS_ACTIVE,
					];
	
					$detalle_jornada_id = $this->Constant_model->insertDataReturnLastId("detalle_jornada", $detalle_jornada_data);
	
					// Guardar el ID en el array de IDs
					$detalle_jornada_ids[] = $detalle_jornada_id;
				}
	
				// Insertar los registros en la tabla "asistencia" para cada día de la semana
				foreach ($detalle_jornada_ids as $detalle_jornada_id) {
					foreach ($diasSemana as $dia) {
						$data_asistencia = [
							"detalle_jornada_id"    => $detalle_jornada_id,
							"dia"                   => $dia,
							"estado"                => 0,
							"created_user_id"       => $this->session->userdata("userID"),
							"created_datetime"      => $tm,
							"status"                => RECORD_STATUS_ACTIVE,
						];
						$asistencia_id = $this->Constant_model->insertDataReturnLastId("asistencia", $data_asistencia);
					}
				}


			} else {
			
				$jornada_id = $this->Constant_model->insertDataReturnLastId("jornada", $data_jornada);
	
				for ($i = 0; $i < $personalCount; $i++) {
					$personal_id = $items["personal_id"][$i];
					
					$detalle_jornada_data = [
						"jornada_id"        => $jornada_id,
						"personal_id"       => $personal_id,
						"obra_id"           => $obra_id,
						"created_user_id"   => $this->session->userdata("userID"),
						"created_datetime"  => $tm,
						"status"            => RECORD_STATUS_ACTIVE,
					];
	
					$detalle_jornada_id = $this->Constant_model->insertDataReturnLastId("detalle_jornada", $detalle_jornada_data);
	
					// Guardar el ID en el array de IDs
					$detalle_jornada_ids[] = $detalle_jornada_id;
				}
	
				// Insertar los registros en la tabla "asistencia" para cada día de la semana
				foreach ($detalle_jornada_ids as $detalle_jornada_id) {
					foreach ($diasSemana as $dia) {
						$data_asistencia = [
							"detalle_jornada_id"    => $detalle_jornada_id,
							"dia"                   => $dia,
							"estado"                => 0,
							"created_user_id"       => $this->session->userdata("userID"),
							"created_datetime"      => $tm,
							"status"                => RECORD_STATUS_ACTIVE,
						];
						$asistencia_id = $this->Constant_model->insertDataReturnLastId("asistencia", $data_asistencia);
					}
				}
			}

			$this->db->trans_complete();
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	public function jornadas_personal_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		$obra_id = $this->input->get("obra_id");
		
		$sql = 
			"select 
				dj.*
			from detalle_jornada dj
			inner join obra o on o.id = dj.obra_id
			where dj.jornada_id = $id";
		$data =  $this->db->query($sql)->result_array();
		$jornada_id = $data[0]["jornada_id"];

		$sql_stp =
			"select 
				format(sum(dj.sueldo_personal_semana), 2) as sueldo_personal_semana
			from detalle_jornada dj
			where dj.jornada_id = $id and dj.status = ".RECORD_STATUS_ACTIVE;
		$data_ =  $this->db->query($sql_stp)->result_array();
		$sueldo_personal_semana = $data_[0]["sueldo_personal_semana"];

		$costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;

		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("detalle_jornada", "jornada_id", $jornada_id)))
				throw new Exception("REGISTRO NO ENCONTRADO");
			$this->Constant_model->updateDataJornada("detalle_jornada", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $jornada_id);
			$this->Constant_model->updateData("obra", ["costo_obra" => ($costo_obra - $sueldo_personal_semana), "updated_datetime" => date('Y-m-d H:i:s', time()) ], $obra_id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	public function asistencia() {
		$ret["success"] = false;
		$jornada_id = $this->input->get("astID");
	
		$sql =
			"select
				dj.*
			from detalle_jornada dj
			where dj.jornada_id = ? AND dj.status = " . RECORD_STATUS_ACTIVE;
		$query = $this->db->query($sql, [$jornada_id]);
		$this->data["records"] = $query->result_array();

		$sql_ = 
			"select 
				format(sum(dj.sueldo_personal_semana), 2) as sueldo_personal_semana
			from jornada j 
			inner join detalle_jornada dj on dj.jornada_id = j.id
			where j.id = ? and j.status = ". RECORD_STATUS_ACTIVE;
		$query_ = $this->db->query($sql_, [$jornada_id]);
		$this->data["res"] = $query_->row_array();
	
		$this->data["page_title"] = "Jornadas";
		$this->data["jornada_id"] = $jornada_id;
		$this->load->view($this->name() . "/list_asistencias", $this->data);
	}	

	public function asistencia_list() {
		$requestData = $this->input->post();
		$jornada_id = $this->input->get("jornada_id");
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'personal',
			2 => 'area',
			3 => 'especialidad',
			4 => 'direccion',
			5 => 'banco',
			6 => 'num_cuenta',
			7 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select
					dj.id,
					concat(p.nombre,' ',p.apellidos) as personal,
					a.nombre as area,
					e.nombre as especialidad,
					p.direccion,
					p.banco,
					p.num_cuenta,
					k1.valor as status
				from detalle_jornada dj
				inner join personal p on p.id = dj.personal_id
				inner join area a on a.id = p.area_id
				inner join especialidad e on e.id = p.especialidad_id
				inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = dj.status
				where dj.jornada_id = $jornada_id and dj.status = ".RECORD_STATUS_ACTIVE;
		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( concat(p.nombre,' ',p.apellidos) LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR p.banco LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR p.banco LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR p.num_cuenta LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR e.nombre LIKE '".$requestData['search']['value']."%') ";
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

	public function asistencia_create() {
		$data = [];
		$detalle_jornada_id = $this->input->post("id");

		$sql =
			"select
				a.id,
				a.detalle_jornada_id,
				a.dia,
				a.estado,
				a.horas_extras
			from asistencia a
			inner join detalle_jornada dj ON dj.id = a.detalle_jornada_id
			where a.detalle_jornada_id = $detalle_jornada_id AND dj.status = " . RECORD_STATUS_ACTIVE;

		$this->data["asistencias"] = $this->db->query($sql)->result_array();

		//die(var_dump($this->data["asistencias"]));

		$this->load->view($this->name() . "/create_asistencia", $this->data);
	}

	public function asistencia_save() {
		$ret["success"] = false;
	
		date_default_timezone_set('America/Lima');
		$t_asistencia = $this->input->post("t_asistencia");
		$t_horas_extras = $this->input->post("t_horas_extras");
		$sueldo_fijo = $this->input->post("sueldo_fijo");
		$sueldo_horas_extras = $this->input->post("sueldo_horas_extras");
		$sueldo_total = $this->input->post("sueldo_total");
		$idList = $this->input->post("id[]");
	
		$tm = date('Y-m-d H:i:s', time());

		$detalle_jornada_id = $this->input->post("detalle_jornada_id");
		$sueldo_horas_extras_jornada = $this->input->post("sueldo_horas_extras_jornada");		
		$sueldo_personal_semana_jornada = $this->input->post("sueldo_personal_semana_jornada");		
		$sueldo_total_asistencias_jornada = $this->input->post("sueldo_total_asistencias_jornada");		
		$sueldo_total_horas_extras_jornada = $this->input->post("sueldo_total_horas_extras_jornada");

		$obra_id = $this->input->post("obra_id");
		$costo_obra = $this->input->post("costo_obra");
	
		$data_asistencia = [
			"sueldo_fijo" 				=> $sueldo_fijo,
			"sueldo_horas_extras" 		=> $sueldo_horas_extras,
			"sueldo_total"				=> $sueldo_total,
			"t_asistencia" 				=> $t_asistencia,
			"t_horas_extras"			=> $t_horas_extras,
		];
	
		try {
			$this->db->trans_start();						

			if($sueldo_horas_extras)
				$data_jornada["sueldo_horas_extras"] = $sueldo_horas_extras_jornada + $sueldo_horas_extras;

			if($t_asistencia)
				$data_jornada["total_asistencias"] = $sueldo_total_asistencias_jornada + $t_asistencia;
				
			if($t_horas_extras)
				$data_jornada["total_horas_extras"] = $sueldo_total_horas_extras_jornada + $t_horas_extras;

			if($sueldo_total) {
				$data_jornada["sueldo_personal_semana"] = $sueldo_personal_semana_jornada + $sueldo_total;
				$data_obra["costo_obra"] = $costo_obra + $sueldo_total;
			}

			$this->db->where("id", $detalle_jornada_id);
			$this->db->update("detalle_jornada", $data_jornada);

			$this->db->where("id", $obra_id);
			$this->db->update("obra", $data_obra);
	
			// Iterar sobre la lista de IDs y actualizar los registros correspondientes
			foreach ($idList as $index => $id) {
				$estado = isset($this->input->post("estado")[$index]) ? 1 : 0; // Obtener el estado correspondiente al ID
				$horas_extras = isset($this->input->post("horas_extras")[$index]) ? $this->input->post("horas_extras")[$index] : NULL; // Obtener las horas extras correspondientes al ID
	
				$data_asistencia["estado"] = $estado;
				$data_asistencia["horas_extras"] = $horas_extras;
	
				// Actualizar el registro de asistencia por su ID
				$this->db->where("id", $id);
				$this->db->update("asistencia", $data_asistencia);
			}
	
			$this->db->trans_complete();
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
	
		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	public function asistencia_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");

		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("detalle_jornada", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("detalle_jornada", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}	

	public function upload_excel() {
		$obra_id = $this->input->get("num");
		$ret["success"] = false;

        require_once APPPATH.'third_party/PHPExcel.php';
        require_once APPPATH.'third_party/PHPExcel/IOFactory.php';

        try {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $uploadedFile = $_FILES['excelFile']['tmp_name'];
            $objPHPExcel = $objReader->load($uploadedFile);
            $uploadPath = './assets/upload/jornInsert/';
            $uploadedFileName = 'excel_jornada_'.date('dmYHis').'.xlsx';
            $uploadedFilePath = $uploadPath . $uploadedFileName;
            move_uploaded_file($uploadedFile, $uploadedFilePath);
            $objReader->load($uploadedFilePath);

			$worksheet = $objPHPExcel->getActiveSheet();
			$j_name = $worksheet->getCell('A7')->getValue();
			$jornada_nombre = trim(mb_strtoupper($j_name));
			// Extraer las fechas
			$eraser_per = str_replace('PERIODO: ', '', $jornada_nombre);
			$fechas = explode(" - ", $eraser_per);
			$jornada_fecha_inicio = $fechas[0];
			$jornada_fecha_final = $fechas[1];

			$jornada_id = $this->Constant_model->insertDataReturnLastId('jornada', [
				"descripcion" 			=> $jornada_nombre,
				"fecha_inicio"		 	=> $jornada_fecha_inicio,
				"fecha_final" 			=> $jornada_fecha_final,
				"created_user_id" 		=> $this->session->userdata("userID"),
				"created_datetime" 		=> $tm,
				"status" 				=> RECORD_STATUS_ACTIVE,
			]);			
	
			// Generar el array de días de la semana en español
			$diasSemana = [];
			$currentDate = new DateTime($jornada_fecha_inicio);
			$endDate = new DateTime($jornada_fecha_final);
			$esSpanish = new IntlDateFormatter('es', IntlDateFormatter::NONE, IntlDateFormatter::NONE, null, null, 'EEEE');
			while ($currentDate <= $endDate) {
				$diasSemana[] = mb_convert_case($esSpanish->format($currentDate), MB_CASE_UPPER, "UTF-8");
				$currentDate->modify('+1 day');
			}

			$t_asistencia = count($diasSemana);

            foreach ($objPHPExcel->getAllSheets() as $objWorksheet) {
                if ($objWorksheet->getSheetState() == "hidden") continue;

                $highestRow = $objWorksheet->getHighestRow();   
                $highestColumn = $objWorksheet->getHighestColumn();

                for ($row = 9; $row <= $highestRow; ++$row) {
                    $rows = array();
                    for ($col = 'A'; $col <= "G"; ++$col) {
                        $cell_val = $objWorksheet->getCell($col . $row)->getValue();
                        if (substr($cell_val, 0, 1) == "=") 
                            $cell_val = $objWorksheet->getCell($col . $row)->getOldCalculatedValue();
                        array_push($rows, $cell_val);
                    }

                    if (strlen(trim($rows[1])) == 0 || empty($row)) continue;

                    date_default_timezone_set('America/Lima');
                    $tm = date('Y-m-d H:i:s', time());
					$personal_id = null;
                    $personal_name = trim(mb_strtoupper($rows[0]));
                    $personal_apellido = trim(mb_strtoupper($rows[1]));
                    $personal_dni = trim($rows[2]);
					$especialidad_id = null;
                    $personal_especialidad = explode('>', trim(mb_strtoupper($rows[3])))[1];
					$detalle_jornada_id = null;
                    $asistencia_dia = trim(mb_strtoupper($rows[5]));
    
                    if ($tmp = gw("especialidad", [ "UPPER(TRIM(nombre))" => $personal_especialidad  ])->row())
                        $especialidad_id = $tmp->id;
                    else
                    $especialidad_id = $this->Constant_model->insertDataReturnLastId('especialidad', [
                        'nombre' => $personal_especialidad,
                        'created_user_id' => $this->session->userdata("userID"),
                        'created_datetime' => $tm,
                        "status" => RECORD_STATUS_ACTIVE,
                    ]);

					$ins_data = array(
						'especialidad_id' => $especialidad_id,
						'nombre' => $personal_name,
						'apellidos' => $personal_apellido,
						'dni' => $personal_dni,
						'created_user_id' => $this->session->userdata("userID"),
						'created_datetime' => $tm,
						"status" => RECORD_STATUS_ACTIVE,
                    );

					if ($tmp = gw("personal", [ "UPPER(TRIM(dni))" => $personal_dni  ])->row()){
                        $personal_id = $tmp->id;
                        $this->Constant_model->updateData("personal", $ins_data, $personal_id);
						$sueldo_personal = gw("personal", ["id" => $personal_id])->row()->sueldo;
						$sueldo_fijo = $sueldo_personal * $t_asistencia;
						$sueldo_total = $sueldo_fijo;

						if(!gw("detalle_jornada", ["jornada_id" => $jornada_id, "personal_id" => $personal_id])->row()){
							$detalle_jornada_data = [
								"jornada_id"        		=> $jornada_id,
								"personal_id"       		=> $personal_id,
								"obra_id"           		=> $obra_id,
								# "sueldo_horas_extras" 		=> $sueldo_horas_extras,
								"total_asistencias" 		=> $t_asistencia,
								# "total_horas_extras" 			=> $total_horas_extras,
								"sueldo_personal_semana"	=> $sueldo_total,
								"created_user_id"   		=> $this->session->userdata("userID"),
								"created_datetime"  		=> $tm,
								"status"            		=> RECORD_STATUS_ACTIVE,
							];	

							// Insertar el detalle de jornada en la tabla "detalle_jornada"
							$this->db->insert("detalle_jornada", $detalle_jornada_data);		
							// Obtener el ID del registro insertado en "detalle_jornada"
							$detalle_jornada_id = $this->db->insert_id();

							foreach ($diasSemana as $dia) {
								$data_asistencia = [
									"detalle_jornada_id"    	=> $detalle_jornada_id,
									"dia"                   	=> $dia,
									"estado"                	=> 1,
									"sueldo_fijo" 				=> $sueldo_fijo,
									# "sueldo_horas_extras" 			=> $sueldo_horas_extras,
									"sueldo_total"				=> $sueldo_total,
									"t_asistencia" 				=> $t_asistencia,
									# "t_horas_extras"				=> $t_horas_extras,
									"created_user_id"       	=> $this->session->userdata("userID"),
									"created_datetime"      	=> $tm,
									"status"                	=> RECORD_STATUS_ACTIVE,
								];
								$this->db->insert("asistencia", $data_asistencia);
							}
							// Update Costo_Obro
								$costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;
								$data_obra_co["costo_obra"] = $costo_obra + $sueldo_total;
								$this->Constant_model->updateData("obra", $data_obra_co, $obra_id);
							// End Update Costo_Obro
						}
                    } else {
                        $personal_id = $this->Constant_model->insertDataReturnLastId('personal', $ins_data);
						$sueldo_personal = gw("personal", ["id" => $personal_id])->row()->sueldo;
						$sueldo_fijo = $sueldo_personal * $t_asistencia;
						$sueldo_total = $sueldo_fijo;

						$detalle_jornada_data = [
							"jornada_id"        		=> $jornada_id,
							"personal_id"       		=> $personal_id,
							"obra_id"           		=> $obra_id,
							# "sueldo_horas_extras" 		=> $sueldo_horas_extras,
							"total_asistencias" 		=> $t_asistencia,
							# "total_horas_extras" 			=> $total_horas_extras,
							"sueldo_personal_semana"	=> $sueldo_total,
							"created_user_id"   		=> $this->session->userdata("userID"),
							"created_datetime"  		=> $tm,
							"status"           			=> RECORD_STATUS_ACTIVE,
						];

						// Insertar el detalle de jornada en la tabla "detalle_jornada"
						$this->db->insert("detalle_jornada", $detalle_jornada_data);		
						// Obtener el ID del registro insertado en "detalle_jornada"
						$detalle_jornada_id = $this->db->insert_id();

						foreach ($diasSemana as $dia) {
							$data_asistencia = [
								"detalle_jornada_id"    	=> $detalle_jornada_id,
								"dia"                   	=> $dia,
								"estado"                	=> 1,
								"sueldo_fijo" 				=> $sueldo_fijo,
								#"sueldo_horas_extras" 		=> $sueldo_horas_extras,
								"sueldo_total"				=> $sueldo_total,
								"t_asistencia" 				=> $t_asistencia,
								#"t_horas_extras"			=> $t_horas_extras,
								"created_user_id"       	=> $this->session->userdata("userID"),
								"created_datetime"      	=> $tm,
								"status"                	=> RECORD_STATUS_ACTIVE,
							];
							$this->db->insert("asistencia", $data_asistencia);
						}
						// Update Costo_Obro
							$costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;
							$data_obra_co["costo_obra"] = $costo_obra + $sueldo_total;
							$this->Constant_model->updateData("obra", $data_obra_co, $obra_id);
						// End Update Costo_Obro
					}
                }
			}

            $ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);        
        redirect(base_url()."jornadas/list_jornadas?num=$obra_id");
    }
}
