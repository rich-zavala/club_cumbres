<?php
class Mopartido extends CI_Model
{
	/*
	== Minutos que dura un juego según el tamaño de la cancha ==
	Útil para evitar colisiones de cancha-fecha
	*/
	var $duracion = array(
		1 => 130, //Cancha chica
		2 => 90 //Cancha grande
	);
	
	//Límites de partidos por cancha en el mismo dia y hora
	var $limite = array(
		1 => 1, //Cancha Chica
		2 => 3 //Cancha Chica
	);
	
	//Descripción de canchas
	var $canchas = array(
		1 => 'CH',
		2 => 'PRO'
	);
	
	var $id = 0;
	var $equipo1 = 0;
	var $equipo2 = 0;
	var $jornada = 0;
	var $cancha = 0;
	var $pendiente = false;
	var $fecha = '';
	var $hora = '';
	
	//Crear dateTime
	private function fechaHora(){ if($this->fecha != '' and $this->hora != '')  $this->fecha = $this->fecha . ' ' . $this->hora; }
	
	public function init(){ return $this; }
	public function id($i){ $this->id = (int)$i; }
	public function equipo1($i){ (int)$this->equipo1 = $i; }
	public function equipo2($i){ (int)$this->equipo2 = $i; }
	public function jornada($i){ (int)$this->jornada = (int)$i; }
	public function cancha($i){ (int)$this->cancha = $i; }
	public function fecha($i){
		(int)$this->fecha = $i;
		$this->fechaHora();
	}
	public function hora($i){
		(int)$this->hora = $i;
		$this->fechaHora();
	}
	public function pendiente($i){
		(bool)$this->pendiente = $i;
		if($this->pendiente)
		{
			$this->fecha = '0000-00-00';
			$this->hora = '00:00:00';
		}
	}
	
	/*
	Validadores
	*/
	//Verificar que no haya un partido en la misma hora para el límite de canchas permitido
	public function validar_cancha()
	{
		if(!$this->pendiente)
		{
			$w = "FechaHora BETWEEN '{$this->fecha}' AND ('{$this->fecha}' + INTERVAL {$this->duracion[$this->cancha]} MINUTE) AND ID_Partido != {$this->id}";
			$q = $this->db->select('ID_Partido')->where('TipoCancha', $this->cancha)->where($w)->get('partidos');
			return $q->num_rows() < $this->limite[$this->cancha];
		} else return true;
	}
	
	public function validar_equipos_partidos($edicion = false)
	{		
		$w = array( 'ID_Jornada' => $this->jornada );
		$q = $this->db->where($w, false)->where("(ID_Equipo = {$this->equipo1} OR ID_Equipo = {$this->equipo2})")
		->join('part_punt pp', 'pp.ID_Partido = p.ID_Partido', 'inner');
		
		//Para edición no comparar equipos del partido en edición
		$q->where('pp.ID_Partido != ' . $this->id);
		
		return $q->get('partidos p')->num_rows() == 0;
	}
	
	//Validar la disposición para un partido
	public function validar($edicion = false)
	{
		$r = array(
			'error' => 0,
			'id' => 0
		);
		
		//Verificar colisión
		if($this->equipo1 == $this->equipo2) $r['error'] = 1;
		
		//Si no es pendiente
		if($r['error'] == 0 and !$this->validar_cancha()) $r['error'] = 2;

		//Verificar si los equipos tienen partidos
		if($r['error'] == 0 and !$this->validar_equipos_partidos($edicion)) $r['error'] = 3;
		
		return $r;
	}
	
	//Formar arreglo para activeScript
	public function active_array()
	{
		return  array(
						'ID_Jornada' => $this->jornada,
						'FechaHora' => $this->fecha,
						'Es_Pendiente' => $this->pendiente ? 1 : 0,
						'TipoCancha' => $this->cancha
					);
	}
	
	//Agregar equipos a un partido
	private function agregar_equipos($partido)
	{
		//Registrar equipos
		$i = array(
			array(
				'ID_Partido' => $partido,
				'ID_Equipo' => $this->equipo1
			),
			array(
				'ID_Partido' => $partido,
				'ID_Equipo' => $this->equipo2
			)
		);
		return $this->db->insert_batch('part_punt', $i);
	}
	
	//Registro de nuevo partido
	/*
	Códigos de error:
	1 Equipos iguales
	2 Canchas no disponibles en fecha - hora
	3 Algún equipo con partido registrado previamente
	4 y 5 Error de BDD
	*/
	public function crear()
	{
		$r = $this->validar();
		
		//Registrar partido
		if($r['error'] == 0)
		{
			$i = $this->active_array();
			if(!$this->db->insert('partidos', $i))
				$r['error'] = 4;
			else
				$r['id'] = $this->db->insert_id();
		}
		
		//Registrar equipos
		if($r['error'] == 0 and !$this->agregar_equipos($r['id'])) $r['error'] = 5;
		
		//Notificar jugadores
		if($r['error'] == 0 and !$this->notificar()) $r['error'] = 6;
		
		//Rollback!!!
		if($r['error'] > 0)
		{
			$this->id($r['id']);
			$this->eliminar();
		}
		
		return $r;
	}
	
