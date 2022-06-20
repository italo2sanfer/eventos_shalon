<form action="#" method="post">
 <p>Nome: <input type="text" name="nome" /></p>
 <p>CPF: <input type="text" name="cpf" /></p>
 <p>Email: <input type="text" name="email" /></p>
 <p>Telefone de contato: <input type="text" name="telefone_contato" /></p>
 <hr>
 <p>Perfil de usuario: <input type="text" name="perfil_usuario" /></p>
 <p>Nome de usuario: <input type="text" name="nome_usuario" /></p>
 <p>Senha: <input type="password" name="senha" /></p>
 <p>Confirmar Senha: <input type="password" name="confirmar_senha" /></p>
 <p><input type="submit" /></p>
</form>
<hr>

<?php

include_once("main.php");

$nome = isset($_POST['nome'])?$_POST['nome']:null;
$cpf = isset($_POST['cpf'])?$_POST['cpf']:null;
$email = isset($_POST['email'])?$_POST['email']:null;
$telefone_contato = isset($_POST['telefone_contato'])?$_POST['telefone_contato']:null;
$nome_usuario = isset($_POST['nome_usuario'])?$_POST['nome_usuario']:null;
$senha = isset($_POST['senha'])?$_POST['senha']:null;
$perfil_usuario = isset($_POST['perfil_usuario'])?$_POST['perfil_usuario']:null;
$confirmar_senha = isset($_POST['confirmar_senha'])?$_POST['confirmar_senha']:null;

if ($senha==$confirmar_senha and $senha!=null){
  $pessoas = $pdo->query( "SELECT id FROM pessoa where cpf='$cpf' and email='$email';")->fetchAll(PDO::FETCH_CLASS, 'Pessoa');
  $id_pessoa = null;
  if (count($pessoas)==0){
    $stmt = $pdo->prepare("INSERT INTO pessoa(cpf, nome, email, telefone_contato) VALUES(?,?,?,?)");
    $stmt->execute([$cpf, $nome, $email, $telefone_contato]);
    $id_pessoa = $pdo->lastInsertId();
      echo "Adicionando Pessoa ...  Adicionada ... ";
  }else{
    $id_pessoa = $pessoas[0]->id;
    echo "Adicionando Pessoa ... Ja existente ... ";
  }
  echo "id=$id_pessoa <br>";

  $perfil_usuario = $pdo->query( "SELECT * FROM perfil_usuario where nome='".$perfil_usuario."';")->fetchAll(PDO::FETCH_CLASS, 'PerfilUsuario');
  $id_perfil_usuario = $perfil_usuario[0]->id;

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

