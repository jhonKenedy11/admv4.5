<link rel="stylesheet" href="{$bootstrap}/css/switchery/switchery.min.css" />
<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda.js"> </script>

    <!-- page content -->
    <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Pedidos de Venda</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="">   
            <input name=form                type=hidden value="">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesq                type=hidden value={$pesq}>
            <input name=itensPedido         type=hidden value={$itensPedido}>
            <input name=fornecedor          type=hidden value="">
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $subMenu eq "cadastrar"}
                            Cadastro 
                        {else}
                            Altera&ccedil;&atilde;o 
                        {/if} 
                        {if $mensagem neq ''}
                            {if $tipoMsg eq 'sucesso'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}
                    </h2>

            <div class="row">
                <div class="col-lg-3 text-left">
                    <label for="pesProduto">Produto</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite o nome do produto para pesquisar." id="pesProduto" name="pesProduto" value={$pesProduto} >
                    </div>
                </div>
                <div class="col-lg-3 text-left">
                    <label>Grupo</label>
                    <div class="panel panel-default">
                        <SELECT class="form-control" name="grupo"> 
                            {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                        </SELECT>
                    </div>
                </div>
                <div class="col-lg-1 text-left">
                    <label> </label>
                    <div>
                        <button type="button" class="btn btn-success" onClick="javascript:submitBuscar('');"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar</button>
                    </div>
                </div>
                <div class="col-lg-2 text-left">
                    <label> </label>
                    <div>
                        <button type="button" class="btn btn-success" onClick="javascript:submitIncluirItem('');"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Incluir item</button>
                    </div>
                </div>
                <div class="col-lg-1 text-left">
                    <label for="promocoes">Promoções</label>
                    <div class="panel" >
                        <input type="checkbox" class="js-switch" id="promocoes" name="promocoes" {if $promocoes eq 'S'} checked {/if} value="{$promocoes}" /> 
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-lg-3 text-left">
                    <label for="itensQtde">Quantidade</label>
                    <div class="form-group">
                        <input class="form-control" placeholder="Digite a qtde a incluir." id="itensQtde" name="itensQtde" value={$itensQtde} >
                    </div>
                </div>
            </div>

            <div class="container body">
                <div class="main_container">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">

                            <div class="x_content">

                                <table id="datatable-buttons" class="table table-striped table-bordered">
                                    <thead>
                                        <tr style="background: #2A3F54; color: white;">
                                            <th style="width: 20px;">Selecionar</th>
                                            <th>Descrição</th>
                                            <th>Valor Unitário</th>
                                            <th>Ref.</th>

                                        </tr>
                                    </thead>
                                    <tbody>


                                        {section name=i loop=$lancPesq}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td align="center"> <input type="checkbox"  name="itemCheckbox" id="{$lancPesq[i].CODIGO}" value="{$lancPesq[i].CODIGO}"></td>
                                                <td> {$lancPesq[i].DESCRICAO} </td>
                                                <td> {$lancPesq[i].VENDA} </td>
                                                <td> {$lancPesq[i].CODIGO} </td>
                                            </tr>
                                        <p>
                                        {sectionelse}
                                        <td colspan="3">n&atilde;o h&aacute; Contas Cadastradas</td>
                                    {/section} 

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container body">
                <div class="main_container">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <b>Carrinho de Compra</b>
                            <div class="x_content">

                                <table id="datatable-buttons2" class="table table-striped table-bordered">
                                    <thead>
                                        <tr style="background: #2A3F54; color: white;">
                                            <th>Ref.</th>
                                            <th>Descrição</th>
                                            <th>Qtde</th>
                                            <th>Valor Unitário</th>
                                            <th>Valor Total</th>
                                            <th>Cancelar</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lancItens}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td> {$lancItens[i].ITEMESTOQUE} </td>
                                                <td> {$lancItens[i].DESCRICAO} </td>
                                                <td> {$lancItens[i].QTSOLICITADA} </td>
                                                <td> {$lancItens[i].UNITARIO} </td>
                                                <td> {$lancItens[i].TOTAL} </td>
                                                <td> <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> </td>
                                            </tr>
                                        <p>
                                        {sectionelse}
                                        <td>n&atilde;o h&aacute; Contas Cadastradas</td>
                                    {/section} 

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 text-left">
                    <div>
                        <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('');"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Concluir </button>
                        <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancelar</button>
                        <button type="button" class="btn btn-warning" onClick="javascript:submitVoltar('');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Imprimir</button>
                    </div>
                </div>

            </div>



        </form>
    </body>        
    <script src="{$bootstrap}/js/switchery/switchery.min.js"></script>
    <!-- Arquivos para datagrid -->
    <script src="{$bootstrap}/js/datatables/jquery.dataTables.min.js"></script>
    <script src="{$bootstrap}/js/datatables/dataTables.bootstrap.js"></script>
    <script src="{$bootstrap}/js/datatables/dataTables.buttons.min.js"></script>
    <script src="{$bootstrap}/js/datatables/buttons.bootstrap.min.js"></script>
    <script src="{$bootstrap}/js/datatables/jszip.min.js"></script>
    <script src="{$bootstrap}/js/datatables/pdfmake.min.js"></script>
    <script src="{$bootstrap}/js/datatables/vfs_fonts.js"></script>
    <script src="{$bootstrap}/js/datatables/buttons.html5.min.js"></script>
    <script src="{$bootstrap}/js/datatables/buttons.print.min.js"></script>
    <script src="{$bootstrap}/js/datatables/dataTables.fixedHeader.min.js"></script>
    <script src="{$bootstrap}/js/datatables/dataTables.keyTable.min.js"></script>
    <script src="{$bootstrap}/js/datatables/dataTables.responsive.min.js"></script>
    <script src="{$bootstrap}/js/datatables/responsive.bootstrap.min.js"></script>
    <script src="{$bootstrap}/js/datatables/dataTables.scroller.min.js"></script>
    <script src="{$bootstrap}/js/pace/pace.min.js"></script>

    <script>
                            var handleDataTableButtons = function () {
                                "use strict";
                                0 !== $("#datatable-buttons").length && $("#datatable-buttons").DataTable({
                                    dom: "Bfrtip",
                                    buttons: [{
                                            extend: "copy",
                                            className: "btn-sm"
                                        }, {
                                            extend: "csv",
                                            className: "btn-sm"
                                        }, {
                                            extend: "excel",
                                            className: "btn-sm"
                                        }, {
                                            extend: "pdf",
                                            className: "btn-sm"
                                        }, {
                                            extend: "print",
                                            className: "btn-sm"
                                        }],
                                    responsive: 1
                                })
                            },
                                    TableManageButtons = function () {
                                        "use strict";
                                        return {
                                            init: function () {
                                                handleDataTableButtons()
                                            }
                                        }
                                    }();

    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({
                keys: true
            });
            $('#datatable-responsive').DataTable();
            $('#datatable-scroller').DataTable({
                ajax: "js/datatables/json/scroller-demo.json",
                deferRender: true,
                scrollY: 380,
                scrollCollapse: true,
                scroller: true
            });
            var table = $('#datatable-fixed-header').DataTable({
                fixedHeader: true
            });
        });
        TableManageButtons.init();
    </script>
</body>

</html>