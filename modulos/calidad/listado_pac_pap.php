<?

require_once("../../config.php");

$msg=$parametros['msg'];

if($_POST['boton_borrar']=="Borrar"){
	            $db->StartTrans();
 	            $i=1;$bien=1;
 	            while ($i<=$_POST['cant'])
                {
                  if ($_POST['borrar_'.$i]!="")
                  {
                   $sql="delete from log_pac_pap where id_pac_pap=".$_POST['borrar_'.$i];
                   if($db->Execute($sql))
                   {$sql="delete from pac_pap where id_pac_pap=".$_POST['borrar_'.$i];
                    if(!$db->Execute($sql))
                   	 $bien=0; 
                   }
                   else 
                    $bien=0; 
                  }
                  $i++;
                } 
                if($bien)
                 $msg="<b><center>Los items seleccionados se borraron con éxito</b></center>"; 
                else 
                 $msg="<b><center>Los items seleccionados no se pudieron borrar</b></center>";  
                $db->CompleteTrans(); 
}

variables_form_busqueda("pac_pap");
	
if ($cmd == "") $cmd="pac";

$datos_barra = array(
					array(
						"descripcion"	=> "Pedido de Acción Correctiva",
						"cmd"			=> "pac",
						),
					array(
						"descripcion"	=> "Pedido de Acción Preventiva",
						"cmd"			=> "pap"
						),
				    array(
						"descripcion"	=> "Todas",
						"cmd"			=> "todas"
						),
					array(
						"descripcion"	=> "Estadísticas",
						"cmd"			=> "estadisticas"
						)	
				 );
generar_barra_nav($datos_barra);
echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";

if($cmd!="estadisticas")//funciona como antes.....
{?>
<script>
var contador=0;
//esta funcion sirve para habilitar el boton de cerrar 
function habilitar_borrar(valor)
{
 if (valor.checked)
             contador++;
             else
             contador--;
 if (contador>=1)
         window.document.all.boton_borrar.disabled=0;
        else
         window.document.all.boton_borrar.disabled=1;
}//fin function
</script>

<form name="form1" method="POST" action="listado_pac_pap.php">
<?
if($cmd!="todas")
$orden = array(
		"default" => "1",
		"1" => "pac_pap.id_pac_pap",
		"2" => "no_conformidad.descripcion"
	);
else
$orden = array(
		"default" => "1",
		"default_up" => "0",
		"1" => "pac_pap.id_pac_pap",
		"2" => "no_conformidad.descripcion",
		"3" => "pac_pap.tipo"
	);

$filtro = array(
		"pac_pap.id_pac_pap" => "ID",
		"no_conformidad.descripcion" => "No Conformidad",
		"pac_pap.descripcion" => "PAC/PAP Descripción",
		"pac_pap.area" => "Area",
		"pac_pap.accion_inmediata" => "Acción Inmediata",
		"pac_pap.causa_nc" => "Causa No Conformidad",
		"pac_pap.accion_correctiva" => "Acción Correctiva",
		"pac_pap.evaluacion_eficacia" => "Evaluación de Eficacia",
		
	);

$query="select id_pac_pap,pac_pap.tipo,pac_pap.descripcion,pac_pap.area,pac_pap.accion_inmediata,pac_pap.causa_nc,pac_pap.verificacion,no_conformidad.descripcion,pac_pap.accion_correctiva as ac,pac_pap.evaluacion_eficacia as ee , estado_pa
from calidad.pac_pap join calidad.no_conformidad using (id_no_conformidad)";
$where="";
if($cmd=="pac")$where_tmp=" pac_pap.tipo=0";
elseif($cmd=="pap")$where_tmp=" pac_pap.tipo=1";?>

<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($query,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'>
	  </td>
     </tr>
</table>
<?$result=sql($sql) or die;?>
<?=$msg;?>

<table border=0 width=95% cellspacing=2 cellpadding=2 class="table table-striped table-advance table-hover" align=center>
  <tr>
  	<td colspan=12 align=left id=ma>
     <table width=100%>
      <tr id=ma>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>

	<tr id=mo>
	 <td width="1%"></td>
	 <td width='10%'><b><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"1","up"=>$up))?>'>ID</a></b></td>
	 <td width='80%'><b><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"2","up"=>$up))?>'>No Conformidad</a></b></td>
	 <?if($cmd=="todas"){?>
	 <td width="10%"><b><a id=mo href='<?=encode_link($_SERVER["PHP_SELF"],array("sort"=>"3","up"=>$up))?>'>Tipo</a></b></td>
	 <?}?>
   <td width="80%" align="center" id=mo><b>Revisado</b></td>
   <td width="80%" align="center" id=mo><b>Aprobado</b></td>

	</tr>

