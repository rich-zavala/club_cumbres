<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Print_cedulas extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->load->model('motorneos');
		$this->load->model('mopartido');
	}
	
	public function index(){ show_404(); } //No usamos el index
	
	function imprimir($idpart)
	{
		if(!$this->moguardia->isloged(true)) exit();
		// p($this->session->all_userdata());
		
		// $idpart = $_GET['idpart'];
		$s = "SELECT part_punt.ID_Equipo, equipos_catalog.NomEquipo FROM part_punt, equipos_catalog WHERE part_punt.ID_Equipo=equipos_catalog.ID_Equipo AND part_punt.ID_Partido=".$idpart;
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
					p.ID_Partido = ".$idpart;
		$info = $this->db->query($s)->row();
		
		// p($info);
		
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
<script language="javascript" type="text/javascript">

var gV1;

function abreVentana (aPag, aAncho, aAlto, aArriba, aIzquierda, aAtributos){
	var mAtrib= aAtributos.split(":");
	var mAtribL= mAtrib.length;
	
	var cadAtrib="";
	var cBand=false;
	var fBand=false;
	
	for (var i=0; i<mAtribL; i++){
		switch (mAtrib[i]){
			case "t": cadAtrib += "toolbars=yes,"; break;
			case "l": cadAtrib += "location=yes,"; break;
			case "m": cadAtrib += "menubar=yes,"; break;
			case "st": cadAtrib += "status=yes,"; break;
			case "sb": cadAtrib += "scrollbars=yes,"; break;
			case "r": cadAtrib += "resizable=yes,"; break;
			case "f": cadAtrib += "fullscreen=yes,"; fBand=true; break;
			case "c": 
				var an = (screen.width - aAncho)/2;
				var alt = (screen.height - aAlto)/2;
				cadAtrib += "top="+alt+",left="+an+",";
				cBand=true;
				break;
		}
	}
	
	if(fBand){
		cadAtrib += "height="+screen.height+", width="+screen.width;
	} else {
		cadAtrib += "height="+aAlto+", width="+aAncho;
	}
	
	if (!cBand){
		cadAtrib += ", top="+aArriba+",left="+aIzquierda;
	}
	
	gV1 = window.open(aPag, "Identificador", cadAtrib);
}
</script>
</head>

<body style="margin:0px">
<table align="center" class="cedula_completa" width="770" border="0" cellspacing="0" cellpadding="2px">
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100"><img src="<?=base()?>r_/images/cedula-izq.png" alt="Logo CCumbres" width="181" height="60" /></td>
        <td width="550"><div align="center" style="font-size:13pt"><?=$info->torneo?></div></td>
        <td width="100"><img src="<?=base()?>r_/images/cedula-der.png" alt="Logo FRapido Mexico" width="181" height="60" /></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
	  	<td><table class="tbl_cedula" align="left" cellspacing="0" cellpadding="0">
          <tr>
            <td class="label">FECHA</td>
            <td align="center"<?=(($reg_part["FechaHorP"]==0)?' style="color:#FFFFFF"':""); ?>><?=date("d/m/Y", $reg_part["FechaHorP"]);?></td>
          </tr>
        </table></td>
        <td><table class="tbl_cedula" align="center" cellspacing="0" cellpadding="0">
          <tr>
            <td class="label">HORA</td>
            <td align="center"<?=(($reg_part["FechaHorP"]==0)?' style="color:#FFFFFF"':""); ?>><?=date("H:i", $reg_part["FechaHorP"]);?></td>
          </tr>
        </table></td>
        <td><table class="tbl_cedula" align="center" cellspacing="0" cellpadding="0">
          <tr>
            <td class="label">CATEGORIA</td>
            <td align="center" width="250"><?=$info->categoria?></td>
          </tr>
        </table></td>
        <td><table class="tbl_cedula" align="right" cellspacing="0" cellpadding="0">
          <tr>
            <td class="label">JORNADA</td>
            <td align="center" width="30"><?=$info->jornada?></td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td><table width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table class="tbl_cedula" width="460" align="left" cellspacing="0" cellpadding="0">
          <tr>
            <td width="80" align="center" class="label">ARBITRO</td>
            <td align="center">&nbsp;</td>
          </tr>
        </table>
          <table class="tbl_cedula" width="260" align="right" cellspacing="0" cellpadding="0">
          <tr>
            <td width="60" align="center" class="label">CRONO</td>
            <td align="center">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?php
// while ($reg_eqs = mysql_fetch_array($res_eqs)){
foreach($res_eqs->result_array() as $reg_eqs)
{
?>
  <tr>
    <td valign="top"><table width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top">
          <table style="padding-bottom:2px" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td style="height:100%" width="300"><table class="tbl_cedula" style="margin-right:2px" width="100%" align="left" cellspacing="0" cellpadding="0" border="0">
                <tr>
                  <td width="80" align="center" class="label">EQUIPO</td>
                  <td align="center"><?=$reg_eqs['NomEquipo']?></td>
                </tr>
              </table></td>
              <td><table class="tbl_cedula" width="100%" align="right" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="60" align="center" class="label">MARCADOR</td>
                  <td align="center">&nbsp;</td>
                </tr>
              </table></td>
            </tr>
          </table>
          <table class="tbl_cedula" width="100%" cellspacing="0" cellpadding="0" style="border-top-style:none; border-left-style:none;">
          <tr>
            <td colspan="2">&nbsp;</td>
            <td colspan="5" style="border-top-style:solid; border-top-width:2px;">FALTAS</td>
            <td colspan="3" style="border-top-style:solid; border-top-width:2px;">SANCION</td>
            <td rowspan="21" width="1" style="border-bottom-style:none; padding:0px">&nbsp;</td>
            <td colspan="4" style="border-top-style:solid; border-top-width:2px;">ANOTADORES</td>
            </tr>
          <tr>
            <td width="10" style="border-left-style:solid; border-left-width:2px;">No.</td>
            <td width="250">NOMBRE</td>
            <td colspan="5">INDIVIDUALES</td>
            <td width="5" style="padding:1px">Az</td>
            <td width="5" style="padding:1px">Am</td>
            <td width="5" style="padding:1px">R</td>
            <td style="font-size:7pt; padding:1px" width="20">T1</td>
            <td style="font-size:7pt; padding:1px" width="20">T2</td>
            <td style="font-size:7pt; padding:1px" width="20">T3</td>
            <td style="font-size:7pt; padding:1px" width="20">T4</td>
          </tr>
		  <?
		  $res_jug = mysql_query("SELECT NomJug, ApeJug FROm equipos_jugadores WHERE ID_Equipo=".$reg_eqs['ID_Equipo']." ORDER BY ApeJug, NomJug");
		  
		  $cont=0;
		  while( ($reg_jug = mysql_fetch_array($res_jug))||($cont<18) ){
		  $cont++;
		  ?>
          <tr class="tr_jugadores">
            <td style="border-left-style:solid; border-left-width:2px;">&nbsp;</td>
            <td style="text-align:left"><?
			if($reg_jug){
				echo $reg_jug['NomJug']." ".$reg_jug['ApeJug']; // echo $reg_jug['ApeJug'].", ".$reg_jug['NomJug'];
			} else {
				echo "&nbsp;";
			}
			?></td>
            <td width="5">&nbsp;</td>
            <td width="5">&nbsp;</td>
            <td width="5">&nbsp;</td>
            <td width="5">&nbsp;</td>
            <td width="5">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
		  <? } ?>
        </table>
          <table class="tbl_cedula_small" width="100%" cellspacing="0" cellpadding="0" style="padding-top:2px">
            <tr>
              <td style="height:100%"><table width="200" class="tbl_cedula" align="left" cellspacing="0" cellpadding="0" style="height:100%">
                  <tr>
                    <td class="label" style="padding-left:5px; padding-right:5px">TIEMPOS<br />FUERA</td>
                    <td class="label" style="width:50px; padding-top:15px">1</td>
                    <td class="label" style="width:50px; padding-top:15px">2</td>
                    <td class="label" style="width:50px; padding-top:15px">3</td>
                    <td class="label" style="width:50px; padding-top:15px">4</td>
                  </tr>
              </table></td>
              <td><table class="tbl_cedula_goles" style="border-top-style:solid; border-top-width:2px; border-left-style:solid; border-left-width:2px; border-color:#000000" align="center" cellspacing="0" cellpadding="0">
                  <tr>
                    <td rowspan="2" width="40" style="padding-left:8px; padding-right:8px; font-weight:bold">GOLES</td>
                    <td class="gol">1</td>
                    <td class="gol">2</td>
                    <td class="gol">3</td>
                    <td class="gol">4</td>
                    <td class="gol">5</td>
                    <td class="gol">6</td>
                    <td class="gol">7</td>
                    <td class="gol">8</td>
                    <td class="gol">9</td>
                    <td class="gol">10</td>
                  </tr>
                  <tr>
                    <td class="gol">11</td>
                    <td class="gol">12</td>
                    <td class="gol">13</td>
                    <td class="gol">14</td>
                    <td class="gol">15</td>
                    <td class="gol">16</td>
                    <td class="gol">17</td>
                    <td class="gol">18</td>
                    <td class="gol">19</td>
                    <td class="gol">20</td>
                  </tr>
              </table></td>
              </tr>
          </table></td>
        <td width="150" valign="top"><table class="tbl_cedula_small" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table class="tbl_cedula" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="6" class="label">ACUMULATIVAS</td>
                </tr>
              <tr>
                <td style="width:25px">1</td>
                <td style="width:25px">2</td>
                <td style="width:25px">3</td>
                <td style="width:25px">4</td>
                <td style="width:25px">5</td>
                <td style="width:25px">6</td>
              </tr>
              <tr>
                <td style="padding-top:2px; padding-bottom:2px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-top:2px; padding-bottom:2px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-top:2px; padding-bottom:2px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td style="padding-top:2px; padding-bottom:2px">&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table class="tbl_cedula" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" class="label">AMONESTADOS</td>
              </tr>
              <tr>
                <td class="label">#JUG</td>
                <td class="label">MINUTO</td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                </tr>
            </table></td>
          </tr>
          <tr>
            <td><table class="tbl_cedula" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="3" class="label">SHOOT OUTS </td>
              </tr>
              <tr>
                <td width="15">1</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>2</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>3</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>4</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>5</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>6</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>7</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>8</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
		  <tr>
		  <td><div style="height:65px; border-style:solid; border-width:2px; border-color:#000000; text-align:center;">
            <div style="vertical-align:top; font-size:7pt">FIRMA DEL CAPITAN</div>
          </div></td>
		  </tr>
        </table></td>
      </tr>
    </table></td>
  </tr>
<?
}
?>
</table>
</body>
</html>
<?php
	}
}
?>