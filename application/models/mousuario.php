<?php
class Mousuario extends CI_Model
{
	function __construct(){
		
			parent::__construct();
			$this->load->database();
	}//Fin del constructor
	
	
	var $registros;
	var $registros_cantidad = 0;
	
	//Variables para cambio en campo booleano
	var $tabla;
	var $id = 0;
	var $campo;
	
	public function init(){ return $this; }
	
	public function tabla($tabla){ $this->tabla = $tabla; }
	public function id($id){ $this->id = (int)$id; }
	public function nombre($i){ $this->nombre = $i; }
	public function usuario($i){ $this->usuario = $i; }
	public function pass($i){ $this->pass = $i; }
	public function campo($campo){ $this->campo = $campo; }
	
	//Catálogo de todos las categorias
	public function generar()
	{
		// $s = "*, IF(TabTmpAct = 1, 'Activo', 'Inactivo') label_rol, IF(estatus = 1, 'Activo', 'Inactivo') label_estatus";
		// $q = $this->db->select($s, false)->order_by('YrTorn DESC')->get('torneos');
		$q = $this->db->order_by('ID_Usuario')->get('usuarios_admin');
		$this->registros_cantidad = $q->num_rows();
		if($this->registros_cantidad > 0) $this->registros = $q->result();
		
		
	}
	
	public function info()
	{
		$r = array();
		if($this->id > 0) $r = $this->db->select('NomUsr,Usuario,HashPass')->where('ID_Usuario', $this->id)->get('usuarios_admin')->row();
		
		return $r;
		
	}
	
	
	
	
	public function crear()
	{
		$i = array(
			'NomUsr' => $this->nombre,
			'Usuario' => $this->usuario,
			'HashPass' => sha1($this->pass)
		);
		$r = $this->db->insert('usuarios_admin', $i);
		$this->session->set_flashdata('creado', true);
		return $r;
	}
	
	//Actualizar un usuario
	public function actualizar()
	{
		$i = array(
			'NomUsr' => $this->nombre,
			'Usuario' => $this->usuario,
			'HashPass' => sha1($this->pass)
		);
		$r = $this->db->where('ID_Usuario', $this->id)->update('usuarios_admin', $i);
		$this->session->set_flashdata('actualizado', true);
		return $r;
	}
	
	
	
	
	
}