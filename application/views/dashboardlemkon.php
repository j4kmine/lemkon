<?php 
function pembersih($str)
{
		$pjgStr = strlen($str)-1;
		$str = substr($str, 0, $pjgStr);
		return $str;
}

function getEmbel2($value){
    $value = number_format($value);
    $temp = explode(",", $value);
    $embel2 = "";
    switch (count($temp)){
        case 2:
            $embel2=" Ribu";            
            break;
        case 3:
            $embel2=" Juta";
            break;
        case 4:
            $embel2=" Milyar";
            break;
        case 5:
            $embel2=" Trilyun";
            break;
        default:
            $embel2=" ";
            break;
    }
    $result = (count($temp)>1)?$temp[0].",".$temp[1].$embel2:$value;
    return $result;
}
//---------------------------------------------investasi--------------------------------------//
$labelLK_investasi="";
$modalAwal = "";
$investasi_pegawai = "";
$investasi_sarana = "";
foreach($investasi as $row){
    $labelLK_investasi .= "{label:'".$row->nama_lk."'},";
    $modalAwal .="{value:'".$row->modal_awal."'},";
    $investasi_pegawai .="{value:'".$row->investasi_pegawai."'},";
    $investasi_sarana .="{value:'".$row->investasi_sarana."'},";
}
$labelLK_investasi = pembersih($labelLK_investasi);
$modalAwal = pembersih($modalAwal);
$investasi_pegawai = pembersih($investasi_pegawai);
$investasi_sarana = pembersih($investasi_sarana);
//---------------------------------------------end of investasi--------------------------------------//
//---------------------------------------------Annual investasi--------------------------------------//
$labelAnnualLK_investasi="";
$valueAnnualPegawai="";
$valueAnnualSarana="";
$valueAnnualModalAwal="";
$lkAnnual="";
$modalAwalAnnual="";
foreach($annualInvestasi as $row2){
    $labelAnnualLK_investasi .= "{label:'".$row2->tahun."'},";
    $valueAnnualPegawai .="{value:'".$row2->investasi_pegawai."'},";
    $valueAnnualSarana .="{value:'".$row2->investasi_sarana."'},";
    $valueAnnualModalAwal.="{value:'".$row2->modal_awal."'},";
    $lkAnnual = $row2->nama_lk;
    $modalAwalAnnual = $row2->modal_awal;
    
}
$modalAwalAnnual = getEmbel2($modalAwalAnnual);
$labelAnnualLK_investasi = pembersih($labelAnnualLK_investasi);
$valueAnnualPegawai = pembersih($valueAnnualPegawai);
$valueAnnualModalAwal = pembersih($valueAnnualModalAwal);
$valueAnnualSarana = pembersih($valueAnnualSarana);
//---------------------------------------------end of investasi--------------------------------------//
//---------------------------------------------sebaran satwa--------------------------------------//
$valueSebaranSatwa="";
$totalSatwa=0;
foreach($sebaranSatwa as $row){
    $valueSebaranSatwa .= "{ 'id': '".$row->id_map."', 'value': '".$row->jml."'},";    
    $totalSatwa += $row->jml;
  }
  $valueSebaranSatwa = pembersih($valueSebaranSatwa);
//---------------------------------------------end of sebaran satwa--------------------------------------//
//-----------------------------------------------kelahiran kematian chart 1---------------------------------------//
$labelLK="";
$valueLahir="";
$valueMati="";
foreach($lahirMatiChart1 as $row){
  $labelLK.= "{label:'$row->nama_lk'},";
  $valueLahir.= "{value:'$row->kelahiran'},";
  $valueMati.= "{value:'$row->kematian'},";
}
$labelLK = pembersih($labelLK);
$valueLahir = pembersih($valueLahir);
$valueMati = pembersih($valueMati);
//--------------------------------------------end of kelahiran kematian chart 1------------------------------------//
?>

