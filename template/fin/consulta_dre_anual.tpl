<script src="{$bootstrap}/Chart.js/dist/Chart.min.js"></script>
<script src="{$pathJs}/../bib/js/vendor/xlsx.full.min.js"></script>
<script type="text/javascript" src="{$pathJs}/fin/s_lancamento.js"> </script>

<style>
  /* Padrão de impressão (igual demais relatórios) */
  @media print {
    @page { margin: 0.5cm 0.5cm; }
    .no-print { display: none !important; }

    body { margin: 0 !important; padding: 0 !important; font-size: 10pt; }
    .right_col { margin: 0 !important; padding: 0 !important; }
    .x_panel { margin: 0 !important; padding: 0 !important; border: none !important; box-shadow: none !important; }
    .x_content { padding: 0 !important; }

    .invoice-header img { max-width: 120px !important; max-height: 30px !important; }
    .invoice-header h3 { font-size: 12px !important; margin: 0 0 2px 0 !important; }
    .invoice-header h2 { font-size: 10px !important; margin: 0 !important; }

    table.table { width: 100% !important; border-collapse: collapse !important; }
    table.table th, table.table td { padding: 3px 4px !important; font-size: 9px !important; }
    table.table th { white-space: nowrap; }
  }
</style>

<!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <!--div class="page-title">
              <div class="col-md-4 col-sm-4 col-xs-4 form-group">
                <img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></A>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6 form-group">
                  {$empresa[0].NOMEEMPRESA}<br>
                  ({$empresa[0].FONEAREA}) {$empresa[0].FONENUM}
              </div>
              <div class="col-md-2 col-sm-2 col-xs-2 form-group">
                  <h2>Pedido: {$pedido[0].PEDIDO}<BR>Romaneio</h2>
              </div>
                
            </div>
            <div class="clearfix"></div-->


                <div class="x_panel">
                  <div class="x_content">


                    <section class="content invoice">
                      <!-- title row -->
                      <div class="row">
                        <div class="col-xs-12 invoice-header">
                            <i class="pull-left"><img  src="images/logo.png" aloign="right" width=180 height=45 border="0"></i>
                                  
                            <h3 class="pull-left"><span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>   DRE Financeiro - Anual</h3>
                            <h2 class="pull-right">Per&iacute;odo - In&iacute;cio: {$dataInicio} - Fim: {$dataFim}
                            </h2>
                        </div>
                        <!-- /.col -->
                      </div>
                            

                      <!--div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="x_panel">
                          <div class="x_title">
                              <h2>Progress&atilde;o D&eacute;bito e Cr&eacute;dito</h2>
                            <ul class="nav navbar-right panel_toolbox">
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <canvas id="lineChart"></canvas>
                          </div>
                        </div>
                      </div-->                            

                           
                            
                            

                      <!-- Table row -->
                      <div class="row">
                        <div class="col-xs-12 table">
                          <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Genero</th>
                                    <th>Descri&ccedil;&atilde;o Genero</th>
                                    <th>Janeiro</th>
                                    <th>Fevereiro</th>
                                    <th>Março</th>
                                    <th>Abril</th>
                                    <th>Maio</th>
                                    <th>Junho</th>
                                    <th>Julho</th>
                                    <th>Agosto</th>
                                    <th>Setembro</th>
                                    <th>Outubro</th>
                                    <th>Novembro</th>
                                    <th>Dezembro</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                {section name=i loop=$lanc}

                                        {assign var="gen" value=$lanc[i].GENERO|truncate:1:""}
                                        {if $gen eq "1"}
                                                {assign var="recOperJanuary" value=$recOperJanuary+$lanc[i].JANUARY}
                                                {assign var="recOperFebruary" value=$recOperFebruary+$lanc[i].FEBRUARY}
                                                {assign var="recOperMarch" value=$recOperMarch+$lanc[i].MARCH}
                                                {assign var="recOperApril" value=$recOperApril+$lanc[i].APRIL}
                                                {assign var="recOperMay" value=$recOperMay+$lanc[i].MAY}
                                                {assign var="recOperJune" value=$recOperJune+$lanc[i].JUNE}
                                                {assign var="recOperJuly" value=$recOperJuly+$lanc[i].JULY}
                                                {assign var="recOperAugust" value=$recOperAugust+$lanc[i].AUGUST}
                                                {assign var="recOperSeptember" value=$recOperSeptember+$lanc[i].SEPTEMBER}
                                                {assign var="recOperOctober" value=$recOperOctober+$lanc[i].OCTOBER}
                                                {assign var="recOperNovember" value=$recOperNovember+$lanc[i].NOVEMBER}
                                                {assign var="recOperDecember" value=$recOperDecember+$lanc[i].DECEMBER}
                                                {assign var="recOper" value=$recOper
                                                +$lanc[i].JANUARY
                                                +$lanc[i].FEBRUARY
                                                +$lanc[i].MARCH
                                                +$lanc[i].APRIL
                                                +$lanc[i].MAY
                                                +$lanc[i].JUNE
                                                +$lanc[i].JULY
                                                +$lanc[i].AUGUST
                                                +$lanc[i].SEPTEMBER
                                                +$lanc[i].OCTOBER
                                                +$lanc[i].NOVEMBER
                                                +$lanc[i].DECEMBER}

                                        {elseif $gen eq "2"}
                                                {assign var="custoVarJanuary" value=$custoVarJanuary+$lanc[i].JANUARY}
                                                {assign var="custoVarFebruary" value=$custoVarFebruary+$lanc[i].FEBRUARY}
                                                {assign var="custoVarMarch" value=$custoVarMarch+$lanc[i].MARCH}
                                                {assign var="custoVarApril" value=$custoVarApril+$lanc[i].APRIL}
                                                {assign var="custoVarMay" value=$custocustoVarMayVar+$lanc[i].MAY}
                                                {assign var="custoVarJune" value=$custoVarJune+$lanc[i].JUNE}
                                                {assign var="custoVarJuly" value=$custoVarJuly+$lanc[i].JULY}
                                                {assign var="custoVarAugust" value=$custoVarAugust+$lanc[i].AUGUST}
                                                {assign var="custoVarSeptember" value=$custoVarSeptember+$lanc[i].SEPTEMBER}
                                                {assign var="custoVarOctober" value=$custoVarOctober+$lanc[i].OCTOBER}
                                                {assign var="custoVarNovember" value=$custoVarNovember+$lanc[i].NOVEMBER}
                                                {assign var="custoVarDecember" value=$custoVarDecember+$lanc[i].DECEMBER}
                                                {assign var="custoVar" value=$custoVar
                                                +$lanc[i].JANUARY
                                                +$lanc[i].FEBRUARY
                                                +$lanc[i].MARCH
                                                +$lanc[i].APRIL
                                                +$lanc[i].MAY
                                                +$lanc[i].JUNE
                                                +$lanc[i].JULY
                                                +$lanc[i].AUGUST
                                                +$lanc[i].SEPTEMBER
                                                +$lanc[i].OCTOBER
                                                +$lanc[i].NOVEMBER
                                                +$lanc[i].DECEMBER}
                                        {elseif $gen eq "4"}
                                                {assign var="custoFixoJanuary" value=$custoFixoJanuary+$lanc[i].JANUARY}
                                                {assign var="custoFixoFebruary" value=$custoFixoFebruary+$lanc[i].FEBRUARY}
                                                {assign var="custoFixoMarch" value=$custoFixoMarch+$lanc[i].MARCH}
                                                {assign var="custoFixoApril" value=$custoFixoApril+$lanc[i].APRIL}
                                                {assign var="custoFixoMay" value=$custoFixoMay+$lanc[i].MAY}
                                                {assign var="custoFixoJune" value=$custoFixoJune+$lanc[i].JUNE}
                                                {assign var="custoFixoJuly" value=$custoFixoJuly+$lanc[i].JULY}
                                                {assign var="custoFixoAugust" value=$custoFixoAugust+$lanc[i].AUGUST}
                                                {assign var="custoFixoSeptember" value=$custoFixoSeptember+$lanc[i].SEPTEMBER}
                                                {assign var="custoFixoOctober" value=$custoFixoOctober+$lanc[i].OCTOBER}
                                                {assign var="custoFixoNovember" value=$custoFixoNovember+$lanc[i].NOVEMBER}
                                                {assign var="custoFixoDecember" value=$custoFixoDecember+$lanc[i].DECEMBER}
                                                {assign var="custoFixo" value=$custoFixo
                                                +$lanc[i].JANUARY
                                                +$lanc[i].FEBRUARY
                                                +$lanc[i].MARCH
                                                +$lanc[i].APRIL
                                                +$lanc[i].MAY
                                                +$lanc[i].JUNE
                                                +$lanc[i].JULY
                                                +$lanc[i].AUGUST
                                                +$lanc[i].SEPTEMBER
                                                +$lanc[i].OCTOBER
                                                +$lanc[i].NOVEMBER
                                                +$lanc[i].DECEMBER}
                                        {elseif $gen eq "5"}
                                                {assign var="custoFixoJanuary" value=$custoFixoJanuary+$lanc[i].JANUARY}
                                                {assign var="custoFixoFebruary" value=$custoFixoFebruary+$lanc[i].FEBRUARY}
                                                {assign var="custoFixoMarch" value=$custoFixoMarch+$lanc[i].MARCH}
                                                {assign var="custoFixoApril" value=$custoFixoApril+$lanc[i].APRIL}
                                                {assign var="custoFixoMay" value=$custoFixoMay+$lanc[i].MAY}
                                                {assign var="custoFixoJune" value=$custoFixoJune+$lanc[i].JUNE}
                                                {assign var="custoFixoJuly" value=$custoFixoJuly+$lanc[i].JULY}
                                                {assign var="custoFixoAugust" value=$custoFixoAugust+$lanc[i].AUGUST}
                                                {assign var="custoFixoSeptember" value=$custoFixoSeptember+$lanc[i].SEPTEMBER}
                                                {assign var="custoFixoOctober" value=$custoFixoOctober+$lanc[i].OCTOBER}
                                                {assign var="custoFixoNovember" value=$custoFixoNovember+$lanc[i].NOVEMBER}
                                                {assign var="custoFixoDecember" value=$custoFixoDecember+$lanc[i].DECEMBER}
                                                {assign var="custoFixo" value=$custoFixo
                                                +$lanc[i].JANUARY
                                                +$lanc[i].FEBRUARY
                                                +$lanc[i].MARCH
                                                +$lanc[i].APRIL
                                                +$lanc[i].MAY
                                                +$lanc[i].JUNE
                                                +$lanc[i].JULY
                                                +$lanc[i].AUGUST
                                                +$lanc[i].SEPTEMBER
                                                +$lanc[i].OCTOBER
                                                +$lanc[i].NOVEMBER
                                                +$lanc[i].DECEMBER}
                                        {elseif $gen eq "6"}
                                                {assign var="receitaFinJanuary" value=$receitaFinJanuary+$lanc[i].JANUARY}
                                                {assign var="receitaFinFebruary" value=$receitaFinFebruary+$lanc[i].FEBRUARY}
                                                {assign var="receitaFinMarch" value=$receitaFinMarch+$lanc[i].MARCH}
                                                {assign var="receitaFinApril" value=$receitaFinApril+$lanc[i].APRIL}
                                                {assign var="receitaFinMay" value=$receitaFinMay+$lanc[i].MAY}
                                                {assign var="receitaFinJune" value=$receitaFinJune+$lanc[i].JUNE}
                                                {assign var="receitaFinJuly" value=$receitaFinJuly+$lanc[i].JULY}
                                                {assign var="receitaFinAugust" value=$receitaFinAugust+$lanc[i].AUGUST}
                                                {assign var="receitaFinSeptember" value=$receitaFinSeptember+$lanc[i].SEPTEMBER}
                                                {assign var="receitaFinOctober" value=$receitaFinOctober+$lanc[i].OCTOBER}
                                                {assign var="receitaFinNovember" value=$receitaFinNovember+$lanc[i].NOVEMBER}
                                                {assign var="receitaFinDecember" value=$receitaFinDecember+$lanc[i].DECEMBER}
                                                {assign var="receitaFin" value=$receitaFin
                                                +$lanc[i].JANUARY
                                                +$lanc[i].FEBRUARY
                                                +$lanc[i].MARCH
                                                +$lanc[i].APRIL
                                                +$lanc[i].MAY
                                                +$lanc[i].JUNE
                                                +$lanc[i].JULY
                                                +$lanc[i].AUGUST
                                                +$lanc[i].SEPTEMBER
                                                +$lanc[i].OCTOBER
                                                +$lanc[i].NOVEMBER
                                                +$lanc[i].DECEMBER}
                                        {/if}
                                {/section}

                                {assign var="genOld" value='99'}
                                {section name=i loop=$lanc}
                                        {assign var="total" value=$total+$lanc[i].TOTAL}

                                        {assign var="gen" value=$lanc[i].GENERO|truncate:1:""}
                                        {if $gen eq "1"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>1</b> </td>
                                                    <td class=ColunaTitulo> <b>Receita Operacional Total</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperJanuary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperFebruary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperMarch|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperApril|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperMay|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperJune|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperJuly|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperAugust|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperSeptember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperOctober|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperNovember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOperDecember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$recOper|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                {/if}
                                        {elseif $gen eq "2"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>2</b> </td>
                                                        <td class=ColunaTitulo> <b>Custo Variavel</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarJanuary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarFebruary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarMarch|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarApril|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarMay|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarJune|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarJuly|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarAugust|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarSeptember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarOctober|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarNovember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVarDecember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoVar|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                {/if}
                                        {elseif $gen eq "4"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        {assign var="margemJanuary" value=$recOperJanuary-$custoVarJanuary}
                                                        {assign var="margemFebruary" value=$recOperFebruary-$custoVarFebruary}
                                                        {assign var="margemMarch" value=$recOperMarch-$custoVarMarch}
                                                        {assign var="margemApril" value=$recOperApril-$custoVarApril}
                                                        {assign var="margemMay" value=$recOperMay-$custoVarMay}
                                                        {assign var="margemJune" value=$recOperJune-$custoVarJune}
                                                        {assign var="margemJuly" value=$recOperJuly-$custoVarJuly}
                                                        {assign var="margemAugust" value=$recOperAugust-$custoVarAugust}
                                                        {assign var="margemSeptember" value=$recOperSeptember-$custoVarSeptember}
                                                        {assign var="margemOctober" value=$recOperOctober-$custoVarOctober}
                                                        {assign var="margemNovember" value=$recOperNovember-$custoVarNovember}
                                                        {assign var="margemDecember" value=$recOperDecember-$custoVarDecember}
                                                        {assign var="margem" value=$recOper-$custoVar}

                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>3</b> </td>
                                                        <td class=ColunaTitulo> <b>Margem de Contribui&ccedil;&atilde;o (1-2)</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemJanuary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemFebruary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemMarch|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemApril|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemMay|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemJune|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemJuly|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemAugust|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemSeptember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemOctober|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemNovember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margemDecember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$margem|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>4</b> </td>
                                                        <td class=ColunaTitulo> <b>Custo Fixo</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoJanuary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoFebruary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoMarch|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoApril|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoMay|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoJune|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoJuly|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoAugust|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoSeptember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoOctober|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoNovember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoDecember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixo|number_format:2:",":"."}</b> </td>
                                                </tr>

                                                {/if}
                                        {elseif $gen eq "5"}
                                                {if $genOld neq $gen}
                                                        {assign var="genOld" value=$gen}
                                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                                    <td class=ColunaTitulo> <b>4.1</b> </td>
                                                        <td class=ColunaTitulo> <b>Custo Fixo Adicional (Grupo 5)</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoJanuary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoFebruary|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoMarch|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoApril|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoMay|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoJune|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoJuly|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoAugust|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoSeptember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoOctober|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoNovember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixoDecember|number_format:2:",":"."}</b> </td>
                                                    <td class=ColunaTitulo> <b>{$custoFixo|number_format:2:",":"."}</b> </td>
                                                </tr>
                                                {/if}

                                        {/if}


                                        <tr bgcolor="{cycle values="#EBEBEB,#FFFFFF"}">
                                        <td> {$lanc[i].GENERO} </td>
                                        <td> {$lanc[i].DESCRICAO} </td>
                                        <td> {$lanc[i].JANUARY|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].FEBRUARY|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].MARCH|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].APRIL|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].MAY|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].JUNE|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].JULY|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].AUGUST|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].SEPTEMBER|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].OCTOBER|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].NOVEMBER|number_format:2:",":"."} </td>
                                        <td> {$lanc[i].DECEMBER|number_format:2:",":"."} </td>
                                        <td>{$lanc[i].TOTAL|number_format:2:",":"."} </td>
                                </tr>
                                <p>
                                {/section}

                                <tr>
                                        <td> <b>5</b> </td>
                                        <td> <b>Lucro Operacional </b></td>
                                        <td><b>{($margemJanuary-$custoFixoJanuary+$receitaFinJanuary)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemFebruary-$custoFixoFebruary+$receitaFinFebruary)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemMarch-$custoFixoMarch+$receitaFinMarch)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemApril-$custoFixoApril+$receitaFinApril)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemMay-$custoFixoMay+$receitaFinMay)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemJune-$custoFixoJune+$receitaFinJune)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemJuly-$custoFixoJuly+$receitaFinJuly)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemAugust-$custoFixoAugust+$receitaFinAugust)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemSeptember-$custoFixoSeptember+$receitaFinSeptember)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemOctober-$custoFixoOctober+$receitaFinOctober)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemNovember-$custoFixoNovember+$receitaFinNovember)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margemDecember-$custoFixoDecember+$receitaFinDecember)|number_format:2:",":"."}</b></td>
                                        <td><b>{($margem-$custoFixo+$receitaFin)|number_format:2:",":"."}</b></td>
                                        </td>
                                </tr>

                            </tbody>
                        </table>
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->


                      <!-- this row will not appear when printing -->
                      <div class="row no-print" style="text-align: center;">
                        <div class="col-xs-12">
                          <button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
                          <button class="btn btn-success" onclick="exportarTabelaParaExcel();"><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
                        </div>
                      </div>
                    </section>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

            <script type="text/javascript">

                var options = {
                   scales: {
                        yAxes: [{
                          ticks: {
                            beginAtZero: true,
                            suggestedMax : 10000,
                            maxTicksLimit: 8
                          }
                        }]
                      },                    
                    responsive:true
                };

                var data = {
                    labels: {$label},
                    datasets: [
                        {
                            label: "Debitos",
                            fillColor: "rgba(255,10,0,0.2)",
                            strokeColor: "rgba(255,0,0,1)",
                            pointColor: "rgba(255,0,0,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: {$pag}
                        },
                        {
                            label: "Creditos",
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(151,187,205,1)",
                            data: {$rec}
                        }
                    ]
                };               
                
                window.onload = function(){

                    var ctx = document.getElementById("lineChart").getContext("2d");
                    var LineChart = new Chart(ctx).Line(data, options);
                }  
            </script>

            <script type="text/javascript">
                function exportarTabelaParaExcel() {
                    var table = document.querySelector('.table-striped');
                    if (!table) {
                        alert('Tabela não encontrada!');
                        return;
                    }

                    if (typeof XLSX === 'undefined') {
                        alert('Biblioteca de exportação (XLSX) não carregada!');
                        return;
                    }

                    var wb = XLSX.utils.book_new();
                    var ws = XLSX.utils.table_to_sheet(table, { raw: true });

                    if (typeof converteColunaNumeroBR === 'function') {
                        for (var c = 2; c <= 14; c++) {
                            converteColunaNumeroBR(ws, c);
                        }
                    }
                    ws['!cols'] = [
                        {ldelim}wch: 10{rdelim},
                        {ldelim}wch: 40{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 14{rdelim},
                        {ldelim}wch: 18{rdelim}
                    ];

                    XLSX.utils.book_append_sheet(wb, ws, "DRE Anual");

                    var dataIni = '{$dataInicio}';
                    var dataFim = '{$dataFim}';
                    var nomeArquivo = 'DRE_Financeiro_Anual_' + dataIni.replace(/\//g, '_') + '_a_' + dataFim.replace(/\//g, '_') + '.xlsx';
                    XLSX.writeFile(wb, nomeArquivo);
                }
            </script>

