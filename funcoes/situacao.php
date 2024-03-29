<?php

//função calcula qual a situação da licença para o setor saber quais são prioridade no momento
function situacao($unidade, $data_emissao, $previsao, $situacao_processo, $controle, $emitida_nova_licenca, $data_vencimento, $requerimento, $providenciar_doc, $datalimite) 
{
    if (empty($unidade) || empty($data_emissao) || empty($data_vencimento)) {
        return "";
    }

    if ($data_emissao == "-") {
        return "Aguardando análise";
    }

    if ($previsao == "Resolução alterada - Porte inferior a P") {
        return $previsao;
    }

    elseif ($situacao_processo == "Concluído" && $emitida_nova_licenca != "SIM") {
        return "Processo Concluído";
    }


    if($controle == "Autorização"){
        if($requerimento == "SIM" && $situacao_processo != "Concluído"){
            if($previsao == "Prorrogar" || $previsao == "Renovar"){
                return "Em renovação";
            }
            
        }

        if ($previsao == "Não Prorrogar" || $previsao == "Não Renovar" || $previsao == "-") {
            if ($data_vencimento != "-") {
                $dataAtual = strtotime("now");
                $dataVencimento = strtotime(str_replace("/", "-", $data_vencimento));
    
                if ($dataAtual < $dataVencimento) {
                    return "Vigente";
                } else {
                    return "Inválida";
                }
            }
        }
    
        if ($requerimento == "SIM" || $previsao == "Prorrogar" || $previsao == "Renovar") {
            if ($providenciar_doc != "-" && $datalimite != "-") {
                $dataAtual = strtotime("now");
                $providenciarDoc = strtotime(str_replace("/", "-", $providenciar_doc));
                $dataLimite = strtotime(str_replace("/", "-", $datalimite));
    
                if ($dataAtual < $providenciarDoc) {
                    return "Vigente";
                }
                if ($dataAtual < $dataLimite) {
                    return "Vigente - Providenciar Documentos";
                }
                if($situacao_processo == "Concluído"){
                    return "Inválida";
                }
                return "Vencida";
            }
        }
    }
    if ($previsao == "Não Prorrogar" || $previsao == "Não Renovar" || $previsao == "-") {
        if ($data_vencimento != "-") {
            $dataAtual = strtotime("now");
            $dataVencimento = strtotime(str_replace("/", "-", $data_vencimento));

            if ($dataAtual < $dataVencimento) {
                return "Vigente";
            } else {
                return "Inválida";
            }
        }
    }

    if ($requerimento == "SIM" || $previsao == "Prorrogar" || $previsao == "Renovar") {
        if ($providenciar_doc != "-" && $datalimite != "-") {
            $dataAtual = strtotime("now");
            $providenciarDoc = strtotime(str_replace("/", "-", $providenciar_doc));
            $dataLimite = strtotime(str_replace("/", "-", $datalimite));

            if ($dataAtual < $providenciarDoc) {
                return "Vigente";
            }
            if ($dataAtual < $dataLimite) {
                return "Vigente - Providenciar Documentos";
            }
            if($situacao_processo == "Concluído"){
                return "Inválida";
            }
            return "Vencida";
        }
    }


    if ($controle == "Licenciamento") {
        if ($emitida_nova_licenca == "SIM" || $situacao_processo == "Concluído") {
            return "Inválida";
        }

        if ($previsao == "Não Renovar" && $data_vencimento != "-") {
            $dataAtual = strtotime("now");
            $dataVencimento = strtotime(str_replace("/", "-", $data_vencimento));

            if ($dataAtual < $dataVencimento) {
                return "Vigente";
            } else {
                return "Inválida";
            }
        }

        if ($requerimento == "SIM" && $previsao == "Prorrogar") {
            if ($providenciar_doc != "-" && $datalimite != "-" && $data_vencimento != "-") {
                $dataAtual = strtotime("now");
                $providenciarDoc = strtotime(str_replace("/", "-", $providenciar_doc));
                $dataLimite = strtotime(str_replace("/", "-", $datalimite));
                $dataVencimento = strtotime(str_replace("/", "-", $data_vencimento));

                if ($dataAtual < $providenciarDoc) {
                    return "Vigente";
                }
                if ($dataAtual < $dataLimite) {
                    return "Vigente - Providenciar Documentos";
                }
                if ($dataAtual < $dataVencimento) {
                    return "Prazo extrapolado";
                }
                return "Vencida";
            }
        }
    }

    if (($controle == "Condicionante" || $controle == "Info_Complementar") && $providenciar_doc != "-" && $datalimite != "-") {
        $dataAtual = strtotime("now");
        $providenciarDoc = strtotime(str_replace("/", "-", $providenciar_doc));
        $dataLimite = strtotime(str_replace("/", "-", $datalimite));

        if ($dataAtual < $providenciarDoc) {
            return "No prazo - Aguardando providências!";
        }
        if ($dataAtual < $dataLimite) {
            return "No Prazo - Preparar protocolo!";
        }
        return "Adotar Providências";
    }

    if ($controle == "Protocolo" && $data_emissao != "-") {
        return "Retorno de Protocolo";
    }

    if ($controle == "Estudo" && $data_emissao != "-") {
        return "Aprovado";
    }

    return "ERRO";

}


?>
