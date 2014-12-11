<?php
require_once("../../config.php");

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

echo $html_header;
?>

<?php 
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";?>
<div class="newstyle-full-container">
<form name=form1 action="usr_areas_listado.php" method=POST>

  <div class="row-fluid" align="center">
      <div class="span8">
  		<?list($sql,$total_pais,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
  	   <input class="btn" type=submit name="buscar" value='Buscar' >
  	 </div>
  </div>

<?$result = sql($sql,"No se ejecuto en la consulta principal") or die;?>

<hr>
  <div class="pull-right paginador">
      <?=$total_pais?> usuarios.
      <?=$link_pagina?>
  </div> 
  <table class="table table-striped table-advance table-hover">
    <thead>
      <tr>
        <th><i class="glyphicon glyphicon-user"></i> <a href='<?=encode_link("usr_areas_listado.php",array("sort"=>"1","up"=>$up))?>'>Nombre de Usuario para Vincular Areas</a></th>
        <th><i class="glyphicon glyphicon-user"></i> <a href='<?=encode_link("usr_areas_listado.php",array("sort"=>"2","up"=>$up))?>'>Apellido</a></th>       
        <th><i class="glyphicon glyphicon-user"></i> Nombre</th>
      </tr>
    </thead>
  <?
   while (!$result->EOF) {
   		$ref = encode_link("usr_areas_admin.php",array("id_usuario"=>$result->fields['id_usuario'],"pagina"=>"usr_areas_listado"));
    	$onclick_elegir="location.href='$ref'";
   	?>
  
    <tr>     
       <td onclick="<?=$onclick_elegir?>"><?php echo $result->fields['login']?></td>
       <td onclick="<?=$onclick_elegir?>"><?php echo $result->fields['apellido']?></td>
       <td onclick="<?=$onclick_elegir?>"><?php echo $result->fields['nombre']?></td>
      </tr>    
  	  <?$result->MoveNext();
    }?>
  </table>

</form>
</div>
<?echo fin_pagina();// aca termino ?>
