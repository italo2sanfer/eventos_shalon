<?php
class Evento{
  public $id; public $nome;
  public $descricao; public $local;
  public $endereco; public $aberto;
}
class TipoInstituicao{
  public $id; public $descricao;
}
class Instituicao{
  public $id; public $tipo_instituicao_id;
  public $nome; public $nome_responsavel;
  public $endereco;
}
class Pessoa{
  public $id; public $cpf;
  public $nome; public $email;
  public $telefone_contato;
}
class Papel{
  public $id; public $descricao;
}
class Participante{
  const SITUACAO_PRESENTE = 'presente';
  const SITUACAO_AUSENTE = 'ausente';
  public $id; public $evento_id;
  public $pessoa_id; public $papel_id;
  public $instituicao_id; public $situacao;
}
class PerfilUsuario{
  const ADMIN = 'admin';
  const COMUM = 'comum';
  public $id; public $nome;
  public $descricao;
}
class Usuario{
  public $id; public $nome_usuario;
  public $senha; public $pessoa_id;
  public $perfil_usuario_id;
}

define('PLANILHA_IMPORTACAO', 'media/planilha_20220618.csv');

$host = "localhost"; $dbname = "eventos";
$user = "root"; $password = "senhademariadb";
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
$options = [
  PDO::ATTR_EMULATE_PREPARES   => false, // Disable emulation mode for "real" prepared statements
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Make the default fetch be an associative array
];
try {
  $pdo = new PDO($dsn, $user, $password, $options);
} 
catch (Exception $e) {
  error_log($e->getMessage());
  exit('Something bad happened'); 
}


function get_by_id($pdo,$classe,$tabela, $id){
  $objeto = $pdo->query( "SELECT * FROM $tabela where id=$id;")->fetchAll(PDO::FETCH_CLASS, $classe);
  if (count($objeto)>0){
    return $objeto[0];
  }
  return null;
}
function get_by_fields($pdo,$classe,$tabela, $fields){
  $condicoes = array();
  foreach ($fields as $field){
    $valor = ($field['str']=="sim")?("'".$field['valor']."'"):$field['valor'];
    array_push($condicoes,(" ".$field['nome']."=$valor "));
  }
  $were = implode(" and ",$condicoes);
  $objeto = $pdo->query( "SELECT * FROM $tabela where $were;")->fetchAll(PDO::FETCH_CLASS, $classe);
  if (count($objeto)>0){
    return $objeto[0];
  }
  return null;
}
function get_all($pdo,$classe,$tabela){
  $objetos = $pdo->query( "SELECT * FROM $tabela;")->fetchAll(PDO::FETCH_CLASS, $classe);
  if (count($objetos)>0){
    return $objetos;
  }
  return null;
}
function insert_object($pdo,$classe,$tabela,$fields){
  $fields_insert = array();
  $values_insert = array();
  foreach ($fields as $field){
    $valor = ($field['str']=="sim")?("'".$field['valor']."'"):$field['valor'];
    array_push($fields_insert ,$field['nome']);
    array_push($values_insert ,$valor);
  }
  $fields_insert_vai = implode(",",$fields_insert);
  $fields_values_vai = implode(",",$values_insert);
  $stmt = $pdo->prepare("INSERT INTO pessoa($fields_insert_vai) VALUES($fields_values_vai)");
  $stmt->execute();
  return $pdo->lastInsertId();
}

function get_selection($pdo,$classe,$tabela){
  $objetos = get_all($pdo,$classe,$tabela);
  $select_objeto = "<select name='$tabela'>";
  foreach ($objetos as $objeto){
    $select_objeto .= "<option value=".$objeto->id.">".$objeto->nome."</option>";
  }
  $select_objeto .="</select>";
  return $select_objeto;
}








?>
