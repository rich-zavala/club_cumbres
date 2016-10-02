<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailtest extends CI_Controller {

	//Registro de visitantes
	public function index()
	{
		$this->load->library('email');
		$this->email->initialize();
		$this->email->to('rich.zavalac@gmail.com');
		$this->email->from('no-reply@clubcumbres.com', 'Club Cumbres');
		$this->email->bcc('them@their-example.com'); 

		$this->email->subject('Email Test');
		$this->email->message('Testing the email class. <h1>¡CLIB CUMBRES!</h1>ññññáááá');

		$this->email->send();

		echo $this->email->print_debugger();
	}
}