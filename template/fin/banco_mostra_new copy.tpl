<script type="text/javascript" src="{$pathJs}/fin/s_banco.js"> </script>
<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

  <!-- page content -->
  <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}>

        
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
                    <h2>Consulta
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
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
                        <!--span v-bind:title="message">
                          Hover your mouse over me for a few seconds
                          to see my dynamically bound title! - %% message %%
                        </span>
                          <input v-model="message" class="form-control col-md-7 col-xs-12" placeholder="Digite o C&oacute;digo do Banco.">

                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id">Banco <span class="required">*</span>
                          </label>
                          <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="id" name="id" type="text" required="required" 
                                  class="form-control col-md-7 col-xs-12" 
                                  placeholder="Digite o C&oacute;digo do Banco." value={$id}>
                          </div>
                        </div-->

                      <table id="datatable-buttons" class="table table-bordered jambo_table">
                          <thead>
                              <tr class="headings">
                                  <th>Banco</th>
                                  <th>Nome</th>
                                  <th class=" no-link last" style="width: 40px;">Manutenção</th>
                              </tr>
                          </thead>
                          <tbody>
                                <template v-for='banco in bancos.data'>
                                <tr class="even pointer">
                                    <td> %% banco.BANCO %% </td>
                                    <td> %% banco.NOME %% </td>
                                    <td class=" last">
                                        <button type="button"  class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].BANCO}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <button type="button"  class="btn btn-danger btn-xs" onclick="javascript:submitExcluir('{$lanc[i].BANCO}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                                </template>
                          </tbody>
                      </table>



                      </div>


                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>


<script>
  var bancos_var = [];
  bancos_var = {$lanc};
  var app = new Vue({
    delimiters: ['%%', '%%'],
    el: "#app",
    data: {
        message: 'Hello Vue from PHP!',
        mensagem: '',
        banco: '',
        nome: '',
        bancos: []
    },
    mounted: function () {
        
        //console.log(bancos_var);
      this.getBancos()
    },    
    methods: {
      getBancos: function(){
        axios.get('http://localhost:8000/api/v1/default/FINBANCO')
        .then(function (response) {
            app.bancos = response.data;
            console.log(app.bancos);
        })
        .catch(function (error) {
            console.log(error);
        });        
      },
      createBancos: function(){
      },
      resetForm: function(){
        this.banco = '';
        this.nome = '';
      }
    }    
  });

</script>
{include file="template/database.inc"}  
    
    <!-- /Datatables -->
