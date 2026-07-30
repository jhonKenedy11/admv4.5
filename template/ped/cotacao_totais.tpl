<div class="col-md-3 col-sm-6 col-xs-6">
    <label for="valorProdutos">Valor Produtos</label>
    <div class="input-group">
        <span class="input-group-btn">
            <button class="btn btn-default btn-sm not-active" tabindex="-1"
                type="button">R$</button>
        </span>
        <input class="form-control input-sm" placeholder="Valor Produtos." id="valorProdutos"
            name="valorProdutos" value="{$valorProduto}" readonly>
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-6">
    <label for="desconto">Desconto</label>
    <div class="input-group">
        <span class="input-group-btn">
            <button class="btn btn-default btn-sm not-active" tabindex="-1"
                type="button">R$</button>
        </span>
        <input class="form-control input-sm money" placeholder="Desconto."
            id="valorDesconto" name="valorDesconto"
            onClick="javascript:guardaValorAntCotacao();"
            onchange="javascript:atualizarInfo();" value="{$valorDesconto}">
    </div>
</div>
<div class="col-md-3 col-sm-6 col-xs-6">
    <label for="total">T O T A L</label>
    <div class="input-group">
        <span class="input-group-btn">
            <button class="btn btn-default btn-sm not-active" tabindex="-1"
                type="button">R$</button>
        </span>
        <input class="form-control input-sm not-active" tabindex="-1"
            placeholder="Total Cotação." id="valorTotal" name="valorTotal"
            value="{$valorTotal}" readonly>
    </div>
</div>

<div class="col-md-3 col-sm-6 col-xs-6">
    <label for="markupCotacao">
        Markup (%)
        <span class="glyphicon glyphicon-info-sign text-info" 
              style="cursor: help; margin-left: 5px; font-size: 14px;"
              data-toggle="tooltip" 
              data-placement="down" 
              data-html="true"
              title="<strong>Cálculo do Markup:</strong><br/>
                <strong>Só atualiza itens que tem Custo de Compra</strong><br>
                <em>O markup representa a margem de lucro desejada sobre o custo.</em>">
    </label>
    <input class="form-control input-sm money" type="text"
        id="markupCotacao" name="markupCotacao" placeholder="0,00%"
        onchange="javascript:atualizarmarkup();"
        {if $lanc[0].MARKUP != ''}
            value="{$lanc[0].MARKUP|number_format:2:",":"."}"
        {else}
            value="{$markupCotacao}"
        {/if}
    >
</div>
