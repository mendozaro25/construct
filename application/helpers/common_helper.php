<?php

if(!function_exists('getMenuAll')) {
    function getMenuAll()
    {
        $ci =& get_instance();

        $sql = "select m.* from menu m where m.status = 1 and m.tv_visible = 1 order by m.order asc";
        $result = $ci->db->query($sql)->result_array();
        return $result;
    }
}

if(!function_exists('getMenuForUser')) {
    function getMenuForUser($user_id)
    {
        $ci =& get_instance();

        $sql = "select m.*
                from menu_users mu
                inner join menu m on m.id = mu.menu_id
                where mu.user_id = '{$user_id}' and m.tv_visible = 1
                order by m.order asc";
        $result = $ci->db->query($sql)->result_array();
        return $result;
    }
}

if (!function_exists('generateMenu')) {
    function generateMenu($menuItems, $parentId = 0) {
        $html = '';
        foreach ($menuItems as $item) {
            if ($item['parent_id'] == $parentId) {
                if ($item['icon'] == null && $item['parent_id'] != null) {
                    $html .= '<li><a href="' . base_url() . $item['controller'] . '/' . $item['main_method'] . '" class="slide-item">' . $item['display_name'] . '</a></li>';
                } else {
                    if($item['parent_id'] == null && $item['controller'] == null && $item['icon'] == null)
                        $html .= '<li class="side-item side-item-category">'.$item['display_name'].'</li>';
                    $html .= '<li class="slide">';
                    
                    if($item['controller'] == null && $item['main_method'] == null && $item['icon'] != null){
                        $html .= '<a class="side-menu__item" data-toggle="slide" href="#">';                    
                        $html .= '<i class="side-menu__icon ' . $item['icon'] . '"></i>';
                        $html .= '<span class="side-menu__label">' . $item['display_name'] . '</span>';
                    }
                    if($item['controller'] != null && $item['main_method'] != null ){                        
                        $html .= '<a class="side-menu__item" href="' . base_url() . $item['controller'] . '/' . $item['main_method'] . '">';                 
                        $html .= '<i class="side-menu__icon ' . $item['icon'] . '"></i>';
                        $html .= '<span class="side-menu__label">' . $item['display_name'] . '</span>';
                    }

                    // Verificar si el elemento tiene subelementos
                    $hasChildren = false;
                    foreach ($menuItems as $child) {
                        if ($child['parent_id'] == $item['id']) {
                            $hasChildren = true;
                            break;
                        }
                    }

                    if ($hasChildren) {
                        $html .= '<i class="angle fa fa-angle-right"></i></a>';
                        $html .= '<ul class="slide-menu">';
                        $html .= generateMenu($menuItems, $item['id']); // Llamada recursiva para generar subelementos anidados
                        $html .= '</ul>';
                    }
                    

                    $html .= '</a>';

                    $html .= '</li>';
                }
            }
        }

        return $html;
    }
}


