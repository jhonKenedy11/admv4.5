<?php

/**
 * Decodificação de código de barras bancário em I2of5 (Interleaved 2 of 5).
 *
 * ─── Notação usada nos caracteres I2of5 ─────────────────────────────────────
 *   N = barra LARGA     (N maiúsculo)
 *   n = barra estreita  (n minúsculo)
 *   W = espaço LARGO    (W maiúsculo)
 *   w = espaço estreito (w minúsculo)
 *
 * No modo ENTRELAÇADO, dois dígitos são combinados em 10 elementos:
 *   pos 0,2,4,6,8 → barras  → codificam o 1º dígito do par
 *   pos 1,3,5,7,9 → espaços → codificam o 2º dígito do par
 *
 * ─── Tamanho esperado para um boleto de 44 dígitos ─────────────────────────
 *   44 dígitos / 2 por par = 22 pares × 10 chars = 220 chars de dados
 *   + start guard (4 chars: nnnn) + stop guard (3 chars: Nnn) = 227 chars total
 *
 * ─── Estrutura FEBRABAN (44 dígitos) ───────────────────────────────────────
 *   Pos  1– 3  → Banco (3 dígitos)
 *   Pos  4– 4  → Moeda (9=Real, 0=Outras)
 *   Pos  5– 5  → Dígito verificador do código de barras
 *   Pos  6– 9  → Fator de vencimento (dias desde 07/10/1997; 0000=sem vencimento)
 *   Pos 10–19  → Valor (8 inteiros + 2 decimais; ex: 0000010050 = R$ 100,50)
 *   Pos 20–44  → Campo livre (25 dígitos, definido por cada banco)
 *
 * ─── Campo livre Bradesco (banco 237) ──────────────────────────────────────
 *   Pos 20–23  → Agência beneficiária (4 dígitos, sem DV)
 *   Pos 24–25  → Carteira (2 dígitos)
 *   Pos 26–36  → Nosso Número (11 dígitos, sem DV)
 *   Pos 37–43  → Conta do beneficiário (7 dígitos, sem DV)
 *   Pos 44–44  → Zero fixo
 */

class c_api_bradesco_barcode
{

    private $cd_barras = null;

    /**
     * Tabela EBCDIC oficial Bradesco — par de dígitos (00–99) → padrão I2of5 (5 chars).
     * Usada na intercalação barras/espaços para montagem do código de barras.
     */
    const TABELA_EBCDIC_BRADESCO = [
        "00" => "nnWWn",
        "01" => "NnwwN",
        "02" => "nNwwN",
        "03" => "NNwwn",
        "04" => "nnWwN",
        "05" => "NnWwn",
        "06" => "nNWwn",
        "07" => "nnwWN",
        "08" => "NnwWn",
        "09" => "nNwWn",
        "10" => "wnNNw",
        "11" => "WnnnW",
        "12" => "wNnnW",
        "13" => "WNnnw",
        "14" => "wnNnW",
        "15" => "WnNnw",
        "16" => "wNNnw",
        "17" => "wnnNW",
        "18" => "WnnNw",
        "19" => "wNnNw",
        "20" => "nwNNw",
        "21" => "NwnnW",
        "22" => "nWnnW",
        "23" => "NWnnw",
        "24" => "nwNnW",
        "25" => "NwNnw",
        "26" => "nWNnw",
        "27" => "nwnNW",
        "28" => "NwnNw",
        "29" => "nWnNw",
        "30" => "wwNNn",
        "31" => "WwnnN",
        "32" => "wWnnN",
        "33" => "WWnnn",
        "34" => "wwNnN",
        "35" => "WwNnn",
        "36" => "wWNnn",
        "37" => "wwnNN",
        "38" => "WwnNn",
        "39" => "wWnNn",
        "40" => "nnWNw",
        "41" => "NnwnW",
        "42" => "nNwnW",
        "43" => "NNwnw",
        "44" => "nnWnW",
        "45" => "NnWnw",
        "46" => "nNWnw",
        "47" => "nnwNW",
        "48" => "NnwNw",
        "49" => "nNwNw",
        "50" => "wnWNn",
        "51" => "WnwnN",
        "52" => "wNwnN",
        "53" => "WNwnn",
        "54" => "wnWnN",
        "55" => "WnWnn",
        "56" => "wNWnn",
        "57" => "wnwNN",
        "58" => "WnwNn",
        "59" => "wNwNn",
        "60" => "nwWNn",
        "61" => "NwwnN",
        "62" => "nWwnN",
        "63" => "NWwnn",
        "64" => "nwWnN",
        "65" => "NwWnn",
        "66" => "nWWnn",
        "67" => "nwwNN",
        "68" => "NwwNn",
        "69" => "nWwNn",
        "70" => "nnNWw",
        "71" => "NnnwW",
        "72" => "nNnwW",
        "73" => "NNnww",
        "74" => "nnNwW",
        "75" => "NnNww",
        "76" => "nNNww",
        "77" => "nnnWW",
        "78" => "NnnWw",
        "79" => "nNnWw",
        "80" => "wnNWn",
        "81" => "WnnwN",
        "82" => "wNnwN",
        "83" => "WNnwn",
        "84" => "wnNwN",
        "85" => "WnNwn",
        "86" => "wNNwn",
        "87" => "wnnWN",
        "88" => "WnnWn",
        "89" => "wNnWn",
        "90" => "nwNWn",
        "91" => "NwnwN",
        "92" => "nWnwN",
        "93" => "NWnwn",
        "94" => "nwNwN",
        "95" => "NwNwn",
        "96" => "nWNwn",
        "97" => "nwnWN",
        "98" => "NwnWn",
        "99" => "nWnWn",
    ];

