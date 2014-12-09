<?php
require_once("../../config.php");
require_once("funciones.php");

switch ($_POST['boton'])
{case "Enviar Nuevo Mensaje":{require_once("../mensajes/nuevo_mens.php");
							  break;
							 }
 case "Reenviar Mensaje": {require_once("../mensajes/redirige.php");
							break;
						   }
 case "Borrar Mensaje":{require_once("../mensajes/borrar.php");
						break;
					   }
 case "Mensajeria":{require_once('../mensajes/mensajeria.php');
					exit;
					break;
					   }

default:{ $filas_encontradas=0;
echo $html_header;
?>
<script>
// funciones que iluminan las filas de la tabla
function sobre(src,color_entrada) {
	src.style.backgroundColor=color_entrada;src.style.cursor="hand";
}
function bajo(src,color_default) {
	src.style.backgroundColor=color_default;src.style.cursor="default";
}
function comprueba()
{var i;
if (document.form.cantr.value==0)
{alert("Debe seleccionar un mensaje");
 return false;
}
 if (document.form.cantr.value==1)
 {if(document.form.radio.checked)
  return true;
 }
 for(i=0;i<document.form.radio.length;i++)
 {if(document.form.radio[i].checked)
  return true;
 }//for
alert("Debe seleccionar un mensaje");
return false;
}

function borrar()
{var valor;
 if (!comprueba())
 return false;
 else
 {valor=prompt('Dime el motivo por el cual desestimas este mensaje','');
  if ((valor==null) || (valor==""))
   return false;
  else
  {window.document.form.mensaje.value=valor;
   return true;
  }
 }
}
function controlnoc(){
  alert("Hay ordenes de compra que est�n por vencer.");
}
function controlvenc(){
  alert("Hay ordenes de compra vencidas.");
}

</script>
<?php
//este codigo es el que controla si ordenes de compra por vencer o mensajes que venecen hoy
if(($_POST['boton1']!='Mensajeria')&&($_ses_mensajes_primera_v != 1)){
   phpss_svars_set("_ses_mensajes_primera_v",1);
   $fecha_actual=date("Y-m-d H:m:s");
   $fecha_proxima=date("Y-m-d",mktime(0,0,0,date("m"),(date("d")+7),date("Y")));
   $sql = "select count(id_mensaje) as cant from mensajes where tipo1='LIC' and tipo2='NOC' and terminado='f' and desestimado='f' and usuario_destino='".$_ses_user['login']."' and fecha_vencimiento < '$fecha_actual'";
   $result=sql($sql) or fin_pagina();
   $vench=$result->fields["cant"];
   $sql = "select count(id_mensaje) as cant from mensajes where tipo1='LIC' and tipo2='NOC' and terminado='f' and desestimado='f' and usuario_destino='".$_ses_user['login']."' and fecha_vencimiento <= '$fecha_proxima'";
   $result=sql($sql) or fin_pagina();
   $noc=$result->fields["cant"];

 if($noc){
 ?>
 <script language='JavaScript'>
  controlnoc();
 </script>
 <?
 }//if noc

 if($vench){
 	?>
   <script language='JavaScript'>
	 controlvenc();
   </script>
 <? }//vence hoy
 }//if

 // actualizo fecha de recibido y bit de recibido de los que no lo tienen
$sql1="select id_mensaje,fecha_recibo from mensajes where recibido='f' or fecha_recibo is null and usuario_destino='".$_ses_user['login']."'";
$result1=sql($sql1) or fin_pagina();
$fecha_r=date("Y-m-d H:i:s");
while (!$result1->EOF)
{
 if($result1->fields['fecha_recibo']==''){
   $sql="update mensajes set fecha_recibo='".$fecha_r."' where id_mensaje=".$result1->fields['id_mensaje'];
   $result=sql($sql) or fin_pagina();
   }
$result1->MoveNext();      
}
 ?>
<form name="form" method="post" action="mensajes.php">
    
    <table border="1" class="table table-hover">
    
    <div >
    <input class="btn btn-info" type="submit" name="boton" value="Enviar Nuevo Mensaje">
    <input class="btn btn-warning" type="submit" name="boton" value="Reenviar Mensaje" onClick="return comprueba();">
    <input class="btn btn-danger" type="submit" name="boton" value="Borrar Mensaje" onClick="return borrar();">
    </div>

	  <tr id='mo'>
        <td width="2%" >&nbsp;</td>
        <td width="10%"> Fecha entrega </td>
        <td width="70%"> Mensaje </td>
        <td width="10%"> Vencimiento </td>
      </tr>
   
		  
          
      
        <?php
		   //$fecha_actual=date("Y/m/d H:i:s");
		   $fecha_actual=date("Y/m/d");
		   $hora_actual=date("H:i");
		   //$fecha_a=substr($fecha_actual,0,10);
		   //$hora_a=substr($fecha_actual,10,16);
			list($aa,$ma,$da) = explode("/",$fecha_actual);
			list($ha,$mia)= explode(":",$hora_actual);
			$orden=" order by fecha_recibo desc";
			$sql="select tipo1,recibido,id_mensaje,comentario ,titulo, fecha_entrega, fecha_vencimiento,fecha_recibo from mensajes where terminado='f' and desestimado='f' and usuario_destino='".$_ses_user['login']."'".$orden;
			$result=sql($sql) or fin_pagina();
	        
          $cantidad+=$result->RecordCount();
	   			$i=0;
	   			while(!$result->EOF){ 
               	if ($i==0)
	   				{$color=$bgcolor2;
	    			 $color2=$bgcolor1;
	    			 $i=1;
	   				}
	   			else
	   			{$color=$bgcolor1;
	    		 $color2=$bgcolor2;
	    		 $i=0;
	   			}
			 $fecha_v=substr($result->fields['fecha_vencimiento'],0,10);
             $hora_v=substr($result->fields['fecha_vencimiento'],11,16);
			 list($a,$m,$d) = explode("-",$fecha_v);
       list($hv,$miv,$sv)= explode(":",$hora_v);
			             
			 if ($result->fields['fecha_recibo']=="")
 					{$filas_encontradas++;
  					 $id[]=$result->fields['id_mensaje'];
 					}
 				
         ?>
		<input type="hidden" name="tipo1" value="<?php echo $result->fields['tipo1']; ?>">
        <a href="ver_mens.php?id_mensaje=<? echo $result->fields['id_mensaje'];?>&donde=0"> 
        <tr bgcolor="<?php echo $color; ?>"  title="<?php echo $result->fields['titulo']; ?>" onMouseOver="sobre(this,'#FFFFFF');" onMouseOut="bajo(this,'<? echo $color;?>' );"> 
          <td width="24" height="18" valign="top"  > 
            <input type="radio" name="radio" value="<?php echo $result->fields['id_mensaje'] ?>">
          </td>
          <td width="131" valign="top" > 
            <center>
              <font size=2 color="<?php echo $color2; ?>"> 
              <?php 
			  $fecha1=fecha(substr($result->fields['fecha_entrega'],0,10));
			  $tiempo1=substr($result->fields['fecha_entrega'],10,18);
			  echo $fecha1.$tiempo1;
			  ?>
              </font> 
		  </td>
          <td width="388" valign="top"> <font size=2 > 
            <center>
              <?php echo $result->fields['comentario'];?>
            </center>
            </font> </td>
          <td width="145" valign="top" bgcolor="<?php echo $colorfv;?>"><font size=2 color="<?php echo $color2; ?>"> 
            <center>
              <?php 
					$fecha=fecha(substr($result->fields['fecha_vencimiento'],0,10));
					$tiempo=substr($result->fields['fecha_vencimiento'],10,18);
					echo $fecha.$tiempo;?>
            </center>
            </font></td>
        </tr>
        </a> 
        <input type="hidden" name="comentario[<?php echo $result->fields['id_mensaje']; ?>]" value="<?php echo $resultado['comentario']; ?>">
        
		<? 	 
   $result->MoveNext();
	}//while 
?>
	  </table>
          


    
<input type="hidden" name="cantr" value="<?PHP echo $cantidad; ?>">
<input type="hidden" name="mensaje" value="">
</form>

<?php
 }
}// fin switch
?>