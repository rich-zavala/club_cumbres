<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acceso extends CI_Controller {

	//Registro de visitantes
	public function index()
	{
		if(!$this->moguardia->isloged(false))
		{
			$this->moheader->addJs('librerias/acceso.js'); //Agregar librería
			$h = array(
				'include_css' => $this->moheader->include_css(),
				'include_js' => $this->moheader->include_js()
			);
			$this->load->view('layouts/header', $h);
			$this->load->view('acceso');
			$this->load->view('layouts/footer');
		}
		else header("Location: " . INICIO . suffix());
	}
	
	//Validar contraseña
	public function login()
	{
		if($this->input->is_ajax_request())
		{
			// sleep(2);
			$user = trim($this->input->post('user', TRUE));
			$pass = trim($this->input->post('pass', TRUE));
			
			$this->moguardia->setLogin($user, $pass);
			$acceso = $this->moguardia->isloged() ? 0 : 1;
			echo json_encode( array( 'error' => $acceso ) );
		} else show_404();
	}
	
	//Cerrar sesión
	public function logout()
	{
		$this->moguardia->logout();
		header('Location: ' . $this->config->item('base_url'));
	}
}