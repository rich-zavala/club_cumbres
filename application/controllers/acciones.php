<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Acciones extends CI_Controller {
	
	function __construct() {
		parent::__construct();
	}
	
	//Cambio en campo booleano
	public function cambio()
	{
		if($this->input->is_ajax_request() and $this->moguardia->isloged())
		{
			$tabla = trim($this->input->post('tabla', TRUE));
			$id = trim($this->input->post('id', TRUE));
			$campo = trim($this->input->post('campo', TRUE));
			
			$t = $this->mocomun->init();
			$t->tabla($tabla);
			$t->id($id);
			$t->campo($campo);
			
			$r = array(
				'id' => $id,
				'result' => $t->cambio()
			);
			$this->output->set_content_type('application/json')->set_output(json_encode( $r ));
		}
		else show_404();
	}
	
	//Eliminar registro
	/*
	Para "sancionados" nada se elimina, sólo cambia un índice
	*/
	public function eliminar()
	{
		if($this->input->is_ajax_request() and $this->moguardia->isloged())
		{
			$tabla = trim($this->input->post('tabla', TRUE));
			$id = trim($this->input->post('id', TRUE));
			
			if($tabla != 'sancionados')
			{
				$t = $this->mocomun->init();
				$t->tabla($tabla);
				$t->id($id);
				
				$r = array(
					'id' => $id,
					'result' => $t->eliminar()
				);
			}
			else
			{
				$this->load->model('mojugador');
				$j = $this->mojugador->init();
				$j->id($id);
				
				$r = array(
					'id' => $id,
					'result' => $j->sanciones_remover()
				);
			}
			
			$this->output->set_content_type('application/json')->set_output(json_encode( $r ));
		}
		else show_404();
	}
}