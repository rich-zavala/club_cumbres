<?php
class Motorneos extends CI_Model
{
	var $registros;
	var $registros_cantidad = 0;
	var $tabla = '';
	var $id = 0;

	//Variables para crear un torneo
	var $tipo;
	var $nombre;
	var $agno;
	var $sueldos = array();

	//Variables para grupos
	var $grupo_categoria = 0;
	var $grupo_id = 0;
	var $grupo_nombre;
	var $grupo_publico;
	var $grupo_equipos = array();

	//Variables para jornadas
	var $jornada_id = 0;
	var $jornada_nombre;

	public function init(){ return $this; }
	public function tabla($i){ $this->tabla = $i; }
	public function id($i){ $this->id = (int)$i; }
	public function tipo($i){ $this->tipo = (int)$i; }
	public function nombre($i){ $this->nombre = $i; }
	public function agno($i){ $this->agno = (int)$i; }

	//Catálogo de todos los torneos
	public function generar()
	{
		$q = $this->db->select('*, TipoTorneo(TipoTorn) tipo')->order_by('YrTorn DESC, ID_Torneo DESC')->get('torneos');
		$this->registros_cantidad = $q->num_rows();
		if($this->registros_cantidad > 0) $this->registros = $q->result();
	}

	//Toda la información de un torneo
	public function info()
	{
		$r = array();
		if($this->id > 0) $r = $this->db->select('*, TipoTorneo(TipoTorn) tipo')->where('ID_Torneo', $this->id)->get('torneos')->row();

		//Obtener categorías
		$q = $this->db->select('cc.NomCat, tc.ID_CatTorn')
		->join('cat_catalog cc', 'cc.ID_Cat = tc.ID_Cat', 'inner')
		->where('tc.ID_Torneo', $this->id)
		->order_by('NomCat')
		->get('torneos_cats tc');

		//Obtener categorías
		$r->categorias = array();
		foreach($q->result() as $cat) $r->categorias[$cat->ID_CatTorn] = $cat;

		//Obtener equipos
		$r->equipos = array();

		$catsIds = array();
		foreach($r->categorias as $cat)
		{
			$catsIds[] = $cat->ID_CatTorn;
			$r->equipos[$cat->ID_CatTorn] = array();
		}

		if(count($catsIds) > 0)
		{
			$q = $this->db->select('equipos_catalog.ID_Equipo, cat_catalog.NomCat, equipos_catalog.NomEquipo, equipos_catalog.ID_CatTorn')
			->join('cat_catalog', 'torneos_cats.ID_Cat = cat_catalog.ID_Cat', 'inner')
			->join('equipos_catalog', 'torneos_cats.ID_CatTorn = equipos_catalog.ID_CatTorn AND torneos_cats.ID_CatTorn = equipos_catalog.ID_CatTorn', 'inner')
			->where( 'torneos_cats.ID_Torneo', $this->id)
			->where('torneos_cats.ID_CatTorn IN (' . implode($catsIds, ',') . ')')
			->order_by('cat_catalog.ID_Cat, equipos_catalog.NomEquipo')
			->get('torneos_cats');
		}
		else //Mostrar error: Eliminó todas las categorías
		{
			show_error("No hay categorías disponibles en este torneo. Elimínelo y vuélvalo a crear.<script>setTimeout(function(){ window.location = '".base()."' }, 8000)</script>");
		}

		foreach($q->result() as $e) $r->equipos[$e->ID_CatTorn][] = $e;

		//Agregar información de los árbitros
		$r->arbitros_sueldos = $this->db->where('torneo', $this->id)->get('arbitrosSueldos')->row();

		return $r;
	}

	//Obtener configuración
	public function config()
	{
		$config = array();
		foreach($this->db->get('config')->result() as $r) $config[$r->nom_config] = $r->val_config;
		return $config;
	}

	//Crear un torneo
	public function crear()
	{
		$i = array(
			'NomTorneo' => $this->nombre,
			'TipoTorn' => $this->tipo,
			'YrTorn' => $this->agno
		);
		$r = $this->db->insert('torneos', $i);
		$this->id = $this->db->insert_id();
		
		$this->registrar_sueldos();
		
		$this->session->set_flashdata('creado', true);
		return $r;
	}

	//Actualizar un torneo
	public function actualizar()
	{
		$i = array(
			'NomTorneo' => $this->nombre,
			'TipoTorn' => $this->tipo,
			'YrTorn' => $this->agno
		);
		$r = $this->db->where('ID_Torneo', $this->id)->update('torneos', $i);
		$this->session->set_flashdata('actualizado', true);

		$this->registrar_sueldos();		
		return $r;
	}
	
