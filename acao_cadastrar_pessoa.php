<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(!isset($usuario_cookie)) die("Voce deve estar logado!");
?>

<form id="form_cadastrar_pessoa" method="post" action="acao_cadastrar_pessoa.php">
 <p>Nome: <input type="text" name="nome" /></p>
 <p>CPF: <input type="text" name="cpf" /></p>
 <p>Email: <input type="text" name="email" /></p>
 <p>Telefone de contato: <input type="text" name="telefone_contato" /></p>
 <a class="botao botao_acao" href="#" onclick="acao_cadastrar_pessoa()">Cadastrar</a>
</form>
<br>

<?php

include_once("main.php");

$nome = isset($_GET['nome'])?$_GET['nome']:null;
$cpf = isset($_GET['cpf'])?$_GET['cpf']:null;
$email = isset($_GET['email'])?$_GET['email']:null;
$telefone_contato = isset($_GET['telefone_contato'])?$_GET['telefone_contato']:null;

if ($nome!=null and $cpf!=null and $email!=null){
  $verificar = array(
     array("nome"=>"cpf", "valor"=>$cpf, "str"=>"sim"),
     array("nome"=>"email", "valor"=>$email, "str"=>"sim"),
  );
  $inserir = array(
     array("nome"=>"nome", "valor"=>$nome, "str"=>"sim"),
     array("nome"=>"cpf", "valor"=>$cpf, "str"=>"sim"),
     array("nome"=>"email", "valor"=>$email, "str"=>"sim"),
     array("nome"=>"telefone_contato", "valor"=>$telefone_contato, "str"=>"sim"),
  );
  $pessoa = get_by_fields($pdo,"Pessoa","pessoa",$verificar);
  if (!$pessoa){
    $id_pessoa = insert_object($pdo,"Pessoa","pessoa",$inserir); 
    echo "Pessoa cadastrada com sucesso!";
  }else{
    echo "Já existe uma pessoa com esse cpf e e-mail.";
  }
}

?>

