<?php
class Mojugador extends CI_Model
{
		//Variables de formulario
	var $ID_Jugador = 0;
	var $ID_Equipo = 0;

	//Variables de información
	var $jugador_info;
	
	public function init(){ return $this; }
	public function id($i){ $this->ID_Jugador = (int)$i; }
	public function equipo($i){ $this->ID_Equipo = $i; }
	
	//Información de un jugador
	public function info()
	{
		$r = $this->db->where('ID_Jugador', $this->ID_Jugador)->get('equipos_jugadores')->row();
		return $r;
	}
	
	//Actualizar información de un jugador
	public function actualizar($i)
	{
		$r = array( 'error' => 0 );
		
		if(!$this->db->where('ID_Jugador', $this->ID_Jugador)->update('equipos_jugadores', $i))
			$r['error']++;
		else
			$this->session->set_flashdata('id_manipulado', $this->ID_Jugador);

		return $r;
	}
	
	/*
	Métodos para  sanciones
	*/
	var $sancion_partidos;
	var $sancion_jornadas;
	var $id_sancion = 0;
	public function sancion_partidos($i){ $this->sancion_partidos = $i; }
	public function sancion_jornadas($i){ $this->sancion_jornadas = $i; }
	public function id_sancion($i){ $this->id_sancion = (int)$i; }
	
	//Aplicar sanción a jugador
	public function sancionar()
	{
		$r = array( 'error' => 0 );
		
		$i = array(
			'ID_Jugador' => $this->ID_Jugador,
			'PartSan' => $this->sancion_partidos,
			'JorSan' => $this->sancion_jornadas
		);
		
		if(!$this->db->insert('sancionados', $i))
		{
			$r['error']++;
		}
		else
		{
			$id = $this->db->insert_id();
			$this->session->set_flashdata('nueva_sancion', $id);
		}
		
		return $r;
	}
	
	//Remover una sanción de un jugador
	public function sancion_remover()
	{
		$r = array( 'error' => 0 );
		$w = array( 'id' => $this->id_sancion );
		$i = array( 'En_Listado' => 0 );
		if(!$this->db->where($w)->update('sancionados', $i)) $r['error']++;		
		return $r;
	}
	
	//Remover todas las sanciones de un jugador
	public function sanciones_remover()
	{
		$w = array( 'ID_Jugador' => $this->ID_Jugador );
		$i = array( 'En_Listado' => 0 );
		return ($this->db->where($w)->update('sancionados', $i)) ? 1 : 0;
	}
}