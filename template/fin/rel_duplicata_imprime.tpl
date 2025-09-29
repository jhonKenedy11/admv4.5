<!-- page content -->


<div class="right_col" role="main">

    <table class="borda" width="765px">
        <tbody>
            <tr>
                <td>
                    <table width="771px" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr valign="middle">
                                <td class="tdborda" width="50%">
                                    <img  src="images/logo.png">
                                    <div class="cabecalho">
                                        <b> {$empresa[0].NOMEEMPRESA}</b>
                                        <br>
                                        <br>
                                            {$empresa[0].TIPOEND} {$empresa[0].TITULOEND} {$empresa[0].ENDERECO}, {$empresa[0].NUMERO}, {$empresa[0].BAIRRO}
                                        <br>
                                            {$empresa[0].CEP} - {$empresa[0].CIDADE} - {$empresa[0].UF}
                                    </div>
                                </td>
                                <td class="tdborda" width="50%">
                                    <table width="70%" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td valign="middle">
                                                    CNPJ: {$empresa[0].CNPJ}
                                                    <br>
                                                    IE: {$empresa[0].INSCESTADUAL}
                                                    <br>
                                                    <br>
                                                    Data Emiss&atilde;o: <b> {$dataAtual|date_format:"%d/%m/%Y"} </b>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <h5><p class="txtduplicata"><b>DUPLICATA</b></p></h5>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td>
                    <table width="771px" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>  
                                <td class="tdborda">
                                    <table width="760px" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td align="center">
                                                     <strong>Fatura n&deg;</strong>
                                                     <br>
                                                     <strong>&nbsp;</strong>
                                                </td>
                                                <td align="center">
                                                     <strong>Valor R$</strong>
                                                     <br>
                                                     <strong>&nbsp;</strong>
                                                </td>
                                                <td align="center">
                                                     <strong>Duplicata</strong>
                                                     <br>
                                                     <strong>n&deg; de ordem</strong>
                                                </td>
                                                <td align="center">
                                                    <strong> Vencimento </strong>
                                                    <br>
                                                    <strong>&nbsp;</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center"> 
                                                    {$pedido[0].DOCTO}
                                                </td>
                                                <td align="center"> 
                                                    {$pedido[0].TOTAL|number_format:2:",":"."}
                                                </td>
                                                <td align="center"> 
                                                    {$pedido[0].PARCELA}
                                                </td>
                                                <td align="center"> 
                                                    {$pedido[0].VENCIMENTO|date_format:"%d/%m/%Y"}
                                                </td>  
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                               
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td>
                    <table width="771px" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td class="tdborda">
                                    <table height="92px" width="380px" border="0" cellspacing="2" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td class="tcliente1">
                                                    <div>
                                                        Nome do sacado: 
                                                        <span><b>{$cliente[0].NOME}</b></span>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="tcliente2" rowspan="1" colspan="2">
                                                Endere&ccedil;o:
                                                <span ><b>{$cliente[0].ENDERECO}</b></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="tcliente9" rowspan="1" colspan="2">
                                                N&deg;:
                                                <span ><b>{$cliente[0].NUMERO}</b></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="tcliente3">
                                                    CEP/Município: 
                                                    <span><b>{$cliente[0].CEP} - {$cliente[0].CIDADE}</b></span>
                                                </td>
                                                <td class="tcliente4">
                                                    Estado:
                                                    <span class="conteudocliente"><b>{$cliente[0].UF}</b></span>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td class="tcliente5">
                                                    Pra&ccedil;a de pagamento: 
                                                    <span><b>{$cliente[0].CIDADE}</b></span>
                                                </td>
                                                <td class="tcliente6">
                                                    Estado: 
                                                    <span><b>{$cliente[0].UF}</b></span>
                                                </td>
                                            </tr>

                                             <tr>
                                                <td class="tcliente7" width="50%">
                                                    Cnpj/Cpf: 
                                                    <span><b>{$cliente[0].CNPJCPF}</b></span>
                                                </td>
                                                <td class="tcliente8" width="50%">
                                                    Inscr. Est./RG: 
                                                    <span><b>{$cliente[0].INSCESTRG}</b></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                                 <td class="tdborda">
                                    <table width="110px">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <p class="instfin">
                                                        Para uso da institui&ccedil;&atilde;o financeira
                                                    </p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="tdborda">
                    <table width="765px" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td class="vlrex"width="70px">
                                    <b>Valor por
                                    <br>
                                    extenso</b>
                                </td>
                                <td width="765px" style="font-size: 12px;">
                                    &nbsp;({$valorExtenso})
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td class="tdborda">
                    <table width="760px" height="60px" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>
                                <td class="infos1" align="center">
                                    Reconhecemos a exatid&atilde;o desta <b> DUPLICATA DE VENDA MERCANTIL/PRESTA&Ccedil;&Atilde;O DE SERVI&Ccedil;OS</b>, na import&acirc;ncia acima, que pagaremos &agrave;
                                    <p><b>{$empresa[0].NOMEEMPRESA}</b>, ou &agrave; sua ordem, na pra&ccedil;a e vencimentos indicados.
                                </td>
                            </tr>
                            <hr></hr>
                            <tr>
                                <td class="infos2" align="center">
                                        N&atilde;o sendo paga no dia do vencimento, cobrar juros de mora e despesas financeiras.
                                    <p class="txt"> 
                                        N&atilde;o conceder descontos mesmo condicionalmente.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <td>
                    <table width="771px" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr>  
                                <td class="tdborda">
                                    <table width="763px" height="46px" border="0" cellspacing="0" cellpadding="0">
                                        <tbody>
                                            <tr>
                                                <td class="campos1">
                                                    Em _____/_____/_____
                                                </td>
                                                <td class="campos2">
                                                     ____________________________________
                                                </td>
                                                <td class="campos3">
                                                     ____________________________________
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="campostxt1"> 
                                                    DATA DO ACEITE
                                                </td>
                                                <td class="campostxt2"> 
                                                    ASSINATURA EMITENTE
                                                </td>
                                                <td class="campostxt3"> 
                                                    ASSINATURA DO SACADO
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                               
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            
        </tbody>
    </table>
