<?php
session_start();
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
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Usuário - Editar</title>
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
                        <h4>Editar usuario
                            <a href="index.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <?php
                        if(isset($_GET['id'])){
                            $usuario_id=mysqli_real_escape_string($conexao,$_GET['id']);
                            $sql = "SELECT 
                                        u.id AS id,
                                        u.nome AS nome,
                                        u.codigo_acesso AS codigo_acesso,
                                        u.senha,
                                        u.tipo_usuario_id,
                                        tu.tipo AS tipo_usuario,
                                        e.nome AS estado,
                                        e.id AS estado_id,
                                        m.nome AS municipio,
                                        m.id AS municipio_id,
                                        a.nome AS agencia,
                                        a.id AS agencia_id,
                                        pv.data_inicio,
                                        pv.data_fim
                                    FROM 
                                        usuarios u
                                    JOIN 
                                        tipos_usuario tu ON u.tipo_usuario_id = tu.id
                                    JOIN 
                                        usuario_municipio_agencia uma ON u.id = uma.usuario_id
                                    JOIN 
                                        municipios m ON uma.municipio_id = m.id
                                    JOIN 
                                        agencias a ON uma.agencia_id = a.id
                                    JOIN 
                                        estados e ON m.estado_id = e.id
                                    JOIN 
                                        periodos_vigencia pv ON u.id = pv.usuario_id
                                    WHERE 
                                        u.id = ?";

                                    // Preparando o statement
                                    $stmt = $conexao->prepare($sql);

                                    // Ligando o parâmetro para a consulta preparada
                                    $stmt->bind_param("i", $usuario_id);  // "i" para um inteiro

                                    // Executando a consulta
                                    $stmt->execute();

                                    // Obtendo o resultado
                                    $result = $stmt->get_result();
                                    if ($result->num_rows > 0) {
                                        // $usuario = mysqli_fetch_array($query);
                                        $usuario = $result->fetch_assoc();
                                    ?>


                        <form action="controller.php" method="POST">
                                <input type="hidden" name="usuario_id" value="<?=$usuario['id']?>">

                                <div class="row mb-3">         
                                    <div class="col-md-4">
                                        <label>Nome</label>
                                        <input type="text" name="nome"  value="<?=$usuario['nome']?>"  class="form-control" maxlength="50"  placeholder="Ingresse seu nome" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Código</label>
                                        <input type="text" name="codigo"  value="<?=$usuario['codigo_acesso']?>"  class="form-control" maxlength="20" minlength="4" placeholder="Ingresse o codigo" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Senha</label>
                                        <input type="password" name="senha"  value="<?=$usuario['senha']?>"  class="form-control" maxlength="20" placeholder="Ingresse sua senha" required>
                                    </div>
                                    
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3"><!--enum from database-->
                                        <label>Tipo de Usuário</label>
                                        <select name="tipo_usuario" class="form-select" required>
                                            <option  value="<?=$usuario['tipo_usuario_id']?>"  disabled selected> <?=$usuario['tipo_usuario']?> </option>
                                            <?php
                                                if (mysqli_num_rows($tipos) > 0) {
                                                    while ($tipo = mysqli_fetch_assoc($tipos)) {
                                                        echo "<option value='{$tipo['id']}'>{$tipo['tipo']}</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3"><!--from database-->
                                        <label>Estado</label>
                                        <select name="estado" class="form-select" required>
                                            <option  value="<?=$usuario['estado_id']?>"  disabled selected> <?=$usuario['estado']?> </option>
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
                                        <label for="municipio" class="form-label">Município (x->estado)</label>
                                        <select class="form-select" id="municipio" name="municipio" required>
                                        <option value="<?=$usuario['municipio_id']?>" disabled selected><?=$usuario['municipio']?></option>
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
                                            <option value="<?=$usuario['agencia_id']?>" disabled selected><?=$usuario['agencia']?></option>
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

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label>periodo-Inicio</label>
                                        <input id="data_inicio" type="date" name="inicio_periodo" class="form-control"  value="<?=$usuario['data_inicio']?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>periodo-Fim</label>
                                        <input id="data_fim" type="date" name="fim_periodo" class="form-control"  value="<?=$usuario['data_fim']?>" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" name="update_usuario" class="btn btn-primary">Salvar</button>
                                </div>    
                            </form>
                        <?php
                                    }else{

                                    }
                                    $stmt->close();
                                }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </body>
</html>