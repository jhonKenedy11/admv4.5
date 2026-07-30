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
    <input name=param type=hidden id="param" value="">


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

                <!-- Pedidos Campanha -->
                <div id="instrucoes-importaPedidoCampanha" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Preencher a Planilha - Pedidos Campanha</h4>
                  <p><strong>Formato:</strong> Excel (.xls) com <span class="destaque">múltiplas colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong>Coluna 2</strong> - CNPJ do Cliente (deve existir)</li>
                    <li><strong>Coluna 7</strong> - Número do Pedido</li>
                    <li><strong>Coluna 8</strong> - Data e Hora do Pedido</li>
                    <li><strong>Coluna 9</strong> - Código de Barras ou Código Fabricante do Produto</li>
                    <li><strong>Coluna 10</strong> - Descrição do Item</li>
                    <li><strong>Coluna 12</strong> - Quantidade Solicitada</li>
                    <li><strong>Coluna 13</strong> - Valor Unitário</li>
                    <li><strong>Coluna 14</strong> - Valor Total do Item</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Importante:</strong>
                    <ul>
                      <li>Cliente (CNPJ) <span class="destaque">deve estar cadastrado</span> antes da importação</li>
                      <li>Produtos devem existir no cadastro (por código de barras ou código fabricante)</li>
                      <li>Selecione a <span class="destaque">Natureza de Operação</span> e <span class="destaque">Condição de Pagamento</span> abaixo</li>
                      <li>Sistema agrupa itens por número de pedido automaticamente</li>
                      <li>Planilha deve começar na linha 8 (linhas 1-7 são ignoradas)</li>
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

                <!-- Importar CST IBS/CBS -->
                <div id="instrucoes-cstibscbs" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Importar - CST IBS/CBS (Reforma Tributária)</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">10 colunas</span></p>
                  
                  <h5>Colunas da Planilha (nesta ordem):</h5>
                  <ol>
                    <li><strong class="destaque">CST*</strong> - Código do CST (3 caracteres) - <span class="destaque">Obrigatório - Chave Única</span></li>
                    <li><strong>DESCRIÇÃO</strong> - Descrição completa do CST</li>
                    <li><strong>IND_G_IBS_CBS</strong> - Indicador Grupo IBS/CBS (0 ou 1)</li>
                    <li><strong>IND_G_IBS_CBS_MONO</strong> - Indicador Grupo IBS/CBS Monofásico (0 ou 1)</li>
                    <li><strong>IND_G_RED</strong> - Indicador Grupo Redução (0 ou 1)</li>
                    <li><strong>IND_G_DIF</strong> - Indicador Grupo Diferimento (0 ou 1)</li>
                    <li><strong>IND_G_TRANSF_CRED</strong> - Indicador Grupo Transferência Crédito (0 ou 1)</li>
                    <li><strong>IND_G_CRED_PRES_IBS_ZFM</strong> - Indicador Grupo Crédito Presumido IBS ZFM (0 ou 1)</li>
                    <li><strong>IND_G_AJUSTE_COMPET</strong> - Indicador Grupo Ajuste Competência (0 ou 1)</li>
                    <li><strong>IND_REDUTOR_BC</strong> - Indicador Redutor Base de Cálculo (0 ou 1)</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Importante - Ordem de Importação:</strong>
                    <ul>
                      <li><span class="destaque">IMPORTAR PRIMEIRO!</span> Esta tabela deve ser importada <strong>antes</strong> da tabela CClasstrib</li>
                      <li>A tabela CClasstrib possui chave estrangeira (FK) para esta tabela</li>
                      <li>Se o CST já existir, o registro será <strong>atualizado</strong></li>
                      <li>Se não existir, será <strong>inserido</strong></li>
                      <li>Campos de indicadores devem conter apenas 0 ou 1</li>
                      <li>Planilha deve ter <span class="destaque">linha de cabeçalho</span> (linha 1 será ignorada)</li>
                    </ul>
                  </div>
                </div>

                <!-- Importar CClasstrib -->
                <div id="instrucoes-cclasstrib" class="instrucoes-importacao">
                  <h4><i class="fa fa-info-circle"></i> Como Importar - Classificação Tributária (CClasstrib)</h4>
                  <p><strong>Formato:</strong> Excel (.xls ou .xlsx) com <span class="destaque">35 colunas</span></p>
                  
                  <h5>Campos Texto (colunas 1-8):</h5>
                  <ol>
                    <li><strong class="destaque">CST-IBS/CBS*</strong> - Código CST (3 caracteres) - <span class="destaque">Deve existir na tabela CST IBS/CBS</span></li>
                    <li><strong>Descrição CST-IBS/CBS</strong> - Descrição do CST (ignorado na importação)</li>
                    <li><strong class="destaque">cClassTrib*</strong> - Código (6 caracteres) - <span class="destaque">Obrigatório - Chave Única</span></li>
                    <li><strong>Nome cClassTrib</strong> - Nome resumido (máx 100 caracteres)</li>
                    <li><strong>Descrição cClassTrib</strong> - Descrição completa (máx 260 caracteres)</li>
                    <li><strong>LC Redação</strong> - Redação da Lei Complementar</li>
                    <li><strong>LC 214/25</strong> - Referência LC 214/25 (máx 20 caracteres)</li>
                    <li><strong>Tipo de Alíquota</strong> - Tipo de Alíquota (máx 20 caracteres)</li>
                  </ol>

                  <h5>Indicadores Principais (colunas 9-17) - usar 0 ou 1:</h5>
                  <ol start="9">
                    <li><strong>pRedIBS</strong> - Predefinido IBS</li>
                    <li><strong>pRedCBS</strong> - Predefinido CBS</li>
                    <li><strong>ind_gTribRegular</strong> - Indicador Tributação Regular</li>
                    <li><strong>ind_gCredPresOper</strong> - Indicador Crédito Presumido Operação</li>
                    <li><strong>ind_gMonoPadrao</strong> - Indicador Monofásico Padrão</li>
                    <li><strong>ind_gMonoReten</strong> - Indicador Monofásico Retenção</li>
                    <li><strong>ind_gMonoRet</strong> - Indicador Monofásico Retido</li>
                    <li><strong>ind_gMonoDif</strong> - Indicador Monofásico Diferido</li>
                    <li><strong>ind_gEstornoCred</strong> - Indicador Estorno Crédito</li>
                  </ol>

                  <h5>Campos Data (colunas 18-20) - formato AAAA-MM-DD ou DD/MM/AAAA:</h5>
                  <ol start="18">
                    <li><strong>dIniVig</strong> - Data Início Vigência</li>
                    <li><strong>dFimVig</strong> - Data Fim Vigência</li>
                    <li><strong>DataAtualização</strong> - Data Atualização</li>
                  </ol>

                  <h5>Indicadores por Documento Fiscal (colunas 21-35) - usar 0 ou 1:</h5>
                  <ol start="21">
                    <li><strong>indNFeABI</strong> - NF-e ABI</li>
                    <li><strong>indNFe</strong> - NF-e</li>
                    <li><strong>indNFCe</strong> - NFC-e</li>
                    <li><strong>indCTe</strong> - CT-e</li>
                    <li><strong>indCTeOS</strong> - CT-e OS</li>
                    <li><strong>indBPe</strong> - BP-e</li>
                    <li><strong>indBPeTA</strong> - BP-e TA</li>
                    <li><strong>indBPeTM</strong> - BP-e TM</li>
                    <li><strong>indNF3e</strong> - NF3-e</li>
                    <li><strong>indNFSe</strong> - NFS-e</li>
                    <li><strong>indNFSe Via</strong> - NFS-e VIA</li>
                    <li><strong>indNFCom</strong> - NF Comunicação</li>
                    <li><strong>indNFAg</strong> - NF Agro</li>
                    <li><strong>indNFGas</strong> - NF Gás</li>
                    <li><strong>indDERE</strong> - DERE</li>
                  </ol>

                  <div class="obs">
                    <strong><i class="fa fa-exclamation-triangle"></i> Importante:</strong>
                    <ul>
                      <li>O campo <span class="destaque">CST-IBS/CBS (coluna 1)</span> deve existir na tabela EST_CST_IBS_CBS</li>
                      <li>Se o cClassTrib já existir, o registro será <strong>atualizado</strong></li>
                      <li>Se não existir, será <strong>inserido</strong></li>
                      <li>Campos de indicadores devem conter apenas 0 ou 1</li>
                      <li>Campos de data podem ficar vazios</li>
                      <li>Planilha deve ter <span class="destaque">linha de cabeçalho</span> (linha 1 será ignorada)</li>
                    </ul>
                  </div>
                </div>

              </div> <!-- col-md-12 instruções -->

              <!-- Campos adicionais para Pedidos Campanha -->
              <div class="row importaPedidoCampanha" id="camposPedidoCampanha" style="display: none;">
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <label>Natureza de operação</label>
                  <select class="form-control" name=natureza id="natureza">
                    {html_options values=$natureza_ids selected=$natureza_id output=$natureza_names}
                  </select>
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12">
                  <label>Condição de pagamento</label>
                  <select class="form-control" name=condPag id="condPag">
                    {html_options values=$condPag_ids selected=$condPag_id output=$condPag_names}
                  </select>
                </div>
              </div>

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

