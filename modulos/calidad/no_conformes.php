<?
require_once("../../config.php");
echo $html_header;
$msg=$parametros['msg'];

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
		"default" => "1",
    "default_up" => "0",
    "1" => "noconformes.id_noconforme",
		"2" => "noconformes.fecha_evento"    
	);

$filtro = array(
		"noconformes.descripcion_inconformidad" => "Descripcion de Inconformidad"

	);

$query="select * from noconformes";
echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";
?>
<div class="newstyle-full-container">
<form name="form1" method="post" action="no_conformes.php">

  <div class="row-fluid" >
        <div class="span12" align="center">
          <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($query,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
          <input class="btn" type=submit name="buscar" value='Buscar'>
          <input class="btn" type="button" name="boton" value='Agregar Nuevo' onclick="document.location='detalle_no_conformes.php'">
          <input class="btn" type="submit" name="boton" value="Borrar">
     </div>
  </div>

<?$resultado=sql($sql) or die;?>

<?php if ($msg){?>
<div class="alert alert-info" align="center">
  <b>INFORMACION!</b> <?=$msg?>.
</div>
<?php }?>

<hr>
  <div class="pull-right paginador">
      <?=$total_muletos?> Registros.
      <?=$link_pagina?>
  </div>
  
  <table class="table table-striped table-advance table-hover">
    <thead>
      <tr>
        <th width="1%" ></th>
        <th width="10%" ><a  href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"1","up"=>$up))?>'><b>Id</b></a> </th>
        <th width="10%" ><a  href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"2","up"=>$up))?>'><b>Fecha Evento</b></a></th>
        <th width="35%" ><b>Detalle</b></a></th>
        <th width="35%" ><b>Verificacion</b></a></th>
        <th width="5%" ><b>Revisado</b></th>
        <th width="5%" ><b>Aprobado</b></th>
      </tr>
    </thead>
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
  
    <tr>
      <td ><input type="checkbox" name="borrar_<? echo $i; ?>" value="<? echo $resultado->fields['id_noconforme'];?>"></td>
      <td onclick="<?=$onclick_elegir?>"><?='RG-SGC N° TO 0'.$resultado->fields['id_noconforme'].'_03';?></td>
      <td onclick="<?=$onclick_elegir?>"><? echo fecha($resultado->fields['fecha_evento']); ?></td>
      <td onclick="<?=$onclick_elegir?>"><? echo $resultado->fields['deteccion']; ?></td>
      <td onclick="<?=$onclick_elegir?>"><? echo $resultado->fields['con_cambio']; ?></td>
      <td onclick="<?=$onclick_elegir?>"><b><?=$rev_cartel?></b></td>
      <td onclick="<?=$onclick_elegir?>"><b><?=$apro_cartel?></b></td>

    </tr>
    <?
	$resultado->MoveNext();
	$i++;
  }  ?>
  <input type="hidden" name="cant" value="<? echo $resultado->RecordCount(); ?>">

</table>
</form>
</div>
<?echo fin_pagina();// aca termino ?>
