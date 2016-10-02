<?php
class Moequipo extends CI_Model
{
	// var $registros;
	// var $registros_cantidad = 0;
	// var $tabla = '';
	var $id = 0;
	var $nombre;
	var $categoria;
	var $jugadores_limite = 18;
	var $jugadores_insert = array();
	var $torneo;
	var $where = array();
	
	//Variables para crear un torneo
	// var $tipo;
	// var $nombre;
	// var $agno;
	
	public function init(){ return $this; }
	// public function tabla($i){ $this->tabla = $i; }
	public function id($i){
		$this->id = (int)$i;
		$this->where['ID_Equipo'] = $i;
		return $this;
	}
	public function nombre($i){ $this->nombre = $i; }
	public function categoria($i){ $this->categoria = $i; }
	public function torneo($i){ $this->torneo = $i; }
	
	public function info($conGoles = false, $partido = 0)
	{
		$r = $this->db->where($this->where)->get('equipos_catalog')->row();
		$r->jugadores = $this->jugadores($conGoles, $partido);
		return $r;
	}
	
	public function jugadores($conGoles = false, $partido = 0)
	{
		$jugadores = $this->db->select('*, IF(LENGTH(CurpJug) = 0, "~", CurpJug) CurpJug, TIMESTAMPDIFF(YEAR, FNacJug, CURDATE()) edad', false)
									->where($this->where)
									->order_by('NomJug, ApeJug DESC, NumFolioJug')
									->get('equipos_jugadores')
									->result();
		
		//Incluir goles de un partido
		if($conGoles)
		{
			//Jornada de partido
			$jornada_query = $this->db->select('ID_Jornada')->where('ID_Partido', $partido)->get('partidos');
			if($jornada_query->num_rows() > 0)
			{
				$jornada = $jornada_query->row()->ID_Jornada;
				
				//Ids de todos los jugadores de este equipo
				$jugadores_ids = array();
				foreach($jugadores as $k => $j) $jugadores_ids[] = $j->ID_Jugador;
				
				//Mostrar error de jugadores
				if(count($jugadores_ids) == 0) show_error("Uno de los equipos no tiene jugadores registrados.");
				
				//Obtener todos los goles de esta jornada
				//Nota: Se hace de esta manera porque es más rápido
				$where = array(
					'ID_Jornada' => $jornada
				);
				$goles_query = $this->db->where($where)->where('ID_Jugador IN (' . implode(",", $jugadores_ids) . ')')->get('goles_jornadas');
				
				foreach($jugadores as $k => $j)
				{
					$jugadores[$k]->goles = 0;
					if($goles_query->num_rows() > 0)
					{
						foreach($goles_query->result() as $goles)
						{
							if($goles->ID_Jugador == $j->ID_Jugador)
							{
								// $goles = $goles->row();
								$jugadores[$k]->goles = $goles->NumGoles;
							}
						}
					}
				}
			}
		}
		
		return $jugadores;
	}
	
	public function jugadores_cantidad()
	{
		return count($this->jugadores());
	}
	
	//Verificar si el nombre ya existe
	public function nombreExiste()
	{
		$q = $this->db->where('NomEquipo', $this->nombre);
		if($this->id > 0) $q->where('ID_Equipo !=', $this->id); //No comparar ID si es edición
		return $q->get('equipos_catalog')->num_rows() > 0;
	}
	
