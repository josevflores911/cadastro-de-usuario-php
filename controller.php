

<?php
session_start();
require 'conection.php';

// Função para inserir um usuário
function inserir_usuario($nome, $codigo_acesso, $senha, $tipo_usuario_id, $data_inicio, $data_fim, $municipio_id, $agencia_id, $conexao) {
    
    // echo "Nome: $nome, Código de Acesso: $codigo_acesso, Tipo de Usuário: $tipo_usuario_id, Data Início: $data_inicio, Data Fim: $data_fim, Município ID: $municipio_id, Agência ID: $agencia_id<br>";
    
    // Inicia uma transação
    $conexao->begin_transaction();

    try {
        // Inserção na tabela de usuários
        $sql_usuario = "INSERT INTO usuarios (nome, codigo_acesso, senha, tipo_usuario_id) 
                        VALUES ('$nome', '$codigo_acesso', '$senha', $tipo_usuario_id)";
        if ($conexao->query($sql_usuario) === TRUE) {
            // Pega o ID do usuário recém-inserido
            $usuario_id = $conexao->insert_id;

            // Inserção na tabela de períodos de vigência
            $sql_periodo = "INSERT INTO periodos_vigencia (usuario_id, data_inicio, data_fim) 
                            VALUES ($usuario_id, '$data_inicio', '$data_fim')";
            
            if ($conexao->query($sql_periodo) === TRUE) {
                // Inserção na tabela de relacionamentos Usuário-Município-Agência
                $sql_relacionamento = "INSERT INTO usuario_municipio_agencia (usuario_id, municipio_id, agencia_id) 
                                       VALUES ($usuario_id, $municipio_id, $agencia_id)";

                if ($conexao->query($sql_relacionamento) === TRUE) {
                    // Se tudo ocorrer bem, faz o commit da transação
                    $conexao->commit();
                    $_SESSION['mensagem'] = 'Usuário inserido com sucesso!';
                     header('Location: index.php');
                    exit;
                } else {
                    // Se erro no relacionamento, faz rollback e envia a mensagem de erro
                    $conexao->rollback();
                    $_SESSION['mensagem'] = 'Erro ao inserir relacionamento: ' . $conexao->error;
                    echo "Erro xx: " . $conexao->error . "<br>";
                     header('Location: index.php');
                    exit;
                }
            } else {
                // Se erro no período de vigência, faz rollback e envia a mensagem de erro
                echo "Erro xxx: " . $conexao->error . "<br>";
                $conexao->rollback();
                $_SESSION['mensagem'] = 'Erro ao inserir período de vigência: ' . $conexao->error;
                 header('Location: index.php');
                exit;
            }
        } else {
            echo "Erro x: " . $conexao->error . "<br>";
            // Se erro no usuário, faz rollback e envia a mensagem de erro
            $conexao->rollback();
            $_SESSION['mensagem'] = 'Erro ao inserir usuário: ' . $conexao->error;
             header('Location: index.php');
            exit;
        }
    } catch (Exception $e) {
        // Em caso de erro geral, faz rollback e envia a mensagem de erro
        $conexao->rollback();
        $_SESSION['mensagem'] = 'Erro ao processar a transação: ' . $e->getMessage();
         header('Location: index.php');
        exit;
    }
}

// POST - Quando o formulário for enviado
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

    // Chamada para a função inserir_usuario com os dados do formulário
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
?>
