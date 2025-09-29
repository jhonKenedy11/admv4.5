{foreach from=$resultados item=produto}
<tr>
    <td><input type="checkbox" name="itensSelecionados[]" value="{$produto.CODIGO}" /></td>
    <td style="max-width: 140px !important; font-size: 10px;">{$produto.DESCRICAO}</td>
    <td style="text-align: center; max-width: 70px !important; font-size: 10px;">{$produto.GRUPO}</td>
    <td style="text-align: center; font-size: 10px;">{$produto.CODIGO}</td>
    <td style="text-align: center; font-size: 10px;">{$produto.LOCALIZACAO}</td>
</tr>
{/foreach}