	//Crear un equipo
	public function crear()
	{
		$r = array( 'error' => 0 );
		
		//Verificar que no exista otro con mismo nombre
		if($this->nombreExiste())
		{
			$r['error']++;
			$r['msg'] = "El nombre de este equipo ha sido previamente registrado. Intenta nuevamente.";
		}
		else
		{
			//Insertar en equipos
			$i = array(
				'NomEquipo' => $this->nombre,
				'ID_CatTorn' => $this->categoria,
				'UsrEq' => $this->nombre . '_' . rand(1,99),
				'PassEq' => '-'
			);
			if($this->db->insert('equipos_catalog', $i))
			{
				$this->id($this->db->insert_id());
				$r['info'] = $i;
				
				//Crear registro en tabla de puntajes
				$tabla_puntajes = 'eq_punt_' . (($this->categoria == 1) ? 'rapido' : 'socc');
				$i = array(
					'ID_Equipo' => $this->id
				);
				if(!$this->db->insert($tabla_puntajes, $i))
				{
					$r['error']++;
					$r['msg'] = "Ha ocurrido un error y el equipo no fue registrado en la tabla de puntajes. Intenta de nuevo más tarde.";
				}
				else $this->session->set_flashdata('equipo_manipulado', $this->id); //Generar FlashData para colorear la fila de este equipo
			}
			else
			{
				$r['error']++;
				$r['msg'] = "Ha ocurrido un error y el equipo no fue registrado. Intenta de nuevo más tarde.";
			}
		}
		return $r;
	}
	
	//Actualizar un torneo
	public function actualizar()
	{
		$r = array(
			'error' => 0
		);
		
		//Verificar que no exista otro con mismo nombre
		if($this->nombreExiste())
		{
			$r['error']++;
			$r['msg'] = "El nombre de este equipo ha sido previamente registrado. Intenta nuevamente.";
		}
		else
		{
			//Actualizar
			$i = array(
				'NomEquipo' => $this->nombre,
				'ID_CatTorn' => $this->categoria
			);
			if(!$this->db->where('ID_Equipo', $this->id)->update('equipos_catalog', $i))
			{
				$r['error']++;
				$r['msg'] = "Ha ocurrido un error y los cambios no fueron registrados. Intenta de nuevo más tarde.";
			}
			else $this->session->set_flashdata('equipo_manipulado', $this->id); //Generar FlashData para colorear la fila de este equipo
		}
		return $r;
	}

	//Establecer arreglo de jugadores nuevos
	public function jugadores_insert($i)
	{
		if($this->id > 0)
		{
			foreach(explode("\n", $i) as $v) if(strlen(trim($v)) > 0) $this->jugadores_insert[] = trim($v);
			$this->jugadores_insert = array_unique($this->jugadores_insert);
			
			//Quitar jugadores ya registrados
		$q = $this->db->select('CONCAT(NomJug, " ", ApeJug) nombre', false)->where('ID_Equipo', $this->id)->get('equipos_jugadores');
			if($q->num_rows() > 0)
			{
				foreach($q->result() as $r)
				{
					$pos = array_search($r->nombre, $this->jugadores_insert);
					if($pos !== false) unset($this->jugadores_insert[$pos]);
				}
			}
		}
	}
	
	//Crear jugadores
	public function crear_jugadores()
	{
		$r = array(
			'error' => 0
		);
		
		$jugadores_nuevos = count($this->jugadores_insert);
		if($this->id > 0 and $jugadores_nuevos > 0)
		{
			//Verificar cuántos jugadores ya tiene este equipo
			$jugadores_actuales = $this->db->select('ID_Jugador')->where('ID_Equipo', $this->id)->get('equipos_jugadores')->num_rows();
			if($jugadores_actuales + $jugadores_nuevos <= $this->jugadores_limite)
			{
				//Crear arraglo de inserción
				$i = array();
				foreach($this->jugadores_insert as $j)
				{
					$i[] = array(
						'NomJug' => $j,
						'ID_Equipo' => $this->id
					);
				}
				
				//Insertar
				if(!$this->db->insert_batch('equipos_jugadores', $i))
				{
					$r['error']++;
					$r['msg'] = "Ha ocurrido un error y los cambios no fueron registrados. Intenta de nuevo más tarde.";
				}
				else
				{
					//Obtener todos los ids nuevos
					$firstId = $this->db->insert_id();
					$ids = $this->db->select('GROUP_CONCAT(ID_Jugador) ids')->where('ID_Jugador >=' . $firstId)->get('equipos_jugadores')->row()->ids;
					$this->session->set_flashdata('id_manipulado', $ids);
				}
			}
			else
			{
				$r['error']++;
				$r['msg'] = "El equipo ya cuenta con {$jugadores_actuales} jugadores. Al agregar estos {$jugadores_nuevos} más se habrá excedido el límite de {$this->jugadores_limite}.";
			}
		}
		else
		{
			$r['error']++;
			$r['msg'] = "No se identificaron nombres de jugadores nuevos.";
		}
		return $r;
	}

