<?php
//está função calcula o tempo que o setor ambiental deve começar a providenciar os documentos da licença
function providenciar_doc($unidade, $data_vencimento, $tipo, $previsao, $controle) {
    
    if ($data_vencimento === "-") {
        return null; 
    }

    if (empty($unidade) || empty($data_vencimento)) {
        $provi_doc = "";
    } else {
        if ($data_vencimento === "-") {
            $provi_doc = "-";
        } else {
            if ($tipo === "Comissionamento" || $previsao === "Dispensada") {
                $provi_doc = "";
            } else {
                if ($controle === "Estudo" || $controle === "Protocolo" || $previsao === "Não Renovar") {
                    $provi_doc = "";
                } else {
                    $dataVencimentoObj = DateTime::createFromFormat('d/m/Y', $data_vencimento);

                    if (!$dataVencimentoObj) {
                        return "ERRO: Formato de data inválido";
                    }

                    if ($controle === "Condicionante" || $controle === "Info_Complementar" || $previsao === "Prorrogar") {
                        $dataVencimentoObj->modify('-1 month');
                        $provi_doc = $dataVencimentoObj->format('d-m-Y');
                    } elseif ($previsao === "Não Prorrogar" || ($tipo === "LAI" || $tipo === "LAP" || $tipo === "LAP/LAI")) {
                        $dataVencimentoObj->modify('-6 months');
                        $provi_doc = $dataVencimentoObj->format('d-m-Y');
                    } elseif ($tipo === "AuA") {
                        $dataVencimentoObj->modify('-3 months');
                        $provi_doc = $dataVencimentoObj->format('d-m-Y');
                    } elseif ($tipo === "LAO") {
                        $dataVencimentoObj->modify('-6 months');
                        $provi_doc = $dataVencimentoObj->format('d-m-Y');
                    } elseif ($tipo === "Outorga") {
                        $dataVencimentoObj->modify('-4 months');
                        $provi_doc = $dataVencimentoObj->format('d-m-Y');
                    } elseif ($previsao === "Renovar") {
                        $dataVencimentoObj->modify('-1 month');
                        $provi_doc = $dataVencimentoObj->format('d-m-Y');
                    } elseif ($previsao === "Não Prorrogar") {
                        $provi_doc = "-";
                    } else {
                        $provi_doc = "ERRO";
                    }
                }
            }
        }
    }
    
    return $provi_doc;
}

$unidade = "DMC Iririú e Jardim Iririú";
$data_vencimento = "21/10/2023";
$previsao = "Renovar";
$controle = "Autorização";
$tipo = "DANC";

$rst = providenciar_doc($unidade, $data_vencimento, $tipo, $previsao, $controle);
echo $rst;


?>