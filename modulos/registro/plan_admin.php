<?require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

if ($modo=="borrar_archivo") {
		$id=$parametros["id_archivo"];
		$filename=$parametros["filename"];
		$db->beginTrans();
		$query="delete from registro.archivos where id=$id and id_registro=$id_registro";
		sql($query, "Error al eliminar el Archivo ".$filename) or fin_pagina();
		if ((!$error)&&(unlink(UPLOADS_DIR."/archivoscalidad/$filename"))) $db->commitTrans();
		else{
			$db->Rollback();
			echo "<script>alert('No se pudo borrar el archivo')</script>";
		}
		$new="";
		$modo="modif";
}
	
if ($_POST['guardar_1']=="Guardar"){   
   $db->StartTrans();         
		$fecha_carga=date("Y-m-d H:i:s");  
		$usuario=$_ses_user['name'];		
	 
	 	$q="select nextval('registro.registro_id_registro_seq') as id_planilla";
	    $id_planilla=sql($q) or fin_pagina();
	    $id_registro=$id_planilla->fields['id_planilla'];
	       
	    $query="insert into registro.registro
	             (id_registro,id_provincia,tipo_liq,id_periodo,nro_exd,usuario,fecha_carga,estado_capitas,estado_adm)
	             values
	             ('$id_registro','$provincia','$tipo_liq','$periodo','$nro_exd','$usuario','$fecha_carga',0,0)";
	
	    sql($query, "Error al insertar la Planilla") or fin_pagina();
	    
	    $accion="Registro Grabado";  
	     
		$q="select nextval('registro.dato_registro_id_dato_registro_seq') as id_planilla";
	    $id_planilla=sql($q) or fin_pagina();
	    $id_dato_registro=$id_planilla->fields['id_planilla'];
	       
	    $query="insert into registro.dato_registro
	             (id_dato_registro,id_registro)
	             values
	             ('$id_dato_registro','$id_registro')";
	
	    sql($query, "Error al insertar la Planilla") or fin_pagina();
	    	    		 
		 
		 /*cargo los log*/ 
	    $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
	    $id_log=sql($q_1) or fin_pagina();
	    $id_log=$id_log->fields['id_log'];
	    
		    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_carga','Nuevo Registro de Calidad','Se inserta nuevo Registro', '$usuario')";
			sql($log) or fin_pagina();
			


    $db->CompleteTrans();    
    
}//de if ($_POST['guardar']=="Guardar nuevo Muleto")


if ($_POST['guardar_editar_1']=="Guardar"){
			$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             id_provincia='$provincia',
		             tipo_liq= '$tipo_liq',
		             id_periodo= '$periodo',
		             nro_exd= '$nro_exd'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se guardo la modificacion del Registro Basico";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Registro Calidad Datos basicos', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans();    
	}//----------------Fin de guardar editar----------------

//guardo estados en registro de Capitas
if ($_POST['guardar_revisado_2']=="Revisado"){
	$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             estado_capitas= '1'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Marco como Revisado el Registro Capitas";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Se Marco como Revisado el Registro Capitas', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans(); 
		    $contenido_mail_control="El Plan de Calidad (Registro de Capitas) Numero: $id_registro fue Marcado como Revisado por el usuario $usuario, queda pendiente de Aprobacion.
			Notificacion: $fecha_mod. POR FAVOR VERIFICAR DETALLES EN EL SISTEMA DE CALIDAD";
    		//enviar_mail('fernandonu@gmail.com','','','Notificacion Calidad',$contenido_mail_control,'','');
}

if ($_POST['guardar_aprobado_2']=="Aprobado"){
	$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             estado_capitas= '2'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Marco como APROBACION del Registro Capitas";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Marco como APROBACION del Registro Capitas', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans(); 
}

if ($_POST['guardar_crevision_2']=="Corregir Revisado"){
	$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             estado_capitas= '0'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Abre Registro Capitas para Nueva Revision";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Se Abre Registro Capitas para Nueva Revision', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans(); 
}
//guardo estados en registro de administracion
if ($_POST['guardar_revisado_3']=="Revisado"){
	$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             estado_adm= '1'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Marco como Revisado el Registro Administracion";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Se Marco como Revisado el Registro Administracion', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans(); 

		    $contenido_mail_control="El Plan de Calidad (Registro de Administracion) Numero: $id_registro fue Marcado como Revisado por el usuario $usuario, queda pendiente de Aprobacion.
			Notificacion: $fecha_mod. POR FAVOR VERIFICAR DETALLES EN EL SISTEMA DE CALIDAD";
    		//enviar_mail('fernandonu@gmail.com','','','Notificacion Calidad',$contenido_mail_control,'','');
}

if ($_POST['guardar_aprobado_3']=="Aprobado"){
	$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             estado_adm= '2'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Marco como APROBACION del Registro Administracion";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Marco como APROBACION del Registro de Administracion', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans(); 
}

if ($_POST['guardar_crevision_3']=="Corregir Revisado"){
	$usuario=$_ses_user['name'];

		   $fecha_mod=date("Y-m-d H:i:s");
		   $db->StartTrans();
	   
		    $query="update registro.registro set
		             estado_adm= '0'
		             Where id_registro='$id_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se Abre Registro Administracion para Nueva Revision";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Se Abre Registro de Administracion para Nueva Revision', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans(); 
}
//fin de gardar registro administracion y Capitas

if ($_POST['guardar_editar_2']=="Guardar"){
			$usuario=$_ses_user['name'];
		    $fecha_mod=date("Y-m-d H:i:s");
			$revision=fecha_db($revision);
			if ($comp_ajuste=='')$comp_ajuste='1980-01-01';
			else $comp_ajuste=fecha_db($comp_ajuste);
			$liq=fecha_db($liq);
			if ($comp_inf=='')$comp_inf='1980-01-01';
			else $comp_inf=fecha_db($comp_inf);
			$env_exd=fecha_db($env_exd);
			if ($env_res=='')$env_res='1980-01-01';
			else $env_res=fecha_db($env_res);
			$fecha_val=fecha_db($fecha_val);
			$fecha_val1=fecha_db($fecha_val1);			
			$fecha_reval=fecha_db($fecha_reval);
			$fecha_reval1=fecha_db($fecha_reval1);			
			
		   $db->StartTrans();
	   
		    $query="update registro.dato_registro set
		            revision= '$revision',
					validacion= '$validacion',
					revalidacion= '$revalidacion',
					sop_inf= '$sop_inf',
					sis_ges= '$sis_ges',
					ajuste= '$ajuste',
					comp_ajuste= '$comp_ajuste',
					calc_transf= '$calc_transf',
					liq= '$liq',
					comp_inf= '$comp_inf',
					valida= '$valida',
					revalid= '$revalid',
					env_exd= '$env_exd',
					env_res= '$env_res',
					fecha_val= '$fecha_val',
					fecha_val1= '$fecha_val1',					
					fecha_reval= '$fecha_reval',
					fecha_reval1= '$fecha_reval1',					
					observacion= '$observacion'
		             Where id_dato_registro='$id_dato_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se guardo la modificacion del Registro de Capitas";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Registro Calidad Datos Complementarios', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans();    
	}//----------------Fin de guardar editar----------------
	
