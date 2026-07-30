          <div class="col-md-6 col-sm-6 ">
            <div class="x_panel tile" id="acomp">
              <div class="x_title">
                <h2 style="color: #73879C;">Acompanhamentos</h2>
                <button type="button" style="margin-left: 6px" class="btn btn-primary btn-xs pull-left" id="btnDiario"
                  onclick="javascript:buscaAcompanhamentosMetas('diario');">
                  <span class="" aria-hidden="true" data-toggle="tooltip" title="Visualizar diario">Diário</span>
                </button>
                <button type="button" class="btn btn-primary btn-xs pull-left"
                  onclick="javascript:buscaAcompanhamentosMetas('periodo');">
                  <span class="" aria-hidden="true" data-toggle="tooltip" title="Visualizar Periodo">Período</span>
                </button>
                <button type="button" class="btn btn-danger btn-xs pull-left"
                  onclick="javascript:buscaAcompanhamentosMetas('concluido');">
                  <span class="" aria-hidden="true" data-toggle="tooltip" title="Visualizar Periodo">Concluídos período</span>
                </button>
                <button type="button" class="btn btn-success btn-xs pull-right" id="calendar"
                  onclick="javascript:visualizarCalendario('');">
                  <span class="glyphicon glyphicon-calendar calendar" aria-hidden="true" data-toggle="tooltip"
                    title="Visualizar Calendário"></span>
                </button>
                <button type="button" class="btn btn-success btn-xs pull-right" id="btnAddAcomp"
                  onclick="javascript:abrirAcompanhamentoModal();">
                  <span class="glyphicon fa fa-plus-circle" aria-hidden="true" data-toggle="tooltip"
                    title="Adicionar Acompanhamento"></span>
                </button>
                <div class="clearfix"></div>
                <div class="form-group" style="margin: 10px 0 0 0;">
                  <div class="input-group input-group-sm">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="search" id="crmDashBuscaAcomp" class="form-control" placeholder="Buscar na lista (cliente, texto, status…)" autocomplete="off" />
                  </div>
                </div>
              </div>
              <h4> </h4>
              <ul class="list-unstyled scroll-view" id="ulAcompanhamento">
                {include file="crm_dashboard_fragment_acompanhamentos.tpl"}
              </ul>
            </div>
          </div>
