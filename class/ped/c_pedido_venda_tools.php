<?php

/**
 * @package   astecv3
 * @name      c_pedido_venda_tools
 * @version   3.0.00
 * @copyright 2017
 * @link      http://www.admservice.com.br/
 * @author    Marcio Sergio da Silva<marcio.sergio@admservice.com.br>
 * @date      28/06/2017
 */

$dir = dirname(__FILE__);
include_once($dir . "/../../class/ped/c_pedido_venda.php");
include_once($dir . "/../../class/est/c_produto.php");
include_once($dir . "/../../class/est/c_produto_estoque.php");
include_once($dir . "/../../bib/c_date.php");
include_once($dir . "/../../bib/c_tools.php");

//Class 
Class c_pedidoVendaTools extends c_pedidoVenda
{




    /**
     * METODOS DE SETS E GETS
     */

    //############### FIM SETS E GETS ###############


    /**
     * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
     * @name incluiItensPedidoControle
     * @param VARCHAR condPgto
     * @param int total
     * @return Matriz com as datas de vencimento e valores de cada parcela.
     */
    public function incluiItensPedidoControle($cc, &$idPedido, $itensPedido, $itensQtde, $desconto, $tipoMsg = null, $cliente = null, $natOp = null)
    {
        try {
            $parametros = new c_banco;
            $parametros->setTab("EST_PARAMETRO");
            $controlaEstoque = $parametros->getField("CONTROLAESTOQUE", "FILIAL=" . $cc);
            $parametros->close_connection();

            // Caso não existir numero de id de pedidos, cadastro de pedido e setar no id
            if (empty($idPedido)) {
                $this->setCliente($cliente);
                $this->setSituacao(0);
                $this->setEmissao(date("d/m/Y"));
                $this->setAtendimento(date("d/m/Y"));
                $this->setHoraEmissao(date("H:i:s"));
                $this->setEspecie("D");
                $this->setCentroCusto($cc);
                $this->setIdNatop($natOp);
                $idPedido = $this->incluiPedido();
            }
            // cadastra itens selecionados.
            // m_itensPedido -> contem todos os itens checados
            $msg = "";
            $this->setId($idPedido);
            if ($itensPedido != "") {
                $item = explode("|", $itensPedido);
                $objProduto = new c_produto();
                $objProdutoQtde = new c_produto_estoque();
                for ($i = 0; $i < count($item); $i++) {
                    $quantDigitada = $itensQtde; // quant em digitacao
                    $quantPedido = 0;
                    $quantTotal = $quantDigitada;
                    // verifica se produto existe na tabela pedido item.
                    // verificar se existe o item no pedido
                    $this->setItemEstoque($item[$i]);
                    $arrItemPedido = $this->select_pedido_item_id_itemestoque();
                    if (is_array($arrItemPedido)):
                        $quantPedido = $arrItemPedido[0]['QTSOLICITADA']; // quant já cadastrada
                        $quantTotal = $quantDigitada + $quantPedido;
                        $this->pedido_venda_item(false, $arrItemPedido);
                    endif;
                    // Consluta na table de produtos para pegar os dados
                    $objProduto->setId($item[$i]); // CODIGO PRODUTO
                    $arrProduto = $objProdutoQtde->produtoQtdePreco(NULL, $cc, $objProduto->getId());
                    $uniFrac = $arrProduto[0]['UNIFRACIONADA'];
                    $ifControlaEstoque = (($controlaEstoque == 'S') && ($uniFrac == 'N'));

                    //if (($controlaEstoque == 'N') or (($quantDigitada <= $arrProduto[0]['QUANTIDADE']) AND
                    if ((!$ifControlaEstoque) or (($quantDigitada <= $arrProduto[0]['QUANTIDADE']) and
                        (floatval($arrProduto[0]['VENDA']) > floatval(0)))): // TESTA PRECO E QUANT DISPONIVEL
                        if ((floatval($arrProduto[0]['PROMOCAO']) > floatval(0)) and
                            ($quantTotal > $arrProduto[0]['QUANTLIMITE'])
                        ): // TESTA MAXIMO VENDA PROMOCAO
                            $msg .= $arrProduto[0]['DESCRICAO'] . " Quantidade acima limite promoção - Quant:" . $arrProduto[0]['QUANTLIMITE'] . "<br>";
                        else:
                            //$this->setItemEstoque($item[$i]);
                            $this->setItemFabricante($arrProduto[0]['CODFABRICANTE']);
                            $this->setQtSolicitada($quantTotal);
                            if (floatval($arrProduto[0]['PROMOCAO']) > floatval(0)):
                                $this->setUnitario(str_replace('.', ',', $arrProduto[0]['PROMOCAO']));
                            else:
                                $this->setUnitario(str_replace('.', ',', $arrProduto[0]['VENDA']));
                            endif;
                            $this->setPrecoPromocao(str_replace('.', ',', $arrProduto[0]['PROMOCAO']));
                            $this->setVlrTabela(str_replace('.', ',', $arrProduto[0]['VENDA']));
                            $this->setDesconto($desconto);
                            $this->setTotalItem();
                            $this->setGrupoEstoque($arrProduto[0]['GRUPO']);
                            $this->setDescricaoItem($arrProduto[0]['DESCRICAO']);
                            if (is_array($arrItemPedido)):
                                $this->alteraPedidoItem();
                            else:
                                //pegar o ultimo NrItem do pedido
                                $ultimoNrItem = $this->select_pedidoVenda_item_max_nritem();
                                $this->setNrItem($ultimoNrItem[0]['MAXNRITEM'] + 1);
                                $this->IncluiPedidoItem();
                            endif;
                            // reserva produto
                            if ($ifControlaEstoque) :
                                $objProdutoQtde->produtoReserva(
                                    $cc,
                                    "PED",
                                    $this->getId(),
                                    $this->getItemEstoque(),
                                    $quantDigitada
                                );

                            endif;
                        endif;
                    else:
                        $msg .= $arrProduto[0]['DESCRICAO'] . " Preço ou Quantidade não disponivel<br>";
                    endif;

                    // atualiza total
                    $this->setTotal($this->select_totalPedido());
                    $this->setCliente($cliente);
                    $this->setPedido(0);
                    $this->setSituacao(0);

                    $this->alteraPedidoTotal();
                }
                $tipoMsg = "sucesso";
            } else {
                $msg = "Selecione um Produto para compra";
                $tipoMsg = "erro";
            }
            return $msg;
        } catch (Error $e) {
            throw new Exception($e->getMessage() . "Item não cadastrado ");
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . "Item não cadastrado ");
        }
    } // fim incluiItensPedidoControle

    /**
     * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
     * @name incluiItensPedidoControle
     * @param VARCHAR condPgto
     * @param int total
     * @return Matriz com as datas de vencimento e valores de cada parcela.
     */
    public function excluiItensPedidoControle($cc, $idPedido, $idItem, $tipoMsg = null)
    {
        try {
            $tipoMsg = "sucesso";
            $msg = "";
            //BUSCAR DADOS DO ITEM A EXCLUIR
            $this->setId($idPedido);
            $this->setNrItem($idItem);
            $arrPedidoItem = $this->select_pedido_item_id_nritem();
            $this->setId($arrPedidoItem[0]['ID']);
            $this->setItemEstoque($arrPedidoItem[0]['ITEMESTOQUE']);
            $this->setQtSolicitada($arrPedidoItem[0]['QTSOLICITADA']);

            // retira de reserva
            if ($arrPedidoItem[0]['ITEMESTOQUE'] != 'SEM_CODIGO') {
                $objProdutoQtde = new c_produto_estoque();
                $objProdutoQtde->produtoReservaExclui(
                    $cc,
                    "PED",
                    $this->getId(),
                    $this->getItemEstoque(),
                    $this->getQtSolicitada()
                );
            }
            $msg = $this->excluiPedidoItem();

            // atualiza total
            $this->setTotal($this->select_totalPedido());
            $this->setCliente($arrPedidoItem[0]['CLIENTE']);
            $this->setPedido($arrPedidoItem[0]['ID']);
            $this->setSituacao($arrPedidoItem[0]['SITUACAO']);
            //$this->setSituacao(0);
            if ($this->getSituacao() == '6') {
                $this->atualizarField('situacao', '11');
            } //alteracao

            $this->alteraPedidoTotal();

            return $msg;
        } catch (Error $e) {
            throw new Exception($e->getMessage() . "Item não excluido ");
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . "Item não excluido ");
        }
    }


    /**
     * <b> Rotina que gera novo pedido e inclui itens selecionados no pedido </b>
     * @name incluiItensPedidoControle
     * @param VARCHAR condPgto
     * @param int total
     * @return Matriz com as datas de vencimento e valores de cada parcela.
     */
    public function alteraItensPedidoControle($desc, $idPedido, $idItem, $tipoMsg = null)
    {
        try {
            $tipoMsg = "sucesso";
            $msg = "";
            //BUSCAR DADOS DO ITEM A ALTERAR
            $this->setId($idPedido);
            $this->setNrItem($idItem);
            $arrPedidoItem = $this->select_pedido_item_id_nritem();
            $this->setId($arrPedidoItem[0]['ID']);
            $this->setItemEstoque($arrPedidoItem[0]['ITEMESTOQUE']);
            $this->setDescricaoItem($desc);

            // retira de reserva

            $msg = $this->excluiPedidoItem();

            // atualiza total


            return $msg;
        } catch (Error $e) {
            throw new Exception($e->getMessage() . "Item não excluido ");
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . "Item não excluido ");
        }
    }

    /**
     * <b> Rotina que valida dados do cliente e itens do pedido </b>
     * @name validaPedido
     * @param int idPedido
     * @return VARCHAR msg com as inconsistência para apresentar no pedido.
     */
    public function validaPedido($idPedido, $cce)
    {
        try {
            $tipoMsg = "sucesso";
            $msg = null;
            $classProdutoQtde = new c_produto_estoque();
            //BUSCAR DADOS DO ITEM DO PEDIDO
            // $this->setId($idPedido);
            // $arrPedido = $this->select_pedidoVenda();
            $arrPedidoItem = $this->select_todos_pedidos_item($idPedido);
            for ($i = 0; $i < count($arrPedidoItem); $i++) {
                // NOVO CALCULO
                $resultEstoqueCC = $classProdutoQtde->produtoQtdeCC($arrPedidoItem[$i]['ITEMESTOQUE'], $cce);

                // $IndexCC = substr($this->m_empresacentrocusto, 0, 1)-1;
                $quantAtual = $resultEstoqueCC[0]['ESTOQUE'];
                $quantReservada = $resultEstoqueCC[0]['RESERVA'];
                $quantEncomenda = $resultEstoqueCC[0]['ENCOMENDA'];
                $disponivel = $quantAtual - $quantReservada;

                // FIM 


                // $produtoQuant = $classProdutoQtde->produtoQtde($arrPedidoItem[$i]['ITEMESTOQUE'], $cce);
                // $estoque = 0;
                // $reserva = 0;
                // for($q=0;$q<count($produtoQuant);$q++){
                //     // if ($produtoQuant[$q]['STATUS'] == 0):
                //     //     $estoque = $produtoQuant[$q]['QUANTIDADE'];
                //     // else:    
                //     //     $reserva = $produtoQuant[$q]['QUANTIDADE'];
                //     // endif;
                //     switch ($produtoQuant[$q]['STATUS']){
                //         case 0: // Estoque atual
                //             $quantAtual = $produtoQuant[$q]['QUANTIDADE'];
                //         break;
                //         case 1: // Quant reservada, não entregue
                //             $quantReservada = $produtoQuant[$q]['QUANTIDADE'];
                //         break;
                //         case 2: // Quant Encomenda
                //             $quantEncomenda = $produtoQuant[$q]['QUANTIDADE'];
                //         break;
                //         default :
                //             $quantAtual = 0;
                //             $quantReservada = 0;
                //             $quantEncomenda = 0;
                //     }  

                // }
                // $disponivel = $quantAtual - $quantReservada;
                if ($arrPedidoItem[$i]['QTSOLICITADA'] > $disponivel) {
                    $msg .= "Item " . $arrPedidoItem[$i]['ITEMESTOQUE'] . " -> " . $arrPedidoItem[$i]['DESCRICAO'] . " com quantidade indisponível-> Qt Atual: " . $disponivel . "<BR>";
                }
            }

            return $msg;
        } catch (Error $e) {
            throw new Exception($e->getMessage() . "Pedido inconsistente ");
        } catch (Exception $e) {
            throw new Exception($e->getMessage() . "Pedido inconsistente ");
        }
    }

    /**
     * <b> Rotina para alterar situação do pedido para aprovação, atualizando a data de emissão </b>
     * @name validaPedido
     * @param int idPedido
     * @return VARCHAR msg com as inconsistência para apresentar no pedido.
     */
    public function alteraPedidoAprovacao($id)
    {

        $sql = "UPDATE fat_pedido SET ";
        //$sql .= "EMISSAO = '".date("Y-m-d")."',  ";
        //$sql .= "HORAEMISSAO = '".date("H:i:s")."',  ";  
        $sql .= "situacao = 10, ";
        $sql .= "userchange = " . $this->m_userid . ", ";
        $sql .= "datechange = CURRENT_TIMESTAMP() ";
        $sql .= "WHERE id = " . $id . ";";
        //echo strtoupper($sql)."<BR>";

        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }

    /**
     * <b> Rotina para alterar situação do pedido para encomanda, retira prazo de entrega, atualizando a data de emissão </b>
     * @name validaPedido
     * @param int idPedido
     * @return VARCHAR msg com as inconsistência para apresentar no pedido.
     */
    public function alteraPedidoEncomenda($id, $genero=null) {

        $sql = "UPDATE fat_pedido SET ";
        //$sql .= "dataentrega = null, ";
        //$sql .= "prazoentrega = '', ";
        $sql .= "emissao = '".date("Y-m-d")."',  ";
        $sql .= "horaemissao = '".date("H:i:s")."',  ";
        $sql .= "genero = '" .$genero. "', ";     
        $sql .= "situacao = 13 ,";
        $sql .= "userchange = " .$this->m_userid. ", ";
        $sql .= "datechange = CURRENT_TIMESTAMP() ";
        $sql .= "WHERE id = ".$id. ";";
        //echo strtoupper($sql)."<BR>";
    
        $banco = new c_banco;
        $result = $banco->exec_sql($sql);
        $banco->close_connection();
        return $banco->resultado;
    }
}    //	END OF THE CLASS
