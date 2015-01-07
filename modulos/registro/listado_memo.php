<?php

require_once("../../config.php");
variables_form_busqueda("listado_memo");

$orden = array(
        "default" => "1",
		    "default_up" => "0",
        "1" => "id_memo",

       );
$filtro = array(
		    "id_memo" => "Nro memo",
       );


$sql_tmp="SELECT * FROM registro.memo";

echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";?>

<div class="newstyle-full-container">

<form name=form1 action="listado_memo.php" method=POST>
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
        <th ><a href='<?=encode_link("listado_memo.php",array("sort"=>"1","up"=>$up))?>'>Numero</a></th>        
        <th >Origen</th>
        <th >Registro</th>       
        <th >Fecha</th>       
        <th >Monto</th>       
      </tr>
    </thead>

 <?
   while (!$result->EOF) {
	$ref = encode_link("./memo.php", array("pagina_viene"=>"listado_memo.php","id_memo" =>$result->fields["id_memo"],"id_registro" =>$result->fields["id_registro"]));
   	$onclick_elegir="location.href='$ref'";?>

    <tr>     
     <td align="center" onclick="<?=$onclick_elegir?>"><?='RG-SGC N MI'.$result->fields['id_memo'].'_02'?></td>
        <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['origen']?></td>
        <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['registro']?></td>        
        <td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha'])?></td>        
        <td align="center" onclick="<?=$onclick_elegir?>"><?=number_format($result->fields['monto'],2,',','.')?></td>    
    </tr>
	<?$result->MoveNext();
    }?>    
</table>
</form>
</div>
<?echo fin_pagina();// aca termino ?>
