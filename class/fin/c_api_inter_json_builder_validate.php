<?php

/**
 * @package   astecv3
 * @name      c_api_inter_json_builder_validate
 * @version   4.5.00
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy <jhon.kened11@gmail.com>
 * @date      03/12/2025
 * @description Validação completa de dados para registro de boletos na API Inter
 */


Class c_api_inter_json_builder_validate {

    /**
     * Valida os dados antes de gerar o JSON de emitir cobrança
     * @param array $dados Array com os dados da cobrança (passado por referência; pode normalizar campos)
     * @return array Array com erros encontrados (vazio se não houver erros)
     */
    static function validateDadosEmitirCobranca(&$dados) {
        $erros = [];

        $ufsInter = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG',
            'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO',
        ];

        // ====== CORPO DA COBRANÇA (API Inter) — chaves conforme SELECT getDadosEmitirCobranca ======

        /* 
        Campo: seuNumero (Request Body)
        Tipo: string
        Descricao: Campo Seu Número do título
        Tamanho minimo: 1
        Tamanho maximo: 15
        Obrigatório: Sim
        Origem SELECT: seuNumero
        */
        if (!isset($dados['seuNumero']) || trim((string) $dados['seuNumero']) === '') {
            $erros[] = "Campo 'seuNumero' é obrigatório";
        } else {
            $valor = strlen(trim((string) $dados['seuNumero']));
            if ($valor < 1 || $valor > 15) {
                $erros[] = "Campo 'seuNumero' deve ter entre 1 e 15 caracteres";
            }
        }

        /* 
        Campo: valorNominal
        Tipo: number
        Descricao: Valor Nominal do título
        Intervalo: [ 2.5 .. 99999999.99 ]
        Obrigatório: Sim
        Origem SELECT: valorNominal
        */
        if (!isset($dados['valorNominal']) || $dados['valorNominal'] === '' || (float) $dados['valorNominal'] < 2.5) {
            $erros[] = "Campo 'valorNominal' é obrigatório e deve ser no mínimo 2.5";
        } elseif ((float) $dados['valorNominal'] > 99999999.99) {
            $erros[] = "Campo 'valorNominal' não pode ser maior que 99999999.99";
        }

        /* 
        Campo: dataVencimento
        Tipo: string <date>
        Descricao: Data de vencimento do título (YYYY-MM-DD; aceita também DD.MM.YYYY vindo do SELECT)
        Obrigatório: Sim
        Origem SELECT: dataVencimento
        */
        if (!isset($dados['dataVencimento']) || trim((string) $dados['dataVencimento']) === '') {
            $erros[] = "Campo 'dataVencimento' é obrigatório";
        } else {
            $rawV = trim((string) $dados['dataVencimento']);
            $dtVencimento = null;
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawV)) {
                $dtVencimento = DateTime::createFromFormat('Y-m-d', $rawV);
                if (!$dtVencimento || $dtVencimento->format('Y-m-d') !== $rawV) {
                    $dtVencimento = null;
                }
            } else {
                $dtVencimento = self::converterDataParaComparacao($rawV);
            }
            if ($dtVencimento === null) {
                $erros[] = "Campo 'dataVencimento' inválido (use YYYY-MM-DD ou DD.MM.AAAA)";
            }
            if (isset($dados['notaFiscalDataEmissao']) && trim((string) $dados['notaFiscalDataEmissao']) !== '') {
                $rawE = trim((string) $dados['notaFiscalDataEmissao']);
                $dtEmissao = null;
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawE)) {
                    $dtEmissao = DateTime::createFromFormat('Y-m-d', $rawE);
                    if (!$dtEmissao || $dtEmissao->format('Y-m-d') !== $rawE) {
                        $dtEmissao = null;
                    }
                } else {
                    $dtEmissao = self::converterDataParaComparacao($rawE);
                }
                if ($dtEmissao === null) {
                    $erros[] = "Campo 'notaFiscalDataEmissao' inválido (formato esperado: YYYY-MM-DD ou DD.MM.AAAA)";
                } elseif ($dtVencimento !== null && $dtVencimento < $dtEmissao) {
                    $erros[] = "Data de vencimento deve ser maior ou igual à data de emissão";
                }
            }
        }

        /* 
        Campo: numDiasAgenda
        Tipo: integer
        Descricao: Dias corridos após o vencimento para cancelamento automático (0 a 60). Default 0.
        Obrigatório: Sim na API; ausente no SELECT — valida apenas se informado no array
        */
        if (isset($dados['numDiasAgenda']) && $dados['numDiasAgenda'] !== '' && $dados['numDiasAgenda'] !== null) {
            $nda = (int) $dados['numDiasAgenda'];
            if ($nda < 0 || $nda > 60) {
                $erros[] = "Campo 'numDiasAgenda' deve estar entre 0 e 60";
            }
        }

        // ====== MORA (object mora — taxa + codigo TAXAMENSAL) ======

        /* 
        Campo: mora.taxa (moraTaxa no SELECT)
        Tipo: number (máx. duas casas decimais na API)
        Obrigatório: Não
        */
        $temPercentualJuros = isset($dados['moraTaxa']) && $dados['moraTaxa'] !== '' && (float) $dados['moraTaxa'] > 0;
        if ($temPercentualJuros) {
            $pj = (float) $dados['moraTaxa'];
            if (round($pj, 2) != $pj) {
                $erros[] = "Campo 'moraTaxa' (mora.taxa) deve ter no máximo duas casas decimais";
            }
        }

        if (isset($dados['mora']) && is_array($dados['mora'])) {
            if (!isset($dados['moraTaxa']) || $dados['moraTaxa'] === '' || $dados['moraTaxa'] === null) {
                $erros[] = "Campo 'moraTaxa' (mora.taxa) é obrigatório quando o objeto 'mora' é informado";
            } else {
                $pj = (float) $dados['moraTaxa'];
                if (round($pj, 2) != $pj) {
                    $erros[] = "Campo 'moraTaxa' (mora.taxa) deve ter no máximo duas casas decimais";
                }
            }
            $codMora = isset($dados['moraCodigo']) ? trim((string) $dados['moraCodigo']) : '';
            if ($codMora === '') {
                $erros[] = "Campo 'moraCodigo' (mora.codigo) é obrigatório quando o objeto 'mora' é informado";
            } elseif ($codMora !== 'TAXAMENSAL') {
                $erros[] = "Campo 'moraCodigo' (mora.codigo) deve ser TAXAMENSAL";
            }
        }

        // ====== MULTA (object multa — taxa + codigo PERCENTUAL) ======

        /* 
        Campo: multa.taxa (multaTaxa)
        */
        $temPercentualMulta = isset($dados['multaTaxa']) && $dados['multaTaxa'] !== '' && (float) $dados['multaTaxa'] > 0;
        if ($temPercentualMulta) {
            $pm = (float) $dados['multaTaxa'];
            if (round($pm, 2) != $pm) {
                $erros[] = "Campo 'multaTaxa' (multa.taxa) deve ter no máximo duas casas decimais";
            }
        }

        if (isset($dados['multa']) && is_array($dados['multa'])) {
            if (!isset($dados['multaTaxa']) || $dados['multaTaxa'] === '' || $dados['multaTaxa'] === null) {
                $erros[] = "Campo 'multaTaxa' (multa.taxa) é obrigatório quando o objeto 'multa' é informado";
            } else {
                $pm = (float) $dados['multaTaxa'];
                if (round($pm, 2) != $pm) {
                    $erros[] = "Campo 'multaTaxa' (multa.taxa) deve ter no máximo duas casas decimais";
                }
            }
            $codMulta = isset($dados['multaCodigo']) ? trim((string) $dados['multaCodigo']) : '';
            if ($codMulta === '') {
                $erros[] = "Campo 'multaCodigo' (multa.codigo) é obrigatório quando o objeto 'multa' é informado";
            } elseif ($codMulta !== 'PERCENTUAL') {
                $erros[] = "Campo 'multaCodigo' (multa.codigo) deve ser PERCENTUAL";
            }
        }

        // ====== DESCONTO (object desconto) ======

        $codigosDescontoValidos = ['VALORFIXODATAINFORMADA', 'PERCENTUALDATAINFORMADA'];

        $temPercentualDesconto = isset($dados['descontoTaxa']) && $dados['descontoTaxa'] !== '' && (float) $dados['descontoTaxa'] > 0;
        if ($temPercentualDesconto) {
            $pd = (float) $dados['descontoTaxa'];
            if (round($pd, 2) != $pd) {
                $erros[] = "Campo 'descontoTaxa' (desconto.taxa) deve ter no máximo duas casas decimais";
            }
            if (!isset($dados['descontoQuantidadeDias']) || $dados['descontoQuantidadeDias'] === '' || $dados['descontoQuantidadeDias'] === null) {
                $erros[] = "Campo 'descontoQuantidadeDias' é obrigatório quando 'descontoTaxa' é informado";
            }
            $codDesc = isset($dados['descontoCodigo']) ? trim((string) $dados['descontoCodigo']) : '';
            if ($codDesc === '' || !in_array($codDesc, $codigosDescontoValidos, true)) {
                $erros[] = "Campo 'descontoCodigo' (desconto.codigo) deve ser VALORFIXODATAINFORMADA ou PERCENTUALDATAINFORMADA quando há desconto";
            }
        }

        if (isset($dados['desconto']) && is_array($dados['desconto'])) {
            if (!isset($dados['descontoTaxa']) || $dados['descontoTaxa'] === '' || $dados['descontoTaxa'] === null) {
                $erros[] = "Campo 'descontoTaxa' (desconto.taxa) é obrigatório quando o objeto 'desconto' é informado";
            } else {
                $pd = (float) $dados['descontoTaxa'];
                if (round($pd, 2) != $pd) {
                    $erros[] = "Campo 'descontoTaxa' (desconto.taxa) deve ter no máximo duas casas decimais";
                }
            }
            $codDesc = isset($dados['descontoCodigo']) ? trim((string) $dados['descontoCodigo']) : '';
            if ($codDesc === '' || !in_array($codDesc, $codigosDescontoValidos, true)) {
                $erros[] = "Campo 'descontoCodigo' (desconto.codigo) é obrigatório e deve ser VALORFIXODATAINFORMADA ou PERCENTUALDATAINFORMADA";
            }
            if (!isset($dados['descontoQuantidadeDias']) || $dados['descontoQuantidadeDias'] === '' || $dados['descontoQuantidadeDias'] === null) {
                $erros[] = "Campo 'descontoQuantidadeDias' (desconto.quantidadeDias) é obrigatório quando o objeto 'desconto' é informado";
            } else {
                $qd = trim((string) $dados['descontoQuantidadeDias']);
                if ($qd === '' || !preg_match('/^-?\d+$/', $qd)) {
                    $erros[] = "Campo 'descontoQuantidadeDias' (desconto.quantidadeDias) deve ser um número inteiro";
                }
            }
        }

        // ====== DADOS DO PAGADOR (object pagador — API Inter) ======

        /* 
        Campo: pagador.nome
        Tipo: string
        Descricao: Nome do pagador
        Tamanho minimo: 1
        Tamanho maximo: 100
        Obrigatório: Sim
        Origem SELECT: pagadorNome
        */
        if (!isset($dados['pagadorNome']) || trim((string) $dados['pagadorNome']) === '') {
            $erros[] = "Campo 'pagadorNome' (pagador.nome) é obrigatório";
        } else {
            $valor = strlen($dados['pagadorNome']);
            if ($valor < 1 || $valor > 100) {
                $erros[] = "Campo 'pagadorNome' (pagador.nome) deve ter entre 1 e 100 caracteres";
            }
        }

        /* 
        Campo: pagador.endereco
        Origem SELECT: pagadorEndereco
        */
        if (!isset($dados['pagadorEndereco']) || trim((string) $dados['pagadorEndereco']) === '') {
            $erros[] = "Campo 'pagadorEndereco' (pagador.endereco) é obrigatório";
        } else {
            $valor = strlen($dados['pagadorEndereco']);
            if ($valor < 1 || $valor > 100) {
                $erros[] = "Campo 'pagadorEndereco' (pagador.endereco) deve ter entre 1 e 100 caracteres";
            }
        }

        /* 
        Campo: pagador.numero
        Origem SELECT: pagadorNumero
        */
        if (!isset($dados['pagadorNumero']) || trim((string) $dados['pagadorNumero']) === '') {
            $erros[] = "Campo 'pagadorNumero' (pagador.numero) é obrigatório";
        } else {
            $valor = strlen($dados['pagadorNumero']);
            if ($valor < 1 || $valor > 10) {
                $erros[] = "Campo 'pagadorNumero' (pagador.numero) deve ter entre 1 e 10 caracteres";
            }
        }

        /* 
        Campo: pagador.complemento
        Origem SELECT: pagadorComplemento
        */
        if (isset($dados['pagadorComplemento']) && strlen((string) $dados['pagadorComplemento']) > 30) {
            $erros[] = "Campo 'pagadorComplemento' (pagador.complemento) deve ter no máximo 30 caracteres";
        }

        /* 
        Campo: pagador.cep (8 dígitos; SELECT traz pagadorCep)
        */
        $cepDigits = preg_replace('/\D/', '', (string) ($dados['pagadorCep'] ?? ''));
        if (strlen($cepDigits) !== 8) {
            $erros[] = "CEP do pagador deve ter 8 dígitos (campo 'pagadorCep' apenas com números)";
        }

        /* 
        Campo: pagador.bairro (opcional na API; máx. 60)
        Origem SELECT: pagadorBairro
        */
        if (isset($dados['pagadorBairro']) && strlen((string) $dados['pagadorBairro']) > 60) {
            $erros[] = "Campo 'pagadorBairro' (pagador.bairro) deve ter no máximo 60 caracteres";
        }

        /* 
        Campo: pagador.cidade
        Origem SELECT: pagadorCidade
        */
        if (!isset($dados['pagadorCidade']) || trim((string) $dados['pagadorCidade']) === '') {
            $erros[] = "Campo 'pagadorCidade' (pagador.cidade) é obrigatório";
        } else {
            $valor = strlen($dados['pagadorCidade']);
            if ($valor < 1 || $valor > 60) {
                $erros[] = "Campo 'pagadorCidade' (pagador.cidade) deve ter entre 1 e 60 caracteres";
            }
        }

        /* 
        Campo: pagador.uf (EnumUF)
        Origem SELECT: pagadorUf
        */
        if (!isset($dados['pagadorUf']) || trim((string) $dados['pagadorUf']) === '') {
            $erros[] = "Campo 'pagadorUf' (pagador.uf) é obrigatório";
        } else {
            $uf = strtoupper(trim((string) $dados['pagadorUf']));
            if (strlen($uf) !== 2 || !in_array($uf, $ufsInter, true)) {
                $erros[] = "Campo 'pagadorUf' (pagador.uf) deve ser uma UF válida (EnumUF)";
            }
        }

        /* 
        Campo: pagador.tipoPessoa (FISICA/JURIDICA)
        Origem SELECT: pagadorTipoPessoa
        */
        if (!isset($dados['pagadorTipoPessoa']) || $dados['pagadorTipoPessoa'] === '') {
            $erros[] = "Campo 'pagadorTipoPessoa' (pagador.tipoPessoa) é obrigatório";
        } elseif ((string) $dados['pagadorTipoPessoa'] !== 'FISICA' && (string) $dados['pagadorTipoPessoa'] !== 'JURIDICA') {
            $erros[] = "Campo 'pagadorTipoPessoa' deve ser FISICA ou JURIDICA";
        }

        /* 
        Campo: pagador.cpfCnpj (11 a 18 caracteres na API)
        Origem SELECT: pagadorCpfCnpj
        */
        if (!isset($dados['pagadorCpfCnpj']) || trim((string) $dados['pagadorCpfCnpj']) === '') {
            $erros[] = "Campo 'pagadorCpfCnpj' (pagador.cpfCnpj) é obrigatório";
        } else {
            $docLimpo = preg_replace('/\D/', '', (string) $dados['pagadorCpfCnpj']);
            $lenDoc = strlen($docLimpo);
            if ($lenDoc < 11 || $lenDoc > 18) {
                $erros[] = "Campo 'pagadorCpfCnpj' (pagador.cpfCnpj) deve ter entre 11 e 18 dígitos";
            }
        }

        /* 
        Campo: pagador.email (máx. 50)
        Origem SELECT: pagadorEmail
        */
        if (isset($dados['pagadorEmail']) && strlen((string) $dados['pagadorEmail']) > 50) {
            $erros[] = "Campo 'pagadorEmail' (pagador.email) deve ter no máximo 50 caracteres";
        }

        /* Campo: pagador.ddd / pagador.telefone */
        if (isset($dados['pagadorDdd']) && trim((string) $dados['pagadorDdd']) !== '' && strlen((string) $dados['pagadorDdd']) > 2) {
            $erros[] = "Campo 'pagadorDdd' (pagador.ddd) deve ter no máximo 2 caracteres";
        }
        if (isset($dados['pagadorTelefone']) && trim((string) $dados['pagadorTelefone']) !== '' && strlen((string) $dados['pagadorTelefone']) > 9) {
            $erros[] = "Campo 'pagadorTelefone' (pagador.telefone) deve ter no máximo 9 caracteres";
        }

        /* 
        Campo: mensagem.linha1 (demais linhas opcionais; SELECT traz um único campo)
        Origem SELECT: mensagemLinha1
        */
        if (isset($dados['mensagemLinha1']) && trim((string) $dados['mensagemLinha1']) !== '') {
            $valor = strlen($dados['mensagemLinha1']);
            if ($valor > 78) {
                $erros[] = "Campo 'mensagemLinha1' (mensagem.linha1) deve ter no máximo 78 caracteres";
            }
        }

        $linhasMensagem = [
            'mensagemLinha2' => 'linha2',
            'mensagemLinha3' => 'linha3',
            'mensagemLinha4' => 'linha4',
            'mensagemLinha5' => 'linha5',
        ];
        foreach ($linhasMensagem as $campoLinha => $apiLinha) {
            if (isset($dados[$campoLinha]) && trim((string) $dados[$campoLinha]) !== '' && strlen((string) $dados[$campoLinha]) > 78) {
                $erros[] = "Campo '{$campoLinha}' (mensagem.{$apiLinha}) deve ter no máximo 78 caracteres";
            }
        }

        // ====== BENEFICIÁRIO FINAL (object beneficiarioFinal — builder usa beneficiarioFinal*; SELECT pode trazer beneficiario*) ======

        $bfCpf = $dados['beneficiarioFinalCpfCnpj'] ?? $dados['beneficiarioCpfCnpj'] ?? null;
        $bfTipo = $dados['beneficiarioFinalTipoPessoa'] ?? $dados['beneficiarioTipoPessoa'] ?? null;
        $bfNome = $dados['beneficiarioFinalNome'] ?? $dados['beneficiarioNome'] ?? null;
        $bfEnd = $dados['beneficiarioFinalEndereco'] ?? $dados['beneficiarioEndereco'] ?? null;
        $bfBairro = $dados['beneficiarioFinalBairro'] ?? $dados['beneficiarioBairro'] ?? null;
        $bfCidade = $dados['beneficiarioFinalCidade'] ?? $dados['beneficiarioCidade'] ?? null;
        $bfUf = $dados['beneficiarioFinalUf'] ?? $dados['beneficiarioUf'] ?? null;
        $bfCep = $dados['beneficiarioFinalCep'] ?? $dados['beneficiarioCep'] ?? null;

        if (!isset($bfCpf) || trim((string) $bfCpf) === '') {
            $erros[] = "Campo 'beneficiarioFinalCpfCnpj' (beneficiarioFinal.cpfCnpj) é obrigatório";
        } else {
            $docBf = preg_replace('/\D/', '', (string) $bfCpf);
            $lenBf = strlen($docBf);
            if ($lenBf < 11 || $lenBf > 18) {
                $erros[] = "Campo 'beneficiarioFinalCpfCnpj' (beneficiarioFinal.cpfCnpj) deve ter entre 11 e 18 dígitos";
            }
        }

        if (!isset($bfTipo) || trim((string) $bfTipo) === '') {
            $erros[] = "Campo 'beneficiarioFinalTipoPessoa' (beneficiarioFinal.tipoPessoa) é obrigatório";
        } elseif ((string) $bfTipo !== 'FISICA' && (string) $bfTipo !== 'JURIDICA') {
            $erros[] = "Campo 'beneficiarioFinalTipoPessoa' deve ser FISICA ou JURIDICA";
        }

        if (!isset($bfNome) || trim((string) $bfNome) === '') {
            $erros[] = "Campo 'beneficiarioFinalNome' (beneficiarioFinal.nome) é obrigatório";
        } else {
            $ln = strlen((string) $bfNome);
            if ($ln < 1 || $ln > 100) {
                $erros[] = "Campo 'beneficiarioFinalNome' (beneficiarioFinal.nome) deve ter entre 1 e 100 caracteres";
            }
        }

        if (!isset($bfEnd) || trim((string) $bfEnd) === '') {
            $erros[] = "Campo 'beneficiarioFinalEndereco' (beneficiarioFinal.endereco) é obrigatório";
        } else {
            $le = strlen((string) $bfEnd);
            if ($le < 1 || $le > 100) {
                $erros[] = "Campo 'beneficiarioFinalEndereco' (beneficiarioFinal.endereco) deve ter entre 1 e 100 caracteres";
            }
        }

        if (isset($bfBairro) && strlen((string) $bfBairro) > 60) {
            $erros[] = "Campo 'beneficiarioFinalBairro' (beneficiarioFinal.bairro) deve ter no máximo 60 caracteres";
        }

        if (!isset($bfCidade) || trim((string) $bfCidade) === '') {
            $erros[] = "Campo 'beneficiarioFinalCidade' (beneficiarioFinal.cidade) é obrigatório";
        } else {
            $lc = strlen((string) $bfCidade);
            if ($lc < 1 || $lc > 60) {
                $erros[] = "Campo 'beneficiarioFinalCidade' (beneficiarioFinal.cidade) deve ter entre 1 e 60 caracteres";
            }
        }

        if (!isset($bfUf) || trim((string) $bfUf) === '') {
            $erros[] = "Campo 'beneficiarioFinalUf' (beneficiarioFinal.uf) é obrigatório";
        } else {
            $ufBf = strtoupper(trim((string) $bfUf));
            if (strlen($ufBf) !== 2 || !in_array($ufBf, $ufsInter, true)) {
                $erros[] = "Campo 'beneficiarioFinalUf' (beneficiarioFinal.uf) deve ser uma UF válida (EnumUF)";
            }
        }

        $cepBfDigits = preg_replace('/\D/', '', (string) ($bfCep ?? ''));
        if (strlen($cepBfDigits) !== 8) {
            $erros[] = "CEP do beneficiário final deve ter 8 dígitos (campo 'beneficiarioFinalCep' ou 'beneficiarioCep')";
        }

        // ====== FORMAS DE RECEBIMENTO (fixo BOLETO + PIX; padrão se ausente ou null) ======

        if (!array_key_exists('formasRecebimento', $dados) || $dados['formasRecebimento'] === null) {
            $dados['formasRecebimento'] = ['BOLETO', 'PIX'];
        }

        // ====== NOTA FISCAL (object notaFiscal) ======

        if (isset($dados['notaFiscal']) && is_array($dados['notaFiscal'])) {
            $chaveNfe = preg_replace('/\D/', '', (string) ($dados['notaFiscalChaveNFe'] ?? ''));
            if (strlen($chaveNfe) !== 44 || !ctype_digit($chaveNfe)) {
                $erros[] = "Campo 'notaFiscalChaveNFe' (notaFiscal.chaveNFe) deve ter 44 dígitos numéricos";
            }

            if (!isset($dados['notaFiscalNumero']) || $dados['notaFiscalNumero'] === '' || $dados['notaFiscalNumero'] === null) {
                $erros[] = "Campo 'notaFiscalNumero' (notaFiscal.numero) é obrigatório quando o objeto 'notaFiscal' é informado";
            } elseif (!preg_match('/^-?\d+$/', trim((string) $dados['notaFiscalNumero']))) {
                $erros[] = "Campo 'notaFiscalNumero' (notaFiscal.numero) deve ser um número inteiro (int32)";
            }

            if (!isset($dados['notaFiscalSerie']) || $dados['notaFiscalSerie'] === '' || $dados['notaFiscalSerie'] === null) {
                $erros[] = "Campo 'notaFiscalSerie' (notaFiscal.serie) é obrigatório quando o objeto 'notaFiscal' é informado";
            } elseif (!preg_match('/^-?\d+$/', trim((string) $dados['notaFiscalSerie']))) {
                $erros[] = "Campo 'notaFiscalSerie' (notaFiscal.serie) deve ser um número inteiro (int32)";
            }

            if (!isset($dados['notaFiscalDataEmissao']) || trim((string) $dados['notaFiscalDataEmissao']) === '') {
                $erros[] = "Campo 'notaFiscalDataEmissao' (notaFiscal.dataEmissao) é obrigatório quando o objeto 'notaFiscal' é informado";
            } else {
                $rawNf = trim((string) $dados['notaFiscalDataEmissao']);
                $dtNf = null;
                if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawNf)) {
                    $dtNf = DateTime::createFromFormat('Y-m-d', $rawNf);
                    if (!$dtNf || $dtNf->format('Y-m-d') !== $rawNf) {
                        $dtNf = null;
                    }
                } else {
                    $dtNf = self::converterDataParaComparacao($rawNf);
                }
                if ($dtNf === null) {
                    $erros[] = "Campo 'notaFiscalDataEmissao' (notaFiscal.dataEmissao) inválido (use YYYY-MM-DD ou DD.MM.AAAA)";
                }
            }

            if (isset($dados['notaFiscalParcela']) && $dados['notaFiscalParcela'] !== '' && $dados['notaFiscalParcela'] !== null) {
                if (!preg_match('/^-?\d+$/', trim((string) $dados['notaFiscalParcela']))) {
                    $erros[] = "Campo 'notaFiscalParcela' (notaFiscal.parcela) deve ser um número inteiro (int32)";
                }
            }
        }

        return $erros;
    }


    /**
     * Valida os parâmetros de consulta para recuperar coleção de cobranças
     * @param array $dados Array com os parâmetros da consulta (passado por referência; pode normalizar campos)
     * @return array Array com erros encontrados (vazio se não houver erros)
     */
    static function validateDadosRecuperarColecaoCobranca(&$dados) {

        $erros = [];

        // ====== dataInicial (obrigatório, YYYY-MM-DD) ======

        /*
        Campo: dataInicial
        Tipo: string <date>
        Descrição: Data de vencimento do título (início do filtro)
        Formato aceito: YYYY-MM-DD
        Obrigatório: Sim
        */
        $dtInicial = null;
        if (!isset($dados['dataInicial']) || trim((string) $dados['dataInicial']) === '') {
            $erros[] = "Campo 'dataInicial' é obrigatório";
        } else {
            $rawInicial = trim((string) $dados['dataInicial']);
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawInicial)) {
                $dtInicial = DateTime::createFromFormat('Y-m-d', $rawInicial);
                if (!$dtInicial || $dtInicial->format('Y-m-d') !== $rawInicial) {
                    $dtInicial = null;
                }
            } else {
                $dtInicial = self::converterDataParaComparacao($rawInicial);
            }
            if ($dtInicial === null) {
                $erros[] = "Campo 'dataInicial' inválido (use YYYY-MM-DD ou DD.MM.AAAA)";
            }
        }

        // ====== dataFinal (obrigatório, YYYY-MM-DD) ======

        /*
        Campo: dataFinal
        Tipo: string <date>
        Descrição: Data de fim do filtro
        Formato aceito: YYYY-MM-DD
        Obrigatório: Sim
        */
        $dtFinal = null;
        if (!isset($dados['dataFinal']) || trim((string) $dados['dataFinal']) === '') {
            $erros[] = "Campo 'dataFinal' é obrigatório";
        } else {

            $rawFinal = trim((string) $dados['dataFinal']);

            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $rawFinal)) {

                $dtFinal = DateTime::createFromFormat('Y-m-d', $rawFinal);

                if (!$dtFinal || $dtFinal->format('Y-m-d') !== $rawFinal) {
                    $dtFinal = null;
                }

            } else {
                $dtFinal = self::converterDataParaComparacao($rawFinal);
            }

            if ($dtFinal === null) {
                $erros[] = "Campo 'dataFinal' inválido (use YYYY-MM-DD ou DD.MM.AAAA)";
            }
        }

        // Valida que dataFinal >= dataInicial quando ambas são válidas
        if ($dtInicial !== null && $dtFinal !== null && $dtFinal < $dtInicial) {
            $erros[] = "Campo 'dataFinal' deve ser maior ou igual a 'dataInicial'";
        }

        // ====== filtrarDataPor (opcional, enum, default VENCIMENTO) ======

        /*
        Campo: filtrarDataPor
        Tipo: string (FiltrarDataPorEnum)
        Enum: "VENCIMENTO" "EMISSAO" "PAGAMENTO"
        Default: "VENCIMENTO"
        Obrigatório: Não
        */
        $filtrarDataPorValidos = ['VENCIMENTO', 'EMISSAO', 'PAGAMENTO'];
        if (!array_key_exists('filtrarDataPor', $dados) || $dados['filtrarDataPor'] === null || $dados['filtrarDataPor'] === '') {
            $dados['filtrarDataPor'] = 'VENCIMENTO';
        } else {

            $filtrarDataPor = strtoupper(trim((string) $dados['filtrarDataPor']));

            if (!in_array($filtrarDataPor, $filtrarDataPorValidos, true)) {
                $erros[] = "Campo 'filtrarDataPor' deve ser VENCIMENTO, EMISSAO ou PAGAMENTO";
            } else {
                $dados['filtrarDataPor'] = $filtrarDataPor;
            }
        }

        // ====== situacao (opcional, enum) ======

        /*
        Campo: situacao
        Tipo: string (SituacaoCobrancaEnum)
        Enum: "RECEBIDO" "A_RECEBER" "MARCADO_RECEBIDO" "ATRASADO" "CANCELADO" "EXPIRADO"
              "FALHA_EMISSAO" "EM_PROCESSAMENTO" "PROTESTO"
        Obrigatório: Não
        */
        $situacaoValidas = [
            'RECEBIDO', 'A_RECEBER', 'MARCADO_RECEBIDO', 'ATRASADO', 'CANCELADO',
            'EXPIRADO', 'FALHA_EMISSAO', 'EM_PROCESSAMENTO', 'PROTESTO',
        ];

        if (isset($dados['situacao']) && trim((string) $dados['situacao']) !== '') {
            $situacao = strtoupper(trim((string) $dados['situacao']));

            if (!in_array($situacao, $situacaoValidas, true)) {
                $erros[] = "Campo 'situacao' inválido. Valores aceitos: " . implode(', ', $situacaoValidas);
            } else {
                $dados['situacao'] = $situacao;
            }
        }

        // ====== pessoaPagadora (opcional, string) ======

        /*
        Campo: pessoaPagadora
        Tipo: string (Nome da pessoa pagadora)
        Obrigatório: Não
        */
        if (isset($dados['pessoaPagadora']) && trim((string) $dados['pessoaPagadora']) !== '') {
            // sem limite de tamanho definido na spec; apenas sanitiza
            $dados['pessoaPagadora'] = trim((string) $dados['pessoaPagadora']);
        }

        // ====== cpfCnpjPessoaPagadora (opcional, [1..18] chars) ======

        /*
        Campo: cpfCnpjPessoaPagadora
        Tipo: string (cpfCnpj da pessoa pagadora)
        Tamanho: [ 1 .. 18 ] characters
        Obrigatório: Não
        */
        if (isset($dados['cpfCnpjPessoaPagadora']) && trim((string) $dados['cpfCnpjPessoaPagadora']) !== '') {
            $cpfCnpj = trim((string) $dados['cpfCnpjPessoaPagadora']);
            $len = strlen($cpfCnpj);
            if ($len < 1 || $len > 18) {
                $erros[] = "Campo 'cpfCnpjPessoaPagadora' deve ter entre 1 e 18 caracteres";
            }
        }

        // ====== seuNumero (opcional, <=15 chars) ======

        /*
        Campo: seuNumero
        Tipo: string (Seu código da cobrança)
        Tamanho máximo: 15 characters
        Obrigatório: Não
        */
        if (isset($dados['seuNumero']) && trim((string) $dados['seuNumero']) !== '') {
            if (strlen(trim((string) $dados['seuNumero'])) > 15) {
                $erros[] = "Campo 'seuNumero' deve ter no máximo 15 caracteres";
            }
        }

        // ====== tipoCobranca (opcional, enum) ======

        /*
        Campo: tipoCobranca
        Tipo: string (TipoCobrancaEnum)
        Enum: "SIMPLES" "PARCELADO" "RECORRENTE"
        Obrigatório: Não
        */
        $tipoCobrancaValidos = ['SIMPLES', 'PARCELADO', 'RECORRENTE'];
        if (isset($dados['tipoCobranca']) && trim((string) $dados['tipoCobranca']) !== '') {
            $tipoCobranca = strtoupper(trim((string) $dados['tipoCobranca']));
            if (!in_array($tipoCobranca, $tipoCobrancaValidos, true)) {
                $erros[] = "Campo 'tipoCobranca' deve ser SIMPLES, PARCELADO ou RECORRENTE";
            } else {
                $dados['tipoCobranca'] = $tipoCobranca;
            }
        }

        // ====== paginacao.itensPorPagina (opcional, int <=1000, default 100) ======

        /*

        Quantidade máxima de registros retornados em cada página. 
        Apenas a última página pode conter uma quantidade menor de registros.
        
        Campo: paginacao.itensPorPagina
        Tipo: integer <int32>
        Tamanho máximo: 1000
        Default: 100
        Obrigatório: Não
        */
        if (!array_key_exists('paginacao.itensPorPagina', $dados) || $dados['paginacao.itensPorPagina'] === null || $dados['paginacao.itensPorPagina'] === '') {
            $dados['paginacao.itensPorPagina'] = 100;
        } else {
            if (!preg_match('/^\d+$/', trim((string) $dados['paginacao.itensPorPagina']))) {
                $erros[] = "Campo 'paginacao.itensPorPagina' deve ser um número inteiro positivo";
            } else {
                $itensPorPagina = (int) $dados['paginacao.itensPorPagina'];
                if ($itensPorPagina < 1 || $itensPorPagina > 1000) {
                    $erros[] = "Campo 'paginacao.itensPorPagina' deve estar entre 1 e 1000";
                } else {
                    $dados['paginacao.itensPorPagina'] = $itensPorPagina;
                }
            }
        }

        // ====== paginacao.paginaAtual (opcional, int >=0, default 0) ======

        /*
        Número da página a ser retornada. 
        A primeira página é a 0 (zero). 
        A próxima página é a 1, e assim por diante. 
        Se não informado, a API retorna a primeira página (0).
        */
        /*
        Campo: paginacao.paginaAtual
        Tipo: integer <int32>
        Default: 0
        Obrigatório: Não
        */
        if (!array_key_exists('paginacao.paginaAtual', $dados) || $dados['paginacao.paginaAtual'] === null || $dados['paginacao.paginaAtual'] === '') {
            $dados['paginacao.paginaAtual'] = 0;
        } else {
            if (!preg_match('/^\d+$/', trim((string) $dados['paginacao.paginaAtual']))) {
                $erros[] = "Campo 'paginacao.paginaAtual' deve ser um número inteiro maior ou igual a 0";
            } else {
                $dados['paginacao.paginaAtual'] = (int) $dados['paginacao.paginaAtual'];
            }
        }

        // ====== ordenarPor (opcional, enum, default PESSOA_PAGADORA) ======

        /*
        Ordenação dos registros retornados. 
        Se não informado, a API retorna os registros ordenados por PESSOA_PAGADORA.
        
        Campo: ordenarPor
        Tipo: string (OrdenarCobrancasPorEnum)
        Enum: "PESSOA_PAGADORA" "TIPO_COBRANCA" "CODIGO_COBRANCA" "IDENTIFICADOR"
              "DATA_EMISSAO" "DATA_VENCIMENTO" "VALOR" "STATUS"
        Default: "PESSOA_PAGADORA"
        Obrigatório: Não
        */
        $ordenarPorValidos = [
            'PESSOA_PAGADORA', 'TIPO_COBRANCA', 'CODIGO_COBRANCA', 'IDENTIFICADOR',
            'DATA_EMISSAO', 'DATA_VENCIMENTO', 'VALOR', 'STATUS',
        ];
        if (!array_key_exists('ordenarPor', $dados) || $dados['ordenarPor'] === null || $dados['ordenarPor'] === '') {
            $dados['ordenarPor'] = '';
        } else {
            $ordenarPor = strtoupper(trim((string) $dados['ordenarPor']));
            if (!in_array($ordenarPor, $ordenarPorValidos, true)) {
                $erros[] = "Campo 'ordenarPor' inválido. Valores aceitos: " . implode(', ', $ordenarPorValidos);
            } else {
                $dados['ordenarPor'] = $ordenarPor;
            }
        }

        // ====== tipoOrdenacao (opcional, enum, default ASC) ======

        /*
        Tipo de ordenação dos registros retornados. 
        Se não informado, a API retorna os registros ordenados por ASC.
        
        Campo: tipoOrdenacao
        Tipo: string (TipoOrdenacaoCobrancasEnum)
        Enum: "ASC" "DESC"
        Default: "ASC"
        Obrigatório: Não
        */
        $tipoOrdenacaoValidos = ['ASC', 'DESC'];
        if (!array_key_exists('tipoOrdenacao', $dados) || $dados['tipoOrdenacao'] === null || $dados['tipoOrdenacao'] === '') {
            $dados['tipoOrdenacao'] = 'ASC';
        } else {
            $tipoOrdenacao = strtoupper(trim((string) $dados['tipoOrdenacao']));
            if (!in_array($tipoOrdenacao, $tipoOrdenacaoValidos, true)) {
                $erros[] = "Campo 'tipoOrdenacao' deve ser ASC ou DESC";
            } else {
                $dados['tipoOrdenacao'] = $tipoOrdenacao;
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
    
  
}
