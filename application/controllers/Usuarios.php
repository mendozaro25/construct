<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends MY_Controller {
	private $data;
	public function __construct()
	{
		parent::__construct();
		$this->data["page_title"] = "Usuarios";
	}

	public function index()
	{
		redirect(base_url());
	}

	public function usuario() {
        $this->data["uri"] = [
            "title"             => "Usuarios",
            "list"              => base_url().$this->name()."/usuario_list",
            "create"            => base_url().$this->name()."/usuario_create",
            "save"              => base_url().$this->name()."/usuario_save",
            "remove"            => base_url().$this->name()."/usuario_delete",
            "create_access"     => base_url().$this->name()."/usuario_create_access",
            "save_access"       => base_url().$this->name()."/usuario_save_access",
        ];
		$this->load->view($this->name()."/list", $this->data);
	}

    public function usuario_list() {
		$requestData = $this->input->post();
		$columns = array(
			// datatable column index  => database column name
			0 => 'id',
			1 => 'fullname',
			3 => 'email',
			4 => 'dni',
			5 => 'phone',
			6 => 'username',
			7 => 'status',
		);

        $id = $this->session->userdata("userID");

        $sql = "select 
                    u.id, 
                    concat(u.name,' ',u.lastname) as fullname,
                    u.email, 
                    u.dni, 
                    u.phone, 
                    u.username,
                    k1.valor as status 
                from users u
                inner join constante k1 on k1.idconstante = ".ID_CONST_REG_STATUS." and k1.codigo = u.status
                where 1 = 1 and u.username != '".USERNAME_ADMIN."'";

		$rs = $this->db->query($sql);

		$totalData = $rs->num_rows();
		$totalFiltered = $totalData;

		if ($totalData) {
			$sql .= " AND 1 = 1";

			if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $requestData['search']['value'] contains search parameter
				$sql.=" AND ( u.id LIKE '".$requestData['search']['value']."%' ";
                $sql.=" OR concat(u.name,' '.u.lastname) LIKE '".$requestData['search']['value']."%'";
				$sql.=" OR u.status LIKE '".$requestData['search']['value']."%') ";
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

    public function usuario_create() {
		$data = [];
		if ($id = $this->input->post("id"))
			$this->data = array_merge($data, gw("users", ["id" => $id ])->row_array());
		
		$this->load->view($this->name() . "/create", $this->data);
	}

    public function usuario_save() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$id = $this->input->post("id");
		$name = $this->input->post("name");
		$lastname = $this->input->post("lastname");
		$username = $this->input->post("username");
		$password = $this->input->post("password");
		$email = $this->input->post("email");
		$dni = $this->input->post("dni");
		$phone = $this->input->post("phone");
		$status = $this->input->post("status");
        $tm = date('Y-m-d H:i:s', time());

        $data = [
            "name"			            => $name,
            "lastname"			        => $lastname,
            "email"			            => $email,
            "dni"			            => $dni,
            "phone"			            => $phone,
            "photo"			            => UPLOAD_DEFAULT_NOT_IMAGE,
            "username"			        => $username,
            "created_at"                => $tm,
            "user_id"                   => $this->session->userdata("userID"),
            "status"			        => $status
        ];

        if ($password)
        	$data["password"] = password_hash($password, PASSWORD_BCRYPT);

		try {

			$this->db->trans_start();

			if ($id > 0) {

				$this->Constant_model->updateData("users", $data, $id);

            } else {

				$user_id = $this->Constant_model->insertDataReturnLastId("users", $data);

            }

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}

    public function usuario_delete() {
		$ret["success"] = false;
		$id = $this->input->post("id");
		try {
			if (!($record  = $this->Constant_model->getSingleOneColumn("users", "id", $id)))
				throw new Exception("REGISTRO NO ENCONTRADO");
			$this->Constant_model->updateData("users", ["status" => 0, "deleted_at" => date('Y-m-d H:i:s', time()) ], $id);
			$ret["success"] = true;
		} catch (Exception $ex) {
			$ret["message"] = $ex->getMessage();
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
	}

	function getSavedNodes($id) {
		$query = $this->db->select('menu_id')->from('menu_users')->where('user_id', $id)->get();
		$result = $query->result_array();
		
		// Crear un array de objetos con la propiedad "id"
		$savedNodes = array_map(function($item) {
			return (object) ['id' => $item['menu_id']];
		}, $result);
		
		return $savedNodes;
	}		

	public function usuario_create_access() {
		$data = [];

		$id = $this->input->get("userID");
	
		$this->data = array_merge($data, gw("users", ["id" => $id])->row_array());
		$savedNodes = $this->getSavedNodes($id); // Reemplaza esta función con la lógica adecuada para obtener los nodos guardados
		$this->data["savedNodes"] = $savedNodes;

		# die(var_dump($this->data["savedNodes"]));

		$sql = 
			"select
				m.order,
				m.id,
				m.display_name as text,
				m.parent_id,
				if(count(c.id) > 0, true, false) as has_children
			from menu m
			left join menu c on c.parent_id = m.id
			where m.status = ".RECORD_STATUS_ACTIVE." and m.tv_visible = ".RECORD_STATUS_ACTIVE."
			group by m.id
			order by m.order asc";
		$query = $this->db->query($sql);
		$result = $query->result_array();

		// Función recursiva para construir la estructura de árbol
		function buildTree($elements, $parentId = 0) {
			$branch = array();
			foreach ($elements as $element) {
				if ($element['parent_id'] == $parentId) {
					$children = buildTree($elements, $element['id']);
					if ($children) {
						$element['children'] = $children;
					}
					$branch[] = $element;
				}
			}
			return $branch;
		}

		$this->data["dataSource"] = buildTree($result);
		#	die(var_dump($this->data["dataSource"]));
		$this->data["page_title"] = "Usuarios";
		$this->load->view($this->name() . "/access", $this->data);
	}

	public function usuario_save_access() {
		$ret["success"] = false;

        date_default_timezone_set('America/Lima');

		$user_id = $this->input->get("userID");
		$checkedIds = $this->input->post('checkedIds');
		$newCheckedIds = array();
        $tm = date('Y-m-d H:i:s', time());

		try {

			$this->db->trans_start();

			$this->Constant_model->deleteByColumn("menu_users", "user_id", $user_id);

			foreach ($checkedIds as $menu_parent_id) {
				$parent_id = gw("menu", ["id" => $menu_parent_id])->row()->parent_id;
            
				if (!in_array($parent_id, $checkedIds))
					$newCheckedIds[] = $parent_id;
			}
			
			$newCheckedIds = array_values(array_filter(array_unique($newCheckedIds)));
			#	die(var_dump($newCheckedIds));

			foreach ($newCheckedIds as $parent_ids){
				
				$data__ = [
					"user_id"				=> $user_id,
					"menu_id"			   	=> $parent_ids,
					"created_datetime"      => $tm,
					"created_user_id"       => $this->session->userdata("userID"),
				];

				$this->Constant_model->insertDataReturnLastId("menu_users", $data__);
			}

			foreach ($checkedIds as $menu_id){

				$data = [
					"user_id"				=> $user_id,
					"menu_id"			   	=> $menu_id,
					"created_datetime"      => $tm,
					"created_user_id"       => $this->session->userdata("userID"),
				];

				$detalle_compra_id = $this->Constant_model->insertDataReturnLastId("menu_users", $data);
			}

			$this->db->trans_complete();
			$ret["success"] = true;

		} catch (Exception $ex) {

			$ret["message"] = $ex->getMessage();
		}

		header('Content-Type: application/json');
		echo json_encode($ret);
	}


}
