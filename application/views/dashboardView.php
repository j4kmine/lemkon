<?php
	$i=0;
	$label="";
	$data['baik']="";
	$data['hil']="";
	$data['rr']="";
	$data['rb']="";
	$data[0]="";
	$seriesName[$i]="";
	$dataset="";
	$counter=0;
	
	function pembersih($str){
		$pjgStr = strlen($str)-1;
		$str = substr($str, 0, $pjgStr);
		return $str;
	}
	
			foreach ($kondisi as $row)
			{
				//echo $row->Provinsi.' '.$row->kondisi.' '.$row->jml_kondisi.'</br>';
				$label .=  "{'label': "."'".$row->Provinsi."'},";
				$data['baik'] .= "{'value': '".$row->kondisi_baik."'},";
				$data['hil'] .= "{'value': '".$row->kondisi_hilang."'},";
				$data['rb'] .= "{'value': '".$row->kondisi_rb."'},";
				$data['rr'] .= "{'value': '".$row->kondisi_rr."'},";				
			}
			$label = pembersih($label);
			$data['baik'] = pembersih($data['baik']);
			$data['hil'] = pembersih($data['hil']);
			$data['rb'] = pembersih($data['rb']);
			$data['rr'] = pembersih($data['rr']);
			
//----------------------------------------------------------------------------------------------			
			$labelBukuPas = "";
			$pas_berlaku="";
			$pas_kadaluarsa="";
			foreach($buku_pas as $row){
				$labelBukuPas .= "{'label': "."'".$row->Provinsi."'},";
				$pas_berlaku .= "{'value': '".$row->PAS_berlaku."'},";
				$pas_kadaluarsa .= "{'value': '".$row->PAS_kadaluarsa."'},";
			}
			
			$labelBukuPas = pembersih($labelBukuPas);
			$pas_berlaku = pembersih($pas_berlaku);
			$pas_kadaluarsa = pembersih($pas_kadaluarsa);
//-----------------------------------------------------------------------------------------------	
			$senpiMap = "";
			foreach($senpi as $row){
				$senpiMap .= "{'id': '".$row->ID_MAP."','value': '".$row->jml_senpi."'},";
			}
			$senpiMap = pembersih($senpiMap);				
