<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Usuário - Criar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
        <style>
        body {
            background: rgb(2,0,36);
            background: linear-gradient(0deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 54%, rgba(0,102,255,1) 99%);
            color: white; /* Opcional: para garantir que o texto seja visível em cima do fundo escuro */
        }
    </style>
    </head>
    <body>
        <?php include('navbar.php'); ?>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Adicionar usuario
                                <a href="index.php" class="btn btn-danger floar-end">Voltar</a>
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="controller.php" method="POST">
                                <div class="mb-3">
                                    <label>Nome</label>
                                    <input type="text" name="nome" class="form-control" maxlength="50"  placeholder="Ingresse seu nome" required>
                                </div>
                                <div class="mb-3">
                                    <label>Código</label>
                                    <input type="text" name="codigo" class="form-control" maxlength="20" minlength="4" placeholder="Ingresse o codigo" required>
                                </div>
                                <div class="mb-3">
                                    <label>Senha</label>
                                    <input type="password" name="senha" class="form-control" maxlength="20" placeholder="Ingresse sua senha" required>
                                </div>
                                <div class="mb-3"><!--enum from database-->
                                    <label>Tipo de Usuário</label>
                                    <select name="tipo_usuario" class="form-select" required>
                                        <option value="" disabled selected>Selecione un tipo de usuario</option>
                                        <option value="1">Elaborador</option>
                                        <option value="2">Validador</option>
                                        <option value="3">Validador+</option>
                                    </select>
                                </div>
                                <div class="mb-3"><!--from database-->
                                    <label>Estado</label>
                                    <select name="estado" class="form-select" required>
                                        <option value="" disabled selected>Selecione un estado</option>
                                        <option value="1">São Paulo</option>
                                        <option value="2">Rio de Janeiro</option>
                                        <option value="3">Minas Gerais</option>
                                        <option value="4">Bahia</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="municipio" class="form-label">Município</label>
                                    <select class="form-select" id="municipio" name="municipio" required>
                                    <option value="" disabled selected>Selecione un municipio</option>
                                    <option value="1">São Paulo</option>
                                    <option value="2">Campinas</option>
                                    <option value="3">Rio de Janeiro</option>
                                    <option value="4">Niterói</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>Agência</label>
                                    <select name="agencia" class="form-select" required>
                                        <option value="" disabled selected>Selecione uma agencia</option>
                                        <option value="1">Agência Paulista</option>
                                        <option value="2">Agência Campinas</option>
                                        <option value="3">Agência Carioca</option>
                                        <option value="4">Agência Niterói</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label>periodo-Inicio</label>
                                    <input id="data_inicio" type="date" name="inicio_periodo" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label>periodo-Fim</label>
                                    <input id="data_fim" type="date" name="fim_periodo" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="create_usuario" class="btn btn-primary">Salvar</button>
                                </div>    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script>
        // Define a data atual no formato YYYY-MM-DD para o campo de data
        const today = new Date().toISOString().split('T')[0];

        document.getElementById('data_inicio').value = today;
        document.getElementById('data_fim').value = today;
    </script>
</html>