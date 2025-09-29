<!-- page content -->
{section name=i loop=$lanc}

<div class="right_col" role="main">
    <div class="clearfix"></div-->
        <div class="x_panel">
            <table class="table table-responsive" id="tabelaTotal">
                <thead>
                    <tr>
                        <th>
                            <div class="col-md-12 col-sm-12 col-xs-12" align="center" style="height: 23px;">
                                <h3><strong>{$empresa[0].NOMEEMPRESA}</strong></h3>
                            </div>
                        </th>
                    </tr>
                </thead>
                <body>
                    <table class="table table-sm table-responsive" id="tab">
                        <tr>
                            <td colspan="3"> 
                                <strong>Fornecedor:</strong>&nbsp;{$lanc[i].NOMEPESSOA} 
                            </td>
                            <td>
                                &nbsp;
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Documento:</strong>&nbsp;{$lanc[i].DOCTO}
                            </td>
                            <td>
                                <strong>S&eacute;rie:</strong>&nbsp;{$lanc[i].SERIE}
                            </td>
                            <td>
                                &nbsp;
                            </td>
                            <td>
                                <strong>Parcela:</strong>&nbsp;{$lanc[i].PARCELA} / {$lanc[i].TOTALPARCELAS}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Emiss&atilde;o:</strong>&nbsp;{$lanc[i].EMISSAO|date_format:"%d/%m/%Y"}
                            </td>
                            <td colspan="2"> 
                                <strong>Vencimento:&nbsp;{$lanc[i].VENCIMENTO|date_format:"%d/%m/%Y"}</strong>
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <strong>G&ecirc;nero:&nbsp;</strong>{$lanc[i].DESCGENERO}
                            </td>
                            <td>
                                &nbsp;
                            </td>
                            <td>
                                &nbsp;
                            </td>
                        </tr>
                        <tr>
                            {if $lanc[i].OBS eq ''}
                            {else}
                            <td colspan="3">
                                <strong>Obs:</strong>&nbsp;{$lanc[i].OBS}
                            </td>
                            {/if}
                        </tr>
                        <tr>
                            <td>
                                <strong>Impresso:</strong>&nbsp;{$dataAtual|date_format:"%d/%m/%Y"}
                            </td>
                            <td>
                                &nbsp;
                            </td>
                            <td colspan="2" align="center">
                               <strong>Valor:</strong>&nbsp;{$lanc[i].TOTAL|number_format:2:",":"."}
                            </td>
                            <td>
                                 &nbsp;
                            </td>
                        </tr>
                    </table>
                </body>
            </table>
        </div>
    </div>
</div>
{/section} 

<!-- /page content -->

  
<style>
#tabelaTotal{
    margin:0;
    padding:0;
}
#tab{
    margin:0;
    padding:0;
}

#tab td{
padding: 4px;
border:none !important;
font-size: 13px;
}
* {
background:transparent !important;
color:#000 !important;
text-shadow:none !important;
filter:none !important;
-ms-filter:none !important;
}

body {
margin:0;
padding:0;
line-height: 1.4em;
}

h3{
    font-size: 16px;
    margin-top: -1px;
}

.right_col{
        margin-top: -5px;
    }

@media print{
    a[href]:after {
    content: none !important;
    }
    
    @page{
    margin-top: 0;
    margin-bottom: 0;
    display: none;
    }

    .page-break { 
    page-break-after: always; 
    }

    p {
widows: 5;
}

p {
orphans: 5;
}

}

</style>
