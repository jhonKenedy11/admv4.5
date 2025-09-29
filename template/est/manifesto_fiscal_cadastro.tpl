<script type="text/javascript" src="{$pathJs}/est/s_manifesto_fiscal.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/underscore@1.13.6/underscore-umd-min.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">      
    <div class="small">
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="">   
            <input name=form                type=hidden value="">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=idMdf               type=hidden value={$idMdf}>
            <input name=idNotaFiscal        type=hidden value={$idNotaFiscal}>
            <input name=pessoa              type=hidden value={$pessoa}>   
            <input name=transportador       type=hidden value={$transportador}>   
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=centroCusto         type=hidden value={$filial_id}> 
            <input name="t_origem"          type=hidden value={$t_origem}>

        <div class="row">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">  
              <div class="x_title">

                {include file="../bib/msg.tpl"}
                <h2>
                    <div class="form-group">
                        <label class="control-label col-md-4 col-sm-3 col-xs-12">
                            
                            {if $subMenu eq "cadastrar"}
                                Manifesto Fiscal - Cadastro 
                            {else}
                                Manifesto Fiscal - Altera&ccedil;&atilde;o 
                            {/if} 
                        </label>                 
                    </div>
                </h2>

                <ul class="nav navbar-right panel_toolbox">
                    <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar();">
                            <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Salvar </span></button>
                    </li>
                    <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar();">
                            <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
                    </li>
                    {if $situacao_id eq 'B'}
                        <li><button type="button" class="btn btn-danger" id="btnCancela" data-target="#modalCancela" data-toggle="modal">
                                <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span><span> Cancelar MDFe</span></button>
                        </li>
                    {/if}
                  {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                  <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                    <ul class="dropdown-menu" role="menu">
                        {if $subMenu neq "incluir"}
                            <li>
                              <center><button type="button" class="btn btn-dark btn-xs"  onClick="javascript:submitCadastroProdutos({$id});"><span> Cancela MDFe</span></button></center>
                            </li>
                        {/if}  
                    </ul>

                  </li>
                  <li><a class="close-link"><i class="fa fa-close"></i></a>
                  </li>
                </ul> *}
                <div class="clearfix"></div>

            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-6 text-left">
                        <label for="numero">N&uacute;mero</label>
                        <div class="input-group">
                            <input class="form-control input-sm" type="number"  onKeyPress="if(this.value.length==11) return false;" 
                                    {* placeholder="N&uacute;mero MDFe." id="numero" name="numero"{if $subMenu eq "cadastrar"} readonly {else} &nbsp;{/if} value={$numero}> *}
                                    placeholder="N&uacute;mero MDFe." id="numero" name="numero" readonly value={$numero}>
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-6 col-xs-6">
                        <label for="serie">S&eacute;rie</label>
                        <div class="input-group">
                            <input class="form-control input-sm" type="number"  required="required" maxlength="3" placeholder="Serie NFe." id="serie" name="serie" 
                                 onKeyPress="if(this.value.length==3) return false;" value={$serie}>
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-6 col-xs-6">
                        <label for="id">Modelo</label>
                        <div class="input-group">
                            <input class="form-control input-sm" type="text" readonly id="modelo"  name="modelomostra" value={$modelo}>
                        </div>
                    </div>

                    {* <div class="col-md-1 offset-md-1"></div> *}

                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label for="emissao">Emiss&atilde;o</label>
                        <div class="input-group">
                            <input class="form-control input-sm" readonly type="text" id="emissao" name="emissao" value={$emissao}>
                        </div>
                    </div>

                    {* <div class="col-md-1 offset-md-1"></div> *}

                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label for="centroCusto">Centro de Custo</label>
                        <div class="input-group">
                            <select class="form-control input-sm" name=centroCusto>
                               {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-6" name="divTotalMdf">
                    <label id="labelMdf">Total Carga</label>
                    <div class="input-group">
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm btnTotal" tabindex="-1" type="button">R$
                            </button>
                        </span>
                        <input class="form-control input-sm money" readonly placeholder="0,00" type="money"  id="totalcarga"  name="totalcarga" 
                             maxlength="9" value={$totalcarga}>
                    </div>
                </div>
                </div>
                
                <div class="row">
                    
                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <label for="ufini">UF Ini</label>
                        <div class="input-group">
                            <select class="form-control input-sm" name=ufini>
                               {html_options values=$ufini_ids selected=$ufini_id output=$ufini_names}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-6">
                        <label for="uffim">UF Fim</label>
                        <div class="input-group">
                            <select class="form-control input-sm" name=uffim>
                               {html_options values=$uffim_ids selected=$uffim_id output=$uffim_names}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-6">
                        {* <label for="condutor">Condutor</label>
                        <div class="input-group">
                            <select class="form-control input-sm" name=condutor>
                               {html_options values=$condutor_ids selected=$condutor_id output=$condutor_names}
                            </select>
                        </div> *}
                        <label for="clienteCombo">Condutor</label>
                        <select class="js-data-example-ajax js-states form-control" name="condutor" id="condutor" onChange="javascript:setCliente('');">
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <label for="veiculoTracao">Veiculo</label>
                        <div class="input-group">
                            <select class="form-control input-sm" name=veiculoTracao>
                               {html_options values=$veiculo_ids selected=$veiculo_id output=$veiculo_names}
                            </select>
                        </div>
                    </div>

                    {* <div class="col-sm-1 offset-sm-1"></div> *}
                    
                    <div class="col-md-1 col-sm-6 col-xs-6 unidadecarga">
                        <label for="unidadecarga">Un. Carga</label>
                        <div class="input-group">
                            <select class="form-control input-sm" name=unidadecarga>
                                {html_options values=$unidadecarga_ids selected=$unidadecarga_id output=$unidadecarga_names}
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-6 col-xs-6 pesocarga">
                        <label for="pesocarga">Peso Carga</label>
                        <div class="input-group">
                             <span class="input-group-btn">
                                <button class="btn btn-default btn-sm glyphicon glyphicon-scale" readonly tabindex="-1" type="button">
                                </button>
                            </span>
                            <input class="form-control input-sm money" maxlength="15" type="money" name="pesocarga" value={$pesocarga}>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label for="observacao">Observa&ccedil;&atilde;o</label>
                        <div class="panel panel-default">
                            <textarea class="form-control" placeholder="Digite a observação." rows="3"  id="observacao" name="observacao">{$observacao}</textarea>
                        </div>
                    </div>
                </div>           
              
            </div>
            
            <div class="x_panel">
                <div class="" role="tabpanel" data-example-id="togglable-tabs">
                    <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#tab_content1" id="dados-tab" role="tab" data-toggle="tab" aria-expanded="true">Dados do Manifesto</a>
                        </li>
                        {* <li role="presentation" class=""><a href="#tab_content2" role="tab" id="transportadora-tab" data-toggle="tab" aria-expanded="false">Transportador</a>
                        </li> *}
                        <li role="presentation" class=""><a href="#tab_content3" role="tab" id="nfe-tab" data-toggle="tab" aria-expanded="false">Mdfe</a>
                        </li>
                    </ul>
                    <div id="myTabContent" class="tab-content">

                    <div role="tabpanel" class="tab-pane fade active in" id="tab_content1" aria-labelledby="home-tab">
                        <div class="col-md-12 col-sm-12 col-xs-12">

                            <div id="formNotaFiscal">
                                <div class="form-group line-formated">
                                    <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback">
                                        <label for="numNotaFiscal">N&#186; NF</label>
                                        <input class="form-control input-sm" type="text" id="numNotaFiscal" 
                                            placeholder="0" readonly name="numNotaFiscal" value={$numNotaFiscal}>
                                    </div>
                                    <div class="col-md-1 small col-sm-12 col-xs-12 has-feedback">
                                        <label for="numPedido">Documento</label>
                                        <input class="form-control input-sm" readonly type="text" id="numPedido" 
                                            placeholder="Doc" name="numPedido" value={$numPedido}>
                                    </div>
                                    <div class="col-md-2 small col-sm-12 col-xs-12 has-feedback">
                                         <label for="filial">Filial</label>
                                         <input class="form-control input-sm" readonly type="text" id="filial" 
                                            placeholder="Filial" name="filial" value={$filial}>
                                     </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12 small line-formated">
                                        <label for="descPessoa">Pessoa</label>
                                        <div class="input-group line-formated">
                                            <input type="text" class="form-control input-sm" id="descPessoa" name="descPessoa" placeholder="Pesquisar Nota Fiscal" required
                                                value="{$descProduto}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary btn-sm" 
                                                       onClick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=nota_fiscal&from=manifesto_fiscal&opcao=imprimir');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>                                
                                        </div>
                                    </div>

                                    <div class="form-group line-formated">
                                        <div class="col-md-1 col-sm-12 col-xs-12 has-feedback">
                                            <label style="visibility:hidden">btn</label>
                                            <button type="button" class="btn btn-success btn-sm"  onClick="javascript:submitConfirmarNota();">
                                            <span>Confirmar</span></button>                            
                                        </div> 
                                    </div>
                                    
                                </div>

                                <table id="datatable-buttons-nf" class="table table-bordered jambo_table">
                                    <thead>
                                        <tr style="background: gray; color: white;">
                                            <th>ID NFe</th>
                                            <th>N&#186; NFe</th>
                                            <th>Data Emiss&atilde;o</th>
                                            <th>Pessoa</th>
                                            <th>Valor Total Nota Fiscal</th>
                                            <th style="width:38px;">Man.</th>                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {* 
                                        FUNCAO QUE POPULA A TABELA VEM DO AJAX atualizaTabelaNotaFiscal
                                        {section name=i loop=$lanc} 
                                            <tr>
                                                <td> {$lanc[i].ID} </td>
                                                <td> {$lanc[i].MODELO} </td>
                                                <td> {$lanc[i].CODIGONOTA} </td>
                                                <td> {$lanc[i].DESCRICAO} </td>
                                                <td> {$lanc[i].QTSOLICITADA|number_format:2:",":"."} </td>
                                                <td> {$lanc[i].UNITARIO|number_format:2:",":"."} </td>
                                                <td> 
                                                    <button {if $lanc[i].ITEMESTOQUE eq 0} disabled {/if}type="button" class="btn btn-info btn-xs" onclick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&letra=||{$lanc[i].ITEMFABRICANTE}||||{$lanc[i].ITEMESTOQUE}', 'produto');" ><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button> 
                                                    <button type="button" class="btn btn-primary btn-xs" onclick="javascript:editarPeca(this, '{$lanc[i].NRITEM}')" ><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button> 
                                                    <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluiPeca('{$lanc[i].NRITEM}');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button> 
                                                </td>
                                            </tr>
                                        {/section}  *}
                                    </tbody>
                                </table>

                            </div> <!-- FIM DIV formPedidoItem-->
                        </div>
                    </div>

                    <div role="tabpanel" class="tab-pane fade small" id="tab_content3" aria-labelledby="profile-tab">
                        
                        <div class="form-group">
                            <div class="row">

                                <div class="col-md-1 offset-md-1"></div>

                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="dhrecbto">Data e Hora do Recebimento</label>
                                    <input class="form-control" readonly type="text" maxlength="45" title="Data e hora recebimento" 
                                        id="dhrecbto" name="dhrecbto" value={$dhrecbto}>
                                </div>
                            
                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="protocolomdfe">N&uacute;mero Protocolo</label>
                                    <input class="form-control" readonly type="number" id="protocolomdfe" name="protocolomdfe" title="Protocolo"
                                     value={$protocolomdfe}>
                                </div>

                                <div class="col-md-4 col-sm-2 col-xs-2">
                                    <label for="digval">Digest Value da NF-e processada</label>
                                    <input class="form-control" readonly type="text" maxlength="28"
                                        id="digval" name="digval" value={$digval}>
                                </div>
                            
                            </div>

                            <div class="row">
                                        
                            <div class="col-md-1 offset-md-1"></div>
                                <div class="col-md-3 col-sm-2 col-xs-2">
                                    <label for="veraplic">Vers&atilde;o do Aplicativo</label>
                                    <input class="form-control" readonly type="text" maxlength="20" title="XML <verAplic>"
                                    id="veraplic" name="veraplic" value={$veraplic}>
                                </div>

                                <div class="col-md-7 col-sm-2 col-xs-2">
                                    <label for="chaveacessomdfe">Chave de Acesso MDFe</label>
                                    <input class="form-control" readonly type="text" maxlength="20" title="XML <verAplic>"
                                    id="chaveacessomdfe" name="chaveacessomdfe" value={$chaveacessomdfe}>
                                </div>
                            </div>

                        </div>
                    </div> <!-- tabpanel -->

                    <div role="tabpanel" class="tab-pane fade small" id="tab_content2" aria-labelledby="profile-tab">
                        <div class="form-group">
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <label for="modFrete">Modalidade Frete</label>
                                <div class="panel panel-default">
                                    <select name="modFrete" class="form-control form-control-sm" onchange="condFornecedor()">
                                        {html_options values=$modFrete_ids selected=$modFrete_id output=$modFrete_names}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="transpNome">Transportador</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="transpNome" name="transpNome" placeholder="Transportador que realiza o frete"
                                        value="{$transpNome}">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisartransportador');">
                                            <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                        </button>
                                    </span>                                
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="placaVeiculo">Placa Veículo</label>
                                <input class="form-control" type="text" name="placaVeiculo" value={$placaVeiculo}>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="volEspecie">Volume Esp&eacute;cie</label>
                                <input class="form-control" type="text" name="volEspecie" value={$volEspecie}>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="volMarca">Volume Marca</label>
                                <input class="form-control" type="text" name="volMarca" value={$volMarca}>
                            </div>
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
                        </div>
                    </div>
                            
                </div>
            </div> <!-- tabpanel -->
                                    
            </div>
            </div>
        </form>
      </div>

    <!-- Modal Cancela -->
    <div class="modal fade" id="modalCancela" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Cancelamento MDFe - Justificativa</h4>
          </div>
          <div class="modal-body">
              <textarea class="form-control" placeholder="Digite a justificativa para cancelamento." maxlength="250" rows="4"  id="justificativa" name="justificativa">{$justificativa}</textarea>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btnCancel" data-dismiss="modal" onClick="javascript:cancelaMdfe({$id});">Confirma</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>
    <!--FIM Modal Cancela -->

{include file="template/form.inc"}  

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script>
$(document).ready(function(){
    debugger
    $('#condutor').select2({
      placeholder: "Selecione o condutor",
      language: {
        //Descricao da quantidade de caracteres.
        inputTooShort: function () {
          return "Digite no mínimo 3 caracteres";
        }
      },
      minimumInputLength: 3,
      delay: 250,
      ajax: {
        dataType: "json",
        type: "POST",
        url: document.URL+"?mod=est&form=manifesto_fiscal&submenu=pesquisaCondutorAjax&opcao=blank",
        processResults: function (response) {
            debugger
            return{
                results: response
            };
        }
      }
    });
});
</script>

<script>
$(document).ready(function(){
    debugger
    var data = {
    id: {$condutor},
    text: {$nomecondutor}
    };

    var newOption = new Option(data.text, data.id, false, false);
    $('#condutor').append(newOption).trigger('change');
});
</script>

<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
$(document).ready(function(){
  $(".money").maskMoney({                  
   decimal: ",",
   thousands: ".",
   allowZero: true,
  });
});
</script>


<style type="text/css">

.form-control,
.x_panel {
    border-radius: 5px;
}
input, select, .x_panel, th{
    border-radius: 4px !important;
}
.divTotalMdf{
    background-color: rgba(190, 247, 164, 0.532);
}
.btnTotal, #totalcarga{
    background-color: rgba(190, 247, 164, 0.532);
    pointer-events: none;
}

