<?require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$usuario=$_ses_user['name'];
$login=$_ses_user['login'];
$sql="select id_usuario from sistema.usuarios where login='$login'";
$id_usuario=sql($sql, "Error al insertar la Planilla") or fin_pagina();
$id_usuario=$id_usuario->fields['id_usuario'];

if ($_POST['guardar_1']=="Guardar"){   
		$fecha_actual=date("Y-m-d H:i:s");

		if ($fecha_inicio=='')$fecha_inicio='1980-01-01';
		else $fecha_inicio=fecha_db($fecha_inicio);

		if ($fecha_fin=='')$fecha_fin='1980-01-01';
		else $fecha_fin=fecha_db($fecha_fin);

   		$db->StartTrans();         
			 
	 	$q="select nextval('expedientes.contratacion_id_contratacion_seq') as id_planilla";
	    $id_planilla=sql($q) or fin_pagina();
	    $id_contratacion=$id_planilla->fields['id_planilla'];	    
	       
	    $query="insert into expedientes.contratacion
	             (id_contratacion,id_legajo,fecha_inicio,fecha_fin,observaciones,honorario,id_area_con,id_fuente_con,id_funcion_con,
	             	id_metodo_con,id_modalidad_con,id_tdr_con)
	             values
	             ('$id_contratacion','$id_legajo','$fecha_inicio','$fecha_fin','$observaciones','$honorario','$id_area_con','$id_fuente_con','$id_funcion_con',
	             	'$id_metodo_con','$id_modalidad_con','$id_tdr_con')";	
	    sql($query, "Error al insertar") or fin_pagina();
	    
	    $accion="Contratacion Grabada";  
	    	
    	$db->CompleteTrans();     
}

if ($_POST['guardar_editar_1']=="Guardar"){
			$fecha_actual=date("Y-m-d H:i:s");
			
			if ($fecha_inicio=='')$fecha_inicio='1980-01-01';
			else $fecha_inicio=fecha_db($fecha_inicio);

			if ($fecha_fin=='')$fecha_fin='1980-01-01';
			else $fecha_fin=fecha_db($fecha_fin);

			$db->StartTrans();
	   
		    $query="update expedientes.contratacion set
		             id_legajo='$id_legajo',
		             fecha_inicio='$fecha_inicio',
		             fecha_fin='$fecha_fin',
		             observaciones='$observaciones',
		             honorario='$honorario',
		             id_area_con='$id_area_con',
  					 id_fuente_con='$id_fuente_con',
  					 id_funcion_con='$id_funcion_con',
  					 id_metodo_con='$id_metodo_con',
  					 id_modalidad_con='$id_modalidad_con',
  					 id_tdr_con='$id_tdr_con'
		             Where id_contratacion='$id_contratacion'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Modificacion la Contratacion";
			 
		    $db->CompleteTrans();    
}//----------------Fin de guardar editar----------------

if ($id_contratacion){
	$sql="select * from expedientes.contratacion
	where id_contratacion=$id_contratacion";
    $res_registro=sql($sql, "Error al traer los Comprobantes") or fin_pagina();

    $id_legajo=$res_registro->fields['id_legajo'];
	$fecha_inicio=$res_registro->fields['fecha_inicio'];
	$fecha_fin=$res_registro->fields['fecha_fin'];
	$observaciones=$res_registro->fields['observaciones'];
	$honorario=$res_registro->fields['honorario'];
	$id_area_con=$res_registro->fields['id_area_con'];
	$id_fuente_con=$res_registro->fields['id_fuente_con'];
	$id_funcion_con=$res_registro->fields['id_funcion_con'];
	$id_metodo_con=$res_registro->fields['id_metodo_con'];
	$id_modalidad_con=$res_registro->fields['id_modalidad_con'];
	$id_tdr_con=$res_registro->fields['id_tdr_con'];
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
	if(document.form1.fecha_inicio.value==""){
	  alert('Debe Ingresar una Fecha Inicio');
	  document.form1.fecha_inicio.focus();
	  return false;
	}
	if(document.form1.fecha_fin.value==""){
	  alert('Debe Ingresar una Fecha de Fin');
	  document.form1.fecha_fin.focus();
	  return false;
	} 
	if(document.form1.honorario.value==""){
	  alert('Debe Ingresar un Honorario');
	  document.form1.honorario.focus();
	  return false;
	}   
	if (confirm('Esta Seguro que Desea Guardar?'))return true;
	else return false;	
}//de function control_nuevos()

