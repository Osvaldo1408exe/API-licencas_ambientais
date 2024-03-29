<?php

function tramitacao($data_emissao, $data_requerimento) {
    if (empty($data_emissao) || empty($data_requerimento) || $data_emissao === "-" || $data_requerimento === "-") {
        return "-";
    }

    // converção de datas para o formato correto (dd/mm/yyyy)
    $dataEmissao = DateTime::createFromFormat('d/m/Y', $data_emissao);
    $dataRequerimento = DateTime::createFromFormat('d/m/Y', $data_requerimento);

    if (!$dataEmissao || !$dataRequerimento) {
        return "ERRO: Formato de data inválido";
    }

    $hoje = new DateTime();

    if ($dataRequerimento > $hoje) {
        return "ERRO: Data de requerimento posterior à data atual";
    }

    $diferenca = $dataEmissao->diff($dataRequerimento);
    return $diferenca->days;
}

?>