<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Equipos extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
		$this->load->model('moequipo');
	}
	
	public function index(){ show_404(); } //No usamos el index
	
	//Listado de equipos
	public function listado($torneo, $categoria)
	{
		if($this->moguardia->isloged(true) and $torneo > 0 and $categoria > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			$i['categoria'] = $i['info']->categorias[$categoria];
			$i['equipos'] = $i['info']->equipos[$categoria];
			
			//Obtener info de jugadores para el contador
			if(count($i['equipos']) > 0)
			{
				$e = $this->moequipo->init();
				foreach($i['equipos'] as $k => $r) $i['equipos'][$k]->jugadores = $e->id($r->ID_Equipo)->jugadores_cantidad();
			}
			
			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/equipos_listado', $i, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			
			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('librerias/torneos/equipos_listado.js');
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
	
	//Listado de jugadores
	public function jugadores($torneo, $categoria, $equipo)
	{
		if($this->moguardia->isloged(true) and $torneo > 0 and $categoria > 0 and $equipo > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			$i['categoria'] = $i['info']->categorias[$categoria];
			
			//Info de equipo
			$e = $this->moequipo->init();
			$e = $this->moequipo->id($equipo);
			$i['equipo'] = $e->info();
			
			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/jugadores_listado', $i, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			
			//Inicia carga de vistas
			$this->moheader->addJs('bower_components/bootstrap-datepicker-master/dist/js/bootstrap-datepicker.min.js');
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('librerias/torneos/jugadores_listado.js');
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
	
	//Inserción y actualización de registros
	public function submit()
	{
		if($this->moguardia->isloged(true))
		{
			$id = $this->input->post('id', true);
			$nombre = $this->input->post('nombre', true);
			$categoria = $this->input->post('categoria', true);
			
			$t = $this->moequipo->init();
			$t->id($id);
			$t->nombre($nombre);
			$t->categoria($categoria);
			
			if($id == 0)
				$r = $t->crear();
			else
				$r = $t->actualizar();
			
			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}
	
	//Agregar muchos jugadores a un equipo
	public function submit_jugadores_crear()
	{
		if($this->moguardia->isloged(true))
		{
			$id = $this->input->post('id', true);
			$jugadores = $this->input->post('jugadores', true);
			
			$t = $this->moequipo->init();
			$t->id($id);
			$t->jugadores_insert($jugadores);
			$r = $t->crear_jugadores();
			
			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}

	//Importador de jugadores
	public function importar($torneo, $categoria)
	{
		if($this->moguardia->isloged(true) and $torneo > 0 and $categoria > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			$i['categoria'] = $i['info']->categorias[$categoria];
			$i['equipos'] = $i['info']->equipos[$categoria];
			
			//Obtener info de jugadores para el contador
			if(count($i['equipos']) > 0)
			{
				$e = $this->moequipo->init();
				foreach($i['equipos'] as $k => $r) $i['equipos'][$k]->jugadores = $e->id($r->ID_Equipo)->jugadores_cantidad();
			}
			
			//Obtener torneos y sus equipos
			$e->torneo($torneo);
			$e->categoria($categoria);
			$i['torneos'] = $e->torneos_equipos();
			
			//Cargar vistas de torneo
			$i['vista'] = $this->load->view('torneos/equipos_importar', $i, true);
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			
			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('librerias/torneos/equipos_importar.js');
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
	
	//Importador de jugadores
	public function importar_submit()
	{
		if($this->moguardia->isloged(true))
		{
			$torneo = $this->input->post('torneo', true);
			$categoria = $this->input->post('categoria', true);
			$equipoNuevo = $this->input->post('equipoNuevo', true);
			
			$t = $this->moequipo->init();
			$t->id($equipoNuevo);
			$t->torneo($torneo);
			$t->categoria($categoria);
			
			$r = $t->importar();
			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}
}