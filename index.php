<?php
// require 'conection.php'
include('conection.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Garante que o fundo cubra a tela inteira */
        html, body {
            height: 100%; /* Faz com que o HTML e o Body ocupem toda a altura da janela */
            margin: 0; /* Remove as margens padrão */
        }

        body {
            background: rgb(2,0,36); 
            background: linear-gradient(0deg, rgba(2,0,36,1) 0%, rgba(9,9,121,1) 54%, rgba(0,102,255,1) 99%);
            color: white;
            font-family: Arial, sans-serif;
            /* display: flex; */
            /* justify-content: center; */
            /* align-items: center; */
            text-align: center;
            height: 100%;
        }

        h1 {
            margin-bottom: 20px;
        }

        p {
            font-size: 1.2em;
        }
    </style>
  </head>
  <body>

    <?php include('navbar.php'); ?>
    <div class="container mt-4">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h4> Lista de usuarios
                <a href="create-user.php" class="btn btn-primary float-end">Adicionar usuario</a>
              </h4>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Codigo</th>
                    <th>Tipo Usuário</th>
                    <th>Estados</th>
                    <th>Municipios</th>
                    <th>Agencias</th>
                    <th>inicio</th>
                    <th>fim</th> <!-- ate 90 dias -->
                  </tr>
                </thead>
                <tbody>
                  <?php
                    $sql =  'SELECT 
                                  u.id AS id,
                                  u.nome AS nome,
                                  u.codigo_acesso AS codigo_acesso,
                                  tu.tipo AS tipo_usuario,
                                  e.nome AS estado,
                                  m.nome AS municipio,
                                  a.nome AS agencia,
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
                                  periodos_vigencia pv ON u.id = pv.usuario_id';
                    $usuarios = mysqli_query($conexao,$sql);
                    if(mysqli_num_rows($usuarios)>0){
                      foreach($usuarios as $usuario){
                    
                  ?>
                  <tr>
                    <td><?=$usuario['id'] ?></td>
                    <td><?=$usuario['nome']?></td>
                    <td><?=$usuario['codigo_acesso']?></td>
                    <td><?=$usuario['tipo_usuario']?></td>
                    <td><?=$usuario['estado']?></td>
                    <td><?=$usuario['municipio']?></td>
                    <td><?=$usuario['agencia']?></td>
                    <td><?=date('d/m/Y', strtotime($usuario['data_inicio']))?></td>
                    <td><?=date('d/m/Y', strtotime($usuario['data_fim']))?></td>
                    <td>
                      <!-- <a href="" class="btn btn-secondary btn-sm">Visualizar</a> -->
                       <div style="display:flex; flex-direction:row; justify-content:space-around">
                         <div>
                           <a href="" class="btn btn-success btn-sm">Editar</a>
                         </div>
                         <div>
                           <form action="" method="POST" class="">
                             <button onclick="" type="submit" name="" value="" class="btn btn-danger btn-sm">
                               <span class="bi-trash3-fill"></span>&nbsp;Excluir
                             </button>
                           </form>
                         </div>
                       </div>
                    </td>
                  </tr>
                  <?php
                  }
                 } else {
                   echo '<h5>Nenhum usuário encontrado</h5>';
                 }
                 ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>  
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>