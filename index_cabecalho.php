<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(isset($usuario_cookie)){
  echo "
    <div class='cabecalho_1' >
      <img height='50' size='50'  src='media/imgs/home.png' alt='Minha Figura' onclick='window.location.reload();'>
    </div>
    <div class='cabecalho_2'>
      <span>Bem-Vindo(a), $usuario_cookie!</span>&nbsp;&nbsp;
      <img height='50' size='50'  src='media/imgs/sair.png' alt='Minha Figura' onclick='sistema_deslogar();'>
    </div>";
}else{
  echo "
    <div class='cabecalho_1' ></div>
    <div class='cabecalho_2'>
      <span>
        <a class='botao botao_acao' href='#' onclick='sistema_logar_form(\"sistema_logar_form\")'>Login</a>
      </span>
    </div>";
}
?>
