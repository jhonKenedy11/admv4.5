<style>
.table>tbody>tr>td{
      padding: 4px !important;
}
.table{
      margin-bottom: 0 !important;
}
img{
      border-radius: 5px;
}
      @media print {
            @page {
                  size: A4;
                  padding: 0;
                  margin: 0;
            }

            .no-print {
                  display: none;
            }

            tr.avoid-page-break {
                  page-break-before: avoid;
            }
            .table>tbody>tr>td{
                  padding: 2px !important;
                  font-size: 10px;
            }
      }
</style>
<!-- page content -->
<div class="right_col" role="main" style="padding: 0 !important;">
      <div class="x_panel">
            <div class="row small">
                  <div class="row col-md-12 col-sm-12 col-xs-12 cabecalho" style="padding-right: 0;">
                        <div class="col-md-3 col-sm-3 col-xs-3">
                              <img src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
                        </div>
                        <div class="col-md-7 col-sm-7 col-xs-7" style="text-align: center !important;">
                              <h5>
                                    <strong>VENDAS NO PERÍODO</strong>
                                    <br>
                                    {$periodoIni} | {$periodoFim}
                              </h5>
                        </div>
                        <div class="col-md-2 col-sm-2 col-xs-2 text-right" style="padding: 0; font-weight: bold; font-size: 9px;">
                              {$dataHoraNow}
                        </div>
                  </div>
                  <br>
                  <br>
                  <br>
                  <br>
                  <table class="table">
                        <thead style="border-top: 2px solid #ddd;">
                              <tr>
                                    <th>EMISSAO</th>
                                    <th>SERIE</th>
                                    <th>NF</th>
                                    <th>CLIENTE</th>
                                    <th>TOTAL</th>
                              </tr>
                        </thead>
                        <tbody>
                              {section name=i loop=$lanc}
                                    {assign var="total" value=$total+$lanc[i].TOTALNF}
                                    <tr class="avoid-page-break">
                                          <td> {$lanc[i].EMISSAO|date_format:"%d/%m/%Y"}</td>
                                          <td> {$lanc[i].SERIE} </td>
                                          <td> {$lanc[i].NUMERO} </td>
                                          <td> {$lanc[i].NOMECLIENTE} </td>
                                          <td> {$lanc[i].TOTALNF|number_format:2:",":"."}</td>
                                    </tr>
                              {/section}
                              <tr>
                                    <td>
                                          <h6><b>TOTAL </b></h6>
                                    </td>
                                    <td> </td>
                                    <td> </td>
                                    <td> </td>
                                    <td>
                                          <h6><b> {$total|number_format:2:",":"."}</b></h6>
                                    </td>
                              </tr>
                        </tbody>
                  </table>
            </div>
      </div>
      <div class="row no-print">
            <div class="col-xs-12">
                  <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
      </div>
</div>
