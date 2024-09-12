<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public function __construct()
    {
        //  Call the Model constructor
        parent::__construct();

		$this->auth->authenticate();
		$this->load->model('Constant_model');
		$this->load->helper('form');
        date_default_timezone_set("America/Lima");

        $this->verificarAccesoMenu();

        #   die(var_dump($this->session->userdata("userID")));
    }

	public function name() {
		return strtolower(get_class($this));
	}

    protected function obtenerIdMenuDesdeURL() {
        $url_actual = current_url();
        $segments = explode('/', $url_actual);

        $menu_id = gw("menu", ["controller" => $segments[3]])->row()->id;
        //  die(var_dump($menu_id));
        return $menu_id;
    }

    protected function tieneAccesoMenu($user_id, $menu_id) {
        // Consultar la base de datos para verificar si el usuario tiene acceso al menú
        $this->db->where('user_id', $user_id);
        $this->db->where('menu_id', $menu_id);
        $query = $this->db->get('menu_users');

        // Verificar si se encontró una fila en la tabla menu_users
        // Esto indica que el usuario tiene acceso al menú
        if ($query->num_rows() > 0) {
            return true;
        }
        return false;
    }

    protected function passNotVisibles() {
        $result = false;

        $url_actual = current_url();
        $segments = explode('/', $url_actual);

        if ($tmp =  gw("menu", ["status" =>TRUE, "tv_visible" => FALSE ])->result_array()) {
            foreach ( $tmp as $item) {
                if ( $item["controller"] == $segments[3] ) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }
    

    protected function verificarAccesoMenu() {
        // Obtener el ID del usuario actual (puedes usar tu propio método para obtenerlo)
        $user_id = $this->session->userdata("userID");

        // Obtener el ID del menú actual desde la URL
        $menu_id = $this->obtenerIdMenuDesdeURL();

        // Verificar si el usuario tiene acceso al menú actual
        if (!$this->tieneAccesoMenu($user_id, $menu_id)) {
            #if(current_url() == base_url() || $this->session->userdata("username") == USERNAME_ADMIN || current_url() == base_url().'me/me_view' || current_url() == base_url().'me/me_save' || current_url() == base_url().'errores/error403' || current_url() == base_url().'dashboard')
            if(current_url() == base_url() || $this->session->userdata("username") == USERNAME_ADMIN ||   
                $this->passNotVisibles() )
                return;
            redirect('errores/error403');
        }
    }

}