	//Obtener todos los equipos de un torneos según la clasificación
	public function torneos_equipos()
	{
		$equipos = array();
		$q = $this->db->where('ID_Torneo', $this->torneo)->order_by('NomTorneo')->get('torneos');
		if($q->num_rows() > 0)
		{
			foreach($q->result() as $torneo)
			{
				//Identificar el nombre de la categoría para buscar a sus similares
				$categoria = $this->db->where('ID_CatTorn', $this->categoria)->join('cat_catalog cc', 'tc.ID_Cat = cc.ID_Cat', 'inner')->get('torneos_cats tc')->row()->NomCat;
				
				//Buscar los equipos de este torneo en esta categoría
				$equipos = array();
				$where = array(
					'cc.NomCat' => $categoria,
					'tc.ID_Torneo' => $this->torneo
				);
				$q = $this->db->select('ec.ID_Equipo, ec.NomEquipo, tc.ID_Torneo, tc.ID_CatTorn')
							->where($where)
							->join('torneos_cats tc', 'ec.ID_CatTorn = tc.ID_CatTorn', 'inner')
							->join('cat_catalog cc', 'tc.ID_Cat = cc.ID_Cat', 'inner')
							->order_by('NomEquipo')
							->get('equipos_catalog ec');
				if($q->num_rows() > 0)
				{
					$equipos = $q->result();
				}
			}
		}
		
		return $equipos;
	}

	//Importar un equipo a un torneo diferente
	public function importar()
	{
		//Obtener información del equipo original
		$equipo_original = $this->id;
		$info = $this->info();
		
		//Establecer parámetros para "crear()"
		$this->nombre($info->NomEquipo);
		$r = $this->crear();
		
		if($r['error'] == 0) //Insertar jugadores
		{
			$s = "INSERT IGNORE INTO equipos_jugadores SELECT NULL, {$this->id}, NomJug, ApeJug, TelJug, DirJug, FNacJug, NumFolioJug, CapitanJug, NumCamisetaJug, PosicionJug,
						LNacJug, CurpJug, TipoSangreJug, NacionalidadJug, SexoJug, TelCasaJug, EmailJug, EscuelaJug, GEscolarJug, EmpresaJug, TLibreJug, ODeportesJug,
						TJugadosJug, NumAfiJug, AlturaJug, PesoJug FROM equipos_jugadores WHERE ID_Equipo = " . $equipo_original;
			if(!$this->db->query($s))
			{
				$r['error']++;
				$this->db->where('ID_Equipo', $this->id)->delete('equipos_catalog'); //Rollback
			}
		}
		
		return $r;
	}

	//Jugadores goleadores
	public function goleadores($limit, $equipo)
	{
		$r = array();
		$q = $this->db->select('ej.ID_Jugador, ej.NomJug, ej.ApeJug, ec.NomEquipo, g.NumGoles')
					->where('ec.ID_CatTorn', $this->categoria)
					->join('equipos_jugadores ej', 'g.ID_Jugador = ej.ID_Jugador')
					->join('equipos_catalog ec', 'ej.ID_Equipo = ec.ID_Equipo')
					->order_by('g.NumGoles', 'DESC');
					
		if($equipo > 0) $q->where('ej.ID_Equipo', $equipo);
		
		if((int)$limit > 0) $q = $q->limit((int)$limit);
			
		$q = $q->get('goleadores g');
		
		if($q->num_rows() > 0) $r = $q->result();
		
		return $r;
	}
}