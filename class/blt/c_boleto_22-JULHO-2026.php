<?php

/**
 * @package   astecv3
 * @name      c_boleto
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      12/12/2016
 */

$dir = dirname(__FILE__);
//include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_database_pdo.php");

//Class C_FIN_BANCO
class c_boleto extends c_user
{

    /*
     * TABLE NAME FIN_BANCO
     */

    //construtor
    function __construct() {}


    /**
     * @name selectLancBoleto
     * @description seleciona lancamentos para impressao de boletos
     */
    public function selectLancBoleto($id = null, $num = null, $serie = null, $par = null)
    {

        $sql  = "SELECT * FROM FIN_LANCAMENTO ";
        if (!is_null($id) and ($id != '')) {
            // Se o ID contém vírgula, trata como múltiplos IDs
            if (strpos($id, ',') !== false) {
                $ids = explode(',', $id);
                $ids = array_map('trim', $ids);
                $ids = array_filter($ids); // Remove valores vazios
                if (!empty($ids)) {
                    $idsStr = implode(',', array_map('intval', $ids));
                    $sql .= "WHERE (id IN (" . $idsStr . ")) ";
                } else {
                    $sql .= "WHERE (id=" . $id . ") ";
                }
            } else {
                $sql .= "WHERE (id=" . $id . ") ";
            }
        } else {
            $sql .= "where (MODOPGTO='B') and (sitpgto='A') and (TIPOLANCAMENTO='R') and (TIPODOCTO='B')  ";
            if ($num != null) {
                $sql .= "and (numlcto=" . $num . ") ";
                if ($serie != null) {
                    $sql .= "and (origem='" . $serie . "') ";
                }
            }
        }
        /*        if ($num != null){
            $sql .= "and (docto=".$num.") ";
            if ($serie != null){
                $sql .= "and (serie='".$serie."') ";
                if ($par != null){
                    $sql .= "and (parcela=".$par.")";
                }
            }
        }
    }*/
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    } //fim existeBanco

