<style>
    .form-control,
    .x_panel {
        border-radius: 5px;
    }

    #usuarioTabs {
        margin-bottom: 12px;
    }

    #tabelaDireitos {
        font-size: 12px;
    }

    #tabelaDireitos thead th {
        text-align: center;
        vertical-align: middle;
        white-space: nowrap;
    }

    #tabelaDireitos tbody td {
        vertical-align: middle;
    }

    #tabelaDireitos .col-programa {
        max-width: 130px;
        width: 130px;
        overflow: hidden;
    }

    #tabelaDireitos .col-programa .prog-titulo {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
        font-size: 11px;
        line-height: 1.25;
    }

    #tabelaDireitos .col-programa .prog-codigo {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 10px;
        color: #888;
    }

    #tabelaDireitos .prog-cell-wrap {
        position: relative;
        padding-right: 14px;
    }

    #tabelaDireitos .dir-prog-help {
        position: absolute;
        right: 0;
        top: 2px;
        font-size: 12px;
        color: #73879c;
        cursor: help;
    }

    #tabelaDireitos .dir-th-acao {
        text-align: center;
        vertical-align: middle !important;
        padding: 4px 2px !important;
        min-width: 56px;
        font-size: 11px;
    }

    #tabelaDireitos .dir-th-acao .dir-help {
        font-size: 13px;
        color: #73879c;
        cursor: help;
        margin-left: 2px;
    }

    #tabelaDireitos .col-todos {
        width: 48px;
        text-align: center;
        vertical-align: middle !important;
        padding: 4px 2px !important;
    }

    #tabelaDireitos .btn-toggle-prog-dir {
        font-size: 10px;
        padding: 2px 5px;
        line-height: 1.2;
    }

    #tabelaDireitos .col-chk {
        text-align: center;
        width: 36px;
        padding: 4px !important;
    }

    .dir-herdado {
        display: block;
        font-size: 11px;
        color: #888;
        margin-top: 2px;
    }

    .dir-wrap-direitos {
        max-height: 420px;
        overflow-y: auto;
    }

    .usuario-msg-top {
        margin-bottom: 10px;
    }

    .usuario-senha-painel {
        margin-bottom: 12px;
        border-radius: 5px;
        overflow: hidden;
    }

    .usuario-senha-painel .panel-heading {
        cursor: pointer;
        user-select: none;
        background: #f7f7f7;
        border-color: #ddd;
    }

    .usuario-senha-painel .panel-heading .glyphicon-chevron-down {
        transition: transform .2s ease;
    }

    .usuario-senha-painel .panel-heading[aria-expanded="true"] .glyphicon-chevron-down {
        transform: rotate(180deg);
    }

    .usuario-senha-painel .panel-body {
        padding-top: 12px;
    }

    .usuario-tab-pane {
        padding: 14px 6px 6px;
    }

    .usuario-tab-pane > .form-group:last-child {
        margin-bottom: 8px;
    }

    .usuario-secao-titulo {
        font-size: 13px;
        font-weight: 600;
        color: #73879c;
        margin: 4px 0 14px 0;
        padding-bottom: 6px;
        border-bottom: 1px solid #e8e8e8;
    }

    .usuario-tab-pane label {
        font-weight: 600;
        font-size: 12px;
        color: #555;
    }

    .usuario-tab-pane .help-block-campo {
        margin: 4px 0 0;
        font-size: 11px;
        color: #888;
    }

    .usuario-dir-filtro {
        margin-bottom: 12px;
        padding: 10px 12px;
        background: #f9f9f9;
        border: 1px solid #e8e8e8;
        border-radius: 5px;
    }

    .usuario-dir-filtro .dir-filtro-ajuda {
        font-size: 12px;
        color: #666;
        padding-top: 22px;
    }

    @media (max-width: 767px) {
        .usuario-dir-filtro .dir-filtro-ajuda {
            padding-top: 8px;
        }
    }

    .usuario-cadastro-titulo h2 {
        margin: 5px 0 6px;
        line-height: 34px;
    }

    .usuario-cadastro-titulo .panel_toolbox {
        margin-top: 0;
        min-width: auto;
    }

    .usuario-cadastro-titulo .panel_toolbox > li {
        float: left;
    }

    .usuario-cadastro-titulo .panel_toolbox .btn {
        margin-bottom: 0;
    }

    .usuario-dir-intro {
        margin: 0 0 12px;
        padding: 8px 12px;
        font-size: 12px;
        color: #666;
        background: #f5f5f5;
        border-left: 3px solid #73879c;
        border-radius: 0 4px 4px 0;
    }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

