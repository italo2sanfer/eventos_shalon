<form method="POST" action="#">
<label>Usuario:</label><input type="text" name="usuario" id="usuario"><br>
<label>Senha:</label><input type="password" name="senha" id="senha"><br>
<input type="submit" value="entrar" id="entrar" name="entrar"><br>
</form>
<?php
include_once("main.php");

$usuario = isset($_POST['usuario'])?$_POST['usuario']:null;
$entrar = isset($_POST['entrar'])?$_POST['entrar']:null;
$senha = isset($_POST['senha'])?$_POST['senha']:null;

if (isset($entrar)) {
  $usuario = $pdo->query( "SELECT * FROM usuario where nome_usuario='$usuario';")->fetchAll(PDO::FETCH_CLASS, 'Usuario');
  if (count($usuario)>0){
    if (password_verify($senha, $usuario[0]->senha)){
      setcookie("nome_usuario",$usuario[0]->nome_usuario);
      setcookie("id_usuario",$usuario[0]->id);
      header("Location: index.php");
    } else {
      echo 'Senha invalida!';
    }
  }else{
    echo "Usuario nao encontrado.";
  }
}
?>


