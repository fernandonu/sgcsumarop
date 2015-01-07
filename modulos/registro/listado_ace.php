<?php

require_once("../../config.php");
variables_form_busqueda("listado_ace");

$orden = array(
        "default" => "1",
		    "default_up" => "0",
        "1" => "id_informe_ace",

       );
$filtro = array(
		    "id_informe_ace" => "Nro Informe",
       );


$sql_tmp="SELECT * FROM registro.informe_ace";

echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";?>
<div class="newstyle-full-container">

<form name=form1 action="listado_ace.php" method=POST>
<div class="row-fluid" align="center">
        <div class="span8" >
           <?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
           <input class="btn" type=submit name="buscar" value='Buscar' >
           
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
        <th><a href='<?=encode_link("listado_ace.php",array("sort"=>"1","up"=>$up))?>'>Numero</a></th>        
        <th>Auditoria</th>
        <th>Documento</th>       
        <th>Legajo</th>       
        <th>Fecha</th>   
      </tr>
    </thead>    
 <?
   while (!$result->EOF) {
	$ref = encode_link("./inf.php", array("pagina_viene"=>"listado_ace.php","id_informe_ace" =>$result->fields["id_informe_ace"],"id_registro" =>$result->fields["id_registro"]));
   	$onclick_elegir="location.href='$ref'";?>

    <tr>     
        <td onclick="<?=$onclick_elegir?>"><?='RG-SGC N InfACE 0'.$result->fields['id_informe_ace'].'_02'?></td>
        <td onclick="<?=$onclick_elegir?>"><?=$result->fields['audit']?></td>
        <td onclick="<?=$onclick_elegir?>"><?=$result->fields['documento']?></td>       
        <td onclick="<?=$onclick_elegir?>"><?=$result->fields['num_leg']?></td>       
        <td onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha'])?></td>   
    </tr>
	<?$result->MoveNext();
    }?>    
</table>

</form>
</div>
<?echo fin_pagina();// aca termino ?>
