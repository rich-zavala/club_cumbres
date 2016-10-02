<?php
class Mopublicidad extends CI_Model
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
	public function tipo($i){ $this->tipo = $i; }
	public function file_name($i){ $this->file_name = $i; }
	public function campo($campo){ $this->campo = $campo; }
	
	//Catálogo de todos las categorias
	public function generar()
	{
		// $s = "*, IF(TabTmpAct = 1, 'Activo', 'Inactivo') label_rol, IF(estatus = 1, 'Activo', 'Inactivo') label_estatus";
		// $q = $this->db->select($s, false)->order_by('YrTorn DESC')->get('torneos');
		$q = $this->db->order_by('ID_Anuncio')->get('anuncios');
		$this->registros_cantidad = $q->num_rows();
		if($this->registros_cantidad > 0) $this->registros = $q->result();
		
		
	}
	
	public function tipoArchivo()
	{
		// $s = "*, IF(TabTmpAct = 1, 'Activo', 'Inactivo') label_rol, IF(estatus = 1, 'Activo', 'Inactivo') label_estatus";
		// $q = $this->db->select($s, false)->order_by('YrTorn DESC')->get('torneos');
		$q = $this->db->order_by('idTipoAnuncio')->get('tipoanuncio');
		$this->reg_cant = $q->num_rows();
		if($this->reg_cant > 0) $this->reg = $q->result();
		
		
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
			'NomArchivo' => $this->file_name,
			'TipoAnuncio' => $this->tipo
		);
		$r = $this->db->insert('anuncios', $i);
		$this->session->set_flashdata('creado', true);
		return $r;
	}
	
	
	
	
	
	
	
}