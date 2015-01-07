<?php

require_once("../../config.php");

variables_form_busqueda("plan_listado");

$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($cmd == "")  $cmd="mensual";

$orden = array(
        "default" => "3",
		    "default_up" => "0",
        "1" => "provincia.descripcion",
        "2" => "periodo.descripcion",
        "3" => "nro_exd",
        "4" => "usuario",
        "5" => "registro.id_registro",
       );
$filtro = array(
		    "provincia.descripcion" => "Provincia",
        "periodo.descripcion" => "Periodo",
        "nro_exd" => "Expediente",              
        "to_char(registro.id_registro,'999999')" => "Numero de Registro",              
       );
$datos_barra = array(
     array(
        "descripcion"=> "Mensual",
        "cmd"        => "mensual"
     ),
      array(
        "descripcion"=> "Mensual FRSEC",
        "cmd"        => "mensualf"
     ),
     array(
        "descripcion"=> "Cuatrimestral",
        "cmd"        => "cuat"
     ),    
     array(
        "descripcion"=> "Cuatrimestral FRSEC",
        "cmd"        => "cuatf"
     ),
     array(
        "descripcion"=> "Todos",
        "cmd"        => "todos"
     )
);

generar_barra_nav($datos_barra);

$sql_tmp="SELECT
registro.registro.id_registro,
registro.provincia.descripcion as desc,
registro.registro.tipo_liq,
registro.periodo.descripcion,
registro.registro.nro_exd,
registro.registro.estado_capitas,
registro.registro.estado_adm,
registro.registro.usuario
FROM
registro.registro
INNER JOIN registro.dato_registro ON registro.registro.id_registro = registro.dato_registro.id_registro
INNER JOIN registro.periodo ON registro.registro.id_periodo = registro.periodo.id_periodo
INNER JOIN registro.provincia ON registro.registro.id_provincia = registro.provincia.id_provincia";


if ($cmd=="mensual")
    $where_tmp=" (registro.tipo_liq='m')";
    

if ($cmd=="cuat")
    $where_tmp=" (registro.tipo_liq='c')";

if ($cmd=="mensualf")
    $where_tmp=" (registro.tipo_liq='mf')";
    

if ($cmd=="cuatf")
    $where_tmp=" (registro.tipo_liq='cf')";

echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";?>
<div class="newstyle-full-container">

  <form name=form1 action="plan_listado.php" method=POST>
  <div class="row-fluid" >
        <div class="span12" align="center">
          <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
           <input class="btn" type=submit name="buscar" value='Buscar' >
           <input class="btn" type='button' name="nueva_exd" value='Nuevo Expediente' onclick="document.location='plan_admin.php'">
           <? $link=encode_link("plan_listado_excel.php",array("cmd"=>$cmd));?>
           <i class="glyphicon glyphicon-share-alt"></i><a onclick="window.open('<?=$link?>')"> Excel Completo</a>
           <? $link=encode_link("plan_listado_excel1.php",array("cmd"=>$cmd));?>
           <i class="glyphicon glyphicon-share-alt"></i><a onclick="window.open('<?=$link?>')"> Excel Simple</a>
        </div>
  </div>
  <?$result = sql($sql) or die;?>
  <hr>
  <div class="pull-right paginador">
      <?=$total_muletos?> Registros.
      <?=$link_pagina?>
  </div>
      
  <table class="table table-striped table-advance table-hover">
    <thead>
      <tr>
        <th><a href='<?=encode_link("plan_listado.php",array("sort"=>"5","up"=>$up))?>'>Numero de Registro</a></th>        
        <th><a href='<?=encode_link("plan_listado.php",array("sort"=>"1","up"=>$up))?>'>Provincia</a></th>     	
        <th><a href='<?=encode_link("plan_listado.php",array("sort"=>"2","up"=>$up))?>'>Periodo</a></th>
        <th><a href='<?=encode_link("plan_listado.php",array("sort"=>"3","up"=>$up))?>'>Expediente</a></th>
        <th><a href='<?=encode_link("plan_listado.php",array("sort"=>"4","up"=>$up))?>'>Usuario</a></th>    
        <?if ($cmd=="todos"){?>
        	<th>Tipo Liquidacion</th>
        <?}?>  
      </tr>
    </thead>
   <?
     while (!$result->EOF) {
  	$ref = encode_link("plan_admin.php",array("id_registro"=>$result->fields['id_registro'],"pagina_viene"=>"plan_listado.php"));
     	$onclick_elegir="location.href='$ref'";
    
      if (($result->fields['estado_capitas']=='1')or($result->fields['estado_adm']=='1')) {
        $color='#F5F6CE';
        if ($result->fields['estado_capitas']=='1'){
          $title='El Registro de Capita esta Revisado y NO Aprobado';
        }
        else $title='El Registro de Administracion esta Revisado y NO Aprobado';
      }
      else{
        $color='';
      }?>
      <tr>     
       <td onclick="<?=$onclick_elegir?>" bgcolor="<?=$color?>" title="<?=$title?>"><?='RG-SGC N PC'.$result->fields['id_registro'].'_03'?></td>
       <td onclick="<?=$onclick_elegir?>"><?=$result->fields['desc']?></td>
       <td onclick="<?=$onclick_elegir?>"><?=$result->fields['descripcion']?></td>
       <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nro_exd']?></td>     
       <td onclick="<?=$onclick_elegir?>"><?=$result->fields['usuario']?></td>      
       <?if ($cmd=="todos"){?>    
       	<td><?=$result->fields['tipo_liq']?></td> 
       <?}?>    
       </tr>    
  	<?$result->MoveNext();
      }?>    
  </table>
  <br>
    <table class="table table-bordered" >       
       <td width=30% bordercolor='#FFFFFF'>
        <table class="table table-bordered" border=1 bordercolor='#FFFFFF' cellspacing=0 cellpadding=0 width=100%>
         <tr>
          <td width=30 bgcolor='#F5F6CE' bordercolor='#000000' height=30>&nbsp;</td>
          <td bordercolor='#FFFFFF'>El Plan de Calidad contiene un Registro Revisado y NO Aprobado</td>
         </tr>              
        </table>
       </td>
   </table>

  </form>
</div>

<?echo fin_pagina();// aca termino ?>
