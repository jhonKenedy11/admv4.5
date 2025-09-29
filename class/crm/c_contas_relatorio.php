<?php

/**
 * @package   admv4.5
 * @name      c_contas_relatorio
 * @version   4.5
 * @copyright 2025
 * @link      http://www.admservice.com.br/
 * @author    Joshua Silva <joshua.silva@admsistemas.com.br>
 * @date      08/05/2025
 */
$dir = dirname(__FILE__);
include_once($dir . "/../../bib/c_user.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");
include_once($dir . "/../../class/crm/c_conta.php");


//Class c_pedido_venda_relatorios
class c_contas_relatorio extends c_user
{

    private $id         = NULL; // VARCHAR(15)

    /**
     * METODOS DE SETS E GETS
     */
    function getCentrocusto()
    {
        return $this->centrocusto;
    }

    function setCentrocusto($centrocusto)
    {
        $this->centrocusto = $centrocusto;
    }

    function getPesNome()
    {
        return $this->pesNome;
    }

    function setPesNome($pesNome)
    {
        $this->pesNome = $pesNome;
    }

    function getPesCnpjCpf()
    {
        return $this->pesCnpjCpf;
    }

    function setPesCnpjCpf($pesCnpjCpf)
    {
        $this->pesCnpjCpf = $pesCnpjCpf;
    }

    function getDataConsulta()
    {
        return $this->data_consulta;
    }

    function setDataConsulta($data_consulta)
    {
        $this->data_consulta = $data_consulta;
    }

    function getPesCidade()
    {
        return $this->pesCidade;
    }

    function setPesCidade($pesCidade)
    {
        $this->pesCidade = $pesCidade;
    }

    function getIdEstado()
    {
        return $this->idEstado;
    }

    function setIdEstado($idEstado)
    {
        $this->idEstado = $idEstado;
    }

    function getIdFilial()
    {
        return $this->idFilial;
    }

    function setIdFilial($idFilial)
    {
        $this->idFilial = $idFilial;
    }

    function getIdPessoa()
    {
        return $this->idPessoa;
    }

    function setIdPessoa($idPessoa)
    {
        $this->idPessoa = $idPessoa;
    }

    function getIdClasse()
    {
        return $this->idClasse;
    }

    function setIdClasse($idClasse)
    {
        $this->idClasse = $idClasse;
    }

    function getIdAtividade()
    {
        return $this->idAtividade;
    }

    function setIdAtividade($idAtividade)
    {
        $this->idAtividade = $idAtividade;
    }

    function getIdVendedor()
    {
        return $this->idVendedor;
    }

    function setIdVendedor($idVendedor)
    {
        $this->idVendedor = $idVendedor;
    }


    //############### FIM SETS E GETS ###############