<?$i=1;
$cnr=1;
while(!$result->EOF){
	$ref =$link = encode_link("pac_pap.php",array("pagina"=>"listado","id"=>$result->fields["id_pac_pap"]));
	$onclick_elegir="location.href='$ref'";

  if ($result->fields['estado_pa']=='1'){
              $rev_cartel='SI';
              $apro_cartel='NO';
            }
  else if ($result->fields['estado_pa']=='2') {
              $rev_cartel='SI';
              $apro_cartel='SI';
            }
  else{
              $rev_cartel='NO';
              $apro_cartel='NO';
            }
  ?> 


<tr <?=atrib_tr()?>>
  <td width="1%"><input type="checkbox" name="borrar_<? echo $i; ?>" value="<? echo $result->fields['id_pac_pap']; ?>" onclick="habilitar_borrar(this)"></td>

  <?if($result->fields['ac']=="") $fondo_id="red";
	elseif($result->fields['ee']=="") $fondo_id="yellow";
	else $fondo_id="green";?> 

 <td align="center" bgcolor="<?=$fondo_id?>" onclick="<?=$onclick_elegir?>">
  <?if ($result->fields['tipo']=='0')  echo 'RG-SGC N AC'.$result->fields['id_pac_pap'].'_03';
    else echo 'RG-SGC N AP'.$result->fields['id_pac_pap'].'_03'?>
 </td>
 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['descripcion']?> </td>
 <?if($cmd=="todas"){?> 
	 <td align="center" onclick="<?=$onclick_elegir?>">
	  <?if($result->fields['tipo']==0) echo "P.A.C.";
	    else echo "P.A.P.";?> 
	 </td>
 <?}?>
  <td align="center" onclick="<?=$onclick_elegir?>"><b><?=$rev_cartel?></b></td>
  <td align="center" onclick="<?=$onclick_elegir?>"><b><?=$apro_cartel?></b></td>
</tr>
<?$i++;
 $result->MoveNext();
}?>
</table>

<input type="hidden" name="cant" value="<? echo $result->RecordCount(); ?>">

<center>
  <input type="button" name="boton_nuevo" value="Agregar Nuevo" onclick="document.location='pac_pap.php'">
  <input type="submit" name="boton_borrar" value="Borrar" disabled>
</center>
<table width='95%' bgcolor="white" align="center">
 <tr>    
    <td width='50%' align='right'>
    <b><font size="-3"> Faltan: Acción Correctiva y Evalucación de Eficacia
    </td>
    <td width='3%' bgcolor='red'>&nbsp;
    
    </td>
    <td  width='30%' align='right'>
    <b><font size="-3"> Falta: Evaluación de Eficacia
    </td>
    <td width='3%' bgcolor='yellow'>&nbsp;
    
    </td>
    <td  width='10%' align='right'>
    <b><font size="-3"> Completa
    </td>
    <td width='3%' bgcolor='green'>&nbsp;
    
    </td>
 </tr>
</table>

<?
}
else{//estadisticas
$query="select count(pac_pap.id_no_conformidad) as cant,no_conformidad.descripcion,no_conformidad.tipo from pac_pap right join no_conformidad using (id_no_conformidad) group by no_conformidad.descripcion,no_conformidad.tipo order by no_conformidad.tipo,no_conformidad.descripcion";
$result=sql($query) or die;?>

<br>
<table width='95%' border='0' cellspacing='2' align="center">	
<tr id=mo>
 <td>
  Descripcion
 </td>
 <td>
  Tipo
 </td>
 <td>
  Cantidad
 </td>
</tr>
<?
while(!$result->EOF)
{$tr_color=(((++$i)%2)==0)?$bgcolor1:$bgcolor2;
?>
 <tr bgcolor=<?=$tr_color?>>
  <td>
   <font size=2><?=$result->fields['descripcion']?></font>
  </td>
  <td align="center">
   <font size=2><?if($result->fields['tipo']==0) echo "P.A.C.";else echo "P.A.P.";?></font>
  </td>
  <td align="center">
  <font size=2><?=$result->fields['cant']?></font>
  </td>
 </tr> 
<?
 $result->MoveNext();
}
?>
</table>
<?
}	
?>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>