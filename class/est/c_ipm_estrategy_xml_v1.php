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
    function mountXmlIpm(): string
    {
        // Sample data with multiple items
        $dadosNFS = [
            'identificador' => time() . '-nfs',
            // 'valor_total' is now calculated automatically from the items' sum.
            'valor_desconto' => 50.00,
            'valor_ir' => null, // Optional field example
            'observacao' => 'Serviços de consultoria e desenvolvimento.',
            'prestador' => [
                'cpfcnpj' => '26179567000160',
                'cidade' => '4119152',
            ],
            'tomador' => [
                'tipo' => 'J', // J: Juridical Person, F: Physical Person, E: Foreigner 
                'cpfcnpj' => '21033620000105',
                'nome_razao_social' => 'Empresa Contratante Exemplo LTDA',
                'sobrenome_nome_fantasia' => 'Nome Fantasia Exemplo',
                'logradouro' => 'Rua das Flores',
                'numero_residencia' => '123',
                'bairro' => 'Centro',
                'cidade' => '8133', // Service Taker's City Code (TOM) 
                'cep' => '4119152',
                'email' => 'contato@empresaexemplo.com.br;financeiro@empresaexemplo.com.br',
            ],
            'itens' => [ // The <itens> tag holds one or more <lista> tags 
                [
                    'tributa_municipio_prestador' => 'S', // 'S' when taxation occurs in the provider's municipality 
                    'codigo_local_prestacao_servico' => '8055',
                    'codigo_item_lista_servico' => '104', // Service list code as per LC 116/2003 
                    'descritivo' => 'Desenvolvimento de módulo de faturamento.',
                    'aliquota_item_lista_servico' => 5.00,
                    'situacao_tributaria' => 0, // 0 - Tributada Integralmente 
                    'valor_tributavel' => 1400.50, // This value serves as the tax base 
                    'valor_deducao' => 0,
                    'valor_issrf' => 0,
                ],
                [
                    'tributa_municipio_prestador' => 'S',
                    'codigo_local_prestacao_servico' => '8055',
                    'codigo_item_lista_servico' => '107', // Example: 1.07 - Suporte técnico.
                    'descritivo' => 'Suporte técnico especializado por 10 horas.',
                    'aliquota_item_lista_servico' => 5.00,
                    'situacao_tributaria' => 0,
                    'valor_tributavel' => 600.00,
                    'valor_deducao' => 0,
                    'valor_issrf' => 0,
                ]
            ],
            'forma_pagamento' => [
                'tipo_pagamento' => 2, // 2 - A prazo 
                'parcelas' => [
                    ['numero' => 1, 'valor' => 1000.25, 'data_vencimento' => '20/07/2025'],
                    ['numero' => 2, 'valor' => 950.25, 'data_vencimento' => '20/08/2025'],
                ]
            ]
        ];


        $valorTotalCalculado = 0;

        foreach ($dadosNFS['itens'] as $item) {
            $valorTotalCalculado += $item['valor_tributavel'];
        }
        
        $dadosNFS['valor_total'] = $valorTotalCalculado;


        // Inicia a construcao do XML
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><nfse/>');

        // variavel usada para testar o xml 
        $var_teste = true;

        if($var_teste == true) {
            $xml->addChild('nfse_teste', 1);
        }

        // --- <identificador> Tag (Optional) ---
        if (!empty($dadosNFS['identificador'])) {
            $xml->addChild('identificador', $dadosNFS['identificador']); // An identifier to prevent duplicate processing 
        }

        // --- <nf> Tag (Mandatory) ---
        $nf = $xml->addChild('nf'); // Groups the main values of the NFS-e 
        $nf->addChild('valor_total', number_format($dadosNFS['valor_total'], 2, ',', '')); // Total value of the NFS-e 

        if (isset($dadosNFS['valor_desconto']) && $dadosNFS['valor_desconto'] > 0) {
            $nf->addChild('valor_desconto', number_format($dadosNFS['valor_desconto'], 2, ',', '')); // Optional discount value 
        }
        if (isset($dadosNFS['valor_ir']) && $dadosNFS['valor_ir'] > 0) {
            $nf->addChild('valor_ir', number_format($dadosNFS['valor_ir'], 2, ',', '')); // Optional IR value 
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
            $lista->addChild('aliquota_item_lista_servico', number_format($itemNFS['aliquota_item_lista_servico'], 2, ',', '')); // Tax rate for the item 
            $lista->addChild('situacao_tributaria', $itemNFS['situacao_tributaria']); // Tax situation code 
            $lista->addChild('valor_tributavel', number_format($itemNFS['valor_tributavel'], 2, ',', '')); // Taxable value of the item 

            if (isset($itemNFS['valor_deducao']) && $itemNFS['valor_deducao'] > 0) {
                $lista->addChild('valor_deducao', number_format($itemNFS['valor_deducao'], 2, ',', '')); // Deduction value, if applicable 
            }
            if (isset($itemNFS['valor_issrf']) && $itemNFS['valor_issrf'] > 0) {
                $lista->addChild('valor_issrf', number_format($itemNFS['valor_issrf'], 2, ',', '')); // Withheld ISS value, if applicable 
            }
        }

        // --- <forma_pagamento> Tag (Optional) ---
        if (!empty($dadosNFS['forma_pagamento'])) {
            $formaPagamento = $xml->addChild('forma_pagamento'); // Payment method details 
            $formaPagamento->addChild('tipo_pagamento', $dadosNFS['forma_pagamento']['tipo_pagamento']); // Payment type code 

            if (!empty($dadosNFS['forma_pagamento']['parcelas'])) {
                $parcelas = $formaPagamento->addChild('parcelas'); // Groups the installments 
                foreach ($dadosNFS['forma_pagamento']['parcelas'] as $p) {
                    $parcela = $parcelas->addChild('parcela'); // Each installment is a <parcela> tag 
                    $parcela->addChild('numero', $p['numero']); // Installment number (1-24) 
                    $parcela->addChild('valor', number_format($p['valor'], 2, ',', '')); // Installment value 
                    $parcela->addChild('data_vencimento', $p['data_vencimento']); // Due date in dd/mm/yyyy format 
                }
            }
        }


        // Formata o XML para melhor visualização e o salva em uma variável
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $this->xmlString = $dom->saveXML();

        return $this->xmlString;

        // Imprime ou salva o XML gerado
        //$this->savedXml($xmlString, $dadosNFS['identificador'], ADMambDesc, "nf");

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


    /**
     * Salva um evento relacionado à Nota Fiscal de Serviço na tabela
     * EST_NOTA_FISCAL_SERVICO_EVENTOS.
     *
     * Essa função armazena os dados do evento (como emissão, cancelamento, consulta, etc.)
     * incluindo o XML de retorno da requisição feita, que deve ser previamente capturado.
     *
     * @param PDO $pdo Conexão PDO com o banco de dados.
     * @param array $dados Array associativo com os seguintes campos obrigatórios:
     *                     - id_nfs (int): ID da nota fiscal.
     *                     - centro_custo (string): Código do centro de custo (até 11 caracteres).
     *                     - serie (string|null): Série da NFS (até 3 caracteres).
     *                     - numero (int|null): Número da NFS.
     *                     - tipo_evento (string): Tipo de evento (C, E, S, N).
     *                     - codigo_retorno (string|null): Código de retorno da operação (até 10 caracteres).
     *                     - created_user (int): ID do usuário que criou o registro.
     * @param string $xmlRetorno XML de resposta do serviço (pequeno), extraído do corpo da resposta.
     *
     * @return bool Retorna true em caso de sucesso, ou false se ocorrer algum erro na execução.
     */
    function saveEventInvoice(array $dados, string $xmlRetorno): bool
    {



        /*

            '<?xml version="1.0" encoding="ISO-8859-1"?>
            <retorno>
                <mensagem>
                    <codigo>00031 - C�digo do item da lista de servi�o est� preenchido incorretamente.</codigo>
                    <codigo>00034 - Al�quota do servi�o prestado n�o foi preenchida corretamente.</codigo>
                </mensagem>
            </retorno>'

        */

        $sql = "
            INSERT INTO EST_NOTA_FISCAL_SERVICO_EVENTOS (
                ID_NFS,
                CENTRO_CUSTO,
                SERIE,
                NUMERO,
                TIPO_EVENTO,
                CODIGO_RETORNO,
                XML_RETORNO,
                CREATED_USER
            ) VALUES (
                :id_nfs,
                :centro_custo,
                :serie,
                :numero,
                :tipo_evento,
                :codigo_retorno,
                :xml_retorno,
                :created_user
            )
        ";

        try {

            $this->banco = new c_banco_pdo();
            $this->banco->prepare($sql);


            $this->banco->bindValue(':id_nfs',         $dados['id_nfs'],         PDO::PARAM_INT);
            $this->banco->bindValue(':centro_custo',   $dados['centro_custo'],   PDO::PARAM_STR);
            $this->banco->bindValue(':serie',          $dados['serie'],          PDO::PARAM_STR);
            $this->banco->bindValue(':numero',         $dados['numero'],         PDO::PARAM_INT);
            $this->banco->bindValue(':tipo_evento',    $dados['tipo_evento'],    PDO::PARAM_STR);
            $this->banco->bindValue(':codigo_retorno', $dados['codigo_retorno'], PDO::PARAM_STR);
            $this->banco->bindValue(':xml_retorno',    $xmlRetorno,              PDO::PARAM_STR);
            $this->banco->bindValue(':created_user',   $dados['created_user'],   PDO::PARAM_INT);

            $this->banco->execute();

            if ($this->banco->rowCount() > 0) {
                return true;
            }

        } catch (PDOException $e) {
            // DEV verifique o erro nesse caminho /var/tmp/my-errors.log
            error_log("Erro ao salvar evento NFS: " . $e->getMessage());
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
}

?>
