<?


require_once("../../config.php");
variables_form_busqueda("encuesta");


if (!$cmd) {
	$cmd="pendientes";
	$_ses_encuesta["cmd"] = $cmd;
	phpss_svars_set("_ses_encuesta", $_ses_encuesta);
}


if ($_POST['nuevo']=="Nuevo" || $parametros["nuevo"]==1)
{   
    if($parametros["nuevo"]==1)
     $cmd="pendientes";
     
	$link=encode_link("nuevo_evento.php",array("id_evento"=>-1,"cmd"=>$cmd));
     header("location:$link");
	 //require_once("nuevo_evento.php");
	 //exit;
}	


$datos_barra = 
array(
					array(
						"descripcion"	=> "Pendientes",
						"cmd"			=> "pendientes"
						),
					array(
						"descripcion"	=> "Terminadas",
						"cmd"			=> "terminadas"
						)/*,
					array(
						"descripcion"	=> "Estadísticas",
						"cmd"			=> "estadisticas"
						)	*/
	 );

echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";

?>
<script>
// funciones que iluminan las filas de la tabla
function sobre(src,color_entrada) {
    src.style.backgroundColor=color_entrada;src.style.cursor="hand";
}
function bajo(src,color_default) {
    src.style.backgroundColor=color_default;src.style.cursor="default";
}
function link_sobre(src) {
    src.id='me';
}
function link_bajo(src) {
    src.id='mi';
}
</script>

<div class="newstyle-full-container">
<form name="form1" method="post" action="">
<? generar_barra_nav($datos_barra); ?>

  
<?        
  $q="select * from evento  ";
	$q.="left join tipo_evento using (id_tipo_evento)";
	
 $orden = array(
		        "default" => "2",
		        "default_up" => "0",
		        "1" => "evento.id_evento",
		        "2" => "evento.area",
		        "3" => "tipo_evento.id_tipo_evento"		
	           );	
 $filtro = array(
                 "evento.id_evento" => "ID Even./Inci.",
		         "evento.area" => "Area",
		         "tipo_evento.id_tipo_evento" => "Tipo Evento"
	            );	
 if ($cmd=='terminadas') $where = "evento.estado=2";		
 elseif ($cmd=='pendientes') $where = "evento.estado=1";?>

<?php if ($parametros['msg']){?>
<div class="alert alert-info" align="center">
  <b>INFORMACION!</b> <?=$parametros['msg']?>.
</div>
<?php }?>

<div class="row-fluid" align="center">
        <div class="span12" >
          <?list($sql,$total_usr,$link_pagina,$up) = form_busqueda($q,$orden,$filtro,$link_tmp,$where,"buscar");?>
           <input class="btn" type=submit name="buscar" value='Buscar' >
           <?if ($cmd=="pendientes"){?>
              <input class="btn" type="submit" name="nuevo" value="Nuevo">
          <?}?> 
        </div>
</div>

<?$eventos= sql($sql) or reportar_error($sql,__FILE__,__LINE__);?>

 <hr>
  <div class="pull-right paginador">
      <? echo (($eventos)?$eventos->RecordCount():0 )?>.
      <?=$link_pagina?>
  </div>
  
  <table class="table table-striped table-advance table-hover">
    <thead>
      <tr>
        <th>Mostrar Desc.</th>
        <th><a href='<? echo encode_link($_SERVER['PHP_SELF'],array("sort"=>"1","up"=>$up)); ?>'>ID Even./Inci.</a></th>
        <th><a href='<? echo encode_link($_SERVER['PHP_SELF'],array("sort"=>"2","up"=>$up)); ?>'>Area</a></th>
        <th><a href='<? echo encode_link($_SERVER['PHP_SELF'],array("sort"=>"3","up"=>$up)); ?>'>Tipo de Evento</a></th>
    </tr>
    </thead>
 <? 
 
 //inicializo variables
 $eventos->MoveFirst();
 $cont=0;
//lleno la tabla
 while (!$eventos->EOF)
	{
		//va a la pagina referenciada codificada!!
 		$ref = encode_link("nuevo_evento.php",
 				array("id_evento"=>$eventos->fields['id_evento'],"cmd"=>$cmd));
 		//a la variable $onclick le doy la referencia
 		$onclick="onClick=\"location.href='$ref'\";"; 
 		$suc=$eventos->fields['suseso'];
		
 ?>
    <tr><!-- no tiene tile-->
      
      <td align=center> <INPUT type=checkbox name="check_<?=$cont?>" onclick="javascript: (this.checked)?Mostrar('desc_<?=$cont?>') :Ocultar ('desc_<?=$cont?>');"></INPUT></td>
      <td align=center <?=$onclick?> title="<?=$suc?>"> <b> <? echo $eventos->fields['id_evento'] ?> </b></td>
      <td align=center <?=$onclick?> title="<?=$suc?>"> <b> <? echo $eventos->fields['area'] ?> </b></td>
      <td align=center <?=$onclick?> title="<?=$suc?>"> <b> <? echo $eventos->fields['tipo_evento'] ?> </b></td>      
      <tr>
      	<td colspan="4" align="center" <?=$onclick?>> 
      	<div id='desc_<?=$cont?>' style='display:none'>
        	<strong>
        	<textarea name="suceso" cols="150" rows="10" readonly="readonly" id="nombre"> <? echo $eventos->fields['suseso'] ?> </textarea>
  		    </strong>
  	  	</div>
		    </td>
	    </tr>
    </tr>    
 <? 
	 	$cont++;
 		$eventos->MoveNext();	
	}//del while
 ?>
</table>
</form>
</div>

<?=fin_pagina(false);?>