<script type="text/javascript" src="{$pathJs}/blt/s_blt.js"> </script>
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
                  <h3>Impress&atilde;o de Boletos</h3>
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
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitCadastro('banco');">
                                <span class="glyphicon glyphicon-print" aria-hidden="true"></span><span> Imprimir</span></button></li>
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
                                <th>Cliente Nome</th>
                                <th>Docto - S&eacute;rie - Parcela</th>
                                <th>Conta</th>
                                <th>Vencimento</th>
                                <th class=" no-link last" style="width: 40px;">Imprimir</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].PESSOA} </td>
                                    <td> {$lanc[i].DOCTO} - {$lanc[i].SERIE} - {$lanc[i].PARCELA}</td>
                                    <td> {$lanc[i].CONTA}</td>
                                    <td> {$lanc[i].VENCIMENTO}</td>
                                    <td class=" last">
                                        <button type="button" class="btn btn-info btn-xs" 
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=blt&form=boleto_imprime&opcao=blank&letra={$lanc[i].ID}');">
                                            <span class="glyphicon glyphicon-print" aria-hidden="true"></span>
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
