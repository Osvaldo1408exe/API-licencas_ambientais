<?php
require 'conexao.php'; // Arquivo de conexão com o banco de dados
require 'funcoes/providenciarDoc.php'; // Função de providenciar documento
require 'funcoes/datalimite.php'; // Função de cálculo de data limite
require 'funcoes/situacao.php'; // Função de verificação de situação
require 'funcoes/tramitacao.php'; // Função de cálculo de tramitação

// Permitir CORS
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type');

// Verifica se a requisição é do tipo PUT
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Recupera os dados da requisição POST
    $data = json_decode(file_get_contents('php://input'), true);

    //  para cada campo que não é obrigatório, faz uma verificação se ele está vazio e atribui um valor padrão
    $data_requerimento = isset($data['data_requerimento']) ? $data['data_requerimento'] : "-";
    $data_protocolo_orgao = isset($data['data_protocolo_orgao']) ? $data['data_protocolo_orgao'] : "-";

    // Executa as funções necessárias para calcular valores
    $datalimite = datalimite($data['unidade'], $data['data_vencimento'], $data['tipo'], $data['previsao']);
    $tramitacao = tramitacao($data['data_emissao'], $data['data_requerimento']);
    $providenciar_doc = providenciar_doc($data['unidade'], $data['data_vencimento'], $data['tipo'], $data['previsao'], $data['controle']);
    $situacao = situacao($data['unidade'], $data['data_emissao'], $data['previsao'], $data['situacao_processo'], $data['controle'], $data['emitida_nova_licenca'], $data['data_vencimento'], $data['requerimento'], $providenciar_doc, $datalimite);
    
    // Atualiza o registro no banco de dados
    $update = "UPDATE licencas SET area='{$data['area']}', unidade='{$data['unidade']}', sub_unidade='{$data['subunidade']}', data_requerimento='$data_requerimento', controle='{$data['controle']}', orgao_emissor='{$data['orgao_emissor']}', tipo='{$data['tipo']}', especificacao='{$data['especificacao']}', numero_licenca='{$data['numero_licenca']}', fcei_sinfat='{$data['fcei_sinfat']}', num_processo_sinfat='{$data['num_processo_sinfat']}', sgpe='{$data['sgpe']}', num_processo_sei='{$data['num_processo_sei']}', data_emissao='{$data['data_emissao']}', data_vencimento='{$data['data_vencimento']}', previsao='{$data['previsao']}', requerimento='{$data['requerimento']}', data_protocolo_orgao='$data_protocolo_orgao', emitida_nova_licenca='{$data['emitida_nova_licenca']}', situacao_processo='{$data['situacao_processo']}', atualizado_sa='{$data['atualizado_sa']}', observacoes='{$data['observacoes']}', providenciar_doc='$providenciar_doc', datalimite='$datalimite', situacao_licenca='$situacao', tempo_tramitacao='$tramitacao', setor_responsavel='{$data['setor']}' WHERE id={$data['id']}";

    // Executa a consulta no banco de dados
    $result = pg_query($conn, $update);
    
    // Verifica se a consulta foi bem-sucedida
    if ($result) {
        // Retorna uma resposta de sucesso em formato JSON
        http_response_code(200);
        echo json_encode(['mensagem' => 'Registro atualizado com sucesso']);
    } else {
        // Retorna uma resposta de erro em formato JSON
        http_response_code(500);
        echo json_encode(['erro' => 'Erro na atualização do registro: ' . pg_last_error($conn)]);
    }
} else {
    // Retorna uma resposta de erro para métodos não permitidos em formato JSON
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
}
?>
