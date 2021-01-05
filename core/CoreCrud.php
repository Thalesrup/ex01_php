<?php

require 'CoreDB.php';

$requisicao = $_SERVER['REQUEST_METHOD'];
$dataPost   = ($requisicao == 'POST') ? $_POST : $_GET;

validaTipoRequisicao($dataPost);

function validaTipoRequisicao($arrayRequisicao)
{

   	if(array_key_exists('insert', $arrayRequisicao)){
		array_shift($arrayRequisicao);
		insert($arrayRequisicao);
	}
	if(array_key_exists('updateModal', $arrayRequisicao)){
		renderizarModalUpdateUsuario($arrayRequisicao);
	}
	if(array_key_exists('update', $arrayRequisicao)){
		array_shift($arrayRequisicao);
		update($arrayRequisicao);
	}
	if(array_key_exists('delete', $arrayRequisicao)){
		delete($arrayRequisicao);
	}
	if(array_key_exists('read', $arrayRequisicao)){
		ler();
	}
	if(array_key_exists('exportarXML', $arrayRequisicao)){
		exportarListaUsuariosXML();
	}
}

function singletonConectionDB()
{
	$conexao = new CoreDB();
	$conexao = $conexao->getPDO();
	return $conexao;
}


function insert($arrayDados)
{
	extract($arrayDados);
	$conexao = singletonConectionDB();
	$query   = $conexao->prepare("INSERT INTO usuarios (nome, email, sexo) VALUES(?,?,?)");
	$query->bindParam(1, $nome);
	$query->bindParam(2, $email);
	$query->bindParam(3, $sexo);
	
	if($query->execute()){

		if($query->rowCount() > 0){
		echo json_encode(['error' => false, 'msg' => 'Dados Gravados Com Sucesso']);
		exit();
		} else {
			echo json_encode(['error' => true, 'msg' => 'Erro Ao Tentar Salvar Registro!']);
			exit();
		}

	}else{
        throw new \PDOException("Erro: Não foi possível executar a query");
    }

	
}

function update($arrayDados)
{
	extract($arrayDados);
	$conexao = singletonConectionDB();
	$query   = $conexao->prepare("UPDATE usuarios SET nome = :nome, email = :email, sexo = :sexo WHERE id = :id");
	$query->bindParam(':nome', $nome);
	$query->bindParam(':email', $email);
	$query->bindParam(':sexo', $sexo);
	$query->bindParam(':id', $id);

	if($query->execute()){

		if($query->rowCount() > 0){
			echo json_encode(['error' => false, 'msg' => 'Registro Atualizado Com Sucesso']);
			exit();
		} else {
			echo json_encode(['error' => true, 'msg' => 'Erro ao Tentar Atualizar o Registro!']);
			exit();
		}

	} else {
		throw new \PDOException("Error: Não Foi possível executar a Query");
	}
}

function ler()
{
	$conexao = singletonConectionDB();
	$query   = $conexao->prepare("SELECT * FROM usuarios");

	if($query->execute()){
		if($query->rowCount() > 0){
			renderizarListaUsuarios($query->fetchAll(\PDO::FETCH_ASSOC));
		} else {
			renderizarSemRegistros();
		}
	} else {
		throw new \PDOException("Erro: Não foi possivel executar a Query");
	}
}

function delete($id)
{
	$id      = reset($id);
	$conexao = singletonConectionDB();
	$query   = $conexao->prepare("DELETE FROM usuarios WHERE id = ?");
	$query->bindParam(1, $id);

	if($query->execute()){
		if($query->rowCount() > 0){
			echo json_encode(['error' => false, 'msg' => 'Registro Deletado com Sucesso']);
			exit();
		} else {
			echo json_encode(['error' => true, 'msg' => 'Erro ao Tentar Deletar o Registro!']);
			exit();
		}
	} else {
		throw new \PDOException("Error Processing Request", 1);	
	}
}

function renderizarListaUsuarios(array $listaUsuarios)
{
	$listagemHtml = '';

	foreach($listaUsuarios as $usuario){
		$listagemHtml .= "<tr>";
		$listagemHtml .= "<td>".$usuario['nome']."</td>";
		$listagemHtml .= "<td>".$usuario['email']."</td>";
		$listagemHtml .= "<td>".validaSexo($usuario['sexo'])."</td>";
		$listagemHtml .= '<td style="text-align: center; vertical-align: middle;"><button type="button" onclick="editarUsuario('.$usuario['id'].')" id="btnUpdate" data-value="'.$usuario['id'].'" class="btn btn-warning btn-sm"> <i class="fas fa-edit"></i>Editar</button>';
        $listagemHtml .= '  <button type="button" value="'.$usuario['id'].'" onclick="deletarUsuario('.$usuario['id'].')" class="btn btn-danger deleteBtn btn-sm"> <i class="fas fa-trash-alt"></i> Excluir </button>';            
        $listagemHtml .= "</tr>";                           
	}

	echo json_encode(['data' => $listagemHtml]);
	exit();

}

