<?php

/**
 * @package   astec
 * @name      c_ipm_estrategy_xml
 * @version   4.5.0
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy Dos Santos Mello <jhon.kened11@hotmail.com>
 * @date      27/06/2025
 */

$dir = (__DIR__);
include_once($dir . "/../../bib/c_database_pdo.php");


class IpmStrategyXml
{

    public $xmlString = NULL;
    public $banco     = NULL;

    /**
     * Gera o XML para a NFS-e da IPM a partir de um array de dados.
     *
     * @param array $dadosNFS Array contendo todos os dados necessários para a NFS-e.
     * A estrutura do array deve seguir as tags do layout XML, incluindo:
     * - identificador (opcional)
     * - valor_desconto (opcional)
     * - observacao (opcional)
     * - prestador (obrigatório) 
     * - tomador (obrigatório) 
     * - itens (obrigatório, com um ou mais serviços) 
     * - forma_pagamento (opcional) 
     * @return string String contendo o XML da NFS-e formatado.
     */
    function mountXmlIpm(string $json): string
    {
        // Decodificar JSON recebido do JavaScript
        $dadosRecebidos = json_decode($json, true);

        // Validar se o JSON foi decodificado corretamente
        if (!$dadosRecebidos) {
            throw new \InvalidArgumentException('JSON inválido recebido');
        }

        // Montar a estrutura $dadosNFS no padrão esperado pelo XML IPM
        $dadosNFS = [
            // Utilizado para identificação do arquivo a ser processado. Arquivos com mesmo identificador não serão
            // processados mais de uma vez, indiferente se o restante dos dados for correspondente a uma nova NFS-e.
            // Observação: se a tag for informada no arquivo, deve ser informado algum valor. 
            // lenght: 80 - Alfanumerico
            // Opcional
            'identificador' => time() . '-nfs-' . uniqid(),

            // Série da NFS-e.
            // Opcional - Numerico
            'serie' => isset($dadosRecebidos['nota_fiscal']['serie']) ? $dadosRecebidos['nota_fiscal']['serie'] : null,
            
            // Deverá ser preenchido com a data do fator gerador da NFS-e. Exemplo: 15/01/2018. 
            // Lenght: 10 - Texto
            // Opcional
            'data_fato_gerador' => isset($dadosRecebidos['nota_fiscal']['data_fato_gerador']) ? $dadosRecebidos['nota_fiscal']['data_fato_gerador'] : null,
            
            // Valor total da NFS-e. 
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Obrigatorio 
            'valor_total' => isset($dadosRecebidos['nota_fiscal']['valor_total']) ? floatval($dadosRecebidos['nota_fiscal']['valor_total']) : 0.00,

            // Valor do desconto. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_desconto' => isset($dadosRecebidos['nota_fiscal']['valor_desconto']) ? floatval($dadosRecebidos['nota_fiscal']['valor_desconto']) : 0.00,
            
            // Valor do IRRF (Imposto de Renda
            // Retido na Fonte). Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_ir' => isset($dadosRecebidos['nota_fiscal']['valor_ir']) ? floatval($dadosRecebidos['nota_fiscal']['valor_ir']) : 0.00,

            // Valor do INSS. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_inss' => isset($dadosRecebidos['nota_fiscal']['valor_inss']) ? floatval($dadosRecebidos['nota_fiscal']['valor_inss']) : 0.00,

            // Valor da contribuição social. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_contribuicao_social' => isset($dadosRecebidos['nota_fiscal']['valor_contribuicao_social']) ? floatval($dadosRecebidos['nota_fiscal']['valor_contribuicao_social']) : 0.00,

            // Valor do RPS (Retenções da Previdência Social). Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_rps' => isset($dadosRecebidos['nota_fiscal']['valor_rps']) ? floatval($dadosRecebidos['nota_fiscal']['valor_rps']) : 0.00,

            // Valor do PIS (Programa de Integração Social). Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_pis' => isset($dadosRecebidos['nota_fiscal']['valor_pis']) ? floatval($dadosRecebidos['nota_fiscal']['valor_pis']) : 0.00,

            // Valor do COFINS (Contribuição para o Financiamento da Seguridade Social). Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
            // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
            // Opcional
            'valor_cofins' => isset($dadosRecebidos['nota_fiscal']['valor_cofins']) ? floatval($dadosRecebidos['nota_fiscal']['valor_cofins']) : 0.00,

            // Observações da NFS-e.
            // Lenght: 1000 - Alfanumerico
            // Opcional
            'observacao' => isset($dadosRecebidos['nota_fiscal']['observacao']) ? $dadosRecebidos['nota_fiscal']['observacao'] : null,

            
            
            // Dados do prestador
            'prestador' => [
                // CPF/CNPJ do emissor da nota. Informar apenas números.
                // Lenght: 14 - Numerico
                // Obrigatorio
                'cpfcnpj' => $dadosRecebidos['prestador']['cpfcnpj'] ?? '',

                // Código da cidade onde o emissor está
                // estabelecido, junto à Receita Federal (chamado de código TOM).
                // Exemplo: Brusque 8055
                // Lenght: 9 - Numerico
                // Obrigatorio
                'cidade' => $dadosRecebidos['prestador']['cidade'] ?? '',
            ],
            
            // Dados do tomador
            'tomador' => [
                // Tipo da pessoa, informar:
                // • J para Pessoa Jurídica;
                // • F para Pessoa Física;
                // • E para Estrangeiro.
                // Lenght: 1 - Alfanumerico
                // Obrigatorio
                'tipo' => $dadosRecebidos['tomador']['tipo'] ?? 'J',

                // CPF/CNPJ do tomador do(s) serviço(s).
                // Lenght: 14 - Numerico
                // Opcional
                'cpfcnpj' => $dadosRecebidos['tomador']['cpfcnpj'] ?? '',

                // Nome do tomador do(s) serviço(s).
                // Lenght: 100 - Alfanumerico
                // Opcional
                'nome_razao_social' => $dadosRecebidos['tomador']['nome_razao_social'] ?? '',

                // Sobrenome ou Nome Fantasia do Tomador.
                // Lenght: 100 - Alfanumerico
                // Opcional
                'sobrenome_nome_fantasia' => $dadosRecebidos['tomador']['sobrenome_nome_fantasia'] ?? '',

                // Logradouro do endereço do estabelecimento ou residência do tomador do(s) serviço(s).
                // Lenght: 70 - Alfanumerico
                // Opcional
                'logradouro' => $dadosRecebidos['tomador']['logradouro'] ?? '',
                
                // Quando necessário informar mais de um e-mail para o tomador do(s) serviço(s) os mesmos deverão ser separados por (;) ou (,).
                // Lenght: 100 - Alfanumerico
                // Opcional
                'email' => $dadosRecebidos['tomador']['email'] ?? '',

                // Número do endereço do estabelecimento ou residência do tomador do(s) serviço(s).
                // Lenght: 8 - Numerico
                // Opcional
                'numero_residencia' => $dadosRecebidos['tomador']['numero_residencia'] ?? '',

                // Complemento do endereço do estabelecimento ou residência do tomador do(s) serviço(s).
                // Lenght: 50 - Alfanumerico
                // Opcional
                'complemento' => $dadosRecebidos['tomador']['complemento'] ?? '',
                
                // Ponto de referência do endereço do estabelecimento ou residência do tomador do(s) serviço(s).
                // Lenght: 100 - Alfanumerico
                // Opcional
                'ponto_referencia' => $dadosRecebidos['tomador']['ponto_referencia'] ?? '',

                // Bairro do endereço do estabelecimento ou residência do tomador do(s) serviço(s).
                // Lenght: 30 - Alfanumerico
                // Opcional
                'bairro' => $dadosRecebidos['tomador']['bairro'] ?? '',

                // Código da cidade do endereço do estabelecimento ou residência do tomador do(s) serviço(s), 
                // junto à Receita Federal (chamado de código TOM). Exemplo: Brusque 8055. 
                // Observação: quando o tipo do tomador for Estrangeiro, o campo cidade deve ser preenchido com o nome da cidade (máximo 100 caracteres).
                // Lenght: 9 - Numerico
                // Opcional
                'cidade' => $dadosRecebidos['tomador']['cidade'] ?? '',

                // CEP do endereço do estabelecimento ou residência do tomador do(s) serviço(s).
                // Lenght: 8 - Numerico
                // Opcional
                'cep' => $dadosRecebidos['tomador']['cep'] ?? '',
                
                // Código de área do telefone do
                // estabelecimento do tomador do(s) serviço(s).
                // Lenght: 3 - Numerico
                // Opcional
                'ddd_fone_comercial' => $dadosRecebidos['tomador']['ddd_fone_comercial'] ?? '',

                // Telefone do estabelecimento do tomador do(s) serviço(s).
                // Lenght: 9 - Numerico
                // Opcional
                'fone_comercial' => $dadosRecebidos['tomador']['fone_comercial'] ?? '',

                // Código de área do telefone do
                // estabelecimento do tomador do(s) serviço(s).
                // Lenght: 3 - Numerico
                // Opcional
                'ddd_fone_residencial' => $dadosRecebidos['tomador']['ddd_fone_residencial'] ?? '',

                // Telefone do estabelecimento do tomador do(s) serviço(s).
                // Lenght: 9 - Numerico
                // Opcional
                'fone_residencial' => $dadosRecebidos['tomador']['fone_residencial'] ?? '',
                
                // Código de área do telefone do
                // estabelecimento do tomador do(s) serviço(s).
                // Lenght: 3 - Numerico
                // Opcional
                'ddd_fax' => $dadosRecebidos['tomador']['ddd_fax'] ?? '',

                // Telefone do estabelecimento do tomador do(s) serviço(s).
                // Lenght: 9 - Numerico
                // Opcional
                'fone_fax' => $dadosRecebidos['tomador']['fone_fax'] ?? '',
            ],
            
            // Itens dos serviços (array de itens)
            'itens' => []
        ];

        // Processar os itens recebidos
        if (isset($dadosRecebidos['itens']) && is_array($dadosRecebidos['itens'])) {
            foreach ($dadosRecebidos['itens'] as $item) {
                $dadosNFS['itens'][] = [
                    // Esta tag serve para informar onde será recolhido o imposto e deve ser preenchida com: 
                    // • “0” ou “N” quando a tributação ocorre no local da prestação do serviço; 
                    // • “1” ou “S” quando a tributação ocorre no município do prestador.
                    // Lenght: 1 - Alfanumerico
                    // Obrigatorio
                    'tributa_municipio_prestador' => $item['tributa_municipio_prestador'] ?? 'S',

                    // Código da cidade onde o serviço foi prestado, junto à Receita Federal (código TOM ou IBGE). Exemplo: Brusque TOM: 8055/IBGE: 4202909.
                    // Lenght: 9 - Numerico
                    // Obrigatorio
                    'codigo_local_prestacao_servico' => $item['codigo_local_prestacao_servico'] ?? '',

                    // Código do subitem da lista de serviços, em conformidade com a Lei Complementar 116/2003
                    // Lenght: 9 - Numerico
                    // Obrigatorio
                    'codigo_item_lista_servico' => $item['codigo_item_lista_servico'] ?? '',

                    // Descritivo coloquial do serviço prestado.
                    // Lenght: 1000 - Alfanumerico
                    // Obrigatorio
                    'descritivo' => $item['descritivo'] ?? 'Prestação de serviços',

                    // Alíquota que irá incidir sobre a base de cálculo. Esta alíquota será consistida em acordo com a legislação do município. 
                    // ATENÇÃO: caso seja informada incorretamente, o software rejeitará a nota.
                    // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
                    // Obrigatorio
                    'aliquota_item_lista_servico' => floatval($item['aliquota_item_lista_servico'] ?? 0),

                    // Código da situação tributária. Este código caracterizará a forma de cobrança do ISS. Aqui, podem ocorrer as codificações conforme descrito neste manual, no item Situações Tributárias. 
                    // ATENÇÃO: caso seja informada incorretamente, o software rejeitará a nota.
                    // Lenght: 4 - Numerico
                    // Obrigatorio
                    'situacao_tributaria' => intval($item['situacao_tributaria'] ?? 0),

                    // Valor tributável do serviço prestado.
                    // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
                    // Obrigatorio
                    'valor_tributavel' => floatval($item['valor_tributavel'] ?? 0),

                    // Valor da dedução. Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
                    // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
                    // Opcional
                    'valor_deducao' => floatval($item['valor_deducao'] ?? 0),

                    // Valor do ISSRF (Imposto sobre Serviços de Qualquer Natureza). Este valor não afetará a base de cálculo do imposto, apenas assinala na nota.
                    // Lenght: 15 - Real (formato brasileiro: vírgula como decimal no XML)
                    // Opcional
                    'valor_issrf' => $item['valor_issrf'] ?? 0,

                    // Deverá ser o número do Cadastro Nacional de Obras.
                    // Numerico
                    // Opcional
                    'cno' => $item['cno'] ?? '',
                ];
            }
        }

        // Forma de pagamento (opcional)
        if (isset($dadosRecebidos['forma_pagamento']) && !empty($dadosRecebidos['forma_pagamento']['parcelas'])) {
            $dadosNFS['forma_pagamento'] = [
                'tipo_pagamento' => intval($dadosRecebidos['forma_pagamento']['tipo_pagamento'] ?? 1),
                'parcelas' => []
            ];

            // Processar parcelas
            foreach ($dadosRecebidos['forma_pagamento']['parcelas'] as $parcela) {
                $dadosNFS['forma_pagamento']['parcelas'][] = [
                    'numero' => intval($parcela['numero'] ?? 1),
                    'valor' => floatval($parcela['valor'] ?? 0),
                    'data_vencimento' => $parcela['data_vencimento'] ?? ''
                ];
            }
        }

        // Calcular valor total automaticamente a partir dos itens
        $valorTotalCalculado = 0;

        foreach ($dadosNFS['itens'] as $item) {
            $valorTotalCalculado += $item['valor_tributavel'];
        }
        
        $dadosNFS['valor_total'] = $valorTotalCalculado;


        // Inicia a construcao do XML
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><nfse/>');

        // variavel usada para testar o xml 
        $var_teste = false;

        if($var_teste == true) {
            $xml->addChild('nfse_teste', 1);
        }

        // --- <identificador> Tag (Optional) ---
        if (!empty($dadosNFS['identificador'])) {
            $xml->addChild('identificador', $dadosNFS['identificador']); // An identifier to prevent duplicate processing 
        }

        // --- <nf> Tag (Mandatory) ---
        $nf = $xml->addChild('nf'); // Groups the main values of the NFS-e 
        $nf->addChild('valor_total', number_format($dadosNFS['valor_total'], 2, ',', '')); // Formato brasileiro: vírgula como decimal

        if (isset($dadosNFS['valor_desconto']) && $dadosNFS['valor_desconto'] > 0) {
            $nf->addChild('valor_desconto', number_format($dadosNFS['valor_desconto'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (isset($dadosNFS['valor_ir']) && $dadosNFS['valor_ir'] > 0) {
            $nf->addChild('valor_ir', number_format($dadosNFS['valor_ir'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (isset($dadosNFS['valor_inss']) && $dadosNFS['valor_inss'] > 0) {
            $nf->addChild('valor_inss', number_format($dadosNFS['valor_inss'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (isset($dadosNFS['valor_contribuicao_social']) && $dadosNFS['valor_contribuicao_social'] > 0) {
            $nf->addChild('valor_contribuicao_social', number_format($dadosNFS['valor_contribuicao_social'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (isset($dadosNFS['valor_rps']) && $dadosNFS['valor_rps'] > 0) {
            $nf->addChild('valor_rps', number_format($dadosNFS['valor_rps'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (isset($dadosNFS['valor_pis']) && $dadosNFS['valor_pis'] > 0) {
            $nf->addChild('valor_pis', number_format($dadosNFS['valor_pis'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (isset($dadosNFS['valor_cofins']) && $dadosNFS['valor_cofins'] > 0) {
            $nf->addChild('valor_cofins', number_format($dadosNFS['valor_cofins'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        }

        if (!empty($dadosNFS['observacao'])) {
            $nf->addChild('observacao', htmlspecialchars($dadosNFS['observacao'])); // Observations 
        }

        // --- <prestador> Tag (Mandatory) ---
        $prestador = $xml->addChild('prestador'); // Service provider's data 
        $prestador->addChild('cpfcnpj', $dadosNFS['prestador']['cpfcnpj']); // Provider's CPF/CNPJ (numbers only) 
        $prestador->addChild('cidade', $dadosNFS['prestador']['cidade']); // Provider's city code (TOM) 

        // --- <tomador> Tag (Mandatory) ---
        $tomador = $xml->addChild('tomador'); // Service taker's data 
        $tomador->addChild('tipo', $dadosNFS['tomador']['tipo']); // Taker's type (J, F, or E) 
        $tomador->addChild('cpfcnpj', $dadosNFS['tomador']['cpfcnpj']); // Taker's CPF/CNPJ 
        $tomador->addChild('nome_razao_social', htmlspecialchars($dadosNFS['tomador']['nome_razao_social'])); // Taker's name/company name 

        if (!empty($dadosNFS['tomador']['sobrenome_nome_fantasia'])) {
            $tomador->addChild('sobrenome_nome_fantasia', htmlspecialchars($dadosNFS['tomador']['sobrenome_nome_fantasia'])); // Taker's fantasy name 
        }
        $tomador->addChild('logradouro', htmlspecialchars($dadosNFS['tomador']['logradouro'])); // Taker's address 
        if (!empty($dadosNFS['tomador']['email'])) {
            $tomador->addChild('email', htmlspecialchars($dadosNFS['tomador']['email'])); // Taker's email(s), separated by ; or , 
        }
        if (!empty($dadosNFS['tomador']['numero_residencia'])) {
            $tomador->addChild('numero_residencia', $dadosNFS['tomador']['numero_residencia']); // Taker's address number 
        }
        $tomador->addChild('bairro', htmlspecialchars($dadosNFS['tomador']['bairro'])); // Taker's neighborhood 
        $tomador->addChild('cidade', $dadosNFS['tomador']['cidade']); // Taker's city code (TOM) 
        $tomador->addChild('cep', $dadosNFS['tomador']['cep']); // Taker's ZIP code 


        // --- <itens> Tag (Mandatory) ---
        $itens = $xml->addChild('itens'); // Groups all service items 
        foreach ($dadosNFS['itens'] as $itemNFS) {
            $lista = $itens->addChild('lista'); // Each item is a <lista> tag 
            $lista->addChild('tributa_municipio_prestador', $itemNFS['tributa_municipio_prestador']); // Where the tax will be collected 
            $lista->addChild('codigo_local_prestacao_servico', $itemNFS['codigo_local_prestacao_servico']); // City code where service was rendered 
            $lista->addChild('codigo_item_lista_servico', $itemNFS['codigo_item_lista_servico']); // Service item code 
            $lista->addChild('descritivo', htmlspecialchars($itemNFS['descritivo'])); // Service description 
            $lista->addChild('aliquota_item_lista_servico', number_format($itemNFS['aliquota_item_lista_servico'], 4, ',', '')); // Formato brasileiro: vírgula como decimal
            $lista->addChild('situacao_tributaria', $itemNFS['situacao_tributaria']); // Tax situation code 
            $lista->addChild('valor_tributavel', number_format($itemNFS['valor_tributavel'], 2, ',', '')); // Formato brasileiro: vírgula como decimal

            if (isset($itemNFS['valor_deducao']) && $itemNFS['valor_deducao'] > 0) {
                $lista->addChild('valor_deducao', number_format($itemNFS['valor_deducao'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
            }
            if (isset($itemNFS['valor_issrf']) && $itemNFS['valor_issrf'] > 0) {
                $lista->addChild('valor_issrf', number_format($itemNFS['valor_issrf'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
            }
        }

        // --- <forma_pagamento> Tag (Optional) ---
        // if (!empty($dadosNFS['forma_pagamento'])) {
        //     $formaPagamento = $xml->addChild('forma_pagamento'); // Payment method details 
        //     $formaPagamento->addChild('tipo_pagamento', $dadosNFS['forma_pagamento']['tipo_pagamento']); // Payment type code 

        //     if (!empty($dadosNFS['forma_pagamento']['parcelas'])) {
        //         $parcelas = $formaPagamento->addChild('parcelas'); // Groups the installments 
        //         foreach ($dadosNFS['forma_pagamento']['parcelas'] as $p) {
        //             $parcela = $parcelas->addChild('parcela'); // Each installment is a <parcela> tag 
        //             $parcela->addChild('numero', $p['numero']); // Installment number (1-24) 
        //             $parcela->addChild('valor', number_format($p['valor'], 2, ',', '')); // Formato brasileiro: vírgula como decimal
        //             $parcela->addChild('data_vencimento', $p['data_vencimento']); // Due date in dd/mm/yyyy format 
        //         }
        //     }
        // }




        // Formata o XML para melhor visualização e o salva em uma variável
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $this->xmlString = $dom->saveXML();

        // Imprime ou salva o XML gerado
        $this->savedXml($this->xmlString, $dadosNFS['identificador'], ADMambDesc, "nf");

        return $this->xmlString;

    }




    /**
     * Gera o XML para cancelamento de NFS-e da IPM.
     *
     * @param string $json String JSON contendo os dados para cancelamento:
     *                     - numero: Número da NFS-e (obrigatório, numérico 9 dígitos)
     *                     - serie_nfse: Série da NFS-e (obrigatório, numérico 9 dígitos)
     *                     - observacao: Motivo do cancelamento (obrigatório, até 1000 caracteres)
     *                     - prestador: 
     *                       - cpfcnpj: CPF/CNPJ do prestador (obrigatório, numérico 14 dígitos)
     *                       - cidade: Código TOM da cidade (obrigatório, numérico 9 dígitos)
     * @return array Array contendo o XML de cancelamento formatado e o identificador da NFS-e.
     */
    function mountXmlCancelInvoice(array $nfs_data, string $motivo_cancelamento): string
    {
        // Validar campos obrigatórios
        if (empty($nfs_data[0]["NUMERO"])) {
            throw new \InvalidArgumentException('Número da NFS-e é obrigatório para cancelamento');
        }
        if (empty($nfs_data[0]["SERIE"])) {
            throw new \InvalidArgumentException('Série da NFS-e é obrigatória para cancelamento');
        }
        if (empty($motivo_cancelamento)) {
            throw new \InvalidArgumentException('Observação (motivo) é obrigatória para cancelamento');
        }
        if (empty($nfs_data[0]["PRESTADOR_CPFCNPJ"])) {
            throw new \InvalidArgumentException('CPF/CNPJ do prestador é obrigatório para cancelamento');
        }
        if (empty($nfs_data[0]["PRESTADOR_CIDADE_CODIGO"])) {
            throw new \InvalidArgumentException('Código da cidade do prestador é obrigatório para cancelamento');
        }

        // Inicia a construção do XML
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><nfse/>');

        // --- <nf> Tag (Mandatory) ---
        $nf = $xml->addChild('nf'); // Agrupador com dados da NFS-e para cancelamento

        // Número da NFS-e (obrigatório, numérico 9 dígitos)
        $nf->addChild('numero', str_pad((string)$nfs_data[0]["NUMERO"], 9, '0', STR_PAD_LEFT));
        
        // Série da NFS-e (obrigatório, numérico 9 dígitos)
        $nf->addChild('serie_nfse', str_pad((string)$nfs_data[0]["SERIE"], 9, '0', STR_PAD_LEFT));
        
        // Situação deve ser "C" para cancelamento (obrigatório)
        $nf->addChild('situacao', 'C');
        
        // Motivo do cancelamento (obrigatório, até 1000 caracteres)
        $nf->addChild('observacao', htmlspecialchars(substr($motivo_cancelamento, 0, 1000)));

        // --- <prestador> Tag (Mandatory) ---
        $prestador = $xml->addChild('prestador'); // Dados do prestador
        
        // CPF/CNPJ do emissor da nota (obrigatório, numérico 14 dígitos)
        $prestador->addChild('cpfcnpj', preg_replace('/[^0-9]/', '', $nfs_data[0]["PRESTADOR_CPFCNPJ"]));
        
        // Código da cidade onde o emissor está estabelecido (obrigatório, numérico 9 dígitos)
        $prestador->addChild('cidade', str_pad((string)$nfs_data[0]["PRESTADOR_CIDADE_CODIGO"], 9, '0', STR_PAD_LEFT));

        // Formata o XML para melhor visualização e o salva em uma variável
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $this->xmlString = $dom->saveXML();

        // Identificador único para o arquivo de cancelamento
        //$identificador = time() . '-cancel-' . $nfs_data['numero'] . '-' . uniqid();
        
        // Salva o XML de cancelamento
        //$this->savedXml($this->xmlString, $identificador, ADMambDesc, "nf");

        return $this->xmlString;
    }

    /**
     * Salva um arquivo XML na estrutura de pastas correta (nfs/{ambiente}/{tipo}/YYYYMM/).
     * A função cria a estrutura de diretórios caso ela não exista.
     *
     * @param string $xmlContent O conteúdo do XML a ser salvo.
     * @param string $fileName O nome do arquivo XML (ex: 'nota-fiscal-123.xml').
     * @param string $environment O ambiente de destino. Valores válidos: 'producao' ou 'homologacao'.
     * @param string $type O tipo de pasta de destino. Valores válidos: 'enviadas', 'aprovadas' ou 'nf'.
     * @return bool Retorna true em caso de sucesso ou false em caso de falha.
     */
    public function savedXml(string $xmlContent, string $fileName, string $environment, string $type): bool
    {
        try {
            // Valida se os parâmetros de ambiente e tipo são os esperados
            $validEnvironments = ['producao', 'homologacao'];
            $validTypes = ['enviadas', 'aprovadas', 'nf'];

            if (!in_array($environment, $validEnvironments)) {
                error_log("Ambiente inválido fornecido: " . $environment);
                return false;
            }

            if (!in_array($type, $validTypes)) {
                error_log("Tipo inválido fornecido: " . $type);
                return false;
            }

            // 1. Monta o caminho do diretório com base no ano e mês atuais (ex: 202506)
            $yearMonth = date('Ym');
            $directoryPath = 'nfs' . DIRECTORY_SEPARATOR . $environment . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $yearMonth;

            // 2. Verifica se o diretório existe e, se não, o cria recursivamente
            if (!is_dir($directoryPath)) {

                // 0775 é uma permissão comum que permite leitura/escrita pelo dono/grupo e leitura por outros
                if (!mkdir($directoryPath, 0775, true)) {
                    error_log("Falha ao criar o diretório: " . $directoryPath);
                    return false;
                }
            }

            // 3. Monta o caminho completo do arquivo e salva o conteúdo XML
            $filePath = $directoryPath . DIRECTORY_SEPARATOR . $fileName . ".xml";

            // file_put_contents retorna o número de bytes escritos ou false em caso de erro.
            // Retornar a verificação explícita ' !== false ' é a forma mais segura.
            return file_put_contents($filePath, $xmlContent) !== false;

        } catch (Exception $e) {
            // DEV verifique o erro nesse caminho /var/tmp/my-errors.log
            error_log("Erro ao salvar XML: " . $e->getMessage());
            return false;
        }
    }


    // Método comum para todas as implementações
    public function validarComSchema(): bool
    {
        $validator = new JsonValid();
        $validator->check($this->dados, (object)['$ref' => 'file://' . $this->schemaPath]);

        if (!$validator->isValid()) {
            $errors = array_map(function ($error) {
                return "[{$error['property']}] {$error['message']}";
            }, $validator->getErrors());

            throw new \InvalidArgumentException(implode("\n", $errors));
        }
        return true;
    }

    /**
     * Extrai todos os dados da NFS-e do XML de retorno de sucesso
     *
     * @param string $responseXml XML de resposta do webservice
     * @return array Dados extraídos da NFS-e
     */
    public function extrairDadosNfseRetorno(string $responseXml): array
    {
        try {
            // Converter de ISO-8859-1 para UTF-8
            $xmlUtf8 = mb_convert_encoding($responseXml, 'UTF-8', 'ISO-8859-1');
            
            // Atualizar o encoding no cabeçalho XML para UTF-8
            $xmlUtf8 = preg_replace('/encoding="ISO-8859-1"/i', 'encoding="UTF-8"', $xmlUtf8);
            
            $xml = simplexml_load_string($xmlUtf8);
            
            if ($xml === false) {
                return [];
            }
            
            // Extrair todos os dados disponíveis
            $dados = [
                'numero_nfse' => isset($xml->numero_nfse) ? (int)$xml->numero_nfse : null,
                'serie_nfse' => isset($xml->serie_nfse) ? (int)$xml->serie_nfse : null,
                'data_nfse' => isset($xml->data_nfse) ? (string)$xml->data_nfse : null,
                'hora_nfse' => isset($xml->hora_nfse) ? (string)$xml->hora_nfse : null,
                'situacao_codigo_nfse' => isset($xml->situacao_codigo_nfse) ? (int)$xml->situacao_codigo_nfse : null,
                'situacao_descricao_nfse' => isset($xml->situacao_descricao_nfse) ? (string)$xml->situacao_descricao_nfse : null,
                'link_nfse' => isset($xml->link_nfse) ? (string)$xml->link_nfse : null,
                'cod_verificador_autenticidade' => isset($xml->cod_verificador_autenticidade) ? (string)$xml->cod_verificador_autenticidade : null,
            ];
            
            // Filtrar apenas valores não nulos
            return array_filter($dados, function($value) {
                return $value !== null;
            });
            
        } catch (\Exception $e) {
            error_log("Erro ao extrair dados da NFS-e: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Valida e extrai dados do XML de retorno de cancelamento de NFS-e
     * 
     * Layout esperado conforme manual IPM (NTE 35/2021):
     * 
     * Caso 1 - Erro geral (sem estrutura de documentos):
     * <retorno>
     *   <mensagem>
     *     <codigo>00206 - Nenhuma NFSe foi encontrada...</codigo>
     *   </mensagem>
     * </retorno>
     * 
     * Caso 2 - Erro/sucesso específico da nota:
     * <retorno>
     *   <documentos>
     *     <nfse>
     *       <dados>
     *         <numero>123</numero>
     *         <serie>1</serie>
     *       </dados>
     *       <mensagem>
     *         <codigo>[12345] - Descrição do erro</codigo>
     *       </mensagem>
     *     </nfse>
     *   </documentos>
     * </retorno>
     *
     * @param string $responseXml XML de resposta do webservice
     * @return array ['sucesso' => bool, 'mensagem' => string, 'dados' => array]
     */
    public function validarRetornoCancelamento(string $responseXml): array
    {
        try {
            // Converter de ISO-8859-1 para UTF-8
            $xmlUtf8 = mb_convert_encoding($responseXml, 'UTF-8', 'ISO-8859-1');
            
            // Atualizar o encoding no cabeçalho XML para UTF-8
            $xmlUtf8 = preg_replace('/encoding="ISO-8859-1"/i', 'encoding="UTF-8"', $xmlUtf8);
            
            $xml = simplexml_load_string($xmlUtf8);
            
            if ($xml === false) {
                return [
                    'sucesso' => false,
                    'mensagem' => 'XML de retorno inválido',
                    'dados' => [],
                    'responseXml' => $responseXml
                ];
            }

            // Inicializa array de dados extraídos
            $dadosExtraidos = [
                'numero' => null,
                'serie' => null,
                'mensagens_erro' => []
            ];

            $temErro = false;

            // CASO 1: Verifica mensagens de erro diretas em <retorno><mensagem><codigo>
            // Exemplo: Erro geral como "NFSe não encontrada"
            if (isset($xml->mensagem->codigo)) {
                foreach ($xml->mensagem->codigo as $codigoErro) {
                    $mensagemErro = trim((string)$codigoErro);
                    if (!empty($mensagemErro)) {
                        $dadosExtraidos['mensagens_erro'][] = $mensagemErro;
                        $temErro = true;
                    }
                }
            }

            // CASO 2: Verifica estrutura completa com documentos
            // Extrai dados da tag <retorno><documentos><nfse><dados>
            if (isset($xml->documentos->nfse->dados)) {
                $dados = $xml->documentos->nfse->dados;
                $dadosExtraidos['numero'] = isset($dados->numero) ? (int)$dados->numero : null;
                $dadosExtraidos['serie'] = isset($dados->serie) ? (int)$dados->serie : null;
            }

            // Verifica se há mensagens de erro na tag <documentos><nfse><mensagem><codigo>
            if (isset($xml->documentos->nfse->mensagem->codigo)) {
                // Pode ter múltiplas tags <codigo>
                foreach ($xml->documentos->nfse->mensagem->codigo as $codigoErro) {
                    $mensagemErro = trim((string)$codigoErro);
                    if (!empty($mensagemErro)) {
                        $dadosExtraidos['mensagens_erro'][] = $mensagemErro;
                        $temErro = true;
                    }
                }
            }

            // Determina sucesso ou falha
            if ($temErro) {
                return [
                    'sucesso' => false,
                    'mensagem' => implode('<br>', $dadosExtraidos['mensagens_erro']),
                    'dados' => $dadosExtraidos,
                    'responseXml' => $responseXml
                ];
            } else {
                // Se não há erros, o cancelamento foi bem-sucedido
                return [
                    'sucesso' => true,
                    'mensagem' => 'NFS-e cancelada com sucesso',
                    'dados' => $dadosExtraidos,
                    'responseXml' => $responseXml
                ];
            }
            
        } catch (\Exception $e) {
            error_log("Erro ao validar retorno de cancelamento: " . $e->getMessage());
            return [
                'sucesso' => false,
                'mensagem' => 'Erro ao processar XML de retorno: ' . $e->getMessage(),
                'dados' => [],
                'responseXml' => $responseXml
            ];
        }
    }
}

?>
