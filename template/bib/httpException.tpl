        <!-- page content -->
        <div class="right_col" role="main">                
    <form class="full" NAME="error" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$modulo}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=opcao         type=hidden value="{$opcao}">   
        <input name=id            type=hidden value={$id}>
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value=''>

        
        <div class="small">
            <div class="page-title">
              <div class="title_left">
                <h3>{$title}</h3>
              </div>
              <div class="title_left">
                  <b>Entrar em contato com o Suporte ADM</b><br> fone: (41) 99593-0181 <br>email: suporte@admservice.com.br ( Copiar a TELA e enviar por email )<br>Tutoriais: www.admsistema.com.br<br>
                 <button type="button" class="btn btn-warning"  onClick="javascript:document.error.submit();"><span> Continuar</span></button>
              </div>
            </div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-error" role="alert">{$code}&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    <div class="clearfix"></div>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-12 col-sm-12 col-xs-12" >File: {$file} </label>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" >Line: {$line} </label>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" >Data: {$date} </label>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" >Empresa: {$empresa} </label>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" >Us&uacute;ario: {$nomeUser} </label>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" >M&oacute;dulo: {$modulo} </label>
                    </div>
                    <div class="row">
                         <label class="control-label col-md-3 col-sm-3 col-xs-3" >Formul&aacute;rio: {$form} </label>
                    </div>
                    <div class="row">
                         <label class="control-label col-md-3 col-sm-3 col-xs-3" >Menu: {$submenu} </label>
                    </div>
                    <div class="row">
                         <label class="control-label col-md-3 col-sm-3 col-xs-3" >Parametro: {$letra} </label>
                    </div>
                    <div class="row">
                        <label class="control-label col-md-3 col-sm-3 col-xs-3" >Op&ccedil;&atilde;o: {$opcao} </label>
                    </div>
                    <div class="row">
                         <label class="control-label col-md-3 col-sm-3 col-xs-3" >ID: {$id} </label>
                    </div>
                        
                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th>Arquivo</th>
                                <th>Linha</th>
                                <th>Fun&ccedil;&atilde;o</th>
                                <th>Classe</th>
                                <th>Tipo</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$trace}
                                <tr class="even pointer">
                                    <td> {$trace[i].file} </td>
                                    <td> {$trace[i].line} </td>
                                    <td> {$trace[i].function} </td>
                                    <td> {$trace[i].class} </td>
                                    <td> {$trace[i].type} </td>
                                </tr>
                        {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
              </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
            </div> <!-- div class="row "-->
          </div> <!-- class='' = controla menu user -->

    </form>


    {include file="template/database.inc"}  
    
    <!-- /Datatables -->
