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
            max-width: 310px;
      }

      .table td.tipo-servico {
            white-space: normal;
            word-wrap: break-word;
            word-break: break-word;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            line-height: 1.2em;
            max-height: 5em;
            max-width: 660px;
      }


      .x_panel {
            margin-top: 5px;
      }

      .table-responsive {

            max-width: 100%;
      }

      h2 {
            font-size: 14px;
            margin: 5px 0;
      }

      .valor-servico {
            text-align: left;
            font-weight: bold;
      }

      .text-right {
            text-align: left;
      }

      @media print {
            @page {
                  margin: 0.3cm;
                  size: landscape;
            }

            body {
                  font-size: 9pt;
            }

            .height100 {
                  min-height: auto !important;
                  padding: 2px !important;
            }

            .table th,
            .table td {
                  padding: 1px 2px !important;
                  font-size: 9px !important;
            }


            .table td.cliente {
                  max-width: 120px;
            }

            .table td.obra {
                  max-width: 80px;
            }

            .table td.tipo-servico {
                  -webkit-line-clamp: 5;
                  max-height: 6em;
                  max-width: 660px;
            }

            .no-print {
                  display: none;
            }

            .dataHora {
                  font-size: 8px;
            }

            h2 {
                  font-size: 12px;
                  margin: 1px 0 !important;
            }

            .col-md-2, .col-md-8 {
                  padding: 1px !important;
            }

            img {
                  max-width: 100px !important;
                  max-height: 25px !important;
            }

            .print-container {
                  page-break-inside: avoid !important;
            }

             /* Força que o cabeçalho e a tabela fiquem juntos */
             .print-container + .x_panel {
                  page-break-before: avoid !important;
            }
      }
</style>

