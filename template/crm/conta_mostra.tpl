<style>
.right_col{
    border-radius: 5px !important;
    padding: 3px !important;
}

.divPrincipal{
    margin-top: -8px !important;
}

.allFont{
    font-size: 1.0rem;
}
.thEmail {
    width: 100px !important;
    word-wrap: break-word !important;
    word-break: break-all !important;
    white-space: normal !important;
}
.tdEmail {
    width: 100px !important;
    word-wrap: break-word !important;
    word-break: break-all !important;
    white-space: normal !important;
}


.thRepresentante{
    width: 40px !important;
}
.thFone{
    width: 40px !important;
}
.thManutencao,
.tdManutencao {
    width: 63px !important
}
.thCnpj {
    width: 40px !important
}
#pesNome{
    border-radius: 5px;
}
.form-control{
    border-radius: 5px !important;
}
.btnRelatorios {
    width: 100% !important;
}

.dropMenuRel {
    right: -94% !important;
    border-radius: 5px;
    background-color: rgba(76, 75, 75, 0.882);
}
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
<script type="text/javascript" src="{$pathJs}/crm/s_conta.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="row">

            <!-- panel principal  -->
            <div class="col-md-12 col-xs-12 divPrincipal">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Pessoas (Clientes/Fornecedores/Usu&aacute;rios)</h2>
                        {include file="../bib/msg.tpl"}
                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-warning" onClick="javascript:submitLetra();">
                                    <span class="glyphicon glyphicon-zoom-in" aria-hidden="true"></span><span>
                                        Pesquisa</span>
                                </button>
                            </li>
                            <li><button type="button" class="btn btn-primary" id="btnSubmit"
                                    onClick="javascript:submitCadastro('')" ;>
                                    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span>
                                        Cadastro</span>
                                </button>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                    aria-expanded="false"><i class="fa fa-wrench"></i></a>
                                <ul class="dropdown-menu dropMenuRel" role="menu">
                                    <li>
                                    <button type="button" class="btn btn-primary btn-xs btnRelatorios"
                                        onClick="javascript:submitVoltar('');"><span>Perfil</span></button>
                                    <!--<a href="javascript:submitVoltar('');">Perfil</a>-->
                                    </li>

                                    <li>
                                    <button type="button" class="btn btn-primary btn-xs btnRelatorios"
                                        onClick="javascript:submitVoltar('lista');"><span>Lista</span></button>
                                    <!--<a href="javascript:submitVoltar('lista');">Lista</a>-->
                                    </li>
                                </ul>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    <!--div class="x_content" style="display: none;"-->
                    <div class="x_content">

                        <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                            class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod type=hidden value="crm">
                            <input name=form type=hidden value="contas">
                            <input name=id type=hidden value="">
                            <input name=opcao type=hidden value={$opcao}>
                            <input name=letra type=hidden value={$letra}>
                            <input name=submenu type=hidden value={$subMenu}>
                            <input name=pesObs type=hidden value="">

                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label>Pessoa</label>
                                <input class="form-control" id="pesNome" name="pesNome" autofocus
                                    placeholder="Digite o nome do Pessoa." value={$pesNome}>
                            </div>


                            <div class="clearfix"></div>

                            <!-- dados adicionais -->
                            <!-- start accordion -->
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <div class="accordion" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel">
                                        <a class="panel-heading collapsed" role="tab" id="headingTwo" data-toggle="collapse"
                                            data-parent="#accordion" href="#collapseTwo" aria-expanded="false"
                                            aria-controls="collapseTwo">
                                            <h4 class="panel-title">Dados Adicionais <i class="fa fa-chevron-down"></i>
                                            </h4>
                                        </a>
                                        <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel"
                                            aria-labelledby="headingTwo">
                                            <div class="panel-body">
                                                <div class="x_panel">

                                                    <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                                        <input class="form-control" type="text" id="pesCnpjCpf"
                                                            name="pesCnpjCpf" placeholder="Digite o CNPJ/CPF."
                                                            value={$pesCnpjCpf}>
                                                    </div>
                                                    <div class="form-group col-md-5 col-sm-12 col-xs-12">
                                                        <input class="form-control" type="text" id="pesCidade"
                                                            name="pesCidade" placeholder="Digite a cidade." value={$cidade}>
                                                    </div>
                                                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                        <SELECT class="form-control" name="idEstado">
                                                            {html_options values=$estado_ids output=$estado_names selected=$estado_id}
                                                        </SELECT>
                                                    </div>
                                                    <div class="clearfix"></div>

                                                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                        <SELECT class="form-control" name="idVendedor">
                                                            {html_options values=$responsavel_ids output=$responsavel_names selected=$responsavel_id}
                                                        </SELECT>
                                                    </div>


                                                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                        <SELECT class="form-control" name="idAtividade">
                                                            {html_options values=$atividade_ids output=$atividade_names selected=$atividade_id}
                                                        </SELECT>
                                                    </div>

                                                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                        <SELECT class="form-control" name="idClasse">
                                                            {html_options values=$classe_ids output=$classe_names selected=$classe_id}
                                                        </SELECT>
                                                    </div>

                                                    <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                                        <SELECT class="form-control" name="idPessoa">
                                                            {html_options values=$tipoPessoa_ids output=$tipoPessoa_names selected=$tipoPessoa_id}
                                                        </SELECT>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- end of accordion -->

                        </form>

                        <div class="clearfix"></div>

                        <!-- tabela de resultados -->
                        <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                        <table id="datatable-buttons" class="table table-bordered jambo_table">
                            <thead>
                                <tr style="background: #2A3F54; color: white;">
                                    <th>Nome</th>
                                    <th class="thCnpj">CNPJ/CPF</th>
                                    <th>Cidade</th>
                                    <th class="thFone">Telefone</th>
                                    <th class="thEmail">Email</th>
                                    <th class="thRepresentante">Representante</th>
                                    <th class="thManutencao"></th>

                                </tr>
                            </thead>
                            <tbody>

                                {section name=i loop=$lanc}
                                    {assign var="total" value=$total+1}
                                    <tr>
                                        <td class="allFont"> {$lanc[i].NOME} </td>
                                        <td class="allFont"> {$lanc[i].CNPJCPF} </td>
                                        <td class="allFont"> {$lanc[i].CIDADE} - {$lanc[i].UF} </td>
                                        <td class="allFont"> {$lanc[i].FONE} / {$lanc[i].CELULAR} </td>
                                        <td class="allFont tdEmail"> {$lanc[i].EMAIL} </td>
                                        <td class="allFont"> {$lanc[i].REPRESENTANTE} </td>
                                        <td class="allFont tdManutencao">
                                            <button type="button" class="btn btn-primary btn-xs"
                                                onclick="javascript:submitAlterar('{$lanc[i].CLIENTE}');"><span
                                                    class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs"
                                                onclick="javascript:submitExcluir('{$lanc[i].CLIENTE}');"><span
                                                    class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                        </td>
                                    </tr>
                                {/section}

                            </tbody>
                        </table>

                    </div>

                </div> <!-- div class="x_content" = inicio tabela -->
            </div> <!-- div class="x_panel" = painel principal-->
        </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
    </div> <!-- div class="row "-->



    {include file="template/database.inc"}

<!-- /Datatables -->