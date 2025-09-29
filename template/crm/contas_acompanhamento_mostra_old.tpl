<script type="text/javascript" src="{$pathJs}/crm/s_contas_acompanhamento.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Contas - Acompanhamento</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta
                            {if $mensagem neq ''}
                                <div class="container">
                                    <div class="alert alert-success fade in"><strong>Sucesso!</strong> {$mensagem}</div>
                                </div>    
                            {/if}
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
                <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
                    <input name=mod           type=hidden value="{$mod}">   
                    <input name=form          type=hidden value="{$form}">   
                    <input name=opcao         type=hidden value="{$opcao}">   
                    <input name=id            type=hidden value="{$id}">
                    <input name=pessoa        type=hidden value="{$pessoa}">
                    <input name=dataContato   type=hidden value="{$dataContato}">
                    <input name=horaContato   type=hidden value="{$horaContato}">
                    <input name=letra         type=hidden value={$letra}>
                    <input name=submenu       type=hidden value={$subMenu}>
                    <div class="row">
                        <div class="col-lg-8 text-left">
                            <label>Cliente</label>
                                <input class="form-control" id="nome" name="nome" placeholder="Digite o nome do Cliente."  value={$nome} >
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-3 text-left">
                            <label for="nome">Inicio</label>
                            <div class="form-group input-group">
                                <input type="text" class="form-control" id="dataIni" name="dataIni" value={$dataIni}>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>
                        <div class="col-lg-3 text-left">
                            <label for="nome">Fim</label>
                            <div class="form-group input-group">
                                <input class="form-control" id="dataFim" name="dataFim" value={$dataFim} >
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i>
                                    </button>
                                </span>
                            </div>
                        </div>


                        <div class="col-lg-2 text-left">
                            <label for="vendedor">Vendedor</label>
                            <div class="panel panel-default">
                                <SELECT class="form-control" name="vendedor"> 
                                    {html_options values=$vendedor_ids output=$vendedor_names selected=$vendedor_id}
                                </SELECT>
                            </div>
                        </div>

                    </form>

                </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->



        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
                <div class="x_panel">

                    <div class="x_content">

                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr style="background: #2A3F54; color: white;">
                                    <th><b>Cliente</b></th>
                                    <th>Data</th>
                                    <th>Vendedor</th>
                                    <th>A&ccedil;&atilde;o</th>
                                    <th>Acompanhamento</th>
                                    <th data-field="date"  data-sortable="true" data-sort-name="_date_data" data-sorter="monthSorter">Proximo Contato</th>
                                    <th style="width: 40px;">Manuten&ccedil;&atilde;o</th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    <tr>
                                        <td> {$lanc[i].NOMEREDUZIDO} </td>
                                        <td> {$lanc[i].DATA|date_format:"%d/%m/%Y %H:%M"}</td>
                                        <td> {$lanc[i].VENDEDOR} </td>
                                        <td> {$lanc[i].DESCRICAO} </td>
                                        <td> {$lanc[i].RESULTADO} </td>
                                        <td> {$lanc[i].LIGARDIA|date_format:"%d/%m/%y %H:%M"} </td>
                                        <td >
                                            <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                        </td>
                                    </tr>
                        {/section} 

                    </tbody>
                </table>

              </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
          </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->



    {include file="template/database.inc"}  
