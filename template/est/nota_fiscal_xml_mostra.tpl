<!-- Header -->
{debug}
<style type="text/css">
  @media print {
    @page {
      margin-left: 15mm;
      margin-right: 15mm;
    }

    footer {
      page-break-after: always;
    }
  }

  * {
    margin: 0;
  }

  .ui-widget-content {
    border: none !important;
  }

  .nfe-square {
    margin: 0 auto 2cm;
    box-sizing: border-box;
    width: 2cm;
    height: 1cm;
    border: 1px solid #000;
  }

  .nfeArea.page {
    position: relative;
    font-family: "Times New Roman", serif;
    color: #000;
    margin: 0 auto;
    overflow: hidden;
  }

  .nfeArea .font-12 {
    font-size: 12pt;
  }

  .nfeArea .font-8 {
    font-size: 8pt;
  }

  .nfeArea .bold {
    font-weight: bold;
  }

  /* == TABELA == */
  .nfeArea .area-name {
    font-family: "Times New Roman", serif;
    color: #000;
    font-weight: bold;
    margin: 5px 0 0;
    font-size: 7pt;
    text-transform: uppercase;
  }

  .nfeArea .txt-upper {
    text-transform: uppercase;
  }

  .nfeArea .txt-center {
    text-align: center;
  }

  .nfeArea .txt-right {
    text-align: right;
  }

  .nfeArea .nf-label {
    text-transform: uppercase;
    margin-bottom: 2px;
    display: block;
    margin-top: -10px;
  }

  .nfeArea .nf-label.label-small {
    letter-spacing: -0.5px;
    font-size: 4pt;
  }

  .nfeArea .info {
    font-weight: bold;
    font-size: 10.5pt;
    display: block;
    line-height: 0.8em;
  }

  .nfeArea table {
    font-family: "Times New Roman", serif;
    color: #000;
    font-size: 5pt;
    border-collapse: collapse;
    width: 100%;
    border-color: #000;
    border-radius: 10px !important;
  }

  .nfeArea .no-top {
    margin-top: -1px;
  }

  .nfeArea .mt-table {
    margin-top: 3px;
  }

  .nfeArea .valign-middle {
    vertical-align: middle;
  }

  .nfeArea td {
    vertical-align: top;
    box-sizing: border-box;
    overflow: hidden;
    border-color: #000;
    padding: 1px;
    height: 5mm;
  }

  .nfeArea .tserie {
    width: 32.2mm;
    vertical-align: middle;
    font-size: 8pt;
    font-weight: bold;
  }

  .nfeArea .tserie span {
    display: block;
  }

  .nfeArea .tserie h3 {
    display: inline-block;
  }

  .nfeArea .entradaSaida .legenda {
    text-align: left;
    margin-left: 2mm;
    margin-top: 4mm;
    display: block;
    position: relative;
  }

  .nfeArea .entradaSaida .legenda span {
    display: block;
  }

  .nfeArea .entradaSaida .identificacao {
    float: right;
    font-size: 16px;
    margin-right: 2mm;
    margin-top: -1mm;
    ;
    border: 1px solid black;
    width: 5mm;
    height: 7.6mm;
    text-align: center;
    font-weight: bold;
    padding-top: 1mm;
    line-height: 5mm;
    border-radius: 3px;
  }

  .nfeArea .hr-dashed {
    border: none;
    border-top: 1px dashed #444;
    margin: 5px 0;
  }

  .nfeArea .client_logo {
    height: 22mm;
    width: 70%;
    border-radius: 5px;
    position: relative;
    top: 10%;
    left: 15%;
  }

  .info-container {
    display: flex;
    flex-direction: column;
    /* This ensures the items are arranged vertically */
    align-items: flex-start;
    /* Align items to the start of the container */
  }

  .nfeArea .title {
    font-size: 13pt;
    margin-bottom: 2mm;
  }

  .nfeArea .txtc {
    text-align: center;
  }

  .nfeArea .pd-0 {
    padding: 0;
  }

  .nfeArea .mb2 {
    margin-bottom: 2mm;
  }

  .nfeArea table table {
    margin: -1pt;
    width: 100.5%;
  }

  .nfeArea .wrapper-table {
    margin-bottom: 2pt;
  }

  .nfeArea .wrapper-table table {
    margin-bottom: 0;
  }

  .nfeArea .wrapper-table table+table {
    margin-top: -1px;
  }

  .nfeArea .boxImposto {
    table-layout: fixed;
  }

  .nfeArea .boxImposto td {
    width: 11.11%;
  }

  .nfeArea .boxImposto .nf-label {
    font-size: 5pt;
  }

  .nfeArea .boxImposto .info {
    text-align: right;
  }

  .nfeArea .wrapper-border {
    border: 1px solid #000;
    border-width: 0 1px 1px;
    height: 75.7mm;
  }

  .nfeArea .wrapper-border table {
    margin: 0 -1px;
    width: 100.4%;
  }

  .nfeArea .content-spacer {
    display: block;
    height: 10px;
  }

  .nfeArea .titles th {
    padding: 3px 0;
    font-size: 9px;
  }

  .nfeArea .listProdutoServico td {
    padding: 0;
  }

  .nfeArea .codigo {
    display: block;
    text-align: center;
    margin-top: 5px;
  }

  .nfeArea .boxProdutoServico tr td:first-child {
    border-left: none;
  }

  .nfeArea .boxProdutoServico td {
    height: auto;
  }

  .nfeArea .boxFatura span {
    display: block;
  }

  .nfeArea .boxFatura td {
    border: 1px solid #000;
  }

  .nfeArea .freteConta .border {
    width: 5mm;
    height: 5mm;
    float: right;
    text-align: center;
    line-height: 5mm;
    border: 1px solid black;
  }

  .nfeArea .freteConta .info {
    line-height: 5mm;
  }

  .page .boxFields td p {
    font-family: "Times New Roman", serif;
    font-size: 5pt;
    line-height: 1.2em;
    color: #000;
  }

  .nfeArea .imgCanceled {
    position: absolute;
    top: 75mm;
    left: 30mm;
    z-index: 3;
    opacity: 0.8;
    display: none;
  }

  .nfeArea.invoiceCanceled .imgCanceled {
    display: block;
  }

  .nfeArea .imgNull {
    position: absolute;
    top: 75mm;
    left: 20mm;
    z-index: 3;
    opacity: 0.8;
    display: none;
  }

  .nfeArea.invoiceNull .imgNull {
    display: block;
  }

  .nfeArea.invoiceCancelNull .imgCanceled {
    top: 100mm;
    left: 35mm;
    display: block;
  }

  .nfeArea.invoiceCancelNull .imgNull {
    top: 65mm;
    left: 15mm;
    display: block;
  }

  .nfeArea .page-break {
    page-break-before: always;
  }

  .nfeArea .block {
    display: block;
  }

  .label-mktup {
    font-family: Arial !important;
    font-size: 8px !important;
    padding-top: 8px !important;
  }

  .right_col {
    padding-left: 5px !important;
    padding-right: 1px !important;
  }

  .divIdentEmitente {
    position: relative;
    align-self: center;
  }

  .classIdentEmite {
    position: relative;
    font-size: 11px;
    top: -7px;
    margin-top: -5px;
  }

  .font12 {
    font-size: 12px;
  }

  .font14 {
    font-size: 14px;
  }

  .font16 {
    font-size: 16px;
  }

  .font13 {
    font-size: 13px;
  }

  .font9 {
    font-size: 9px;
  }

  .font10 {
    font-size: 10px;
  }

  .snf,
  .nnf {
    padding: 0;
  }

  .barcode {
    padding: 0;
    display: flex;
    flex-direction: row;
    justify-content: center;
    height: 30px;
  }

  .bar {
    width: 3px;
    margin-right: 1px;
    background-color: black;
  }

  .bar2 {
    width: 4px;
    margin-right: 1px;
    background-color: black;
  }

  .bar3 {
    width: 5px;
    margin-right: 1px;
    background-color: black;
  }

  .space {
    width: 2px;
    margin-right: 1px;
    background-color: white;
  }

  .space2 {
    width: 1px;
    margin-right: 1px;
    background-color: white;
  }

  .infosProd {
    font-size: 12px;
  }

  /* Efeito de zoom e mudança de cor na linha inteira com gradiente */
  .trInfosProd {
    transition: transform 0.3s ease-in-out, background-image 0.3s ease-in-out;
  }

  .trInfosProd:hover {
    transform: scale(1.002);
    /* Leve aumento de tamanho */
    background: rgb(117, 251, 63);
    background: radial-gradient(circle, rgb(155, 247, 204) 43%, rgb(29, 25, 25) 180%);
  }

  .faturas {
    display: grid;
    grid-template-columns: repeat(7, 13.85%);
    /* 7 colunas com largura fixa */
    gap: 5px;
  }

  .rectangle {
    border: 1px solid #000;
    border-radius: 5px;
    padding: 2px;
    box-sizing: border-box;
    text-align: center;
    height: 45px;
  }

  .rectangle .line {
    display: flex;
    justify-content: space-between;
    margin: 1px 0;
    line-height: 12px;
    font-size: 10px;
  }

  .tab-pane {
    padding: 10px;
  }

  .form-control {
    border-radius: 5px;
  }

  .hidden {
    visibility: hidden;
  }

  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  input[type="number"] {
    -moz-appearance: textfield;
  }

  #myTabContent .form-control {
    font-size: 3.1mm;
    border-radius: 5px;
  }

  .input-group {
    margin-bottom: 0 !important;
  }

  #sCifrao {
    margin-top: 25px;
    margin-left: -12px;
  }

  .form-control-feedback {
    font-size: 12px !important;
    z-index: 999 !important;
  }
  #li_infos {
    font-family: FreeMono, monospace;
    float: right;
  }
  label{
    font-weight: unset;
  }
  .form-control{
    font-weight: bold !important;
  }
  #tableDisagreements>tbody>tr>td, #tableDisagreements>tbody>tr>th, #tableDisagreements>tfoot>tr>td, #tableDisagreements>tfoot>tr>th, #tableDisagreements>thead>tr>td, #tableDisagreements>thead>tr>th {
    padding: 2px;
    background-color: rgba(255, 179, 194, 0.796);
  }
  h5 {
    display: inline-block;
    margin: 0 auto;
    color: #fff;
    font-weight: bold;
    animation: mover 2s ease-in-out infinite;
    background-color: #030303;
    padding: 3px;
    border-radius: 10px;
    width: 120px;
    position: relative;
  }
  @keyframes mover {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.08);
    }
    100% {
        transform: scale(1);
    }
  }
  .tdDivergencias{
    font-size: 12px;
  }
