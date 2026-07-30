<?php

/**
 * @package   astecv3
 * @name      c_api_bradesco_json_builder_validate
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 * @description Validação completa de dados para registro de boletos na API Bradesco
 *              Baseado em: Manual de Cobrança v1.6.0 - Registro de Título - BRADESCO
 */


Class c_api_bradesco_json_builder_validate {

    /**
     * Valida os dados antes de gerar o JSON de registro de boleto
     * @param array $dados Array com os dados do boleto
     * @return array Array com erros encontrados (vazio se não houver erros)
     * @version 1.6.0 - Manual de cobrança - Registro de Título - BRADESCO
     */
    static function validateDadosRegistroBoleto($dados) {
        $erros = [];
        
        // ====== CAMPOS OBRIGATÓRIOS DO BENEFICIÁRIO ======

        /*
        Exemplo de captura dos parametros CNPJ

        {
            CNPJ: 40.321.987/0001-65
            "nuCNPJCPF": "40321987",
            "filialCNPJCPF": "0001",
            "ctrlCNPJCPF": "65"
        */

        
        /* 
        Campo: nuCPFCNPJ
        Tipo: Numérico
        Descricao: Número do CPFCNPJ do Beneficiário
        Tamanho minimo: 1
        Tamanho maximo: 14
        Obrigatório: Sim
        */
        if (!isset($dados['NU_CPFCNPJ']) || $dados['NU_CPFCNPJ'] === '') {
            $erros[] = "Campo 'nu_cpf_cnpj' é obrigatório";
        }
        
        /* 
        Campo: FilialCPFCNPJ
        Tipo: Numérico
        Descricao: filial CPFCNPJ do Beneficiário. OBS: Se CPF, filial = 0
        Tamanho minimo: 1
        Tamanho maximo: 4
        Obrigatório: Sim
        */
        if (!isset($dados['FILIAL_CPF_CNPJ']) || $dados['FILIAL_CPF_CNPJ'] === '') {
            $erros[] = "Campo 'filial_cpf_cnpj' é obrigatório";
        } else {
            $valor = strlen((string)$dados['FILIAL_CPF_CNPJ']);
            if ($valor < 1 || $valor > 4) {
                $erros[] = "Campo 'filial_cpf_cnpj' deve ter entre 1 e 4 dígitos";
            }
        }
        
        /* 
        Campo: ctrlCPFCNPJ
        Tipo: Numérico
        Descricao: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho minimo: 1
        Tamanho maximo: 2
        Obrigatório: Sim
        */
        if (!isset($dados['CTRL_CPFCNPJ']) || $dados['CTRL_CPFCNPJ'] === '') {
            $erros[] = "Campo 'ctrl_cpf_cnpj' é obrigatório";
        } else {
            $valor = strlen((string)$dados['CTRL_CPFCNPJ']);
            if ($valor < 1 || $valor > 2) {
                $erros[] = "Campo 'ctrl_cpf_cnpj' deve ter entre 1 e 2 dígitos";
            }
        }
        
        /* 
        Campo: idProduto
        Tipo: Numérico
        Descricao: Carteira de Cobrança 
        Tamanho minimo: 1
        Tamanho maximo: 2
        Obrigatório: Sim
        */
        if (!isset($dados['ID_PRODUTO']) || $dados['ID_PRODUTO'] === '') {
            $erros[] = "Campo 'id_produto' é obrigatório";
        } else {
            $valor = strlen((string)$dados['ID_PRODUTO']);
            if ($valor < 1 || $valor > 2) {
                $erros[] = "Campo 'id_produto' deve ter entre 1 e 2 dígitos";
            }
        }
        
        /* 
        Campo: nuNegociacao
        Tipo: Numérico
        Descricao: Número da Negociação. Formato: Agência (4 posições), Zeros (7 posições), Conta (7 posições). 
                   Ex.: AAAA0000000CCCCCCC
        Tamanho minimo: 18
        Tamanho maximo: 18
        Obrigatório: Sim
        */
        if (!isset($dados['NU_NEGOCIACAO']) || $dados['NU_NEGOCIACAO'] === '') {
            $erros[] = "Campo 'nu_negociacao' é obrigatório";
        } else {
            $valor = strlen((string)$dados['NU_NEGOCIACAO']);
            if ($valor != 18) {
                $erros[] = "Campo 'nu_negociacao' deve ter exatamente 18 dígitos (Formato: AAAA0000000CCCCCCC)";
            }
        }
        
        /* 
        Campo: nuTitulo
        Tipo: Numérico
        Descricao: Número do Título. É o Nosso Número, sem o dígito. É a identificação do título para o banco, 
                   pode ser informado pelo cliente ou gerado pelo banco, esse número deverá ser único, 
                   de acordo com a carteira e negociação utilizadas.
        Tamanho minimo: 1
        Tamanho maximo: 11
        Obrigatório: Não
        */
        // if (isset($dados['nu_titulo']) && $dados['nu_titulo'] !== '') {
        //     $valor = strlen((string)$dados['nu_titulo']);
        //     if ($valor < 1 || $valor > 11) {
        //         $erros[] = "Campo 'nu_titulo' deve ter entre 1 e 11 dígitos";
        //     }
        // }
        
        /* 
        Campo: nuCliente
        Tipo: Alfanumérico
        Descricao: Número do Cliente. Seu Número. É a identificação do título para o cliente.
        Tamanho minimo: 1
        Tamanho maximo: 25
        Obrigatório: Sim
        */
        if (!isset($dados['NU_CLIENTE']) || trim($dados['NU_CLIENTE']) === '') {
            $erros[] = "Campo 'nu_cliente' é obrigatório";
        } else {
            $valor = strlen($dados['NU_CLIENTE']);
            if ($valor < 1 || $valor > 25) {
                $erros[] = "Campo 'nu_cliente' deve ter entre 1 e 25 caracteres";
            }
        }
        
        /* 
        Campo: dtEmissaoTitulo
        Tipo: Alfanumérico
        Descricao: Data de Emissão do Título (Formato: DD.MM.AAAA)
        Tamanho minimo: 10
        Tamanho maximo: 10
        Obrigatório: Sim
        */
        if (!isset($dados['DT_EMISSAO_TITULO']) || trim($dados['DT_EMISSAO_TITULO']) === '') {
            $erros[] = "Campo 'dt_emissao_titulo' é obrigatório";
        } else {
            $valor = strlen($dados['DT_EMISSAO_TITULO']);
            if ($valor != 10) {
                $erros[] = "Campo 'dt_emissao_titulo' deve ter 10 caracteres (formato DD.MM.AAAA)";
            }
        }
        
        /* 
        Campo: dtVencimentoTitulo
        Tipo: Alfanumérico
        Descricao: Data de Vencimento do Título. Deve ser maior ou igual a data de emissão do título.
                   (Formato: DD.MM.AAAA)
        Tamanho minimo: 10
        Tamanho maximo: 10
        Obrigatório: Sim
        */
        if (!isset($dados['DT_VENCIMENTO_TITULO']) || trim($dados['DT_VENCIMENTO_TITULO']) === '') {

            $erros[] = "Campo 'dt_vencimento_titulo' é obrigatório";

        } else {

            $valor = strlen($dados['DT_VENCIMENTO_TITULO']);
            
            if ($valor != 10) {
                $erros[] = "Campo 'dt_vencimento_titulo' deve ter 10 caracteres (formato DD/MM/AAAA)";
            }
            
            // Validar se data de vencimento >= data de emissão
            if (isset($dados['DT_EMISSAO_TITULO']) && strlen($dados['DT_EMISSAO_TITULO']) == 10) {

                $dtEmissao = self::converterDataParaComparacao($dados['DT_EMISSAO_TITULO']);
                $dtVencimento = self::converterDataParaComparacao($dados['DT_VENCIMENTO_TITULO']);
                
                // Verifica se as datas são válidas antes de comparar
                if ($dtEmissao === null) {
                    $erros[] = "Data de emissão inválida (formato esperado: DD/MM/AAAA)";
                } elseif ($dtVencimento === null) {
                    $erros[] = "Data de vencimento inválida (formato esperado: DD/MM/AAAA)";
                } elseif ($dtVencimento < $dtEmissao) {
                    $erros[] = "Data de vencimento deve ser maior ou igual à data de emissão";
                }
            }
        }
        
        /* 
        Campo: vlNominalTitulo
        Tipo: Numérico
        Descricao: Valor Nominal do Título.
        Tamanho minimo: 10
        Tamanho maximo: 10
        Obrigatório: Sim
        */
        if (!isset($dados['VL_NOMINAL_TITULO']) || $dados['VL_NOMINAL_TITULO'] <= 0) {
            $erros[] = "Campo 'vl_nominal_titulo' é obrigatório e deve ser maior que zero";
        }
        
        /* 
        Campo: cdEspecieTítulo
        Tipo: Numérico
        Descricao: Código da Espécie do Título. Verificar na TABELA DE ESPÉCIES DE TÍTULOS.
        Tamanho minimo: 1
        Tamanho maximo: 2
        Obrigatório: Sim
        */
        if (!isset($dados['CD_ESPECIE_TITULO']) || $dados['CD_ESPECIE_TITULO'] === '') {
            $erros[] = "Campo 'cd_especie_titulo' é obrigatório";
        } else {
            $valor = strlen((string)$dados['CD_ESPECIE_TITULO']);
            if ($valor < 1 || $valor > 2) {
                $erros[] = "Campo 'cd_especie_titulo' deve ter entre 1 e 2 dígitos";
            }
        }
        


        /* 
        Campo: prazoProtestoAutomaticoNegativacao
        Tipo: Numérico
        Descricao: Protesto: A partir de 3 dias úteis após vencimento. 
                    Para negativação: A partir de 5 dias corridos após o vencimento.
        Tamanho minimo: 1
        Tamanho maximo: 2
        Obrigatório: Não. Obrigatório se preencher tpProtestoAutomaticoNegativacao.
        */
        $prazo = (int) $dados['PRAZO_PROTESTO_AUTOMATICO_NEGATIVACAO'] ?? 0;

        // Se prazo não foi informado, não faz nada
        if ($prazo > 0) {


            /* 
            Campo: tpProtestoAutomaticoNegativacao
            Tipo: Numérico
            Descricao: Tipo de Protesto Automático ou Negativação: Informar 1 - para dias corridos ou 2 - para dias úteis
            Tamanho minimo: 1
            Tamanho maximo: 1
            Obrigatório: Não
            */
            // Define o tipo automaticamente (regra interna)
            if ($prazo >= 5) {
                // Negativação
                $dados['TP_PROTESTO_AUTOMATICO_NEGATIVACAO'] = 1;

                if ($prazo < 5) {
                    $erros[] = "Negativação exige prazo mínimo de 5 dias corridos";
                }

            } elseif ($prazo >= 3) {
                // Protesto
                $dados['TP_PROTESTO_AUTOMATICO_NEGATIVACAO'] = 2;

                if ($prazo < 3) {
                    $erros[] = "Protesto exige prazo mínimo de 3 dias úteis";
                }

            } else {
                $erros[] = "Prazo inválido. Mínimo: 3 dias úteis (protesto) ou 5 dias corridos (negativação)";
            }
        }



        
        /* 
        Campo: controleParticipante
        Tipo: Alfanumérico
        Descricao: Controle Participante. Campo de responsabilidade do cliente, caso desejado. 
                   Não consistido pelo banco.
        Tamanho minimo: 0
        Tamanho maximo: 25
        Obrigatório: Não
        */
        if (isset($dados['CONTROLE_PARTICIPANTE']) && strlen($dados['CONTROLE_PARTICIPANTE']) > 25) {
            $erros[] = "Campo 'controle_participante' deve ter no máximo 25 caracteres";
        }
        
        /* 
        Campo: cdPagamentoParcial
        Tipo: Alfanumérico
        Descricao: Informe 'S' (Sim) para permitir pagamento parcial ou 'N' (Não) para não permitir.
        Tamanho minimo: 0
        Tamanho maximo: 1
        Obrigatório: Não
        */
        // if (isset($dados['cd_pagamento_parcial']) && $dados['cd_pagamento_parcial'] == 'S') {
        //     /* 
        //     Campo: qtdePagamentoParcial
        //     Tipo: Numérico
        //     Descricao: Quantidade de Pagamentos Parciais.
        //     Tamanho minimo: 1
        //     Tamanho maximo: 3
        //     Obrigatório: Não. Obrigatório se preencher cdPagamentoParcial.
        //     */
        //     if (!isset($dados['qtde_pagamento_parcial']) || $dados['qtde_pagamento_parcial'] === '' || $dados['qtde_pagamento_parcial'] <= 0) {
        //         $erros[] = "Campo 'qtde_pagamento_parcial' é obrigatório quando pagamento parcial está ativo";
        //     } else {
        //         $valor = strlen((string)$dados['qtde_pagamento_parcial']);
        //         if ($valor < 1 || $valor > 3) {
        //             $erros[] = "Campo 'qtde_pagamento_parcial' deve ter entre 1 e 3 dígitos";
        //         }
        //     }
        // }
        
        /* 
        Campo: tipoPrazoDecursoTres
        Tipo: Numérico
        Descricao: Quantidade de dias de decurso. Para o comando ser acatado via API, 
                   no Cadastro de Cobrança do cliente, o parâmetro deverá estar zerado.
        Tamanho minimo: 1
        Tamanho maximo: 3
        Obrigatório: Não
        */
        // if (isset($dados['tipo_prazo_decurso_tres']) && $dados['tipo_prazo_decurso_tres'] !== '') {
        //     $valor = strlen((string)$dados['tipo_prazo_decurso_tres']);
        //     if ($valor < 1 || $valor > 3) {
        //         $erros[] = "Campo 'tipo_prazo_decurso_tres' deve ter entre 1 e 3 dígitos";
        //     }
        // }
        
        // ====== JUROS ======
        
        /* 
        Campo: percentualJuros
        Tipo: Numérico
        Descricao: Percentual de Juros. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 5
        Obrigatório: Não. Este campo não deve ser preenchido caso escolha cobrar Juros por Valor (campo vlJuros).
        */
        $temPercentualJuros = isset($dados['PERCENTUAL_JUROS']) && $dados['PERCENTUAL_JUROS'] !== '' && $dados['PERCENTUAL_JUROS'] > 0;

        if ($temPercentualJuros) {

            $valor = strlen((string)$dados['PERCENTUAL_JUROS']);

            if ($valor < 1 || $valor > 5) {
                $erros[] = "Campo 'percentual_juros' deve ter entre 1 e 5 dígitos";
            }
        }
        
        /* 
        Campo: vlJuros
        Tipo: Numérico
        Descricao: Valor de Juros. Pattern="^\d+.\d{2}$"
        Tamanho minimo: 1
        Tamanho maximo: 10
        Obrigatório: Não. Este campo não deve ser preenchido caso escolha cobrar juros por percentual (campo percentualJuros).
        */
        //$temVlJuros = isset($dados['VL_JUROS']) && $dados['VL_JUROS'] !== '' && $dados['VL_JUROS'] > 0;
        
        // if ($temPercentualJuros && $temVlJuros) {
        //     $erros[] = "Não é permitido informar 'percentual_juros' e 'vl_juros' ao mesmo tempo. Escolha apenas um";
        // }
        
        // if ($temPercentualJuros) {
        //     $valor = strlen((string)$dados['PERCENTUAL_JUROS']);
        //     if ($valor < 1 || $valor > 5) {
        //         $erros[] = "Campo 'percentual_juros' deve ter entre 1 e 5 dígitos";
        //     }
        // }
        
        // if ($temVlJuros) {
        //     $valor = strlen(number_format($dados['VL_JUROS'], 2, '.', ''));
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'vl_juros' deve ter entre 1 e 10 caracteres";
        //     }
        // }
        
        /* 
        Campo: qtdeDiasJuros
        Tipo: Numérico
        Descricao: Dias a partir da data de vencimento (dtVencimentoTitulo) para início da cobrança de juros.
                   Exemplo: se dtVencimentoTitulo for 01/10 e qtdeDiasJuros for 2, 
                   os juros começarão a ser cobrados a partir de 04/10 (ou seja, 2 dias após o vencimento).
        Tamanho minimo: 1
        Tamanho maximo: 2
        Obrigatório: Sim, apenas se informado vlJuros ou percentualJuros
        ################################### FALTA INCLUI NA TABELA FIN_CONTA
        */
        if (!isset($dados['QTDE_DIAS_JUROS']) || $dados['QTDE_DIAS_JUROS'] === '') {

            $dados['QTDE_DIAS_JUROS'] = 1;

        } else {

            $valor = strlen((string)$dados['QTDE_DIAS_JUROS']);

            if ($valor < 1 || $valor > 2) {
                $erros[] = "Campo 'qtde_dias_juros' deve ter entre 1 e 2 dígitos";
            }

        }
        
        // ====== MULTA ======
        
        /* 
        Campo: percentualMulta
        Tipo: Numérico
        Descricao: Percentual de Multa. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 5
        Obrigatório: Não
        */

        $temPercentualMulta = isset($dados['PERCENTUAL_MULTA']) && $dados['PERCENTUAL_MULTA'] !== '' && $dados['PERCENTUAL_MULTA'] > 0;
        
        if ($temPercentualMulta) {

            $valor = strlen((string)$dados['PERCENTUAL_MULTA']);
            
            if ($valor < 1 || $valor > 5) {
                $erros[] = "Campo 'percentual_multa' deve ter entre 1 e 5 dígitos";
            }
        }
        
        /* 
        Campo: vlMulta
        Tipo: Numérico
        Descricao: Valor da Multa. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 10
        Obrigatório: Não
        */
        // $temVlMulta = isset($dados['vl_multa']) && $dados['vl_multa'] !== '' && $dados['vl_multa'] > 0;
        
        // if ($temVlMulta) {
        //     $valor = strlen(number_format($dados['vl_multa'], 2, '.', ''));
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'vl_multa' deve ter entre 1 e 10 caracteres";
        //     }
        // }
        
        /* 
        Campo: qtdeDiasMulta
        Tipo: Numérico
        Descricao: Dias a partir da data de vencimento (dtVencimentoTitulo) para cobrança de multa.
                   Exemplo: se dtVencimentoTitulo for 01/10 e qtdeDiasMulta for 2, 
                   a multa será cobrada a partir de 04/10 (ou seja, 2 dias após o vencimento).
        Tamanho minimo: 1
        Tamanho maximo: 3
        Obrigatório: Não
        ################################### FALTA INCLUI NA TABELA FIN_CONTA
        */
        if (isset($dados['QTDE_DIAS_MULTA']) && $dados['QTDE_DIAS_MULTA'] !== '' && $dados['QTDE_DIAS_MULTA'] > 0) {
            
            $valor = strlen((string)$dados['QTDE_DIAS_MULTA']);

            if ($valor < 1 || $valor > 3) {
                $erros[] = "Campo 'qtde_dias_multa' deve ter entre 1 e 3 dígitos";
            }
        }
        
        // ====== DESCONTOS ======
        
        /* 
        Campo: percentualDesconto1
        Tipo: Numérico
        Descricao: Percentual do Primeiro Desconto. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 5
        Obrigatório: Não
        */
        // $temPercentualDesconto1 = isset($dados['percentual_desconto1']) && $dados['percentual_desconto1'] !== '' && $dados['percentual_desconto1'] > 0;
        
        // if ($temPercentualDesconto1) {
        //     $valor = strlen((string)$dados['percentual_desconto1']);
        //     if ($valor < 1 || $valor > 5) {
        //         $erros[] = "Campo 'percentual_desconto1' deve ter entre 1 e 5 dígitos";
        //     }
        // }
        
        /* 
        Campo: vlDesconto1
        Tipo: Numérico
        Descricao: Valor do Primeiro Desconto. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 10
        Obrigatório: Não
        */
        // $temVlDesconto1 = isset($dados['vl_desconto1']) && $dados['vl_desconto1'] !== '' && $dados['vl_desconto1'] > 0;
        
        // if ($temVlDesconto1) {
        //     $valor = strlen((string)$dados['vl_desconto1']);
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'vl_desconto1' deve ter entre 1 e 10 dígitos";
        //     }
        // }
        
        /* 
        Campo: dataLimiteDesconto1
        Tipo: Alfanumérico
        Descricao: Data Limite para Primeiro Desconto. Formato: DD.MM.AAAA
        Tamanho minimo: 0
        Tamanho maximo: 11
        Obrigatório: Não. Obrigatório se percentualDesconto1 ou vlDesconto1 informados.
        */
        // if ($temPercentualDesconto1 || $temVlDesconto1) {
        //     if (!isset($dados['data_limite_desconto1']) || trim($dados['data_limite_desconto1']) === '') {
        //         $erros[] = "Campo 'data_limite_desconto1' é obrigatório quando desconto 1 está informado";
        //     }
        // }
        
        /* 
        Campo: percentualDesconto2
        Tipo: Numérico
        Descricao: Percentual do Segundo Desconto. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 5
        Obrigatório: Não
        */
        // $temPercentualDesconto2 = isset($dados['percentual_desconto2']) && $dados['percentual_desconto2'] !== '' && $dados['percentual_desconto2'] > 0;
        
        // if ($temPercentualDesconto2) {
        //     $valor = strlen((string)$dados['percentual_desconto2']);
        //     if ($valor < 1 || $valor > 5) {
        //         $erros[] = "Campo 'percentual_desconto2' deve ter entre 1 e 5 dígitos";
        //     }
        // }
        
        /* 
        Campo: vlDesconto2
        Tipo: Numérico
        Descricao: Valor do Segundo Desconto. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 0
        Tamanho maximo: 10
        Obrigatório: Não
        */
        // $temVlDesconto2 = isset($dados['vl_desconto2']) && $dados['vl_desconto2'] !== '' && $dados['vl_desconto2'] > 0;
        
        // if ($temVlDesconto2) {
        //     $valor = strlen((string)$dados['vl_desconto2']);
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'vl_desconto2' deve ter entre 1 e 10 dígitos";
        //     }
        // }
        
        /* 
        Campo: dataLimiteDesconto2
        Tipo: Alfanumérico
        Descricao: Data Limite para Segundo Desconto. Formato: DD.MM.AAAA
        Tamanho minimo: 0
        Tamanho maximo: 11
        Obrigatório: Não. Obrigatório se percentualDesconto2 ou vlDesconto2 informados.
        */
        // if ($temPercentualDesconto2 || $temVlDesconto2) {
        //     if (!isset($dados['data_limite_desconto2']) || trim($dados['data_limite_desconto2']) === '') {
        //         $erros[] = "Campo 'data_limite_desconto2' é obrigatório quando desconto 2 está informado";
        //     }
        // }
        
        /* 
        Campo: percentualDesconto3
        Tipo: Numérico
        Descricao: Percentual do Terceiro Desconto. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 5
        Obrigatório: Não
        */
        // $temPercentualDesconto3 = isset($dados['percentual_desconto3']) && $dados['percentual_desconto3'] !== '' && $dados['percentual_desconto3'] > 0;
        
        // if ($temPercentualDesconto3) {
        //     $valor = strlen((string)$dados['percentual_desconto3']);
        //     if ($valor < 1 || $valor > 5) {
        //         $erros[] = "Campo 'percentual_desconto3' deve ter entre 1 e 5 dígitos";
        //     }
        // }
        
        /* 
        Campo: vlDesconto3
        Tipo: Numérico
        Descricao: Valor do Desconto 3. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 0
        Tamanho maximo: 10
        Obrigatório: Não
        */
        // $temVlDesconto3 = isset($dados['vl_desconto3']) && $dados['vl_desconto3'] !== '' && $dados['vl_desconto3'] > 0;
        
        // if ($temVlDesconto3) {
        //     $valor = strlen((string)$dados['vl_desconto3']);
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'vl_desconto3' deve ter entre 1 e 10 dígitos";
        //     }
        // }
        
        /* 
        Campo: dataLimiteDesconto3
        Tipo: Alfanumérico
        Descricao: Data Limite para Desconto 3. Formato: DD.MM.AAAA
        Tamanho minimo: 0
        Tamanho maximo: 11
        Obrigatório: Não. Obrigatório se percentualDesconto3 ou vlDesconto3 informados.
        */
        // if ($temPercentualDesconto3 || $temVlDesconto3) {
        //     if (!isset($dados['data_limite_desconto3']) || trim($dados['data_limite_desconto3']) === '') {
        //         $erros[] = "Campo 'data_limite_desconto3' é obrigatório quando desconto 3 está informado";
        //     }
        // }
        
        // ====== BONIFICAÇÃO ======
        
        /* 
        Campo: percentualBonificacao
        Tipo: Numérico
        Descricao: Percentual de Bonificação
        Tamanho minimo: -
        Tamanho maximo: -
        Obrigatório: Não
        */
        // Campo opcional, sem validação de tamanho definida na documentação

        // if (isset($dados['percentual_bonificacao']) && $dados['percentual_bonificacao'] !== '' && $dados['percentual_bonificacao'] > 0) {
        //     $valor = strlen((string)$dados['percentual_bonificacao']);
        //     if ($valor < 1 || $valor > 5) {
        //         $erros[] = "Campo 'percentual_bonificacao' deve ter entre 1 e 5 dígitos";
        //     }
        // }
        
        /* 
        Campo: vlBonificacao
        Tipo: Numérico
        Descricao: Valor de bonificação. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 100
        Obrigatório: Não
        */
        // if (isset($dados['vl_bonificacao']) && $dados['vl_bonificacao'] !== '' && $dados['vl_bonificacao'] > 0) {
        //     $valor = strlen((string)$dados['vl_bonificacao']);
        //     if ($valor < 1 || $valor > 100) {
        //         $erros[] = "Campo 'vl_bonificacao' deve ter entre 1 e 100 dígitos";
        //     }
        // }
        
        /* 
        Campo: dtLimiteBonificacao
        Tipo: Alfanumérico
        Descricao: Data Limite Bonificação (Formato: DD.MM.AAAA)
        Tamanho minimo: 1
        Tamanho maximo: 100
        Obrigatório: Não
        */
        // if (isset($dados['dt_limite_bonificacao']) && $dados['dt_limite_bonificacao'] !== '') {
        //     $valor = strlen((string)$dados['dt_limite_bonificacao']);
        //     if ($valor < 1 || $valor > 100) {
        //         $erros[] = "Campo 'dt_limite_bonificacao' deve ter entre 1 e 100 caracteres";
        //     }
        // }
        
        // ====== ABATIMENTO E IOF ======
        
        /* 
        Campo: vlAbatimento
        Tipo: Numérico
        Descricao: Valor do abatimento. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 10
        Obrigatório: Não
        */
        // if (isset($dados['vl_abatimento']) && $dados['vl_abatimento'] !== '' && $dados['vl_abatimento'] > 0) {
        //     $valor = strlen((string)$dados['vl_abatimento']);
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'vl_abatimento' deve ter entre 1 e 10 dígitos";
        //     }
        // }
        
        /* 
        Campo: vlIOF
        Tipo: Numérico
        Descricao: Valor do IOF. Pattern = "^\d+(.00)?$"
        Tamanho minimo: 1
        Tamanho maximo: 30
        Obrigatório: Não
        */
        // if (isset($dados['vl_iof']) && $dados['vl_iof'] !== '' && $dados['vl_iof'] > 0) {
        //     $valor = strlen((string)$dados['vl_iof']);
        //     if ($valor < 1 || $valor > 30) {
        //         $erros[] = "Campo 'vl_iof' deve ter entre 1 e 30 dígitos";
        //     }
        // }
        
        // ====== DADOS DO PAGADOR ======
        
        /* 
        Campo: nomePagador
        Tipo: Alfanumérico
        Descricao: Nome do Sacado
        Tamanho minimo: 1
        Tamanho maximo: 70
        Obrigatório: Sim
        */
        if (!isset($dados['NOME_PAGADOR']) || trim($dados['NOME_PAGADOR']) === '') {

            $erros[] = "Campo 'nome_pagador' é obrigatório";

        } else {
            
            $valor = strlen($dados['NOME_PAGADOR']);

            if ($valor < 1 || $valor > 70) {
                $erros[] = "Campo 'nome_pagador' deve ter entre 1 e 70 caracteres";
            }

        }
        
        /* 
        Campo: logradouroPagador
        Tipo: Alfanumérico
        Descricao: Endereço do Sacado
        Tamanho minimo: 1
        Tamanho maximo: 100
        Obrigatório: Sim
        */
        if (!isset($dados['LOGRADOURO_PAGADOR']) || trim($dados['LOGRADOURO_PAGADOR']) === '') {

            $erros[] = "Campo 'logradouro_pagador' é obrigatório";

        } else {

            $valor = strlen($dados['LOGRADOURO_PAGADOR']);

            if ($valor < 1 || $valor > 100) {
                $erros[] = "Campo 'logradouro_pagador' deve ter entre 1 e 100 caracteres";
            }
        }
        
        /* 
        Campo: nuLogradouroPagador
        Tipo: Alfanumérico
        Descricao: Número do endereço do Sacado
        Tamanho minimo: 1
        Tamanho maximo: 10
        Obrigatório: Sim
        */
        if (!isset($dados['NU_LOGRADOURO_PAGADOR']) || trim($dados['NU_LOGRADOURO_PAGADOR']) === '') {

            $erros[] = "Campo 'nu_logradouro_pagador' é obrigatório";

        } else {

            $valor = strlen($dados['NU_LOGRADOURO_PAGADOR']);

            if ($valor < 1 || $valor > 10) {
                $erros[] = "Campo 'nu_logradouro_pagador' deve ter entre 1 e 10 caracteres";
            }
        }
        
        /* 
        Campo: complementoLogradouroPagador
        Tipo: Alfanumérico
        Descricao: Complemento do endereço do Sacado
        Tamanho minimo: 0
        Tamanho maximo: 30
        Obrigatório: Não
        */
        if (isset($dados['COMPLEMENTO_LOGADOURO_PAGADOR']) && strlen($dados['COMPLEMENTO_LOGADOURO_PAGADOR']) > 30) {
            $erros[] = "Campo 'complemento_logradouro_pagador' deve ter no máximo 30 caracteres";
        }
        
        /* 
        Campo: tpVencimento
        Tipo: Numérico
        Descricao: Tipo de Vencimento – Fixo "0"
        Tamanho minimo: 1
        Tamanho maximo: 1
        Obrigatório: Sim
        */
        if (!isset($dados['TP_VENCIMENTO']) || $dados['TP_VENCIMENTO'] === '') {
            $dados['TP_VENCIMENTO'] = 0;
        }
        
        /* 
        Campo: cepPagador
        Tipo: Numérico
        Descricao: CEP do Sacado
        Tamanho minimo: 5
        Tamanho maximo: 5
        Obrigatório: Não
        */
        if (isset($dados['CEP_PAGADOR']) && $dados['CEP_PAGADOR'] !== '') {

            $valor = strlen($dados['CEP_PAGADOR']);
            
            if ($valor < 5 || $valor > 5) {
                $erros[] = "Campo 'cep_pagador' deve ter no máximo 5 dígitos";
            }
        }
        
        /* 
        Campo: complementoCepPagador
        Tipo: Numérico
        Descricao: Complemento do CEP do Sacado
        Tamanho minimo: 1
        Tamanho maximo: 3
        Obrigatório: Não
        */
        // if (isset($dados['complemento_cep_pagador']) && $dados['complemento_cep_pagador'] !== '') {
        //     $valor = strlen((string)$dados['complemento_cep_pagador']);
        //     if ($valor < 1 || $valor > 3) {
        //         $erros[] = "Campo 'complemento_cep_pagador' deve ter entre 1 e 3 dígitos";
        //     }
        // }
        
        /* 
        Campo: bairroPagador
        Tipo: Alfanumérico
        Descricao: Bairro do Sacado
        Tamanho minimo: 1
        Tamanho maximo: 50
        Obrigatório: Sim
        */
        if (!isset($dados['BAIRRO_PAGADOR']) || trim($dados['BAIRRO_PAGADOR']) === '') {

            $erros[] = "Campo 'bairro_pagador' é obrigatório";

        } else {

            $valor = strlen($dados['BAIRRO_PAGADOR']);

            if ($valor < 1 || $valor > 50) {
                $erros[] = "Campo 'bairro_pagador' deve ter entre 1 e 50 caracteres";
            }
        }
        
        /* 
        Campo: municipioPagador
        Tipo: Alfanumérico
        Descricao: Município Sacado
        Tamanho minimo: 1
        Tamanho maximo: 50
        Obrigatório: Sim
        */
        if (!isset($dados['MUNICIPIO_PAGADOR']) || trim($dados['MUNICIPIO_PAGADOR']) === '') {
            
            $erros[] = "Campo 'municipio_pagador' é obrigatório";

        } else {

            $valor = strlen($dados['MUNICIPIO_PAGADOR']);

            if ($valor < 1 || $valor > 50) {
                $erros[] = "Campo 'municipio_pagador' deve ter entre 1 e 50 caracteres";
            }
        }
        
        /* 
        Campo: ufPagador
        Tipo: Alfanumérico
        Descricao: Estado Sacado
        Tamanho minimo: 2
        Tamanho maximo: 2
        Obrigatório: Sim
        */
        if (!isset($dados['UF_PAGADOR']) || trim($dados['UF_PAGADOR']) === '') {

            $erros[] = "Campo 'uf_pagador' é obrigatório";

        } else {

            $valor = strlen($dados['UF_PAGADOR']);

            if ($valor != 2) {
                $erros[] = "Campo 'uf_pagador' deve ter exatamente 2 caracteres";
            }
        }
        
        /* 
        Campo: cdIndCpfcnpjPagador
        Tipo: Numérico
        Descricao: Indicador do tipo de documento: 1 – CPF ou 2 – CNPJ (Cadastro Nacional da Pessoa Jurídica)
        Tamanho minimo: 1
        Tamanho maximo: 1
        Obrigatório: Não
        */
        if (isset($dados['CD_IND_CPF_CNPJ_PAGADOR']) && $dados['CD_IND_CPF_CNPJ_PAGADOR'] !== '') {

            if ($dados['CD_IND_CPF_CNPJ_PAGADOR'] != 1 && $dados['CD_IND_CPF_CNPJ_PAGADOR'] != 2) {

                $erros[] = "Campo 'cd_ind_cpfcnpj_pagador' deve ser 1 (CPF) ou 2 (CNPJ)";
            }
        }
        
        /* 
        Campo: nuCpfcnpjPagador
        Tipo: Numérico
        Descricao: Para CPF, o número deve conter 11 dígitos, incluindo o dígito verificador. 
                   Para CNPJ, o número deve conter 14 dígitos, incluindo o número da filial e o dígito verificador.
        Tamanho minimo: 11
        Tamanho maximo: 14
        Obrigatório: Não
        */
        if (isset($dados['NU_CPFCNPJ_PAGADOR']) && $dados['NU_CPFCNPJ_PAGADOR'] !== '') {

            $valor = strlen((string)$dados['NU_CPFCNPJ_PAGADOR']);
            
            if ($valor < 11 || $valor > 14) {
                $erros[] = "Campo 'nu_cpfcnpj_pagador' deve ter entre 11 (CPF) e 14 (CNPJ) dígitos";
            }
        }
        
        /* 
        Campo: endEletronicoPagador
        Tipo: Alfanumérico
        Descricao: E-mail do Pagador.
        Tamanho minimo: 0
        Tamanho maximo: 100
        Obrigatório: Não
        */
        if (isset($dados['END_ELETRONICO_PAGADOR']) && strlen($dados['END_ELETRONICO_PAGADOR']) > 100) {
            $erros[] = "Campo 'end_eletronico_pagador' deve ter no máximo 100 caracteres";
        }
        
        // ====== DÉBITO AUTOMÁTICO ======
        
        /* 
        Campo: debitoAutomatico
        Tipo: Alfanumérico
        Descricao: Informe 'S' (Sim) para automático ou 'N' (Não) caso não deseje ativá-lo. 
                   OBS: Instituições autorizadas BACEN não devem utilizar.
        Tamanho minimo: 0
        Tamanho maximo: 1
        Obrigatório: Não
        */
        // if (isset($dados['debito_automatico']) && strlen($dados['debito_automatico']) > 1) {
        //     $erros[] = "Campo 'debito_automatico' deve ter no máximo 1 caractere";
        // }
        
        // Se débito automático = 'S', campos bancários são obrigatórios
        // if (isset($dados['debito_automatico']) && $dados['debito_automatico'] == 'S') {
            
        //     /* 
        //     Campo: bancoDoDebAutomatico
        //     Tipo: Numérico
        //     Descricao: Banco Débito
        //     Tamanho minimo: 2
        //     Tamanho maximo: 3
        //     Obrigatório: Não. Obrigatório se o campo debitoAutomatico estiver com "S"
        //     */
        //     if (!isset($dados['banco_deb_automatico']) || $dados['banco_deb_automatico'] === '') {
        //         $erros[] = "Campo 'banco_deb_automatico' é obrigatório quando débito automático está ativo";
        //     } else {
        //         $valor = strlen((string)$dados['banco_deb_automatico']);
        //         if ($valor < 2 || $valor > 3) {
        //             $erros[] = "Campo 'banco_deb_automatico' deve ter entre 2 e 3 dígitos";
        //         }
        //     }
            
            /* 
            Campo: agenciaDoDebAutomatico
            Tipo: Numérico
            Descricao: Agência Débito
            Tamanho minimo: 1
            Tamanho maximo: 5
            Obrigatório: Não. Obrigatório se o campo debitoAutomatico estiver com "S"
            */
            // if (!isset($dados['agencia_deb_automatico']) || $dados['agencia_deb_automatico'] === '') {
            //     $erros[] = "Campo 'agencia_deb_automatico' é obrigatório quando débito automático está ativo";
            // } else {
            //     $valor = strlen((string)$dados['agencia_deb_automatico']);
            //     if ($valor < 1 || $valor > 5) {
            //         $erros[] = "Campo 'agencia_deb_automatico' deve ter entre 1 e 5 dígitos";
            //     }
            // }
            
        //     /* 
        //     Campo: digitoAgenciaDoDebAutomat
        //     Tipo: Numérico
        //     Descricao: Dígito Agência de Débito
        //     Tamanho minimo: 1
        //     Tamanho maximo: 3
        //     Obrigatório: Não. Obrigatório se o campo debitoAutomatico estiver com "S"
        //     */
        //     if (!isset($dados['digito_agencia_deb_automatico']) || $dados['digito_agencia_deb_automatico'] === '') {
        //         $erros[] = "Campo 'digito_agencia_deb_automatico' é obrigatório quando débito automático está ativo";
        //     } else {
        //         $valor = strlen((string)$dados['digito_agencia_deb_automatico']);
        //         if ($valor < 1 || $valor > 3) {
        //             $erros[] = "Campo 'digito_agencia_deb_automatico' deve ter entre 1 e 3 dígitos";
        //         }
        //     }
            
        //     /* 
        //     Campo: contaDoDebAutomatico
        //     Tipo: Numérico
        //     Descricao: Conta Débito. Informar o número completo da conta, incluindo o dígito verificador. 
        //                Exemplo: Conta Corrente '123-4', deve ser informado '1234'. 
        //                Caso o dígito seja 'P', substitua por '0'. Por exemplo, '123-P' deve ser preenchido como '1230'.
        //     Tamanho minimo: 1
        //     Tamanho maximo: 13
        //     Obrigatório: Obrigatório se o campo debitoAutomatico estiver com "S"
        //     */
        //     if (!isset($dados['conta_deb_automatico']) || $dados['conta_deb_automatico'] === '') {
        //         $erros[] = "Campo 'conta_deb_automatico' é obrigatório quando débito automático está ativo";
        //     } else {
        //         $valor = strlen((string)$dados['conta_deb_automatico']);
        //         if ($valor < 1 || $valor > 13) {
        //             $erros[] = "Campo 'conta_deb_automatico' deve ter entre 1 e 13 dígitos";
        //         }
        //     }
            
        //     /* 
        //     Campo: razaoDoDebAutomatico
        //     Tipo: Numérico
        //     Descricao: Razão Conta de Débito
        //     Tamanho minimo: 1
        //     Tamanho maximo: 6
        //     Obrigatório: Não. Obrigatório se o campo debitoAutomatico estiver com "S"
        //     */
        //     if (!isset($dados['razao_deb_automatico']) || $dados['razao_deb_automatico'] === '') {
        //         $erros[] = "Campo 'razao_deb_automatico' é obrigatório quando débito automático está ativo";
        //     } else {
        //         $valor = strlen((string)$dados['razao_deb_automatico']);
        //         if ($valor < 1 || $valor > 6) {
        //             $erros[] = "Campo 'razao_deb_automatico' deve ter entre 1 e 6 dígitos";
        //         }
        //     }
        // }
        
        // ====== DADOS DO SACADOR AVALISTA (OPCIONAL) ======
        
        /* 
        Campo: nomeSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Nome Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 40
        Obrigatório: Não
        */
        // if (isset($dados['nome_sacador_avalista']) && $dados['nome_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['nome_sacador_avalista']);
        //     if ($valor < 1 || $valor > 40) {
        //         $erros[] = "Campo 'nome_sacador_avalista' deve ter entre 1 e 40 caracteres";
        //     }
        // }
        
        /* 
        Campo: logradouroSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Endereço Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 40
        Obrigatório: Não
        */
        // if (isset($dados['logradouro_sacador_avalista']) && $dados['logradouro_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['logradouro_sacador_avalista']);
        //     if ($valor < 1 || $valor > 40) {
        //         $erros[] = "Campo 'logradouro_sacador_avalista' deve ter entre 1 e 40 caracteres";
        //     }
        // }
        
        /* 
        Campo: nuLogradouroSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Número do endereço do Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 10
        Obrigatório: Não
        */
        // if (isset($dados['nu_logradouro_sacador_avalista']) && $dados['nu_logradouro_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['nu_logradouro_sacador_avalista']);
        //     if ($valor < 1 || $valor > 10) {
        //         $erros[] = "Campo 'nu_logradouro_sacador_avalista' deve ter entre 1 e 10 caracteres";
        //     }
        // }
        
        /* 
        Campo: complementoLogradouroSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Complemento endereço Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 15
        Obrigatório: Não
        */
        // if (isset($dados['complemento_logradouro_sacador_avalista']) && $dados['complemento_logradouro_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['complemento_logradouro_sacador_avalista']);
        //     if ($valor < 1 || $valor > 15) {
        //         $erros[] = "Campo 'complemento_logradouro_sacador_avalista' deve ter entre 1 e 15 caracteres";
        //     }
        // }
        
        /* 
        Campo: cepSacadorAvalista
        Tipo: Numérico
        Descricao: CEP Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 5
        Obrigatório: Não
        */
        // if (isset($dados['cep_sacador_avalista']) && $dados['cep_sacador_avalista'] !== '') {
        //     $valor = strlen((string)$dados['cep_sacador_avalista']);
        //     if ($valor < 1 || $valor > 5) {
        //         $erros[] = "Campo 'cep_sacador_avalista' deve ter entre 1 e 5 dígitos";
        //     }
        // }
        
        /* 
        Campo: complementoCepSacadorAvalista
        Tipo: Numérico
        Descricao: Complemento do CEP Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 3
        Obrigatório: Não
        */
        // if (isset($dados['complemento_cep_sacador_avalista']) && $dados['complemento_cep_sacador_avalista'] !== '') {
        //     $valor = strlen((string)$dados['complemento_cep_sacador_avalista']);
        //     if ($valor < 1 || $valor > 3) {
        //         $erros[] = "Campo 'complemento_cep_sacador_avalista' deve ter entre 1 e 3 dígitos";
        //     }
        // }
        
        /* 
        Campo: bairroSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Bairro Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 40
        Obrigatório: Não
        */
        // if (isset($dados['bairro_sacador_avalista']) && $dados['bairro_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['bairro_sacador_avalista']);
        //     if ($valor < 1 || $valor > 40) {
        //         $erros[] = "Campo 'bairro_sacador_avalista' deve ter entre 1 e 40 caracteres";
        //     }
        // }
        
        /* 
        Campo: municipioSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Município Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 40
        Obrigatório: Não
        */
        // if (isset($dados['municipio_sacador_avalista']) && $dados['municipio_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['municipio_sacador_avalista']);
        //     if ($valor < 1 || $valor > 40) {
        //         $erros[] = "Campo 'municipio_sacador_avalista' deve ter entre 1 e 40 caracteres";
        //     }
        // }
        
        /* 
        Campo: ufSacadorAvalista
        Tipo: Numérico
        Descricao: Estado Sacador Avalista
        Tamanho minimo: 2
        Tamanho maximo: 2
        Obrigatório: Não
        */
        // if (isset($dados['uf_sacador_avalista']) && $dados['uf_sacador_avalista'] !== '') {
        //     $valor = strlen((string)$dados['uf_sacador_avalista']);
        //     if ($valor != 2) {
        //         $erros[] = "Campo 'uf_sacador_avalista' deve ter exatamente 2 caracteres";
        //     }
        // }
        
        /* 
        Campo: cdIndCpfcnpjSacadorAvalista
        Tipo: Numérico
        Descricao: Indicador do tipo de documento: 1 – CPF ou 2 – CNPJ (Cadastro Nacional da Pessoa Jurídica)
        Tamanho minimo: 1
        Tamanho maximo: 1
        Obrigatório: Não
        */
        if (isset($dados['CD_IND_CPF_CNPJ_SACADOR_AVALISTA']) && $dados['CD_IND_CPF_CNPJ_SACADOR_AVALISTA'] != 1 && $dados['CD_IND_CPF_CNPJ_SACADOR_AVALISTA'] != 2) {
            $erros[] = "Campo 'CD_IND_CPF_CNPJ_SACADOR_AVALISTA' deve ser 1 (CPF) ou 2 (CNPJ)";
        }
        
        /* 
        Campo: nuCpfcnpjSacadorAvalista
        Tipo: Numérico
        Descricao: Para CPF, o número deve conter 11 dígitos, incluindo o dígito verificador. 
                   Para CNPJ, o número deve conter 14 dígitos, incluindo o número da filial e o dígito verificador.
        Tamanho minimo: 11
        Tamanho maximo: 14
        Obrigatório: Não
        */
        // if (isset($dados['nu_cpfcnpj_sacador_avalista']) && $dados['nu_cpfcnpj_sacador_avalista'] !== '') {
        //     $valor = strlen((string)$dados['nu_cpfcnpj_sacador_avalista']);
        //     if ($valor < 11 || $valor > 14) {
        //         $erros[] = "Campo 'nu_cpfcnpj_sacador_avalista' deve ter entre 11 (CPF) e 14 (CNPJ) dígitos";
        //     }
        // }
        
        /* 
        Campo: enderecoSacadorAvalista
        Tipo: Alfanumérico
        Descricao: Endereço Sacador Avalista
        Tamanho minimo: 1
        Tamanho maximo: 70
        Obrigatório: Não
        */
        // if (isset($dados['endereco_sacador_avalista']) && $dados['endereco_sacador_avalista'] !== '') {
        //     $valor = strlen($dados['endereco_sacador_avalista']);
        //     if ($valor < 1 || $valor > 70) {
        //         $erros[] = "Campo 'endereco_sacador_avalista' deve ter entre 1 e 70 caracteres";
        //     }
        // }
        
        // ====== MENSAGEM ======
        
        /* 
        Campo: mensagem
        Tipo: Alfanumérico
        Descricao: Mensagem a ser exibida no Boleto
        Tamanho minimo: 1
        Tamanho maximo: 80
        Obrigatório: Não
        */
        if (isset($dados['MENSAGEM']) && trim($dados['MENSAGEM']) !== '') {
            $valor = strlen($dados['MENSAGEM']);
            if ($valor < 1 || $valor > 80) {
                $erros[] = "Campo 'mensagem' deve ter entre 1 e 80 caracteres";
            }
        }
        
        return $erros;
    }
    
    /**
     * Converte data no formato DD.MM.AAAA para AAAAMMDD para comparação
     * @param string $data Data no formato DD.MM.AAAA
     * @return string Data no formato AAAAMMDD
     */
    /**
     * Converte string de data para objeto DateTime para comparação segura
     * 
     * @param string $data Data no formato DD/MM/AAAA ou DD.MM.AAAA
     * @return DateTime|null Objeto DateTime ou null se inválido
     */
    static function converterDataParaComparacao($data) {
        // Aceita formatos: DD.MM.AAAA ou DD/MM/AAAA
        $separador = strpos($data, '/') !== false ? '/' : '.';
        $formato = 'd' . $separador . 'm' . $separador . 'Y';
        
        $dateTime = DateTime::createFromFormat($formato, $data);
        
        // Verifica se a data é válida (ex: 31/02/2026 seria inválido)
        if ($dateTime && $dateTime->format($formato) === $data) {
            return $dateTime;
        }
        
        return null;
    }
    
    /**
     * Valida os dados antes de gerar o JSON de baixa de título
     * 
     * Baseado na documentação da API de Baixa de Título do Bradesco
     * Endpoint: /boleto/cobranca-baixa/v1/baixar
     * 
     * @param array $dados Array com os dados para baixa do título
     * @return array Array com erros encontrados (vazio se não houver erros)
     */
    static function validateDadosBaixaTitulo($dados) {
        $erros = [];
        
        // ====== CAMPOS OBRIGATÓRIOS DO CPF/CNPJ BENEFICIÁRIO ======
        
        /*
        Campo: cpfCnpj
        Tipo: Numérico
        Descrição: Raiz CPF/CNPJ Beneficiário
        Tamanho máximo: 9 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CPF_CNPJ']) || $dados['CPF_CNPJ'] === '' || $dados['CPF_CNPJ'] === null) {
            $erros[] = "Campo 'cpf_cnpj' é obrigatório (Raiz CPF/CNPJ Beneficiário - máx. 9 caracteres)";
        } else {
            $valor = strlen((string)$dados['CPF_CNPJ']);
            if ($valor > 9) {
                $erros[] = "Campo 'cpf_cnpj' deve ter no máximo 9 caracteres";
            }
        }
        
        /*
        Campo: filial
        Tipo: Numérico
        Descrição: Filial CPF/CNPJ do Beneficiário. Se CPF, filial = 0
        Tamanho máximo: 4 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['FILIAL']) || ($dados['FILIAL'] === '' && $dados['FILIAL'] !== 0)) {
            $erros[] = "Campo 'filial' é obrigatório (Filial CPF/CNPJ - máx. 4 caracteres, se CPF = 0)";
        } else {
            $valor = strlen((string)$dados['FILIAL']);
            if ($valor > 4) {
                $erros[] = "Campo 'filial' deve ter no máximo 4 caracteres";
            }
        }
        
        /*
        Campo: controle
        Tipo: Numérico
        Descrição: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CONTROLE']) || $dados['CONTROLE'] === '' || $dados['CONTROLE'] === null) {
            $erros[] = "Campo 'controle' é obrigatório (Dígito de Controle - máx. 2 caracteres)";
        } else {
            $valor = strlen((string)$dados['CONTROLE']);
            if ($valor > 2) {
                $erros[] = "Campo 'controle' deve ter no máximo 2 caracteres";
            }
        }
        
        // ====== CAMPOS DO TÍTULO ======
        
        /*
        Campo: produto
        Tipo: Numérico
        Descrição: Código da carteira/modalidade de cobrança. Ex.: 09 (Cobrança escritural), 05 (Cobrança de Seguros)
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['PRODUTO']) || $dados['PRODUTO'] === '' || $dados['PRODUTO'] === null) {
            $erros[] = "Campo 'produto' é obrigatório (Código da carteira/modalidade - máx. 2 caracteres)";
        } else {
            $valor = strlen((string)$dados['PRODUTO']);
            if ($valor > 2) {
                $erros[] = "Campo 'produto' deve ter no máximo 2 caracteres";
            }
        }
        
        /*
        Campo: negociacao
        Tipo: Numérico
        Descrição: Número da Negociação. Formato: Agência: 4 posições (Sem dígito); Conta: 7 posições (Sem dígito)
        Tamanho máximo: 11 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['NEGOCIACAO']) || $dados['NEGOCIACAO'] === '' || $dados['NEGOCIACAO'] === null) {
            $erros[] = "Campo 'negociacao' é obrigatório (Agência 4 posições + Conta 7 posições = 11 caracteres)";
        } else {
            $valor = strlen((string)$dados['NEGOCIACAO']);
            if ($valor > 11) {
                $erros[] = "Campo 'negociacao' deve ter no máximo 11 caracteres";
            }
        }
        
        /*
        Campo: nossoNumero
        Tipo: Numérico
        Descrição: Identificação do título para o banco. Nosso Número sem o dígito
        Tamanho máximo: 11 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['NOSSO_NUMERO']) || $dados['NOSSO_NUMERO'] === '' || $dados['NOSSO_NUMERO'] === null) {
            $erros[] = "Campo 'nosso_numero' é obrigatório (Nosso Número sem dígito - máx. 11 caracteres)";
        } else {
            $valor = strlen((string)$dados['NOSSO_NUMERO']);
            if ($valor > 11) {
                $erros[] = "Campo 'nosso_numero' deve ter no máximo 11 caracteres";
            }
        }
        
        /*
        Campo: sequencia
        Tipo: Numérico
        Descrição: Número de sequência. Fixo "0"
        Tamanho máximo: 1 caractere
        Obrigatório: Sim
        */
        if (!isset($dados['SEQUENCIA']) || $dados['SEQUENCIA'] === '' || $dados['SEQUENCIA'] === null) {
            $erros[] = "Campo 'sequencia' é obrigatório (Número de sequência - Fixo 0)";
        } else {
            if (intval($dados['SEQUENCIA']) !== 0) {
                $erros[] = "Campo 'sequencia' deve ser fixo 0";
            }
        }
        
        /*
        Campo: codigoBaixa
        Tipo: Numérico
        Descrição: Código da baixa - Veja a Tabela 1 - Baixa (57 = CONFORME SEU PEDIDO)
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CODIGO_BAIXA']) || $dados['CODIGO_BAIXA'] === '' || $dados['CODIGO_BAIXA'] === null) {
            $erros[] = "Campo 'codigo_baixa' é obrigatório";
        } 

        return $erros;
    }


    static function validateDadosConsultaTitulosLiquidados($dados) {
        $erros = [];

        // ====== CAMPOS OBRIGATÓRIOS DO CPF/CNPJ BENEFICIÁRIO ======
        /*
        Campo: cpfCnpj
        Tipo: Numérico
        Descrição: Raiz CPF/CNPJ Beneficiário
        Tamanho máximo: 9 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CPF_CNPJ']) || $dados['CPF_CNPJ'] === '' || $dados['CPF_CNPJ'] === null) {
            $erros[] = "Campo 'cpf_cnpj' é obrigatório (Raiz CPF/CNPJ Beneficiário - máx. 9 caracteres)";
        } else {

            $valor = (string)$dados['CPF_CNPJ'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'cpf_cnpj' deve conter apenas números";
            } else if (strlen($valor) > 9) {
                $erros[] = "Campo 'cpf_cnpj' deve ter no máximo 9 caracteres";
            }
        }

        /*
        Campo: filial
        Tipo: Numérico
        Descrição: Filial CPF/CNPJ do Beneficiário (Se CPF, filial = 0)
        Tamanho máximo: 4 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['FILIAL']) || ($dados['FILIAL'] === '' && $dados['FILIAL'] !== 0)) {
            $erros[] = "Campo 'filial' é obrigatório (Filial CPF/CNPJ - máx. 4 caracteres, se CPF = 0)";
        } else {

            $valor = (string)$dados['FILIAL'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'filial' deve conter apenas números";
            } else if (strlen($valor) > 4) {
                $erros[] = "Campo 'filial' deve ter no máximo 4 caracteres";
            }
        }

        /*
        Campo: controle
        Tipo: Numérico
        Descrição: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CONTROLE']) || $dados['CONTROLE'] === '' || $dados['CONTROLE'] === null) {
            $erros[] = "Campo 'controle' é obrigatório (Dígito de Controle - máx. 2 caracteres)";
        } else {

            $valor = (string)$dados['CONTROLE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'controle' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'controle' deve ter no máximo 2 caracteres";
            }
        }

        // ====== CAMPOS DA CONSULTA ======
        /*
        Campo: produto
        Tipo: Numérico
        Descrição: Identificação do Código da carteira/Modalidade de cobrança
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['PRODUTO']) || $dados['PRODUTO'] === '' || $dados['PRODUTO'] === null) {
            $erros[] = "Campo 'produto' é obrigatório (ID Produto - máx. 2 caracteres)";
        } else {

            $valor = (string)$dados['PRODUTO'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'produto' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'produto' deve ter no máximo 2 caracteres";
            }
        }

        /*
        Campo: negociacao
        Tipo: Numérico
        Descrição: Número da Negociação (Agência 4 posições + Conta 7 posições, sem dígito)
        Tamanho máximo: 11 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['NEGOCIACAO']) || $dados['NEGOCIACAO'] === '' || $dados['NEGOCIACAO'] === null) {
            $erros[] = "Campo 'negociacao' é obrigatório (Agência 4 posições + Conta 7 posições = 11 caracteres)";
        } else {

            $valor = (string)$dados['NEGOCIACAO'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'negociacao' deve conter apenas números";
            } else if (strlen($valor) > 11) {
                $erros[] = "Campo 'negociacao' deve ter no máximo 11 caracteres";
            }
        }

        // ====== FILTROS OPCIONAIS (quando não informado, preencher com 0) ======
        // Observação: o manual orienta preencher com "0". Na prática, este validador aceita:
        // - campo ausente/vazio (tratado como "não informado")
        // - "0" ou sequência de zeros no tamanho do campo (tratado como "não informado")
        // - valor numérico com tamanho exato (quando aplicável)

        /*
        Campo: dataMovimentoDe
        Tipo: Numérico
        Descrição: Data de Movimento inicial da pesquisa (Formato: DDMMAAAA)
        Tamanho: 8 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['DATA_MOVIMENTO_DE']) && $dados['DATA_MOVIMENTO_DE'] !== '' && $dados['DATA_MOVIMENTO_DE'] !== null) {

            $valor = (string)$dados['DATA_MOVIMENTO_DE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'data_movimento_de' deve conter apenas números (DDMMAAAA ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok: tratado como não informado (0 / 00000000)
            } else if (strlen($valor) !== 8) {
                $erros[] = "Campo 'data_movimento_de' deve estar no formato DDMMAAAA (8 dígitos) ou 0";
            }
        }

        /*
        Campo: dataMovimentoAte
        Tipo: Numérico
        Descrição: Data de Movimento final da pesquisa (Formato: DDMMAAAA)
        Tamanho: 8 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['DATA_MOVIMENTO_ATE']) && $dados['DATA_MOVIMENTO_ATE'] !== '' && $dados['DATA_MOVIMENTO_ATE'] !== null) {

            $valor = (string)$dados['DATA_MOVIMENTO_ATE'];

            if (!ctype_digit($valor)) {

                $erros[] = "Campo 'data_movimento_ate' deve conter apenas números (DDMMAAAA ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok
            } else if (strlen($valor) !== 8) {
                $erros[] = "Campo 'data_movimento_ate' deve estar no formato DDMMAAAA (8 dígitos) ou 0";
            }
        }

        /*
        Campo: dataPagamentoDe
        Tipo: Numérico
        Descrição: Data de Pagamento inicial da pesquisa (Formato: DDMMAAAA)
        Tamanho: 8 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['DATA_PAGAMENTO_DE']) && $dados['DATA_PAGAMENTO_DE'] !== '' && $dados['DATA_PAGAMENTO_DE'] !== null) {

            $valor = (string)$dados['DATA_PAGAMENTO_DE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'data_pagamento_de' deve conter apenas números (DDMMAAAA ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok
            } else if (strlen($valor) !== 8) {
                $erros[] = "Campo 'data_pagamento_de' deve estar no formato DDMMAAAA (8 dígitos) ou 0";
            }
        }

        /*
        Campo: dataPagamentoAte
        Tipo: Numérico
        Descrição: Data de Pagamento final da pesquisa (Formato: DDMMAAAA)
        Tamanho: 8 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['DATA_PAGAMENTO_ATE']) && $dados['DATA_PAGAMENTO_ATE'] !== '' && $dados['DATA_PAGAMENTO_ATE'] !== null) {
            
            $valor = (string)$dados['DATA_PAGAMENTO_ATE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'data_pagamento_ate' deve conter apenas números (DDMMAAAA ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok
            } else if (strlen($valor) !== 8) {
                $erros[] = "Campo 'data_pagamento_ate' deve estar no formato DDMMAAAA (8 dígitos) ou 0";
            }
        }

        /*
        Campo: origemPagamento
        Tipo: Numérico
        Descrição: Tipo de Registro (Origem) - Tabela 1
        Tamanho: 2 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['ORIGEM_PAGAMENTO']) && $dados['ORIGEM_PAGAMENTO'] !== '' && $dados['ORIGEM_PAGAMENTO'] !== null) {

            $valor = (string)$dados['ORIGEM_PAGAMENTO'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'origem_pagamento' deve conter apenas números (2 dígitos ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok (0 / 00)
            } else if (strlen($valor) !== 2) {
                $erros[] = "Campo 'origem_pagamento' deve ter 2 dígitos ou 0";
            }
        }

        /*
        Campo: valorTituloDe
        Tipo: Numérico
        Descrição: Valor do pagamento inicial da pesquisa (Formato: 9(15)V99)
        Tamanho: 17 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['VALOR_TITULO_DE']) && $dados['VALOR_TITULO_DE'] !== '' && $dados['VALOR_TITULO_DE'] !== null) {

            $valor = (string)$dados['VALOR_TITULO_DE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'valor_titulo_de' deve conter apenas números (17 dígitos ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok
            } else if (strlen($valor) !== 17) {
                $erros[] = "Campo 'valor_titulo_de' deve ter 17 dígitos (9(15)V99) ou 0";
            }
        }

        /*
        Campo: valorTituloAte
        Tipo: Numérico
        Descrição: Valor do pagamento final da pesquisa (Formato: 9(15)V99)
        Tamanho: 17 caracteres
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['VALOR_TITULO_ATE']) && $dados['VALOR_TITULO_ATE'] !== '' && $dados['VALOR_TITULO_ATE'] !== null) {

            $valor = (string)$dados['VALOR_TITULO_ATE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'valor_titulo_ate' deve conter apenas números (17 dígitos ou 0)";
            } else if (preg_match('/^0+$/', $valor)) {
                // ok
            } else if (strlen($valor) !== 17) {
                $erros[] = "Campo 'valor_titulo_ate' deve ter 17 dígitos (9(15)V99) ou 0";
            }
        }

        /*
        Campo: paginaAnterior
        Tipo: Numérico
        Descrição: Na primeira chamada enviar 0; nas seguintes enviar o valor do campo "pagina" retornado da última chamada
        Obrigatório: Não (se não informar, preencher com 0)
        */
        if (isset($dados['PAGINA_ANTERIOR']) && $dados['PAGINA_ANTERIOR'] !== '' && $dados['PAGINA_ANTERIOR'] !== null) {

            $valor = (string)$dados['PAGINA_ANTERIOR'];
            
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'pagina_anterior' deve conter apenas números (0 na primeira chamada)";
            }
        }
        
        return $erros;
    }

    static function validateDadosConsultaTituloPendente(array $dados) {
        $erros = [];
        
        // ====== CAMPOS OBRIGATÓRIOS DO CPF/CNPJ BENEFICIÁRIO ======
        /*
        Campo: cpfCnpj
        Tipo: Numérico
        Descrição: Raiz CPF/CNPJ Beneficiário
        Tamanho máximo: 9 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CPF_CNPJ']) || $dados['CPF_CNPJ'] === '' || $dados['CPF_CNPJ'] === null) {

            $erros[] = "Campo 'cpf_cnpj' é obrigatório (Raiz CPF/CNPJ Beneficiário - máx. 9 caracteres)";

        } else {

            $valor = (string)$dados['CPF_CNPJ'];
            
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'cpf_cnpj' deve conter apenas números";
            } else if (strlen($valor) > 9) {
                $erros[] = "Campo 'cpf_cnpj' deve ter no máximo 9 caracteres";
            }
        }

        /*
        Campo: filial
        Tipo: Numérico
        Descrição: Filial CPF/CNPJ do Beneficiário. Se CPF, filial = 0
        Tamanho máximo: 4 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['FILIAL']) || ($dados['FILIAL'] === '' && $dados['FILIAL'] !== 0)) {

            $erros[] = "Campo 'filial' é obrigatório (Filial CPF/CNPJ - máx. 4 caracteres, se CPF = 0)";
            
        } else {

            $valor = (string)$dados['FILIAL'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'filial' deve conter apenas números";
            } else if (strlen($valor) > 4) {
                $erros[] = "Campo 'filial' deve ter no máximo 4 caracteres";
            }
        }

        /*
        Campo: controle
        Tipo: Numérico
        Descrição: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['CONTROLE']) || $dados['CONTROLE'] === '' || $dados['CONTROLE'] === null) {

            $erros[] = "Campo 'controle' é obrigatório (Dígito de Controle - máx. 2 caracteres)";
            
        } else {

            $valor = (string)$dados['CONTROLE'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'controle' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'controle' deve ter no máximo 2 caracteres";
            }
        }

        // ====== CAMPOS DA CONSULTA ======

        /*
        Campo: produto
        Tipo: Numérico
        Descrição: ID Produto (código da carteira/modalidade de cobrança. Ex.: 09 Cobrança escritural, 05 Cobrança de Seguros)
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['PRODUTO']) || $dados['PRODUTO'] === '' || $dados['PRODUTO'] === null) {

            $erros[] = "Campo 'produto' é obrigatório (ID Produto - máx. 2 caracteres)";

        } else {

            $valor = (string)$dados['PRODUTO'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'produto' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'produto' deve ter no máximo 2 caracteres";
            }
        }

        /*
        Campo: negociacao
        Tipo: Numérico
        Descrição: Número da Negociação. Formato: Agência: 4 posições (sem dígito), Conta: 7 posições (sem dígito)
        Tamanho máximo: 11 caracteres
        Obrigatório: Sim
        */
        if (!isset($dados['NEGOCIACAO']) || $dados['NEGOCIACAO'] === '' || $dados['NEGOCIACAO'] === null) {

            $erros[] = "Campo 'negociacao' é obrigatório (Agência 4 posições + Conta 7 posições = 11 caracteres)";

        } else {

            $valor = (string)$dados['NEGOCIACAO'];

            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'negociacao' deve conter apenas números";
            } else if (strlen($valor) > 11) {
                $erros[] = "Campo 'negociacao' deve ter no máximo 11 caracteres";
            }
        }

        /*
        Campo: nossoNumero
        Tipo: Numérico
        Descrição: Identificação do título para o banco. Pode ser informado pelo cliente ou gerado pelo banco. Informar sem o dígito.
        Tamanho máximo: 11 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['NOSSO_NUMERO']) && $dados['NOSSO_NUMERO'] !== '' && $dados['NOSSO_NUMERO'] !== null) {
        //     $valor = (string)$dados['NOSSO_NUMERO'];
        //     if (!ctype_digit($valor)) {
        //         $erros[] = "Campo 'nosso_numero' deve conter apenas números";
        //     } else if (strlen($valor) > 11) {
        //         $erros[] = "Campo 'nosso_numero' deve ter no máximo 11 caracteres";
        //     }
        // }

        // ====== CPF/CNPJ PAGADOR (OPCIONAL) ======

        /*
        Campo: cpfCnpj (Pagador)
        Tipo: Numérico
        Descrição: Número documento (CNPJ ou CPF) do Pagador (raiz)
        Tamanho máximo: 9 caracteres
        Obrigatório: Não
        */
        if (isset($dados['CPF_CNPJ_PAGADOR']) && $dados['CPF_CNPJ_PAGADOR'] !== '' && $dados['CPF_CNPJ_PAGADOR'] !== null) {
            $valor = (string)$dados['CPF_CNPJ_PAGADOR'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'cpf_cnpj_pagador' deve conter apenas números";
            } else if (strlen($valor) > 9) {
                $erros[] = "Campo 'cpf_cnpj_pagador' deve ter no máximo 9 caracteres";
            }
        }

        /*
        Campo: filial (Pagador)
        Tipo: Numérico
        Descrição: Filial do CNPJ, se for CPF informar zeros do Pagador
        Tamanho máximo: 4 caracteres
        Obrigatório: Não
        */
        if (isset($dados['FILIAL_PAGADOR']) && $dados['FILIAL_PAGADOR'] !== '' && $dados['FILIAL_PAGADOR'] !== null) {
            $valor = (string)$dados['FILIAL_PAGADOR'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'filial_pagador' deve conter apenas números";
            } else if (strlen($valor) > 4) {
                $erros[] = "Campo 'filial_pagador' deve ter no máximo 4 caracteres";
            }
        }

        /*
        Campo: controle (Pagador)
        Tipo: Numérico
        Descrição: Controle do CNPJ ou CPF do Pagador
        Tamanho máximo: 2 caracteres
        Obrigatório: Não
        */
        if (isset($dados['CONTROLE_PAGADOR']) && $dados['CONTROLE_PAGADOR'] !== '' && $dados['CONTROLE_PAGADOR'] !== null) {
            $valor = (string)$dados['CONTROLE_PAGADOR'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'controle_pagador' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'controle_pagador' deve ter no máximo 2 caracteres";
            }
        }

        // ====== FILTROS DE DATA (OPCIONAIS) ======

        /*
        Campo: dataVencimentoDe
        Tipo: Numérico
        Descrição: Data de vencimento inicial da pesquisa. Formato DDMMAAAA
        Tamanho: 8 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['DATA_VENCIMENTO_DE']) && $dados['DATA_VENCIMENTO_DE'] !== '' && $dados['DATA_VENCIMENTO_DE'] !== null) {
        //     $valor = (string)$dados['DATA_VENCIMENTO_DE'];
        //     if (!ctype_digit($valor) || strlen($valor) !== 8) {
        //         $erros[] = "Campo 'data_vencimento_de' deve estar no formato DDMMAAAA (8 dígitos)";
        //     }
        // }

        /*
        Campo: dataVencimentoAte
        Tipo: Numérico
        Descrição: Data de vencimento final da pesquisa. Formato DDMMAAAA
        Tamanho: 8 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['DATA_VENCIMENTO_ATE']) && $dados['DATA_VENCIMENTO_ATE'] !== '' && $dados['DATA_VENCIMENTO_ATE'] !== null) {
        //     $valor = (string)$dados['DATA_VENCIMENTO_ATE'];
        //     if (!ctype_digit($valor) || strlen($valor) !== 8) {
        //         $erros[] = "Campo 'data_vencimento_ate' deve estar no formato DDMMAAAA (8 dígitos)";
        //     }
        // }

        /*
        Campo: dataRegistroDe
        Tipo: Numérico
        Descrição: Data de Registro inicial da pesquisa. Formato DDMMAAAA
        Tamanho: 8 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['DATA_REGISTRO_DE']) && $dados['DATA_REGISTRO_DE'] !== '' && $dados['DATA_REGISTRO_DE'] !== null) {
        //     $valor = (string)$dados['DATA_REGISTRO_DE'];
        //     if (!ctype_digit($valor) || strlen($valor) !== 8) {
        //         $erros[] = "Campo 'data_registro_de' deve estar no formato DDMMAAAA (8 dígitos)";
        //     }
        // }

        /*
        Campo: dataRegistroAte
        Tipo: Numérico
        Descrição: Data de Registro final da pesquisa. Formato DDMMAAAA
        Tamanho: 8 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['DATA_REGISTRO_ATE']) && $dados['DATA_REGISTRO_ATE'] !== '' && $dados['DATA_REGISTRO_ATE'] !== null) {
        //     $valor = (string)$dados['DATA_REGISTRO_ATE'];
        //     if (!ctype_digit($valor) || strlen($valor) !== 8) {
        //         $erros[] = "Campo 'data_registro_ate' deve estar no formato DDMMAAAA (8 dígitos)";
        //     }
        // }

        // ====== FILTRO DE VALOR (OPCIONAL) ======

        /*
        Campo: valorTituloDe
        Tipo: Numérico
        Descrição: Valor do título a partir do qual deve ser feita a pesquisa
        Tamanho máximo: 15 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['VALOR_TITULO_DE']) && $dados['VALOR_TITULO_DE'] !== '' && $dados['VALOR_TITULO_DE'] !== null) {
        //     $valor = (string)$dados['VALOR_TITULO_DE'];
        //     if (!ctype_digit($valor)) {
        //         $erros[] = "Campo 'valor_titulo_de' deve conter apenas números";
        //     } else if (strlen($valor) > 15) {
        //         $erros[] = "Campo 'valor_titulo_de' deve ter no máximo 15 caracteres";
        //     }
        // }

        // ====== PAGINAÇÃO / FAIXA (OBRIGATÓRIO + OPCIONAL) ======

        /*
        Campo: faixaVencto
        Tipo: Numérico
        Descrição: Faixa de vencimento na qual deve ser feita a pesquisa. Tabela 1 – Faixa
        Tamanho máximo: 2 caracteres
        Obrigatório: Sim
        */
        // if (!isset($dados['FAIXA_VENCIMENTO']) || $dados['FAIXA_VENCIMENTO'] === '' || $dados['FAIXA_VENCIMENTO'] === null) {
        //     $erros[] = "Campo 'faixa_vencimento' é obrigatório (Faixa de vencimento - máx. 2 caracteres)";
        // } else {
        //     $valor = (string)$dados['FAIXA_VENCIMENTO'];
        //     if (!ctype_digit($valor)) {
        //         $erros[] = "Campo 'faixa_vencimento' deve conter apenas números";
        //     } else if (strlen($valor) > 2) {
        //         $erros[] = "Campo 'faixa_vencimento' deve ter no máximo 2 caracteres";
        //     }
        // }

        /*
        Campo: paginaAnterior
        Tipo: Numérico
        Descrição: Última página retornada. Deve ser enviada para restart de paginação. No primeiro acesso enviar 0 (zero) e nos seguintes enviar conforme campo [pagina] retornado
        Tamanho máximo: 6 caracteres
        Obrigatório: Não
        */
        // if (isset($dados['PAGINA_ANTERIOR']) && $dados['PAGINA_ANTERIOR'] !== '' && $dados['PAGINA_ANTERIOR'] !== null) {
        //     $valor = (string)$dados['PAGINA_ANTERIOR'];
        //     if (!ctype_digit($valor)) {
        //         $erros[] = "Campo 'pagina_anterior' deve conter apenas números";
        //     } else if (strlen($valor) > 6) {
        //         $erros[] = "Campo 'pagina_anterior' deve ter no máximo 6 caracteres";
        //     }
        // }

        return $erros;
    }

    /**
     * Valida os dados antes de gerar o JSON de consulta de títulos baixados.
     *
     * Layout de entrada (Bradesco):
     * - versao: fixo 001
     * - cpfCnpj: { cpfCnpj, filial, controle }
     * - produto
     * - negociacao
     * - dataVencimentoDe / dataVencimentoAte (opcionais)
     * - valorTituloInicio (opcional)
     * - codigoBaixa (opcional)
     * - paginaAnterior (opcional)
     *
     * @param array $dados Dados para consulta de títulos baixados
     * @return array Array de strings com erros (vazio se OK)
     */
    static function validateDadosConsultaTitulosBaixados($dados) {
        $erros = [];

        // ====== LAYOUT ENTRADA - CONSULTA TITULOS BAIXADOS ======
        /*
        Campo: versao
        Tipo: Numérico
        Descrição: Fixo 001
        Tamanho: 3
        Obrigatório: Sim
        Observação: O campo é preenchido no builder com valor fixo 1.
        */

        // ====== BENEFICIARIO (cpfCnpj) ======
        /*
        Campo: cpfCnpj
        Tipo: Numérico
        Descrição: Raiz CPF/CNPJ Beneficiário
        Tamanho: 9
        Obrigatório: Sim
        */
        /*
        Campo: filial
        Tipo: Numérico
        Descrição: Filial CPF/CNPJ do Beneficiário (se CPF, filial = 0)
        Tamanho: 4
        Obrigatório: Sim
        */
        /*
        Campo: controle
        Tipo: Numérico
        Descrição: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho: 2
        Obrigatório: Sim
        */
        if (!isset($dados['CPF_CNPJ']) || $dados['CPF_CNPJ'] === '' || $dados['CPF_CNPJ'] === null) {
            $erros[] = "Campo 'cpf_cnpj' é obrigatório (Raiz CPF/CNPJ Beneficiário - máx. 9 caracteres)";
        } else {
            $valor = (string)$dados['CPF_CNPJ'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'cpf_cnpj' deve conter apenas números";
            } else if (strlen($valor) > 9) {
                $erros[] = "Campo 'cpf_cnpj' deve ter no máximo 9 caracteres";
            }
        }

        if (!isset($dados['FILIAL']) || $dados['FILIAL'] === '' || $dados['FILIAL'] === null) {
            $erros[] = "Campo 'filial' é obrigatório (Filial CPF/CNPJ do Beneficiário - máx. 4 caracteres)";
        } else {
            $valor = (string)$dados['FILIAL'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'filial' deve conter apenas números";
            } else if (strlen($valor) > 4) {
                $erros[] = "Campo 'filial' deve ter no máximo 4 caracteres";
            }
        }

        if (!isset($dados['CONTROLE']) || $dados['CONTROLE'] === '' || $dados['CONTROLE'] === null) {
            $erros[] = "Campo 'controle' é obrigatório (Dígito de Controle do CPF/CNPJ - máx. 2 caracteres)";
        } else {
            $valor = (string)$dados['CONTROLE'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'controle' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'controle' deve ter no máximo 2 caracteres";
            }
        }

        // ====== DADOS DA CONSULTA ======
        /*
        Campo: produto
        Tipo: Numérico
        Descrição: ID Produto (código da carteira/modalidade de cobrança. Ex.: 09, 05)
        Tamanho: 2
        Obrigatório: Sim
        */
        if (!isset($dados['PRODUTO']) || $dados['PRODUTO'] === '' || $dados['PRODUTO'] === null) {
            $erros[] = "Campo 'produto' é obrigatório (ID Produto - máx. 2 caracteres)";
        } else {
            $valor = (string)$dados['PRODUTO'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'produto' deve conter apenas números";
            } else if (strlen($valor) > 2) {
                $erros[] = "Campo 'produto' deve ter no máximo 2 caracteres";
            }
        }

        /*
        Campo: negociacao
        Tipo: Numérico
        Descrição: Número da Negociação (Agência: 4 + Conta: 7, sem dígito)
        Tamanho: 11
        Obrigatório: Sim
        */
        if (!isset($dados['NEGOCIACAO']) || $dados['NEGOCIACAO'] === '' || $dados['NEGOCIACAO'] === null) {
            $erros[] = "Campo 'negociacao' é obrigatório (11 dígitos)";
        } else {
            $valor = (string)$dados['NEGOCIACAO'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'negociacao' deve conter apenas números";
            } else if (strlen($valor) > 11) {
                $erros[] = "Campo 'negociacao' deve ter no máximo 11 caracteres";
            }
        }

        // ====== FILTROS OPCIONAIS ======
        /*
        Campo: dataVencimentoDe
        Tipo: Numérico
        Descrição: Data de vencimento inicial da pesquisa (Formato AAAAMMDD)
        Tamanho: 8
        Obrigatório: Não
        */
        /*
        Campo: dataVencimentoAte
        Tipo: Numérico
        Descrição: Data de vencimento final da pesquisa (Formato AAAAMMDD)
        Tamanho: 8
        Obrigatório: Não
        */
        // Aqui validamos 8 dígitos numéricos quando o campo for informado.
        foreach ([
            'DATA_VENCIMENTO_DE' => "dataVencimentoDe",
            'DATA_VENCIMENTO_ATE' => "dataVencimentoAte"
        ] as $campo => $label) {
            if (!isset($dados[$campo]) || $dados[$campo] === '' || $dados[$campo] === null) {
                continue;
            }

            $valor = (string)$dados[$campo];
            // Como padrão do seu sistema, opcional preenchido com 0
            if ($valor === '0') {
                continue;
            }

            if (!ctype_digit($valor) || strlen($valor) !== 8) {
                $erros[] = "Campo '$label' deve estar no formato numérico com 8 dígitos (AAAAMMDD ou DDMMAAAA)";
            }
        }

        /*
        Campo: valorTituloInicio
        Tipo: Numérico
        Descrição: Valor do título a partir do qual deve ser feita a pesquisa
        Formato: 9(13)V99
        Tamanho: 15
        Obrigatório: Não
        */
        if (isset($dados['VALOR_TITULO_INICIO']) && $dados['VALOR_TITULO_INICIO'] !== '' && $dados['VALOR_TITULO_INICIO'] !== null) {
            $v = str_replace(',', '.', (string)$dados['VALOR_TITULO_INICIO']);
            if (!is_numeric($v)) {
                $erros[] = "Campo 'valorTituloInicio' deve ser numérico";
            } else {
                // tolerante: garante até 2 casas decimais
                $vDec = (string)($v);
                if (strpos($vDec, '.') !== false) {
                    $frac = explode('.', $vDec, 2)[1];
                    if (strlen($frac) > 2) {
                        $erros[] = "Campo 'valorTituloInicio' deve ter no máximo 2 casas decimais";
                    }
                }
            }
        }

        /*
        Campo: codigoBaixa
        Tipo: Numérico
        Descrição: Código do tipo de baixa.
                   Se não informado, a API retorna todos os boletos baixados.
        Tamanho: 2
        Obrigatório: Não
        */
        if (isset($dados['CODIGO_BAIXA']) && $dados['CODIGO_BAIXA'] !== '' && $dados['CODIGO_BAIXA'] !== null) {
            $valor = (string)$dados['CODIGO_BAIXA'];
            if ($valor !== '0' && (!ctype_digit($valor) || strlen($valor) > 2)) {
                $erros[] = "Campo 'codigoBaixa' deve ter no máximo 2 dígitos numéricos";
            }
        }

        /*
        Campo: paginaAnterior
        Tipo: Numérico
        Descrição: Última página retornada (controle de paginação).
                   Primeiro acesso: enviar 0. Próximos: enviar a página retornada anteriormente.
        Tamanho: 6
        Obrigatório: Não
        */
        if (isset($dados['PAGINA_ANTERIOR']) && $dados['PAGINA_ANTERIOR'] !== '' && $dados['PAGINA_ANTERIOR'] !== null) {
            $valor = (string)$dados['PAGINA_ANTERIOR'];
            if (!ctype_digit($valor)) {
                $erros[] = "Campo 'paginaAnterior' deve conter apenas números";
            } else if (strlen($valor) > 6) {
                $erros[] = "Campo 'paginaAnterior' deve ter no máximo 6 caracteres";
            }
        }

        return $erros;
    }

    /**
     * Valida os dados da consulta de título unitário.
     *
     * Layout de entrada:
     * cpfCnpj, filial, controle, produto, negociacao, nossoNumero, sequencia e status(opcional)
     *
     * @param array $dados
     * @return array
     */
    static function validateDadosConsultaTituloUnitario($dados) {
        $erros = [];

        // ====== BENEFICIARIO (objeto cpfCnpj) ======
        /*
        Campo: cpfCnpj.cpfCnpj
        Tipo: Numérico
        Descrição: Raiz CPF/CNPJ Beneficiário
        Tamanho: 9
        Obrigatório: Sim
        */
        if (!isset($dados['CPF_CNPJ']) || $dados['CPF_CNPJ'] === '' || $dados['CPF_CNPJ'] === null) {
            $erros[] = "Campo 'cpf_cnpj' é obrigatório";
        } else {
            $v = (string)$dados['CPF_CNPJ'];
            if (!ctype_digit($v)) $erros[] = "Campo 'cpf_cnpj' deve conter apenas números";
            if (strlen($v) > 9) $erros[] = "Campo 'cpf_cnpj' deve ter no máximo 9 caracteres";
        }

        /*
        Campo: cpfCnpj.filial
        Tipo: Numérico
        Descrição: Filial CPF/CNPJ do Beneficiário. Se CPF, filial = 0
        Tamanho: 4
        Obrigatório: Sim
        */
        if (!isset($dados['FILIAL']) || $dados['FILIAL'] === '' || $dados['FILIAL'] === null) {
            $erros[] = "Campo 'filial' é obrigatório";
        } else {
            $v = (string)$dados['FILIAL'];
            if (!ctype_digit($v)) $erros[] = "Campo 'filial' deve conter apenas números";
            if (strlen($v) > 4) $erros[] = "Campo 'filial' deve ter no máximo 4 caracteres";
        }

        /*
        Campo: cpfCnpj.controle
        Tipo: Numérico
        Descrição: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho: 2
        Obrigatório: Sim
        */
        if (!isset($dados['CONTROLE']) || $dados['CONTROLE'] === '' || $dados['CONTROLE'] === null) {
            $erros[] = "Campo 'controle' é obrigatório";
        } else {
            $v = (string)$dados['CONTROLE'];
            if (!ctype_digit($v)) $erros[] = "Campo 'controle' deve conter apenas números";
            if (strlen($v) > 2) $erros[] = "Campo 'controle' deve ter no máximo 2 caracteres";
        }

        // ====== DADOS DA CONSULTA ======
        /*
        Campo: produto
        Tipo: Numérico
        Descrição: ID Produto (código da carteira/modalidade de cobrança. Ex.: 09 Cobrança escritural, 05 Cobrança de Seguros)
        Tamanho: 2
        Obrigatório: Sim
        */
        if (!isset($dados['PRODUTO']) || $dados['PRODUTO'] === '' || $dados['PRODUTO'] === null) {
            $erros[] = "Campo 'produto' é obrigatório";
        } else {
            $v = (string)$dados['PRODUTO'];
            if (!ctype_digit($v)) $erros[] = "Campo 'produto' deve conter apenas números";
            if (strlen($v) > 2) $erros[] = "Campo 'produto' deve ter no máximo 2 caracteres";
        }

        /*
        Campo: negociacao
        Tipo: Numérico
        Descrição: Número da Negociação. Formato: Agência 4 posições (sem dígito) + Conta 7 posições (sem dígito)
        Tamanho: 11
        Obrigatório: Sim
        */
        if (!isset($dados['NEGOCIACAO']) || $dados['NEGOCIACAO'] === '' || $dados['NEGOCIACAO'] === null) {
            $erros[] = "Campo 'negociacao' é obrigatório";
        } else {
            $v = (string)$dados['NEGOCIACAO'];
            if (!ctype_digit($v)) $erros[] = "Campo 'negociacao' deve conter apenas números";
            if (strlen($v) > 11) $erros[] = "Campo 'negociacao' deve ter no máximo 11 caracteres";
        }

        /*
        Campo: nossoNumero
        Tipo: Numérico
        Descrição: Número do Título (Nosso Número sem o dígito)
        Tamanho: 11
        Obrigatório: Sim
        */
        if (!isset($dados['NOSSO_NUMERO']) || $dados['NOSSO_NUMERO'] === '' || $dados['NOSSO_NUMERO'] === null) {
            $erros[] = "Campo 'nosso_numero' é obrigatório";
        } else {
            $v = (string)$dados['NOSSO_NUMERO'];
            if (!ctype_digit($v)) $erros[] = "Campo 'nosso_numero' deve conter apenas números";
            if (strlen($v) > 11) $erros[] = "Campo 'nosso_numero' deve ter no máximo 11 caracteres";
        }

        /*
        Campo: sequencia
        Tipo: Numérico
        Descrição: Número de sequência. Fixo "0"
        Tamanho: 1
        Obrigatório: Sim
        */
        // if (!isset($dados['SEQUENCIA']) || $dados['SEQUENCIA'] === '' || $dados['SEQUENCIA'] === null) {
        //     $erros[] = "Campo 'sequencia' é obrigatório";
        // } else if ((int)$dados['SEQUENCIA'] !== 0) {
        //     $erros[] = "Campo 'sequencia' deve ser 0";
        // }

        /*
        Campo: status
        Tipo: Numérico
        Descrição: Status do Título (Tabela de status)
        Tamanho: 2
        Obrigatório: Não
        */
        // $status = $dados['STATUS_TITULO'] ?? ($dados['STATUS'] ?? null);
        // if ($status !== '' && $status !== null) {
        //     $v = (string)$status;
        //     if (!ctype_digit($v)) $erros[] = "Campo 'status' deve conter apenas números";
        //     if (strlen($v) > 2) $erros[] = "Campo 'status' deve ter no máximo 2 caracteres";
        // }

        return $erros;
    }

    /**
     * Valida os dados antes de gerar o JSON de alteração de título.
     *
     * Baseado no layout fornecido (alteração /boleto/cobranca-alteracao/v1/alterar).
     *
     * @param array $dados Dados retornados do repositório
     * @return array Lista de erros (vazio se OK)
     */
    static function validateDadosAlteraTitulo($dados) {
        $erros = [];

        // ====== BENEFICIARIO (OBJETO cpfCnpj) ======
        /*
        Campo: cpfCnpj.cpfCnpj
        Tipo: Numérico
        Descrição: Raiz CPF/CNPJ Beneficiário
        Tamanho máximo: 9
        Obrigatório: Sim
        */
        /*
        Campo: cpfCnpj.filial
        Tipo: Numérico
        Descrição: Filial CPF/CNPJ do Beneficiário. Se CPF, filial = 0
        Tamanho máximo: 4
        Obrigatório: Sim
        */
        /*
        Campo: cpfCnpj.controle
        Tipo: Numérico
        Descrição: Dígito de Controle do CPF/CNPJ Beneficiário
        Tamanho máximo: 2
        Obrigatório: Sim
        */
        foreach ([
            'CPF_CNPJ' => 'cpf_cnpj',
            'FILIAL' => 'filial',
            'CONTROLE' => 'controle'
        ] as $campo => $label) {
            if (!isset($dados[$campo]) || $dados[$campo] === '' || $dados[$campo] === null) {
                $erros[] = "Campo '$label' é obrigatório";
                continue;
            }
        }

        // Validação de formato/tamanho: cpfCnpj.cpfCnpj
        if (isset($dados['CPF_CNPJ'])) {
            $v = (string)$dados['CPF_CNPJ'];
            if (!ctype_digit($v)) $erros[] = "Campo 'cpf_cnpj' deve conter apenas números";
            if (strlen($v) > 9) $erros[] = "Campo 'cpf_cnpj' deve ter no máximo 9 caracteres";
        }

        // Validação de formato/tamanho: cpfCnpj.filial
        if (isset($dados['FILIAL'])) {
            $v = (string)$dados['FILIAL'];
            if (!ctype_digit($v)) $erros[] = "Campo 'filial' deve conter apenas números";
            if (strlen($v) > 4) $erros[] = "Campo 'filial' deve ter no máximo 4 caracteres";
        }

        // Validação de formato/tamanho: cpfCnpj.controle
        if (isset($dados['CONTROLE'])) {
            $v = (string)$dados['CONTROLE'];
            if (!ctype_digit($v)) $erros[] = "Campo 'controle' deve conter apenas números";
            if (strlen($v) > 2) $erros[] = "Campo 'controle' deve ter no máximo 2 caracteres";
        }

        // ====== DADOS PRINCIPAIS DO TITULO ======
        /*
        Campo: produto
        Tipo: Numérico
        Descrição: ID Produto (código da carteira/modalidade de cobrança)
        Tamanho máximo: 2
        Obrigatório: Sim
        */
        /*
        Campo: negociacao
        Tipo: Numérico
        Descrição: Número da Negociação (Agência: 4 + Conta: 7)
        Tamanho máximo: 11
        Obrigatório: Sim
        */
        /*
        Campo: nossoNumero
        Tipo: Numérico
        Descrição: Número do Título (Nosso Número sem o dígito)
        Tamanho máximo: 11
        Obrigatório: Sim
        */
        foreach (['PRODUTO' => 2, 'NEGOCIACAO' => 11, 'NOSSO_NUMERO' => 11] as $campo => $max) {
            if (!isset($dados[$campo]) || $dados[$campo] === '' || $dados[$campo] === null) {
                $erros[] = "Campo '$campo' é obrigatório";
                continue;
            }
            $v = (string)$dados[$campo];
            if (!ctype_digit($v)) $erros[] = "Campo '$campo' deve conter apenas números";
            if (strlen($v) > $max) $erros[] = "Campo '$campo' deve ter no máximo $max caracteres";
        }

        // ====== DADOS TITULO > protesto ======
        /*
        Campo: protesto.codInstrucaoProtesto
        Tipo: Numérico
        Descrição: Código de Instrução Protesto (1 = Dias corridos / 2 = Dias úteis)
        Tamanho máximo: 1
        Obrigatório: Sim
        */
        if (!isset($dados['COD_INSTRUCAO_PROTESTO']) || $dados['COD_INSTRUCAO_PROTESTO'] === '' || $dados['COD_INSTRUCAO_PROTESTO'] === null) {
            $erros[] = "Campo 'codInstrucaoProtesto' (COD_INSTRUCAO_PROTESTO) é obrigatório";
        }

        // ====== DADOS TITULO > decurso ======
        /*
        Campo: decurso.codDecursoPrazo
        Tipo: Numérico
        Descrição: Código Decurso de Prazo
        Tamanho máximo: 1
        Obrigatório: Sim
        */
        if (!isset($dados['COD_DECURSO_PRAZO']) || $dados['COD_DECURSO_PRAZO'] === '' || $dados['COD_DECURSO_PRAZO'] === null) {
            $erros[] = "Campo 'codDecursoPrazo' (COD_DECURSO_PRAZO) é obrigatório";
        }

        // ====== DADOS TITULO > abatimento ======
        /*
        Campo: abatimento.tipoAbatimento
        Tipo: Numérico
        Descrição: Tipo do Abatimento - preencher com valor fixo "1"
        Tamanho máximo: 1
        Obrigatório: Sim
        */
        if (!isset($dados['TIPO_ABATIMENTO']) || $dados['TIPO_ABATIMENTO'] === '' || $dados['TIPO_ABATIMENTO'] === null) {
            $erros[] = "Campo 'tipoAbatimento' (TIPO_ABATIMENTO) é obrigatório (preencher com 1)";
        } else if ((int)$dados['TIPO_ABATIMENTO'] !== 1) {
            // A API pode rejeitar se não for 1
            $erros[] = "Campo 'tipoAbatimento' (TIPO_ABATIMENTO) deve ser 1";
        }

        // ====== DADOS TITULO > multa ======
        /*
        Campo: codigoMulta
        Tipo: Numérico
        Descrição: Código da Multa (1 = Valor / 2 = Percentual)
        Tamanho máximo: 1
        Obrigatório: Sim
        */
        if (!isset($dados['CODIGO_MULTA']) || $dados['CODIGO_MULTA'] === '' || $dados['CODIGO_MULTA'] === null) {
            $erros[] = "Campo 'codigoMulta' (CODIGO_MULTA) é obrigatório";
        }

        // ====== DADOS TITULO > negativacao ======
        /*
        Campo: codigoNegativacao
        Tipo: Numérico
        Descrição: Código da Negativação - preencher com valor fixo "1"
        Tamanho máximo: 1
        Obrigatório: Sim
        */
        if (!isset($dados['CODIGO_NEGATIVACAO']) || $dados['CODIGO_NEGATIVACAO'] === '' || $dados['CODIGO_NEGATIVACAO'] === null) {
            $erros[] = "Campo 'codigoNegativacao' (CODIGO_NEGATIVACAO) é obrigatório (preencher com 1)";
        } else if ((int)$dados['CODIGO_NEGATIVACAO'] !== 1) {
            $erros[] = "Campo 'codigoNegativacao' (CODIGO_NEGATIVACAO) deve ser 1";
        }

        // ====== CAMPOS OPCIONAIS (SEM BLOQUEIO) ======
        /*
        Campo: codigoControleParticipante
        Tipo: Alfanumérico
        Descrição: Código do Controle do Participante
        Tamanho máximo: 25
        Obrigatório: Não
        */
        /*
        Campo: indicadorAvisoSacado
        Tipo: Alfanumérico
        Descrição: Indicador Aviso Sacado. Fixo "0"
        Tamanho máximo: 1
        Obrigatório: Não
        */

        return $erros;
    }
}
