<?php

/**
 * @package   adm
 * @name      p_nfe_json_espelho
 * @version   4.5.0
 * @copyright 2026
 * @link      http://www.admservice.com.br/
 * @author    Sistema ADM
 * @date      10/03/2026
 *
 * Form de espelho de NF-e que monta o XML a partir de um JSON,
 * separando o fluxo em funções por bloco (contexto, ide, emit, dest, etc.).
 * Nesta primeira etapa, implementa apenas o bloco de CONTEXTO.
 */

$dir = (__DIR__);

//error_reporting(E_ALL);
ini_set('display_errors', 'Off');
require_once $dir . '/../../../sped/vendor/autoload.php';

include_once($dir . "/../../bib/c_user.php");
require_once($dir . "/../../class/est/c_nfe_json_tags.php");

class p_nfe_json_espelho extends c_user
{
    /**
     * Instância do montador de XML da NFe (Make).
     *
     * @var \NFePHP\NFe\Make|null
     */
    private $nfe = null;

    /**
     * Dados completos do JSON decodificado.
     * @var array
     */
    private $dadosNota = [];

    /**
     * Bloco de contexto do JSON (empresa, filial, ambiente, id_nf).
     * @var array
     */
    private $contexto = [];

    public function __construct()
    {
        // Filtro padrão, seguindo o padrão dos outros forms
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet  = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        $this->m_submenu = isset($parmPost['submenu']) ? $parmPost['submenu'] : null;

        // Cria uma instancia variaveis de sessao
        $this->from_array($_SESSION['user_array']);
    }

    /**
     * Carrega o JSON de uma string ou de um arquivo
     * e popula $this->dadosNota e $this->contexto.
     *
     * Este método é o ponto de entrada do BLOCO CONTEXTO.
     *
     * @param string|null $jsonString Conteúdo JSON já carregado (opcional).
     * @param string|null $jsonPath   Caminho do arquivo JSON (opcional).
     * @return array Retorna o array de contexto para uso pelo restante do fluxo.
     */
    public function monta_contexto($jsonString = null, $jsonPath = null)
    {
        // Prioriza string recebida; se não vier, tenta carregar de arquivo.
        if ($jsonString === null) {
            if ($jsonPath === null) {
                // Caminho padrão de teste que você criou em class/est/JSON
                $jsonPath = dirname(__FILE__) . "/../../class/est/JSON";
            }

            if (!file_exists($jsonPath)) {
                throw new Exception("Arquivo JSON de NF-e não encontrado em: " . $jsonPath);
            }

            $jsonString = file_get_contents($jsonPath);
        }

        $dados = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON inválido para NF-e: " . json_last_error_msg());
        }

        $this->dadosNota = $dados;
        $this->contexto  = isset($dados['contexto']) && is_array($dados['contexto'])
            ? $dados['contexto']
            : [];

        // Ajusta alguns campos de contexto no usuário/logado, se fizer sentido.
        if (isset($this->contexto['empresa_id'])) {
            $this->m_empresaid = $this->contexto['empresa_id'];
        }
        if (isset($this->contexto['filial_id'])) {
            $this->m_empresacentrocusto = $this->contexto['filial_id'];
        }

        // Retorna apenas o bloco de contexto para facilitar debug/uso externo.
        return $this->contexto;
    }

    /**
     * Inicializa o objeto NFePHP\NFe\Make para este form, se ainda não estiver criado.
     *
     * @return \NFePHP\NFe\Make
     */
    protected function getMakeInstance()
    {
        if ($this->nfe === null) {
            // PL_010 é o schema mais novo (NF-e 4.0 / IBS-CBS)
            $this->nfe = new \NFePHP\NFe\Make('PL_010');
        }
        return $this->nfe;
    }

    /**
     * Controle de blocos: chama os métodos de montagem de tags
     * numa classe dedicada (c_nfe_json_tags).
     *
     * @param array $blocos Lista de blocos em ordem. Ex: ['ide', 'emitente']
     * @return string XML resultante (parcial ou completo, conforme blocos)
     */
    public function processa_blocos(array $blocos)
    {
        if (empty($this->dadosNota)) {
            $this->monta_contexto();
        }

        $nfe = $this->getMakeInstance();
        $tags = new c_nfe_json_tags($nfe, $this->dadosNota, $this->contexto);

        foreach ($blocos as $bloco) {
            switch ($bloco) {
                case 'ide':
                    $tags->monta_ide();
                    break;
                case 'emitente':
                    $tags->monta_emitente();
                    break;
                case 'destinatario':
                    $tags->monta_destinatario();
                    break;
                case 'produtos':
                    $tags->monta_produtos();
                    break;
                case 'totais':
                    $tags->monta_totais();
                    break;
                case 'transporte':
                    $tags->monta_transporte();
                    break;
                case 'cobranca_pagamentos':
                    $tags->monta_cobranca_pagamentos();
                    break;
                case 'infAdic':
                    $tags->monta_infAdic();
                    break;
                default:
                    break;
            }
        }

        return $nfe->getXML();
    }
}

