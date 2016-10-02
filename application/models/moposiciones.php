<?php
class Moposiciones extends CI_Model
{
	function __construct(){
		parent::__construct();
	}//Fin del constructor

	var $tip_torn;
	var $con_hist;
	var $sql_res_jon;
	var $es_pub;
	var $id_ctorn;
	var $sql_eqs_vg_A;
	var $sql_eqs_vg_B;
	var $sql_res_jon2;
	
	public function generar()
	{
		$tip_torn = $this->tip_torn;
		$con_hist = $this->con_hist;
		$id_ctorn = $this->id_ctorn;
		$sql_eqs_vg_A = $this->sql_eqs_vg_A;
		$sql_eqs_vg_B = $this->sql_eqs_vg_B;
		$sql_res_jon2 = $this->sql_res_jon2;
	?>
		<table class="table table-condensed table-striped table-bordered font11">
			<thead><tr>
				<th>Pos.</th>
				<th>Equipos</th>
		<?php
		$res_jon = mysql_query($this->sql_res_jon);
		if(mysql_num_rows($res_jon) == 0)
		{
			echo '<td colspan="2" align="center">Jornadas</td>';
		}	
		
		while ($reg_jorn = mysql_fetch_array($res_jon))
		{
		?>
			<th colspan="2" class='text-center'><?=$reg_jorn['DenomJor']?></th>
		<?php
		}
		
		if($this->es_pub or !$ct_con_vuelgrup) // Solo para categorias VG
		{
		?>
				<th class='text-center'>JG</th>
				<th class='text-center'>JP</th>
		<?php
			if ($tip_torn == 1)
			{
			?>
				<th class='text-center'>JGS</th>
				<th class='text-center'>JPS</th>
			<?php
			}
			else
			{
			?>
				<th class='text-center'>JE</th>
			<?php
			}
			?>
				<th class='text-center'>DG</th>
				<th class='text-center'>PTS</th>
			</tr></thead>
		<?php
		}
		
		if($tip_torn == 1) // Rapido
		{
			if(!$con_hist)
			{
				$tab_sql = "eq_punt_rapido";
				$tab_sql_where_hist = "";
			}
			else
			{
				$tab_sql = "eq_punt_rapido_hist";
				$tab_sql_where_hist = "AND eq_punt_rapido_hist.ID_VueltaGpo='".$reg_vg['ID_VueltaGpo']."'";
			}
		
			$flds_sql = $tab_sql.".JuGanR, ".$tab_sql.".JuPerR, ".$tab_sql.".JuGS, ".$tab_sql.".JuPS, ".$tab_sql.".DifGolR, ".$tab_sql.".PtsR";
			$ord_by_sql = $tab_sql.".PtsR DESC, ".$tab_sql.".DifGolR DESC";
		
		}
		elseif($tip_torn == 2) // Soccer
		{
			$tab_sql = "eq_punt_socc";
			$flds_sql = $tab_sql.".JuGanS, ".$tab_sql.".JuPerS, ".$tab_sql.".JuEmp, ".$tab_sql.".DifGolS, ".$tab_sql.".PtsS";
			$ord_by_sql = $tab_sql.".PtsS DESC, ".$tab_sql.".DifGolS DESC";
		}

		$s = "SELECT equipos_catalog.ID_Equipo, equipos_catalog.NomEquipo, ".$flds_sql."
					FROM torneos_cats, equipos_catalog, ".$sql_eqs_vg_A.$tab_sql."
					WHERE ".$sql_eqs_vg_B."torneos_cats.ID_CatTorn=equipos_catalog.ID_CatTorn AND equipos_catalog.ID_Equipo=".$tab_sql.".ID_Equipo AND torneos_cats.ID_CatTorn='".$id_ctorn."'".$tab_sql_where_hist."
					ORDER BY ".$ord_by_sql;
					// echo $s;
		$res_eqs = mysql_query($s) or die ($s . "<br>" . mysql_error());
		$cont = 0;
		while($reg_eqs = mysql_fetch_array($res_eqs))
		{
			$cont++;
			echo "<tr class='text-center'>
							<td class=>".$cont."</td>
							<td class='text-left'>".$reg_eqs['NomEquipo']."</td>";
		
			/*	Puntajes de cada jornada	*/
			$res_jon = mysql_query($sql_res_jon2);
		
			if(mysql_num_rows($res_jon)==0)
			{
				echo "<td colspan='2'>A&uacute;n no hay jornadas</td>";
			}
		
			while($reg_jorn = mysql_fetch_array($res_jon))
			{
				$s = "SELECT pp.Puntaje, p.ID_Partido, p.Es_Pendiente, p.Punt_Fue_Asig FROM part_punt pp, partidos p WHERE p.ID_Jornada='".$reg_jorn['ID_Jornada']."' AND p.ID_Partido = pp.ID_Partido AND pp.ID_Equipo='".$reg_eqs['ID_Equipo']."' LIMIT 1";
				// echo $s."<hr>";
				$res_punt_eq = mysql_query($s);
				if(mysql_num_rows($res_punt_eq) == 0)
				{
					echo "<td colspan='2'>DESC.</td>";
		
				}
				else
				{
					$reg_punt_eq = mysql_fetch_array($res_punt_eq);
					if($reg_punt_eq['Es_Pendiente'])
					{
						echo "<td colspan='2'>PEND.</td>";
					}
					elseif(!$reg_punt_eq['Punt_Fue_Asig'])
					{
						echo "<td>-</td>
									<td>-</td>";
					}
					else
					{
						$punt_eq_actual = $reg_punt_eq['Puntaje'];
						$res_punt_eq = mysql_query("SELECT pp.Puntaje FROM part_punt pp WHERE pp.ID_Partido='".$reg_punt_eq['ID_Partido']."' AND pp.ID_Equipo<>'".$reg_eqs['ID_Equipo']."'");
						$reg_punt_eq = mysql_fetch_array($res_punt_eq);
						$punt_eq_contrario = $reg_punt_eq['Puntaje'];
						
						echo "<td>$punt_eq_actual</td>
									<td>$punt_eq_contrario</td>";
					}
				}
			}
		
			if($this->es_pub or !$ct_con_vuelgrup) // Solo para categorias VG
			{
		
				if($tip_torn == 1) // Rapido
				{
					echo "<td>".$reg_eqs['JuGanR']."</td>
								<td>".$reg_eqs['JuPerR']."</td>
								<td>".$reg_eqs['JuGS']."</td>
								<td>".$reg_eqs['JuPS']."</td>
								<td>".$reg_eqs['DifGolR']."</td>
								<td>".$reg_eqs['PtsR']."</td>";
				}
				elseif($tip_torn == 2) // Soccer
				{
					echo "<td>".$reg_eqs['JuGanS']."</td>
								<td>".$reg_eqs['JuPerS']."</td>
								<td>".$reg_eqs['JuEmp']."</td>
								<td>".$reg_eqs['DifGolS']."</td>
								<td>".$reg_eqs['PtsS']."</td>";
				}
			}
		
			echo "</tr>";
		}
		
		echo "</table>";
	}
}
?>