<?php
 require_once ("../../config.php");
 extract($_POST,EXTR_SKIP);
 if ($parametros) extract($parametros,EXTR_OVERWRITE);
 echo $html_header;

 if ($vincular=='ok'){
 	$q="select nextval('expedientes.legajo_x_exd_id_legajo_x_exd_seq') as id_planilla";
	    $id_planilla=sql($q) or fin_pagina();
	    $id_legajo_x_exd=$id_planilla->fields['id_planilla'];

		$query="insert into expedientes.legajo_x_exd
	             (id_legajo_x_exd,id_expediente,id_legajo,comentario)
	             values
	             ('$id_legajo_x_exd','$id_expediente','$id_legajo','Vinculado desde Legajos')";	
	    sql($query, "Error al insertar") or fin_pagina();
 	?>
	<script>
	window.opener.location.reload();
	window.close();
	</script>
 <?}?>


<script>
function iSubmitEnter(oEvento, oFormulario){
     var iAscii;

     if (oEvento.keyCode)
         iAscii = oEvento.keyCode;
     else if (oEvento.which)
         iAscii = oEvento.which;
     else
         return false;
     oFormulario.submit();

     return true;
}
</script>

<FORM METHOD="get" ACTION="" name="form1" id="form1">
<br>
	<tr><td>
	<table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'> 
			<tr id="mo">
				<td>
					<font size=2><b>Ingrese Expediente o Extracto para Buscar</b> </font>&nbsp; 
					<input type="text" style="font-size:10" name="efectores" size="20" id="efectores" maxlength="40" onkeyup="iSubmitEnter(event, document.form1)">
					<input type="hidden" name="legajos1" id="legajos1" value="<?=$id_legajo?>">
				</td>
			</tr>
	</table>
	</td></tr>
</FORM>

<script>
document.getElementById('efectores').focus();
</script>

<?if ($_GET['efectores'] || $_GET['efectores']=='0'){ 
	$nefectores= ($_GET['efectores']);
	$id_legajo= ($_GET['legajos1']);
	$sql=  "select a.id_expediente, a.extracto
			from expedientes.expediente a 			       
			WHERE  upper(a.extracto) like upper('%$nefectores%') or to_char(a.id_expediente,'999999') like upper('%$nefectores%')
			order by a.id_expediente ";
	$res_efectores=sql($sql) or fin_pagina();?>
	
	<script>
		document.getElementById('efectores').value='<?=$nefectores?>';
		document.getElementById('legajos1').value='<?=$id_legajo?>';
	</script>
	
	<table border=0 width=95% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align='center'>
		<tr>
    		<td align=right id='mo'>Numero Expediente</td>
			<td align=right id='mo'>Extracto</td>
		</tr>

		<?while (!$res_efectores->EOF){
			$ref = encode_link("vincula_exd.php",array("id_expediente"=>$res_efectores->fields['id_expediente'],"id_legajo"=>$id_legajo,"vincular"=>"ok"));
   			$onclick_elegir="if (confirm('Esta Seguro que Desea Vincular el Expediente ".$res_efectores->fields['id_expediente']."?')) location.href='$ref'
  								else return false;";
   			?>
			<tr <?=atrib_tr()?>>     
     			<td onclick="<?=$onclick_elegir?>" align='center'><b><?=$res_efectores->fields['id_expediente']?></b></td>
				<td onclick="<?=$onclick_elegir?>"><?=$res_efectores->fields['extracto']?></td>							
			</tr>
			<?$res_efectores->movenext();
		}?> 
	</table>		 
<?}?>
	<tr><td>
	<table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'> 
			<tr id="mo">
				<td>
					<input type="button" name="Cerrar Ventana" value="Cerrar Ventana" onclick="window.opener.location.reload(); window.close()">
				</td>
			</tr>
	</table>
	</td></tr>
<?echo fin_pagina();// aca termino?>
