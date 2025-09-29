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
            <div class="row">
                  <div class="col-md-2 col-sm-2 col-xs-2">
                        <img src="images/logo.png" align="left" width="180" height="46" border="0">
                  </div>
                  <div class="col-md-8 col-sm-8 col-xs-8 text-center">
                        <h2 style="margin: 0; padding: 0;">
                              <strong>Relatório Equipamento</strong><br>
                              Período - {$data_ini} | {$data_fim}
                        </h2>
                  </div>
                  <div class="col-md-2 col-sm-2 col-xs-2">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>
            </div>

            <div class="clearfix"></div>
            <div class="x_panel">
                  <div class="x_content">
                        {if count($lanc) > 0}
                              <div class="table-responsive">
                                    <table class="table table-striped">
                                          <thead>
                                                <tr>
                                                      <th style="width: 5%">Nº OS</th>
                                                      <th style="width: 5%">Nº Pedido</th>
                                                      <th style="width: 30%">Equipamento</th>
                                                      <th style="width: 20%">Cliente</th>
                                                      <th style="width: 20%">Serviço</th>
                                                      <th style="width: 10%">Data Abertura</th>
                                                      <th style="width: 10%">Data Fechamento</th>
                                                </tr>
                                          </thead>
                                          <tbody>
                                                {foreach $lanc as $item}
                                                      <tr>
                                                            <td>{$item.num_os}</td>
                                                            <td>{$item.num_pedido}</td>
                                                            <td>{$item.equipamento}</td>
                                                            <td>{$item.cliente}</td>
                                                            <td>{$item.id_servico}</td>
                                                            <td>{$item.data_abertura|date_format:"%d/%m/%Y"}</td>
                                                            <td>
                                                                  {if $item.data_fechamento}
                                                                        {$item.data_fechamento|date_format:"%d/%m/%Y"}
                                                                  {else}
                                                                        -
                                                                  {/if}
                                                            </td>
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
                        <button class="btn btn-default btn-sm" onclick="window.print();">
                              <i class="fa fa-print"></i> Imprimir
                        </button>
                  </div>
            </div>
      </div>
</section>