<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/est/s_tabela_preco_item.js"></script>
        <!-- page content -->
        <div class="right_col" role="main">
    <form class="full" NAME="lancamento" METHOD="POST" enctype="multipart/form-data" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="est">
        <input name=form          type=hidden value="tabela_preco_item">
        <input name=id            type=hidden value="{$id}">
        <input name=id_tabela_preco type=hidden value="{$id_tabela_preco}">
        <input name=codigo        type=hidden value="{$codigo}">
        <input name=tabela_preco  type=hidden value="">
        <input name=submenu       type=hidden value="">

        <div class="">
            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Importar Excel — Tabela de Preço (Itens)</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li>
                            <button type="button" class="btn btn-success" onclick="submitImportar({$id_tabela_preco});">
                                <span class="glyphicon glyphicon-import" aria-hidden="true"></span>&nbsp; Importar
                            </button>
                          <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar();">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true">Voltar</span></button></li>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content" style="padding-top:18px;">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1">
                            <div class="panel panel-default" style="padding:16px; border-radius:6px;">
                                {if $mensagem neq ''}
                                    <div class="alert alert-info" role="alert" style="margin-bottom:12px;">{$mensagem}</div>
                                {/if}
                                <div class="form-group">
                                    <label for="arquivo_excel" class="control-label">Selecione o arquivo Excel (.xls)</label>
                                    <input type="file" id="arquivo_excel" name="arquivo_excel" accept=".xls" class="form-control" style="padding:6px;">
                                </div>
                                
                                <hr style="margin:14px 0;">
                                <div class="well" style="background:#f7fbfd; border:1px solid #e1eef4; padding:14px; margin-bottom:0;">
                                    <h4 style="margin-top:0;">Instruções de importação (detalhado)</h4>
                                    <p style="margin:6px 0;"><strong>Formato do arquivo:</strong> .xls (Excel 97-2003). A primeira linha é ignorada (cabeçalho) — os dados começam na 2ª linha.</p>
                                    <p style="margin:6px 0;"><strong>Ordem fixa das colunas (a partir da 2ª linha):</strong></p>
                                    <ol style="margin-top:6px;">
                                        <li><strong>codigo_fabricante </strong> usado para localizar o produto em <code>est_produto</code>;</li>
                                        <li><strong>descricao </strong> descrição do item;</li>
                                        <li><strong>grupo </strong>grupo;</li>
                                        <li><strong>marca </strong> marca do produto;</li>
                                        <li><strong>valor base (precobase)</strong> use vírgula ou ponto para decimais; evite separador de milhares;</li>
                                        <li><strong>margem</strong> porcentagem (ex.: 20 ou 20,5).</li>
                                    </ol>
                                    <p style="margin:8px 0 0 0;"><strong>Exemplo (linha a partir da 2ª):</strong></p>
                                    <pre style="background:#ffffff; padding:8px; border:1px solid #e6e6e6; margin-top:6px;">ABC123    Parafuso 3/8    MarcaX    Parafusaria    12,50    10</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> 
          </div> 

    </form>

    {include file="template/database.inc"}

    <!-- /Datatables -->
