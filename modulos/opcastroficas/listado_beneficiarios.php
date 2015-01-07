<?php

require_once("../../config.php");

variables_form_busqueda("listado_efectores");
$fecha_hoy=date("Y-m-d H:i:s");
$fecha_hoy=fecha($fecha_hoy);

if ($_GET['borrar']){
	$nro_documento = $_GET['nro_documento'];
	$tipo_documento = $_GET['tipo_documento'];
	$db->StartTrans();         
	    $query="DELETE FROM opcastroficas.beneficiario
	            where tipo_documento='$tipo_documento' and nro_documento='$nro_documento'";
	
	sql($query, "Error al eliminar el beneficiario") or fin_pagina();
    $db->CompleteTrans();    
};

if ($cmd == "") $cmd="mensual";

$orden = array(
        "default" => "3",
		"default_up" => "0",
        "1" => "beneficiario.apellido",
        "2" => "beneficiario.nombre"
       );

$filtro = array(
		    "beneficiario.nro_documento" => "Nro. Documento",
			"beneficiario.apellido" => "Apellido",
			"beneficiario.nombre" => "nombre"              
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

$sql_tmp="SELECT * FROM opcastroficas.beneficiario";

//if ($cmd=="mensual")    $where_tmp=" (registro.tipo_liq='m')";
    

//if ($cmd=="cuat") $where_tmp=" (registro.tipo_liq='c')";

//if ($cmd=="mensualf") $where_tmp=" (registro.tipo_liq='mf')";
    

///if ($cmd=="cuatf") $where_tmp=" (registro.tipo_liq='cf')";

echo $html_header;
?>
<form name=form1 action="listado_beneficiarios.php" method=POST>

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center >
     <tr >
      <td align=center class="table table-hover">
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
		&nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'style="width:120px;height:30px">
		&nbsp;&nbsp;<input type='button' name="nueva_exd" value='Nuevo Beneficiario' onclick="document.location='formulario_beneficiario.php'" style="width:120px;height:30px">
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
    <td align=right id=mo><a id=mo href='#'>Nro. Beneficiario</a></td>        
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"1","up"=>$up))?>'>Nro. Documento</a></td>      	
    <td align=right id=mo><a id=mo href='<?=encode_link("listado_beneficiarios.php",array("sort"=>"2","up"=>$up))?>'>Apellido</a></td>
    <td align=right id=mo><a id=mo href='#'>Nombre</a></td>
    <td align=right id=mo><a id=mo href='#'>Edad</a></td>    
	<td align=right id=mo><a id=mo href='#'>Acciones</a></td>    
  </tr>
 <?
   while (!$result->EOF) {
	$ref = encode_link("formulario_beneficiario.php",array("tipo_documento"=>$result->fields['tipo_documento'],"nro_documento"=>$result->fields['nro_documento'],"pagina_viene"=>"listado_beneficiarios.php"));
	$ref_delete = "listado_beneficiarios.php?borrar=1&tipo_documento=".$result->fields['tipo_documento']."&nro_documento=".$result->fields['nro_documento'];
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
     <td onclick="<?=$onclick_elegir?>" bgcolor="<?=$color?>" title="<?=$title?>"><?=$result->fields['numero_beneficiario']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nro_documento']?></td>
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido']?></td>     
     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['nombre']?></td>      
	 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['edad']?></td>
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