if ($_POST['guardar_editar_3']=="Guardar"){
			$usuario=$_ses_user['name'];
		    $fecha_mod=date("Y-m-d H:i:s");
		    $importe=str_replace('.','',$importe);		    
		    $importe=str_replace(',','.',$importe);
			
			if ($fecha_ingreso=='')$fecha_ingreso='1980-01-01';
			else $fecha_ingreso=fecha_db($fecha_ingreso);

			if ($fecha_adm=='')$fecha_adm='1980-01-01';
			else $fecha_adm=fecha_db($fecha_adm);

			if ($dia_salida=='')$dia_salida='1980-01-01';
			else $dia_salida=fecha_db($dia_salida);

			if ($fecha_pago=='')$fecha_pago='1980-01-01';
			else $fecha_pago=fecha_db($fecha_pago);

			if ($fecha_val2=='')$fecha_val2='1980-01-01';
			else $fecha_val2=fecha_db($fecha_val2);

			if ($fecha_reval2=='')$fecha_reval2='1980-01-01';
			else $fecha_reval2=fecha_db($fecha_reval2);

		   $db->StartTrans();
	   
		    $query="update registro.dato_registro set
		            importe= '$importe',
					fecha_ingreso= '$fecha_ingreso',
					revision_exd= '$revision_exd',
					valid_adm= '$valid_adm',
					contingencia= '$contingencia',
					fecha_adm= '$fecha_adm',
					revalid_adm= '$revalid_adm',
					armado_autorizacion= '$armado_autorizacion',
					aut_capitas= '$aut_capitas',
					nota_ufis= '$nota_ufis',
					dia_salida= '$dia_salida',	
					fecha_val2= '$fecha_val2',	
					fecha_reval2= '$fecha_reval2',			
					fecha_pago= '$fecha_pago'
		            Where id_dato_registro='$id_dato_registro'";
		
		    sql($query, "Error al modificar la factura $id_factura") or fin_pagina();
		    
		    $accion="Se guardo la modificacion del Registro Administracion";
			
		    /*cargo los log*/ 
		     $q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
			 $id_log=sql($q_1) or fin_pagina();
			 $id_log=$id_log->fields['id_log'];	    
			$log="insert into registro.log_registro
				   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
			values ($id_log, '$id_registro','$id_dato_registro','$fecha_mod','Modificacion','Registro Calidad Datos de Administracion', '$usuario')";
			sql($log) or fin_pagina();
			 
		    $db->CompleteTrans();    
	}//----------------Fin de guardar editar----------------

if ($id_registro){
	$sql="select * from registro.registro
	where id_registro=$id_registro";
    $res_registro=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
    
    $id_provincia=$res_registro->fields['id_provincia'];
	$tipo_liq=$res_registro->fields['tipo_liq'];
	$id_periodo=$res_registro->fields['id_periodo'];
	$nro_exd=$res_registro->fields['nro_exd'];
	$usuario=$res_registro->fields['usuario'];
	$fecha_carga=$res_registro->fields['fecha_carga'];
	$estado_capitas=$res_registro->fields['estado_capitas'];
	$estado_adm=$res_registro->fields['estado_adm'];
	
	$sql="select * from registro.dato_registro
	where id_registro=$id_registro";
    $res_registro_1=sql($sql, "Error al traer los Comprobantes") or fin_pagina();
	
	$id_dato_registro=$res_registro_1->fields['id_dato_registro'];
	$revision=$res_registro_1->fields['revision'];
	$validacion=$res_registro_1->fields['validacion'];
	$revalidacion=$res_registro_1->fields['revalidacion'];
	$sop_inf=$res_registro_1->fields['sop_inf'];
	$sis_ges=$res_registro_1->fields['sis_ges'];
	$ajuste=$res_registro_1->fields['ajuste'];
	$comp_ajuste=$res_registro_1->fields['comp_ajuste'];
	$calc_transf=$res_registro_1->fields['calc_transf'];
	$liq=$res_registro_1->fields['liq'];
	$comp_inf=$res_registro_1->fields['comp_inf'];
	$valida=$res_registro_1->fields['valida'];
	$revalid=$res_registro_1->fields['revalid'];
	$env_exd=$res_registro_1->fields['env_exd'];
	$env_res=$res_registro_1->fields['env_res'];
	$observacion=$res_registro_1->fields['observacion'];	
	$importe=$res_registro_1->fields['importe'];	
	$fecha_ingreso=$res_registro_1->fields['fecha_ingreso'];	
	$revision_exd=$res_registro_1->fields['revision_exd'];	
	$valid_adm=$res_registro_1->fields['valid_adm'];	
	$contingencia=$res_registro_1->fields['contingencia'];	
	$fecha_adm=$res_registro_1->fields['fecha_adm'];	
	$revalid_adm=$res_registro_1->fields['revalid_adm'];	
	$armado_autorizacion=$res_registro_1->fields['armado_autorizacion'];	
	$aut_capitas=$res_registro_1->fields['aut_capitas'];	
	$nota_ufis=$res_registro_1->fields['nota_ufis'];	
	$dia_salida=$res_registro_1->fields['dia_salida'];	
	$fecha_pago=$res_registro_1->fields['fecha_pago'];	
	$fecha_val=$res_registro_1->fields['fecha_val'];
	$fecha_val1=$res_registro_1->fields['fecha_val1'];
	$fecha_val2=$res_registro_1->fields['fecha_val2'];
	$fecha_reval=$res_registro_1->fields['fecha_reval'];
	$fecha_reval1=$res_registro_1->fields['fecha_reval1'];
	$fecha_reval2=$res_registro_1->fields['fecha_reval2'];
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
	 if(document.form1.provincia.value=="-1"){
	  alert('Debe Seleccionar una Provincia');
	  return false;
	 } 
	 if(document.form1.tipo_liq.value=="-1"){
	  alert('Debe Seleccionar un Tipo Liquidacion');
	  return false;
	 } 
	 if(document.form1.periodo.value=="-1"){
	  alert('Debe Seleccionar un Periodo');
	  return false;
	 } 
	 if(document.form1.nro_exd.value==""){
	  alert('Debe Seleccionar un Numero Expediente');
	  return false;
	 } 
 if (confirm('Esta Seguro que Desea Agregar Registro?'))return true;
	 else return false;	
}//de function control_nuevos()

function control_nuevos_2()
{
 if (confirm('Esta Seguro que Desea Agregar Registro Complementarios?'))return true;
	 else return false;	
}//de function control_nuevos()


function control_nuevos_3()
{
 if (confirm('Esta Seguro que Desea Agregar Registro de Administracion?'))return true;
	 else return false;	
}//de function control_nuevos()