/* 

    if(!function_exists('getMenuAccess')) {
        function getMenuAccess($user_id)
        {
            $ci =& get_instance();

            $sql = "select m.* from menu m 
                    inner join menu_users mu on mu.menu_id=m.id and mu.user_id='{$user_id}'
                    where m.status=1 order by  m.parent_id, m.`order`, m.id";
            $result = $ci->db->query($sql)->result_array();
            seek_flags($result);
            return $result;
        }
    }

    if(!function_exists('getMenuAccessAllMenus')) {
        function getMenuAccessAllMenus($user_id)
        {
            $ci =& get_instance();

            $sql = "select m.*, if(mu.user_id is null, 0, 1) as checked from menu m 
                    left join menu_users mu on mu.menu_id=m.id and mu.user_id='{$user_id}'
                    where m.status=1 order by  m.parent_id, m.`order`, m.id";
            $result = $ci->db->query($sql)->result_array();
            seek_flags($result);
            return $result;
        }
    }

    if(!function_exists('getMenuAccessAsTree')) {
        function getMenuAccessAsTree($user_id = false, $all_menus = false)
        {
            $ci =& get_instance();
            if (!$user_id) $user_id =  $ci->auth->user_id;
            $arr = !$all_menus ? getMenuAccess($user_id) : getMenuAccessAllMenus($user_id);
            if ($arr)
                return buildTree($arr, 0);
            return false;
        }
    }

    if(!function_exists('ulTreeUserMenus')) {
        function ulTreeUserMenus($items = [])
        {
            foreach ($items as $it) {
                echo "<li id='{$it["id"]}' ". ($it["checked"] ==1 && ($it["parent_id"]>0 || !count($it["children"])) ? "checked" : "") ." ><span>{$it["display_name"]}</span>\n";
                if ( count($it["children"])) {
                    echo "<ul>"; ulTreeUserMenus($it["children"]); echo "</ul>\n";
                }
                echo "</li>\n";
            }
        }
    } 

*/

if(!function_exists('gw')) {
	function gw($table, $where = [])
	{
		$ci =& get_instance();
		return $ci->db->get_where($table, $where);
	}
}

if(!function_exists('gwo')) {
    function gwo($table, $where = [], $orderBy = "id DESC")
    {

        $ci =& get_instance();
        return $ci->db->select("*")->from($table)->where($where)->order_by($orderBy)->get();
    }
}

if(!function_exists('pathImage')) {
	function pathImage($path)
	{
		$ret = "https://dummyimage.com/200x200/8a8a8a/4d4d4d&text=no-image";
		if ( ($path == "no_image.jpg" && file_exists($path) || file_exists($path) ) )
			$ret = base_url().$path;
		return $ret;
	}
}

if(!function_exists('getNroCorrelativoTc')) {
	function getNroCorrelativoTc()
	{
		$ci =& get_instance();
		$ret = "-";
		if ($nro = $ci->db->query("select right(concat('000000', ( select valor from config where id=1)), 6) as nro;")->row()->nro)
			$ret = $nro;
		return $ret;
	}
}

if(!function_exists('getNroCorrelativoLicFun')) {
    function getNroCorrelativoLicFun()
    {
        try {
            $ci =& get_instance();
            if ($nro = $ci->db->query("select right(concat('0000', ( select valor from config where id=7 and status=1)), 4) as nro;")->row()->nro)
                return $nro;
            else
                throw new Exception("El correlativo de lic. de funcionamiento no ha sido configurado aún.\nComuníquese con el área de sistemas.");
        } catch (Exception $ex) {
            throw new Exception($ex ->getMessage());
        }
    }
}


if(!function_exists('getNroCorrelativoMesaPartes')) {
    function getNroCorrelativoMesaPartes()
    {
        try {
            $ci =& get_instance();
            if ($nro = $ci->db->query("select right(concat('0000', ( select valor from config where id=8 and status=1)), 4) as nro;")->row()->nro)
                return $nro;
            else
                throw new Exception("El correlativo de mesa de partes no ha sido configurado aún.\nComuníquese con el área de sistemas.");
        } catch (Exception $ex) {
            throw new Exception($ex ->getMessage());
        }
    }
}

if(!function_exists('getNroCorrelativoLibroRec')) {
    function getNroCorrelativoLibroRec()
    {
        try {
            $ci =& get_instance();
            if ($nro = $ci->db->query("select right(concat('00000', ( select valor from config where id=10 and status=1)), 5) as nro;")->row()->nro)
                return $nro;
            else
                throw new Exception("El correlativo para este registro virtual <no ha sido configurado aún.\nComuníquese con el área de sistemas.");
        } catch (Exception $ex) {
            throw new Exception($ex ->getMessage());
        }
    }
}

