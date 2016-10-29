<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Partidos extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
		$this->load->model('mopartido');
	}

	public function index(){ show_404(); } //No usamos el index

	//Listado de partidos. Página principal.
	public function listado($torneo, $categoria, $grupo_actual = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$t->grupo_categoria($categoria);
			$i['info'] = $t->info(true);
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;

			$i['categoria'] = $i['info']->categorias[$categoria];
			$i['equipos'] = $i['info']->equipos[$categoria];

			//Catálogo de árbitros
			$this->arbitros = $this->load->model("moarbitros");
			$moarbitros = $this->moarbitros->init();
			$moarbitros->generar();
			$i['info']->arbitros = (array)$moarbitros->registros;

			//Identificar equipos para continuar
			$i['sinGrupo'] = true;
			$i['sinEquipos'] = true;
			if(count($i['equipos']) > 0)
			{
				$i['sinEquipos'] = false;

				//Información de grupos
				$i['grupos'] = $t->grupos(true, true);

				//Grupo actual
				if(count($i['grupos']) > 0)
				{
					if($grupo_actual == 0) //Si no se ha seleccionado, seleccionamos el primero
					{
						$i['grupo_actual'] = $i['grupos'][0];
						$i['sinGrupo'] = false;
					}
					else //Grupo seleccionado
					{
						foreach($i['grupos'] as $grupo)
						{
							if($grupo->ID_VueltaGpo == $grupo_actual)
							{
								$i['grupo_actual'] = $grupo;
								$i['sinGrupo'] = false;
								break;
							}
						}
					}
				}
			}

			//Identificar jornadas
			$i['sinJornadas'] = @count($i['grupo_actual']->jornadas) == 0;

			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			$i['vista'] = $this->load->view('torneos/partidos_listado', $i, true);

			//Inicia carga de vistas
			$this->moheader->addJs('dist/js/jquery.timepicker.min.js');
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('librerias/torneos/partidos.js');
			$this->moheader->addJs('dist/js/validator.min.js');

			$this->moheader->addCss('dist/css/jquery.timepicker.css');
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

	//Creación y edición de grupo
	public function grupo_formulario($torneo, $categoria, $grupo_actual = 0)
	{
		if($this->moguardia->isloged(true) and $torneo > 0)
		{
			//Generar información del torneo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$t->grupo_categoria($categoria);
			$i['info'] = $t->info();
			$i['config'] = $t->config();
			$i['torneo'] = $torneo;
			$i['categoria'] = $i['info']->categorias[$categoria];
			$i['equipos'] = $i['info']->equipos[$categoria];

			//Información de grupos
			$i['grupos'] = $t->grupos();


			//Grupo actual
			if($grupo_actual == 0) //Es creación
			{
				$i['breadcrumb_titulo'] = 'Nuevo';
				$i['grupo_actual'] = (object)array('Es_Public' => 1);
			}
			else //Es edición
			{
				foreach($i['grupos'] as $grupo)
				{
					if($grupo->ID_VueltaGpo == $grupo_actual)
					{
						$i['breadcrumb_titulo'] = $grupo->DenomVG;
						$i['grupo_actual'] = $grupo;
						break;
					}
				}
			}

			//El grupo no existe: Error
			if(!isset($i['breadcrumb_titulo'])) show_404();

			//Cargar vistas de torneo
			$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
			$i['vista'] = $this->load->view('torneos/grupo_formulario', $i, true);

			//Inicia carga de vistas
			$this->moheader->addJs('librerias/torneos/comunes.js');
			$this->moheader->addJs('librerias/torneos/torneos_listado.js');
			$this->moheader->addJs('librerias/torneos/grupo_formulario.js');
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
	public function grupo_submit()
	{
		if($this->moguardia->isloged(true))
		{
			$id = $this->input->post('id', true);

			$t = $this->motorneos->init();
			$t->grupo_id($id);
			$t->grupo_nombre($this->input->post('DenomVG', true));
			$t->grupo_publico($this->input->post('Es_Public', true));
			$t->grupo_equipos($this->input->post('equipos', true));
			$t->grupo_categoria($this->input->post('ID_CatTorn', true));
			$t->id($this->input->post('torneo', true));

			if($id == 0)
				$r = $t->grupo_crear();
			else
				$r = $t->grupo_actualizar();

			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}

	//Inserción y actualización de jornadas
	public function jornada_submit()
	{
		if($this->moguardia->isloged(true))
		{
			$id = $this->input->post('id', true);

			$t = $this->motorneos->init();
			$t->jornada_id($id);
			$t->jornada_nombre($this->input->post('DenomJor', true));
			$t->grupo_id($this->input->post('grupoId', true));

			if($id == 0)
				$r = $t->jornada_crear();
			else
				$r = $t->jornada_actualizar();

			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}

	//Inserción y actualización de partidos
	public function partido_submit()
	{
		if($this->moguardia->isloged(true))
		{
			$p = $this->mopartido->init();
			$p->id($this->input->post('id', true));
			$p->equipo1($this->input->post('eq1', true));
			$p->equipo2($this->input->post('eq2', true));
			$p->jornada($this->input->post('idjorn', true));
			$p->cancha($this->input->post('tipoCancha', true));
			$p->fecha($this->input->post('partidoFecha', true));
			$p->hora($this->input->post('partidoHora', true));
			$p->pendiente((int)$this->input->post('es_pendiente', true) == 1);

			/*
			Ricardo 26 oct 2015
			Se reportó un error en el que sólamente se registraba un equipo.
			Verificar que ambos equipos existan
			*/

			/*
			Ricardo 13 oct 2016
			Agregar información de los árbitros
			*/
			$p->setArbitros(array(
				$this->input->post('formArbitro1', true),
				$this->input->post('formArbitro2', true),
				$this->input->post('formArbitro3', true)
			));
			
			/*
			Ricardo 28 oct 2016
			Agregar información de faltas de los árbitros
			*/			
			for($i = 1; $i < 4; $i++)
			{
				for($ii = 1; $ii < 5; $ii++)
				{
					$arbitroFaltaIndice = 'arbitro' . $i . 'faltas' . $ii; 
					$p->addArbitrosFalta($arbitroFaltaIndice, $this->input->post($arbitroFaltaIndice));
				}
			}

			if($p->id == 0)
				$r = $p->crear();
			else
				$r = $p->actualizar();

			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}

	//Eliminar un partido
	public function eliminar()
	{
		if($this->moguardia->isloged(true))
		{
			$p = $this->mopartido->init();
			$p->id($this->input->post('id', true));
			echo json_encode(array( 'error' => $p->eliminar() ? 0 : 1 ));
		}
		else show_404();
	}

	//Establecer puntaje de un partido
	public function puntaje($torneo, $categoria, $grupo, $id)
	{
		if($this->moguardia->isloged(true) and $torneo > 0 and $categoria > 0 and $id > 0)
		{
			//Info del grupo
			$t = $this->motorneos->init();
			$t->id($torneo);
			$t->grupo_categoria($categoria);
			$grupos = $t->grupos(true, true);
			foreach($grupos as $grupoInfo)
			{
				if($grupoInfo->ID_VueltaGpo == $grupo)
				{
					break;
				}
			}

			//Identificar partido
			foreach($grupoInfo->jornadas as $jornada)
			{
				foreach($jornada->partidos as $jornadaPartido)
				{
					if($jornadaPartido->ID_Partido == $id)
					{
						$partido = $jornadaPartido;
						break;
					}
				}
			}

			//Asociar información
			if(isset($partido))
			{
				$i['partido'] = $partido;
				$i['grupo'] = (object) array('id' => $grupoInfo->ID_VueltaGpo, 'nombre' => $grupoInfo->DenomVG);

				//Info de jugadores
				$this->load->model('moequipo');
				$e = $this->moequipo->init();
				$e->id($partido->equipos[0]['id']);
				$i['partido']->equipos[0]['jugadores'] = $e->info(true, $id)->jugadores;
				$e->id($partido->equipos[1]['id']);
				$i['partido']->equipos[1]['jugadores'] = $e->info(true, $id)->jugadores;

				//Mostrar vista
				//Cargar vistas de torneo
				$i['info'] = $t->info();
				$i['config'] = $t->config();
				$i['torneo'] = $torneo;
				$i['categoria'] = $i['info']->categorias[$categoria];
				// $i['equipos'] = $i['info']->equipos[$categoria];
				$i['formularioTorneo'] = $this->load->view('torneos/torneos_formulario', $i['info'], true);
				$i['vista'] = $this->load->view('torneos/partido_puntajes', $i, true);

				//Inicia carga de vistas
				$this->moheader->addJs('librerias/torneos/comunes.js');
				$this->moheader->addJs('librerias/torneos/torneos_listado.js');
				$this->moheader->addJs('librerias/torneos/partido_puntaje.js');
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
		else show_404();
	}

	//Registrar puntaje de un partido
	public function puntaje_submit()
	{
		if($this->moguardia->isloged(true))
		{
			$p = $this->mopartido->init();
			$p->id($this->input->post('partido', true));
			$p->jornada($this->input->post('jornada', true));
			$p->shootouts($this->input->post('shootouts', true));
			$p->shootEquipo($this->input->post('shootEquipo', true));
			$p->goles_jugadores($this->input->post('goles_jugador', true));
			$r = $p->setPuntaje();

			$this->output->set_content_type('application/json')->set_output(json_encode($r));
		}
		else show_404();
	}

	//Listado de partidos por cancha
	public function partidos_cancha()
	{
		if($this->moguardia->isloged(true) and $torneo > 0)
		{
			$this->load->view('torneos/partidos_cancha');
		}
	}
}