function renderizarSemRegistros()
{
	$listagemHtml = '<tr> Não Foram Encontrados Registros </tr>';
	echo json_encode(['data' => $listagemHtml]);
	exit();
}

function validaSexo(string $sexo)
{
	return ($sexo == 'm') ? 'Masculino' : 'Feminino';
}

function renderizarModalUpdateUsuario($id)
{
	$id      = reset($id);
	$conexao = singletonConectionDB();
	$query   = $conexao->prepare("SELECT * FROM usuarios WHERE id = ?");
	$query->bindParam(1, $id);

	if($query->execute()){
		if($query->rowCount() > 0){
			renderizarModalEditarUsuario($query->fetch(\PDO::FETCH_ASSOC));
		} else {
			echo json_encode(['error' => true, 'msg' => 'Não Foram encontrados Dados Usando o Id Informado']);
			exit();
		}
	} else {
		throw new PDOException("Não Foi Possivel executar a Query");
	}

}

function renderizarModalEditarUsuario($arrayDadosUsuario)
{
	$modal = '
         <div class="modal-dialog" role="document">
                     
	        <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Editar Usuário</h4>
              </div>


              <div class="modal-body">
                      <form data-toggle="validator" id="formUpdateUsuario" action="" method="">

                      <input name="update" hidden />
                      <input hidden id="idUsuario" name="id" value="'.$arrayDadosUsuario['id'].'">
                          <div class="form-group">
                            <label class="control-label" for="nome"></label>
                            <input type="text" id="nomeUsuario" name="nome" value="'.$arrayDadosUsuario['nome'].'" class="form-control" data-error="Digite um Nome de usuário" required />
                            <div class="help-block with-errors"></div>
                        </div>


                        <div class="form-group">
                            <label class="control-label" for="email">Email:</label>
                            <input type="email" id="emailUsuario" name="email" value="'.$arrayDadosUsuario['email'].'" class="form-control" data-error="Digite Email Válido" required></textarea>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="sexo"></label>
                            <select name="sexo" id="sexoUsuario">
                              '.montaSelectSexo($arrayDadosUsuario['sexo']).'
                            </select>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>

                      </form>

              </div>
             </div>
            </div>';

            echo json_encode(['data' => $modal]);
            exit();
}

function montaSelectSexo($sexo)
{
	$selectMasculino = ($sexo == 'm') ? 'selected' : '';
	$selectFeminino  = ($sexo == 'f') ? 'selected' : '';
	$selectOptions   = '<option '.$selectMasculino.' value="m">Masculino</option>
		                      <option '.$selectFeminino.' value="f">Feminino</option>';

    return $selectOptions;                  
}

function exportarListaUsuariosXML()
{
	$conexao = singletonConectionDB();
	$query   = $conexao->prepare("SELECT * FROM usuarios");
	if($query->execute()){
		if($query->rowCount() > 0){
			$listaUsuarios = $query->fetchAll(\PDO::FETCH_ASSOC);
			$xml_data = new SimpleXMLElement('<UsuariosLista></UsuariosLista>');
		    array_to_xml($listaUsuarios, $xml_data);
		    
		    if(!file_put_contents('listaUsuarios.xml', $xml_data->asXML())){
		    	echo json_encode(['error' => true, 'msg' => 'Erro Ao Criar Arquivo XML']);
		    	exit();
		    }
		    
		    if(copy('listaUsuarios.xml', '../tempFiles/listaUsuarios.xml')){
		    	unlink('listaUsuarios.xml');
		    	
		    	/**
		    	 * Caminho Absoluto do Arquivo
		    	$dirDownload = $_SERVER['SERVER_NAME'].'\spaphp\tempFiles\\'.'listaUsuarios.xml';
		    	**/

		    	$dirDownload = 'tempFiles\\'.'listaUsuarios.xml';
		    	
		    	echo json_encode(['error' => false, 
		    		'msg' => 'Documento gerado Com sucesso', 
		    		'dirDownload' => $dirDownload,
		    		]);

		    	exit();
		    }

		} else {
			echo json_encode(['error' => true, 'msg' => 'Não há Registros Disponiveis para gerar Documento']);
			exit();
		}
	} else {
		throw new \PDOException("Error Processing Request", 1);
	}

	
}

function array_to_xml($data, &$xml_data, $itemCustom = 'usuario')
{
	 foreach($data as $key => $value ) {
		   if(is_array($value)) {
		     if(is_numeric($key)){
		        $key = ($itemCustom??'item');
		     }
		   $subnode = $xml_data->addChild($key);
		     array_to_xml($value, $subnode);
		} else {
		   $xml_data->addChild("$key",htmlspecialchars("$value"));
		}
	}
}