<?

require_once("../../config.php");

$id=$parametros['id'];
$ver_mod="si";
$pac_pap=-1;

$pagina_viene=$parametros["pagina_viene"] or $pagina_viene=$_POST["pagina_viene"];
$id_registro=$parametros["id_registro"] or $id_registro=$_POST["id_registro"];
if ($id_registro=='')$id_registro=0;

switch($_POST['boton'])
{
 case "Guardar Registro":$db->StartTrans();
							if($id)
                            {//actualizamos el pac_pap por las dudas que haya cambios
//                         	 $query="update pac_pap set descripcion='".$_POST['descripcion']."',id_no_conformidad=".$_POST['no_conformidad'].",tipo=".$_POST['pac_pap'].",fecha='".fecha_db($_POST['fecha'])."',area='".$_POST['area']."' where id_pac_pap=$id";
                             $query="update pac_pap set descripcion='".$_POST['descripcion']."',id_no_conformidad=".$_POST['no_conformidad'].",tipo=".$_POST['pac_pap'].",area='".$_POST['area']."' where id_pac_pap=$id";
                           	 if($db->Execute($query) or die($query."<br>".$db->errormsg()))
                           	 {
                           	  //$fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  $fecha_hoy=Fecha_db($_POST['fecha_g_desc']) or $fecha_hoy=date("Y-m-d H:i:s",mktime());;
                           	  //insertamos el registro de la actividad, en el log
                           	  /*$forma="actualización";
                           	  $query="insert into log_pac_pap(id_pac_pap,fecha,usuario,tipo,forma) values($id,'$fecha_hoy','".$_ses_user['name']."','descripcion','$forma')";
                           	  if($db->Execute($query))
                           	   $msg="<b><center>Los datos se actualizaron con éxito</center></b>";
                           	  else
                           	   $msg="<b><center>Los datos no se pudieron actualizar</center></b>";*/
                           	 }
                           	 else
                           	  $msg="<b><center>Los datos no se pudieron actualizar</center></b>";
                            }
                            else
                            {//insertamos el pac_pap por las dudas que haya cambios
//                           	 $query="insert into pac_pap (descripcion,id_no_conformidad,tipo,fecha,area)values('".$_POST['descripcion']."',".$_POST['no_conformidad'].",".$_POST['pac_pap'].",'".fecha_db($_POST['fecha'])."','".$_POST['area']."')";
                             $query="insert into pac_pap (descripcion,id_no_conformidad,tipo,area,id_registro)values('".$_POST['descripcion']."',".$_POST['no_conformidad'].",".$_POST['pac_pap'].","."'".$_POST['area']."','".$id_registro."')";
                           	 if($db->Execute($query))
                           	 {$query="select max(id_pac_pap) as maxid from pac_pap";
                           	  $maxid=$db->Execute($query) or die($db->ErrorMsg()."<br>Error en la seleccion de maxid de pac_pap");
                           	  //$fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  $fecha_hoy=Fecha_db($_POST['fecha_g_desc']) or $fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  //insertamos el registro de la actividad, en el log
                           	  $query="insert into log_pac_pap(id_pac_pap,fecha,usuario,tipo,forma) values(".$maxid->fields['maxid'].",'$fecha_hoy','".$_ses_user['name']."','descripcion','insersión')";
                           	  if($db->Execute($query))
                           	   $msg="<b><center>Los datos se insertaron con éxito</center></b>";
                           	  else
                           	   $msg="<b><center>Los datos no se pudieron insertar</center></b>"; 
                           	 }	 
                           	 else
                           	  $msg="<b><center>Los datos no se pudieron insertar</center></b>";
                            }  
                            $db->CompleteTrans();
                            $link=encode_link("listado_pac_pap.php",array("msg"=>$msg));  
                            header("location: $link");
                            break;
 case "Guardar Acción Correctiva":
                             $db->StartTrans();

                           	 $query="update pac_pap ";
//                             $query.=" set accion_correctiva='".$_POST['accion_correctiva']."'";
                             $query.=" set accion_correctiva='".$_POST['accion_correctiva']."'";
                            
                             $query.=",fecha_cierre='".fecha_db($_POST['fecha_cierre'])."'";
                             $query.=",id_no_conformidad=".$_POST['no_conformidad'];
                             $query.=",tipo=".$_POST['pac_pap'];
                             $query.=",area='".$_POST['area']."'";
                             $query.=" where id_pac_pap=$id";
                           	 if($db->Execute($query))
                           	 {
                           	  //$fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  $fecha_hoy=Fecha_db($_POST['fecha_g_ac']) or $fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  //insertamos el registro de la actividad, en el log
                           	  $forma="actualización";                           	  
                           	  $sql="select id_log_pac_pap from calidad.log_pac_pap where id_pac_pap=$id and tipo='accion_correctiva'";
                           	  $result_control_log=sql($sql,'No se Puede Ejecutar');                           	  
                           	  if ($result_control_log->RecordCount()=='0'){
	                           	  $query="insert into log_pac_pap(id_pac_pap,fecha,usuario,tipo,forma) values($id,'$fecha_hoy','".$_ses_user['name']."','accion_correctiva','$forma')";
	                           	  if($db->Execute($query))
	                           	   $msg="<b><center>Los datos se actualizaron con éxito<b><center>";
								  else
	                           	   $msg="<b><center>Los datos no se pudieron actualizar</center></b>";
                           	  }
                           	 }
                           	 else
                           	  $msg="<b><center>Los datos no se pudieron actualizar</center></b>";
                             $db->CompleteTrans();
                             $link=encode_link("listado_pac_pap.php",array("msg"=>$msg));
                             //echo $query;
                             header("location: $link");
                             break;
 case "Guardar Evaluación de Eficacia":
                             $db->StartTrans();
                           	 $query="update pac_pap set evaluacion_eficacia='".$_POST['evaluacion_eficacia']."',id_no_conformidad=".$_POST['no_conformidad'].",tipo=".$_POST['pac_pap'].",area='".$_POST['area']."' where id_pac_pap=$id";
                           	 if($db->Execute($query))
                           	 {
                           	  //$fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  $fecha_hoy=Fecha_db($_POST['fecha_g_ee']) or $fecha_hoy=date("Y-m-d H:i:s",mktime());
                           	  //insertamos el registro de la actividad, en el log
                           	  $forma="actualización";
                           	  $sql="select id_log_pac_pap from calidad.log_pac_pap where id_pac_pap=$id and tipo='evaluacion_eficacia'";
                           	  $result_control_log=sql($sql,'No se Puede Ejecutar');                           	  
                           	  if ($result_control_log->RecordCount()=='0'){
	                           	  $query="insert into log_pac_pap(id_pac_pap,fecha,usuario,tipo,forma) values($id,'$fecha_hoy','".$_ses_user['name']."','evaluacion_eficacia','$forma')";
	                           	  if($db->Execute($query))
	                           	   $msg="<b><center>Los datos se actualizaron con éxito</center></b>";
	                           	  else
	                           	   $msg="<b><center>Los datos no se pudieron actualizar</center></b>";
                           	  }
                           	 }
                           	 else
                           	  $msg="<b><center>Los datos no se pudieron actualizar</center></b>";
                             $db->CompleteTrans();

                             $link=encode_link("listado_pac_pap.php",array("msg"=>$msg));
                             header("location: $link");

                             break;
 case "Guardar Verificación":
                             $db->StartTrans();
                             if (!fechaok($_POST["fecha_verificacion"]))
                                        $msg="La Fecha de Verificación es inválida";
                             if ($_POST["verificacion_si_no"]=="si")  $verificacion=1;
                             elseif ($_POST["verificacion_si_no"]=="no")  $verificacion=0;
                                else $verificacion=-1;

                             $fecha_verificacion=fecha_db($_POST["fecha_verificacion"]);
                             $area=$_POST["area"];
                             $query="  update pac_pap set ";
                             $query.=" fecha_verificacion='$fecha_verificacion'";
                             $query.=",causa_nc='".$_POST['causa_nc']."'";
                             $query.=",accion_inmediata='".$_POST['accion_inmediata']."'";
                             $query.=",verificacion=$verificacion ";
                             //$query.=",fecha='$fecha'";
                             $query.=",area='$area'";
                             $query.=" where id_pac_pap=$id";

                              if($db->Execute($query))
                                {
                                 //$fecha_hoy=date("Y-m-d H:i:s",mktime());
                                 $fecha_hoy=Fecha_db($_POST['fecha_g_v']) or $fecha_hoy=date("Y-m-d H:i:s",mktime());
                                 //insertamos el registro de la actividad, en el log
                                 $forma="actualización";
                                 $sql="select id_log_pac_pap from calidad.log_pac_pap where id_pac_pap=$id and tipo='verificacion'";
                           	  	 $result_control_log=sql($sql,'No se Puede Ejecutar');                           	  
                           	  	 if ($result_control_log->RecordCount()=='0'){
	                                 $query="insert into log_pac_pap";
	                                 $query.="(id_pac_pap,fecha,usuario,tipo,forma)";
	                                 $query.=" values";
	                                 $query.="($id,'$fecha_hoy','".$_ses_user['name']."','verificacion','$forma')";
	                                 if($db->Execute($query))
	                                  $msg="<b><center>Los datos se actualizaron con éxito</center></b>";
	                                 else
	                                  $msg="<b><center>Los datos no se pudieron actualizar</center></b>";
                           	     }
                                }
                                else
                                 $msg="<b><center>Los datos no se pudieron actualizar</center></b>";

                             $db->CompleteTrans();

                             $link=encode_link("listado_pac_pap.php",array("msg"=>$msg));
                             header("location: $link");

                             break;

}
$pac_pap=$_POST['pac_pap'] or $pac_pap=0;
if(($parametros['pagina']=="listado")or($pagina_viene=="plan_admin"))
{//traemos los datos del pac/pap
 $query="select log_pac_pap.fecha as fecha_log,";
 $query.=" log_pac_pap.id_log_pac_pap as id_log,";
 $query.=" log_pac_pap.usuario,log_pac_pap.tipo as tipo_log,";
 $query.=" log_pac_pap.forma,pac_pap.* ";
 $query.=" from pac_pap join log_pac_pap using(id_pac_pap)";
 $query.=" where id_pac_pap=$id";
 $datos=$db->Execute($query) or die($db->ErrorMsg()."<br>Seleccion de datos del pac/pap");
 //almacenamos los datos generales en variables
 $no_conformidad=$datos->fields['id_no_conformidad'];
 $descripcion=$datos->fields['descripcion'];
 $causa_nc=$datos->fields['causa_nc'];
 $accion_inmediata=$datos->fields['accion_inmediata'];
 $accion_correctiva=$datos->fields['accion_correctiva'];
 $evaluacion_eficacia=$datos->fields['evaluacion_eficacia'];
 $pac_pap=$datos->fields['tipo'];
 $fecha=fecha($datos->fields['fecha']);
 $area=$datos->fields['area'];
 $fecha_cierre=fecha($datos->fields['fecha_cierre']);

 $verificacion=$datos->fields['verificacion'];
 $fecha_verificacion=fecha($datos->fields['fecha_verificacion']);

 //y separamos lo especifico en descripcion,accion_correctiva y evaluacion_eficacia
 //en arreglos para mostrarlos por pantalla.
 $log_desc=array();$i_d=0;
 $log_ac=array();$i_ac=0;
 $log_ee=array();$i_ee=0;
 $log_ve=array();$i_ve=0;
 while(!$datos->EOF)
 {switch($datos->fields['tipo_log'])
  {case "descripcion":
                      $log_desc[$i_d]=array();
                      $fecha_aux=split(" ",$datos->fields['fecha_log']);
                      $log_desc[$i_d]["fecha"]=fecha($fecha_aux[0]);
                      $log_desc[$i_d]["hora"]=$fecha_aux[1];
                      $log_desc[$i_d]["usuario"]=$datos->fields['usuario'];
                      $log_desc[$i_d]["forma"]=$datos->fields['forma'];
                      $log_desc[$i_d]["id_log"]=$datos->fields['id_log'];
                      $i_d++;
                      break;
   case "accion_correctiva":
                      $log_ac[$i_ac]=array();
                      $fecha_aux=split(" ",$datos->fields['fecha_log']);
                      $log_ac[$i_ac]["fecha"]=fecha($fecha_aux[0]);
                      $log_ac[$i_ac]["hora"]=$fecha_aux[1];
                      $log_ac[$i_ac]["usuario"]=$datos->fields['usuario'];
                      $log_ac[$i_ac]["forma"]=$datos->fields['forma'];
                      $log_ac[$i_ac]["id_log"]=$datos->fields['id_log'];
                      $i_ac++;
                      break;
   case "evaluacion_eficacia":
                      $log_ee[$i_ee]=array();
                      $fecha_aux=split(" ",$datos->fields['fecha_log']);
                      $log_ee[$i_ee]["fecha"]=fecha($fecha_aux[0]);
                      $log_ee[$i_ee]["hora"]=$fecha_aux[1];
                      $log_ee[$i_ee]["usuario"]=$datos->fields['usuario'];
                      $log_ee[$i_ee]["forma"]=$datos->fields['forma'];
                      $log_ee[$i_ee]["id_log"]=$datos->fields['id_log'];
                      $i_ee++;
                      break;
  case "verificacion":
                      $log_ve[$i_ve]=array();
                      $fecha_aux=split(" ",$datos->fields['fecha_log']);
                      $log_ve[$i_ve]["fecha"]=fecha($fecha_aux[0]);
                      $log_ve[$i_ve]["hora"]=$fecha_aux[1];
                      $log_ve[$i_ve]["usuario"]=$datos->fields['usuario'];
                      $log_ve[$i_ve]["forma"]=$datos->fields['forma'];
                      $log_ve[$i_ve]["id_log"]=$datos->fields['id_log'];
                      $i_ve++;
                      break;

  }
  $datos->MoveNext();
 }
}//de if($parametros['pagina']=="listado")

echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";
?>
<SCRIPT language='JavaScript' src="../../lib/funciones.js">
</script>
<script>

function control_datos(tipo)
{
 var i=0;
 var check_si=0;
 var cant=document.all.pac_pap.length;

 for(i;i<cant;i++)
 {if(document.all.pac_pap[i].checked)
   check_si=1;
 }
 if(!check_si)
 {alert('Debe seleccionar un tipo Pedido de Acción');
  return false;
 }
 //if(document.all.fecha.value=="")
 //{alert('Debe especificar una fecha');
 // return false;
 //}
 if(document.all.no_conformidad.value==-1)
 {alert('Debe seleccionar un tipo de No Conformidad');
  return false;
 }
 if(document.all.area.value=="")
 {alert('Debe especificar un área');
  return false;
 }

 var texto_desc = document.getElementById('descripcion').value; 
 if(texto_desc=="")
 {alert('Debe especificar un Nro de Registro ("0" en caso de que no exista)');
  return false;
 }

 switch(tipo)
 {case "evaluacion_eficacia":
        var texto_evaluacion_eficacia = document.getElementById('evaluacion_eficacia').value; 
        if(texto_evaluacion_eficacia=="")
							 {alert('Debe especificar una Evaluación de Eficacia');
							  return false;
							 }
                           break;
  case "accion_correctiva":  
						   if(document.all.accion_correctiva.value=="")
						   {alert('Debe especificar una Accion Correctiva'); 
							return false;
						   }

						   if(document.all.fecha_cierre.value=="")
						   {alert('Debe especificar una fecha de cierre para la Acción Correctiva');
							return false;
						   }
						break;
  case "verificacion": if (document.all.fecha_verificacion.value=="")
                        {
                        alert('Debe elegir una fecha para verificación');
                        return(false);
                        }
 }
 return true;
}
</script>
<?
$link=encode_link("pac_pap.php", array("id"=>$id));
?>
<br>
<?=$msg?>
<form name="form1" action="<?=$link?>" method="POST">
<input type="hidden" name="id_registro" value="<?=$id_registro?>">
<input type="hidden" name="pagina_viene" value="<?=$pagina_viene?>">
<input type="hidden" name="pagina" value="<?=$parametros['pagina']?>">

 <table width="90%"  border="1" align="center" cellpadding="4" class="table table-bordered">
    <tr id=mo>
      <td colspan="3">
        P.A.C/P.A.P
      </td>
    </tr>


  
  <tr>
   <td colspan="2">
    <font size="3">
      <b>Acción Correctiva
      <input type="radio" name="pac_pap" value="0" <?if($pac_pap==0)echo "checked";?> onclick='if(<?=$pac_pap?>==1)document.form1.submit();'> 
        <?if ($pac_pap=='0')  echo 'RG-SGC N AC'.$id.'_03';?><br>
        Acción Preventiva:
      <input type="radio" name="pac_pap" value="1" <?if($pac_pap==1)echo "checked";?> onclick='if(<?=$pac_pap?>==0)document.form1.submit();'>
        <?if ($pac_pap=='1')  echo 'RG-SGC N AP'.$id.'_03';?></b>
    </font>
   </td>
  </tr>
  
  <tr>
   <td>    
    <b>Area</b>&nbsp;
    <input type="text" name="area" value="<?=$area?>" size=58>
   </td>
  </tr>
  <tr>

   <td colspan="3">
    <b>No conformidad</b>
    <?
     $query="select * from no_conformidad";
     if($pac_pap==1)//si el tipo es pac traemos las no conformidades de pac
      $query.=" where tipo=1";
     elseif($pac_pap==0)//si el tipo es pap traemos las no conformidades de pap
      $query.=" where tipo=0";
     $resultado_no_conformidad=$db->Execute($query) or die($db->ErrorMsg()."<br>error al traer las no conformidades");
     $cantidad=$resultado_no_conformidad->RecordCount();

    ?>
    <select name="no_conformidad">
     <option value=-1>Seleccione una opción</option>
     <?
      while(!$resultado_no_conformidad->EOF)
      {?>
       <option value="<?=$resultado_no_conformidad->fields['id_no_conformidad']?>" <?if($resultado_no_conformidad->fields['id_no_conformidad']==$no_conformidad)echo "selected"?> ><?=$resultado_no_conformidad->fields['descripcion']?></option>
     <?
      $resultado_no_conformidad->MoveNext();
      }
     ?>
    </select>
   </td>
  </tr>
