<?php
class Moaviso extends CI_Model
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
	public function campo($campo){ $this->campo = $campo; }
	
	//Catálogo de todos las categorias
	public function generar()
	{
		// $s = "*, IF(TabTmpAct = 1, 'Activo', 'Inactivo') label_rol, IF(estatus = 1, 'Activo', 'Inactivo') label_estatus";
		// $q = $this->db->select($s, false)->order_by('YrTorn DESC')->get('torneos');
		$q = $this->db->query("select a.ID_Aviso,t.NomTorneo,a.TextoAviso from torneos t ,avisos a where t.ID_Torneo=a.ID_torneo");
		
		$this->registros_cantidad = $q->num_rows();
		if($this->registros_cantidad > 0) $this->registros = $q->result();
		
		
	}
	
	public function torneo_activo() 
	{

     $query = $this->db->query('select count(*) as activo from torneos where estatus=1');

      

     if ($query->num_rows() > 0) {
         return $query->row()->activo;
     }
     return false;
    }
	
	
	
	
	
	
}