function editar_campos_1()
{	
	document.form1.provincia.disabled=false;
	document.form1.tipo_liq.disabled=false;
	document.form1.periodo.disabled=false;
	document.form1.nro_exd.disabled=false;
		
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

<form name='form1' action='plan_admin.php' method='POST'>
<input type="hidden" value="<?=$id_registro?>" name="id_registro">
<input type="hidden" value="<?=$id_dato_registro?>" name="id_dato_registro">

<?php if ($accion){?>
<table width='85%' border='1' align="center" class="table table-bordered"> 
	<tr id="mo">
  		<td id=mo colspan="2">
  			<b>Mensajes de Registros</b>
  		</td>
  	</tr>  
	<tr align="center"class="table table-bordered">			
		   <td>
			<b><font size="3" color="Red"><?=$accion?></font></b>
		   </td>
     </tr>           
</table>
<?php }?>


<?
/*******Traemos y mostramos el Log **********/
if ($id_registro){
$q="SELECT 
	  *
	FROM
     registro.log_registro       
	where log_registro.id_registro='$id_registro'
	order by id_log_registro DESC";
$log=sql($q);
?>
	<input name="mostrar_ocultar_log" type="checkbox" value="0" onclick="if(!this.checked)
																	  document.all.tabla_logs.style.display='none'
																	 else 
																	  document.all.tabla_logs.style.display='block'
																	  "> Mostrar Logs
<!-- tabla de Log de la OC -->
<div style="none:'block';width:98%;overflow:auto;<? if ($log->RowCount() > 3) echo 'height:60;' ?> " id="tabla_logs" >
<table width='100%' border='1' align="center" class="table table-bordered"> 
<?while (!$log->EOF){?>
	<tr class="table table-bordered">
	      <td height="20" nowrap>Fecha <?=fecha($log->fields['fecha']). " " .Hora($log->fields['fecha']);?> </td>
	      <td nowrap > Usuario : <?=$log->fields['usuario']; ?> </td>
	      <td nowrap > Tipo : <?=$log->fields['tipo']; ?> </td>
	      <td nowrap > descipcion : <?=$log->fields['descripcion']; ?> </td>	      
	</tr>
	<?$log->MoveNext();
}?>
</table>
</div>

<?}/*******************  FIN  LOG  ****************************/?>
<table width='85%' border='1' align="center" class="table table-bordered">
 <tr id="mo">
    <td>
    	<?if ($id_registro){?>
    		<font size=+1><b>Plan de Calidad <?='RG-SGC N PC'.$id_registro.'_03'?></b></font>   
    	<?}
    	else{?>
    		<font size=+1><b>Plan de Calidad NUEVO</b></font>
    	<?}?> 	       
    </td>
 </tr>
 
 <tr ><td class="table table-bordered">
 <table width=100% align="center" class="table table-bordered">
	     <tr align="center" id="mo">
	      <td colspan="4" >
	       <b> Carga Datos </b>
	      </td>
	     </tr>
         <tr >
         	<td align="right">
				<b>Provincia:</b>
			</td>
			<td align="left">			 	
			 <select name=provincia Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_registro) echo "disabled"?>>
			 <option value=-1>Seleccione</option>
			 <?
			 $sql= "select * from registro.provincia order by codigo";
			 $res=sql($sql) or fin_pagina();
			 while (!$res->EOF){ 
			 	$id_provincia1=$res->fields['id_provincia'];
			    $codigo=$res->fields['codigo'];
			    $descripcion=$res->fields['descripcion'];?>
				<option value='<?=$id_provincia1?>' <?if ($id_provincia==$id_provincia1) echo "selected"?> ><?=$codigo." - ".$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
       
         	<td align="right">
         	  <b>Tipo Liquidacion:</b>
         	</td>         	
            <td align='left'>
              <select name=tipo_liq Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_registro) echo "disabled"?>>
				<option value=-1>Seleccione</option>			 
				<option value=m <?if ($tipo_liq=='m') echo "selected"?>>Mensual</option>			 
				<option value=c <?if ($tipo_liq=='c') echo "selected"?>>Cuatrimestral</option>			 
				<option value=mf <?if ($tipo_liq=='mf') echo "selected"?>>Mensual FRSEC</option>			 
				<option value=cf <?if ($tipo_liq=='cf') echo "selected"?>>Cuatrimestral FRSEC</option>			 
			</select>
            </td>
         </tr> 
		<tr>
         	<td align="right">
				<b>Periodo:</b>
			</td>
			<td align="left">			 	
			 <select name=periodo Style="width:257px" 
        		onKeypress="buscar_combo(this);"
				onblur="borrar_buffer();"
				onchange="borrar_buffer();" 
				<?if ($id_registro) echo "disabled"?>>
			 <option value=-1>Seleccione</option>
			 <?
			 $sql= "select * from registro.periodo order by descripcion";
			 $res=sql($sql) or fin_pagina();
			
			 while (!$res->EOF){ 
			 	$id_periodo1=$res->fields['id_periodo'];
			 	$tipo_liq_per=$res->fields['tipo_liq_per'];
			    $descripcion=$res->fields['descripcion'];?>
				<option value='<?=$id_periodo1?>' <?if ($id_periodo==$id_periodo1) echo "selected"?> ><?=$descripcion?></option>
			    <?
			    $res->movenext();
			    }?>
			</select>
			</td>
         
			<td align="right">
				<b>Numero Expediente:</b>
			</td>
		    <td align="left">
				<input type="text" size="40" value="<?=$nro_exd?>" name="nro_exd" <?if ($id_registro) echo "disabled"?>>		    	 			    	 
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
	 	if($estado_capitas=="0") $desabilita_editar_1=""; else $desabilita_editar_1="disabled";
	 	
	 	if (!$id_registro){?>
			<input type="submit" name="guardar_1" value="Guardar" title="Guardar" style="width:150px;height:50px" onclick="return control_nuevos_1()" >&nbsp;&nbsp;
		<?}
		else{?>
			<input type=button name="editar" value="Editar" onclick="editar_campos_1()" title="Edita Campos" style="width:130px" <?=$desabilita_editar_1?>> &nbsp;&nbsp;
		    <input type="submit" name="guardar_editar_1" value="Guardar" title="Guardar" style="width:130px" onclick="return control_nuevos_1()" disabled>&nbsp;&nbsp;
		    <input type="button" name="cancelar_editar_1" value="Cancelar" title="Cancela Edicion" disabled style="width:130px" onclick="document.location.reload()">
	 	<?}?> 
	 </td>
	</tr>

	<?if ($id_registro){?>
	<table width=100% align="center" class="table table-bordered">
     <tr align="center" id="mo">
      <td colspan="4" >
       <b> Carga datos Capitas </b>
      </td>
     </tr>
	 
		<tr>	
			<td align="right">
				<b>Fecha Ingreso y revision DDJJ:</b>
			</td>
			<td align="left">
		    	<?if (!$revision) $revision=date("d/m/Y");
				  else $revision=fecha($revision)?>
		    	 <input type=text id=revision name=revision value='<?=$revision;?>' size=15  readonly> 
		    	 <?=link_calendario("revision");?>
		    </td>

			<td align="right">
         	  &nbsp
         	</td>         	
            <td align='left'>
              &nbsp
            </td>	    		    
		</tr>  
		
		<script type="text/javascript">
			function muestra_revalid(obj_tabla,nro){
				if (nro=='1') obj_tabla.style.display='none';
				else obj_tabla.style.display='inline';
			}
   		</script>

		<tr>	
			<td align="right">
				<b>Validacion:</b>
			</td>	
			<td align="left">	
		    	<input type="radio" name="validacion" value="S" <?=($validacion=='S')?'checked':'';?> onclick="muestra_revalid(document.all.revalid1,1);document.form1.fecha_val.focus();">Si
				<input type="radio" name="validacion" value="N" <?=($validacion=='N')?'checked':'';?> onclick="muestra_revalid(document.all.revalid1,2);alert('Debe Generar Producto NO Conforme');" >No
		    </td>
			<td align="right">
				<b>Fecha Validacion:</b>
			</td>
			<td align="left">
		    	<?if (!$fecha_val) $fecha_val=date("d/m/Y");
				  else $fecha_val=fecha($fecha_val)?>
		    	 <input type=text id=fecha_val name=fecha_val value='<?=$fecha_val;?>' size=15  readonly> 
		    	 <?=link_calendario("fecha_val");?>
		    </td>					    		    
		</tr>

		
		<tr><td colspan="4"><table id='revalid1' style='display: none;'><tr>	
			<td align="right">
				<b>Revalidacion:</b>
			</td>
			<td align="left">
		    	<input type="radio" name="revalidacion" value="S" <?=($revalidacion=='S')?'checked':'';?> onclick="document.form1.fecha_reval.focus()">Si
				<input type="radio" name="revalidacion" value="N" <?=($revalidacion=='N')?'checked':'';?> onclick="alert('Debe Generar Producto NO Conforme')" >No
		    </td>

			<td align="right">
				<b>Fecha Revalidacion:</b>
			</td>
			<td align="left">
		    	<?if (!$fecha_reval) $fecha_reval=date("d/m/Y");
				  else $fecha_reval=fecha($fecha_reval)?>
		    	 <input type=text id=fecha_reval name=fecha_reval value='<?=$fecha_reval;?>' size=15  readonly> 
		    	 <?=link_calendario("fecha_reval");?>
		    </td>		    		    
		</tr></table></td></tr>
		

		<tr>
			<td align="right">
				<b>Soporte Informatico:</b>
			</td>	
			<td align="left">	
		    	<input type="radio" name="sop_inf" value="A" <?=($sop_inf=='A')?'checked':'';?> >Aprobado
				<input type="radio" name="sop_inf" value="D" <?=($sop_inf=='D')?'checked':'';?> onclick="alert('Debe Generar Producto NO Conforme')">Desaprobado
		    </td>	
			<td align="right">
         	  <b>Sistema de Gestion:</b>
         	</td>         	
            <td align='left'>
              <input type="radio" name="sis_ges" value="A" <?=($sis_ges=='A')?'checked':'';?> >Aprobado
			  <input type="radio" name="sis_ges" value="D" <?=($sis_ges=='D')?'checked':'';?> onclick="alert('Debe Generar Producto NO Conforme')">Desaprobado
            </td>			    		    
		</tr> 
		     
        <tr>
         	<td align="right">
         	  <b><?if ($tipo_liq=='m'or$tipo_liq=='mf') echo "Calculo Transferencia Bruta:"; else echo "Calculo de Transferencia Base:"?></b>
         	</td>         	
            <td align='left'>
              <input type="radio" name="calc_transf" value="A" <?=($calc_transf=='A')?'checked':'';?> >Aprobado
			  <input type="radio" name="calc_transf" value="D" <?=($calc_transf=='D')?'checked':'';?> onclick="alert('Debe Generar Producto NO Conforme')">Desaprobado
            </td>
         	<td align="right">
         	  <b>Ajustes:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$ajuste?>" name="ajuste" >
            </td>	         	              
         </tr>

          <tr>
         	<td align="right">
         	  <b>Comp. ajuste Memo Conductor:</b>
         	</td>         	
            <td align='left'>
              <?if (!$comp_ajuste or $comp_ajuste=='1980-01-01') $comp_ajuste='';
				  else $comp_ajuste=fecha($comp_ajuste)?>
		    	 <input type=text id=comp_ajuste name=comp_ajuste value='<?=$comp_ajuste;?>' size=15> 
		    	 <?=link_calendario("comp_ajuste");?>
            </td>        
         	<td align="right">
         	  &nbsp
         	</td>         	
            <td align='left'>
              &nbsp
            </td>              
         </tr>  
		 
		 <tr>
         	<td align="right">
         	  <b>Liquidacion:</b>
         	</td>         	
            <td align='left'>
              <?if (!$liq) $liq=date("d/m/Y");
				  else $liq=fecha($liq)?>
		    	 <input type=text id=liq name=liq value='<?=$liq;?>' size=15  readonly> 
		    	 <?=link_calendario("liq");?>
            </td>        
         	<td align="right">
         	<b>
         	<?if ($tipo_liq=='m'or$tipo_liq=='mf') echo "Comparacion Informe Capitas:";
         	if ($tipo_liq=='c') echo "Comparacion Archivo Cuenta Corriente:";?>
         	</b>
         	</td>         	
            <td align='left'>
              <?if (!$comp_inf or $comp_inf=='1980-01-01') $comp_inf='';
				  else $comp_inf=fecha($comp_inf)?>
		    	 <input type=text id=comp_inf name=comp_inf value='<?=$comp_inf;?>' size=15 > 
		    	 <?=link_calendario("comp_inf");?>
            </td>
         </tr>
		 
		 <tr>	
			<td align="right">
				<b>Validacion:</b>
			</td>
			<td align="left">
		    	<input type="radio" name="valida" value="S" <?=($valida=='S')?'checked':'';?> onclick="muestra_revalid(document.all.revalid2,1);document.form1.fecha_val1.focus()" >Si
				<input type="radio" name="valida" value="N" <?=($valida=='N')?'checked':'';?> onclick="muestra_revalid(document.all.revalid2,2);alert('Debe Generar Producto NO Conforme')">No
		    </td>
		    <td align="right">
				<b>Fecha Validacion:</b>
			</td>
			<td align="left">
		    	<?if (!$fecha_val1) $fecha_val1=date("d/m/Y");
				  else $fecha_val1=fecha($fecha_val1)?>
		    	 <input type=text id=fecha_val1 name=fecha_val1 value='<?=$fecha_val1;?>' size=15  readonly> 
		    	 <?=link_calendario("fecha_val1");?>
		    </td>					    		    
		</tr> 

		 <tr><td colspan="4"><table id='revalid2' style='display: none;'><tr>
			<td align="right">
				<b>Revalidacion:</b>
			</td>	
			<td align="left">	
		    	<input type="radio" name="revalid" value="S" <?=($revalid=='S')?'checked':'';?> onclick="document.form1.fecha_reval1.focus()" >Si
				<input type="radio" name="revalid" value="N" <?=($revalid=='N')?'checked':'';?> onclick="alert('Debe Generar Producto NO Conforme')">No
		    </td>	
		    <td align="right">
				<b>Fecha Revalidacion:</b>
			</td>
			<td align="left">
		    	<?if (!$fecha_reval1) $fecha_reval1=date("d/m/Y");
				  else $fecha_reval1=fecha($fecha_reval1)?>
		    	 <input type=text id=fecha_reval1 name=fecha_reval1 value='<?=$fecha_reval1;?>' size=15  readonly> 
		    	 <?=link_calendario("fecha_reval1");?>
		    </td>
		</tr></table></td></tr>

		<tr>
         	<td align="right">
         	  <b>Envio Expedientes:</b>
         	</td>         	
            <td align='left'>
              <?if (!$env_exd) $env_exd=date("d/m/Y");
				  else $env_exd=fecha($env_exd)?>
		    	 <input type=text id=env_exd name=env_exd value='<?=$env_exd;?>' size=15  readonly> 
		    	 <?=link_calendario("env_exd");?>
            </td>        
         	<td align="right">
         	  <b>Envio de Repuestas:</b>
         	</td>         	
            <td align='left'>
              <?php if (!$env_res or $env_res=='1980-01-01') $env_res='';
				  else $env_res=fecha($env_res)?>
		    	 <input type=text id=env_res name=env_res value='<?=$env_res;?>' size=15  readonly> 
		    	 <?=link_calendario("env_res");?>
            </td>
         </tr>
		 
		 <tr>
         	<td align="right">
         	  <b>Observaciones:</b>
         	</td>         	
            <td align='left'>
              <textarea cols='40' rows='2' name='observacion' ><?=$observacion;?></textarea>
            </td>      
         	<td align="right">
         	  <b>&nbsp</b>
         	</td>         	
            <td align='left'>
              &nbsp
            </td>
         </tr> 
		 
        
      </td>      
     </tr> 
     <?// -------------tablas dobes para armar los datos de la vacuna-----------------?>	  
	 
	 <tr id="sub_tabla">
  		<td align=center colspan="8">
  			<b>Acciones</b>
  		</td>
  	</tr>  
  	<tr class="table table-bordered">
	 <td align="center" colspan="8" class="bordes">
	 	<?if ($estado_capitas==0){?>
		    <input type="submit" name="guardar_editar_2" value="Guardar" title="Guardar Registro Capitas" style="width:150px;height:40px" onclick="return control_nuevos_2()" >&nbsp;&nbsp;
		 <?}?>
		 <?if ($estado_capitas==0){?>
		    <input type="submit" name="guardar_revisado_2" value="Revisado" title="Marca Registro Capitas Revisado" style="width:150px;height:40px" onclick="if (confirm('Esta Seguro que Desea Marcar el Registro como Revisado?'))return true;else return false;">&nbsp;&nbsp;
		 <?}?>
		 <?if ($estado_capitas==1){?>
		    <input type="submit" name="guardar_crevision_2" value="Corregir Revisado" title="Abre Registro Capitas Para Corregir" style="width:150px;height:40px" onclick="if (confirm('Esta Seguro que Desea Abrir el Registro para Revision?'))return true;else return false;">&nbsp;&nbsp;
		    <input type="submit" name="guardar_aprobado_2" value="Aprobado" title="Marca Registro Capitas APROBADO" style="width:150px;height:40px" onclick="if (confirm('Esta Seguro que Desea Marcar el Registro como Aprobado? EL REGISTRO QUEDARA CERRADO PARA MODIFICACIONES'))return true;else return false;">&nbsp;&nbsp;
	 	 <?}?>
	 	 <?if ($estado_capitas==2){?>
	 	 	<font color="red" size="+1"><b>Registro de Capitas Revisado Y Aprobado</b></font>
	 	 <?}?>

	 </td>
	</tr>	
	</table>
	<?}?>

	<?if ($id_registro){?>
	
	<table width=100% align="center" class="table table-bordered">
	
     <tr align="center" id="mo">
      <td colspan="4" >
       <b> Carga datos de Administracion </b>

      </td>
     </tr>
	 
	 <tr>	
		<td align="center" colspan=2>
		<b><font size="1" color="Red">Ingresar valores numericos CON separadores de miles, y "," como separador DECIMAL</font> </b>
		 </td>
	 </tr>
	 
		<tr>	
			<td align="right">
				<b>Importe:</b>
			</td>
			<td align='left'>
              <input type="text" size="40" value="<?=number_format($importe,2,',','.')?>" name="importe" >
            </td>

			<td align="right">
				<b>Fecha Ingreso:</b>
			</td>	
			<td align='left'>
              <?if (!$fecha_ingreso or $fecha_ingreso=='1980-01-01') $fecha_ingreso='';
				  else $fecha_ingreso=fecha($fecha_ingreso)?>
		    	 <input type=text id=fecha_ingreso name=fecha_ingreso value='<?=$fecha_ingreso;?>' size=15  > 
		    	 <?=link_calendario("fecha_ingreso");?>
            </td>  	    		    
		</tr>  
	
		<tr>	
			<td align="right">
				<b>Validacion:</b>
			</td>	
			<td align="left">	
		    	<input type="radio" name="valid_adm" value="S" <?=($valid_adm=='S')?'checked':'';?> onclick="muestra_revalid(document.all.revalid3,1);document.form1.fecha_val2.focus()">Si
				<input type="radio" name="valid_adm" value="N" <?=($valid_adm=='N')?'checked':'';?> onclick="muestra_revalid(document.all.revalid3,2);alert('Debe Generar Producto NO Conforme')">No
		    </td>	
		     <td align="right">
				<b>Fecha Validacion:</b>
			</td>
			<td align="left">
		    	<?if (!$fecha_val2 or $fecha_val2=='1980-01-01') $fecha_val2='';
				  else $fecha_val2=fecha($fecha_val2)?>
		    	 <input type=text id=fecha_val2 name=fecha_val2 value='<?=$fecha_val2;?>' size=15  > 
		    	 <?=link_calendario("fecha_val2");?>
		    </td>   		    
		</tr> 

		<tr><td colspan="4"><table id='revalid3' style='display: none;'><tr>				
		    <td align="right">
         	  <b>Revalidacion:</b>
         	</td>         	
            <td align='left'>
              <input type="radio" name="revalid_adm" value="S" <?=($revalid_adm=='S')?'checked':'';?> onclick="document.form1.fecha_reval2.focus()">Si
			  <input type="radio" name="revalid_adm" value="N" <?=($revalid_adm=='N')?'checked':'';?> onclick="alert('Debe Generar Producto NO Conforme')">No
            </td>  	 
			<td align="right">
				<b>Fecha Revalidacion:</b>
			</td>
			<td align="left">
		    	<?if (!$fecha_reval2 or $fecha_reval2=='1980-01-01') $fecha_reval2='';
				  else $fecha_reval2=fecha($fecha_reval2)?>
		    	 <input type=text id=fecha_reval2 name=fecha_reval2 value='<?=$fecha_reval2;?>' size=15  > 
		    	 <?=link_calendario("fecha_reval2");?>
		    </td>
		</tr> </table></td></tr>

		<tr>	
			<td align="right">
				<b>Revision:</b>
			</td>
			<td align="left">
		    	<input type="radio" name="revision_exd" value="S" <?=($revision_exd=='S')?'checked':'';?> >Si
				<input type="radio" name="revision_exd" value="N" <?=($revision_exd=='N')?'checked':'';?> >No
		    </td>
		    <td align="right">
         	  <b>Armado Autorizacion:</b>
         	</td>         	
            <td align='left'>
              <input type="radio" name="armado_autorizacion" value="S" <?=($armado_autorizacion=='S')?'checked':'';?> >Si
			  <input type="radio" name="armado_autorizacion" value="N" <?=($armado_autorizacion=='N')?'checked':'';?> >No
            </td>
				    		    
		</tr>  
		     
          <tr>
         	<td align="right">
         	  <b>Contingencia:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$contingencia?>" name="contingencia" >
            </td>      
         	<td align="right">
         	  <b>Fecha Administracion:</b>
         	</td>         	
            <td align='left'>
              <?if (!$fecha_adm or $fecha_adm=='1980-01-01') $fecha_adm='';
				  else $fecha_adm=fecha($fecha_adm)?>
		    	 <input type=text id=fecha_adm name=fecha_adm value='<?=$fecha_adm;?>' size=15  > 
		    	 <?=link_calendario("fecha_adm");?>
            </td>  	
         </tr>  	
		   
		  <tr>
         	<td align="right">
         	  <b>Autorizacion Capitas:</b>
         	</td>         	
            <td align='left'>
              <input type="radio" name="aut_capitas" value="S" <?=($aut_capitas=='S')?'checked':'';?> >Si
			  <input type="radio" name="aut_capitas" value="N" <?=($aut_capitas=='N')?'checked':'';?> >No
            </td>        
         	<td align="right">
         	  <b>Nota UFIS:</b>
         	</td>         	
            <td align='left'>
              <input type="text" size="40" value="<?=$nota_ufis?>" name="nota_ufis" >
            </td>
         </tr>
		 
		 <tr>
         	<td align="right">
         	  <b>Fecha Salida:</b>
         	</td>         	
            <td align='left'>
              <?if (!$dia_salida or $dia_salida=='1980-01-01') $dia_salida='';
				  else $dia_salida=fecha($dia_salida)?>
		    	 <input type=text id=dia_salida name=dia_salida value='<?=$dia_salida;?>' size=15  > 
		    	 <?=link_calendario("dia_salida");?>
            </td>       
         	<td align="right">
         	  <b>Fecha Pago:</b>
         	</td>         	
            <td align='left'>
              <?if (!$fecha_pago or $fecha_pago=='1980-01-01') $fecha_pago='';
				  else $fecha_pago=fecha($fecha_pago)?>
		    	 <input type=text id=fecha_pago name=fecha_pago value='<?=$fecha_pago;?>' size=15  > 
		    	 <?=link_calendario("fecha_pago");?>
            </td>  	
         </tr>
		 

     <?// -------------tablas dobes para armar los datos de la vacuna-----------------?>	  
	 
	 <tr id="sub_tabla">
  		<td align=center colspan="8">
  			<b>Acciones</b>
  		</td>
  	</tr>  
  	<tr>
	 <td align="center" colspan="8" class="bordes">
	 		<?if ($estado_adm==0){?>
		    	<input type="submit" name="guardar_editar_3" value="Guardar" title="Guardar Datos Administracion" style="width:150px;height:40px" onclick="return control_nuevos_3()">&nbsp;&nbsp;
			<?}?>
			<?if ($estado_adm==0){?>
			    <input type="submit" name="guardar_revisado_3" value="Revisado" title="Marca Registro Administracion Revisado" style="width:150px;height:40px" onclick="if (confirm('Esta Seguro que Desea Marcar el Registro de Administracion como Revisado?'))return true;else return false;">&nbsp;&nbsp;
			 <?}?>
			 <?if ($estado_adm==1){?>
			    <input type="submit" name="guardar_crevision_3" value="Corregir Revisado" title="Abre Registro Administracion Para Corregir" style="width:150px;height:40px" onclick="if (confirm('Esta Seguro que Desea Abrir el Registro de Administracion para Revision?'))return true;else return false;">&nbsp;&nbsp;
			    <input type="submit" name="guardar_aprobado_3" value="Aprobado" title="Marca Registro Administracion APROBADO" style="width:150px;height:40px" onclick="if (confirm('Esta Seguro que Desea Marcar el Registro de Administracion como Aprobado? EL REGISTRO QUEDARA CERRADO PARA MODIFICACIONES'))return true;else return false;">&nbsp;&nbsp;
		 	 <?}?>
		 	 <?if ($estado_adm==2){?>
		 	 	<font color="red" size="+1"><b>Registro Administracion Revisado Y Aprobado</b></font>
		 	 <?}?>
	 </td>
	</tr>

	</table>

	<?}?>
	
   

  
  
