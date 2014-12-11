<?
require_once ("../../config.php");

extract ( $_POST, EXTR_SKIP );
if ($parametros)
	extract ( $parametros, EXTR_OVERWRITE );
cargar_calendario ();

if ($borra_efec == 'borra_efec') {
	
	$query = "delete from expedientes.usu_area  
			where id_usu_area='$id_usu_area'";
	
	sql ( $query, "Error al eliminar el pcia" ) or fin_pagina ();
	$accion = "Los datos se han borrado";
}

if ($id_usuario) {
	$query = " SELECT 
		 *
		FROM
		  sistema.usuarios  
		  where id_usuario=$id_usuario";
	
	$res_usuario = sql ( $query, "Error al traer el Comprobantes" ) or fin_pagina ();
	$login = $res_usuario->fields ['login'];
	$login = strtoupper ( $login );
}

if ($_POST ['guardar_provincia'] == 'Guardar') {
	$db->StartTrans ();
	
	for($i = 0; $i < count ( $cuie ); $i ++) {
		$efector = $cuie [$i];
		
		$query = "insert into expedientes.usu_area
				   	(id_area, id_usuario)
				   	values
				   	('$efector', '$id_usuario')";
		
		sql ( $query, "Error al insertar Efector" ) or fin_pagina ();
	
	}
	
	$accion = "Los datos se han guardado correctamente";
	
	$db->CompleteTrans ();
}
//---------------------fin provincia------------------------------


echo $html_header;

?>
<script>
</script>
<?php echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";?>
<div class="newstyle-full-container">
	<form name='form1' action='usr_areas_admin.php' method='POST'>
		<input type="hidden" value="<?=$id_usuario?>" name="id_usuario">

		<? 
			if($accion) {
		?>
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<?= $accion ?>
			</div>
		<? 
			}
		?>
		<legend>Agregar Provincia</legend>
		<div>
			<p><strong>Usuario:</strong> <?= (!$id_usuario)? "Nuevo" : $login ?></p>
			<p><strong>ID Usuario:</strong> <?= ($id_usuario)? $id_usuario : "Nuevo"?></p>
		</div>
	
		<div class="row-fluid">
					
 		<?if ($id_usuario) {?>
 			<div class="span6">
				<legend>Areas</legend> 
										<select style="width: 100%; height: 150px; margin-bottom: 10px;" multiple name="cuie[]" 
										onKeypress="buscar_combo(this);"	onblur="borrar_buffer();" onchange="borrar_buffer();">
											<?php
											$sql = "select * from expedientes.areas order by desc_area";
											$res_efectores = sql ( $sql ) or fin_pagina ();
											while ( ! $res_efectores->EOF ) {
												$cuiel = $res_efectores->fields ['id_area'];
												$nombre_efector = $res_efectores->fields ['desc_area'];?>
												<option value='<?=$cuiel?>'><?=$nombre_efector?></option>
												<?$res_efectores->movenext ();
											}?>
										</select>

										<div>											
											<input class="btn btn-primary pull-right" type="submit" name="guardar_provincia" value="Guardar" title="Guardar">
										</div>
			</div>
  			<div class="span6">
				<legend>Areas Relacionadas</legend>
					<table class="table table-condensed table-bordered table-hover">
					<?$query = "select expedientes.areas.desc_area, expedientes.usu_area.id_usu_area
					from expedientes.areas 
					join expedientes.usu_area on (expedientes.areas.id_area = expedientes.usu_area.id_area) 
			        join sistema.usuarios on (expedientes.usu_area.id_usuario = sistema.usuarios.id_usuario) 
			        where sistema.usuarios.id_usuario = '$id_usuario' order by areas.desc_area";
					$res_comprobante = sql ( $query, "<br>Error al traer los comprobantes<br>" ) or fin_pagina ();
			
					if ($res_comprobante->RecordCount () == 0) {?>
					 	<tr>
							<td >
								<b>No existe ningun Area relacionado con este Usuario</b>
							</td>
						</tr>
				 	<?} 
				 	else {?>
				 		<thead>
							<tr>
								<th>Area</th>
								<th>Borrar</th>
							</tr>
						</thead>					 	
						<?$res_comprobante->movefirst ();
						while ( ! $res_comprobante->EOF ) {?>
							<tr>
								<td><?=$res_comprobante->fields ['desc_area']?></td>
								<?$ref = encode_link ( "usr_areas_admin.php", array ("id_usu_area" => $res_comprobante->fields ['id_usu_area'], "borra_efec" => "borra_efec", "id_usuario" => $id_usuario ) );
								$onclick_provincia = "if (confirm('Seguro que desea eliminar el Area?')) location.href='$ref'";?>
								<td align="center"><a href="#" onclick="<?=$onclick_provincia?>"><i class="icon-trash"></i> Eliminar</a></td>
							</tr>
							<?$res_comprobante->movenext ();
						} // fin while
					}?>	 	
					</table>
			</div>
		<?php } ?>

		</div> 

		<div class="form-actions">
			<input class="btn btn-info btn-large" type='button' name="volver" value="Volver" onclick="document.location='usr_areas_listado.php'" title="Volver al Listado">
		</div>
	</form>
</div>
<?=fin_pagina ();// aca termino ?>
