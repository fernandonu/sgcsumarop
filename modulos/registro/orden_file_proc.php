<?

$id_registro=$parametros['id_registro'];
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
	    	if (subir_archivo($_FILES["archivo"]["tmp_name"][$i],UPLOADS_DIR."/archivoscalidad/$filename",$error_msg)===true){
	         $sql="select nextval('registro.archivos_id_seq') as idfile ";
	         $res=sql($sql) or $db->errormsg()."<br>";
	         $idfile=$res->fields['idfile'];
	         $q="INSERT INTO registro.archivos
	              (id_registro, nombre,comentario,creadopor,fecha,size,acceso) Values
	              ($id_registro,'$filename','$comentario','".$user."','$fecha','$tamanio','$acceso')";
	         if (!sql($q)) $error_msg="No se pudo insertar el archivo ".$db->errormsg()."<br>$q ";	
	         else $ok_msg="El archivo '$filename' se subi� con �xito";
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