<table width=100% align="center" class="table table-bordered">
	<tr>
  		<td id=mo colspan="2">
  			<b>Acciones</b>
  		</td>
  	</tr>  
	<tr align="center">			
		   <td>
			<input type=button name="volver" value="Volver" onclick="document.location='./plan_listado.php'"title="Volver al Listado" style="width:150px;height:40px">
		   </td>
     </tr>           
</table>


<script>
function realizaProceso(id1, id2, id3, ac1, ac2){
        var parametros = {
                "id1" : id1,
                "id2" : id2,
                "id3" : id3,
                "ac1" : ac1,
                "ac2" : ac2
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

<table width=100% align="center" class="table table-bordered">
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
</table>

  </table></td></tr>
  </table>

 <?// --------------------tablas de muestra de vacunas dadas y facturadas 
if ($id_registro){//carga de prestacion a paciente NO PLAN NACER
	$query="SELECT * FROM
			calidad.noconformes
			where id_registro='$id_registro'
			ORDER BY id_noconforme DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>

<table width=100% align="center" class="table table-bordered">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida,2);" >
	  </td>
	  <td align="center">
	   <b>No Conformidades</b> &nbsp&nbsp
	   <?$ref =encode_link("../calidad/detalle_no_conformes.php", array("pagina_viene"=>"plan_admin","id_registro" =>$id_registro));
	   $onclick="location.href='$ref'";?>
	   &nbsp;&nbsp;<input type="button" name="boton" value='Agregar Nuevo' onclick="<?=$onclick?>" class="btn btn-primary">
	  </td>
	</tr>
</table>
<table id="prueba_vida" border="1" width="100%" style="display:none;border:thin groove"align="center"class="table table-bordered">
	<?if ($res_comprobante->RecordCount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen: No Conformidades</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">		 	    
	 		<td >Nro Registro</td>	 		
	 		<td >Area</td>	 		
	 		<td >Fecha Emision</td>	 		
	 		<td >Fecha Evento</td>	 		
	 		<td >Usuario</td>	 			 		
	 		<td >Revisar</td>	 			 		
	 		<td >Aprobar</td>	 			 		
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 	while (!$res_comprobante->EOF) {
			
			$ref = encode_link("../calidad/detalle_no_conformes.php", array("pagina_viene"=>"plan_admin","id" =>$res_comprobante->fields["id_noconforme"],"id_registro" =>$id_registro));             
            $onclick_elegir="location.href='$ref'";
            if ($res_comprobante->fields['estado_nc']=='1'){
            	$rev_cartel='SI';
            	$apro_cartel='NO';
            }
            else if ($res_comprobante->fields['estado_nc']=='2') {
            	$rev_cartel='SI';
            	$apro_cartel='SI';
            }
            else{
            	$rev_cartel='NO';
            	$apro_cartel='NO';
            }
            ?>
	 		<tr <?=atrib_tr()?>>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?='RG-SGC N TO'.$res_comprobante->fields['id_noconforme'].'_03'?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['area']?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha_emision'])?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha_evento'])?></td>	
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['usuario']?></td>				
		 		<td align="center">
		 			<?if ($apro_cartel=='NO'){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como REVISADO')){realizaProceso(<?=$res_comprobante->fields['id_noconforme']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Revisar','nc');}" value="Revisar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Revisado: <?=$rev_cartel?></b>
		 		</td>				
		 		<td align="center">
		 			<?if (($rev_cartel=='SI')&&($apro_cartel=='NO')){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como APROBADO')){realizaProceso(<?=$res_comprobante->fields['id_noconforme']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Aprobar','nc');}" value="Aprobar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Aprobado: <?=$apro_cartel?></b>
		 		</td>				
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	}
	 }?>
