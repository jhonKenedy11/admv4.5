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

$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../class/crm/c_conta.php");

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

    public function setPessoaNome() {
        $cliente = new c_conta();
        $cliente->setId($this->getCliente());
        $reg_nome = $cliente->select_conta();
        $this->nomePessoa = "'" . $reg_nome[0]['NOME'] . "'";
        //$this->nomeReduzido = "'".$reg_nome[0]['NOMEREDUZIDO']."'";
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

    public function getcomissaoFatura() {
        return $this->comissaoFatura;
    }

    public function setcomissaoReceb($comissaoReceb) {
        $this->comissaoReceb = $comissaoReceb;
    }

    public function getcomissaoReceb() {
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
     * Consulta todos os registros da tabela
     * @name select_usuario_geral
     * @return ARRAY com todos os campos do banco
     */
    public function select_usuario_geral() {
        $sql = "SELECT DISTINCT u.*, c.nome as nomeusuario, s.padrao as descSituacao, t.padrao as descTipo ";
        $sql .= "FROM amb_usuario u ";
        $sql .= "left join amb_ddm s on ((s.tipo = u.situacao) and (s.alias='AMB_MENU') and (s.campo='SituacaoUsuario')) ";
        $sql .= "left join amb_ddm t on ((t.tipo = u.tipo) and (t.alias='AMB_MENU') and (t.campo='TipoUsuario')) ";
        $sql .= "left join fin_cliente c on (u.cliente = c.cliente) ";
        $sql .= "ORDER BY u.situacao, u.tipo,c.nome";
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
        $sql = "SELECT DISTINCT u.*, c.nome as nomeusuario, s.padrao as descSituacao, t.padrao as descTipo ";
        $sql .= "FROM amb_usuario u ";
        $sql .= "left join amb_ddm s on ((s.tipo = u.situacao) and (s.alias='AMB_MENU') and (s.campo='SituacaoUsuario')) ";
        $sql .= "left join amb_ddm t on ((t.tipo = u.tipo) and (t.alias='AMB_MENU') and (t.campo='TipoUsuario')) ";
        $sql .= "inner join fin_cliente c on (u.cliente = c.cliente) ";
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
    public function incluiUsuario() {
        $sql = "INSERT INTO AMB_USUARIO (";

        $sql .= " USUARIO, 
                    NOME, 
                    NOMEREDUZIDO, 
                    CLIENTE, 
                    SENHA, 
                    SITUACAO, 
                    TIPO, 
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
        $sql .= "VALUES ('" . $this->getUsuario() . "', '";
        $sql .= $this->getLogin() . "', '" . $this->getNomeReduzido() . "', '";
        $sql .= $this->getcliente() . "', '" . $this->getsenha() . "', '" . $this->getsituacao() . "', '" . $this->gettipo() . "', '";
        $sql .= $this->getconta() . "', '" . $this->getsalario('B') . "', '" . $this->getencargos() . "', '";
        $sql .= $this->getgeneroPgto() . "', '" . $this->getccustoPgto() . "', '" . $this->getcomissaoFatura() . "' ,'";
        $sql .= $this->getcomissaoReceb() . "', '" . $this->getGrupo(). "', '" . $this->getSmtp(). "', '" . $this->getEmail(). "', '" . $this->getEmailSenha() . "'); ";
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
    public function alteraUsuario() {
        $sql = "UPDATE AMB_USUARIO ";
        $sql .= "SET  CLIENTE = " . $this->getcliente() . ", ";
        $sql .= "USUARIO =  " . $this->getUsuario() . ", ";
        $sql .= "NOME =  '" . $this->getLogin() . "', ";
        $sql .= "NOMEREDUZIDO =  '" . $this->getNomeReduzido() . "', ";
        $sql .= "SENHA =  '" . $this->getsenha() . "', ";
        $sql .= "SITUACAO = '" . $this->getsituacao() . "', ";
        $sql .= "TIPO = '" . $this->gettipo() . "', ";
        $sql .= "CONTA =  '" . $this->getconta() . "', ";
        $sql .= "SALARIO = '" . $this->getsalario('B') . "', ";
        $sql .= "ENCARGOS = '" . $this->getencargos() . "', ";
        $sql .= "GENEROPGTO =  '" . $this->getgeneroPgto() . "', ";
        $sql .= "CCUSTOPGTO = '" . $this->getccustoPgto() . "', ";
        $sql .= "COMISSAOFATURA = '" . $this->getcomissaoFatura() . "', ";
        $sql .= "COMISSAORECEB = '" . $this->getcomissaoReceb() . "', ";
        $sql .= "GRUPO = '" . $this->getGrupo() . "', ";        
        $sql .= "SMTP = '" . $this->getSmtp() . "', ";
        $sql .= "EMAIL = '" . $this->getEmail() . "', ";
        $sql .= "EMAILSENHA = '" . $this->getEmailSenha() . "' ";
        $sql .= "WHERE USUARIO = '" . $this->getUsuario() . "';";
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
}

//	END OF THE CLASS
?>
