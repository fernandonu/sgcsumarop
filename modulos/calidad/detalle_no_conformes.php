<?
/*AUTOR: diegoinga

$Author: mari $
$Revision: 1.1 $
$Date: 2006/12/27 20:30:03 $
*/


require_once("../../config.php");
$descripcion="";
$pagina_viene=$parametros["pagina_viene"] or $pagina_viene=$_POST["pagina_viene"];
$id_registro=$parametros["id_registro"] or $id_registro=$_POST["id_registro"];

if ($id_registro=='')$id_registro=0;

switch($_POST['boton']) {
case "Guardar":  
	
	$fecha_emision=date("Y-m-d"); 
  $fecha_evento=fecha_db($_POST['fecha_evento']); 
	$fecha_sol_in=fecha_db($_POST['fecha_sol_in']); 
	$id_proveedor = $_POST['id_proveedor'];
	$id_prod_esp = $_POST['id_prod_esp'];
	$usuario = $_POST['usuario'];
	$descripcion_inconformidad = $_POST['descripcion_inconformidad'];
	$disposicion = $_POST['disposicion'];
	$area = $_POST['area'];
	$tipo_producto = $_POST['tipo_producto'];
	$texto_deteccion = $_POST['texto_deteccion'];
	$nro_serie = $_POST['nro_serie'];
	$id = $_POST['id'];
	$bar_code=$_POST['bar_code'];
  $resultado=$_POST['resultado'];
  $requi_incum=$_POST['requi_incum'];
  $sol_in=$_POST['sol_in'];
  $met_uti=$_POST['met_uti'];
	$con_cambio=$_POST['con_cambio'];
	
	if ($fecha_evento == "") $fecha_evento = "NULL";
		else  $fecha_evento = "'".$fecha_evento."'";
  if ($fecha_sol_in == "") $fecha_sol_in = "NULL";
    else  $fecha_sol_in = "'".$fecha_sol_in."'";
	if ($id_proveedor == "") $id_proveedor = "NULL";
	if ($id_prod_esp == ""){
		$id_prod_esp = "NULL";
		$bar_code = "";		
	} 
	if ($disposicion == "-1") $disposicion = "NULL";
	
	if ($_POST['ie']=="Insertar") {
		$sql="insert into noconformes(id_proveedor,fecha_evento,usuario,fecha_emision,descripcion_inconformidad,id_disposicion,id_prod_esp,area,id_tipo_producto,deteccion,nro_serie,cod_barra,id_registro,resultado,fecha_sol_in,requi_incum,sol_in,met_uti,con_cambio)
				values($id_proveedor,$fecha_evento,'$usuario','$fecha_emision','$descripcion_inconformidad',$disposicion,$id_prod_esp,'$area',$tipo_producto,'$texto_deteccion','$nro_serie','$bar_code','$id_registro','$resultado',$fecha_sol_in,'$requi_incum','$sol_in','$met_uti','$con_cambio');";
		sql($sql,"No se puede Ejecutar la Consulta") or fin_pagina();
		$msg="<b><center>El Producto No Conforme se insertó con éxito</center></b>";
		$link=encode_link("no_conformes.php",array("msg"=>$msg));  
		header("location: $link");
	}
	else {  //actualizo
		//print_r($_POST);
		
		$sql="update noconformes set id_proveedor=$id_proveedor, fecha_evento=$fecha_evento, 
					usuario='$usuario', fecha_emision='$fecha_emision', descripcion_inconformidad='$descripcion_inconformidad', 
					id_disposicion=$disposicion, id_prod_esp=$id_prod_esp,area='$area',
					id_tipo_producto=$tipo_producto,deteccion='$texto_deteccion', cod_barra='$bar_code',nro_serie='$nro_serie' ,
          fecha_sol_in=$fecha_sol_in,
          requi_incum='$requi_incum',
          sol_in='$sol_in',
          met_uti='$met_uti',
          con_cambio='$con_cambio',
          resultado='$resultado'
					where id_noconforme=$id";
		sql($sql,"No se puede Ejecutar la Consulta") or fin_pagina();
		$msg="<b><center>El Producto No Conforme se actualizó con éxito</center></b>";
		$link=encode_link("no_conformes.php",array("msg"=>$msg));  
		header("location: $link");
	}
	break;
default:{
         if($parametros['id']){
          $id=$parametros['id'];
          $sql="select noconformes.deteccion,noconformes.nro_serie,noconformes.id_tipo_producto, 
          noconformes.id_proveedor,noconformes.id_prod_esp,noconformes.fecha_evento,noconformes.usuario,
          noconformes.descripcion_inconformidad,noconformes.id_disposicion,noconformes.area, noconformes.cod_barra , noconformes.id_registro, 
          noconformes.resultado, noconformes.fecha_sol_in,noconformes.requi_incum,noconformes.sol_in,noconformes.met_uti,noconformes.con_cambio,estado_nc
          from calidad.noconformes           
          where noconformes.id_noconforme=$id";
          $resultado=sql($sql,"No se puede Ejecutar la Consulta") or fin_pagina();
          
          $fecha_evento=fecha($resultado->fields['fecha_evento']);
          $usuario=$resultado->fields['usuario'];
          $descripcion_inconformidad=$resultado->fields['descripcion_inconformidad'];
          $disposicion=$resultado->fields['id_disposicion'];
          $area=$resultado->fields['area'];
          $nro_serie=$resultado->fields['nro_serie'];
          $tipo_producto=$resultado->fields['id_tipo_producto'];
          $texto_deteccion=$resultado->fields['deteccion'];
          $bar_code = $resultado->fields['cod_barra'];         
          $fecha_sol_in = fecha($resultado->fields['fecha_sol_in']);
          $requi_incum = $resultado->fields['requi_incum'];
          $sol_in = $resultado->fields['sol_in'];
          $met_uti = $resultado->fields['met_uti'];
          $con_cambio = $resultado->fields['con_cambio'];
          $id_registro = $resultado->fields['id_registro']; 
          $id_proveedor = $resultado->fields['id_proveedor']; 
          $resultado1 = $resultado->fields['resultado'];
          $estado_nc = $resultado->fields['estado_nc'];
         }
         else //inserto nuevo
          extract($_POST);
echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";
?>         
<SCRIPT language='JavaScript' src="../../lib/funciones.js">
cargar_calendario();
</script>

<br>
<?
$link=encode_link("detalle_no_conformes.php", array("pagina"=>$parametros['pagina'],"id" =>$parametros['id']));
?>

<form name="form1" action="<?=$link?>" method="POST">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="id_registro" value="<?=$id_registro?>">
<input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
<table width="90%"  border="1" align="center" class="table table-bordered">

  <tr>
    <td id=mo colspan="4">Detalle de Producto No Conforme</td>
  </tr>
  <tr>  
    <td colspan="4"><b>Tratamiento de Ocurrencias <?='RG-SGC N° TO 0'.$id.'_03';?></b></td> 
  </tr>
<tr>
<td>
<?
 $sql="select * from tipo_producto";
 $resultado_tipo_prod=$db->Execute($sql) or die($db->ErrorMsg()."<br>".$sql);
?>
<strong>Producto</strong>
</td>
<td>
<select name="tipo_producto">
<?
while(!$resultado_tipo_prod->EOF)
{
?>
<option value="<?=$resultado_tipo_prod->fields['id_tipo_producto'];?>" <?=($tipo_producto==$resultado_tipo_prod->fields['id_tipo_producto'])?"selected":"";?>><?=$resultado_tipo_prod->fields['descripcion'];?></option>
<?
$resultado_tipo_prod->MoveNext();
}
?>
</select>
</td>
<td>
<strong>Categorias</strong>
</td>
<td>
      <select name='id_proveedor' Style="width:200px" 
            onKeypress="buscar_combo(this);"
        onblur="borrar_buffer();"
        onchange="borrar_buffer();">
        <option value="-1">Seleccione</option>
       <?
       $sql= "select * from calidad.categorias_ocurrencias order by desc_cat_ocu";
       $res=sql($sql) or fin_pagina();
      
       while (!$res->EOF){ 
          $id_select=$res->fields['id_categorias_ocurrencias'];
          $descripcion=$res->fields['desc_cat_ocu'];?>
        <option value='<?=$id_select?>' <?if ($id_proveedor==$id_select) echo "selected"?> ><?=$descripcion?></option>
          <?
          $res->movenext();
          }?>
      </select>      
</td>
</tr>
<tr>
    <td><strong>Fecha Evento</strong></td>
    <td><input type="text" name="fecha_evento" value="<?=$fecha_evento?>">&nbsp;<? cargar_calendario(); echo link_calendario("fecha_evento"); ?></td>
    <td><strong>Usuario</strong></td>
    <td><input type="text" name="usuario" value="<? if($usuario=="") echo $_ses_user['name'];else echo $usuario; ?>" readonly></td>
    </tr>
    <tr>
     <td><strong>Área</strong></td>
     <td colspan="3"><input type="text" name="area" value="<?=$area?>" style="size:50"></td>
</tr>

     <tr>
     <td colspan="4" align="center">
      <br>
       <strong>Varios</strong><br>
      <textarea name="requi_incum" style="margin: 0px; width: 1258px; height: 40px;" ><?=$requi_incum?></textarea>
     </td>
    </tr>

   <tr>
     <td colspan="4" align="center">
      <br>
       <strong>Detalle de la No Conformidad/PNC</strong><br>
  		<textarea name="texto_deteccion" style="margin: 0px; width: 1258px; height: 90px;"><?=$texto_deteccion?></textarea>
  	 </td>
  	</tr>
      <tr>
    	 <td colspan="4">
    		<strong>Disposición</strong>&nbsp;
    	 	<?
    		 $query="select * from disposicion";
    		 $disp=$db->Execute($query) or die($db->ErrorMsg()."<br>Error al traer las disposiciones");
    		?>
    		<select name="disposicion">
    		 <option value=-1>Seleccione una disposicion</option>
    		<?
          while(!$disp->EOF)
          {?>
           <option value=<?=$disp->fields['id_disposicion']?> <?if($disp->fields['id_disposicion']==$_POST['disposicion'] || $disp->fields['id_disposicion']==$disposicion) echo "selected"?>><?=$disp->fields['descripcion']?></option>
          <?
           $disp->MoveNext();
          }?> 
    		</select>
    	 <strong>Fecha de correción/solución inmediata</strong>
      <input type="text" name="fecha_sol_in" value="<?=$fecha_sol_in?>">&nbsp;<? cargar_calendario(); echo link_calendario("fecha_sol_in"); ?>
      </td>
   	</tr>	 
    	
    <tr>
     <td colspan="4" align="center">
      <br>
       <strong>Causa</strong><br>
  		<textarea name="descripcion_inconformidad" style="margin: 0px; width: 1258px; height: 90px;" rows="4"><?=$descripcion_inconformidad?></textarea>
  	 </td>
     </tr>

    <tr>
     <td colspan="4" align="center">
      <br>
       <strong>Solución Inmediata/Corrección</strong><br>
      <textarea name="sol_in" style="margin: 0px; width: 1258px; height: 90px;" rows="4"><?=$sol_in?></textarea>
     </td>
    </tr>
    <!--<tr>
     <td colspan="4" align="center">
      <br>
       <strong>Método utilizado para identificación de la causa</strong><br>
      <textarea name="met_uti" cols="80" rows="4"><?=$met_uti?></textarea>
     </td>
    </tr>-->
    
   <tr>
     <td colspan="4" align="center">
      <br>
       <strong>Verificación / Control de Cambios</strong><br>
      <textarea name="con_cambio" style="margin: 0px; width: 1258px; height: 90px;" rows="4"><?=$con_cambio?></textarea>
     </td>
    </tr>
	 <tr>
     <td colspan="4" align="center">
      <br>
       <strong>Resultado</strong><br>
  		<textarea name="resultado" style="margin: 0px; width: 1258px; height: 90px;" rows="4"><?=$resultado1?></textarea>
  	 </td>
  	</tr>
<tr>
      <td align="center" colspan="4">
        <input class="btn btn-primary" type="submit" name="boton" value='Guardar' <?=($estado_nc==2)?'disabled':'';?>>
        <input type="hidden" name="ie" value='<?if($parametros['pagina']!="listado")echo "Insertar"; else echo "Editar"?>'>
        <?if ($pagina_viene=='plan_admin'){?>
        <input class="btn btn-info btn-large" type="button" name="boton" value='Volver' onclick="document.location='../registro/plan_listado.php'">
        <?}else{?>
        <input class="btn btn-info btn-large" type="button" name="boton" value='Volver' onclick="document.location='no_conformes.php'">
        <?}?>
      </td>     
</tr>
</table> 

</form>
</body>
</html>
<?
  break;
 }//del default
}//fin switch
fin_pagina();
?>
