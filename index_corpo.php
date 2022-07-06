<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(isset($usuario_cookie)){
  include_once("main.php");
  $permissoes = array(
     array("perfil_usuario"=>"admin,comum", "acao"=>"crud_participante_listar1_form", "label"=>"Listar Participantes"),
     array("perfil_usuario"=>"admin", "acao"=>"crud_pessoa_adicionar_form", "label"=>"Cadastrar Pessoa"),
     array("perfil_usuario"=>"admin", "acao"=>"crud_usuario_adicionar_form", "label"=>"Cadastrar Usuário"),
     array("perfil_usuario"=>"admin", "acao"=>"crud_participante_adicionar1_form", "label"=>"Cadastrar Participante"),
     array("perfil_usuario"=>"admin", "acao"=>"crud_usuario_trocar_senha_form", "label"=>"Trocar Senha de Usuário"),
     array("perfil_usuario"=>"admin", "acao"=>"sistema_csv_importar", "label"=>"Importar CSV"),
     array("perfil_usuario"=>"admin", "acao"=>"sistema_csv_verificar", "label"=>"Verificar CSV"),
  );
  $usuario = get_by_id($pdo,"Usuario","usuario",$_COOKIE['id_usuario']);
  $perfil_usuario = ($usuario)?get_by_id($pdo,"PerfilUsuario","perfil_usuario",$usuario->perfil_usuario_id):null;
  if ($perfil_usuario){
    foreach ($permissoes as $permissao){
      $perfil_usuario_array = explode(",",$permissao["perfil_usuario"]);
      if (in_array($perfil_usuario->nome,$perfil_usuario_array)){
        echo "<a class='botao botao_acao' href='#' onclick='f1(\"".$permissao['acao']."\")'>".$permissao['label']."</a>";
        echo "&nbsp;&nbsp;&nbsp;";
      }
    }
  }
  /*if ($usuario->perfil_usuario_id==1){
    echo "<a class='botao botao_acao' href='#' onclick='f1(\"crud_participante_listar1_form\")'>Listar Participantes M1 2</a>";
    echo "<a class='botao botao_acao' href='#' onclick='f1(\"crud_usuario_adicionar_form\")'>Cadastrar Usuario 2</a>";
    echo "<a class='botao botao_acao' href='#' onclick='f1(\"crud_usuario_trocar_senha_form\")'>Trocar Senha de Usuário 2</a>";
    echo "<a class='botao botao_acao' href='#' onclick='f1(\"crud_pessoa_adicionar_form\")'>Cadastrar Pessoa 2</a>";
    echo "<a class='botao botao_acao' href='#' onclick='f1(\"sistema_csv_importar\")'>Importar CSV 2</a>";
    echo "<a class='botao botao_acao' href='#' onclick='f1(\"sistema_csv_verificar\")'>Verificar CSV 2</a>";
  }*/
}
///else{
//  include "sistema_logar.php";
//}
?>
