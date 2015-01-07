<?php
function drawView(){
	global $mock;
	global $result;
	?>
	<table cellspacing=2 cellpadding=2 border=0 width=100% align=center >
		<tr >
			<td align=center class="table table-hover">
				<?//list($sql,$total_muletos,$link_pagina,$up) = form_busqueda($sql_tmp,$orden,$filtro,$link_tmp,$where_tmp,"buscar");?>
				&nbsp;&nbsp;<input type=submit name="buscar" value='Buscar'style="width:120px;height:30px">
				&nbsp;&nbsp;<input type='button' name="nueva_exd" value='Nuevo Efector' onclick="document.location='formulario_efector.php'" style="width:120px;height:30px">
				&nbsp;&nbsp;<? $link=encode_link("plan_listado_excel.php",array("cmd"=>$cmd));?>
				<b>Listado Completo: </b><img src="../../imagenes/excel.gif" style='cursor:hand;' title="Listado Completo" onclick="window.open('<?=$link?>')">
				&nbsp;&nbsp;<? $link=encode_link("plan_listado_excel1.php",array("cmd"=>$cmd));?>
				<b>Listado Simple: </b><img src="../../imagenes/excel.gif" style='cursor:hand;' title="Listado Simple" onclick="window.open('<?=$link?>')">
			</td>
		</tr>
	</table>
	
	<table class="table table-hover">
	  <tr>
	  	<td colspan=12 align=left id=ma>
	     <table width=100%>
	      <tr id=ma>
	       <td width=30% align=left><b>Total:</b> <?=$total_efectores?></td>       
	       <td width=40% align=right><?=$link_pagina?></td>
	      </tr>
	    </table>
	   </td>
	  </tr>
	  <tr>
	    <td align=right id=mo><a id=mo href='<?=encode_link("./view/v_listado_efectores.php",array("sort"=>"1","up"=>$up))?>' >Código</a></td>      	
	    <td align=right id=mo><a id=mo href='<?=encode_link("./view/v_listado_efectores.php",array("sort"=>"2","up"=>$up))?>' >Descripción</a></td>      	
		<td align=right id=mo><a id=mo href='<?=encode_link("./view/v_listado_efectores.php",array("sort"=>"2","up"=>$up))?>' >Provincia</a></td>      	
		<td align=right id=mo><a id=mo href='<?=encode_link("./view/v_listado_efectores.php",array("sort"=>"2","up"=>$up))?>' >Email</a></td>      	
		<td align=right id=mo><a id=mo href='<?=encode_link("./view/v_listado_efectores.php",array("sort"=>"2","up"=>$up))?>' >Telefono</a></td>      	
	  </tr>
		<?
		if($mock == false){
			while (!$result->EOF) {
				$ref = encode_link("./formulario_efector.php",array("id_efector"=>$result->fields['id_efector'],"pagina"=>"listado_efectores"));
				$onclick_elegir="location.href='$ref'";
				if (permisos_check(permisis_notof_celular,'')){}
		?>
		    <tr <?=atrib_tr()?>>     
		     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['login']?></td>
		     <td onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido'].', '.$result->fields['nombre']?></td>
			 <td onclick="<?=$onclick_elegir?>"><?=$result->fields['apellido']?></td>
			 <td onclick="<?=$onclick_otro?>"><?=$result->fields['apellido']?></td>
		    </tr>    
			<?$result->MoveNext();
			}
		} else {
			$i = 0;
			while($i < count($result)){
				$ref = encode_link("./formulario_efector.php",array("id_efector"=>$result[$i]['id_efector'],"pagina"=>"listado_efectores"));
				$onclick_elegir="location.href='$ref'";
		
				?>
				<tr <?=atrib_tr()?>>     
					<td onclick="<?=$onclick_elegir?>"><?=$result[$i]['id_efector']?></td>
					<td onclick="<?=$onclick_elegir?>"><?=$result[$i]['descripcion']?></td>
					<td onclick="<?=$onclick_elegir?>"><?=$result[$i]['provincia']?></td>
					<td onclick="<?=$onclick_elegir?>"><?=$result[$i]['email']?></td>
					<td onclick="<?=$onclick_elegir?>"><?=$result[$i]['telefono']?></td>
				</tr>    
				<?
				$i++;
			}
		}
		?>
	</table>
<?
}
?>