<style>

      .height100 {
            min-height: 100vh;
            background-color: #F7F7F7;
            margin: 0;
            padding: 0px;
      }


      .div-table {
            display: table;
            width: 100%;
            max-width: 100%;
            font-size: 10px;
            line-height: 1.1;
            border-collapse: collapse;
      }

      .div-table-row {
            display: table-row;
            height: 18px;
      }

      .div-table-cell,
      .div-table-header {
            display: table-cell;
            padding: 2px;
            font-size: 10px;
            white-space: nowrap;
            overflow: hidden;
            vertical-align: middle;
            text-overflow: ellipsis;
            line-height: 1.1;
            border: 1px solid #ddd;
      }

      .div-table-header {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
      }

      .div-table-cell {
            max-width: 120px;
            display: table-cell;
      }

      .dataHora {
            font-size: 10px;
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
      }

      .x_panel {
            margin-top: 3px;
            padding: 0px;
            background-color: white;
            border: none;
      }

      .table-responsive {
            overflow-x: auto;
            max-width: 100%;
            margin-bottom: 10px;
            border: none;
      }

      h2 {
            font-size: 14px;
            margin: 5px 0;
      }

      .div-table-cell.desc-servico {
            word-wrap: break-word;
            white-space: normal;
      }

      .total-row {
            font-weight: bold;
            background-color: #e9ecef !important;
      }


      .no-print {
            display: block;
      }

      @media print {
            .no-print {
                  display: none !important;
            }
      }

      .periodo-info {
            background-color: #e3f2fd;
            border: 1px solid #2196f3;
            padding: 8px;
            margin: 10px 0;
            border-radius: 4px;
            font-size: 11px;
      }

      .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
      }



      @media print {
            body,
            .height100 {
                  background-color: white !important;
                  min-height: auto !important;
                  margin: 0 !important;
                  padding: 0 !important;
            }

            .relatorio-container {
                  max-width: 100% !important;
                  margin: 0 !important;
                  padding: 0 !important;
                  width: 100% !important;
            }

            .header-container {
                  display: flex !important;
                  page-break-inside: avoid !important;
                  margin-bottom: 15px !important;
            }

            .div-table-cell img {
                  max-height: 60px !important;
                  max-width: 100% !important;
                  height: auto !important;
            }

            .header-container .div-table-cell {
                  border: none !important;
                  padding: 5px !important;
            }

            .header-container .div-table-cell:first-child {
                  width: 20% !important;
                  text-align: center !important;
            }

            .header-container .div-table-cell:last-child {
                  width: 80% !important;
                  text-align: center !important;
            }


            .div-table-cell.desc-servico {
                  word-wrap: break-word;
                  white-space: normal;
                  font-size: 9px !important;
            }

            .x_panel {
                  page-break-inside: avoid !important;
                  border: none !important;
                  margin: 0 0 15px 0 !important;
                  padding: 0 !important;
                  background-color: white !important;
            }

            .div-table {
                  font-size: 8px !important;
                  line-height: 1.1 !important;
                  width: 100% !important;
                  table-layout: fixed !important;
                  display: table !important;
            }

            .div-table-cell,
            .div-table-header {
                  padding: 2px 1px !important;
                  font-size: 8px !important;
                  line-height: 1.1 !important;
                  border: 1px solid #000 !important;
                  vertical-align: middle !important;
                  display: table-cell !important;
            }

            .div-table-header {
                  background-color: #f0f0f0 !important;
                  font-weight: bold !important;
            }

            h2 {
                  font-size: 12px !important;
                  margin: 5px 0 8px 0 !important;
                  font-weight: bold !important;
            }

            .no-print {
                  display: none !important;
            }

            @page {
                  margin: 0.5cm;
                  size: A4 portrait;
            }

            .table-responsive {
                  page-break-inside: avoid;
                  margin-bottom: 10px !important;
            }

            .div-table-row {
                  page-break-inside: avoid;
                  page-break-after: auto;
            }

            .total-row {
                  background-color: #f0f0f0 !important;
                  font-weight: bold !important;
            }

            .periodo-info {
                  display: none !important;
            }

            .div-table-row {
                  height: auto !important;
                  min-height: 18px !important;
            }

            .div-table-cell {
                  vertical-align: middle !important;
            }

            .status-badge {
                  padding: 1px 3px !important;
                  font-size: 7px !important;
                  border-radius: 2px !important;
            }

            .div-table-row {
                  height: 16px !important;
                  min-height: 16px !important;
            }

            .div-table-cell.desc-servico {
                  font-size: 7px !important;
                  line-height: 1.0 !important;
                  padding: 1px !important;
            }

            h2 {
                  font-size: 10px !important;
                  margin: 3px 0 5px 0 !important;
                  font-weight: bold !important;
            }

            .x_panel {
                  margin-bottom: 8px !important;
            }

            .table-responsive {
                  width: 100% !important;
                  overflow: visible !important;
            }

            .div-table-row {
                  width: 100% !important;
            }

            .div-table-cell,
            .div-table-header {
                  width: auto !important;
            }

            .x_content {
                  width: 100% !important;
                  padding: 0 !important;
                  margin: 0 !important;
            }

            .x_panel .x_content {
                  width: 100% !important;
            }

            .right_col {
                  width: 100% !important;
                  max-width: 100% !important;
                  margin: 0 !important;
                  padding: 0 !important;
            }

            .row {
                  margin: 0 !important;
                  width: 100% !important;
            }

            .col-md-12, .col-sm-12, .col-xs-12 {
                  width: 100% !important;
                  padding: 0 !important;
            }
      }

      .header-container {
            margin-bottom: 2px;
            border: none;
      }


      .relatorio-container {
            max-width: 100%;
            margin: 0;
            padding: 0;
      }
