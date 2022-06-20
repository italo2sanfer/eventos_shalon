<?php
include_once("main.php");

$permissoes = array(
   array("perfil_usuario"=>"admin,comum", "acao"=>"acao_listar_participantes", "label"=>"Listar Participantes"),
   array("perfil_usuario"=>"admin,comum", "acao"=>"acao_cadastrar_usuario", "label"=>"Cadastrar Usuario"),
   array("perfil_usuario"=>"admin", "acao"=>"acao_csv_importar", "label"=>"Importar CSV"),
   array("perfil_usuario"=>"admin", "acao"=>"acao_csv_verificar", "label"=>"Verificar CSV"),
);

$usuario = get_by_id($pdo,"Usuario","usuario",$_COOKIE['id_usuario']);
$perfil_usuario = ($usuario)?get_by_id($pdo,"PerfilUsuario","perfil_usuario",$usuario->perfil_usuario_id):null;
if ($perfil_usuario){
  foreach ($permissoes as $permissao){
    if (str_contains($permissao["perfil_usuario"],$perfil_usuario->nome)){
      echo "<a class='botao botao_acao' href='#' onclick='".$permissao['acao']."()'>".$permissao['label']."</a>
        &nbsp;&nbsp;&nbsp;";
    }
  }
}

?>
