<style>
.form-control,
.x_panel {
    border-radius: 5px;
}

.modal-dialog.modal-custom {
  min-width: 500px;
  margin: 60px auto;
  display: flex;
  align-items: center;
  justify-content: center;
}

.modal-body-left .form-group label,
.modal-body-left .form-group input,
.modal-body-left .form-group select {
  text-align: left !important;
  display: block;
}

/* Flex no header para botões à direita */
.modal-header-flex {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.modal-header-buttons {
  display: flex;
  gap: 8px;
}
</style>
<script type="text/javascript" src="{$pathJs}/est/s_inventario.js"> </script>
        <!-- page content -->
        <div class="right_col" role="main">      
        <div class="">

            <div class="row">


              <!-- panel principal  -->  
              <div class="col-md-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title" style="margin-bottom: -5px;">
                    <h2>Inventario
                            {if $mensagem neq ''}
                                {if $tipoMsg eq 'sucesso'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-success" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Sucesso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>
                                {elseif $tipoMsg eq 'alerta'}
                                    <div class="row">
                                        <div class="col-lg-12 text-left">
                                            <div>
                                                <div class="alert alert-danger" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                <strong>--Aviso!</strong>&nbsp;{$mensagem}</div>
                                            </div>
                                        </div>
                                    </div>       
                                {/if}  
                            {/if}
                    </h2>
                    <div class="clearfix"></div>
                  </div>
                <!--div class="x_content" style="display: none;"-->
                <div class="x_content">

                    <form id="lancamento" name="lancamento" data-parsley-validate METHOD="POST"
                          class="form-horizontal form-label-left" ACTION={$SCRIPT_NAME}>
                            <input name=mod           type=hidden value="est">   
                            <input name=form          type=hidden value="inventario">   
                            <input name=id            type=hidden value="">
                            <input name=opcao         type=hidden value="{$opcao}">
                            <input name=letra         type=hidden value="{$letra}">
                            <input name=submenu       type=hidden value="{$subMenu}">
                            <input name=pessoa        type=hidden value={$pessoa}>
                            <input name=fornecedor    type=hidden value={$fornecedor}>
                            <input name=codProduto    type=hidden value={$codProduto}>
                            <input name=unidade       type=hidden value={$unidade}>
                            <input name=descProduto   type=hidden value={$descProduto}>
                            <input name=valorVenda    type=hidden value={$valorVenda}> 
                            <input name=uniFracionada type=hidden value="{$uniFracionada}">
                            <input name=pesq          type=hidden value={$pesq}>
                            <input name=tela          type=hidden value={$tela}>
                            <input name=dataIni       type=hidden value={$dataIni}> 
                            <input name=dataFim       type=hidden value={$dataFim}> 

                        <div class="form-group line-formated">
                        <div class="form-group col-md-3 col-sm-12 col-xs-12">
                                <label class="">Per&iacute;odo</label>
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>  
                                <div>
                                    <input type="text" name="dataConsulta" id="dataConsulta" class="form-control"
                                    value="{$dataIni} - {$dataFim}">
                                </div>
                            </div>
                            <div class="form-group col-md-2 col-sm-12 col-xs-12">
                                <label>Status</label>
                                <SELECT class="form-control" name="status"> 
                                    {html_options values=$status_ids output=$status_names selected=$status_id}
                                </SELECT>
                            </div>
                            <div class="form-group col-md-7 col-sm-12 col-xs-12">
                                <div style="display: flex; justify-content: flex-end; gap: 5px; margin-top: 25px; padding-right: 0;">
                                    <button type="button" class="btn btn-warning" onClick="javascript:submitLetra()">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span><span> Pesquisar</span>
                                    </button>
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modalNovoInventario">
                                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span> Novo Inventário</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                  </div>

                  <div class="x_content" style="padding-top: -10px;">
                    <table id="datatable-buttons" class="table table-bordered jambo_table">
                        <thead>
                            <tr class="headings">
                                <th style="width:150px">Identificação Inventario</th>
                                <th>Referencia</th>
                                <th style="width:80px">Status</th>
                                <th>Usuario</th>
                                <th style="width:100px">Data Cadastro</th>
                                <th class=" no-link last" style="width: 80px;">Manutenção</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].ID} </td>
                                    <td> {$lanc[i].REFERENCIA} </td>
                                    <td> {$lanc[i].STATUS} </td>
                                    <td> {$lanc[i].NOME} </td>
                                    <td> {$lanc[i].CREATED_AT|date_format:"%d/%m/%Y"} </td>
                                    <td class="last">
                                        <div style="display: flex; gap: 4px;">
                                        {if $lanc[i].STATUS eq 'BAIXADO'} 
                                            <button type="button" class="btn btn-warning btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                                        {else} 
                                            <button type="button" class="btn btn-primary btn-xs" onclick="javascript:submitAlterar('{$lanc[i].ID}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                            <button type="button" class="btn btn-danger btn-xs" onclick="javascript:excluirInventario('{$lanc[i].ID}')">
                                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                                            </button>
                                        {/if}
                                        <button type="button" class="btn btn-info btn-xs" onclick="javascript:abrir('index.php?mod=est&form=rel_inventario&opcao=imprimir&id={$lanc[i].ID}');"><span class="glyphicon glyphicon-print" aria-hidden="true" data-toggle="tooltip" title="Impressão"></span></button>  
                                        </div>
                                    </td>
                                </tr>
                        {/section} 

                        </tbody>

                    </table>

                  </div> <!-- div class="x_content" = inicio tabela -->
                    
                </div> <!-- x_panel -->

                
                          
            </div> <!-- div class="tamanho --> 
        </div>  <!-- div row = painel principal-->



        
        
          </div> <!-- div  "-->
        </div> <!-- div role=main-->



    {include file="template/database.inc"}  
    <script src="{$bootstrap}/select2-master/dist/js/select2.full.min.js"></script>
    <!-- select2 -->
    <script>

        $("#grupo.select2_multiple").select2({
          placeholder: "Selecione o Grupo"
        });

        $("#centroCusto.js-example-basic-single").select2({
            placeholder: "Selecione o Centro de Custo",
            allowClear: true
        });

    </script>

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
    <style>
        .line-formated{
            margin-bottom: 1px;
        }
    </style>

    <!-- Modal Novo Inventário -->
    <div class="modal fade" id="modalNovoInventario" nome="modalNovoInventario" tabindex="-1" role="dialog" aria-labelledby="modalNovoInventarioLabel">
      <div class="modal-dialog modal-custom" role="document">
        <div class="modal-content">
          <form id="formNovoInventario" name="formNovoInventario" method="POST" action="{$SCRIPT_NAME}" class="form-horizontal">
            <div class="modal-body modal-body-left">
              <input type="hidden" name="mod" value="est">
              <input type="hidden" name="form" value="inventario">
              <input type="hidden" name="submenu" value="inclui">
              <div class="form-group">
                <label>Referência</label>
                <input type="text" class="form-control" name="referencia" maxlength="20" required placeholder="Referência do inventário">
              </div>
              <div class="form-group">
                <label>Centro de Custo</label>
                <input type="text" class="form-control" name="centroCusto" value="{$centroCusto_nome}" readonly>
                <input type="hidden" name="centroCusto_id" value="{$centroCusto_id}">
              </div>
              <div class="form-group">
                <label>Status</label>
                <input type="hidden" name="status" value="A">
                <input type="text" class="form-control" value="Aberto" readonly>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" onclick="javascript:submitConfirmar()">Cadastrar</button>
            </div>
          </form>
        </div>
      </div>
    </div>  


    <script src="js/datepicker/daterangepicker.js"></script>

    <script type="text/javascript">
    $('input[name="dataConsulta"]').daterangepicker(
    {
        startDate: moment("{$dataIni}", "DD/MM/YYYY"),
        endDate: moment("{$dataFim}", "DD/MM/YYYY"),
        ranges: {
            'Hoje': [moment(), moment()],
            'Ontem': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Últimos 7 Dias': [moment().subtract(6, 'days'), moment()],
            'Últimos 30 Dias': [moment().subtract(29, 'days'), moment()],
            'Este Mes': [moment().startOf('month'), moment().endOf('month')],
            'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Confirma',
            cancelLabel: 'Limpa',
            fromLabel: 'Início',
            toLabel: 'Fim',
            customRangeLabel: 'Calendário',
            daysOfWeek: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
            monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
            firstDay: 1
        }

    }, 
    //funcao para recuperar o valor digirado        
    function(start, end, label) {
        f = document.lancamento;
        f.dataIni.value = start.format('DD/MM/YYYY');
        f.dataFim.value = end.format('DD/MM/YYYY');            
    });
</script>  