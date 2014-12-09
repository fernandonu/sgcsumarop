<?

if (ereg("/login.php",$_SERVER["SCRIPT_NAME"])) {
	$tmp=explode("/login.php",$_SERVER["SCRIPT_NAME"]);
	$html_root = $tmp[0];
}
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Programa Sumar</title>
<style type="text/css">
<!--
body {
	background-image: url(imagenes/fondo.jpg);
	background-repeat: repeat-x;
	background-attachment:fixed;
	background-color: #E7E7E7;
}
.Fecha {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #999999;
}
.Area {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #009ADF;
}
.Aviso {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 9px;
	color: #990000;
}
a:link {
	color: #FFFFFF;
	text-decoration: none;
}
a:visited {
	color: #FFFFFF;
	text-decoration: none;
}
a:hover {
	text-decoration: none;
	color: #333333;
}
a:active {
	text-decoration: none;
	color: #FFFFFF;
}
.Calendario{
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #333333;
}
.Calendario2{
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	font-weight:bold;
	color: #333333;
}
.GrisClaro{
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #999999;
}
.Grande {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 16px;
	color: #666666;
}
.Grande2 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #009ADF;
	font-weight: bold;
}
.Grande3 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #666666;
	font-weight: bold;
}
.Cargo {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #009ADF;
	font-weight: bold;
}
.Cargo2 {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #FFFFFF;
	font-weight: bold;
}
.Texto {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	color: #333333;
}
.TituloBlanco {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 14px;
	font-weight: bold;
	color: #FFFFFF;
}
.TextoBlanco {
	font-family: Geneva, Arial, Helvetica, sans-serif;
	font-size: 10px;
	font-weight: bold;
	color: #FFFFFF;
}
.Estilo1 {font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 16px; color: #009ADF; font-weight: bold; }
-->
</style>
<head>
<link rel="icon" href="<? echo ((($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST']).$html_root; ?>/favicon.ico">
<link REL='SHORTCUT ICON' HREF='<? echo ((($_SERVER['HTTPS'])?"https":"http")."://".$_SERVER['HTTP_HOST']).$html_root; ?>/favicon.ico'>

<link type='text/css' href='<? echo $html_root; ?>/lib/estilos.css' REL='stylesheet'>
</head>


<body style="overflow:hidden;" onLoad="javascript: document.frm.username.focus();" topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<form action='index.php' method='post' name='frm'>
<input type="hidden" name="resolucion_ancho" value="">
<input type="hidden" name="resolucion_largo" value="">


<table width="100" border="0" align="center" cellpadding="0" cellspacing="2">
  <tr>
    <td height="528" align="center"><table width="1000" height="528" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td width="8">&nbsp;</td>
        <td width="800" valign="bottom"><img src="imagenes/somb.png" width="100%" height="11" /></td>
        <td width="8">&nbsp;</td>
      </tr>
      <tr>
        <td rowspan="3" align="right" valign="top"><img src="imagenes/somb2.png" width="8" height="500" /></td>
        <td background="imagenes/gris.jpg" bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="20" cellpadding="0">
            <tr>
              <td width="71" align="center"><a href="http://www.msal.gov.ar/sumar/"><img src="imagenes/logo_sumar.png" alt="Programa SUMAR" width="71" height="71" border="0" /></a></td>
              <td width="504" align="center"><div align="left"><span class="Grande2"><b>Sistema de Gestion de la Calidad.</b></span></div></td>
              <td width="87" align="center"><a href="http://www.plannacer.msal.gov.ar/"><img src="imagenes/logo_plan-nacer.png" alt="Plan Nacer" width="87" height="55" border="0" /></a></td>
              <td width="218" align="right"><a href="http://www.msal.gov.ar/"><img src="imagenes/logo_msal.png" alt="Ministerio de Salud de la Nación" width="160" height="55" border="0" /></a></td>
              </tr>
        </table></td>
        <td rowspan="3" align="left" valign="top"><img src="imagenes/somb3.png" width="8" height="500" /></td>
      </tr>
      <tr>
        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="1%" background="imagenes/barra.gif" bgcolor="#4396DE"><img src="imagenes/barra.gif" width="2" height="33" /></td>
            <td width="99%" align="right" background="imagenes/barra.gif" bgcolor="#4396DE">&nbsp;</td>
          </tr>
        </table></td>
        </tr>
      
	  <tr>
        <td valign="top" bgcolor="#FFFFFF"><table width="983" border="0" cellspacing="0" cellpadding="0">
		<form method="POST" action="--WEBBOT-SELF--">
          <tr>
            <td width="462" valign="top"><img src="http://programasumar.com.ar/banner/bannergc.jpg" width="460" height="342" /></td>
            <td width="521" align="center"><table width="0" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" valign="middle" nowrap="nowrap">&nbsp;</td>
              </tr>
              <tr>
                <td nowrap="nowrap"><div align="center" class="Estilo1">
                  <div align="left">Ingrese su usuario y contraseña <font color=red> </font></div>
                </div></td>
              </tr>
              <tr>
                <td nowrap="nowrap" class="Estilo1">. . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . </td>
              </tr>
              <tr>
                <td nowrap="nowrap" class="Estilo1">&nbsp;</td>
              </tr>
            </table>
              <table border="0" align="center" cellpadding="0" cellspacing="15">
              <tr>
                <td class="Texto"><img src="imagenes/usr.png" alt="Usuario:" width="32" height="32" /></td>
                <td align="left" valign="middle" class="Titulo"><input name="username" type="text" class="Texto" size="10" /></td>
              </tr>
              <tr>
                <td class="Texto"><img src="imagenes/contrasena.png" alt="Contraseña:" width="32" height="32" /></td>
                <td align="left" valign="middle" class="Titulo"><input name="password" type="password" class="Texto" size="10" /></td>
              </tr>
              <tr>
                <td colspan="2" align="center" class="Titulo"><div align="center">
                    <input type="submit" name="loginform" class="Grande2" value="Ingresar" />
                </div></td>
              </tr>
			</form>
            </table></td>
          </tr>
        </table></td>
        </tr>
		
    </table>
    </td>
  </tr>
</table>


<script>
//guardamos la resolucion de la pantalla del usuario en los hiddens para despues recuperarlas
//y guardarlas en las variable de sesion $_ses_user
document.all.resolucion_ancho.value=screen.width;
document.all.resolucion_largo.value=screen.height;

</script>
</body>
</form>
</html>