<div class="row">
    <div class="col-xs-6" id="divInvest">
        <div id="chart-divInvest">Grafik Rekapitulasi investasi</div>
    </div>
    <div class="col-xs-6" id="divAnnualInvest">
            <select class="chosen-select form-control" id="ddLemkon" data-placeholder="Pilih LK">	
                    <option value=""></option>
                    
                    <?php
                    foreach($investasi as $row){
                      echo "<option value='$row->informasi_lk_umum_kode_lk'>$row->nama_lk</option>";
                    }
                    ?>
            </select>
        <div id="chart-divAnnualInvest">Grafik Rekapitulasi investasi Tahunan</div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12" id="divSebaranSatwa">
    <select class="chosen-select form-control" id="ddSebaranSatwa" data-placeholder="Pilih Satwa" multiple="multiple">	
                    <option value=""></option>
                    <option value="all"> Semua</option>
                    
                    <?php
                    foreach($daftarSatwa as $row){
                      echo "<option value='$row->master_satwa_nama_latin'>$row->jenis_satwa / $row->master_satwa_nama_latin</option>";
                    }
                    ?>
            </select>
        <div id="chart-satwaMap">Grafik sebaran satwa by Province</div>
    </div>
</div>

<div class="row">
  <div class="col-xs-6" id="divLahirMati">
            <select class="chosen-select form-control" id="ddSatwaLahirMati" data-placeholder="Pilih Satwa">	
                    <option value=""></option>
                    <!--option value="all"> Semua</option-->
                    
                    <?php
                    foreach($daftarSatwaLahirMati as $row){
                      echo "<option value='$row->master_satwa_nama_latin'>$row->jenis_satwa / $row->master_satwa_nama_latin</option>";
                    }
                    ?>
            </select>
        <div id="chart-divLahirMati">Grafik Lahir Mati Satwa</div>
    </div>
</div>

<div id="modalDiv" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header no-padding">
				<div class="table-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                <span class="white">&times;</span>
                                        </button>
                                         _ 
                                </div>
			</div>

			<div class="modal-body no-padding">
                            <div id="isi-modal"></div>
			</div>

			<div class="modal-footer no-margin-top">
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<script type="text/javascript" src="<?=base_url()?>assets/chart/js/fusioncharts.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/chart/js/themes/fusioncharts.theme.fusion.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/chart/js/themes/fusioncharts.theme.ocean.js"></script>

