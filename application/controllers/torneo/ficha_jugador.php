<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ficha_jugador extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
		$this->load->model('mopartido');
	}
	
	public function index(){ show_404(); } //No usamos el index
	
	function generar($equipo, $jugador)
	{
		if(!$this->moguardia->isloged(true)) exit();
		// p($this->session->all_userdata());
		
		// $idpart = $_GET['idpart'];
		/*$s = "SELECT part_punt.ID_Equipo, equipos_catalog.NomEquipo FROM part_punt, equipos_catalog WHERE part_punt.ID_Equipo=equipos_catalog.ID_Equipo AND part_punt.ID_Partido=".$idpart;
		$res_eqs = $this->db->query($s);

		$sql_part = "SELECT partidos.FechaHorP, jornadas.DenomJor FROM partidos, jornadas WHERE partidos.ID_Jornada=jornadas.ID_Jornada AND partidos.ID_Partido=".$idpart;
		$res_part = mysql_query($sql_part);
		$reg_part = mysql_fetch_array($res_part);
		
		//Obtener más información del partido
		$s = "SELECT
					j.DenomJor jornada,
					g.DenomVG grupo,
					cat_catalog.NomCat categoria,
					t.NomTorneo torneo
					FROM
					partidos AS p
					INNER JOIN jornadas AS j ON p.ID_Jornada = j.ID_Jornada
					INNER JOIN vg_jorn AS v ON j.ID_Jornada = v.ID_Jornada
					INNER JOIN vueltas_gpos AS g ON v.ID_VueltaGpo = g.ID_VueltaGpo
					INNER JOIN torneos_cats AS tc ON g.ID_CatTorn = tc.ID_CatTorn
					INNER JOIN torneos AS t ON tc.ID_Torneo = t.ID_Torneo
					INNER JOIN cat_catalog ON tc.ID_Cat = cat_catalog.ID_Cat
					WHERE
					p.ID_Partido = 4";
		$info = $this->db->query($s)->row();*/
		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ClubCumbres.com ~ Torneos ~ Ficha de Jugador</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #000000;
	}
.Estilo1 {
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}
</style>
<?php
	$w = array(
		'ID_Equipo' => $equipo,
		'ID_Jugador' => $jugador
	);
	$rows = $this->db->where($w)->get('equipos_jugadores')->row_array();
	// echo $this->db->last_query();

	// require('configuracion.inc');
	// $enlace = mysql_connect($host, $usuario, $password);
	// mysql_select_db($db, $enlace);

	// $sql=mysql_query("SELECT * FROM equipos_jugadores WHERE ID_Equipo='".$_GET['id_eq']."' AND ID_Jugador='".$_GET['id_jug']."'");
	// $rows=mysql_fetch_array($sql);
?>
	<table width="500" align="center" cellpadding="3" cellspacing="2" class="Estilo1">
		<tr><td width="200">Nombre: </td><td>&nbsp;</td><td width="300"><?=$rows['NomJug']. " ".$rows['ApeJug']?></td></tr>
		<tr><td>Direccion: </td><td>&nbsp;</td><td><?=$rows['DirJug']?></td></tr>
		<tr><td>Tel&eacute;fono Celular: </td><td>&nbsp;</td><td><?=$rows['TelJug']?></td></tr>
		<tr><td>Tel&eacute;fono Casa: </td><td>&nbsp;</td><td><?=$rows['TelCasaJug']?></td></tr>
		<tr><td>CURP: </td><td>&nbsp;</td><td><?=$rows['CurpJug']?></td></tr>
		<tr><td>Tipo de Sangre: </td><td>&nbsp;</td><td><?=$rows['TipoSangreJug']?></td></tr>
		<tr><td>Es Capitan?</td><td>&nbsp;</td><td><?=$rows['CapitanJug']?></td></tr>
		<tr><td>N&uacute;mero de Camiseta: </td><td>&nbsp;</td><td><?=$rows['NumCamisetaJug']?></td></tr>
		<tr><td>Posici&oacute;n Jugador: </td><td>&nbsp;</td><td><?=$rows['PosicionJug']?></td></tr>
		<tr><td>Lugar de Nacimiento: </td><td>&nbsp;</td><td><?=$rows['LNacJug']?></td></tr>
		<tr><td>Fecha de nacimiento: </td><td>&nbsp;</td><td><?=$rows['FNacJug']?></td></tr>
		<tr><td>Nacionalidad: </td><td>&nbsp;</td><td><?=$rows['NacionalidadJug']?></td></tr>
		<tr><td>Sexo: </td><td>&nbsp;</td><td><?=$rows['SexoJug']?></td></tr>
		<tr><td>Email: </td><td>&nbsp;</td><td><?=$rows['EmailJug']?></td></tr>
		<tr><td>Escuela: </td><td>&nbsp;</td><td><?=$rows['EscuelaJug']?></td></tr>
		<tr><td>Grado Escolar: </td><td>&nbsp;</td><td><?=$rows['GEscolarJug']?></td></tr>
		<tr><td>Empresa: </td><td>&nbsp;</td><td><?=$rows['EmpresaJug']?></td></tr>
		<tr><td>Tiempo Libre: </td><td>&nbsp;</td><td><?=$rows['TLibreJug']?></td></tr>
		<tr><td>Otros Deportes: </td><td>&nbsp;</td><td><?=$rows['ODeportesJug']?></td></tr>
		<tr><td>Torneos Jugados: </td><td>&nbsp;</td><td><?=$rows['TJugadosJug']?></td></tr>
	</table>
</body>
</html>
<?php
	}
}
?>