<?php include_once("main.php"); ?>
<html>
<head>
  <title>CheckList Convidados</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script type="text/javascript" src="main.js"></script>
  <link rel="stylesheet" type="text/css" href="main.css">    
</head>
<body>
<div style="text-align:right;" name="cabecalho" id="cabecalho">
<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(isset($usuario_cookie)){
  echo"Bem-Vindo, $usuario_cookie!";
  echo "&nbsp;<a href='#' onclick='window.location.reload()'>Inicio</a>";
  echo "&nbsp;<a href='#' onclick='deslogar()'>Sair</a>";
  echo "<hr>";
}
?>
</div>
<div name="corpo" id="corpo">
<?php
$acao = (isset($usuario_cookie))?"acao.php":"login.php";
include $acao;
?>
</div>
</body>
</html>
