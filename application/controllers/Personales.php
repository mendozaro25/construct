<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Personales extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Personales";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function personal() {
        $this->data["uri"] = [
            "title"         => "Personales",
            "list"          => base_url().$this->name()."/personal_list",
            "create"        => base_url().$this->name()."/personal_create",
            "save"          => base_url().$this->name()."/personal_save",
            "remove"        => base_url().$this->name()."/personal_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function personal_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'personal',
			2 => 'dni',
			3 => 'telefono',
			4 => 'direccion',
			5 => 'especialidad',
			6 => 'area',
			7 => 'banco',
			8 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    p.id, 
                    concat(p.nombre,' ',p.apellidos) as personal, 
                    p.dni, 
                    p.telefono, 
                    p.direccion,
                    e.nombre as especialidad,
                    a.nombre as area,
                    p.banco,
                    k1.valor as status 
                from personal p
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = p.status
                inner join especialidad e on e.id = p.especialidad_id
                inner join area a on a.id = p.area_id
                where 1 = 1";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( p.id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR p.dni LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR e.nombre LIKE '".$requestData['search']['value']."%'";
                $sql.=" OR a.nombre LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR concat(p.nombre,' ',p.apellidos) LIKE '".$requestData['search']['value']."%') ";
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

    public function personal_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("personal", ["id" => $id ])->row_array());
        
        $noArray = [];
        if ($tmp = $this->db->query("select distinct banco from personal")->result_array()) {
            foreach ($tmp as $i) {
                if (strlen(trim($i["banco"])) >0 && !in_array(trim($i["banco"]), $noArray))
                    $noArray = array_merge($noArray, [$i["banco"]]);
            }
        }
		$this->data = array_merge($this->data, ["noArray" => $noArray ]);
		
        $this->data["especialidades"] = gw("especialidad", ["status" => RECORD_STATUS_ACTIVE ])->result_array();    
        $this->data["areas"] = gw("area", ["status" => RECORD_STATUS_ACTIVE ])->result_array();		
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function personal_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$especialidad_id = $this->input->post("especialidad_id");
		$area_id = $this->input->post("area_id");
		$nombre = $this->input->post("nombre");
		$apellidos = $this->input->post("apellidos");
		$dni = $this->input->post("dni");
		$direccion = $this->input->post("direccion");
		$telefono = $this->input->post("telefono");
        $sueldo = $this->input->post("sueldo");
		$banco = $this->input->post("banco");
		$num_cuenta = $this->input->post("num_cuenta");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());


        $data = [
            "especialidad_id"			=> $especialidad_id,
            "area_id"			        => $area_id,
            "nombre"			        => $nombre,
            "apellidos"			        => $apellidos,
            "dni"			            => $dni,
            "direccion"			        => $direccion,
            "telefono"			        => $telefono,
            "sueldo"                    => $sueldo,
            "banco"			            => $banco,
            "num_cuenta"			    => $num_cuenta,
            "created_user_id"			=> $this->session->userdata("userID"),
            "created_datetime"			=> $tm,
            "status"			        => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {

                if($sueldo){
                
                    $sql = 
                        "select 
                            dj.id as id_detalle_jornada,
                            a.id as id_asistencia
                        from detalle_jornada dj 
                        inner join asistencia a on a.detalle_jornada_id = dj.id
                        where dj.personal_id = $id and dj.status = ".RECORD_STATUS_ACTIVE;        
                    $records = $this->db->query($sql)->result_array();
    
                    $array_ids_dj = [];
                    $array_ids_as = [];
    
                    foreach ($records as $row) {
                        $array_ids_dj[] = $row["id_detalle_jornada"];
                        $array_ids_as[] = $row["id_asistencia"];
                    }

                    foreach ($array_ids_dj as $detalle_jornada_id) { 
                        $sueldo_personal_old = gw("personal", ["id" => $id])->row()->sueldo;    

                        $obra_id = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->obra_id;   
                        $jornada_id = gw("detalle_jornada", ["id" => $detalle_jornada_id])->row()->jornada_id;   

                        $costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;
                        $sql_obra = 
                            "select 
                                sum(dj.sueldo_personal_semana) as sueldo_personal_semana
                            from detalle_jornada dj
                            where dj.obra_id = ? and dj.status = ".RECORD_STATUS_ACTIVE;
                        $rs = $this->db->query($sql_obra, [$obra_id])->row_array();

                       
                        if ($sueldo != $sueldo_personal_old) {
                            $t_asistencia = gw("asistencia", ["detalle_jornada_id" => $detalle_jornada_id])->row()->t_asistencia;
                            $data_dj["sueldo_fijo"] = $sueldo;
                            $data_dj["sueldo_personal_semana"] = $sueldo * $t_asistencia;
                            $data_dj["total_asistencias"] = $t_asistencia;
                            $data_dj["updated_user_id"] = $this->session->userdata("userID"); 
                            $data_dj["updated_datetime"] = $tm;
                            $this->Constant_model->updateData("detalle_jornada", $data_dj, $detalle_jornada_id);
                            if($sueldo > $sueldo_personal_old){
                                $data_ob["costo_obra"] = $rs_compra["importe_total_compra"] + $rs["sueldo_personal_semana"] + $diff_sueldo;
                            } else {
                                $data_ob["costo_obra"] = $rs_compra["importe_total_compra"] + $rs["sueldo_personal_semana"] - $diff_sueldo;
                            }
                            $this->Constant_model->updateData("obra", $data_ob, $obra_id);
                            foreach ($array_ids_as as $asistencia_id) {
                                $t_asistencia = gw("asistencia", ["id" => $asistencia_id])->row()->t_asistencia;
                                # $sueldo_total_old = gw("asistencia", ["id" => $asistencia_id])->row()->sueldo_total;
                                $data_as["sueldo_fijo"] = $sueldo;
                                $data_as["sueldo_total"] = $sueldo * $t_asistencia;
                                $data_as["updated_user_id"] = $this->session->userdata("userID");
                                $data_as["updated_datetime"] = $tm;    
                                $this->Constant_model->updateData("asistencia", $data_as, $asistencia_id);
                            }
                            $this->Constant_model->updateData("jornada", ["costo_jornada" => $data_dj["sueldo_personal_semana"], "updated_datetime" => date('Y-m-d H:i:s', time()) ], $jornada_id);
                        }  
                        if ($sueldo_personal_old == 0) {
                            $t_asistencia = gw("asistencia", ["detalle_jornada_id" => $detalle_jornada_id])->row()->t_asistencia;
                            $data_dj["sueldo_fijo"] = $sueldo;
                            $data_dj["sueldo_personal_semana"] = $sueldo * $t_asistencia;
                            $data_dj["total_asistencias"] = $t_asistencia;
                            $data_dj["updated_user_id"] = $this->session->userdata("userID");
                            $data_dj["updated_datetime"] = $tm;
                            $this->Constant_model->updateData("detalle_jornada", $data_dj, $detalle_jornada_id);
                            $data_ob["costo_obra"] = $costo_obra + $data_dj["sueldo_personal_semana"];
                            $this->Constant_model->updateData("obra", $data_ob, $obra_id);
                            $this->Constant_model->updateData("jornada", ["costo_jornada" => $data_dj["sueldo_personal_semana"], "updated_datetime" => date('Y-m-d H:i:s', time()) ], $jornada_id);
                            foreach ($array_ids_as as $asistencia_id) {
                                $t_asistencia = gw("asistencia", ["id" => $asistencia_id])->row()->t_asistencia;
                                # $sueldo_total_old = gw("asistencia", ["id" => $asistencia_id])->row()->sueldo_total;
                                $data_as["sueldo_fijo"] = $sueldo;
                                $data_as["sueldo_total"] = $sueldo * $t_asistencia;
                                $data_as["updated_user_id"] = $this->session->userdata("userID");
                                $data_as["updated_datetime"] = $tm;    
                                $this->Constant_model->updateData("asistencia", $data_as, $asistencia_id);
                            }
                        }   
                        if ($sueldo_personal_old == $sueldo){
                            $data_ob["costo_obra"] = $costo_obra;
                            $this->Constant_model->updateData("obra", $data_ob, $obra_id);
                        }
                    }    
                    #die(var_dump($array_ids_as));
                }                
					
                $data["updated_user_id"] = $this->session->userdata("userID");
                $data["updated_datetime"] = $tm;

				$this->Constant_model->updateData("personal", $data, $id);

            } else {

				$personal_id = $this->Constant_model->insertDataReturnLastId("personal", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function personal_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("personal", "id", $id)))
				throw new Exception("Registro no encontrado");
			$this->Constant_model->updateData("personal", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	public function upload_excel() {
		$ret["success"] = false;

        require_once APPPATH.'third_party/PHPExcel.php';
        require_once APPPATH.'third_party/PHPExcel/IOFactory.php';

        try {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $uploadedFile = $_FILES['excelFile']['tmp_name'];
            $objPHPExcel = $objReader->load($uploadedFile);
            $uploadPath = './assets/upload/personInsert/';
            $uploadedFileName = 'excel_personal_'.date('dmYHis').'.xlsx';
            $uploadedFilePath = $uploadPath . $uploadedFileName;
            move_uploaded_file($uploadedFile, $uploadedFilePath);
            $objReader->load($uploadedFilePath);

            foreach ($objPHPExcel->getAllSheets() as $objWorksheet) {
                if ($objWorksheet->getSheetState() == "hidden") continue;

                $highestRow = $objWorksheet->getHighestRow();   
                $highestColumn = $objWorksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $rows = array();
                    for ($col = 'A'; $col <= "J"; ++$col) {
                        $cell_val = $objWorksheet->getCell($col . $row)->getValue();
                        if (substr($cell_val, 0, 1) == "=") 
                            $cell_val = $objWorksheet->getCell($col . $row)->getOldCalculatedValue();
                        array_push($rows, $cell_val);
                    }

                    if (strlen(trim($rows[1])) == 0 || empty($row)) continue;

                    date_default_timezone_set('America/Lima');
                    $tm = date('Y-m-d H:i:s', time());
                    $especialidad_id = null;
                    $especialidad_name = trim(mb_strtoupper($rows[0]));		
                    $area_id = null;	
                    $area_name = trim(mb_strtoupper($rows[1]));
    
                    if ($tmp = gw("especialidad", [ "UPPER(TRIM(nombre))" => $especialidad_name  ])->row())
                        $especialidad_id = $tmp->id;
                    else
                    $especialidad_id = $this->Constant_model->insertDataReturnLastId('especialidad', [
                        'nombre' => $especialidad_name,
                        'created_user_id' => $this->session->userdata("userID"),
                        'created_datetime' => $tm,
                        "status" => RECORD_STATUS_ACTIVE,
                    ]);
    
                    if ($tmp = gw("area", [ "UPPER(TRIM(nombre))" => $area_name  ])->row())
                        $area_id = $tmp->id;
                    else
                    $area_id = $this->Constant_model->insertDataReturnLastId('area', [
                        'nombre' => $area_name,
                        'created_user_id' => $this->session->userdata("userID"),
                        'created_datetime' => $tm,
                        "status" => RECORD_STATUS_ACTIVE,
                    ]);

                    $nombre = trim(mb_strtoupper($rows[2]));
                    $apellidos = trim(mb_strtoupper($rows[3]));
                    $dni = trim(mb_strtoupper($rows[4]));
                    $direccion = trim(mb_strtoupper($rows[5]));
                    $telefono = trim(mb_strtoupper($rows[6]));
                    $sueldo = floatval($rows[7]);
                    $banco = trim(mb_strtoupper($rows[8]));
                    $num_cuenta = trim(mb_strtoupper($rows[9]));

                    $ins_data = array(
                        'especialidad_id' => $especialidad_id,
                        'area_id' => $area_id,
                        'nombre' => $nombre,
                        'apellidos' => $apellidos,
                        'dni' =>  $dni,
                        'direccion' =>  $direccion,
                        'telefono' => $telefono,
                        'banco' => $banco,
                        'sueldo' => $sueldo,
                        'num_cuenta' => $num_cuenta,
                        'created_user_id' => $this->session->userdata("userID"),
                        'created_datetime' => $tm,
                        "status" => RECORD_STATUS_ACTIVE,
                    );

                    if ($tmp = gw("personal", [ "UPPER(TRIM(dni))" => $dni  ])->row()){
                        $personal_id = $tmp->id;
                        $this->Constant_model->updateData("personal", $ins_data, $personal_id);
                    } else {
                        $personal_id = $this->Constant_model->insertDataReturnLastId('personal', $ins_data);
                    }                    
                }
            }
            $ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);        
        redirect(base_url()."personales/personal");
    }
}
