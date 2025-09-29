 
    <script type="text/javascript" src="{$pathJs}/ped/s_pedido_orcamento.js"> </script>
    <!--    page content -->
    <div class="right_col" role="main">                

        <div class="">

            <div class="row">

                <!-- panel principal  -->  

                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Planejamento Or&ccedil;ament&aacute;rio
                                <strong>
                                    {if $mensagem neq ''}
                                        <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                                    {/if}
                                </strong>
                            </h2>
                        
                            <ul class="nav navbar-right panel_toolbox">
                                <li>
                                    <button type="button" class="btn btn-warning"  onClick="javascript:submitPesquisar();">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisa</span>
                                    </button>
                                </li>
                                <li>
                                    <button type="button" class="btn btn-primary" onClick="javascript:submitCadastro();" >
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                                    </button>
                                </li>                        
                                <li>
                                    <button type="button" class="btn btn-primary" >
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Gerar Previs&atilde;o</span>
                                    </button>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div> <!-- <div class="x_title"> -->
                
                        <div class="x_content">
                            <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                                class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                                <input name=mod           type=hidden value="ped">   
                                <input name=form          type=hidden value="pedido_orcamento">   
                                <input name=opcao         type=hidden value="">   
                                <input name=centrocusto   type=hidden value="">
                                <input name=mes          type=hidden value="">
                                <input name=ano          type=hidden value="">
                                <input name=genero        type=hidden value="">
                                <input name=submenu       type=hidden value={$submenu}>
                                <input name=letra         type=hidden value={$letra}>
                                <input name=dados       type=hidden value={$dados}>
                                <input name=desc        type=hidden value={$desc}>
                                <input name=perc        type=hidden value={$perc}>
                                <input name=linhas      type=hidden value={$linhas}>
                                <input name=titulo      type=hidden value={$titulo}>
                                <input name=total       type=hidden value={$total}>
                                <input name=orcCPG      type=hidden value={$orcCPG}>
                                <input name=finCPG      type=hidden value={$finCPG}>
                                                                                
                                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                    <label for="mesBase">Mês Base</label>
                                    <SELECT class="form-control" name="mesBase">                                 
                                        {html_options values=$mesBase_ids selected=$mesBase_id output=$mesBase_names}
                                    </SELECT>
                                </div>  
                                
                                <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                    <label class="">Mês Saldo</label>
                                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                                    <div>
                                        <input type="text" name="anoBase" id="anoBase" class="form-control" value="{$anoBase}">
                                    </div>
                                </div>
                                
                                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                    <label for="filial">Conta</label>
                                    <SELECT class="form-control" name="filial">                                 
                                        {html_options values=$filial_ids selected=$filial_id output=$filial_names}
                                    </SELECT>
                                </div>                       
                            </form>              
                        
                        </div> <!-- div class="x_content" = inicio tabela -->

                    </div> <!-- div class="x_panel" = painel principal-->

                    <!-- panel tabela dados -->  
                    <div class="responsive">
                        <div class="x_panel">
                            <table id="datatable-buttons" class="table table-bordered jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>Data</th>
                                        <th>G&ecirc;nero</th>
                                        <th>G&ecirc;nero Descri&ccedil;&atilde;o</th>
                                        <th>Filial</th>
                                        <th>Previsto</th>
                                        <th>Realizado</th>
                                        <th>Editar</th>
                                        <th>Apagar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {section name=i loop=$lanc}
                                    {assign var="total" value=$total+$lanc[i].TOTAL}
                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                            <td align="center"> {$lanc[i].MES}/{$lanc[i].ANO} </td>
                                            <td> {$lanc[i].GENERO} </td>
                                            <td> {$lanc[i].DESCRICAO} </td>
                                            <td> {$lanc[i].FILIAL} </td>
                                            <td> {$lanc[i].VALOR|number_format:2:",":"."} </td>
                                            <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                            <td> <div align=center>  </div> 
                                            </td>
                                            <td> <div align=center>  </div> 
                                            </td>
                                        </tr>
                                    <p>
                                    {/section} 
                                </tbody>
                            </table>
                        </div class="x_panel">
                    </div> <!-- class="responsive" -->                             
                </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->
    </div>

    <!-- /Datatables -->

    {include file="template/database.inc"}
