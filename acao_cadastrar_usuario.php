<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(!isset($usuario_cookie)) die("Voce deve estar logado!");

include_once("main.php");

?>

<form id="form_cadastrar_usuario" method="post" action="acao_cadastrar_usuario.php">
 <p>Pessoa: <?php echo get_selection($pdo,"Pessoa","pessoa"); ?></p>
 <p>Perfil de usuário: <?php echo get_selection($pdo,"PerfilUsuario","perfil_usuario"); ?></p>
 <p>Nome de usuário: <input type="text" name="nome_usuario" /></p>
 <p>Senha: <input type="password" name="senha" /></p>
 <p>Confirmar Senha: <input type="password" name="confirmar_senha" /></p>
 <a class="botao botao_acao" href="#" onclick="acao_cadastrar_usuario()">Cadastrar</a>
</form>
<br>

<?php

$id_pessoa = isset($_GET['pessoa'])?$_GET['pessoa']:null;
$nome_usuario = isset($_GET['nome_usuario'])?$_GET['nome_usuario']:null;
$senha = isset($_GET['senha'])?$_GET['senha']:null;
$id_perfil_usuario = isset($_GET['perfil_usuario'])?$_GET['perfil_usuario']:null;
$confirmar_senha = isset($_GET['confirmar_senha'])?$_GET['confirmar_senha']:null;

if ($senha==$confirmar_senha and $senha!=null){
  $senha = password_hash($senha, PASSWORD_DEFAULT);
  $usuario = $pdo->query( "SELECT * FROM usuario where pessoa_id=$id_pessoa and perfil_usuario_id=$id_perfil_usuario;")->fetchAll(PDO::FETCH_CLASS, 'Usuario');
  $id_usuario = null;
  if (count($usuario)==0){
    $stmt = $pdo->prepare("INSERT INTO usuario(nome_usuario, senha, pessoa_id, perfil_usuario_id) VALUES(?,?,?,?)");
    $stmt->execute([$nome_usuario, $senha, $id_pessoa, $id_perfil_usuario]);
    $id_usuario = $pdo->lastInsertId();
      echo "Adicionando Usuario ...  Adicionado ... ";
  }else{
    $id_usuario = $usuario[0]->id;
    echo "Adicionando Usuario ... Ja existente ... ";
  }
  echo "id=$id_usuario <br>";

}else{
  if ($senha!=null){
    echo "Senhas nao conferem!";
  }
}


?>