if(!function_exists('uploadFile')) {
    function uploadFile($fileInputName, /*$fileName,*/ $pathUpload, $ext_allowed = ["pdf", "doc", "docx"], $size = 26214400)
    {
		$ci =& get_instance();
        try {
			#die(var_dump($size));
            $temp_fn = $_FILES[$fileInputName]['name'];
            if (!empty($temp_fn)) {
                $temp_fn_ext = pathinfo($temp_fn, PATHINFO_EXTENSION);

                if (!empty($ext_allowed)) {
                    if (in_array($temp_fn_ext, $ext_allowed)) {
                        if ($_FILES[$fileInputName]['size'] > $size)
                            throw  new Exception("El volumen de archivo excede a lo permitido.!");
                    } else
                        throw  new Exception("Formato de imagen inválida. <br>Permitidas son:  ". implode(",", $ext_allowed) );
                }
            } else
                return false;

            $main_ext = pathinfo($temp_fn, PATHINFO_EXTENSION);

            $config['upload_path']   = $pathUpload;
            $config['allowed_types'] = implode("|", $ext_allowed);
            $config['max_size']      = $size;
            $config['overwrite']     = TRUE;
            $config['file_name']     = $temp_fn; #.".$main_ext";
            /*$config['encrypt_name']  = TRUE;*/
            $config['remove_spaces'] = TRUE;

            $ci->load->library('upload', $config);

            // check if folder exists
            if( ! is_dir($config['upload_path']))
                @mkdir($config['upload_path'], 0777, true);


            if ( ! $ci->upload->do_upload($fileInputName))
                throw new Exception($ci->upload->display_errors());

            return $ci->upload->data("file_name");


        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }

        return false;
    }
}


if(!function_exists('uploadImage')) {
	function uploadImage($fileInputName, $fileName, $pathUpload, $haveThumbs =false)
	{
		$ci =& get_instance();
		try {
			$temp_fn = $_FILES[$fileInputName]['name'];
			if (!empty($temp_fn)) {
				$temp_fn_ext = pathinfo($temp_fn, PATHINFO_EXTENSION);

				if (($temp_fn_ext == 'jpg') || ($temp_fn_ext == 'png') || ($temp_fn_ext == 'jpeg')) {
					if ($_FILES[$fileInputName]['size'] > 2048000)
						throw  new Exception("El volumen de archivo debe ser igual o inferior a 2MB!");
				} else
					throw  new Exception("Formato de imagen inválida. <br>Permitidas son:  JPG, PNG, JPEG");
			}

			$mainPhoto_fn = $temp_fn;
			if (!empty($mainPhoto_fn)) {
				$main_ext = pathinfo($mainPhoto_fn, PATHINFO_EXTENSION);
				$mainPhoto_name = $fileName.".$main_ext";

				// Main Photo -- START;
				$config['upload_path'] = $pathUpload; #  './assets/upload/products/';
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['file_name'] = $mainPhoto_name;
				$config['overwrite'] = TRUE;
				$ci->load->library('upload', $config);

				if (!$ci->upload->do_upload($fileInputName)) {
					throw new Exception($ci->upload->display_errors());
				} else {
					if ($haveThumbs) {
						$width_array = array(100, 200);
						$height_array = array(100, 200);

						$ci->load->library('image_lib');

						for ($i = 0; $i < count($width_array); ++$i) {
							$config['image_library'] = 'gd2';
							$config['source_image'] = $pathUpload.$mainPhoto_name;  #"./assets/upload/products/$mainPhoto_name";
							$config['maintain_ratio'] = true;
							$config['width'] = $width_array[$i];
							$config['height'] = $height_array[$i];
							$config['quality'] = '100%';

							if (!file_exists($pathUpload.$dir_array[$i].'/'.$fileName)) {
								mkdir($pathUpload.$dir_array[$i].'/'.$fileName, 0777, true);
							}

							$config['new_image'] = $pathUpload.$dir_array[$i].'/'.$fileName.'/'.$mainPhoto_name;

							$ci->image_lib->clear();
							$ci->image_lib->initialize($config);
							$ci->image_lib->resize();
						}

						$ci->load->helper('file');
						$path = $pathUpload.$mainPhoto_name;

						if (unlink($path)) {
						}
					}
					return $mainPhoto_name;
				}
				// Main Photo -- END;
			}// End of File;

		} catch (Exception $ex) {
			throw new Exception($ex->getMessage());
		}
		return false;
	}
}

