<?php

require_once("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

$usuario=$_ses_user['name'];
$login=$_ses_user['login'];
$sql="select id_usuario from sistema.usuarios where login='$login'";
$id_usuario=sql($sql, "Error al insertar la Planilla") or fin_pagina();
$id_usuario=$id_usuario->fields['id_usuario'];

if($_POST["guardar"]=="Guardar"){
   $fecha_pase=Fecha_db($fecha_pase); 
   $fecha_aceptacion='1980-01-01';
   
   $db->StartTrans();
   
   	$q_1="select nextval('expedientes.pases_id_pases_seq') as id_log";
	$id_log=sql($q_1) or fin_pagina();
	$id_pases=$id_log->fields['id_log'];

   $query="insert into expedientes.pases
			(id_pases,id_expediente,fecha_pase,usuario,id_tipo_pase,area_origen,area_destino,id_motivo,instrucciones,id_prioridad,
				observaciones,fecha_aceptacion)
			values
			('$id_pases','$id_expediente','$fecha_pase','$login','$id_tipo_pase','$area_origen','$area_destino','$id_motivo','$instrucciones','$id_prioridad',
				'$observaciones','$fecha_aceptacion')";   			
   sql($query, "Error al insertar") or fin_pagina();

   $query="update expedientes.expediente set
	        area_actual='$area_destino',
	        estado_simple='d'
	        Where id_expediente='$id_expediente'";
   sql($query, "Error al modificar") or fin_pagina();

   /*cargo los log*/ 
	$fecha_actual=date("Y-m-d H:i:s");
	$q_1="select nextval('expedientes.log_expediente_id_log_expediente_seq') as id_log";
	$id_log=sql($q_1) or fin_pagina();
	$id_log=$id_log->fields['id_log'];
	   
	$log="insert into expedientes.log_expediente
		   (id_log_expediente,id_expediente,fecha, tipo, descripcion, usuario,login) 
			values 
		   ($id_log, '$id_expediente','$fecha_actual','Nuevo Pase','Alta de pase Nro $id_pases', '$usuario', '$login')";
	sql($log, "Error al insertar Log") or fin_pagina();
      
   $db->CompleteTrans();
   
   $ref = encode_link("exd_nuevo.php",array("id_expediente"=>$id_expediente));                
   echo "<script>   			
   			location.href='$ref';
   		</script>";    	
 }

echo $html_header;
cargar_calendario();

if ($id_pases){
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
			where id_pases='$id_pases'";
	$res_registro=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();
	
	$id_pases=$res_registro->fields['id_pases'];
	$fecha_pase=$res_registro->fields['fecha_pase'];
	$desc_tipo_pase=$res_registro->fields['desc_tipo_pase'];
	$origen=$res_registro->fields['origen'];
	$destino=$res_registro->fields['destino'];
	$desc_motivo=$res_registro->fields['desc_motivo'];
	$instrucciones=$res_registro->fields['instrucciones'];
	$usuario=$res_registro->fields['usuario'];
	$observaciones=$res_registro->fields['observaciones'];
	$fecha_aceptacion=$res_registro->fields['fecha_aceptacion'];
}?>

<script>
//controlan que ingresen todos los datos necesarios par el muleto
function control_nuevos()
{
 if(document.form1.fecha_pase.value==""){
  alert('Debe Ingresar una fecha de pase');
  return false;
 }
 
 if (confirm('Esta Seguro que Desea Realizar Pase?'))return true;
 else return false;	
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

<form name=form1 action="pases.php" method=POST>
<input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
<input type="hidden" name="id_pases" value="<?=$id_pases?>">
<input type="hidden" name="id_expediente" value="<?=$id_expediente?>">

<table cellspacing='2' cellpadding='2' width='60%' align='center' border='1' bgcolor='<?=$bgcolor_out?>'>
<br>
    <tr id="mo">
    	<td align="center" colspan="2">
    		<b>PASES</b>
    	</td>    	
    </tr>
	
	<tr>
		<td align="right" ><b>Fecha:</b></td>
		<td align="left" >
			<?$fecha_pase=fecha($fecha_pase)?>
			<input type=text id=fecha_pase name=fecha_pase value='<?=$fecha_pase;?>' size=15 readonly>
        	 <?=link_calendario("fecha_pase");?>					    	 
		</td>		    
	</tr>
	
    <tr>
		<td align="right" ><b>Tipo de Pase:</b></td>
		<td align="left" >		          			
			<select name='id_tipo_pase' Style="width:257px" <?if ($id_pases) echo "disabled"?> >
			 <?$sql= "select * from expedientes.tipo_pase order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_tipo_pase'];
			    $descripcion=$res->fields['desc_tipo_pase'];?>
				<option value='<?=$id_select?>' <?if ($id_tipo_pase==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
		</td>
	</tr>
	<tr>
		<td align="right">
				<b>Area Origen:</b>
			</td>
			<td align="left">
			 <select name='area_origen' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_pases) echo "disabled"?> >
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
	</tr>
	<tr>
		<td align="right">
				<b>Area Destino:</b>
			</td>
			<td align="left">
			 <select name='area_destino' Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_pases) echo "disabled"?> >
			 <?
			 $sql= "select * 
			 		from expedientes.areas			 		
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
	</tr>	
	

	<tr>
		<td align="right" ><b>Tipo de Pase:</b></td>
		<td align="left" >		          			
			<select name='id_motivo' Style="width:257px" <?if ($id_pases) echo "disabled"?> >
			 <?$sql= "select * from expedientes.motivo order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_motivo'];
			    $descripcion=$res->fields['desc_motivo'];?>
				<option value='<?=$id_select?>' <?if ($id_motivo==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
		</td>
	</tr>

	<tr>
		<td align="right" ><b>Instrucciones:</b></td>
		<?if ($instrucciones=='')$instrucciones='Se envia para la prosecucion del tramite'?>
		<td align="left" >		          			
			<input type="text" name="instrucciones" value="<?=$instrucciones?>" size=60 align="right" <?if ($id_pases) echo "disabled"?>>
		</td>
	</tr>

	<tr>
		<td align="right" ><b>Prioridad:</b></td>
		<td align="left" >		          			
			<select name='id_prioridad' Style="width:257px" <?if ($id_pases) echo "disabled"?> >
			 <?$sql= "select * from expedientes.prioridad order by orden";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_select=$res->fields['id_prioridad'];
			    $descripcion=$res->fields['desc_prioridad'];?>
				<option value='<?=$id_select?>' <?if ($id_motivo==$id_select) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
		</td>
	</tr>

	<tr>
		<td align="right" ><b>Observaciones:</b></td>
		<td align="left" >		          			
			<input type="text" name="observaciones" value="<?=$observaciones?>" size=60 align="right" <?if ($id_pases) echo "disabled"?>>
		</td>
	</tr>

    <tr>
    	<td align="center" colspan="2"  id="mo">
    		<?if (!$id_pases){?>
    			<input type="submit" name="guardar" value="Guardar" onclick="return control_nuevos()" style='width:130px;height:25px'>
			<?}?>
			&nbsp;&nbsp;&nbsp;
    		<?$ref = encode_link("exd_nuevo.php",array("id_expediente"=>$id_expediente)); ?>               
   			<input type="button" name="cerrar" value="Volver" onclick="document.location='<?=$ref?>'" style='width:130px;height:25px'>
    	</td>    	
    </tr>    
</table>
<br>
<br>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>