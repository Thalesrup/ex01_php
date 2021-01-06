$(document).ready(function(){

listarUsuarios();


var tbodyUsuarios      = $('#listagemUsuarios');
var modalEditarUsuario = $('#edit-usuario');
var modalInserirUsuario= $('#create-usuario');
var formInserir        = $('#formInserirUsuario');
var formUpdateUsuario  = $('#edit-usuario');
var btnGerarXML        = $('#gerarXMLUsuarios'); 

window.editarUsuario = function(id){
	$.ajax({
		url:'core/CoreCrud.php?updateModal='+id,
		type: 'GET',
		dataType: 'json',
		success: function(retorno){
			modalEditarUsuario.html(retorno.data);
			modalEditarUsuario.modal('show');
		}
	});
}

btnGerarXML.click(function(){
	$.ajax({
		url: 'core/CoreCrud.php?exportarXML=true',
		type: 'GET',
		dataType: 'json',
		success: function(callback){
			if(callback.error){
				msgErro('Falha', callback.msg);
			}
			if(!callback.error){
				console.log(callback);
				msgSucesso('Sucesso', callback.msg, callback.dirDownload);       
	            
			}
		}
	});
});



// Submit responsável por gerenciar Update do usuario

$(this).submit(function(e){
	e.preventDefault();

	var id    = $('#idUsuario').val();
	var nome  = $('#nomeUsuario').val();
	var email = $('#emailUsuario').val();
	var sexo  = $('#sexoUsuario').val();

	$.ajax({
		url: 'core/CoreCrud.php',
		type: 'POST',
		data: {update: true, nome: nome, email: email, sexo: sexo, id : id},
		dataType: 'json',
		success: function(callback){
			console.log(callback);
			if(callback.error){
				msgErro('Falha', callback.msg);
			}
			if(!callback.error){
				msgSucesso('Sucesso', callback.msg, false, true);
				fecharFormulario(formUpdateUsuario);
			}
		}
	});
});

formInserir.submit(function(evento){
	evento.preventDefault();
	var formData = formInserir.serialize();
	var url      = 'core/CoreCrud.php'
	
	$.ajax({
		url: url,
		type:'POST',
		data: formData,
		dataType: 'json',
		success: function(callback){
    		console.log(callback);
    		if (callback.error){
                msgErro('Falha', callback.msg);
              }
            if (!callback.error){
           		limparFormulario(formInserir);
            	msgSucesso('Sucesso', callback.msg, false, true);
            	fecharFormulario(modalInserirUsuario);
            }
		}
	});

});


window.deletarUsuario = function(id){
	$.ajax({
		url: 'core/CoreCrud.php?delete='+id,
		type: 'GET',
		dataType: 'json',
		success: function(callback){
			if(callback.error){
				msgErro('Falha', callback.msg);
			}
			if(!callback.error){
				msgSucesso('Sucesso', callback.msg, false, true);
			}
		}
	});
};

	
function listarUsuarios(){
	$.ajax({
		url:'core/CoreCrud.php?read=true',
		type:'GET',
		dataType:'json',
		success: function(retorno){
			tbodyUsuarios.html(retorno.data);
		}
	});
}

function msgSucesso(title ,msgBody, callbackLinkDownload = false, refresh = false){
        $.confirm({
            title: title,
            content: msgBody,
            type: 'blue',
            buttons: {
                sim: {
                    text: 'Ok',
                    btnClass: 'btn-info',
                    action: function(){
                    	if(callbackLinkDownload){
                    		window.open(callbackLinkDownload);
                    	}
                    	if(refresh){
                    		location.reload();
						}
                    }
                }
            }
        });
    }

function msgErro(title ,msgBody){
    $.dialog({
	  title: title,
      content: msgBody,
      type: 'red',
    });
}

function limparFormulario(elemento){
  $(elemento).each(function(){
     this.reset();
  });
}


function fecharFormulario(elemento){
  elemento.modal('hide');
}


});

