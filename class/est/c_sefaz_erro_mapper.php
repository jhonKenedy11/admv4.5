<?php

class c_sefaz_erro_mapper
{
    /**
     * Erro vindo em $std->xMotivo (retorno SEFAZ).
     */
    public static function fromMotivo($cStat, $xMotivo)
    {
        $msg = (string) $xMotivo;

        // 1) Tenta mapear erro de pattern de schema
        $texto = self::mapPatternError($msg, false);
        if ($texto !== null) {
            return $texto;
        }

        // 2) Elemento de imposto/bloco incompleto no XML (ex.: PIS sem PISAliq/PISNT)
        $texto = self::mapComplexTypeError($msg);
        if ($texto !== null) {
            return $texto;
        }

        // 225 - Erro de schema
        if ($cStat == '225') {
            $textoSchema = self::mapSchemaElementError($msg);
            if ($textoSchema !== null) {
                return $textoSchema;
            }

            return 'Falha no schema XML da NF-e. Verifique os dados do cliente, itens, impostos e cobrança.';
        }

        // 2) Falha genérica de schema
        if (strpos($msg, 'Falha no Schema XML do lote de NFe') !== false) {
            return 'Falha na validação do XML da NF-e. Verifique campos obrigatórios e formatos (IE, CNPJ/CPF, chave, observações, etc.).';
        }

        // 3) Fallback simples (sem despejar XML inteiro)
        $cStat = (string) $cStat;
        if ($cStat !== '') {
            return "Rejeição SEFAZ (cStat {$cStat}): {$msg}";
        }
  
        return $msg;
    }

    /**
     * Erro vindo em Exception (PHP / NFePHP).
     */
    public static function fromException(\Throwable $e)
    {
        $msg = $e->getMessage();

        // 1) Tenta mapear erro de pattern de schema (com detalhe técnico)
        $texto = self::mapPatternError($msg, true);
        if ($texto !== null) {
            return $texto;
        }

        // 2) Elemento de imposto/bloco incompleto no XML
        $texto = self::mapComplexTypeError($msg, true);
        if ($texto !== null) {
            return $texto;
        }

        // 3) Outros erros de validação XML
        if (strpos($msg, 'SAXParseException') !== false || strpos($msg, 'cvc-') !== false) {
            return 'Falha na validação do XML da NF-e. Verifique se todos os campos obrigatórios estão preenchidos e com o formato correto.';
        }

        // 3) Fallback genérico
        return 'Erro ao processar a NF-e.';
    }

    /**
     * Mapeia mensagens no formato:
     * cvc-pattern-valid: Value 'X' ... pattern 'P' ... type 'T'
     */
    private static function mapPatternError(string $msg, bool $incluirDetalheTecnico): ?string
    {
        if (!preg_match(
            "/cvc-pattern-valid: Value '([^']*)' is not facet-valid with respect to pattern '([^']*)' for type '([^']*)'/",
            $msg,
            $m
        )) {
            return null;
        }

        $valor  = $m[1];
        $padrao = $m[2];
        $tipo   = $m[3];
        $campo  = self::tipoToCampo($tipo);

        // Caso específico: descricao do pedido com caractere invalido isolado (ex.: "N?")
        if (
            stripos($valor, 'PEDIDO:') !== false
            && (strpos($valor, "�") !== false || preg_match('/\b[A-Za-z]\?\b/u', $valor))
        ) {
            $texto = "Descricao do pedido com caractere invalido para NF-e. "
                . "Remova caracteres isolados (ex.: 'N?') e use apenas letras, numeros e pontuacao simples.";
            if ($incluirDetalheTecnico) {
                $texto .= ' Detalhe técnico: ' . $msg;
            }
            return $texto;
        }

        // Casos específicos mais comuns (IE destinatário / transportador)
        if (in_array($tipo, ['TIeDest', 'TIeDestNaoIsento'], true) && $padrao === '[0-9]{2,14}') {
            $texto = "Inscrição Estadual inválida (destinatário ou transportador). "
                . "Informe apenas números (2 a 14 dígitos). Valor informado: '{$valor}'.";
        } elseif ($tipo === 'TIeDest' && $padrao === 'ISENTO|[0-9]{2,14}') {
            $texto = "Inscrição Estadual inválida (destinatário ou transportador). "
                . "Informe apenas números (2 a 14 dígitos) ou 'ISENTO'. Valor informado: '{$valor}'.";
        } else {
            $texto = "Valor '{$valor}' inválido para o campo {$campo}. Ele deve obedecer ao padrão: '{$padrao}'.";
        }

        if ($incluirDetalheTecnico) {
            $texto .= ' Detalhe técnico: ' . $msg;
        }

        return $texto;
    }

