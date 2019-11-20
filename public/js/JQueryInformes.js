$(document).ready(function() {
  var f = new Date();
  //document.write(f.getDate() + "/" + (f.getMonth() +1) + "/" + f.getFullYear());

  $('.inicioAnual').val(f.getFullYear()+'-01-01');
  $('.finalAnual').val(f.getFullYear()+'-12-31');

  $('.finalAnual').attr({ "min" : f.getFullYear()+'-01-01' });

  $(document).on('change','.inicioAnual',function(){
    let id = this.id;
    nuevoid = id.replace('Inicio', 'Fin');

    $('#'+nuevoid).attr({ "min" : $('#'+id).val()});

  });

});