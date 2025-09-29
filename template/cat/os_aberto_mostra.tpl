        <!-- page content -->
        <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}>

        
        <div class="">
              <div class="col-md-12 col-sm-12 col-xs-12 small">
                  <div class="x_title">
                    <h2>Ordem Servi&ccedil;os Abertas
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
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
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>Ordem Servi&ccedil;o</th>
                                <th>Abertura</th>
                                <th>Nome</th>
                                <th>Fone</th>
                                <th>Endere&ccedil;o</th>
                                <th class=" no-link last" ></th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].NUMATENDIMENTO}</td>
                                    <td> {$lanc[i].DATAABERATEND} {$lanc[i].HORAABERATEND}</td>
                                    <td> {$lanc[i].NOMEREDUZIDO} </td>
                                    <td> {$lanc[i].FONEAREA} {$lanc[i].FONE}</td>
                                    <td> {$lanc[i].ENDERECO}, {$lanc[i].NUMERO}, {$lanc[i].COMPLEMENTO} - {$lanc[i].BAIRRO} - {$lanc[i].CIDADE}</td>
                                    <td class=" last">
                                        <button type="button" class="btn btn-success btn-xs" onclick="javascript:submitExcluir('{$lanc[i].ID}');"><span class="glyphicon glyphicon-check" aria-hidden="true"></span></button>
                                    </td>
                                </tr>
                        {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
          </div> <!-- class='' = controla menu user -->

    </form>


    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
