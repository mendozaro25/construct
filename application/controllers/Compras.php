<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Compras extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Compras";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function compra() {
        $this->data["uri"] = [
            "title"         => "Compra",
            "list"          => base_url().$this->name()."/compra_list",
            "create"        => base_url().$this->name()."/compra_create",
            "save"          => base_url().$this->name()."/compra_save",
            "remove"        => base_url().$this->name()."/compra_delete",
            "compras"       => base_url().$this->name()."/detalle_compra",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

	// Obra 

    public function compra_list() {
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
					dc.obra_id as id,
					o.nombre as obra,
					format(o.costo_obra, 2) as costo_obra,
					o.fecha_inicio,
					o.fecha_final,
					k1.valor as status 
				from detalle_compra dc
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = dc.status
				inner join obra o on o.id = dc.obra_id
                where dc.proveedor_id is null and dc.producto_id is null and o.status = ".RECORD_STATUS_ACTIVE;

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

    public function compra_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("detalle_compra", ["id" => $id ])->row_array());
		
		$this->data["obras"] = gw("obra", ["status" => RECORD_STATUS_ACTIVE ])->result_array();    
		
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function compra_save() {
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

				$this->Constant_model->updateData("detalle_compra", $data, $id);

            } else {
				
				$detalle_jornada_id = $this->Constant_model->insertDataReturnLastId("detalle_compra", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	// Detalle Compras

	public function detalle_compra() {
		$ret["success"] = false;	
		$obra_id = $this->input->get("num");

		$sql = 
			"select 
				dc.id,
				dc.obra_id,
				dc.compra_id,
				o.nombre,
				o.fecha_inicio,
				o.fecha_final		
			from detalle_compra dc
			inner join obra o on o.id = dc.obra_id
			where o.id=?";
        $this->data = $this->db->query($sql, [$obra_id])->row_array();

		$sql_pg =
		"select 
			dc.producto_id as prod_reg
		from detalle_compra dc
		where dc.obra_id = $obra_id and dc.compra_id is not null and dc.status = ".RECORD_STATUS_ACTIVE."
		group by dc.producto_id";
		$result =  $this->db->query($sql_pg)->result_array();
		$this->data["prod_reg"] = count($result);

		$sql_stp =
		"select 
			format(sum(dc.total), 2) as total
		from detalle_compra dc
		inner join compra c on c.id = dc.compra_id
		where dc.obra_id = $obra_id and dc.compra_id is not null and c.status = ".RECORD_STATUS_ACTIVE;
		$this->data["result_"] =  $this->db->query($sql_stp)->row_array();

		$sql_stp =
		"select
			format(sum(dc.total),2) as materiales
		from detalle_compra dc 
		inner join compra c on c.id = dc.compra_id
		where dc.obra_id = $obra_id and c.tipo_rubro = '".CONST_COD_MATERIALES."' and c.status = ".RECORD_STATUS_ACTIVE;
		$this->data["mat"] =  $this->db->query($sql_stp)->row_array();

		$sql_stp =
		"select
			format(sum(dc.total),2) as herramientas
		from detalle_compra dc 
		inner join compra c on c.id = dc.compra_id
		where dc.obra_id = $obra_id and c.tipo_rubro = '".CONST_COD_HERRAMIENTAS."' and c.status = ".RECORD_STATUS_ACTIVE;
		$this->data["her"] =  $this->db->query($sql_stp)->row_array();

		$sql_stp =
		"select
			format(sum(dc.total),2) as equipos
		from detalle_compra dc 
		inner join compra c on c.id = dc.compra_id
		where dc.obra_id = $obra_id and c.tipo_rubro = '".CONST_COD_EQUIPOS."' and c.status = ".RECORD_STATUS_ACTIVE;
		$this->data["eqp"] =  $this->db->query($sql_stp)->row_array();

		$this->data["tipo_rubros"] = gw("constante", ["idconstante" => ID_CONST_REG_TRUBRO,])->result_array();

		$this->data["page_title"] = "Compras";			
		$this->load->view($this->name() . "/list_detcompras", $this->data);
	}

	public function detalle_compra_list() {
		$requestData = $this->input->post();
		$obra_id = $this->input->get("obra_id");
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'fecha_compra',
			2 => 'comprador',
			3 => 'proveedor',
			4 => 'tipo_comprobante',
			5 => 'serie_numero',
			6 => 'tipo_rubro',
			7 => 't_importe_total',
			8 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select distinct
					c.id,
					c.fecha_compra,
					u.name as comprador,
					pro.nombre as proveedor,
					k2.valor as tipo_comprobante,
					c.serie_numero,
					k3.valor as tipo_rubro,
					format(c.t_importe_total, 2) as t_importe_total,
					k1.valor as status
				from compra c
				inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = c.status
				inner join users u on u.id = c.comprador_id
				inner join detalle_compra dc on dc.compra_id = c.id
				inner join proveedor pro on pro.id = dc.proveedor_id
				inner join constante k2 on k2.idconstante = ".ID_CONST_REG_TCOMPRAB." and k2.codigo = c.tipo_comprobante
				inner join constante k3 on k3.idconstante = ".ID_CONST_REG_TRUBRO." and k3.codigo = c.tipo_rubro
				where 1 = 1 and dc.obra_id = $obra_id";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( c.id LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR pro.nombre LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR c.fecha_compra LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR u.name LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR k2.valor LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR k3.valor LIKE '".$requestData['search']['value']."%') ";
			}

			if ($requestData["tipo_rubro"] != null){
				$sql1.=" AND k3.codigo='". $requestData["tipo_rubro"] ."'";
			}

			$sql = $sql . $sql1;

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

	public function detalle_compra_create(){
		$data = [];
		$compra_id = $this->input->get("shopID");
		$obra_id = $this->input->get("siteID");
		
		if ($compra_id > 0){
			$sql__ = 
				"select 
					count(dc.id) as producto_count
				from detalle_compra dc
				where dc.compra_id = $compra_id and dc.status = ".RECORD_STATUS_ACTIVE;
			$this->data["pcount"] =  $this->db->query($sql__)->row_array();

			$sql = 
				"select 
					dc.*,
					p.id as proveedor_id,
					u.name as comprador,
					c.fecha_compra,
					c.tipo_comprobante,
					c.serie_numero,
					c.fecha_comprobante,
					c.tipo_rubro,
					c.status as estado_compra,
					c.t_subtotal,
					c.t_impuestos,
					c.t_importe_total
				from detalle_compra dc
				inner join compra c on c.id = dc.compra_id
				inner join proveedor p on p.id = dc.proveedor_id
				inner join producto pr on pr.id = dc.producto_id
				inner join users u on u.id = c.comprador_id
				where dc.compra_id = $compra_id and dc.status = ".RECORD_STATUS_ACTIVE;
			$this->data["rs"] =  $this->db->query($sql)->result_array();

			$sql_ = 
				"select 
					dc.id as detalle_compra_id,
					pr.id as producto_id,
					pr.nombre,
					pr.categoria,
					ud.simbolo,
					dc.cantidad,
					pr.precio_unitario,
					dc.subtotal,
					dc.igv,
					dc.total
				from detalle_compra dc
				inner join producto pr on pr.id = dc.producto_id
				inner join unidad_medida ud on ud.id = pr.unidad_medida_id
				where dc.compra_id = $compra_id";
			$this->data["data_prod_dcomp"] = $this->db->query($sql_)->result_array();

		}else {
			$sql = 
				"select
					o.*,
					dc.compra_id,
					dc.proveedor_id
				from obra o
				inner join detalle_compra dc on dc.obra_id = o.id
				where o.id = $obra_id and o.status = ".RECORD_STATUS_ACTIVE;
			$this->data["records"] =  $this->db->query($sql)->result_array();
		}
	
		$producto = gw("producto", ["status" => RECORD_STATUS_ACTIVE])->result_array();
		$productoData = [];
		foreach ($producto as $prod) {
			$productoData[] = [
				'id' => $prod['id'],
				'text' => $prod['nombre'].' ('.$prod['categoria'].')',
				'unidad_medida' => gw("unidad_medida", ["id" => $prod['unidad_medida_id']])->row()->simbolo,
				'precio_unitario' => $prod['precio_unitario']
			];
		}
		$this->data["productos"] = $productoData;

		$this->data["proveedores"] = gw("proveedor", ["status" => RECORD_STATUS_ACTIVE])->result_array();
		$this->data["productos_select"] = gw("producto", ["status" => RECORD_STATUS_ACTIVE ])->result_array();
		$this->data["page_title"] = "Compras";		
		$this->load->view($this->name() . "/create_detcompras", $this->data);
	}

	public function detalle_compra_save() {
		$ret["success"] = false;

		date_default_timezone_set('America/Lima');
		$compra_id = $this->input->post("compra_id");
		$obra_id = $this->input->post("obra_id");
		$proveedor_id = $this->input->post("proveedor_id");
		
		$t_subtotal = $this->input->post("subtotal");
		$t_impuestos = $this->input->post("impuestos");
		$t_importe_total = $this->input->post("importe_total");

		$tipo_comprobante = $this->input->post("tipo_comprobante");
		$serie_numero = $this->input->post("serie_numero");
		$fecha_comprobante = $this->input->post("fecha_comprobante");
		$tipo_rubro = $this->input->post("tipo_rubro");
		$status = $this->input->post("status");

		$tm = date('Y-m-d H:i:s', time());

		$producto_count = $this->input->post("producto_count");

		$data_compra = [
			"comprador_id" 			=> $this->session->userdata("userID"),
			"fecha_compra"		 	=> $tm,
			"tipo_comprobante" 		=> $tipo_comprobante,
			"tipo_rubro" 			=> $tipo_rubro,
			"serie_numero" 			=> $serie_numero,
			"fecha_comprobante" 	=> $fecha_comprobante,
			"t_subtotal" 			=> $t_subtotal,
			"t_impuestos" 			=> $t_impuestos,
			"t_importe_total" 		=> $t_importe_total,
			"created_user_id" 		=> $this->session->userdata("userID"),
			"created_datetime" 		=> $tm,
			"status" 				=> $status,
		];

		$detalle_compra_data = [];

		try {
			$this->db->trans_start();

			if ($compra_id > 0) {
				
				$items = $_POST["items"];
				# die(var_dump($items));

				$costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;
				$t_importe_total_old = gw("compra", ["id" => $compra_id])->row()->t_importe_total;
				$diff = $t_importe_total - $t_importe_total_old;

				if ($diff < 0) {
					$data_obra["costo_obra"] = $costo_obra - abs($diff);
				} else {
					$data_obra["costo_obra"] = $costo_obra + $diff;
				}

				if($status == RECORD_STATUS_ACTIVE)
					$data_obra["costo_obra"] = $costo_obra + $t_importe_total;

				$this->Constant_model->updateData("obra", $data_obra, $obra_id);


                $data_compra["updated_datetime"] = $tm;					
                $data_compra["updated_user_id"] = $this->session->userdata("userID");
				$this->Constant_model->updateData("compra", $data_compra, $compra_id);

				$this->Constant_model->deleteByColumn("detalle_compra", "compra_id", $compra_id);

				for ($i = 0; $i < $producto_count; $i++) {
					$producto_id = $items["producto_id"][$i];
					$cantidad = $items["cantidad"][$i];
					$igv = $items["igv"][$i];
					$subtotal = $items["subtotal"][$i];
					$total = $items["total"][$i];

					$detalle_compra_data = [
						"obra_id"        	=> $obra_id,
						"compra_id"       	=> $compra_id,
						"producto_id"       => $producto_id,
						"proveedor_id"     	=> $proveedor_id,
						"cantidad"          => $cantidad,
						"igv"           	=> $igv,
						"subtotal"          => $subtotal,
						"total"          	=> $total,
						"created_user_id"   => $this->session->userdata("userID"),
						"created_datetime"  => $tm,
						"status"            => RECORD_STATUS_ACTIVE,
					];

					$detalle_compra_id = $this->Constant_model->insertDataReturnLastId("detalle_compra", $detalle_compra_data);
				}

            } else {

				$items = $_POST["items"];
				# die(var_dump($items));

				$compra_id = $this->Constant_model->insertDataReturnLastId("compra", $data_compra);

				for ($i = 0; $i < $producto_count; $i++) {
					$producto_id = $items["producto_id"][$i];
					$cantidad = $items["cantidad"][$i];
					$igv = $items["igv"][$i];
					$subtotal = $items["subtotal"][$i];
					$total = $items["total"][$i];

					$detalle_compra_data = [
						"obra_id"        	=> $obra_id,
						"compra_id"       	=> $compra_id,
						"producto_id"       => $producto_id,
						"proveedor_id"     	=> $proveedor_id,
						"cantidad"          => $cantidad,
						"igv"           	=> $igv,
						"subtotal"          => $subtotal,
						"total"          	=> $total,
						"created_user_id"   => $this->session->userdata("userID"),
						"created_datetime"  => $tm,
						"status"            => RECORD_STATUS_ACTIVE,
					];

					$detalle_compra_id = $this->Constant_model->insertDataReturnLastId("detalle_compra", $detalle_compra_data);
				}

				$costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;
				$data_obra["costo_obra"] = $costo_obra + $t_importe_total;
				$this->Constant_model->updateData("obra", $data_obra, $obra_id);

            }

			$this->db->trans_complete();
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
		redirect(base_url()."compras/detalle_compra?num=$obra_id");
	}

	public function detalle_compra_delete() {
		$ret["success"] = false;
		$compra_id = $this->input->post("id");
		$obra_id = $this->input->get("obra_id");

		$importe_total_compra = gw("compra", ["id" => $compra_id])->row()->t_importe_total;
		$status_compra = gw("compra", ["id" => $compra_id])->row()->status;
		$costo_obra = gw("obra", ["id" => $obra_id])->row()->costo_obra;

		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("compra", "id", $compra_id)))
				throw new Exception("REGISTRO NO ENCONTRADO");

			if ($status_compra == RECORD_STATUS_INACTIVE){
				throw new Exception("REGISTRO SE ENCUENTRA INACTIVO");
			} else {
				$this->Constant_model->updateData("compra", ["status" => 0, "deleted_datetime" => date('Y-m-d H:i:s', time()) ], $compra_id);
				$this->Constant_model->updateData("obra", ["costo_obra" => ($costo_obra - $importe_total_compra), "updated_datetime" => date('Y-m-d H:i:s', time()) ], $obra_id);
			}
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}
}
