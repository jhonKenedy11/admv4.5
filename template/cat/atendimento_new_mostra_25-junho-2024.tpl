    
    <script type="text/javascript" src="{$pathJs}/cat/s_atendimento_new.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                

        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Ordem de Serviço
                    </h2>
                    {include file="../bib/msg.tpl"}                    
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Novo Atendimento</span>
                            </button>
                        </li>
                         <li>
                             <a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                         <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                               <i class="fa fa-print"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                            </ul>
                         </li>
                         <li>
                             <a class="close-link"><i class="fa fa-close"></i></a>
                         </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod                     type=hidden value="cat">   
                        <input name=form                    type=hidden value="atendimento_new">   
                        <input name=id                      type=hidden value="">
                        <input name=opcao                   type=hidden value={$opcao}>
                        <input name=letra                   type=hidden value={$letra}>
                        <input name=submenu                 type=hidden value={$subMenu}>
                        <input name=fornecedor              type=hidden value="">
                        <input name=pessoa                  type=hidden value={$pessoa}>
                        <input name=codProduto              type=hidden value={$codProduto}>
                        <input name=unidade                 type=hidden value={$unidade}>
                        <input name=situacao                type=hidden value={$situacao}>
                        <input name=situacoesAtendimento    type=hidden value={$situacoesAtendimento}>
                        <input name=dataIni                 type=hidden value={$dataIni}>
                        <input name=dataFim                 type=hidden value={$dataFim}>
                        <input name=print                   type=hidden value={$print}>
                        <input name=idOs                    type=hidden value={$idOs}>

                        <div class="form-group col-md-2 col-sm-6 col-xs-6">
                                <label>Num Atendimento</label>
                                <input class="form-control" id="numAtendimento" name="numAtendimento" placeholder="Num Atendimento."  value={$numAtendimento} >
                        </div>
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                                <label>Situação</label>
                                <select class="select2_multiple form-control" multiple="multiple" id="situacaoAtendimento" name="situacaoAtendimento">
                                    {html_options values=$situacaoAtendimento_ids output=$situacaoAtendimento_names selected=$situacaoAtendimento_id}
                              </select>
                        </div>
      
                        <div class="form-group col-md-3 col-sm-6 col-xs-6">
                            <label class="">Data Abertura - Fechamento</label>
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                            <div>
                                <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                value="{$dataIni} - {$dataFim}">
                            </div>
                        </div>
                        <div class="form-group col-md-4 col-sm-12 col-xs-12">
                            <label class="">Cliente</label>
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
                                    <th>Atend.</th>
                                    <th>Tipo</th>
                                    <th>Situação</th>
                                    <th>Cliente</th>
                                    <th>Equipamento</th>
                                    <th>Abertura</th>
                                    <th>Fechamento</th>
                                    <th style="width: 60px;">Total</th>
                                    <th style="width: 108px;">Manuten&ccedil;&atilde;o</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    <tr>
                                        <td> {$lanc[i].ID} </td>
                                        <td> {$lanc[i].TIPODESC} </td>
                                        <td> {$lanc[i].SITUACAODESC} </td>
                                        <td> {$lanc[i].NOME} </td>
                                        <td> {$lanc[i].DESCEQUIPAMENTO} </td>
                                        <td> {$lanc[i].DATAABERATEND|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].DATAFECHATEND|date_format:"%d/%m/%Y"} </td>
                                        <td align=right> {$lanc[i].VALORTOTAL|number_format:2:",":"."} </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true" data-toggle="tooltip" title="Editar"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitCancelar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-remove" aria-hidden="true" data-toggle="tooltip" title="Cancelar"></span></button>
                                            <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitCadastrarAtendimentoPedido('{$lanc[i].ID}');"><span class="glyphicon glyphicon glyphicon-refresh" aria-hidden="true" data-toggle="tooltip" title="Cadastro OS Pedido"></span></button>
                                            <button type="submit" class="btn btn-dark btn-xs" onclick="javascript:submitCadastrarImagemOS('{$lanc[i].ID}');"><span class="glyphicon glyphicon-camera" aria-hidden="true" data-toggle="tooltip" title="Imagem"></span></button>
                                            <br>
                                            <button type="button" class="btn btn-info btn-xs" onclick="javascript:imprimeOs('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Imprimir"></span></button>
                                            <button type="button" class="btn-group btn-group-toggle btn-xs" onclick="javascript:imprimeOsMecanico('{$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Imprimir OS Mecanico"></span></button>
                                            <button type="button" class="btn btn-success btn-xs" onclick="javascript:abrir('index.php?mod=cat&form=orcamento_imprime&opcao=imprimir&id={$lanc[i].ID}');"><span class="glyphicon glyphicon glyphicon-briefcase" aria-hidden="true" data-toggle="tooltip" title="Imprimir orçamento"></span></button>
                                        </td>
                                    </tr>
                                <p>
                            {/section} 
                            <div id="popup" title="IMPRIMIR ORDEM DE SERVIÇO!"></div>
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
        $("#situacaoAtendimento.select2_multiple").select2({
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
    document.addEventListener("keypress", function (e) {
        if (e.keyCode === 13) {
            submitLetra();
        }
    });
    </script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
<style>
.ui-widget-content{
    min-height: 1px !important;
    border:#ffff !important;
}
.ui-helper-clearfix{
    margin-bottom: -6px;
}
</style>

