<style>
.x_panel{
  border-radius: 5px;
}
.right_col{
    padding: 5px;
}
.arquivo {
    display: none !important;
}
.file {
    margin-left: -3px;
    border-radius: 5px;
    line-height: 30px;
    height: 32.5px;
    border: 1px solid #A7A7A7;
    padding: 5px;
    font-size: 15px;
    vertical-align: middle;
    width: 60%;
}
.btnSelecionar {
    border-radius: 3px;
    box-sizing: border-box;
    border: none;
    padding: 2px 10px;
    background-color: #4493c7;
    color: #FFF;
    height: 32px;
    font-size: 15px;
    vertical-align: middle;
}
.btnSelecionar:hover{
    background-color: #295c7e;
}
.imgModal{
    max-width: 80em;
}
#modal-body {
    position: absolute;
    z-index: 2;
    top: 0;
    left: 0;
    filter: alpha(opacity=0);
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
    opacity: 0;
    background-color: transparent;
    color: transparent;
}
.btnManutencao {
    margin-top: 5px;
    text-align: center;
    transform: translateX(-4.5%);
}

.image-container {
    width: 200px; /* Largura desejada */
    height: 150px; /* Altura desejada */
    overflow: hidden;
}

.tagImg {
    border-radius: 5px !important;
    width: 100%;
    height: 100%;
    object-fit: contain; /* Para manter a proporção da imagem e cortar o excesso */
}
.arquivo{
    width: 120%;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"> </script>
<script type="text/javascript" src="{$pathJs}/est/s_produto.js"> </script>

<!-- page content -->
<div class="right_col" role="main">
    <form class="full" NAME="lancamentoajax" METHOD="POST" class="form-horizontal form-label-left" novalidate ACTION={$SCRIPT_NAME} enctype="multipart/form-data">
        <input name=mod type=hidden value="est">
        <input name=form type=hidden value="inventario">
        <input name=id type=hidden value={$id}>
        <input name=idimg type=hidden value=''>
        <input name=destaque type=hidden value=''>
        <input name=letra type=hidden value={$letra}>
        <input name=submenu type=hidden value={$subMenu}>
        <input name=descricaoProduto type=hidden value={$descricaoProduto}>

        {include file="../bib/msg.tpl"}

        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>Imagens - {$descricaoProduto} </h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><button type="button" class="btn btn-danger" onClick="javascript:submitVoltarImagem('');">
                                    <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                                    <span> Voltar</span>
                                </button>
                            </li>
                        </ul>
                        <div class="clearfix"></div>
                    </div>

                    <div class="x_content">
                        <div class="col-md-10 col-sm-10 col-xs-12">
                            <input type="button" class="btnSelecionar" value="SELECIONAR">
                            <input type="file" name="upload" id="upload" class="arquivo" accept="image/*, application/pdf">
                            <input type="text" name="file" id="file" class="file" placeholder="Arquivo" readonly="readonly">
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <button type="button" class="btn btn-dark pull-right" 
                                {if $totalImg > 5} 
                                    disabled 
                                {/if}
                                onClick="javascript:submitSalvarImagem({$id});">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span>
                                <span> Salvar Imagem</span>
                            </button>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="container">
                        <div class="x_panel">
                            <div class="row" style="margin-top: 15px;">
                                {section name=i loop=$lanc}
                                    {* <div class="col-md-3 col-sm-6 col-xs-12">
                                        <img src="images/doc/est/{$lanc[i].ID_DOC}/{$lanc[i].ID}.jpg" class="img-rounded abrirModal tagImg"/>

                                        <div class="row btnManutencao">
                                            <button type="button" 
                                                {if $lanc[i].DESTAQUE eq 'N'} class="btn-xs btn-warning" {else} class="btn-xs btn-success" {/if}
                                                onClick="javascript:submitDestaqueImagem({$lanc[i].ID}, '{$lanc[i].DESTAQUE}');">
                                                <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
                                                <span> Destaque</span>
                                            </button>

                                            <button type="button" class="btn-xs btn-danger" onClick="javascript:submitExcluirImagem({$lanc[i].ID_DOC}, {$lanc[i].ID});">
                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                <span> Apagar </span>
                                            </button>
                                        </div>
                                    </div> *}

                                    <div class="col-md-3 col-sm-6 col-xs-12">
                                        <div class="image-container">
                                            <img src="images/doc/est/{$lanc[i].ID_DOC}/{$lanc[i].ID}.jpg" class="img-rounded abrirModal tagImg" />
                                        </div>

                                        <div class="row btnManutencao">
                                            <button type="button" 
                                                {if $lanc[i].DESTAQUE eq 'N'} class="btn-xs btn-warning" {else} class="btn-xs btn-success" {/if}
                                                onClick="javascript:submitDestaqueImagem({$lanc[i].ID}, '{$lanc[i].DESTAQUE}');">
                                                <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
                                                <span> Destaque</span>
                                            </button>

                                            <button type="button" class="btn-xs btn-danger" onClick="javascript:submitExcluirImagem({$lanc[i].ID_DOC}, {$lanc[i].ID});">
                                                <span class="glyphicon glyphicon-remove-circle" aria-hidden="true"></span>
                                                <span> Apagar </span>
                                            </button>
                                        </div>
                                    </div>


                                    {assign var="total" value=($total+1)}
                                {/section}
                            </div>
                        </div>
                    </div>

                  </div> <!-- div class="x_content" = inicio tabela -->
                </div> <!-- div class="x_panel" = painel principal-->
            </div> <!-- div class="col-md-12 col-sm-12 col-xs-12 "-->
        </div> <!-- div class="row "-->
    </form>
</div> <!--  class="right_col" -->
    
{include file="template/database.inc"}
<!-- /Datatables -->

<!-- MODAL MOSTRA IMAGEM -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" style="width:100em !important;" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Exemplo</h4>
      </div>
      <div class="modal-body text-center">
        <img class="imgModal" src="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Sair</button>
      </div>
    </div>
  </div>
</div>




<script>
var arqName = document.querySelector("#upload-file-info");
var input = document.querySelector("#user_image");
var imgWidth = document.querySelector("#imgLarg");
var imgHeight = document.querySelector("#imgAlt");
input.addEventListener("change", function() {
    arqName.innerText = "Nenhuma imagem selecionada.";
    if (input.files.length > 0) {
        arqName.innerText = input.files[0].name;
    }
})
</script>

<script>
$(".abrirModal").click(function() {
    debugger
    var thisValue = $(this);
    var url = $(this).attr("src");
    $("#myModal img").attr("src", url);
    $("#myModal").modal("show");
});
</script>

<script>
$('.btnSelecionar').on('click', function() {
    $('.arquivo').trigger('click');
});

$('.arquivo').on('change', function() {
    var fileName = $(this)[0].files[0].name;
    $('#file').val(fileName);
});
</script>