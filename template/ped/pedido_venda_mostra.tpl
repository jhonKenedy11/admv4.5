<script type="text/javascript" src="{$pathJs}/ped/s_pedido_venda.js"> </script>
<!-- page content -->
<div class="right_col" role="main">                
    <!-- Select2 -->
    <link href="{$bootstrap}/select2-master/dist/css/select2.min.css" rel="stylesheet">

        <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Pedidos</h3>
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
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                        
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-warning"  onClick="javascript:submitLetra();">
                                <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span> Pesquisa</span>
                            </button>
                        </li>
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="{$mod}">   
                        <input name=form          type=hidden value="{$form}">   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>

      
                        <div class="col-lg-3 text-left">
                            <label for="situacao">Situação</label>
                            <div class="form-group">
                                <select class="select2_multiple form-control" multiple="multiple" name="situacao">
                                    {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                </select>
                            </div>
                        </div>

                        </form>

                              
                    </div>
                          
                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->

              <!-- panel tabela dados -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">

                            <thead>
                                <tr style="background: #2A3F54; color: white;">
                                    <th>Pedido</th>
                                    <th>Data</th>
                                    <th>Situacao</th>
                                    <th>Total</th>
                                    <th>Progresso</th>
                                    <th style="width: 120px;">Manutenção</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    <tr>
                                        <td> {$lanc[i].ID} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"} </td>
                                        <td> {$lanc[i].PADRAO} </td>
                                        <td> R$ {$lanc[i].TOTAL|number_format:0:",":"."} </td>
                                        <td> 
                                            {if $lanc[i].SITUACAO eq 'D'}
                                                <div class="progress">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 10%;">
                                                    10%
                                                </div>
                                            </div>
                                            {else}
                                                <div class="progress">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 30%;">
                                                    30%
                                                </div>
                                            </div>
                                            {/if}
                                            

                                        </td>


                                        <td >
                                            <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
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
    



