<?php include_once("main.php"); ?>
<html>
<head>
  <title>CheckList Convidados</title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script type="text/javascript" src="main.js"></script>
  <link rel="stylesheet" type="text/css" href="main.css">    
</head>
<body>
<div class="cabecalho_body">
<?php

$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(isset($usuario_cookie)){
  echo "
    <div class='cabecalho_body_1' >
      <img height='50' size='50'  src='media/imgs/home.png' alt='Minha Figura' onclick='window.location.reload();'>
    </div>";
  echo"
    <div class='cabecalho_body_2'>
      <span>Bem-Vindo, $usuario_cookie!</span>&nbsp;&nbsp;
      <img height='50' size='50'  src='media/imgs/sair.png' alt='Minha Figura' onclick='deslogar();'>
    </div>";
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
