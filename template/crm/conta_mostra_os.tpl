    <script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">
            <div class="page-title">
              <div class="title_left">
                  <h3>Pessoas</h3>
              </div>
            </div>

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Abre OS
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
                        <!--li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span>
                            </button>
                        </li>
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li-->
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

                    <form id="lancamento" name="pessoa" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                        <input name=mod           type=hidden value="{$mod}">   
                        <input name=form          type=hidden value="{$form}">   
                        <input name=id            type=hidden value="">
                        <input name=opcao         type=hidden value={$opcao}>
                        <input name=letra         type=hidden value={$letra}>
                        <input name=submenu       type=hidden value={$subMenu}>
                        <input name=pesObs        type=hidden value="">
                        <input name=pesCidade     type=hidden value="">
                        <input name=idEstado      type=hidden value="">
                        <input name=idVendedor    type=hidden value="">
                        <input name=idAtividade   type=hidden value="">
                        <input name=idClasse      type=hidden value="">
                        <input name=idPessoa      type=hidden value="">

                    <div class="form-group col-md-8 col-sm-12 col-xs-12">
                        <label>Pessoa</label>
                        <input class="form-control" id="pesNome" name="pesNome" placeholder="Digite o nome do Pessoa."  value={$pesNome} >
                    </div>

                    </form>

                </div>
                          
            </div> <!-- div class="x_content" = inicio tabela -->
        </div> <!-- div class="x_panel" = painel principal-->



        <!-- panel tabela dados -->  
        <div class="col-md-12 col-xs-12">
            <div class="x_panel">
                <table id="datatable-buttons" class="table table-bordered jambo_table">
                    <thead>
                        <tr style="background: #2A3F54; color: white;">
                            <th>Nome</th>
                            <th>CNPJ/CPF</th>
                            <th>Cidade</th>
                            <th>Telefone</th>
                            <th>Abre OS</th>

                        </tr>
                    </thead>
                    <tbody>

                        {section name=i loop=$lanc}
                            {assign var="total" value=$total+1}
                            <tr>
                                <td> {$lanc[i].NOME} </td>
                                <td> {$lanc[i].CNPJCPF} </td>
                                <td> {$lanc[i].CIDADE} - {$lanc[i].UF} </td>
                                <td> {$lanc[i].FONE} / {$lanc[i].CELULAR} </td>
                                <td >
                                    <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].CLIENTE}');"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span></button>
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
    
    <!-- /Datatables -->