<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('mousuario');
	}

	//Registro de visitantes
	public function index(){ show_404(); } //No usamos el index
	
	//Listado inicial
	public function lista()
	{
		
		if($this->moguardia->isloged())
		{
			//Generamos lista de categorias
			$usuario = $this->mousuario->init();
			$usuario->generar();
			$d['registros_cantidad'] = $usuario->registros;
			$d['formularioUsuario'] = $this->load->view('usuario/formularioUsuario',null, true);
			if($usuario->registros_cantidad > 0) $d['registros'] = $usuario->registros;
			
			
			$this->moheader->addJs('librerias/usuario/comunes.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			$this->moheader->addJs('librerias/usuario/usuarios_del.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			); //Genera las librerías
			//Inicia carga de vistas
			
			
			
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			$this->load->view('layouts/header', $h); //Agregar cabecera (incluye librerías)
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
		$this->load->view('usuario/listado', $d);
		$this->load->view('layouts/footer');
			
			
			}
	}
	
	public function submit()
	{
		$id = $this->input->post('id', true);
		$nombre = $this->input->post('nombre', true);
		$usuario = $this->input->post('usuario', true);
		$pass = $this->input->post('pass', true);
		
		$t = $this->mousuario->init();
		$t->id($id);
		$t->nombre($nombre);
		$t->usuario($usuario);
		$t->pass($pass);
		
		
		if($id == 0)
			$r = $t->crear();
		else
			$r = $t->actualizar();
		
		if($r)
		{
			$this->load->helper('url');
			if($id == 0) //Fue creación
				redirect(base() . 'usuario/lista' . suffix());
			else //Fue edición
				redirect($this->input->post('referer', true));
		}
		else //Ocurrió un error
			show_error('Ha ocurrido un error al ejecutar el registro.<br>Póngase en contacto con soporte técnico.');
	}
}