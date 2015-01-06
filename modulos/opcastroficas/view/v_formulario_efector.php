<?
require_once ("../../config.php");
require_once ("../../lib/dropdown.php");

function drawView($provincias){
	global $html_header;
	
	global $mock;
	global $result;
	
	echo $html_header;
	
	if($mock) {
		$id_efector = $result[0]['id_efector'];
		$descripcion = $result[0]['descripcion']; 
		$provincia = $result[0]['provincia'];
		$email = $result[0]['email'];
		$telefono = $result[0]['telefono'];
		$direccion = $result[0]['direccion'];
	} else {
		//$id_efector = $result->fields ['id_efector'];
	}
	
	//HTML
	?>
	<script>
		function validar(){
			return true;
		}
	</script>
		<form name="form1" action="formulario_efector.php" method="POST">
			<input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
			<input type="hidden" name="id_efector" value="<?=$id_efector?>">
			
			<table cellspacing='2' cellpadding='2' width='60%' align='center' border='1' bgcolor='<?=$bgcolor_out?>'>
				<tr id="mo">
			    	<td align="center" colspan="2">
			    		<b>Cod. Efector <?=$id_efector?></b>
			    	</td>    	
			    </tr>
				
				<tr>
					<td align="right" ><b>Descripción:</b></td>
					<td align="left" >		          			
						<input type="text" name="descripcion" value="<?=$descripcion?>" size="60" align="right">
					</td>
				</tr>
				
				<tr>
					<td align="right" ><b>Provincia:</b></td>
					<td align="left" >		          			
						<input type="text" name="provincia" value="<?=$provincia?>" size="60" align="right">
					</td>
				</tr>
				
				<tr>
					<td align="right" ><b>Provincia:</b></td>
					<td align="left" >		          			
						<select name="liq_apli1">
							<option value=-1>Seleccione</option>
							<?
							//$opciones = sql("select * from registro.provincia","No se ejecuto en la consulta principal") or die;
							while(!$provincias->EOF){
								$opt = '<option value="'.$provincias->fields['id_provincia'].'" '. ($provincias->fields['id_provincia'] == $provincia ? 'selected' : '') .'>'.$provincias->fields['descripcion'].'</option>';
								echo $opt;
								$provincias->MoveNext();
							}
							?>
						</select>
					</td>
				</tr>
				
				<tr>
					<td align="right" ><b>Email:</b></td>
					<td align="left" >		          			
						<input type="text" name="email" value="<?=$email?>" size="60" align="right">
					</td>
				</tr>
				
				<tr>
					<td align="right" ><b>Teléfono:</b></td>
					<td align="left" >		          			
						<input type="text" name="telefono" value="<?=$telefono?>" size="60" align="right">
					</td>
				</tr>
				
				<tr>
					<td align="right" ><b>Dirección:</b></td>
					<td align="left" >		          			
						<input type="text" name="direccion" value="<?=$direccion?>" size="60" align="right">
					</td>
				</tr>
				
				<tr>
			    	<td align="center" colspan="2"  id="mo">
						<?if ($id_efector){?>
							<input type="submit" name="guardar_editar" value="Guardar" onclick="return validar()">
						<?}else{?>
							<input type="submit" name="guardar" value="Guardar" onclick="return validar()">
						<?}?>
			    		&nbsp;&nbsp;&nbsp;
			    		<input type="button" name="cerrar" value="Volver" onclick="document.location='listado_efectores.php'" >
			    	</td>    	
			    </tr>    
				
			</table>
			<br>
			<br>
		</form>
	<?
	//HTML
}
?>