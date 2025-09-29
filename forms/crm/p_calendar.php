<?php

if (!defined('ADMpath')):
    exit;
endif;

$dir = (__DIR__);
include_once($dir . "/../../../smarty/libs/Smarty.class.php");
include_once($dir . "/../../class/crm/c_contas_acompanhamento.php");
include_once($dir . "/../../class/crm/c_conta.php");

Class p_calendar extends c_contas_acompanhamento{

    private $smarty                   = NULL;
    private $submenu                  = NULL;
    private $m_id                     = NULL;
    private $m_descricao              = NULL;
    private $m_proximo_contato        = NULL;
    private $m_proximo_contato_hora   = NULL;
    private $m_atividade              = NULL;
    private $m_idPedido               = NULL;
    private $m_evento_realizado       = NULL;
    private $m_evento_realizado_hora  = NULL;
    private $m_data_contato           = NULL;
    private $m_data_contato_anterior  = NULL;
    private $m_new_data_hora          = NULL;
    public  $m_registerNewEvent       = NULL;
    private $m_date_delivery_block    = NULL;
    private $end_date                 = NULL; 
    private $start_date               = NULL;
    private $query_date               = NULL;

    function __construct(){

        //Assim obtém os dados passando pelo filtro contra INJECTION ( segurança PHP )
        $parmPost = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        $parmGet = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        // Cria uma instancia variaveis de sessao
        session_start();
        c_user::from_array($_SESSION['user_array']);

        // Cria uma instancia do Smarty
        $this->smarty = new Smarty;
        // caminhos absolutos para todos os diretorios do Smarty
        $this->smarty->template_dir = ADMraizFonte . "/template/crm";
        $this->smarty->compile_dir = ADMraizCliente . "/smarty/templates_c/";
        $this->smarty->config_dir = ADMraizCliente . "/smarty/configs/";
        $this->smarty->cache_dir = ADMraizCliente . "/smarty/cache/";

        $this->smarty->assign('pathJs',  ADMhttpBib.'/js');
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('raizCliente', $this->raizCliente);

        $this->m_submenu = (isset($parmGet['submenu']) ? $parmGet['submenu'] : (isset($parmPost['submenu']) ? $parmPost['submenu'] : ''));
        $this->m_id = (isset($parmGet['id_reg']) ? $parmGet['id_reg'] : (isset($parmPost['id_reg']) ? $parmPost['id_reg'] : ''));
        $this->m_descricao = (isset($parmGet['descReg']) ? $parmGet['descReg'] : (isset($parmPost['descReg']) ? $parmPost['descReg'] : ''));

        $this->m_proximo_contato = (isset($parmGet['proximoContato']) ? $parmGet['proximoContato'] : (isset($parmPost['proximoContato']) ? $parmPost['proximoContato'] : ''));
        $this->m_proximo_contato_hora = (isset($parmGet['proximoContatoHora']) ? $parmGet['proximoContatoHora'] : (isset($parmPost['proximoContatoHora']) ? $parmPost['proximoContatoHora'] : ''));

        $this->m_data_contato_anterior = (isset($parmGet['data_contato_anterior']) ? $parmGet['data_contato_anterior'] : (isset($parmPost['data_contato_anterior']) ? $parmPost['data_contato_anterior'] : ''));
        $this->m_atividade = (isset($parmGet['atividade']) ? $parmGet['atividade'] : (isset($parmPost['atividade']) ? $parmPost['atividade'] : ''));
        $this->m_idPedido = (isset($parmGet['idPedido']) ? $parmGet['idPedido'] : (isset($parmPost['idPedido']) ? $parmPost['idPedido'] : ''));

        $this->m_evento_realizado = (isset($parmGet['evento_realizado']) ? $parmGet['evento_realizado'] : (isset($parmPost['evento_realizado']) ? $parmPost['evento_realizado'] : ''));
        $this->m_evento_realizado_hora = (isset($parmGet['evento_realizado_hora']) ? $parmGet['evento_realizado_hora'] : (isset($parmPost['evento_realizado_hora']) ? $parmPost['evento_realizado_hora'] : ''));
        
        $this->m_data_contato = (isset($parmGet['dataContato']) ? $parmGet['dataContato'] : (isset($parmPost['dataContato']) ? $parmPost['dataContato'] : ''));
        $this->m_registerNewEvent = (isset($parmGet['registerNewEvent']) ? $parmGet['registerNewEvent'] : (isset($parmPost['registerNewEvent']) ? $parmPost['registerNewEvent'] : ''));

        //param da busca das datas bloqueadas para entrega - origem json objeto
        $this->end_date = (isset($parmPost['end_date']) ? $parmPost['end_date'] : null);
        $this->start_date = (isset($parmPost['start_date']) ? $parmPost['start_date'] : null);

    }

    public function controle() {
        switch ($this->m_submenu) {
            case 'desenha_calendario':
                $this->desenhaCalendar();
            break;
            case 'atualiza_acomp': //update origem Ajax
                $objAcomp = new c_contas_acompanhamento();

                //cadastra novo evento
                if($this->m_registerNewEvent == 'S'){
                    //consulta
                    $objAcomp->setId($this->m_id);
                    $newregister = $objAcomp->select_pessoaAcomp();
                    //set novo registro
                    $objAcomp->setPessoa($newregister[0]['PESSOA']);
                    $objAcomp->setIdPedido($newregister[0]['PEDIDO_ID']);
                    $objAcomp->setDataContato(null);    
                    $objAcomp->setAcao($newregister[0]['ATIVIDADE']);
                    $objAcomp->setResultContato(null);
                    $objAcomp->setVendedorAcomp($this->m_userid);
                    $objAcomp->setProximoContato(null);
                    $objAcomp->setUsrIC($this->m_userid);

                    //trata data e hora
                     if($this->m_proximo_contato !== '' and $this->m_proximo_contato_hora !== ''){
                        //hora
                        $n_hora = isset($this->m_proximo_contato_hora) ? $this->m_proximo_contato_hora : "00:00";
                        //data
                        $f_data = explode('/', $this->m_proximo_contato);
                        $n_data = $f_data[2] . "-" . $f_data[1] . "-" . $f_data[0];

                        $this->m_new_data_hora = $n_data .'T'. $n_hora . ':00'; 
                    }
                    $objAcomp->setDateInsert($this->m_new_data_hora);
                    //register
                    $objAcomp->incluiPessoaAcomp();

                }//Fim cadastra novo evento

                //set form
                $this->setId($this->m_id);
                $this->setProximoContato($this->m_proximo_contato.$this->m_proximo_contato_hora);
                $this->setAcao($this->m_atividade);
                $this->setResultContato($this->m_descricao);
                $this->setUsrIC($this->m_userid);

                //se o evento foi realizado o status altera para baixado    
                if(($this->m_evento_realizado !== '') || ($this->m_evento_realizado !== '')){
                    $this->setDateChange($this->m_evento_realizado.$this->m_evento_realizado_hora);
                    $this->setDataContato($this->m_evento_realizado.$this->m_evento_realizado_hora);
                    $this->setStatus('B');
                }else{
                    $this->setStatus('A');
                }

                //set origem database
                $origemBd = $this->select_pessoaAcomp();
                $this->setIdPedido($origemBd[0]['ID']);
                $this->setVendedorAcomp($origemBd[0]['USRVENDEDOR']);
                $this->setVeiculo($origemBd[0]['VEICULO']);
                $this->setOrigem($origemBd[0]['ORIGEM']);
                $this->setDestino($origemBd[0]['DESTINO']);
                $this->setKM($origemBd[0]['KM']);
                

                if($this->alteraPessoaAcomp() == true){   
                    echo 'atualizado';
                }else{
                    echo 'naoAtualizado';
                }
            break;
            case 'busca_datas_bloqueadas':
                $objAcomp = new c_contas_acompanhamento();

                $result = $objAcomp->buscaDatasBloqueadas($this->start_date, $this->end_date);

                if($result !== false){ //ocorreu tudo certo
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }else{
                    header('Content-Type: application/json; charset=utf-8');
                    echo json_encode($result);
                }
            break;
            case 'query_combo_atividade':
                $this->queryCombo();
            break;
            default:
                $this->desenhaCalendar();
        }
    }

    public function desenhaCalendar(){

        $this->smarty->assign('pathCliente', ADMhttpCliente);
        $this->smarty->assign('pathRaiz', ADMhttpBib);
        $this->smarty->assign('bootstrap', ADMbootstrap);
        $this->smarty->assign('pathJs',  ADMhttpBib . '/js');
        
        $vertodoslancamentos = $this->verificaDireitoUsuario('PEDVERTODOSLANCAMENTOS', 'S', 'N');
        $this->smarty->assign('vertodoslancamentos', $vertodoslancamentos);
        
        // COMBOBOX VENDEDOR
        $consulta = new c_banco();
        //$sql = "SELECT USUARIO, NOME FROM AMB_USUARIO WHERE TIPO='V'";
        $sql = "SELECT USUARIO, NOME FROM AMB_USUARIO ";
        $sql.= "WHERE (NOME != 'ADMIN' ) and (NOME != 'GRUPO GERAL' ) and (SITUACAO != 'I') and (TIPO != 'Z')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado ?? [];
        for ($i = 0; $i < count($result); $i++) {
            $vendedor_ids[$i + 1] = $result[$i]['USUARIO'];
            $vendedor_names[$i] = $result[$i]['NOME'];
        }

        $this->smarty->assign('vendedor_ids', $vendedor_ids);
        $this->smarty->assign('vendedor_names', $vendedor_names);
        $this->smarty->assign('vendedor_id', $this->m_userid);      
        
        $this->smarty->display('calendar.tpl');
    }

    public function queryCombo(){
        //#################### COMBO ACAO ####################
        $consulta = new c_banco();
        $sql = "select atividade as id, descricao from fat_atividade_acomp";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $acao_ids[$i] = $result[$i]['ID'];
            $acao_names[$i] = $result[$i]['DESCRICAO'];
        }
        echo json_encode($result);
    }
}

$calendar = new p_calendar();
$calendar->controle();

?>    