</table>
<?}?>

<?if ($id_registro){//pac pap
	$query="select *, no_conformidad.descripcion as descri
			from calidad.pac_pap 
			left join calidad.no_conformidad using (id_no_conformidad)
			where id_registro='$id_registro'
			ORDER BY id_pac_pap DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>
<table width=100% align="center" class="table table-bordered">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida2,2);" >
	  </td>
	  <td align="center">
	   <b>Planes de Accion</b> &nbsp&nbsp
	   <?$ref =encode_link("../calidad/pac_pap.php", array("id_registro" =>$id_registro));
	   $onclick="location.href='$ref'";?>
	   &nbsp;&nbsp;<input type="button" name="boton" value='Agregar Nuevo' onclick="<?=$onclick?>" class="btn btn-primary">
	  </td>
	</tr>
</table>
<table class="table table-bordered" id="prueba_vida2" border="1" width="100%" style="display:none;border:thin groove"align="center">
	<?if ($res_comprobante->RecordCount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen: Planes de Accion</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">		 	    
	 		<td >Nro Registro</td>	 		
	 		<td >No conformidad</td>	 		
	 		<td >Tipo</td>	
	 		<td >Revisar</td>	 			 		
	 		<td >Aprobar</td> 		
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 	while (!$res_comprobante->EOF) {
			
			$ref = encode_link("../calidad/pac_pap.php", array("pagina_viene"=>"plan_admin","id" =>$res_comprobante->fields["id_pac_pap"],"id_registro" =>$id_registro));             
            $onclick_elegir="location.href='$ref'";
             if ($res_comprobante->fields['estado_pa']=='1'){
            	$rev_cartel='SI';
            	$apro_cartel='NO';
            }
            else if ($res_comprobante->fields['estado_pa']=='2') {
            	$rev_cartel='SI';
            	$apro_cartel='SI';
            }
            else{
            	$rev_cartel='NO';
            	$apro_cartel='NO';
            }?>
	 		<tr <?=atrib_tr()?>>
		 		<td align="center" onclick="<?=$onclick_elegir?>">
		 			<?if ($res_comprobante->fields['tipo']=='0')	echo 'RG-SGC N AC'.$res_comprobante->fields['id_pac_pap'].'_03';
		 			else echo 'RG-SGC N AP'.$res_comprobante->fields['id_pac_pap'].'_03'?>
		 		</td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['descri']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?if($res_comprobante->fields['tipo']==0) echo "P.A.C."; else echo "P.A.P.";?></td>
		 		<td align="center">
		 			<?if ($apro_cartel=='NO'){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como REVISADO')){realizaProceso(<?=$res_comprobante->fields['id_pac_pap']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Revisar','pa');}" value="Revisar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Revisado: <?=$rev_cartel?></b>
		 		</td>				
		 		<td align="center">
		 			<?if (($rev_cartel=='SI')&&($apro_cartel=='NO')){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como APROBADO')){realizaProceso(<?=$res_comprobante->fields['id_pac_pap']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Aprobar','pa');}" value="Aprobar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Aprobado: <?=$apro_cartel?></b>
		 		</td>				
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	}
	 }?>
