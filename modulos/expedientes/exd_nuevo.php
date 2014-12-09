<?require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$usuario=$_ses_user['name'];
$login=$_ses_user['login'];
$sql="select id_usuario from sistema.usuarios where login='$login'";
$id_usuario=sql($sql, "Error al insertar la Planilla") or fin_pagina();
$id_usuario=$id_usuario->fields['id_usuario'];

if ($modo=="borrar_archivo") {

		/*cargo los log*/ 
		$fecha_actual=date("Y-m-d H:i:s");
	    $q_1="select nextval('expedientes.log_expediente_id_log_expediente_seq') as id_log";
	    $id_log=sql($q_1) or fin_pagina();
	    $id_log=$id_log->fields['id_log'];
	    
		$log="insert into expedientes.log_expediente
			   (id_log_expediente,id_expediente,fecha, tipo, descripcion, usuario,login) 
				values 
			   ($id_log, '$id_expediente','$fecha_actual','Borrado de Archivo Adjunto','Se borro el archivo $filename', '$usuario', '$login')";
		sql($log, "Error al insertar Log") or fin_pagina();

		$id=$parametros["id_archivo"];
		$filename=$parametros["filename"];
		$db->beginTrans();
		$query="delete from expedientes.archivos where id=$id and id_expediente=$id_expediente";
		sql($query, "Error al eliminar el Archivo ".$filename) or fin_pagina();
		if ((!$error)&&(unlink(UPLOADS_DIR."/archivosexd/$filename"))) $db->commitTrans();
		else{
			$db->Rollback();
			echo "<script>alert('No se pudo borrar el archivo')</script>";
		}
		$new="";
		$modo="modif";

		
}

if ($_POST['guardar_1']=="Guardar"){   
   		$db->StartTrans();         
		$fecha_actual=date("Y-m-d H:i:s");  
			 
	 	$q="select nextval('expedientes.expediente_id_expediente_seq') as id_planilla";
	    $id_planilla=sql($q) or fin_pagina();
	    $id_expediente=$id_planilla->fields['id_planilla'];

	    if ($fecha_creacion=='')$fecha_creacion='1980-01-01';
		else $fecha_creacion=fecha_db($fecha_creacion);
	       
	    $query="insert into expedientes.expediente
	             (id_expediente,extracto,fecha_actual,fecha_creacion,area_genera,area_actual,estado_simple,
				  id_estado,id_prioridad,id_tipo_tramite,id_nivel_acceso,observaciones,id_provincias,usuario_crea,
				  id_tipo_expediente)
	             values
	             ('$id_expediente','$extracto','$fecha_actual','$fecha_creacion','$area_genera','$area_genera','c',
				  '$id_estado','$id_prioridad','$id_tipo_tramite','$id_nivel_acceso','$observaciones','$id_provincias','$login',
				  '$id_tipo_expediente')";	
	    sql($query, "Error al insertar") or fin_pagina();
	    
	    $accion="Expediente Grabado";  
	     
		/*cargo los log*/ 
	    $q_1="select nextval('expedientes.log_expediente_id_log_expediente_seq') as id_log";
	    $id_log=sql($q_1) or fin_pagina();
	    $id_log=$id_log->fields['id_log'];
	    
		$log="insert into expedientes.log_expediente
			   (id_log_expediente,id_expediente,fecha, tipo, descripcion, usuario,login) 
				values 
			   ($id_log, '$id_expediente','$fecha_actual','Carga de Nuevo Expediente','Se crea Nuevo Expediente en Bandeja Personal', '$usuario', '$login')";
		sql($log, "Error al insertar Log") or fin_pagina();
    	$db->CompleteTrans();  ?>  

    	<script>
			document.location='./exd_listado_per.php';
		</script> 
<?}

