<?php

require_once("../../config.php");
variables_form_busqueda("listado_memo");

$orden = array(
        "default" => "1",
		    "default_up" => "0",
        "1" => "id_memo",

       );
$filtro = array(
		    "id_memo" => "Nro memo",
       );


$sql_tmp="SELECT * FROM registro.memo";

echo $html_header;?>

<form name=form1 action="listado_memo.php" method=POST>
<table cellspacing=2 cellpadding=2 border=0 width=100% align=center>
     <tr>
      <td align=center>
		<?list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
	    &nbsp;&nbsp;<input type=submit name="buscar" value='Buscar' style='width:130px;height:25px'>
	  </td>
     </tr>
</table>

<?$result = sql($sql) or die;?>

<table border=0 width=100% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align='center'>
  <tr>
  	<td colspan=12 align=left id='ma'>
     <table width='100%'>
      <tr id='ma'>
       <td width=30% align=left><b>Total:</b> <?=$total_muletos?></td>       
       <td width=40% align=right><?=$link_pagina?></td>
      </tr>
    </table>
   </td>
  </tr>
  

  <tr>
    <td align=right id='mo'><a id=mo href='<?=encode_link("listado_memo.php",array("sort"=>"1","up"=>$up))?>'>Numero</a></td>        
    <td align=right id='mo'>Origen</td>
    <td align=right id='mo'>Registro</td>       
    <td align=right id='mo'>Fecha</td>       
    <td align=right id='mo'>Monto</td>       
  </tr>
 <?
   while (!$result->EOF) {
	$ref = encode_link("./memo.php", array("pagina_viene"=>"listado_memo.php","id_memo" =>$result->fields["id_memo"],"id_registro" =>$result->fields["id_registro"]));
   	$onclick_elegir="location.href='$ref'";?>

    <tr <?=atrib_tr()?>>     
     <td align="center" onclick="<?=$onclick_elegir?>"><?='RG-SGC N MI'.$result->fields['id_memo'].'_02'?></td>
        <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['origen']?></td>
        <td align="center" onclick="<?=$onclick_elegir?>"><?=$result->fields['registro']?></td>        
        <td align="center" onclick="<?=$onclick_elegir?>"><?=fecha($result->fields['fecha'])?></td>        
        <td align="center" onclick="<?=$onclick_elegir?>"><?=number_format($result->fields['monto'],2,',','.')?></td>    
    </tr>
	<?$result->MoveNext();
    }?>    
</table>
<br>
</form>
</body>
</html>
<?echo fin_pagina();// aca termino ?>
