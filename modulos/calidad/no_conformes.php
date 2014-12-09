<?
/*AUTOR: MAC

$Author: mari $
$Revision: 1.4 $
$Date: 2007/01/03 13:59:39 $
*/

require_once("../../config.php");
echo $html_header;
$msg=$parametros['msg'];

//$up=$_POST["up"] or $up=$_GET["up"];
if($_POST['boton']=="Borrar")
{               $db->StartTrans();
 	            $i=1;$bien=1;
 	            while ($i<=$_POST['cant'])
                {if ($_POST['borrar_'.$i]!="")
                  {$sql="delete from noconformes where id_noconforme=".$_POST['borrar_'.$i];
                   if(!$db->Execute($sql))
                    $bien=0; 
                  }
                  $i++;
                } 
                if($bien)
                 $msg="<b><center>Los Productos No Conformes seleccionados se borraron con éxito</b></center>"; 
                else 
                 $msg="<b><center>Los Productos No Conformes seleccionados no se pudieron borrar</b></center>";  
                $db->CompleteTrans(); 
}//del if de borrar

$orden = array(
		"default" => "3",
    "default_up" => "0",
		"1" => "noconformes.fecha_evento",
		"2" => "noconformes.descripcion_inconformidad",
    "3" => "noconformes.id_noconforme"
	);

$filtro = array(
		"noconformes.descripcion_inconformidad" => "Descripcion de Inconformidad"

	);

$query="select id_noconforme,descripcion_inconformidad,fecha_evento, estado_nc from noconformes";
echo $html_header;
?>
<script>
</script>
<form name="form1" method="post" action="no_conformes.php">
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($query,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
		&nbsp;&nbsp;<input type="button" name="boton" value='Agregar Nuevo' onclick="document.location='detalle_no_conformes.php'">
		&nbsp;&nbsp;<input type="submit" name="boton" value="Borrar">
	  </td>
     </tr>
</table>
<?$resultado=sql($sql) or die;?>

<br>
<?=$msg?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
  <tr>
  	<td colspan=12 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  
    <tr>
      <td width="1%" id=mo></td>
      <td width="10%" align="center" id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"3","up"=>$up))?>'><b>Id</b></a> </td>
      <td width="10%" align="center" id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"1","up"=>$up))?>'><b>Fecha Evento</b></a></td>
      <td width="70%" align="center" id=mo><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"2","up"=>$up))?>'><b>Descripción</b></a></td>
      <td width="10%" align="center" id=mo><b>Revisado</b></td>
      <td width="10%" align="center" id=mo><b>Aprobado</b></td>
    </tr>
    <?
  $i=1;
  while (!$resultado->EOF ) {
  
  if ($resultado->fields['estado_nc']=='1'){
              $rev_cartel='SI';
              $apro_cartel='NO';
            }
  else if ($resultado->fields['estado_nc']=='2') {
              $rev_cartel='SI';
              $apro_cartel='SI';
            }
  else{
              $rev_cartel='NO';
              $apro_cartel='NO';
            }

  $ref =encode_link("detalle_no_conformes.php", array("pagina"=>"listado","id" =>$resultado->fields["id_noconforme"]));
  $onclick_elegir="location.href='$ref'";?>
  
    <tr  <?=atrib_tr()?>>
      <td align="center"><input type="checkbox" name="borrar_<? echo $i; ?>" value="<? echo $resultado->fields['id_noconforme'].'_03';?>"></td>
      <td align="center" onclick="<?=$onclick_elegir?>"><?='RG-SGC N TO'.$resultado->fields['id_noconforme'];?></font></td>
      <td align="center" onclick="<?=$onclick_elegir?>"><? echo fecha($resultado->fields['fecha_evento']); ?></font></td>
      <td align="center" onclick="<?=$onclick_elegir?>"><? echo $resultado->fields['descripcion_inconformidad']; ?></font></td>
      <td align="center" onclick="<?=$onclick_elegir?>"><b><?=$rev_cartel?></b></td>
      <td align="center" onclick="<?=$onclick_elegir?>"><b><?=$apro_cartel?></b></td>

    </tr>
    <?
	$resultado->MoveNext();
	$i++;
  }  ?>
  <input type="hidden" name="cant" value="<? echo $resultado->RecordCount(); ?>">

</table>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
