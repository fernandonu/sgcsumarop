<?

require_once("../../config.php");

//esto es para cuando lo creo
if ($_POST['guardar']=="Guardar")
{$fecha=date("Y-m-d H:i:s");
  $db->StartTrans();
    $sql = "select nextval('evento_id_evento_seq') as id_evento";
    $id_eve = sql($sql,"Erro al traer el id_evento") or fin_pagina();
    $sql = "insert into evento (id_evento,id_tipo_evento,area,suseso,estado,medida)";
    $sql .= " values (".$id_eve->fields['id_evento'].",".$_POST['tipos'].",'".$_POST['area']."','".$_POST['suseso']."',1,'".$_POST['medida']."')";
    $resultconsulta = sql($sql,"Error al insertar el nuevo evento/incidente") or fin_pagina(); 
    $sql = "insert into log_evento (id_evento,usuario,fecha,comentario)";
    $sql .= " values (".$id_eve->fields['id_evento'].",'$_ses_user[name]','$fecha','Creado')";
    $resultconsulta = sql($sql,"Error al insertar el log") or fin_pagina(); 
  $db->CompleteTrans();
  header("location:eventos_incidentes.php");
  
}
//************************************************************************************************************

//para cuando es editar
if ($_POST['editar']=="Guardar")
{$fecha=date("Y-m-d H:i:s");
  $db->StartTrans();    
    $sql = "update evento set id_tipo_evento=".$_POST['tipos'].",area='".$_POST['area']."',suseso='".$_POST['suseso']."',medida='".$_POST['medida']."',estado=1 where id_evento=".$_POST['id_evento'];    
    $resultconsulta = sql($sql,"Error al insertar el nuevo evento/incidente") or fin_pagina(); 
    $sql = "insert into log_evento (id_evento,usuario,fecha,comentario)";
    $sql .= " values (".$_POST['id_evento'].",'$_ses_user[name]','$fecha','Modificado')";
    $resultconsulta = sql($sql,"Error al insertar el log") or fin_pagina(); 
  $db->CompleteTrans();
  header("location:eventos_incidentes.php");
}
//***********************************************************************************************************


//para cuando lo paso a terminado
if ($_POST['terminar']=="Terminar")
{$fecha=date("Y-m-d H:i:s");
  $db->StartTrans();    
    $sql = "update evento set id_tipo_evento=".$_POST['tipos'].",area='".$_POST['area']."',suseso='".$_POST['suseso']."',medida='".$_POST['medida']."',estado=2 where id_evento=".$_POST['id_evento'];    
    $resultconsulta = sql($sql,"Error al insertar el nuevo evento/incidente") or fin_pagina(); 
    $sql = "insert into log_evento (id_evento,usuario,fecha,comentario)";
    $sql .= " values (".$_POST['id_evento'].",'$_ses_user[name]','$fecha','Terminado')";
    $resultconsulta = sql($sql,"Error al insertar el log") or fin_pagina(); 
  $db->CompleteTrans();
  header("location:eventos_incidentes.php");
}
//***********************************************************************************************************

if ($parametros['id_evento'])
   {$sql = "select * from evento where id_evento=".$parametros['id_evento'];
    $resul_sql = sql($sql,"Error al traer los datos del evento $sql") or fin_pagina();
    $area=$resul_sql->fields['area'];
    $tipo=$resul_sql->fields['id_tipo_evento'];
    $suseso=$resul_sql->fields['suseso'];
    $medida=$resul_sql->fields['medida'];
   	
   }	

?>


<?php
echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";
?>


<div  class="newstyle-full-container">

 <form name="nuevo_evento" method="POST" action="nuevo_evento.php">
 
  <legend>Eventos - Incidentes</legend>          
      
  <br>
 <?if ($parametros['id_evento']!=-1){
    $sql = "select * from log_evento where id_evento=".$parametros['id_evento'];
    $resul_log = sql($sql,"Error a traer los log");?>	

    <table class="table table-striped table-advance table-hover">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Usuario</th>
          <th>Acción</th>      
        </tr>
      </thead>
   <?while (!$resul_log->EOF){?>
    <tr>
     <td ><?=fecha($resul_log->fields['fecha'])?></td>
     <td ><?=$resul_log->fields['usuario']?></td>
     <td ><?=$resul_log->fields['comentario']?></td>
    </tr>
   <?$resul_log->MoveNext();
    }?>
  </table>
  <?}?>

  <br>

  <div class="row-fluid">
    <div class="span6">
      <label>Area:</label><input type="hidden" name="provinciacn" value="<?=$provinciacn?>" id="provinciacn">
      <input <?if ($parametros['cmd']=="terminadas") echo "readonly"?>  name="area" type="text" size="25" value="<?=$area?>"> 
    </div>    

    <div class="span6">
      <label>Tipo:</label><input type="hidden" name="tdrcn" value="<?=$tdrcn?>" id="tdrcn">
      <select name="tipos" <?if ($parametros['cmd']=="terminadas") echo "disabled"?> >
      <?$sql = "select * from tipo_evento";
        $resul_eventos=sql($sql,"Error no se pudieron consultar los eventos ene l select de eventos");
        $selected="";
        while (!$resul_eventos->EOF)
        {if ($resul_eventos->fields['id_tipo_evento']==$tipo) $selected="selected";
      ?>     
      <option <?=$selected?> value="<?=$resul_eventos->fields['id_tipo_evento']?>"><?=$resul_eventos->fields['tipo_evento']?></option>     
      <?
        $selected="";
        $resul_eventos->MoveNext();
        }
      ?>
     </select>
    </div>
  </div>

  <div class="row-fluid">
    <div class="span6">
      <label>Suceso:</label><input type="hidden" name="provinciacn" value="<?=$provinciacn?>" id="provinciacn">
      <textarea <?if ($parametros['cmd']=="terminadas") echo "readonly"?> name="suseso" style="width: 571px;"><?=$suseso?></textarea>
    </div>    

    <div class="span6">
      <label>Medida Tomada:</label><input type="hidden" name="tdrcn" value="<?=$tdrcn?>" id="tdrcn">
      <textarea <?if ($parametros['cmd']=="terminadas") echo "readonly"?> name="medida" style="width: 571px;"><?=$medida?></textarea>
    </div>
  </div> 
      
     
<?if ($parametros['cmd']=="pendientes"){?> 
   <?if ($parametros['id_evento']==-1) {?> 
      <div class="form-actions">
        <input class="btn btn-primary" type="submit" name="guardar" value="Guardar">
      </div>    
    <?}
    else {?>
      <div class="form-actions">
        <input class="btn btn-primary" type="submit" name="editar" value="Guardar">
        <input class="btn btn-primary" type="submit" name="terminar" value="Terminar">
        <input class="btn btn-info btn-large" type="button" name="volver" value="Volver" onclick="document.location='eventos_incidentes.php'">
      </div>
    <?}?>
   <input type="hidden" name="id_evento" value="<?=$parametros['id_evento']?>">
<?}?>

</form>
</div>
<?=fin_pagina();?>