if ($_POST['guardar_editar_1']=="Guardar"){
			$fecha_actual=date("Y-m-d H:i:s");
		   	$db->StartTrans();
	   
		    $query="update expedientes.expediente set
		             id_estado='$id_estado'
		             Where id_expediente='$id_expediente'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Modificacion Expediente";
			
		    /*cargo los log*/ 
		    $q_1="select nextval('expedientes.log_expediente_id_log_expediente_seq') as id_log";
			$id_log=sql($q_1) or fin_pagina();
			$id_log=$id_log->fields['id_log'];	    
			$log="insert into expedientes.log_expediente
			   (id_log_expediente,id_expediente,fecha, tipo, descripcion, usuario,login) 
				values 
			   ($id_log, '$id_expediente','$fecha_actual','Modificacion Datos de Expediente','Se Modifica Expediente en Bandeja Personal', '$usuario', '$login')";
			sql($log, "Error al insertar Log") or fin_pagina();
			 
		    $db->CompleteTrans();    
}//----------------Fin de guardar editar----------------

if ($id_expediente){
	$sql="select * from expedientes.expediente
	where id_expediente=$id_expediente";
    $res_registro=sql($sql, "Error al traer los Comprobantes") or fin_pagina();

    $extracto=$res_registro->fields['extracto'];
	$fecha_creacion=$res_registro->fields['fecha_creacion'];
	$area_genera=$res_registro->fields['area_genera'];
	$area_actual=$res_registro->fields['area_actual'];
	$estado_simple=$res_registro->fields['estado_simple'];
	$id_estado=$res_registro->fields['id_estado'];
	$id_prioridad=$res_registro->fields['id_prioridad'];
	$id_tipo_tramite=$res_registro->fields['id_tipo_tramite'];	
	$id_nivel_acceso=$res_registro->fields['id_nivel_acceso'];	
	$observaciones=$res_registro->fields['observaciones'];	
	$id_provincias=$res_registro->fields['id_provincias'];	
	$id_tipo_expediente=$res_registro->fields['id_tipo_expediente'];	
	$estado_simple=$res_registro->fields['estado_simple'];	
} 

echo $html_header;
cargar_calendario();
?>
<script>
var img_ext='<?=$img_ext='../../imagenes/rigth2.gif' ?>';//imagen extendido
var img_cont='<?=$img_cont='../../imagenes/down2.gif' ?>';//imagen contraido
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
}
//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos_1()
{
	if(document.form1.extracto.value==""){
	  alert('Debe Ingresar un Extracto');
	  document.form1.extracto.focus();
	  return false;
	}
	if(document.form1.fecha_creacion.value==""){
	  alert('Debe Ingresar una Fecha de Creacion');
	  document.form1.fecha_creacion.focus();
	  return false;
	}  
	if (confirm('Esta Seguro que Desea Crear Expediente?'))return true;
	else return false;	
}//de function control_nuevos()

function editar_campos_1()
{	
	document.form1.id_estado.disabled=false;
		
	document.all.guardar_editar_1.disabled=false;
	document.all.cancelar_editar_1.disabled=false;

	return true;
}

/**********************************************************/
//funciones para busqueda abreviada utilizando teclas en la lista que muestra los clientes.
var digitos=10; //cantidad de digitos buscados
var puntero=0;
var buffer=new Array(digitos); //declaraci�n del array Buffer
var cadena="";

function buscar_combo(obj)
{
   var letra = String.fromCharCode(event.keyCode)
   if(puntero >= digitos)
   {
       cadena="";
       puntero=0;
   }   
   //sino busco la cadena tipeada dentro del combo...
   else
   {
       buffer[puntero]=letra;
       //guardo en la posicion puntero la letra tipeada
       cadena=cadena+buffer[puntero]; //armo una cadena con los datos que van ingresando al array
       puntero++;

       //barro todas las opciones que contiene el combo y las comparo la cadena...
       //en el indice cero la opcion no es valida
       for (var opcombo=1;opcombo < obj.length;opcombo++){
          if(obj[opcombo].text.substr(0,puntero).toLowerCase()==cadena.toLowerCase()){
          obj.selectedIndex=opcombo;break;
          }
       }
    }//del else de if (event.keyCode == 13)
   event.returnValue = false; //invalida la acci�n de pulsado de tecla para evitar busqueda del primer caracter
}//de function buscar_op_submit(obj)

