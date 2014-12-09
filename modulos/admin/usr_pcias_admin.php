<?
require_once ("../../config.php");

extract ( $_POST, EXTR_SKIP );
if ($parametros)
	extract ( $parametros, EXTR_OVERWRITE );
cargar_calendario ();

if ($borra_efec == 'borra_efec') {
	
	$query = "delete from expedientes.usu_prov  
			where id_usu_prov='$id_usu_prov'";
	
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
		$id_provincias = $cuie [$i];
		
		$query = "insert into expedientes.usu_prov
				   	(id_provincias, id_usuario)
				   	values
				   	('$id_provincias', '$id_usuario')";
		
		sql ( $query, "Error al insertar Efector" ) or fin_pagina ();
	
	}
	
	$accion = "Los datos se han guardado correctamente";
	
	$db->CompleteTrans ();
}
//---------------------fin provincia------------------------------


echo $html_header;

?>
<script>

//fin de function control_nuevos()
//empieza funcion mostrar tabla
var img_ext='<?=$img_ext = '../../imagenes/rigth2.gif'?>';//imagen extendido
var img_cont='<?=$img_cont = '../../imagenes/down2.gif'?>';//imagen contraido

function muestra_tabla(obj_tabla,nro){
 oimg=eval("document.all.imagen_"+nro);//objeto tipo IMG
 if (obj_tabla.style.display=='none'){
 	obj_tabla.style.display='inline';
    oimg.show=0;
    oimg.src=img_ext;
 }
 else{
 	obj_tabla.style.display='none';
    oimg.show=1;
	oimg.src=img_cont;
 }
}//termina muestra tabla

</script>

<form name='form1' action='usr_pcias_admin.php' method='POST'>
<input type="hidden" value="<?=$id_usuario?>" name="id_usuario">
<?
echo "<center><b><font size='+1' color='red'>$accion</font></b></center>";
?>
<table width="85%" cellspacing=0 border=1 align="center" bgcolor='<?=$bgcolor_out?>'>
	<tr id="mo">
		<td>
	    	<?if (! $id_usuario) {?>  
	    		<font size=+1><b>Nuevo Dato</b></font>   
	    	<?} 
	    	else {?>
	        	<font size=+1><b><?=$login?></b></font>   
	        <?}?>
        </td>
	</tr>
	<tr>
		<td>
		<table width=90% align="center" border=1>
			<tr>
				<td id=mo colspan="2"><b> Usuario</b></td>
			</tr>
			<tr>
				<td>
				<table>
					<tr>
						<td align="center" colspan="2">
							<b> ID Usuario: 
							<font size="+1" color="Red"> <?=($id_usuario) ? $id_usuario : "Nuevo Dato"?></font>
							</b>
						</td>
					</tr>
					
					<tr>
						<td align="right">
							<b>Login:</b></td>
						<td align='left'>
							<input type="text" size="40" value="<?=$login;?>" name="login" disabled>
						</td>
					</tr>
				</table>
				</td>
			</tr>
			
	<?if ($id_usuario) {?>
 
 
 	<tr>
				<td>
				<table width="100%" border=1 align="center">
					<tr align="center" id="mo">
						<td align="center"><b>Agregar Provincia</b></td>
					</tr>
					<tr>
						<td>
						<table width="100%" align="center">

							<tr>
								<td align="right"><b>Provincias:</b></td>
								<td align='left'>
								<select multiple name="cuie[]" Style=""
									size="20" onKeypress="buscar_combo(this);"
									onblur="borrar_buffer();" onchange="borrar_buffer();"
									<?php
									$sql = "select * from registro.provincia order by codigo";
									$res_efectores = sql ( $sql ) or fin_pagina ();
									while ( ! $res_efectores->EOF ) {
										$cuiel = $res_efectores->fields ['id_provincia'];
										$nombre_efector = $res_efectores->fields ['descripcion'];
										$codigo = $res_efectores->fields ['codigo'];?>
										<option value='<?=$cuiel?>'>
											<?=$codigo . " - " . $nombre_efector?>
										</option>
										<?$res_efectores->movenext ();
									}?>
								</select>
								</td>
							</tr>
							<tr>
								<td align="center" colspan="5" class="bordes">
									<input type="submit" name="guardar_provincia" value="Guardar" title="Guardar" style='width:130px;height:25px'>
								</td>
							</tr>

						</table>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		
			<tr>
				<td>
				<table width="100%" class="bordes" align="center">
					<tr align="center" id="mo">
						<td align="center" width="3%">
							<img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar" align="left"
							style="cursor: pointer;" onclick="muestra_tabla(document.all.prueba_vida,2);">
						</td>
						<td align="center">
							<b>Provincias Relacionados</b>
						</td>
					</tr>
				</table>
				</td>
			</tr>


			<tr>
				<td>
				<table id="prueba_vida" border="1" width="100%" style="display: none; border: thin groove">
					<?$query = "select registro.provincia.descripcion, registro.provincia.codigo,expedientes.usu_prov.id_usu_prov
								from registro.provincia 
								join expedientes.usu_prov on (registro.provincia.id_provincia = expedientes.usu_prov.id_provincias) 
						        join sistema.usuarios on (expedientes.usu_prov.id_usuario = sistema.usuarios.id_usuario) 
						        where sistema.usuarios.id_usuario = '$id_usuario' order by provincia.codigo";
			
								$res_comprobante = sql ( $query, "<br>Error al traer los comprobantes<br>" ) or fin_pagina ();
					if ($res_comprobante->RecordCount () == 0) {?>
				 	<tr>
						<td align="center">
						<font size="2" color="Red">
							<b>No existe ninguna Provincia relacionado con este Usuario</b>
						</font>
						</td>
					</tr>
				 	<?} 
				 	else {?>
					 	<tr id="sub_tabla">						
							<td width="20%">Codigo</td>
							<td width="30%">Provincia</td>
							<td width=1%>Borrar</td>
						</tr>
						<?$res_comprobante->movefirst ();
						while ( ! $res_comprobante->EOF ) {?>
							<tr <?=atrib_tr ()?>>						
								<td><?=$res_comprobante->fields ['codigo']?></td>
								<td><?=$res_comprobante->fields ['descripcion']?></td>
								<?$ref = encode_link ( "usr_pcias_admin.php", array ("id_usu_prov" => $res_comprobante->fields ['id_usu_prov'], "borra_efec" => "borra_efec", "id_usuario" => $id_usuario ) );
								$onclick_provincia = "if (confirm('Seguro que desea eliminar la Provincia?')) location.href='$ref'";?>
								 <td align="center"><img src='../../imagenes/sin_desc.gif'style='cursor: pointer;' onclick="<?=$onclick_provincia?>"></td>
							</tr>
						<?$res_comprobante->movenext ();
						} // fin while
					}?>	 	
				</table>
				</td>
			</tr>
		 <?php } ?>
			<tr>
				<td>
				<table width=100% align="center" class="bordes">
					<tr align="center">
						<td>
							<input type=button name="volver" value="Volver" onclick="document.location='usr_pcias_listado.php'"
							title="Volver al Listado" style='width:130px;height:25px'>
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
</form>

<?=fin_pagina ();// aca termino ?>
