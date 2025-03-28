

<?php
session_start();
require 'conection.php';


function inserir_usuario($nome, $codigo_acesso, $senha, $tipo_usuario_id, $data_inicio, $data_fim, $municipio_id, $agencia_id, $conexao) {
    
    // echo "Nome: $nome, Código de Acesso: $codigo_acesso, Tipo de Usuário: $tipo_usuario_id, Data Início: $data_inicio, Data Fim: $data_fim, Município ID: $municipio_id, Agência ID: $agencia_id<br>";
    
    // Inicia uma transação
    $conexao->begin_transaction();

    try {
        // Inserção na tabela de usuários
        $sql_usuario = "INSERT INTO usuarios (nome, codigo_acesso, senha, tipo_usuario_id) 
                        VALUES ('$nome', '$codigo_acesso', '$senha', $tipo_usuario_id)";
        if ($conexao->query($sql_usuario) === TRUE) {
          
            $usuario_id = $conexao->insert_id;

            // Inserção na tabela de períodos de vigência
            $sql_periodo = "INSERT INTO periodos_vigencia (usuario_id, data_inicio, data_fim) 
                            VALUES ($usuario_id, '$data_inicio', '$data_fim')";
            
            if ($conexao->query($sql_periodo) === TRUE) {
                // Inserção na tabela de relacionamentos Usuário-Município-Agência
                $sql_relacionamento = "INSERT INTO usuario_municipio_agencia (usuario_id, municipio_id, agencia_id) 
                                       VALUES ($usuario_id, $municipio_id, $agencia_id)";

                if ($conexao->query($sql_relacionamento) === TRUE) {
                    $conexao->commit();
                    $_SESSION['mensagem'] = 'Usuário inserido com sucesso!';
                    header('Location: index.php');
                    exit;
                } else {
                  
                    $conexao->rollback();
                    $_SESSION['mensagem'] = 'Erro ao inserir relacionamento: ' . $conexao->error;
                    // echo "Erro xx: " . $conexao->error . "<br>";
                     header('Location: index.php');
                    exit;
                }
            } else {
               
                // echo "Erro xxx: " . $conexao->error . "<br>";
                $conexao->rollback();
                $_SESSION['mensagem'] = 'Erro ao inserir período de vigência: ' . $conexao->error;
                 header('Location: index.php');
                exit;
            }
        } else {
            // echo "Erro x: " . $conexao->error . "<br>";
          
            $conexao->rollback();
            $_SESSION['mensagem'] = 'Erro ao inserir usuário: ' . $conexao->error;
             header('Location: index.php');
            exit;
        }
    } catch (Exception $e) {
      
        echo "Erro xxxx: " . $conexao->error . "<br>";
        $conexao->rollback();
        $_SESSION['mensagem'] = 'Erro ao processar a transação: ' . $e->getMessage();
         header('Location: index.php');
        exit;
    }
}

//GET_BY_ID
function get_usuario($usuario_id, $conexao) {
    $usuario = null;

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

   
    if ($stmt = $conexao->prepare($sql)) {
        
        $stmt->bind_param("i", $usuario_id);  // "i" means integer
    
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Check if any rows were returned
            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
            }
        } else {
            echo "Error executing the query: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing the query: " . $conexao->error;
    }

    return $usuario;
}


function atualizar_usuario($usuario_id, $nome, $codigo_acesso, $senha, $tipo_usuario_id, $data_inicio, $data_fim, $municipio_id, $agencia_id, $conexao) {

    $conexao->begin_transaction();

    try {
       
        $sql_usuario = "UPDATE usuarios 
                        SET nome = '$nome', codigo_acesso = '$codigo_acesso', senha = '$senha', tipo_usuario_id = $tipo_usuario_id
                        WHERE id = $usuario_id";

        if ($conexao->query($sql_usuario) === TRUE) {
          
            $sql_periodo = "UPDATE periodos_vigencia 
                            SET data_inicio = '$data_inicio', data_fim = '$data_fim'
                            WHERE usuario_id = $usuario_id";
            
            if ($conexao->query($sql_periodo) === TRUE) {
        
                $sql_relacionamento = "UPDATE usuario_municipio_agencia 
                                       SET municipio_id = $municipio_id, agencia_id = $agencia_id 
                                       WHERE usuario_id = $usuario_id";

                if ($conexao->query($sql_relacionamento) === TRUE) {
                  
                    $conexao->commit();
                    $_SESSION['mensagem'] = 'Usuário atualizado com sucesso!';
                    header('Location: index.php');
                    exit;
                } else {
                    
                    $conexao->rollback();
                    $_SESSION['mensagem'] = 'Erro ao atualizar relacionamento: ' . $conexao->error;
                    echo "Erro xx: " . $conexao->error . "<br>";
                    // header('Location: index.php');
                    exit;
                }
            } else {
               
                echo "Erro xxx: " . $conexao->error . "<br>";
                $conexao->rollback();
                $_SESSION['mensagem'] = 'Erro ao atualizar período de vigência: ' . $conexao->error;
                header('Location: index.php');
                exit;
            }
        } else {
           
            echo "Erro x: " . $conexao->error . "<br>";
            $conexao->rollback();
            $_SESSION['mensagem'] = 'Erro ao atualizar usuário: ' . $conexao->error;
            header('Location: index.php');
            exit;
        }
    } catch (Exception $e) {
       
        echo "Erro xxxx: " . $conexao->error . "<br>";
        $conexao->rollback();
        $_SESSION['mensagem'] = 'Erro ao processar a transação: ' . $e->getMessage();
        header('Location: index.php');
        exit;
    }
}