var warchivos=0;
function moveOver() {
	var boxLength;// = document.form1.compatibles.length;
  var prodLength = document.form1.sel_disponible.length;
  var selectedText;  // = document.choiceForm.available.options[selectedItem].text;
  var selectedValue; // = document.form1.productos.options[selectedItem].value;
  var i;
  var isNew = true;

  arrText = new Array();
  arrValue = new Array();
  var count = 0;

  for (i = 0; i < prodLength; i++) {
    if (document.form1.sel_disponible.options[i].selected) {
      arrValue[count] = document.form1.sel_disponible.options[i].value;
      arrText[count] = document.form1.sel_disponible.options[i].text;
      count++;
    }
	}
  for(j = 0; j < count; j++){
	  isNew = true;
		boxLength = document.form1.sel_afectado.length;
		selectedText=arrText[j];
 		selectedValue=arrValue[j];
		if (boxLength != 0) {
  	  for (i = 0; i < boxLength; i++) {
  		  thisitem = document.form1.sel_afectado.options[i].text;
      	if (thisitem == selectedText) {
        	isNew = false;
	      }
  	  }
	  }
  	if (isNew) {
  		newoption = new Option(selectedText, selectedValue, false, false);
	    document.form1.sel_afectado.options[boxLength] = newoption;
  	}
	  document.form1.sel_disponible.selectedIndex=-1;
  } 
}

function removeMe() {
  var boxLength = document.form1.sel_afectado.length;
  arrSelected = new Array();
  var count = 0;
  for (i = 0; i < boxLength; i++) {
    if (document.form1.sel_afectado.options[i].selected) {
      arrSelected[count] = document.form1.sel_afectado.options[i].value;
    }
    count++;
  }
  var x;
  for (i = 0; i < boxLength; i++) {
    for (x = 0; x < arrSelected.length; x++) {
      if (document.form1.sel_afectado.options[i].value == arrSelected[x]) {
        document.form1.sel_afectado.options[i] = null;
      }
    }
    boxLength = document.form1.sel_afectado.length;
  }
}
function val_text(){
	var a=new Array();
  var largo=document.form1.sel_afectado.length;
  var i=0;
  
  for(i;i<largo;i++){
  	a[i]=document.form1.sel_afectado.options[i].value;
  }
	document.form1.afectadosValues.value=a;
	document.form1.hguardar.value='sip';
	if ((typeof(document.form1.tcomentarios.value)!="undefined")&&(document.form1.tcomentarios.value!="")) document.form1.hcomentarios.value=document.form1.tcomentarios.value;
	else document.form1.hcomentarios.value=" ";
}
function update_disponibles(sel){
	var backup_value=new Array();
	var backup_text=new Array();
	var obj=document.form1.sel_disponible;
	
	document.form1.hlocacion.value=sel.options[sel.selectedIndex].text;

	if (document.form1.hbck_text.value==''){
		for(i=obj.length-1; i>=0; i--){
			backup_value[i]=obj.options[i].value+"|";
			backup_text[i]=obj.options[i].text+"|";
		}
		document.form1.hbck_value.value=backup_value;
		document.form1.hbck_text.value=backup_text;
	}
	
	var str_value= new String(document.form1.hbck_value.value);
	var str_text= new String(document.form1.hbck_text.value);
	backup_value=str_value.split("|");
	backup_text=str_text.split("|");
	for (i=obj.length-1; i>=0; i--) obj.options[i]=null;
	for (i=0, j=0; i<backup_text.length; i++){
		if ((backup_text[i].indexOf(document.form1.hlocacion.value)!=-1)||(document.form1.hlocacion.value==" ")){
			if (i!=0){
				var strt=backup_text[i].substring(1, backup_text[i].length);
				var strv=backup_value[i].substring(1, backup_value[i].length);
			}else{
				var strt=backup_text[i];
				var strv=backup_value[i];
			}
			newoption = new Option(strt, strv, false, false);
		  obj.options[j++] = newoption;
		}
	}	
}
function clearfields(){
	document.form1.afectadosValues.value="";
	document.form1.hlocacion.value="";
	document.form1.hbck_value.value="";
	document.form1.hbck_text.value="";
	document.form1.hguardar.value="";
}
</script>

