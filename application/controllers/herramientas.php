<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Herramientas extends CI_Controller {
	
	public function index(){ show_404(); }
	
	//Envío masivo de emails
	public function email()
	{
		if($this->moguardia->isloged(true))
		{
			$r['error'] = 0;
			$asunto = $this->input->post('asunto', true);
			$mensaje = $this->input->post('mensaje', true);
			$destinatario = $this->input->post('destinatario', true);
			$destinatario_valor = $this->input->post('destinatario_valor', true);
			
			//Encontrar emails de destinatarios
			$destinatarios = array();
			
			switch($destinatario)
			{
				case 'categoria':
					$s = "SELECT TRIM(j.EmailJug) email
								FROM torneos_cats AS t
								INNER JOIN equipos_catalog AS e ON t.ID_CatTorn = e.ID_CatTorn
								INNER JOIN equipos_jugadores AS j ON e.ID_Equipo = j.ID_Equipo
								WHERE t.ID_CatTorn = {$destinatario_valor} AND EmailJug IS NOT NULL AND EmailJug != ''";
					break;
				
				case 'equipo':
					$s = "SELECT TRIM(j.EmailJug) email
								FROM equipos_catalog AS e
								INNER JOIN equipos_jugadores AS j ON e.ID_Equipo = j.ID_Equipo
								WHERE e.ID_Equipo = {$destinatario_valor} AND EmailJug IS NOT NULL AND EmailJug != ''";
					break;
			}
			
			foreach($this->db->query($s)->result() as $v) $destinatarios[] = $v->email;
			$destinatarios = array_unique($destinatarios);
			
			//No hay emails
			if(count($destinatarios) == 0) $r['error']++;
			
			
			
			if($r['error'] == 0)
			{	
				$destinatarios_grupos = array_chunk($destinatarios, 15);
				$this->load->library('email');
				foreach($destinatarios_grupos as $d)
				{
					$this->email->initialize();
					$this->email->from(EMAIL_ADMIN, 'Club Cumbres');
					$this->email->to($d);
					$this->email->subject('Email Test');
					$this->email->message($mensaje);	
					if(!$this->email->send())
					{
						$r['error']++;
						sleep(1);
					}
				}
			}
			echo json_encode($r);
		}
	}
}