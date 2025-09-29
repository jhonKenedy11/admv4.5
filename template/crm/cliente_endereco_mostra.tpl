<style>
.form-control, .x_panel{
    border-radius: 5px;
}
.title-cadastro {
  margin-top: 11px;
  margin-left: -13px;
  width: 400px !important;
}
</style>
<script type="text/javascript" src="{$pathJs}/crm/s_cliente_endereco.js"> </script>
  <!-- page content -->
  <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}>
        <input name=opcao         type=hidden value={$opcao}>
        <input name=id_cliente    type=hidden value={$id_cliente}>

        
        <div class="">

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                      <div class="">
                          <div class="col-md-2">
                            <h3 class="title-cadastro_">Endereço entrega -</h3>
                          </div>
                          <div class="col-md-10 title-cadastro">
                            <h2><i>Consulta</i></h2>
                          </div>
                      </div>

                      <ul class="nav navbar-right panel_toolbox">
                          <li><button type="button"  class="btn btn-primary"  onClick="javascript:submitCadastro();">
                                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Cadastro </span></button>
                          </li>
                      </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>Titulo End.</th>
                                <th>Endereco</th>
                                <th>Numero</th>
                                <th>Complemento</th>
                                <th>Bairro</th>
                                <th>Cidade</th>
                                <th>Fone</th>
                                <th>Man.</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                <tr class="even pointer">

                                    <td> {$lanc[i].TITULOEND} </td>
                                    <td> {$lanc[i].ENDERECO} </td>
                                    <td> {$lanc[i].NUMERO} </td>
                                    <td> {$lanc[i].COMPLEMENTO} </td>
                                    <td> {$lanc[i].BAIRRO} </td>
                                    <td> {$lanc[i].CIDADE} </td>
                                    <td> {$lanc[i].FONE} </td>
                                    <td style="width:30px;">
                                        <button type="button" class="btn btn-success btn-xs" onclick="javascript:fechaPesqEndEntrega('{$lanc[i].ID}', '{$lanc[i].TITULOEND}');"> 
                                            <span class="glyphicon glyphicon-ok"></span>
                                        </button>
                                    </td>
                                </tr>
                          {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>


    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
