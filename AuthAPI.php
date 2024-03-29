<?php
require 'conexao.php';

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);

    // Verifica se os campos de usuário e senha foram preenchidos
    if ($data['username'] !== null && $data['password']) {
        $username = $data['username'];
        $password = $data['password'];


        // Configurações do servidor LDAP
        $ldap_server = "ldap://000.000.0.0";
        $ldap_port = 389; // Porta padrão LDAP
        
        // Tenta se conectar ao servidor LDAP
        $ldap_conn = ldap_connect($ldap_server, $ldap_port);

        if ($ldap_conn) {
            // Tentativa de autenticação
            if (@ldap_bind($ldap_conn, $username, $password)) {
                // Autenticação bem-sucedida
                
                
                $_SESSION['username'] = $username;

                // Historico 
                $user_registro = $username;
                date_default_timezone_set('America/Sao_Paulo');
                $data_entrada = date('Y-m-d H:i:s');
                $ip = $_SERVER['REMOTE_ADDR'];

                $update_historico = "INSERT INTO logs (usuario, data_entrada,ip) VALUES ('$user_registro', '$data_entrada', '$ip')";
                $result_historico = pg_query($conn, $update_historico);




                // Cria um array associativo com os dados de resposta
                $response = array(
                    "auth" => true,
                    "username" => $username
                );

                // Define o cabeçalho Content-Type para JSON
                header("Content-Type: application/json");

                // Retorna os dados de resposta em formato JSON
                echo json_encode($response);

                

                exit; // Importante: encerra o script PHP após o redirecionamento

            } else {// Falha na autenticação
                
                
                //  array associativo com a resposta
                $response = array(
                    "auth" => false,
                    "motivo" => "Login incorreto"
                );

                // Define o cabeçalho Content-Type para JSON
                header("Content-Type: application/json");

                // Retorna os dados de resposta em formato JSON
                echo json_encode($response);

                exit; // Importante: encerra o script PHP após o retorno da requisição
            }
        } else {
            //  array associativo com a resposta
            $response = array(
                "auth" => false,
                "motivo" => "Falha na conexão com o servidor LDAP"
            );

            // Define o cabeçalho Content-Type para JSON
            header("Content-Type: application/json");

            // Retorna os dados de resposta em formato JSON
            echo json_encode($response);

            exit; // Importante: encerra o script PHP após o retorno da requisição
        }

        // Feche a conexão 
        ldap_close($ldap_conn);
    } else {
        
       // Cria um array associativo com os dados de resposta
        $response = array(
            "auth" => false,
            "motivo" => "Campos de usuário ou senha em falta"
        );

        // Define o cabeçalho Content-Type para JSON
        header("Content-Type: application/json");

        // Retorna os dados de resposta em formato JSON
        echo json_encode($response);

    }
}

?>

