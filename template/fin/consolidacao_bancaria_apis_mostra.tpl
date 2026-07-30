<style type="text/css">
.form-control,
.x_panel {
  border-radius: 5px !important;
  font-size: 12px !important;
}
  .json-result-box {
    background: #1e1e1e;
    color: #d4d4d4;
    border-radius: 8px;
    padding: 16px;
    font-family: Consolas, Monaco, monospace;
    font-size: 12px;
    max-height: 480px;
    overflow: auto;
    white-space: pre-wrap;
    word-break: break-word;
    border: 2px solid #e9e4ff;
  }
  .param-box {
    background: linear-gradient(to bottom, #f8f7ff 0%, #ffffff 100%);
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 1px;
    border: 2px solid #e9e4ff;
  }
  .x_title h2 { 
    color: #667eea; 
    padding-bottom: 1px !important;  
  }

  #btn_pagina_anterior,
  #btn_pagina_proxima {
    padding: 5px 8px;
    font-size: 10px;
    font-weight: 600;
    width: 10em !important;
    border: none;
    color: #e2dfdf;
    background: linear-gradient(135deg, #1b3d9cd5 0%, #282d4bbb 100%);
    box-shadow: 0 2px 3px rgba(102, 126, 234, 0.25) !important;
    transition: all 0.3s ease;
    transform: translateY(0);
  }
  #btn_pagina_anterior:hover,
  #btn_pagina_proxima:hover {
    color: #e2dfdf;
    transform: translateY(-1px) !important;
    transition: all 0.3s ease !important;
    scale: 1.02 !important;
    box-shadow: 0 6px 12px rgba(102, 126, 234, 0.57) !important;
  }

  .hidden {
      display: none !important;
  }


  /* BANK CARD */

    .bank_card_area {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100%;
    }

    .bank-card {
      background: #ffffff;
      border: 1px solid rgba(0, 0, 0, 0.08);
      border-radius: 14px;
      overflow: hidden;
      max-width: 700px;
      width: 100%;
    }
 
    .bank-card-accent {
      height: 3px;
      background: linear-gradient(90deg, #185FA5 0%, #1D9E75 50%, #185FA5 100%);
      background-size: 200% 100%;
      animation: shimmer 2.5s infinite linear;
    }
 
    @keyframes shimmer {
      0%   { background-position: 200% 0; }
      100% { background-position: -200% 0; }
    }
 
    .bank-card-body {
      padding: 1.75rem 2rem;
      display: flex;
      gap: 1.25rem;
      align-items: flex-start;
    }
 
    .bank-icon-wrap {
      flex-shrink: 0;
      width: 48px;
      height: 48px;
      border-radius: 10px;
      background: #e6f1fb;
      display: flex;
      align-items: center;
      justify-content: center;
    }
 
    .bank-icon-wrap svg {
      width: 22px;
      height: 22px;
      stroke: #185FA5;
      fill: none;
      stroke-width: 1.7;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
 
    .bank-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      font-size: 11px;
      font-weight: 600;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      padding: 3px 10px;
      border-radius: 99px;
      background: #faeeda;
      color: #854F0B;
      margin-bottom: 10px;
    }
 
    .bank-badge svg {
      width: 12px;
      height: 12px;
      stroke: #854F0B;
      fill: none;
      stroke-width: 2;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
 
    .bank-title {
      font-size: 17px;
      font-weight: 700;
      color: #1a1a1a;
      margin-bottom: 6px;
      line-height: 1.3;
      letter-spacing: -0.01em;
    }
 
    .bank-desc {
      font-size: 14px;
      color: #666;
      line-height: 1.65;
      margin-bottom: 1.25rem;
    }
  /* FIM BANK CARD */

  .btn_processar {
    background-color: #329ebe;
    color: #f1f1f1;
    font-weight: 600;
    font-size: 1.2rem;
    transition: all 0.2s ease;
    transform: translateY(0);
    background: linear-gradient(135deg, #1b3d9cd5 0%, #282d4bbb 100%);
    box-shadow: 0 2px 3px rgba(102, 126, 234, 0.25) !important;
    
  }
  .btn_processar:hover {
    color: #f1f1f1 !important;
    transform: translateY(-1px) !important;
    transition: all 0.3s ease !important;
    scale: 1.02 !important;
    box-shadow: 0 4px 8px rgba(102, 126, 234, 0.73) !important;
  }

  .label_filtros {
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
  }
  .span_titulos_pesquisados {
    font-weight: 600;
    color: #666;
    margin-bottom: 5px;
    font-size: 16px;
  }

</style>


<link rel="stylesheet" href="{$pathCss}/consolidacao_bancaria_apis_mostra_informativo.css">
<link rel="stylesheet" href="{$pathCss}/consolidacao_bancaria_apis_mostra_inter.css">
<link rel="stylesheet" href="{$pathCss}/consolidacao_bancaria_apis_mostra_bradesco.css">

<div class="right_col" role="main">

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
          <div class="x_title">
            <h2><i class="fa fa-plug"></i> {$titulo}</h2>
            <div class="clearfix"></div>
          </div>

          <div class="x_content">

            <div class="param-box">

              <div class="row">
                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                  <label class="control-label label_filtros">Banco</label>
                    <select class="form-control" id="filtro_banco_api" name="filtro_banco_api" onchange="SelecionaBancoApi($('#filtro_banco_api').val());">
                        <option value="" selected>Selecione um banco</option>
                        {foreach from=$bancos_api item=b}
                          <option value="{$b.BANCO|escape}"> {$b.NOME|escape} </option>
                        {/foreach}
                    </select>
                </div>

                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                  <label class="control-label label_filtros">Conta banc&aacute;ria</label>
                  <select class="form-control" id="filtro_conta_api" name="filtro_conta_api">
                      <option value="" id="filtro_conta_api_placeholder" selected>Selecione o banco para filtrar as contas</option>
                      {foreach from=$contas_api item=c}
                        <option value="{$c.CONTA|escape}" data-banco="{$c.BANCO|escape}"> {$c.NOME|escape} </option>
                      {/foreach}  
                  </select>
                </div>

                <div class="form-group col-md-4 col-sm-12 col-xs-12">
                  <label class="control-label label_filtros">Centro de custo</label>
                  <select class="form-control" id="filtro_centro_custo_api" name="filtro_centro_custo_api">
                      {foreach from=$centros_custo_api item=c}
                        <option value="{$c.CENTROCUSTO|escape}"> {$c.NOME|escape} </option>
                      {/foreach}
                  </select>
                </div>

              </div>

              <div class="row row_botoes_api">
                <div class="col-xs-12 text-center">

                  <div class="bank_card_area hidden">
                    <div class="bank-card">
                    <div class="bank-card-accent"></div>
                    <div class="bank-card-body">
                
                      <div class="bank-icon-wrap">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                          <line x1="3" y1="21" x2="21" y2="21"/>
                          <polyline points="3 10 12 3 21 10"/>
                          <rect x="9" y="14" width="6" height="7"/>
                          <rect x="3" y="11" width="3" height="4"/>
                          <rect x="18" y="11" width="3" height="4"/>
                        </svg>
                      </div>
                
                      <div>
                        <div class="bank-badge">
                          <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <polyline points="12 7 12 12 15 15"/>
                          </svg>
                          Em breve
                        </div>
                
                        <p class="bank-title">Este banco ainda não está disponível</p>
                        <p class="bank-desc">
                          A integração com esta instituição financeira está em desenvolvimento.
                          Estamos trabalhando para disponibilizá-la o mais breve possível
                          com toda a segurança e agilidade que você merece.
                        </p>
                      </div>
                
                    </div>  
                  </div>
                </div>



                  <!------------------------------------------
                  ----------  INICIO BOTOES BRADESCO ---------
                   ------------------------------------------->
                  <div class="btn_acoes_bradesco hidden">

                    <button 
                      type="button" 
                      class="btn btns_bradesco" 
                      onClick="javascript:ConsultarTitulosBradesco('pendentes');" >
                      <i class="fa fa-search fa_sm_bradesco"></i> T&iacute;tulos pendentes
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_bradesco" 
                      onClick="javascript:ConsultarTitulosBradesco('baixados');" >
                      <i class="fa fa-search fa_sm_bradesco"></i> T&iacute;tulos baixados
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_bradesco" 
                      onClick="javascript:ConsultarTitulosBradesco('liquidados');" >
                      <i class="fa fa-search fa_sm_bradesco"></i> T&iacute;tulos liquidados
                    </button>
                  </div>
                  <!------------------------------------------
                  ----------  FIM BOTOES BRADESCO ------------
                   ------------------------------------------->

                  <!-- ----------------------------------------
                  ----------  INICIO BOTOES INTER -------------
                   ------------------------------------------->
                  <div class="btn_acoes_inter hidden">

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('RECEBIDO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Recebido
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('A_RECEBER');" >
                      <i class="fa fa-search fa_sm_inter"></i> A receber
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('MARCADO_RECEBIDO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Marcado recebido
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter"  
                      onClick="ConsultarColecaoCobrancaInter('EM_PROCESSAMENTO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Em processamento
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('ATRASADO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Atrasado
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('FALHA_EMISSAO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Falha emissão
                    </button>

                    <button 
                      type="button"   
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('PROTESTO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Protesto
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('CANCELADO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Cancelado
                    </button>

                    <button 
                      type="button" 
                      class="btn btns_inter" 
                      onClick="ConsultarColecaoCobrancaInter('EXPIRADO');" >
                      <i class="fa fa-search fa_sm_inter"></i> Expirado
                    </button>

                  </div>
                  <!-- ----------------------------------------
                  ----------  FIM BOTOES INTER ---------------
                   ------------------------------------------->

                </div>
              </div>

            </div>

            <div class="param-box info-passos" id="como_aplicar_filtros">

              <div class="info-passos-header">
                <div class="info-passos-icon">
                  <i class="fa fa-info"></i>
                </div>
                <div>
                  <div class="info-passos-titulo">Como realizar uma consulta</div>
                  <span class="info-passos-subtitulo">Siga os passos abaixo para pesquisar os t&iacute;tulos pela API do banco</span>
                </div>
              </div>

              <div class="info-passos-body">

                <div class="info-passo">
                  <div class="info-passo-numero">1</div>
                  <div class="info-passo-texto">
                    <strong>Selecione o banco</strong>
                    <span>Escolha a institui&ccedil;&atilde;o financeira no campo "Banco".</span>
                  </div>
                </div>

                <div class="info-passo">
                  <div class="info-passo-numero">2</div>
                  <div class="info-passo-texto">
                    <strong>Selecione a conta banc&aacute;ria</strong>
                    <span>Escolha a conta vinculada ao banco selecionado.</span>
                  </div>
                </div>

                <div class="info-passo">
                  <div class="info-passo-numero">3</div>
                  <div class="info-passo-texto">
                    <strong>Clique em um dos bot&otilde;es</strong>
                    <span>Acione o bot&atilde;o da situa&ccedil;&atilde;o desejada para realizar a pesquisa.</span>
                  </div>
                </div>

              </div>

            </div>



            <div id="api_titulos_area" class="param-box" style="display:none;">

              <div style="margin-top:1px;">

                <div class="row" style="margin-top:10px;">

                    <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5 text-left">
                      <button 
                        type="button" 
                        id="btn_titulos_processar" 
                        class="btn btn_processar" 
                        onclick="ProcessaTitulosSelecionados();">
                        <i class="fa fa-tasks"></i> Processar títulos selecionados
                      </button>
                    </div>

                    <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 text-left">
                      <span class="span_titulos_pesquisados">Título(s) Pesquisado(s)</span>
                    </div>

                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 text-right">
                      <button 
                        type="button" 
                        id="btn_pagina_anterior" 
                        class="btn" 
                        data-consulta-id=""
                        onclick="javascript:alterarPagina('previous');" >
                        <i class="fa fa-arrow-left"></i> Página anterior
                      </button>

                      <button 
                        type="button" 
                        id="btn_pagina_proxima" 
                        class="btn" 
                        data-consulta-id=""
                        onclick="javascript:alterarPagina('next');" >
                        Próxima página <i class="fa fa-arrow-right"></i>
                      </button>
                    </div>

                </div>

              </div>

              <div class="row" style="margin-top: 14px;">
                <div class="col-xs-12">
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed" style="margin-bottom: 0;">
                      <thead>
                        <tr>
                          <th style="text-align:center;"><input type="checkbox" id="marcar_todos_thead" title="Marcar todos" /></th>
                          <th>Nome pagador</th>
                          <th>Seu número</th>
                          <th>Data vencimento</th>
                          <th>Data Pagamento</th>
                          <th>Data Movimento</th>
                          <th>Valor Titulo</th>
                          <th>Valor Pagamento</th>
                          <th>Descrição pagamento</th>
                        </tr>
                      </thead>

                      <tbody id="titulos_tbody"></tbody>
                    </table>
                  </div>
                  
                      
                </div>
              </div>
            </div>

            {* <div class="param-box">
              <h4 class="section-title"><i class="fa fa-code"></i> Resposta (JSON)</h4>
              <pre id="api_json_result" class="json-result-box" role="region" aria-label="Resposta JSON da API"></pre>
            </div> *}
          </div>
        </div>
      </div>
    </div>
</div>

<div class="modal fade" id="modal_detalhe_titulo" tabindex="-1" role="dialog" aria-labelledby="modal_detalhe_titulo_label">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="modal_detalhe_titulo_label"><i class="fa fa-info-circle"></i> Detalhes do título</h4>
      </div>
      <div class="modal-body">
        <table class="table table-bordered table-condensed" style="margin-bottom:0;">
          <tbody id="modal_detalhe_titulo_tbody"></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>


{include file="template/form.inc"}


<!-- scripts -->
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_consolidacao_bancaria_apis.js"></script>
