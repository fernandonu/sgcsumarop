<?php

require_once("../../config.php");

variables_form_busqueda("listado_ugsp");
$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($_GET['borrar']){
	$id_ugsp = $_GET['id_ugsp'];
	/*$tipo_documento = $_GET['tipo_documento'];*/
	$db->StartTrans();         
	    $query="DELETE FROM opcastroficas.ugsp
	            where id_ugsp='$id_ugsp'"; /*and nro_documento='$nro_documento'*/
	
	sql($query, "Error al eliminar el beneficiario") or fin_pagina();
    $db->CompleteTrans();    
};

if ($cmd == "") $cmd="mensual";

$orden = array(
        "default" => "1",
		"default_up" => "1",
        "1" => "ugsp.id_ugsp",
        "2" => "ugsp.descripcion"
       );

$filtro = array(
		    "ugsp.id_ugsp" => "Nro. UGSP",
			"ugsp.descripcion" => "Descripción",
			"ugsp.domicilio" => "domicilio"
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

//generar_barra_nav($datos_barra);

$sql_tmp="SELECT * FROM opcastroficas.ugsp";

//if ($cmd=="mensual")    $where_tmp=" (registro.tipo_liq='m')";
    
//if ($cmd=="cuat") $where_tmp=" (registro.tipo_liq='c')";

//if ($cmd=="mensualf") $where_tmp=" (registro.tipo_liq='mf')";
    
///if ($cmd=="cuatf") $where_tmp=" (registro.tipo_liq='cf')";

echo $html_header;
?>
<form name=form1 action="listado_ugsp.php" method=POST>

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center >
     <tr >
      <td align=center class="table table-hover">
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
		&nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'style="width:120px;height:30px">
		&nbsp;&nbsp;<input type='button' name="nueva_exd" value='Nuevo UGSP' onclick="document.location='formulario_ugsp.php'" style="width:120px;height:30px">
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table class="table table-hover">
  <tr>
  	<td colspan=12 align=left id=ma>
     <table width=100%>
      <tr >
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  
  <tr>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_ugsp.php",array("sort"=>"1","up"=>$up))?>'>Nro. UGSP</a></td>
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_ugsp.php",array("sort"=>"2","up"=>$up))?>'>Descripción</a></td>
    <td align=right id=mo><a id=mo href='#'>Domicilio</a></td>
    <td align=right id=mo><a id=mo href='#'>Cod. Postal</a></td>
	<td align=right id=mo><a id=mo href='#'>Localidad</a></td>
	<td align=right id=mo><a id=mo href='#'>Provincia</a></td>
	<td align=right id=mo><a id=mo href='#'>Tipo IVA</a></td>
	<td align=right id=mo><a id=mo href='#'>CUIT</a></td>
	<td align=right id=mo><a id=mo href='#'>Acciones</a></td>
  </tr>
 <?
   while (!$result->EOF) {
	$ref = encode_link("formulario_ugsp.php",array("id_ugsp"=>$result->fields['id_ugsp'],"pagina_viene"=>"listado_ugsp.php"));/*"nro_documento"=>$result->fields['nro_documento'],*/
	$ref_delete = "listado_ugsp.php?borrar=1&id_ugsp=".$result->fields['id_ugsp'];/*."&nro_documento=".$result->fields['nro_documento']*/
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
    <tr <?=atrib_tr()?>>     
     <td onclick="<?=$onclick_elegir?>" bgcolor="<?=$color?>" title="<?=$title?>"><?=$result->fields['id_ugsp']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['descripcion']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['domicilio']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['codigo_postal']?></td>      
	 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['localidad']?></td>      
	 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['provincia']?></td>      
	 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['tipo_iva']?></td>      
	 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['cuit']?></td>
	 <td>
		<div>
			<a href="<?=$ref_delete?>" onclick="return confirm('¿Desea borrar el registro?');">Borrar</a>
		</div>
	 </td>
    </tr>    
	<?$result->MoveNext();
    }?>    
</table>

</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