</style>

<section class="height100">
      <div class="right_col relatorio-container" role="main">
            <div class="row header-container" style="page-break-inside: avoid;">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel" style="margin-bottom: 0; height: 100%;">
                              <div class="div-table">
                                    <div class="div-table-row">
                                          <div class="div-table-cell"
                                                style="width: 20%; vertical-align: middle; text-align: center; border-right: none; padding: 5px;">
                                                <img src="images/logo.png"
                                                      style="max-height: 80px; max-width: 100%; height: auto; display: inline-block;">
                                          </div>

                                          <div class="div-table-cell"
                                                style="width: 80%; border-left: none; padding-left: 15px; vertical-align: middle; position: relative;">
                                                <div style="text-align: center;">
                                                      <div style="font-size: 20px; font-weight: bold; color: #ff6600; margin-bottom: 5px;">
                                                            CONCRETA
                                                      </div>
                                                      <div style="font-size: 12px; font-weight: bold; margin-bottom: 8px;">
                                                            RELATÓRIO DE ACOMPANHAMENTO
                                                      </div>
                                                      <div style="font-size: 10px; color: #666; margin-bottom: 5px;">
                                                            ACOMPANHAMENTO DE CONTRATOS E ORDENS DE SERVIÇO
                                                      </div>
                                                </div>
                                                <b class="dataHora">{$smarty.now|date_format:'%d/%m/%Y %H:%M'}</b>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>

            {if $data_inicio_periodo && $data_fim_periodo}
            <div class="periodo-info">
                  <strong>Período de Análise:</strong> {$data_inicio_periodo} até {$data_fim_periodo}
            </div>
            {/if}

            {if !empty($lanc)}
            <div class="x_panel">
                  <div class="x_content" id="cabecalho">
                        <div class="clearfix"></div>
                        <div class="div-table">
                              {assign var="primeiro_servico" value=$lanc[0]}
                              <div class="div-table-row">
                                    <div class="div-table-cell"><strong>Cliente: </strong>
                                          {$primeiro_servico.NOME_CLIENTE|default:"-"}</div>
                                    <div class="div-table-cell"><strong>Contrato: </strong>
                                          {$primeiro_servico.PEDIDO_ID|default:"-"}</div>
                                    <div class="div-table-cell"><strong>Data Abertura: </strong>
                                          {if $primeiro_servico.DATAABERATEND}
                                                {$primeiro_servico.DATAABERATEND|date_format:"%d/%m/%Y"}
                                          {else}
                                                -
                                          {/if}</div>
                              </div>
                              <div class="div-table-row">
                                    <div class="div-table-cell"><strong>Valor Total: </strong>
                                          R$ {$primeiro_servico.TOTAL|number_format:2:",":"."}</div>
                                    <div class="div-table-cell"><strong>Período: </strong>
                                          {$dataIni} até {$dataFim}</div>
                                    <div class="div-table-cell"><strong>Prazo Entrega: </strong>
                                          {if $primeiro_servico.PRAZOENTREGA}
                                                {$primeiro_servico.PRAZOENTREGA|date_format:"%d/%m/%Y"}
                                          {else}
                                                -
                                          {/if}</div>
                              </div>
                        </div>
                  </div>
            </div>
            {/if}

            {if !empty($lanc)}
            <div class="x_panel">
                  <div class="x_content" id="corpo_relatorio">
                        <h2>Ordens de Serviço Cadastradas</h2>
                        <div class="table-responsive">
                              <div class="div-table">
                                    <div class="div-table-row">
                                          <div class="div-table-header" style="width: 8%">OS</div>
                                          <div class="div-table-header" style="width: 15%">Data Abertura</div>
                                          <div class="div-table-header" style="width: 15%">Prazo Entrega</div>
                                          <div class="div-table-header" style="width: 20%">Valor Serviços</div>
                                          <div class="div-table-header" style="width: 20%">Valor Total</div>
                                          <div class="div-table-header" style="width: 22%">Situação OS</div>
                                    </div>

                                    {assign var="os_ja_exibidas" value=[]}
                                    {foreach $lanc as $servico}
                                          {if !in_array($servico.CAT_ATENDIMENTO_ID, $os_ja_exibidas)}
                                                {assign var="os_ja_exibidas" value=$os_ja_exibidas|@array_merge:[$servico.CAT_ATENDIMENTO_ID]}
                                                <div class="div-table-row">
                                                      <div class="div-table-cell" style="text-align: center;">{$servico.CAT_ATENDIMENTO_ID}</div>
                                                      <div class="div-table-cell" style="text-align: center;">
                                                            {if $servico.DATAABERATEND}
                                                                  {$servico.DATAABERATEND|date_format:"%d/%m/%Y %H:%M"}
                                                            {else}
                                                                  -
                                                            {/if}
                                                      </div>
                                                      <div class="div-table-cell" style="text-align: center;">
                                                            {if $servico.PRAZOENTREGA}
                                                                  {$servico.PRAZOENTREGA|date_format:"%d/%m/%Y %H:%M"}
                                                            {else}
                                                                  -
                                                            {/if}
                                                      </div>
                                                      <div class="div-table-cell" style="text-align: right;">
                                                            R$ {$servico.VALORSERVICOS|number_format:2:",":"."}
                                                      </div>
                                                      <div class="div-table-cell" style="text-align: right;">
                                                            R$ {$servico.VALORTOTAL|number_format:2:",":"."}
                                                      </div>
                                                      <div class="div-table-cell" style="text-align: center;">
                                                            {if $servico.SITUACAO}
                                                                  <span class="status-badge status-{$servico.SITUACAO_ID|default:'aberto'}">{$servico.SITUACAO}</span>
                                                            {else}
                                                                  <span class="status-badge status-aberto">ABERTO</span>
                                                            {/if}
                                                      </div>
                                                </div>
                                          {/if}
                                    {/foreach}
                              </div>
                        </div>
                  </div>
            </div>
            {/if}

            {if !empty($lanc)}
            <div class="x_panel">
                  <div class="x_content" id="servicos_contrato">
                        <h2>Serviços Executados</h2>
                        <div class="table-responsive">
                              <div class="div-table">
                                    <div class="div-table-row">
                                          <div class="div-table-header" style="width: 4%">#</div>
                                          <div class="div-table-header" style="width: 52%">Descrição do Serviço</div>
                                          <div class="div-table-header" style="width: 5%">Unidade</div>
                                          <div class="div-table-header" style="width: 8%">Qtd. Contratada</div>
                                          <div class="div-table-header" style="width: 8%">Qtd. Executada</div>
                                          <div class="div-table-header" style="width: 8%">Saldo</div>
                                          <div class="div-table-header" style="width: 7%">% Exec.</div>
                                          <div class="div-table-header" style="width: 4%">Valor Unit.</div>
                                          <div class="div-table-header" style="width: 4%">Valor Total</div>
                                    </div>

                                    {assign var="item_count" value=1}
                                    {assign var="total_contratado" value=0}
                                    {assign var="total_executado" value=0}
                                    {assign var="total_valor" value=0}

                                    {foreach $lanc as $servico}
                                          {assign var="saldo" value=$servico.QUANTIDADE - $servico.QUANTIDADE_EXECUTADA}
                                          {assign var="percentual" value=($servico.QUANTIDADE_EXECUTADA * 100) / $servico.QUANTIDADE_CONTRATADA}
                                          {* Cálculo igual ao relatório de medição: QUANTIDADE_EXECUTADA * VALUNITARIO *}
                                          {assign var="valor_total_item" value=$servico.QUANTIDADE_EXECUTADA * $servico.VALUNITARIO}

                                          {assign var="total_contratado" value=$total_contratado + $servico.QUANTIDADE}
                                          {assign var="total_executado" value=$total_executado + $servico.QUANTIDADE_EXECUTADA}
                                          {assign var="total_valor" value=$total_valor + $valor_total_item}

                                          <div class="div-table-row">
                                                <div class="div-table-cell" style="text-align: center;">{$item_count}</div>
                                                <div class="div-table-cell desc-servico">{$servico.DESCSERVICO}</div>
                                                <div class="div-table-cell" style="text-align: center;">{$servico.UNIDADE|default:"UN"}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$servico.QUANTIDADE_CONTRATADA|number_format:2:",":"."}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$servico.QUANTIDADE_EXECUTADA|number_format:2:",":"."}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$saldo|number_format:2:",":"."}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$percentual|number_format:1:",":"."}%</div>
                                                <div class="div-table-cell" style="text-align: right;">R$ {$servico.VALUNITARIO|number_format:2:",":"."}</div>
                                                <div class="div-table-cell" style="text-align: right;">R$ {$valor_total_item|number_format:2:",":"."}</div>
                                          </div>
                                          {assign var="item_count" value=$item_count+1}
                                    {/foreach}

                                    <div class="div-table-row total-row">
                                          <div class="div-table-cell" style="text-align: center;"><strong></strong></div>
                                          <div class="div-table-cell" style="text-align: left;"><strong>TOTAIS:</strong></div>
                                          <div class="div-table-cell" style="text-align: center;"><strong></strong></div>
                                          <div class="div-table-cell" style="text-align: right;"><strong>{$total_contratado|number_format:2:",":"."}</strong></div>
                                          <div class="div-table-cell" style="text-align: right;"><strong>{$total_executado|number_format:2:",":"."}</strong></div>
                                          <div class="div-table-cell" style="text-align: right;"><strong>{($total_contratado - $total_executado)|number_format:2:",":"."}</strong></div>
                                          <div class="div-table-cell" style="text-align: right;"><strong>{($total_executado * 100 / $total_contratado)|number_format:1:",":"."}%</strong></div>
                                          <div class="div-table-cell" style="text-align: right;"><strong></strong></div>
                                          <div class="div-table-cell" style="text-align: right;"><strong>R$ {$total_valor|number_format:2:",":"."}</strong></div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
            {/if}

            <div class="x_panel">
                  <div class="x_content" id="resumo_financeiro">
                        <h2>Resumo Financeiro</h2>
                        <div class="table-responsive">
                              <div class="div-table">
                                    {* Calcular valor total se todos os serviços fossem executados 100% *}
                                    {assign var="valor_total_servicos" value=0}
                                    {foreach $lanc as $servico}
                                          {assign var="valor_total_servico" value=$servico.QUANTIDADE * $servico.VALUNITARIO}
                                          {assign var="valor_total_servicos" value=$valor_total_servicos + $valor_total_servico}
                                    {/foreach}
                                    
                                    {* Valor executado é o total calculado na tabela de serviços executados *}
                                    {assign var="valor_executado" value=$total_valor}
                                    {* Saldo a executar é a diferença entre total dos serviços e executado *}
                                    {assign var="saldo_executar" value=$valor_total_servicos - $valor_executado}
                                    {* Percentual executado igual ao da tabela de serviços executados *}
                                    {assign var="percentual_execucao" value=($total_executado * 100) / $total_contratado}
                                    
                                    <div class="div-table-row total-row">
                                          <div class="div-table-cell" style="width: 70%">Valor Total dos Serviços:</div>
                                          <div class="div-table-cell" style="text-align: right; width: 30%">R$ {$valor_total_servicos|number_format:2:",":"."}</div>
                                    </div>
                                    <div class="div-table-row total-row">
                                          <div class="div-table-cell" style="width: 70%">Valor Executado:</div>
                                          <div class="div-table-cell" style="text-align: right; width: 30%">R$ {$valor_executado|number_format:2:",":"."}</div>
                                    </div>
                                    <div class="div-table-row total-row">
                                          <div class="div-table-cell" style="width: 70%">Saldo a Executar:</div>
                                          <div class="div-table-cell" style="text-align: right; width: 30%">R$ {$saldo_executar|number_format:2:",":"."}</div>
                                    </div>
                                    <div class="div-table-row total-row">
                                          <div class="div-table-cell" style="width: 70%">Percentual Executado:</div>
                                          <div class="div-table-cell" style="text-align: right; width: 30%">{$percentual_execucao|number_format:1:",":"."}%</div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>

            <div class="x_panel">
                  <div class="x_content" id="assinaturas">
                        <div class="div-table">
                              <div class="div-table-row">
                                    <div class="div-table-cell" style="width: 50%; text-align: center; padding: 20px;">
                                          <strong>Responsável Técnico</strong><br>
                                          <br><br>
                                          _________________________________<br>
                                          Nome e CREA
                                    </div>
                                    <div class="div-table-cell" style="width: 50%; text-align: center; padding: 20px;">
                                          <strong>Cliente</strong><br>
                                          <br><br>
                                          _________________________________<br>
                                          Nome e Assinatura
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>
      </div>

      <div class="row no-print">
            <div class="col-xs-12 text-center">
                  <button class="btn btn-default btn-sm" onclick="window.print();">
                        <i class="fa fa-print"></i> Imprimir
                  </button>
            </div>
      </div>
</section>
