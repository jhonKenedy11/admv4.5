<style>
  .form-control,
  .x_panel {
    border-radius: 5px;
  }
  .instrucoes-importacao {
    display: none;
    margin-top: 20px;
    padding: 15px;
    background-color: #f8f9fa;
    border-left: 4px solid #17a2b8;
    border-radius: 4px;
  }
  .instrucoes-importacao.active {
    display: block;
  }
  .instrucoes-importacao h4 {
    color: #17a2b8;
    margin-top: 0;
    margin-bottom: 15px;
  }
  .instrucoes-importacao ul {
    margin-bottom: 10px;
  }
  .instrucoes-importacao li {
    margin-bottom: 5px;
  }
  .instrucoes-importacao .obs {
    background-color: #fff3cd;
    padding: 10px;
    border-left: 3px solid #ffc107;
    margin-top: 10px;
  }
  .instrucoes-importacao .destaque {
    color: #dc3545;
    font-weight: bold;
  }
</style>

<script type="text/javascript" src="{$pathJs}/util/s_util.js"> </script>

<script type="text/javascript">
// Função para mostrar instrução correspondente
function mostraInstrucao() {
    var tipoImportacao = document.getElementById('arqImporta').value;
    
    // Esconde todas as instruções
    var instrucoes = document.querySelectorAll('.instrucoes-importacao');
    for (var i = 0; i < instrucoes.length; i++) {
        instrucoes[i].classList.remove('active');
    }
    
    // Mostra a instrução correspondente
    var instrucaoId = 'instrucoes-' + tipoImportacao;
    var instrucaoAtiva = document.getElementById(instrucaoId);
    if (instrucaoAtiva) {
        instrucaoAtiva.classList.add('active');
    }
}

// Adiciona evento ao select quando carregar a página
document.addEventListener('DOMContentLoaded', function() {
    var selectImporta = document.getElementById('arqImporta');
    if (selectImporta) {
        selectImporta.addEventListener('change', mostraInstrucao);
        // Mostra instrução inicial se houver seleção
        mostraInstrucao();
    }
});
</script>

