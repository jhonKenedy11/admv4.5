<!-- Abas de Equivalências e Informações do Produto -->
<style>
    #abaProdutos tr.linhaEquivalenciaProduto {
        cursor: pointer;
    }
    #abaProdutos tr.linhaEquivalenciaProduto:hover td {
        background-color: #f5f5f5;
    }
    #abaProdutos tr.linhaEquivalenciaProduto.info td {
        background-color: #d9edf7 !important;
    }
</style>
<div>
    <!-- Abas -->
    <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 10px;">
        <li role="presentation" class="active">
            <a href="#abaProdutos" aria-controls="abaProdutos" role="tab" data-toggle="tab">Produtos</a>
        </li>
        <li role="presentation" id="liCompras" style="display: none;">
            <a href="#abaComprasEquiv" aria-controls="abaComprasEquiv" role="tab" data-toggle="tab">Últimas Compras</a>
        </li>
        <li role="presentation" id="liVendas" style="display: none;">
            <a href="#abaVendasEquiv" aria-controls="abaVendasEquiv" role="tab" data-toggle="tab">Últimas Vendas</a>
        </li>
        <li role="presentation" id="liMarkup" style="display: none;">
            <a href="#abaMarkupEquiv" aria-controls="abaMarkupEquiv" role="tab" data-toggle="tab">Markup</a>
        </li>
        {if !empty($equivalencias)}
        <li role="presentation" id="liEquivalencias">
            <a href="#abaEquivalencias" aria-controls="abaEquivalencias" role="tab" data-toggle="tab">Equivalências</a>
        </li>
        {/if}
    </ul>
    
    <!-- Conteúdo das abas -->
    <div class="tab-content">
        <!-- Aba Produtos (Tabela de Equivalências) -->
        <div role="tabpanel" class="tab-pane active" id="abaProdutos">
            {if $mostrarMensagemRefinar}
            <div class="alert alert-info" style="margin: 10px; padding: 10px; text-align: center;">
                <strong>Atenção!</strong> Foram encontrados 50 ou mais produtos. Por favor, refine sua pesquisa para obter resultados mais precisos.
            </div>
            {/if}
            <div style="max-height: 160px; overflow-y: auto; overflow-x: hidden;">
                <table class="table table-bordered jambo_table" style="margin-bottom: 0;">
                    <thead style="position: sticky; top: 0; background: gray; z-index: 10;">
                        <tr style="background: gray; color: white;">
                            <th style="width: 50px; padding: 5px;">Selecionar</th>
                            <th style="padding: 5px;">Código fabricante</th>
                            <th style="padding: 5px;">Código equivalente</th>
                            <th style="padding: 5px;">Descrição</th>
                            <th style="padding: 5px;">Marca</th>
                            <th style="padding: 5px;">NCM</th>
                            <th style="padding: 5px;">Estoque</th>
                        </tr>
                    </thead>
                    <tbody>
                        {section name=i loop=$equivalencias}
                            <tr id="rowProduto_{$equivalencias[i].ID}" class="linhaEquivalenciaProduto{if $equivalencias[i].SELECIONADO} info{/if}">
                            <td style="padding: 5px;"><input type="checkbox" class="checkboxEquivalencia" name="equivalencia" value="{$equivalencias[i].ID}" 
                                {if $equivalencias[i].SELECIONADO}checked{/if}
                                data-cod-equivalente="{$equivalencias[i].CODEQUIVALENTE}" 
                                data-cod-produto="{$equivalencias[i].ID}" 
                                data-cod-fabricante="{$equivalencias[i].CODIGO_FABRICANTE}"
                                data-descricao="{$equivalencias[i].DESCEQUIVALENTE|default:''}"
                                data-unidade="{$equivalencias[i].UNIDADE|default:''}"
                                data-valor-venda="{$equivalencias[i].VENDA|default:'0,00'}"></td>
                                <hidden name="id" value="{$equivalencias[i].ID}"></hidden>
                                <td style="padding: 5px;">{$equivalencias[i].CODIGO_FABRICANTE}</td>
                                <td style="padding: 5px;">{$equivalencias[i].CODEQUIVALENTE}</td>
                                <td style="padding: 5px;">{$equivalencias[i].DESCEQUIVALENTE|default:''}</td>
                                <td style="padding: 5px;">{$equivalencias[i].NOMEMARCA}</td>
                                <td style="padding: 5px;">{$equivalencias[i].NCM|default:''}</td>
                                <td style="padding: 5px;" class="estoqueProduto" data-cod-produto="{$equivalencias[i].ID}">
                                    <span class="text-muted" style="font-style: italic; font-size: 11px;">Selecione para mais informações</span>
                                </td>
                            </tr>
                        {/section}
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Aba Equivalências (conteúdo injetado via AJAX) -->
        <div role="tabpanel" class="tab-pane" id="abaEquivalencias">
            <!-- Conteúdo será inserido aqui via JavaScript -->
        </div>

        <!-- Aba Últimas 3 Compras (aparece apenas quando um item for selecionado) -->
        <div role="tabpanel" class="tab-pane" id="abaComprasEquiv">
            <!-- Conteúdo será inserido aqui via JavaScript -->
        </div>
        
        <!-- Aba Últimas 3 Vendas (aparece apenas quando um item for selecionado) -->
        <div role="tabpanel" class="tab-pane" id="abaVendasEquiv">
            <!-- Conteúdo será inserido aqui via JavaScript -->
        </div>
        
        <!-- Aba Markup (aparece apenas quando um item for selecionado e houver dados) -->
        <div role="tabpanel" class="tab-pane" id="abaMarkupEquiv">
            <!-- Conteúdo será inserido aqui via JavaScript -->
        </div>
    </div>
</div>
