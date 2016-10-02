<?php
class Moguardia extends CI_Model
{
	function __construct() {
		parent::__construct();
	}
	
	function init(){ return $this;	}
	
	//Verificar que exista una sesión en el sistema
	public function isloged($redirect = false)
	{
		$isLoged = $this->session->userdata('acceso');
		if(!$isLoged)
			if($redirect)
			{
				$this->load->helper('url');
				redirect($this->config->item('base_url'));
			}
			else return false;
		else return true;
	}
	
	//Registrar sesión de usuario
	public function setLogin($u, $p)
	{
		$w = array(
			'Usuario' => $u,
			'HashPass' => sha1($p)
		);
		$q = $this->db->where($w)->get('usuarios_admin');
		if($q->num_rows() > 0)
		{
			$data = array(
				'acceso' => true,
				'horaLogueo' => now()
			);
			$data = array_merge($data, $q->row_array());
			$this->session->set_userdata($data);
		}
		else @$this->session->sess_destroy();
	}
	
	//Cerrar sesión
	public function logout()
	{	
		$data = array( 'acceso' => false );
		$this->session->set_userdata($data);
		@$this->session->sess_destroy();
	}
}