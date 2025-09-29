<style>
      .message-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 70vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
      }

      .message-container h4 {
            color: #6c757d;
            font-size: 1.5rem;
            text-align: center;
      }

      .height100 {
            min-height: 100vh;
            background-color: #F7F7F7;
            margin: 0;
            padding: 10px;
      }

      .dataHora {
            font-size: 9px;
      }

      .table {
            font-size: 10px;
            width: 100%;
            table-layout: fixed;
      }

      .table th {
            padding: 2px 3px !important;
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
      }

      .table td {
            padding: 2px 3px !important;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 120px;
      }

      .x_panel {
            margin-top: 5px;
      }

      .table-responsive {
            overflow-x: auto;
            max-width: 100%;
      }

      h2 {
            font-size: 14px;
            margin: 5px 0;
      }

      @media print {
            @page {
                  margin: 0.5cm;
                  size: landscape;
            }

            body {
                  font-size: 9pt;
            }

            .table th,
            .table td {
                  padding: 1px 2px !important;
                  font-size: 9px !important;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 12px;
            }
      }
</style>

<section class="height100">
      <div class="right_col" role="main">
            <div class="">
                  <div class="col-md-4 col-sm-4 col-xs-4">
                        <img src="images/logo.png" aloign="right" width=180 height=46 border="0"></A>
                  </div>
                  <div class="col-md-5 col-sm-5 col-xs-5">
                        <div>
                              <h2 class="text-center">
                                    <strong>Relatório Aniversario</strong><br>
                                    <strong>Período - {$dataIni} | {$dataFim}</strong>
                              </h2>
                        </div>
                  </div>
                  <div class="col-md-3 col-sm-3 col-xs-3">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>

            </div>


            <div class="clearfix"></div>
            <div class="x_panel">
                  <div class="x_content">
                        {if count($lanc) > 0}
                              <div class="table-responsive">
                                    <table class="table table-striped" style="margin-bottom: 0;">
                                          <thead>
                                                <tr>
                                                      <th style="width: 20%">Nome completo</th>
                                                      <th style="width: 10%">Nascimento</th>
                                                      <th style="width: 10%">Telefone</th>
                                                      <th style="width: 12%">Email</th>
                                                      <th style="width: 20%">Cidade</th>
                                                      <th style="width: 20%">Bairro</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                {foreach $lanc as $item}
                                                      <tr>
                                                            <td style="white-space: nowrap;">{$item.NOME}</td>
                                                            <td>{$item.DATANASCIMENTO|date_format:"%d/%m/%Y"}</td>
                                                            <td>
                                                                  {if $item.FONE}
                                                                        {if $item.FONEAREA}({$item.FONEAREA}) {/if}{$item.FONE}
                                                                  {/if}
                                                            </td>
                                                            <td>{$item.EMAIL}</td>
                                                            <td>{$item.CIDADE}</td>
                                                            <td>{$item.BAIRRO}</td>
                                                      </tr>
                                                {/foreach}
                                          </tbody>
                                    </table>
                              </div>
                        {else}
                              <div class="message-container">
                                    <h4>Nenhum registro localizado!</h4>
                              </div>
                        {/if}
                  </div>
            </div>

            <div class="row no-print">
                  <div class="col-xs-12 text-center">
                        <button class="btn btn-default" onclick="window.print();">
                              <i class="fa fa-print"></i> Imprimir
                        </button>
                  </div>
            </div>
      </div>
</section>