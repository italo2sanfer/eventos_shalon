<?php
include_once("main.php");

$id_participante = $_POST['id_participante'];
$situacao_final = $_POST['situacao_final'];

$stmt = $pdo->prepare("UPDATE participante SET situacao = ? where id=?;");
$stmt->execute([$situacao_final, $id_participante]);

echo json_encode(array("retorno"=>"Participante $situacao_final."));

?>
