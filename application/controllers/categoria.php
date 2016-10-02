<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categoria extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		
	}

	//Registro de visitantes
	public function index(){ show_404(); } //No usamos el index
	
	//Listado inicial
	public function lista()
	{
		
		if($this->moguardia->isloged())
		{
			//Generamos lista de categorias
			$categoria = $this->mocategorias->init();
			$categoria->generar();
			
			$d['registros_cantidad'] = $categoria->registros;
			$d['formularioCategoria'] = $this->load->view('categoria/formularioCategoria', null, true);
			if($categoria->registros_cantidad > 0) $d['registros'] = $categoria->registros;
			
			
			$this->moheader->addJs('librerias/categoria/comunes.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			); //Genera las librerías
			//Inicia carga de vistas
			
			
			
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			$this->load->view('layouts/header', $h); //Agregar cabecera (incluye librerías)
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
		$this->load->view('categoria/listado', $d);
		$this->load->view('layouts/footer');
			
			
			}
	}
	
	public function submit()
	{
		$id = $this->input->post('id', true);
		$nombre = $this->input->post('nombre', true);
		
		$t = $this->mocategorias->init();
		$t->id($id);
		$t->nombre($nombre);
		
		
		if($id == 0)
			$r = $t->crear();
		else
			$r = $t->actualizar();
		
		if($r)
		{
			$this->load->helper('url');
			if($id == 0) //Fue creación
				redirect(base() . 'categoria/lista' . suffix());
			else //Fue edición
				redirect($this->input->post('referer', true));
		}
		else //Ocurrió un error
			show_error('Ha ocurrido un error al ejecutar el registro.<br>Póngase en contacto con soporte técnico.');
	}
}