<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Arbitros extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('moarbitros');
	}

	public function index(){ show_404(); } //No usamos el index
	
	//Listado inicial
	public function listado()
	{
		if($this->moguardia->isloged(true)) //Verifica que esté logueado
		{
			//Generamos lista de torneos usando el modelo
			$arbitros = $this->moarbitros->init();
			$arbitros->generar();
			$d['registros_cantidad'] = $arbitros->registros;
			if($arbitros->registros_cantidad > 0) $d['registros'] = $arbitros->registros;
			
			//Eventos de creación / actualización
			$d['creado'] = $this->session->flashdata('creado');
			$d['formularioArbitro'] = $this->load->view('arbitros/formulario', null, true);
			
			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/arbitros_listado.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			); //Genera las librerías
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			$this->load->view('layouts/header', $h); //Agregar cabecera (incluye librerías)
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
			$this->load->view('arbitros/listado', $d); //Agrega cuerpo de documento (incluye variables)
			$this->load->view('layouts/footer'); //Agrega footer
		}
	}
	
	//Inserción y actualización de registros
	public function submit()
	{
		if($this->moguardia->isloged(true))
		{
			$id = $this->input->post('id', true);
			$nombre = $this->input->post('nombre', true);
			$telefono = $this->input->post('telefono', true);
			
			$arbitro = $this->moarbitros->init();
			$arbitro->id($id);
			$arbitro->nombre($nombre);
			$arbitro->telefono($telefono);
			
			if($id == 0)
				$r = $arbitro->crear();
			else
				$r = $arbitro->actualizar();
			
			if($r)
			{
				$this->load->helper('url');
				if($id == 0) //Fue creación
					redirect(base() . 'arbitros/listado' . suffix());
				else //Fue edición
					redirect($this->input->post('referer', true));
			}
			else //Ocurrió un error
				show_error('Ha ocurrido un error al ejecutar el registro.<br>Póngase en contacto con soporte técnico.');
		}
		else show_404();
	}
}