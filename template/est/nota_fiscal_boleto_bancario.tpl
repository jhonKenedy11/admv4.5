<style>
    html, body {
        height: 100%;
        margin: 0;
        overflow: hidden;
    }

    .nf_boleto_wrapper {
        display: flex;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        overflow: hidden;
    }

    /* ── Sidebar ── */
    .nf_boleto_sidebar {
        width: 30%;
        min-width: 260px;
        height: 100%;
        overflow-y: auto;
        border-right: 1px solid #ddd;
        background: #f9f9f9;
        box-sizing: border-box;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
    }

    .sidebar_header {
        padding: 15px 15px 10px;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
    }

    .sidebar_header h6 {
        margin: 0 0 12px;
        font-size: 13px;
        font-weight: 700;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .sidebar_actions {
        padding: 12px 15px;
        border-bottom: 1px solid #e0e0e0;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .sidebar_actions .btn {
        width: 100%;
        text-align: left;
        font-size: 13px;
    }

    .sidebar_boletos_list {
        flex: 1;
        padding: 12px 15px 0;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    #btn_fechar_tela {
        position: sticky;
        bottom: 0;
        margin: auto -15px 0;
        padding: 10px 15px;
        background: #f9f9f9;
        border-top: 1px solid #e0e0e0;
    }

    .sidebar_section_title {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #999;
        margin: 0 0 10px;
        letter-spacing: 0.6px;
    }

    /* ── Cards de boleto ── */
    .boleto_card {
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 10px 12px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: border-color 0.15s, box-shadow 0.15s, background-color 0.15s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .boleto_card:hover:not(.boleto_card_processando):not(.boleto_card_info_static):not(.boleto_card_erro) {
        border-color: #337ab7;
        box-shadow: 0 2px 6px rgba(51,122,183,0.12);
    }

    .boleto_card.active {
        border-color: #337ab7;
        background: #f0f7ff;
    }

    .boleto_card.processando,
    .boleto_card_processando {
        opacity: 0.7;
        cursor: default;
        border-left: 4px solid #2196F3;
    }

    .boleto_card.boleto_card_info_static {
        cursor: default;
        pointer-events: none;
    }

    .boleto_card.boleto_card_info_static:hover {
        border-color: #e0e0e0;
        box-shadow: none;
    }

    /* Estado: Sucesso */
    .boleto_card.sucesso,
    .boleto_card_sucesso {
        border-left: 4px solid #4CAF50;
        background: #f1f8f4;
        border-color: #4CAF50;
    }

    .boleto_card.sucesso .boleto_card_icon,
    .boleto_card_sucesso .boleto_card_icon {
        color: #27ae60;
    }

    /* Estado: Erro */
    .boleto_card.falha,
    .boleto_card.boleto_card_erro,
    .boleto_card_erro {
        border-left: 4px solid #f44336;
        background: #fdf5f5;
        border-color: #f44336;
    }

    .boleto_card.falha .boleto_card_icon,
    .boleto_card.boleto_card_erro .boleto_card_icon,
    .boleto_card_erro .boleto_card_icon {
        color: #e74c3c;
    }

    .boleto_card.falha .boleto_card_sub,
    .boleto_card.boleto_card_erro .boleto_card_sub,
    .boleto_card_erro .boleto_card_sub {
        color: #c0392b;
    }

    .boleto_card_icon {
        font-size: 22px;
        color: #337ab7;
        flex-shrink: 0;
        width: 28px;
        text-align: center;
        transition: color 0.15s;
    }

    .boleto_card.processando .boleto_card_icon,
    .boleto_card_processando .boleto_card_icon {
        color: #2196F3;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .boleto_card_info {
        flex: 1;
        min-width: 0;
    }

    .boleto_card_title {
        font-size: 13px;
        font-weight: 600;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .boleto_card_sub {
        font-size: 11px;
        color: #888;
        margin-top: 2px;
        transition: color 0.15s;
    }

    .boleto_card_badge {
        flex-shrink: 0;
    }

    .boleto_card_mensagem_erro {
        font-size: 11px;
        color: #d32f2f;
        margin-top: 4px;
        padding: 4px 0;
        line-height: 1.3;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* estado vazio */
    .boletos_empty {
        text-align: center;
        color: #bbb;
        font-size: 13px;
        padding: 30px 10px;
    }

    .boletos_empty i {
        display: block;
        font-size: 36px;
        margin-bottom: 8px;
    }

    /* ── Viewer ── */
    .nf_viewer {
        flex: 1;
        height: 100%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .nf_viewer iframe {
        width: 100%;
        flex: 1;
        border: none;
        display: block;
    }

    .viewer_placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        color: #ccc;
        font-size: 15px;
        flex-direction: column;
        gap: 12px;
    }

    .viewer_placeholder i {
        font-size: 56px;
    }

    .viewer_placeholder span {
        color: #aaa;
    }
#boletos_empty {
    position: relative;
    padding: 28px 20px 28px 55px;
    border: 2px dashed #d6dbe1;
    border-radius: 10px;
    background: #fcfcfd;
    color: #6c757d;
    text-align: center;
    font-size: 12px;
    line-height: 1.6;
    transition: all .2s ease;
}

#boletos_empty:hover {
    border-color: #b8c2cc;
    background: #f8fafc;
}

#boletos_empty i.fa-barcode {
    font-size: 26px;
    color: #adb5bd;
    display: block;
    margin-bottom: 12px;
}

#boletos_empty::before {
    content: "!";
    position: absolute;
    top: 14px;
    left: 14px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: #fff3cd;
    border: 1px solid #ffe69c;
    color: #856404;
    font-weight: bold;
    font-size: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.swal_custom {
    font-size: 13px !important;
}
</style>

<script type="text/javascript" src="{$pathJs}/est/s_nota_fiscal_boleto_bancario.js"></script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<div id="params_hidden" style="display:none;">
    <input type="hidden" id="id"                     value="{$id}">
    <input type="hidden" id="numero_nota_fiscal"     value="{$numero_nota_fiscal}">
    <input type="hidden" id="numero_pedido"          value="{$numero_pedido}">
    <input type="hidden" id="pessoa"                 value="{$pessoa}">
    <input type="hidden" id="gera_boleto_automatico" value="{$gera_boleto_automatico}">
    <input type="hidden" id="finalidade_emissao"     value="{$finalidade_emissao|default:0}">
</div>

<div class="nf_boleto_wrapper">

    <!-- Sidebar -->
    <div class="nf_boleto_sidebar">

        <div class="sidebar_header">
            <h6>Nota Fiscal / Boletos</h6>
            <div style="font-size:12px; color:#777;">
                {if $numero_nota_fiscal neq ''}
                    Nota Fiscal: <strong>{$numero_nota_fiscal}</strong>
                {/if}
                {if $numero_pedido neq ''}
                    &nbsp;|&nbsp; Nº Pedido: <strong>{$numero_pedido}</strong>
                {/if}
            </div>
        </div>

        <!-- Banner de restrição — preenchido pelo JS quando finalidade_emissao IN (4,5,6) -->
        <div id="banner_restricao_boleto" style="display:none; margin:10px 15px 0; padding:9px 12px; background:#fff8e1; border-left:4px solid #ffa000; border-radius:4px; font-size:12px; color:#6d4c00; line-height:1.4;"></div>

        <div class="sidebar_actions">
            <button 
                id="btn_imprimir_boleto" 
                class="btn btn-primary btn-sm" 
                onclick="iniciarEmissaoBoletos({$numero_pedido})">
                <i class="fa fa-barcode"></i> Gerar Boleto(s)
            </button>

            <button 
                id="btn_email_nf" 
                class="btn btn-primary btn-sm" 
                onclick="enviarEmail()"
                title="Enviar NF por E-mail">
                <i class="fa fa-paper-plane-o"></i> &nbsp; Enviar Nota Fiscal por e-mail
            </button>

            <button 
                id="btn_email_boleto_e_nf" 
                class="btn btn-primary btn-sm" 
                onclick="enviarEmail()"
                title="Enviar Boleto e NF por E-mail"
                disabled>
                <i class="fa fa-paper-plane-o"></i> &nbsp; Enviar Nota Fiscal e Boleto por e-mail
            </button>
        
            <button
                id="btn_imprimir_todos_boletos"
                class="btn btn-default btn-sm"
                onclick="imprimirTodosBoletos()"
                title="Abre um PDF único com todas as parcelas do pedido">
                <i class="fa fa-print"></i> &nbsp; Imprimir todos os boletos
            </button>
        </div>
        

        <div class="sidebar_boletos_list">

            {if $danfe neq ''}
            <div class="sidebar_section_title">Nota Fiscal</div>
            <div class="boleto_card" id="nf_card_danfe"
                 data-url="{$danfe}"
                 onclick="_abrirNfNoViewer()">
                <div class="boleto_card_icon" style="color:#27ae60;">
                    <i class="fa fa-file-pdf-o"></i>
                </div>
                <div class="boleto_card_info">
                    <div class="boleto_card_title">
                        Nota Fiscal{if $numero_nota_fiscal neq ''} nº {$numero_nota_fiscal}{/if}
                    </div>
                    <div class="boleto_card_sub">Clique para visualizar</div>
                </div>
                <div class="boleto_card_badge">
                    <span class="label label-success"><i class="fa fa-file-pdf-o"></i></span>
                </div>
            </div>
            {/if}

            <div class="sidebar_section_title" {if $danfe neq ''}style="margin-top:14px;"{/if}>Boletos</div>

            <!-- Cards inseridos dinamicamente via JS -->
            <div id="boletos_container">
                <div class="boletos_empty" id="boletos_empty">
                    <i class="fa fa-barcode"></i>
                    Clique em "Gerar Boleto(s)"<br>para gerar os boletos e poder enviar por e-mail
                </div>
            </div>

            <div id="btn_fechar_tela">
                <button class="btn btn-danger btn-sm btn-block" onclick="window.close();" title="Fechar Tela">
                    <i class="fa fa-times"></i> Fechar Tela
                </button>
            </div>
        </div>

    </div>

    <!-- Viewer PDF -->
    <div class="nf_viewer" id="nf_viewer">

        <div class="viewer_placeholder" id="viewerPlaceholder">
            <i class="fa fa-file-pdf-o"></i>
            <span>Selecione um item para visualizar</span>
        </div>

    </div>

</div>

<!-- ── Templates HTML usados pelo JS (ocultos) ── -->
    <!-- Template de estado inicial -->
    <div id="tpl_estado_inicial" style="display:none;">
        <div class="boletos_empty">
            <i class="fa fa-barcode"></i>
            Clique em "Gerar Boleto(s)"<br>para gerar os boletos
        </div>
    </div>

    <!-- Template de busca -->
    <div id="tpl_buscando" style="display:none;">
        <div class="boletos_empty">
            <i class="fa fa-spin fa-circle-o-notch"></i>
            Buscando boletos...
        </div>
    </div>

    <!-- Template de erro -->
    <div id="tpl_erro_container" style="display:none;">
        <div class="boletos_empty">
            <i class="fa fa-exclamation-triangle" style="color:#d9534f;"></i>
            <span style="color:#d9534f;" id="tpl_erro_container_msg"></span>
        </div>
    </div>

    <!-- Template de erro no viewer -->
    <div id="tpl_viewer_placeholder_erro" style="display:none;">
        <div class="viewer_placeholder">
            <i class="fa fa-exclamation-triangle"></i>
            <span>PDF não disponível para este boleto.</span>
        </div>
    </div>
<!-- ── Templates HTML usados pelo JS (ocultos) ── -->

{include file="template/form.inc"}