<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal.js"> </script>
    <!-- page content -->
    <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=opcao         type=hidden value="{$opcao}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}>
        <input name=grupoBase     type=hidden value={$grupoBase}>
        <input name=nivel         type=hidden value={$nivel}>

        
        <div class="">
            <div class="page-title">
              <div class="title_left">
                <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>

                <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>   Consolida&ccedil;&atilde;o Produtos</h3>
                <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                </h2>
              </div>
            </div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                          <table id="datatable-buttons" class="table table-bordered jambo_table small">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>Quant</th>
                                    <th>Total</th>
                                    <th>Nf</th>
                                    <th>Emissão</th>
                                    <th>Pedido</th>
                                    <th>Cliente</th>
                                </tr>
                            </thead>
                            <tbody>
                                {assign var="quant" value=0}
                                {assign var="total" value=0}
                                {assign var="icms" value=0}
                                {assign var="pis" value=0}
                                {assign var="cofins" value=0}
                                {section name=i loop=$lanc}
                                        {assign var="total" value=$total+$lanc[i].TOTAL}
                                        {assign var="quant" value=$quant+$lanc[i].QUANT}

                                    <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                        <td> {$lanc[i].CODPRODUTO} </td>
                                        <td> {$lanc[i].DESCRICAO} </td>
                                        <td> {$lanc[i].QUANT|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].NUMERO} </td>
                                        <td> {$lanc[i].EMISSAO|date_format:"%e %b, %Y %H:%M:%S"} </td>
                                        <td> {$lanc[i].DOC} </td>
                                        <td> {$lanc[i].NOMEREDUZIDO} </td>

                                    </tr>
                                <p>
                                {sectionelse}
                                    <tr>
                                            <td>n&atilde;o h&aacute; Lan&ccedil;amentos Cadastrados</td>
                                    </tr>
                                {/section}

                                    <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                        <td>  </td>
                                        <td> T O T A L </td>
                                        <td> {$quant|number_format:2:",":"."} </td>
                                        <td> {$total|number_format:2:",":"."} </td>
                                        <td>  </td>
                                        <td>  </td>
                                        <td>  </td>
                                        <td>  </td>
                                    </tr>
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
