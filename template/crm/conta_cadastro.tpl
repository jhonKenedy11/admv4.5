<style>
    .checkCond {
        margin-left: 100px;
        margin-top: 7px;
    }

    .btnAdicionaBonus {
        width: 20px;
        height: 20px;
        padding: 0;
        cursor: pointer;
    }

    .glyphiconAdd {
        margin-top: -4px;
    }

    #vlrBonus,
    #pedidoId,
    #nrItem,
    #tdAddBns {
        border-radius: 5px;
        text-align: center;
    }

    .input-group-addon,
    .x_panel {
        border-radius: 5px;
    }

    .modal-bonus {
        width: 300px;
        margin-left: 26%;
    }

    .modal-header .close {
        margin-top: -27px;
    }

    .inputBonus {
        margin-left: 9%;
    }

    .inputBonus2 {
        margin-left: 9%;
        margin-top: -28px;
    }

    #btnCancelar {
        margin-top: 5px;
    }

    [aria-label] {
        position: relative;
    }

    [aria-label]::after {
        content: attr(aria-label);
        display: none;
        position: absolute;
        top: -12px;
        left: -160px;
        z-index: 5000;
        pointer-events: none;
        padding: 8px 10px;
        text-decoration: none;
        font-size: 12px;
        color: #fff;
        background-color: #aa2424;
        border-radius: 5px;
        font-weight: bold;
    }

    .form-control {
        border-radius: 5px;
    }

    .modal-body-address {
        padding: 10px;
    }

    .modal-body-address span,
    .modal-body-address label,
    #desativa {
        color: #636363;
    }

    .modal-body-address input {
        font-size: 12px;
    }

    /* Estilo para os botões de ação nos anexos */
    .btnManutencao {
        display: flex;
        justify-content: space-between;
        position: absolute;
        bottom: 10px;
        width: 90%;
    }

    .btnManutencao button {
        margin: 1px;
        flex: 1;
    }
</style>

