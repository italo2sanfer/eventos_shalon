<?php

class SistemaAdmin{
  public function csv_verificar($pdo,$POST){
    $resultado = "<h1>Verificador de csv</h1>";
    $row = 1;
    if (($handle = fopen(PLANILHA_IMPORTACAO, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $num = count($data);
            $resultado .= "<b> $num campos na linha $row: <br /></b>\n";
            $row++;
            for ($c=0; $c < $num; $c++) {
                $resultado .= $data[$c]."<br/>\n";
            }
            $resultado .= "<br>";
        }
        fclose($handle);
    }
    return $resultado;
  }
  public function csv_importar($pdo,$POST){
    $resultado = "<h1>Importador de csv</h1>";
    $resultado .= "
      <h5 style='color:red'>
        !!!!!!!! Estão sendo importados como PRESENTES!!!!!!!!<br>
        Isso deve mudar depois da Assembleia
      </h5>";
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
            $resultado .= "Adicionando Pessoa ...  Adicionada ... ";
        }else{
          $id_pessoa = $pessoas[0]->id;
          $resultado .= "Adicionando Pessoa ... Ja existente ... ";
        }
        $resultado .= "id=$id_pessoa <br>";

        $instituicoes = $pdo->query( "SELECT id FROM instituicao where nome='$nome_igreja';")->fetchAll(PDO::FETCH_CLASS, 'Instituicao');
        $id_instituicao = null;
        if (count($instituicoes)==0){
          $resultado .= "Adicionando Instituicao ...  Adicionada ... ";
          $stmt = $pdo->prepare("INSERT INTO instituicao(tipo_instituicao_id,nome,nome_responsavel) VALUES(?,?,?)");
          $stmt->execute([1, $nome_igreja, $nome_pastor]);
          $id_instituicao = $pdo->lastInsertId();
        }else{
          $resultado .= "Adicionando Instituicao ... Ja existente ... ";
          $id_instituicao = $instituicoes[0]->id;
        }
        $resultado .= "id=$id_instituicao <br>";
            
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
          $resultado .= "Adicionando Participante ...  Adicionada ... ";
          $stmt = $pdo->prepare("INSERT INTO participante(evento_id, pessoa_id, papel_id, instituicao_id, situacao) VALUES(?,?,?,?,?)");
          $stmt->execute([$id_evento, $id_pessoa, $id_papel, $id_instituicao, Participante::SITUACAO_PRESENTE]);
          $id_participante = $pdo->lastInsertId();
        }else{
          $resultado .= "Adicionando Participante ...  Ja existente ... ";
          $id_participante = $participantes[0]->id;
        }
        $resultado .= "id=$id_participante <br>";

        $resultado .=  "<br><br>";
      }
      fclose($handle);
      $resultado .= "Sucesso!";
    }
    return $resultado;
  }
  public function deslogar($pdo,$POST){
    setcookie('nome_usuario', null, -1);
    setcookie('id_usuario', null, -1);
    echo json_encode(array("retorno"=>"Deslogado."));
  }
  public function logar_form($pdo,$POST){
    $id_form = "logar_form";
    $form = "
    <div class='login'>
      <form id='$id_form' method='post' action='#'>
        <label>Usuario:</label><br><input type='text' name='usuario' id='usuario'><br>
        <label>Senha:</label><br><input type='password' name='senha' id='senha'><br><br>
        <input type='hidden' name='funcao' value='sistema_logar_acao'><br>
        <a class='submit_login' href='#' onclick='sistema_logar_acao(\"$id_form\")'>Entrar</a><br>
      </form>
    </div>";
    return $form;
  }
  public function logar_acao($pdo,$POST){
    $resultado = "";
    $usuario = isset($POST['usuario'])?$POST['usuario']:null;
    $entrar = isset($POST['entrar'])?$POST['entrar']:null;
    $senha = isset($POST['senha'])?$POST['senha']:null;
    if ($usuario && $senha) {
      $usuario = $pdo->query( "SELECT * FROM usuario where nome_usuario='$usuario';")->fetchAll(PDO::FETCH_CLASS, 'Usuario');
      if (count($usuario)>0){
        if (password_verify($senha, $usuario[0]->senha)){
          setcookie("nome_usuario",$usuario[0]->nome_usuario);
          setcookie("id_usuario",$usuario[0]->id);
          $resultado .= "Sucesso!";
        } else {
          $resultado .= 'Senha invalida!';
        }
      }else{
        $resultado .= "Usuario nao encontrado.";
      }
    }
    if ($resultado != "Sucesso!"){
      $resultado = $this->logar_form($pdo,$POST).$resultado;
    }
    return $resultado;
  }

}


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
class Papel{
  public $id; public $descricao;
}
class PerfilUsuario{
  const ADMIN = 'admin';
  const COMUM = 'comum';
  public $id; public $nome;
  public $descricao;
}
class Participante{
  const SITUACAO_PRESENTE = 'presente';
  const SITUACAO_AUSENTE = 'ausente';
  public $id; public $evento_id;
  public $pessoa_id; public $papel_id;
  public $instituicao_id; public $situacao;
}

