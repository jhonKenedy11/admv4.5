{if count($listaEquivalentes) > 0}
    {section name=i loop=$listaEquivalentes}
        <tr class="equiv-row {if $smarty.section.i.first}equiv-row-selected{/if}" style="cursor:pointer;">
            <td style="text-align:left; padding:6px;">
                <input type="radio" name="produto_equiv_sel" class="produto-equiv-radio" value="{$listaEquivalentes[i].CODIGO|escape:'html'}" {if $smarty.section.i.first}checked{/if} style="display:none;">
                {$listaEquivalentes[i].CODIGO|escape:'html'}
            </td>
            <td style="text-align:left; padding:6px;">{$listaEquivalentes[i].CODFABRICANTE|escape:'html'}</td>
            <td style="text-align:left; padding:6px;">{$listaEquivalentes[i].DESCRICAO|escape:'html'}</td>
            <td style="text-align:left; padding:6px;">{$listaEquivalentes[i].NOMEMARCA|escape:'html'}</td>
            <td style="text-align:left; padding:6px;">{$listaEquivalentes[i].UNIDADE|escape:'html'}</td>
        </tr>
    {/section}
{else}
    <tr>
        <td colspan="5" style="padding:8px; text-align:left;">Nenhum produto encontrado.</td>
    </tr>
{/if}
