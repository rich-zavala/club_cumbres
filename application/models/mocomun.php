<?php
/*
Coleccción de herramientas comunmente usadas en los controladores
*/
class Mocomun extends CI_Model
{
	var $tabla;
	var $id = 0;
	var $campo;
	
	function __construct()
	{
		parent::__construct();
		$this->db->query('SET lc_time_names = "es_MX"');
	}
	
	public function init(){ return $this; }
	
	public function tabla($tabla){ $this->tabla = $tabla; }
	public function id($id){ $this->id = (int)$id; }
	public function campo($campo){ $this->campo = $campo; }
	
	//Eliminador
	public function eliminar()
	{
		return $this->db->where($this->getPrimaryKey(), $this->id)->delete($this->tabla) ? 1 : 0;
	}
	
	//Ejecutar cambio en campo booleano
	public function cambio()
	{
		$s = "UPDATE {$this->tabla} SET {$this->campo} = !{$this->campo} WHERE " . $this->getPrimaryKey() . " = {$this->id}";
		$this->db->query($s);
		
		//Librerías según tabla
		if($this->tabla == 'torneos' or $this->tabla == 'config')
		{			
			$this->load->model('motorneos');
			$t = $this->motorneos->init();
			$t->id($this->id);
		}
		
		//Retorno de información según tabla
		switch($this->tabla)
		{
			case 'torneos':
				return (int)$t->info()->{$this->campo};
			case 'config': //Switch de inscripciones
				$config = $t->config();
				return $config['estatus_registro'];
				break;
		}
	}
	
	//Obtener nombre del campo de clave primaria de una tabla
	public function getPrimaryKey()
	{
		$pk = '';
		foreach($this->db->field_data($this->tabla) as $r)
		{
			if($r->primary_key > 0)
			{
				$pk = $r->name;
				break;
			}
		}
		return $pk;
	}
}