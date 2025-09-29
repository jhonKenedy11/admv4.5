<script type="text/javascript" src="{$pathJs}/cat/s_servico_new.js"> </script>
  <!-- page content -->
  <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod                  type=hidden value="cat">   
        <input name=form                 type=hidden value="servico">   
        <input name=id                   type=hidden value="">
        <input name=letra                type=hidden value={$letra}>
        <input name=submenu              type=hidden value={$subMenu}>
        <input name=idServicos           type=hidden value="{$idServicos}">
        <input name=quantidadeServico    type=hidden value="{$quantidadeServico}"> <!-- Atendimento -->
        <input name=vlrUnitarioServico   type=hidden value="{$vlrUnitarioServico}"> <!-- Atendimento -->
        <input name=origem               type=hidden value="{$origem}">

        
        <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Serviço</h3>
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
                        <li><button type="button" disabled class="btn btn-primary"  onClick="javascript:submitCadastro('banco');">
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
                  
                  
                </div> <!-- div class="x_panel" = painel principal-->
                  <div class="panel-body">
                          <div class="x_panel">
                              <div class="col-md-12 col-sm-12 col-xs-12">
                                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                                              <thead>
                                                  <tr class="headings">
                                                      <th>Código</th>
                                                      <th>Descrição</th>
                                                      <th>Unidade</th>
                                                      <th>Valor Unitário</th>
                                                      <th>Selecionar</th>
                                                  </tr>
                                              </thead>
                                              <tbody>
                                                  {section name=i loop=$lanc}
                                                      {assign var="total" value=$total+1}
                                                      <tr class="even pointer">
                                                          <td> {$lanc[i].ID} </td>
                                                          <td> {$lanc[i].DESCRICAO} </td>
                                                          <td> {$lanc[i].UNIDADE} </td>
                                                          <td> {$lanc[i].VALORUNITARIO|number_format:2:",":"."}</td>
                                                          <td class=" last">
                                                              <button type="button" class="btn btn-success btn-xs" 
                                                              onclick="javascript:fechaServicoPesquisaAtendimento(this);">
                                                              <span class="glyphicon glyphicon-ok" aria-hidden="true"></span></button>
                                                          </td>
                                                      </tr>
                                              {/section} 

                                              </tbody>

                                          </table>
                            </div>
                                        
                        </div>
                      </div>
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>


    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
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