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
            padding: 0px;
      }

      .dataHora {
            font-size: 9px;
            margin: 0;
            line-height: 1.1;
      }

      .div-table {
            display: table;
            width: 100%;
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

      .subtotal-row {
            font-weight: bold;
            background-color: #f5f5f5 !important;
      }

      .total-os-row {
            font-weight: bold;
            background-color: #e9ecef !important;
      }

      .total-os-cell {
            text-align: right !important;
      }

      .no-print {
            display: block;
      }

      @media print {

            body,
            .height100 {
                  background-color: white !important;
                  min-height: auto !important;
            }

            .header-container {
                  display: flex !important;
                  page-break-inside: avoid !important;
            }

            .div-table-cell img {
                  max-height: 120px !important;
                  max-width: 100% !important;
                  height: auto !important;
            }

            .logo-container {
                  padding-right: 0px !important;
            }

            .div-table-cell.desc-servico {
                  word-wrap: break-word;
                  white-space: normal;
            }

            .div-table-cell {
                  max-width: 60px !important;
                  font-size: 7px !important;
                  padding: 1px !important;
                  line-height: 1.0 !important;
            }

            .div-table-header {
                  font-size: 7px !important;
                  padding: 1px !important;
                  line-height: 1.0 !important;
            }

            .table-responsive {
                  overflow: visible !important;
                  width: 100% !important;
            }

            .x_panel {
                  page-break-inside: avoid !important;
                  border: none !important;
                  margin: 0 !important;
                  padding: 0 !important;
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
            }

            .div-table-row {
                  page-break-inside: avoid;
                  page-break-after: auto;
            }
      }

      .header-container {
            margin-bottom: 2px;
            border: none;
      }

      .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 0px;
      }
</style>

