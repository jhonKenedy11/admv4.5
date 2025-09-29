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
        color: #6c767d;
        font-size: 2rem;
        text-align: center;
    }
    .error-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 70vh;
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }
    .error-message {
        background-color: #f8d7da;
        color: #721c24;
        padding: 40px;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        text-align: center;
        max-width: 700px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .error-message h4 {
        color: #721c24;
        font-size: 2.2rem;
        margin-bottom: 20px;
        font-weight: bold;
    }
    .error-message p {
        font-size: 1.3rem;
        margin: 15px 0;
        line-height: 1.6;
    }
    .error-message .error-details {
        margin-top: 25px;
        padding: 15px;
        background-color: #f1b0b7;
        border-radius: 5px;
        border-left: 4px solid #721c24;
    }
    .height100 {
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
        height: 100vh;
    }
    .x_panel {
        padding: 0;
    }
    .table {
        margin-bottom: 0;
        margin-top: 20px; /* ALTERADO: era -6px */
    }
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 5px !important;
    }
    .dataHora {
        font-size: 10px;
    }
    .print-section {
        page-break-inside: avoid;
    }
    .total-row {
        font-weight: bold;
    }
    .tributos-row {
        background-color: #f8f9fa;
        font-size: 10px;
        color: #6c757d;
        border-top: 1px dotted #dee2e6;
    }
    .tributos-row td {
        padding: 3px 5px !important;
        font-style: italic;
    }
    
    /* NOVO: Classe para o cabeçalho */
    .header-row {
        margin-bottom: 20px;
    }
    
    img{
        border-radius: 5px !important;
        padding-bottom: 2px;
        margin-bottom: 1px; /* NOVO: margem inferior */
    }
    
    @media print {
        @page {
            size: auto;
            margin: 15mm; /* NOVO: margem na página */
        }
        body {
            padding: 0px;
        }
        
        /* NOVO: Espaçamento específico para impressão */
        img {
            margin-bottom: 10px !important;
            padding-bottom: 5px !important;
        }
        
        .header-row {
            margin-bottom: 25px !important;
            padding-bottom: 10px !important;
        }
        
        .table {
            margin-top: -5px !important; /* NOVO: maior espaçamento na impressão */
        }
        
        .x_content {
            margin-top: 20px !important; /* NOVO */
        }
        
        td,
        th,
        h6 {
            font-size: 8px;
            line-height: 8px !important;
        }
        .no-print {
            display: none !important;
        }
        .table>tbody>tr>td,
        .table>tbody>tr>th,
        .table>tfoot>tr>td,
        .table>tfoot>tr>th,
        .table>thead>tr>td,
        .table>thead>tr>th {
            padding: 5px !important;
        }
        .tributos-row td {
            padding: 2px 3px !important;
            font-size: 6.5px;
        }
        .dataHora {
            font-size: 8px;
        }
        h2 {
            font-size: 13px;
            margin-bottom: 10px !important; /* NOVO */
        }
        h4 {
            font-size: 11px;
            margin-bottom: 8px !important; /* NOVO */
        }
        h5 {
            font-size: 10px;
        }
        .error-container {
            display: none !important;
        }
        
        .print-section {
            page-break-inside: avoid;
            margin-top: 20px !important; /* NOVO */
        }
    }
</style>

