<style>
body { font-family: Arial, sans-serif; font-size: 8pt; margin: 5mm; }
table { width: 100%; border-collapse: collapse;}
/* Padding zerado no default, será controlado dentro de cada campo */
td { border: 1pt solid black; padding: 0; vertical-align: top; }

/* NOVAS CLASSES PADRONIZADAS PARA OS CAMPOS */
.campo-titulo {
    font-size: 7pt;
    padding: 2pt 3pt;
    line-height: 7pt;
}
.campo-valor {
    font-size: 9pt;
    font-weight: bold;
    padding: 1pt 3pt 2pt 3pt;
    line-height: 9pt;
}

/* CLASSES ANTIGAS MANTIDAS */
.center { text-align: center; }
.left { text-align: left; }
.right { text-align: right; }
.sem-borda { border: none; }
.borda-left { border: none; border-right: 1pt solid black; }
.cabecalho { font-weight: bold; font-size: 10pt; }
.numero_agencia { font-size: 14px; font-weight: bold; text-align: center; vertical-align: bottom;}
.codigo_barras_numeral { text-align: right; font-size: 14px; font-weight: bold; vertical-align: bottom;}
.recibo_pagador { font-size: 9px; text-align: right; }
.linha-corte { text-align: right; font-size: 8px; }
hr { border-top: 2pt dashed; margin: 0;}
</style>

<hr>
<div style="text-align: right;">
    <p class="recibo_pagador">Recibo do Pagador</p>
</div>

