<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(!isset($usuario_cookie)) die("Voce deve estar logado!");

include_once("main.php");

$usuarios = get_all($pdo,"Usuario","usuario");
$select_usuario = "<select name='usuario'>";
foreach ($usuarios as $usuario){
  $select_usuario .= "<option value=".$usuario->id.">".$usuario->nome_usuario."</option>";
}
$select_usuario .="</select>";

?>

<form id="form_trocar_senha_usuario" method="post" action="acao_trocar_senha_usuario.php">
 <p>Usuário: <?php echo $select_usuario; ?></p>
 <p>Nova Senha: <input type="password" name="nova_senha" /></p>
 <p>Confirmar Nova Senha: <input type="password" name="confirmar_nova_senha" /></p>
 <a class="botao botao_acao" href="#" onclick="acao_trocar_senha_usuario()">Atualizar</a>
</form>
<br>

<?php
$id_usuario = isset($_GET['usuario'])?$_GET['usuario']:null;
$nova_senha = isset($_GET['nova_senha'])?$_GET['nova_senha']:null;
$confirmar_nova_senha = isset($_GET['confirmar_nova_senha'])?$_GET['confirmar_nova_senha']:null;
if ($nova_senha==$confirmar_nova_senha and $nova_senha!=null){
  $nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
  $stmt = $pdo->prepare("UPDATE usuario set senha=? where id=?;");
  $stmt->execute([$nova_senha, $id_usuario]);
}else{
  if ($nova_senha!=null){
    echo "Senhas nao conferem!";
  }
}
?>