<form name='form1' action='exd_nuevo.php' method='POST'>
<input type="hidden" value="<?=$id_expediente?>" name="id_expediente">
<input type="hidden" value="<?=$pagina_viene?>" name="pagina_viene">

<tr><td><table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'> 
	<tr id="mo">
  		<td id=mo colspan="2">
  			<b>Mensajes</b>
  		</td>
  	</tr>  
	<tr align="center">			
		   <td>
			<b><font size="3" color="Red"><?=$accion?></font></b>
		   </td>
     </tr>           
</table></td></tr> 

<?
/*******Traemos y mostramos el Log **********/
if ($id_expediente){
$q="SELECT *
	FROM
     expedientes.log_expediente   
	where id_expediente='$id_expediente'
	order by id_log_expediente DESC";
$log=sql($q);
?>
<div align="right">
	<input name="mostrar_ocultar_log" type="checkbox" value="1" onclick="if(!this.checked)
																	  document.all.tabla_logs.style.display='none'
																	 else 
																	  document.all.tabla_logs.style.display='block'
																	  "> Mostrar Logs
</div>	
<!-- tabla de Log de la OC -->
<div style="display:'none';width:98%;overflow:auto;<? if ($log->RowCount() > 3) echo 'height:60;' ?> " id="tabla_logs" >
<table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'>
<?while (!$log->EOF){?>
	<tr>
	      <td height="20" nowrap>Fecha <?=fecha($log->fields['fecha']). " " .Hora($log->fields['fecha']);?> </td>
	      <td nowrap > Usuario: <?=$log->fields['usuario']; ?> </td>
	      <td nowrap > Tipo: <?=$log->fields['tipo']; ?> </td>
	      <td nowrap > Descipcion: <?=$log->fields['descripcion']; ?> </td>	      
	      <td nowrap > Login: <?=$log->fields['login']; ?> </td>	      
	</tr>
	<?$log->MoveNext();
}?>
</table>
</div>
<hr>
<?}/*******************  FIN  LOG  ****************************/?>
<table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'>
 <tr id="mo">
    <td>
    	<?if ($id_expediente){?>
    		<font size=+1><b>Registro Expediente Numero: <font size=+1 color='red'><?=$id_expediente?></font></b></font>  	       
    	<?}
    	else{?>
    		<font size=+1><b>NUEVO Registro Expediente</b></font>  	       
    	<?}?>
    </td>
 </tr>
 
 <tr><td>
 <table width=100% align="center" >
	     <tr align="center" id="mo">
	      <td colspan="4" >
	       <b> Carga Datos </b>
	      </td>
	     </tr>
         
	     <tr>
	     	<td align="right">
				<b>Extracto:</b>
			</td>
		    <td align="left">
				<input type="text" size="80" value="<?=$extracto?>" name="extracto" <?if ($id_expediente) echo "disabled"?>>		    	 			    	 
		    </td>

         	<td align="right">
				<b>Tipo Expediente:</b>
			</td>
			<td align="left">			 	
			 <select name='id_tipo_expediente' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.tipo_expediente order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_tipo_expediente'];
			    $descripcion=$res->fields['desc_tipo_expediente'];?>
				<option value='<?=$id_select?>' <?if ($id_tipo_expediente==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>			    	    
		</tr>

		<tr>
	     	<td align="right">
         	  <b>Fecha Creacion:</b>
         	</td>         	
            <td align='left'>
              <?if (!$fecha_creacion or $fecha_creacion=='1980-01-01') $fecha_creacion='';
				  else $fecha_creacion=fecha($fecha_creacion)?>
		    	 <input type='text' id='fecha_creacion' name='fecha_creacion' value='<?=$fecha_creacion;?>' size=15 readonly> 
		    	 <?=link_calendario("fecha_creacion");?>
            </td> 

         	<td align="right">
				<b>Estado:</b>
			</td>
			<td align="left">			 	
			 <select name='id_estado' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.estado order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_estado'];
			    $descripcion=$res->fields['desc_estado'];?>
				<option value='<?=$id_select?>' <?if ($id_estado==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>			    	    
		</tr>

		<tr>
         	<td align="right">
				<b>Area Generadora:</b>
			</td>
			<td align="left">
			 <select name='area_genera' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select expedientes.areas.* 
			 		from expedientes.usu_area
			 		inner join expedientes.areas on (expedientes.usu_area.id_area = expedientes.areas.id_area)  
			 		where id_usuario='$id_usuario'
			 		order by desc_area";
			 $res=sql($sql) or fin_pagina();
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_area'];
			    $descripcion=$res->fields['desc_area'];?>
				<option value='<?=$id_select?>' <?if ($area_genera==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
       
         	<td align="right">
				<b>Prioridad:</b>
			</td>
			<td align="left">			 	
			 <select name='id_prioridad' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.prioridad order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_prioridad'];
			    $descripcion=$res->fields['desc_prioridad'];?>
				<option value='<?=$id_select?>' <?if ($id_prioridad==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
         </tr> 

         <tr>
         	<td align="right">
				<b>Provincia:</b>
			</td>
			<td align="left">
			 <select name='id_provincias' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select registro.provincia.* 
			 		from expedientes.usu_prov
			 		inner join registro.provincia on (expedientes.usu_prov.id_provincias = registro.provincia.id_provincia)  
			 		where id_usuario='$id_usuario'
			 		order by codigo";
			 $res=sql($sql) or fin_pagina();
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_provincia'];
			    $codigo=$res->fields['codigo'];
			    $descripcion=$res->fields['descripcion'];?>
				<option value='<?=$id_select?>' <?if ($id_provincias==$id_select) echo "selected"?> ><?=$codigo." - ".$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
       
         	<td align="right">
				<b>Tipo Tramite:</b>
			</td>
			<td align="left">			 	
			 <select name='id_tipo_tramite' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.tipo_tramite order by ORDEN";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_tipo_tramite'];
			    $descripcion=$res->fields['desc_tipo_tramite'];?>
				<option value='<?=$id_select?>' <?if ($id_tipo_tramite==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
         </tr> 

          <tr>
           	<td align="right">
				<b>Nivel de Acceso:</b>
			</td>
			<td align="left">			 	
			 <select name='id_nivel_acceso' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_expediente) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.nivel_acceso order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_nivel_acceso'];
			    $descripcion=$res->fields['desc_nivel_acceso'];?>
				<option value='<?=$id_select?>' <?if ($id_nivel_acceso==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
			<td align="right">
				<b>Observaciones:</b>
			</td>
		    <td align="left">
				<input type="text" size="80" value="<?=$observaciones?>" name="observaciones" <?if ($id_expediente) echo "disabled"?>>		    	 			    	 
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
	 	if (!$id_expediente){?>
			<input type="submit" name="guardar_1" value="Guardar" title="Guardar" style='width:130px;height:25px' onclick="return control_nuevos_1()" >&nbsp;&nbsp;
		<?}
		else{
			if (trim($estado_simple)=='a'){?>
				<input type=button name="editar" value="Editar" onclick="editar_campos_1()" title="Edita Campos" style='width:130px;height:25px' > &nbsp;&nbsp;
			    <input type="submit" name="guardar_editar_1" value="Guardar" title="Guardar" style='width:130px;height:25px' onclick="return control_nuevos_1()" disabled>&nbsp;&nbsp;
			    <input type="button" name="cancelar_editar_1" value="Cancelar" title="Cancela Edicion" disabled style='width:130px;height:25px' onclick="document.location.reload()">
	 	<?	}
	 	}?> 
	 </td>
	</tr>  

  </table></td></tr>
  </table>
<tr><td><table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'> 
	<tr id="mo">
  		<td id=mo colspan="2">
  			<b>Acciones</b>
  		</td>
  	</tr>  
	<tr align="center">	
			<? if ($pagina_viene=='') $pagina_viene='exd_listado_per.php'?>
		   <td>
			<input type=button name="volver" value="Volver" onclick="document.location='./<?=$pagina_viene?>'"title="Volver al Listado" style='width:130px;height:25px'>
		   </td>
     </tr>           
</table></td></tr> 

<script src="<?=$html_root?>/lib/bootstrap-3.1.1-dist/js/jquery.js" type="text/javascript"></script>
<script>
function realizaProceso(id1, id2, id3){
        var parametros = {
                "id1" : id1,
                "id2" : id2,
                "id3" : id3
            };
        $.ajax({
                data:  parametros,
                url:   'ajax_proceso.php',
                type:  'post',
                beforeSend: function () {
                        $("#resultado").html("Procesando, espere por favor...");
                },
                success:  function (response) {
                        $("#resultado").html(response);
                }
        });
}
</script>

<tr><td><table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'> 
	<tr id="mo">
  		<td id=mo colspan="2">
  			<b>Mensajes de Registros Soporte</b>
  		</td>
  	</tr>  
	<tr align="center">			
		   <td>
			<b><font size="3" color="Red"><span id="resultado"></span></font></b>
		   </td>
     </tr>           
</table></td></tr> 

<br>

<?if ($id_expediente){//memo
	$query="SELECT
			expedientes.pases.id_pases,
			expedientes.pases.fecha_pase,
			expedientes.tipo_pase.desc_tipo_pase,
			origen.desc_area AS origen,
			destino.desc_area AS destino,
			expedientes.motivo.desc_motivo,
			expedientes.pases.instrucciones,
			expedientes.pases.usuario,
			expedientes.pases.observaciones,
			expedientes.pases.fecha_aceptacion
			FROM
			expedientes.pases
			INNER JOIN expedientes.tipo_pase ON expedientes.pases.id_tipo_pase = expedientes.tipo_pase.id_tipo_pase
			INNER JOIN expedientes.areas as origen ON expedientes.pases.area_origen = origen.id_area
			INNER JOIN expedientes.areas as destino ON expedientes.pases.area_destino = destino.id_area
			INNER JOIN expedientes.motivo ON expedientes.pases.id_motivo = expedientes.motivo.id_motivo
			where id_expediente='$id_expediente'
			ORDER BY id_pases DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>
<br>
<tr><td><table width="100%" class="bordes" align="center">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida3,2);" >
	  </td>
	  <td align="center">
	   <b>PASES</b> &nbsp&nbsp
	   <?$ref =encode_link("./pases.php", array("pagina_viene"=>"exd_nuevo.php","id_expediente" =>$id_expediente));
	   $onclick="location.href='$ref'";?>
	   <?if ($pagina_viene!='exd_listado_tot.php'){?>
	   		&nbsp;&nbsp;<input type="button" name="boton" value='Agregar Nuevo' onclick="<?=$onclick?>" style='width:130px;height:25px' <?if (trim($estado_simple)=='d') echo 'disabled';?>>
	  	<?}?>
	  </td>
	</tr>
</table></td></tr>
<tr><td><table id="prueba_vida3" border="1" width="100%" style="display:none;border:thin groove"align="center">
	<?if ($res_comprobante->RecordCount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen: Pases</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">		
	 		<td >Nro</td> 	    
	 		<td >Fecha Pase</td>	 		
	 		<td >Tipo</td>	 		
	 		<td >Origen</td>	 		
	 		<td >Destino</td>
	 		<td >Motivo</td>	
	 		<td >Instruccion</td>	 			 		
	 		<td >Usuario</td> 
	 		<td >Observaciones</td> 
	 		<td >Estado</td> 
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 	while (!$res_comprobante->EOF) {
			
			$ref = encode_link("./pases.php", array("pagina_viene"=>"exd_nuevo","id_pases" =>$res_comprobante->fields["id_pases"],"id_expediente" =>$id_expediente));             
            $onclick_elegir="location.href='$ref'";?>

	 		<tr <?=atrib_tr()?>>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['id_pases']?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha_pase'])?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['desc_tipo_pase']?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['origen']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['destino']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['desc_motivo']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['instrucciones']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['usuario']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['observaciones']?></td>

		 		<?if (trim($res_comprobante->fields['fecha_aceptacion'])=='1980-01-01 00:00:00'){?>
		 			<?//ver por que cuando hago un pase a otra a area que no tengo acceso, le puedo aceptar el pase
		 			if ($pagina_viene=='exd_listado_tot.php') $disables_acepta_pase='disabled'; else $disables_acepta_pase=''?>
		 			<td align="center">
		 				Derivado: 
		 				<input type="button" href="javascript:;" value="Aceptar"
		 				onclick="if (confirm('Esta Seguro que desea Aceptar el PASE')){
		 							realizaProceso(<?=$res_comprobante->fields['id_pases']?>, <?=$id_expediente?>,'Aceptar');
								}" <?=$disables_acepta_pase?>/>
		 			</td>	
		 		<?}
		 		else{?> 	 		
		 		<td align="center"><?='Aceptado: '.fecha($res_comprobante->fields['fecha_aceptacion'])?></td>	
		 		<?}?> 			 		
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	}
	 }?>
</table></td></tr>
<?}?>

<br>
<?if (($id_expediente)and($estado_simple!='d')){//archivo?>
<table border="1" cellspacing="0" bgcolor="<?=$bgcolor3?>" width="100%" align="center">
			<tr>
				<td align="center" colspan="5">
					<?$sql_archivos="select * from expedientes.archivos where id_expediente=$id_expediente";
						$rta_archivos=sql($sql_archivos) or fin_pagina();?>
					Archivos (<?=$rta_archivos->recordcount()?> en total)
					<?if ($pagina_viene!='exd_listado_tot.php'){?>
						<input type="button" name="bagregar" value="Agregar" style='width:130px;height:25px' <?if (trim($estado_simple)=='d') echo 'disabled';?> 
						onclick="if (typeof(warchivos)=='object' && warchivos.closed || warchivos==false) warchivos=window.open('<?= encode_link('./archivos_subir.php',array("id_expediente"=>$id_expediente, "user"=>$_ses_user["name"], "onclickaceptar"=>"window.self.focus();", "proc_file"=>"./orden_file_proc.php")) ?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1'); else warchivos.focus()" <?=$new?>>
					<?}?>
				</td>	
			</tr>
			<?if ($rta_archivos->recordcount()>0){?>
			<tr>
				<td align=right id='mo'>Archivo</td>
				<td align=right id=mo>Fecha</td>
				<td align=right id=mo>Subido por</td>
				<td align=right id=mo>Tamaño</td>
				<td align=center id='mo'>&nbsp;</td>
			</tr>
				<?while (!$rta_archivos->EOF){?>
				<tr style='font-size: 9pt'>
					<td align=center>
 						<?if (is_file("../../uploads/archivosexd/".$rta_archivos->fields["nombre"])) 
 							echo "<a target=_blank href='".encode_link("../archivos/archivos_lista.php", array ("file" =>$rta_archivos->fields["nombre"],"size" => $rta_archivos->fields["size"],"cmd" => "download_exd"))."'>";
				  			echo $rta_archivos->fields["nombre"]."</a>";?>  
				  	</td>		  
  	  				<td align=center>&nbsp;<?=Fecha($rta_archivos->fields["fecha"])?></td>
				    <td align=center>&nbsp;<?= $rta_archivos->fields["creadopor"] ?></td>
				    <td align=center>&nbsp;<?= $size=number_format($rta_archivos->fields["size"] / 1024); ?> Kb</td>
					<?$lnk=encode_link("$_SERVER[PHP_SELF]",Array("id_expediente"=>$id_expediente,"id_archivo"=>$rta_archivos->fields["id"],"filename"=>$rta_archivos->fields["nombre"],"modo"=>"borrar_archivo"));
		      		$onclick_eliminar="if (confirm('Esta Seguro que Desea Eliminar el Archivo ".$rta_archivos->fields["nombre"]."?')) location.href='$lnk'
	            						else return false;	";?>
		      		<td onclick="<?=$onclick_eliminar?>" align="center"><img src='../../imagenes/sin_desc.gif' style='cursor:hand;'></td>
		      	</tr>
	  	   		<?$rta_archivos->movenext();
				}
			}?>		
		</table>
<?}?>

<br> 

  
 </table>  
 </form>
 
 <?=fin_pagina();// aca termino ?>