</table>
<br>
<table width="90%"  border="1" align="center" cellpadding="4" bgcolor='<?=$bgcolor_out?>'>
 <tr>
  <td>
   <b>Nro de Registro de la No Conformidad ó el Producto No conforme Asociado</b><br>
   <textarea name="descripcion" id="descripcion" cols="107" rows="1"><?=$descripcion?></textarea>
   
   <div align="right">
  <b> Fecha de Creación del registro </b>
   <?if ($ver_mod=="si"){?>
   	<?cargar_calendario();?>
    <input type=text name='fecha_g_desc' value="<?=date("d/m/Y")?>" size=10 readonly>
	<?=link_calendario("fecha_g_desc");?>
   <?}?>
    <input class="btn btn-primary" type="submit" name="boton" value="Guardar Registro" onclick="return control_datos('descripcion');">
   </div>
   
   <table width="100%">
   <?
   $tam=sizeof($log_desc);
   for($i=0;$i<$tam;$i++)
   {?>
    <tr id=ma>
	
    
    <td>
     <?$ref=encode_link("pac_pap_actualiza_log.php",array("id_log"=>$log_desc[$i]['id_log']));?>
     <?if ($ver_mod=="si"){?>
     	<input class="btn btn-primary" type="button" value="$" name="$" onclick="window.open('<?=$ref?>')">
     <?}?>
    </td>
   
    
    <td align="left">
     Fecha de <?=$log_desc[$i]['forma']?> de la Descripción: <?=$log_desc[$i]['fecha']//." ".$log_desc[$i]["hora"]?>
    </td>
       
    <td  align="right">
     Usuario: <?=$log_desc[$i]['usuario']?>
    </td>
    </tr>
   <?
   }
   ?>
   </table>
  </td>
 </tr>
