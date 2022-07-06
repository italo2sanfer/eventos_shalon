function informar_presenca(id_participante) {
  if (confirm('Deseja mesmo INFORMAR PRESENÇA?')){
    var funcao_origem = "crud_participante_trocar_situacao";
    var funcao_destino = "crud_participante_listar1_form";
    $.ajax({
      url:"f1.php",
      type: "post",
      dataType: 'json',
      data: {id_participante: id_participante, situacao_final: "presente", funcao: funcao_origem },
      success:function(result){
        alert(result.retorno);
        //$("#corpo").load("acao_listar_participantes.php");
        f1(funcao_destino);
      }
    });
  }
}

function informar_ausencia(id_participante) {
  if (confirm('Deseja mesmo INFORMAR AUSÊNCIA?')){
    var funcao_origem = "crud_participante_trocar_situacao";
    var funcao_destino = "crud_participante_listar1_form";
    $.ajax({
      url:"f1.php",
      type: "post",
      dataType: 'json',
      data: {id_participante: id_participante, situacao_final: "ausente", funcao: funcao_origem},
      success:function(result){
        alert(result.retorno);
        f1(funcao_destino);
        //$("#corpo").load("acao_listar_participantes.php");
      }
    });
  }
}

function sistema_deslogar() {
  if (confirm('Deseja me-smo DESLOGAR?')){
    var funcao_origem = "sistema_deslogar";
    $.ajax({
      url:"f1.php",
      type: "post",
      dataType: 'json',
      data: {funcao: funcao_origem},
      success:function(result){
        alert(result.retorno);
        window.location.reload();
      }
    });
  }
}

function sistema_logar_form(funcao){
  $.ajax({
    url:"f1.php",
    type: "post",
    dataType: 'html',
    data: {funcao: funcao},
    success:function(result){
        $("#corpo").html(result);
    }
  });
}

function sistema_logar_acao(form){
  dados = $('#'+form).serialize();
  $.ajax({
    url:"f1.php",
    type: "post",
    dataType: 'html',
    data: dados,
    success:function(result){
      if (result=="Sucesso!"){
        window.location.href = "index.php";
      }else{
        $("#corpo").html(result);
      }
    }
  });
}

function f1(funcao){
  $.ajax({
    url:"f1.php",
    type: "post",
    dataType: 'html',
    data: {funcao: funcao},
    success:function(result){
      $("#corpo").html(result);
    }
  });
}

function f2(form){
  dados = $('#'+form).serialize();
  $.ajax({
    url:"f1.php",
    type: "post",
    dataType: 'html',
    data: dados,
    success:function(result){
      $("#corpo").html(result);
    }
  });
}

/*$(document).ready(function() {
  //$("#corpo").on("click form button[id='submitBtn']", function() {
  //var

  //$("#corpo").find("#submitBtn").click(function() {
  $("#corpo").find("form[0]").find("#submitBtn").click(function() {
    dados = $('#usuario_adicionar_form').serialize();
    $.ajax({
      url:"f1.php",
      type: "post",
      dataType: 'html',
      data: dados,
      success:function(result){
        $("#corpo").html(result);
      }
    });
  });
});*/

