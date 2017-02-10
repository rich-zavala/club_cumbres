<div id="reporte_contenedor">
	<div class="container-fluid">
		<table align="center" class="cedula_completa" width="100%" border="0" cellspacing="0" cellpadding="2px">
			<tr>
				<td>
					<table width="100%" border="0" cellspacing="0" cellpadding="0">
						<tr>
							<td width="100"><img src="<?=base()?>r_/images/cedula-izq.png" alt="Logo CCumbres" width="181" height="60" /></td>
							<td width="550"><div align="center" style="font-size:13pt"><?=$info->NomTorneo?></div></td>
							<td width="100"><img src="<?=base()?>r_/images/cedula-der.png" alt="Logo FRapido Mexico" width="181" height="60" /></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>
					<?php
					$kf = 0;
					foreach($fechas as $ff => $fecha){ //Por cada fecha
						$fecha_letras = $fecha[key($fecha)][0]->fecha;
						$kf++;
					?>
					<?=($kf > 1) ? '<hr />' : ''?>
					<table class="table table-bordered table-condensed table-hover marginTop10">
						<thead>
							<tr>
								<th colspan="12" class="text-center info"><?=$fecha_letras?></th>
							</tr>
							<tr class="active">
								<th>Hora</th>
								<th>Árbitraje</th>
								<th>Equipo 1</th>
								<th>Equipo 2</th>
								<th>Arbitraje</th>
								<th>Árbitro 1</th>
								<th>Pago</th>
								<th>Árbitro 2</th>
								<th>Pago</th>
								<th>Crono</th>
								<th>Pago</th>
								<th>Mesa</th>
							</tr>
							</thead>
							<tbody>
							<?php
							reset($fecha);
							$primera_cancha = 0;
							foreach($fecha as $k => $e){ //Por cada cancha ($e = evento)
								if($primera_cancha !== 0 or $primera_cancha !== $k){ ?>
							<tr><th colspan="12" class="text-center warning">Cancha <?=($k == 1) ? 'chica' : 'profesional'?></th></tr>
							<?php
								}
								foreach($e as $p){ // Por cada partido
							?>
							<tr>
								<td><?=$p->hora?></td>
								<td><?=$p->equipos[0]['pago'] == 1 ? money($p->servicio) : '<span class="text-muted">No pagó</span>'?></td>
								<td><?=$p->equipos[0]['nombre']?></td>
								<td><?=$p->equipos[1]['nombre']?></td>
								<td><?=$p->equipos[1]['pago'] == 1 ? money($p->servicio) : '<span class="text-muted">No pagó</span>'?></td>
								<td><?=$p->arbitroNombre1?></td>
								<td><?=$p->arbitroSueldo1 > 0 ? money($p->arbitroSueldo1) : '<span class="text-muted">No hubo</span>'?></td>
								<td><?=$p->arbitroNombre2?></td>
								<td><?=$p->arbitroSueldo2 > 0 ? money($p->arbitroSueldo1) : '<span class="text-muted">No hubo</span>'?></td>
								<td><?=$p->crono?></td>
								<td><?=$p->cronoSueldo > 0 ? money($p->cronoSueldo) : '<span class="text-muted">No hubo</span>'?></td>
								<td><?=$p->mesa?></td>
							</tr>
							<?php
								}
							}
							?>
						</tbody>
					</table>
					
					<table class="table table-bordered table-condensed table-hover marginTop10 marginBottom0 tabla-sumas pull-right">
						<tr>
							<th class="active">Árbitros, cronos y mesas</th>
							<td class="text-right">$ <?=money($sumas[$ff]['otros'])?></td>
						</tr>
						<tr>
							<th class="active">Arbitrajes efectuados</th>
							<td class="text-right">$ <?=money($sumas[$ff]['arbitrajes'])?></td>
						</tr>
						<tr>
							<th class="active">Total</th>
							<td class="text-right">$ <?=money($sumas[$ff]['total'])?></td>
						</tr>
						<tr>
							<th class="active">IVA</th>
							<td class="text-right">$ <?=money($sumas[$ff]['iva'])?></td>
						</tr>
						<tr>
							<th class="active">Neto</th>
							<td class="text-right">$ <?=money($sumas[$ff]['neto'])?></td>
						</tr>
					</table>
					
					<div class="clearfix"></div>
					<?php
					}
					?>
				</td>
			</tr>
		</table>
	</div>
</div>