<section class="height100">
      <div class="print-container">
            <div class="row">
                  <div class="col-md-2 col-sm-2 col-xs-2">
                        <img src="images/logo.png" align="left" width="180" height="46" border="0">
                  </div>
                  <div class="col-md-8 col-sm-8 col-xs-8 text-center">
                        <h2 style="margin: 0; padding: 0;">
                              <strong>Relatório Consolidado por Período</strong><br>
                              Período - {$data_ini} | {$data_fim}
                        </h2>
                  </div>
                  <div class="col-md-2 col-sm-2 col-xs-2">
                        <b class="pull-right dataHora">{$dataImp}</b>
                  </div>
            </div>
            <div class="x_panel">
                        {if count($lanc) > 0}
                              <div class="table-responsive">
                                    <table class="table table-striped">
                                          <tbody>
                                                {assign var="total_geral" value=0}
                                                {assign var="status_atual" value=""}
                                                {assign var="status_atual_desc" value=""}
                                                {assign var="status_atual_id" value=0}
                                                {assign var="status_atual_cancelado" value=false}
                                                {assign var="cliente_atual" value=""}
                                                {assign var="os_atual" value=""}
                                                {assign var="obra_atual" value=""}
                                                {assign var="os_atual_cancelado" value=false}
                                                {assign var="total_status" value=0}
                                                {assign var="total_os" value=0}
                                                {assign var="total_contrato_os" value=0}
                                                
                                                {foreach $lanc as $item}
                                                      {assign var="status_id" value=$item.status_id|default:0}
                                                      {assign var="status_desc" value=$item.situacao_os|default:"Sem Status"}
                                                      {assign var="cliente" value=$item.cliente|default:"-"}
                                                      {assign var="num_os" value=$item.num_os|default:0}
                                                      {assign var="obra" value=$item.obra|default:"-"}
                                                      {assign var="valor" value=$item.total_faturado_servico|default:0}
                                                      {assign var="total_contrato" value=$item.total_contrato|default:0}
                                                      
                                                      {* Verifica se é cancelado ou orçamento (IDs 8 e 9) *}
                                                      {assign var="eh_cancelado" value=false}
                                                      {if $status_id == 8 || $status_id == 9}
                                                            {assign var="eh_cancelado" value=true}
                                                      {/if}
                                                      
                                                      {* Verifica se mudou situação *}
                                                      {if $status_id != $status_atual && $status_atual != ""}
                                                            {* Total da OS anterior antes de mudar situação *}
                                                            {if $os_atual != "" && !$os_atual_cancelado}
                                                                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                                        <td colspan="4"></td>
                                                                        <td class="text-right">
                                                                              <strong>Total OS {$os_atual}:</strong>
                                                                        </td>
                                                                        <td class="text-right valor-servico">
                                                                              R$ {$total_os|number_format:2:",":"."}
                                                                        </td>
                                                                  </tr>
                                                            {/if}
                                                            {* Total da situação anterior (sempre mostra) *}
                                                            <tr style="background-color: #e8e8e8; font-weight: bold; border-bottom: 2px solid #999;">
                                                                  <td colspan="5" class="text-right">
                                                                        <strong>Total {$status_atual_desc}:</strong>
                                                                  </td>
                                                                  <td class="text-right valor-servico">
                                                                        R$ {$total_status|number_format:2:",":"."}
                                                                  </td>
                                                            </tr>
                                                            <tr><td colspan="6" style="height: 10px; border: none;"></td></tr>
                                                            {assign var="total_status" value=0}
                                                      {/if}
                                                      
                                                      {* Cabeçalho do grupo quando muda situação *}
                                                      {if $status_id != $status_atual}
                                                            <tr style="background-color: #e8e8e8; font-weight: bold; border-top: 1px solid #ccc;">
                                                                  <td colspan="6" style="padding: 8px;">
                                                                        <strong style="font-size: 14px;">Situação: {$status_desc}</strong>
                                                                  </td>
                                                            </tr>
                                                            {assign var="status_atual" value=$status_id}
                                                            {assign var="status_atual_id" value=$status_id}
                                                            {assign var="status_atual_desc" value=$status_desc}
                                                            {assign var="status_atual_cancelado" value=$eh_cancelado}
                                                            {assign var="cliente_atual" value=""}
                                                            {assign var="os_atual" value=""}
                                                            {assign var="obra_atual" value=""}
                                                            {assign var="os_atual_cancelado" value=false}
                                                            {assign var="total_os" value=0}
                                                            {assign var="total_contrato_os" value=0}
                                                      {/if}
                                                      
                                                      {* Cabeçalho do grupo quando muda cliente *}
                                                      {if $cliente != $cliente_atual}
                                                            {* Total da OS anterior *}
                                                            {if $os_atual != "" && !$os_atual_cancelado}
                                                                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                                        <td colspan="4"></td>
                                                                        <td class="text-right">
                                                                              <strong>Total OS {$os_atual}:</strong>
                                                                        </td>
                                                                        <td class="text-right valor-servico">
                                                                              R$ {$total_os|number_format:2:",":"."}
                                                                        </td>
                                                                  </tr>
                                                            {/if}
                                                            <tr style="background-color: #f5f5f5; font-weight: bold;">
                                                                  <td colspan="6" style="padding: 0px!important;">
                                                                        <strong>Cliente: {$cliente}</strong>
                                                                  </td>
                                                            </tr>
                                                            {assign var="cliente_atual" value=$cliente}
                                                            {assign var="os_atual" value=""}
                                                            {assign var="obra_atual" value=""}
                                                            {assign var="os_atual_cancelado" value=false}
                                                            {assign var="total_os" value=0}
                                                            {assign var="total_contrato_os" value=0}
                                                      {/if}
                                                      
                                                      {* Cabeçalho do grupo quando muda OS *}
                                                      {if $num_os != $os_atual}
                                                            {* Total da OS anterior *}
                                                            {if $os_atual != "" && !$os_atual_cancelado}
                                                                  <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                                        <td colspan="4"></td>
                                                                        <td class="text-right">
                                                                              <strong>Total OS {$os_atual}:</strong>
                                                                        </td>
                                                                        <td class="text-right valor-servico">
                                                                              R$ {$total_os|number_format:2:",":"."}
                                                                        </td>
                                                                  </tr>
                                                            {/if}
                                                            {* Espaçamento antes da nova OS *}
                                                            {if $os_atual != ""}
                                                                  <tr><td colspan="6" style="height: 8px; border: none; background-color: transparent;"></td></tr>
                                                            {/if}
                                                            <tr style="background-color: #fafafa;">
                                                                  <td colspan="6" style="padding: 5px;">
                                                                        <strong style="font-size: 13px;">OS: {$num_os} | Situação: {$status_desc} | Obra: {$obra} | Total Contrato: R$ {if $total_contrato}{$total_contrato|number_format:2:",":"."}{else}0,00{/if}</strong>
                                                                  </td>
                                                            </tr>
                                                            {* Cabeçalhos da tabela acima dos serviços *}
                                                            <tr style="background-color: #e0e0e0; font-weight: bold;">
                                                                  <th style="width: 55%">Tipo de Serviço</th>
                                                                  <th style="width: 10%" class="text-left">Qtd Contratada</th>
                                                                  <th style="width: 10%" class="text-left">Qtd Executada</th>
                                                                  <th style="width: 5%">Unidade</th>
                                                                  <th style="width: 10%" class="text-left">Custo Unitário</th>
                                                                  <th style="width: 15%" class="text-left">Total Faturado</th>
                                                            </tr>
                                                            {assign var="os_atual" value=$num_os}
                                                            {assign var="obra_atual" value=$obra}
                                                            {assign var="os_atual_cancelado" value=$eh_cancelado}
                                                            {assign var="total_os" value=0}
                                                            {assign var="total_contrato_os" value=$total_contrato}
                                                      {/if}
                                                      
                                                      {* Linha do item *}
                                                      <tr>
                                                            <td class="tipo-servico">{$item.tipo_servico} - {$item.obs_servico}</td>
                                                            <td class="text-left">
                                                                  {if $item.quantidade_contratada}
                                                                        {$item.quantidade_contratada|number_format:2:",":"."}
                                                                  {else}
                                                                        0,00
                                                                  {/if}
                                                            </td>
                                                            <td class="text-left">
                                                                  {if $item.quantidade_executada}
                                                                        {$item.quantidade_executada|number_format:2:",":"."}
                                                                  {else}
                                                                        0,00
                                                                  {/if}
                                                            </td>
                                                            <td>{$item.unidade_servico}</td>
                                                            <td class="text-left">
                                                                  {if $item.custo_unitario}
                                                                        R$ {$item.custo_unitario|number_format:2:",":"."}
                                                                  {else}
                                                                        R$ 0,00
                                                                  {/if}
                                                            </td>
                                                            <td class="text-left valor-servico">
                                                                  {if $item.total_faturado_servico}
                                                                        R$ {$item.total_faturado_servico|number_format:2:",":"."}
                                                                  {else}
                                                                        R$ 0,00
                                                                  {/if}
                                                            </td>
                                                      </tr>
                                                      
                                                      {* Calcula total da OS se não for cancelado ou orçamento (IDs 8 e 9) *}
                                                      {if !$eh_cancelado}
                                                            {assign var="total_os" value=$total_os+$valor}
                                                      {/if}
                                                      
                                                      {* Sempre calcula total da situação (mesmo cancelado) *}
                                                      {assign var="total_status" value=$total_status+$valor}
                                                      
                                                      {if $status_id != 8 && $status_id != 9}
                                                            {assign var="total_geral" value=$total_geral+$valor}
                                                      {/if}
                                                {/foreach}
                                                
                                                {* Total da última OS (se não for cancelado) *}
                                                {if $os_atual != "" && !$os_atual_cancelado}
                                                      <tr style="background-color: #f9f9f9; font-weight: bold;">
                                                            <td colspan="4"></td>
                                                            <td class="text-right">
                                                                  <strong>Total OS {$os_atual}:</strong>
                                                            </td>
                                                            <td class="text-right valor-servico">
                                                                  R$ {$total_os|number_format:2:",":"."}
                                                            </td>
                                                      </tr>
                                                {/if}
                                                
                                                {* Total da última situação (sempre mostra, mesmo se cancelado) *}
                                                {if $status_atual != ""}
                                                      <tr style="background-color: #e8e8e8; font-weight: bold; border-bottom: 2px solid #999;">
                                                            <td colspan="5" class="text-right">
                                                                  <strong>Total {$status_atual_desc}:</strong>
                                                            </td>
                                                            <td class="text-right valor-servico">
                                                                  R$ {$total_status|number_format:2:",":"."}
                                                            </td>
                                                      </tr>
                                                      <tr><td colspan="6" style="height: 10px; border: none;"></td></tr>
                                                {/if}
                                                
                                                {* Total Geral (exclui situações 8 e 9) *}
                                                <tr style="background-color: #d0d0d0; font-weight: bold; border-top: 2px solid #666;">
                                                      <td colspan="5" class="text-right">
                                                            <strong style="font-size: 12px;">TOTAL GERAL:</strong>
                                                      </td>
                                                      <td class="text-right valor-servico" style="font-size: 12px;">
                                                            R$ {$total_geral|number_format:2:",":"."}
                                                      </td>
                                                </tr>
                                          </tbody>
                                    </table>
                              </div>
                        {else}
                              <div class="message-container">
                                    <h4>Nenhum registro localizado para o período selecionado!</h4>
                              </div>
                        {/if}
                  </div>
            </div>

            <div class="row no-print">
                  <div class="col-xs-12 text-center">
                        <button class="btn btn-success" onclick="exportarTabelaParaExcel();" style="margin-right: 10px;">
                              <i class="fa fa-file-excel-o"></i> Exportar Excel
                        </button>
                        <button class="btn btn-default" onclick="window.print();">
                              <i class="fa fa-print"></i> Imprimir
                        </button>
                  </div>
            </div>
      </div>