<!-- page content -->
<div class="right_col" role="main">
  <form id="lancamento" class="form-horizontal form-label-left" NAME="lancamento" ACTION="{$SCRIPT_NAME}" METHOD="post"
    enctype="multipart/form-data">
    <input name=mod type=hidden value="{$mod}">
    <input name=form type=hidden value="{$form}">
    <input name=id type=hidden value="">
    <input name=letra type=hidden value={$letra}>
    <input name=submenu type=hidden value={$subMenu}>


    <div class="">
      
      <div class="row">

        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Importa&ccedil;&otilde;es - Consulta
                <strong>
                  {if $mensagem neq ''}
                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                  {/if}
                </strong>
              </h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar();">
                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Importar</span></button>
                </li>
                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li> *}
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">

              <div class="form-group">
                <div class="col-md-4 col-sm-12 col-xs-12">
                  <label>Arquivo </label>
                  <select class="form-control" name=arqImporta id="arqImporta">
                    {html_options values=$arqImporta_ids selected=$arqImporta_id output=$arqImporta_names}
                  </select>
                </div>
                <div class="col-md-8 col-sm-12 col-xs-12">
                  <label for="nome">Selecione a Planilha</label>
                  <div class="fileinput fileinput-new" data-provides="fileinput">
                    <span class="btn btn-default btn-file"><input type="file" name="arq" /></span>
                  </div>
                  <div class="form-group">
                  </div>
                </div> <!-- div class="col-md-8" -->
              </div> <!-- div class="form-group" -->

              <!-- Instruções de Importação -->
              <div class="col-md-12">
                
                <!-- Importar Pessoa -->
                <div id="instrucoes-pessoa" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Preencher a Planilha - Importação de Pessoas</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">20 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">RAZÃO SOCIAL*</strong> - Nome completo (obrigatório)</li>
                    <li><strong class="destaque">CNPJ/CPF*</strong> - Com ou sem máscara (obrigatório)</li>
                    <li><strong>IE/RG</strong> - Inscrição Estadual ou RG</li>
                    <li><strong>CEP</strong> - Se informado, busca endereço automaticamente!</li>
                    <li><strong>ENDEREÇO</strong> - Rua/Avenida (se CEP vazio)</li>
                    <li><strong>NUMERO</strong> - Número do endereço</li>
                    <li><strong>COMPLEMENTO</strong> - Apto, Sala, etc</li>
                    <li><strong>BAIRRO</strong> - Nome do bairro (se CEP vazio)</li>
                    <li><strong>CIDADE</strong> - Nome da cidade (se CEP vazio)</li>
                    <li><strong>UF</strong> - Sigla do estado (se CEP vazio)</li>
                    <li><strong>TELEFONE</strong> - Telefone fixo</li>
                    <li><strong>CELULAR</strong> - Telefone celular</li>
                    <li><strong>EMAIL</strong> - Email (usado também para NFE)</li>
                    <li><strong>CONTATO</strong> - Nome da pessoa de contato</li>
                    <li><strong>HOMEPAGE</strong> - Site/Website</li>
                    <li><strong>DATA NASCIMENTO</strong> - Formato: dd/mm/aaaa</li>
                    <li><strong>INSCRIÇÃO MUNICIPAL</strong></li>
                    <li><strong>RESPONSAVEL</strong> - Nome do vendedor</li>
                    <li><strong>CLASSE</strong> - Código ou descrição</li>
                    <li><strong>ATIVIDADE</strong> - Código ou descrição</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-lightbulb-o"></i> Dicas Importantes:</strong>
                    <ul>
                      <li><strong>CEP:</strong> Se informar CEP válido, sistema preenche endereço completo + código IBGE automaticamente!</li>
                      <li><strong>CNPJ/CPF:</strong> Sistema detecta automaticamente se é Pessoa Física (11 dígitos) ou Jurídica (14 dígitos)</li>
                      <li><strong>Vendedor/Classe/Atividade:</strong> Sistema busca por código ou nome</li>
                      <li>Planilha deve ter <span class="destaque">linha de cabeçalho</span></li>
                      <li>Incluir <span class="destaque">linha em branco</span> no final</li>
                    </ul>
                  </div>
                </div>

                <!-- Importar Boleto Financeiro -->
                <div id="instrucoes-boletoFinanceiro" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Preencher a Planilha - Importação de Boletos Financeiros</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">10 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">NUM DOC*</strong> - Número do documento (obrigatório)</li>
                    <li><strong class="destaque">SERIE*</strong> - Série do documento</li>
                    <li><strong class="destaque">ORIGEM*</strong> - Origem do lançamento</li>
                    <li><strong>DATA DOC</strong> - Data do documento</li>
                    <li><strong>DATA VENCIMENTO</strong> - Data de vencimento</li>
                    <li><strong class="destaque">CNPJ SACADO*</strong> - CNPJ do cliente (deve existir)</li>
                    <li><strong>VALOR</strong> - Valor do boleto</li>
                    <li><strong>GENERO</strong> - Gênero do lançamento</li>
                    <li><strong>CENTRO CUSTO</strong> - Centro de custo</li>
                    <li><strong>CONTA</strong> - Conta bancária</li>
                    <li><strong>MODO PGTO</strong> - Modo de pagamento</li>
                    <li><strong>TIPO DOCTO</strong> - Tipo do documento</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Importante:</strong>
                    <ul>
                      <li>Cliente (CNPJ) <span class="destaque">deve estar cadastrado</span> antes da importação</li>
                      <li>Valores devem usar vírgula como separador decimal</li>
                    </ul>
                  </div>
                </div>

                <!-- Entrada Produtos Estoque -->
                <div id="instrucoes-produtosquant" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Preencher a Planilha - Entrada de Produtos no Estoque</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">8 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">CÓDIGO*</strong> - Código do produto (deve existir)</li>
                    <li><strong class="destaque">QTDE*</strong> - Quantidade a dar entrada</li>
                    <li><strong class="destaque">NUM NF*</strong> - Número da nota fiscal (deve existir)</li>
                    <li><strong>LOCALIZAÇÃO</strong> - Local no estoque</li>
                    <li><strong>DATA FABRICAÇÃO</strong> - Data de fabricação</li>
                    <li><strong>NUM LOTE</strong> - Número do lote</li>
                    <li><strong>VALIDADE</strong> - Data de validade</li>
                    <li><strong>CENTRO CUSTO</strong> - Centro de custo</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Pré-requisitos:</strong>
                    <ul>
                      <li>Produto <span class="destaque">deve estar cadastrado</span></li>
                      <li>Nota Fiscal <span class="destaque">deve existir no sistema</span></li>
                      <li>Sistema criará N itens individuais no estoque (N = quantidade)</li>
                    </ul>
                  </div>
                </div>

                <!-- Saída Produtos Estoque -->
                <div id="instrucoes-saidaprodutosquant" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Preencher a Planilha - Saída de Produtos (Baixa/Perda)</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">8 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">CÓDIGO*</strong> - Código do produto</li>
                    <li><strong class="destaque">QTDE*</strong> - Quantidade a dar baixa</li>
                    <li><strong class="destaque">NUM NF*</strong> - Número da NF de saída</li>
                    <li><strong>LOCALIZAÇÃO</strong></li>
                    <li><strong>NUM LOTE</strong></li>
                    <li><strong>VALIDADE</strong></li>
                    <li><strong>ORIGEM</strong></li>
                    <li><strong>CENTRO CUSTO</strong> - Centro de custo da perda</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Atenção:</strong>
                    <ul>
                      <li>Usado para baixas por <span class="destaque">perda, quebra ou avaria</span></li>
                      <li>Produtos devem existir no estoque</li>
                    </ul>
                  </div>
                </div>

                <!-- Repasse MKT -->
                <div id="instrucoes-extratorepassemkt" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Preencher a Planilha - Repasse Marketing</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">9 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">CNPJ FORNECEDOR*</strong> - CNPJ do fornecedor</li>
                    <li><strong>RAZÃO SOCIAL</strong> - Nome do fornecedor</li>
                    <li><strong>CODIGO FARMACIA</strong> - Código da farmácia</li>
                    <li><strong class="destaque">CNPJ FARMACIA*</strong> - CNPJ do cliente</li>
                    <li><strong>VALOR COMPRADO</strong> - Valor total comprado</li>
                    <li><strong class="destaque">VALOR ASSOCIADO*</strong> - Valor do repasse</li>
                    <li><strong>GENERO</strong> - Gênero do lançamento</li>
                    <li><strong>DATA COMPETENCIA</strong> - Mês/ano</li>
                    <li><strong>OBS</strong> - Observações</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-info-circle"></i> Observação:</strong>
                    <ul>
                      <li>Fornecedor e Cliente devem estar cadastrados</li>
                      <li>Gera extrato de repasse de marketing</li>
                    </ul>
                  </div>
                </div>

                <!-- Importar Boleto BIG -->
                <div id="instrucoes-financeiro" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Importar - Boleto BIG (Convênio)</h4>
                  <p><strong>Formato:</strong> Arquivo TXT ou CSV com <span class="destaque">19 colunas separadas por ponto-e-vírgula (;)</span></p>
                  
                  <h5>Colunas do Arquivo (nesta ordem):</h5>
                  <ol start="0">
                    <li><strong>NUMERO DOC</strong></li>
                    <li><strong>DATA DOC</strong></li>
                    <li><strong>AGENCIA</strong></li>
                    <li><strong>COD. CEDENTE</strong></li>
                    <li><strong>CONTA CORRENTE</strong></li>
                    <li><strong>CNPJ CEDENTE</strong></li>
                    <li><strong>NOSSO NUMERO</strong></li>
                    <li><strong>DATA PROCESSAMENTO</strong></li>
                    <li><strong class="destaque">CNPJ SACADO*</strong> - Cliente deve existir</li>
                    <li><strong>VALOR</strong></li>
                    <li><strong>$ JUROS</strong></li>
                    <li><strong>MULTA</strong></li>
                    <li><strong>% DESCONTO</strong></li>
                    <li><strong>VALOR DESCONTO</strong></li>
                    <li><strong>DATA VENCIMENTO</strong></li>
                    <li><strong>LINHA DIGITAVEL</strong></li>
                    <li><strong>GENERO</strong></li>
                    <li><strong>CENTRO CUSTO</strong></li>
                    <li><strong class="destaque">CONTA*</strong> - ID da conta bancária</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Importante:</strong>
                    <ul>
                      <li>Arquivo <span class="destaque">TXT ou CSV</span> (não Excel)</li>
                      <li>Separador: <span class="destaque">ponto-e-vírgula (;)</span></li>
                      <li>Cliente e conta bancária devem existir</li>
                      <li>Sistema gera número de remessa automaticamente</li>
                    </ul>
                  </div>
                </div>

                <!-- Atualizar IBPT -->
                <div id="instrucoes-ibpt" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Importar - Tabela IBPT (Impostos)</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">10 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">NCM*</strong> - Código NCM do produto</li>
                    <li><strong>EX</strong> - Exceção (se houver)</li>
                    <li><strong>TIPO</strong> - Tipo do produto</li>
                    <li><strong>DESCRIÇÃO</strong> - Descrição do NCM</li>
                    <li><strong>ALIQ TT NAC FEDERAL</strong> - % Federal</li>
                    <li><strong>ALIQ TT IMP FEDERAL</strong> - % Importação Federal</li>
                    <li><strong>ALIQ TT ESTADUAL</strong> - % Estadual</li>
                    <li><strong>ALIQ TT MUNICIPAL</strong> - % Municipal</li>
                    <li><strong>VIGENCIA INICIO</strong> - Data início</li>
                    <li><strong>VIGENCIA FIM</strong> - Data fim</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-info-circle"></i> Informação:</strong>
                    <ul>
                      <li>Tabela oficial do IBPT para cálculo de impostos aproximados</li>
                      <li>Se NCM já existir, será atualizado</li>
                      <li>Se NCM não existir, será criado</li>
                      <li>Download da tabela: <a href="https://deolhonoimposto.ibpt.org.br/" target="_blank">IBPT Oficial</a></li>
                    </ul>
                  </div>
                </div>

              </div> <!-- col-md-12 instruções -->

              <div class="clearfix"></div>
              <div class="form-group">
                <div class="col-md-12">

                </div> <!-- div class="x_content" = inicio tabela -->
              </div> <!-- div class="x_panel" = painel principal-->
            </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
          </div> <!-- div class="row "-->
        </div> <!-- class='' = controla menu user -->

  </form>


  {include file="template/database.inc"}

<!-- /Datatables -->