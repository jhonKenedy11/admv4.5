<script type="text/javascript" src="{$pathJs}/fin/s_banco.js"> </script>
  <!-- page content -->
  <div class="right_col" role="main">
    <form class="full" NAME="lancamento" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} >
        <input name=mod           type=hidden value="{$mod}">   
        <input name=form          type=hidden value="{$form}">   
        <input name=id            type=hidden value="">
        <input name=letra         type=hidden value={$letra}>
        <input name=submenu       type=hidden value={$subMenu}>

        
        <div class="">
            <div class="page-title">
              <div class="title_left">
                <h3>Bancos</h3>
              </div>
            </div>

  					<div class="clearfix"></div>

            <div class="row">

              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Consulta</h2>
                    {include file="../bib/msg.tpl"}

                    <ul class="nav navbar-right panel_toolbox">
                      <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                      <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      </li>
                      <li><a class="close-link"><i class="fa fa-close"></i></a>
                      </li>
                    </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!--table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap table-condensed" cellspacing="0" width="100%">
                    <table id="datatable-buttons" class="table table-bordered jambo_table"-->
                      <div class="row">
                          <div class="col-sm-12">
                            <div class="card-box table-responsive">
                    <p class="text-muted font-13 m-b-30">
                      DataTables has most features enabled by default, so all you need to do to use it with your own tables is to call the construction function: <code>$().DataTable();</code>
                    </p>
                    <table id="datatable-responsive" class="table table-striped table-bordered">                    
                        <thead class="thead-dark">
                            <tr>
                                <th>Banco</th>
                                <th>Nome</th>
                                <th class=" no-link last" style="width: 120px;">Manutenção</th>
                            </tr>
                        </thead>

                        <tbody>

                            {section name=i loop=$lanc}
                                {assign var="total" value=$total+1}
                                <tr class="even pointer">
                                    <td> {$lanc[i].BANCO} </td>
                                    <td> {$lanc[i].NOME} </td>
                                    <td class=" last">
                                        <button type="button"  title='Editar' class="btn btn-outline-primary btn-round btn-sm" onclick="javascript:submitAlterar('{$lanc[i].BANCO}');"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></button>
                                        <button type="button"  title='Excluir' class="btn btn-outline-danger btn-round btn-sm" onclick="javascript:submitExcluir('{$lanc[i].BANCO}');"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button>
                                    </td>
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

    <!-- jQuery -->
    <script src="{$bootstrap}/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
   <script src="{$bootstrap}/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="{$bootstrap}/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="{$bootstrap}/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="{$bootstrap}/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="{$bootstrap}/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{$bootstrap}/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="{$bootstrap}/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{$bootstrap}/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="{$bootstrap}/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{$bootstrap}/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{$bootstrap}/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{$bootstrap}/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="{$bootstrap}/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="{$bootstrap}/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="{$bootstrap}/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="{$bootstrap}/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="{$bootstrap}/jszip/dist/jszip.min.js"></script>
    <script src="{$bootstrap}/pdfmake/build/pdfmake.min.js"></script>
    <script src="{$bootstrap}/pdfmake/build/vfs_fonts.js"></script>
    <!-- Datatables -->

    <!-- Custom Theme Scripts -->
    <script src="js/custom.min.js"></script>
    
    <!-- /Datatables -->
