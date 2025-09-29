<script type="text/javascript" src="{$pathJs}/est/s_meta_mensal.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">
        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="{$mod}">   
            <input name=form          type=hidden value="{$form}">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <input name=metaid        type=hidden value={$letra}>
            <input name=id            type=hidden value={$id}> 
            
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Metas Empresa - 
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
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar('');">
                                <span class="glyphicon glyphicon-export" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        {if $subMenu != "cadastrar"}
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitAddMetaUsuario('');">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span>Meta usuário</span></button>
                        </li>
                        {/if}
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('');">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span> Cancelar</span></button>
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
                    <br/>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
                <label for="centrocusto">Centro Custo</label>
                <div class="input-group">
                    <SELECT class="form-control form-control-sm" name="centrocusto" required="required"> 
                        {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                    </select>
                </div>
            </div>

            <div class="col-md-2 col-sm-6 col-xs-6">
                <label for="metamargem">Meta Margem</label>
                <div class="input-group">
                    <input class="form-control input" type="text" id="metamargem"  name="metamargem" value={$metamargem}>
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">%</button>
                    </span>
                </div>
            </div>

            <div class="col-md-2 col-sm-6 col-xs-6">
                <label for="meta">Meta</label>
                <div class="input-group">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button">$</button>
                    </span>
                    <input class="form-control input" type="text" id="meta"  name="meta" value={$meta}>
                </div>
            </div>

            <div class="col-md-1 col-sm-3 col-xs-3">
                <label  for="ano">Ano <span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" id="ano" name="ano" value={$ano}>    
                </div>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-2">
                <label for="mes">Mês</label>
                <div>
                    <SELECT class="form-control" name="mes" required="required"> 
                        {html_options values=$mes_ids selected=$mes_id output=$mes_names}
                    </SELECT>
                </div>
            </div>

            <div class="col-md-1 col-sm-1 col-xs-1">
                <label  for="totaldiames">Dia Mês <span class="required"></span></label>
                <div>
                    <input class="form-control" type="text" id="totaldiames" name="totaldiames" value={$totaldiames}>    
                </div>
            </div>
        </div>

                                  <!--
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="ano">Ano <span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" id="ano" name="ano" value={$ano}>    
                        </div>
                      </div>
                      
                      <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="mes">Mês <span class="required"></span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                          <select class="form-control" name=mes id="mes">
                                {html_options values=$mes_ids selected=$mes_id output=$mes_names}
                          </select>
                          </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Meta Margem<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" id="metamargem" name="metamargem" value={$metamargem}>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Total Dia Mês<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input class="form-control" type="text" id="totaldiames" name="totaldiames" value={$totaldiames}>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="meta">Centro de Custo<span class="required"></span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                          <div class="panel panel-default small">
                                <select name="centrocusto" class="form-control">
                                    {html_options values=$centroCusto_ids selected=$centroCusto_id output=$centroCusto_names}
                                </select>
                          </div>
                          </div>
                      </div> -->




                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>Vendedor</th>
                                <th>Meta</th>
                                <th class=" no-link last" style="width: 120px;">Manutenção</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$metas}
                                <tr class="even pointer">
                                    <td> {$metas[i].VENDEDOR}</td>
                                    <td> {$metas[i].META} </td>
                                    <td class=" last">
                                        <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterarVendedor('{$metas[i].ID}','{$metas[i].METAID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluirVendedor('{$metas[i].ID}','{$metas[i].METAID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                        {/section} 

                        </tbody>

                    </table>
                  </div>
                        
                      <div class="ln_solid"></div>
                        
                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}  
