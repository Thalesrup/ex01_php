<!DOCTYPE html>
<html>
<head>

    <title>CRUD PHP AJAX Ex01</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>

    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    
    <script src="js/main.js"></script>
</head>
<body>


    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">                    
                <div class="pull-left">
                    <h2>PHP CRUD AJAX Desafio 01</h2>
                </div>
                <div class="pull-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-usuario">
                      criar usuario
                </button>
                <button type="button" id="gerarXMLUsuarios" class="btn btn-success">
                      Exportar XML
                </button>
                </div>
            </div>
        </div>


        <table class="table table-bordered">
            <thead>
                <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Sexo</th>
                <th width="200px">Ação</th>
                </tr>
            </thead>
            <tbody id="listagemUsuarios">

            </tbody>
        </table>


        <ul id="pagination" class="pagination-sm"></ul>

        <div class="modal fade" id="create-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Criar Usuário</h4>
              </div>


              <div class="modal-body">
                      <form id="formInserirUsuario" data-toggle="validator" action="" method="">

                          <input hidden name="insert"/>
                          <div class="form-group">
                            <label class="control-label" for="nome">Nome</label>
                            <input type="text" name="nome" class="form-control" data-error="Digite um Nome de usuário" required />
                            <div class="help-block with-errors"></div>
                        </div>


                        <div class="form-group">
                            <label class="control-label" for="email">Email:</label>
                            <input type="email" name="email" class="form-control" data-error="Digite Email Válido" required></textarea>
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label>Sexo</label>
                            <select name="sexo" class="form-control">
                              <option value="m">Masculino</option>
                              <option value="f">Feminino</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn crud-submit btn-success">Salvar</button>
                        </div>

                      </form>

              </div>
            </div>


          </div>
        </div>

        <div class="modal fade" id="edit-usuario" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          
        </div>

    </div>
</body>
</html>