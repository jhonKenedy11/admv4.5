    <script type="text/javascript" src="{$pathJs}/coc/s_ordem_compra.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                

        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <div id="msgAlert">
                    <h2>Ordem de Compras
                        
                        <strong>
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>Sucesso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>
                                {else}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>Aviso!</strong>&nbsp;{$mensagem}</div>
                                            </div
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}
                        </strong>
                    </h2>
                    </div>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Nova Ordem de Compra </span>
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="coc">   
                        <input name=form          type=hidden value="ordem_compra">   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=fornecedor    type=hidden value="">
                        <input name=pessoa        type=hidden value={$pessoa}>
                        <input name=codProduto    type=hidden value={$codProduto}>
                        <input name=unidade       type=hidden value={$unidade}>
                        <input name=situacao      type=hidden value={$situacao}>
                        <input name=dataIni       type=hidden value={$dataIni}>
                        <input name=dataFim       type=hidden value={$dataFim}>

                        <div class="form-group col-md-2 col-sm-6 col-xs-6">
                                <label>Ordem de Compra</label>
                                <input class="form-control" id="numOrdemCompra" name="numOrdemCompra" placeholder="Número da ordem de compra."  value={$numOrdemCompra} >
                        </div>
                         <div class="col-md-2 col-sm-12 col-xs-12">
                                <label>Numero NF</label>
                                <input class="form-control" id="numDocto" name="numDocto" placeholder="Número NF."  value={$numDocto} >
                        </div>
      
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label class="">Per&iacute;odo</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                            <div>
                                <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>
                        
                        <div class="form-group col-md-4 col-sm-6 col-xs-6">
                            <label>Situação</label>
                              <select class="select2_multiple form-control" multiple="multiple" id="situacaoCombo" name="situacaoCombo">
                                {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                              </select>
                        </div>

                        
                        <div class="form-group">
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label class="">Conta</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" readonly id="nome" name="nome" placeholder="Conta" value="{$nome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                           
                            
                        </div>     

                          {include file="ordem_compra_email_modal.tpl"}
                        </form>

                              
                    </div>
                          
                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->

              <!-- panel tabela dados -->  
              <div class="responsive">
                <div class="x_panel">
                      <table id="datatable-buttons" class="table table-bordered jambo_table">
                      <!--table class="table table-striped jambo_table bulk_action"-->
                            <thead>
                                <tr class="headings">
                                    <th style="width: 100px;">Ordem Compra</th>
                                    <th style="width: 60px;">Emissão</th>
                                    <th>Situação</th>
                                    <th>Numero NF</th>
                                    <th>Serie NF</th>
                                    <th>Cliente</th>
                                    <th>CNPJ</th>
                                    <th style="width: 100px;">Total</th>
                                    <th style="width: 140px;">Manuten&ccedil;&atilde;o</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    {assign var="perc" value={$lanc[i].SITUACAO*20}+20}
                                    <tr>
                                        <td> {$lanc[i].ID} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].DESCSITUACAO} </td>
                                        <td> {$lanc[i].NUMERONF} </td>
                                        <td> {$lanc[i].SERIENF} </td>
                                        <td> {$lanc[i].NOME} </td>
                                        <td> {$lanc[i].CNPJCPF} </td>
                                        <td align=right> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true" data-toggle="tooltip" title="Editar"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].ID}' , '{$lanc[i].SITUACAO}');"><span class="glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip" title="Excluir"></span></button>
                                            <button type="button" class="btn btn-success btn-xs" onClick="javascript:submitGerarNFC('{$lanc[i].ID}', '{$lanc[i].SITUACAO}');"><span class="glyphicon glyphicon-flag" aria-hidden="true" data-toggle="tooltip" title="Gerar NFC"></span></button>
                                            <button type="button" class="btn btn-info btn-xs" onclick="javascript:abrir('index.php?mod=coc&form=ordem_compra_imprime&opcao=imprimir&parm={$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Impressão"></span></button>                                       
                                            <button type="button" class="btn btn-info btn-xs" data-toggle="modal" data-backdrop="static" data-target="#modalEmail" onclick="javascript:buscaEmailCliente('{$lanc[i].ID}','{$lanc[i].CLIENTE}');" ><span class="glyphicon glyphicon-envelope"  aria-hidden="true" title="Enviar Email"></span></button>                                      
                                            
                                        <!--
                                            <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitEstornar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-refresh" aria-hidden="true" data-toggle="tooltip" title="Estornar"></span></button>
                                            <button type="button" class="btn btn-success btn-xs" onclick="javascript:submitEntregue('{$lanc[i].ID}');"><span class="glyphicon glyphicon-flag" aria-hidden="true" data-toggle="tooltip" title="Entregue"></span></button>
                                            <button type="submit" class="btn btn-info btn-xs" onclick="javascript:abrir('index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Impressão"></span></button>                                       
                                        -->
                                        </td>
                                    </tr>
                                <p>
                            {/section} 

                            </tbody>
                        </table>
                       </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    <!-- /Datatables -->
    
    
    {include file="template/database.inc"}
    

<!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>

    <!-- Select2 -->
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    
    <script>
      $(document).ready(function() {
        $("#situacao.select2_multiple").select2({
          placeholder: "Escolha a Situação",
          allowClear: true
        });

      });
    </script>

    <!-- daterangepicker -->
    <script type="text/javascript">
        $('input[name="dataConsulta"]').daterangepicker(
        {
            startDate: moment("{$dataIni}", "DD/MM/YYYY"),
            endDate: moment("{$dataFim}", "DD/MM/YYYY"),
            ranges: {
                'Hoje': [moment(), moment()],
                'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Confirma',
                cancelLabel: 'Limpa',
                fromLabel: 'Início',
                toLabel: 'Fim',
                customRangeLabel: 'Calendário',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                firstDay: 1
            }

        }, 
        //funcao para recuperar o valor digirado        
        function(start, end, label) {
            f = document.lancamento;
            f.dataIni.value = start.format('DD/MM/YYYY');
            f.dataFim.value = end.format('DD/MM/YYYY');            
        });
    </script>    

        
    <script>
      $(document).ready(function() {
        $("#situacaoCombo.select2_multiple").select2({
          placeholder: "Escolha a Situação",
          allowClear: true,
          width: "95%"
        });

      });
    </script>
    
    <script>
    document.addEventListener("keypress", function (e) {
        if (e.keyCode === 13) {
            submitLetra();
        }
    });
    </script>

