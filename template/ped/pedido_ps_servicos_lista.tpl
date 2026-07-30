<style>
    #listaServicosEncontrados tr.linhaServicoEncontrado {
        cursor: pointer;
    }
    #listaServicosEncontrados tr.linhaServicoEncontrado:hover td {
        background-color: #f5f5f5;
    }
    #listaServicosEncontrados tr.linhaServicoEncontrado.info td {
        background-color: #d9edf7 !important;
    }
</style>
<div>
    {if $mostrarMensagemRefinar}
    <div class="alert alert-info" style="margin: 10px; padding: 10px; text-align: center;">
        <strong>Atenção!</strong> Foram encontrados 50 ou mais serviços. Refine a pesquisa para resultados mais precisos.
    </div>
    {/if}
    <div style="max-height: 160px; overflow-y: auto; overflow-x: hidden;">
        <table class="table table-bordered jambo_table" style="margin-bottom: 0;">
            <thead style="position: sticky; top: 0; background: gray; z-index: 10;">
                <tr style="background: gray; color: white;">
                    <th style="width: 50px; padding: 5px;">Selecionar</th>
                    <th style="padding: 5px;">Cód</th>
                    <th style="padding: 5px;">Descrição</th>
                    <th style="padding: 5px;">Unidade</th>
                    <th style="padding: 5px;">Valor unit.</th>
                </tr>
            </thead>
            <tbody>
                {section name=i loop=$servicos}
                <tr id="rowServico_{$servicos[i].ID}" class="linhaServicoEncontrado{if $servicos[i].SELECIONADO} info{/if}">
                    <td style="padding: 5px;">
                        <input type="checkbox" class="checkboxServico" name="servicoEncontrado" value="{$servicos[i].ID}"
                            {if $servicos[i].SELECIONADO}checked{/if}
                            data-cod-servico="{$servicos[i].ID}"
                            data-descricao="{$servicos[i].DESCRICAO|default:''}"
                            data-unidade="{$servicos[i].UNIDADE|default:''}"
                            data-valor-unitario="{$servicos[i].VALORUNITARIO|default:'0,00'}">
                    </td>
                    <td style="padding: 5px;">{$servicos[i].ID}</td>
                    <td style="padding: 5px;">{$servicos[i].DESCRICAO|default:''}</td>
                    <td style="padding: 5px;">{$servicos[i].UNIDADE|default:''}</td>
                    <td style="padding: 5px;">{$servicos[i].VALORUNITARIO|default:'0,00'}</td>
                </tr>
                {/section}
            </tbody>
        </table>
    </div>
</div>
