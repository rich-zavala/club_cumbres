<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Publicidad extends CI_Controller {
	
	
	function __construct() {
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
	
		
	}

	//Registro de visitantes
	public function index(){ show_404(); } //No usamos el index
	
	//Listado inicial
	public function lista()
	{
		
		if($this->moguardia->isloged())
		{
			//Generamos lista de categorias
			$publicidad = $this->mopublicidad->init();
			$publicidad->generar();
			$publicidad->tipoArchivo();
			$d['registros_cantidad'] = $publicidad->registros;
			$e['reg_cant'] = $publicidad->reg;
			
			if($publicidad->registros_cantidad > 0) $d['registros'] = $publicidad->registros;
			
			if($publicidad->reg_cant > 0) $e['reg'] = $publicidad->reg;
			$d['formulariopublicidad'] = $this->load->view('publicidad/formularioPublicidad',$e,true);
			
			$this->moheader->addJs('librerias/publicidad/comunes.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			$this->moheader->addJs('librerias/publicidad/publicidad_del.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			); //Genera las librerías
			//Inicia carga de vistas
			
			
			
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			$this->load->view('layouts/header', $h); //Agregar cabecera (incluye librerías)
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
		$this->load->view('publicidad/listado', $d);
		$this->load->view('layouts/footer');
			
			
			}else
				redirect(base());
	}
	
	public function submit(){
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|swf|jpeg';
		$config['max_size']	= '10240KB';
		$config['max_width']  = '2048';
		$config['max_height']  = '2048';

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload())
		{
			$error = array('error_data' => $this->upload->display_errors());

			$this->load->view('publicidad/listado', $error);
		}
		else
		{
			$data =  $this->upload->data();
			
			
			$id = $this->input->post('id', true);
			$tipo = $this->input->post('tipo', true);
			$file_name = $data['file_name'];
			
			$t = $this->mopublicidad->init();
			$t->id($id);
			$t->tipo($tipo);
			$t->file_name($file_name);
			
			
			if($id == 0)
			$r = $t->crear();
		else
			$r = $t->actualizar();
			
			
			if($r)
		{
			
			if($id == 0) //Fue creación
				redirect(base() . 'publicidad/lista' . suffix());
			else //Fue edición
				redirect($this->input->post('referer', true));
		}
		else //Ocurrió un error
			show_error('Ha ocurrido un error al ejecutar el registro.<br>Póngase en contacto con soporte técnico.');
			

			
		}
	}
		
	
}