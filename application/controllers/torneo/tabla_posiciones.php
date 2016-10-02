<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tabla_posiciones extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		// $this->load->model('motorneos');
		// $this->load->model('mopartido');
	}
	
	public function index()
	{
		// $id_ctorn = $_SESSION["admin_cc"]->id_ctorn;
		// $tip_torn = $_SESSION["admin_cc"]->tip_torn;
		// $ct_con_vuelgrup = $_SESSION["admin_cc"]->ct_con_vuelgrup;
		
		$id_ctorn = $this->input->post('categoria', true);
		$tip_torn = $this->input->post('tipo', true);
		$ct_con_vuelgrup = $this->input->post('vuelta', true);
		
		// $id_ctorn = 318;
		// $tip_torn = 1;
		// $ct_con_vuelgrup = true;
		// $categoria_nombre = "HOLA";
		
		$this->load->model('moposiciones');
		$this->moposiciones->tip_torn = $tip_torn;

		?>
		<!--<h5 align="center"><?=$categoria_nombre?></h5>
		<div align="left" style="font-size:9px; padding-bottom:10px; padding-left:55px"><strong>Fecha de Impresi&oacute;n:</strong> <? echo date("d-m-Y", time()); ?></div>-->
		<?php
		//======================== TABLA DE POSICIONES SIMPLE ==========================//
		if(!$ct_con_vuelgrup)
		{
			$sql_res_jon = "SELECT jornadas.ID_Jornada, jornadas.DenomJor FROM jornadas, tcat_jorn WHERE tcat_jorn.ID_Jornada=jornadas.ID_Jornada AND tcat_jorn.ID_CatTorn='".$id_ctorn."' ORDER BY DenomJor + 1 DESC";
			$sql_res_jon2 = $sql_res_jon;
			$this->moposiciones->generar();
			
			if(mysql_num_rows($res_eqs) == 0)
			{
				echo '<br />
				<table class="tbl_posiciones" width="650" align="center" border="0" cellspacing="0" cellpadding="3">
				<tr style="font-weight:bold">
				<td style="padding-top:35px; padding-bottom:35px">A&uacute;n no se han creado equipos en esta categor&iacute;a</td>
				</tr>
				</table><br>';
			}

			//======================== TABLA DE POSICIONES CON VUELTAS / GRUPOS ==========================//	
		}
		elseif ($ct_con_vuelgrup)
		{
			// $str_vg = $_SESSION["admin_cc"]->str_vg;
			$res_vg = mysql_query("SELECT ID_VueltaGpo, DenomVG, Es_Public, Con_Historial FROM vueltas_gpos WHERE ID_CatTorn='".$id_ctorn."' AND Es_Public=1");
			if(mysql_num_rows($res_vg) == 0)
			{
				echo '<table class="tbl_posiciones" width="650" align="center" border="0" cellspacing="0" cellpadding="3">
				<tr style="font-weight:bold">
				<td style="padding-top:35px; padding-bottom:35px">A&uacute;n no se han creado categorías en esta categor&iacute;a</td>
				</tr>
				</table>';
			}
			
			while($reg_vg = mysql_fetch_array($res_vg))
			{
				echo "<h4>Grupo {$reg_vg['DenomVG']}</h4>";
				$es_pub = $reg_vg['Es_Public'];
				$con_hist = $reg_vg['Con_Historial'];
			?>
			<!--<div align="left" style="padding-left:50px; padding-top:10px; padding-bottom:5px; font-weight:bold">Grupo X</div>-->
			<?php
				$sql_res_jon = "SELECT j.ID_Jornada, j.DenomJor FROM jornadas j, vg_jorn v WHERE v.ID_Jornada = j.ID_Jornada AND v.ID_VueltaGpo='".$reg_vg['ID_VueltaGpo']."' ORDER BY DenomJor + 1 DESC";
				$sql_eqs_vg_A = "vg_equipos, ";
				$sql_eqs_vg_B = "vg_equipos.ID_Equipo=equipos_catalog.ID_Equipo AND vg_equipos.ID_VueltaGpo='".$reg_vg['ID_VueltaGpo']."' AND ";
				$sql_res_jon2 = $sql_res_jon;
			
				// include('tabla_pos_B.php');
				$this->moposiciones->sql_res_jon = $sql_res_jon;
				$this->moposiciones->es_pub = $es_pub;
				$this->moposiciones->con_hist = $con_hist;
				$this->moposiciones->sql_eqs_vg_A = $sql_eqs_vg_A;
				$this->moposiciones->sql_eqs_vg_B = $sql_eqs_vg_B;
				$this->moposiciones->sql_res_jon2 = $sql_res_jon2;
				$this->moposiciones->id_ctorn = $id_ctorn;
				$this->moposiciones->generar();
			}
		}

		if($tip_torn == 1) // Rapido
		{
		?>
		<hr />
		<div class="text-center">
			<small align="center"><b>JG</b> (Juegos Ganados), <b>JP</b> (Juegos Perdidos), <b>JGS</b> (Juegos Ganados por <em>Shoot Out</em>),<br /><b>JPS</b> (Juegos Perdidos por <em>Shoot Out</em>), <b>DG</b> (Diferencia de Goles).</small>
		</div>
		<?php
		}
		elseif($tip_torn == 2) // Soccer
		{
		?>
		<hr />
		<div class="text-center">
			<small align="center"><b>JG</b> (Juegos Ganados), <b>JP</b> (Juegos Perdidos), <b>JE</b> (Juegos Empatados), <b>DG</b> (Diferencia de Goles).</small>
		</div>
		<?php
		}
	}
}
?>