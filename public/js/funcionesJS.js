function formatNumber(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}

function dateToYMD(date) {
    var d = date.getDate();
    var m = date.getMonth() + 1 ;
    var y = date.getFullYear();
    if(d>28){
    	d=1;
    	m=m+1;
    	if(m==13){
    		m=1;
    		y=y+1;
    	}
    }
    return '' + y + '-' + (m <= 9 ? '0' + m : m) + '-' + (d <= 9 ? '0' + d : d);
}

function calcularFechaFacturacion(us_fechaPago, us_fechaFacturacion, diaPago, mesPago, anoPago){
	let fecha = Date.parse(document.getElementById(us_fechaPago).value);
	let diasEnMilisegundos = 1000 * 60 * 60 * 24 * 13;
	let suma = fecha - diasEnMilisegundos;
	var fechaFacturacion = new Date();
	fechaFacturacion.setTime(suma);
	document.getElementById(us_fechaFacturacion).value=dateToYMD(fechaFacturacion);
}

function formateaRut(us_rut){
	var user = document.getElementById(us_rut).value;
	var aux = "";
	for(i=0;i<user.length;i++){
		if((user[i]==0)||(user[i]==1)||(user[i]==2)||(user[i]==3)||(user[i]==4)||(user[i]==5)||(user[i]==6)||(user[i]==7)||(user[i]==8)||(user[i]==9)||(user[i]=='k')||(user[i]=='K')){
			if(user[i]=='k')
				aux=aux+'K';
			else
				aux=aux+user[i];
		}
	}
	if(aux=="")
		document.getElementById(us_rut).value="";
	else{
		i=aux.length-2;
		while((aux[i]!='k')&&(aux[i]!='K')&&(i>=0)){
			i--;
		}
		if(i!=-1)
			document.getElementById(us_rut).value="";
		else {
			var tmp = "";
			tmp='-'+aux[aux.length-1]+tmp;
			for(i=aux.length-2,j=0;i>=0;i--,j++){
				if((j%3==0)&&(j!=0)) tmp='.'+tmp;
				tmp=aux[i]+tmp;
			}
			document.getElementById(us_rut).value=tmp;
		}
	}
}


$(document).ready(function() {
	$(document).on('hidden.bs.modal', '.modal', function () {
    	$('.modal:visible').length && $(document.body).addClass('modal-open');
	});


	//----- MANEJO FECHAS DE PAGO Y FACTURACIÓN (MESES CON 29 DÍAS, BISIESTOS ETC)-----//

	var fechaMin=$(".dateLimited").attr('min');
	var array_fechaMin = fechaMin.split("-");
    var diaMin  = parseInt(array_fechaMin[2]);
    var mesMin  = parseInt(array_fechaMin[1]);
    var anoMin  = parseInt(array_fechaMin[0]);
    console.log(diaMin +" "+ (mesMin <= 9 ? '0' + mesMin : mesMin)+" "+anoMin);

    var diaMax, mesMax, anoMax;

    if(diaMin==1){
    	diaMax=28;
    	mesMax=mesMin;
    	anoMax=anoMin;
    }
    else{
    	if(diaMin==31 || diaMin==30){
    		diaMax=28;
    	}
    	else{
    		diaMax=diaMin-1;
    	}
    	
    	mesMax=mesMin+1;
    	if(mesMax==13){
    		mesMax=1;
    		anoMax=anoMin+1;
    	}
    	else{
    		anoMax=anoMin;
    	}
    }
    console.log(diaMax +" "+ (mesMax <= 9 ? '0' + mesMax : mesMax)+" "+anoMax);

    $(".dateLimited").attr({"max" : anoMax +"-"+ (mesMax <= 9 ? '0' + mesMax : mesMax)+"-"+ (diaMax <= 9 ? '0' + diaMax : diaMax)});

	//Manejo de fechas 29, 30 y 31
	$(document).on('change', '.dateLimited', function () {
		var id=this.id;
		console.log(id);
        if(id==="fechaPagoClienteDet"){
            idFac="fechaFacturacionClienteDet";
        }else{
            idFac="fechaFacturacionCliente";
        }
		var fecha=$("#"+id).val();
    	var array_fechasol = fecha.split("-");
    	var dia  = parseInt(array_fechasol[2]);
    	var mes  = parseInt(array_fechasol[1]);
    	var ano  = parseInt(array_fechasol[0]);
    	console.log(dia);

    	if( dia == 29 || dia == 30 || dia == 31) {
    		dia = "01";
    		mes++;
    		if(mes==13){
    			mes=1;
    			ano++;
    		}
    		console.log(dia +" "+ (mes <= 9 ? '0' + mes : mes)+" "+ano);
    		$("#"+id).val(ano +"-"+ (mes <= 9 ? '0' + mes : mes)+"-"+dia);
	    }

	    calcularFechaFacturacion(id, idFac, dia, mes, ano);
	});

    $(document).on('change', '.numberPositivo', function () {

        var id=this.id;
        var number=parseInt(this.value);

        if (number<0){
            $("#"+id+'.numberPositivo').val(0)
        }
        if (this.value.length<=0){
            $("#"+id+'.numberPositivo').val(0)
        }
    });

});