function editar_campos_1()
{	
	document.form1.observaciones.disabled=false;
	document.form1.honorario.disabled=false;
	document.form1.id_area_con.disabled=false;
	document.form1.id_fuente_con.disabled=false;
	document.form1.id_funcion_con.disabled=false;
	document.form1.id_metodo_con.disabled=false;
	document.form1.id_modalidad_con.disabled=false;
	document.form1.id_tdr_con.disabled=false;
		
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

<form name='form1' action='con_nuevo.php' method='POST'>
<input type="hidden" value="<?=$id_legajo?>" name="id_legajo">
<input type="hidden" value="<?=$id_contratacion?>" name="id_contratacion">
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

<table width='95%' border='1' align="center" bgcolor='<?=$bgcolor_out?>'>
 <tr id="mo">
    <td>
    	<?if ($id_contratacion){?>
    		<font size=+1><b>Detalle Contratacion Numero: <font size=+1 color='red'><?=$id_contratacion?></font></b></font>  	       
    	<?}
    	else{?>
    		<font size=+1><b>NUEVO Detalle Contratacion</b></font>  	       
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
         	  <b>Fecha Inicio:</b>
         	</td>         	
            <td align='left'>
              <?if (!$fecha_inicio or $fecha_inicio=='1980-01-01') $fecha_inicio='';
				  else $fecha_inicio=fecha($fecha_inicio)?>
		    	 <input type='text' id='fecha_inicio' name='fecha_inicio' value='<?=$fecha_inicio;?>' size=15 readonly> 
		    	 <?=link_calendario("fecha_inicio");?>
            </td> 

         	<td align="right">
         	  <b>Fecha Fin:</b>
         	</td>         	
            <td align='left'>
              <?if (!$fecha_fin or $fecha_fin=='1980-01-01') $fecha_fin='';
				  else $fecha_fin=fecha($fecha_fin)?>
		    	 <input type='text' id='fecha_fin' name='fecha_fin' value='<?=$fecha_fin;?>' size=15 readonly> 
		    	 <?=link_calendario("fecha_fin");?>
            </td> 	    	    
		</tr>

	     <tr>
	     	<td align="right">
				<b>Observaciones:</b>
			</td>
		    <td align="left">
				<input type="text" size="50" value="<?=$observaciones?>" name="observaciones" <?if ($id_contratacion) echo "disabled"?>>		    	 			    	 
		    </td>

         	<td align="right">
				<b>Honorario:</b>
			</td>
		    <td align="left">
				<input type="text" size="20" value="<?=$honorario?>" name="honorario" <?if ($id_contratacion) echo "disabled"?>>		    	 			    	 
		    </td>       	    
		</tr>

		<tr>
	     	<td align="right">
				<b>Area:</b>
			</td>
			<td align="left">			 	
			 <select name='id_area_con' Style="width:200px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_contratacion) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.area_con order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_area_con'];
			    $descripcion=$res->fields['desc_area_con'];?>
				<option value='<?=$id_select?>' <?if ($id_area_con==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>	

			<td align="right">
				<b>Fuente:</b>
			</td>
			<td align="left">			 	
			 <select name='id_fuente_con' Style="width:200px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_contratacion) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.fuente_con order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_fuente_con'];
			    $descripcion=$res->fields['desc_fuente_con'];?>
				<option value='<?=$id_select?>' <?if ($id_fuente_con==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>        		    
		</tr>

		<tr>
	     	<td align="right">
				<b>Funcion:</b>
			</td>
			<td align="left">			 	
			 <select name='id_funcion_con' Style="width:200px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_contratacion) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.funcion_con order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_funcion_con'];
			    $descripcion=$res->fields['desc_funcion_con'];?>
				<option value='<?=$id_select?>' <?if ($id_funcion_con==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>	

			<td align="right">
				<b>Metodo:</b>
			</td>
			<td align="left">			 	
			 <select name='id_metodo_con' Style="width:200px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_contratacion) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.metodo_con order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_metodo_con'];
			    $descripcion=$res->fields['desc_metodo_con'];?>
				<option value='<?=$id_select?>' <?if ($id_metodo_con==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>         		    
		</tr>

		<tr>
	     	<td align="right">
				<b>TDR:</b>
			</td>
			<td align="left">			 	
			 <select name='id_tdr_con' Style="width:200px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_contratacion) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.tdr_con order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_tdr_con'];
			    $descripcion=$res->fields['desc_tdr_con'];?>
				<option value='<?=$id_select?>' <?if ($id_tdr_con==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>	

			<td align="right">
				<b>Modalidad:</b>
			</td>
			<td align="left">			 	
			 <select name='id_modalidad_con' Style="width:200px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_contratacion) echo "disabled"?>>
			 <?
			 $sql= "select * from expedientes.modalidad_con order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_modalidad_con'];
			    $descripcion=$res->fields['desc_modalidad_con'];?>
				<option value='<?=$id_select?>' <?if ($id_modalidad_con==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
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
	 	if (!$id_contratacion){?>
			<input type="submit" name="guardar_1" value="Guardar" title="Guardar" style='width:130px;height:25px' onclick="return control_nuevos_1()" >&nbsp;&nbsp;
		<?}
		else{?>
				<input type=button name="editar" value="Editar" onclick="editar_campos_1()" title="Edita Campos" style='width:130px;height:25px' > &nbsp;&nbsp;
			    <input type="submit" name="guardar_editar_1" value="Guardar" title="Guardar" style='width:130px;height:25px' onclick="return control_nuevos_1()" disabled>&nbsp;&nbsp;
			    <input type="button" name="cancelar_editar_1" value="Cancelar" title="Cancela Edicion" disabled style='width:130px;height:25px' onclick="document.location.reload()">
	 	<?}?> 
	 		<input type=button name="cerrar" value="Cerrar" onclick="window.opener.location.reload();window.close()"title="Cerrar Pantalla" style='width:130px;height:25px'>

	 </td>
	</tr>  

  </table></td></tr>
  </table>

 </table>  
 </form>
 
 <?=fin_pagina();// aca termino ?>
