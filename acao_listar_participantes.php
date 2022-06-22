<?php
$usuario_cookie = isset($_COOKIE['nome_usuario'])?$_COOKIE['nome_usuario']:null;
if(!isset($usuario_cookie)) die("Voce deve estar logado!");
?>

<form id="form_participantes" method="POST" action="acao_listar_participantes.php">
<label>Instituicao/Igreja:</label><input type="text" name="instituicao_busca" id="instituicao_busca"><br>
<label>Nome da pessoa:</label><input type="text" name="nome_busca" id="nome_busca"><br>
<a class="botao botao_acao" href="#" onclick="acao_listar_participantes()">Buscar</a>

</form>
<?php
include_once("main.php");

$nome_busca = isset($_GET['nome_busca'])?$_GET['nome_busca']:null;
$instituicao_busca = isset($_GET['instituicao_busca'])?$_GET['instituicao_busca']:null;

$where_busca = ($nome_busca)?" AND pe.nome like '%$nome_busca%'":"";
$where_busca = $where_busca.(($instituicao_busca)?" AND ins.nome like '%$instituicao_busca%'":"");

$descricao_papel = 'convidado'; $id_evento = 1;
$papel = $pdo->query("SELECT * FROM papel where descricao='$descricao_papel';")->fetchAll(PDO::FETCH_CLASS, 'Papel');
$evento = $pdo->query("SELECT * FROM evento where id=$id_evento;")->fetchAll(PDO::FETCH_CLASS, 'Evento');
$query_participantes = "SELECT pa.* ".
  "FROM participante pa, pessoa pe, instituicao ins ".
  "where pa.evento_id=".$evento[0]->id." and ".
  "  pa.papel_id=".$papel[0]->id." and ".
  "  pa.instituicao_id=ins.id and ".
  "  pa.pessoa_id=pe.id ".$where_busca.
  "order by pa.situacao asc, ".
  "  pe.nome asc;";

$participantes = $pdo->query($query_participantes)->fetchAll(PDO::FETCH_CLASS, 'Participante');
echo "<div class='grid-center'>";
foreach ($participantes as $participante){
  $pessoa = $pdo->query("SELECT * FROM pessoa where id=$participante->pessoa_id;")->fetchAll(PDO::FETCH_CLASS, 'Pessoa');
  $instituicao = $pdo->query("SELECT * FROM instituicao where id='$participante->instituicao_id';")->fetchAll(PDO::FETCH_CLASS, 'Instituicao');
  $estilo_botao = ($participante->situacao == "presente")?"botao_presente":"botao_ausente";
  $estilo_div = ($participante->situacao == "presente")?"div_presente":"div_ausente";
  $texto_botao = ($participante->situacao == "presente")?"Informar Ausência":"Informar Presença";
  $funcao_js = ($participante->situacao == "presente")?"informar_ausencia($participante->id);":"informar_presenca($participante->id);";
  echo "".
    "<div class='convidado $estilo_div'>".
    "<a class='botao $estilo_botao' href='#' onclick='$funcao_js' >$texto_botao</a>".
    "    <h2>".$pessoa[0]->nome."</h2>".
    "    <span>".$pessoa[0]->cpf."</span><br>".
    "    <span>".$instituicao[0]->nome."</span>".
    "</div>";
}
echo "</div>";
?>
