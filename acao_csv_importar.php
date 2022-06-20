<?php
include_once("main.php");

echo "<h1>Importador de csv</h1>";

$row = 1;
if (($handle = fopen(PLANILHA_IMPORTACAO, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $email = " "; $nome = " ";
    $nome_igreja = " "; $cpf = " ";
    $nome_pastor = " "; $telefone_contato = " ";
    $num = count($data);
    $row++;
    for ($c=0; $c < $num; $c++) {
      $email = $data[1];
      $nome = $data[2];
      $nome_igreja = $data[3];
      $cpf = $data[4];
      $nome_pastor = $data[5];
      $telefone_contato = $data[6];
    }

    $pessoas = $pdo->query( "SELECT id FROM pessoa where cpf='$cpf' and email='$email';")->fetchAll(PDO::FETCH_CLASS, 'Pessoa');
    $id_pessoa = null;
    if (count($pessoas)==0){
      $stmt = $pdo->prepare("INSERT INTO pessoa(cpf, nome, email, telefone_contato) VALUES(?,?,?,?)");
      $stmt->execute([$cpf, $nome, $email, $telefone_contato]);
      $id_pessoa = $pdo->lastInsertId();
        echo "Adicionando Pessoa ...  Adicionada ... ";
    }else{
      $id_pessoa = $pessoas[0]->id;
      echo "Adicionando Pessoa ... Ja existente ... ";
    }
    echo "id=$id_pessoa <br>";

    $instituicoes = $pdo->query( "SELECT id FROM instituicao where nome='$nome_igreja';")->fetchAll(PDO::FETCH_CLASS, 'Instituicao');
    $id_instituicao = null;
    if (count($instituicoes)==0){
      echo "Adicionando Instituicao ...  Adicionada ... ";
      $stmt = $pdo->prepare("INSERT INTO instituicao(tipo_instituicao_id,nome,nome_responsavel) VALUES(?,?,?)");
      $stmt->execute([1, $nome_igreja, $nome_pastor]);
      $id_instituicao = $pdo->lastInsertId();
    }else{
      echo "Adicionando Instituicao ... Ja existente ... ";
      $id_instituicao = $instituicoes[0]->id;
    }
    echo "id=$id_instituicao <br>";
        
    $id_evento = 1; $id_papel = 2;
    $participantes = $pdo->query("
      SELECT id
      FROM participante
      where evento_id=$id_evento and
        pessoa_id=$id_pessoa and
        papel_id=$id_papel and
        instituicao_id=$id_instituicao;")->fetchAll(PDO::FETCH_CLASS, 'Participante');
    $id_participante = null;
    if (count($participantes)==0){
      echo "Adicionando Participante ...  Adicionada ... ";
      $stmt = $pdo->prepare("INSERT INTO participante(evento_id, pessoa_id, papel_id, instituicao_id, situacao) VALUES(?,?,?,?,?)");
      $stmt->execute([$id_evento, $id_pessoa, $id_papel, $id_instituicao, Participante::SITUACAO_AUSENTE]);
      $id_participante = $pdo->lastInsertId();
    }else{
      echo "Adicionando Participante ...  Ja existente ... ";
      $id_participante = $participantes[0]->id;
    }
    echo "id=$id_participante <br>";

    echo "<br><br>";
  }
  fclose($handle);

  echo "Sucesso!";
}


?>
