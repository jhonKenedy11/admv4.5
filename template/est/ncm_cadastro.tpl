<style>

.form-control,
.x_panel {
    border-radius: 5px;
}
</style>
<!--<script type="text/javascript" src="{$pathJs}/est/s_ncm.js"> </script> -->
<script type="text/javascript" src="{$pathJs}/bib/s_default.js"> </script>
<script type="text/javascript" src="{$pathSweet}/dist/sweetalert2.all.min.js"></script>

        <!-- page content -->
        <div class="right_col" role="main">      
      <div class="">

        <div class="clearfix"></div>

        <form id="lancamento" data-parsley-validate class="form-horizontal form-label-left" NAME="lancamento"  ACTION="{$SCRIPT_NAME}" METHOD="post">
            <input name=mod           type=hidden value="est">   
            <input name=form          type=hidden value="ncm">   
            <input name=opcao         type=hidden value="">   
            <input name=submenu       type=hidden value={$subMenu}>
            <input name=letra         type=hidden value={$letra}>
            {if $subMenu eq "alterar"}  
                <input name=id type=hidden value={$id}> 
            {/if}

            
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>
                        {if $subMenu eq "cadastrar"}
                          Ncm - Cadastro 
                        {else}
                          Ncm - Altera&ccedil;&atilde;o 
                        {/if}
                    </h2>
                    {include file="../bib/msg.tpl"}
                    
                    <ul class="nav navbar-right panel_toolbox">
                        <li><button type="button" class="btn btn-primary" id="btnSubmit" onClick="javascript:submitConfirmar();">
                                <span class="glyphicon glyphicon-floppy-save" aria-hidden="true"></span><span> Confirmar</span></button>
                        </li>
                        <li><button type="button" class="btn btn-danger"  onClick="javascript:submitVoltar();">
                                <span class="glyphicon glyphicon-backward" aria-hidden="true"></span><span> Voltar</span></button>
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
                    <div class="d-flex justify-content-center">
                    <form class="container" novalidate="" action="/echo" method="POST" id="myForm">
                    

                      <div class="form-group">

                          <div class="col-md-2 col-sm-12 col-xs-12"></div>

                          <div class="col-md-2 col-sm-12 col-xs-12">
                            <label for="ncm">Ncm</label>
                            <input class="form-control" type="text" maxlength="8" required id="ncm" 
                              name="ncm" {if $subMenu eq "alterar"} disabled {/if} 
                              placeholder="Digite o código da NCM." value={$ncm}>
                          </div>

                           <div class="col-md-6 col-sm-12 col-xs-12">
                            <label for="descricao">Descri&ccedil;&atilde;o</label>
                            <input class="form-control" type="text" maxlength="256" required id="descricao" name="descricao" 
                              placeholder="Digite a descrição da NCM." value={$descricao}>
                          </div>

                      </div>

                      <div class="form-group">

                        <div class="col-md-2 col-sm-12 col-xs-12"></div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="id">Id</label>
                          <input class="form-control money" disabled 
                            type="text" id="id" name="id" value={$id}>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqIpi">Alíq. Ipi</label>
                          <input class="form-control money" type="money" maxlength="5" id="aliqIpi" name="aliqIpi" value={$aliqIpi}>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqPisMonofasica">Alíq. Pis Monofasica</label>
                          <input class="form-control money" type="money" maxlength="5" id="aliqPisMonofasica" name="aliqPisMonofasica" value={$aliqPisMonofasica}>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqCofinsMonofasica">Alíq. Cofins Monofasica</label>
                          <input class="form-control money" type="money" maxlength="5" id="aliqCofinsMonofasica" name="aliqCofinsMonofasica" value={$aliqCofinsMonofasica}>
                        </div>

                      </div>

                      <div class="form-group">

                        <div class="col-md-2 col-sm-12 col-xs-12"></div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqTTNacFederal">Alíq. Nac Federal</label>
                          <input class="form-control money" type="money" maxlength="5" id="aliqTTNacFederal" name="aliqTTNacFederal" value={$aliqTTNacFederal}>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqTTImpFederal">Alíq. Imp Federal</label>
                         <input type="money" class="form-control money" maxlength="5" id="aliqTTImpFederal" name="aliqTTImpFederal" value={$aliqTTImpFederal}>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqTTEstadual">Alíq. Estadual</label>
                         <input class="form-control money" type="money" maxlength="5" id="aliqTTEstadual" name="aliqTTEstadual" value={$aliqTTEstadual}>
                        </div>

                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <label for="aliqTTMunicipal">Alíq. Municipa</label>
                         <input class="form-control money" type="money" maxlength="5" id="aliqTTMunicipal" name="aliqTTMunicipal" value={$aliqTTMunicipal}>
                        </div>

                      </div>      

                      <div class="form-group">

                        <div class="col-md-2 col-sm-12 col-xs-12"></div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label for="vigenciaInicio">Vigencia Inicio</label>
                         <input class="form-control" type="date" id="vigenciaInicio" name="vigenciaInicio" 
                            value={$vigenciaInicio}>
                        </div>

                        <div class="col-md-4 col-sm-12 col-xs-12">
                          <label for="vigenciaFim">Vigencia Final</label>
                         <input class="form-control" type="date" id="vigenciaFim" name="vigenciaFim" 
                            value={$vigenciaFim}>
                        </div>

                      </div>
                    </div>
      
                  </form>                      


                      <div class="ln_solid"></div>
                        
                  </div>
                </div>
              </div>
            </div>
        </form>

      </div>

    {include file="template/form.inc"}


<style type="text/css">
.form-control:focus {
    border-color: #159ce4;
    transition: all 0.7s ease;
}
 
}
</style>
<script src="https://cdn.rawgit.com/plentz/jquery-maskmoney/master/dist/jquery.maskMoney.min.js"></script> 
    <script>
      $(document).ready(function(){
        $(".money").maskMoney({            
         decimal: ",",
         thousands: ".",
         allowNegative: true
        });        
     });
    </script>

