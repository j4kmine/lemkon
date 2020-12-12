<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <!--<script src="<?php  base_url();?>/pslh/assets/html/js/chart-master/Chart.js"></script>
    <link href="<?php  base_url();?>/pslh/assets/html/css/bootstrap.css" rel="stylesheet">    
    -->
    <link href="<?php  base_url();?>/pslh/assets/html/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?php  base_url();?>/pslh/assets/html/css/zabuto_calendar.css" />
    <link rel="stylesheet" type="text/css" href="<?php  base_url();?>/pslh/assets/html/js/gritter/css/jquery.gritter.css" />
    <link rel="stylesheet" type="text/css" href="<?php  base_url();?>/pslh/assets/html/lineicons/style.css" />            
    <link href="<?php  base_url();?>/pslh/assets/html/css/style.css" rel="stylesheet" />
    <link href="<?php  base_url();?>/pslh/assets/html/css/style-responsive.css" rel="stylesheet" />
    

<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
</head>
<body>
<div>
		<?php echo $output; ?>
</div><br>
    <div id="dlFiles" style="margin-left: 3%; background-color: rgb(206, 206, 206);">
        <H3>File PDF:</H3>
        <div id="divUrl"></div>
    </div>
    
</body>
<script src="<?php  echo base_url("assets/html/js/jquery.blockUI.js");?>"></script> 
<script type="text/javascript">
    function listSengketaPengadilan(){
        var sumberSengketa = $(".search_SUMBER_SENGKETA").val();
        var penggugat = $(".search_PENGGUGAT").val();
        var tergugat = $(".search_TERGUGAT").val();
        var regional = $(".search_s2a982776").val();
        var sektor = $(".search_s3c455e00").val();
        var kota = $(".search_s1006e797").val();
        var propinsi = $(".search_sb87a63c3").val();
        var tahapan = $(".search_s5434c8b0").val();
        var tahunKegiatan = $(".search_tanggal_kegiatan").val();
        var key ="sumberSengketa="+sumberSengketa+"&sengketa_1="+penggugat+"&sengketa_2="+tergugat+"&regional="+regional
                +"&sektor="+sektor+"&kota="+kota+"&propinsi="+propinsi+"&tahapan="+tahapan+"&tahunKegiatan="+tahunKegiatan;
        $.ajax({
                        url: "<?php echo site_url('master_control/printListSengketaPengadilan');?>",
                        type:'POST',
                        data: key,
                        success:function(data){
                            /*$("#searchResult").html(data);
                            $("#searchResult").show("slow");*/
                            
                            var url = data.split("#");
                            var htmls='<div id="dlFiles" style="margin-left: 3%; background-color: rgb(206, 206, 206);">'+
                                        '<H3>File PDF:</H3>'+
                                        '<div id="divUrl">';
                            for(var i=0;i<url.length;i++){
                                var files = url[i].split("/");
                                htmls += '<a href="'+url[i]+'" target="_blank">'+files[files.length - 1]+'</a></br></br>';
                            }
                            htmls += '</div>'+
                                        '</div>';
                            $(".clear").html(htmls);
                            $(".clear").show("slow");
                            //window.open(data, '_blank');
                            //console.log(data);
                        },                   
                        beforeSend: function(){
                            //$(".site-footer").css("position","inherit");
                            /*$("#searchResult").hide("slow");
                            $("#searchResult").html("");*/  
                            $(".clear").hide("slow");
                            $.blockUI({});
                        },
                                                               
                        complete: function(){
                            setTimeout($.unblockUI(), 500);
                                                                                
                        }                                    
        });
    }
    
    function listSengketa(){
        var sumberSengketa = $(".search_SUMBER_SENGKETA").val();
        var sengketa_1 = $(".search_SENGKETA_1").val();
        var sengketa_2 = $(".search_SENGKETA_2").val();
        var regional = $(".search_s2a982776").val();
        var sektor = $(".search_s3c455e00").val();
        var kota = $(".search_s1006e797").val();
        var propinsi = $(".search_sb87a63c3").val();
        var tahapan = $(".search_s5434c8b0").val();
        var tahunKegiatan = $(".search_TAHUN_KEGIATAN").val();
        var key ="sumberSengketa="+sumberSengketa+"&sengketa_1="+sengketa_1+"&sengketa_2="+sengketa_2+"&regional="+regional
                +"&sektor="+sektor+"&kota="+kota+"&propinsi="+propinsi+"&tahapan="+tahapan+"&tahunKegiatan="+tahunKegiatan;
        $.ajax({
                        url: "<?php echo site_url('master_control/printListSengketa');?>",
                        type:'POST',
                        data: key,
                        success:function(data){
                            /*$("#searchResult").html(data);
                            $("#searchResult").show("slow");*/
                            
                            var url = data.split("#");
                            var htmls='<div id="dlFiles" style="margin-left: 3%; background-color: rgb(206, 206, 206);">'+
                                        '<H3>File PDF:</H3>'+
                                        '<div id="divUrl">';
                            for(var i=0;i<url.length;i++){
                                var files = url[i].split("/");
                                htmls += '<a href="'+url[i]+'" target="_blank">'+files[files.length - 1]+'</a></br></br>';
                            }
                            htmls += '</div>'+
                                        '</div>';
                            $(".clear").html(htmls);
                            $(".clear").show("slow");
                            //window.open(data, '_blank');
                            //console.log(data);
                        },                   
                        beforeSend: function(){
                            //$(".site-footer").css("position","inherit");
                            /*$("#searchResult").hide("slow");
                            $("#searchResult").html("");*/  
                            $(".clear").hide("slow");
                            $.blockUI({});
                        },
                                                               
                        complete: function(){
                            setTimeout($.unblockUI(), 500);
                                                                                
                        }                                    
        });
    }
    
    $(document).ready(function(){
        $(".ui-buttonset").append('<a title="View print view" id="ToolTables_groceryCrudTable_2" class="DTTT_button ui-button ui-state-default DTTT_button_print"><span>PDF</span></a>');
        $("#ToolTables_groceryCrudTable_0").css( "display", "none" );
        $("#ToolTables_groceryCrudTable_1").css( "display", "none" );
        
        $("#dlFiles").css( "display", "none" );
        $("#ToolTables_groceryCrudTable_2").click(function(){
            var url = window.location.href;
            var temp = url.split('/');            
            switch(temp[5]){
                case 'listSengketa':
                        listSengketa();
                break;  
                case 'listSengketaPengadilan':
                    listSengketaPengadilan();
                break;
                default:
                    alert("Maaf, fitur cetak PDF tidak tersedia untuk Menu ini. Terimakasih.");
                    break;
            }            
        });
    });
</script>
</html>