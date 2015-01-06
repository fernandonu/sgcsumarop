<?php
/*
Author: JAB

modificada por
$Author: JAB $
$Revision: 1.0 $
$Date: 2015/01/06 $
*/

$mock = true;
$result = null;

function getData(){
	global $mock, $sql;
	if($mock == true) {
		$ret = json_decode(file_get_contents('./data/facturas.json'), true);
	} else {
		$ret = sql($sql,"No se ejecuto en la consulta principal") or die;
	}
	return $ret;
}

?>