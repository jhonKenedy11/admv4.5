  <!-- page content -->
  <div class="right_col" role="main">                
    <!--form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate  >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}-->

        
        <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Bancos</h3>
              </div>
            </div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button"  class="btn btn-primary"  onClick="javascript:submitCadastro('banco');">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro</span></button></li>
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
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->

                    <div id="app">
                        <span v-bind:title="message">
                          Hover your mouse over me for a few seconds
                          to see my dynamically bound title! - {{ message }}
                        </span>
                        <input v-model="message" class="form-control col-md-7 col-xs-12" placeholder="Digite o C&oacute;digo do Banco.">
                        <button @click="getBancos">Pesquisa</button>
                        <button @click="reverseMessage">Inverter Mensagem</button>
                        <ol>
                          <li v-for="todo in bancos">
                            {{ todo.NOME }}
                          </li>
                        </ol>


                      <table id="datatable-buttons" class="table table-bordered jambo_table">
                          <thead>
                              <tr class="headings">
                                  <th>Banco</th>
                                  <th>Nome {{ message }}</th>
                                  <th class=" no-link last" style="width: 40px;">Manutenção</th>
                              </tr>
                          </thead>
                          <tbody>
                                <tr  v-for='banco in bancos' :key="banco.BANCO">
                                    <td> {{ banco.BANCO }} </td>
                                    <td> {{ banco.NOME }} </td>
                                    <td class=" last">
                                        <button type="button"  class="btn btn-primary btn-xs" onclick="javascript:submitAlterar();"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <button type="button"  class="btn btn-danger btn-xs" @click="deleteItem(banco.BANCO)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                          </tbody>
                      </table>
                        <table id="example" class="display table table-bordered jambo_table" width="100%"></table>

                        <!--table id="example1" class="display table table-bordered jambo_table" width="100%"></table-->

                    </div>


                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    <!--/form-->
    <script src="<!--{$pathJs}-->/../src/js/vue.js" charset="utf-8"></script>
    <script src="https://unpkg.com/vue-toasted"></script>
    <script src="<!--{$pathJs}-->/../src/js/axios.min.js" charset="utf-8"></script>
    <script src="<!--{$pathJs}-->/../src/services/BaseService.js" charset="utf-8"></script>
    <script src="<!--{$pathJs}-->/../src/services/Messages.js" charset="utf-8"></script>

    <script src="<!--{$pathJs}-->/../src/views/fin/banco_list.js" charset="utf-8"></script>

    <!--{include file="template/database.inc"}-->
    
    <!-- /Datatables -->