    /**
     * @name selectAllBoletos
     * @description seleciona lançamentos para impressão de boletos por número do documento e pessoa
     * @param int $numDoc Número do documento
     * @param int $pessoa ID da pessoa/cliente
     * @param string $origem Origem do documento (PED ou outro)
     * @return array Resultado da consulta
     */
    public function selectAllBoletos(int $numDoc, int $pessoa, string $origem): array
    {
        try {
            // Monta a consulta SQL com placeholders
            if ($origem == 'PED') {
                $sql = "SELECT * FROM FIN_LANCAMENTO 
                    WHERE MODOPGTO = :modopgto 
                    AND SITPGTO = :sitpgto 
                    AND TIPOLANCAMENTO = :tipolancamento 
                    AND TIPODOCTO = :tipodocto 
                    AND NUMLCTO = :numdoc 
                    AND PESSOA = :pessoa";
            } else {
                $sql = "SELECT * FROM FIN_LANCAMENTO 
                    WHERE MODOPGTO = :modopgto 
                    AND SITPGTO = :sitpgto 
                    AND TIPOLANCAMENTO = :tipolancamento 
                    AND TIPODOCTO = :tipodocto 
                    AND DOCTO = :numdoc 
                    AND PESSOA = :pessoa";
            }

            // Define os parâmetros para bind
            $binds = [
                ':modopgto' => 'B',
                ':sitpgto' => 'A',
                ':tipolancamento' => 'R',
                ':tipodocto' => 'B',
                ':numdoc' => $numDoc,
                ':pessoa' => $pessoa
            ];

            // Salva SQL e binds para debug
            $debugInfo = [
                'sql' => preg_replace('/\s+/', ' ', trim($sql)),
                'binds' => $binds,
                'origem' => $origem
            ];

            // Executa a consulta usando PDO
            $banco = new c_banco_pdo();
            $banco->prepare($sql);

            // Faz o bind dos parâmetros
            foreach ($binds as $param => $value) {
                $banco->bindValue($param, $value);
            }

            $banco->execute();
            $resultado = $banco->fetchAll();

            // Retorna resultado ou informações de debug se não encontrar registros
            if (!empty($resultado)) {
                return $resultado;
            } else {
                return [
                    'status' => false,
                    'message' => 'Nenhum registro encontrado',
                    'debug' => $debugInfo,
                    'rowCount' => $banco->rowCount()
                ];
            }
        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'debug' => isset($debugInfo) ? $debugInfo : ['sql' => preg_replace('/\s+/', ' ', trim($sql ?? '')), 'binds' => $binds ?? []]
            ];
        }
    } //fim selectAllBoletos


    /**
     * @name getDadosContaBanco
     * @description seleciona dados da conta bancária
     * @param int $conta Conta bancária
     * @return array Resultado da consulta
     */
    public function getDadosContaBanco(int $conta): array
    {
        $sql = "SELECT * FROM FIN_CONTA WHERE CONTA = :conta";
        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':conta', $conta, PDO::PARAM_INT);
        $banco->execute();
        return $banco->fetch();
    }

    /**
     * @name getDadosTabelaApi
     * @description seleciona dados da tabela de API
     * @param int $id_lancamento ID do lançamento
     * @return ?array Dados da tabela de API
     */
    public function getDadosTabelaApiInter(int $id_lancamento): ?array
    {
        $sql = "SELECT *
            FROM FIN_API_INTER
            WHERE ID_LANCAMENTO = :id_lancamento
            AND SITUACAO <> 'CANCELADO'
            ORDER BY ID DESC
            LIMIT 1";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
        $banco->execute();
        $resultado = $banco->fetch();
        return $resultado ? $resultado : null;
    }

    public function getDadosTabelaApiBradesco(int $id_lancamento): ?array
    {
        $sql = "SELECT *
            FROM FIN_API_BRADESCO
            WHERE ID_LANCAMENTO = :id_lancamento
            ORDER BY ID DESC
            LIMIT 1";

        $banco = new c_banco_pdo();
        $banco->prepare($sql);
        $banco->bindValue(':id_lancamento', $id_lancamento, PDO::PARAM_INT);
        $banco->execute();
        return $banco->fetch();
    }

    /**
     * Exibe uma página de erro formatada quando boleto não é encontrado
     * 
     * @param array $retorno Resposta da API
     */
    function exibirErroBoletoPagina($retorno)
    {
        $mensagem_erro = isset($retorno['erros']['title'])
            ? $retorno['erros']['title']
            : 'Erro desconhecido ao gerar boleto';

        $codigo_erro = isset($retorno['http_code']) ? $retorno['http_code'] : 'N/A';
        $data_hora = date('d/m/Y H:i:s');

        // CONFIGURE AQUI OS DADOS DE SUPORTE
        $email_suporte = 'suporte@empresa.com.br';
        $telefone_suporte = '(11) 3000-0000';
        $whatsapp_suporte = '11999999999';

?>
        <!DOCTYPE html>
        <html lang="pt-BR">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Boleto Não Localizado</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    background: linear-gradient(135deg, rgb(241, 236, 235) 0%, rgb(222, 200, 197) 100%);
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: 20px;
                }

                .container-erro {
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 15px 50px rgba(0, 0, 0, 0.3);
                    max-width: 700px;
                    padding: 50px 40px;
                    text-align: center;
                }

                .icone-erro {
                    width: 100px;
                    height: 100px;
                    margin: 0 auto 25px;
                    background: #ffe5e5;
                    border-radius: 50%;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 50px;
                    border: 3px solid #e74c3c;
                }

                h1 {
                    color: #c0392b;
                    font-size: 28px;
                    margin-bottom: 10px;
                    font-weight: bold;
                }

                .subtitulo {
                    color: #7f8c8d;
                    font-size: 14px;
                    margin-bottom: 25px;
                }

                .caixa-alerta {
                    width: 100%;
                    background: #fff3cd;
                    border: 2px solid #ffc107;
                    border-radius: 8px;
                    padding: 20px;
                    margin-bottom: 25px;
                    text-align: left;
                }

                .caixa-alerta strong {
                    color: #856404;
                    display: block;
                    margin-bottom: 8px;
                    font-size: 15px;
                }

                .caixa-alerta p {
                    color: #856404;
                    font-size: 14px;
                    line-height: 1.6;
                    margin: 5px 0;
                }

                .secao-suporte {
                    background: #f0f0f0;
                    border-radius: 8px;
                    padding: 25px;
                    margin-bottom: 25px;
                    text-align: left;
                }

                .secao-suporte h3 {
                    color: #c0392b;
                    font-size: 16px;
                    margin-bottom: 15px;
                    text-align: center;
                }

                .contato-item {
                    display: flex;
                    align-items: center;
                    margin-bottom: 12px;
                    padding: 12px;
                    background: white;
                    border-radius: 6px;
                    transition: all 0.3s ease;
                }

                .contato-item:hover {
                    background: #e8e8e8;
                    transform: translateX(5px);
                }

                .contato-icone {
                    font-size: 20px;
                    margin-right: 12px;
                    min-width: 30px;
                }

                .contato-info {
                    flex: 1;
                    text-align: left;
                }

                .contato-info label {
                    display: block;
                    font-size: 12px;
                    color: #7f8c8d;
                    margin-bottom: 3px;
                }

                .contato-info a {
                    color: #c0392b;
                    text-decoration: none;
                    font-weight: 600;
                    font-size: 15px;
                }

                .contato-info a:hover {
                    text-decoration: underline;
                }

                .botoes {
                    display: flex;
                    gap: 12px;
                    justify-content: center;
                    flex-wrap: wrap;
                    margin-top: 25px;
                }

                button {
                    padding: 12px 28px;
                    border: none;
                    border-radius: 6px;
                    font-size: 14px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                }

                .btn-voltar {
                    background: #c0392b;
                    color: white;
                }

                .btn-voltar:hover {
                    background: #a93226;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(192, 57, 43, 0.4);
                }

                .btn-home {
                    background: #34495e;
                    color: white;
                }

                .btn-home:hover {
                    background: #2c3e50;
                    transform: translateY(-2px);
                    box-shadow: 0 5px 15px rgba(52, 73, 94, 0.4);
                }

                .codigo-erro {
                    text-align: center;
                    margin-top: 25px;
                    padding-top: 20px;
                    border-top: 1px solid #ddd;
                    font-size: 12px;
                    color: #7f8c8d;
                }

                .codigo-erro p {
                    margin: 5px 0;
                }
            </style>
        </head>

        <body>
            <div class="container-erro">
                <div class="icone-erro">❌</div>
                <h1>Boleto Não Localizado</h1>

                <div class="caixa-alerta">
                    <strong>⚠️ Atenção!</strong>
                    <p>O boleto que você está procurando não foi localizado em nosso sistema.</p>
                    <p>Por favor, entre em contato com nosso suporte para resolver este problema.</p>
                </div>

                <div class="secao-suporte">
                    <h3>📞 Entre em Contato com o Suporte</h3>

                    <!-- <div class="contato-item">
                    <div class="contato-icone">📧</div>
                    <div class="contato-info">
                        <label>Email:</label>
                        <a href="mailto:<?php echo htmlspecialchars($email_suporte); ?>">
                            <?php echo htmlspecialchars($email_suporte); ?>
                        </a>
                    </div>
                </div>
                
                <div class="contato-item">
                    <div class="contato-icone">📱</div>
                    <div class="contato-info">
                        <label>Telefone:</label>
                        <a href="tel:<?php echo preg_replace('/\D/', '', $telefone_suporte); ?>">
                            <?php echo htmlspecialchars($telefone_suporte); ?>
                        </a>
                    </div>
                </div>
                
                <div class="contato-item">
                    <div class="contato-icone">💬</div>
                    <div class="contato-info">
                        <label>WhatsApp:</label>
                        <a href="https://wa.me/55<?php echo preg_replace('/\D/', '', $whatsapp_suporte); ?>" target="_blank">
                            <?php echo htmlspecialchars($whatsapp_suporte); ?>
                        </a>
                    </div>
                </div>
            </div> -->

                    <div class="botoes">
                        <button class="btn-voltar" onclick="window.close();">
                            Fechar esta janela
                        </button>

                    </div>

                    <div class="codigo-erro">
                        <p><strong>Código do erro:</strong> <?php echo htmlspecialchars($codigo_erro); ?></p>
                        <p><strong>Data/Hora:</strong> <?php echo htmlspecialchars($data_hora); ?></p>
                    </div>
                </div>
        </body>

        </html>
<?php
    }
}    //	END OF THE CLASS
?>