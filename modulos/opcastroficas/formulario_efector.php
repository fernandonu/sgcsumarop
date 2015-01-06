<?php

require_once("../../config.php");
require_once("./model/efector.php");
require_once("./view/v_formulario_efector.php");

extract($_POST, EXTR_SKIP);
if ($parametros) extract ($parametros, EXTR_OVERWRITE );

//VERIFICO SI ES UN POST (INSERT O UPDATE)
if($_POST["guardar_editar"]=="Guardar"){
   guardarEfector($id_efector, $descripcion);
   $ref = encode_link("listado_efectores.php",array("id_efector"=>$id_efector,"pagina_viene"=>"formulario_efector.php"));                  
   echo "<script>location.href='$ref';</script>";
}
 
if($_POST["guardar"]=="Guardar"){
   //echo $descripcion;
   crearEfector($descripcion);
   
   echo "<script>   			
   			alert($descripcion);
	</script>";    	
	
   $ref = encode_link("listado_efectores.php",array("id_efector"=>$id_efector,"pagina_viene"=>"formulario_efector.php"));                  
   /*
	echo "<script>   			
   			location.href='$ref';
   		</script>";    	
		*/
}
 
$result = null;
$provincias = getProvincias();

if($id_efector){
	$result = getEfector($id_efector);
}

//if $_POST...
drawView($provincias);
?>
