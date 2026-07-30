<?php

/**
 * @package   astecv3
 * @name      c_usuario
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Lucas tortola da Silva Bucko<lucas.tortola@admservice.com.br>
 * @date      20/08/2017
 */

$dirUtil = dirname(__FILE__);
include_once($dirUtil . "/../../bib/c_user.php");
include_once($dirUtil . "/../../class/crm/c_conta.php");
include_once($dirUtil . "/c_usuario_autoriza.php");

//Class C_USUARIO
Class c_usuario extends c_user {

    /** CLASSE C_USUARIO - OBJETOS TABLE AMB_USUARIO   */
    private $usuario            = NULL; // INT(11)
    private $login              = NULL; // VARCHAR(40)
    private $nomeReduzido       = NULL; // VARCHAR(15)
    private $cliente            = NULL; // INT(11)
    private $nomePessoa         = NULL; // VARCHAR(50)
    private $senha              = NULL; // VARCHAR(15)
    private $situacao           = NULL; // CHAR(1)
    private $tipo               = NULL; // CHAR(1)
    private $conta              = NULL; // SMALLINT(6)
    private $salario            = NULL; // DECIMAL(9,2)
    private $encargos           = NULL; // DECIMAL(5,2)
    private $generoPgto         = NULL; // VARCHAR(4)
    private $ccustoPgto         = NULL; // INT(11)
    private $comissaoFatura     = NULL; // DECIMAL(5,2)
    private $comissaoReceb      = NULL; // DECIMAL(5,2)
    private $grupo              = NULL; // INT(11)
    private $smtp               = NULL;
    private $email              = NULL;
    private $emailsenha         = NULL;
    private $empresa            = NULL;
// ###############################################################
// #################### INICIO GETS E SETS #######################    

    public function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    public function getUsuario() {
        return $this->usuario;
    }

    public function setLogin($login) {
        $this->login = $login;
    }

    public function getLogin() {
        return $this->login;
    }

    public function setNomeReduzido($nomeReduzido) {
        $this->nomeReduzido = $nomeReduzido;
    }

    public function getNomeReduzido() {
        return $this->nomeReduzido;
    }

    public function setcliente($cliente) {
        $this->cliente = $cliente;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function setPessoaNome($nome = null) {
        if ($nome !== null) {
            $this->nomePessoa = $nome;
            return;
        }
        $this->nomePessoa = '';
        if ((int) $this->getCliente() <= 0) {
            return;
        }
        $cliente = new c_conta();
        $cliente->setId($this->getCliente());
        $reg_nome = $cliente->select_conta();
        if (is_array($reg_nome) && isset($reg_nome[0]['NOME'])) {
            $this->nomePessoa = $reg_nome[0]['NOME'];
        }
    }

    public function getPessoaNome() {
        return $this->nomePessoa;
    }

    public function setsenha($senha) {
        $this->senha = $senha;
    }

    public function getsenha() {
        return $this->senha;
    }

    public function setsituacao($situacao) {
        $this->situacao = $situacao;
    }

    public function getsituacao() {
        return $this->situacao;
    }

    public function settipo($tipo) {
        $this->tipo = $tipo;
    }

    public function gettipo() {
        return $this->tipo;
    }

    public function setconta($conta) {
        $this->conta = $conta;
    }

    public function getconta() {
        return $this->conta;
    }

    public function setsalario($salario) {
        $this->salario = $salario;
    }

    public function getsalario($format = null) {
        if ($format == 'F') {
            return number_format((float)$this->salario, 2, ',', '.');
        } else {
            if ($this->salario != null) {
                $num = str_replace('.', '', $this->salario);
                $num = str_replace(',', '.', $num);
                return $num;
            } else {
                return 0;
            }
        }
    }

    public function getEmpresa() {
        return $this->empresa;
    }


    public function setEmpresa($empresa) {
        $this->empresa = $empresa;
    }

    public function setencargos($encargos) {
        $this->encargos = $encargos;
    }

    public function getencargos() {
        return $this->encargos;
    }

    public function setgeneroPgto($generoPgto) {
        $this->generoPgto = $generoPgto;
    }

    public function getgeneroPgto() {
        return $this->generoPgto;
    }

    public function setccustoPgto($ccustoPgto) {
        $this->ccustoPgto = $ccustoPgto;
    }

    public function getccustoPgto() {
        return $this->ccustoPgto;
    }

    public function setcomissaoFatura($comissaoFatura) {
        $this->comissaoFatura = $comissaoFatura;
    }

    public function getcomissaoFatura($format = null) {
        if ($format == 'F') {
            return number_format((float) $this->comissaoFatura, 2, ',', '.');
        }
        if ($format == 'B') {
            if ($this->comissaoFatura != null && $this->comissaoFatura !== '') {
                $num = str_replace('.', '', $this->comissaoFatura);
                $num = str_replace(',', '.', $num);
                return $num;
            }
            return 0;
        }
        return $this->comissaoFatura;
    }

    public function setcomissaoReceb($comissaoReceb) {
        $this->comissaoReceb = $comissaoReceb;
    }

    public function getcomissaoReceb($format = null) {
        if ($format == 'F') {
            return number_format((float) $this->comissaoReceb, 2, ',', '.');
        }
        if ($format == 'B') {
            if ($this->comissaoReceb != null && $this->comissaoReceb !== '') {
                $num = str_replace('.', '', $this->comissaoReceb);
                $num = str_replace(',', '.', $num);
                return $num;
            }
            return 0;
        }
        return $this->comissaoReceb;
    }

    public function setGrupo($grupo) {
        $this->grupo = $grupo;
    }

    public function getGrupo() {
        return $this->grupo;
    }

    public function setSmtp($smtp) {
        $this->smtp = $smtp;
    }

    public function getSmtp() {
        return $this->smtp;
    }
        
    public function setEmail($email) {
        $this->email = $email;
    }

    public function getEmail() {
        return $this->email;
    }
    
    public function setEmailSenha($emailsenha) {
        $this->emailsenha = $emailsenha;
    }

    public function getEmailSenha() {
        return $this->emailsenha;
    }
    
// ####################### FIM GETS E SETS #######################    
// ###############################################################

    /**
     * Sets de todos os objetos da classe
     * @name AmbUsuario
     */
    public function AmbUsuario() {
        $usuario = $this->select_usuario_matricula();
        $this->setUsuario($usuario[0]['USUARIO']);
        $this->setLogin($usuario[0]['NOME']);
        $this->setNomeReduzido($usuario[0]['NOMEREDUZIDO']);
        $this->setcliente($usuario[0]['CLIENTE']);
        $this->setPessoaNome();
        $this->setsenha($usuario[0]['SENHA']);
        $this->setsituacao($usuario[0]['SITUACAO']);
        $this->settipo($usuario[0]['TIPO']);
        $this->setconta($usuario[0]['CONTA']);
        $this->setsalario($usuario[0]['SALARIO']);
        $this->setencargos($usuario[0]['ENCARGOS']);
        $this->setgeneroPgto($usuario[0]['GENEROPGTO']);
        $this->setccustoPgto($usuario[0]['CCUSTOPGTO']);
        $this->setcomissaoFatura($usuario[0]['COMISSAOFATURA']);
        $this->setcomissaoReceb($usuario[0]['COMISSAORECEB']);
        $this->setGrupo($usuario[0]['GRUPO']);
        $this->setSmtp($usuario[0]['SMTP']);
        $this->setEmail($usuario[0]['EMAIL']);
        $this->setEmailSenha($usuario[0]['EMAILSENHA']);
    } // AmbUsuario
    
    /**
     * Verifica se existe usuario cadastrado de acordo com o cliente
     * @name existeCliente
     * @param INT getCliente pessoa que esta cadastrado na table FIN_CLIENTE
     * @return boolean true caso retorne valor
     */
    public function existeCliente() {
        $sql = "SELECT * ";
        $sql .= "FROM amb_usuario ";
        $sql .= "WHERE (cliente = '" . $this->getcliente() . "')";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }//fim existeCliente

    /**
     * Verifica se existe usuario com a mesma matricula
     * @name existeUsuario
     * @param INT getUsuario Chave primaria da table
     * @return boolean true caso retorne valor
     */
    public function existeUsuario() {

        $sql = "SELECT * ";
        $sql .= "FROM amb_usuario ";
        $sql .= "WHERE (usuario = '" . $this->getUsuario() . "')";
        //  echo strtoupper($sql)."<br>";

        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return is_array($banco->resultado);
    }//fim existeUsuario

    /**
     * Consulta na tabela trazendo todos as colunas de acordo com o cliente
     * @name select_usuario_cliente
     * @param INT getCliente Pessoa da table CLiente
     * @return ARRAY com todos os campos do banco
     */
    public function select_usuario_cliente() {
        $sql = "SELECT  * ";
        $sql .= "FROM amb_usuario ";
        $sql .= "WHERE ( cliente= '" . $this->getcliente() . "') ";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_usuario

    /**
     * Consulta na table trazendo todas as colunas de acordo com a matricula
     * @name select_usuario_matricula
     * @param INT Usuario Chave primaria
     * @return ARRAY com todos os campos do banco
     */
    public function select_usuario_matricula() {
        $sql = "SELECT  * ";
        $sql .= "FROM amb_usuario ";
        $sql .= "WHERE ( usuario = '" . $this->getUsuario() . "') ";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco();
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_usuario_matricula

    /**
     * Lista usuários para a tela de consulta (campos usados no template).
     * @name select_usuario_geral
     */
    public function select_usuario_geral() {
        $sql = "SELECT u.usuario, u.nomereduzido, c.nome AS nomeusuario, ";
        $sql .= "s.padrao AS descSituacao, t.padrao AS descTipo, g.nomereduzido AS nomeGrupo ";
        $sql .= "FROM amb_usuario u ";
        $sql .= "LEFT JOIN amb_ddm s ON ((s.tipo = u.situacao) AND (s.alias='AMB_MENU') AND (s.campo='SituacaoUsuario')) ";
        $sql .= "LEFT JOIN amb_ddm t ON ((t.tipo = u.tipo) AND (t.alias='AMB_MENU') AND (t.campo='TipoUsuario')) ";
        $sql .= "LEFT JOIN fin_cliente c ON (u.cliente = c.cliente) ";
        $sql .= "LEFT JOIN amb_usuario g ON (g.usuario = u.grupo AND g.tipo = 'Z') ";
        $sql .= "ORDER BY u.situacao, u.tipo, c.nome";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_usuario_geral

    /**
     * Consulta de acordo com $letra
     * @name select_usuario_letra
     * @param STRING $letra inicio do nome para pesquisa
     * @return ARRAY com todos os campos do banco
     */
    public function select_usuario_letra($letra) {
        $sql = "SELECT u.usuario, u.nomereduzido, c.nome AS nomeusuario, ";
        $sql .= "s.padrao AS descSituacao, t.padrao AS descTipo, g.nomereduzido AS nomeGrupo ";
        $sql .= "FROM amb_usuario u ";
        $sql .= "LEFT JOIN amb_ddm s ON ((s.tipo = u.situacao) AND (s.alias='AMB_MENU') AND (s.campo='SituacaoUsuario')) ";
        $sql .= "LEFT JOIN amb_ddm t ON ((t.tipo = u.tipo) AND (t.alias='AMB_MENU') AND (t.campo='TipoUsuario')) ";
        $sql .= "INNER JOIN fin_cliente c ON (u.cliente = c.cliente) ";
        $sql .= "LEFT JOIN amb_usuario g ON (g.usuario = u.grupo AND g.tipo = 'Z') ";
        $sql .= "WHERE c.nome LIKE '" . $letra . "%' ";
        $sql .= "ORDER BY u.situacao, u.tipo, c.nome";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }//fim select_usuario_letra

    /**
     * Inclusao no banco de dados
     * @name incluiUsuario
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    private function quoteSql($value)
    {
        $banco = new c_banco();
        $q = $banco->quote($value, $banco->id_connection);
        $banco->close_connection();
        return $q;
    }

    public function incluiUsuario() {
        $sql = "INSERT INTO AMB_USUARIO (";

        $sql .= " USUARIO, 
                    NOME, 
                    NOMEREDUZIDO, 
                    CLIENTE, 
                    SENHA, 
                    SITUACAO, 
                    TIPO, 
                    EMPRESA,
                    CONTA, 
                    SALARIO, 
                    ENCARGOS, 
                    GENEROPGTO, 
                    CCUSTOPGTO, 
                    COMISSAOFATURA, 
                    COMISSAORECEB, 
                    GRUPO,
                    SMTP,
                    EMAIL,
                    EMAILSENHA) ";
        $sql .= "VALUES ('" . (int) $this->getUsuario() . "', ";
        $sql .= $this->quoteSql($this->getLogin()) . ", " . $this->quoteSql($this->getNomeReduzido()) . ", '";
        $sql .= (int) $this->getcliente() . "', " . $this->quoteSql($this->getsenha()) . ", " . $this->quoteSql($this->getsituacao()) . ", ";
        $sql .= $this->quoteSql($this->gettipo()) . ", ";
        $sql .= $this->getEmpresa() !== '' && $this->getEmpresa() !== null ? (int) $this->getEmpresa() : "NULL";
        $sql .= ", '" . (int) $this->getconta() . "', '" . $this->getsalario('B') . "', '" . (float) $this->getencargos() . "', ";
        $sql .= $this->quoteSql($this->getgeneroPgto()) . ", '" . (int) $this->getccustoPgto() . "', '" . (float) $this->getcomissaoFatura('B') . "' ,'";
        $sql .= (float) $this->getcomissaoReceb('B') . "', '" . (int) $this->getGrupo() . "', ";
        $sql .= $this->quoteSql($this->getSmtp()) . ", " . $this->quoteSql($this->getEmail()) . ", " . $this->quoteSql($this->getEmailSenha()) . "); ";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco;
        $res_acessorio = $banco->exec_sql_lower_case($sql);
        $banco->close_connection();
        if ($res_acessorio > 0) {
            return '';
        } else {
            return 'Os dados do usuario ' . $this->getNomeReduzido() . ' n&atilde;o foram cadastrados!';
        }//if
    }// fim incluiUsuario

    /**
     * Alteracao no Banco de dados
     * @name alteraUsuario
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function alteraPasswordUsuario($user, $pw) {
        $sql = "UPDATE amb_usuario ";
        $sql .= "SET ";
        $sql .= "senha =  '" . $pw . "' ";
        $sql .= "WHERE usuario = '" . $user . "';";
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return 'Senha alterada com sucesso..';
        } else {
            return 'Senha não alterada!!';
        }//if
    }// fim alteraPassword

    /**
     * Alteracao no Banco de dados
     * @name alteraUsuario
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function alteraPasswordPessoa($user, $pw) {
        $sql = "UPDATE fin_cliente ";
        $sql .= "SET ";
        $sql .= "password =  '" . $pw . "' ";
        $sql .= "WHERE userlogin = '" . $user . "';";
        $banco = new c_banco;
        $res = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res > 0) {
            return 'Senha alterada com sucesso..';
        } else {
            return 'Senha não alterada!!';
        }//if
    }// fim alteraPassword

    /**
     * Alteracao no Banco de dados
     * @name alteraUsuario
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function alteraUsuario($atualizarSenha = true) {
        $sql = "UPDATE AMB_USUARIO ";
        $sql .= "SET  CLIENTE = " . (int) $this->getcliente() . ", ";
        $sql .= "USUARIO =  " . (int) $this->getUsuario() . ", ";
        $sql .= "NOME =  " . $this->quoteSql($this->getLogin()) . ", ";
        $sql .= "NOMEREDUZIDO =  " . $this->quoteSql($this->getNomeReduzido()) . ", ";
        if ($atualizarSenha && $this->getsenha() !== '' && $this->getsenha() !== null) {
            $sql .= "SENHA =  " . $this->quoteSql($this->getsenha()) . ", ";
        }
        $sql .= "SITUACAO = " . $this->quoteSql($this->getsituacao()) . ", ";
        $sql .= "TIPO = " . $this->quoteSql($this->gettipo()) . ", ";
        $sql .= "CONTA =  '" . (int) $this->getconta() . "', ";
        $sql .= "SALARIO = '" . $this->getsalario('B') . "', ";
        $sql .= "ENCARGOS = '" . (float) $this->getencargos() . "', ";
        $sql .= "GENEROPGTO =  " . $this->quoteSql($this->getgeneroPgto()) . ", ";
        $empresa = $this->getEmpresa();
        $sql .= "EMPRESA = " . ($empresa !== '' && $empresa !== null ? (int) $empresa : "NULL") . ", ";
        $sql .= "CCUSTOPGTO = '" . (int) $this->getccustoPgto() . "', ";
        $sql .= "COMISSAOFATURA = '" . (float) $this->getcomissaoFatura('B') . "', ";
        $sql .= "COMISSAORECEB = '" . (float) $this->getcomissaoReceb('B') . "', ";
        $sql .= "GRUPO = '" . (int) $this->getGrupo() . "', ";
        $sql .= "SMTP = " . $this->quoteSql($this->getSmtp()) . ", ";
        $sql .= "EMAIL = " . $this->quoteSql($this->getEmail());
        if ($this->getEmailSenha() !== '' && $this->getEmailSenha() !== null) {
            $sql .= ", EMAILSENHA = " . $this->quoteSql($this->getEmailSenha());
        }
        $sql .= " ";
        $sql .= "WHERE USUARIO = '" . (int) $this->getUsuario() . "';";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco;
        $res_acessorio = $banco->exec_sql_lower_case($sql);
        $banco->close_connection();
        if ($res_acessorio > 0) {
            return '';
        } else {
            return 'Os dados do usuario ' . $this->getNomeReduzido() . ' n&atilde;o foram alterados!';
        }//if
    }// fim alteraUsuario

    /**
     * Exclusao no banco de dados
     * @name excluiUsuario
     * @return string Retorna vazio se a operacao for bem sucedida
     */
    public function excluiUsuario() {

        $sql = "DELETE FROM amb_usuario ";
        $sql .= "WHERE usuario = '" . $this->getUsuario(). "'";
        //  echo strtoupper($sql)."<br>";
        $banco = new c_banco;
        $res_acessorio = $banco->exec_sql($sql);
        $banco->close_connection();
        if ($res_acessorio > 0) {
            return '';
        } else {
            return 'Os dados do usuario ' . $this->getNomeReduzido() . ' n&atilde;o foram excluidos!';
        }//if
    }// fim excluiUsuario

    /** Matrícula reservada para administrador. */
    const MATRICULA_ADMIN = 999;

    /** Grupos (tipo Z) usam matrícula acima deste valor. */
    const MATRICULA_GRUPO_MIN = 1001;

    /**
     * Matrícula 999 (admin). Demais abaixo de 1000 são operacionais; grupos ficam &gt;= 1001.
     */
    public function matriculaReservadaAdmin($matricula)
    {
        return ((int) $matricula === self::MATRICULA_ADMIN);
    }

    public function ehTipoGrupo($tipo = null)
    {
        $t = ($tipo !== null) ? $tipo : $this->gettipo();
        return ($t === 'Z');
    }

    /**
     * Próxima matrícula conforme o tipo (operacional ou grupo).
     */
    public function proximaMatriculaUsuario($tipo = null)
    {
        if ($this->ehTipoGrupo($tipo)) {
            return $this->proximaMatriculaGrupo();
        }
        return $this->proximaMatriculaOperacional();
    }

    /**
     * Última matrícula operacional + 1 (ignora 999 e usuários tipo grupo).
     * @return int 0 se não couber abaixo de 1000
     */
    public function proximaMatriculaOperacional()
    {
        $banco = new c_banco();
        $banco->exec_sql("SELECT MAX(usuario) AS ULTIMO FROM amb_usuario "
            . "WHERE usuario <> " . self::MATRICULA_ADMIN . " "
            . "AND (tipo IS NULL OR tipo <> 'Z') "
            . "AND usuario < " . self::MATRICULA_GRUPO_MIN);
        $banco->close_connection();

        $ultimo = 0;
        if (is_array($banco->resultado) && isset($banco->resultado[0]['ULTIMO'])) {
            $ultimo = (int) $banco->resultado[0]['ULTIMO'];
        }
        $prox = $ultimo + 1;
        if ($prox === self::MATRICULA_ADMIN) {
            $prox++;
        }
        if ($prox >= self::MATRICULA_GRUPO_MIN) {
            return 0;
        }
        return $prox;
    }

    /**
     * Última matrícula de grupo (tipo Z, &gt;= 1001) + 1.
     */
    public function proximaMatriculaGrupo()
    {
        $banco = new c_banco();
        $banco->exec_sql("SELECT MAX(usuario) AS ULTIMO FROM amb_usuario "
            . "WHERE tipo = 'Z' AND usuario >= " . self::MATRICULA_GRUPO_MIN);
        $banco->close_connection();

        $ultimo = self::MATRICULA_GRUPO_MIN - 1;
        if (is_array($banco->resultado) && isset($banco->resultado[0]['ULTIMO'])) {
            $ultimo = (int) $banco->resultado[0]['ULTIMO'];
        }
        return $ultimo + 1;
    }

    /**
     * Ajusta matrícula no cadastro se vazia ou fora da faixa do tipo.
     */
    public function aplicarProximaMatriculaCadastro()
    {
        $mat = (int) $this->getUsuario();
        if ($this->ehTipoGrupo()) {
            if ($mat < self::MATRICULA_GRUPO_MIN) {
                $this->setUsuario($this->proximaMatriculaGrupo());
            }
            return;
        }
        if ($mat <= 0 || $this->matriculaReservadaAdmin($mat) || $mat >= self::MATRICULA_GRUPO_MIN) {
            $prox = $this->proximaMatriculaOperacional();
            if ($prox > 0) {
                $this->setUsuario($prox);
            }
        }
    }

    /**
     * Combos do cadastro de usuário (ids + labels).
     */
    public function comboEmpresaUsuario()
    {
        $ids = array('');
        $names = array('selecione uma Empresa');
        $banco = new c_banco();
        $banco->exec_sql("SELECT empresa AS ID, nomefantasia AS DESCRICAO FROM amb_empresa");
        $banco->close_connection();
        if (is_array($banco->resultado)) {
            foreach ($banco->resultado as $row) {
                $ids[] = $row['ID'];
                $names[] = $row['DESCRICAO'];
            }
        }
        return array('ids' => $ids, 'names' => $names);
    }

    public function comboSituacaoUsuario()
    {
        return $this->comboAmbDdm('SituacaoUsuario');
    }

    public function comboTipoUsuario()
    {
        return $this->comboAmbDdm('TipoUsuario');
    }

    public function comboGrupoUsuario()
    {
        $ids = array(0);
        $names = array('Sem Grupo');
        $banco = new c_banco();
        $banco->exec_sql("SELECT usuario AS ID, nomereduzido AS DESCRICAO FROM amb_usuario "
            . "WHERE situacao='A' AND tipo='Z'");
        $banco->close_connection();
        if (is_array($banco->resultado)) {
            foreach ($banco->resultado as $row) {
                $ids[] = $row['ID'];
                $names[] = $row['DESCRICAO'];
            }
        }
        return array('ids' => $ids, 'names' => $names);
    }

    private function comboAmbDdm($campo)
    {
        $ids = array();
        $names = array();
        $banco = new c_banco();
        $banco->exec_sql("SELECT tipo AS ID, padrao AS DESCRICAO FROM amb_ddm "
            . "WHERE alias='AMB_MENU' AND campo='" . $campo . "'");
        $banco->close_connection();
        if (is_array($banco->resultado)) {
            foreach ($banco->resultado as $i => $row) {
                $ids[$i] = $row['ID'];
                $names[$i] = $row['DESCRICAO'];
            }
        }
        return array('ids' => $ids, 'names' => $names);
    }

    /**
     * Grava direitos enviados pelo POST (JSON da aba Direitos).
     */
    public function salvarDireitosCadastro($jsonDireitos)
    {
        if (trim($jsonDireitos) === '') {
            return '';
        }
        $pode = $this->verificaDireitoUsuario('AmbUsuario', 'I', 'N')
            || $this->verificaDireitoUsuario('AmbUsuario', 'A', 'N');
        if (!$pode) {
            return ' Sem permiss&atilde;o para gravar direitos do usu&aacute;rio.';
        }
        $dados = json_decode($jsonDireitos, true);
        if (!is_array($dados)) {
            return ' N&atilde;o foi poss&iacute;vel gravar os direitos (dados inv&aacute;lidos).';
        }
        if ((int) $this->getUsuario() <= 0) {
            return ' Matr&iacute;cula inv&aacute;lida para gravar direitos.';
        }
        $aut = new c_usuario_autoriza();
        return $aut->syncAutorizacoesUsuario($this->getUsuario(), $dados);
    }

    /**
     * Programas + checkboxes da aba Direitos.
     */
    public function programasUiCadastro()
    {
        $letras = array('I', 'A', 'E', 'C', 'S', 'R');
        $grupoId = (int) $this->getGrupo();
        $grupoNome = '';
        $usuarioId = (int) $this->getUsuario();
        $mapUsuario = array();
        $mapGrupo = array();

        if ($usuarioId > 0) {
            $aut = new c_usuario_autoriza();
            $rowsU = $aut->select_autorizacao_por_usuario($usuarioId);
            if (is_array($rowsU)) {
                foreach ($rowsU as $r) {
                    $prog = strtoupper(trim($r['PROGRAMA']));
                    if ($prog !== '') {
                        $mapUsuario[$prog] = trim($r['DIREITOS']);
                    }
                }
            }
            if ($grupoId > 0) {
                $rowsG = $aut->select_autorizacao_por_usuario($grupoId);
                if (is_array($rowsG)) {
                    foreach ($rowsG as $r) {
                        $prog = strtoupper(trim($r['PROGRAMA']));
                        if ($prog !== '') {
                            $mapGrupo[$prog] = trim($r['DIREITOS']);
                        }
                    }
                }
                $b = new c_banco();
                $b->exec_sql("SELECT nomereduzido AS NOMEREDUZIDO FROM amb_usuario WHERE usuario = " . $grupoId);
                $b->close_connection();
                if (is_array($b->resultado) && isset($b->resultado[0]['NOMEREDUZIDO'])) {
                    $grupoNome = $b->resultado[0]['NOMEREDUZIDO'];
                }
            }
        }

        $banco = new c_banco();
        $banco->exec_sql("SELECT nomeform AS NOMEFORM, descricao AS DESCRICAO, help AS HELP "
            . "FROM amb_form ORDER BY descricao");
        $banco->close_connection();

        $programasUi = array();
        if (is_array($banco->resultado)) {
            foreach ($banco->resultado as $p) {
                $nomeform = trim($p['NOMEFORM']);
                if ($nomeform === '') {
                    continue;
                }
                $descricao = trim($p['DESCRICAO']);
                $helpRaw = trim($p['HELP']);
                $helpForm = '';
                if ($helpRaw !== '') {
                    $helpForm = strip_tags($helpRaw);
                    $helpForm = html_entity_decode($helpForm, ENT_QUOTES, 'UTF-8');
                    $helpForm = trim(preg_replace('/\s+/', ' ', $helpForm));
                }
                $chave = strtoupper($nomeform);
                $du = isset($mapUsuario[$chave]) ? $mapUsuario[$chave] : '';
                $dg = isset($mapGrupo[$chave]) ? $mapGrupo[$chave] : '';
                $chk = array();
                foreach ($letras as $lt) {
                    $chk[$lt] = ($du !== '' && strpos($du, $lt) !== false);
                }
                $programasUi[] = array(
                    'nomeform' => $nomeform,
                    'descricao' => $descricao,
                    'help' => $helpForm,
                    'direitos_usuario' => $du,
                    'direitos_grupo' => $dg,
                    'chk' => $chk,
                );
            }
        }

        return array(
            'programasUi' => $programasUi,
            'grupoId' => $grupoId,
            'grupoNome' => $grupoNome,
        );
    }
}

//	END OF THE CLASS
?>
