    <script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                
    <!-- Select2 -->
    <!--link href="{$bootstrap}/select2-master/dist/css/select2.min.css" rel="stylesheet"-->

        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Pedidos
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Novo Pedido</span>
                            </button>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="ped">   
                        <input name=form          type=hidden value="pedido_venda_online">   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=pessoa        type=hidden value={$pessoa}>
                        <input name=situacao      type=hidden value={$situacao}>

      

                        </form>

                              
                    </div>
                          
                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->

              <!-- panel tabela dados -->  
              <div class="responsive">
                <div class="x_panel">
                    <!--table id="datatable-buttons" class="table table-bordered jambo_table"-->
                      <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr class="headings">
                                    <th>Pedido</th>
                                    <th>Conta</th>
                                    <th>Data</th>
                                    <th>Situacao</th>
                                    <th>Total</th>
                                    <th>Progresso</th>
                                    <th style="width: 120px;">Pedidos</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    {assign var="perc" value={$lanc[i].SITUACAO*20}+20}
                                    <tr>
                                        <td> {$lanc[i].PEDIDO} </td>
                                        <td> {$lanc[i].NOMEREDUZIDO} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].PADRAO} </td>
                                        <td align=right> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                        <td> 
                                            <div class="progress">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{$perc}" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: {$perc}%;">
                                                    {$perc}%
                                                </div>
                                            </div>

                                        </td>


                                        <td >
                                            {if $lanc[i].SITUACAO eq 0}
                                                <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}', '{$lanc[i].SITUACAO}', '{$lanc[i].CLIENTE}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            {/if}    
                                            <!--button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button-->
                                            <button type="button" class="btn btn-info btn-xs" onclick="javascript:abrir('index.php?mod=ped&form=pedido_venda_imp_romaneio&opcao=imprimir&parm={$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true"></span></button>
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
    



