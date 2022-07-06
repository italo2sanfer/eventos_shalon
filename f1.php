<?php

include_once("main.php");

$funcao = isset($_POST['funcao'])?$_POST['funcao']:null;

if ($funcao){
  $funcaop = explode("_",$funcao);
  if ($funcaop[0] == "crud"){
    $classe = ucfirst($funcaop[1]);
    $funcao_ = implode("_",array_slice($funcaop,2,count($funcaop)));
    $_classe_admin = $classe."Admin";
    $classe_admin = new $_classe_admin();
    echo $classe_admin->$funcao_($pdo,$_POST);
  }elseif ($funcaop[0] == "sistema"){
    $funcao_ = implode("_",array_slice($funcaop,1,count($funcaop)));
    $_classe_admin = "SistemaAdmin";
    $classe_admin = new $_classe_admin();
    echo $classe_admin->$funcao_($pdo,$_POST);
  }
}

## Function without argument 
#function func() {
#    echo "hello ";
#}
## Function with argument
#function fun($msg) {
#    echo $msg." ";
#}
#$var = "func";
#$var1 = "fun"; 
## 1st method by using variable name
#$var();
#$var1("geek");  
#echo "\n"; 
## 2nd method by using php inbuilt
## function call_user_func()
#call_user_func($var);
#call_user_func($var1, "fun_function");


?>
