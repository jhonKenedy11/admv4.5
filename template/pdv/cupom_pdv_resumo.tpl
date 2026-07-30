<div id="pdvResumoBloco">
    <span id="pdvResumoSyncId" style="display:none;">{$pedido.id}</span>
    <span id="pdvResumoSyncCliente" style="display:none;">{$pedido.cliente}</span>
    <span id="pdvResumoSyncTotal" style="display:none;">{$pedido.totalCupom|default:'0,00'}</span>
    <span id="pdvResumoSyncQtd" style="display:none;">{$pedido.qtdItens|default:0}</span>

    <div class="pdv-resumo-meta">
        Cupom
        {if $pedido.id neq ''}
            <strong>#{$pedido.id}</strong>
        {else}
            <strong>Novo</strong>
        {/if}
    </div>

    <div class="pdv-resumo-itens">
        <table class="table table-striped table-condensed" id="tblItensCupom">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Descrição</th>
                    <th class="text-right">Qtde</th>
                    <th>UN</th>
                    <th class="text-right">Unit.</th>
                    <th class="text-right">Total</th>
                    <th></th>
                </tr>
            </thead>
            <tbody id="tbodyItensCupom">
                {if $pedido.lancItens|@count gt 0}
                    {section name=i loop=$pedido.lancItens}
                        <tr>
                            <td>{if $pedido.lancItens[i].CODFABRICANTE != ''}{$pedido.lancItens[i].CODFABRICANTE}{else}{$pedido.lancItens[i].ITEMESTOQUE}{/if}</td>
                            <td>{$pedido.lancItens[i].DESCRICAO}</td>
                            <td class="text-right">{$pedido.lancItens[i].QTSOLICITADA|number_format:3:",":"."}</td>
                            <td>{$pedido.lancItens[i].UNIDADE}</td>
                            <td class="text-right">{$pedido.lancItens[i].UNITARIO|number_format:2:",":"."}</td>
                            <td class="text-right"><strong>{$pedido.lancItens[i].TOTAL|number_format:2:",":"."}</strong></td>
                            <td class="text-right pdv-acoes-item" nowrap="nowrap">
                                <button type="button" class="btn btn-primary btn-xs btn-editar-item"
                                    data-nritem="{$pedido.lancItens[i].NRITEM}"
                                    data-codigo="{$pedido.lancItens[i].ITEMESTOQUE}"
                                    data-descricao="{$pedido.lancItens[i].DESCRICAO|escape:'html'}"
                                    data-unidade="{$pedido.lancItens[i].UNIDADE|default:$pedido.lancItens[i].unidade|default:''}"
                                    data-quantidade="{$pedido.lancItens[i].QTSOLICITADA}"
                                    data-unitario="{$pedido.lancItens[i].UNITARIO|number_format:2:",":"."}"
                                    title="Editar">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                                </button>
                                <button type="button" class="btn btn-danger btn-xs btn-excluir-item"
                                    data-nritem="{$pedido.lancItens[i].NRITEM}" title="Remover">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                </button>
                            </td>
                        </tr>
                    {/section}
                {else}
                    <tr id="trItensVazio">
                        <td colspan="7" class="text-center text-muted">Nenhum item. Pesquise e adicione produtos.</td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>

    <div class="pdv-resumo-totais">
        <div class="pdv-resumo-linha">
            <span>Subtotal</span>
            <span id="pdvResumoSubtotal">R$ {$pedido.totalProdutos|default:'0,00'}</span>
        </div>
        <div class="pdv-resumo-linha pdv-resumo-linha-campo">
            <label for="pdvDesconto">Desconto</label>
            <input type="text" class="form-control input-sm text-right" id="pdvDesconto" name="desconto"
                value="{$pedido.desconto|default:'0,00'}" autocomplete="off" inputmode="decimal" title="Desconto do cupom">
        </div>
        <div class="pdv-resumo-linha pdv-resumo-linha-campo">
            <label for="pdvFrete">Frete</label>
            <input type="text" class="form-control input-sm text-right" id="pdvFrete" name="frete"
                value="{$pedido.frete|default:'0,00'}" autocomplete="off" inputmode="decimal" title="Frete do cupom">
        </div>
        <div class="pdv-resumo-total-box">
            <span class="pdv-resumo-total-label">Total do cupom</span>
            <span class="pdv-resumo-total-valor" id="pdvResumoTotal">R$ {$pedido.totalCupom|default:'0,00'}</span>
            <span id="totalCupomDisplay" style="display:none;">{$pedido.totalCupom|default:'0,00'}</span>
        </div>
        <div class="pdv-resumo-acao">
            <button type="button" class="btn btn-primary btn-block" id="btnEmitirCupom"
                onclick="pdvAbrirModalEmitir();" {if $jaExisteCpm}disabled="disabled"{/if}>
                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                <span> Emitir cupom</span>
            </button>
        </div>
    </div>
</div>
