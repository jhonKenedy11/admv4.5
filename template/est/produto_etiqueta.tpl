<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Etiqueta Produto</title>
  <style>
    @page {
      /* Etiqueta 10cm x 3cm (100mm x 30mm) */
      size: 100mm 30mm;
      margin: 0;
    }
    html, body {
      width: 100mm;
      height: 30mm;
      margin: 0;
      padding: 0;
      background: #fff;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
      font-family: Arial, Helvetica, sans-serif;
      -webkit-text-size-adjust: 100%;
    }
    * { box-sizing: border-box; }
    .sheet {
      width: 100mm;
      height: 30mm;
      /* top, right, bottom, left */
      padding: 2.5mm 1.2mm 0 1mm;
      overflow: hidden;
    }
    .page-break {
      break-after: page;
      page-break-after: always;
      height: 0;
    }
    .top {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      gap: 2mm;
    }
    .empresa {
      max-width: 83mm; /* 100 - padding-left(15) - padding-right(2) */
    }
    .empresa .nome {
      font-size: 5.0mm;
      font-weight: 800;
      letter-spacing: 0.2px;
      line-height: 1.05;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .empresa {
      margin-top: 0.2mm;
      font-size: 2.8mm;
      font-weight: 700;
      line-height: 1.0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .endereco {
      margin-top: 0.2mm;
      font-size: 2.0mm;
      font-weight: 700;
      line-height: 1.0;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .linha {
      margin-top: 0.5mm;
      font-size: 3.6mm;
      font-weight: 800;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .linha.sec {
      font-weight: 700;
    }
    .kv {
      display: flex;
      gap: 2.5mm;
      margin-top: 0.5mm;
      font-size: 3.1mm;
      font-weight: 800;
      white-space: nowrap;
    }
    .kv span {
      font-weight: 900;
    }
  </style>
</head>
<body>
  {assign var="q" value=$qtde|default:1}
  {section name=i loop=$q}
    <div class="sheet">
      <div class="empresa">
        <div class="nome">{$empresa[0].NOMEFANTASIA}</div>
        <div class="endereco">{$empresa[0].TIPOEND} {$empresa[0].ENDERECO} - {$empresa[0].NUMERO} - {$empresa[0].COMPLEMENTO} {$empresa[0].BAIRRO} - {$empresa[0].CIDADE} - {$empresa[0].UF} - ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM} / {$empresa[0].FAXNUM}</div>
      </div>
      <div class="linha">
        {$produto.DESCRICAO}
      </div>

      <div class="kv">
        <div><span>Ref:</span> {$produto.CODFABRICANTE}</div>
        <div><span>Cod:</span> {$produto.CODIGO}</div>
        <div><span>Local:</span> {$produto.LOCALIZACAO}</div>
      </div>
    </div>
        <div><span></span></div>
    {if not $smarty.section.i.last}
      <div class="page-break"></div>
    {/if}
  {/section}

  <script>
    (function () {
      try {
        window.focus();
        window.print();
        setTimeout(function () { window.close(); }, 300);
      } catch (e) {}
    })();
  </script>
</body>
</html>