// form
if(!function_exists('form_dropdown_objects'))
{
	function form_dropdown_objects($name, $objectsArr, $extra = [], $selected = false, $keyValue = "id", $keyDisplay = "name", $attrId = false)
	{
		$options = array();
		foreach ($objectsArr as $item)
			$options[$item->{$keyValue}] = $item->{$keyDisplay};

		if (!$attrId) $attrId = $name;
		$extra["id"] = $attrId;

		echo form_dropdown($name, $options, $selected, $extra);

	}
}

if(!function_exists('form_dropdown_array'))
{
	function form_dropdown_array($name, $arr, $extra = false, $selected = false, $default_text = false, $keyValue = "id", $keyDisplay = "name", $attrId = false, $outPrint = true )
	{
		if ($default_text) array_push($arr, ["$keyValue" => "", "$keyDisplay" => ($default_text!=OPTION_DEFAULT_TEXT?$default_text:OPTION_DEFAULT_TEXT)  ]) ;

		#array_column ( array $input , mixed $column_key [, mixed $index_key = null ] ) : array
		$options = array_column($arr, $keyDisplay, $keyValue);

		if (!$attrId) $attrId = $name;
		if (is_array($extra))
			$extra["id"] = $attrId;
		else
			$extra .= " id='$attrId'";

		$out = form_dropdown($name, $options, $selected, $extra);
		if ($outPrint)
			echo $out;
		else
			return $out;
	}
}


if(!function_exists('getReportAsPdf'))
{
    defined('HTML_PRINT') or define('HTML_PRINT', -1);
    defined('PDF_PRINT') or define('PDF_PRINT', 0);
    defined('PDF_SAVE_AS') or define('PDF_SAVE_AS', 1);
    defined('PDF_RAW') or define('PDF_RAW', 2);
    function getReportAsPdf($tplName, $data = []
        , $pdfOptions  = [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
            'margin-left' => '0mm',
            'margin-right' => '0mm',
        ]
        , $output = PDF_PRINT, $outFileName = "report.pdf"
        , $twigOptions = ["template_dir" => TWIG_MY_TEMPLATES_PATH]
    )
    {

        $ci = get_instance();
        $ci->load->library('twig', $twigOptions);

        $html =  $ci->twig->render($tplName, $data);

        if ($output == HTML_PRINT) die($html);

        $ci->load->library('pdfReport');
        $ci->pdfreport->setOptions( $pdfOptions);
        $binPath = getPathBin();
        if (file_exists($binPath))
            $ci->pdfreport->setBinPath($binPath);

        $pdf = $ci->pdfreport->addPage($html);
        $result = true;
        switch ($output) {
            case PDF_PRINT:
                $result = $pdf->send();
                break;
            case PDF_SAVE_AS:
                $result = $pdf->send($outFileName);
                break;
            case PDF_RAW:
                return $result = $pdf->toString();
                break;
        }

        if ($result === false) {
            throw new Exception('Could not create PDF: '.$pdf->getError());
        }
    }
}

