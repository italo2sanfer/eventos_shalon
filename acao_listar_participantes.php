<form method="POST" action="#">
<label>Instituicao/Igreja:</label><input type="text" name="instituicao_busca" id="instituicao_busca"><br>
<label>Nome da pessoa:</label><input type="text" name="nome_busca" id="nome_busca"><br>
<input type="submit" value="Buscar"><br>
</form>
<?php
include_once("main.php");

$nome_busca = isset($_POST['nome_busca'])?$_POST['nome_busca']:null;
$instituicao_busca = isset($_POST['instituicao_busca'])?$_POST['instituicao_busca']:null;

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


#echo $query_participantes;

$participantes = $pdo->query($query_participantes)->fetchAll(PDO::FETCH_CLASS, 'Participante');
echo "<h1>".$evento[0]->nome."</h1>";
foreach ($participantes as $participante){
  $pessoa = $pdo->query("SELECT * FROM pessoa where id=$participante->pessoa_id;")->fetchAll(PDO::FETCH_CLASS, 'Pessoa');
  $instituicao = $pdo->query("SELECT * FROM instituicao where id='$participante->instituicao_id';")->fetchAll(PDO::FETCH_CLASS, 'Instituicao');
  $estilo_botao = ($participante->situacao == "presente")?"botao_presente":"botao_ausente";
  $estilo_div = ($participante->situacao == "presente")?"div_presente":"div_ausente";
  $texto_botao = ($participante->situacao == "presente")?"Informar Ausencia":"Informar Presenca";
  $funcao_js = ($participante->situacao == "presente")?"informar_ausencia($participante->id);":"informar_presenca($participante->id);";
  echo "".
    "<div class='convidado $estilo_div'>".
    "<a href='#' onclick='$funcao_js' class='botao $estilo_botao'>$texto_botao</a>".
    "    <h2>".$pessoa[0]->nome."</h2>".
    "    <span>".$pessoa[0]->cpf."</span><br>".
    "    <span>".$instituicao[0]->nome."</span>".
    "</div>";
}
?>
