<?
$id_expediente=$parametros['id_expediente'];
$user=$parametros['user'];
if ($_POST['baceptar']){
    $acceso="Todos";
    $comentario="Archivo de Calidad";
    $fecha=date("Y/m/d");
    $files_total=count($_FILES['archivo']["name"]);
    $error_vector=array();
    for ($i=0; $i < $files_total ; $i++ ){
    	$filename=$_FILES["archivo"]["name"][$i];
    	$tamanio=$_FILES['archivo']["size"][$i];
	    if (!$filename) $error_msg="Debe seleccionar un archivo";
	    elseif ($_FILES["archivo"]["error"][$i]) $error_msg="El archivo '$filename' es muy grande ";
	    if (!$error_msg){
	    	$filename=$id_expediente.'-'.$filename;
	    	if (subir_archivo($_FILES["archivo"]["tmp_name"][$i],UPLOADS_DIR."/archivosexd/$filename",$error_msg)===true){
	         $sql="select nextval('expedientes.archivos_id_seq') as idfile ";
	         $res=sql($sql) or $db->errormsg()."<br>";
	         $idfile=$res->fields['idfile'];
	         $q="INSERT INTO expedientes.archivos
	              (id_expediente, nombre,comentario,creadopor,fecha,size,acceso) Values
	              ($id_expediente,'$filename','$comentario','".$user."','$fecha','$tamanio','$acceso')";
	         if (!sql($q)){
	         	$error_msg="No se pudo insertar el archivo ".$db->errormsg()."<br>$q ";	
	         }
	         else{ 
	         	$ok_msg="El archivo '$filename' se subió con éxito";
	         	/*cargo los log*/ 
	         	$usuario=$_ses_user['name'];
				$login=$_ses_user['login'];
				$sql="select id_usuario from sistema.usuarios where login='$login'";
				$id_usuario=sql($sql, "Error al insertar la Planilla") or fin_pagina();
				$id_usuario=$id_usuario->fields['id_usuario'];
				$fecha_actual=date("Y-m-d H:i:s");
			    $q_1="select nextval('expedientes.log_expediente_id_log_expediente_seq') as id_log";
			    $id_log=sql($q_1) or fin_pagina();
			    $id_log=$id_log->fields['id_log'];
			    
				$log="insert into expedientes.log_expediente
					   (id_log_expediente,id_expediente,fecha, tipo, descripcion, usuario,login) 
						values 
					   ($id_log, '$id_expediente','$fecha_actual','Archivo Adjunto','Subida archivo $filename', '$usuario', '$login')";
				sql($log, "Error al insertar Log") or fin_pagina();
	     	 }
	         echo "<script>window.opener.location.reload();</script>";
	    	}
	    }
	     $error_vector[]=$error_msg;
	     $error_msg="";
	     $ok_vector[]=$ok_msg;
	     $ok_msg="";
    }
}
?>