// POST
if (isset($_POST['create_usuario'])) {
    // Usando mysqli_real_escape_string para prevenir SQL Injection
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $codigo_acesso = mysqli_real_escape_string($conexao, trim($_POST['codigo']));
    $senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));
    $tipo_usuario = mysqli_real_escape_string($conexao, trim($_POST['tipo_usuario']));
    $estado = mysqli_real_escape_string($conexao, trim($_POST['estado']));
    $municipio = mysqli_real_escape_string($conexao, trim($_POST['municipio']));
    $agencia = mysqli_real_escape_string($conexao, trim($_POST['agencia']));
    $inicio_periodo = mysqli_real_escape_string($conexao, trim($_POST['inicio_periodo']));
    $fim_periodo = mysqli_real_escape_string($conexao, trim($_POST['fim_periodo']));


    // echo "Nome: $nome, Código de Acesso: $codigo_acesso, Tipo de Usuário: $tipo_usuario_id, Data Início: $data_inicio, Data Fim: $data_fim, Município ID: $municipio_id, Agência ID: $agencia_id<br>";
    inserir_usuario($nome, $codigo_acesso, $senha, $tipo_usuario, $inicio_periodo, $fim_periodo, $municipio, $agencia, $conexao);
}

//GET

//DELETE
if (isset($_POST['delete_usuario'])) {
    $usuario_id = mysqli_real_escape_string($conexao, $_POST['delete_usuario']);
    
    // Iniciar uma transação para garantir a integridade dos dados
    $conexao->begin_transaction();

    try {
        // Excluir registros na tabela periodos_vigencia
        $sql1 = "DELETE FROM periodos_vigencia WHERE usuario_id='$usuario_id'";
        $result1 = $conexao->query($sql1);
        
        if (!$result1) {
            throw new Exception("Erro ao deletar da tabela periodos_vigencia: " . mysqli_error($conexao));
        }
        
        // Excluir o usuário da tabela usuarios
        $sql2 = "DELETE FROM usuarios WHERE id='$usuario_id'";
        $result2 = $conexao->query($sql1);

        if (!$result2) {
            throw new Exception("Erro ao deletar da tabela usuarios: " . mysqli_error($conexao));
        }

        // Se ambas as queries foram executadas com sucesso, confirma a transação
        $conexao->commit();
        // mysqli_commit($conexao);

        
        // echo "Erro x: " . $conexao->error . "<br>";
        // Mensagem de sucesso
        $_SESSION['message'] = 'Usuário deletado com sucesso!';
        header('Location:index.php');
        exit;
    } catch (Exception $e) {
        // Em caso de erro, faz rollback da transação
        echo "Erro x: " . $conexao->error . "<br>";
        $conexao->rollback();
        // mysqli_rollback($conexao);
        
        // Exibe a mensagem de erro
        $_SESSION['message'] = 'Erro ao deletar o usuário: ' . $e->getMessage();
        header('Location:index.php');
        exit;
    }
}


//PUT
if(isset($_POST['update_usuario'])){
    $usuario_id=mysqli_real_escape_string($conexao,$_POST['usuario_id']);
    $nome = mysqli_real_escape_string($conexao, trim($_POST['nome']));
    $codigo_acesso = mysqli_real_escape_string($conexao, trim($_POST['codigo']));
    $senha = mysqli_real_escape_string($conexao, trim($_POST['senha']));
  
    $usuario=get_usuario($usuario_id,$conexao);

    if (isset($_POST['tipo_usuario'])) {
        $tipo_usuario = mysqli_real_escape_string($conexao, trim($_POST['tipo_usuario']));
    } else {
        $tipo_usuario=$usuario['tipo_usuario_id'];
    }
    // $estado = mysqli_real_escape_string($conexao, trim($_POST['estado']));
    if(isset($_POST['municipio'])){
        $municipio = mysqli_real_escape_string($conexao, trim($_POST['municipio']));
    }else{
        $municipio=$usuario['municipio_id'];
    }
    if(isset($_POST['agencia'])){
        $agencia = mysqli_real_escape_string($conexao, trim($_POST['agencia']));
    }else{
        $agencia=$usuario['agencia_id'];
    }
    $inicio_periodo = mysqli_real_escape_string($conexao, trim($_POST['inicio_periodo']));
    $fim_periodo = mysqli_real_escape_string($conexao, trim($_POST['fim_periodo']));

    // echo '<pre>';
    // var_dump($_POST);
    // echo '</pre>';
    //  echo "id: $usuario_id, Nome: $nome, Código de Acesso: $codigo_acesso,senha; $senha  , Tipo de Usuário: $tipo_usuario, Data Início: $inicio_periodo, Data Fim: $fim_periodo , Município ID: $municipio, Agência ID: $agencia<br>";

    atualizar_usuario($usuario_id,$nome, $codigo_acesso, $senha, $tipo_usuario, $inicio_periodo, $fim_periodo, $municipio, $agencia, $conexao);

}




?>