</table>
<br>
<table width="90%"  border="1" align="center" cellpadding="4" bgcolor='<?=$bgcolor_out?>'>
 <tr>
  <td>
   <b>Evaluación / Análisis</b>
   <br>
   <br>
   <b>Detalle</b>
   <br>
   <textarea name="accion_correctiva" id="accion_correctiva" cols="107" rows="5" <?=$dis_ac?>><?=$accion_correctiva?></textarea>
   <br>
   <b>Fecha última estimada para su implementación</b>
   &nbsp;
   <input type="text" name="fecha_cierre" value="<?=$fecha_cierre?>">&nbsp;<? cargar_calendario(); echo link_calendario("fecha_cierre"); ?>
   <br>
   
   <div align="right">
   <?if ($ver_mod=="si"){?>
   	<input type=text name='fecha_g_ac' value="<?=date("d/m/Y")?>" size=10 readonly>
	<?=link_calendario("fecha_g_ac");?>
   <?}?>
	<input class="btn btn-primary" type="submit" name="boton" value="Guardar Acción Correctiva" onclick="return control_datos('accion_correctiva')">
   </div>
   
   <table width="100%">
   <?
   $tam=sizeof($log_ac);
   for($i=0;$i<$tam;$i++)
   {?>
    <tr id=ma>
    
     
     <td>
     <?$ref=encode_link("pac_pap_actualiza_log.php",array("id_log"=>$log_ac[$i]['id_log']));?>
     <?if ($ver_mod=="si"){?>
     <input class="btn btn-primary" type="button" value="$" name="$" onclick="window.open('<?=$ref?>')">     
     <?}?>
    </td>
    
    
    <td align="left">
     Fecha de <?=$log_ac[$i]['forma']?> de la Acción Correctiva: <?=$log_ac[$i]['fecha']//." ".$log_ac[$i]["hora"]?>
    </td>
    
    <td align="right">
     Usuario: <?=$log_ac[$i]['usuario']?>
    </td>
    </tr>
   <?
   }
   ?>

   </table>
  </td>
 </tr>
