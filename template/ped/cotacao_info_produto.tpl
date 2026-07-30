<!-- Abas -->
<ul class="nav nav-tabs" role="tablist" style="margin-bottom: 10px;">
    <li role="presentation" class="active">
        <a href="#abaCompras" aria-controls="abaCompras" role="tab" data-toggle="tab">Últimas Compras</a>
    </li>
    <li role="presentation">
        <a href="#abaVendas" aria-controls="abaVendas" role="tab" data-toggle="tab">Últimas Vendas</a>
    </li>
    <li role="presentation">
        <a href="#abaMarkup" aria-controls="abaMarkup" role="tab" data-toggle="tab">Markup</a>
    </li>
    <!-- Aba Equivalências removida daqui — agora está em cotacao_equivalencias.tpl -->
</ul>

<!-- Conteúdo das abas -->
<div class="tab-content">
    <!-- Aba Últimas 3 Compras -->
    <div role="tabpanel" class="tab-pane active" id="abaCompras">
        {if !empty($ultimasCompras)}
        <div style="max-height: 200px; overflow-y: auto;">
            <table class="table table-bordered jambo_table" style="margin-bottom: 0;">
                <thead style="position: sticky; top: 0; background: gray; z-index: 10;">
                    <tr style="background: gray; color: white;">
                        <th style="padding: 5px;">Data</th>
                        <th style="padding: 5px;">Nº Nota</th>
                        <th style="padding: 5px;">Fornecedor</th>
                        <th style="padding: 5px;">Quantidade</th>
                        <th style="padding: 5px;">Valor Unitário</th>
                        <th style="padding: 5px;">Valor Total</th>
                        <th style="padding: 5px;">Impostos</th>
                    </tr>
                </thead>
                <tbody>
                    {section name=i loop=$ultimasCompras}
                    <tr>
                        <td style="padding: 5px;">{$ultimasCompras[i].dataCompra|default:''}</td>
                        <td style="padding: 5px;">{$ultimasCompras[i].numeroNota|default:''}</td>
                        <td style="padding: 5px;">{$ultimasCompras[i].fornecedorNome|default:''}</td>
                        <td style="padding: 5px;">{$ultimasCompras[i].quantidade|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$ultimasCompras[i].valorUnitario|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$ultimasCompras[i].valorEntrada|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$ultimasCompras[i].totalImpostos|default:'0,00'}</td>
                    </tr>
                    {/section}
                </tbody>
            </table>
        </div>
        {else}
        <div style="padding: 15px; text-align: center;">
            <p>Nenhum registro de compra encontrado.</p>
        </div>
        {/if}
    </div>
    
    <!-- Aba Últimas 3 Vendas -->
    <div role="tabpanel" class="tab-pane" id="abaVendas">
        {if !empty($ultimasVendas)}
        <div style="max-height: 200px; overflow-y: auto;">
            <table class="table table-bordered jambo_table" style="margin-bottom: 0;">
                <thead style="position: sticky; top: 0; background: gray; z-index: 10;">
                    <tr style="background: gray; color: white;">
                        <th style="padding: 5px;">Data</th>
                        <th style="padding: 5px;">Nº Pedido</th>
                        <th style="padding: 5px;">Quantidade</th>
                        <th style="padding: 5px;">Valor Unitário</th>
                        <th style="padding: 5px;">Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    {section name=i loop=$ultimasVendas}
                    <tr>
                        <td style="padding: 5px;">{$ultimasVendas[i].dataVenda|default:''}</td>
                        <td style="padding: 5px;">{$ultimasVendas[i].numeroPedido|default:''}</td>
                        <td style="padding: 5px;">{$ultimasVendas[i].quantidade|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$ultimasVendas[i].valorVenda|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$ultimasVendas[i].valorTotal|default:'0,00'}</td>
                    </tr>
                    {/section}
                </tbody>
            </table>
        </div>
        {else}
        <div style="padding: 15px; text-align: center;">
            <p>Nenhum registro de venda encontrado para este cliente.</p>
        </div>
        {/if}
    </div>
    
    <!-- Aba Markup -->
    <div role="tabpanel" class="tab-pane" id="abaMarkup">
        {if $dadosMarkup.temDados}
        <div style="max-height: 200px; overflow-y: auto;">
            <table class="table table-bordered jambo_table" style="margin-bottom: 0;">
                <thead style="position: sticky; top: 0; background: gray; z-index: 10;">
                    <tr style="background: gray; color: white;">
                        <th style="padding: 5px;">Quantidade</th>
                        <th style="padding: 5px;">Valor Unitário</th>
                        <th style="padding: 5px;">Custo Unitário</th>
                        <th style="padding: 5px;">Custo Total</th>
                        <th style="padding: 5px;">Total do Item</th>
                        <th style="padding: 5px;">Lucro Bruto</th>
                        <th style="padding: 5px;">Markup (%)</th>
                        <th style="padding: 5px;">Margem Líquida</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 5px;">{$dadosMarkup.quantidade|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$dadosMarkup.valorUnitario|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$dadosMarkup.custo|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$dadosMarkup.custoTotal|default:'0,00'}</td>
                        <td style="padding: 5px;">R$ {$dadosMarkup.totalItem|default:'0,00'}</td>
                        <td style="padding: 5px; background-color: #f0f0f0;"><strong>R$ {$dadosMarkup.lucroBruto|default:'0,00'}</strong></td>
                        <td style="padding: 5px; background-color: #e8f5e9;"><strong style="color: {if $dadosMarkup.markup|replace:',':'.'|floatval >= 0}#4caf50{else}#f44336{/if};">{$dadosMarkup.markup|default:'0,00'}%</strong></td>
                        <td style="padding: 5px;">R$ {$dadosMarkup.margemLiquida|default:'0,00'}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        {else}
        <div style="padding: 15px; text-align: center;">
            <p>Informe o valor unitário e a quantidade do produto para calcular o markup.</p>
        </div>
        {/if}
    </div>
    
    <!-- Aba Equivalências -->
    <div role="tabpanel" class="tab-pane" id="abaEquivalencias">
        <div style="max-height:200px; overflow-y:auto;">
        <table class="table table-bordered jambo_table" style="margin-bottom: 0;">
            <thead style="position: sticky; top: 0; background: gray; z-index: 10;">
                <tr style="background: gray; color: white;">
                    <th style="padding: 5px;">Equivalente</th>
                    <th style="padding: 5px;">Preço Unitário</th>
                    <th style="padding: 5px;">Marca</th>
                </tr>
            </thead>
            <tbody>
                {section name=i loop=$equivalencias}
                <tr>
                    <td style="padding: 5px;">{$equivalencias[i].CODEQUIVALENTE}</td>
                    <td style="padding: 5px;">{$equivalencias[i].PRECOUNITARIO|default:'0,00'}</td>
                    <td style="padding: 5px;">{$equivalencias[i].MARCA|default:''}</td>
                </tr>
            {/section}
            </tbody>
        </table>
        </div>
    </div>
</div>