<section class="height100">
      <div class="right_col" role="main">
            <div class="row header-container" style="page-break-inside: avoid;">
                  <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel" style="margin-bottom: 0; height: 100%;">
                              <div class="div-table">
                                    <div class="div-table-row">
                                          <div class="div-table-cell"
                                                style="width: 30%; vertical-align: middle; text-align: center; border-right: none; padding: 5px;">
                                                <img src="images/logo.png"
                                                      style="max-height: 120px; max-width: 100%; height: auto; display: inline-block;">
                                          </div>

                                          <div class="div-table-cell"
                                                style="width: 100%; border-left: none; padding-left: 10px;">
                                                <div class="div-table" style="width: 100%;">
                                                      <div class="div-table-row">
                                                            {if !empty($lanc)}
                                                                  {assign var="header" value=$lanc[0]}
                                                                  <div class="div-table-cell" colspan="2"
                                                                        style="font-size: 14px; font-weight: bold; text-align: center;">
                                                                        PROPOSTA COMERCIAL
                                                                  </div>
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 11px;">
                                                                        <strong>Contrato: {$header.NUM_PEDIDO}</strong></div>

                                                            </div>

                                                            <div class="div-table-row">
                                                                  <div class="div-table-cell" colspan="2"
                                                                        style="font-size: 12px; text-align: center; word-wrap: break-word; white-space: normal">
                                                                        PROJETO E EXECUÇÃO DE PISO INDUSTRIAL
                                                                  </div>
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 11px;">
                                                                        Curitiba, {$dataImp}
                                                                  </div>
                                                            </div>
                                                            <div class="div-table-row">
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        Resp. Técnico Comercial:
                                                                  </div>
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        {$header.RESPONSAVEL_TECNICO}
                                                                  </div>
                                                            </div>

                                                            <div class="div-table-row">
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        CREA-PR:
                                                                  </div>
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        {$header.CREA}
                                                                  </div>
                                                            </div>

                                                            <div class="div-table-row">
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        Celular:
                                                                  </div>
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        {$header.FONE_RESPONSAVEL}
                                                                  </div>
                                                            </div>

                                                            <div class="div-table-row">
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        E-mail:
                                                                  </div>
                                                                  <div class="div-table-cell"
                                                                        style="text-align: left; font-size: 10px;">
                                                                        {$header.EMAIL_RESPONSAVEL}
                                                                  </div>
                                                            </div>
                                                      {/if}
                                                </div>
                                          </div>
                                    </div>
                              </div>
                        </div>
                  </div>
            </div>




            <div class="x_panel">
                  <div class="x_content" id="cabecalho">
                        <div class="div-table">
                              {if !empty($lanc)}
                                    {assign var="cabecalho" value=$lanc[0]}
                                    <div class="div-table-row">
                                          <div class="div-table-cell"><strong>Empresa: </strong>
                                                {$cabecalho.NOMEEMPRESA}</div>
                                          <div class="div-table-cell"><strong>CNPJ: </strong>
                                                {$cabecalho.CNPJ_EMPRESA}</div>
                                          <div class="div-table-cell"><strong>IN. ESTADUAL: </strong>
                                                {$cabecalho.INSC_ESTADUAL_EMPRESA}</div>
                                    </div>
                                    <div class="div-table-row">
                                          <div class="div-table-cell"><strong>Solicitante: </strong>
                                                {$cabecalho.CLIENTE}
                                          </div>
                                          <div class="div-table-cell"><strong>DOCUMENTO: </strong>
                                                {$cabecalho.DOCUMENTO_CLIENTE}</div>
                                          <div class="div-table-cell"><strong>IN. ESTADUAL: </strong>
                                                {$cabecalho.INSC_ESTADUAL_CLIENTE}</div>
                                    </div>
                                    <div class="div-table-row">
                                          <div class="div-table-cell" style="width: 25%;"><strong>Obra:
                                                </strong>{$cabecalho.OBRA}</div>
                                          <div class="div-table-cell" style="width: 25%;"><strong>CNO:
                                                </strong>{$cabecalho.CNO}</div>
                                          <div class="div-table-cell" style="width: 25%;"><strong>ART:
                                                </strong>{$cabecalho.ART}</div>
                                    </div>
                                    <div class="div-table-row">
                                          <div class="div-table-cell"><strong>Responsável Técnico: </strong>
                                                {$cabecalho.NOME_RESPONSAVEL_TECNICO}</div>
                                          <div class="div-table-cell"><strong>CREA: </strong>
                                                {$cabecalho.CREA_RESPONSAVEL_TECNICO}</div>
                                          <div class="div-table-cell"><strong>CPF: </strong>
                                                {$cabecalho.CPF_RESPONSAVEL_TECNICO}</div>
                                    </div>
                                    <div class="div-table-row">
                                          <div class="div-table-cell"><strong>Telefone: </strong>
                                                {$cabecalho.TELEFONE_RESPONSAVEL_TECNICO}</div>
                                          <div class="div-table-cell"><strong>Email: </strong>
                                                {$cabecalho.EMAIL_RESPONSAVEL_TECNICO}</div>
                                          <div class="div-table-cell"></div>
                                    </div>                                    
                              {/if}
                        </div>
                  </div>
            </div>

            <div class="x_panel">
                  <div class="x_content" id="corpo_relatorio">
                        {assign var="current_os" value=""}
                        {assign var="is_first_table" value=true}
                        {assign var="global_total" value=0}
                        {assign var="os_item_count" value=0}
                        {foreach $lanc as $item}
                              {* Verifica se a OS não está cancelada (STATUSDESC != "Cancelado") *}
                              {if $item.STATUSDESC != "Cancelado"}
                                    {if $item.NUM_OS != $current_os}
                                          {if !$is_first_table}
                                          </div>
                                    </div>
                              {/if}

                              <div class="table-responsive">
                                    <div class="div-table">
                                          <div class="div-table-row">
                                                <div class="div-table-header" style="width: 5%"><strong>OS: {$item.NUM_OS}</strong>
                                                </div>
                                                <div class="div-table-header" style="width: 45%">Descrição do Serviço</div>
                                                <div class="div-table-header" style="width: 8%">Unidade</div>
                                                <div class="div-table-header" style="width: 8%">Quantidade Contratada</div>
                                                <div class="div-table-header" style="width: 8%">Quantidade OS</div>
                                                <div class="div-table-header" style="width: 8%">Total Executado</div>
                                                <div class="div-table-header" style="width: 8%">Quantidade Pendente</div>
                                                <div class="div-table-header" style="width: 8%">% Exec.</div>
                                                <div class="div-table-header" style="width: 10%">V. Unitário</div>
                                                <div class="div-table-header" style="width: 10%">V. Total</div>
                                          </div>


                                          {assign var="current_os" value=$item.NUM_OS}
                                          {assign var="is_first_table" value=false}
                                          {assign var="os_total" value=0}
                                          {assign var="os_item_count" value=1}
                                    {/if}

                                    {if $item.tipo != 'cabecalho'}
                                          {assign var="item_total" value=$item.QUANTIDADE_EXECUTADA*$item.VALUNITARIO}
                                          {assign var="global_total" value=$global_total+$item_total}
                                          {assign var="valor_total" value=$global_total-$cabecalho.VALOR_DESCONTO}
                                          {assign var="os_total" value=$os_total+$item_total}


                                          <div class="div-table-row">
                                                <div class="div-table-cell">{$os_item_count}</div>
                                                <div class="div-table-cell desc-servico">{$item.DESCSERVICO}</div>
                                                <div class="div-table-cell" style="text-align: center;">{$item.UNIDADE}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$item.QUANTIDADE_CONTRATADA}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$item.QUANTIDADE}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$item.QUANTIDADE_EXECUTADA}
                                                </div>
                                                <div class="div-table-cell" style="text-align: right;">{($item.QUANTIDADE - $item.QUANTIDADE_EXECUTADA)|number_format:2:",":"."}</div>
                                                <div class="div-table-cell" style="text-align: right;">{$item.PERCENTUAL_EXECUCAO}%
                                                </div>
                                                <div class="div-table-cell" style="text-align: right;">{if $item.VALUNITARIO}R$
                                                      {$item.VALUNITARIO|number_format:2:",":"."}{else}-
                                                      {/if}</div>
                                                <div class="div-table-cell" style="text-align: right;">R$
                                                      {$item_total|number_format:2:",":"."}</div>
                                          </div>
                                          {assign var="os_item_count" value=$os_item_count+1}
                                    {/if}

                                    {if $item.subtotal}
                                          <div class="div-table-row subtotal-row">
                                                <div class="div-table-cell" colspan="7" style="text-align: right;">Subtotal
                                                      {$item.grupo}:
                                                </div>
                                                <div class="div-table-cell" style="text-align: right;">R$
                                                      {$item.subtotal|number_format:2:",":"."}</div>
                                          </div>
                                    {/if}

                                    {if $item@last || ($item@index + 1 < $lanc|@count && $lanc[$item@index + 1].NUM_OS != $current_os && $lanc[$item@index + 1].STATUSDESC != "Cancelado")}
                                          <div class="div-table-row total-os-row">
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell"></div>
                                                <div class="div-table-cell total-os-cell" colspan="2">Total OS {$item.NUM_OS}: </div>
                                                <div class="div-table-cell" style="text-align: right;">R$
                                                      {$os_total|number_format:2:",":"."}</div>
                                          </div>
                                    {/if}

                                    {if $item@last}
                                    </div>
                              </div>
                        {/if}
                              {/if}
                        {/foreach}

            <div class="table-responsive">
                  <div class="div-table">
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 70%">Quadro Resumo Financeiro</div>
                              <div class="div-table-cell total-os-cell" style="width: 15%">Subtotal:</div>
                              <div class="div-table-cell" style="text-align: right; width: 15%">R$
                                    {$global_total|number_format:2:",":"."}</div>
                        </div>
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 70%"></div>
                              <div class="div-table-cell total-os-cell" style="text-align: right; width: 20%">
                                    Desconto Comercial
                                    ({$totais.percentual_desconto}%):</div>
                              <div class="div-table-cell" style="text-align: right; width: 15%">R$
                                    {$cabecalho.VALOR_DESCONTO|number_format:2:",":"."}</div>
                        </div>
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 70%"></div>
                              <div class="div-table-cell total-os-cell" style="text-align: right;">Total
                                    Geral:</div>
                              <div class="div-table-cell" style="text-align: right;  width: 15%">R$
                                    {$valor_total|number_format:2:",":"."}</div>
                        </div>
                  </div>
            </div>
            <div class="table-responsive">
                  <div class="div-table">
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 3%">Proponente: </div>
                              <div class="div-table-cell" style="width: 50%">{$cabecalho.NOMEEMPRESA}
                              </div>
                        </div>
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 3%">CNPJ: </div>
                              <div class="div-table-cell" style="text-align: left; width: 50%">
                                    {$cabecalho.CNPJ_EMPRESA}
                              </div>
                        </div>
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 3%">Solicitante: </div>
                              <div class="div-table-cell" style="text-align: left; width: 50%">
                                    {$cabecalho.CLIENTE}
                              </div>
                        </div>
                        <div class="div-table-row total-row">
                              <div class="div-table-cell" style="width: 3%">CNPJ: </div>
                              <div class="div-table-cell" style="text-align: left; width: 50%">
                                    {$cabecalho.DOCUMENTO_CLIENTE}
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
      </div>
</section>