</style>
<link rel="stylesheet" type="text/css" href="{$pathBib}/bib/sweetalert2/dist/sweetalert2.min.css" media="screen" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>
<script type="text/javascript" src="{$pathJs}/est/s_nota_xml_mostra.js"> </script>
<script type="text/javascript" src="{$pathBib}/bib/sweetalert2/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$bootstrap}/input_mask/jquery.inputmask.js"></script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="" id="AllNfe">
    <div class="clearfix"></div>
    <form name="upload" method="post" action={$SCRIPT_NAME} enctype="multipart/form-data">
      <input name=id type=hidden value={$id}>
      <input name=mod type=hidden value="est">
      <input name=form type=hidden value="nota_xml_importa">
      <input name=opcao type=hidden value="">
      <input name=submenu type=hidden value={$subMenu}>
      <input name=letra type=hidden value={$letra}>
      <input name=imagem type=hidden value={$imagem}>
      <input name=return_alter_product id=return_alter_product type=hidden value="{$return_alter_product}">
      <textArea id=xml_arq name=xml_arq style="display:none">{$xml_arq}</textArea>

      <div class="x_panel">
          <ul class="nav navbar-right panel_toolbox" id="btnsAcao">
              <li><button {if $xml_arq eq '' or $existeNotaFiscal eq '1'} readonly {/if} id="btnValidar" type="button" class="btn btn-primary"  onClick="javascript:submitValidarNf({$id});">
                <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span><span>&nbsp;&nbsp;Validar</span></button>
              </li>
              <li><button type="button" id="bnt_cadastrar" class="btn btn-success" onClick="javascript:submitCadastrar();">
                <span style="color:rgb(72, 72, 72);" class="glyphicon glyphicon-send" aria-hidden="true"></span><span style="color:rgb(72, 72, 72);">&nbsp;&nbsp;Emitir Nf-e</span></button>
              </li>
          </ul>
      </div>

      <!-- /Header -->
      <!-- Recebimentos -->
      <div class="page nfeArea">
        {if $divergencias neq null and $divergencias neq ''}
          <table id="tableDisagreements" class="table tableProd table-bordered" width="100%" style="border-radius:8px !important; border-collapse:inherit !important;">
            <tbody>
              <tr colspan="4" align="center">
                <td align="center" id="divergencia" colspan="4">
                  <h5>Divergências !</h5>
                </td>
              </tr>
              <tr>
                <td class="tdDivergencias" align="center">{$divergencias.erro}</td>
                <!-- SE O VALOR DO ID FOR FALSE SIGNIFICA QUE O ERRO SERA CORRIGIDO NA MESMA TELA -->
                {if $divergencias._form neq 'telaAtual'}
                  <td class="tdDivergencias" align="center"> {$divergencias._dica}</td>
                  <td class="tdDivergencias" align="center">
                    <input type="button" id="submitFornecedor" class="btn btn-xs btn-success" value="Ir para correção?" onClick="javascript:buttonOpen('{$pathCliente}', '{$divergencias._form}', '{$divergencias._mod}','{$divergencias._submenu}','{$divergencias._varControle}','{$divergencias._id}');">
                  </td>
                {else}
                  <td class="tdDivergencias" align="center"> {$divergencias._dica}</td>
                {/if}

              </tr>
            </tbody>
          </table>
        {/if}
        {* <img class="imgCanceled" src="tarja_nf_cancelada.png" alt="" />
        <img class="imgNull" src="tarja_nf_semvalidade.png" alt="" /> *}
        <div class="boxFields" style="padding-top: 1px;">
          {* <table cellpadding="0" cellspacing="0" border="1">
            <tbody>
              <tr>
                <td colspan="2" class="txt-upper">
                  Recebemos de [ds_company_issuer_name] os produtos e serviços constantes na nota fiscal indicada ao
                  lado
                </td>
                <td rowspan="2" class="tserie txt-center">
                  <span class="font-12" style="margin-bottom: 5px;">NF-e</span>
                  <span>Nº [nl_invoice]</span>
                  <span>Série [ds_invoice_serie]</span>
                </td>
              </tr>
              <tr>
                <td style="width: 32mm">
                  <span class="nf-label">Data de recebimento</span>
                </td>
                <td style="width: 124.6mm">
                  <span class="nf-label">Identificação de assinatura do Recebedor</span>
                </td>
              </tr>
            </tbody>
          </table> 
          <hr class="hr-dashed" /> *}
          <table cellpadding="0" cellspacing="0" border="1">
            <tbody>
              <tr>
                <td rowspan="5" style="width: 40rem;text-align: center;">
                  <div style="font-size:10px;margin-bottom:2px;margin-top:-2px;"><i>IDENTIFICAÇÃO DO EMITENTE</i></div>
                  <div class="info-container">
                    <img class="client_logo" src="images/logo.png" alt=""
                      onerror="javascript:this.src='data:image/png;base64,'" />
                    <div class="divIdentEmitente">
                      <div class="mb2 bold block" style="font-size: 16px;margin-top:8px;" name="nomeEmpresa"
                        id="nomeEmpresa">{$nomeEmpresa}</div>
                      <div class="block classIdentEmite" id="endereco">{$endereco} - {$enderecoNum} - {$enderecoCom}
                      </div>
                      <div class="block classIdentEmite">{$bairro} - {$cep}</div>
                      <div class="block classIdentEmite">{$cidade} - {$uf} - Fone: {$fone}</div>
                    </div>
                  </div>
                </td>
                <td rowspan="3" class="txtc txt-upper" style="width: 15rem; height: 29.5mm;">
                  <h3 class="title bold font1">Danfe</h3>
                  <p class="mb2" style="font-size: 9px;margin-top:-2px;">Documento auxiliar da Nota Fiscal Eletrônica
                  </p>
                  <p class="entradaSaida mb2">
                    <span class="identificacao">
                      <span>{$codigoOperacao}</span>
                    </span>
                    <span class="legenda font12">
                      <span style="line-height:1em;">0 - Entrada</span>
                      <span style="line-height:1em;">1 - Saída</span>
                    </span>
                  </p>
                  <p>
                    <span class="block bold nnf">
                      <span class="font12">Nº</span>
                      <span class="font12">{$numeroNf}</span>
                    </span>
                    <span class="block bold snf" style="margin-top: -15px;">
                      <span class="font12">SÉRIE:</span>
                      <span class="font12">{$serie}</span>
                    </span>
                    <span class="block" style="margin-top: -15px;">
                      <span style="font-size: 10px;"><i>Folha</i></span>
                      <span style="font-size: 10px;"><i>1</i></span>
                      <span style="font-size: 10px;"><i>de</i></span>
                      <span style="font-size: 10px;"><i>1</i></span>
                    </span>
                  </p>
                </td>
                <td class="txt-upper" style="width: 7rem;">
                  <span class="codigo" style="margin-top:11px;">
                    <div class="barcode">
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="space"></div>
                      <div class="bar2"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="bar"></div>
                      <div class="bar2"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar2"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar2"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar3"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="bar3"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar3"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="space"></div>
                      <div class="bar2"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="bar"></div>
                      <div class="bar2"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="space"></div>
                      <div class="bar2"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="bar"></div>
                      <div class="bar2"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="bar"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                      <div class="space1"></div>
                      <div class="bar3"></div>
                      <div class="bar"></div>
                      <div class="space"></div>
                    </div>
                  </span>
                </td>
              </tr>
              <tr>
                <td>
                  <span class="nf-label font9" style="margin-top:-9px;">CHAVE DE ACESSO</span>
                  <span class="bold block txt-center info">[ds_danfe]</span>
                </td>
              </tr>
              <tr>
                <td class="txt-center valign-middle" style="font-size: 11px;">
                  <span class="block" style="position: relative;">Consulta de autenticidade no portal nacional da NF-e
                  </span>
                  <span class="block" style="position: relative;margin-top:-15px;">www.nfe.fazenda.gov.br/portal ou no
                    site da Sefaz Autorizada.</span>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- Natureza da Operação -->
          <table cellpadding="0" cellspacing="0" class="boxNaturezaOperacao no-top" border="1">
            <tbody>
              <tr>
                <td>
                  <span class="nf-label font9">NATUREZA DA OPERAÇÃO</span>
                  <span class="info">{$descNaturezaOperacao}</span>
                </td>
                <td style="width: 84.7mm;">
                  <span class="nf-label font9">PROTOCOLO DE AUTORIZAÇÃO DE USO</span>
                  <span class="info">{$protocolo}</span>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Inscrição -->
          <table cellpadding="0" cellspacing="0" class="boxInscricao no-top" border="1">
            <tbody>
              <tr>
                <td>
                  <span class="nf-label font9">INSCRIÇÃO ESTADUAL</span>
                  <span class="info"><b>{$emitenteIe}</b></span>
                </td>
                <td style="width: 67.5mm;">
                  <span class="nf-label font9">INSCRIÇÃO ESTADUAL DO SUBST. TRIB.</span>
                  <span class="info"><b>{$emitenteIe}</b></span>
                </td>
                <td style="width: 64.3mm">
                  <span class="nf-label font9">CNPJ</span>
                  <span class="info"><b>{$emitenteCnpj}</b></span>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Destinatário/Emitente -->
          <p class="area-name">Destinatário/Emitente</p>
          <table cellpadding="0" cellspacing="0" class="boxDestinatario" border="1">
            <tbody>
              <tr>
                <td class="pd-0">
                  <table cellpadding="0" cellspacing="0" border="1">
                    <tbody>
                      <tr>
                        <td>
                          <span class="nf-label font9">NOME/RAZÃO SOCIAL</span>
                          <span class="info">{$destRazao}</span>
                        </td>
                        <td style="width: 40mm">
                          <span class="nf-label font9">CNPJ/CPF</span>
                          <span class="info">{$destCnpj}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td style="width: 20rem" style="align-items: center;">
                  <span class="nf-label font9">DATA DE EMISSÃO</span>
                  <span class="info">{$dataEmissao}</span>
                </td>
              </tr>
              <tr>
                <td class="pd-0">
                  <table cellpadding="0" cellspacing="0" border="1">
                    <tbody>
                      <tr>
                        <td>
                          <span class="nf-label font9">ENDEREÇO</span>
                          <span class="info">{$destEndereco}, {$destEnderecoNum}</span>
                        </td>
                        <td style="width: 47mm;">
                          <span class="nf-label font9">BAIRRO/DISTRITO</span>
                          <span class="info">{$destMunicipio}</span>
                        </td>
                        <td style="width: 37.2 mm">
                          <span class="nf-label font9">CEP</span>
                          <span class="info">{$destCep}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td>
                  <span class="nf-label font9">DATA DE ENTR./SAÍDA</span>
                  <span class="info">{$dataEntradaSaida}</span>
                </td>
              </tr>
              <tr>
                <td class="pd-0">
                  <table cellpadding="0" cellspacing="0" border="1">
                    <tbody>
                      <tr>
                        <td>
                          <span class="nf-label font9">MUNICÍPIO</span>
                          <span class="info">{$destMunicipio}</span>
                        </td>
                        <td style="width: 47mm;">
                          <span class="nf-label font9">FONE/FAX</span>
                          <span class="info">{$destFone}</span>
                        </td>
                        <td style="width: 37.2 mm">
                          <span class="nf-label font9">UF</span>
                          <span class="info">{$destUf}</span>
                        </td>
                        <td style="width: 37.2 mm">
                          <span class="nf-label font9">INSCRIÇÃO ESTADUAL</span>
                          <span class="info">{$destIe}</span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td>
                  <span class="nf-label font9">DATA DE ENTR./SAÍDA</span>
                  <span class="info">{$dataEntradaSaida}</span>
                </td>
              </tr>
              {* <tr>
              <td class="pd-0" style="height:20px;">
                <table cellpadding="0" cellspacing="0" style="margin-bottom: -1px; height:100%; width:100%;" border="1">
                  <tbody>
                    <tr>
                      <td>
                        <span class="nf-label">MUNICÍPIO</span>
                        <span class="info">{$destMunicipio}</span>
                      </td>
                      <td style="width: 34mm">
                        <span class="nf-label">FONE/FAX</span>
                        <span class="info">{$destFone}</span>
                      </td>
                      <td style="width: 28mm">
                        <span class="nf-label">UF</span>
                        <span class="info">{$destUf}</span>
                      </td>
                      <td style="width: 51mm">
                        <span class="nf-label">INSCRIÇÃO ESTADUAL</span>
                        <span class="info">{$destIe}</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
              <td style="width: 15rem; height: 40px;">
                <span class="nf-label">HORA ENTR./SAÍDA</span>
                <span id="info">{$dataEntradaSaida}</span>
              </td>
            </tr> *}

            </tbody>
          </table>

          {if $lancFinanceiro neq null}
            <!-- Fatura -->
            <div class="boxFatura">
              <p class="area-name font9"><b>Fatura/Duplicata &nbsp;</b> <b style="color: red;">(Os dados financeiros
                  apresentados não representam a fatura final)</b></p>
              <div class="faturas">
                {section name=f loop=$lancFinanceiro}
                  <div class="rectangle">
                    <div class="line"><span>Num.</span><span><b>{$lancFinanceiro[f]["PARCELA"]}</b></span></div>
                    <div class="line">
                      <span>Venc.</span><span><b>{$lancFinanceiro[f]["VENCIMENTO"]|date_format:"%d/%m/%Y"}</b></span>
                    </div>
                    <div class="line"><span>Valor</span><span><b>{$lancFinanceiro[f]["VALOR"]}</b></span></div>
                  </div>
                {/section}
              </div>
            </div>
          {/if}


          {if $arrayImpostos neq null}
            <!-- Calculo do Imposto -->
            <p class="area-name">Calculo do imposto</p>
            <div class="wrapper-table">
              <table cellpadding="0" cellspacing="0" border="1" class="boxImposto">
                <tbody>
                  <tr>
                    <td>
                      <span class="nf-label label-small">BASE DE CÁLC. DO ICMS</span>
                      <span class="info">[tot_bc_icms]</span>
                    </td>
                    <td>
                      <span class="nf-label">VALOR DO ICMS</span>
                      <span class="info">[tot_icms]</span>
                    </td>
                    <td>
                      <span class="nf-label label-small" style="font-size: 4pt;">BASE DE CÁLC. DO ICMS ST</span>
                      <span class="info">[tot_bc_icms_st]</span>
                    </td>
                    <td>
                      <span class="nf-label">VALOR DO ICMS ST</span>
                      <span class="info">[tot_icms_st]</span>
                    </td>
                    <td>
                      <span class="nf-label label-small">V. IMP. IMPORTAÇÃO</span>
                      <span class="info"></span>
                    </td>
                    <td>
                      <span class="nf-label label-small">V. ICMS UF REMET.</span>
                      <span class="info"></span>
                    </td>
                    <td>
                      <span class="nf-label">VALOR DO FCP</span>
                      <span class="info">[tot_icms_fcp]</span>
                    </td>
                    <td>
                      <span class="nf-label">VALOR DO PIS</span>
                      <span class="info"></span>
                    </td>
                    <td>
                      <span class="nf-label label-small">V. TOTAL DE PRODUTOS</span>
                      <span class="info">[vl_total_prod]</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span class="nf-label">VALOR DO FRETE</span>
                      <span class="info">[vl_shipping]</span>
                    </td>
                    <td>
                      <span class="nf-label">VALOR DO SEGURO</span>
                      <span class="info">[vl_insurance]</span>
                    </td>
                    <td>
                      <span class="nf-label">DESCONTO</span>
                      <span class="info">[vl_discount]</span>
                    </td>
                    <td>
                      <span class="nf-label">OUTRAS DESP.</span>
                      <span class="info">[vl_other_expense]</span>
                    </td>
                    <td>
                      <span class="nf-label">VALOR DO IPI</span>
                      <span class="info">[tot_total_ipi_tax]</span>
                    </td>
                    <td>
                      <span class="nf-label">V. ICMS UF DEST.</span>
                      <span class="info"></span>
                    </td>
                    <td>
                      <span class="nf-label label-small">V. APROX. DO TRIBUTO</span>
                      <span class="info">{$ApproximateTax}</span>
                    </td>
                    <td>
                      <span class="nf-label label-small">VALOR DA CONFINS</span>
                      <span class="info"></span>
                    </td>
                    <td>
                      <span class="nf-label label-small">V. TOTAL DA NOTA</span>
                      <span class="info">[vl_total]</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          {/if}

          {if $arrayTransp neq null}
            <!-- Transportador/Volumes transportados -->
            <p class="area-name">Transportador/volumes transportados</p>
            <table cellpadding="0" cellspacing="0" border="1">
              <tbody>
                <tr>
                  <td>
                    <span class="nf-label font9">RAZÃO SOCIAL</span>
                    <span class="info">{$arrayTransp[0]["NOME"]}</span>
                  </td>
                  <td class="freteConta" style="width: 32mm">
                    <span class="nf-label font9">FRETE</span>
                    <span class="info">{$frete}</span>
                  </td>
                  <td style="width: 17.3mm">
                    <span class="nf-label font9">CÓDIGO ANTT</span>
                    <span class="info">{$codAntt}</span>
                  </td>
                  <td style="width: 24.5mm">
                    <span class="nf-label font9">PLACA</span>
                    <span class="info">{$arrayNotaFiscal[0]["PLACAVEICULO"]}</span>
                  </td>
                  <td style="width: 11.3mm">
                    <span class="nf-label font9">UF</span>
                    <span class="info">{$arrayTransp[0]["UF"]}</span>
                  </td>
                  <td style="width: 29.5mm">
                    <span class="nf-label">CNPJ/CPF</span>
                    <span class="info">{$arrayTransp[0]["CNPJCPF"]}</span>
                  </td>
                </tr>
              </tbody>
            </table>

            <table cellpadding="0" cellspacing="0" border="1" class="no-top">
              <tbody>
                <tr>
                  <td class="field endereco">
                    <span class="nf-label font9">ENDEREÇO</span>
                    <span class="content-spacer info">{$arrayTransp[0]["ENDERECO"]}</span>
                  </td>
                  <td style="width: 32mm">
                    <span class="nf-label">MUNICÍPIO</span>
                    <span class="info">{$arrayTransp[0]["CIDADE"]}</span>
                  </td>
                  <td style="width: 31mm">
                    <span class="nf-label font9">UF</span>
                    <span class="info">{$arrayTransp[0]["UF"]}</span>
                  </td>
                  <td style="width: 51.4mm">
                    <span class="nf-label font9">INSC. ESTADUAL</span>
                    <span class="info">{$arrayTransp[0]["INSCESTRG"]}</span>
                  </td>
                </tr>
              </tbody>
            </table>
            <table cellpadding="0" cellspacing="0" border="1" class="no-top">
              <tbody>
                <tr>
                  <td class="field quantidade">
                    <span class="nf-label font9">QUANTIDADE</span>
                    <span class="content-spacer info">{$arrayNotaFiscal[0]["VOLUME"]}</span>
                  </td>
                  <td style="width: 31.4mm">
                    <span class="nf-label font9">ESPÉCIE</span>
                    <span class="info">{$arrayNotaFiscal[0]["VOLESPECIE"]}</span>
                  </td>
                  <td style="width: 31mm">
                    <span class="nf-label font9">MARCA</span>
                    <span class="info">{$arrayNotaFiscal[0]["VOLMARCA"]}</span>
                  </td>
                  <td style="width: 31.5mm">
                    <span class="nf-label font9">NUMERAÇÃO</span>
                    <span class="info">{$arrayNotaFiscal[0]["NUMERACAO"]}</span>
                  </td>
                  <td style="width: 31.5mm">
                    <span class="nf-label font9">PESO BRUTO</span>
                    <span class="info">{$arrayNotaFiscal[0]["VOLPESOBRUTO"]}</span>
                  </td>
                  <td style="width: 32.5mm">
                    <span class="nf-label font9">PESO LÍQUIDO</span>
                    <span class="info">{$arrayNotaFiscal[0]["VOLPESOLIQ"]}</span>
                  </td>
                </tr>
              </tbody>
            </table>
          {/if}

          <!-- Dados do produto/serviço -->
          <p class="area-name">Dados do produto/serviço</p>
          <div class="wrapper-border">
            <table cellpadding="0" cellspacing="0" border="1" class="boxProdutoServico">
              <thead class="listProdutoServico" id="table">
                <tr class="titles">
                  <th class="cod" style="width: 6%;text-align:center;">CÓDIGO</th>
                  <th class="descrit" style="text-align:center;">DESCRIÇÃO DO PRODUTO/SERVIÇO</th>
                  <th class="ncmsh" style="width: 4%;text-align:center;">NCM/SH</th>
                  <th class="cst" style="width: 3%;text-align:center;">CST</th>
                  <th class="cfop" style="width: 3%;text-align:center;">CFOP</th>
                  <th class="un" style="width: 2%;text-align:center;">UN</th>
                  <th class="amount" style="width: 5%;text-align:center;">QTD.</th>
                  <th class="valUnit" style="width: 5%;text-align:center;">VLR.UNIT</th>
                  <th class="valTotal" style="width: 5%;text-align:center;">VLR.TOTAL</th>
                  <th class="bcIcms" style="width: 5%;text-align:center;">BC ICMS</th>
                  <th class="valIcms" style="width: 5%;text-align:center;">VLR.ICMS</th>
                  <th class="valIpi" style="width: 5%;text-align:center;">VLR.IPI</th>
                  <th class="aliqIcms" style="width: 5%;text-align:center;">ALIQ.ICMS</th>
                  <th class="aliqIpi" style="width: 4%;text-align:center;">ALIQ.IPI</th>
                </tr>
              </thead>
              <tbody>
                {section name=i loop=$lanc}
                <tr class="trInfosProd" {if $divergencias._id eq $lanc[i].ID} style="background:rgb(233, 126, 126);" {/if} id="{$lanc[i].ID}" onclick="showProductInfo(this)">
                    <td class="infosProd" id="codigo" style="text-align: center;">{$lanc[i].CODPRODUTO} </td>
                    <td class="infosProd" id="descricao"> {$lanc[i].DESCRICAO} </td>
                    <td class="infosProd" id="ncm" style="text-align: center;"> {$lanc[i].NCM} </td>
                    <td class="infosProd" id="origem" style="text-align: center;"> {$lanc[i].ORIGEM}{$lanc[i].TRIBICMS}
                    </td>
                    <td class="infosProd" id="cfop" style="text-align: center;"> {$lanc[i].CFOP} </td>
                    <td class="infosProd" id="unidade" style="text-align: center;"> {$lanc[i].UNIDADE} </td>
                    <td class="infosProd" id="quantidade" style="text-align: center;"> {$lanc[i].QUANT|number_format:2:",":"."} </td>
                    
                    <td class="infosProd" id="valorUnitario" style="text-align: center;">
                      {$lanc[i].UNITARIO|number_format:2:",":"."} </td>
                    <td class="infosProd" id="valorTotal" style="text-align: center;">
                      {$lanc[i].TOTAL|number_format:2:",":"."} </td>
                    <td class="infosProd" id="bcIcms" style="text-align: center;">
                      {$lanc[i].BCICMS|number_format:2:",":"."} </td>
                    <td class="infosProd" id="valIcms" style="text-align: center;">
                      {$lanc[i].VALORICMS|number_format:2:",":"."} </td>
                    <td class="infosProd" id="valorIpi" style="text-align: center;">
                      {$lanc[i].VALORIPI|number_format:2:",":"."} </td>
                    <td class="infosProd" id="aliqIcms" style="text-align: center;">
                      {$lanc[i].ALIQICMS|number_format:2:",":"."} </td>
                    <td class="infosProd" id="aliqIpi" style="text-align: center;">
                      {$lanc[i].ALIQIPI|number_format:2:",":"."} </td>
                    <!-- campos hidden -->
                    <!-- produto -->
                    <td class="hidden" id="idProd"> {$lanc[i].ID} </td>
                    <td class="hidden" id="numeroSerie"> {$lanc[i].NRSERIE} </td>
                    <td class="hidden" id="osParceiro"> {$lanc[i].ORDEM} </td>
                    <td class="hidden" id="cest"> {$lanc[i].CEST} </td>
                    <td class="hidden" id="codigoBeneficio"> {$lanc[i].CBENEF} </td>
                    <td class="hidden" id="desconto"> {$lanc[i].DESCONTO|number_format:2:",":"."} </td>
                    <td class="hidden" id="lote"> {$lanc[i].LOTE} </td>
                    <td class="hidden" id="dataFabricacao"> {$lanc[i].DATAFABRICACAO} </td>
                    <td class="hidden" id="dataValidade"> {$lanc[i].DATAVALIDADE} </td>
                    <td class="hidden" id="dataGarantia"> {$lanc[i].DATAGARANTIA} </td>
                    <td class="hidden" id="frete"> {$lanc[i].FRETE} </td>
                    <td class="hidden" id="despAcessorias"> {$lanc[i].DESPACESSORIAS} </td>
                    <td class="hidden" id="datafabricacao"> {$lanc[i].DATAFABRICACAO} </td>
                    <!-- icms -->
                    <td class="hidden" id="tribIcms"> {$lanc[i].TRIBICMS} </td>
                    <td class="hidden" id="modBc"> {$lanc[i].MODBC} </td>
                    <td class="hidden" id="percReducaoBc"> {$lanc[i].PERCREDUCAOBC|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorIcmsOperacao"> {$lanc[i].VALORICMSOPERACAO|number_format:2:",":"."} </td>
                    <td class="hidden" id="percDiferido"> {$lanc[i].PERCDIFERIDO|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorIcmsDiferido"> {$lanc[i].VALORICMSDIFERIDO|number_format:2:",":"."} </td>
                    <td class="hidden" id="modBcSt"> {$lanc[i].MODBCST} </td>
                    <td class="hidden" id="valorBcSt"> {$lanc[i].VALORBCST|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqIcmsSt"> {$lanc[i].ALIQICMSST|number_format:2:",":"."} </td>
                    <td class="hidden" id="percReducaoBcSt"> {$lanc[i].PERCREDUCAOBCST|number_format:2:",":"."} </td>
                    <td class="hidden" id="percMvaSt"> {$lanc[i].PERCMVAST|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorIcmsSt"> {$lanc[i].VALORICMSST|number_format:2:",":"."} </td>
                    <td class="hidden" id="bcFcpSt"> {$lanc[i].BCFCPST|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqFcpSt"> {$lanc[i].ALIQFCPST|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorFcpSt"> {$lanc[i].VALORFCPST|number_format:2:",":"."} </td>
                    <td class="hidden" id="bcFcpUfDest"> {$lanc[i].BCFCPUFDEST|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqFcpUfDest"> {$lanc[i].ALIQFCPUFDEST|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorFcpUfDest"> {$lanc[i].VALORFCPUFDEST|number_format:2:",":"."} </td>
                    <td class="hidden" id="bcIcmsUfDest"> {$lanc[i].BCICMSUFDEST|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqIcmsUfDest"> {$lanc[i].ALIQICMSUFDEST|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorIcmsUfDest"> {$lanc[i].VALORICMSUFDEST|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqIcmsInter"> {$lanc[i].ALIQICMSINTER|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqIcmsInterPart"> {$lanc[i].ALIQICMSINTERPART|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorIcmsUfRemet"> {$lanc[i].VALORICMSUFREMET|number_format:2:",":"."} </td>
                    <!-- ipi/pis/cofins -->
                    <td class="hidden" id="cstIpi"> {$lanc[i].CSTIPI} </td>
                    <td class="hidden" id="bcIpi"> {$lanc[i].BCIPI|number_format:2:",":"."} </td>
                    <td class="hidden" id="cstPis"> {$lanc[i].CSTPIS} </td>
                    <td class="hidden" id="bcPis"> {$lanc[i].BCPIS|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqPis"> {$lanc[i].ALIQPIS|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorPis"> {$lanc[i].VALORPIS|number_format:2:",":"."} </td>
                    <td class="hidden" id="cstCofins"> {$lanc[i].CSTCOFINS} </td>
                    <td class="hidden" id="bcCofins"> {$lanc[i].BCCOFINS|number_format:2:",":"."} </td>
                    <td class="hidden" id="aliqCofins"> {$lanc[i].ALIQCOFINS|number_format:2:",":"."} </td>
                    <td class="hidden" id="valorCofins"> {$lanc[i].VALORCOFINS|number_format:2:",":"."} </td>
                  </tr>
                {/section}
              </tbody>
            </table>
          </div>

          <!-- Calculo de ISSQN -->
          <p class="area-name">Calculo do issqn</p>
          <table cellpadding="0" cellspacing="0" border="1" class="boxIssqn">
            <tbody>
              <tr>
                <td class="field inscrMunicipal">
                  <span class="nf-label">INSCRIÇÃO MUNICIPAL</span>
                  <span class="info txt-center">[ds_company_im]</span>
                </td>
                <td class="field valorTotal">
                  <span class="nf-label">VALOR TOTAL DOS SERVIÇOS</span>
                  <span class="info txt-right">[vl_total_serv]</span>
                </td>
                <td class="field baseCalculo">
                  <span class="nf-label">BASE DE CÁLCULO DO ISSQN</span>
                  <span class="info txt-right">[tot_bc_issqn]</span>
                </td>
                <td class="field valorIssqn">
                  <span class="nf-label">VALOR DO ISSQN</span>
                  <span class="info txt-right">[tot_issqn]</span>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Dados adicionais -->
          <p class="area-name">Dados adicionais</p>
          <table cellpadding="0" cellspacing="0" border="1" class="boxDadosAdicionais">
            <tbody>
              <tr>
                <td class="field infoComplementar">
                  <span class="nf-label">INFORMAÇÕES COMPLEMENTARES</span>
                  <span>[ds_additional_information]</span>
                </td>
                <td class="field reservaFisco" style="width: 85mm; height: 24mm">
                  <span class="nf-label">RESERVA AO FISCO</span>
                  <span></span>
                </td>
              </tr>
            </tbody>
          </table>

          <footer>
            <table cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="text-align: right"><strong>Empresa de Software www.empresa.com</strong></td>
                </tr>
              </tbody>
            </table>
          </footer>
        </div>
        [page-break]
      </div>
    </form>

  </div> <!-- id=AllNfe -->
</div> <!-- class="right_col" -->

{include file="template/form.inc"}

<!-- Modal Edit Produto-->
<div class="modal fade" id="produtoModal" tabindex="-1" aria-labelledby="produtoModalLabel" aria-hidden="true"
  data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content modal-dialog-centered">

      <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab_content_produto" id="tab_content_produto-tab" role="tab"
            data-toggle="tab" aria-expanded="true">Produto</a>
        </li>
        <li role="presentation" class=""><a href="#tab_content_icms" role="tab" id="tab_content_icms-tab"
            data-toggle="tab" aria-expanded="false">ICMS</a>
        </li>
        <li role="presentation" class=""><a href="#tab_content_ipi" role="tab" id="tab_content_ipi-tab"
            data-toggle="tab" aria-expanded="false">IPI/PIS/CONFINS</a>
        </li>
        
        <p id="li_infos"></p>
        
      </ul>
      <div id="myTabContent" class="tab-content">
        <input name="m_idProd" id="m_idProd" type=hidden value={$idProd}>
        <div role="tabpanel" class="tab-pane fade active in small" id="tab_content_produto"
          aria-labelledby="tab_content_produto-tab">

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="input-group input-group-sm col-md-12 col-sm-12 col-xs-12">
                <label for="m_produto">Produto</label>
                <input class="form-control" id="m_produto" name="m_produto" maxlength="100" type="text" value="" readonly>
              </div>
            </div>
          </div> <!-- FIM row -->

          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_codProduto">Código produto</label>
                <input class="form-control" readonly type="number" id="m_codProduto" name="m_codProduto" value="" readonly>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_unidade">Unidade</label>
                <input class="form-control" id="m_unidade" name="m_unidade" type="text" maxlength="3" value="" readonly>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_serie">Número de Série</label>
                <input class="form-control" id="m_serie" name="m_serie" type="text" maxlength="25" value="">
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_ordemServico">O.S. Parceiro</label>
                <input class="form-control" id="m_ordemServico" name="m_ordemServico" type="text" maxlength="20" value="">
              </div>
            </div>
          </div> <!-- FIM row -->

          <div class="row">
            <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_cfop">CFOP</label>
                <input class="form-control" id="m_cfop" name="m_cfop" type="number" value=""
                  onKeyPress="if(this.value.length==11) return false;">
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_ncm">NCM</label>
                <input class="form-control" id="m_ncm" name="m_ncm" type="number" value=""
                  onKeyPress="if(this.value.length==15) return false;">
              </div>
            </div>
            <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_cest">CEST</label>
                <input class="form-control" id="m_cest" name="m_cest" type="number" value=""
                  onKeyPress="if(this.value.length==15) return false;">
              </div>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_cbenef">Código Benefício</label>
                <select class="form-control input" id="m_cbenef" name="m_cbenef">
                  {html_options values=$m_cbenef_ids selected=$m_cbenef output=$m_cbenef_names}
                </select>
              </div>
            </div>
          </div> <!-- FIM row -->

          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_quantidade">Quantidade</label>
                <input class="form-control money" id="m_quantidade" name="m_quantidade" type="money" maxlength="9" value="" readonly onblur="soma()">
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorUnitario">Valor Unitário</label>
                <input class="form-control moneyUnitario has-feedback-left" id="m_valorUnitario" name="m_valorUnitario"
                  type="money" maxlength="14" value="" readonly onblur="soma()">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_desconto">Desconto</label>
                <input class="form-control money has-feedback-left" id="m_desconto" name="m_desconto" type="money"
                  maxlength="14" value="" readonly onblur="soma()">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_total">Total Produto</label>
                <input class="form-control money has-feedback-left" id="m_total" name="m_total" type="money"
                  maxlength="11" value="" readonly onblur="soma()">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div> <!-- FIM row -->

          <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-2">
              <div class="form-group input-group input-group-sm">
                <label for="m_lote">Lote</label>
                <input class="form-control" id="m_lote" name="m_lote" type="text" maxlength="30" value="{$lote}">
              </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2">
              <div class="form-group input-group input-group-sm">
                <label for="m_dataFabricacao">Data Fabricação</label>
                <input class="form-control masked-date" id="m_dataFabricacao" name="m_dataFabricacao" type="text" value="">
              </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2">
              <div class="form-group input-group input-group-sm">
                <label for="m_dataValidade">Data Validade</label>
                <input class="form-control masked-date" id="m_dataValidade" name="m_dataValidade" type="text" value="">
              </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2">
              <div class="form-group input-group input-group-sm">
                <label for="m_dataGarantia">Data Garantia</label>
                <input class="form-control masked-date" id="m_dataGarantia" name="m_dataGarantia" type="text" value="" onchange="">
              </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2">
              <div class="form-group input-group input-group-sm">
                <label for="m_frete">Frete</label>
                <input class="form-control money has-feedback-left" id="m_frete" name="m_frete" type="money" value=""
                  disabled>
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-2 col-sm-2 col-xs-2">
              <div class="form-group input-group input-group-sm">
                <label for="m_despAcessorias">Despesas Acessórias</label>
                <input class="form-control money has-feedback-left" id="m_despAcessorias" name="m_despAcessorias"
                  type="money" value="" disabled>
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div> <!-- FIM row -->

        </div>

        <div role="tabpanel" class="tab-pane fade small" id="tab_content_icms" aria-labelledby="tab_content_icms-tab">


          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="form-group input-group input-group-sm">
                <label for="m_origem" title="<orig>">Origem</label>
                <select class="form-control input" id="m_origem" name="m_origem">
                  {html_options values=$m_origem_ids selected=$m_origem output=$m_origem_names}
                </select>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-8 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_tribIcms" title="<ICMS>">Tributação ICMS / CSOSN</label>
                <select class="form-control input" id="m_tribIcms" name="m_tribIcms">
                  {html_options values=$m_tribIcms_ids selected=$m_tribIcms output=$m_tribIcms_names}
                </select>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_modBc" title="<modBC>">Modalidade</label>
                <select class="form-control" id="m_modBc" name="m_modBc"
                  title="Modalidade de determinação da BC do ICMS.">
                  {html_options values=$m_modBc_ids selected=$m_modBc output=$m_modBc_names}
                </select>
              </div>
            </div>
          </div> <!-- FIM row -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_bcIcms" title="<vBC>">Base Cálculo ICMS</label>
                <input class="form-control money has-feedback-left" id="m_bcIcms" name="m_bcIcms" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqIcms" title="<pICMS>">Alíquota ICMS</label>
                <input class="form-control money has-feedback-left" id="m_aliqIcms" name="m_aliqIcms" type="money"
                  maxlength="5" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorIcms" title="<vICMS>">Valor ICMS</label>
                <input class="form-control money has-feedback-left" id="m_valorIcms" name="m_valorIcms" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_percReducaoBc" title="<pRedBC>">Alíquota da Redução de BC</label>
                <input class="form-control money has-feedback-left" id="m_percReducaoBc" name="m_percReducaoBc"
                  type="money" maxlength="5" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorIcmsOperacao" title="<pRedBC>">Valor ICMS da Operação</label>
                <input class="form-control money has-feedback-left" id="m_valorIcmsOperacao" name="m_valorIcmsOperacao"
                  type="money" maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_percDiferimento" title="<pDif>">Alíquota do Diferimento</label>
                <input class="form-control money has-feedback-left" id="m_percDiferimento" name="m_percDiferimento"
                  type="money" maxlength="5" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorIcmsDiferimento" title="<vICMSDif>">Valor do ICMS Diferido</label>
                <input class="form-control money has-feedback-left" id="m_valorIcmsDiferimento"
                  name="m_valorIcmsDiferimento" type="money" maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->


          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_modBcSt" title="<modBCST>">Modalidade ST</label>
                <select class="form-control" id="m_modBcSt" name="m_modBcSt"
                  title="Modalidade de determinação da BC do ICMS ST">
                  {html_options values=$m_modBcSt_ids selected=$m_modBcSt output=$m_modBcSt_names}
                </select>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_percMvaSt" title="<pMVAST> Percentual da margem de valor Adicionado(MVA) do ICMS ST">Percentual MVA do ICMS ST</label>
                <input class="form-control money has-feedback-left" id="m_percMvaSt" name="m_percMvaSt" type="money"
                  maxlength="6" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_percReducaoBcSt" title="<pRedBCST>">Percentual da Redução de BC do ICMS ST</label>
                <input class="form-control money has-feedback-left" id="m_percReducaoBcSt" name="m_percReducaoBcSt"
                  type="money" maxlength="5" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorBcSt" title="<vBCST>">Valor da BC do ICMS ST</label>
                <input class="form-control money has-feedback-left" id="m_valorBcSt" name="m_valorBcSt" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqIcmsSt" title="<pICMSST>">Alíquota do imposto do ICMS ST</label>
                <input class="form-control money has-feedback-left" id="m_aliqIcmsSt" name="m_aliqIcmsSt" type="money"
                  maxlength="5" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorIcmsSt" title="<vICMSST>">Valor do ICMS ST</label>
                <input class="form-control money has-feedback-left" id="m_valorIcmsSt" name="m_valorIcmsSt" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_bcFcpSt" title="<vBCFCPST>">Valor da BC do FCP retido por ST</label>
                <input class="form-control money has-feedback-left" id="m_bcFcpSt" name="m_bcFcpSt" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqFcpSt" title="<pFCPST>">Percentual do FCP retido por ST</label>
                <input class="form-control money has-feedback-left" id="m_aliqFcpSt" name="m_aliqFcpSt" type="money"
                  maxlength="7" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorFcpSt" title="<pFCPST>">Valor do FCP retido por ST</label>
                <input class="form-control money has-feedback-left" id="m_valorFcpSt" name="m_valorFcpSt" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_bcFcpUfDest" title="<vBCFCPUFDest>">Valor da BC FCP na UF de Destino</label>
                <input class="form-control money has-feedback-left" id="m_bcFcpUfDest" name="m_bcFcpUfDest" type="money"
                  maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqFcpUfDest" title="<pFCPUFDest>">Percentual do ICMS relativo ao (FCP) na UF de destino</label>
                <input class="form-control money has-feedback-left" id="m_aliqFcpUfDest" name="m_aliqFcpUfDest"
                  type="money" maxlength="9" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorFcpUfDest" title="<vFCPUFDest>">Valor do ICMS Relativo ao FCP da UF de
                  Destino</label>
                <input class="form-control money has-feedback-left" id="m_valorFcpUfDest" name="m_valorFcpUfDest"
                  type="money" maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_BcIcmsUfDest" title="<vBCUFDest>">Valor da BC do ICMS na UF de Destino</label>
                <input class="form-control money has-feedback-left" id="m_BcIcmsUfDest" name="m_BcIcmsUfDest"
                  type="money" value="" maxlength="11">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqIcmsUfDest" title="<pICMSUFDest>">Alíquota Interna da UF de Destino</label>
                <input class="form-control money has-feedback-left" id="m_aliqIcmsUfDest" name="m_aliqIcmsUfDest"
                  type="money" maxlength="9" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorIcmsUfDest" title="<vICMSUFDest>">Valor do ICMS Interestadual para a UF de
                  Destino</label>
                <input class="form-control money has-feedback-left" id="m_valorIcmsUfDest" name="m_valorIcmsUfDest"
                  type="money" maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

          <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqIcmsInter" title="<pICMSInter>">Alíquota Interestadual das UF Envolvidas</label>
                <input class="form-control money has-feedback-left" id="m_aliqIcmsInter" name="m_aliqIcmsInter"
                  type="money" maxlength="9" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_aliqIcmsInterPart" title="<pICMSInterPart>">Percentual provisório de partilha do ICMS Interestadual</label>
                <input class="form-control money has-feedback-left" id="m_aliqIcmsInterPart" name="m_aliqIcmsInterPart"
                  type="money" maxlength="9" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-6">
              <div class="form-group input-group input-group-sm">
                <label for="m_valorIcmsUfRemet" title="<vICMSUFRemet>">Valor do ICMS Inter para a UF do
                  Remetente</label>
                <input class="form-control money has-feedback-left" id="m_valorIcmsUfRemet" name="m_valorIcmsUfRemet"
                  type="money" maxlength="11" value="">
                <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
              </div>
            </div>
          </div><!-- FIM row -->

        </div> <!-- tabpanel -->

        <div role="tabpanel" class="tab-pane fade small" id="tab_content_ipi" aria-labelledby="tab_content_ipi-tab">
          <div class="form-group">
            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_cstIpi" title="<CST>">CST IPI</label>
                  <select class="form-control" id="m_cstIpi" name="m_cstIpi">
                    {html_options values=$m_cstIpi_ids selected=$m_cstIpi output=$m_cstIpi_names}
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_bcIpi" title="<vBC>">Valor da BC do IPI</label>
                  <input class="form-control money has-feedback-left" id="m_bcIpi" name="m_bcIpi" type="money"
                    maxlength="11" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_aliqIpi" title="<pIPI>">Alíquota IPI</label>
                  <input class="form-control money has-feedback-left" id="m_aliqIpi" name="m_aliqIpi" type="money"
                    maxlength="5" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_valorIpi" title="<vIPI>">Valor IPI</label>
                  <input class="form-control money has-feedback-left" id="m_valorIpi" name="m_valorIpi" type="money"
                    maxlength="11" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
                </div>
              </div>
            </div><!-- FIM row -->

            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_cstPis" title="<CST>">CST PIS</label>
                  <select class="form-control" id="m_cstPis" name=m_cstPis>
                    {html_options values=$m_cstPis_ids selected=$m_cstPis output=$m_cstPis_names}
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_bcPis" title="<vBC>">Valor da BC do PIS </label>
                  <input class="form-control money has-feedback-left" id="m_bcPis" name="m_bcPis" type="money"
                    maxlength="11" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_aliqPis" title="<pPIS>">Alíquota PIS</label>
                  <input class="form-control money has-feedback-left" id="m_aliqPis" name="m_aliqPis" type="money"
                    maxlength="5" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_valorPis" title="<vPIS>">Valor PIS</label>
                  <input class="form-control money has-feedback-left" id="m_valorPis" name="m_valorPis" type="money"
                    maxlength="11" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
                </div>
              </div>
            </div><!-- FIM row -->

            <div class="row">
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_cstCofins" title="<CST>">CST COFINS</label>
                  <select class="form-control" id="m_cstCofins" name=m_cstCofins>
                    {html_options values=$m_cstCofins_ids selected=$m_cstCofins output=$m_cstCofins_names}
                  </select>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_bcCofins" title="<vBC>">Valor da BC COFINS</label>
                  <input class="form-control money has-feedback-left" id="m_bcCofins" name="m_bcCofins" type="money"
                    maxlength="11" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_aliqCofins" title="<pCOFINS>">Alíquota da COFINS</label>
                  <input class="form-control money has-feedback-left" id="m_aliqCofins" name="m_aliqCofins" type="money"
                    maxlength="5" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>&#37;</b></span>
                </div>
              </div>
              <div class="col-md-3 col-sm-6 col-xs-6">
                <div class="form-group input-group input-group-sm">
                  <label for="m_valorCofins" title="<vCOFINS>">Valor da COFINS</label>
                  <input class="form-control money has-feedback-left" id="m_valorCofins" name="m_valorCofins"
                    type="money" maxlength="11" value="">
                  <span class="form-control-feedback left" aria-hidden="true" id="sCifrao"><b>R$</b></span>
                </div>
              </div>
            </div><!-- FIM row -->


          </div>
        </div> <!-- tabpanel -->

        <div class="modal-footer">
          <div class="col-md-12 col-sm-12 col-xs-12">
            <button type="button" class="btn btn-secondary" id="btnCancelar" onclick="javascript:limparCampos()" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" onclick="javascript:updateProduct()">Salvar</button>
          </div>
        </div>

      </div> <!-- FIM myTabContent -->
    </div> <!-- FIM class="modal-content modal-dialog-centered"-->
  </div> <!-- FIM class="modal-dialog modal-lg" -->
</div> <!-- FIM id="produtoModal" -->


<!-- PACOTES -->
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>
<script>
  $(document).ready(function() {
    $(".moneyUnitario").maskMoney({
      decimal: ",",
      thousands: ".",
      allowZero: true,
      precision: 4
    });
  });
</script>
<script>
  $(document).ready(function() {
    $(".money").maskMoney({
      decimal: ",",
      thousands: ".",
      allowZero: true,
    });
  });
</script>

<!-- OBSERVACOES
Utilizado pois tem melhor comportamento com campos tipo NUMBER
* onKeyPress="if(this.value.length==11) return false;"