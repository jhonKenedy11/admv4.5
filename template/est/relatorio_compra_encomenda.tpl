<section class="height100">
<!-- page content -->
<div class="right_col" role="main">
      <div class="col-md-12 col-sm-12 col-xs-12 form-group">
            <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                  <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
            </div>   
            <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                <div>
                  <h2>
                        <center>
                        <strong>COMPRA POR ENCOMENDA </strong><br>
                        <h5>Data
                        <br>{$dataImp}
                        </h5>
                      </center>
                  </h2>
                </div>

            </div>   
      </div>

      <!-- page content -->
      <div class="right_col" role="main">
          <div class="clearfix"></div-->
                <div class="x_panel">
                        <div class="x_content">
                              <section class="content invoice">
                                    <div class="row small">
                                          <div class="col-xs-12 table">
                                                {foreach $pedidos as $ped}
                                                <div class="pedido-bloco" style="margin-bottom:15px;border:1px solid #ccc;">
                                                      <div class="pedido-cabecalho" style="background:#2a3f54;color:#fff;padding:6px 10px;font-weight:bold;">
                                                            Pedido {$ped.PEDIDO} &mdash; {$ped.CLIENTE}
                                                            {if $ped.PRAZOENTREGA neq ''} | Prazo: {$ped.PRAZOENTREGA}{/if}
                                                      </div>
                                                      <table class="table table-striped" style="margin-bottom:0;">
                                                            <thead>
                                                                  <tr>
                                                                        <th></th>
                                                                        <th>C&Oacute;DIGO</th>
                                                                        <th>DESCRI&Ccedil;&Atilde;O</th>
                                                                        <th>GRUPO</th>
                                                                        <th>SOLIC.</th>
                                                                        <th>DISP.</th>
                                                                        <th>FALTA</th>
                                                                  </tr>
                                                            </thead>
                                                            <tbody>
                                                                  {foreach $ped.ITENS as $item}
                                                                  <tr>
                                                                        <td>{$item.NRITEM}</td>
                                                                        <td>{$item.CODIGO}</td>
                                                                        <td>{$item.DESCRICAO}</td>
                                                                        <td>{$item.NOMEGRUPO}</td>
                                                                        <td>{$item.QTSOLICITADA|number_format:2:",":"."}</td>
                                                                        <td>{$item.DISPONIVEL|number_format:2:",":"."}</td>
                                                                        <td>{$item.QTD_FALTA|number_format:2:",":"."}</td>
                                                                  </tr>
                                                                  {/foreach}
                                                                  <tr style="font-weight:bold;background:#f5f5f5;">
                                                                        <td colspan="6" style="text-align:right;">Total em falta</td>
                                                                        <td>{$ped.TOTAL_FALTA|number_format:2:",":"."}</td>
                                                                  </tr>
                                                            </tbody>
                                                      </table>
                                                </div>
                                                {foreachelse}
                                                <p>Nenhum registro localizado.</p>
                                                {/foreach}
                                          </div>
                                    </div>
                              </section>
                        </div>
                </div>
          </div>
      </div>
      
      <div class="row no-print">
            <div class="col-xs-12">
              <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
      
</div>
<!-- /page content -->
<style>
.height100 {
      height: 100vh;
      background-color: #F7F7F7;
      margin-top: 0;
      margin-bottom: 0;
      padding: 0;
}

@media print{
      @page{
            margin-top: 0;
            margin-bottom: 0;
            display: none;
            }
    
      .no-print{
      display: none;
      }

      td{
            font-size: 9px;
      }
      tr{
            font-size: 9px;
      }

}

</style>