    /**
     * Mapeia mensagens do tipo:
     * Rejeicao: Falha no Schema XML da NFe (Elemento: enviNFe/NFe[1]/infNFe/dest/CPF/)
     */
    private static function mapSchemaElementError(string $msg): ?string
    {
        if (!preg_match('/Elemento:\s*([^)]+)/', $msg, $m)) {
            return null;
        }

        $elemento = trim($m[1]);

        if (strpos($elemento, '/dest/CPF') !== false) {
            return 'CPF do destinatário inválido ou em formato incorreto. Verifique o cadastro do cliente e tente novamente.';
        }

        if (strpos($elemento, '/dest/CNPJ') !== false) {
            return 'CNPJ do destinatário inválido ou em formato incorreto. Verifique o cadastro do cliente e tente novamente.';
        }

        if (strpos($elemento, '/dest/IE') !== false) {
            return 'Inscrição Estadual do destinatário inválida. Verifique o cadastro do cliente e tente novamente.';
        }

        if (strpos($elemento, '/cobr') !== false || strpos($elemento, '/dup') !== false || strpos($elemento, '/pag') !== false) {
            return 'Dados de cobrança/pagamento inválidos. Verifique parcelas, vencimentos e valores da nota.';
        }

        return "Falha no schema XML da NF-e no elemento '{$elemento}'. Verifique os dados vinculados a esse bloco.";
    }

    /**
     * Mapeia mensagens do tipo:
     * cvc-complex-type.2.4.b: The content of element 'PIS' is not complete.
     * One of '{"http://www.portalfiscal.inf.br/nfe":PISAliq, ...}' is expected.
     */
    private static function mapComplexTypeError(string $msg, bool $incluirDetalheTecnico = false): ?string
    {
        if (!preg_match(
            "/cvc-complex-type[^:]*:\s*The content of element '([^']+)' is not complete/i",
            $msg,
            $m
        )) {
            return null;
        }

        $elemento = strtoupper($m[1]);
        $filhos   = self::extractExpectedChildElements($msg);

        $hints = self::elementoSchemaHints();
        if (isset($hints[$elemento])) {
            $hint     = $hints[$elemento];
            $subtags  = $filhos !== [] ? implode(', ', $filhos) : $hint['subtags'];
            $texto    = "Dados de {$hint['nome']} incompletos no XML da NF-e. "
                . "O grupo {$elemento} foi gerado sem o subelemento obrigatório ({$subtags}). "
                . $hint['dica'];
        } else {
            $subtags = $filhos !== [] ? implode(', ', $filhos) : 'subelemento obrigatório';
            $texto   = "Conteúdo incompleto no elemento '{$elemento}' do XML da NF-e. "
                . "É necessário informar um destes subelementos: {$subtags}. "
                . 'Verifique os dados vinculados a esse bloco na nota.';
        }

        if ($incluirDetalheTecnico) {
            $texto .= ' Detalhe técnico: ' . $msg;
        }

        return $texto;
    }

    /**
     * Extrai nomes de subelementos esperados de "One of '{...}'".
     */
    private static function extractExpectedChildElements(string $msg): array
    {
        if (!preg_match("/One of '\{([^']+)\}'/i", $msg, $m)) {
            return [];
        }

        preg_match_all('/:([A-Za-z][A-Za-z0-9]+)/', $m[1], $matches);

        return array_values(array_unique($matches[1] ?? []));
    }

    /**
     * Dicas legíveis para blocos fiscais comuns em erros de schema.
     */
    private static function elementoSchemaHints(): array
    {
        return [
            'PIS' => [
                'nome'   => 'PIS',
                'subtags' => 'PISAliq, PISQtde, PISNT ou PISOutr',
                'dica'   => 'Verifique o CST PIS e os campos de base, alíquota e valor PIS em cada item da nota.',
            ],
            'COFINS' => [
                'nome'   => 'COFINS',
                'subtags' => 'COFINSAliq, COFINSQtde, COFINSNT ou COFINSOutr',
                'dica'   => 'Verifique o CST COFINS e os campos de base, alíquota e valor COFINS em cada item da nota.',
            ],
            'ICMS' => [
                'nome'   => 'ICMS',
                'subtags' => 'ICMS00, ICMS10, ICMS20, ICMS30, ICMS40, ICMS51, ICMS60, ICMS70, ICMS90, ICMSPart, ICMSST, ICMSSN101, ICMSSN102, ICMSSN201, ICMSSN202, ICMSSN500 ou ICMSSN900',
                'dica'   => 'Verifique o CST/CSOSN ICMS, origem, base, alíquota e valor ICMS do item.',
            ],
            'IPI' => [
                'nome'   => 'IPI',
                'subtags' => 'IPITrib ou IPINT',
                'dica'   => 'Verifique o CST IPI e os valores de base, alíquota e valor IPI do item.',
            ],
            'ISSQN' => [
                'nome'   => 'ISSQN',
                'subtags' => 'dados do ISSQN',
                'dica'   => 'Verifique alíquota, base, valor ISSQN e código do serviço municipal.',
            ],
        ];
    }

    /**
     * Converte o tipo XSD em nome de campo legível.
     */
    private static function tipoToCampo(string $tipo): string
    {
        if ($tipo === '') {
            return 'informado';
        }

        switch ($tipo) {
            case 'TIeDest':
            case 'TIeDestNaoIsento':
                return 'Inscrição Estadual do destinatário';
            case 'TChNFe':
                return 'Chave da NF-e';
            default:
                // Remove prefixo T e separa camelCase
                $semPrefixo = preg_replace('/^T/', '', $tipo);
                $legivel    = preg_replace('/([a-z])([A-Z])/', '$1 $2', $semPrefixo);
                return trim($legivel);
        }
    }
}
