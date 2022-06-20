<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(!isset($usuario_cookie)) die("Voce deve estar logado!");

include_once("main.php");
setcookie('nome_usuario', null, -1);
setcookie('id_usuario', null, -1);
echo json_encode(array("retorno"=>"Deslogado."));

?>
