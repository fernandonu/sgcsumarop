<?php
require_once ("../../config.php");

extract($_POST,EXTR_SKIP);
if ($parametros) extract($parametros,EXTR_OVERWRITE);

if ($cmd=="mensual")
    $tipo_liq=" where registro.tipo_liq='m'";
    

if ($cmd=="cuat")
    $tipo_liq=" where registro.tipo_liq='c'";

if ($cmd=="mensualf")
    $tipo_liq=" where registro.tipo_liq='mf'";
    

if ($cmd=="cuatf")
    $tipo_liq=" where registro.tipo_liq='cf'";

excel_header("listado_completo.xls");
//echo $html_header;
?>

<form name="form1" method="post" action="plan_listado_excel.php">
 <br>
 <table width="100%" align="center" border="1" bordercolor="#585858" cellspacing="0" cellpadding="5"> 
  <tr bgcolor="#C0C0FF">
    <td align="right" id="mo">Codigo Provincia</td>
    <td align="right" id="mo">Provincia</td>
    <td align="right" id="mo">Tipo Liquidacion</td>
    <td align="right" id="mo">Periodo</td>
    <td align="right" id="mo">Tipo Liquidacion</td>
    <td align="right" id="mo">Expediente</td>
    <td align="right" id="mo">Usuario</td>
    <td align="right" id="mo">Fecha Carga</td>
    <td align="right" id="mo">Fecha Ingreso y revision DDJJ</td>
    <td align="right" id="mo">Validacion</td>
    <td align="right" id="mo">Revalidacion</td>
    <td align="right" id="mo">Soporte Informatico</td>
    <td align="right" id="mo">Sistema de Gestion</td>
    <td align="right" id="mo">Ajuste</td>
    <td align="right" id="mo">Comparacion ajuste Memo</td>    
    <td align="right" id="mo">Liquidacion</td>    
    <td align="right" id="mo">Comparacion Archivo Cuenta Corriente</td>    
    <td align="right" id="mo">Validacion</td>    
    <td align="right" id="mo">ReValidacion</td>    
    <td align="right" id="mo">Envio Expedientes</td>    
    <td align="right" id="mo">Envio de Repuestas</td>
    <td align="right" id="mo">Calculo Transferencia</td>    
    <td align="right" id="mo">Fecha Validacion</td>    
    <td align="right" id="mo">Fecha Validacion</td>    
    <td align="right" id="mo">Fecha ReValidacion</td>    
    <td align="right" id="mo">Fecha ReValidacion</td>    
    <td align="right" id="mo">Fecha Validacion ADM</td>    
    <td align="right" id="mo">Envio de Repuestas</td>   
    <td align="right" id="mo">Observaciones</td>    
    <td align="right" id="mo">Importe ADM</td>    
    <td align="right" id="mo">Fecha Validacion ADM</td>    
    <td align="right" id="mo">Revision ADM</td>    
    <td align="right" id="mo">Validacion ADM</td>    
    <td align="right" id="mo">Contingencia ADM</td>    
    <td align="right" id="mo">Fecha Administracion</td>    
    <td align="right" id="mo">Revalidacion ADM</td>    
    <td align="right" id="mo">Armado Autorizacion ADM</td>    
    <td align="right" id="mo">Autorizacion Capitas ADM</td>    
    <td align="right" id="mo">Nota Ufis ADM</td>    
    <td align="right" id="mo">Fecha Salida ADM</td>    
    <td align="right" id="mo">Fecha Pago ADM</td>    
        
  </tr>
  <?$sq2="SELECT
              registro.provincia.codigo,
              registro.provincia.descripcion,
              registro.periodo.tipo_liq_per,
              registro.periodo.descripcion as desc1,
              registro.registro.tipo_liq,
              registro.registro.nro_exd,
              registro.registro.usuario,
              registro.registro.fecha_carga,
              registro.dato_registro.revision,
              registro.dato_registro.validacion,
              registro.dato_registro.revalidacion,
              registro.dato_registro.sop_inf,
              registro.dato_registro.sis_ges,
              registro.dato_registro.ajuste,
              registro.dato_registro.comp_ajuste,
              registro.dato_registro.liq,
              registro.dato_registro.comp_inf,
              registro.dato_registro.valida,
              registro.dato_registro.revalid,
              registro.dato_registro.env_exd,
              registro.dato_registro.env_res,
              registro.dato_registro.observacion,
              registro.dato_registro.importe,
              registro.dato_registro.fecha_ingreso,
              registro.dato_registro.revision_exd,
              registro.dato_registro.valid_adm,
              registro.dato_registro.contingencia,
              registro.dato_registro.fecha_adm,
              registro.dato_registro.revalid_adm,
              registro.dato_registro.armado_autorizacion,
              registro.dato_registro.aut_capitas,
              registro.dato_registro.nota_ufis,
              registro.dato_registro.dia_salida,
              registro.dato_registro.fecha_pago,
              registro.dato_registro.calc_transf,
              registro.dato_registro.fecha_val,
              registro.dato_registro.fecha_val1,
              registro.dato_registro.fecha_reval,
              registro.dato_registro.fecha_reval1,
              registro.dato_registro.fecha_val2,
              registro.dato_registro.fecha_reval2
              FROM
              registro.registro
              INNER JOIN registro.provincia ON registro.registro.id_provincia = registro.provincia.id_provincia
              INNER JOIN registro.periodo ON registro.registro.id_periodo = registro.periodo.id_periodo
              INNER JOIN registro.dato_registro ON registro.registro.id_registro = registro.dato_registro.id_registro
              $tipo_liq";
       $result = sql($sq2) or die;
  
  while (!$result->EOF) {?>
    <tr>     
	   <td><?=$result->fields['codigo']?></td>
     <td><?=$result->fields['descripcion']?></td>
     <td><?=$result->fields['tipo_liq_per']?></td>
     <td><?=$result->fields['desc1']?></td>
     <td><?=$result->fields['tipo_liq']?></td>
     <td><?=$result->fields['nro_exd']?></td>
     <td><?=$result->fields['usuario']?></td>
     <td><?=fecha($result->fields['fecha_carga'])?></td>
     <td><?=fecha($result->fields['revision'])?></td>
     <td><?=$result->fields['validacion']?></td>
     <td><?=$result->fields['revalidacion']?></td>
     <td><?=$result->fields['sop_inf']?></td>
     <td><?=$result->fields['sis_ges']?></td>
     <td><?=$result->fields['ajuste']?></td>
     <td><?=fecha($result->fields['comp_ajuste'])?></td>
     <td><?=fecha($result->fields['liq'])?></td>
     <td><?=fecha($result->fields['comp_inf'])?></td>
     <td><?=$result->fields['valida']?></td>
     <td><?=$result->fields['revalid']?></td>
     <td><?=fecha($result->fields['env_exd'])?></td>
     <td><?=fecha($result->fields['env_res'])?></td>
     <td><?=$result->fields['calc_transf']?></td>
     <td><?=fecha($result->fields['fecha_val'])?></td>
     <td><?=fecha($result->fields['fecha_val1'])?></td>
     <td><?=fecha($result->fields['fecha_reval'])?></td>
     <td><?=fecha($result->fields['fecha_reval1'])?></td>
     <td><?=fecha($result->fields['fecha_val2'])?></td>
     <td><?=fecha($result->fields['fecha_reval2'])?></td>
     <td><?=$result->fields['observacion']?></td>
     <td><?=number_format($result->fields['importe'],2,',','.')?></td>
     <td><?=fecha($result->fields['fecha_ingreso'])?></td>
     <td><?=$result->fields['revision_exd']?></td>
     <td><?=$result->fields['valid_adm']?></td>
     <td><?=$result->fields['contingencia']?></td>
     <td><?=fecha($result->fields['fecha_adm'])?></td>
     <td><?=$result->fields['revalid_adm']?></td>
     <td><?=$result->fields['armado_autorizacion']?></td>
     <td><?=$result->fields['revalid_adm']?></td>
     <td><?=$result->fields['nota_ufis']?></td>
     <td><?=fecha($result->fields['dia_salida'])?></td>
     <td><?=fecha($result->fields['fecha_pago'])?></td>
     
    </tr>
	<?$result->MoveNext();
    }?>
 </table>
 </form>