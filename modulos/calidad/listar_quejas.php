<?php

require_once("../../config.php");

echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";

?>

<script language="javascript">

// funciones que iluminan las filas de la tabla

function sobre(src,color_entrada) {

    src.style.backgroundColor=color_entrada;src.style.cursor="hand";

}

function bajo(src,color_default) {

    src.style.backgroundColor=color_default;src.style.cursor="default";

}

</script>
<div class="newstyle-full-container">
<form action="listar_quejas.php" method="post" name="form_listar_queja">



<?php 

echo "<input type=hidden name=sort value='$sort'>\n";

echo "<input type=hidden name=up value='$up'>\n";


// Formulario de busqueda

// Variables necesarias

$itemspp=10;

$up = $_POST["up"] or $up = $parametros["up"];

$sort = $_POST["sort"] or $sort = $parametros["sort"] or $sort = "";

$page = $parametros["page"] or $page = 0;                                //pagina actual

$filter = $_POST["filter"] or $filter = $parametros["filter"];           //campo por el que se esta filtrando

$keyword = $_POST["keyword"] or $keyword = $parametros["keyword"];       //palabra clave

// Fin variables necesarias

if ($up=="") $up = "1";   // 1 ASC 0 DESC

$orden = Array (

"default" => "2",

"1" => "nbre_cl",

"2" => "fecha",

"3" => "tipo_queja",

//"4"=> "usuario",

"5"=> "mail");



$filtro = Array (

"nbre_cl" => "Cliente",

"fecha" => "Fecha",

"tipo_queja" => "Tipo",

//"usuario" => "usuario",

"mail" => "mail");



$sql_temp="select * from Quejas join log_quejas using(id_queja)";

$contar="select count(*) from quejas";

if($_POST['keyword'] || $keyword)// en la variable de sesion para keyword hay datos)

     $contar="buscar";?>



<div class="row-fluid" align="center">
        <div class="span12" >
          <?list($sql,$total,$link_pagina,$up2) = form_busqueda($sql_temp,$orden,$filtro,$link_tmp,$where_tmp,$contar);?>
           <input class="btn" type=submit name="buscar" value='Buscar' >
           <input class="btn" name="nueva_queja" type="button" value="Nueva Queja" Onclick="location.href='calidad_quejas.php';">
        </div>
</div>

<? $res_query = sql($sql) or die();?>

<br>


<hr>
  <div class="pull-right paginador">
      <?=$total ?> Quejas.
      <?=$link_pagina?>
  </div>
  
  <table class="table table-striped table-advance table-hover">
    <thead>
      <tr>

		<td ><a href='<? echo encode_link("listar_quejas.php",Array('sort'=>1,'up'=>$up2,'page'=>$page,'keyword'=>$keyword,'filter'=>$filter))?>'><b>Numero</b></a></td>
		<td ><a href='<? echo encode_link("listar_quejas.php",Array('sort'=>1,'up'=>$up2,'page'=>$page,'keyword'=>$keyword,'filter'=>$filter))?>'><b>Nombre_cliente</b></a></td>

		<td ><a href='<? echo encode_link("listar_quejas.php",Array('sort'=>5,'up'=>$up2,'page'=>$page,'keyword'=>$keyword,'filter'=>$filter))?>'><b>E-mail</b></a></td>

		<td ><a href='<? echo encode_link("listar_quejas.php",Array('sort'=>2,'up'=>$up2,'page'=>$page,'keyword'=>$keyword,'filter'=>$filter))?>'><strong>Fecha</strong></a></td>

		<td ><a href='<? echo encode_link("listar_quejas.php",Array('sort'=>3,'up'=>$up2,'page'=>$page,'keyword'=>$keyword,'filter'=>$filter))?>'><strong>Tipo</strong></a></td>

	  </tr>
	  </thead>



<? $cont_filas=0;

   

  while (!$res_query->EOF )

  {

   if ($cnr==1)

    {$color1=$bgcolor1;

     $color =$bgcolor2;

     $cnr=0;

    }

  else

   {$color1=$bgcolor2;

    $color =$bgcolor1;

    $cnr=1;

   }

//guardamos en esta variable, las observaciones de la licitacion

 //para mostrarlos en title del nombre de la licitacion

	$title_obs=$res_query->fields['descripcion'];



 //LIMITAR OBSERVACIONES: controlamos el ancho y la cantidad de

 //lineas que tienen las observaciones y cortamos el string si

 //se pasa de alguno de los limites

	$long_title=strlen($title_obs);

	//cortamos si el string supera los 600 caracteres

	if($long_title>600)

		{$title_obs=substr($title_obs,0,600);

    	 $title_obs.="   SIGUE >>>";

		}

		$count_n=str_count_letra("\n",$title_obs);

		//cortamos si el string tiene mas de 12 lineas

		if($count_n>12)

		{$cn=0;$j=0;

		 for($i=0;$i<$long_title;$i++)

		 {

		  if($cn>12)

		   $i=$long_title;

		  if($title_obs[$i]=="\n")

		   $cn++;

		  $j++;



		 }

		 $title_obs=substr($title_obs,0,$j);

		 $title_obs.="   SIGUE >>>";

		}

 ?>

<?$ref = encode_link("calidad_quejas.php", array("id_queja" =>$res_query->fields["id_queja"]));
    $onclick_elegir="location.href='$ref'";?>
<tr >
   <td align="center" onclick="<?=$onclick_elegir?>"><? echo $res_query->fields["id_queja"] ?></td>
   <td align="center" onclick="<?=$onclick_elegir?>"><? echo $res_query->fields['nbre_cl'] ?></td>
   <td align="center" onclick="<?=$onclick_elegir?>"><? echo $res_query->fields['mail'] ?></td>
   <td align="center" onclick="<?=$onclick_elegir?>"><? echo Fecha($res_query->fields['fecha']) ?></td>
   <td align="center" onclick="<?=$onclick_elegir?>"><? echo $res_query->fields['tipo_queja'] ?></td>
</tr>

  <?   

     $cont_filas++;

	 $res_query->MoveNext();

  }  ?>



</table>

</form>

</div>