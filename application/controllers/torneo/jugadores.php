<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jugadores extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('mojugador');
	}
	
	public function index(){ show_404(); } //No usamos el index
	
	//Listado de equipos
	public function edicion($jugador)
	{
		if($this->moguardia->isloged(true) and $jugador > 0)
		{
			//Generar información del torneo
			$j = $this->mojugador->init();
			$j->id($jugador);
			$i['info'] = $j->info();
			$this->load->view('torneos/jugador_formulario', $i);
		}
		else show_404();
	}
	
	//Actualizar jugador
	public function actualizar()
	{
		if($this->moguardia->isloged(true))
		{
			$i = array(
				'NomJug' => $this->input->post('NomJug', true),
				'ApeJug' => $this->input->post('ApeJug', true),
				'DirJug' => $this->input->post('DirJug', true),
				'TelJug' => $this->input->post('TelJug', true),
				'TelCasaJug' => $this->input->post('TelCasaJug', true),
				'CurpJug' => $this->input->post('CurpJug', true),
				'EmailJug' => $this->input->post('EmailJug', true),
				'FNacJug' => $this->input->post('FNacJug', true),
				'SexoJug' => $this->input->post('SexoJug', true),
				'NumCamisetaJug' => $this->input->post('NumCamisetaJug', true),
				'NumFolioJug' => $this->input->post('NumFolioJug', true),
				'NacionalidadJug' => $this->input->post('NacionalidadJug', true),
				'LNacJug' => $this->input->post('LNacJug', true),
				'CapitanJug ' => $this->input->post('CapitanJug', true),
				'PosicionJug' => $this->input->post('PosicionJug', true),
				'TipoSangreJug' => $this->input->post('TipoSangreJug', true),
				'EmpresaJug' => $this->input->post('EmpresaJug', true),
				'ODeportesJug' => $this->input->post('ODeportesJug', true),
				'TLibreJug' => $this->input->post('TLibreJug', true),
				'EscuelaJug' => $this->input->post('EscuelaJug', true),
				'GEscolarJug' => $this->input->post('GEscolarJug', true),
				'TJugadosJug' => $this->input->post('TJugadosJug', true),
				'NumAfiJug' => $this->input->post('NumAfiJug', true),
				'AlturaJug' => $this->input->post('AlturaJug', true),
				'PesoJug' => $this->input->post('PesoJug', true)
			);
			
			$j = $this->mojugador->init();
			$j->id( $this->input->post('ID_Jugador', true));
			$r = $j->actualizar($i);
			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}
	
	//Listado de goleadores de un torneo por categoría
	public function goleadores($torneo, $categoria, $limit = 10, $equipo = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0 and $categoria > 0)
		{
			//Generar información del torneo
			$this->load->model('motorneos');
			$this->load->model('moequipo');
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			$i['categoria'] = $i['info']->categorias[$categoria];
			$i['equipos'] = $i['info']->equipos[$categoria];
			
			//Obtener info de jugadores para el contador
			$e = $this->moequipo->init();
			$e->categoria($categoria);
			$i['limit'] = $limit > 0  ? $limit : 'todos los';
			
			//Definir equipo actual
			if($equipo == 0)
				$i['equipo'] = array( 'ID_Equipo' => 0, 'NomEquipo' => 'Todos los equipos' );
			else
				foreach($i['equipos'] as $eq)
					if($eq->ID_Equipo == $equipo)
						$i['equipo'] = (array)$eq;
					
			$i['goleadores'] = $e->goleadores($limit, $equipo);
			
			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/goleadores', $i, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			
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
}