	//Registrar sueldos
	private function registrar_sueldos()
	{
		$this->sueldos['torneo'] = $this->id;
		
		if($this->db->where('torneo', $this->id)->get('arbitrosSueldos')->num_rows() > 0)
			return $this->db->where('torneo', $this->id)->update('arbitrosSueldos', $this->sueldos);
		else
			return $this->db->insert('arbitrosSueldos', $this->sueldos);
	}

	/*
	Funcionas para GRUPOS
	*/
	public function grupo_id($i){ $this->grupo_id = (int)$i; }
	public function grupo_nombre($i){ $this->grupo_nombre = trim($i); }
	public function grupo_publico($i){ $this->grupo_publico = (int)$i; }
	public function grupo_equipos($i){ $this->grupo_equipos = $i; }
	public function grupo_categoria($i){ $this->grupo_categoria = (int)$i; }

	//Grupos de un torneo
	public function grupos($incluirJornadas = false, $incluirPartidos = false)
	{
		$r = array();
		$q = $this->db->where('ID_CatTorn', $this->grupo_categoria)->get('vueltas_gpos');
		if($q->num_rows() > 0)
		{
			$r = $q->result();
			foreach($r as $k => $v)
			{
				//Obtener sus equipos
				$r[$k]->equipos = $this->db->select('e.ID_Equipo, e.NomEquipo')->join('equipos_catalog e', 'e.ID_Equipo = v.ID_Equipo')->distinct()
													->where('ID_VueltaGpo', $v->ID_VueltaGpo)->get('vg_equipos v')->result();

				//Obtener sus jornadas
				if($incluirJornadas)
				{
					$r[$k]->jornadas = $this->db->select('j.ID_Jornada, j.DenomJor')->join('vg_jorn v', 'v.ID_Jornada = j.ID_Jornada')
														->where('ID_VueltaGpo', $v->ID_VueltaGpo)->order_by('CAST(DenomJor AS UNSIGNED)', 'DESC')->get('jornadas j')->result();
				}

				//Alimentar información de partidos
				if($incluirPartidos)
				{
					if(count($r[$k]->jornadas) > 0) //Si hay jornadas
					{
						$jornadas = array();
						foreach($r[$k]->jornadas as $jor) $jornadas[] = $jor->ID_Jornada;

						$q = $this->db->select('p.ID_Jornada,
																		p.ID_Partido,
																		p.FechaHora,
																		DATE_FORMAT(FechaHora, "%W %d/%b/%Y") fecha,
																		DATE_FORMAT(FechaHora, "%H:%i") hora,
																		p.Es_Pendiente, Punt_Fue_Asig, p.TipoCancha,
																		GROUP_CONCAT(CONCAT(pp.ID_Equipo, "|", pp.Puntaje, "|", pago_servicio) ORDER BY pp.ID_Equipo) equipos,
																		IFNULL(ps.ID_Equipo, 0) ganoSO,
																		CONCAT(IFNULL(ap.arbitro1, ""), "|", IFNULL(ap.arbitro2, ""), "|", IFNULL(ap.arbitro3, "")) arbitros,
																		CONCAT(IFNULL(arbitro1faltas1,"0"),",",IFNULL(arbitro1faltas2,"0"),",",IFNULL(arbitro1faltas3,"0"),",",
																		IFNULL(arbitro1faltas4,"0"),",",IFNULL(arbitro2faltas1,"0"),",",IFNULL(arbitro2faltas2,"0"),",",
																		IFNULL(arbitro2faltas3,"0"),",",IFNULL(arbitro2faltas4,"0"),",",IFNULL(arbitro3faltas1,"0"),",",
																		IFNULL(arbitro3faltas2,"0"),",",IFNULL(arbitro3faltas3,"0"),",",IFNULL(arbitro3faltas4,"0")) arbitrosFaltas', false)
						->where('jornadas.ID_Jornada IN (' . implode($jornadas, ',') . ')')
						->join('partidos p', 'jornadas.ID_Jornada = p.ID_Jornada')
						->join('part_punt pp', 'p.ID_Partido = pp.ID_Partido', 'left')
						->join('part_gan_sout ps', 'ps.ID_Partido = p.ID_Partido', 'left')
						->join('arbitrosPartidos ap', 'ap.partido = p.ID_Partido', 'left')
						->join('arbitrospartidosfaltas apf', 'apf.arbitrospartido = ap.id', 'left')
						->group_by('p.ID_Partido')
						->order_by('FechaHora')
						->get('jornadas');
						
						// echo $this->db->last_query();
					}

					foreach($r[$k]->jornadas as $kj => $jor)
					{
						$r[$k]->jornadas[$kj]->partidos = array();
						$equipos_ocupados = array(); //Índice para identificar equipos con descanso
						foreach($q->result() as $partido)
						{
							if($jor->ID_Jornada == $partido->ID_Jornada)
							{
								$partido_equipos = explode(',', $partido->equipos);
								$partido->equipos = array();
								foreach($partido_equipos as $equipo_k => $equipo)
								{
									$equipo_puntaje = explode('|', $equipo);
									$nombre = null;

									//Identificar nombres de equipos
									foreach($r[$k]->equipos as $equipo_grupo)
									{
										if($equipo_grupo->ID_Equipo == $equipo_puntaje[0])
										{
											$nombre = $equipo_grupo->NomEquipo;
											break;
										}
									}

									$partido->equipos[] = array(
										'id' => $equipo_puntaje[0],
										'nombre' => $nombre,
										'puntaje' => $equipo_puntaje[1],
										'servicio' => $equipo_puntaje[2]
									);

									if(!is_array($partido->arbitros))
										$partido->arbitros = explode('|', $partido->arbitros);

									$equipos_ocupados[] = $equipo_puntaje[0];
								}

								$r[$k]->jornadas[$kj]->partidos[] = $partido;
							}
						}

						//Incluir equipos que descansan
						$r[$k]->jornadas[$kj]->descansos = $descansos = array();
						foreach($r[$k]->equipos as $equipo_grupo)
						{
							if(!in_array($equipo_grupo->ID_Equipo, $equipos_ocupados) and !in_array($equipo_grupo->ID_Equipo, $descansos))
							{
								$r[$k]->jornadas[$kj]->descansos[] = $equipo_grupo;
								$descansos[] = $equipo_grupo->ID_Equipo;
							}
						}
					}
				}
			}
		}

		return $r;
	}

	//Crear grupo de torneo
	public function grupo_crear()
	{
		$r = array( 'error' => 0 );

		$i = array(
			'ID_CatTorn' => $this->grupo_categoria,
			'DenomVG' => $this->grupo_nombre,
			'Es_Public' => $this->grupo_publico
		);

		//Insertar grupo
		if($this->db->insert('vueltas_gpos', $i))
		{
			$grupo_id = $this->db->insert_id();
			$r['redirect'] = "{$this->id}/{$this->grupo_categoria}/{$grupo_id}";

			//Insertar equipos que pertenecerán a este grupo
			if(is_array($this->grupo_equipos) and array_sum($this->grupo_equipos) > 0)
			{
				$i = array();
				foreach($this->grupo_equipos as $e)
				{
					$i[] = array(
						'ID_VueltaGpo' => $grupo_id,
						'ID_Equipo' => $e
					);
				}

				if(!$this->db->insert_batch('vg_equipos', $i))
				{
					$r['error'] = 2;
					break;
				}
			}
		}
		else $r['error']++;

		$this->session->set_flashdata('grupo_creado', $grupo_id);
		return $r;
	}

	//Actualizar grupo de torneo
	public function grupo_actualizar()
	{
		$r = array( 'error' => 0 );

		$i = array(
			'DenomVG' => $this->grupo_nombre,
			'Es_Public' => $this->grupo_publico
		);

		//Insertar grupo
		$actualizado = $this->db->where('ID_VueltaGpo', $this->grupo_id)->update('vueltas_gpos', $i);

		//Insertar equipos que pertenecerán a este grupo
		if($actualizado)
		{
			$w = array( 'ID_VueltaGpo' => $this->grupo_id );
			if(is_array($this->grupo_equipos) and array_sum($this->grupo_equipos) > 0)
			{
				//Eliminar equipos que fueron removidos
				$this->db->where($w)->where('ID_Equipo NOT IN (' . implode(",", $this->grupo_equipos) .')')->delete('vg_equipos');

				//Detectar cambios en equipos
				foreach($this->grupo_equipos as $e)
				{
					$w['ID_Equipo'] = $e;
					if($this->db->where($w)->get('vg_equipos')->num_rows() == 0)
					{
						if(!$this->db->insert('vg_equipos', $w))
						{
							$r['error'] = 2;
							break;
						}
					}
				}
			}
			else //Eliminar todos los equipos
			{
				$this->db->where($w)->delete('vg_equipos');
			}
		}
		else $r['error']++;

		$this->session->set_flashdata('editado', $this->grupo_id);
		return $r;
	}

	/*
	Funciones para Jornadas
	*/
	public function jornada_id($i){ $this->jornada_id = (int)$i; }
	public function jornada_nombre($i){ $this->jornada_nombre = trim($i); }

	//Información de una jornada
	public function jornada_info()
	{
		$q = $this->db->select('torneos.NomTorneo, cat_catalog.NomCat, jornadas.DenomJor')
		->join('jornadas', 'vg_jorn.ID_Jornada = jornadas.ID_Jornada')
		->join('vueltas_gpos', 'vg_jorn.ID_VueltaGpo = vueltas_gpos.ID_VueltaGpo')
		->join('torneos_cats', 'vueltas_gpos.ID_CatTorn = torneos_cats.ID_CatTorn')
		->join('torneos', 'torneos_cats.ID_Torneo = torneos.ID_Torneo')
		->join('cat_catalog', 'torneos_cats.ID_Cat = cat_catalog.ID_Cat')
		->where('vg_jorn.ID_Jornada', $this->jornada_id)
		->get('vg_jorn');
		return $q->row();
	}

	//Crear jornada de grupo
	public function jornada_crear()
	{
		$r = array( 'error' => 0 );

		$i = array(
			'DenomJor' => $this->jornada_nombre
		);

		//Insertar jornada
		if($this->db->insert('jornadas', $i))
		{
			$jornada_id = $this->db->insert_id();

			//Indezar jornada con grupo
			$i = array(
				'ID_VueltaGpo' => $this->grupo_id,
				'ID_Jornada' => $jornada_id
			);

			if(!$this->db->insert('vg_jorn', $i)) $r['error'] = 2;
		}
		else $r['error']++;

		$this->session->set_flashdata('jornada_creada', $jornada_id);
		return $r;
	}

	//Actualizar jornada de grupo
	public function jornada_actualizar()
	{
		$r = array( 'error' => 0 );

		//Verificar que no exista esta jornada duplicada
		$w = array(
			'ID_VueltaGpo' => $this->grupo_id,
			'DenomJor' => $this->jornada_nombre
		);

		if($this->db->select('v.ID_Jornada')->join('vg_jorn v', 'v.ID_Jornada = j.ID_Jornada')->where('v.ID_Jornada != ', $this->jornada_id)->where($w)->get('jornadas j')->num_rows() == 0)
		{
			$i = array(
				'DenomJor' => $this->jornada_nombre
			);

			if(!$this->db->where('ID_Jornada', $this->jornada_id)->update('jornadas', $i)) $r['error']++;
		}
		else $r['error'] = 2;
		return $r;
	}

	//Jugadores de este torneo
	public function jugadores_sanciones($jugador = 0, $listado = false)
	{
		$w = null;
		$g = ' GROUP BY ej.ID_Jugador';
		if($jugador > 0) //Sanciones de un sólo jugador
		{
			$w = ' AND ej.ID_Jugador = ' . $jugador;
			$g = ' GROUP BY s.id';
		}

		if($listado) //Sanciones de un torneo
		{
			$w = ' AND En_Listado = 1';
			$g .= ' ORDER BY name';
		}

		$s = "SELECT DISTINCT s.id, ej.ID_Jugador, ej.ID_Equipo, CONCAT(ej.NomJug, ' ', ej.ApeJug) name, ec.NomEquipo, s.PartSan, s.JorSan, s.En_Listado, IFNULL(NumGoles, 0) NumGoles,
					DATE_FORMAT(fechaRegistro, '%W %d/%b/%Y a las %H:%m') fecha,
					COUNT(s.id) sanciones
					FROM equipos_jugadores AS ej
					INNER JOIN equipos_catalog AS ec ON ec.ID_Equipo = ej.ID_Equipo
					INNER JOIN torneos_cats AS tc ON tc.ID_CatTorn = ec.ID_CatTorn
					LEFT JOIN goleadores AS g ON g.ID_Jugador = ej.ID_Jugador
					LEFT JOIN sancionados AS s ON s.ID_Jugador = ej.ID_Jugador
					WHERE tc.ID_Torneo = " . $this->id . $w . $g;
					// echo $s;
		return $this->db->query($s)->result();
	}
}
