<?php
include_once("main.php");

$permissoes = array(
   array("perfil_usuario" => "admin,comum", "acao" => "acao_listar_participantes"),
   array("perfil_usuario" => "admin,comum", "acao" => "acao_cadastrar_pessoa"),
   array("perfil_usuario" => "admin,comum", "acao" => "acao_cadastrar_usuario"),
   array("perfil_usuario" => "admin", "acao" => "acao_importar_csv"),
   array("perfil_usuario" => "admin", "acao" => "acao_verificar_csv"),
);

$usuario = get_by_id($pdo,"Usuario","usuario",$_COOKIE['id_usuario']);
$perfil_usuario = ($usuario)?get_by_id($pdo,"PerfilUsuario","perfil_usuario",$usuario->perfil_usuario_id):null;
if ($perfil_usuario){
  foreach ($permissoes as $permissao){
    if (str_contains($permissao["perfil_usuario"],$perfil_usuario->nome)){
      echo "<a href='#' onclick='".$permissao['acao']."()'>".$permissao['acao']."</a><br>";
    }
  }
}

?>
