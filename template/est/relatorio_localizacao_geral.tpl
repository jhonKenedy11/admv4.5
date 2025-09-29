<style>
    .x_panel {
        padding: 0 !important;
    }

    .right_col {
        padding: 1px !important;
    }

    #logo {
        border-radius: 10px;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 4px !important;
    }

    #dataHora {
        text-align: end !important;
        font-size: 9px !important;
        font-weight: bold !important;
        padding: 0 !important;
    }

    .form-group {
        margin-bottom: 1px !important;
    }

    .top {
        background-color: #f1efef;
    }

    /* Estilos de impressão */
    @media print {
        body {
            margin: 0;
        }

        .no-print {
            display: none;
        }

        #cabecalho,
        #bodyTable {
            position: absolute;
            width: 100%;
        }

        #cabecalho {
            top: 2;
        }

        #bodyTable {
            top: 70px;
            /* Ajuste conforme necessário para evitar sobreposição com o cabeçalho */
        }

        body {
            font-size: 10px !important;
        }

        /* Outros estilos de impressão que você desejar adicionar */
    }
</style>
<section id="divAll">
    <!-- page content -->
    <div class="right_col" role="main">
        <div id="cabecalho">
            <div class="col-md-3 col-sm-3 col-xs-2 form-group">
                <img src="images/logo.png" aloign="right" width=180 height=45 border="0" id="logo"></A>
            </div>
            <div class="col-md-7 col-sm-7 col-xs-7 form-group">
                <div>
                    <h2>
                        <center>
                            <strong>ESTOQUE POR LOCALIZA&Ccedil;&Atilde;O</strong><br>
                        </center>
                    </h2>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2 form-group" id="dataHora">
                {$dataImp}
            </div>
        </div>

        <!-- page content -->
        <div id="bodyTable" class="x_panel form-group">
            <div class="table form-group">
                <table class="table table-striped small form-group">
                    <thead>
                        <tr>
                            <th>LOC.</th>
                            <th>C&Oacute;D FAB</th>
                            <th>DESCRI&Ccedil;&Atilde;O</th>
                            <th>
                                <center>UNIDADE</center>
                            </th>
                            <th>
                                <center>PRE&Ccedil;O VENDA</center>
                            </th>
                            <th>
                                <center> QTD RESERVADA</center>
                            </th>
                            <th>
                                <center> QTD SISTEMA</center>
                            </th>
                            <th>
                                <center> QTD FISICA</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        {assign var="localizacao" value=""}
                        {section name=i loop=$pedido}
                            {if $pedido[i].LOCALIZACAO neq $localizacao }
                                <th id="date" colspan="8">{$pedido[i].LOCALIZACAO}</th>
                                {$localizacao = $pedido[i].LOCALIZACAO}
                            {/if}
                            {if isset($pedido[i].CUSTOCOMPRA) && $pedido[i].CUSTOCOMPRA > 0}
                                {math assign="margem" equation=(($pedido[i].VENDA*100)/$pedido[i].CUSTOCOMPRA)-100 format="%.2f"}
                            {else}
                                {assign var="margem" value="N/A"} {* Ou outro valor padrão, como 0 *}
                            {/if}


                            <tr>
                                <td> </td>
                                <td> {$pedido[i].CODFABRICANTE} </td>
                                <td> {$pedido[i].DESCRICAO} </td>
                                <td>
                                    <center> {$pedido[i].UNIDADE} </center>
                                </td>
                                <td>
                                    <center> {$pedido[i].VENDA|number_format:2:",":"."} </center>
                                </td>
                                <td>
                                    <center> {$pedido[i].RESERVA|number_format:2:",":"."} </center>
                                </td>
                                <td>
                                    <center> {$pedido[i].ESTOQUE|number_format:2:",":"."} </center>
                                </td>
                                <td style="vertical-align: bottom !important; padding-bottom: 0 !important;">
                                    <center> _____________ </center>
                                </td>
                            </tr>
                        {/section}

                    </tbody>
                </table>

            </div>
        </div>

        <div class="row no-print" style="position: inherit; bottom: 0;">
            <div class="col-xs-12">
                <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
            </div>
        </div>
    </div>
    <!-- /page content -->
</section>

<script>
    window.addEventListener('DOMContentLoaded', function() {
        debugger
        // Verifica se a tag section com id "divAll" existe
        var divAll = document.getElementById('divAll');
        if (divAll) {
            // Se existir, adiciona a classe à tag body
            document.body.classList.add('top');
        }
    });
</script>