<script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="">
            <input name=form type=hidden value="">
            <input name=opcao type=hidden value={$opcao}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=id type=hidden value={$id}>
            <input name=letra type=hidden value={$letra}>
            <input name=idCredito type=hidden value={$idCredito}>
            <input id="tipo" name=tipo type=hidden value={$tipo}> <!-- tipo endereco -->
            <input id="permiteIncluirBonus" name=permiteIncluirBonus type=hidden value={$permiteIncluirBonus}>

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Pessoas -
                                {if $subMenu eq "cadastrar"}
                                    Cadastro
                                {else}
                                    Altera&ccedil;&atilde;o
                                {/if}
                                {if $mensagem neq ''}
                                    {if $tipoMsg eq 'sucesso'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-success" role="alert">{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {elseif $tipoMsg eq 'alerta'}
                                        <div class="row">
                                            <div class="col-lg-12 text-left">
                                                <div>
                                                    <div class="alert alert-danger" role="alert">{$mensagem}</div>
                                                </div>
                                            </div>
                                        </div>
                                    {/if}

                                {/if}
                            </h2>

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar('');">
                                        <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger"
                                        onClick="javascript:submitVoltar('{$opcao}');">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span>
                                            Cancelar</span></button>
                                </li>

                                {* <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li> *}

                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <div class="form-group">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label for="nomeReduzido">*Nome</label>
                                    <input class="form-control border-blue" required="required" maxlength="60"
                                        type="text" id="nomeReduzido" name="nomeReduzido"
                                        placeholder="Digite o nome Reduzido." title="Digite o nome Reduzido."
                                        value={$nomeReduzido}>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 col-sm-12 col-xs-12">
                                    <label for="pessoa">*Tipo Pessoa</label>
                                    <SELECT class="form-control" name="pessoa" required="required">
                                        {html_options values=$pessoa_ids output=$pessoa_names selected=$pessoa_id}
                                    </SELECT>
                                </div>

                                <div class="col-md-3 col-sm-12 col-xs-12 ">
                                    <label for="cnpjCpf">*CNPJ/CPF</label>
                                    <input class="form-control" maxlength="14" type="text" id="cnpjCpf" name="cnpjCpf"
                                        placeholder="Digite somente numeros." title="Digite CNPJ/CPF somente numeros."
                                        onblur="showHint(this.value);" value={$cnpjCpf}>
                                </div>

                                <div class="col-md-3 col-sm-12 col-xs-12">
                                    <label for="ieRg">Insc. Estadual/R.G.</label>
                                    <input class="form-control" maxlength="15" type="text" id="ieRg" name="ieRg"
                                        placeholder="Digite somente numeros." title="Digite o RG/IE somente numeros."
                                        value={$ieRg}>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <label for="im">Insc. Municipal</label>
                                    <input class="form-control" maxlength="10" type="text" id="im" name="im"
                                        placeholder="Digite somente numeros." title="Digite a IM somente numeros."
                                        value={$im}>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-8 col-sm-12 col-xs-12">
                                    <label for="nome">*Raz&atilde;o Social / Nome Completo</label>
                                    <input class="form-control" maxlength="60" required="required" type="text" id="nome"
                                        name="nome" placeholder="Digite o nome completo."
                                        title="Digite o nome completo." value={$nome}>
                                </div>
                                <div class="col-md-4 col-sm-12 col-xs-12">
                                    <label for="contato">Contato</label>
                                    <input class="form-control" maxlength="15" type="text" id="contato" name="contato"
                                        placeholder="Digite o contato." value={$contato}>
                                </div>
                            </div>


                            <div class="form-group">

                                <h4 class="col-md-1 col-sm-1 col-xs-1">Endere&ccedil;o</h4>
                                <div class="form-check form-switch checkCond">
                                    <input class="form-check-input" type="checkbox" id="checkCond" name="checkCond"
                                        {if $checkCond eq 'S'} checked{/if} value={$checkCond}>
                                    <label class="form-check-label" for="checkCond">Condom&iacute;nio</label>
                                </div>
                            </div>
                            <span class="section"></span>

                            <div class="form-group">
                                <div class="col-md-2 col-sm-6 col-xs-6">
                                    <input class="form-control has-feedback-left" required="required" maxlength="9"
                                        type="text" data-inputmask="'mask' : '99999-999'" id="cep" name="cep"
                                        placeholder="*Cep" onblur="pesquisacep(this.value);" value={$cep}>
                                    <span class="fa fa-envelope form-control-feedback left" aria-hidden="true"></span>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <input class="form-control has-feedback-left" maxlength="60" type="text"
                                        id="endereco" name="endereco" placeholder="Endereço." value={$endereco}>
                                    <span class="glyphicon glyphicon-home form-control-feedback left"
                                        aria-hidden="true"></span>
                                </div>
                                <div class="col-md-1 col-sm-6 col-xs-6">
                                    <input class="form-control" maxlength="7" type="text" id="numero" name="numero"
                                        placeholder="Numero" value={$numero}>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <input class="form-control" maxlength="15" type="text" id="complemento"
                                        name="complemento" placeholder="Complemento" value={$complemento}>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <input class="form-control" maxlength="60" type="text" id="bairro" name="bairro"
                                        placeholder="Bairro" value={$bairro}>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <input class="form-control" maxlength="40" type="text" id="cidade" name="cidade"
                                        placeholder="Cidade." value={$cidade}>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <SELECT class="form-control" name="estado" id="estado">
                                        {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                                    </SELECT>
                                </div>
                            </div>

                            <div class="form-group">

                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <input type="text" class="form-control has-feedback-left" placeholder="Email"
                                        id="email" name="email" value={$email}>
                                    <span class="fa fa-at form-control-feedback left" aria-hidden="true"></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <input type="text" class="form-control has-feedback-left"
                                        data-inputmask="'mask' : '(99) 9999-9999'" id="fone" placeholder="Fone"
                                        name="fone" value={$fone}>
                                    <span class="fa fa-phone form-control-feedback left" aria-hidden="true"></span>
                                </div>

                                <div class="col-md-3 col-sm-6 col-xs-6">
                                    <input type="text" class="form-control has-feedback-left"
                                        data-inputmask="'mask' : ['(99) 99999-9999', '(99) 99999-9999'], 'keepStatic': 'true'"
                                        id="celular" name="celular" placeholder="Celular" value={$celular}>
                                    <span class="glyphicon glyphicon-phone form-control-feedback left"
                                        aria-hidden="true"></span>
                                </div>
                            </div>

                        </div>
                        </0div>
                        <div class="x_panel">
                            <!-- dados adicionaris -->
                            <!-- start accordion -->
                            <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                    data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    <h4 class="panel-title">Dados Adicionais <i class="fa fa-chevron-down"></i>
                                    </h4>
                                </a>
                                <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                    aria-labelledby="headingTwo">
                                    <div class="panel-body">
                                        <div class="" role="tabpanel" data-example-id="togglable-tabs">
                                            <ul id="myTab" class="nav nav-tabs bar_tabs" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#tab_content1" id="dados-cliente-tab" role="tab"
                                                        data-toggle="tab" aria-expanded="true">Dados Clientes</a>
                                                </li>

                                                {if $id != ""}
                                                    <li role="presentation" class="">
                                                        <a href="#tab_content2" role="tab" id="dados-credito-tab"
                                                            data-toggle="tab" aria-expanded="true">Créditos</a>
                                                    </li>
                                                {/if}

                                                <li role="presentation" class="">
                                                    <a href="#tab_content3" id="referencia-cliente-tab" role="tab"
                                                        data-toggle="tab" aria-expanded="true">Ponto Referência</a>
                                                </li>

                                                <li role="presentation" class="">
                                                    <a href="#tab_content4" id="tributos-tab" role="tab"
                                                        data-toggle="tab" aria-expanded="true">Tributos</a>
                                                </li>

                                                {if $id != ""}
                                                    <li role="presentation" class="{$active02}">
                                                        <a href="#tab_content5" role="tab" id="rateio-tab" data-toggle="tab"
                                                            aria-expanded="true">Pedidos</a>
                                                    </li>

                                                    <li role="presentation" class="">
                                                        <a href="#tab_content6" role="tab" id="enderecos-tab"
                                                            data-toggle="tab" aria-expanded="true">Endereços entrega</a>
                                                    </li>

                                                    <li role="presentation" class="">
                                                        <a href="#tab_content7" id="dados-cliente-tab" role="tab"
                                                            data-toggle="tab" aria-expanded="true">Obras</a>
                                                    </li>
                                                {/if}
                                            </ul>
                                        </div>
                                    </div>
                                    <div id="myTabContent" class="tab-content">
                                        <div role="tabpanel" class="tab-pane fade active in" id="tab_content1"
                                            aria-labelledby="home-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <div class="form-group">
                                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                                            <label for="classe">Classe</label>
                                                            <SELECT class="form-control" name="classe">
                                                                {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                                                            </SELECT>
                                                        </div>

                                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                                            <label for="atividade">Atividade</label>
                                                            <SELECT class="form-control" name="atividade">
                                                                {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                                                            </SELECT>
                                                        </div>

                                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                                            <label for="vendedor">Responsável</label>
                                                            <SELECT class="form-control" name="vendedor">
                                                                {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                                                            </SELECT>
                                                        </div>
                                                        <div class="col-md-3 col-sm-6 col-xs-6">
                                                            <label for="vendedor">Filial</label>
                                                            <SELECT class="form-control" name="filial">
                                                                {html_options values=$filial_ids output=$filial_names selected=$filial_id}
                                                            </SELECT>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <div class="col-md-3 col-sm-12 col-xs-12  has-feedback">
                                                            <label for="datanascimento">Data Nascimento</label>
                                                            <input class="form-control" type="text" size="15"
                                                                placeholder="Ex:01/01/2020" id="dataNascimento"
                                                                name="dataNascimento"
                                                                data-inputmask="'mask':'99/99/9999'"
                                                                value={$dataNascimento}>
                                                        </div>

                                                        <div class="col-md-3 col-sm-12 col-xs-12">
                                                            <label for="codMunicipio">C&oacute;digo do Municipio</label>
                                                            <input class="form-control" maxlength="7" type="text"
                                                                id="codMunicipio" name="codMunicipio" readonly
                                                                placeholder="Numero" value={$codMunicipio}>
                                                        </div>

                                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                                            <label for="homePage">Home Page</label>
                                                            <div class="form-group input-group">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default" type="button"><i
                                                                            class="fa fa-unlink"></i></button>
                                                                </span>
                                                                <input class="form-control" type="url" id="homePage"
                                                                    name="homePage"
                                                                    placeholder="http://www.admservice.com.br."
                                                                    value={$homePage}>
                                                            </div>
                                                        </div>


                                                    </div>

                                                    <div class="form-group">



                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <label for="obs">Observa&ccedil;&atilde;o</label>
                                                            <textarea class="resizable_textarea form-control" id="obs"
                                                                name="obs" rows="3">{$obs}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                                            <label for="suframa">Usu&aacute;rio Login</label>
                                                            <input class="form-control" maxlength="30" type="text"
                                                                name="userLogin"
                                                                placeholder="Usu&aacute;rio utilizado para acesso externo."
                                                                value={$userLogin}>
                                                        </div>
                                                        <div class="col-md-6 col-sm-6 col-xs-6">
                                                            <label for="suframa">Senha Login</label>
                                                            <input class="form-control" maxlength="30" type="password"
                                                                name="senhaLogin"
                                                                placeholder="Senha utilizada para acesso externo."
                                                                value={$senhaLogin}>
                                                        </div>
                                                    </div>



                                                </div>
                                                <!--FIM class="x_panel" -->
                                            </div>
                                            <!--FIM class="panel-body" -->
                                        </div>
                                        <!--FIM class="tab-pane fade active in" -->


                                        <div role="tabpanel" class="tab-pane fade small" id="tab_content2"
                                            aria-labelledby="profile-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <table id="datatable-cc"
                                                        class="table table-bordered jambo_table col-md-8">
                                                        <thead>
                                                            <tr style="background: gray; color: white;">
                                                                <th width="10%">Pedido</th>
                                                                <th width="10%">Nr Item</th>
                                                                <th width="10%">Quantidade</th>
                                                                <th width="15%">Unitario</th>
                                                                <th width="15%">Valor</th>
                                                                <th width="19%">Valor Utilizado</th>
                                                                <th width="19%">PED Utilizado</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {assign var="total" value="0"}
                                                            {assign var="utilizado" value="0"}
                                                            {section name=i loop=$credito}
                                                                {assign var="valorTotal" value=$valorTotal+$credito[i].VALOR}
                                                                {assign var="valorUtilizado" value=$valorUtilizado+$credito[i].UTILIZADO}

                                                                <tr>
                                                                    <td name="pedido"> {$credito[i].PEDIDO} </td>
                                                                    <td name="nritem"> {$credito[i].NRITEM} </td>
                                                                    <td name="quantidade"> {$credito[i].QUANTIDADE} </td>
                                                                    <td name="unitario"> {$credito[i].UNITARIO} </td>
                                                                    <td name="valor"> {$credito[i].VALOR} </td>
                                                                    <td name="credutilizado"> {$credito[i].UTILIZADO} </td>
                                                                    <td name="pedutilizado"> {$credito[i].PEDIDOUTILIZADO}
                                                                    </td>
                                                                    <td>
                                                                        <a
                                                                            href="javascript:submitExcluirCredito('{$credito[i].ID}');"><i
                                                                                class="fa fa-trash fa-lg red"
                                                                                aria-hidden="true"></i></a>
                                                                    </td>
                                                                </tr>
                                                                <p>
                                                                {/section}
                                                                <tr>
                                                                    <td>Totais</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>R$ {$valorTotal|number_format:2:",":"."}</td>
                                                                    <td>R$ {$valorUtilizado|number_format:2:",":"."}
                                                                    </td>
                                                                    <td></td>
                                                                </tr>
                                                        </tbody>
                                                    </table>

                                                </div> <!-- FIM class="x_panel" -->
                                            </div> <!-- FIM class="panel-body" -->
                                        </div> <!-- FIM class="tab-pane fade small" -->

                                        <!-- TAB 3 -->
                                        <div role="tabpanel" class="tab-pane fade small" id="tab_content3"
                                            aria-labelledby="profile-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <div class="form-group">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <label for="referencia">Ponto de Referência</label>
                                                            <textarea class="resizable_textarea form-control"
                                                                id="referencia" name="referencia"
                                                                rows="3">{$referencia}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <label for="transversal1">Transversal1</label>
                                                            <textarea class="resizable_textarea form-control"
                                                                id="transversal1" name="transversal1"
                                                                rows="3">{$transversal1}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <label for="transversal2">Transversal2</label>
                                                            <textarea class="resizable_textarea form-control"
                                                                id="transversal2" name="transversal2"
                                                                rows="3">{$transversal2}</textarea>
                                                        </div>
                                                    </div>
                                                </div> <!-- FIM class="x_panel" -->
                                            </div> <!-- FIM class="panel-body" -->
                                        </div> <!-- FIM class="tab-pane fade small" -->

                                        <!-- TAB 4-->
                                        <div role="tabpanel" class="tab-pane fade small" id="tab_content4"
                                            aria-labelledby="profile-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <div class="form-group">
                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="suframa">Suframa</label>
                                                            <input class="form-control" maxlength="10" type="text"
                                                                id="suframa" name="suframa"
                                                                placeholder="Digite somente numeros."
                                                                title="C&oacute;digo Suframa." value={$suframa}>
                                                        </div>
                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="limiteCredito">Limite Cr&eacute;dito</label>
                                                            <input class="form-control" maxlength="10" type="text"
                                                                id="limiteCredito" name="limiteCredito"
                                                                placeholder="Digite somente numeros."
                                                                title="Limite de cr&eacute;dito venda."
                                                                value={$limiteCredito}>
                                                        </div>
                                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                                            <label for="emailNfe">Email Nfe</label>
                                                            <div class="form-group input-group">
                                                                <span class="input-group-btn">
                                                                    <button class="btn btn-default" type="button"><i
                                                                            class="fa fa-at"></i> </button>
                                                                </span>
                                                                <input type="email" class="form-control"
                                                                    placeholder="Email Nfe" id="emailNfe"
                                                                    name="emailNfe" value={$emailNfe}>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="regimeEspecialST">Regime Esp. ST</label>
                                                            <select class="form-control" name="regimeEspecialST"
                                                                id="regimeEspecialST" title="Regime especial ST.">
                                                                {html_options values=$boolean_ids selected=$regimeEspecialST output=$boolean_names}
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="regimeEspecialSTMT">Regime especial ST
                                                                MT</label>
                                                            <select class="form-control" name="regimeEspecialSTMT"
                                                                id="regimeEspecialSTMT" title="Regime especial ST MT.">
                                                                {html_options values=$boolean_ids selected=$regimeEspecialSTMT output=$boolean_names}
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="contribuinteICMS">Contribuinte de ICMS</label>
                                                            <select class="form-control" name="contribuinteICMS"
                                                                id="contribuinteICMS" title="Contribuinte de ICMS.">
                                                                {html_options values=$boolean_ids selected=$contribuinteICMS output=$boolean_names}
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="consumidorFinal">Consumidor Final</label>
                                                            <select class="form-control" name="consumidorFinal"
                                                                id="consumidorFinal" title="Consumidor final.">
                                                                {html_options values=$boolean_ids selected=$consumidorFinal output=$boolean_names}
                                                            </select>
                                                        </div>

                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="regimeEspecialSTMTAliq">Regime esp ST MT
                                                                Aliq</label>
                                                            <input class="form-control" id="regimeEspecialSTMTAliq"
                                                                name="regimeEspecialSTMTAliq"
                                                                value={$regimeEspecialSTMTAliq}>
                                                        </div>

                                                        <div class="col-md-2 col-sm-12 col-xs-12">
                                                            <label for="regimeEspecialSTAliq">Regime esp ST Aliq</label>
                                                            <input class="form-control" id="regimeEspecialSTAliq"
                                                                name="regimeEspecialSTAliq"
                                                                value={$regimeEspecialSTAliq}>
                                                        </div>

                                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                                            <label for="regimeEspecialSTMsg">Regime Esp. ST MSg</label>
                                                            <textarea class="resizable_textarea form-control"
                                                                id="regimeEspecialSTMsg" name="regimeEspecialSTMsg"
                                                                rows="3">{$regimeEspecialSTMsg}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">

                                                    </div>

                                                </div> <!-- FIM class="x_panel" -->
                                            </div> <!-- FIM class="panel-body" -->
                                        </div> <!-- FIM class="tab-pane fade small" -->

                                        <!-- TAB 5-->
                                        <div role="tabpanel" class="tab-pane fade small" id="tab_content5"
                                            aria-labelledby="profile-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <table id="datatable-cc"
                                                        class="table table-bordered jambo_table col-md-8">
                                                        <thead>
                                                            <tr style="background: gray; color: white;">
                                                                <th style="width:100px">Pedido</th>
                                                                <th style="width:140px">Centro De Custo</th>
                                                                <th style="width:140px">Vendedor</th>
                                                                <th style="width:80px">Emissão</th>
                                                                <th style="width:100px">Hora Emissão</th>
                                                                <th style="width:100px">Frete</th>
                                                                <th style="width:100px">Desp. Acessórias</th>
                                                                <th style="width:100px">Total</th>
                                                                {* <th style="width:50px; text-align: center;">Crédito</th> *}
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {section name=i loop=$lancPedidos}
                                                                <tr>
                                                                    <td> {$lancPedidos[i].PEDIDO} </td>
                                                                    <td> {$lancPedidos[i].NOMEFANTASIA} </td>
                                                                    <td> {$lancPedidos[i].VENDEDOR} </td>
                                                                    <td> {$lancPedidos[i].EMISSAO|date_format:"%d/%m/%Y"}
                                                                    </td>
                                                                    <td> {$lancPedidos[i].HORAEMISSAO} </td>
                                                                    <td> R$ {$lancPedidos[i].FRETE|number_format:2:",":"."}
                                                                    </td>
                                                                    <td> R$
                                                                        {$lancPedidos[i].DESPACESSORIAS|number_format:2:",":"."}
                                                                    </td>
                                                                    <td> R$ {$lancPedidos[i].TOTAL} </td>
                                                                    {* <td id="tdAddBns">
                                                                        <!-- Button trigger modal -->
                                                                        <button {if $permiteIncluirBonus neq true} disabled
                                                                            aria-label="Usuário Sem Permissão" {/if}
                                                                            type="button"
                                                                            class="btn btn-success btn-sm btnAdicionaBonus"
                                                                            data-toggle="modal" data-target="#ModalBonus"
                                                                            onclick="javascript:submitAbreModalCredito({$lancPedidos[i].PEDIDO},'999')">
                                                                            <span
                                                                                class="glyphicon glyphicon-plus glyphiconAdd"
                                                                                aria-hidden="true"></span>
                                                                        </button>
                                                                    </td> *}
                                                                </tr>
                                                                <p>
                                                                {/section}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <!--FIM TAB 5-->

                                        <!--TAB 6-->
                                        <div role="tabpanel" class="tab-pane fade small" id="tab_content6"
                                            aria-labelledby="profile-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <table id="datatable-address"
                                                        class="table table-bordered jambo_table col-md-8">
                                                        <thead>
                                                            <div style="text-align: right; margin-bottom: 10px;">
                                                                <button type="button" class="btn btn-success btn-xs"
                                                                    data-toggle="modal" data-target="#ModalAddress"
                                                                    onclick="javascript:openModalAddress(null, {$id}, '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'A')">
                                                                    <i class="fa fa-plus"></i> Novo Endereço
                                                                </button>
                                                            </div>
                                                            <tr style="background: rgb(118, 73, 73); color: white;">
                                                                <th width="">Titulo</th>
                                                                <th width="">Endereco</th>
                                                                <th width="">Numero</th>
                                                                <th width="">Bairro</th>
                                                                <th width="">Cidade</th>
                                                                <th width="">UF</th>
                                                                <th width="">Fone</th>
                                                                <th width="">Status</th>
                                                                <th width="30px">Man.</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            {section name=k loop=$deliveryAddress}
                                                                <tr>
                                                                    <td name="addressTitulo">
                                                                        {$deliveryAddress[k].TITULOEND} </td>
                                                                    <td name="addressEndereco">
                                                                        {$deliveryAddress[k].ENDERECO} </td>
                                                                    <td name="addressNumero"> {$deliveryAddress[k].NUMERO}
                                                                    </td>
                                                                    <td name="addressBairro"> {$deliveryAddress[k].BAIRRO}
                                                                    </td>
                                                                    <td name="addressNumero"> {$deliveryAddress[k].CIDADE}
                                                                    </td>
                                                                    <td name="addressNumero"> {$deliveryAddress[k].UF} </td>
                                                                    <td name="addressNumero"> {$deliveryAddress[k].FONE}
                                                                    </td>
                                                                    <td name="addressStatus"> {$deliveryAddress[k].STATUS}
                                                                    </td>
                                                                    <td>
                                                                        <a data-toggle="modal" data-target="#ModalAddress">
                                                                            <center>
                                                                                <i class="fa fa fa-pencil fa-lg green"
                                                                                    style="cursor: pointer;"
                                                                                    onclick="javascript:openModalAddress({$deliveryAddress[k].ID}, {$deliveryAddress[k].CLIENTE}, '{$deliveryAddress[k].DESCRICAO}',
                                                                                                        '{$deliveryAddress[k].TIPOEND}', '{$deliveryAddress[k].TITULOEND}', '{$deliveryAddress[k].ENDERECO}',
                                                                                                        '{$deliveryAddress[k].NUMERO}', '{$deliveryAddress[k].COMPLEMENTO}', '{$deliveryAddress[k].BAIRRO}',
                                                                                                        '{$deliveryAddress[k].CIDADE}', '{$deliveryAddress[k].UF}', '{$deliveryAddress[k].CEP}', '{$deliveryAddress[k].FONEAREA}',
                                                                                                        '{$deliveryAddress[k].FONE}', '{$deliveryAddress[k].FONERAMAL}', '{$deliveryAddress[k].FONECONTATO}', 
                                                                                                        '{$deliveryAddress[k].ENDENTREGAPADRAO}', '{$deliveryAddress[k].STATUS}')"
                                                                                    aria-hidden="true">
                                                                            </center></i>
                                                                        </a>
                                                                    </td>
                                                                </tr>
                                                            {/section}
                                                        </tbody>
                                                    </table>

                                                </div> <!-- FIM class="x_panel" -->
                                            </div> <!-- FIM class="panel-body" -->
                                        </div>
                                        {* tab 7 *}
                                        <div role="tabpanel" class="tab-pane fade small" id="tab_content7"
                                            aria-labelledby="profile-tab">
                                            <div class="panel-body">
                                                <div class="x_panel">
                                                    <div style="text-align: right; margin-bottom: 10px;">
                                                        <button type="button" class="btn btn-success btn-xs"
                                                            onclick="abrirModalObra()"> <i class="fa fa-plus"></i> Nova
                                                            Obra
                                                        </button>
                                                    </div>

                                                    <table class="table table-bordered jambo_table col-md-8">
                                                        <thead>
                                                            <tr style="background: rgb(144, 155, 216); color: white;">
                                                                <th style="white-space: nowrap;">CNO</th>
                                                                <th>Projeto</th>
                                                                <th style="white-space: nowrap;">R. Técnico </th>
                                                                <th style="white-space: nowrap;">CREA</th>
                                                                <th style="white-space: nowrap;">ART</th>
                                                                <th style="width: 80px; text-align: center;">Status</th>
                                                                <th style="width: 180px; text-align: center;">Ações</th>
                                                            </tr>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="lista-obras">
                                                            {* O conteúdo deste tbody será preenchido via JavaScript *}
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>{* FIM tab 7 *}
                            </div>
                        </div>
                    </div>
                </div>
            </div>


    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalBonus" tabindex="-1" role="dialog" aria-labelledby="ModalBonus" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-dialog-centered modal-bonus">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Adiciona B&ocirc;nus</h5>
                <button type="button" class="close btnFecha" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="input-group col-md-10 col-sm-10 col-xs-10 inputBonus">
                    <span class="input-group-addon">Pedido</span>
                    <input id="pedidoId" type="text" class="form-control  col-md-6" readonly maxlength="10"
                        name="pedidoId" value="{$pedidoId}">
                </div>
                <div class="input-group col-md-10 col-sm-10 col-xs-10 inputBonus">
                    <span class="input-group-addon">N&#186; Item</span>
                    <input id="nrItem" type="text" class="form-control  col-md-6" readonly maxlength="10" name="nrItem"
                        value="{$nrItem}">
                </div>
            </div>

            <div class="modal-body">
                <div class="input-group col-md-10 col-sm-10 col-xs-10 inputBonus2">
                    <span class="input-group-addon">Valor</span>
                    <input id="vlrBonus" type="text" class="form-control money" maxlength="10" name="vlrBonus"
                        placeholder="Valor do bonus" value="{$vlrBonus}">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="btnCancelar" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary"
                    onclick="javascript:submitCadastraCredito()">Salvar</button>
            </div>

        </div>
    </div>
</div>
<!--Fim myModal -->

<!-- MODAL DELIVERY ADDRESS -->
<div class="modal fade" id="ModalAddress" tabindex="-1" role="dialog" aria-labelledby="ModalAddress" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog-centered">
            <div class="modal-header">
                <h5 class="modal-title" style="font-size:18px;">Editar endereco de entrega</h5>
                <button type="button" class="close btnFecha" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body-address">

                <div class="row">

                    <div class="col-md-3 col-sm-3 col-xs-3 form-group">
                        <span class="fa fa-asterisk" aria-hidden="true"></span>
                        <label for="tituloEndereco-address" class="col-form-label">Titulo endereco</label>
                        <input class="form-control" maxlength="15" type="text" id="tituloEndereco-address"
                            name="tituloEndereco-address" placeholder="titulo do endereco"
                            value={$tituloEndereco_address}>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                        <span class="fa fa-comments" aria-hidden="true"></span>
                        <label for="descricao" class="col-form-label">Descricao</label>
                        <input class="form-control" maxlength="35" type="text" id="descricao-address"
                            name="descricao-address" placeholder="descricao do endereço" value={$descricao_address}>
                    </div>
                    <div class="col-md-1 col-sm-1 col-xs-1 form-group">
                        <span class="fa fa-globe" aria-hidden="true"></span>
                        <label for="ddd" class="col-form-label">DDD</label>
                        <input class="form-control" maxlength="4" type="text" id="ddd-address" name="ddd-address"
                            placeholder="ddd" value={$ddd_address}>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                        <span class="fa fa-phone-square" aria-hidden="true"></span>
                        <label for="fone-address" class="col-form-label">Celular</label>
                        <input class="form-control" maxlength="10" type="text" id="fone-address" name="fone-address"
                            data-inputmask="'mask' : '99999-9999', 'keepStatic': 'true'" placeholder="fone-address"
                            value={$fone_address}>
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                        <span class="fa fa-phone-square" aria-hidden="true"></span>
                        <label for="-address" class="col-form-label">Fone Contato</label>
                        <input class="form-control" maxlength="15" type="text" id="foneContato-address"
                            name="foneContato-address" data-inputmask="'mask' : '9999-9999', 'keepStatic': 'true'"
                            placeholder="foneContato-address" value={$foneContato_address}>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 col-sm-6 col-xs-6 form-group">
                        <span class="fa fa-home" aria-hidden="true"></span>
                        <label for="cep-address" class="col-form-label">Cep</label>
                        <input class="form-control" required="required" maxlength="11" type="text"
                            data-inputmask="'mask' : '99999-999'" id="cep-address" name="cep-address" placeholder="cep"
                            onblur="pesquisarEnderecoECarregarFormulario(this.value);" value={$cep_address}>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                        <span class="fa fa-map-marker" aria-hidden="true"></span>
                        <label for="endereco-address" class="col-form-label">Endereço</label>
                        <input class="form-control" maxlength="40" type="text" id="endereco-address"
                            name="endereco-address" placeholder="Endereço" value={$endereco_address}>
                    </div>
                    <div class="col-md-1 col-sm-6 col-xs-6 form-group">
                        <label for="numero-address" class="col-form-label">Numero</label>
                        <input class="form-control" maxlength="7" type="text" id="numero-address" name="numero-address"
                            value={$numero_address}>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6 form-group">
                        <span class="fa fa-plus-square" aria-hidden="true"></span>
                        <label for="complemento-address" class="col-form-label">Complemento</label>
                        <input class="form-control" maxlength="15" type="text" id="complemento-address"
                            name="complemento-address" placeholder="Complemento" value={$complemento_address}>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <span class="fa fa-home" aria-hidden="true"></span>
                        <label for="bairro-address" class="col-form-label">Bairro</label>
                        <input class="form-control" maxlength="20" type="text" id="bairro-address" name="bairro-address"
                            placeholder="Bairro" value={$bairro_address}>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <span class="fa fa-home" aria-hidden="true"></span>
                        <label for="cidade-address" class="col-form-label">Cidade</label>
                        <input class="form-control" maxlength="40" type="text" id="cidade-address" name="cidade-address"
                            placeholder="Cidade" value={$cidade_address}>
                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <span class="fa fa-home" aria-hidden="true"></span>
                        <label for="estado-address" class="col-form-label">Estado</label>
                        <SELECT class="form-control" name="estado-address" id="estado-address">
                            {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                        </SELECT>
                    </div>
                    <div class="" hidden>
                        <input type="text" id="id-address" name="id-address" value={$id_address}>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <div class="col-md-3 col-sm-3 col-xs-3 d-flex align-items-left">
                    <div class="form-check pull-left" style="margin-top: 10px; margin-left: -12px; font-size:14px;">
                        <input class="form-check-input" type="checkbox" name="status-address" id="status-address"
                            value={$status_address} />
                        <label class="form-check-label" for="status-address" id="desativa">Desativado</label>
                    </div>
                </div>

                <div class="col-md-9 col-sm-9 col-xs-9">
                    <button type="button" class="btn btn-secondary" id="btnCancelar"
                        data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="javascript:insertAddress()">Salvar</button>
                </div>
            </div>

        </div>

    </div>
</div>
<!--END MODAL DELIVERY ADDRESS -->

<!-- MODAL OBRAS -->
<div class="modal fade" id="ModalObra" tabindex="-1" role="dialog" aria-labelledby="ModalObra" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-dialog-centered">
            <div class="modal-header">
                <h5 class="modal-title" id="modalObraTitle"></h5>
                <button type="button" class="close btnFecha" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="formObra">
                    <input type="hidden" id="id_obra_modal" name="id_obra" value="">
                    <input type="hidden" name="cliente_id" value="{$id}">

                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-6 form-group">
                            <label for="cno_modal">CNO</label>
                            <input class="form-control" maxlength="13" type="text" id="cno_modal" name="cno"
                                placeholder="CNO">
                        </div>
                        <div class="col-md-9 col-sm-6 col-xs-6 form-group">
                            <label for="projeto_modal">Projeto</label>
                            <input class="form-control" maxlength="90" type="text" id="projeto_modal" name="projeto"
                                placeholder="Projeto">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                            <label for="responsavel_tecnico_modal">Responsável Técnico</label>
                            <SELECT class="form-control" name="responsavel_tecnico" id="responsavel_tecnico_modal">
                                {html_options values=$responsavel_tecnico_ids output=$responsavel_tecnico_names selected=''}
                            </SELECT>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                            <label for="crea_modal">CREA</label>
                            <input class="form-control" maxlength="9" type="text" id="crea_modal" name="crea"
                                placeholder="CREA">
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-6 form-group">
                            <label for="art_modal">ART</label>
                            <input class="form-control" maxlength="13" type="text" id="art_modal" name="art"
                                placeholder="ART">
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-warning" onclick="limparModalObra()">Limpar Formulário</button>
                <button type="button" class="btn" id="btnSalvarAtualizarObra" onclick="salvarObra()"></button>
                <div class="col-md-3 col-sm-3 col-xs-3 d-flex align-items-left">
                    <div class="form-check pull-left" style="margin-top: 10px; margin-left: -12px; font-size:14px;">
                        <input class="form-check-input" type="checkbox" name="status_obra" id="status_obra" value="">
                        <label class="form-check-label" for="status_obra" id="desativa_obra">Desativado</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END MODAL OBRAS -->

<!-- Modal Anexo -->
<div class="modal fade" id="ModalAnexo" tabindex="-1" role="dialog" aria-labelledby="ModalAnexoLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="display: flex; flex-direction: column; gap: 10px;">
                <!-- Botões no topo à direita -->
                <div style="display: flex; justify-content: flex-end; width: 100%; gap: 10px;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="salvarAnexo()">Anexar</button>
                </div>

                <!-- Campo de arquivo e mensagem com borda ajustável -->
                <div class="modal-group" style="width: 100%;">
                    <input type="file" class="form-control-file" id="arquivoAnexo" name="file">
                    <div style="display: inline-block; text-align: left; margin-top: 5px; color: #dc3545; font-size: 0.875em; 
                        border: 1px solid #dc3545; border-radius: 4px; padding: 5px 8px; background-color: #ffffff;
                        white-space: nowrap;">
                        <i class="fa fa-exclamation-triangle" aria-hidden="true"
                            style="color: #dc3545; margin-right: 5px;"></i>
                        Apenas arquivos JPEG, JPG e PDF são permitidos.
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <form id="formAnexo" enctype="multipart/form-data">

                        <input type="hidden" id="id_obra_anexo" name="id_obra" value="">
                    </form>
                    <div id="anexosExistentes" class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Fim Modal Anexo -->

    {include file="template/form.inc"}
    </form>

    <script>
        $(function() {
            $('#dataNascimento').daterangepicker({
                singleDatePicker: true,
                autoUpdateInput: false,
                calender_style: "picker_1",
                locale: {
                    format: 'DD/MM/YYYY',
                    daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho',
                        'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
                    ],
                }

            });
        });
    </script>

    <script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".money").maskMoney({
                decimal: ",",
                thousands: ".",
                allowNegative: true,
                allowZero: true
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            carregarListaObras();
        });
</script>