?>
<div class="row">
	<div class="col-xs-6" id="divChart1">
		<div id="chart-container">Grafik kondisi Senpi</div>
	</div>
	<div class="col-xs-6" id="divChart2">
		<div id="chart-bukuPas">Grafik masa berlaku Buku Pas</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12" id="divChart3">
		<div id="chart-sebaranSenpi">Grafik Persebaran Senpi</div>
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
													Kondisi Senpi
												</div>
			</div>

			<div class="modal-body no-padding">
				<div id="chart-kondisiSenpi" >Grafik Persebaran Senpi</div>
			</div>

			<div class="modal-footer no-margin-top">
				
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<script type="text/javascript" src="<?=base_url()?>assets/chart/js/fusioncharts.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/chart/js/themes/fusioncharts.theme.fusion.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/chart/js/themes/fusioncharts.theme.ocean.js"></script>
<!--<script type="text/javascript" src="<?=base_url()?>assets/chart/js/fusioncharts.maps.js"></script>-->
<script type="text/javascript">
	FusionCharts.ready(function() {
		
		
    var kondisiChart = new FusionCharts({
        type: 'scrollstackedcolumn2d',//'mscolumn3d', // The chart type
        renderAt: 'chart-container', // Container of the chart
        width: '100%', // Width of the chart
        height: '400', // Height of the chart
        dataFormat: 'json', // Data type
        dataSource: {
    "chart": {
        "caption": "Kondisi Senpi KLHK di Indonesia",
        //"subCaption": "Sales by quarter",
        "xAxisName": "Provinsi",
        "yAxisName": "Kondisi Senpi",
        //"numberPrefix": "$",
        "exportEnabled": "1",        
        "theme": "fusion"
    },
    "categories": [
        {
            "category": [           
            <?=$label?>    
            ]
        }
    ],
    "dataset": [
        {
            "seriesname": "Baik",
            "data": [
                <?=$data['baik']?>
            ]
        },
        {
            "seriesname": "Rusak Berat",
            "data": [
                <?=$data['rb']?>
            ]
        },
        {
            "seriesname": "Rusak Ringan",
            "data": [
                <?=$data['rr']?>
            ]
        },
        {
            "seriesname": "Hilang",
            "data": [
                <?=$data['hil']?>
            ]
        }
    ]
}
    });
     kondisiChart.render();
    var bukuPasChart = new FusionCharts({
        type: 'overlappedcolumn2d', // The chart type
        renderAt: 'chart-bukuPas', // Container of the chart
        width: '100%',//'700', // Width of the chart
        height: '400', // Height of the chart
        dataFormat: 'json', // Data type
        dataSource: {
    "chart": {
        "caption": "Masa Berlaku Buku Pas",
        //"subCaption": "Sales by quarter",
        "xAxisName": "Provinsi",
        "yAxisName": "Jumlah Buku Pas",
        //"numberPrefix": "$",
        "exportEnabled": "1",        
        "theme": "ocean"
    },
    "categories": [
        {
            "category": [           
            <?=$labelBukuPas?>    
            ]
        }
    ],
    "dataset": [
        {
            "seriesname": "Buku Pas Berlaku",
            "data": [
                <?=$pas_berlaku?>
            ]
        },
        {
            "seriesname": "Buku Pas Kadaluarsa",
            "data": [
                <?=$pas_kadaluarsa?>
            ]
        }
    ]
}
    });
     bukuPasChart.render();
    var chartSebaran = new FusionCharts({
        "type":"maps/indonesia",
        "renderAt": "chart-sebaranSenpi",
        "width": "100%",
        "height": "400",
        "dataFormat": "json",
        "useSNameInLabels":"1",
        "includeValueInLabels":"1",
        "events": {            
            "entityClick": function(evt, map) {
                //alert("You have clicked on " + data.label + ". id= "+data.id);
                //$(".propinsi-list").hide("fast");
                //$("#prop-"+data.id).show("slow");
                //$('#modalDiv').modal('show');
                $.ajax({
                    type: "POST",
                    //url: "<?php echo site_url('index.php/main/insertInstansi');?>/", 
                    url: "<?php echo site_url('dashboard/showKondisiSenpi');?>/", 
                    data: {idMap: map.id},
                    //contentType: "application/json; charset=utf-8",
                    dataType: "json",  
                    cache:false,
                    beforeSend: function(){			                	
                                   $("#chart-kondisiSenpi").hide("fast");
                           $("#chart-sebaranSenpi").LoadingOverlay("show", {
                                               image       : "",
                                               fontawesome : "fa fa-cog fa-spin"});								
                                   },
                    success: 
                         function(results){

                           var category = results.categories.category
                           var dataset = results.dataset;


                                           const dataSource = {
                                             "chart": {
                                               "caption": "Kondisi SENPI",
                                               "exportEnabled": "1",

                                               //"subcaption": "2012-2016",
                                               "xaxisname": "instansi",
                                               "yaxisname": "Jumlah Senpi",
                                               "formatnumberscale": "1",
                                               "plottooltext": "<b>$dataValue</b> senjata $label berstatus <b>$seriesName</b>",
                                               "theme": "fusion",
                                               "drawcrossline": "1"
                                             },
                                             "categories": [
                                               {
                                                 "category": category
                                               }
                                             ],
                                             "dataset": dataset
                                           };
                                      var myChart = new FusionCharts({
                                         type: "mscolumn2d",
                                         renderAt: "chart-kondisiSenpi",
                                         width: "100%",
                                         height: "400",
                                         dataFormat: "json",
                                         dataSource
                                      }).render();

                                           $("#chart-sebaranSenpi").LoadingOverlay("hide", true);
                                           $("#chart-kondisiSenpi").show("fast");
                                           $('#modalDiv').modal('show');
                         }
				          });
            }
        },
        "dataSource": {
            "chart": {
                "caption": "Sebaran Jumlah SENPI",
                //"subcaption": "Tahun 2017",
                "entityFillHoverColor": "#cccccc",
                "numberScaleValue": "1,10,10",
                "exportEnabled": "1",
                //"numberScaleUnit": "K,M,B",
                //"numberPrefix": "$",
                "showLabels": "1",
                "useSNameInLabels":"1",
                "includeValueInLabels":"1",
                "showLabels": "1",
                "theme": "fusion"
            },
            "colorrange": {
                /*"minvalue": "1",
                "startlabel": "Low",
                "endlabel": "High",
                "code": "#6baa01",
                "gradient": "1",*/
                "color": [
                //     {
                //"maxvalue": "20",
                //"displayvalue": "Average",
                //"code": "#f8bd19"
            //},
            {
                "maxvalue": "100",
                "code": "#6baa01"
            }
                ]
            },
            "data": [
                <?=$senpiMap?>   
            ]
        }
    });
    chartSebaran.render();
});
</script>