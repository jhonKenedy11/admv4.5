<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            color: #333;
        }
        .header {
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }
        .info-box {
            margin: 15px 0;
            padding: 10px;
            background-color: #f5f5f5;
            border-left: 4px solid #333;
        }
        .info-box strong {
            display: inline-block;
            width: 150px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table thead {
            background-color: #333;
            color: white;
        }
        table th {
            padding: 4px 8px;
            text-align: left;
            font-size: 10px;
            border: 1px solid #ddd;
        }
        table td {
            padding: 3px 6px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .totals {
            margin-top: 20px;
            margin-bottom: 100px;
            text-align: right;
        }
        .totals table {
            width: 300px;
            margin-left: auto;
            margin-bottom: 0;
        }
        .totals td {
            padding: 5px 10px;
        }
        .totals .label {
            font-weight: bold;
            text-align: right;
        }
        .totals .value {
            text-align: right;
            border-top: 1px solid #333;
        }
        .footer {
            margin-top: 80px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="images/logo.png" align="right" width=180 height=46 border="0">
        <h1>{$empresa[0].NOME|default:$empresa[0].NOMEEMPRESA|default:'Empresa'}</h1>
        <h2>{if $pedido[0].SITUACAO eq 5}COTAÇÃO{else}PEDIDO{/if} Nº {$pedido[0].ID}</h2>
    </div>

    <div class="info-box">
        {if isset($pedido[0].EMISSAO) && $pedido[0].EMISSAO neq ''}
        <div><strong>Data de Emissão:</strong> {$pedido[0].EMISSAO|date_format:"%d/%m/%Y"}</div>
        {/if}
        {if isset($pedido[0].NOME) && $pedido[0].NOME neq ''}
        <div><strong>Cliente:</strong> {$pedido[0].NOME}</div>
        {/if}
        {if isset($pedido[0].CNPJCPF) && $pedido[0].CNPJCPF neq ''}
        <div><strong>{if isset($pedido[0].PESSOA) && $pedido[0].PESSOA eq 'J'}CNPJ:{else}CPF:{/if}</strong> {$pedido[0].CNPJCPF}</div>
        {/if}
        {if isset($pedido[0].ENDERECO) && $pedido[0].ENDERECO neq ''}
        <div><strong>Endereço:</strong> {$pedido[0].ENDERECO}{if isset($pedido[0].NUMERO) && $pedido[0].NUMERO neq ''}, {$pedido[0].NUMERO}{/if}{if isset($pedido[0].BAIRRO) && $pedido[0].BAIRRO neq ''} - {$pedido[0].BAIRRO}{/if}{if isset($pedido[0].CIDADE) && $pedido[0].CIDADE neq ''} - {$pedido[0].CIDADE}{/if}{if isset($pedido[0].UF) && $pedido[0].UF neq ''}/{$pedido[0].UF}{/if}{if isset($pedido[0].CEP) && $pedido[0].CEP neq ''} - CEP: {$pedido[0].CEP}{/if}</div>
        {/if}
        {if isset($pedido[0].FONE) && $pedido[0].FONE neq ''}
        <div><strong>Telefone:</strong> {$pedido[0].FONE}</div>
        {/if}
        {if isset($descCondPgto) && $descCondPgto neq ''}
        <div><strong>Condição de Pagamento:</strong> {$descCondPgto}</div>
        {/if}
        {if isset($prazoEntrega) && $prazoEntrega neq ''}
        <div><strong>Prazo de Entrega:</strong> {$prazoEntrega}</div>
        {/if}
    </div>

    {if isset($pedidoItem) && is_array($pedidoItem) && count($pedidoItem) > 0}
    <h3>Itens da Cotação</h3>
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Descrição</th>
                <th>Unidade</th>
                <th>Quantidade</th>
                <th>Valor Unitário</th>
                <th>Desconto</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            {section name=i loop=$pedidoItem}
            <tr>
                <td>{if isset($pedidoItem[i].ITEMESTOQUE) && $pedidoItem[i].ITEMESTOQUE neq ''}{$pedidoItem[i].ITEMESTOQUE}{elseif isset($pedidoItem[i].CODIGO)}{$pedidoItem[i].CODIGO}{else}-{/if}</td>
                <td>{if isset($pedidoItem[i].DESCRICAO)}{$pedidoItem[i].DESCRICAO}{else}-{/if}</td>
                <td>{if isset($pedidoItem[i].UNIDADE)}{$pedidoItem[i].UNIDADE}{else}-{/if}</td>
                <td style="text-align: right;">{if isset($pedidoItem[i].QTSOLICITADA)}{$pedidoItem[i].QTSOLICITADA|number_format:2:",":"."}{else}0,00{/if}</td>
                <td style="text-align: right;">R$ {if isset($pedidoItem[i].UNITARIO)}{$pedidoItem[i].UNITARIO|number_format:2:",":"."}{else}0,00{/if}</td>
                <td style="text-align: right;">{if isset($pedidoItem[i].DESCONTO) && $pedidoItem[i].DESCONTO > 0}R$ {$pedidoItem[i].DESCONTO|number_format:2:",":"."}{else}-{/if}</td>
                <td style="text-align: right;">R$ {if isset($pedidoItem[i].TOTAL)}{$pedidoItem[i].TOTAL|number_format:2:",":"."}{else}0,00{/if}</td>
            </tr>
            {/section}
        </tbody>
    </table>

    <div class="totals">
        <table>
            {if isset($pedido[0].TOTALPRODUTOS) && $pedido[0].TOTALPRODUTOS neq '' && $pedido[0].TOTALPRODUTOS > 0}
            <tr>
                <td class="label">Subtotal Produtos:</td>
                <td style="text-align: right;">R$ {$pedido[0].TOTALPRODUTOS|number_format:2:",":"."}</td>
            </tr>
            {/if}
            {if isset($pedido[0].DESCONTO) && $pedido[0].DESCONTO > 0}
            <tr>
                <td class="label">Desconto:</td>
                <td style="text-align: right;">R$ {$pedido[0].DESCONTO|number_format:2:",":"."}</td>
            </tr>
            <tr>
                <td colspan="1" style="padding: 5px;"></td>
            </tr>
            {/if}
            <tr>
                <td class="label value"><strong>Total:</strong></td>
                <td class="value"><strong>R$ {if isset($pedido[0].TOTAL)}{$pedido[0].TOTAL|number_format:2:",":"."}{else}0,00{/if}</strong></td>
            </tr>
        </table>
    </div>
    {/if}

    <div style="height: 80px;"></div>

    <div class="footer">
        <p style="margin-bottom: 10px;">Este documento foi gerado automaticamente em {$dataImp}</p>
        <p>{$empresa[0].NOME|default:$empresa[0].NOMEEMPRESA|default:'Empresa'}</p>
    </div>
</body>
</html>

