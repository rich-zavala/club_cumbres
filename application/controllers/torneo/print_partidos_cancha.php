<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_partidos_cancha extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
		$this->load->model('mopartido');
	}
	
	public function index(){ show_404(); } //No usamos el index
	
	function imprimir()
	{
		if(!$this->moguardia->isloged(true)) exit();
		$f1 = $_GET['fechaInicial'];
		$f2 = $_GET['fechaFinal'];
		$tipo = $_GET['tipoCancha'];
		$torneo = $_GET['torneo'];
		
		//Etiqueta de cancha
		$tc = $tipo == 1 ? 'Cancha chica' : 'Cancha profesional';
		
		//Info del torneo
		$t = $this->motorneos->init();
		$t->id($torneo);
		$info = $t->info();
		
		//Información de los partidos
		$q = $this->db->select('p.ID_Partido, p.FechaHora, e.NomEquipo, DATE_FORMAT(p.FechaHora, "%W (%d de %M de %Y)") fecha, DATE_FORMAT(p.FechaHora, "%h:%i %p") hora, DATE_FORMAT(p.FechaHora, "%H:%i") h,', false)
				->join('part_punt pp', 'p.ID_Partido = pp.ID_Partido', 'inner')
				->join('equipos_catalog e', 'pp.ID_Equipo  = e.ID_Equipo', 'inner')
				->where('FechaHora BETWEEN "' . $f1 . ' 00:00:01" AND "' . $f2 . ' 23:59:59"')
				->where('TipoCancha', $tipo)
				->order_by('p.FechaHora, p.ID_Partido')
				->get('partidos p');
		if($q->num_rows() == 0)
		{
			echo "<h1>No hay partidos disponibles.</h1>";
			exit;
		}
		else
		{
			$partidos = array();
			$horarios = array();
			foreach($q->result() as $r)
			{
				$pr = $partidos[$r->ID_Partido][] = $r;
				$horarios[$r->fecha][] = $pr;
			}
		}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Club Cumbres ~ Torneos ~ Modulo Administrativo</title>
<link rel='stylesheet' href='<?=base()?>r_/librerias/print.css'></link>
<style type="text/css">
body, div, td{
background-color:#FFFFFF;
color:#000000;
font-family:Verdana, Arial, Helvetica, sans-serif;
font-size:10pt;
}
</style>
</head>
<body style="margin:0px">
<table align="center" class="cedula_completa" width="770" border="0" cellspacing="0" cellpadding="2px">
  <tr>
    <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100"><img src="<?=base()?>r_/images/cedula-izq.png" alt="Logo CCumbres" width="181" height="60" /></td>
					<td width="550"><div align="center" style="font-size:13pt"><?=$info->NomTorneo?><br>Rol de Juegos (<?=$tc?>)</div></td>
					<td width="100"><img src="<?=base()?>r_/images/cedula-der.png" alt="Logo FRapido Mexico" width="181" height="60" /></td>
				</tr>
			</table>
		</td>
  </tr>
  <tr>
    <td>
			<?php
			foreach($horarios as $dia)
			{
			?>
				<hr>
				<table class="tbl_cedula" width="100%" align="center" cellspacing="0" cellpadding="0" style="margin-bottom:10px">
					<tr>
						<td colspan="4" style="background-color: #D5DEE8;"><b><?=$dia[0]->fecha?></b></td>
					</tr>
					<?php
					for($e = 0; $e < count($dia); $e++)
					{
					?>
					<tr class="tr_jugadores">
						<td width="15%"><b><?=$dia[$e]->hora?></b></td>
						<td width="37.5%"><?=$dia[$e]->NomEquipo?></td>
						<td width="*" align="center" style="background-color: #D5DEE8;">VS</td>
						<?php $e++; ?>
						<td width="37.5%"><?=$dia[$e]->NomEquipo?></td>
					</tr>
					<?php
					}
					?>
				</table>
			<?php
			}
			?>
		</td>
	</tr>
</table>
</body>
</html>
<?php
	}
}
?>