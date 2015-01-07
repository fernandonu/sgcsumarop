<?php

require_once("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

if($_POST["guardar_editar"]=="Guardar"){
   $fecha=Fecha_db($fecha); 
   $notif_pcias=Fecha_db($notif_pcias); 
   $fecha_inicio_ajuste=Fecha_db($fecha_inicio_ajuste); 
   $monto_aj_apli=str_replace('.','',$monto_aj_apli);		    
   $monto_aj_apli=str_replace(',','.',$monto_aj_apli);
   $monto_mul_apli=str_replace('.','',$monto_mul_apli);		    
   $monto_mul_apli=str_replace(',','.',$monto_mul_apli);
   $monto_total_apli=str_replace('.','',$monto_total_apli);		    
   $monto_total_apli=str_replace(',','.',$monto_total_apli);
    
   $db->StartTrans();
   
   $query="update registro.informe_ace
   			set 
   				fecha = '$fecha',
				audit = '$audit',
				documento = '$documento',
				num_leg = '$num_leg',
				notif_pcias = '$notif_pcias',
				fecha_inicio_ajuste = '$fecha_inicio_ajuste',
				aplicados = '$aplicados',
				liq_apli = '$liq_apli',
				liq_apli1 = '$liq_apli1',
				liq_apli2 = '$liq_apli2',
				monto_aj_apli = '$monto_aj_apli',
				monto_mul_apli = '$monto_mul_apli',
				monto_total_apli = '$monto_total_apli'
   			where id_informe_ace='$id_informe_ace'";
			
   sql($query, "Error al vincular comprobante") or fin_pagina();
      
   $db->CompleteTrans();
   
   $ref = encode_link("plan_admin.php",array("id_registro"=>$id_registro,"pagina_viene"=>"plan_listado.php"));                  
   echo "<script>   			
   			location.href='$ref';
   		</script>";    	
 }
 
 if($_POST["guardar"]=="Guardar"){
   $fecha=Fecha_db($fecha); 
   $notif_pcias=Fecha_db($notif_pcias); 
   $fecha_inicio_ajuste=Fecha_db($fecha_inicio_ajuste); 
   $monto_aj_apli=str_replace('.','',$monto_aj_apli);		    
   $monto_aj_apli=str_replace(',','.',$monto_aj_apli);
   $monto_mul_apli=str_replace('.','',$monto_mul_apli);		    
   $monto_mul_apli=str_replace(',','.',$monto_mul_apli);
   $monto_total_apli=str_replace('.','',$monto_total_apli);		    
   $monto_total_apli=str_replace(',','.',$monto_total_apli);
    
   $db->StartTrans();
   
   $query="insert into registro.informe_ace
			(id_registro,fecha,audit,documento,num_leg,notif_pcias,fecha_inicio_ajuste,aplicados,liq_apli,
				monto_aj_apli,monto_mul_apli,monto_total_apli,liq_apli1,liq_apli2)
			values
			('$id_registro','$fecha','$audit','$documento','$num_leg','$notif_pcias','$fecha_inicio_ajuste','$aplicados','$liq_apli',
				'$monto_aj_apli','$monto_mul_apli','$monto_total_apli','$liq_apli1','$liq_apli2')";   			
   sql($query, "Error al vincular comprobante") or fin_pagina();
      
   $db->CompleteTrans();
   
   $ref = encode_link("plan_admin.php",array("id_registro"=>$id_registro,"pagina_viene"=>"plan_listado.php"));                 
   echo "<script>   			
   			location.href='$ref';
   		</script>";    	
 }

echo $html_header;
cargar_calendario();
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";

if ($id_informe_ace){
	$query="select *
			from registro.informe_ace
			where id_registro='$id_registro'";
	$res_registro=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
	
	$fecha=$res_registro->fields['fecha'];
	$audit=$res_registro->fields['audit'];
	$documento=$res_registro->fields['documento'];
	$num_leg=$res_registro->fields['num_leg'];
	$notif_pcias=$res_registro->fields['notif_pcias'];
	$fecha_inicio_ajuste=$res_registro->fields['fecha_inicio_ajuste'];
	$aplicados=$res_registro->fields['aplicados'];
	$liq_apli=$res_registro->fields['liq_apli'];
	$monto_aj_apli=$res_registro->fields['monto_aj_apli'];
	$monto_mul_apli=$res_registro->fields['monto_mul_apli'];
	$monto_total_apli=$res_registro->fields['monto_total_apli'];
	$liq_apli1=$res_registro->fields['liq_apli1'];
	$liq_apli2=$res_registro->fields['liq_apli2'];

	
}
?>
<script>
//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos()
{
  if(document.form1.fecha.value==""){
  alert('Debe Ingresar una fecha');
  return false;
 }
   if(document.form1.notif_pcias.value==""){
  alert('Debe Ingresar una fecha de notificacion');
  return false;
 }
   if(document.form1.fecha_inicio_ajuste.value==""){
  alert('Debe Ingresar una fecha inicio ajuste');
  return false;
 }
 
 if (confirm('Esta Seguro que Desea Agregar Informe?'))return true;
 else return false;	
}

function mostrar_1(){	
	 if (document.form1.aplicados.value=='Total'){
	 	document.all.per.style.display='inline';
	    document.all.per.show=0;
	 }
	 else{
	 	document.all.per.style.display='none';
	    document.all.per.show=1;
	 }

	 if (document.form1.aplicados.value=='Parcial'){
	 	document.all.per1.style.display='inline';
	    document.all.per1.show=0;
	 }
	 else{
	 	document.all.per1.style.display='none';
	    document.all.per1.show=1;
	 }
}
</script>

<form name=form1 action="inf.php" method=POST>
<input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
<input type="hidden" name="id_registro" value="<?=$id_registro?>">
<input type="hidden" name="id_informe_ace" value="<?=$id_informe_ace?>">

<table cellspacing='2' cellpadding='2' width='60%' align='center' border='1' class="table table-bordered">
<br>
    <tr id="mo">
    	<td align="center" colspan="2">
    		<b>Informes ACE <?='RG-SGC N InfACE'.$id_informe_ace.'_02'?></b>
    	</td>    	
    </tr>
	
	<tr>
		<td align="right" ><b>Fecha Ingreso Informe:</b></td>
		<td align="left" >
			<?$fecha=fecha($fecha)?>
			<input type=text id=fecha name=fecha value='<?=$fecha;?>' size=15 readonly>
        	 <?=link_calendario("fecha");?>					    	 
		</td>		    
	</tr>
	
    <tr>
		<td align="right" ><b>Auditoria:</b></td>
		<td align="left" >		          			
			<input type="text" name="audit" value="<?=$audit?>" size=60 align="right">
		</td>
	</tr>
	<tr>
		<td align="right" ><b>Documento:</b></td>
		<td align="left" >		          			
			<input type="text" name="documento" value="<?=$documento?>" size=60 align="right">
		</td>
	</tr>
	<tr>
		<td align="right" ><b>Numeracion DOC Exteno:</b></td>
		<td align="left" >		          			
			<input type="text" name="num_leg" value="<?=$num_leg?>" size=60 align="right">
		</td>
	</tr>	
	<tr>
		<td align="right" ><b>Fecha Recepcion Capita</b></td>
		<td align="left" >
			<?$fecha_inicio_ajuste=fecha($fecha_inicio_ajuste)?>
			<input type=text id=fecha_inicio_ajuste name=fecha_inicio_ajuste value='<?=$fecha_inicio_ajuste;?>' size=15 readonly>
        	 <?=link_calendario("fecha_inicio_ajuste");?>					    	 
		</td>		    
	</tr>
	<tr>
		<td align="right" ><b>Fecha Notificacion Provincias</b></td>
		<td align="left" >
			<?$notif_pcias=fecha($notif_pcias)?>
			<input type=text id=notif_pcias name=notif_pcias value='<?=$notif_pcias;?>' size=15 readonly>
        	 <?=link_calendario("notif_pcias");?>					    	 
		</td>		    
	</tr>
	
	<tr>
		<td align="right" ><b>Aplicacion:</b></td>
		<td align="left" >		          			
			<select name="aplicados" onclick="mostrar_1()">
    		 <option value=-1>Seleccione</option>
    		 <option value='Parcial' <?if ($aplicados=='Parcial') echo 'selected'?> >Parcial</option>
    		 <option value='Total' <?if ($aplicados=='Total') echo 'selected'?> >Total</option>    		
    		</select>
		</td>
	</tr>
	
	<tr><td colspan=2><table id="per" border="1" width="100%" style="display:none;border:thin groove"align="center">
		<tr >
		<td align="right" ><b>Periodos:</b></td>
		<td align="left" >		          			
			<input type="text" name="liq_apli" value="<?=$liq_apli?>" size=60 align="right">
		</td>
		</tr>
	</table></td></tr>

	<tr><td colspan=2><table id="per1" border="1" width="100%" style="display:none;border:thin groove"align="center">
		<tr >
		<td align="right" ><b>Parcial:</b></td>
		<td align="left" >		          			
			<select name="liq_apli1">
    		 <option value=-1>Seleccione</option>
    		 <option value='Transferencia' <?if ($liq_apli1=='Transferencia') echo 'selected'?> >La transferencia Bruta no es suficiente para aplicar ajustes totales</option>
    		 <option value='Otros' <?if ($liq_apli1=='Otros') echo 'selected'?> >Otros</option>    		
    		</select>
		</td>
		</tr>
		<tr >
		<td align="right" ><b>Observaciones:</b></td>
		<td align="left" >		          			
			<input type="text" name="liq_apli2" value="<?=$liq_apli2?>" size=60 align="right">
		</td>
		</tr>
	</table></td></tr>
	
	<tr>
		<td align="right">
				<b>Monto de Ajustes Aplicados:</b>
			</td>
			<td align='left'>
              <input type="text" size="40" value="<?=number_format($monto_aj_apli,2,',','.')?>" name="monto_aj_apli" >
        </td>
	</tr>

	<tr>
		<td align="right">
				<b>Monto de Multas Aplicadas:</b>
			</td>
			<td align='left'>
              <input type="text" size="40" value="<?=number_format($monto_mul_apli,2,',','.')?>" name="monto_mul_apli" >
        </td>
	</tr>

	<tr>
		<td align="right">
				<b>Monto Total Aplicado:</b>
			</td>
			<td align='left'>
              <input type="text" size="40" value="<?=number_format($monto_total_apli,2,',','.')?>" name="monto_total_apli" >
        </td>
	</tr>

	<tr>	
		<td align="center" colspan=2>
		<b><font size="1" color="Red">Ingresar valores numericos CON separadores de miles, y "," como separador DECIMAL</font> </b>
		 </td>
	 </tr>

    <tr>
    	<td align="center" colspan="2">
    		<?if ($id_informe_ace){?>
				<input class="btn btn-primary" type="submit" name="guardar_editar" value="Guardar" onclick="return control_nuevos()">
			<?}else{?>
				<input class="btn btn-primary" type="submit" name="guardar" value="Guardar" onclick="return control_nuevos()">
			<?}?>
    		&nbsp;&nbsp;&nbsp;
    		<?if ($pagina_viene=='listado_ace.php'){?>
    			<input class="btn btn-info btn-large" type="button" name="cerrar" value="Volver" onclick="document.location='listado_ace.php'" >
    		<?}
    		else{?>
    			<input class="btn btn-info btn-large" type="button" name="cerrar" value="Volver" onclick="document.location='plan_listado.php'" >
    		<?}?>
    	</td>    	
    </tr>    
</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>