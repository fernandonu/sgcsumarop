<?php

require_once("../../config.php");
variables_form_busqueda("exd_listado_tot");

$usuario=$_ses_user['name'];
$login=$_ses_user['login'];
$sql="select id_usuario from sistema.usuarios where login='$login'";
$id_usuario=sql($sql, "Error al insertar la Planilla") or fin_pagina();
$id_usuario=$id_usuario->fields['id_usuario'];

$orden = array(
        "default" => "1",
		    "default_up" => "0",
        "1" => "id_expediente",
        "2" => "extracto",
        "3" => "fecha_creacion",
        "4" => "desc_area",
        "5" => "desc_estado",
        "6" => "desc_prioridad",
        "7" => "desc_tipo_tramite",
       );
$filtro = array(
		    "id_expediente" => "Nro Expediente",
        "extracto" => "Extracto",
        "desc_prioridad" => "Prioridad",              
       );


$sql_tmp="SELECT
expedientes.expediente.id_expediente,
expedientes.expediente.extracto,
expedientes.expediente.fecha_creacion,
expedientes.expediente.area_actual,
expedientes.expediente.estado_simple,
genera.desc_area AS genera,
actual.desc_area AS actual,
expedientes.estado.desc_estado,
expedientes.prioridad.desc_prioridad,
expedientes.tipo_tramite.desc_tipo_tramite,
expedientes.nivel_acceso.desc_nivel_acceso
FROM
expedientes.expediente
INNER JOIN expedientes.areas as genera ON expedientes.expediente.area_genera = genera.id_area
INNER JOIN expedientes.areas as actual ON expedientes.expediente.area_actual = actual.id_area
INNER JOIN expedientes.estado ON expedientes.estado.id_estado = expedientes.expediente.id_estado
INNER JOIN expedientes.prioridad ON expedientes.prioridad.id_prioridad = expedientes.expediente.id_prioridad
INNER JOIN expedientes.tipo_tramite ON expedientes.tipo_tramite.id_tipo_tramite = expedientes.expediente.id_tipo_tramite
INNER JOIN expedientes.nivel_acceso ON expedientes.nivel_acceso.id_nivel_acceso = expedientes.expediente.id_nivel_acceso";

if ($cmd == "")  $cmd="activos";
$datos_barra = array(
     array(
        "descripcion"=> "Activos",
        "cmd"        => "activos"
     ),
      array(
        "descripcion"=> "Archivados",
        "cmd"        => "archivados"
     )     
);
generar_barra_nav($datos_barra);
if ($cmd=="activos")
    $where_tmp=" (estado.desc_estado<>'ARCHIVADO')";
if ($cmd=="archivados")
    $where_tmp=" (estado.desc_estado='ARCHIVADO')";

echo $html_header;
?>
<form name=form1 action="exd_listado_tot.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar' style='width:130px;height:25px'>
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align='center'>
  <tr>
  	<td colspan=12 align=left id='ma'>
     <table width='100%'>
      <tr id='ma'>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"1","up"=>$up))?>'>Numero</a></td>        
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"2","up"=>$up))?>'>Extracto</a></td>      	
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"3","up"=>$up))?>'>Fecha de Creacion</a></td>
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"4","up"=>$up))?>'>Area Genera</a></td>
    <td align=right id='mo'>Area Actual</td>
    <td align=right id='mo'>Estado Simple</td>
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"5","up"=>$up))?>'>Estado</a></td>       
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"6","up"=>$up))?>'>Prioridad</a></td>       
    <td align=right id='mo'><a id=mo href='<?=encode_link("exd_listado_tot.php",array("sort"=>"7","up"=>$up))?>'>Tramite</a></td>       
    <td align=right id='mo'>Acceso</td>       
  </tr>
 <?
   while (!$result->EOF) {
	$ref = encode_link("exd_nuevo.php",array("id_expediente"=>$result->fields['id_expediente'],"pagina_viene"=>"exd_listado_tot.php"));
   	$onclick_elegir="location.href='$ref'";?>

    <tr <?=atrib_tr()?>>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['id_expediente']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['extracto']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha_creacion'])?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['genera']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['actual']?></td>     
     <td onclick="<?=$onclick_elegir?>">
      <?if (trim ($result->fields['estado_simple'])=='a') echo 'Aceptado';
        else if (trim ($result->fields['estado_simple'])=='c') echo 'Creado';
        else if (trim ($result->fields['estado_simple'])=='d') echo 'Derivado';?>
    </td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['desc_estado']?></td>      
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['desc_prioridad']?></td>      
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['desc_tipo_tramite']?></td>      
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['desc_nivel_acceso']?></td>      
       
    </tr>
	<?$result->MoveNext();
    }?>    
</table>
<br>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