</div>
  
<style>
table{
    font-size: 11px;
    border-collapse: separate;
    text-indent: initial;
    white-space: normal;
    line-height: normal;
    border-spacing: 2px;
    font-variant: normal;
    box-sizing: border-box;
}

.borda {
    border: 1px solid black;
}

td.tdborda{
    border: 1px solid;
    border-radius: 5px;
}

.instfin{
    left: 22px;
    text-align: center;
    position: absolute;
    margin: .1em;
    left: 612px;
    top: 130px;
}

.txtduplicata{
    margin-top: -55px;
    margin-left: 285px;
}

td.tcliente1{
    position: absolute;
    margin: .1em;
    left: 17px;
    top: 122px;
}

td.tcliente2{
    position: absolute;
    margin: .1em;
    left: 17px;
    top: 140px;
}

td.tcliente3{
    position: absolute;
    margin: .1em;
    left: 17px;
    top: 158px;
}

td.tcliente4{
    position: absolute;
    margin: .1em;
    left: 390px;
    top: 158px;
}

td.tcliente5{
    position: absolute;
    margin: .1em;
    left: 17px;
    top: 175px;
}

td.tcliente6{
    position: absolute;
    margin: .1em;
    left: 390px;
    top: 175px;
}

td.tcliente7{
    position: absolute;
    margin: .1em;
    left: 17px;
    top: 192px;
}

td.tcliente8{
    position: absolute;
    margin: .1em;
    left: 390px;
    top: 192px;
}

td.tcliente9{
    position: absolute;
    margin: .1em;
    left: 390px;
    top: 140px;
}

td.vlrex{
   border-right: 1px solid #000000;
   border-right-width: 1px;
}

td.campos1{
    position: absolute;
    margin: .1em;
    left: 28px;
    top: 340px;
}

td.campos2{
    position: absolute;
    margin: .1em;
    left: 227px;
    top: 340px;
}

td.campos3{
    position: absolute;
    margin: .1em;
    left: 530px;
    top: 340px;
}

td.campostxt1{
    position: absolute;
    margin: .1em;
    left: 52px;
    top: 355px;
}

td.campostxt2{
    position: absolute;
    margin: .1em;
    left: 273px;
    top: 355px;
}

td.campostxt3{
    position: absolute;
    margin: .1em;
    left: 573px;
    top: 355px;
}

td.infos1{
    position: absolute;
    margin: .1em;
    left: 40px;
    top: 259px;
}

td.infos2{
    bottom: 5px;
    position: absolute;
    margin: .1em;
    left: 190px;
    top: 289px;
}

hr{
    border-color: black;
    position: absolute;
    margin: .1em;
    width: 770px;
    left: 12px;
    top: 286px;
}

.infoempresa{
    position: absolute;
    margin: .1em;
    left: 40px;
    top: 259px;
    text-size: 12px;
}

div.cabecalho{
    position: absolute;
    margin: .1em;
    left: 75px;
    top: 18px;
}

img {
    filter: grayscale(100%);
    -webkit-filter: grayscale(100%);
    -moz-filter: grayscale(100%);
    -ms-filter: grayscale(100%);
    -o-filter: grayscale(100%);
    aloign: righ;
    width: 57px;
    height: 51px;
    border-radius: 20%;
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
</style>


