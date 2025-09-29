{section name=i loop=$lancItens}
    <tr>
        <td> {$lancItens[i].ITEMESTOQUE} </td>
        <td> {$lancItens[i].DESCRICAO} </td>
        <td align=center> {$lancItens[i].QTSOLICITADA|number_format:0:",":"."} </td>
        <td align=center> {$lancItens[i].UNITARIO|number_format:2:",":"."} </td>
        <td align=center> {$lancItens[i].VLICMSST|number_format:2:",":"."} </td>
        <td align=center> {$lancItens[i].TOTAL|number_format:2:",":"."} </td>
        <td>
            <button type="button" class="btn btn-danger btn-xs" onClick="ajaxExcluirItem({$lancItens[i].ID}, {$lancItens[i].NRITEM}, this);">
                <span class="glyphicon glyphicon-remove" text-align="center" aria-hidden="true"></span>
            </button>
        </td>
    </tr>
{/section}