<script>
  // Verifica se o valor selecionado no menu suspenso é igual a "importaPedidoCampanha"
  document.getElementById('arqImporta').addEventListener('change', function() {
    var selectedValue = this.value;
    var camposDiv = document.getElementById('camposPedidoCampanha');
    if (selectedValue === 'importaPedidoCampanha') {
      // Se for igual, mostra os campos de natureza e condição de pagamento
      if (camposDiv) {
        camposDiv.style.display = 'block';
      }
    } else {
      // Se não for igual, esconde os campos
      if (camposDiv) {
        camposDiv.style.display = 'none';
      }
    }
  });

  // Atualiza o campo param quando os selects mudarem
  function atualizaParam() {
    var condPag = document.getElementById('condPag') ? document.getElementById('condPag').value : '';
    var natureza = document.getElementById('natureza') ? document.getElementById('natureza').value : '';
    var paramField = document.getElementById('param');
    if (paramField) {
      paramField.value = condPag + '|' + natureza;
    }
  }

  // Adiciona eventos aos selects
  document.addEventListener('DOMContentLoaded', function() {
    var condPag = document.getElementById('condPag');
    var natureza = document.getElementById('natureza');
    if (condPag) {
      condPag.addEventListener('change', atualizaParam);
    }
    if (natureza) {
      natureza.addEventListener('change', atualizaParam);
    }
  });
</script>

<!-- /Datatables -->