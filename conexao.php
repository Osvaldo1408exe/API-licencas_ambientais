<?php

// Parâmetros de conexão com o banco de dados PostgreSQL
$host = "000.000.0.0"; //  endereço IP do servidor PostgreSQL
$port = "5432"; // porta padrão 
$dbname = "nome_banco"; //nome do banco
$user = "postgres"; //usuario padrão
$password = "senha_banco"; //senha banco

// Conecta-se ao banco de dados PostgreSQL
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Erro de conexão com o banco de dados.");
}


?>