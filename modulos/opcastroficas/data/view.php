<?php
/*
Author: JAB

modificada por
$Author: JAB $
$Revision: 1.0 $
$Date: 2015/01/06 $
*/
function drawView(){
	global $mock;
	global $result;
	?>
	<table border=0 width=50% cellspacing=2 cellpadding=2 bgcolor='<?=$bgcolor3?>' align=center>
	  <tr>
	  	<td colspan=12 align=left id=ma>
	     <table width=100%>
	      <tr id=ma>
	       <td width=30% align=left><b>Total:</b> <?=$total_pais?></td>       
	       <td width=40% align=right><?=$link_pagina?></td>
	      </tr>
	    </table>
	   </td>
	  </tr>
	  <tr>
	    <td align=right id=mo><a id=mo href='<?=encode_link("listado.php",array("sort"=>"1","up"=>$up))?>' >Login</a></td>      	
	    <td align=right id=mo><a id=mo href='<?=encode_link("listado.php",array("sort"=>"2","up"=>$up))?>' >Nombre y Apellido</a></td>      	
		<td align=right id=mo><a id=mo href='<?=encode_link("listado.php",array("sort"=>"2","up"=>$up))?>' >Apellido</a></td>      	
		<td align=right id=mo><a id=mo href='<?=encode_link("listado.php",array("sort"=>"2","up"=>$up))?>' >Acciones</a></td>      	
	  </tr>
		<?
		if($mock == false){
			while (!$result->EOF) {
		   		$ref = encode_link("form_op.php",array("id_usuario"=>$result->fields['id_usuario'],"pagina"=>"usr_areas_listado"));
		    	$onclick_elegir="location.href='$ref'";
				$onclick_otro = "alert('hadooken')";
		   	?>
			<? if (permisos_check(permisis_notof_celular,'')){
			}?>
		  
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
				?>
				<tr <?=atrib_tr()?>>     
					<td onclick="#"><?=$result[$i]['CoCuenta']?></td>
					<td onclick="#">dasdasd</td>
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