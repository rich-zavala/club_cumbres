<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sanciones extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
	}
	
	public function index(){ show_404(); } //No usamos el index
	
	//Listado de sanciones
	public function listado($torneo = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			
			//Información de sanciones de este torneo
			$i['info'] = $t->info();
			$i['sanciones'] = $t->jugadores_sanciones(0, true);
			
			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/sanciones_listado', $i, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			
			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('bower_components/bootstrap-typeahead/bootstrap3-typeahead.min.js');
			$this->moheader->addJs('librerias/torneos/sanciones_buscador.js');
			$this->moheader->addJs('librerias/torneos/sanciones_listado.js');
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
		
	//Registro de nueva sanción
	public function registro($torneo = 0, $jugador = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0 and $jugador > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			
			//Info de jugador
			$t = $this->motorneos->init();
			$i['jugador'] = $t->jugadores_sanciones($jugador);
			
			//Identificar si tiene sanciones
			$i['conSanciones'] = false;
			foreach($i['jugador'] as $sancion)
			{
				if(strlen(trim($sancion->PartSan)) > 0)
				{
					$i['conSanciones'] = true;
					break;
				}
			}
			
			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/sanciones_formulario', $i, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			
			//Inicia carga de vistas
			// $this->moheader->addJs('bower_components/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js');
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('bower_components/bootstrap-typeahead/bootstrap3-typeahead.min.js');
			$this->moheader->addJs('librerias/torneos/sanciones_formulario.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('dist/js/validator.min.js');
			
			$this->moheader->addCss('bower_components/bootstrap-datepicker-master/dist/css/bootstrap-datepicker.css');
			
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
	
	//Generar un listado de todos los jugadores de este torneo
	public function jugadores_listado($torneo = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0)
		{
			$e = $this->motorneos->init();
			$e->id($torneo);
			$jugadores = $e->jugadores_sanciones();
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode( $jugadores ));
		}
		else show_404();
	}
	
	//Inserción y actualización de registros
	public function submit()
	{
		$jugador = $this->input->post('jugador', true);
		$PartSan = $this->input->post('PartSan', true);
		$JorSan = $this->input->post('JorSan', true);
		
		$this->load->model('mojugador');
		$j = $this->mojugador->init();
		$j->id($jugador);
		$j->sancion_partidos($PartSan);
		$j->sancion_jornadas($JorSan);
		
		$r = $j->sancionar();
		
		$this->output->set_content_type('application/json')->set_output(json_encode($r));
	}
	
	//Remover una sanción
	public function sancion_remover()
	{
		$id = $this->input->post('id', true);
		
		$this->load->model('mojugador');
		$j = $this->mojugador->init();
		$j->id_sancion($id);
		
		$r = $j->sancion_remover();
		$this->output->set_content_type('application/json')->set_output(json_encode($r));
	}
}