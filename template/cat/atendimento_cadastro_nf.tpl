<script type="text/javascript" src="{$pathJs}/cat/s_atendimento_nf.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="cat">   
            <input name=form                type=hidden value="atendimento_nf">   
            <input name=opcao               type=hidden value="{$opcao}">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=letra               type=hidden value={$letra}>
            <input name=id                  type=hidden value={$id}>
            <input name=cliente             type=hidden value={$cliente}>
            <input name=descCondPgto        type=hidden value="{$descCondPgto}">
            <input name=condPgto_id         type=hidden value="{$condPgto_id}">
            <input name=dadosFinanceiros    type=hidden value={$dadosFinanceiros}>
            <input name=dadosParcelas       type=hidden value={$dadosParcelas}>
            <input name=dadosNf             type=hidden value={$dadosNf}>
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                    Ordem de Serviço {$id} - Lançamento Financeiro
                    </h2>
                    {include file="../bib/msg.tpl"}                    

                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastraLancamentoFin({$id});">
                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('');">
                                <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span><span> Listar OS</span></button>
                        </li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content small">
                    <div class="row">
                        
                        
                        
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="serie">Serie Documento Financeiro</label>
                            <div class="panel panel-default">
                                <input class="form-control" type="text" maxlength="3" id="serieDocto" name="serieDocto" value={$serieDocto}>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <label for="nf">N&uacute;mero Documento Financeiro</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control" id="numDocto" maxlength="11" name="numDocto" value="{$numDocto}">
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <label for="data">Data Fechamento</label>
                            <div class="panel panel-default">
                                <input type="text" class="form-control" id="dataFechamento" name="dataFechamento" disabled value="{$dataFechamento}">
                            </div>
                        </div> 

                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <label for="idNatop">Situação</label>
                            <div class="panel panel-default">
                                    <select id="situacao" name="situacao" class="form-control">
                                        {html_options values=$situacao_ids selected=$situacao output=$situacao_names}
                                    </select>
                            </div>
                        </div>
                            
                    </div>
                    

                    <div class="row">
                        <div class="col-md-8 col-sm-8 col-xs-8">
                                <label for="clienteNome">Cliente</label>
                                <div class="panel panel-default">
                                    <input type="text" class="form-control" id="clienteNome" name="clienteNome" disabled value="{$clienteNome}">
                                </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-3">
                            <label for="centroCusto">Centro de Custo</label>
                            <div class="panel panel-default">
                                <select id="centroCusto" name="centroCusto" class="form-control" onChange="javascript:submitAtual({$id});">
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <label for="genero">G&ecirc;nero</label>
                            <div class="panel panel-default">
                                <select name="genero" class="form-control">
                                    {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <label for="condPgto">Condi&ccedil;&atilde;o de Pagamento</label>
                            <div class="panel panel-default">
                                <select name="condPgto" class="form-control" onChange="javascript:submitAtual({$id});">
                                    {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3 ">
                            <label for="total">T O T A L</label>
                            <div class="panel panel-default left_col">
                                <input class="form-control" type="text" id="total" name="total" readonly value={$total}>
                            </div>
                        </div>


                    </div>
                    
                </div>
               
                <div class="x_panel">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="parcelas-tab" role="tab" data-toggle="tab" aria-expanded="true">Parcelas</a>
                        </li>
                        <li role="presentation"><a href="#tab_content2" id="pecas-tab" role="tab" data-toggle="tab" aria-expanded="true">Peças</a>
                        </li>
                        <li role="presentation"><a href="#tab_content3" id="servicos-tab" role="tab" data-toggle="tab" aria-expanded="true">Serviços</a>
                        </li>
                        <li role="presentation"><a href="#tab_content4" id="obs-tab" role="tab" data-toggle="tab" aria-expanded="true">Observações</a>
                        </li>
                      </ul>
                      <div id="myTabContent" class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">

                        <!-- panel tabela dados -->  
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <table id="datatable-buttons-1" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>Parcela</th>
                                            <th>Data Vencimento</th>
                                            <th>Valor</th>
                                            <th>Tipo Documento</th>
                                            <th>Conta Recebimento</th>
                                            <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                            <th>Obs</th>
                                            <th>Opções</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$fin}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td> {$fin[i].PARCELA} </td>
                                                <td> 
                                                    <input class="form-control" type="text" id="venc" name="venc{$fin[i].PARCELA}" value={$fin[i].VENCIMENTO|date_format:"%d/%m/%Y"} >
                                                </td>
                                                <td> 
                                                    <input class="form-control money" type="text" id="valor" name="valor{$fin[i].PARCELA}" value={$fin[i].VALOR|number_format:2:",":"."}>

                                                </td>
                                                <td>
                                                    <select id="idTipoDoc" name="tipo{$fin[i].PARCELA}" class="form-control">
                                                        {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                    </select>
                                                </td>
                                                <td>
                                                    <select id="idConta" name="conta{$fin[i].PARCELA}" class="form-control">
                                                        {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    <select id="idSitucao" name="situacao{$fin[i].PARCELA}" class="form-control">
                                                        {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                    </select>
                                                </td>
                                                <td> 
                                                    
                                                    
                                                    <input class="form-control" type="text" id="obs" name="obs{$fin[i].PARCELA}" value={$fin[i].OBS}>

                                                </td>
                                                <td> 
                                                   <button type="button" class="btn btn-success"  {if $fin[i].ID eq ''} disabled {/if}
                                                             onClick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra={$fin[i].ID}');">
                                                            <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span><span> Boleto </span></button>

                                                </td>
                                            </tr>
                                        <p>
                                    {/section} 

                                    </tbody>
                                </table>
                              </div>       

                        </div>
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content2" aria-labelledby="profile-tab">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2 col-xs-2">
                                        <label for="serie">Serie NF</label>
                                        <div class="panel panel-default">
                                            <input class="form-control" type="text" maxlength="3" id="serieDoctoNf" name="serieDoctoNf" value={$serieDoctoNf}>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-xs-3">
                                        <label for="nf">N&uacute;mero NF</label>
                                        <div class="panel panel-default">
                                            <input type="text" readonly class="form-control" id="numDoctoNf" maxlength="11" name="numDoctoNf" value="{$numDoctoNf}">
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-3 col-xs-3">
                                        <label for="nf">Modelo NF</label>
                                        <div class="panel panel-default">
                                            <input type="text" class="form-control" id="modeloDocto" maxlength="11" name="modeloDocto" value="{$modeloDocto}">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-3 col-xs-3">
                                        <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                                        <div class="panel panel-default">
                                                <select id="idNatop" name="idNatop" class="form-control">
                                                    {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                                </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-12 col-xs-12 has-feedback">
                                        <label style="visibility:hidden">btn salva NF</label>
                                        <button type="button" {if $disabledBtnNf eq 'true'} disabled {/if} class="btn btn-success btn-sm"  onClick="javascript:submitCadastraNf({$id});">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span><span> Cadastrar NF </span></button>                            
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12 line-formated">
                                        <label for="conta">Link acesso NFE</label>
                                        <div class="input-group line-formated">
                                            <input type="text" class="form-control input-sm" id="linkNfe" name="linkNfe" placeholder="link Nfe" required
                                                value="{$linkNfe}" readonly>
                                            <span class="input-group-btn">
                                                <a {if $linkNfe neq ''}href="{$linkNfe}"{/if}  target="blank">
                                                <button type="button" class="btn btn-primary btn-sm">
                                                    <span class="glyphicon glyphicon-open-file" aria-hidden="true"></span>
                                                </button>
                                                </a>
                                            </span>                                
                                        </div>
                                    </div>
                                </div>
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <table id="datatable-buttons-2" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>Cód</th>
                                             <th>Cód. Nota</th>
                                            <th>Descrição</th>
                                            <th>Unidade</th>
                                            <th>Quantidade</th>
                                            <th>valor Unitário</th>
                                            <th>Valor Desc</th>
                                            <th>% Desc</th>
                                            <th>TOTAL</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lancPecas}
                                            <tr>
                                                <td> {$lancPecas[i].CODPRODUTO} </td>
                                                <td> {$lancPecas[i].CODPRODUTONOTA} </td>
                                                <td> {$lancPecas[i].DESCRICAO} </td>
                                                <td> {$lancPecas[i].UNIDADE} </td>
                                                <td> {$lancPecas[i].QUANTIDADEUTILIZADA|number_format:2:",":"."} </td>
                                                <td> {$lancPecas[i].VALORUNITARIO|number_format:2:",":"."} </td>
                                                <td> {$lancPecas[i].DESCONTO|number_format:2:",":"."} </td>
                                                <td> {$lancPecas[i].PERCDESCONTO|number_format:2:",":"."} </td>
                                                <td> {$lancPecas[i].TOTALUTILIZADO|number_format:2:",":"."} </td>
                                                </tr>
                                            <p>
                                        {/section} 
                                    </tbody>
                                </table>
                              </div>       

                        </div>

                        <div role="tabpanel" class="tab-pane fade small" id="tab_content3" aria-labelledby="profile-tab">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                
                                <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                                <table id="datatable-buttons-2" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>Cód</th>
                                            <th>Descrição</th>
                                            <th>Unidade</th>
                                            <th>Quantidade</th>
                                            <th>Valor Untário</th>
                                            <th>TOTAL</th>                                      
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {section name=i loop=$lancServicos}
                                            <tr>
                                                <td> {$lancServicos[i].ID} </td>
                                                <td> {$lancServicos[i].DESCSERVICO} </td>
                                                <td> {$lancServicos[i].UNIDADE} </td>
                                                <td> {$lancServicos[i].QUANTIDADE|number_format:2:",":"."} </td>
                                                <td> {$lancServicos[i].VALUNITARIO|number_format:2:",":"."} </td>
                                                <td> {$lancServicos[i].TOTALSERVICO|number_format:2:",":"."} </td>
                                            </tr>
                                            <p>
                                        {/section} 
                                    </tbody>
                                </table>
                              </div>       

                        </div>
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content4" aria-labelledby="profile-tab">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label for="obs" >Observa&ccedil;&atilde;o</label>
                                <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="3" >{$obs}</textarea>
                            </div> 
                        </div>
                    </div>
                </div> <!-- tabpanel -->
            </div> <!-- panel -->


                                
              <div class="ln_solid"></div>
                        
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"} 

    <script>
      $(function() {
        $('#dataEntrada').daterangepicker({
          singleDatePicker: true,
          calender_style: "picker_1",
          locale: {
                format: 'DD/MM/YYYY',
                daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            }
          
        });

      });
    </script> 

    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowZero: true
        });        
     });
    </script>  