if(!function_exists('sendEmail')) {
    function sendEmail($subject, $body, $toEmails = [])
    {
        try {
            $ci = get_instance();
            $ci->load->config("my_config");
            $ci->load->library('email');
            $ci->email->initialize($ci->config->item("smtp"));
            $ci->email->set_newline("\r\n");
            $ci->email->from(FROM_EMAIL, FROM_FULLNAME);
            $list = $toEmails;
            $ci->email->to($list);
            $ci->email->subject($subject);
            $ci->email->message($body);

            if ($ci->email->send())
                return true;
            else
                throw new Exception("Error en el servidor de correos, intente más tarde.");

        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
        return false;
    }
}

if(!function_exists('getMailer')) {
    function getMailer($subject, $body)
    {
        try {
            $ci = get_instance();
            $ci->load->config("my_config");
            $ci->load->library('email');
            $ci->email->initialize($ci->config->item("smtp"));
            $ci->email->set_newline("\r\n");
            $ci->email->from(FROM_EMAIL, FROM_FULLNAME);
            //$ci->email->to($list);
            $ci->email->subject($subject);
            $ci->email->message($body);

            return $ci->email;

        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
        return false;
    }
}

if(!function_exists('getHtmlFromTwig'))
{
    function getHtmlFromTwig($tplName, $data = [], $twigOptions = ["template_dir" => TWIG_MY_TEMPLATES_PATH]) {
        $ci = get_instance();
        $ci->load->library('twig', $twigOptions);

        return $ci->twig->render($tplName, $data);
    }
}

if(!function_exists('getPdfFromHtml'))
{
    function getPdfFromHtml($tplName, $data = [], $twigOptions = ["template_dir" => TWIG_MY_TEMPLATES_PATH]) {
        $ci = get_instance();
        $ci->load->library('twig', $twigOptions);

        return $ci->twig->render($tplName, $data);
    }
}


function getPathBin()
{
    if (isWindows())
        $path = 'C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe';
    else
        $path = '/usr/local/bin/wkhtmltopdf'; //linux
    return $path;
}

function isWindows()
{
    return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
}


function array_extend(&$result) {
    if (!is_array($result)) {
        $result = array();
    }

    $args = func_get_args();

    for ($i = 1; $i < count($args); $i++) {
        // we only work on array parameters:
        if (!is_array($args[$i])) continue;

        // extend current result:
        foreach ($args[$i] as $k => $v) {
            if (!isset($result[$k])) {
                $result[$k] = $v;
            }
            else {
                if (is_array($result[$k]) && is_array($v)) {
                    array_extend($result[$k], $v);
                }
                else {
                    $result[$k] = $v;
                }
            }
        }
    }

    return $result;
}

if(!function_exists('normalizeDecimal'))
{
    function normalizeDecimal($val, $precision = 4)
    {
        $input = str_replace(' ', '', $val);
        $number = str_replace(',', '.', $input);
        if (strpos($number, '.')) {
            $groups = explode('.', str_replace(',', '.', $number));
            $lastGroup = array_pop($groups);
            $number = implode('', $groups) . '.' . $lastGroup;
        }
        return bcadd($number, 0, $precision);
    }
}

if(!function_exists('getConstante'))
{
    function getConstante($id, $asArrAssoc = false)
    {
        $ci = get_instance();
        $sql = "select * from constante where idconstante=? and estado='1' and not codigo is null order by orden ASC ";
        $query = $ci->db->query($sql, [$id]);
        if ($query->num_rows()) {
            $method = !$asArrAssoc ? "result" : "result_array";
            $result = $query->{$method}();
            $ci->db->save_queries = false;
            return $result;
        }
        return false;
    }
}

function tm() {
    return  date('Y-m-d H:i:s', time());
}

function validateDate($date, $format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

// Función para encriptar una cadena
if(!function_exists('encriptar'))
{
    function encriptar($cadena) {
        $ci = get_instance();
        $ci->load->library('encryption');
        $encriptado = $ci->encryption->encrypt($cadena);
        return $encriptado;
     }
}
 
 // Función para desencriptar una cadena
 if(!function_exists('desencriptar'))
{
    function desencriptar($cadena_encriptada) {
        $ci = get_instance();
        $ci->load->library('encryption');
        $desencriptado = $ci->encryption->decrypt($cadena_encriptada);
        return $desencriptado;
    }
}