    /**
     * Tabela I2of5: padrão de 5 larguras → dígito (0-9).
     *
     * Chaves para barras (N=largo, n=estreito).
     * Para espaços, a conversão W→N e w→n é feita antes do lookup.
     */
    const I2OF5_DIGIT_TABLE = [
        'nnWWn' => '0',  // estreito estreito LARGO  LARGO  estreito
        'NnwwN' => '1',  // LARGO  estreito estreito estreito LARGO
        'nNwwN' => '2',
        'NNwwn' => '3',
        'nnWwN' => '4',
        'NnWwn' => '5',
        'nNWwn' => '6',
        'nnwWN' => '7',
        'NnwWn' => '8',
        'nNwWn' => '9',
    ];

    /**
     * Construtor da classe c_api_bradesco_barcode
     * @param string $cd_barras Código de barras bancário
     */
    public function __construct($cd_barras)
    {
        $this->cd_barras = $cd_barras;
    }

    /**
     * Normaliza código de barras da API (I2of5 EBCDIC) ou numérico (44 dígitos) para linha numérica FEBRABAN.
     *
     * @param  string $cdBarras Código retornado pela API (padrão N/n/W/w) ou 44 dígitos
     * @return string           Código de barras com 44 posições numéricas
     * @throws \InvalidArgumentException
     */
    public static function resolveCodigoBarrasNumerico(string $cdBarras): string
    {
        $cdBarras = trim($cdBarras);

        // Verifica se o código de barras foi informado
        if ($cdBarras === '') {
            throw new \InvalidArgumentException('Código de barras não informado.');
        }

        // Verifica se o código de barras é numérico e tem 44 dígitos
        $apenasDigitos = preg_replace('/\D/', '', $cdBarras);
        if (strlen($apenasDigitos) === 44 && preg_match('/^\d{44}$/', $apenasDigitos)) {
            return $apenasDigitos;
        }

        // Verifica se o código de barras é I2of5
        if (preg_match('/[NnWw]/', $cdBarras)) {
            $sequence = preg_replace('/[^NnWw]/', '', $cdBarras);

            // API Bradesco: 22 pares × 5 chars EBCDIC = 110 (frequentemente entre < >)
            if (strlen($sequence) === 110) {
                return self::decodeEbcdicBradescoSequence($sequence);
            }
            // Decodifica o código de barras I2of5
            $decoder = new self($cdBarras);
            $decoded = $decoder->decodeBoleto();
            return $decoded['cd_barras_decodificado'];
        }

        throw new \InvalidArgumentException(
            'Código de barras inválido. Informe 44 dígitos numéricos ou padrão EBCDIC/I2of5 Bradesco.'
        );
    }

