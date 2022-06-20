function acao_listar_participantes(){
    var form = $("#form_participantes");
    $("#corpo").load("acao_listar_participantes.php?"+form.serialize());
}
function acao_cadastrar_usuario(){
  var form = $("#form_cadastrar_usuario");
  $("#corpo").load("acao_cadastrar_usuario.php?"+form.serialize());
}
function acao_csv_importar() { $("#corpo").load("acao_csv_importar.php"); }
function acao_csv_verificar() { $("#corpo").load("acao_csv_verificar.php"); }

function informar_presenca(id_participante) {
  if (confirm('Deseja mesmo INFORMAR PRESENCA?')){
    $.ajax({
      url:"acao_trocar_situacao.php",
      type: "post",
      dataType: 'json',
      data: {id_participante: id_participante, situacao_final: "presente"},
      success:function(result){
        alert(result.retorno);
        $("#corpo").load("acao_listar_participantes.php");
      }
    });
  }
}
function informar_ausencia(id_participante) {
  if (confirm('Deseja mesmo INFORMAR AUSENCIA?')){
    $.ajax({
      url:"acao_trocar_situacao.php",
      type: "post",
      dataType: 'json',
      data: {id_participante: id_participante, situacao_final: "ausente"},
      success:function(result){
        alert(result.retorno);
        $("#corpo").load("acao_listar_participantes.php");
      }
    });
  }
}

function sistema_deslogar() {
  if (confirm('Deseja mesmo DESLOGAR?')){
    $.ajax({
      url:"sistema_deslogar.php",
      type: "post",
      dataType: 'json',
      data: {},
      success:function(result){
        alert(result.retorno);
        window.location.reload();
      }
    });
  }
}