	public function actualizar()
	{
		$r = $this->validar(true);
		
		//Verificar que el partido no tenga puntaje asignado
		if($r['error'] == 0 and (int)$this->db->where('ID_Partido', $this->id)->get('partidos')->row()->Punt_Fue_Asig > 0) $r['error'] = 9;
		
		//Registrar partido
		if($r['error'] == 0)
		{
			$i = $this->active_array();
			if(!$this->db->where('ID_Partido', $this->id)->update('partidos', $i))
				$r['error'] = 4;
			else
				$r['id'] = $this->id;
		}
		
		//Registrar equipos
		if($r['error'] == 0 and !$this->db->where('ID_Partido', $this->id)->delete('part_punt')) $r['error'] = 5;
		if($r['error'] == 0 and !$this->agregar_equipos($this->id)) $r['error'] = 5;
		
		return $r;
	}

	//Notificar por email acerca de un partido nuevo
	public function notificar()
	{
		//Obtener información de los jugadores de los equipos
		$this->load->model('moequipo');
		$e = $this->moequipo->init();
		$e->id($this->equipo1);
		$e1_info = $e->info();
		$e->id($this->equipo2);
		$e2_info = $e->info();
		$jugadores =  (object) array_merge((array) $e1_info->jugadores, (array) $e2_info->jugadores);
		
		//Crear arreglo de emails de jugadores
		$emails = array();
		foreach($jugadores as $j) if(strlen(trim($j->EmailJug)) > 0) $emails[] = trim($j->EmailJug);
		
		if(count($emails) > 0)
		{
			//Info del torneo
			$this->load->model('motorneos');
			$t = $this->motorneos->init();
			$t->jornada_id($this->jornada);
			$info = $t->jornada_info();
			
			//Formateo de fecha y hora
			$fecha = @FormatoFechaHora($this->fecha);
			
			//Crear mensaje
			$msg = "<table width=550 border=0 cellpadding=0 cellspacing=0>
								<tr><td><img src=http://www.clubcumbres.com/2010/admin/imagenes/logocumbresced.gif width=225 height=62/></td></tr>
								<tr><td><br><br>
									Apreciable participante del torneo <b>{$info->NomTorneo}</b> en la categoría <b>{$info->NomCat}</b>:<br>
									Por este medio te notificamos que se ha programado un partido para tu equipo.
								<tr><td>&nbsp;</td></tr>
								<tr><td align=center style=color:#000000; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold>
								<b>{$e1_info->NomEquipo}</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;V.S.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>{$e2_info->NomEquipo}</b>
								</td></tr>
								<tr><td>&nbsp;</td></tr>
								<tr><td>
								Este partido es correspondiente a la jornada <b>{$info->DenomJor}</b> y esta programado para el dia <b>{$fecha}</b>, en la cancha <b>{$this->canchas[$this->cancha]}</b>.<br>
								<br>Agradeceremos tu puntualidad.</td></tr>
								<tr><td align=right><img src=http://www.clubcumbres.com/2010/admin/imagenes/futbolrapido.gif width=111 height=60/></td></tr>
							</table>";		
			
			//Crear objeto de email
			$this->load->library('email', emailConfig());
			$this->email->from(EMAIL_ADMIN, 'Club Cumbres');
			$this->email->to($emails);
			$this->email->subject('Nuevo partido programado');
			
			//Enviar
			$this->email->message($msg);
			$enviado = $this->email->send();
			if(!$enviado) echo $this->email->print_debugger();
			return $enviado;
		}
		else return false;
	}

	//Eliminar partido
	public function eliminar()
	{
		return $this->db->where('ID_Partido', $this->id)->delete('partidos');
	}

	//Establecer puntaje de un partido
	public function shootouts($i){ $this->shootouts = (int)$i; }
	public function shootEquipo($i){ $this->shootEquipo = (int)$i; }
	public function goles_jugadores($i){ $this->goles_jugadores = $i; }
	public function setPuntaje()
	{
		$r = array( 'error' => 0 );
		//Resetear los puntajes de este partido
		$this->db->where('ID_Partido', $this->id)->delete('goles_jornadas');
		
		foreach($this->goles_jugadores as $equipo => $jugadores) //Registrar goles
		{
			
			foreach($jugadores as $jugador => $goles)
			{
				$id = 0;
				$w = array(
					'ID_Jugador' => $jugador,
					'ID_Jornada' => $this->jornada,
					'ID_Partido' => $this->id,
					'ID_Equipo' => (int)$equipo,
					'NumGoles' => (int)$goles
				);
				
				if((int)$goles > 0) if(!$this->db->insert('goles_jornadas', $w)) $r['error']++;
			}
		}
		
		$this->db->where('ID_Partido', $this->id)->delete('part_gan_sout');
		if($r['error'] == 0 and $this->shootouts == 1) //Registrar victoria por shoot outs
		{
			$i = array(
				'ID_Partido' => $this->id,
				'ID_Equipo' => $this->shootEquipo
			);
			if(!$this->db->insert('part_gan_sout', $i)) $r['error']++;
		}
		
		if($r['error'] == 0) //Índice de puntaje asignado
		{
			$i = array( 'Punt_Fue_Asig' => 1 );
			if(!$this->db->where('ID_Partido', $this->id)->update('partidos', $i)) $r['error']++;
		}
		
		if($r['error'] == 0) //Actualizar puntajes
		{
			$s = "CALL setPuntaje({$this->id})";
			if(!$this->db->simple_query($s)) $r['error']++;
		}
		
		return $r;
	}
}