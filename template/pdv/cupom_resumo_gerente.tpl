<style>
    .cupom-resumo-gerente .resumo-totais input { font-weight: 600; background: #f7f7f7; }
    .cupom-resumo-gerente .resumo-erro { margin-bottom: 12px; }
    .cupom-resumo-gerente .itens-scroll { max-height: 200px; overflow-y: auto; margin: 10px 0; }
    .cupom-resumo-gerente .bloco-pagamento { margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid #e6e9ed; }
    .cupom-resumo-gerente .troco-detalhe { margin-top: 8px; }
</style>

{if $mensagem neq ''}
<div class="alert alert-{if $tipoMsg eq 'erro' || $tipoMsg eq 'error'}danger{elseif $tipoMsg eq 'alerta'}warning{else}info{/if} resumo-erro">
    {$mensagem}
</div>
{/if}

{if $pedido.id|default:'' eq ''}
<p class="text-muted">Pedido não disponível para emissão.</p>
{else}

<span id="cupomGerenteFlags"
    data-ja-cpm="{if $jaExisteCpm}1{else}0{/if}"
    data-id="{$pedido.id}"
    style="display:none;"></span>

{if $jaExisteCpm}<div class="alert alert-warning small">Já existe NFC-e (CPM) para este pedido.</div>{/if}

<form id="formCupomGerentePedidoPs" method="post" action="{$SCRIPT_NAME}" style="display:none;" target="_blank">
    <input type="hidden" name="mod" value="ped">
    <input type="hidden" name="form" value="pedido_ps">
    <input type="hidden" name="submenu" value="alterar">
    <input type="hidden" name="id" value="{$pedido.id}">
    <input type="hidden" name="situacao" value="{$pedido.situacao}">
    <input type="hidden" name="pessoa" value="{$pedido.cliente}">
    <input type="hidden" name="origem" value="cupomGerente">
</form>

<form id="formCupomGerente" class="cupom-resumo-gerente" method="post" action="{$SCRIPT_NAME}">
    <input type="hidden" name="mod" value="pdv">
    <input type="hidden" name="form" value="cupom">
    <input type="hidden" name="opcao" value="{$opcaoResumo|default:'gerente'}">
    <input type="hidden" name="ajax" value="1">
    <input type="hidden" name="id" value="{$pedido.id}">
    <input type="hidden" name="cliente" value="{$pedido.cliente}">
    <input type="hidden" id="totalPedidoFixo" value="{$pedido.totalCupom}">
    <input type="hidden" name="desconto" value="{$pedido.desconto|default:'0,00'}">
    <input type="hidden" name="frete" value="{$pedido.frete|default:'0,00'}">

    <div class="row">
        <div class="col-sm-6 col-xs-12">
            <label class="small text-muted">Cliente</label>
            <p class="form-control-static" style="margin:0;">{$pedido.nomeCliente}</p>
        </div>
        <div class="col-sm-6 col-xs-12">
            <label class="small text-muted">Emissão</label>
            <p class="form-control-static" style="margin:0;">{$pedido.emissao}</p>
        </div>
    </div>

    {if $danfe eq ''}
    <div class="bloco-pagamento" style="margin-top:12px;">
        <div class="row">
            <div class="col-sm-4 col-xs-12">
                <label for="condPg">Condição de pagamento</label>
                <select name="condPg" id="condPg" class="form-control">
                    {html_options values=$condPg_ids selected=$pagamento.condPg output=$condPg_names}
                </select>
            </div>
            <div class="col-sm-4 col-xs-12">
                <label for="modo">Forma de pagamento</label>
                <select name="modo" id="modo" class="form-control">
                    {html_options values=$modo_ids selected=$pagamento.modo output=$modo_names}
                </select>
            </div>
            <div class="col-sm-4 col-xs-12">
                <label for="cpf">CPF na nota (opcional)</label>
                <input type="text" class="form-control input-sm" id="cpf" name="cpf" value="{$pagamento.cpf}" placeholder="000.000.000-00">
            </div>
        </div>
        <div class="row" style="margin-top:10px;">
            <div class="col-xs-12">
                <label class="checkbox-inline" style="font-weight:normal;margin:0;">
                    <input type="checkbox" id="temTroco" name="temTroco" value="1"
                        {if $pagamento.temTroco}checked="checked"{/if}
                        onchange="cupomGerenteToggleTroco();">
                    Tem troco
                </label>
            </div>
        </div>
        <div id="cupomGerenteTrocoBloco" class="troco-detalhe" style="{if !$pagamento.temTroco}display:none;{/if}">
            <div class="row">
                <div class="col-sm-6 col-xs-12">
                    <label for="valorPago">Valor recebido</label>
                    <input type="text" class="form-control" id="valorPago" name="valorPago"
                        onchange="calculaTotalCupomGerente();" value="{$pagamento.valorPago}">
                </div>
                <div class="col-sm-6 col-xs-12">
                    <label for="trocoMostra">Troco</label>
                    <input type="text" class="form-control text-right" id="trocoMostra" readonly value="{$pagamento.troco}">
                </div>
            </div>
        </div>
    </div>
    {/if}

    <div class="row resumo-totais" style="margin-top:4px;">
        <div class="col-xs-3 col-sm-3">
            <label class="small">Subtotal</label>
            <input type="text" class="form-control input-sm text-right" readonly value="{$pedido.totalProdutos}">
        </div>
        <div class="col-xs-3 col-sm-3">
            <label class="small">Desconto</label>
            <input type="text" class="form-control input-sm text-right" readonly value="{$pedido.desconto}">
        </div>
        <div class="col-xs-3 col-sm-3">
            <label class="small">Frete</label>
            <input type="text" class="form-control input-sm text-right" readonly value="{$pedido.frete}">
        </div>
        <div class="col-xs-3 col-sm-3">
            <label class="small">Total do cupom</label>
            <input type="text" id="totalCupomMostra" class="form-control input-sm text-right" readonly value="{$pedido.totalCupom}">
        </div>
    </div>

    <div class="itens-scroll">
        <table class="table table-condensed table-striped table-bordered" style="margin-bottom:0;">
            <thead>
                <tr>
                    <th>Ref.</th>
                    <th>Descrição</th>
                    <th class="text-right">Qtde</th>
                    <th class="text-right">Unit.</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                {if $pedido.lancItens|@count gt 0}
                    {section name=i loop=$pedido.lancItens}
                    <tr>
                        <td>{if $pedido.lancItens[i].CODFABRICANTE != ''}{$pedido.lancItens[i].CODFABRICANTE}{elseif $pedido.lancItens[i].ITEMFABRICANTE != ''}{$pedido.lancItens[i].ITEMFABRICANTE}{else}{$pedido.lancItens[i].ITEMESTOQUE}{/if}</td>
                        <td>{$pedido.lancItens[i].DESCRICAO}</td>
                        <td class="text-right">{$pedido.lancItens[i].QTSOLICITADA|number_format:3:",":"."}</td>
                        <td class="text-right">{$pedido.lancItens[i].UNITARIO|number_format:2:",":"."}</td>
                        <td class="text-right">{$pedido.lancItens[i].TOTAL|number_format:2:",":"."}</td>
                    </tr>
                    {/section}
                {else}
                    <tr><td colspan="5" class="text-center">Sem itens.</td></tr>
                {/if}
            </tbody>
        </table>
    </div>

    {if $danfe neq ''}
    <div class="alert alert-success" style="margin-top:12px;">NFC-e autorizada.</div>
    <iframe src="{$danfe}" style="width:100%;height:320px;border:1px solid #ddd;"></iframe>
    {/if}
</form>
{/if}
