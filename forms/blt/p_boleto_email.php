<?php
/**
 * @package   astecv3
 * @name      p_boleto_email
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy dos Santos Mello <jhon.kened11@gmail.com>
 * @date      20/09/2025
 */
if (!defined('ADMpath')): exit;
endif;

$dir = (__DIR__);
include_once($dir."/../../class/blt/c_boleto.php");
include_once($dir."/../../class/crm/c_conta.php");
include_once($dir."/../../class/fin/c_lancamento.php");
include_once($dir."/../../class/fin/c_conta_banco.php");
include_once($dir."/../../class/est/c_nota_fiscal.php");
include_once($dir."/../../forms/blt/p_boleto_pdf.php");
include_once($dir."/../../bib/c_mail.php");
include_once($dir."/../../bib/c_database_pdo.php");

//Class p_boleto_email
Class p_boleto_email extends c_boleto {

    private $m_submenu = NULL;
    private $m_letra = NULL;
    public $smarty = NULL;

    /**
     * Construtor da classe p_boleto_email
     * 
     * Inicializa a classe obtendo parâmetros POST e GET de forma segura,
     * configura a sessão do usuário e define variáveis de controle.
     * 
     * @return void
     * @author ADMSistema
     * @since 4.5
     */
    function __construct(){
        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);  
    
        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // inicializa variaveis de controle
        $this->m_submenu=(isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_letra=(isset($parmGet['letra']) ? $parmGet['letra'] : (isset($parmPost['letra']) ? $parmPost['letra'] : ''));
        $this->m_opcao=(isset($parmGet['opcao']) ? $parmGet['opcao'] : (isset($parmPost['opcao']) ? $parmPost['opcao'] : ''));
                
        $this->m_par = explode("|", $this->m_letra);
    }

    
    /**
     * Valida se um endereço de email é válido
     * 
     * Remove espaços em branco e utiliza o filtro nativo do PHP
     * para validar o formato do endereço de email.
     * Suporta múltiplos emails separados por ponto e vírgula (;)
     * 
     * @param string $email Endereço de email a ser validado (pode conter múltiplos separados por ;)
     * @return bool True se todos os emails forem válidos, false caso contrário
     * @author ADMSistema
     * @since 4.5
     */
    function validarEmail($email) {
        // Remove espaços em branco
        $email = trim($email);
        
        // Verifica se não está vazio
        if (empty($email)) {
            return false;
        }
        
        // Verifica se há múltiplos emails separados por ;
        if (strpos($email, ';') !== false) {
            $emails = explode(';', $email);
            foreach ($emails as $emailIndividual) {
                $emailIndividual = trim($emailIndividual);
                if (!empty($emailIndividual) && filter_var($emailIndividual, FILTER_VALIDATE_EMAIL) === false) {
                    return false; // Se algum email for inválido, retorna false
                }
            }
            return true; // Todos os emails são válidos
        }
        
        // Email único - filtro nativo do PHP
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }


    /**
     * Busca configurações de email do usuário logado
     * 
     * Consulta a tabela AMB_USUARIO para obter as configurações de SMTP
     * do usuário logado. Retorna configurações padrão caso não encontre.
     * 
     * @return array Array associativo com as configurações de email:
     *               - host: Servidor SMTP
     *               - remetente: Email do remetente
     *               - senha: Senha do email
     *               - porta: Porta do servidor SMTP
     * @throws Exception Em caso de erro na consulta ao banco
     * @author ADMSistema
     * @since 4.5
     */
    private function getConfiguracaoEmail() {
        try {
            $pdo = new c_banco_pdo();
            
            // Busca configurações de email do usuário logado
            $sql = "SELECT EMAIL, SMTP, EMAILSENHA, PORT 
                    FROM AMB_USUARIO 
                    WHERE USUARIO = :usuario";
            
            $pdo->prepare($sql);
            $pdo->bindParam(':usuario', $this->m_userid, PDO::PARAM_STR);
            $pdo->execute();
            
            $config = $pdo->fetch();
            
            // Configurações padrão se não encontradas
            $hostSMTP = !empty($config['SMTP']) ? $config['SMTP'] : "mail.admsistema.com.br";
            $userSMTP = !empty($config['EMAIL']) ? $config['EMAIL'] : 'suporte@admsistema.com.br';
            $passSMTP = !empty($config['EMAILSENHA']) ? $config['EMAILSENHA'] : "Sup=2025";
            $portSMTP = !empty($config['PORT']) ? $config['PORT'] : 587;
            
            return [
                'host' => $hostSMTP,
                'remetente' => $userSMTP,
                'senha' => $passSMTP,
                'porta' => $portSMTP
            ];
            
        } catch (Exception $e) {
            error_log("Erro ao buscar configuração de email: " . $e->getMessage());
            
            // Configurações padrão em caso de erro
            return [
                'host' => 'erro',
                'remetente' => 'erro',
                'nome_remetente' => 'erro',
                'senha' => 'erro',
                'porta' => 'erro'
            ];
        }
    }


    /**
     * Registra log de erro de envio de email/boleto
     * 
     * Insere um registro na tabela AMB_LOG_EMAIL para controle
     * de erros no envio de emails com DANFE e boletos.
     * 
     * @param string $numero_nota Número da nota fiscal
     * @param int $id_nota ID da nota fiscal no sistema
     * @param string $erro Mensagem de erro detalhada
     * @param mixed $usuario Identificador do usuário responsável (opcional)
     * @return bool True se inseriu com sucesso, false caso contrário
     * @throws Exception Em caso de erro na inserção no banco
     * @author ADMSistema
     * @since 4.5
     */
    public static function registraLogErro($numero_nota, $id_nota, $erro, $usuario = null) {
        try {
            $pdo = new c_banco_pdo();
            
            $sql = "INSERT INTO AMB_LOG_EMAIL (NUMERO_NOTA, ID_NOTA, USUARIO, ERRO, DATA_HORA) 
                    VALUES (:numero_nota, :id_nota, :usuario, :erro, NOW())";
            
            $pdo->prepare($sql);
            $pdo->bindParam(':numero_nota', $numero_nota, PDO::PARAM_STR);
            $pdo->bindParam(':id_nota', $id_nota, PDO::PARAM_INT);
            $pdo->bindParam(':usuario', $usuario, PDO::PARAM_STR);
            $pdo->bindParam(':erro', $erro, PDO::PARAM_STR);
            
            return $pdo->execute();
            
        } catch (Exception $e) {
            error_log("Erro ao registrar log de email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se existem boletos para o documento
     * 
     * Consulta se há lançamentos financeiros (boletos) associados
     * ao documento especificado para a pessoa informada.
     * 
     * @param string $documento Número do documento (nota fiscal, pedido, etc.)
     * @param string $pessoa ID da pessoa/cliente no sistema
     * @param string $origem Origem do documento (padrão: 'PED' para pedidos)
     * @return bool True se existem boletos, false caso contrário
     * @throws Exception Em caso de erro na consulta
     * @author ADMSistema
     * @since 4.5
     */
    public function verificaBoletos($documento, $pessoa, $origem = 'PED') {
        try {

            $lanc = $this->selectLancBoleto($documento, $pessoa, '', '');
            return !empty($lanc);

        } catch (Exception $e) {
            
            error_log("Erro ao verificar boletos: " . $e->getMessage());
            return false;
        }
    }


    /**
     * Envia email com DANFE e boletos
     * 
     * Função principal que envia por email o XML da NFe, DANFE em PDF
     * e boletos de cobrança (quando existirem) para o cliente.
     * Inclui validações de email, geração de arquivos e controle de logs.
     * 
     * @param string $id_nota_fiscal ID da nota fiscal no sistema
     * @param string $numero_nota_fiscal Número da nota fiscal
     * @param string $pessoa ID da pessoa/cliente destinatário
     * @param string $numero_pedido Número do pedido
     * @param string $origem Origem do documento (PED ou NFS)
     * @return string|bool Mensagem de sucesso ou erro do envio, false em caso de falha
     * @throws Exception Em caso de erro no processo de envio
     * @author ADMSistema
     * @since 4.5
     * 
     * @example
     * $email = new p_boleto_email();
     * $resultado = $email->sendDocumentsEmail('123', '000001', '456');
     * if ($resultado !== false) {
     *     echo $resultado; // "email XML/DANFE e Boletos enviados com sucesso!!!"
     * }
     */
    public function sendDocumentsEmail($id_nota_fiscal, $numero_nota_fiscal, $pessoa, $numero_pedido = null)
    {
        try {
            // Busca dados da pessoa
            $obj_conta = new c_conta();
            $obj_conta->setId($pessoa);
            $conta = $obj_conta->select_conta();
            
            // Verifica se o email do cliente está vazio
            if (empty($conta[0]['EMAILNFE'])) {

                $return = array(   
                    'success' => false,
                    'mensagem' => 'Erro no envio de email',
                    'erro' => [ 'Email do cliente não encontrado, verifique o campo EMAIL NFE no cadastro de conta e clique em enviar novamente.' ]
                );

                // Registra log de erro
                $this->registraLogErro($numero_nota_fiscal, $id_nota_fiscal, 'Email do cliente não encontrado', $this->m_userid);

                return $return;
            }

            // Verifica se o email do cliente é válido
            if(!$this->validarEmail($conta[0]['EMAILNFE'])) {

                $return = array(   
                    'success' => false,
                    'mensagem' => 'Erro no envio de email',
                    'erro' => [ 'Email do cliente inválido' ]
                );

                // Registra log de erro
                $this->registraLogErro($numero_nota_fiscal, $id_nota_fiscal, 'Email do cliente inválido', $this->m_userid);

                return $return;
            }

            // Busca dados da nota fiscal para obter informações necessárias
            $obj_nota_fiscal = new c_nota_fiscal();
            $obj_nota_fiscal->setId($id_nota_fiscal);
            $nota_fiscal = $obj_nota_fiscal->select_nota_fiscal();
            
            if (empty($nota_fiscal)) {
                $this->registraLogErro($numero_nota_fiscal, $id_nota_fiscal, 'Nota fiscal não encontrada', $this->m_userid);

                $return = array(   
                    'success' => false,
                    'mensagem' => 'Erro no envio de email',
                    'erro' => [ 'Nota fiscal não encontrada' ]
                );

                return $return;
            }
            
            // Extrai dados da NFe
            $data_hora_emissao = $nota_fiscal[0]["EMISSAO"];
            $nome_emitente     = $conta[0]['NOME'];
            $chave             = $nota_fiscal[0]["CHNFE"];
            $serie             = $nota_fiscal[0]["SERIE"];
            
            $nf_extensao_pdf = '-danfe.pdf';
            $nf_extensao     = '-nfe.xml';
            $data_emissao    = date('d-m-Y', strtotime($data_hora_emissao));
            $ano             = date('Y', strtotime($data_hora_emissao));
            $mes             = date('m', strtotime($data_hora_emissao));
            $ano_mes         = $ano . $mes;


            // Monta diretórios dos arquivos
            $ambiente_situacao = ADMambDesc; // Padrão do sistema

            define('BASE_DIR_NFE_AMB', ADMnfe . '/' . $this->m_empresaid . '/' . ADMambDesc);
            $path =  BASE_DIR_NFE_AMB;

            //define('BASE_DIR_ENVIADA_APROVADAS', $path . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR . 'aprovadas' . DIRECTORY_SEPARATOR . $ano_mes . DIRECTORY_SEPARATOR . $chave . $nf_extensao);
            //HOMOLOGACAO
            define('BASE_DIR_ENVIADA_APROVADAS', $path . DIRECTORY_SEPARATOR . 'enviadas' . DIRECTORY_SEPARATOR . 'aprovadas' . DIRECTORY_SEPARATOR . $ano_mes . DIRECTORY_SEPARATOR . $chave . $nf_extensao);
            define('BASE_DIR_PDF', $path . DIRECTORY_SEPARATOR . 'pdf' . DIRECTORY_SEPARATOR . $ano_mes . DIRECTORY_SEPARATOR . $chave . $nf_extensao_pdf);

            $path_xml = BASE_DIR_ENVIADA_APROVADAS;
            $path_pdf = BASE_DIR_PDF;

            // Busca lançamentos financeiros para esta nota fiscal
            $lanc = $this->selectAllBoletos((int)$numero_pedido, (int)$pessoa, 'PED');

            $path_boleto = null;
            $temBoleto = false;

            // Verifica se tem lançamentos do tipo boleto (TIPODOCTO = 'B')
            // Se não tiver erro explícito E tiver lançamentos válidos, gera o boleto
            if (!empty($lanc) && is_array($lanc) && !isset($lanc['status'])) {
                // Tem lançamentos válidos do tipo boleto
                $temBoleto = true;
                
                // Gera PDF dos boletos usando a classe p_boleto_pdf
                $obj_pdf = new p_boleto_pdf();
                $pdf_content = $obj_pdf->geraPdfBoletos($lanc);
                
                // Verifica se o PDF foi gerado com sucesso
                if (is_array($pdf_content) && isset($pdf_content['status']) && $pdf_content['status'] == false) {
                    // Erro ao gerar PDF - não anexa boleto
                    error_log("Erro ao gerar PDF de boletos para pedido {$numero_pedido}: " . $pdf_content['msg']);
                    $temBoleto = false;
                } else {
                    // PDF gerado com sucesso - salva temporariamente
                    $path_boleto = tempnam(sys_get_temp_dir(), 'boleto_nfe_') . '.pdf';
                    file_put_contents($path_boleto, $pdf_content);
                }
            }


            $email = strtolower($conta[0]['EMAILNFE']);
            $cc = null; // Pode ser implementado posteriormente se necessário
            $mail = new admMail;
            $assunto_email = $temBoleto ? "NF-e - envio XML/DANFE e Boletos" : "NF-e - envio XML/DANFE";
            $body_email = ''; // Usa corpo padrão

            if ($body_email == '') 
            {
                $body = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; color: #333;'>
                    <div style='background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 20px; text-align: center; color: white; border-radius: 8px 8px 0 0;'>
                        <h2 style='margin: 0; font-weight: 300;'>Nota Fiscal Eletrônica</h2>
                    </div>
                    
                    <div style='padding: 30px; background: #f8f9fa; border-radius: 0 0 8px 8px;'>
                        <p style='font-size: 16px; line-height: 1.5; margin-bottom: 20px;'>
                            Olá! Você recebeu uma <strong>Nota Fiscal Eletrônica</strong> de <strong> $obj_conta->m_empresanome </strong>.
                        </p>
                        
                        <div style='background: white; padding: 20px; border-radius: 6px; border-left: 4px solid #667eea; margin: 20px 0;'>
                            <h3 style='margin-top: 0; color: #667eea;'>📋 Detalhes da Nota</h3>
                            <p style='margin: 8px 0;'><strong>Número:</strong> {$numero_nota_fiscal}</p>
                            <p style='margin: 8px 0;'><strong>Série:</strong> {$serie}</p>
                            <p style='margin: 8px 0;'><strong>Data de Emissão:</strong> {$data_emissao}</p>
                            <p style='margin: 8px 0;'><strong>Ambiente:</strong> {$ambiente_situacao}</p>
                        </div>";
                
                // Adiciona seção de boletos se existirem
                if ($temBoleto) {
                    $body .= "
                        <div style='background: #fff3cd; padding: 15px; border-radius: 6px; border-left: 4px solid #ffc107; margin: 20px 0;'>
                            <h4 style='margin-top: 0; color: #856404;'>💳 Pagamento</h4>
                            <p style='margin: 0; color: #856404;'>
                                <strong>Atenção:</strong> Este email contém os boletos de pagamento. 
                                Imprima e pague até a data de vencimento.
                            </p>
                        </div>";
                }
                
                $body .= "
                        <div style='background: white; padding: 20px; border-radius: 6px; margin: 20px 0;'>
                            <h3 style='color: #667eea; margin-top: 0;'>📦 O que fazer agora?</h3>
                            <ol style='padding-left: 20px; line-height: 1.6;'>
                                <li>Junto com a mercadoria, você receberá o <strong>DANFE</strong> (documento impresso)</li>
                                <li>Guarde este email e o DANFE para seus registros fiscais</li>
                                <li>Para verificar a autenticidade, acesse: 
                                    <a href='http://www.nfe.fazenda.gov.br' style='color: #667eea;'>www.nfe.fazenda.gov.br</a>
                                </li>
                            </ol>
                        </div>
                        
                        <div style='background: #e3f2fd; padding: 15px; border-radius: 6px; font-size: 14px; color: #1565c0;'>
                            <strong>💡 Lembre-se:</strong> A NFe é um documento totalmente digital e tem a mesma validade jurídica 
                            que uma nota fiscal tradicional, garantida por assinatura digital.
                        </div>
                        
                        <hr style='border: none; height: 1px; background: #ddd; margin: 30px 0;'>
                        
                        <p style='font-size: 14px; color: #666; text-align: center; margin: 0;'>
                            Este é um email automático. Em caso de dúvidas, entre em contato conosco.
                        </p>
                    </div>
                </div>

                <div style='text-align: center; padding: 15px; background: #f1f1f1; border-radius: 0 0 8px 8px; font-size: 12px; color: #777;'>
                    Desenvolvido por <strong style='color: #667eea;'>ADMSistema</strong>
                </div>"
                ;
            } else {

                $body = nl2br(htmlspecialchars($body_email));
                
                // Adiciona informação sobre boletos se existirem e corpo customizado
                if ($temBoleto) {
                    $body .= "<br><br><strong>ATENÇÃO:</strong> Este email também contém os boletos de pagamento referentes a esta nota fiscal.";
                }
            }

            // Busca configurações de email do sistema
            $configEmail = $this->getConfiguracaoEmail();

            // Envia email com XML, DANFE e boletos (se existirem)
            // Suporta múltiplos destinatários separados por ; no campo EMAILNFE
            $result = $mail->SendMail2(
                $configEmail['host'],
                $configEmail['remetente'],
                $obj_conta->m_empresanome . " - NF-e",
                $configEmail['senha'],
                $body,
                $assunto_email,
                $email,
                "",
                $configEmail['remetente'],
                $obj_conta->m_empresanome . " - NF-e",
                $path_xml,
                $path_pdf,
                $path_boleto,
                $configEmail['porta'],
                'several'  // Parâmetro para enviar para múltiplos destinatários
            );

            // Remove arquivo temporário do boleto se foi criado
            if ($path_boleto && file_exists($path_boleto)) {
                unlink($path_boleto);
            }

            if ($result['success'] == false){

                $mensagem = $result['message'];
                
                // Registra log de erro
                $this->registraLogErro($numero_nota_fiscal, $id_nota_fiscal, $mensagem . " - Detalhes: " . $result, $this->m_userid);
                return ['success' => false, 'mensagem' => $mensagem];

            } else {

                $mensagem = 'Email XML/DANFE enviado com sucesso!';

                if ($temBoleto) {
                    $mensagem = 'Email XML/DANFE e Boletos enviados com sucesso!';
                }

                // Retorna mensagem de sucesso
                return ['success' => true, 'mensagem' => $mensagem];
            }

        } catch (Exception $e) {
            $mensagem = 'Erro -> ' . $e->getMessage();

            // Registra log de erro
            $this->registraLogErro($numero_nota_fiscal, $id_nota_fiscal, $mensagem, $this->m_userid);

            // Retorna mensagem de erro
            return ['success' => false, 'mensagem' => $mensagem];
        }
    }

}

?>

