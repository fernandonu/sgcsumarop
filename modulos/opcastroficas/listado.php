<?php
/*
Author: JEM

modificada por
$Author: JAB $
$Revision: 2.0 $
$Date: 2015/01/06 $
*/
require_once("../../config.php");
require_once("./data/model.php");
require_once("./data/view.php");
echo $html_header;

variables_form_busqueda("usr_areas_listado");

$orden = array(
        "default" => "1",
        "1" => "login",
		    "2" => "apellido"
       );
$filtro = array(
		"login" => "login",
		"apellido" => "apellido"  
       );
$sql_tmp="select * from sistema.usuarios";

?>
<form name=form1 action="usr_areas_listado.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_pais,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	  </td>
     </tr>
</table>

<?
$result = getData();
drawView();
?>

</form>
<?echo fin_pagina();// aca termino ?>
