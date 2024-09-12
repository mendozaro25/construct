<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Reportes";
	}

	public function index()
	{
		redirect(base_url());
	}

	//Report -> Jornadas

	public function report_personal() {
		$obra_id = $this->input->get("oID");

        $this->data["uri"] = [
            "title"		=> "Reporte Personal",
            "report"	=> base_url().$this->name()."/personales",
        ];
        $sql_jornada = 
                "select
                    j.id,
                    j.descripcion
                from jornada j
                inner join detalle_jornada dj on dj.jornada_id = j.id
                where dj.obra_id = $obra_id and dj.status = ".RECORD_STATUS_ACTIVE."
                group by j.id, j.descripcion";
		$this->data["jornadas"] = $this->db->query($sql_jornada)->result_array();	
        $sql_personal = 
                "select
                    p.id,
                    concat(p.nombre,' ',p.apellidos) as personal
                from personal p
                inner join detalle_jornada dj on dj.personal_id = p.id
                where dj.obra_id = $obra_id and dj.status = ".RECORD_STATUS_ACTIVE."
                group by p.id, concat(p.nombre,' ',p.apellidos)";
		$this->data["personales"] = $this->db->query($sql_personal)->result_array();
        $this->data["obra_id"] = $obra_id;
		$this->load->view($this->name()."/personales", $this->data);
	}

    public function personales() {
		$obra_id = $this->input->get("oID");
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'direccion',
			1 => 'especialidad',
			2 => 'personal',
			3 => 'banco',
			4 => 'num_cuenta',
			5 => 'jornada',
			6 => 't_asistencia',
			7 => 't_horas_extras',
			8 => 'sueldo',
			9 => 'sueldo_fijo',
		    10 => 'sueldo_horas_extras',
			11 => 'sueldo_total',
		);

        $sql = 
            "select 
                p.direccion,
                e.nombre as especialidad,
                concat(p.nombre,' ',p.apellidos) as personal,
                p.banco,
                p.num_cuenta,
                j.descripcion as jornada,
                a.t_asistencia,
                a.t_horas_extras,
                p.sueldo,
                a.sueldo_fijo,
                a.sueldo_horas_extras,
                a.sueldo_total
            from asistencia a
            inner join detalle_jornada dj on dj.id = a.detalle_jornada_id
            inner join obra o on o.id = dj.obra_id
            inner join jornada j on j.id = dj.jornada_id
            inner join personal p on p.id = dj.personal_id
            inner join especialidad e on e.id = p.especialidad_id
            where dj.obra_id = $obra_id and dj.status = ".RECORD_STATUS_ACTIVE;

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;
		$sql_gb = " group by 
                    p.direccion,
                    e.nombre,
                    concat(p.nombre,' ',p.apellidos),
                    p.banco,p.num_cuenta,
                    j.descripcion,
                    a.t_asistencia,
                    a.t_horas_extras,
                    p.sueldo,
                    a.sueldo_fijo,
                    a.sueldo_horas_extras,
                    a.sueldo_total";
		$sql1 = "";

		if ($totalData) {
			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql2.=" AND ( dj.id LIKE '".$requestData['search']['value']."%' ";
				$sql2.=" OR concat(p.nombre,' ',p.apellidos) LIKE '".$requestData['search']['value']."%' ";
				$sql2.=" OR p.banco LIKE '".$requestData['search']['value']."%' ";
				$sql2.=" OR j.descripcion LIKE '".$requestData['search']['value']."%' ";
				$sql2.=" OR p.direccion LIKE '".$requestData['search']['value']."%' ";
				$sql2.=" OR e.nombre LIKE '".$requestData['search']['value']."%') ";
			}

			if (intval($requestData["jornada"]) > 0){
				$sql1.=" AND j.id='". $requestData["jornada"] ."'";
			}

			if ($requestData["personal"] > 0){
				$sql1.=" AND p.id='". $requestData["personal"] ."'";
			}

			$sql = $sql.$sql1.$sql2.$sql_gb ;
			$rs = $this ->db->query($sql);
			$totalFiltered = $rs->num_rows();

			$sql.=" ORDER BY ".   $columns[$requestData['order'][0]['column']] ."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

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

	//Report -> Compras

	public function report_compra() {
		$obra_id = $this->input->get("oID");

        $this->data["uri"] = [
            "report"	=> base_url().$this->name()."/compras",
        ];

        $sql1 = 
                "select distinct
					p.id,
					p.nombre as proveedor
				from proveedor p
				inner join detalle_compra dc on dc.proveedor_id = p.id
				where dc.obra_id = $obra_id and p.status = ".RECORD_STATUS_ACTIVE;
		$this->data["proveedores"] = $this->db->query($sql1)->result_array();

		$sql2 = 
                "select distinct
					u.id,
					concat(u.name,' ',u.lastname) as comprador
				from users u
				inner join compra c on c.comprador_id = u.id
				inner join detalle_compra dc on dc.compra_id = c.id
				where dc.obra_id = $obra_id and u.status = ".RECORD_STATUS_ACTIVE;
		$this->data["compradores"] = $this->db->query($sql2)->result_array();

		$this->data["tipo_rubros"] = gw("constante", ["idconstante" => ID_CONST_REG_TRUBRO,])->result_array();
		$this->data["tipo_comprobantes"] = gw("constante", ["idconstante" => ID_CONST_REG_TCOMPRAB,])->result_array();

        $this->data["obra_id"] = $obra_id;
		$this->load->view($this->name()."/compras", $this->data);
	}

    public function compras() {
		$obra_id = $this->input->get("oID");
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'fecha_compra',
			1 => 'tipo_rubro',
			2 => 'comprador',
			3 => 'proveedor',
			4 => 'direccion_proveedor',
			5 => 'serie_numero',
			6 => 'tipo_comprobante',
			7 => 'producto',
			8 => 'unidad_medida',
			9 => 'cantidad',
			10 => 'precio_unitario',
			11 => 'parcial',
		    12 => 'igv',
			13 => 'total',
		);

        $sql = 
			"select 
				c.fecha_compra,
				k1.valor as tipo_rubro,
				concat(u.name,' ',u.lastname) as comprador,
				p.nombre as proveedor,
				p.direccion as direccion_proveedor,
				c.serie_numero,
				k2.valor as tipo_comprobante,
				pr.nombre as producto,
				un.simbolo as unidad_medida,
				dc.cantidad,
				pr.precio_unitario,
				dc.subtotal as parcial,
				dc.igv,
				dc.total
			from detalle_compra dc
			inner join compra c on c.id = dc.compra_id
			inner join constante k1 on k1.idconstante = ".ID_CONST_REG_TRUBRO." and k1.codigo = c.tipo_rubro
			inner join users u on u.id = c.comprador_id
			inner join proveedor p on p.id = dc.proveedor_id
			inner join constante k2 on k2.idconstante = ".ID_CONST_REG_TCOMPRAB." and k2.codigo = c.tipo_comprobante
			inner join producto pr on pr.id = dc.producto_id
			inner join unidad_medida un on un.id = pr.unidad_medida_id
			where dc.obra_id = $obra_id and c.status = ".RECORD_STATUS_ACTIVE;

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		$sql1 = "";

		if ($totalData) {
			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( c.fecha_compra LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR concat(u.name,' ',u.lastname) LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR pr.nombre LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR un.simbolo LIKE '".$requestData['search']['value']."%' ";
				$sql.=" OR c.serie_numero LIKE '".$requestData['search']['value']."%') ";
			}

			if (intval($requestData["proveedor"]) > 0){
				$sql1.=" AND p.id='". $requestData["proveedor"] ."'";
			}

			if (intval($requestData["comprador"] > 0)){
				$sql1.=" AND u.id='". $requestData["comprador"] ."'";
			}

			if ($requestData["tipo_rubro"] != null){
				$sql1.=" AND k1.codigo='". $requestData["tipo_rubro"] ."'";
			}

			if ($requestData["tipo_comprobante"] != null){
				$sql1.=" AND k2.codigo='". $requestData["tipo_comprobante"] ."'";
			}

			$sql = $sql . $sql1;

			$rs = $this ->db->query($sql);
			$totalFiltered = $rs->num_rows();

			$sql.=" ORDER BY ".   $columns[$requestData['order'][0]['column']] ."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";

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
	
}
