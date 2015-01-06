<?php
$mock = true;
$result = null;

function getEfectores(){
	global $mock, $sql;
	if($mock == true) {
		$ret = json_decode(file_get_contents('./model/efectores.json'), true);
	} else {
		$ret = sql($sql,"No se ejecuto en la consulta principal") or die;
	}
	return $ret;
}

function getEfector($id_efector){
	global $mock;
	$query = "SELECT * FROM opcatastroficas.efector where id_usuario=$id_efector";
	
	if($mock == true){
		$ret = json_decode(file_get_contents('./model/efector.json'), true);
	} else {
		$ret = sql($sql,"Error al obtener el efector $id_efector") or fin_pagina();
	}

	return $ret;
}

function guardarEfector($id_efector, $descripcion){
	global $mock;
	$query="update opcastroficas.efector
   			set 
   				descripcion = '$descripcion'
   			where id_efector='$id_efector'";

	if($mock) {
		echo "<script> alert('GUARDANDO el efector $descripcion'); </script>";    	
	} else {
		//sql($query, "Error al vincular comprobante") or fin_pagina();
	}
}

function crearEfector($descripcion){
	echo "<script>   			
   			alert('CREANDO el efector $descripcion');
	</script>";    	
	
}

function getProvincias(){
	$query = "select * from registro.provincia";
	$ret = sql($query,"No se pudieron obtener las provincias") or die;
	return $ret;
}

?>