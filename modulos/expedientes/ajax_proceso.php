<?php
require_once ("../../config.php");

$id1=$_POST['id1'];
$id2=$_POST['id2'];
$id3=$_POST['id3'];

$usuario=$_ses_user['name'];
$login=$_ses_user['login'];
$sql="select id_usuario from sistema.usuarios where login='$login'";
$id_usuario=sql($sql, "Error al insertar la Planilla") or fin_pagina();
$id_usuario=$id_usuario->fields['id_usuario'];

if ($id3=='Aceptar'){			
	$fecha_mod=date("Y-m-d H:i:s");
	
	$db->StartTrans();
	    $query="update expedientes.expediente set
	        estado_simple='a'
	        Where id_expediente='$id2'";
   		sql($query, "Error al modificar") or fin_pagina();

   		$query="update expedientes.pases set
	        fecha_aceptacion='$fecha_mod'
	        Where id_pases='$id1'";
   		sql($query, "Error al modificar") or fin_pagina();


		/*cargo los log*/ 
		$q_1="select nextval('expedientes.log_expediente_id_log_expediente_seq') as id_log";
		$id_log=sql($q_1) or fin_pagina();
		$id_log=$id_log->fields['id_log'];	    
		$log="insert into expedientes.log_expediente
			   (id_log_expediente,id_expediente, fecha, tipo, descripcion, usuario,login) 
				values ($id_log, '$id2','$fecha_mod','Acepta Pase','Acepta Pase: $id1', '$usuario', '$login')";
		sql($log) or fin_pagina();
	$db->CompleteTrans(); 

	$resultado = "Se Acepto el Pase. El expediente estara 'En Dependencia'";
}
echo $resultado;
?>
