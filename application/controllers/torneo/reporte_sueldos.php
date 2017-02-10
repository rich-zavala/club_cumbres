<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reporte_sueldos extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this->moguardia->isloged(true);
		$this->load->model('motorneos');
	}

	//Registro de visitantes
	public function index(){ show_404(); } //No usamos el index

	//Listado inicial
	public function imprimir()
	{
		$torneo = $this->input->get('torneo', true);
		$f1 = $this->input->get('fechaInicial', true);
		$f2 = $this->input->get('fechaFinal', true);
		
		//Acumular datos
		$fechas = array();
		$sumas = array();
		
		$q = $this->db->where('FechaHora BETWEEN "' . $f1 . ' 00:00:01" AND "' . $f2 . ' 23:59:59"')
		->where('ID_Torneo', $torneo)->order_by('FechaHora ASC, TipoCancha ASC')->get('reporte_sueldos');
		
		if($q->num_rows() > 0)
		{
			foreach($q->result() as $r)
			{
				if(!isset($fechas[$r->fechaId])) //Índice de fecha
					$fechas[$r->fechaId] = array();
				
				if(!isset($fechas[$r->fechaId][$r->TipoCancha])) //Índice de cancha
					$fechas[$r->fechaId][$r->TipoCancha] = array();
				
				//Información de los equipos
				$equipos = explode(',', $r->equipos);
				$equipo1 = explode('|', $equipos[0]);
				$equipo2 = explode('|', $equipos[1]);
				
				if(isset($equipo1[2]) > 0 and isset($equipo2[2]) > 0)
				{
					$r->equipos = array(
						array(
							'id' => $equipo1[0],
							'nombre' => $equipo1[1],
							'pago' => $equipo1[2]
						),
						array(
							'id' => $equipo2[0],
							'nombre' => $equipo2[1],
							'pago' => $equipo2[2]
						)
					);
					
					$fechas[$r->fechaId][$r->TipoCancha][] = $r;
					
					if(!isset($sumas[$r->fechaId])) //Índice de sumas de esta fecha
						$sumas[$r->fechaId] = array(
							'otros' => 0,
							'arbitrajes' => 0,
							'total' => 0,
							'iva' => 0,
							'neto' => 0
						);
						
					//Realizar cálculos
					$sumas[$r->fechaId]['otros'] += $r->cronoSueldo + $r->mesa
						+ ($r->servicio * $r->equipos[0]['pago'])
						+ ($r->servicio * $r->equipos[1]['pago']);
					
					$sumas[$r->fechaId]['arbitrajes'] += $r->arbitroSueldo1 + $r->arbitroSueldo2;
					$sumas[$r->fechaId]['total'] += $sumas[$r->fechaId]['otros'] + $sumas[$r->fechaId]['arbitrajes'];
					$sumas[$r->fechaId]['iva'] = ($sumas[$r->fechaId]['otros'] + $sumas[$r->fechaId]['arbitrajes']) * .16;
					$sumas[$r->fechaId]['neto'] = $sumas[$r->fechaId]['total'] + $sumas[$r->fechaId]['iva'];
				}
			}
		}
			
		if(count($fechas) == 0){
			echo "<h1>No hay partidos disponibles.</h1>";
			exit;
		}
		
		//Info del torneo
		$t = $this->motorneos->init();
		$t->id($torneo);
		$info = $t->info();
		
		//Generar la tabla
		$h = array(
			'include_css' => $this->moheader->include_css(),
			'include_js' => $this->moheader->include_js()
		);
		$this->load->view('layouts/header', $h);
		$this->load->view('reportes/sueldos', array(
			'info' => $info,
			'fechas' => $fechas,
			'sumas' => $sumas
		));
	}
}
