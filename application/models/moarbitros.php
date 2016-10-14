<?php
class Moarbitros extends CI_Model
{
	var $registros;
	var $registros_cantidad = 0;
	var $id = 0;
	var $nombre;
	var $telefono;
	var $activo = 1;

	public function init(){ return $this; }
	public function id($i){ $this->id = (int)$i; }
	public function nombre($i){ $this->nombre = $i; }
	public function fechaRegistro($i){ $this->fechaRegistro = $i; }
	public function telefono($i){ $this->telefono = $i; }
	public function activo($i){ $this->activo = (int)$i; }

	//Catálogo de todos los torneos
	public function generar()
	{
		$q = $this->db->select("*, DATE_FORMAT(fechaRegistro, '%e %b, %Y - %h:%i') fecha", false)->order_by('nombre')->get('arbitros');
		$this->registros_cantidad = $q->num_rows();
		if($this->registros_cantidad > 0) $this->registros = $q->result();
	}

	//Toda la información de un árbitro
	public function info()
	{
		$r = array();
		if($this->id > 0)
			$r = $this->db->select('*')->where('id', $this->id)->get('arbitros')->row();

		return $r;
	}

	//Crear un árbitro
	public function crear()
	{
		$i = array(
			'nombre' => $this->nombre,
			'telefono' => $this->telefono,
			'activo' => $this->activo
		);
		$r = $this->db->insert('arbitros', $i);
		$this->session->set_flashdata('creado', true);
		return $r;
	}

	//Actualizar un torneo
	public function actualizar()
	{
		$i = array(
			'nombre' => $this->nombre,
			'telefono' => $this->telefono
		);
		$r = $this->db->where('id', $this->id)->update('arbitros', $i);
		$this->session->set_flashdata('actualizado', true);
		return $r;
	}
}
