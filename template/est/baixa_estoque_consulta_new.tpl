<style>
.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_baixa_estoque_new.js"> </script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  
<!-- page content -->
    <div class="right_col" role="main">                

        <div class="">
            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Movimentação de Estoque Entrada - Consulta
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
                                                <strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}                          
                        </strong>
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetraConsulta();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <button type="button" class="btn btn-warning btn-xs" onClick="javascript:limpaDadosFormConsulta();" > <!-- onClick="javascript:submitAgruparPedidos();"  data-toggle="modal"  data-target="#modalAgrupamentoPed"-->
                                        <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span><span> Limpar Dados Formulário</span>
                                    </button>
                                </li>
                            </ul>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod                       type=hidden value="est">   
                        <input name=form                      type=hidden value="baixa_estoque_new">   
                        <input name=id                        type=hidden value="{$id}" id="id">
                        <input name=idnf                      type=hidden value="" >
                        <input name=opcao                     type=hidden value={$opcao}>
                        <input name=letra                     type=hidden value={$letra}>
                        <input name=submenu                   type=hidden value={$subMenu}>
                        <input name=fornecedor                type=hidden value={$pessoa}> 
                        <input name=pessoa                    type=hidden value={$pessoa}> 
                        <input name=codProduto                type=hidden value={$codProduto}>
                        <input name=unidade                   type=hidden value={$unidade}>
                        <input name=descProduto               type=hidden value={$descProduto}>
                        <input name=quantAtual                type=hidden value={$quantAtual}> 
                        <input name=valorVenda                type=hidden value={$valorVenda}> 
                        <input name=uniFracionada             type=hidden value="{$uniFracionada}">
                        <input name=dataIni                   type=hidden value={$dataIni}> 
                        <input name=dataFim                   type=hidden value={$dataFim}> 
                        <input name=genero                    type=hidden value={$genero}> 
                        <input name=transportador             type=hidden value={$transportador}> 

                        <div class="form-group line-formated">                   
                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>N&uacute;mero NF</label>
                                <input class="form-control" id="numNf" name="numNf" placeholder="N&uacute;mero da NF."  value={$numNf} >
                            </div>
                            <div class="form-group col-md-2 col-sm-1 col-xs-1">
                                <label>Série</label>
                                <input class="form-control" id="serieNf" name="serieNf" placeholder="Série da NF"  value={$serieNf} >
                            </div>
                            <div class="col-md-3 col-sm-12 col-xs-12 has-feedback">
                                <label class="">Per&iacute;odo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                                </div>
                                                            
                            </div>
                            <div class="col-lg-5 col-sm-10 col-xs-10 text-left line-formated">
                                <label>Produto</label>
                                <div class="input-group line-formated">
                                    <input READONLY      
                                    class="form-control" placeholder="Produto" id="pesProduto" 
                                    name="pesProduto" value="{$pesProduto}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisar&from=baixa_estoque', 'produto');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span> 
                                </div>
                            </div>
                        </div>
                  </div>
                        
           

          <!--div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
            <div class="panel">
                <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    <h4 class="panel-title">Filtros <i class="fa fa-chevron-down"></i></h4>
                </a>
                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                    <div class="x_panel">
                        <div class="form-group">
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                                <div class="panel panel-default">
                                        <select id="idNatop" name="idNatop" class="form-control" >
                                            {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                        </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="modFrete">Finalidade Emissão</label>
                                    <div class="panel panel-default">
                                        <select name=finalidadeEmissao class="form-control form-control-sm">
                                             {html_options values=$finalidadeEmissao_ids selected=$finalidadeEmissao_id output=$finalidadeEmissao_names}
                                        </select>
                                    </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="modFrete">Modalidade Frete</label>
                                    <div class="panel panel-default">
                                        <select name="modFrete" class="form-control form-control-sm">
                                            {html_options values=$modFrete_ids selected=$modFrete_id output=$modFrete_names}
                                        </select>
                                    </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="genero" >G&ecirc;nero</label>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control" id="descgenero" name="descgenero" placeholder="Genero" required="required"
                                           value="{$descGenero}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=fin&form=genero&opcao=pesquisar');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="transpNome">Transportador</label>
                                <div class="input-group">
                                    <input readonly type="text" class="form-control" id="transpNome" name="transpNome" placeholder="Transportador que realiza o frete"
                                           value="{$transpNome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisartransportador');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                             
                        </div>
                                        
                                        
                                    
                    </div>
                </div>
            </div> 
        </div-->  
                        
        </div>   
        {include file="baixa_estoque_alterar_quant_modal.tpl"}                           
      </form>
      </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        
        </div> <!-- div class="x_panel" = painel principal-->

        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
            <div class="x_panel small">
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>Data</th>   
                            <th>Serie</th>
                            <th>Numero</th>
                            <th>Usuario</th>                                    
                            <th>Produto</th> 
                            <th>Unidade</th>                                    
                            <th>Quantidade</th>
                            <th>Unitario</th>
                            <th>Total</th>
                            <th>Observacoes</th>                                         
                            <th style="width: 100px;">Manuten&ccedil;&atilde;o</th>
                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].DATASAIDAENTRADA|date_format:"%d/%m/%Y"} </td>
                                <td> {$lanc[i].SERIE} </td>
                                <td> {$lanc[i].NUMERO} </td>
                                <td> {$lanc[i].NOMEUSUARIO} </td>
                                <td> {$lanc[i].NOMEPRODUTO} </td>
                                <td> {$lanc[i].UNIDADE} </td>
                                <td> {$lanc[i].QUANT|number_format:2:",":"."} </td>
                                <td> {$lanc[i].NFPUNITARIO|number_format:2:",":"."} </td>
                                <td> {$lanc[i].NFPTOTAL|number_format:2:",":"."} </td>
                                <td> {$lanc[i].OBS} </td>
                                <td >
                                    <button type="button" title="Alterar" class="btn btn-primary btn-xs" onclick="javascript:alterarQuantModal(this, '{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                    <button type="button" title="Deletar" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                </td>
                            </tr>
                        {/section} 

                    </tbody>
                </table>

                        
              </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
          </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        



    {include file="template/database.inc"}  
    
   <!-- bootstrap-daterangepicker -->
    <script src="js/moment/moment.min.js"></script>
    <script src="js/datepicker/daterangepicker.js"></script>
    

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


 <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>

     $(document).ready(function(){
        $(".money").maskMoney({
         decimal: ",",
         thousands: ".",
         allowNegative: true
        });
    });
    </script>