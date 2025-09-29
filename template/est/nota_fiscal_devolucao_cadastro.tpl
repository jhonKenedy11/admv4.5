<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                

        <div class="small">
            <div class="page-title">
              <div class="title_left">
                <h3>Nota Fiscal - Devolução</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta
                        <strong>
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                        <div class="alert alert-success small" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                                {else}        
                                        <div class="alert alert-error small" role="alert">Erro!&nbsp;{$mensagem}</div>
                                {/if}
                            {/if}
                        </strong>
                    </h2>
                        <!--div class="col-md-2 col-sm-2 col-xs-2">
                            <input class="form-control input-sm" type="text" readonly id="situacao"  name="situacao" value={$situacao_name}>
                        </div-->                    
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltarNfMostra({$idnf});">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span> Voltar</span></button>
                        </li>
                        
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitDevolucao({$idnf});">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span>
                            </button>
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
                  <div class="x_content">
                    <form id="mostra" name="mostra" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value='est'>
                        <input name=form          type=hidden value=''>
                        <input name=opcao         type=hidden value={$opcao}>   
                        <input name=id            type=hidden value={$id}>
                        <input name=idnf          type=hidden value={$idnf}>
                        <input name=pessoa        type=hidden value={$pessoa}>
                        <input name=codProduto    type=hidden value={$codProduto}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=nfProdutos    type=hidden value={$nfProdutos}>
                        <input name=todosChecked  type=hidden value={$todosChecked}>

                        <div class="row">
                            <!--div class="col-md-1 col-sm-6 col-xs-6">
                                <label for="id">Modelo</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text" readonly id="modelo"  name="modelomostra" value={$modelo}>
                                </div>
                            </div>
                            <div class="col-md-1 col-sm-6 col-xs-6">
                                <label for="serie">S&eacute;rie</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text"  readonly maxlength="2" placeholder="Serie NFe." id="serie" name="serie" value={$serie}>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6 text-left">
                                <label for="numero">N&uacute;mero</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text" readonly maxlength="11"  placeholder="Numero NFe." id="numero" name="numero" value={$numero}>
                                </div>
                            </div-->
                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                <label>Centro de Custo</label>
                                <select class="js-example-basic-single form-control" name="ccusto" id="ccusto"> 
                                    {html_options values=$ccusto_ids output=$ccusto_names selected=$ccusto_id}
                                </SELECT>
                            </div>
                            <div class="col-md-2 col-sm-6 col-xs-6">
                                <label for="tipo">Tipo Nota</label>
                                <div class="input-group-sm">
                                    <input class="form-control input-sm" type="text"  id="tipo"  name="tipo" value={$tipo_name}>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <label for="emissao">Emiss&atilde;o</label>
                                <div class="input-group">
                                    <input class="form-control input-sm" type="text"  name="emissao" value={$emissao}>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="nome">Pessoa</label>
                                <div class="input-group-sm">
                                    <input type="text" required="required" class="form-control input-sm" id="nome" name="nome" 
                                           value="{$pessoaNome}">
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12 col-xs-12">
                                <label>Natureza Operação</label>
                                <select class="form-control form-control-sm" name=idNatOp>
                                    {html_options values=$natOperacao_ids selected=$natOperacao_id output=$natOperacao_names}
                                </select>
                            </div>
                        </div>
                    </form>

                  </div>
                </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->

        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
            <div class="x_panel small">
                <div class="col-md-2 col-sm-12 col-xs-12 has-feedback">
                    <label style="visibility:hidden">btn atualizaAll</label>
                    <button type="button" class="btn btn-primary btn-sm"  onClick="javascript:submitSelecionarTodos();">
                    <span class="glyphicon glyphicon-arrow-down" aria-hidden="true"></span><span> Selecionar Todos</span></button>                            
                </div>
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>#</th>
                            <th>NF</th>
                            <th>Produto</th>
                            <th>Descri&ccedil;&atilde;o</th>
                            <th>Uni</th>		
                            <th>Qtde</th>
                            <th>Qtde Devolução</th>
                            <th>Vl. Uni</th>		
                            <th>CFOP</th>		
                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="num" value=$num+1}
                            {assign var="total" value=$total+$lanc[i].TOTAL}
                            <tr>
                                <td> <input type="checkBox"  name="prodChecked" id="{$lanc[i].ID}"/> </td>
                                <td> {$lanc[i].CODIGONOTA} </td>
                                <td> {$num} - {$lanc[i].CODPRODUTO} </td>
                                <td> {$lanc[i].DESCRICAO} </td>
                                <td> {$lanc[i].UNIDADE} </td>
                                <td id="qtde{$lanc[i].ID}"> {$lanc[i].QUANT|number_format:2:",":"."} </td>
                                <td>
                                    <input class="form-control input-sm money" value="{$lanc[i].QUANT|number_format:2:",":"."}"
                                    title="Digite a qtde para este item." id="quantDevolucao{$lanc[i].ID}" 
                                    onblur="qtdeDevolucao('{$lanc[i].ID}',$(this).val())"name=quant{$lanc[i].CODPRODUTO} />
                                </td>
                                <td>
                                    <input class="form-control input-sm money" 
                                    title="Digite o valor unitario para este item." id="vlrUnitario{$lanc[i].ID}" 
                                    name=vlrUni{$lanc[i].CODPRODUTO} value="{$lanc[i].UNITARIO|number_format:2:",":"."}"/>
                                </td>
                                <td>
                                    <input class="form-control input-sm" 
                                    title="Digite a CFOP para este item." id="cfop{$lanc[i].ID}" 
                                    name=cfop{$lanc[i].CODPRODUTO} value="{$lanc[i].CFOP}"/>
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



    {include file="template/database.inc"}  

<script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 

<script>
  $(document).ready(function(){
    $(".money").maskMoney({            
     decimal: ",",
     thousands: ".",
     allowNegative: true,
     allowZero: true
    });        
 });
</script>  
<script>
    $("#ccusto.js-example-basic-single").select2({
            placeholder: "Selecione o Centro de Custo",
            allowClear: true
    });

    $("#natop.js-example-basic-single").select2({
            placeholder: "Selecione a Natureza de Operação",
            allowClear: true
    });

</script>