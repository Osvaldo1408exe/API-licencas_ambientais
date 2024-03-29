<?php

//a função calcula a data limite de entrega baseada no vencimento de uma licença
function datalimite($unidade, $data_vencimento, $tipo, $previsao) {
    if (empty($unidade) || empty($data_vencimento)) {
        return "";
    } elseif ($data_vencimento === "-") {
        return "-";
    } elseif (($tipo == "Comissionamento" || $previsao == "Dispensada")) {
        return "-";
    } elseif (($tipo == "LAO" && $previsao == "Renovar")) {
        return date('d-m-Y', strtotime($data_vencimento . ' -4 months'));
    } elseif (($tipo == "Outorga" && $previsao == "Não Renovar")) {
        return date('d-m-Y', strtotime($data_vencimento . ' -3 months'));
    } elseif (($previsao == "Não Prorrogar" || ($tipo == "LAI" || $tipo == "LAP" || $tipo == "LAP/LAI"))) {
        return date('d-m-Y', strtotime($data_vencimento . ' -4 months'));
    } elseif ($previsao == "Não Prorrogar" || $previsao == "Não Renovar") {
        return "-";
    } else {
        return $data_vencimento;
    }
}
?>