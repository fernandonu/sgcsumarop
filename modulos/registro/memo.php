<?php

require_once("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

if($_POST["guardar_editar"]=="Guardar"){
   $fecha=Fecha_db($fecha); 
   $monto_ajuste=str_replace('.','',$monto_ajuste);		    
   $monto_ajuste=str_replace(',','.',$monto_ajuste);
   $monto_multa=str_replace('.','',$monto_multa);		    
   $monto_multa=str_replace(',','.',$monto_multa);
   $monto=$monto_ajuste+$monto_multa;

   $db->StartTrans();
   
   $query="update registro.memo 
   			set 
   				origen = '$origen',
				registro = '$registro',
				fecha = '$fecha',
				obs = '$obs',
				monto_ajuste = '$monto_ajuste',
				monto_multa = '$monto_multa',
				monto = '$monto'
   			where id_memo='$id_memo'";
   sql($query, "Error al vincular comprobante") or fin_pagina();
      
   $db->CompleteTrans();
   
   $ref = encode_link("plan_admin.php",array("id_registro"=>$id_registro,"pagina_viene"=>"plan_listado.php"));                  
   echo "<script>   			
   			location.href='$ref';
   		</script>";    	
 }
 
 if($_POST["guardar"]=="Guardar"){
   $fecha=Fecha_db($fecha); 
   $monto_ajuste=str_replace('.','',$monto_ajuste);		    
   $monto_ajuste=str_replace(',','.',$monto_ajuste);
   $monto_multa=str_replace('.','',$monto_multa);		    
   $monto_multa=str_replace(',','.',$monto_multa);
   $monto=$monto_ajuste+$monto_multa;
    
   $db->StartTrans();
   
   $query="insert into registro.memo 
			(id_registro, origen , registro,fecha,obs,monto,monto_ajuste,monto_multa)
			values
			('$id_registro', '$origen' , '$registro','$fecha','$obs','$monto','$monto_ajuste','$monto_multa')";   			
   sql($query, "Error al vincular comprobante") or fin_pagina();
      
   $db->CompleteTrans();
   
   $ref = encode_link("plan_admin.php",array("id_registro"=>$id_registro,"pagina_viene"=>"plan_listado.php"));                 
   echo "<script>   			
   			location.href='$ref';
   		</script>";    	
 }

echo $html_header;
cargar_calendario();
if ($id_memo){
	$query="select *
			from registro.memo
			where id_registro='$id_registro'";
	$res_registro=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
	
	$id_memo=$res_registro->fields['id_memo'];
	$origen=$res_registro->fields['origen'];
	$registro=$res_registro->fields['registro'];
	$fecha=$res_registro->fields['fecha'];
	$obs=$res_registro->fields['obs'];
	$monto=$res_registro->fields['monto'];
	$monto_ajuste=$res_registro->fields['monto_ajuste'];
	$monto_multa=$res_registro->fields['monto_multa'];
}
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos()
{
 if(document.form1.monto.value==""){
  alert('Debe Ingresar un monto');
  return false;
 }
 
  if(document.form1.fecha.value==""){
  alert('Debe Ingresar una fecha');
  return false;
 }
 
 if (confirm('Esta Seguro que Desea Agregar Memo?'))return true;
 else return false;	
}
</script>
<form name=form1 action="memo.php" method=POST>
<input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
<input type="hidden" name="id_registro" value="<?=$id_registro?>">
<input type="hidden" name="id_memo" value="<?=$id_memo?>">

<table cellspacing='2' cellpadding='2' width='60%' align='center' border='1' bgcolor='<?=$bgcolor_out?>'>
<br>
    <tr id="mo">
    	<td align="center" colspan="2">
    		<b>Memo Interno de Ajustes y Multas <?='RG-SGC N MI'.$id_memo.'_02'?></b>
    	</td>    	
    </tr>
    <tr>
		<td align="right" ><b>Origen:</b></td>
		<td align="left" >		          			
			<input type="text" name="origen" value="<?=$origen?>" size=60 align="right">
		</td>
	</tr>
	<tr>
		<td align="right" ><b>Registro:</b></td>
		<td align="left" >		          			
			<input type="text" name="registro" value="<?=$registro?>" size=60 align="right">
		</td>
	</tr>
	<tr>
		<td align="right" ><b>Fecha:</b></td>
		<td align="left" >
			<?$fecha=fecha($fecha)?>
			<input type=text id=fecha name=fecha value='<?=$fecha;?>' size=15 readonly>
        	 <?=link_calendario("fecha");?>					    	 
		</td>		    
	</tr>
	<tr>
		<td align="right" ><b>Observaciones:</b></td>
		<td align="left" >		          			
			<input type="text" name="obs" value="<?=$obs?>" size=60 align="right">
		</td>
	</tr>
	
	<tr>
		<td align="right" ><b>Monto Ajuste:</b></td>
		<td align="left" >		          			
			<input type="text" name="monto_ajuste" value="<?=number_format($monto_ajuste,2,',','.');?>" size=60 align="right">
		</td>
	</tr>
	<tr>
		<td align="right" ><b>Monto Multa:</b></td>
		<td align="left" >		          			
			<input type="text" name="monto_multa" value="<?=number_format($monto_multa,2,',','.');?>" size=60 align="right">
		</td>
	</tr>

	<tr>
		<td align="right" ><b>Monto Total:</b></td>
		<td align="left" >		          			
			<input type="text" name="monto" value="<?=number_format($monto,2,',','.');?>" size=30 align="right" readonly>
		</td>
	</tr>
	
	<tr>	
		<td align="center" colspan=2>
		<b><font size="1" color="Red">Ingresar valores numericos CON separadores de miles, y "," como separador DECIMAL</font> </b>
		 </td>
	 </tr>
	 
    <tr>
    	<td align="center" colspan="2"  id="mo">
    		<?if ($id_memo){?>
				<input type="submit" name="guardar_editar" value="Guardar" onclick="return control_nuevos()">
			<?}else{?>
				<input type="submit" name="guardar" value="Guardar" onclick="return control_nuevos()">
			<?}?>
    		&nbsp;&nbsp;&nbsp;
    		<?if ($pagina_viene=='listado_memo.php'){?>
    			<input type="button" name="cerrar" value="Volver" onclick="document.location='listado_memo.php'" >
    		<?}
    		else{?>
    			<input type="button" name="cerrar" value="Volver" onclick="document.location='plan_listado.php'" >
    		<?}?>
    	</td>    	
    </tr>    
</table>
<br>
<br>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>