</table>
<?}?>

<?if ($id_registro){//memo
	$query="select *
			from registro.memo
			where id_registro='$id_registro'
			ORDER BY id_memo DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>
<table width=100% align="center" class="table table-bordered">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida3,2);" >
	  </td>
	  <td align="center">
	   <b>Memo</b> &nbsp&nbsp
	   <?$ref =encode_link("./memo.php", array("pagina_viene"=>"plan_admin","id_registro" =>$id_registro));
	   $onclick="location.href='$ref'";?>
	   &nbsp;&nbsp;<input type="button" name="boton" value='Agregar Nuevo' onclick="<?=$onclick?>" class="btn btn-primary">
	  </td>
	</tr>
</table>
<table class="table table-bordered" id="prueba_vida3" border="1" width="100%" style="display:none;border:thin groove"align="center">
	<?if ($res_comprobante->RecordCount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen: Memo</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">		
	 		<td >Nro Registro</td> 	    
	 		<td >Origen</td>	 		
	 		<td >Registro</td>	 		
	 		<td >Fecha</td>	 		
	 		<td >Monto</td>	
	 		<td >Revisar</td>	 			 		
	 		<td >Aprobar</td> 		
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 	while (!$res_comprobante->EOF) {
			
			$ref = encode_link("./memo.php", array("pagina_viene"=>"plan_admin","id_memo" =>$res_comprobante->fields["id_memo"],"id_registro" =>$id_registro));             
            $onclick_elegir="location.href='$ref'";
             if ($res_comprobante->fields['estado_me']=='1'){
            	$rev_cartel='SI';
            	$apro_cartel='NO';
            }
            else if ($res_comprobante->fields['estado_me']=='2') {
            	$rev_cartel='SI';
            	$apro_cartel='SI';
            }
            else{
            	$rev_cartel='NO';
            	$apro_cartel='NO';
            }?>

	 		<tr <?=atrib_tr()?>>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?='RG-SGC N MI'.$res_comprobante->fields['id_memo'].'_02'?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['origen']?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['registro']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha'])?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=number_format($res_comprobante->fields['monto'],2,',','.')?></td>	
		 		<td align="center">
		 			<?if ($apro_cartel=='NO'){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como REVISADO')){realizaProceso(<?=$res_comprobante->fields['id_memo']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Revisar','me');}" value="Revisar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Revisado: <?=$rev_cartel?></b>
		 		</td>				
		 		<td align="center">
		 			<?if (($rev_cartel=='SI')&&($apro_cartel=='NO')){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como APROBADO')){realizaProceso(<?=$res_comprobante->fields['id_memo']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Aprobar','me');}" value="Aprobar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Aprobado: <?=$apro_cartel?></b>
		 		</td>		 		
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	}
	 }?>
