<div class="login">
  <form method="POST" action="#">
    <label>Usuario:</label><br><input type="text" name="usuario" id="usuario"><br>
    <label>Senha:</label><br><input type="password" name="senha" id="senha"><br><br>
    <input class="submit_login" type="submit" value="Entrar" id="entrar" name="entrar"><br>
  </form>
</div>
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


