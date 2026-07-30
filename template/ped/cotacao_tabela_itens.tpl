{section name=i loop=$lancPesq}
    <tr>
        <td hidden class="i_nr_item"> {$lancPesq[i].NRITEM} </td>
        <td hidden class="i_data_entrega"> {$lancPesq[i].DATAENTREGAPECA|date_format:"%d/%m/%Y"} </td>
        <td class="i_item_estoque"> {$lancPesq[i].ITEMESTOQUE} </td>
        <td class="i_item_fabricante"> {$lancPesq[i].ITEMFABRICANTE} </td>
        <td class="i_codigo_nota"> {$lancPesq[i].CODIGONOTA} </td>
        <td class="i_decricao"> {$lancPesq[i].DESCRICAO} </td>
        <td class="i_qtd_solicitada">
            {$lancPesq[i].QTSOLICITADA|number_format:2:",":"."} </td>
        <td class="i_unitario">
            {$lancPesq[i].UNITARIO|number_format:2:",":"."} </td>
        <td class="i_perc_desconto">
            {$lancPesq[i].PERCDESCONTO|number_format:2:",":"."} </td>
        <td class="i_desconto">
            {$lancPesq[i].DESCONTO|number_format:2:",":"."} </td>
        <td class="i_data_entrega_td"> {$lancPesq[i].DATAENTREGAPECA|date_format:"%d/%m/%Y"} </td>
        <td class="i_total"> {$lancPesq[i].TOTAL|number_format:2:",":"."} </td>
        <td class="i_markup"> {$lancPesq[i].MARKUP|number_format:2:",":"."|default:'0,00'} </td>
        <td hidden class="i_unidade"> {$lancPesq[i].UNIDADE|default:''} </td>
        <td hidden class="i_custo"> {$lancPesq[i].CUSTOCOMPRA|number_format:2:",":"."|default:'0,00'} </td>
        <td>
            <button {if $lancPesq[i].ITEMESTOQUE eq 0} disabled
                {/if}type="button" class="btn btn-info btn-xs"
                onclick="javascript:abrir('{$pathCliente}/index.php?mod=est&form=produto&opcao=pesquisarpecas&letra=||{$lancPesq[i].ITEMFABRICANTE}||||{$lancPesq[i].ITEMESTOQUE}', 'produto');"><span
                    class="glyphicon glyphicon-search"
                    aria-hidden="true"></span></button>
            <button type="button" class="btn btn-primary btn-xs"
                onclick="javascript:editarProduto(this, '{$lancPesq[i].NRITEM}')"><span
                    class="glyphicon glyphicon-pencil"
                    aria-hidden="true"></span></button>
            <button type="button" class="btn btn-danger btn-xs"
                onclick="javascript:submitExcluiItem('{$lancPesq[i].NRITEM}');"><span
                    class="glyphicon glyphicon-remove"
                    aria-hidden="true"></span></button>
        </td>
    </tr>
{/section}

