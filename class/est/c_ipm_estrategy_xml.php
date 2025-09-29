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
     * Gera o XML da NFS-e (layout 2.5) a partir de um JSON, incluindo obrigatórias e opcionais sob condição.
     * @param string $jsonNFS JSON com os dados da nota.
     * @return string XML gerado.
     * @throws \InvalidArgumentException Se o JSON for inválido ou faltar campo obrigatório.
     */
    public function mountXmlIpm(string $jsonNFS): string
    {
        $dadosNFS = json_decode($jsonNFS, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($dadosNFS)) {
            throw new \InvalidArgumentException('JSON inválido fornecido para montagem do XML.');
        }

        // Função para sanitizar caracteres proibidos
        $sanitize = function($value) {
            $search  = ['<', '>', '\'', '"', '/'];
            $replace = ['&lt;', '&gt;', '&apos;', '&quot;', ''];
            $value = str_replace($search, $replace, $value);
            if (str_contains($value, '&')) {
                throw new \InvalidArgumentException('O caractere "&" não é permitido no XML.');
            }
            return $value;
        };

        // Validação dos campos obrigatórios
        $obrigatorios = [
            'valor_total' => $dadosNFS['valor_total'] ?? null,
            'prestador'   => $dadosNFS['prestador'] ?? null,
            'tomador'     => $dadosNFS['tomador'] ?? null,
            'itens'       => $dadosNFS['itens'] ?? null,
        ];
        foreach ($obrigatorios as $campo => $valor) {
            if (empty($valor)) {
                throw new \InvalidArgumentException("Campo obrigatório ausente: $campo");
            }
        }

        if (
            empty($dadosNFS['prestador']['cpfcnpj']) ||
            empty($dadosNFS['prestador']['cidade']) ||
            empty($dadosNFS['tomador']['tipo']) ||
            empty($dadosNFS['itens'][0]['tributa_municipio_prestador']) ||
            empty($dadosNFS['itens'][0]['codigo_local_prestacao_servico']) ||
            empty($dadosNFS['itens'][0]['codigo_item_lista_servico']) ||
            empty($dadosNFS['itens'][0]['descritivo']) ||
            !isset($dadosNFS['itens'][0]['aliquota_item_lista_servico']) ||
            !isset($dadosNFS['itens'][0]['situacao_tributaria']) ||
            !isset($dadosNFS['itens'][0]['valor_tributavel'])
        ) {
            throw new \InvalidArgumentException("Campos obrigatórios de itens ausentes.");
        }

        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><nfse/>');

        // <identificador> (opcional)
        if (!empty($dadosNFS['identificador'])) {
            $xml->addChild('identificador', $sanitize($dadosNFS['identificador']));
        }

        // <rps> (opcional, agrupador)
        if (!empty($dadosNFS['rps'])) {
            $rps = $xml->addChild('rps');
            if (!empty($dadosNFS['rps']['nro_recibo_provisorio'])) {
                $rps->addChild('nro_recibo_provisorio', $sanitize($dadosNFS['rps']['nro_recibo_provisorio']));
            }
            if (!empty($dadosNFS['rps']['serie_recibo_provisorio'])) {
                $rps->addChild('serie_recibo_provisorio', $sanitize($dadosNFS['rps']['serie_recibo_provisorio']));
            }
            if (!empty($dadosNFS['rps']['data_emissao_recibo_provisorio'])) {
                $rps->addChild('data_emissao_recibo_provisorio', $sanitize($dadosNFS['rps']['data_emissao_recibo_provisorio']));
            }
            if (!empty($dadosNFS['rps']['hora_emissao_recibo_provisorio'])) {
                $rps->addChild('hora_emissao_recibo_provisorio', $sanitize($dadosNFS['rps']['hora_emissao_recibo_provisorio']));
            }
        }

        // <pedagio> (opcional, agrupador)
        if (!empty($dadosNFS['pedagio'])) {
            $pedagio = $xml->addChild('pedagio');
            if (!empty($dadosNFS['pedagio']['cod_equipamento_automatico'])) {
                $pedagio->addChild('cod_equipamento_automatico', $sanitize($dadosNFS['pedagio']['cod_equipamento_automatico']));
            }
        }

        // <nf> (obrigatório)
        $nf = $xml->addChild('nf');
        // <serie_nfse> (opcional)
        if (!empty($dadosNFS['serie_nfse'])) {
            $nf->addChild('serie_nfse', $sanitize($dadosNFS['serie_nfse']));
        }
        // <data_fato_gerador> (opcional)
        if (!empty($dadosNFS['data_fato_gerador'])) {
            $nf->addChild('data_fato_gerador', $sanitize($dadosNFS['data_fato_gerador']));
        }
        // <valor_total> (obrigatório)
        $nf->addChild('valor_total', number_format($dadosNFS['valor_total'], 2, ',', ''));
        // <valor_desconto> (opcional)
        if (!empty($dadosNFS['valor_desconto'])) {
            $nf->addChild('valor_desconto', number_format($dadosNFS['valor_desconto'], 2, ',', ''));
        }
        // <valor_ir> (opcional)
        if (!empty($dadosNFS['valor_ir'])) {
            $nf->addChild('valor_ir', number_format($dadosNFS['valor_ir'], 2, ',', ''));
        }
        // <valor_inss>, <valor_contribuicao_social>, <valor_rps>, <valor_pis>, <valor_cofins> (opcionais)
        $opcionaisNf = [
            'valor_inss', 'valor_contribuicao_social', 'valor_rps', 'valor_pis', 'valor_cofins'
        ];
        foreach ($opcionaisNf as $tag) {
            if (!empty($dadosNFS[$tag])) {
                $nf->addChild($tag, number_format($dadosNFS[$tag], 2, ',', ''));
            }
        }
        // <observacao> (opcional)
        if (!empty($dadosNFS['observacao'])) {
            $nf->addChild('observacao', $sanitize($dadosNFS['observacao']));
        }

        // <prestador> (obrigatório)
        $prestador = $xml->addChild('prestador');
        $prestador->addChild('cpfcnpj', $sanitize($dadosNFS['prestador']['cpfcnpj']));
        $prestador->addChild('cidade', $sanitize($dadosNFS['prestador']['cidade']));

        // <tomador> (obrigatório)
        $tomador = $xml->addChild('tomador');
        $tomador->addChild('tipo', $sanitize($dadosNFS['tomador']['tipo']));
        // Opcionais do tomador
        $opcionaisTomador = [
            'identificador', 'estado', 'pais', 'cpfcnpj', 'ie', 'nome_razao_social', 'sobrenome_nome_fantasia',
            'logradouro', 'email', 'numero_residencia', 'complemento', 'ponto_referencia', 'bairro', 'cidade', 'cep',
            'ddd_fone_comercial', 'fone_comercial', 'ddd_fone_residencial', 'fone_residencial', 'ddd_fax', 'fone_fax'
        ];
        foreach ($opcionaisTomador as $tag) {
            if (!empty($dadosNFS['tomador'][$tag])) {
                $tomador->addChild($tag, $sanitize($dadosNFS['tomador'][$tag]));
            }
        }

        // <itens> (obrigatório)
        $itens = $xml->addChild('itens');
        foreach ($dadosNFS['itens'] as $item) {
            $lista = $itens->addChild('lista');
            $lista->addChild('tributa_municipio_prestador', $sanitize($item['tributa_municipio_prestador']));
            $lista->addChild('codigo_local_prestacao_servico', $sanitize($item['codigo_local_prestacao_servico']));
            $lista->addChild('codigo_item_lista_servico', $sanitize($item['codigo_item_lista_servico']));
            $lista->addChild('descritivo', $sanitize($item['descritivo']));
            $lista->addChild('aliquota_item_lista_servico', number_format($item['aliquota_item_lista_servico'], 2, ',', ''));
            $lista->addChild('situacao_tributaria', $sanitize($item['situacao_tributaria']));
            $lista->addChild('valor_tributavel', number_format($item['valor_tributavel'], 2, ',', ''));

            // Opcionais do item
            $opcionaisItem = [
                'valor_deducao', 'valor_issrf', 'cno', 'unidade_codigo', 'unidade_quantidade', 'unidade_valor_unitario',
                'codigo_atividade'
            ];
            foreach ($opcionaisItem as $tag) {
                if (!empty($item[$tag])) {
                    $lista->addChild($tag, is_numeric($item[$tag]) ? number_format($item[$tag], 2, ',', '') : $sanitize($item[$tag]));
                }
            }
        }

        // <forma_pagamento> (opcional)
        if (!empty($dadosNFS['forma_pagamento'])) {
            $formaPagamento = $xml->addChild('forma_pagamento');
            if (!empty($dadosNFS['forma_pagamento']['tipo_pagamento'])) {
                $formaPagamento->addChild('tipo_pagamento', $sanitize($dadosNFS['forma_pagamento']['tipo_pagamento']));
            }
            if (!empty($dadosNFS['forma_pagamento']['parcelas'])) {
                $parcelas = $formaPagamento->addChild('parcelas');
                foreach ($dadosNFS['forma_pagamento']['parcelas'] as $p) {
                    $parcela = $parcelas->addChild('parcela');
                    if (!empty($p['numero'])) {
                        $parcela->addChild('numero', $sanitize($p['numero']));
                    }
                    if (!empty($p['valor'])) {
                        $parcela->addChild('valor', number_format($p['valor'], 2, ',', ''));
                    }
                    if (!empty($p['data_vencimento'])) {
                        $parcela->addChild('data_vencimento', $sanitize($p['data_vencimento']));
                    }
                }
            }
        }

        // <genericos> e <produtos> (opcionais, exemplo)
        if (!empty($dadosNFS['genericos'])) {
            $genericos = $xml->addChild('genericos');
            foreach ($dadosNFS['genericos'] as $linha) {
                $linhaTag = $genericos->addChild('linha');
                if (!empty($linha['titulo'])) {
                    $linhaTag->addChild('titulo', $sanitize($linha['titulo']));
                }
                if (!empty($linha['descricao'])) {
                    $linhaTag->addChild('descricao', $sanitize($linha['descricao']));
                }
            }
        }
        if (!empty($dadosNFS['produtos'])) {
            $produtos = $xml->addChild('produtos');
            if (!empty($dadosNFS['produtos']['descricao'])) {
                $produtos->addChild('descricao', $sanitize($dadosNFS['produtos']['descricao']));
            }
            if (!empty($dadosNFS['produtos']['valor'])) {
                $produtos->addChild('valor', number_format($dadosNFS['produtos']['valor'], 2, ',', ''));
            }
        }

        // Formata o XML para melhor visualização
        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        $this->xmlString = $dom->saveXML();

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