class ParticipanteAdmin{
  public function listar1_form($pdo,$POST){
    $selection_pessoa = get_selection($pdo,"Pessoa","pessoa","id","nome");
    $selection_perfil_usuario = get_selection($pdo,"PerfilUsuario","perfil_usuario","id","nome");
    $funcaop = explode("_",$POST['funcao']);
    $classe = ($funcaop[0] == "crud")?$funcaop[1]:"";
    $id_form = "listar1_form";
    $evento = $pdo->query("SELECT * FROM evento where id=1;")->fetchAll(PDO::FETCH_CLASS, 'Evento');
    $participantes_presentes = $pdo->query("
      SELECT *
      FROM participante
      where situacao='".Participante::SITUACAO_PRESENTE."' and
        evento_id=".$evento[0]->id.";")->fetchAll(PDO::FETCH_CLASS, 'Participante');
    $participantes_ausentes = $pdo->query("
      SELECT *
      FROM participante
      where situacao='".Participante::SITUACAO_AUSENTE."' and
        evento_id=".$evento[0]->id.";")->fetchAll(PDO::FETCH_CLASS, 'Participante');
    $h1 = "<h1>".ucfirst($evento[0]->nome)."</h1>";
    $placar = "
      <div style='display:flex; justify-content:space-around; margin:7px'>
        <div style='background-color:green; color:white'>Presentes: ".count($participantes_presentes)."</div>
        <div style='background-color:red; color:white'>Ausentes: ".count($participantes_ausentes)."</div>
      </div>";

    $form = $h1.$placar."
    <form id='$id_form' method='POST' action='#'>
      <label>Instituicao/Igreja:</label><input type='text' name='instituicao_busca' id='instituicao_busca'><br>
      <label>Nome da pessoa:</label><input type='text' name='nome_busca' id='nome_busca'><br>
      <p><input type='hidden' name='funcao' value='crud_participante_listar1_acao'></p>
      <a class='botao botao_acao' href='#' onclick='f2(\"$id_form\")'>Buscar</a>
    </form>";
    return $form;
  }
  public function listar1_acao($pdo,$POST){
    $resultado = "";
    $resultado .= $this->listar1_form($pdo,$POST);

    $nome_busca = isset($POST['nome_busca'])?$POST['nome_busca']:null;
    $instituicao_busca = isset($POST['instituicao_busca'])?$POST['instituicao_busca']:null;

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
    $resultado .= "<div class='grid-center'>";
    foreach ($participantes as $participante){
      $pessoa = $pdo->query("SELECT * FROM pessoa where id=$participante->pessoa_id;")->fetchAll(PDO::FETCH_CLASS, 'Pessoa');
      $instituicao = $pdo->query("SELECT * FROM instituicao where id='$participante->instituicao_id';")->fetchAll(PDO::FETCH_CLASS, 'Instituicao');
      $estilo_botao = ($participante->situacao == "presente")?"botao_presente":"botao_ausente";
      $estilo_div = ($participante->situacao == "presente")?"div_presente":"div_ausente";
      $texto_botao = ($participante->situacao == "presente")?"Informar Ausência":"Informar Presença";
      $funcao_js = ($participante->situacao == "presente")?"informar_ausencia($participante->id);":"informar_presenca($participante->id);";
      $resultado .= "".
        "<div class='convidado $estilo_div'>".
        "<a class='botao $estilo_botao' href='#' onclick='$funcao_js' >$texto_botao</a>".
        "    <h2>".$pessoa[0]->nome."</h2>".
        "    <span>".$pessoa[0]->cpf."</span><br>".
        "    <span>".$instituicao[0]->nome."</span>".
        "</div>";
    }
    $resultado .= "</div>";

    return $resultado;
  }
  public function adicionar1_form($pdo,$POST){
    $funcaop = explode("_",$POST['funcao']);
    $classe = ($funcaop[0] == "crud")?$funcaop[1]:"";
    $selection_instituicao = get_selection($pdo,"Instituicao","instituicao","id","nome");
    $id_form = "adicionar1_form";
    $h1 = "<h1>Adicionar Participante</h1>";
    $form = "
    <form id='$id_form' method='post' action='#'>
      <p>Nome: <input type='text' name='nome' /></p>
      <p>CPF: <input type='text' name='cpf' /></p>
      <p>Email: <input type='text' name='email' /></p>
      <p>Telefone de contato: <input type='text' name='telefone_contato' /></p>
      <p>Instituicao: $selection_instituicao</p>
      <p><input type='hidden' name='funcao' value='crud_participante_adicionar1_acao'></p>
      <a class='botao botao_acao' href='#' onclick='f2(\"$id_form\")'>Cadastrar</a>
    </form>";
    return $h1.$form;
  }
  public function adicionar1_acao($pdo,$POST){
    $resultado = "";
    $resultado .= $this->adicionar1_form($pdo,$POST);
    $nome = isset($POST['nome'])?$POST['nome']:null;
    $cpf = isset($POST['cpf'])?$POST['cpf']:null;
    $email = isset($POST['email'])?$POST['email']:null;
    $telefone_contato = isset($_POST['telefone_contato'])?$_POST['telefone_contato']:null;
    $instituicao = isset($_POST['instituicao'])?$_POST['instituicao']:null;
    if ($nome!=null and $cpf!=null and $email!=null){
      $verificar_pessoa = array(
         array("nome"=>"cpf", "valor"=>$cpf, "str"=>"sim"),
         array("nome"=>"email", "valor"=>$email, "str"=>"sim"),
      );
      $inserir_pessoa = array(
         array("nome"=>"nome", "valor"=>$nome, "str"=>"sim"),
         array("nome"=>"cpf", "valor"=>$cpf, "str"=>"sim"),
         array("nome"=>"email", "valor"=>$email, "str"=>"sim"),
         array("nome"=>"telefone_contato", "valor"=>$telefone_contato, "str"=>"sim"),
      );
      $pessoa = get_by_fields($pdo,"Pessoa","pessoa",$verificar_pessoa);
      if (!$pessoa){
        $id_pessoa = insert_object($pdo,"Pessoa","pessoa",$inserir_pessoa); 
        $resultado .= "Pessoa cadastrada com sucesso!<br>";
      }else{
        $resultado .= "Já existe uma pessoa com esse cpf e e-mail.<br>";
        $id_pessoa = $pessoa->id;
      }
      $verificar_participante = array(
         array("nome"=>"evento_id", "valor"=>1, "str"=>"nao"),
         array("nome"=>"papel_id", "valor"=>2, "str"=>"nao"),
         array("nome"=>"instituicao_id", "valor"=>$instituicao, "str"=>"nao"),
      );
      $inserir_participante = array(
         array("nome"=>"evento_id", "valor"=>1, "str"=>"nao"),
         array("nome"=>"pessoa_id", "valor"=>$id_pessoa, "str"=>"nao"),
         array("nome"=>"papel_id", "valor"=>2, "str"=>"nao"),
         array("nome"=>"instituicao_id", "valor"=>$instituicao, "str"=>"nao"),
         array("nome"=>"situacao", "valor"=>"presente", "str"=>"sim"),
      );
      $participante = get_by_fields($pdo,"Participante","participante",$verificar_participante);
      if (!$participante){
        $id_participante = insert_object($pdo,"Participante","participante",$inserir_participante); 
        $resultado .= "Participante cadastrado com sucesso!<br>";
      }else{
        $resultado .= "Já existe um participante com essas caracteristicas (evento,papel,instituicao).<br>";
      }
    }
    return $resultado;
  }



  public function trocar_situacao($pdo,$POST){
    $id_participante = $POST['id_participante'];
    $situacao_final = $POST['situacao_final'];

    $stmt = $pdo->prepare("UPDATE participante SET situacao = ? where id=?;");
    $stmt->execute([$situacao_final, $id_participante]);

    return json_encode(array("retorno"=>"Participante $situacao_final."));
  }
}


class Usuario{
  public $id; public $nome_usuario;
  public $senha; public $pessoa_id;
  public $perfil_usuario_id;
}
class UsuarioAdmin{
  public function adicionar_form($pdo,$POST){
    $selection_pessoa = get_selection($pdo,"Pessoa","pessoa","id","nome");
    $selection_perfil_usuario = get_selection($pdo,"PerfilUsuario","perfil_usuario","id","nome");
    $funcaop = explode("_",$POST['funcao']);
    $classe = ($funcaop[0] == "crud")?$funcaop[1]:"";
    $id_form = "adicionar_form";
    $h1 = "<h1>Adicionar Usuário</h1>";
    $form = "
    <form id='$id_form' method='post' action='#'>
      <p>Pessoa: $selection_pessoa</p>
      <p>Perfil de usuário: $selection_perfil_usuario</p>
      <p>Nome de usuário: <input type='text' name='nome_usuario' /></p>
      <p>Senha: <input type='password' name='senha' /></p>
      <p>Confirmar Senha: <input type='password' name='confirmar_senha' /></p>
      <p><input type='hidden' name='funcao' value='crud_usuario_adicionar_acao'></p>
      <!-- <button type='button' id='submitBtn'>Submit Form</button> -->
      <a class='botao botao_acao' href='#' onclick='f2(\"$id_form\")'>Cadastrar</a>
    </form>";
    return $h1.$form;
  }
  public function adicionar_acao($pdo,$POST){
    $resultado = "";
    $resultado .= $this->adicionar_form($pdo,$POST);

    $id_pessoa = isset($POST['pessoa'])?$POST['pessoa']:null;
    $nome_usuario = isset($POST['nome_usuario'])?$POST['nome_usuario']:null;
    $senha = isset($POST['senha'])?$POST['senha']:null;
    $id_perfil_usuario = isset($POST['perfil_usuario'])?$POST['perfil_usuario']:null;
    $confirmar_senha = isset($POST['confirmar_senha'])?$POST['confirmar_senha']:null;
    if ($senha==$confirmar_senha and $senha!=null){
      $senha = password_hash($senha, PASSWORD_DEFAULT);
      $usuario = $pdo->query( "SELECT * FROM usuario where pessoa_id=$id_pessoa and perfil_usuario_id=$id_perfil_usuario;")->fetchAll(PDO::FETCH_CLASS, 'Usuario');
      $id_usuario = null;
      if (count($usuario)==0){
        $stmt = $pdo->prepare("INSERT INTO usuario(nome_usuario, senha, pessoa_id, perfil_usuario_id) VALUES(?,?,?,?)");
        $stmt->execute([$nome_usuario, $senha, $id_pessoa, $id_perfil_usuario]);
        $id_usuario = $pdo->lastInsertId();
          $resultado .= "Adicionando Usuario ...  Adicionado ... \n";
      }else{
        $id_usuario = $usuario[0]->id;
        $resultado .= "Adicionando Usuario ... Ja existente ... \n";
      }
      $resultado .= "id=$id_usuario <br>\n";

    }else{
      if ($senha!=null){
        $resultado .= "Senhas nao conferem!\n";
      }
    }
    return $resultado;
  }
  public function trocar_senha_form($pdo,$POST){
    $selection_usuario = get_selection($pdo,"Usuario","usuario","id","nome_usuario");
    $funcaop = explode("_",$POST['funcao']);
    $classe = ($funcaop[0] == "crud")?$funcaop[1]:"";
    $id_form = "trocar_senha_form";
    $form = "
    <form id='$id_form' method='post' action='#'>
     <p>Usuário: $selection_usuario</p>
     <p>Nova Senha: <input type='password' name='nova_senha' /></p>
     <p>Confirmar Nova Senha: <input type='password' name='confirmar_nova_senha' /></p>
      <p><input type='hidden' name='funcao' value='crud_usuario_trocar_senha_acao'></p>
     <a class='botao botao_acao' href='#' onclick='f2(\"$id_form\")'>Trocar</a>
    </form>";
    return $form;
  }
  public function trocar_senha_acao($pdo,$POST){
    $id_usuario = isset($POST['usuario'])?$POST['usuario']:null;
    $nova_senha = isset($POST['nova_senha'])?$POST['nova_senha']:null;
    $confirmar_nova_senha = isset($POST['confirmar_nova_senha'])?$POST['confirmar_nova_senha']:null;
    $resultado = "";
    if ($nova_senha==$confirmar_nova_senha and $nova_senha!=null){
      $nova_senha = password_hash($nova_senha, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE usuario set senha=? where id=?;");
      $stmt->execute([$nova_senha, $id_usuario]);
      $resultado .= "Senha trocada com sucesso!";
    }else{
      if ($nova_senha!=null){
        $resultado .= "Senhas nao conferem!";
      }
    }
    return $resultado;
  }
}



class Pessoa{
  public $id; public $cpf;
  public $nome; public $email;
  public $telefone_contato;
}

class PessoaAdmin{
  public function adicionar_form($pdo,$POST){
    $funcaop = explode("_",$POST['funcao']);
    $classe = ($funcaop[0] == "crud")?$funcaop[1]:"";
    $id_form = "adicionar_form";
    $form = "
    <form id='$id_form' method='post' action='#'>
      <p>Nome: <input type='text' name='nome' /></p>
      <p>CPF: <input type='text' name='cpf' /></p>
      <p>Email: <input type='text' name='email' /></p>
      <p>Telefone de contato: <input type='text' name='telefone_contato' /></p>
      <p><input type='hidden' name='funcao' value='crud_pessoa_adicionar_acao'></p>
      <a class='botao botao_acao' href='#' onclick='f2(\"$id_form\")'>Cadastrar</a>
    </form>";
    return $form;
  }
  public function adicionar_acao($pdo,$POST){
    $nome = isset($POST['nome'])?$POST['nome']:null;
    $cpf = isset($POST['cpf'])?$POST['cpf']:null;
    $email = isset($POST['email'])?$POST['email']:null;
    $telefone_contato = isset($_POST['telefone_contato'])?$_POST['telefone_contato']:null;
    $resultado = "";
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
        $resultado .= "Pessoa cadastrada com sucesso!";
      }else{
        $resultado .= "Já existe uma pessoa com esse cpf e e-mail.";
      }
    }
    return $resultado;
  }
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
  $stmt = $pdo->prepare("INSERT INTO $tabela($fields_insert_vai) VALUES($fields_values_vai)");
  $stmt->execute();
  return $pdo->lastInsertId();
}

function get_selection($pdo,$classe,$tabela,$value,$text){
  $objetos = get_all($pdo,$classe,$tabela);
  $select_objeto = "<select name='$tabela'>";
  foreach ($objetos as $objeto){
    $select_objeto .= "<option value=".$objeto->$value.">".$objeto->$text."</option>";
  }
  $select_objeto .="</select>";
  return $select_objeto;
}









?>