    public function select_conta_aniversario()
    {
        $sql = "SELECT c.*, u.nomereduzido as representante ";
        $sql .= "FROM fin_cliente c ";
        $sql .= "left join amb_usuario u on u.usuario = c.representante ";

        $where = $this->wherePessoa();

        $sql .= " $where " . " ORDER BY MONTH(c.DATANASCIMENTO) ASC, DAY(c.DATANASCIMENTO) ASC, c.NOME ASC";


        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    public function wherePessoa()
    {
        $where = "";
        $isWhere = false;

        // periodo de anivesário com base em dia e mes.
        if ($this->getDataConsulta() != '') {
            $periodo = explode(' - ', $this->getDataConsulta());

            if (count($periodo) == 2) {
                $dataIni = DateTime::createFromFormat('d/m/Y', trim($periodo[0]));
                $dataFim = DateTime::createFromFormat('d/m/Y', trim($periodo[1]));

                if ($dataIni && $dataFim) {
                    $mesIni = $dataIni->format('m');
                    $diaIni = $dataIni->format('d');
                    $mesFim = $dataFim->format('m');
                    $diaFim = $dataFim->format('d');

                    if ($mesIni == $mesFim) {
                        $condicao = "(MONTH(c.datanascimento) = $mesIni AND DAY(c.datanascimento) BETWEEN $diaIni AND $diaFim)";
                    } elseif ($mesIni < $mesFim) {
                        $condicao = "(MONTH(c.datanascimento) = $mesIni AND DAY(c.datanascimento) >= $diaIni) OR ";
                        $condicao .= "(MONTH(c.datanascimento) > $mesIni AND MONTH(c.datanascimento) < $mesFim) OR ";
                        $condicao .= "(MONTH(c.datanascimento) = $mesFim AND DAY(c.datanascimento) <= $diaFim)";
                    } else {
                        $condicao = "(MONTH(c.datanascimento) = $mesIni AND DAY(c.datanascimento) >= $diaIni) OR ";
                        $condicao .= "(MONTH(c.datanascimento) > $mesIni) OR "; 
                        $condicao .= "(MONTH(c.datanascimento) < $mesFim) OR ";
                        $condicao .= "(MONTH(c.datanascimento) = $mesFim AND DAY(c.datanascimento) <= $diaFim)";
                    }

                    if ($isWhere) {
                        $where .= "AND ($condicao) ";
                    } else {
                        $where .= "WHERE ($condicao) ";
                        $isWhere = true;
                    }
                }
            }
        }

        // Nome ou Nome Reduzido
        if ($this->getPesNome() != '') {
            if ($isWhere) {
                $where .= "AND ((c.nome LIKE '%" . $this->getPesNome() . "%') ";
                $where .= "OR (c.nomereduzido LIKE '%" . $this->getPesNome() . "%')) ";
            } else {
                $where .= "WHERE ((c.nome LIKE '%" . $this->getPesNome() . "%') ";
                $where .= "OR (c.nomereduzido LIKE '%" . $this->getPesNome() . "%')) ";
                $isWhere = true;
            }
        }

        // Classe
        if ($this->getIdClasse() != '') {
            if ($isWhere) {
                $where .= "AND (c.classe = '" . $this->getIdClasse() . "') ";
            } else {
                $where .= "WHERE (c.classe = '" . $this->getIdClasse() . "') ";
                $isWhere = true;
            }
        }

        // tipo pessoa
        if ($this->getIdPessoa() != '') {
            if ($isWhere) {
                $where .= "AND (c.pessoa = '" . $this->getIdPessoa() . "') ";
            } else {
                $where .= "WHERE (c.pessoa = '" . $this->getIdPessoa() . "') ";
                $isWhere = true;
            }
        }

        // Estado (UF)
        if ($this->getIdEstado() != '') {
            if ($isWhere) {
                $where .= "AND (c.UF LIKE '" . $this->getIdEstado() . "') ";
            } else {
                $where .= "WHERE (c.UF LIKE '" . $this->getIdEstado() . "') ";
                $isWhere = true;
            }
        }

        // Representante (Vendedor)
        if ($this->getIdVendedor() != '') {
            if ($isWhere) {
                $where .= "AND (c.representante = '" . $this->getIdVendedor() . "') ";
            } else {
                $where .= "WHERE (c.representante = '" . $this->getIdVendedor() . "') ";
                $isWhere = true;
            }
        }

        // Cidade
        if ($this->getPesCidade() != '') {
            if ($isWhere) {
                $where .= "AND (c.cidade LIKE '" . $this->getPesCidade() . "%') ";
            } else {
                $where .= "WHERE (c.cidade LIKE '" . $this->getPesCidade() . "%') ";
                $isWhere = true;
            }
        }

        // filial
        if ($this->getIdFilial() != '') {
            if ($isWhere) {
                $where .= "AND (c.CENTROCUSTO = '" . $this->getIdFilial() . "') ";
            } else {
                $where .= "WHERE (c.CENTROCUSTO = '" . $this->getIdFilial() . "') ";
                $isWhere = true;
            }
        }

        // Atividade
        if ($this->getIdAtividade() != '') {
            if ($isWhere) {
                $where .= "AND (c.atividade = '" . $this->getIdAtividade() . "') ";
            } else {
                $where .= "WHERE (c.atividade = '" . $this->getIdAtividade() . "') ";
                $isWhere = true;
            }
        }

        // CNPJ/CPF
        if ($this->getPesCnpjCpf() != '') {
            if ($isWhere) {
                $where .= "AND (c.CNPJCPF LIKE  '%" . $this->getPesCnpjCpf() . "%') ";
            } else {
                $where .= "WHERE (c.CNPJCPF LIKE % '" . $this->getPesCnpjCpf() . "%') ";
            }
        }

        return $where;
    }

    public function selectRelatorioContas()
    {
        $sql = "SELECT c.*, u.nomereduzido as representante, e.nomeempresa as CENTROCUSTODESC, fc.descricao as CLASSEDESC, a.descricao as ATIVIDADEDESC ";
        $sql .= "FROM fin_cliente c ";
        $sql .= "left join amb_usuario u on u.usuario = c.representante ";
        $sql .= "left join amb_empresa e on c.centrocusto = e.centrocusto ";
        $sql .= "left join fin_atividade a on c.atividade = a.atividade ";
        $sql .= "left join fin_classe fc on c.classe = fc.classe ";


        $where = $this->wherePessoa();

        $sql .= " $where " . " ORDER BY c.NOME ASC";


        $banco = new c_banco;
        $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    // função responsavel pelas combos
    public function comboAniversario()
    {
        $this->comboFilial();
        $this->comboEstados();
        $this->comboResponsaveis();
        $this->comboAtividades();
        $this->comboClasses();
        $this->comboTipos();
    }

    // combo centro custo
    public function comboFilial()
    {
        $consulta = new c_banco();
        $sql = "select centrocusto as id, descricao from fin_centro_custo where (ativo='S')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        for ($i = 0; $i < count($result); $i++) {
            $filial_ids[$i] = $result[$i]['ID'];
            $filial_names[$i] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('filial_ids', $filial_ids);
        $this->smarty->assign('filial_names', $filial_names);
        $this->smarty->assign('filial_id', $this->getCentroCusto());
    }

    //combo estado UF
    public function comboEstados()
    {
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Estado')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $estado_ids[0] = '';
        $estado_names[0] = 'Selecione um Estado';
        for ($i = 0; $i < count($result); $i++) {
            $estado_ids[$i + 1] = $result[$i]['ID'];
            $estado_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('estado_ids', $estado_ids);
        $this->smarty->assign('estado_names', $estado_names);
        if ($this->m_par[3] == "") {
            $this->smarty->assign('estado_id', 'Todos');
        } else {
            $this->smarty->assign('estado_id', $this->m_par[3]);
        }
    }

    //Combo Vendedor
    public function comboResponsaveis()
    {
        $consulta = new c_banco();
        $sql = "select usuario as id, nomereduzido as descricao from amb_usuario where (situacao = 'A') order by nomereduzido";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $responsavel_ids[0] = '';
        $responsavel_names[0] = 'Selecione um Responsável';
        for ($i = 0; $i < count($result); $i++) {
            $responsavel_ids[$i + 1] = $result[$i]['ID'];
            $responsavel_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('responsavel_ids', $responsavel_ids);
        $this->smarty->assign('responsavel_names', $responsavel_names);
        if ($this->m_par[4] == "") {
            $this->smarty->assign('responsavel_id', 'Todos');
        } else {
            $this->smarty->assign('responsavel_id', $this->m_par[4]);
        }
    }

    //combo atividade cliente balcão
    public function comboAtividades()
    {
        $consulta = new c_banco();
        $sql = "select atividade as id, descricao from fin_atividade";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $atividade_ids[0] = '';
        $atividade_names[0] = 'Selecione uma Atividade';
        for ($i = 0; $i < count($result); $i++) {
            $atividade_ids[$i + 1] = $result[$i]['ID'];
            $atividade_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('atividade_ids', $atividade_ids);
        $this->smarty->assign('atividade_names', $atividade_names);

        if ($this->m_par[6] == "") {
            $this->smarty->assign('atividade_id', 'Todos');
        } else {
            $this->smarty->assign('atividade_id', $this->m_par[6]);
        }
    }

    //combo classe ativo, bloqueado
    public function comboClasses()
    {
        $consulta = new c_banco();
        $sql = "select classe as id, descricao from fin_classe";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $classe_ids[0] = '';
        $classe_names[0] = 'Selecione uma Classe';
        for ($i = 0; $i < count($result); $i++) {
            $classe_ids[$i + 1] = $result[$i]['ID'];
            $classe_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('classe_ids', $classe_ids);
        $this->smarty->assign('classe_names', $classe_names);
        if ($this->m_par[1] == "") {
            $this->smarty->assign('classe_id', 'Todos');
        } else {
            $this->smarty->assign('classe_id', $this->m_par[1]);
        }
    }


    //combo pessoa fisica/juridica
    public function comboTipos()
    {
        $consulta = new c_banco();
        $sql = "select tipo as id, padrao as descricao from amb_ddm where (alias='FIN_MENU') and (campo='Pessoa')";
        $consulta->exec_sql($sql);
        $consulta->close_connection();
        $result = $consulta->resultado;
        $tipoPessoa_ids[0] = '';
        $tipoPessoa_names[0] = 'Selecione um Tipo';
        for ($i = 0; $i < count($result); $i++) {
            $tipoPessoa_ids[$i + 1] = $result[$i]['ID'];
            $tipoPessoa_names[$i + 1] = $result[$i]['DESCRICAO'];
        }
        $this->smarty->assign('tipoPessoa_ids', $tipoPessoa_ids);
        $this->smarty->assign('tipoPessoa_names', $tipoPessoa_names);
        if ($this->m_par[2] == "") {
            $this->smarty->assign('tipoPessoa_id', 'Todos');
        } else {
            $this->smarty->assign('tipoPessoa_id', $this->m_par[2]);
        }
    }
}    //	END OF THE CLASS