<table>
    <tr>
        <td class="borda-left" style="width: 150px; vertical-align: bottom; padding: 3pt;">
            <img src="images/blt/logobradesco.jpg" width="120" height="30">
        </td>
        <td class="borda-left numero_agencia" style="padding: 3pt;">
            <?php echo $dadosboleto["codigo_banco_com_dv"]?>
        </td>
        <td class="sem-borda codigo_barras_numeral" colspan="5" style="padding: 3pt;">
            <?php echo $dadosboleto["linha_digitavel"]?>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none;">Local de Pagamento</td></tr>
                <tr><td class="campo-valor" style="border:none;">Pagável Preferencialmente na Rede Bradesco ou no Bradesco Expresso</td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">Data de Vencimento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["data_vencimento"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none;">Nome do Beneficiário / CNPJ / CPF / Endereço:</td></tr>
                <tr><td class="campo-valor" style="border:none;"><?php echo $dadosboleto["identificacao"]; ?> - <?php echo $dadosboleto["cpf_cnpj"] ?></td></tr>
                <tr><td class="campo-valor" style="border:none;"><?php echo $dadosboleto["endereco"];?> -<?php echo $dadosboleto["cidade_uf"]; ?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">Agência/Código Beneficiário</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["agencia_codigo"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Data do documento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["data_documento"]?></td></tr>
            </table>
        </td>
        <td colspan="2">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Núm. do documento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["numero_documento"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Espécie doc</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["especie_doc"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Aceite</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["aceite"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Data Processamento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["data_processamento"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">Nosso Número</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["nosso_numero"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Uso do Banco</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;">&nbsp;</td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">CIP</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;">000</td></tr>
            </table>
        </td>
        <td>
             <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Carteira</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["carteira"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Moeda</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["especie"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Quantidade</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["quantidade"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Valor</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["valor_unitario"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(=) Valor do Documento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["valor_boleto"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="left" colspan="6" rowspan="3" style="padding: 2pt;">
            <span class="campo-titulo">Instruções</span><br>
            <span class="campo-valor" style="font-weight: bold;">
                <span style="font-size: 8pt; margin-left: -3pt;"> <?php echo $dadosboleto["instrucoes1"]; ?></span><br>
                <span style="font-size: 8pt; margin-left: 2pt;" ><?php echo $dadosboleto["instrucoes2"]; ?></span><br>
                <span style="font-size: 8pt;"><?php echo $dadosboleto["instrucoes3"]; ?></span><br>
                <span style="font-size: 8pt;"><?php echo $dadosboleto["instrucoes4"]; ?></span>
            </span>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(-) Descontos/Abatimentos</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: right;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(+) Juros/Multa</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: right;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
             <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(=) Valor Pago</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: right;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="left" colspan="7" style="padding: 2pt;">
            <span class="campo-titulo">Nome do Pagador / CPF / CNPJ / Endereço</span><br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["sacado"]?></b><br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["endereco1"]?> - <?php echo $dadosboleto["endereco2"]?></b>
            <br>
            <span class="campo-titulo">Nome do Beneficiário Final / CPF / CNPJ / Endereço</span><br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["identificacao"]; ?> - <?php echo $dadosboleto["cpf_cnpj"] ?></b>
            <br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["endereco"];?> -<?php echo $dadosboleto["cidade_uf"]; ?></b>
        </td>
    </tr>
</table>

<br>

<table>
    <tr>
        <td class="sem-borda" rowspan="2" style="padding-top: 10pt;">
             <?php fbarcode($dadosboleto["codigo_barras"]); ?>
        </td>
        <td class="sem-borda cabecalho right">
            Autenticação Mecânica
        </td>
    </tr>
     <tr>
        <td class="sem-borda right">
            <b style="text-align: right; font-size: 9pt;">Ficha de Compensação</b>
        </td>
    </tr>
</table>

<br><br>
<p class="linha-corte">Corte na linha pontilhada</p>
<hr>
<br><br>

<table>
    <tr>
        <td class="borda-left" style="width: 150px; vertical-align: bottom; padding: 3pt;">
            <img src="images/blt/logobradesco.jpg" width="120" height="30">
        </td>
        <td class="borda-left numero_agencia" style="padding: 3pt;">
            <?php echo $dadosboleto["codigo_banco_com_dv"]?>
        </td>
        <td class="sem-borda codigo_barras_numeral" colspan="5" style="padding: 3pt;">
            <?php echo $dadosboleto["linha_digitavel"]?>
        </td>
    </tr>
    <tr>
    <td colspan="6">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none;">Local de Pagamento</td></tr>
                <tr><td class="campo-valor" style="border:none;">Pagável Preferencialmente na Rede Bradesco ou no Bradesco Expresso</td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">Data de Vencimento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["data_vencimento"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none;">Nome do Beneficiário / CNPJ / CPF / Endereço:</td></tr>
                <tr><td class="campo-valor" style="border:none;"><?php echo $dadosboleto["identificacao"]; ?> - <?php echo $dadosboleto["cpf_cnpj"] ?></td></tr>
                <tr><td class="campo-valor" style="border:none;"><?php echo $dadosboleto["endereco"];?> -<?php echo $dadosboleto["cidade_uf"]; ?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left; width: 300pt;">Agência/Código Beneficiário</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["agencia_codigo"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Data do documento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["data_documento"]?></td></tr>
            </table>
        </td>
        <td colspan="2">
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Núm. do documento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["numero_documento"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Espécie doc</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["especie_doc"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Aceite</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["aceite"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Data Processamento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["data_processamento"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">Nosso Número</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["nosso_numero"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Uso do Banco</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;">&nbsp;</td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">CIP</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;">000</td></tr>
            </table>
        </td>
        <td>
             <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Carteira</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["carteira"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Moeda</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["especie"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Quantidade</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["quantidade"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: center;">Valor</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["valor_unitario"]?></td></tr>
            </table>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(=) Valor do Documento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: center;"><?php echo $dadosboleto["valor_boleto"]?></td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="left" colspan="6" rowspan="2" style="padding: 3pt;">
            <p style="font-size: 8pt; margin: 0; padding: 0;">Informações de responsabilidade do beneficiário</p>
            <span style="font-size: 8pt; font-weight: bold;">BOLETO DE PROPOSTA</b>
            <br>
            <span style="font-size: 7pt; font-weight: bold;">
                Atenção: O beneficiário declara possuir autorização prévia do pagador para emissão deste boleto.
                <br>
                O pagamento deste Boleto NÃO É OBRIGATÓRIO. O não pagamento não dará
                causa a protestos, a inserção do nome do pagador em cadastro de restrição ao
                crédito ou a cobranças judiciais ou extrajudiciais. O pagamento até a data de
                vencimento significa conhecimento prévio das condições e aceitação da oferta.
                Dúvidas contatar o beneficiário através de seus canais de atendimento.
            </span>
        </td>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(-) Desconto/Abatimento</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: right;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" style="width: 100%; border: none;">
                <tr><td class="campo-titulo" style="border:none; text-align: left;">(=) Valor Cobrado</td></tr>
                <tr><td class="campo-valor" style="border:none; text-align: right;">&nbsp;</td></tr>
            </table>
        </td>
    </tr>
     <tr>
        <td class="left" colspan="7" style="padding: 2pt;">
            <span class="campo-titulo">Nome do Pagador / CPF / CNPJ / Endereço</span><br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["sacado"]?></b><br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["endereco1"]?> - <?php echo $dadosboleto["endereco2"]?></b>
            <br>
            <span class="campo-titulo">Nome do Beneficiário Final / CPF / CNPJ / Endereço</span><br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["identificacao"]; ?> - <?php echo $dadosboleto["cpf_cnpj"] ?></b>
            <br>
            <b style="font-size: 8pt; font-weight: bold; margin-left: 3pt;"><?php echo $dadosboleto["endereco"];?> -<?php echo $dadosboleto["cidade_uf"]; ?></b>
        </td>
    </tr>
</table>

<br>