<style>
        .form-control,
        .x_panel {
            border-radius: 5px;
        }
</style>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="{$pathJs}/util/s_usuario.js"> </script>
<!-- page content -->
<div class="right_col" role="main">
    <div class="">

        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"
            ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod type=hidden value="util">
            <input name=form type=hidden value="usuario">
            <input name=opcao type=hidden value={$opcao}>
            <input name=submenu type=hidden value={$subMenu}>
            <input name=letra type=hidden value={$letra}>
            <input name=fornecedor type=hidden value="">
            <input name=pessoa type=hidden value={$pessoa}>


            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <div class="page-title">
                                <div class="title_left">
                                    <h2>
                                    {if $subMenu eq "cadastrar"}
                                        Usu&aacute;rios - Cadastro
                                    {else}
                                        Usu&aacute;rios - Altera&ccedil;&atilde;o
                                    {/if}
                                    </h2>
                                </div>
                            </div>
                            

                            <ul class="nav navbar-right panel_toolbox">
                                <li><button type="button" class="btn btn-primary"
                                        onClick="javascript:submitConfirmar('usuario');">
                                        <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span>
                                            Confirmar</span></button>
                                </li>
                                <li><button type="button" class="btn btn-danger"
                                        onClick="javascript:submitVoltarTeste('usuario');">
                                        <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span>
                                            Voltar</span></button>
                                </li>
                                {* <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li> *}
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <br />
                            <div class="form-group">
                                <div class="col-md-5 col-sm-12 col-xs-12">
                                    <label for="nome">Pessoa</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nome" name="nome"
                                            placeholder="Conta" required="required" value={$pessoaNome}>
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-primary"
                                                onClick="javascript:abrir('{$pathCliente}/index.php?mod=crm&form=contas&opcao=pesquisar');">
                                                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12 ">
                                    <label for="nomeReduzido">Nome Reduzido</label>
                                    <input class="form-control" maxlength="15" type="text" id="nomeReduzido"
                                        name="nomeReduzido" title="Nome a ser utilizado na apresentação."
                                        value={$nomeReduzido}>
                                </div>


                            </div>

                            <div class="form-group">
                                <div class="col-md-5 col-sm-6 col-xs-12">
                                    <label for="situacao">Empresa</label>
                                    <select class="form-control" name="empresa" id="empresa">
                                        {html_options values=$empresa_ids output=$empresa_names selected=$empresa_id}
                                    </select>
                                </div>
                            </div>



                            <div class="form-group">
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label for="tipo">Tipo </label>
                                    <select class="form-control" name="tipo" id="tipo">
                                        {html_options values=$tipo_ids output=$tipo_names selected=$tipo_id}
                                    </SELECT>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12 ">
                                    <label for="grupo">Grupo</label>
                                    <select class="form-control" name="grupo" id="grupo">
                                        {html_options values=$grupo_ids output=$grupo_names selected=$grupo_id}
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label for="situacao">Situacao</label>
                                    <select class="form-control" name="situacao" id="situacao">
                                        {html_options values=$situacao_ids output=$situacao_names selected=$situacao_id}
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-md-2 col-sm-6 col-xs-12">
                                    <label for="usuario">Matr&iacute;cula </label>
                                    <input class="form-control" type="text" maxlength="6" required id="usuario"
                                        title="Código do usuário ou matricula." name="usuario" value={$usuario}>
                                </div>
                                <div class="col-md-3 col-sm-12 col-xs-12 ">
                                    <label for="log">Login</label>
                                    <input class="form-control" maxlength="14" type="text" id="log" name="login"
                                        placeholder="Nome a ser utilizado na autenticação."
                                        title="Nome a ser utilizado na autenticação." value={$login}>
                                </div>
                                <div class="col-md-3 col-sm-6 col-xs-12">
                                    <label for="senha">Senha</label>
                                    <input class="form-control" type="password" maxlength="20" required id="senha"
                                        title="Senha a ser utilizado na autenticação." name="senha" value={$senha}>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <label for="salario">Custo Hora</label>
                                    <input class="form-control" type="text" id="salario" name="salario"
                                        placeholder="Nome a ser utilizado na autenticação."
                                        title="Nome a ser utilizado na autenticação." value={$salario}>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <label for="comissaoFatura">Comiss&atilde;o Pedido</label>
                                    <input class="form-control" type="text" id="comissaoFatura" name="comissaoFatura"
                                        title="Comissão a ser paga no montante total do pedido" value={$comissaoFatura}>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <label for="salario">Comiss&atilde;o Recebimento</label>
                                    <input class="form-control" type="text" id="comissaoReceb" name="comissaoReceb"
                                        title="Comissão a ser paga no vencimento das parcelas, esse percentual anula o valor da comissão do pedido"
                                        value={$comissaoReceb}>
                                </div>

                            </div>

                            <div class="form-group">
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <label for="smtp">SMTP</label>
                                    <input class="form-control" type="text" id="smtp" name="smtp"
                                        placeholder="preencher SMTP" title="" value={$smtp}>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <label for="email">Email</label>
                                    <input class="form-control" type="text" id="email" name="email"
                                        placeholder="preencher Email" title="" value={$email}>
                                </div>
                                <div class="col-md-2 col-sm-3 col-xs-12">
                                    <label for="emailsenha">senha</label>
                                    <input class="form-control" type="password" id="emailsenha"
                                        placeholder="preencher senha do email" name="emailsenha" value={$emailsenha}>
                                </div>

                            </div>




                            <!--
    <fieldset class="grupo">
        <div class="campo">
            <label for="conta">Conta Banc&aacute;ria</label>
            <input type="text" id="conta" name="conta" style="width: 8em" value={$conta}>
        </div>
    </fieldset>

        tr>
		<td class="ColunaTitulo">
            Encargos:
		</td>		
		<td class="ColunaSubTitulo">
			<input type="text" size="20" name="encargos" value={$encargos}> 
		</td>
	</tr>
	<tr>
		<td class="ColunaTitulo">
            Genero pagamento:
		</td>		
		<td class="ColunaSubTitulo">
			<input type="text" size="8" name="generoPgto" value={$generoPgto}>
		</td>
	</tr>
		<tr>
		<td class="ColunaTitulo">
            Custo Pagamento:
		</td>
		<td class="ColunaSubTitulo">
			<input type="text" size="12" name="ccustoPgto" value={$ccustoPgto}>
		</td>
	</tr>
	<tr>
		<td class="ColunaTitulo">
            Comissao Fatura:
		</td>		
		<td class="ColunaSubTitulo">
			<input type="text" size="12" name="comissaoFatura" value={$comissaoFatura}>
		</td>
	</tr>
	<tr>
		<td class="ColunaTitulo">
            Comissao Recebe:
		</td>		
		<td class="ColunaSubTitulo">
			<input type="text" size="12" name="comissaoReceb" value={$comissaoReceb}> 
		</td>
	</tr -->

                            <div class="ln_solid"></div>

                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    {include file="template/form.inc"}
    