    /**
     * Decodifica sequência EBCDIC Bradesco (22 blocos de 5 chars → 44 dígitos).
     *
     * Formato retornado pela API em cdBarras: "<NWnnw...NWn>" (110 chars N/n/W/w).
     *
     * @param  string $sequence Caracteres N, n, W, w (delimitadores são ignorados)
     * @return string           44 dígitos numéricos FEBRABAN
     * @throws \InvalidArgumentException
     */
    public static function decodeEbcdicBradescoSequence(string $sequence): string
    {
        // Remove qualquer char que não seja N, n, W, w
        $sequence = preg_replace('/[^NnWw]/', '', $sequence);

        // Verifica se o comprimento da sequência é válido
        if ($sequence === '' || strlen($sequence) % 5 !== 0) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Comprimento inválido para EBCDIC Bradesco: %d char(s). Deve ser múltiplo de 5 (esperado 110).',
                    strlen($sequence)
                )
            );
        }

        // Cria a tabela de reversão
        static $reverseTable = null;
        if ($reverseTable === null) {
            $reverseTable = array_flip(self::TABELA_EBCDIC_BRADESCO);
        }

        // Decodifica a sequência
        $digits = '';
        // Divide a sequência em blocos de 5 chars
        foreach (str_split($sequence, 5) as $index => $block) {
            // Verifica se o bloco existe na tabela de reversão
            if (!isset($reverseTable[$block])) {
                // Lança uma exceção se o bloco não existe na tabela de reversão
                throw new \InvalidArgumentException(
                    "Padrão EBCDIC Bradesco inválido no bloco $index: '$block'"
                );
            }
            // Adiciona o dígito decodificado ao resultado
            $digits .= $reverseTable[$block];
        }

        // Verifica se o resultado tem 44 dígitos
        if (strlen($digits) !== 44) {
            // Lança uma exceção se o resultado não tem 44 dígitos
            throw new \InvalidArgumentException(
                sprintf('Esperados 44 dígitos após decodificação EBCDIC, obtidos %d.', strlen($digits))
            );
        }

        // Retorna o resultado
        return $digits;
    }

    /**
     * Normaliza 5 elementos extraídos (mistura de N/n/W/w) para o formato de lookup da tabela.
     *
     * A tabela usa 'N'/'n' para barras e 'W'/'w' para espaços,
     * mas o que define o dígito é apenas LARGO vs ESTREITO.
     * Para barras  → largo=N, estreito=n
     * Para espaços → largo=W, estreito=w
     *
     * @param  string $raw   5 chars extraídos (qualquer combinação de N/n/W/w)
     * @param  bool   $bars  true = são barras, false = são espaços
     * @return string        5 chars normalizados para lookup
     */
    function normalizeI2of5Pattern(string $raw, bool $bars): string
    {
        // Inicializa a saída
        $out = '';
        // Percorre os 5 caracteres
        for ($i = 0; $i < 5; $i++) {
            $isLarge = ($raw[$i] === 'N' || $raw[$i] === 'W');  // maiúscula = largo
            // Verifica se são barras
            if ($bars) {
                // Adiciona o caractere normalizado ao resultado
                $out .= $isLarge ? 'N' : 'n';   // barras:  largo=N, estreito=n
            } else {
                $out .= $isLarge ? 'W' : 'w';   // espaços: largo=W, estreito=w
            }
        }
        // Retorna o resultado
        return $out;
    }

    /**
     * Decodifica uma string I2of5 (N/n/W/w) para dígitos numéricos.
     *
     * Remove automaticamente start guard (nnnn) e stop guard (Nnn) se presentes.
     *
     * @param  string $sequence  Caracteres N, n, W, w (outros são ignorados)
     * @return string            Dígitos numéricos decodificados
     * @throws \InvalidArgumentException Se o comprimento for inválido ou padrão desconhecido
     */
    function decodeI2of5Sequence(string $sequence): string
    {
        // Remove qualquer char que não seja N, n, W, w
        $sequence = preg_replace('/[^NnWw]/', '', $sequence);

        // Remove start guard: 4 barras estreitas (nnnn)
        if (str_starts_with($sequence, 'nnnn')) {
            $sequence = substr($sequence, 4);
        }

        // Remove stop guard: barra larga + 2 estreitas (Nnn)
        if (str_ends_with($sequence, 'Nnn')) {
            $sequence = substr($sequence, 0, -3);
        }

        $len = strlen($sequence);
        if ($len === 0 || $len % 10 !== 0) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Comprimento inválido após remoção de guards: %d char(s). ' .
                        'Deve ser múltiplo de 10. ' .
                        'Para boleto de 44 dígitos: 220 chars de dados + 7 chars de guards = 227 total.',
                    $len
                )
            );
        }

        $digits = '';
        $blocks = str_split($sequence, 10);

        foreach ($blocks as $index => $block) {
            // Extrai barras (pos pares) e espaços (pos ímpares)
            $barsRaw   = $block[0] . $block[2] . $block[4] . $block[6] . $block[8];
            $spacesRaw = $block[1] . $block[3] . $block[5] . $block[7] . $block[9];

            // Normaliza para o formato da tabela
            $barsKey   = $this->normalizeI2of5Pattern($barsRaw, true);
            $spacesKey = $this->normalizeI2of5Pattern($spacesRaw, false);

            if (!isset(self::I2OF5_DIGIT_TABLE[$barsKey])) {
                throw new \InvalidArgumentException(
                    "Padrão de barras inválido no bloco $index: '$barsRaw' → normalizado '$barsKey'"
                );
            }
            if (!isset(self::I2OF5_DIGIT_TABLE[$spacesKey])) {
                throw new \InvalidArgumentException(
                    "Padrão de espaços inválido no bloco $index: '$spacesRaw' → normalizado '$spacesKey'"
                );
            }

            $digits .= self::I2OF5_DIGIT_TABLE[$barsKey] . self::I2OF5_DIGIT_TABLE[$spacesKey];
        }

        return $digits;
    }

    /**
     * Decodifica um código de barras bancário I2of5 e retorna todos os campos do boleto.
     *
     * @param  string $barcode  String I2of5 crua (N/n/W/w).
     *                          Para boleto Bradesco de 44 dígitos: ~227 chars (220 dados + guards).
     *
     * @return array{
     *   cd_barras_decodificado: string,
     *   banco:                 string,
     *   moeda:                 string,
     *   digito_verificador:    string,
     *   fator_vencimento:      string,
     *   data_vencimento:       string,
     *   valor_raw:             string,
     *   valor_formatado:       string,
     *   campo_livre:           string,
     *   agencia:               string,
     *   carteira:              string,
     *   nosso_numero:          string,
     *   conta_beneficiario:    string,
     *   zero_fixo:             string,
     * }
     *
     * @throws \InvalidArgumentException
     */
    function decodeBoleto(): array
    {
        $sequence = preg_replace('/[^NnWw]/', '', $this->cd_barras);

        if (strlen($sequence) === 110) {
            $cd_barras_decodificado = self::decodeEbcdicBradescoSequence($sequence);
        } else {
            $cd_barras_decodificado = $this->decodeI2of5Sequence($this->cd_barras);
        }

        return self::parseCodigoBarrasNumerico($cd_barras_decodificado);
    }

    /**
     * Extrai campos FEBRABAN/Bradesco a partir do código de barras numérico (44 dígitos).
     *
     * @param  string $codigoNumerico
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function parseCodigoBarrasNumerico(string $codigoNumerico): array
    {
        $cd_barras_decodificado = preg_replace('/\D/', '', $codigoNumerico);

        if (strlen($cd_barras_decodificado) !== 44) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Esperados 44 dígitos no código de barras, obtidos %d.',
                    strlen($cd_barras_decodificado)
                )
            );
        }

        // ── Particionamento FEBRABAN ────────────────────────────────────────────
        $banco             = substr($cd_barras_decodificado,  0,  3);  // pos  1–3
        $moeda             = substr($cd_barras_decodificado,  3,  1);  // pos  4
        $digitoVerificador = substr($cd_barras_decodificado,  4,  1);  // pos  5
        $fatorVencimento   = substr($cd_barras_decodificado,  5,  4);  // pos  6–9
        $valorRaw          = substr($cd_barras_decodificado,  9, 10);  // pos 10–19
        $campoLivre        = substr($cd_barras_decodificado, 19, 25);  // pos 20–44

        // Data de vencimento: fator = dias corridos desde 07/10/1997
        if ($fatorVencimento === '0000') {
            $dataVencimento = 'sem vencimento';
        } else {
            $dataBase = new \DateTime('1997-10-07');
            $dataBase->modify('+' . (int) $fatorVencimento . ' days');
            $dataVencimento = $dataBase->format('d/m/Y');
        }

        // Valor: os 2 últimos dígitos são centavos
        $valorFormatado = 'R$ ' . number_format((int) $valorRaw / 100, 2, ',', '.');

        // ── Campo livre Bradesco ────────────────────────────────────────────────
        $agencia           = substr($campoLivre,  0,  4);  // pos 20–23
        $carteira          = substr($campoLivre,  4,  2);  // pos 24–25
        $nossoNumero       = substr($campoLivre,  6, 11);  // pos 26–36
        $contaBeneficiario = substr($campoLivre, 17,  7);  // pos 37–43
        $zeroFixo          = substr($campoLivre, 24,  1);  // pos 44

        return [
            'cd_barras_decodificado' => $cd_barras_decodificado,
            'banco'              => $banco,
            'moeda'              => $moeda,
            'digito_verificador' => $digitoVerificador,
            'fator_vencimento'   => $fatorVencimento,
            'data_vencimento'    => $dataVencimento,
            'valor_raw'          => $valorRaw,
            'valor_formatado'    => $valorFormatado,
            'campo_livre'        => $campoLivre,
            'agencia'            => $agencia,
            'carteira'           => $carteira,
            'nosso_numero'       => $nossoNumero,
            'conta_beneficiario' => $contaBeneficiario,
            'zero_fixo'          => $zeroFixo,
        ];
    }
}
