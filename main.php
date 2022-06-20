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
  public $id;
  public $senha;
  public $pessoa_id;
  public $perfil_usuario_id;
}

define('PLANILHA_IMPORTACAO', 'planilha_20220618.csv');

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






?>
