<?require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);
	
if ($_POST['guardar_1']=="Guardar"){   
   $db->StartTrans();         
	    $query="INSERT INTO opcastroficas.beneficiario(numero_beneficiario, tipo_documento, nro_documento, apellido, nombre, edad)
	            values
	            ('$numero_beneficiario', '$tipo_documento', '$nro_documento', '$apellido', '$nombre', '$edad')";
	
	    sql($query, "Error al insertar el beneficiario") or fin_pagina();
    $db->CompleteTrans();    
    
	
	
}//de if ($_POST['guardar']=="Guardar nuevo Muleto")

if ($_POST['guardar_editar_1']=="Guardar"){
	$db->StartTrans();
		$query="update opcastroficas.beneficiario set
				 apellido='$apellido',
				 nombre= '$nombre'
				 Where nro_documento='$nro_documento' and tipo_documento='$tipo_documento'";
		sql($query, "Error al modificar el beneficiario $numero_beneficiario") or fin_pagina();
		 
	$db->CompleteTrans();    
	
	echo '<script>window.location.href="listado_beneficiarios.php"</script>';
	
}//----------------Fin de guardar editar----------------
	
if ($tipo_documento && $nro_documento){
	$sql="select * from opcastroficas.beneficiario
	where nro_documento='$nro_documento' and tipo_documento='$tipo_documento'";
	
	$res_registro=sql($sql, "Error al traer el beneficiario ".$nro_documento) or fin_pagina();
    
    $numero_beneficiario=$res_registro->fields['numero_beneficiario'];
	$tipo_documento=$res_registro->fields['tipo_documento'];
	$nro_documento=$res_registro->fields['nro_documento'];
	$apellido=$res_registro->fields['apellido'];
	$nombre=$res_registro->fields['nombre'];
	$edad=$res_registro->fields['edad'];
} 

echo $html_header;
cargar_calendario();
?>
<script>
function validar(){
	return true;
}
</script>

<form name='form1' action='formulario_beneficiario.php' method='POST'>
<input type="hidden" value="<?=$tipo_documento?>" name="tipo_documento">
<input type="hidden" value="<?=$nro_documento?>" name="nro_documento">

<table width='85%' border='1' align="center" class="table table-bordered">
 <tr id="mo">
    <td>
    	<?if ($id_registro){?>
    		<font size=+1><b>Beneficiario: <?='RG-SGC N PC'.$id_registro.'_03'?></b></font>   
    	<?}
    	else{?>
    		<font size=+1><b>Nuevo beneficiario</b></font>
    	<?}?> 	       
    </td>
 </tr>
 
 <tr><td class="table table-bordered">
	<table width=100% align="center" class="table table-bordered">
	     <tr align="center" id="mo">
	      <td colspan="4" >
	       <b> Datos del beneficiario </b>
	      </td>
	     </tr>
         <tr>
         	<td align="right">
				<b>Nro. beneficiario:</b>
			</td>
			<td align="left">
				<input type="text" required name="numero_beneficiario" size="40" maxlength="100" value="<?=$numero_beneficiario?>" <?if ($nro_documento) echo "disabled"?>>		    	 			    	 			
			</td>
       
         	<td align="right">
         	  <b>Tipo de documento:</b>
         	</td>         	
            <td align='left'>
				<select name="tipo_documento" style="width:257px" <?if ($nro_documento) echo "disabled"?>>
					<option value=-1>Seleccione</option>
					<option value=DNI <?if ($tipo_documento=='DNI') echo "selected"?>>DNI</option>
					<option value=PAS <?if ($tipo_documento=='PAS') echo "selected"?>>Pasaporte</option>
				</select>
            </td>
         </tr> 
		 <tr>
         	<td align="right">
				<b>Nro. documento:</b>
			</td>
			<td align="left">
				<input type="text" required name="nro_documento" size="40" maxlength="20" value="<?=$nro_documento?>" <?if ($nro_documento) echo "disabled"?>>		    	 			    	 			
			</td>
       
         	<td align="right">
         	  <b>Apellido:</b>
         	</td>         	
            <td align='left'>
				<input type="text" required name="apellido" size="100" maxlength="100" value="<?=$apellido?>" <?if ($nro_documento) echo ""?>>		    	 			    	 			
            </td>
         </tr> 
		<tr>
         	<td align="right">
				<b>Nombre:</b>
			</td>
			<td align="left">			 	
				<input type="text" required name="nombre" size="100" maxlength="100" value="<?=$nombre?>" <?if ($nro_documento) echo ""?>>		    	 			    	 			
			</td>
         
			<td align="right">
				<b>Edad:</b>
			</td>
		    <td align="left">
				<input type="number" required name="edad" size="40" value="<?=$edad?>" <?if ($nro_documento) echo "disabled"?>>		    	 			    	 
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
				if($nro_documento)
				{
					echo '<input type="submit" name="guardar_editar_1" value="Guardar" title="Guardar" style="width:130px" onclick="return validar()">&nbsp;&nbsp;';
				} else {
					echo '<input type="submit" name="guardar_1" value="Guardar" title="Guardar" style="width:130px" onclick="return validar()">&nbsp;&nbsp;';
				}
			?>
			
		    <input type="button" name="cancelar_editar_1" value="Volver" title="Volver" style="width:130px" onclick="window.location.href='listado_beneficiarios.php'">
	 </td>
	</tr>
</table>  
 </form>
 
 <?=fin_pagina();// aca termino ?>
