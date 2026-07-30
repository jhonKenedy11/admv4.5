<style type="text/css">
  .form-control,
  .x_panel {
    border-radius: 5px;
  }

  .radio-group {
    display: flex;
    gap: 15px;
  }

  .combo-comissao {
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .combo-comissao .form-control {
    flex: 1;
  }

  .btn-comissao-info {
    color: #38478b;
    font-size: 20px;
    line-height: 1;
    cursor: pointer;
  }

  .btn-comissao-info:hover,
  .btn-comissao-info:focus {
    color: #6c5ce7;
  }

  /* Fluxograma da modal de comissoes */
  .modal-comissao {
    width: 560px;
    max-width: 95%;
  }

  .modal-comissao .modal-body {
    max-height: 72vh;
    overflow-y: auto;
  }

  .fc {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 4px 0;
  }

  .fc-node {
    width: 230px;
    background: #f7f9fc;
    border: 1px solid #d6deea;
    border-radius: 6px;
    padding: 8px 12px;
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    color: #1f2d69;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
  }

  .fc-node small {
    display: block;
    margin-top: 2px;
    font-size: 11px;
    font-weight: 400;
    color: #6c757d;
    line-height: 1.3;
  }

  .fc-start {
    background: #efeafd;
    border-color: #c8bdf2;
    border-radius: 22px;
  }

  .fc-end {
    background: #eafaf1;
    border-color: #b6e6c8;
    border-radius: 22px;
  }

  .fc-ok {
    width: 100%;
    background: #f3faf5;
    border-color: #c3e6cf;
  }


  /* Conector vertical com seta */
  .fc-conn {
    width: 2px;
    height: 22px;
    background: #c2ccda;
    position: relative;
  }

  .fc-conn:after {
    content: "";
    position: absolute;
    bottom: -1px;
    left: 50%;
    transform: translateX(-50%);
    border-left: 5px solid transparent;
    border-right: 5px solid transparent;
    border-top: 6px solid #c2ccda;
  }

  /* Losango de decisao */
  .fc-decision {
    width: 96px;
    height: 96px;
    transform: rotate(45deg);
    background: #fff7e6;
    border: 2px solid #f0ad4e;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
  }

  .fc-decision span {
    transform: rotate(-45deg);
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    color: #8a6d3b;
    line-height: 1.2;
  }

  /* Nota de cancelamento (apos o fluxo) */
  .fc-nota {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin: 14px auto 2px;
    max-width: 480px;
    background: #fdecea;
    border: 1px solid #f1b0ab;
    border-left: 4px solid #d9534f;
    border-radius: 6px;
    padding: 10px 12px;
  }

  .fc-nota i {
    color: #d9534f;
    font-size: 16px;
    margin-top: 2px;
  }

  .fc-nota strong {
    display: block;
    color: #a94442;
    font-size: 13px;
  }

  .fc-nota span {
    font-size: 12px;
    color: #6c757d;
  }

  /* Ramificacoes SIM / NAO */
  .fc-branches {
    display: flex;
    gap: 24px;
    justify-content: center;
    width: 100%;
  }

  .fc-branch {
    flex: 0 1 230px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .fc-blabel {
    font-size: 10px;
    font-weight: 700;
    padding: 1px 10px;
    border-radius: 10px;
    color: #fff;
    margin-top: 6px;
  }

  .fc-sim {
    background: #28a745;
  }

  .fc-nao {
    background: #d9534f;
  }

  .radio-group label {
    display: flex;
    align-items: center;
    white-space: nowrap;
    font-weight: normal !important;
  }

  .x_title h2 {
    color: #38478b;
  }

  .x_title h2 i {
    color: #283468;
  }

  .section-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2d69;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #95a9fa;
    position: relative;
  }

  .section-title:before {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 5%;
    height: 2px;
    background: #1f2d69;
  }

  .section-title i {
    color: #2d3e8a;
    margin-right: 8px;
  }

  .param-box {
    background: linear-gradient(to bottom, #f8f7ff 0%, #ffffff 100%);
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.1);
  }

  .param-item {
    display: flex;
    align-items: center;
    padding: 12px 15px;
    background: white;
    border-radius: 6px;
    margin-bottom: 10px;
    border: 1px solid #e9e4ff;
    transition: all 0.3s;
  }

  .param-item:last-child {
    margin-bottom: 0;
  }

  .param-item:hover {
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
    border-color: #667eea;
    transform: translateY(-2px);
  }

  .param-item .param-label {
    margin: 0;
    font-weight: 500;
    color: #4b5563;
    flex: 1;
  }

  .param-item .param-label i {
    color: #1f2d69;
    margin-right: 8px;
  }

  .param-item .radio-group {
    display: flex;
    gap: 20px;
  }

  .tributos-box {
    background: white;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    border: 2px solid #e9e4ff;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  .tributos-box label {
    color: #4b5563;
    font-weight: 500;
  }

  .tributo-item {
    background: linear-gradient(to bottom, rgb(243, 241, 241) 0%, rgb(216, 216, 216) 100%);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 15px;
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  .tributo-item:last-child {
    margin-bottom: 0;
  }

  .tributo-item label i {
    color: #1f2d69;
  }

  .input-with-icon {
    position: relative;
  }

  .input-icon-right {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    font-weight: 600;
    color: #1f2d69;
  }

  .form-control.has-icon-right {
    padding-right: 35px;
  }

  .form-control:focus,
  .form-control:focus-within {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
  }

  .info-card {
    background: rgb(255, 248, 248);
    border-radius: 8px;
    padding: 20px;
    border: 2px solid #e9e4ff;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.08);
  }

  .fluxo-pedido-tree {
    font-size: 13px;
  }

  .fluxo-palette {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    padding: 14px;
    background: #fff;
    border: 2px dashed #c7d2fe;
    border-radius: 8px;
    margin-bottom: 18px;
  }

  .fluxo-palette-title {
    width: 100%;
    font-size: 12px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 2px;
  }

  .fluxo-modulo {
    padding: 8px 14px;
    border-radius: 20px;
    border: 2px solid #e9e4ff;
    background: #fff;
    color: #374151;
    cursor: grab;
    user-select: none;
    font-weight: 500;
    transition: all 0.2s;
  }

  .fluxo-modulo:hover {
    border-color: #667eea;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
  }

  .fluxo-modulo.fluxo-modulo-ativo {
    border-color: #1f2d69;
    background: linear-gradient(to bottom, #f8f7ff, #eef2ff);
    color: #1f2d69;
    font-weight: 600;
  }

  .fluxo-modulo.fluxo-modulo-dragging {
    opacity: 0.45;
  }

  .fluxo-canvas {
    border-radius: 8px;
    background: #fafbff;
    padding: 12px;
    border: 1px solid #e9e4ff;
  }

  .fluxo-phase {
    margin-bottom: 4px;
  }

  .fluxo-phase-label {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #6b7280;
    margin-bottom: 10px;
    padding-left: 4px;
  }

  .fluxo-phase-label i {
    color: #1f2d69;
    margin-right: 6px;
  }

  .fluxo-track-scroll {
    overflow-x: auto;
    overflow-y: visible;
    padding: 4px 2px 12px;
    -webkit-overflow-scrolling: touch;
  }

  .fluxo-track-scroll::-webkit-scrollbar {
    height: 8px;
  }

  .fluxo-track-scroll::-webkit-scrollbar-thumb {
    background: #c7d2fe;
    border-radius: 4px;
  }

  .fluxo-row {
    display: flex;
    flex-direction: row;
    align-items: stretch;
    gap: 0;
    min-width: min-content;
    padding: 0 4px;
  }

  .fluxo-v-link {
    display: flex;
    justify-content: center;
    align-items: center;
    color: #9ca3af;
    font-size: 22px;
    padding: 6px 0;
    position: relative;
  }

  .fluxo-v-link::before {
    content: '';
    position: absolute;
    left: 50%;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9e4ff;
    transform: translateX(-50%);
    z-index: 0;
  }

  .fluxo-v-link i {
    position: relative;
    z-index: 1;
    background: #fafbff;
    padding: 0 8px;
  }

  .fluxo-step {
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 0 0 auto;
    min-width: 128px;
    max-width: 160px;
  }

  .fluxo-step.fluxo-step-pivot {
    min-width: 300px;
    max-width: 420px;
    flex: 1 1 auto;
  }

  .fluxo-step.fluxo-step-compact {
    min-width: 100px;
    max-width: 120px;
  }

  .fluxo-arrow-right {
    display: flex;
    align-items: center;
    align-self: center;
    flex-shrink: 0;
    color: #9ca3af;
    font-size: 20px;
    padding: 0 4px;
    line-height: 1;
  }

  .fluxo-node {
    width: 100%;
    min-height: 84px;
    padding: 10px 12px;
    border-radius: 8px;
    border: 2px solid #e9e4ff;
    background: #fff;
    text-align: center;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 100%;
    box-sizing: border-box;
  }

  .fluxo-node.fluxo-node-ativo {
    border-color: #1f2d69;
    background: linear-gradient(to bottom, #f8f7ff 0%, #eef2ff 100%);
    box-shadow: 0 2px 10px rgba(31, 45, 105, 0.12);
  }

  .fluxo-node.fluxo-node-sit {
    cursor: pointer;
  }

  .fluxo-node.fluxo-node-sit:hover {
    border-color: #667eea;
  }

  .fluxo-node.fluxo-node-info {
    background: #f9fafb;
    border-style: dashed;
    color: #6b7280;
    font-size: 12px;
  }

  .fluxo-node-title {
    font-weight: 600;
    color: #1f2d69;
    display: block;
    font-size: 12px;
    line-height: 1.3;
  }

  .fluxo-step-compact .fluxo-node-title {
    font-size: 11px;
  }

  .fluxo-node-sub {
    font-size: 11px;
    color: #6b7280;
    margin-top: 4px;
    display: block;
  }

  .fluxo-node-hint {
    font-size: 10px;
    color: #9ca3af;
    margin-top: 6px;
    display: block;
  }

  .fluxo-dropzone {
    min-height: 84px;
    width: 100%;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 8px;
    color: #9ca3af;
    font-size: 11px;
    line-height: 1.35;
    text-align: center;
    background: #fafafa;
    transition: all 0.2s;
    box-sizing: border-box;
    height: 100%;
  }

  .fluxo-dropzone.fluxo-dropzone-over {
    border-color: #667eea;
    background: #eef2ff;
    color: #1f2d69;
  }

  .fluxo-dropzone.fluxo-dropzone-filled {
    border-style: solid;
    border-color: #1f2d69;
    background: linear-gradient(to bottom, #f8f7ff, #eef2ff);
    color: #1f2d69;
    font-weight: 600;
  }

  .fluxo-dropzone .fluxo-drop-remove {
    margin-left: 10px;
    cursor: pointer;
    color: #ef4444;
    font-size: 14px;
  }

  .fluxo-pivot {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 10px;
    border: 2px solid #e9e4ff;
    border-radius: 8px;
    background: #fff;
    height: 100%;
    box-sizing: border-box;
  }

  .fluxo-pivot-header {
    font-size: 10px;
    font-weight: 600;
    color: #6b7280;
    text-align: center;
    padding-bottom: 6px;
    border-bottom: 1px dashed #e9e4ff;
  }

  .fluxo-pivot-header i {
    color: #1f2d69;
    margin-right: 4px;
  }

  .fluxo-path-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 4px;
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #e9e4ff;
    background: #fafafa;
    min-height: 36px;
  }

  .fluxo-path-row.fluxo-path-row-ativo {
    border-color: #c7d2fe;
    background: linear-gradient(to right, #f8f7ff, #fff);
  }

  .fluxo-path-row.fluxo-path-row-off {
    opacity: 0.72;
    border-style: dashed;
  }

  .fluxo-path-tag {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    color: #6b7280;
    min-width: 72px;
    flex-shrink: 0;
  }

  .fluxo-chip {
    padding: 4px 8px;
    border-radius: 4px;
    background: #eef2ff;
    color: #1f2d69;
    font-size: 10px;
    font-weight: 500;
    white-space: nowrap;
  }

  .fluxo-chip.fluxo-chip-ativo {
    background: #1f2d69;
    color: #fff;
  }

  .fluxo-chip.fluxo-chip-warn {
    background: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
  }

  .fluxo-chip.fluxo-chip-info {
    background: #f3f4f6;
    color: #6b7280;
  }

  .fluxo-chip-arrow {
    color: #9ca3af;
    font-size: 11px;
    flex-shrink: 0;
  }

  .fluxo-path-drop {
    width: 100%;
    margin-top: 4px;
  }

  .fluxo-path-drop .fluxo-dropzone {
    min-height: 56px;
    font-size: 10px;
  }

  .fluxo-merge-note {
    font-size: 10px;
    color: #6b7280;
    text-align: center;
    font-style: italic;
    padding: 2px 8px;
  }

  .fluxo-sit-picker {
    position: absolute;
    left: 0;
    right: 0;
    top: calc(100% + 4px);
    z-index: 200;
    min-width: 200px;
    max-height: 200px;
    overflow-y: auto;
    background: #fff;
    border: 2px solid #e9e4ff;
    border-radius: 8px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    display: none;
  }

  .fluxo-sit-picker.fluxo-sit-picker-open {
    display: block;
  }

  .fluxo-sit-opt {
    padding: 8px 12px;
    cursor: pointer;
    text-align: left;
    font-size: 12px;
    border-bottom: 1px solid #f3f4f6;
  }

  .fluxo-sit-opt:hover,
  .fluxo-sit-opt.fluxo-sit-opt-sel {
    background: #eef2ff;
    color: #1f2d69;
    font-weight: 600;
  }

  .param-field-label {
    color: #374151;
    margin-bottom: 8px;
    display: block;
    font-weight: 600;
    font-size: 12px;
  }

  .param-field-box {
    margin-bottom: 0;
  }

  .fluxo-hidden-fields {
    display: none;
  }

  .fluxo-config-panel {
    display: none;
    margin: 14px 0 0;
    padding: 0 4px;
  }

  .fluxo-config-panel.fluxo-config-panel-visivel {
    display: block;
  }

  #fluxoDescontoConfigAnchor.fluxo-config-panel {
    max-width: 440px;
  }

  #fluxoSitConfigAnchor.fluxo-config-panel {
    max-width: 360px;
  }

  .fluxo-config-panel-label {
    display: block;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #6366f1;
    margin-bottom: 8px;
  }

  .fluxo-node-sit.fluxo-node-sit-editando {
    border-color: #1f2d69 !important;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.35);
  }

  .fluxo-desconto-config-anchor {
    display: none;
    margin: 14px 0 0;
    padding: 0 4px;
  }

  .fluxo-desconto-config-anchor.fluxo-desconto-config-visivel {
    display: block;
  }

  .fluxo-desconto-config-anchor.fluxo-desconto-config-visivel::before {
    content: 'Módulo Desconto / Aprovação — configuração';
    display: block;
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #6366f1;
    margin-bottom: 8px;
  }

  .fluxo-desconto-params,
  .fluxo-desconto-config {
    margin-top: 0;
    padding: 0;
    background: transparent;
    border: none;
    border-radius: 0;
  }

  .fluxo-desconto-step {
    min-width: 140px;
    max-width: 200px;
    flex: 0 1 180px;
  }

  .fluxo-desconto-step.fluxo-desconto-step-ativo {
    min-width: 160px;
    max-width: 260px;
    flex: 0 1 220px;
  }

  .fluxo-dropzone.fluxo-dropzone-desconto {
    min-height: 64px;
    text-align: left;
    padding: 10px 12px;
    position: relative;
  }

  .fluxo-dropzone-desconto.fluxo-dropzone-filled {
    background: linear-gradient(135deg, #f8f7ff 0%, #eef2ff 100%);
    border-color: #a5b4fc;
  }

  .fluxo-dropzone-desconto .fluxo-drop-remove {
    position: absolute;
    top: 6px;
    right: 8px;
  }

  .fluxo-dropzone-desconto-title {
    font-size: 11px;
    font-weight: 700;
    color: #1f2d69;
    margin-bottom: 6px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
  }

  .fluxo-dropzone-desconto-title i {
    color: #6366f1;
    margin-right: 4px;
  }

  .fluxo-dropzone-desconto .fluxo-desconto-resumo {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    padding-right: 18px;
  }

  .fluxo-dropzone-desconto .fluxo-desconto-resumo-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10px;
    font-weight: 600;
    color: #1f2d69;
    background: #fff;
    border: 1px solid #c7d2fe;
    border-radius: 12px;
    padding: 3px 8px;
    white-space: nowrap;
  }

  .fluxo-dropzone-desconto .fluxo-desconto-resumo-tag i {
    color: #6366f1;
    font-size: 10px;
  }

  .fluxo-desconto-config-inner {
    margin-top: 10px;
    padding: 12px;
    background: #fff;
    border: 1px solid #e0e7ff;
    border-radius: 8px;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
  }

  .fluxo-desconto-config-header {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 700;
    color: #1f2d69;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    margin-bottom: 12px;
    padding-bottom: 8px;
    border-bottom: 1px solid #e9e4ff;
  }

  .fluxo-desconto-config-header i {
    color: #6366f1;
  }

  .fluxo-desconto-config-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
  }

  .fluxo-field-aprovacao {
    grid-column: 1 / -1;
  }

  .fluxo-field-aprovacao .fluxo-aprovacao-list {
    max-width: 100%;
  }

  .fluxo-field {
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .fluxo-field-full {
    grid-column: 1 / -1;
  }

  .fluxo-field label {
    margin: 0;
    font-size: 12px;
    font-weight: 600;
    color: #374151;
  }

  .fluxo-field label i {
    color: #6366f1;
    margin-right: 4px;
  }

  .fluxo-field-hint {
    font-size: 10px;
    color: #9ca3af;
    line-height: 1.3;
  }

  .fluxo-aprovacao-list {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
  }

  .fluxo-aprovacao-linha {
    display: flex;
    align-items: flex-start;
    gap: 6px;
    margin: 0;
    padding: 6px 8px;
    border: 1px solid #e9e4ff;
    border-radius: 6px;
    background: #fafbff;
    cursor: pointer;
    font-weight: normal;
    transition: border-color 0.2s, background 0.2s;
  }

  .fluxo-aprovacao-linha:hover {
    border-color: #a5b4fc;
    background: #f8f7ff;
  }

  .fluxo-aprovacao-linha input[type="radio"] {
    margin: 2px 0 0;
    flex-shrink: 0;
    cursor: pointer;
  }

  .fluxo-aprovacao-texto {
    font-size: 11px;
    color: #4b5563;
    line-height: 1.35;
  }

  .fluxo-aprovacao-texto strong {
    color: #1f2d69;
    font-weight: 600;
  }

  .fluxo-aprovacao-linha.fluxo-aprovacao-linha-sel,
  .fluxo-aprovacao-linha:has(input[type="radio"]:checked) {
    border-color: #1f2d69;
    background: #eef2ff;
  }

  .fluxo-help-panel {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 10px;
    margin-bottom: 16px;
    padding: 12px 14px;
    background: #fff;
    border: 1px solid #e9e4ff;
    border-radius: 8px;
  }

  .fluxo-help-item {
    font-size: 12px;
    color: #4b5563;
    line-height: 1.45;
  }

  .fluxo-help-item strong {
    display: block;
    color: #1f2d69;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    margin-bottom: 4px;
  }

  .fluxo-help-hint {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #e9e4ff;
    font-size: 11px;
    color: #6b7280;
    grid-column: 1 / -1;
  }

  @media (max-width: 767px) {
    .fluxo-desconto-config-grid,
    .fluxo-aprovacao-list {
      grid-template-columns: 1fr;
    }

    #fluxoDescontoConfigAnchor.fluxo-config-panel,
    #fluxoSitConfigAnchor.fluxo-config-panel {
      max-width: 100%;
    }

    .fluxo-desconto-step.fluxo-desconto-step-ativo {
      max-width: 100%;
    }
  }

  select.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  select.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  input.form-control,
  textarea.form-control {
    border: 2px solid #e9e4ff;
    transition: all 0.3s;
  }

  input.form-control:focus,
  textarea.form-control:focus {
    border-color: #1f2d69;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15);
  }

  .x_panel {
    border-top: 4px solid #1f2d69;
    box-shadow: 0 4px 20px rgba(0, 35, 192, 0.1);
  }

  .control-label {
    color: #414852;
    font-weight: 400;
    font-size: 14px;
  }

  .swal-modal {
    width: 550px !important;
  }

  .param-note {
    font-size: 12px;
    color: #6b7280;
    margin-top: 6px;
  }

  table {
    border-spacing: 0;
    border-collapse: none !important;
  }

  .table-bordered>thead>tr>th {
    border-radius: 7px !important;
    padding: 5px !important;
  }

  .x_panel,
  [name=datatable-buttons_length],
  [type=search] {
    border-radius: 5px;
  }

  .param-tip {
    color: #9ca3af;
    margin-left: 4px;
    cursor: help;
    font-size: 13px;
    vertical-align: middle;
  }
  .param-tip:hover { color: #1f2d69; }
</style>

<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/ped/s_parametro.js"></script>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

    <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
      ACTION="{$SCRIPT_NAME}" METHOD="post">
      <input name=mod type=hidden value="ped">
      <input name=form type=hidden value="parametro">
      <input name=submenu type=hidden value={$subMenu}>

      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>
                {if $subMenu eq "cadastrar"}
                  <i class="fa fa-plus-circle"></i> FAT Par&acirc;metro - Cadastro
                {else}
                  <i class="fa fa-edit"></i> FAT Par&acirc;metro - Altera&ccedil;&atilde;o
                {/if}
              </h2>

              <ul class="nav navbar-right panel_toolbox">
                <li>
                  <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('parametro');">
                    <i class="fa fa-save"></i> Confirmar
                  </button>
                </li>
                <li>
                  <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('parametro');">
                    <i class="fa fa-arrow-left"></i> Voltar
                  </button>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>

            <div class="x_content">

              <!-- DADOS GERAIS -->
              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-info-circle"></i> Dados Gerais</h4>

                <div class="row">
                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="tributo-item param-field-box">
                      <label class="param-field-label" for="filial">
                        <i class="fa fa-building"></i> Empresa (centro de custo)
                        <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Empresa vinculada ao centro de custo (AMB_EMPRESA.CENTROCUSTO)."></i>
                      </label>
                      <select id="filial" class="form-control input-sm" {if $subMenu neq "cadastrar"}disabled{/if} {if $subMenu eq "cadastrar"}name="filial"{/if}>
                        {html_options values=$filial_ids selected=$filial output=$filial_names}
                      </select>
                      {if $subMenu neq "cadastrar"}
                      <input type="hidden" name="filial" value="{$filial}">
                      {/if}
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="tributo-item param-field-box">
                      <label class="param-field-label" for="grupoServico">
                        <i class="fa fa-tags"></i> Grupo Servi&ccedil;o
                        <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Grupo padrão para itens de serviço no pedido."></i>
                      </label>
                      <input class="form-control input-sm" type="text" maxlength="15" name="grupoServico" id="grupoServico" value="{$grupoServico}">
                    </div>
                  </div>

                  <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="tributo-item param-field-box">
                      <label class="param-field-label" for="valorPedMinimo">
                        <i class="fa fa-usd"></i> Valor Pedido M&iacute;nimo
                        <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Valor mínimo para aceitar um pedido."></i>
                      </label>
                      <input class="form-control input-sm money" type="text" maxlength="13" name="valorPedMinimo" id="valorPedMinimo" value="{$valorPedMinimo}">
                    </div>
                  </div>
                </div>
              </div>

              <!-- FLUXO DO PEDIDO PS (controle interativo) -->
              <div class="param-box fluxo-pedido-tree-box">
                <h4 class="section-title"><i class="fa fa-sitemap"></i> Fluxo do pedido PS</h4>
                <div class="fluxo-help-panel">
                  <div class="fluxo-help-item">
                    <strong>Fase 1 — Cota&ccedil;&atilde;o e estoque</strong>
                    Digita&ccedil;&atilde;o/cota&ccedil;&atilde;o, confirma&ccedil;&atilde;o, desconto (opcional), reserva ou encomenda.
                  </div>
                  <div class="fluxo-help-item">
                    <strong>Fase 2 — Faturamento</strong>
                    Financeiro, ger&ecirc;ncia/expedi&ccedil;&atilde;o, emiss&atilde;o de NF e baixa.
                  </div>
                  <div class="fluxo-help-hint">
                    <i class="fa fa-hand-pointer-o"></i>
                    Arraste m&oacute;dulos opcionais para as zonas tracejadas (padr&atilde;o: <strong>Encomenda</strong> e <strong>Desconto/Aprova&ccedil;&atilde;o</strong> desativados).
                    Clique nos n&oacute;s de situa&ccedil;&atilde;o ou no m&oacute;dulo Desconto/Aprova&ccedil;&atilde;o para configurar nos pain&eacute;is abaixo do fluxo.
                  </div>
                </div>
                <div id="fluxoPedidoTree" class="fluxo-pedido-tree" aria-live="polite"></div>

                <div id="fluxoSitConfigAnchor" class="fluxo-config-panel">
                  <span class="fluxo-config-panel-label">Situa&ccedil;&atilde;o do fluxo &mdash; configura&ccedil;&atilde;o</span>
                  <div class="fluxo-desconto-config-inner">
                    <div class="fluxo-desconto-config-header">
                      <i class="fa fa-flag"></i>
                      <span id="fluxoSitConfigTitulo">Selecione um n&oacute; no fluxo</span>
                    </div>
                    <div class="fluxo-field">
                      <label for="fluxoSitConfigSelect">
                        <i class="fa fa-list"></i> Situa&ccedil;&atilde;o
                      </label>
                      <select class="form-control input-sm" id="fluxoSitConfigSelect">
                        <option value="">—</option>
                      </select>
                      <span class="fluxo-field-hint">Clique em um n&oacute; do fluxo (Cota&ccedil;&atilde;o, Emitir NF ou Baixado) para alterar.</span>
                    </div>
                  </div>
                </div>

                <div id="fluxoDescontoConfigAnchor" class="fluxo-config-panel{if $controleDesconto eq 'S'} fluxo-config-panel-visivel{/if}">
                  <span class="fluxo-config-panel-label">M&oacute;dulo Desconto / Aprova&ccedil;&atilde;o &mdash; configura&ccedil;&atilde;o</span>
                <div id="fluxoDescontoConfigWrap" class="fluxo-desconto-config"{if $controleDesconto neq 'S'} style="display:none;"{/if}>
                  <div class="fluxo-desconto-config-inner">
                    <div class="fluxo-desconto-config-header">
                      <i class="fa fa-sliders"></i>
                      <span>Configurar desconto e aprova&ccedil;&atilde;o</span>
                    </div>
                    <div class="fluxo-desconto-config-grid">
                      <div class="fluxo-field">
                        <label for="descontoMaximo">
                          <i class="fa fa-percent"></i> Desconto m&aacute;ximo
                        </label>
                        <div class="input-with-icon">
                          <input class="form-control input-sm money has-icon-right" type="text" maxlength="13" name="descontoMaximo" id="descontoMaximo" value="{$descontoMaximo}" placeholder="0,00">
                          <span class="input-icon-right">%</span>
                        </div>
                        <span class="fluxo-field-hint">Limite permitido na confirma&ccedil;&atilde;o do pedido.</span>
                      </div>
                      <div class="fluxo-field">
                        <label for="tipoDesconto">
                          <i class="fa fa-tag"></i> Tipo de desconto
                        </label>
                        <select class="form-control input-sm" name="tipoDesconto" id="tipoDesconto">
                          <option value="T" {if $tipoDesconto eq 'T' || $tipoDesconto eq ''}selected{/if}>T &mdash; Total do pedido</option>
                          <option value="L" {if $tipoDesconto eq 'L'}selected{/if}>L &mdash; Por item</option>
                        </select>
                        <span class="fluxo-field-hint">Base de c&aacute;lculo do percentual de desconto.</span>
                      </div>
                      <div class="fluxo-field fluxo-field-full fluxo-field-aprovacao" name="aprovacaoB">
                        <label>
                          <i class="fa fa-thumbs-up"></i> Aprova&ccedil;&atilde;o gerencial
                        </label>
                        <div class="fluxo-aprovacao-list">
                          <label class="fluxo-aprovacao-linha{if $aprovacao eq 'N' || $aprovacao eq ''} fluxo-aprovacao-linha-sel{/if}">
                            <input type="radio" name="aprovacao" value="N"
                              {if $aprovacao eq 'N' || $aprovacao eq ''}checked="checked"{/if}>
                            <span class="fluxo-aprovacao-texto">
                              <strong>N&atilde;o</strong> &mdash; bloqueia a confirma&ccedil;&atilde;o quando o desconto ultrapassar o m&aacute;ximo.
                            </span>
                          </label>
                          <label class="fluxo-aprovacao-linha{if $aprovacao eq 'S'} fluxo-aprovacao-linha-sel{/if}">
                            <input type="radio" name="aprovacao" value="S"
                              {if $aprovacao eq 'S'}checked="checked"{/if}>
                            <span class="fluxo-aprovacao-texto">
                              <strong>Sim</strong> &mdash; envia para Pedido Aprova&ccedil;&atilde;o (sit. 10) se o desconto exceder o limite.
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                </div>

                <div id="fluxoPedidoHiddenFields" class="fluxo-hidden-fields">
                  <input type="hidden" name="sitAberto" id="fluxo_hf_sitAberto" value="{$sitAberto}">
                  <input type="hidden" name="sitBaixado" id="fluxo_hf_sitBaixado" value="{$sitBaixado}">
                  <input type="hidden" name="sitEmitirNf" id="fluxo_hf_sitEmitirNf" value="{$sitEmitirNf}">
                  <input type="hidden" name="encomenda" id="fluxo_hf_encomenda" value="{$encomenda}">
                  <input type="hidden" name="fluxoPedido" id="fluxo_hf_fluxoPedido" value="{$fluxoPedido}">
                  <input type="hidden" name="faturaPedido" id="fluxo_hf_faturaPedido" value="{$faturaPedido}">
                  <input type="hidden" name="lancPedBaixado" id="fluxo_hf_lancPedBaixado" value="{$lancPedBaixado}">
                  <input type="hidden" name="controleDesconto" id="fluxo_hf_controleDesconto" value="{$controleDesconto}">
                </div>

                <p class="param-note" style="margin-top:14px;font-size:11px;color:#6b7280;">
                  Separa&ccedil;&atilde;o parametriz&aacute;vel: &eacute;pico futuro. Confer&ecirc;ncia via romaneio na Ger&ecirc;ncia de Pedidos.
                </p>
              </div>

              <script type="application/json" id="fluxoPedidoCfgData">{ldelim}
                "situacoes": [
                  {section name=fs loop=$pedido_ids}
                  {ldelim}"id":"{$pedido_ids[fs]|escape:'javascript'}","text":"{$pedido_names[fs]|escape:'javascript'}"{rdelim}{if !$smarty.section.fs.last},{/if}
                  {/section}
                ],
                "valores": {ldelim}
                  "sitAberto": "{$sitAberto|escape:'javascript'}",
                  "sitBaixado": "{$sitBaixado|escape:'javascript'}",
                  "sitEmitirNf": "{$sitEmitirNf|escape:'javascript'}",
                  "encomenda": "{$encomenda|escape:'javascript'}",
                  "fluxoPedido": "{$fluxoPedido|escape:'javascript'}",
                  "faturaPedido": "{$faturaPedido|escape:'javascript'}",
                  "lancPedBaixado": "{$lancPedBaixado|escape:'javascript'}",
                  "controleDesconto": "{$controleDesconto|escape:'javascript'}"
                {rdelim}
              {rdelim}</script>

              <!-- CONFIGURAÇÕES NUMÉRICAS -->
              <div class="info-card">
                <h4 class="section-title"><i class="fa fa-calculator"></i> Configura&ccedil;&otilde;es Num&eacute;ricas</h4>
                <div class="row">
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-sort-numeric-asc"></i> Casas Decimais
                        <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Quantidade de casas decimais nos valores do pedido."></i>
                      </label>
                      <select name="casasDecimais" class="form-control input-sm">
                        <option value="2" {if $casasDecimais == 2}selected{/if}>2</option>
                        <option value="4" {if $casasDecimais == 4 || $casasDecimais == ''}selected{/if}>4</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-user"></i> Controle Vendedor
                        <i class="fa fa-info-circle param-tip" data-toggle="tooltip" title="Restringe o pedido ao vendedor logado."></i>
                      </label>
                      <select name="controleVendedor" class="form-control input-sm">
                        <option value="0" {if $controleVendedor == 0 || $controleVendedor == ''}selected{/if}>N&atilde;o controla</option>
                        <option value="1" {if $controleVendedor == 1}selected{/if}>Controla vendedor</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="tributo-item">
                      <label style="color: #374151; margin-bottom: 8px; display: block;">
                        <i class="fa fa-percent"></i> Tipo de Comiss&atilde;o
                      </label>
                      <div class="combo-comissao">
                        <select name="tipoComissao" class="form-control input-sm">
                          <option value="1" {if $tipoComissao == 1 || $tipoComissao == ''}selected{/if}>Faturamento</option>
                          <option value="2" {if $tipoComissao == 2}selected{/if}>Recebimento</option>
                        </select>
                        <a href="javascript:void(0)" class="btn-comissao-info" data-toggle="modal" data-target="#ModalComissao" title="Como funcionam as comiss&otilde;es?">
                          <i class="fa fa-info-circle"></i>
                        </a>
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

  </div>
</div>

<!-- MODAL EXPLICATIVA DAS COMISSOES (somente front) -->
<div class="modal fade" id="ModalComissao" tabindex="-1" role="dialog" aria-labelledby="ModalComissao" aria-hidden="true">
  <div class="modal-dialog modal-comissao" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="fa fa-sitemap"></i> Como funcionam as comiss&otilde;es</h4>
      </div>

      <div class="modal-body">
        <div class="fc">

          <div class="fc-node fc-start">
            Tipo de comiss&atilde;o
            <small>Faturamento ou Recebimento</small>
          </div>
          <div class="fc-conn"></div>

          <div class="fc-node">
            Define os percentuais
            <small>Vendedor e Grupo do produto</small>
          </div>
          <div class="fc-conn"></div>

          <div class="fc-decision">
            <span>Grupo tem<br>% &gt; 0 ?</span>
          </div>
          <div class="fc-branches">
            <div class="fc-branch">
              <span class="fc-blabel fc-nao">N&Atilde;O</span>
              <div class="fc-conn"></div>
              <div class="fc-node fc-ok">Usa o <strong>% do vendedor</strong></div>
            </div>
            <div class="fc-branch">
              <span class="fc-blabel fc-sim">SIM</span>
              <div class="fc-conn"></div>
              <div class="fc-node fc-ok">Usa o <strong>% do grupo</strong></div>
            </div>
          </div>
          <div class="fc-conn"></div>

          <div class="fc-node">
            Grava no item do pedido
            <small>percentual (%) e base (R$)</small>
          </div>
          <div class="fc-conn"></div>

          <div class="fc-node fc-end">
            Apura&ccedil;&atilde;o no Relat&oacute;rio
            <small>Pedido, Emitir NF e Pedido baixado<br>Valor = base &times; % &divide; 100</small>
          </div>

        </div>

        <div class="fc-nota">
          <i class="fa fa-exclamation-triangle"></i>
          <div>
            <strong>Cancelamento do pedido</strong>
            <span>Ao cancelar, o percentual e a base do item s&atilde;o zerados &mdash; o item deixa de gerar comiss&atilde;o.</span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

{include file="template/form.inc"}
<script src="{$bootstrap}/input_mask/jquery.maskMoney.js"></script>
<script>
// Registra o gatilho da modal informativa de forma independente, para nao
// depender da execucao do restante do ready (evita que falha de plugin aborte).
initModalInfoCampo();

$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip({ container: 'body' });

  if ($.fn.maskMoney) {
    $(".money").maskMoney({
     decimal: ",",
     thousands: ".",
     allowNegative: true,
     allowZero: true
    });
  }

  {if $swalText}
  Swal.fire({
    icon: '{$swalIcon}',
    title: '{$swalTitle|escape:'javascript'}',
    text: '{$swalText|escape:'javascript'}',
    width: 510,
    {if $swalAutoClose}
    timer: 3000,
    showConfirmButton: false
    {else}
    confirmButtonText: 'OK'
    {/if}
  });
  {/if}
});
</script>
