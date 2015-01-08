<?require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
	
if ($_POST['guardar_1']=="Guardar"){   
   $db->StartTrans();         
	    $query="INSERT INTO opcastroficas.ugsp(id_ugsp, descripcion, domicilio, codigo_postal, localidad, provincia,tipo_iva, cuit)
			VALUES
			('$id_ugsp', '$descripcion', '$domicilio', '$codigo_postal', '$localidad', '$provincia', '$tipo_iva', '$cuit')";
		sql($query, "Error al insertar el UGSP") or fin_pagina();
    $db->CompleteTrans();    
    	
}//de if ($_POST['guardar']=="Guardar nuevo Muleto")

if ($_POST['guardar_editar_1']=="Guardar"){
	$db->StartTrans();
		$query="update opcastroficas.ugsp set
					descripcion= '$descripcion',
					domicilio='$domicilio',
					codigo_postal= '$codigo_postal',
					localidad='$localidad',
					provincia= '$provincia',
					tipo_iva='$tipo_iva',
					cuit= '$cuit',
				Where id_ugsp='$id_ugsp'";
		sql($query, "Error al modificar el UGSP $id_ugsp") or fin_pagina();
		 
	$db->CompleteTrans();    
	
	echo '<script>window.location.href="listado_ugsp.php"</script>';
	
}//----------------Fin de guardar editar----------------
	
if ($id_ugsp){
	$sql="select * from opcastroficas.ugsp
	where id_ugsp='$id_ugsp'";
	
	$res_registro=sql($sql, "Error al traer el UGSP ".$id_ugsp) or fin_pagina();
    
	$id_ugsp=$res_registro->fields['id_ugsp'];
	$descripcion=$res_registro->fields['descripcion'];
	$domicilio=$res_registro->fields['domicilio'];
	$codigo_postal=$res_registro->fields['codigo_postal'];
	$localidad=$res_registro->fields['localidad'];
	$provincia=$res_registro->fields['provincia'];
	$tipo_iva=$res_registro->fields['tipo_iva'];
	$cuit=$res_registro->fields['cuit'];

} 

echo $html_header;
cargar_calendario();
?>
<script>
function validar(){
	return true;
}
</script>

<form name='form1' action='formulario_ugsp.php' method='POST'>
<input type="hidden" value="<?=$id_ugsp?>" name="id_ugsp">


<table width='85%' border='1' align="center" class="table table-bordered">
 <tr id="mo">
    <td>
    	<?if ($id_registro){?>
    		<font size=+1><b>Beneficiario: <?='RG-SGC N PC'.$id_registro.'_03'?></b></font>   
    	<?}
    	else{?>
    		<font size=+1><b>Nuevo UGSP</b></font>
    	<?}?> 	       
    </td>
 </tr>
 
 <tr><td class="table table-bordered">
	<table width=100% align="center" class="table table-bordered">
	     <tr align="center" id="mo">
	      <td colspan="4" >
	       <b> Datos del UGSP </b>
	      </td>
	    </tr>
		<tr>
		
			<td align="right">
				<b>Nro. UGSP:</b>
			</td>
			<td align="left">
				<input type="text" required name="id_ugsp" size="40" maxlength="100" value="<?=$id_ugsp?>" <?if ($id_ugsp) echo "disabled"?>>
			</td>
			<td align="right">
				<b>Descripción:</b>
			</td>
			<td align="left">
				<input type="text" name="descripcion" size="40" maxlength="100" value="<?=$descripcion?>">
			</td>
		</tr> 
		<tr>
			<td align="right">
				<b>Domicilio:</b>
			</td>
			<td align="left">
				<input type="text" name="domicilio" size="40" maxlength="100" value="<?=$domicilio?>">
			</td>
			<td align="right">
				<b>Cod. Postal:</b>
			</td>
			<td align="left">
				<input type="text" name="codigo_postal" size="40" maxlength="100" value="<?=$codigo_postal?>">
			</td>
		</tr> 
		<tr>
			<td align="right" ><b>Provincia:</b></td>
			<td align="left" >		          			
				<select name=provincia Style="width:257px">
					<option value=-1>Seleccione</option>
					<?
					$sql= "select * from registro.provincia order by codigo";
					$res=sql($sql) or fin_pagina();
					while (!$res->EOF){ 
					 	$id_provincia1=$res->fields['id_provincia'];
					    $codigo=$res->fields['codigo'];
					    $descripcion=$res->fields['descripcion'];?>
						<option value='<?=$id_provincia1?>' <?if ($provincia==$id_provincia1) echo "selected"?> ><?=$codigo." - ".$descripcion?></option>
					<?
					$res->movenext();
					}?>
				</select>
			</td>
			<td align="right">
				<b>Localidad:</b>
			</td>
			<td align="left">
				<input type="text" name="localidad" size="40" maxlength="100" value="<?=$localidad?>">
			</td>
		</tr>
		<tr>
			<td align="right">
				<b>Tipo IVA:</b>
			</td>
			<td align="left">
				<input type="text" name="tipo_iva" size="40" maxlength="100" value="<?=$tipo_iva?>">
			</td>
			<td align="right">
				<b>CUIT:</b>
			</td>
			<td align="left">
				<input type="text" name="cuit" size="40" maxlength="100" value="<?=$cuit?>">
			</td>
		</tr> 
	<tr id="sub_tabla">
  		<td align=center colspan="8">
  			<b>Acciones</b>
  		</td>
  	</tr>  
  	<tr>
	 <td align="center" colspan="8" class="bordes">
	 	    <?
				if($id_ugsp)
				{
					echo '<input type="submit" name="guardar_editar_1" value="Guardar" title="Guardar" style="width:130px" onclick="return validar()">&nbsp;&nbsp;';
				} else {
					echo '<input type="submit" name="guardar_1" value="Guardar" title="Guardar" style="width:130px" onclick="return validar()">&nbsp;&nbsp;';
				}
			?>
			
		    <input type="button" name="cancelar_editar_1" value="Volver" title="Volver" style="width:130px" onclick="window.location.href='listado_ugsp.php'">
	 </td>
	</tr>
</table>  
 </form>
 
 <?=fin_pagina();// aca termino ?>