</table>
<?}?>

<?if ($id_registro){//audit
	$query="select *
			from registro.informe_ace
			where id_registro='$id_registro'
			ORDER BY id_informe_ace DESC";
$res_comprobante=sql($query,"<br>Error al traer los comprobantes<br>") or fin_pagina();?>

<table width=100% align="center" class="table table-bordered">
	<tr align="center" id="mo">
	  <td align="center" width="3%">
	   <img id="imagen_2" src="<?=$img_ext?>" border=0 title="Mostrar" align="left" style="cursor:pointer;" onclick="muestra_tabla(document.all.prueba_vida4,2);" >
	  </td>
	  <td align="center">
	   <b>Informes ACE</b> &nbsp&nbsp
	   <?$ref =encode_link("./inf.php", array("pagina_viene"=>"plan_admin","id_registro" =>$id_registro));
	   $onclick="location.href='$ref'";?>
	   &nbsp;&nbsp;<input type="button" name="boton" value='Agregar Nuevo' onclick="<?=$onclick?>" class="btn btn-primary">
	  </td>
	</tr>
</table>
<table class="table table-bordered" id="prueba_vida4" border="1" width="100%" style="display:none;border:thin groove"align="center">
	<?if ($res_comprobante->RecordCount()==0){?>
	 <tr>
	  <td align="center">
	   <font size="3" color="Red"><b>No existen: Informes</b></font>
	  </td>
	 </tr>
	 <?}
	 else{	 	
	 	?>
	 	<tr id="sub_tabla">	
	 		<td >Nro Registro</td> 	    
	 		<td >Auditoria</td>	 		
	 		<td >Documento</td>	 		
	 		<td >Legajo</td>	 		
	 		<td >Fecha</td>	 
	 		<td >Revisar</td>	 			 		
	 		<td >Aprobar</td>		
	 	</tr>
	 	<?
	 	$res_comprobante->movefirst();
	 	while (!$res_comprobante->EOF) {
			
			$ref = encode_link("./inf.php", array("pagina_viene"=>"plan_admin","id_informe_ace" =>$res_comprobante->fields["id_informe_ace"],"id_registro" =>$id_registro));             
            $onclick_elegir="location.href='$ref'";

             if ($res_comprobante->fields['estado_ace']=='1'){
            	$rev_cartel='SI';
            	$apro_cartel='NO';
            }
            else if ($res_comprobante->fields['estado_ace']=='2') {
            	$rev_cartel='SI';
            	$apro_cartel='SI';
            }
            else{
            	$rev_cartel='NO';
            	$apro_cartel='NO';
            }?>

	 		<tr <?=atrib_tr()?>>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?='RG-SGC N InfACE'.$res_comprobante->fields['id_informe_ace'].'_02'?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['audit']?></td>
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['documento']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=$res_comprobante->fields['num_leg']?></td>		 		
		 		<td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($res_comprobante->fields['fecha'])?></td>
		 		<td align="center">
		 			<?if ($apro_cartel=='NO'){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como REVISADO')){realizaProceso(<?=$res_comprobante->fields['id_informe_ace']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Revisar','ac');}" value="Revisar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Revisado: <?=$rev_cartel?></b>
		 		</td>				
		 		<td align="center">
		 			<?if (($rev_cartel=='SI')&&($apro_cartel=='NO')){?>
		 				<input type="button" href="javascript:;" onclick="if (confirm('Esta Seguro que desea Marcar como APROBADO')){realizaProceso(<?=$res_comprobante->fields['id_informe_ace']?>, <?=$id_registro?>,<?=$id_dato_registro?>,'Aprobar','ac');}" value="Aprobar"/>
		 			<?}?>
		 			&nbsp&nbsp <b>Aprobado: <?=$apro_cartel?></b>
		 		</td>			 		
		 	</tr>	
		 	
	 		<?$res_comprobante->movenext();
	 	}
	 }?>
