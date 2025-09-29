<style> 
.select-read-only{
    background: #eee; 
    pointer-events: none;
    touch-action: none;
}
.swal-modal{
    width: 600px !important;
}
.form-control, .x_panel{
    border-radius: 5px;
}
#radiosVenda[aria-label] {
position: relative;
}

#radiosVenda[aria-label]::after {
content: attr(aria-label);
display: none;
position: absolute;
top: -40px;
left: -140px;
z-index: 5000;
pointer-events: none;
padding: 5px 3px 5px 8px;
text-decoration: none;
font-size: 11px;
color: #fff;
background-color: #aa2424;
border-radius: 5px;
font-weight: bold;
}

#radiosVenda[aria-label]:hover::after {
display: block;
margin-top: 73px;
margin-left: 100px;
}
label{
    font-size: 12px;
}
#radiosVenda{
    margin-top: 7px;
}
.fonteParcelas{
    font-size: 1.3rem;
}

.lds-ring {
  display: inline-block;
  position: relative;
  width: 64px;
  height: 64px;
}

.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 51px;
  height: 51px;
  margin: 6px;
  border: 6px solid #ccc;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #ccc transparent transparent transparent;
}

@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}

.classEmitirNota{
    width: 600px !important;
    font-size: 14px !important;
}


</style>

