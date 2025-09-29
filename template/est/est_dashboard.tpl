<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>

  <!-- page content -->
    <div class="right_col" role="main"> 
      <div class="row">
        <div class="col-md-9 col-sm-9 ">
          <canvas id="bar-chart" width="200" height="90"></canvas>
        </div>

        <div class="col-md-3 col-sm-3 ">
        <br>
        <br>
              <div class="x_panel tile fixed_height_320">
                <div class="x_title">
                  <h2 align="center">Gerencial</h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <table>
                    <tr>
                      <td width=70%>Valor de Vendas</td>
                      <td align="right" width=30%>{$vendas}</td>
                    </tr>
                    <tr>
                      <td width=70%>Lucro Bruto</td>
                      <td align="right" width=30%>{$lucrobruto}</td>
                    </tr>
                    <tr>
                      <td width=70%>Lucro Líquido</td>
                      <td align="right" width=30%>{$lucroliquido}</td>
                    </tr>
                    <tr>
                      <td width=70%>Ponto de Equi</td>
                      <td align="right" width=30%>{$pontoequilibrio}</td>
                    </tr>
                    <tr>
                      <td width=70%>Custo de Vendas</td>
                      <td align="right" width=30%>{$custovenda}</td>
                    </tr>
                  </table>                  
                </div>
              </div>
            </div>






        
                           
      </div> 
      <div class="row">
        <div class="col-md-12 col-sm-12 ">
          <table  class="table table-bordered jambo_table">
            <thead>
                <tr class="headings">
                    <th style="width: 700px;">Vendedor</th>
                    <th style="width: 700px;">Meta</th>
                    <th style="width: 700px;">ICM Vendas</th>
                    <th style="width: 700px;">Valor Vendido</th>
                    <th style="width: 700px;">Meta Diario</th>
                    <th style="width: 700px;">Custo Medio</th>
                </tr>
            </thead>
            <tbody>
              {section name=i loop=$lanc}
                <tr class="even pointer">
                    <td> {$lanc[i].VENDEDOR} </td>
                    <td> {$lanc[i].METAS} </td>
                    <td> {$lanc[i].ICM_VENDAS} </td>
                    <td> {$lanc[i].VALOR_VENDIDO} </td>
                    <td> {$lanc[i].META_DIARIA} </td>   
                    <td> {$lanc[i].CUSTOMEDIO} </td>                                    
                </tr>
              {/section} 
            </tbody>
          </table>
        </div>             
      </div>
      <div class="row">
        <div class="col-md-12 col-sm-12 ">
          <table  class="table table-bordered jambo_table">
            <thead>
                <tr class="headings">
                    <th style="width: 800px;">Vendedor</th>
                    <th style="width: 800px;">Meta Margem Liquida (15%)</th>
                    <th style="width: 800px;">ICM</th>
                    <th style="width: 800px;">Margem Liquida</th>
                </tr>
            </thead>
            <tbody>
              {section name=i loop=$lanc}
                <tr class="even pointer">
                    <td> {$lanc[i].VENDEDOR} </td>
                    <td> {$lanc[i].METAMARGEMLIQUIDA} </td>
                    <td> {$lanc[i].ICM} </td>
                    <td> {$lanc[i].MARGEMLIQUIDA} </td>                                    
                </tr>
              {/section} 
            </tbody>
          </table>
        </div>             
      </div>
    </div>

<script>

new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: [{$labels}],
      datasets: [
        {
          label: [{$label}],
          backgroundColor: [{$bckgroundColor}],
          data: [{$dados}]
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Meta do mês'
      },
      scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        min: 0
                    }
                }]                
            },
    }
});


      
</script>
{include file="template/database.inc"}