</section>

<script type="text/javascript">
function exportarTabelaParaExcel() {
    // Pega a tabela que já está sendo exibida
    var table = document.querySelector('.table-striped');
    if (!table) {
        alert('Tabela não encontrada!');
        return;
    }
    
    // Converte a tabela para CSV
    var csv = '';
    var rows = table.querySelectorAll('tr');
    
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var cells = row.querySelectorAll('td, th');
        var rowData = [];
        
        for (var j = 0; j < cells.length; j++) {
            var cellText = cells[j].textContent.trim();
            // Remove caracteres especiais e formata valores monetários
            cellText = cellText.replace(/R\$/g, '').trim();
            // Escapa vírgulas e aspas
            if (cellText.indexOf(',') !== -1 || cellText.indexOf('"') !== -1) {
                cellText = '"' + cellText.replace(/"/g, '""') + '"';
            }
            rowData.push(cellText);
        }
        
        csv += rowData.join(',') + '\n';
    }
    
    // Cria o blob e faz o download
    var blob = new Blob([csv], {ldelim}type: 'text/csv;charset=utf-8;'{rdelim});
    var link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    
    // Nome do arquivo com período
    var dataIni = '{$data_ini|replace:"/":"_"}';
    var dataFim = '{$data_fim|replace:"/":"_"}';
    link.download = 'Relatorio_Periodo_' + dataIni + '_a_' + dataFim + '.csv';
    link.click();
}
</script>
