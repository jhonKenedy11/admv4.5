<?php
/**
 * @package   astec
 * @name      c_parametro
 * @version   4.5.0
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy Dos Santos Mello <jhon.kened11@hotmail.com>
 * @date      30/05/2025
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/est/c_ipm_estrategy.php");

//Class c_nota_fiscal_servico
class c_nota_fiscal_servico extends c_user
{
    protected $config;
    protected $dados;
    protected $schemaPath;

    function __construct()
    {   
        // Cria uma instancia variaveis de sessao
        c_user::from_array($_SESSION['user_array']);

        $this->config = NULL;
        $this->dados = NULL;
    }

    public function typeFramework()
    {
        $sql = "SELECT CODMUNICIPIO FROM AMB_EMPRESA WHERE EMPRESA = ?";

        $banco = new c_banco();
        $banco->prepare($sql);
        $banco->bind('s',  $this->m_empresaid);
        $banco->execute();

        $resultado = $banco->fetchOneAssoc();

        $codigo_municipio = $resultado["CODMUNICIPIO"];

        $config = $this->getConfigMunicipality($codigo_municipio);

        switch (isset($config["padrao"])) {
            case 'IPM':
                $origem_dados = 'pedido_servico';
                $id = 13;
                $objIpm =  new IpmStrategy();

                $objIpm->processForShipping($config, $id, $origem_dados);

            case 'GINFES':
                //return new GinfesStrategy($config, $dados);
            default:
                throw new \Exception("Municipio nao suportado:  $codigo_municipio");
        }
    }

    public function getConfigMunicipality(string $codigoMunicipio): ?array
    {
        $caminhoJson = __DIR__ . '/../../bib/storage/urls_webservices.json';

        if (!file_exists($caminhoJson)) {
            throw new \Exception("Arquivo de configuração não encontrado: $caminhoJson");
        }

        $conteudo = file_get_contents($caminhoJson);
        $configs = json_decode($conteudo, true);

        if (!is_array($configs)) {
            throw new \Exception("Erro ao decodificar JSON de configuração.");
        }

        return $configs[$codigoMunicipio] ?? null;
    }


    //abstract public function gerarXml(): string;
    //abstract public function enviar(): string;


}
?>
