    <link rel="stylesheet" href="{$bootstrap}/css/switchery/switchery.min.css" />
        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Pedido</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <FORM NAME="proposta" ACTION={$SCRIPT_NAME} METHOD="post">
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            <header>
                <div align="left">
                    <h3>
                        <img id="logoProp" src="{$pathImagem}/logo.png" aloign="right" width=224 height=54 border="0">
                    </h3>
                </div>
                <div align="right">
                    <h3>
                        {$dataAtual}<br>
                        Proposta: PCM{$pedido[0].ID}V{$pedido[0].VERSAO} - {$clientenome}
                    </h3>
                </div>
            </header>



            <table width="85%" border="0" align="center" class="CorTabela">

                <thead>
                    <tr>
                        <th>
                <div align="left">
                    <A href="http://www.admservice.com.br">
                        <img  src="{$pathImagem}/logo.png" aloign="right" width=224 height=54 border="0"></A>
                </div>    
                <div align="right"> <FONT size=2> PCM_{$pedido[0].ID} Vers&atilde;o {$pedido[0].VERSAO}.0 - 
                    {$pedido[0].DATA|date_format:" %e/%m/%Y"}</FONT>
                </div>
                </th>
                <tr>
                    <td class="Pesquisa" colspan="2" height="1"></td>
                </tr>   
                </tr>
                </thead>    

                <tfoot>
                    <tr>
                        <td class="Pesquisa" colspan="2" height="1"></td>
                    </tr>   
                    <tr>
                        <td>
                            admService Inform&aacute;tica e TI Ltda.<BR>
                            Av. Paran&aacute;, 891 lj 21<BR>
                            Tel. +55 44 3024-9119 | +55 44 3046-6118<BR>
                            Maring&aacute; - Pr.
                        </td>
                        <TD>
                            <img  src="{$pathImagem}/par_fsecure.jpg" aloign="right" width=112 height=30 border="0"></A>
                        </TD>
                        <TD>
                            <img  src="{$pathImagem}/par_microsoft.jpg" aloign="right" width=112 height=40 border="0"></A>
                        </TD>
                        <TD>
                            <img  src="{$pathImagem}/par_positivo.jpg" aloign="right" width=60 height=60 border="0"></A>
                        </TD>
                    </tr>
                </tfoot>

                <tbody>
                    <tr>
                        <td width='90%' align='left'>
                <center class='TituloPagina'>
                    <br>
                    <h2 class="fundo">
                        PROPOSTA COMERCIAL
                    </h2>
                    <br>
                </center>

                <h4>
                    {$pedido[0].NOME}<br>
                    {$pedido[0].TITULOEND} {$pedido[0].TIPOEND} {$pedido[0].ENDERECO}, n. {$pedido[0].NUMERO} - {$pedido[0].COMPLEMENTO}<br>
                    {$pedido[0].BAIRRO} - {$pedido[0].CIDADE} - {$pedido[0].UF}
                </h4>    
                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        1. Apresenta&ccedil;&atilde;o da empresa
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].APRESENTACAO}</FONT></p>
                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        2. Objetivo
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].OBJETIVO}</FONT></p>
                <BR>


                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        3. Valores
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].ITEM}</FONT></p>


                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        4. Condi&ccedil;&odblac;es de pagamento
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].CONDPGTO}</FONT></p>
                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        5. Garantia
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].GARANTIA}</FONT></p>
                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        6. Impostos
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].IMPOSTOS}</FONT></p>
                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        7. Prazo de entrega
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].PRAZOENTREGA}</FONT></p>
                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        8. Validade da proposta
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].VALIDADE}</FONT></p>
                <BR>

                <left class='TituloPagina'>
                    <br><br>
                    <h2 class="fundo">
                        9. Observa&ccedil;&atilde;o
                    </h2>
                </left>
                <p><FONT size=2>{$pedido[0].GARANTIA}</FONT></p>
                <BR>

                <center class='TituloPagina'>
                    <br><br>
                    <br><br>
                    <br><br>
                    <h2 class="fundo">
                        ACEITE DA PROPOSTA COMERCIAL PCM_{$pedido[0].ID}
                    </h2>
                    <br><br>
                </center>
                <p><FONT size=2>{$pedido[0].ACEITE}</FONT></p>
                <BR>

                </tbody>    


            </table>
        </form>
    </body>
</html>