{* Verifica se a variável $pedido.PEDIDO existe e não está vazia *}
{if $status_relatorio !== false}
    {* Volta a aplicar class="height100" no section *}
    <section class="height100">
        <div class="right_col" role="main">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-4">
                    <img src="images/logo.png" aloign="right" width=200 height=58 border="0">
                </div>
                <div class="col-md-5 col-sm-5 col-xs-5">
                    <h2 style="text-align: center;">
                        <strong>RELATÓRIO SIMULA IMPOSTOS</strong><br>
                    </h2>
                    <h4 style="text-align: center;">
                        Pedido - {$pedido.PEDIDO}
                    </h4>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-3">
                    <b class="pull-right dataHora">{$smarty.now|date_format:'%d/%m/%Y %H:%M'}</b>
                </div>
            </div>
            <div class="clearfix">
                <div class="x_panel">
                    <div class="x_content print-section">
                        <section class="content invoice">
                            <div class="row small">
                                <div class="col-xs-12">
                                    {* <h4>Pedido Nº: <strong>{$pedido.PEDIDO}</strong></h4> *}
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Cód. Produto</th>
                                                <th>Descrição</th>
                                                <th>Qtd</th>
                                                <th>Valor Unit.</th>
                                                <th>Valor Total</th>
                                                <th>ICMS</th>
                                                <th>IPI</th>
                                                <th>PIS</th>
                                                <th>COFINS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {assign var="totalGeral" value=0}
                                            {assign var="totalIcms" value=0}
                                            {assign var="totalIpi" value=0}
                                            {assign var="totalPis" value=0}
                                            {assign var="totalCofins" value=0}
                                            {foreach from=$itens item=item}
                                            {* Linha principal do item *}
                                            <tr>
                                                <td>{$item.ITEMESTOQUE}</td>
                                                <td>{$item.DESCRICAO}</td>
                                                <td>{$item.QTSOLICITADA|number_format:2:",":"."}</td>
                                                <td>{$item.UNITARIO|number_format:2:",":"."}</td>
                                                <td>{$item.TOTAL|number_format:2:",":"."}</td>
                                                <td>
                                                    {if $item.impostos.vlIcms && $item.impostos.vlIcms != 0}
                                                        {$item.impostos.vlIcms|number_format:2:",":"."}
                                                        {assign var="totalIcms" value=$totalIcms+$item.impostos.vlIcms}
                                                    {else}
                                                        Não informado
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if $item.impostos.vlIpi && $item.impostos.vlIpi != 0}
                                                        {$item.impostos.vlIpi|number_format:2:",":"."}
                                                        {assign var="totalIpi" value=$totalIpi+$item.impostos.vlIpi}
                                                    {else}
                                                        Não informado
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if $item.impostos.vlPis && $item.impostos.vlPis != 0}
                                                        {$item.impostos.vlPis|number_format:2:",":"."}
                                                        {assign var="totalPis" value=$totalPis+$item.impostos.vlPis}
                                                    {else}
                                                        Não informado
                                                    {/if}
                                                </td>
                                                <td>
                                                    {if $item.impostos.vlCofins && $item.impostos.vlCofins != 0}
                                                        {$item.impostos.vlCofins|number_format:2:",":"."}
                                                        {assign var="totalCofins" value=$totalCofins+$item.impostos.vlCofins}
                                                    {else}
                                                        Não informado
                                                    {/if}
                                                </td>
                                            </tr>
                                            {* Linha adicional com detalhes dos tributos *}
                                            <tr class="tributos-row">
                                                <td colspan="2">
                                                    <strong>CFOP</strong> {if $item.impostos.cfop}{$item.impostos.cfop}{else}N/A{/if} | 
                                                    <strong>Origem:</strong> {if $item.impostos.origem || $item.impostos.origem == "0"}{$item.impostos.origem}{else}N/A{/if}
                                                </td>
                                                <td colspan="2">
                                                    <strong>ICMS:</strong> 
                                                    CST: {if $item.impostos.icmsSaida}{$item.impostos.icmsSaida}{else}N/A{/if} | 
                                                    BC: {if $item.impostos.bcIcms}{$item.impostos.bcIcms|number_format:2:",":"."}{else}N/A{/if} | 
                                                    Alíq: {if $item.impostos.icms_aliq}{$item.impostos.icms_aliq}%{else}N/A{/if}
                                                </td>
                                                <td>
                                                    <strong>IPI:</strong>
                                                    CST: {if $item.impostos.ipi_cst}{$item.impostos.ipi_cst}{else}N/A{/if} | 
                                                    Alíq: {if $item.impostos.ipi_aliq}{$item.impostos.ipi_aliq}%{else}N/A{/if}
                                                </td>
                                                <td colspan="2">
                                                    <strong>PIS:</strong> CST: {if $item.impostos.pis_cst}{$item.impostos.pis_cst}{else}N/A{/if} | BC: {if $item.impostos.bcPis}{$item.impostos.bcPis|number_format:2:",":"."}{else}N/A{/if} | Alíq: {if $item.impostos.pis_aliq}{$item.impostos.pis_aliq}%{else}N/A{/if}
                                                </td>
                                                <td colspan="2">
                                                    <strong>COFINS:</strong> CST: {if $item.impostos.cofins_cst}{$item.impostos.cofins_cst}{else}N/A{/if} | BC: {if $item.impostos.bcCofins}{$item.impostos.bcCofins|number_format:2:",":"."}{else}N/A{/if} | Alíq: {if $item.impostos.cofins_aliq}{$item.impostos.cofins_aliq}%{else}N/A{/if}
                                                </td>
                                            </tr>
                                            {assign var="totalGeral" value=$totalGeral+$item.TOTAL}
                                            {/foreach}
                                        </tbody>
                                        <tfoot>
                                            <tr class="total-row">
                                              <td><div class="row no-print">
                                                    <div class="col-xs-12">
                                                    <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                                                    </div>
                                              </div>
                                              </td>
                                                <td colspan="3" style="text-align:right;"><strong>TOTAIS:</strong></td>
                                                <td><strong>{$totalGeral|number_format:2:",":"."}</strong></td>
                                                <td><strong>{$totalIcms|number_format:2:",":"."}</strong></td>
                                                <td><strong>{if $totalIpi !=0 }
                                                    {$totalIpi|number_format:2:",":"."}
                                                    {else}Não informado{/if}</strong></td>
                                                <td><strong>{$totalPis|number_format:2:",":"."}</strong></td>
                                                <td><strong>{$totalCofins|number_format:2:",":"."}</strong></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
{else}
    {* Exibe mensagem de erro quando $pedido.PEDIDO não existe ou está vazio *}
    <section class="height100">
        <div class="error-container">
            <div class="error-message">
                <h4>⚠️ Erro no Relatório Impostos</h4>
                <p>Não foi possível gerar o relatório imposto do pedido.</p>
                
                <div class="error-details">
                    <p><strong>Motivo:</strong></p>
                    <p>• {$msg_erro} </p>
                </div>
                <p style="margin-top: 25px;"><strong>Recomendação:</strong> Faça o login novamente, se o erro persistir entre em contato com o suporte.</p>
            </div>
        </div>
    </section>
{/if}