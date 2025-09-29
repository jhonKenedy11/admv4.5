        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="page-title">
          <div class="title_left">
              <h3>Lan&ccedil;amentos Financeiros</h3>
          </div>
        </div>
        <div class="clearfix"></div>
        <form id="lancamento" data-parsley-validate  class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod                 type=hidden value="est">   
            <input name=form                type=hidden value="produto">   
            <input name=submenu             type=hidden value={$subMenu}>
            <input name=id                  type=hidden value={$id}>
            <input name=letra               type=hidden value={$letra}>
            <input name=opcao               type=hidden value={$opcao}>
            <input name=pesquisa	    type=hidden value={$pesquisa}>
            <input name=loc                 type=hidden value={$loc}>
            <input name=ns                  type=hidden value={$ns}>
            <input name=idNF                type=hidden value={$idNF}>
            <input name=fornecedor          type=hidden value="">
            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
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
                                            <div class="alert alert-success" role="alert"><strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>
                            {elseif $tipoMsg eq 'alerta'}
                                <div class="row">
                                    <div class="col-lg-12 text-left">
                                        <div>
                                            <div class="alert alert-danger" role="alert"><strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                        </div>
                                    </div>
                                </div>       
                            {/if}

                        {/if}
                    </h2>
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary"  onClick="javascript:submitConfirmar('');">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar('');">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span> Cancelar</span></button>
                        </li>
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                      </li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                        <div class="form-group">

            <div class="row">
                <div class="col-md-12col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <small>Dados do Produto</small>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <!-- primeiro -->
                            <div class="row">
                                <div class="col-lg-2 text-left">
                                    <label for="id">Código</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" READONLY size="30" name="id"  value={$id}>
                                    </div>
                                </div>

                                <div class="col-lg-10 text-left">
                                    <label for="desc">Descricao</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" name="desc" placeholder="Descrição do produto*" maxlenght="60" required value={$desc}>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-2 text-left">
                                    <label for="nome">Unidade</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="5" name="uni" maxlength="3" value={$uni}>
                                    </div>
                                </div>
                                <div class="col-lg-6 text-left">
                                    <label for="grupo">Grupo</label>
                                    <div class="panel panel-default">
                                        <SELECT class="form-control" name="grupo" > 
                                            {html_options values=$grupo_ids selected=$grupo output=$grupo_names}
                                        </SELECT>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <a href=javascript:abrir('../crm/p_conta.php?pesquisa=lancamento')>
                                    <div class="col-lg-2 text-left">
                                        <label for="pessoa">Cod.</label>
                                        <div class="panel panel-default" >
                                            <input class="form-control" type="text" size="7" name="pessoa" readonly value={$pessoa}>
                                        </div>
                                    </div>
                                    <div class="col-lg-10 text-left">
                                        <label for="nome">Nome</label>
                                        <div class="form-group input-group">
                                            <input class="form-control" type="text" size="60" name="nome" placeholder="Selecione o fabricante" disabled  value={$nome}>
                                            <span class="input-group-btn">
                                                <button class="btn btn-default" type="button"><i class="fa fa-search"></i>
                                                </button>
                                            </span>
                                        </div>
                                    </div>


                                </a>
                            </div>
                            <div class="row">

                                <div class="col-lg-3 text-left">
                                    <label for="bloqueado">Cod. Fabricante</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="30" name="codFabricante" placeholder="Digite o cód. fabricante" maxlength="25" value={$codFabricante}>
                                    </div>
                                </div>
                                <div class="col-lg-3 text-left">
                                    <label for="ieRg">Localização</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="20" name="localizacao" maxlength="10" placeholder="Digite a localização do produto" value={$localizacao}>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <div class="x_panel" >
                        <div class="x_title">
                            <small>Tributação</small>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>
                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content" {if $subMenu eq "alterar"} style="display: none;"  {/if} >

                            <div class="row">
                                <div class="col-lg-6 text-left">
                                    <label for="cep">Origem</label>
                                    <div class="panel panel-default">
                                        <select name="origem" class="form-control">
                                            {html_options values=$origem_ids selected=$origem output=$origem_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 text-left">
                                    <label for="tipo">Tributação ICMS</label>
                                    <div class="panel panel-default">
                                        <select class="form-control" name=tribIcms>
                                            {html_options values=$tribIcms_ids selected=$tribIcms output=$tribIcms_names}
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 text-left">
                                    <label for="endereco">NCM</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="30" name="ncm" maxlength="15" placeholder="Digite o cód. NCM" value={$ncm}>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>




                </div>

                <div class="col-md-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <small>Valores</small>
                            <ul class="nav navbar-right panel_toolbox">
                                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                                </li>

                            </ul>
                            <div class="clearfix"></div>
                        </div>
                        <div class="x_content">
                            <div class="row">
                                <div class="col-lg-4 text-left">
                                    <label for="precoBase">Preço Base</label>
                                    <div class="panel panel-default">
                                        <select class="form-control" name="precoBase">
                                            {html_options values=$precoBase_ids selected=$precoBase_id output=$precoBase_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="foneArea">Preço Venda</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="venda" name="venda" placeholder="Valor de venda" value={$venda}>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="fone">Ultima Compra</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="custoCompra" name="custoCompra" placeholder="Valor da ultima compra" value={$custoCompra}>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4 text-left">
                                    <label for="faxArea">Custo Médio</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="custoMedio" name="custoMedio" placeholder="Valor médio" value={$custoMedio}>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="fax">Custo Reposição</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="custoReposicao" name="custoReposicao" placeholder="Valor de reposição" value={$custoReposicao}>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="precoInformado">Valor Informado Custo</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="precoInformado" name="precoInformado" placeholder="Digite o valor informado custo." value={$precoInformado}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 text-left">
                                    <label for="percCalculo">% Calculo</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="percCalculo" name="percCalculo" placeholder="Digite a % para calculo." value={$percCalculo}>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="contato">Moeda</label>
                                    <div class="panel panel-default">
                                        <select class="form-control" name=moeda>
                                            {html_options values=$moeda_ids selected=$moeda output=$moeda_names}
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="contato">Data Fora Linha</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="15" name="dataForaLinha" data-inputmask="'mask': '99/99/9999'" placeholder="Data Fora de Linha" value={$dataForaLinha}>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-3 text-left">
                                    <label for="bloqueado">Qtde. Min.</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="5" name="quantMinima" value={$quantMinima}>
                                    </div>
                                </div>

                                <div class="col-lg-3 text-left">
                                    <label for="bloqueado">Qtde. Max.</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" size="5" name="quantMaxima" value={$quantMaxima}>
                                    </div>
                                </div>
                                <div class="col-lg-6 text-left">
                                    <label for="bloqueado">Data Cadastro</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" READONLY size="15" name="dataCadastro" value={$dataCadastro}>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-4 text-left">
                                    <label for="precoPromocao">Valor Promoção</label>
                                    <div class="panel panel-default">
                                        <input class="form-control dinheiro" type="text" id="precoPromocao" name="precoPromocao" placeholder="Digite o valor de promoção." value={$precoPromocao}>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="inicioPromocao">Periodo Inicio Promoção</label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" id="inicioPromocao" name="inicioPromocao" data-inputmask="'mask': '99/99/9999'" placeholder="Data de Inicio da Promoção." value={$inicioPromocao}>
                                    </div>
                                </div>
                                <div class="col-lg-4 text-left">
                                    <label for="fimPromocao">Periodo Fim Promoção </label>
                                    <div class="panel panel-default">
                                        <input class="form-control" type="text" id="fimPromocao" name="fimPromocao" data-inputmask="'mask': '99/99/9999'" placeholder="Data Fim da Promoção." value={$fimPromocao}>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>





                <div class="row">
                    <div class="col-lg-12 text-left">
                        <label for="obs">Observação</label>
                        <div class="panel panel-default">
                            <textarea class="form-control" placeholder="Digite observação do produto" rows="4"  id="obs" name="obs">{$obs}</textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 text-left">
                        <div>
                            <button type="button" class="btn btn-primary" onClick="javascript:submitConfirmar('');"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Confirmar</button>
                            <button type="button" class="btn btn-danger" onClick="javascript:submitVoltar('');"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span> Cancelar</button>
                        </div>
                    </div>

                </div>

        </form>
      </div>

    {include file="template/form.inc"}  
                    
    <script src="{$bootstrap}/js/switchery/switchery.min.js"></script>
    <script src="{$bootstrap}/js/input_mask/jquery.inputmask.js"></script>
    <script src="{$bootstrap}/js/input_mask/jquery.maskMoney.js"></script>
    {literal}
    <script>
            $(document).ready(function () {
                $(":input").inputmask();
                $("input.dinheiro").maskMoney({decimal:",", thousands:"."});
            });
    </script>
    {/literal}