</table>
<br>
<table width="90%"  border="1" align="center" cellpadding="4" bgcolor='<?=$bgcolor_out?>'>
  <tr>
    <td>
        <table width=100% align=center>
           <tr>
             <td colspan=2 align=left>
             <b>Verificación</b>
             </td>
           </tr>
           <tr>
             <td width=30% align=left>
             <b>Se implantó acción preventiva</b>
             </td>
             <td width=70% align=left>
             <input type=radio name=verificacion_si_no value="si" <?if ($verificacion==1) echo "checked";?>>
             &nbsp<b>Si</b>
             </td>
           </tr>
           <tr>
             <td>
             &nbsp;
             </td>
             <td align=left>
             <input type=radio name=verificacion_si_no value="no" <?if ($verificacion==0) echo "checked";?>>
             &nbsp<b>No</b>
             </td>
            </tr>

            <tr>
              <td>
                <b>Verificacion / Control de Cambio:</b>
              </td>
              <td>
                <textarea name="causa_nc" cols="107" rows="5" ><?=$causa_nc?></textarea>
              </td>
            </tr>
            <tr>
              <td>
                <b>Resultado:</b>
              </td>
              <td>
                <textarea name="accion_inmediata" cols="107" rows="5" ><?=$accion_inmediata?></textarea>
              </td>
            </tr>
       <tr>
        <td colspan=3>
        <b>Fecha evaluación eficacia</b>&nbsp;
        <input type="text" name="fecha_verificacion" value="<?=$fecha_verificacion?>">&nbsp;<? cargar_calendario(); echo link_calendario("fecha_verificacion"); ?>
        </td>
       </tr>
      <tr>
        <td colspan=3 align=right>
        	<?if ($ver_mod=="si"){?>
        	<input type=text name='fecha_g_v' value="<?=date("d/m/Y")?>" size=10 readonly>
			<?=link_calendario("fecha_g_v");?>
			<?}?>
           <input class="btn btn-primary" type=submit name="boton" value="Guardar Verificación" onclick="return control_datos('verificacion');">
        </td>
      </tr>
     </table>
    </td>
    <tr>
    <td>
    <table width="100%">
    <?
      $tam=sizeof($log_ve);
         for($i=0;$i<$tam;$i++)
        {
    ?>
       <tr id=ma>
       	   
       
		  <td>
     		<?$ref=encode_link("pac_pap_actualiza_log.php",array("id_log"=>$log_ve[$i]['id_log']));?>
     		<?if ($ver_mod=="si"){?>
     		<input class="btn btn-primary" type="button" value="$" name="$" onclick="window.open('<?=$ref?>')">     
     		<?}?>
    	  </td>   
    	 
    		    
       	  <td align="left">
            Fecha de <?=$log_ve[$i]['forma']?> de la Verificación: <?=$log_ve[$i]['fecha']//." ".$log_ac[$i]["hora"]?>
          </td>
          
          <td align="right">
          Usuario: <?=$log_ve[$i]['usuario']?>
          </td>
    </tr>
   <?
   }
   ?>

   </table>

    </td>
  </tr>
