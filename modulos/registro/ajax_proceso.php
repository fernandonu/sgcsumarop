<?php
require_once ("../../config.php");

$ac1=$_POST['ac1'];
$ac2=$_POST['ac2'];
$id1=$_POST['id1'];
$id2=$_POST['id2'];
$id3=$_POST['id3'];

if (($ac1=='Revisar')&&($ac2=='nc')){			
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update calidad.noconformes set
	            estado_nc= '1'
	            Where id_noconforme='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Revisado el Registro No Conforme $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "La No Conformidad Numero ".$id1." se Marco como Revisada";

	$contenido_mail_control="El Producto No Conforme Numero $id1 fue Marcado como Revisado por el usuario $usuario, queda pendiente de Aprobacion en el Plan de Calidad Numero $id2.
	Notificacion: $fecha_mod. POR FAVOR VERIFICAR DETALLES EN EL SISTEMA DE CALIDAD";
    //enviar_mail('fernandonu@gmail.com','','','Notificacion Calidad',$contenido_mail_control,'','');
}

if (($ac1=='Aprobar')&&($ac2=='nc')) {
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update calidad.noconformes set
	            estado_nc= '2'
	            Where id_noconforme='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Aprobado el Registro No Conforme $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "La No Conformidad Numero ".$id1." se Marco como Aprobada";
}

if (($ac1=='Revisar')&&($ac2=='pa')){			
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update calidad.pac_pap set
	            estado_pa= '1'
	            Where id_pac_pap='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Revisado el Registro Plan de Accion $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "El Plan de Accion Numero ".$id1." se Marco como Revisada";

	$contenido_mail_control="El Plan de Accion Numero $id1 fue Marcado como Revisado por el usuario $usuario, queda pendiente de Aprobacion en el Plan de Calidad Numero $id2.
	Notificacion: $fecha_mod. POR FAVOR VERIFICAR DETALLES EN EL SISTEMA DE CALIDAD";
    //enviar_mail('fernandonu@gmail.com','','','Notificacion Calidad',$contenido_mail_control,'','');
}

if (($ac1=='Aprobar')&&($ac2=='pa')) {
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update calidad.pac_pap set
	            estado_pa= '2'
	            Where id_pac_pap='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Aprobado el Registro Plan de Accion $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "El Plan de Accion Numero ".$id1." se Marco como Aprobada";
}

if (($ac1=='Revisar')&&($ac2=='me')){			
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update registro.memo set
	            estado_me= '1'
	            Where id_memo='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Revisado el Registro MEMO $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "El MEMO Numero ".$id1." se Marco como Revisado";

	$contenido_mail_control="El MEMO Numero $id1 fue Marcado como Revisado por el usuario $usuario, queda pendiente de Aprobacion en el Plan de Calidad Numero $id2.
	Notificacion: $fecha_mod. POR FAVOR VERIFICAR DETALLES EN EL SISTEMA DE CALIDAD";
    //enviar_mail('fernandonu@gmail.com','','','Notificacion Calidad',$contenido_mail_control,'','');
}

if (($ac1=='Aprobar')&&($ac2=='me')) {
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update registro.memo set
	            estado_me= '2'
	            Where id_memo='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Aprobado el MEMO $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "El MEMO Numero ".$id1." se Marco como Aprobado";
}

if (($ac1=='Revisar')&&($ac2=='ac')){			
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update registro.informe_ace set
	            estado_ace= '1'
	            Where id_informe_ace='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Revisado el Registro Informe ACE $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "El Informe ACE Numero ".$id1." se Marco como Revisado";

	$contenido_mail_control="El Informe ACE Numero $id1 fue Marcado como Revisado por el usuario $usuario, queda pendiente de Aprobacion en el Plan de Calidad Numero $id2.
	Notificacion: $fecha_mod. POR FAVOR VERIFICAR DETALLES EN EL SISTEMA DE CALIDAD";
    //enviar_mail('fernandonu@gmail.com','','','Notificacion Calidad',$contenido_mail_control,'','');
}

if (($ac1=='Aprobar')&&($ac2=='ac')) {
	$usuario=$_ses_user['name'];
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update registro.informe_ace set
	            estado_ace= '2'
	            Where id_informe_ace='$id1'";
	    sql($query, "Error al modificar") or fin_pagina();

		/*cargo los log*/ 
		$q_1="select nextval('registro.log_registro_id_log_registro_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into registro.log_registro
			   (id_log_registro,id_registro, id_dato_registro,fecha, tipo, descripcion, usuario) 
				values ($id_log, '$id2','$id3','$fecha_mod','Modificacion','Marco como Aprobado el Informe ACE $id1', '$usuario')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "El Informe ACE Numero ".$id1." se Marco como Aprobado";
}

echo $resultado;
?>