<script type="text/javascript">
            
	FusionCharts.ready(function() {

    function mapfun(urlTo, arData, divExistChart, idChart, chartType, divContainer, bukaModal, typeData, ddl, urlEntity){

  $.ajax({
    type: 'POST',
    url:  "<?=base_url()?>"+urlTo,
    //contentType: "application/json; charset=utf-8",                
    dataType: typeData, 
    data: arData,                
    beforeSend: function(){			                	
            $("#"+divContainer).LoadingOverlay("show", {
                                image       : "",
                                fontawesome : "fa fa-cog fa-spin"});
            $("#"+divExistChart).fadeOut("fast");
                    },
    success: function (hasil) {	
  if(idChart!=null){
    var removeChart = FusionCharts(idChart);
    removeChart.dispose();
    var dataset = hasil.data;    
    var varChart= hasil.chart; 
    var color = hasil.colorrange;
      var dataSource = {
        "chart": varChart,
        "colorrange":color,
        "data": dataset                        
      };
      //FusionCharts("chart-RekapTahapanLP").dispose();
      var chartRekapTahapanLP = new FusionCharts({
        type: chartType,
        id: idChart,
        renderAt: divExistChart,
        width: "100%",
        height: "400",
        dataFormat: "json",
        "events": {            
            "entityClick": function(evt, data) {
                //alert("You have clicked on " + data.label + ". id= "+data.id);
            var arData = { 
                key : data.id,
                label : data.label,   
                latin : $('#'+ddl).val()
            };
            var urlTo = urlEntity;
            var idChart = null;
            var divExistChart = null;
            var chartType = null;
            //var chartType = "pie3d";
            //var divContainer = divContainer;
            funAjax(urlTo, arData, divExistChart, idChart, chartType, divContainer, true, "text");
            }
        },
        dataSource
      });
    //chartRekapTahapanLP.dispose();
    //var listenerRekapTahapanLP = getListeneRekapTahapanLP();
    //chartRekapTahapanLP.addEventListener("slicingStart", listenerRekapTahapanLP);
    chartRekapTahapanLP.render();
  }
    
    //chartRekapTahapanLP.render();
    if(bukaModal){
      $('#isi-modal').html(hasil);
      $('#modalDiv').modal('show');
    } 
    $("#"+divContainer).LoadingOverlay("hide", true);
    $("#"+divExistChart).fadeIn();
},
    error: function (result) {
      console.log(result);
    }
  });
    }

        function funAjax(urlTo, arData, divExistChart, idChart, chartType, divContainer, bukaModal, typeData){
    //var arData = { A1984 : 1, A9873 : 5, A1674 : 2, A8724 : 1, A3574 : 3, A1165 : 5 }
            $.ajax({
                type: 'POST',
                
                url:  "<?php echo site_url();?>/"+urlTo,
                //contentType: "application/json; charset=utf-8",
                
                dataType: typeData, 
                data: arData,                
                beforeSend: function(){			                	
                        $("#"+divContainer).LoadingOverlay("show", {
                                            image       : "",
                                            fontawesome : "fa fa-cog fa-spin"});
                        $("#"+divExistChart).fadeOut("fast");
                                },
                success: function (hasil) {	
                    console.log(hasil);
                  if(idChart!=null){
                    var removeChart = FusionCharts(idChart);
                    removeChart.dispose();
                    var dataset = hasil.data;    
                    var varChart= hasil.chart; 
                     /*var dataSource = {
                        "chart": 
                          varChart
                        ,
                        "data": 
                          dataset                        
                      };*/
                      var dataSource = hasil;
                      //FusionCharts("chart-RekapTahapanLP").dispose();
                     var chartRekapTahapanLP = new FusionCharts({
                        type: chartType,
                        id: idChart,
                        renderAt: divExistChart,
                        width: "100%",
                        height: "400",
                        dataFormat: "json",
                        dataSource
                    });
                    //chartRekapTahapanLP.dispose();
                    //var listenerRekapTahapanLP = getListeneRekapTahapanLP();
                    //chartRekapTahapanLP.addEventListener("slicingStart", listenerRekapTahapanLP);
                    chartRekapTahapanLP.render();
                  }
                    
                    //chartRekapTahapanLP.render();
                    if(bukaModal){
                      $('#isi-modal').html(hasil);
                      $('#modalDiv').modal('show');
                    } 
                    $("#"+divContainer).LoadingOverlay("hide", true);
                    $("#"+divExistChart).fadeIn();
                },
                error: function (result) {
                    console.log(result);
                }
            });
        }

        var dataSource = {
  chart: {
    caption: "Rekapitulasi Investasi Lembaga Konservasi",
    //subcaption: "By Countries",
    yaxisname: "Besaran Investasi",
    numvisibleplot: "12",
    labeldisplay: "auto",
    exportEnabled: "1",        
    showvalues: "1",    
    enablemultislicing: "1",
    decimalSeparator:',',
    thousandSeparator:'.',
    numberScaleValue: "1000,1000,1000",
    numberScaleUnit: "rb, jt, m",
    //rotatevalues: "1",
    theme: "fusion"
  },
  categories: [
    {
      category: [
        <?=$labelLK_investasi?>
      ]
    }
  ],
  dataset: [
    {
      seriesname: "Modal Awal",
      data: [
        <?=$modalAwal?>
      ]
    },
    {
      seriesname: "Investasi Pegawai",
      data: [
        <?=$investasi_pegawai?>
      ]
    },
    {
        seriesname: "Investasi Sarana",
        data: [
            <?=$investasi_sarana?>
        ] 
    }
  ]
};

var investasiChart = new FusionCharts({
    type: "scrollcolumn2d",
    renderAt: "chart-divInvest",
    width: "100%",
    height: "400",
    dataFormat: "json",
    dataSource
  }).render();
//------------------------------------end of investasi chart--------------------------------------
//------------------------------------end of Annual investasi chart--------------------------------------
dataSource = {
  chart: {
    caption: "Rekapitulasi Investasi Tahunan Lembaga Konservasi",
    yaxisname: "Besaran Investasi (Rupiah)",
    subcaption: "<?=$lkAnnual?> dengan modal awal <?=$modalAwalAnnual?>",
    showhovereffect: "1",    
    drawcrossline: "1",
    plottooltext: "<b>$dataValue</b> telah diinvestasikan untuk $seriesName",
    labeldisplay: "auto",
    exportEnabled: "1",        
    decimalSeparator:',',
    thousandSeparator:'.',
    numberScaleValue: "1000,1000,1000",
    numberScaleUnit: "rb, jt, m",
    theme: "fusion"
  },
  categories: [
    {
      category: [
        <?=$labelAnnualLK_investasi?>
      ]
    }
  ],
  dataset: [
    {
      seriesname: "Investasi Pegawai",
      data: [
        <?=$valueAnnualPegawai?>
      ]
    },
    {
      seriesname: "modal awal",
      data: [
        <?=$valueAnnualModalAwal?>
      ]
    },
    {
      seriesname: "Investasi sarana",
      data: [
        <?=$valueAnnualSarana?>
      ]
    }
    
  ]
};

var AnnualInvestasiChart = new FusionCharts({
    type: "msline",
    renderAt: "chart-divAnnualInvest",
    id:"annualInvestChartID",
    width: "100%",
    height: "400",
    dataFormat: "json",
    dataSource
  }).render();

  $("#ddSebaranSatwa").change(function(){
          var arData={
              lk : $("#ddSebaranSatwa").val(),
              lktext : $("#ddSebaranSatwa option:selected").text()
          }  
          var urlTo = "lemkon/getSebaranSatwa/";
          var urlEntity = "lemkon/getdetilProp/";
          var idChart = "satwaMapID";
          var divExistChart = "chart-satwaMap";
          var chartType = "maps/indonesia";
          //var chartType = "pie3d";
          var divContainer = "divSebaranSatwa";
          mapfun(urlTo, arData, divExistChart, idChart, chartType, divContainer, false, "json", "ddSebaranSatwa",urlEntity);
        });

  $("#ddLemkon").change(function(){
            //alert($("#ddUnitPel").val());
            var arData = {                 
                lk : $("#ddLemkon").val(),
                lktext : $("#ddLemkon option:selected").text()
            };
            var urlTo = "lemkon/getAnnualInvest/";
            var idChart = "annualInvestChartID";
            var divExistChart = "chart-divAnnualInvest";
            var chartType = "msline";
            //var chartType = "pie3d";
            var divContainer = "divAnnualInvest";
            funAjax(urlTo, arData, divExistChart, idChart, chartType, divContainer, false, "json");
        });

    $("#ddSatwaLahirMati").change(function(){
            var arData = {                 
                lk : $("#ddSatwaLahirMati").val(),
                lktext : $("#ddSatwaLahirMati option:selected").text()
            };
            var urlTo = "lemkon/getLahirMatiperSatwa/";
            var idChart = "lahirMatiChartID";
            var divExistChart = "chart-divLahirMati";
            var chartType = "overlappedcolumn2d";
            //var chartType = "pie3d";
            var divContainer = "divLahirMati";
            funAjax(urlTo, arData, divExistChart, idChart, chartType, divContainer, false, "json");
    });

        var p21Map = new FusionCharts({
        "type": "maps/indonesia",
        "renderAt": "chart-satwaMap",
        "id":"satwaMapID",
        "width": "100%",
        "height": "400",
        "dataFormat": "json",
        "events": {            
            "entityClick": function(evt, data) {
                //alert("You have clicked on " + data.label + ". id= "+data.id);
            
            var arData = { 
                key : data.id,
                label : data.label,   
                latin : $('#ddSebaranSatwa').val()
            };
            var urlTo = "lemkon/getdetilProp/";
            var idChart = null;
            var divExistChart = null;
            var chartType = null;
            //var chartType = "pie3d";
            var divContainer = "divSebaranSatwa";
            funAjax(urlTo, arData, divExistChart, idChart, chartType, divContainer, true, "text");
            }
        },
        "dataSource": {
            "chart": {
                "caption": "Sebaran Satwa dalam Lembaga Konservasi",
                "subcaption": "Terdapat <?=$totalSatwa?> Satwa Tersebar di Seluruh Indonesia",
                "entityFillHoverColor": "#cccccc",
                "numberScaleValue": "1,1000,1000",
                
                //"numberScaleUnit": "K,M,B",
                //"numberPrefix": "$",
                "exportEnabled": "1",        
                "showLabels": "1",
                "useSNameInLabels":"1",
                "includeValueInLabels":"1",
                "theme": "ocean"
            },
            "colorrange": {
                /*"minvalue": "1",
                "startlabel": "Low",
                "endlabel": "High",
                "code": "#6baa01",
                "gradient": "1",*/
                "color": [
                    {
                        //"maxvalue": "10",
                        "minvalue": "1",
                        "maxvalue": "20",
                        "displayvalue": "1 < 20",
                        "code": "#6daa01"
                    },
                    {
                        //"maxvalue": "20",
                        "minvalue": "21",
                        "maxvalue": "50",
                        "displayvalue": "21 - 50",
                        "code": "#bcb50f"
                    },
                    {
                        //"maxvalue": "20",
                        "minvalue": "51",
                        "maxvalue": "100",
                        "displayvalue": "51 - 100",
                        "code": "#dab914"
                    },
                    {
                        //"maxvalue": "20",
                        "minvalue": "101",
                        "maxvalue": "150",
                        "displayvalue": "101 - 150",
                        "code": "#f3a013;"
                    }                
                    ,
                    {
                        //"maxvalue": "20",
                        "minvalue": "151",
                        "maxvalue": "200",
                        "displayvalue": "151 - 200",
                        "code": "#ef890e"
                    },
                    {
                        //"maxvalue": "20",
                        "minvalue": "201",
                        "maxvalue": "250",
                        "displayvalue": "201 - 250",
                        "code": "#ea6e08"
                    },
                    {
                        //"maxvalue": "20",
                        "minvalue": "251",
                        "maxvalue": "300",
                        "displayvalue": "251 - 300",
                        "code": "#e55001"
                    }
                ]
            },
            "data": [ <?=$valueSebaranSatwa?>
                ]
        }
    });
    p21Map.render();

    dataSource = {
  chart: {
    caption: "Kelahiran vs Kematian Satwa",
    //subcaption: "Bilbus",
    yaxisname: "Jumlah Kejadian",
    showhovereffect: "1",
    drawcrossline: "1",
    labeldisplay: "auto",
    exportEnabled: "1",        
    decimalSeparator:',',
    thousandSeparator:'.',
    numberScaleValue: "1000,1000,1000",
    numberScaleUnit: "rb, jt, m",
    theme: "fusion",
    showvalues: "1"
  },
  categories: [
    {
      category: [
        <?=$labelLK?>
      ]
    }
  ],
  dataset: [
    {
      seriesname: "Kelahiran",
      data: [
        <?=$valueLahir?>
      ]
    },
    {
      seriesname: "Kematian",
      data: [
        <?=$valueMati?>
      ]
    }
  ]
};

var chartLahirMati = new FusionCharts({
    type: "overlappedcolumn2d",
    renderAt: "chart-divLahirMati",
    id: "lahirMatiChartID",
    width: "100%",
    height: "400",
    dataFormat: "json",
    dataSource
  }).render();

});
</script>