<div class="right_col" role="main">
    <div class="clearfix"></div>

    <form id="lancamento" data-parsley-validate
        data-parsley-excluded="input[type=button], input[type=submit], input[type=hidden], .chk-dir, .btn-toggle-prog-dir"
        class="form-horizontal form-label-left" name="lancamento"
        action="{$SCRIPT_NAME}" method="post">
        <input type="hidden" name="mod" value="util">
        <input type="hidden" name="form" value="usuario">
        <input type="hidden" name="submenu" value="{$subMenu}">
        <input type="hidden" name="letra" value="{$letra}">
        <input type="hidden" name="fornecedor" value="">
        <input type="hidden" name="pessoa" value="{$pessoa}">
        <input type="hidden" name="direitos_json" id="direitos_json" value="">
        <input type="hidden" id="ehAlteracao" value="{if $ehAlteracao}1{else}0{/if}">
        <input type="hidden" name="usuario" id="usuario" value="{$usuario|escape:'html'}">
        <input type="hidden" id="proximaMatriculaOperacional" value="{$proximaMatriculaOperacional|escape:'html'}">
        <input type="hidden" id="proximaMatriculaGrupo" value="{$proximaMatriculaGrupo|escape:'html'}">
        <input type="hidden" name="conta" value="{$conta|escape:'html'}">
        <input type="hidden" name="encargos" value="{$encargos|escape:'html'}">
        <input type="hidden" name="generoPgto" value="{$generoPgto|escape:'html'}">
        <input type="hidden" name="ccustoPgto" value="{$ccustoPgto|escape:'html'}">

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title usuario-cadastro-titulo">
                        <h2>
                            {if $subMenu eq "cadastrar"}
                                Usu&aacute;rios - Cadastro
                            {else}
                                Usu&aacute;rios - Altera&ccedil;&atilde;o
                            {/if}
                        </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-primary" onclick="submitConfirmar();">
                                    <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                    <span> Confirmar</span>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="btn btn-danger" onclick="submitVoltar();">
                                    <span class="glyphicon glyphicon-backward" aria-hidden="true"></span>
                                    <span> Voltar</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        {if $mensagem neq ''}
                            <div class="usuario-msg-top">
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="alert alert-success alert-dismissible" role="alert">{$mensagem}</div>
                                {else}
                                    <div class="alert alert-warning alert-dismissible" role="alert">{$mensagem}</div>
                                {/if}
                            </div>
                        {/if}

                        <ul class="nav nav-tabs bar_tabs" id="usuarioTabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tabIdentificacao" role="tab" data-toggle="tab">Identifica&ccedil;&atilde;o</a>
                            </li>
                            <li role="presentation">
                                <a href="#tabAcesso" role="tab" data-toggle="tab">Acesso</a>
                            </li>
                            <li role="presentation">
                                <a href="#tabComercial" role="tab" data-toggle="tab">Comercial</a>
                            </li>
                            <li role="presentation">
                                <a href="#tabEmail" role="tab" data-toggle="tab">E-mail</a>
                            </li>
                            <li role="presentation">
                                <a href="#tabDireitos" role="tab" data-toggle="tab">Direitos</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Identificação -->
                            <div role="tabpanel" class="tab-pane fade active in usuario-tab-pane" id="tabIdentificacao">
                                <p class="usuario-secao-titulo">Dados da pessoa</p>
                                <div class="form-group">
                                    <div class="col-md-7 col-sm-12 col-xs-12">
                                        <label for="nome">Pessoa (conta)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="nome" name="nome"
                                                placeholder="Selecione a conta no CRM" required="required"
                                                value="{$pessoaNome|escape:'html'}">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-primary" title="Pesquisar conta"
                                                    onclick="abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                                </button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-5 col-sm-12 col-xs-12">
                                        <label for="nomeReduzido">Nome reduzido</label>
                                        <input class="form-control" maxlength="15" type="text" id="nomeReduzido"
                                            name="nomeReduzido" placeholder="Exibido no sistema"
                                            value="{$nomeReduzido|escape:'html'}">
                                        <p class="help-block-campo">At&eacute; 15 caracteres.</p>
                                    </div>
                                </div>
                                <p class="usuario-secao-titulo">Empresa</p>
                                <div class="form-group">
                                    <div class="col-md-6 col-sm-8 col-xs-12">
                                        <label for="empresa">Empresa padr&atilde;o</label>
                                        <select class="form-control" name="empresa" id="empresa">
                                            {html_options values=$empresa_ids output=$empresa_names selected=$empresa_id}
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Acesso -->
                            <div role="tabpanel" class="tab-pane fade usuario-tab-pane" id="tabAcesso">
                                <p class="usuario-secao-titulo">Perfil e status</p>
                                <div class="form-group">
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label for="tipo">Tipo</label>
                                        <select class="form-control" name="tipo" id="tipo">
                                            {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <label for="situacao">Situa&ccedil;&atilde;o</label>
                                        <select class="form-control" name="situacao" id="situacao">
                                            {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <label for="grupo">Grupo de direitos</label>
                                        <select class="form-control" name="grupo" id="grupo">
                                            {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                        </select>
                                        <p class="help-block-campo">Usu&aacute;rio tipo Z &eacute; o pr&oacute;prio grupo.</p>
                                    </div>
                                </div>
                                <p class="usuario-secao-titulo">Autentica&ccedil;&atilde;o</p>
                                {if $ehAlteracao}
                                    <div class="form-group">
                                        <div class="col-md-6 col-sm-8 col-xs-12">
                                            <label for="log">Login</label>
                                            <input class="form-control" maxlength="40" type="text" id="log" name="login"
                                                placeholder="Usu&aacute;rio para entrar no sistema"
                                                value="{$login|escape:'html'}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel panel-default usuario-senha-painel">
                                                <div class="panel-heading" role="button" data-toggle="collapse"
                                                    data-target="#painelSenhaAcesso" aria-expanded="false"
                                                    aria-controls="painelSenhaAcesso" id="headingSenhaAcesso">
                                                    <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                                                    Alterar senha de acesso
                                                    <span class="glyphicon glyphicon-chevron-down pull-right" aria-hidden="true"></span>
                                                </div>
                                                <div id="painelSenhaAcesso" class="panel-collapse collapse"
                                                    aria-labelledby="headingSenhaAcesso">
                                                    <div class="panel-body">
                                                        <p class="text-muted" style="margin-bottom: 12px;">
                                                            A senha atual n&atilde;o &eacute; exibida por seguran&ccedil;a.
                                                            Preencha os campos abaixo somente se desejar definir uma nova senha.
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-md-5 col-sm-6 col-xs-12">
                                                                <label for="senha">Nova senha</label>
                                                                <div class="input-group">
                                                                    <input class="form-control" type="password" maxlength="20"
                                                                        id="senha" name="senha" autocomplete="new-password"
                                                                        placeholder="Digite a nova senha">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default"
                                                                            title="Mostrar senha"
                                                                            onclick="toggleSenha('senha', this);">
                                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-5 col-sm-6 col-xs-12">
                                                                <label for="senhaConfirm">Confirmar nova senha</label>
                                                                <div class="input-group">
                                                                    <input class="form-control" type="password" maxlength="20"
                                                                        id="senhaConfirm" name="senhaConfirm"
                                                                        autocomplete="new-password"
                                                                        placeholder="Repita a nova senha">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default"
                                                                            title="Mostrar senha"
                                                                            onclick="toggleSenha('senhaConfirm', this);">
                                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {else}
                                    <div class="form-group" id="blocoSenhaAcesso">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <label for="log">Login</label>
                                            <input class="form-control" maxlength="40" type="text" id="log" name="login"
                                                placeholder="Usu&aacute;rio para entrar no sistema"
                                                value="{$login|escape:'html'}">
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <label for="senha">Senha de acesso</label>
                                            <div class="input-group">
                                                <input class="form-control" type="password" maxlength="20" id="senha"
                                                    name="senha" autocomplete="new-password" required
                                                    placeholder="Senha de acesso">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" title="Mostrar senha"
                                                        onclick="toggleSenha('senha', this);">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <label for="senhaConfirm">Confirmar senha</label>
                                            <div class="input-group">
                                                <input class="form-control" type="password" maxlength="20" id="senhaConfirm"
                                                    name="senhaConfirm" autocomplete="new-password"
                                                    placeholder="Repita a senha">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" title="Mostrar senha"
                                                        onclick="toggleSenha('senhaConfirm', this);">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            <!-- Comercial -->
                            <div role="tabpanel" class="tab-pane fade usuario-tab-pane" id="tabComercial">
                                <p class="usuario-secao-titulo">Comiss&otilde;es e custo</p>
                                <div class="form-group">
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label for="salario">Custo hora (R$)</label>
                                        <input class="form-control money" type="text" id="salario" name="salario"
                                            placeholder="0,00" value="{$salario|escape:'html'}">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label for="comissaoFatura">Comiss&atilde;o no pedido (%)</label>
                                        <input class="form-control money" type="text" id="comissaoFatura" name="comissaoFatura"
                                            placeholder="0,00" value="{$comissaoFatura|escape:'html'}">
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <label for="comissaoReceb">Comiss&atilde;o no recebimento (%)</label>
                                        <input class="form-control money" type="text" id="comissaoReceb" name="comissaoReceb"
                                            placeholder="0,00" value="{$comissaoReceb|escape:'html'}">
                                    </div>
                                </div>
                            </div>

                            <!-- E-mail -->
                            <div role="tabpanel" class="tab-pane fade usuario-tab-pane" id="tabEmail">
                                <p class="usuario-secao-titulo">Configura&ccedil;&atilde;o de envio</p>
                                <div class="form-group">
                                    <div class="col-md-5 col-sm-6 col-xs-12">
                                        <label for="smtp">Servidor SMTP</label>
                                        <input class="form-control" type="text" id="smtp" name="smtp"
                                            placeholder="smtp.exemplo.com.br" value="{$smtp|escape:'html'}">
                                    </div>
                                    <div class="col-md-7 col-sm-6 col-xs-12">
                                        <label for="email">E-mail</label>
                                        <input class="form-control" type="email" id="email" name="email"
                                            placeholder="usuario@empresa.com.br" value="{$email|escape:'html'}">
                                    </div>
                                </div>
                                {if $ehAlteracao}
                                    <p class="usuario-secao-titulo">Credenciais do e-mail</p>
                                    <div class="form-group">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <div class="panel panel-default usuario-senha-painel">
                                                <div class="panel-heading" role="button" data-toggle="collapse"
                                                    data-target="#painelSenhaEmail" aria-expanded="false"
                                                    aria-controls="painelSenhaEmail" id="headingSenhaEmail">
                                                    <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                                                    Alterar senha do e-mail
                                                    <span class="glyphicon glyphicon-chevron-down pull-right" aria-hidden="true"></span>
                                                </div>
                                                <div id="painelSenhaEmail" class="panel-collapse collapse"
                                                    aria-labelledby="headingSenhaEmail">
                                                    <div class="panel-body">
                                                        <p class="text-muted" style="margin-bottom: 12px;">
                                                            A senha do e-mail cadastrada permanece a mesma.
                                                            Preencha o campo abaixo somente para definir uma nova senha.
                                                        </p>
                                                        <div class="row">
                                                            <div class="col-md-4 col-sm-6 col-xs-12">
                                                                <label for="emailsenha">Nova senha do e-mail</label>
                                                                <div class="input-group">
                                                                    <input class="form-control" type="password"
                                                                        id="emailsenha" name="emailsenha"
                                                                        autocomplete="new-password"
                                                                        placeholder="Digite a nova senha do e-mail" value="">
                                                                    <span class="input-group-btn">
                                                                        <button type="button" class="btn btn-default"
                                                                            title="Mostrar senha"
                                                                            onclick="toggleSenha('emailsenha', this);">
                                                                            <span class="glyphicon glyphicon-eye-open"></span>
                                                                        </button>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {else}
                                    <p class="usuario-secao-titulo">Credenciais do e-mail</p>
                                    <div class="form-group" id="blocoSenhaEmail">
                                        <div class="col-md-5 col-sm-6 col-xs-12">
                                            <label for="emailsenha">Senha do e-mail</label>
                                            <div class="input-group">
                                                <input class="form-control" type="password" id="emailsenha"
                                                    name="emailsenha" autocomplete="new-password"
                                                    placeholder="Senha do e-mail" value="">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default" title="Mostrar senha"
                                                        onclick="toggleSenha('emailsenha', this);">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                {/if}
                            </div>

                            <!-- Direitos -->
                            <div role="tabpanel" class="tab-pane fade usuario-tab-pane" id="tabDireitos">
                                <p class="usuario-dir-intro">
                                    Defina o que este usu&aacute;rio pode fazer em cada programa.
                                    Sem permiss&atilde;o pr&oacute;pria na linha, valem os direitos do grupo (se houver).
                                </p>
                                {if $grupoId > 0}
                                    <div class="alert alert-info" style="margin-bottom: 12px; padding: 8px 12px;">
                                        Grupo: <strong>{$grupoNome|escape:'html'}</strong>.
                                        Sem permiss&atilde;o pr&oacute;pria na linha, o login usa os direitos cadastrados no usu&aacute;rio grupo.
                                    </div>
                                {/if}
                                <div class="usuario-dir-filtro">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-6 col-xs-12">
                                            <label for="filtroPrograma" style="font-weight: 600; font-size: 12px;">Filtrar programa</label>
                                            <input type="text" class="form-control input-sm" id="filtroPrograma"
                                                placeholder="Nome ou c&oacute;digo do programa...">
                                        </div>
                                        <div class="col-md-8 col-sm-6 col-xs-12 dir-filtro-ajuda">
                                            O bot&atilde;o <strong>Todos</strong> na linha marca ou desmarca os direitos daquele programa (n&atilde;o afeta a lista inteira).
                                        </div>
                                    </div>
                                </div>
                                <div class="dir-wrap-direitos">
                                    <table class="table table-bordered table-condensed" id="tabelaDireitos">
                                        <thead>
                                            <tr>
                                                <th class="col-programa">
                                                    Programa
                                                    <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left"
                                                        title="Descrição do menu. Ícone ? na linha mostra o HELP de AMB_FORM (cadastro Form)."></i>
                                                </th>
                                                <th class="col-todos">
                                                    Todos
                                                    <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left"
                                                        title="Marca ou desmarca todos os direitos daquele programa (Incluir, Alterar, Excluir, Consultar, Serviço e Relatório)."></i>
                                                </th>
                                                <th class="dir-th-acao">Incluir <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left" title="Cadastrar novos registros no programa."></i></th>
                                                <th class="dir-th-acao">Alterar <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left" title="Editar registros existentes."></i></th>
                                                <th class="dir-th-acao">Excluir <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left" title="Remover registros."></i></th>
                                                <th class="dir-th-acao">Consultar <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left" title="Telas de consulta e listagem."></i></th>
                                                <th class="dir-th-acao">Servi&ccedil;o <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left" title="Rotinas e processos do programa."></i></th>
                                                <th class="dir-th-acao">Relat&oacute;rio <i class="fa fa-question-circle dir-help" data-toggle="tooltip" data-placement="left" title="Gerar e visualizar relatórios."></i></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {foreach from=$programasUi item=prog}
                                                <tr class="linha-direito" data-programa="{$prog.nomeform|escape:'html'}"
                                                    data-tinha-direito="{if $prog.direitos_usuario neq ''}1{else}0{/if}"
                                                    data-busca="{$prog.nomeform|escape:'html'} {$prog.descricao|escape:'html'}">
                                                    <td class="col-programa"
                                                        title="{$prog.descricao|escape:'html'} ({$prog.nomeform|escape:'html'}){if $prog.direitos_usuario eq '' && $grupoId > 0 && $prog.direitos_grupo neq ''} — Herdado: {$prog.direitos_grupo|escape:'html'}{/if}">
                                                        <div class="prog-cell-wrap">
                                                            <span class="prog-titulo">{$prog.descricao|escape:'html'}</span>
                                                            <span class="prog-codigo">{$prog.nomeform|escape:'html'}</span>
                                                            {if $prog.direitos_usuario eq '' && $grupoId > 0 && $prog.direitos_grupo neq ''}
                                                                <span class="dir-herdado" title="Herdado do grupo">↳ {$prog.direitos_grupo|escape:'html'}</span>
                                                            {/if}
                                                            {if $prog.help neq ''}
                                                                <i class="fa fa-question-circle dir-prog-help" data-toggle="tooltip"
                                                                    data-placement="left" title="{$prog.help|escape:'html'}"></i>
                                                            {/if}
                                                        </div>
                                                    </td>
                                                    <td class="col-todos">
                                                        <button type="button" class="btn btn-default btn-xs btn-toggle-prog-dir"
                                                            title="Marcar ou desmarcar todos os direitos deste programa">
                                                            Todos
                                                        </button>
                                                    </td>
                                                    <td class="col-chk"><input type="checkbox" class="chk-dir" data-letra="I" data-parsley-ignore="true" aria-label="Incluir — {$prog.descricao|escape:'html'}" {if $prog.chk.I}checked="checked"{/if}></td>
                                                    <td class="col-chk"><input type="checkbox" class="chk-dir" data-letra="A" data-parsley-ignore="true" aria-label="Alterar — {$prog.descricao|escape:'html'}" {if $prog.chk.A}checked="checked"{/if}></td>
                                                    <td class="col-chk"><input type="checkbox" class="chk-dir" data-letra="E" data-parsley-ignore="true" aria-label="Excluir — {$prog.descricao|escape:'html'}" {if $prog.chk.E}checked="checked"{/if}></td>
                                                    <td class="col-chk"><input type="checkbox" class="chk-dir" data-letra="C" data-parsley-ignore="true" aria-label="Consultar — {$prog.descricao|escape:'html'}" {if $prog.chk.C}checked="checked"{/if}></td>
                                                    <td class="col-chk"><input type="checkbox" class="chk-dir" data-letra="S" data-parsley-ignore="true" aria-label="Serviço — {$prog.descricao|escape:'html'}" {if $prog.chk.S}checked="checked"{/if}></td>
                                                    <td class="col-chk"><input type="checkbox" class="chk-dir" data-letra="R" data-parsley-ignore="true" aria-label="Relatório — {$prog.descricao|escape:'html'}" {if $prog.chk.R}checked="checked"{/if}></td>
                                                </tr>
                                            {/foreach}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="ln_solid"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{include file="template/form.inc"}
<script src="{$pathJs}/cleave/dist/cleave.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_usuario.js"></script>
<script>
document.querySelectorAll('#tabComercial .money').forEach(function (el) {
    new Cleave(el, {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        numeralDecimalMark: ',',
        delimiter: '.',
        numeralDecimalScale: {$casasDecimais}
    });
});
</script>

