<?php
require_once "conexao.php";

// Permitir CORS
header('Access-Control-Allow-Origin: *'); // atualmente permite todos, altere para um especifico
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

// Executa a consulta SQL para selecionar os dados da tabela
$sql = "SELECT * FROM licencas ORDER BY area, unidade";
$resultado = pg_query($conn, $sql);
if (!$resultado) {
    die("Erro ao executar a consulta SQL.");
}

// Cria um array para armazenar os resultados da consulta
$dados = array();

// Itera sobre os resultados e adicione-os ao array
while ($linha = pg_fetch_assoc($resultado)) {
   

    // Reorganiza as chaves do array associativo conforme a solicitação para ser apresentada em ordem 
    $linhaOrdenada = array(
        'id' => $linha['id'],
        'area' => $linha['area'],
        'unidade' => $linha['unidade'],
        'sub_unidade' => $linha['sub_unidade'],
        'data_requerimento' => $linha['data_requerimento'],
        'controle' => $linha['controle'],
        'orgao_emissor' => $linha['orgao_emissor'],
        'tipo' => $linha['tipo'],
        'especificacao' => $linha['especificacao'],
        'numero_licenca' => $linha['numero_licenca'],
        'num_processo_sei' => $linha['num_processo_sei'],
        'data_emissao' => $linha['data_emissao'],
        'data_vencimento' => $linha['data_vencimento'],
        'previsao' => $linha['previsao'],
        'requerimento' => $linha['requerimento'],
        'data_protocolo_orgao' => $linha['data_protocolo_orgao'],
        'emitida_nova_licenca' => $linha['emitida_nova_licenca'],
        'situacao_processo' => $linha['situacao_processo'],
        'atualizado_sa' => $linha['atualizado_sa'],
        'observacoes' => $linha['observacoes'],
        'providenciar_doc' => $linha['providenciar_doc'],
        'datalimite' => $linha['datalimite'],
        'tempo_tramitacao' => $linha['tempo_tramitacao'],
        'situacao_licenca' => $linha['situacao_licenca'],
        'setor_responsavel' => $linha['setor_responsavel'],
        'dias_para_vencer' => $linha['dias_para_vencer']
    );

    $dados[] = $linhaOrdenada;
}

// Define o cabeçalho Content-Type como UTF-8
header('Content-Type: application/json; charset=utf-8');

// Converte os dados para o formato JSON e imprime
echo json_encode($dados);

// Fecha a conexão com o banco
pg_close($conn);
?>
