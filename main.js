function acao_listar_participantes() {
  $("#corpo").load("acao_listar_participantes.php");
}
function informar_presenca(id_participante) {
  if (confirm('Deseja mesmo INFORMAR PRESENCA?')){
    $.ajax({
      url:"troca_situacao.php",
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
      url:"troca_situacao.php",
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
function deslogar() {
  if (confirm('Deseja mesmo DESLOGAR?')){
    $.ajax({
      url:"deslogar.php",
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
