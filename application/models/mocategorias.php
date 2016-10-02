<?php
class Mocategorias extends CI_Model
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
	public function campo($campo){ $this->campo = $campo; }
	
	//Catálogo de todos las categorias
	public function generar()
	{
		// $s = "*, IF(TabTmpAct = 1, 'Activo', 'Inactivo') label_rol, IF(estatus = 1, 'Activo', 'Inactivo') label_estatus";
		// $q = $this->db->select($s, false)->order_by('YrTorn DESC')->get('torneos');
		$q = $this->db->order_by('ID_Cat ASC')->get('cat_catalog');
		$this->registros_cantidad = $q->num_rows();
		if($this->registros_cantidad > 0) $this->registros = $q->result();
	}
	
	//Actualizar un torneo
	public function actualizar()
	{
		$i = array(
			'ID_Cat' => $this->id,
			'NomCat' => $this->nombre
			
		);
		$r = $this->db->where('ID_Cat', $this->id)->update('cat_catalog', $i);
		$this->session->set_flashdata('actualizado', true);
		return $r;
	}
	
	
	
	
	
	
}