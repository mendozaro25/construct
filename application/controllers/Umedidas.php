<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Umedidas extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Unidades Medidas";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function umedida() {
        $this->data["uri"] = [
            "title"         => "Unidades Medidas",
            "list"          => base_url().$this->name()."/umedida_list",
            "create"        => base_url().$this->name()."/umedida_create",
            "save"          => base_url().$this->name()."/umedida_save",
            "remove"        => base_url().$this->name()."/umedida_delete",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function umedida_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'simbolo',
			2 => 'nombre',
			3 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    u.id, 
					u.simbolo,
					u.nombre,
                    k1.valor as status 
                from unidad_medida u
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = u.status
                where 1 = 1";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( u.id LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR u.simbolo LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR u.nombre LIKE '".$requestData['search']['value']."%') ";
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

    public function umedida_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("unidad_medida", ["id" => $id ])->row_array());

		$this->load->view($this->name() . "/create", $this->data);
	}

    public function umedida_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$simbolo = $this->input->post("simbolo");
		$nombre = $this->input->post("nombre");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());

        $data = [
            "simbolo"			    	=> $simbolo,
            "nombre"			        => $nombre,
            "created_user_id"			=> $this->session->userdata("userID"),
            "created_datetime"			=> $tm,
            "status"			        => $status,
        ];

		try {

			$this->db->trans_start();

			if ($id > 0) {
					
                $data["updated_user_id"] = $this->session->userdata("userID");
                $data["updated_datetime"] = $tm;

				$this->Constant_model->updateData("unidad_medida", $data, $id);

            } else {

				$personal_id = $this->Constant_model->insertDataReturnLastId("unidad_medida", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function umedida_delete() {
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

	public function upload_excel() {
		$ret["success"] = false;

        require_once APPPATH.'third_party/PHPExcel.php';
        require_once APPPATH.'third_party/PHPExcel/IOFactory.php';

        try {
            $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $uploadedFile = $_FILES['excelFile']['tmp_name'];
            $objPHPExcel = $objReader->load($uploadedFile);
            $uploadPath = './assets/upload/unimedInsert/';
            $uploadedFileName = 'excel_undmedida_'.date('dmYHis').'.xlsx';
            $uploadedFilePath = $uploadPath . $uploadedFileName;
            move_uploaded_file($uploadedFile, $uploadedFilePath);
            $objReader->load($uploadedFilePath);

            foreach ($objPHPExcel->getAllSheets() as $objWorksheet) {
                if ($objWorksheet->getSheetState() == "hidden") continue;

                $highestRow = $objWorksheet->getHighestRow();   
                $highestColumn = $objWorksheet->getHighestColumn();

                for ($row = 2; $row <= $highestRow; ++$row) {
                    $rows = array();
                    for ($col = 'A'; $col <= "B"; ++$col) {
                        $cell_val = $objWorksheet->getCell($col . $row)->getValue();
                        if (substr($cell_val, 0, 1) == "=") 
                            $cell_val = $objWorksheet->getCell($col . $row)->getOldCalculatedValue();
                        array_push($rows, $cell_val);
                    }

                    if (strlen(trim($rows[1])) == 0 || empty($row)) continue;

                    date_default_timezone_set('America/Lima');
                    $tm = date('Y-m-d H:i:s', time());
                    $unidad_medida_id = null;
                    $simbolo = trim(mb_strtoupper($rows[0]));
                    $nombre = trim(mb_strtoupper($rows[1]));

                    $ins_data = array(
                        'simbolo' 				=> $simbolo,
                        'nombre' 				=> $nombre,
                        'created_user_id'		=> $this->session->userdata("userID"),
                        'created_datetime' 		=> $tm,
                        "status" 				=> RECORD_STATUS_ACTIVE,
                    );

                    if ($tmp = gw("unidad_medida", [ "UPPER(TRIM(simbolo))" => $simbolo  ])->row()){
                        $unidad_medida_id = $tmp->id;
                        $this->Constant_model->updateData("unidad_medida", $ins_data, $unidad_medida_id);
                    } else {
                        $unidad_medida_id = $this->Constant_model->insertDataReturnLastId('unidad_medida', $ins_data);
                    }                    
                }
            }
            $ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);        
        redirect(base_url()."umedidas/umedida");
    }
}