input[type=number]::-webkit-inner-spin-button { 
    -webkit-appearance: none;
    
}
.glyphicon-scale{
    pointer-events: none;
    margin-top: -1px;
}
.swal-button--confirm{
    background-color: #3b60af;
    transition: background-color 500ms;
    transition: box-shadow 300ms;
}
.swal-button--confirm:hover{
    background-color: #234faf !important;
    box-shadow: 2px 2px 2px 1px #3258ab95;
}
.swal-button--cancel:hover{
    box-shadow: 2px 2px 2px 1px #aaaaaa;
    transition: box-shadow 300ms;
}
select[name=centroCusto]{
    width: 229px !important;
}
select[name=unidadecarga]{
    width: 77px !important;
    padding: 1px;
}

#numNotaFiscal, 
#numPedido, 
#filial, 
#descPessoa{
    padding: 5px;
}
.btn-remover{
    width: 21px;
    padding: 0;
    margin: 0;
    height: 19px;
}
.glyphicon-remove{
    padding: 0;
    font-size: 11px;
}
select[name=ufini], select[name=uffim]{
    padding: 1px !important;
}

select[name=unidadecarga]{
    width: 69px !important;
    padding: 1px !important;
}

.unidadecarga{
    margin-left: -47px !important;
}

input[name="pesocarga"]{
    width: 72px !important;
    padding: 4px;
}

.pesocarga{
    margin-left: 4px;
}
/*css para select condutor*/
.select2-selection--single{
    border-radius: 5px !important;
    min-height: 31px !important;
}
.select2-results__option{
    border-radius: 5px !important;
}
#select2-condutor-container{
    line-height: 20px !important;
}
.select2-selection__arrow{
    height: 29px !important;
}
/* FIM css para select condutor*/

.btnSalvar{
    background-color: #04830e !important;
}
.btnSalvar:hover{
    background-color: #03920f !important;
    cursor: pointer !important;
}
.btnCancel{
    margin-top: 5px;
}
</style>