<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Torneos extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
	}

	//Registro de visitantes
	public function index(){ show_404(); } //No usamos el index

	//Listado inicial
	public function listado()
	{
		if($this->moguardia->isloged(true)) //Verifica que esté logueado
		{
			//Generamos lista de torneos usando el modelo
			$torneos = $this->motorneos->init();
			$torneos->generar();
			$d['registros_cantidad'] = $torneos->registros;
			if($torneos->registros_cantidad > 0) $d['registros'] = $torneos->registros;

			//Eventos de creación / actualización
			$d['creado'] = $this->session->flashdata('creado');
			$d['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', null, true);

			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			); //Genera las librerías
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar(); //Genera el menú
			$this->load->view('layouts/header', $h); //Agregar cabecera (incluye librerías)
			$this->load->view('layouts/sitio_wrapper', $menu); //Agrega estructura
			$this->load->view('torneos/torneos_listado', $d); //Agrega cuerpo de documento (incluye variables)
			$this->load->view('layouts/footer'); //Agrega footer
		}
	}

	//Vista inicial de un torneo
	public function inicio($torneo = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();

			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/inicio', null, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			$i['torneo'] = $torneo;

			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			);
			$menu['menuLateral'] = $this->momenu->menuLateralGenerar();
			$this->load->view('layouts/header', $h);
			$this->load->view('layouts/sitio_wrapper', $menu);
			$this->load->view('torneos/dashboard', $i);
			$this->load->view('layouts/footer');
		}
		else show_404();
	}

	//Inserción y actualización de registros
	public function submit()
	{
		if($this->moguardia->isloged(true))
		{
			$id = $this->input->post('id', true);
			$nombre = $this->input->post('nombre', true);
			$agno = $this->input->post('agno', true);
			$tipo = $this->input->post('tipo', true);

			$t = $this->motorneos->init();
			$t->id($id);
			$t->nombre($nombre);
			$t->agno($agno);
			$t->tipo($tipo);

			//Sueldos de árbitros
			$t->sueldos = array(
				'sueldo1' 	=> (float) $this->input->post('sueldo1', true),
				'sueldo2' 	=> (float) $this->input->post('sueldo2', true),
				'sueldo3' 	=> (float) $this->input->post('sueldo3', true),
				'sueldo4' 	=> (float) $this->input->post('sueldo4', true),
				'sueldo5' 	=> (float) $this->input->post('sueldo5', true),
				'descuento' => (float) $this->input->post('descuento', true)
			);

			if($id == 0)
				$r = $t->crear();
			else
				$r = $t->actualizar();

			if($r)
			{
				$this->load->helper('url');
				if($id == 0) //Fue creación
					redirect(base() . 'torneos/listado' . suffix());
				else //Fue edición
					redirect($this->input->post('referer', true));
			}
			else //Ocurrió un error
				show_error('Ha ocurrido un error al ejecutar el registro.<br>Póngase en contacto con soporte técnico.');
		}
		else show_404();
	}
}
