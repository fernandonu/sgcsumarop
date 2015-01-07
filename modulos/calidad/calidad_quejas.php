<?php
require_once("../../config.php");
echo $html_header;
echo "<link rel=stylesheet type='text/css' href='$html_root/lib/bootstrap-3.3.1/css/custom-bootstrap.css'>";
cargar_calendario();

if ($_POST['guardar_queja']=='Guardar Queja'){
	$fech=date("Y-m-d H:i:s",mktime()); //fecha actual
	$query_insert="insert into Quejas (fecha,nbre_cl,mail,descripcion,tipo_queja) values ('".fecha_db($_POST['fecha_queja'])."','".$_POST['nombre_cl']."','".$_POST['mail']."','".$_POST['descripcion']."','".$_POST['tipo']."') ";
	sql($query_insert) or fin_pagina();
	$query_max="select id_queja from Quejas where fecha='".fecha_db($_POST['fecha_queja'])."' and nbre_cl='".$_POST['nombre_cl']."' and mail='".$_POST['mail']."' and descripcion='".$_POST['descripcion']."' and tipo_queja='".$_POST['tipo']."'";
	$res=sql($query_max) or fin_pagina();
	$id_queja=$res->fields['id_queja'];
	$query_insert="insert into log_quejas (usuario,fecha_log,id_queja,tipo) values ('".$_ses_user['name']."','$fech',$id_queja,'insert')";
	sql ($query_insert) or fin_pagina();

	if ($_POST['tipo']=='Queja'){?>
		<script type="text/javascript">
			alert('Recuerde Generar un Producto No Conforme de la presente Queja');
			location.href='listar_quejas.php';
		</script>
	<?}
	else {?>
		<script type="text/javascript">
			location.href='listar_quejas.php';
		</script>
	<?}
}

?>
<div  class="newstyle-full-container">
<form name="form_queja" action="calidad_quejas.php" method="post">
<? 
if ($parametros) {
$query="select * from Quejas join log_quejas using(id_queja) where Quejas.id_queja=".$parametros['id_queja'];
$result=sql($query) or fin_pagina();
}
?>

<legend>Queja/Consulta</legend> 
<div class="row-fluid">
		<div class="span3">
			<label>Numero:</label>
			<?=$result->fields['id_queja'] ?>		
		</div>		

		<div class="span3">
			<label>Fecha:</label>
			<input name="fecha_queja" type="text" value="<?=Fecha($result->fields['fecha']) ?>">&nbsp;&nbsp;
			<?php echo link_calendario("fecha_queja"); ?>
		</div>
		<div class="span3">
			<label>Nombre Cliente:</label>
			<input name="nombre_cl" type="text" size="50" value=<?=$result->fields['nbre_cl'] ?>>
		</div>
</div>
<div class="row-fluid">
		<div class="span3">
			<label>Sector al que Pertenece:</label>
			<input name="mail" type="text" size="50" value="<?=$result->fields['mail'] ?>">		
		</div>		

		<div class="span3">
			<label>Descripción:</label>
			<textarea name="descripcion" cols="60" rows="4"><?=$result->fields['descripcion'] ?></textarea>
		</div>
		<div class="span3">
			<label>Tipo: </label>
			<select name="tipo">
			   <option value='Queja' <? if ($result->fields['tipo_queja'] == 'Queja') echo 'selected';?>>Queja</option>
			   <option value='Consulta' <? if ($result->fields['tipo_queja'] == 'Consulta') echo 'selected';?> >Consulta</option>
			  </select>
		</div>
</div>

<div class="form-actions">
<? if ($parametros) {?>
<input class="btn btn-info btn-large" name="Volver" type="button" value="Volver" Onclick="location.href='listar_quejas.php'">
<? } else {?>
<input class="btn btn-primary" name="guardar_queja" type="submit" value="Guardar Queja">
<? } ?>
</div>

</form>
</div>