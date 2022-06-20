<?php
include_once("main.php");
setcookie('nome_usuario', null, -1);
setcookie('id_usuario', null, -1);
echo json_encode(array("retorno"=>"Deslogado."));

?>
