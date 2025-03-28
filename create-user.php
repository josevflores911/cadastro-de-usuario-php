<?php
// Conexão com o banco de dados
include('conection.php');

// Consulta para pegar os tipos de usuários
$sql_tipos = 'SELECT * FROM tipos_usuario';
$tipos = mysqli_query($conexao, $sql_tipos);

// Consulta para pegar os estados
$sql_estados = 'SELECT * FROM estados';
$estados = mysqli_query($conexao, $sql_estados);

// Consulta para pegar os municípios
$sql_municipios = 'SELECT * FROM municipios';
$municipios = mysqli_query($conexao, $sql_municipios);

// Consulta para pegar as agências
$sql_agencias = 'SELECT * FROM agencias';
$agencias = mysqli_query($conexao, $sql_agencias);
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Usuário - Criar</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="styles.css">
        
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
                        <!-- Primeira linha (nome, código e senha) -->
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" maxlength="50" placeholder="Ingresse seu nome" required>
                            </div>
                            <div class="col-md-4">
                                <label>Código</label>
                                <input type="text" name="codigo" class="form-control" maxlength="20" minlength="4" placeholder="Ingresse o código" required>
                            </div>
                            <div class="col-md-4">
                                <label>Senha</label>
                                <input type="password" name="senha" class="form-control" maxlength="20" placeholder="Ingresse sua senha" required>
                            </div>
                        </div>

                        <!-- Segunda linha (tipo de usuário, estado, município e agência) -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Tipo de Usuário</label>
                                <select name="tipo_usuario" class="form-select" required>
                                    <option value="" disabled selected>Selecione um tipo de usuário</option>
                                    <?php
                                    if (mysqli_num_rows($tipos) > 0) {
                                        while ($tipo = mysqli_fetch_assoc($tipos)) {
                                            echo "<option value='{$tipo['id']}'>{$tipo['tipo']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Estado</label>
                                <select name="estado" class="form-select" required>
                                    <option value="" disabled selected>Selecione um estado</option>
                                    <?php
                                    if (mysqli_num_rows($estados) > 0) {
                                        while ($estado = mysqli_fetch_assoc($estados)) {
                                            echo "<option value='{$estado['id']}'>{$estado['nome']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Município (x->estado)</label>
                                <select class="form-select" id="municipio" name="municipio" required>
                                    <option value="" disabled selected>Selecione um município</option>
                                    <?php
                                    if (mysqli_num_rows($municipios) > 0) {
                                        while ($municipio = mysqli_fetch_assoc($municipios)) {
                                            echo "<option value='{$municipio['id']}'>{$municipio['nome']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Agência (x->estado/municipio)</label>
                                <select name="agencia" class="form-select" required>
                                    <option value="" disabled selected>Selecione uma agência</option>
                                    <?php
                                    if (mysqli_num_rows($agencias) > 0) {
                                        while ($agencia = mysqli_fetch_assoc($agencias)) {
                                            echo "<option value='{$agencia['id']}'>{$agencia['nome']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Terceira linha (período início e fim) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Período - Início</label>
                                <input id="data_inicio" type="date" name="inicio_periodo" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Período - Fim</label>
                                <input id="data_fim" type="date" name="fim_periodo" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" name="create_usuario" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                        <!-- </div> -->

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