</table>
<br>
<table width="90%"  border="1" align="center" cellpadding="4" bgcolor='<?=$bgcolor_out?>'>
 <tr>
  <td>
    <?
    if($descripcion=="" || $accion_correctiva=="")
     $dis_ee="disabled";
    else
     $dis_ee="";
    ?>
   <b>Evaluación de eficacia</b><br>
   <textarea name="evaluacion_eficacia" id="evaluacion_eficacia" cols="107" rows="5" <?=$dis_ee?>><?=$evaluacion_eficacia?></textarea>
   <div align="right">
   <?if ($ver_mod=="si"){?>
    <input type=text name='fecha_g_ee' value="<?=date("d/m/Y")?>" size=10 readonly>
	<?=link_calendario("fecha_g_ee");?>
   <?}?>
    <input class="btn btn-primary" type="submit" name="boton" value="Guardar Evaluación de Eficacia" <?=$dis_ee?> onclick="return control_datos('evaluacion_eficacia')">
   </div>
   <table width="100%">
   <?
   $tam=sizeof($log_ee);
   for($i=0;$i<$tam;$i++)
   {?>
    <tr id=ma>
    
    
    <td>
     <?$ref=encode_link("pac_pap_actualiza_log.php",array("id_log"=>$log_ee[$i]['id_log']));?>
     <?if ($ver_mod=="si"){?>
     <input class="btn btn-primary" type="button" value="$" name="$" onclick="window.open('<?=$ref?>')">     
     <?}?>
    </td>
    
    
    <td align="left">
     Fecha de <?=$log_ee[$i]['forma']?> de la evaluación de eficacia: <?=$log_ee[$i]['fecha']//." ".$log_ee[$i]["hora"]?>
    </td>
    
    <td align="right">
     Usuario: <?=$log_ee[$i]['usuario']?>
    </td>
    </tr>
   <?
   }
   ?>
   </table>
  </td>
 </tr>

<tr>
      <td colspan="4" >
      <input class="btn btn-info btn-large" type="button" name="volver" value="Volver" onclick="document.location='listado_pac_pap.php'">
      </td>     
</tr>

</table>

</form>
</body>
</html>