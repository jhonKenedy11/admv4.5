<style>
#dataConsulta{
  height: 38px !important;
  text-align: center;
  border-radius: 0px 5px 5px 0px !important;
  font-size: 13px;
}
.hidden {
  opacity: 0;
  transition: opacity 0.9s ease-out;
}
#limparData{
  cursor: pointer !important;
  border-radius: 5px;
  height: 35px;
  width: 40px;
  padding-top: 10px;
}
#confirmar{
  cursor: pointer !important;
  border-radius: 5px;
  height: 35px;
  width: 40px;
}
.btn-danger {
  background-color: #d9534f; /* Cor de fundo vermelha */
}

.btn-danger:hover {
  background-color: #c9302c; /* Cor de fundo vermelha com efeito hover */
}

.btn-warning{
  background-color: #e3a146; /* Cor de fundo vermelha */
}
.form-control, .x_panel{
  border-radius: 5px;
}
#divInputBloq{
  padding-right: 0 !important;
}

#divConfirmar{
  padding-left: 0 !important;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script type="text/javascript" src="{$pathJs}/ped/s_data_entrega_bloqueio.js"> </script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <!-- page content -->
  <div class="right_col" role="main">                
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}>

        
        <div class="">
            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Data de entrega bloqueada
                        <strong>
                            {if $mensagem neq ''}
                                    <div class="alert alert-success" role="alert">Sucesso!&nbsp;{$mensagem}</div>
                            {/if}
                        </strong>
                    </h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <div class="row">
                            <div class="col-md-3 col-xs-3 col-sm-12" id="divInputBloq">
                                <div class="input-group" style="height:38px !important;" id="periodoBloq">
                                    <label for="label_bloqueia" id="label_bloqueia" class="input-group-addon" style="font-size:13px; padding:5px;">Bloqueia data entrega</label>
                                    <input type="text" name="dataConsulta" id="dataConsulta" autocomplete="off" class="form-control" onchange="javascript:verificaData(this.value)" value="{$data}">

                                </div>
                                
                            </div>
                            <div class="col-md-4 col-xs-4 col-sm-12" id="divConfirmar">
                                <span id="confirmar" style="height:37px !important; font-size:13px;" class="input-group-addon hidden" onclick="javascript:dateDeliveryBlockInsert(dataConsulta.value)">Confirmar</span>
                            </div>
                            <div class="col-md-3 offset-md-3"></div>

                            <div class="col-md-2 col-xs-2 col-sm-12">
                                <span id="limparData" title="limpar campo" class="btn-warning input-group-addon glyphicon glyphicon-erase pull-right"></span>
                            </div>

                        </div>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%"-->
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th width="40px">ID</th>
                                <th>Data</th>
                                <th>Descrição</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].ID} </td>
                                    <td> {$lanc[i].DATA|date_format:"%d/%m/%Y"} </td>
                                    <td> {$lanc[i].RESULTADO} </td>
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
<script>
$(function() {
    $('input[name="dataConsulta"]').daterangepicker(
    {
        singleDatePicker: true,
        calender_style: "picker_1",
        locale: {
          format: 'DD/MM/YYYY',
            customRangeLabel: 'Calendário',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
        }
    });
});
</script>