</table>
<?}?>

<?if ($id_registro){//archivo?>
<table width=100% align="center" class="table table-bordered">
			<tr id="mo">
				<td align="center" colspan="5">
				<?$sql_archivos="select * from registro.archivos where id_registro=$id_registro";
				$rta_archivos=sql($sql_archivos) or fin_pagina();?>
					Archivos (<?=$rta_archivos->recordcount()?> en total)
				<?
				
?>
					<input class="btn btn-primary" type="button" name="bagregar" value="Agregar" onclick="if (typeof(warchivos)=='object' && warchivos.closed || warchivos==false) warchivos=window.open('<?= encode_link('./archivos_subir.php',array("id_registro"=>$id_registro, "user"=>$_ses_user["name"], "onclickaceptar"=>"window.self.focus();", "proc_file"=>"./orden_file_proc.php")) ?>','','toolbar=0,location=0,directories=0,status=0, menubar=0,scrollbars=1'); else warchivos.focus()" <?=$new?>>
					
				</td>	
			</tr>
<?
			if ($rta_archivos->recordcount()>0){
?>
			<tr>
				<td align=right id=mo>Archivo</td>
				<!--<td align=right id=mo>Fecha</td>-->
				<td align=right id=mo>Subido por</td>
				<td align=right id=mo>Tamaño</td>
				<td align=center id=mo>&nbsp;</td>
			</tr>
<?
				while (!$rta_archivos->EOF){
					echo "<tr class='table table-bordered' style='font-size: 9pt'><td align=center>";
 					if (is_file("../../uploads/archivoscalidad/".$rta_archivos->fields["nombre"])) echo "<a target=_blank href='".encode_link("../archivos/archivos_lista.php", array ("file" =>$rta_archivos->fields["nombre"],"size" => $rta_archivos->fields["size"],"cmd" => "download_plan"))."'>";
				  echo $rta_archivos->fields["nombre"]."</a></td>";
		?>    
  	  			<!--<td align=center>&nbsp;<?//=Fecha($rta_archivos->fields["fecha"])?></td>-->
				    <td align=center>&nbsp;<?= $rta_archivos->fields["creadopor"] ?></td>
				    <td align=center>&nbsp;<?= $size=number_format($rta_archivos->fields["size"] / 1024); ?> Kb</td>
	    			<td align=center>
		<?    
					$lnk=encode_link("$_SERVER[PHP_SELF]",Array("id_registro"=>$id_registro,"id_archivo"=>$rta_archivos->fields["id"],"filename"=>$rta_archivos->fields["nombre"],"modo"=>"borrar_archivo"));
		      
		      	echo "<a href='$lnk'><img src='../../imagenes/close1.gif' border=0 alt='Eliminar el archivo: \"". $rta_archivos->fields["nombre"] ."\"'></a>";
		      
	  	    echo "</td></tr>";
					$rta_archivos->movenext();
				}
			}
?>		
		</table>
<?}?>

  
 </table>  
 </form>
 
 <?=fin_pagina();// aca termino ?>