<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda_nf_pecas_novo.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
    <!-- page content -->
    <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  
              ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="{$mod}">   
            <input name=form          type=hidden value="{$form}">   
            <input name=opcao         type=hidden value="{$opcao}">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}>
            <input name=cliente       type=hidden value={$cliente}>
            <input name=pessoa        type=hidden value={$pessoa}>
            <input name=fornecedor    type=hidden value=''>
            <input name=descCondPgto  type=hidden value="{$descCondPgto}">
            <input name=alteraCondPgto  type=hidden value="{$alteraCondPgto}">
            <input name=t_origem        type=hidden value="{$t_origem}">

            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                    {if $formNf eq true}
                        Cadastro de Nota fiscal e Financeiro
                        </h2>
                        <br />
                        {if $mensagem neq ''}
                            <h5>
                                <div class="alert alert-warning" role="alert">{$mensagem}</div>
                                <!--div class="checkbox">
                                    <input type="checkbox" class="flat" name="nfAberto" value="false"> Confirma cadastro NF em ABERTO? confime novamente.
                                </div-->
                            </h5>
                        {/if}
                    {else}
                        Cadastro de Parcelas no Financeiro
                        </h2>
                        {if $mensagem neq ''}
                            <h5>
                                <div class="alert alert-warning" role="alert">{$mensagem}</div>
                            </h5>
                        {/if}
                    {/if}

                    <ul class="nav navbar-right panel_toolbox">
                        {if $formNf eq true}
                            <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastraNf('{$id}');" >
                            <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar Nf</span></button> 
                            </li>
                        {else}
                            {if $parcelasCadastrada neq true}
                                <li><button type="button" class="btn btn-primary" onClick="javascript:submitCadastraFinanceiro('{$id}');">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar Financeiro</span></button> 
                                </li>
                            {/if}
                        {/if}
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarNovo('{$opcao}');">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content small">
                    <div class="row">
                        <h5>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="numeroPedido">N&uacute;mero do Pedido</label>
                            <div class="panel-default">
                                <input type="text" class="form-control" id="pedido" name="pedido" disabled value="{$pedido}">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="data">Data</label>
                            <div class="panel-default">
                                <input type="text" class="form-control" id="data" name="data" disabled value="{$data}">
                            </div>
                        </div>

                        <div class="col-md-8 col-sm-8 col-xs-8">
                            <label for="clienteNome">Cliente</label>
                        <div class="panel-default">
                                <input type="text" class="form-control" id="clienteNome" name="clienteNome" disabled value="{$clienteNome}">
                            </div>
                        </div>
                        </h5>    
                    </div>
                    <br>
                    <div class="row">

                        <div class="col-md-2 col-sm-2 col-xs-2">
                            <label for="condPgto">Condi&ccedil;&atilde;o de Pagamento</label>
                            <div class="panel-default">
                                <select name="condPgto" class="form-control" onChange="javascript:submitAtual({$id}, 'true');">
                                    {html_options values=$condPgto_ids selected=$condPgto_id output=$condPgto_names}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-2 ">
                            <label for="total">Total</label>
                            <div class="panel-default left_col">
                                <input class="form-control" type="text" id="total" name="total" readonly value={$total}>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <label for="centroCusto">Centro de Custo</label>
                            <div class="panel-default">
                                <select name="centroCusto" class="form-control" onChange="javascript:submitAtual({$id});">
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-4">
                            <label for="genero">G&ecirc;nero</label>
                            <div class="panel-default">
                                <select name="genero" class="form-control">
                                    {html_options values=$genero_ids selected=$genero_id output=$genero_names}
                                </select>
                            </div>
                        </div>

                    </div>

                    <br>
                    
                    <div class="row">
                        {if $subMenu !== 'financeiroEntradaNf'}
                            <div class="col-md-2 col-sm-2 col-xs-2">
                                <label>Venda Presencial</label>
                                    <div id="radiosVenda" aria-label="Ao utilizar 'SIM' será desconsiderado a transportadora, o consumidor será final e não existirá frete.">
                                        {html_radios class="flat" name="vendaPresencial" id="vendaPresencial" values=$boolean_ids output=$boolean_names selected=$vendaPresencial separator="&nbsp;"}
                                    </div>
                            </div>
                        {/if}

                        <div {if $subMenu !== 'financeiroEntradaNf'} 
                                class="col-md-8 col-sm-8 col-xs-8" 
                             {else} 
                                class="col-md-9 col-sm-9 col-xs-9"
                             {/if}
                        >
                            <label for="idNatop">Natureza Opera&ccedil;&atilde;o</label>
                            <div class="panel-default">
                                    <select id="idNatop" name="idNatop" class="form-control">
                                        {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                    </select>
                            </div>
                        </div>

                        <div {if $subMenu !== 'financeiroEntradaNf'} 
                                class="col-md-2 col-sm-2 col-xs-2" 
                             {else} 
                                class="col-md-3 col-sm-3 col-xs-3"
                             {/if}
                        >
                            <label for="serie">Serie</label>
                            <div class="panel-default">
                                <input class="form-control" type="text" id="serie" name="serie" value={$serie}>
                            </div>
                        </div>
                    </div>
                </div>
               
                <div class="x_panel">
                    <div class="" role="tabpanel" data-example-id="togglable-tabs">
                      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="parcelas-tab" role="tab" data-toggle="tab" aria-expanded="true">Parcelas</a>
                        </li>
                        {if $formNf eq true}
                            <li role="presentation" class=""><a href="#tab_content2" role="tab" id="transportadora-tab" data-toggle="tab" aria-expanded="false">Transportador / Observa&ccedil;&atilde;o</a>
                            </li>
                        {/if}
                        
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
                                            <th width="120px"><center>Data Vencimento</center></th>
                                            <th width="100px"><center>Valor</center></th>
                                            <th><center>Tipo Documento</center></th>
                                            <th>Conta Recebimento</th>
                                            <th>Situa&ccedil;&atilde;o Lan&ccedil;amento</th>
                                            <th>Obs</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        {section name=i loop=$fin}
                                            {assign var="total" value=$total+1}
                                            <tr>
                                                <td class="fonteParcelas"><center> {$fin[i].PARCELA} </center></td>

                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control fonteParcelas" type="text" id="venc" name="venc{$fin[i].PARCELA}" value={$fin[i].VENCIMENTO|date_format:"%d/%m/%Y"} >
                                                </td>

                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control fonteParcelas" type="text" id="valor" name="valor{$fin[i].PARCELA}" value={$fin[i].VALOR|number_format:2:",":"."}>

                                                </td>

                                                <td>
                                                    <select id="idTipoDoc" name="tipo{$fin[i].PARCELA}" class="form-control fonteParcelas {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$tipoDocto_ids selected=$tipoDocto_id output=$tipoDocto_names}
                                                    </select>
                                                </td>

                                                <td>
                                                    <select  id="idConta" name="conta{$fin[i].PARCELA}" class="form-control fonteParcelas {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$conta_ids selected=$conta_id output=$conta_names}
                                                    </select>
                                                </td>

                                                <td> 
                                                    <select id="idSitucao" name="situacao{$fin[i].PARCELA}" class="form-control fonteParcelas {if $parcelasCadastrada eq true} select-read-only {/if}">
                                                        {html_options values=$situacaoLanc_ids selected=$situacaoLanc_id output=$situacaoLanc_names}
                                                    </select>
                                                </td>

                                                <td> 
                                                    <input {if $parcelasCadastrada eq true} readonly {/if} class="form-control fonteParcelas" type="text" id="obs" name="obs{$fin[i].PARCELA}" value={$fin[i].OBS}>
                                                </td>
                                            </tr>
                                        <p>
                                    {/section} 

                                    </tbody>
                                </table>
                              </div>       

                        </div>
                        <div role="tabpanel" class="tab-pane fade small" id="tab_content2" aria-labelledby="profile-tab">
                            <div class="form-group">
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <label for="modFrete">Modalidade Frete</label>
                                    <div class="panel-default">
                                        <select name="modFrete" class="form-control">
                                            {html_options values=$modFrete_ids selected=$modFrete_id output=$modFrete_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="transpNome">Transportador</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="transpNome" name="nome" placeholder="Transportador que realiza o frete"
                                               value="{$transpNome}">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary" 
                                                    onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>                                
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-4">
                                    <label for="dataSaidaEntrada">Data Saída/Entrada</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                                    <input class="form-control" type="text" name="dataSaidaEntrada" id="dataSaidaEntrada" 
                                           value="{$dataSaidaEntrada}" placeholder="dd/mm/aaaa hh:mm" 
                                           title="Data e Hora Saída/Entrada" 
                                           alt="Data e Hora Saída/Entrada">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="placaVeiculo">Placa Veículo</label>
                                    <input class="form-control" type="text" name="placaVeiculo"
                                    placeholder="Ex: ABC1234" value={$placaVeiculo}>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="codAntt">Código ANTT</label>
                                    <input class="form-control" type="text" name="codAntt"
                                    placeholder="Ex: 12345678" value={$codAntt}>
                                </div>
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <label for="uf">UF</label>
                                    <input class="form-control" type="text" name="uf" id="uf"
                                    placeholder="Ex: PR" maxlength="2" style="text-transform: uppercase;" value={$uf}>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="volEspecie">Volume Esp&eacute;cie</label>
                                    <input class="form-control" type="text" name="volEspecie" value={$volEspecie}>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="volMarca">Volume Marca</label>
                                    <input class="form-control" type="text" name="volMarca" value={$volMarca}>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="volume">Quantidade de Volumes</label>
                                    <input class="form-control" type="text" name="volume" value={$volume}>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="volPesoLiq">Peso Liquido</label>
                                    <input class="form-control" type="text" name="volPesoLiq" value={$volPesoLiq}>
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-2">
                                    <label for="volPesoBruto">Peso Bruto</label>
                                    <input class="form-control" type="text" name="volPesoBruto" value={$volPesoBruto}>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <label for="obs" >Observa&ccedil;&atilde;o Documento</label>
                                    <textarea class="resizable_textarea form-control" id="obs" name="obs" rows="2" >{$obs}</textarea>
                                </div>  
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
    $(document).ready(function() {
        $('#dataSaidaEntrada').daterangepicker({
            singleDatePicker: true,
            timePicker: true,
            timePicker24Hour: true,
            opens: 'up',
            locale: {
                format: 'DD/MM/YYYY HH:mm'
            }
        });
    });
</script>  