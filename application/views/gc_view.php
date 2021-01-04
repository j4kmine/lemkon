<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

 <?php foreach($js_files as $file): ?>
        <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
    <script src="<?=base_url("assets/html/js/cleave.js")?>"></script>

    <script type="text/javascript">
			jQuery(function($) {
                   
                $('select').on('change', function() {
                    if(this.value  == "TITIP"){
                        $("div#nomer_sk_field_box").hide();
                        $("div#pdf_upload_sk_field_box").hide();
                    }else if(this.value == "KOLEK"){
                        $("div#nomer_sk_field_box").show();
                        $("div#pdf_upload_sk_field_box").show();
                    }
                });                
            });
            $( document ).ready(function() {
                //$("#field-tersangka").val("unkno");
                //$("#field-tersangka").trigger('liszt:updated');

                if($('#field-tahapan_id_tahapan').val()!="P21"){
                    $("#field_provinsi_p21_chzn").css("display","none");
                    $("#kabupaten_id_kab_input_box").css("display","none");
                    $("#kecamatan_id_kecamatan_input_box").css("display","none");
                    $("#kelurahan_id_lurah_input_box").css("display","none");
                    $("#field-nama_jalan").css("display","none"); 
                }
                /*$("#field-id_histori_kasus").val(
                    $("#field-provinsi_p21").val()+"-"+
                    $("#field-kabupaten_id_kab").val()+"-"+
                    $("#field-kecamatan_id").val()+"-"+
                    $("#field-kelurahan_id_lurah").val()+"-"+
                    $("#field-nama_jalan").val()
                    );*/
                $('#field-tahapan_id_tahapan').on('change', function(evt, params) {                    
                    isihk(evt, params);
                    
                    if($('#field-tahapan_id_tahapan').val()=="P21"){
                        $("#field_provinsi_p21_chzn").css("display","inline-block");
                        $("#kabupaten_id_kab_input_box").css("display","inline-block");
                        $("#kecamatan_id_kecamatan_input_box").css("display","inline-block");
                        $("#kelurahan_id_lurah_input_box").css("display","inline-block");
                        $("#field-nama_jalan").css("display","inline-block");                        
                    }
                    else{ 
                        $("#field_provinsi_p21_chzn").css("display","none");
                        $("#kabupaten_id_kab_input_box").css("display","none");
                        $("#kecamatan_id_kecamatan_input_box").css("display","none");
                        $("#kelurahan_id_lurah_input_box").css("display","none");
                        $("#field-nama_jalan").css("display","none"); 
                    }
                });

                $('#field-provinsi_id_prov').on('change', function(evt, params){
                    $("#kabupaten_id_kab_input_box").css("display","inline-block");
                        $("#kecamatan_id_kecamatan_input_box").css("display","inline-block");
                        $("#kelurahan_id_lurah_input_box").css("display","inline-block");
                        $("#field-nama_jalan").css("display","inline-block"); 
                });

                $("#field-nomor_perkara").change(function(evt, params){
                    isihk(evt, params);
                });

                $("#field-tipologi_kasus").change(function(evt, params){
                    isiTersangka("#field-tipologi_kasus");                    
                });

                $("#field-tahapan_id_tahapan").change(function(evt, params){
                    isiTersangka("#field-tgl_pelaksanaan");
                });

                $("#field-tgl_pelaksanaan").change(function(evt, params){
                    isiTersangka("#field-tgl_pelaksanaan");
                });

                $("#field-ketua_pelaksana").change(function(evt, params){
                    isiTersangka("#field-ketua_pelaksana");
                });

                $("#field-anggota_pelaksana").change(function(evt, params){
                    isiTersangka("#field-anggota_pelaksana");
                });

                $("#field-unit_pelaksana").change(function(evt, params){
                    isiTersangka("#field-unit_pelaksana");
                });

                $("#field-deskripsi_hasil_pelaksanaan").change(function(evt, params){
                    isiTersangka("#field-deskripsi_hasil_pelaksanaan");
                });

                $("#field-anggota_pelaksana").change(function(evt, params){
                    isiTersangka("#field-anggota_pelaksana");
                });

                /*$("form").on('submit',function(event){                    
                        var valueTsk = $("#field-tersangka").val();
                        if(valueTsk==""){
                            $("#field-tersangka").val("unkno");
                            $("#field-tersangka").trigger('liszt:updated');
                            alert("Mvalue = "+$("#field-tersangka").val());
                            event.preventDefault();
                            event.stopImmediatePropagation()
                        }
                    
                });*/

                const patokan = $("#field-provinsi_p21").val()+"-"+
                    $('select[name="kabupaten_id_kab"]').val()+"-"+
                    $('select[name="kecamatan_id_kecamatan"]').val()+"-"+
                    $('select[name="kelurahan_id_lurah"]').val()+"-"+
                    $("#field-nama_jalan").val();

                function isihk(evt, params){
                    if(params != undefined){
                        var selectedValue = params.selected;
                        var res = $("#field-nomor_perkara").val()+"-"+selectedValue;
                        var temp = res.split("/");
                        var vari = "";
                        $.each(temp, function( index, value ) {                        
                            vari += value + "_" ;
                        });
                        vari = vari.substring(0, vari.length - 1);                    
                        vari += "#"+patokan;
                        $("#field-id_histori_kasus").val(vari);
                    }
                    
                }

                function isiTersangka(id){
                    if(($("#field-tersangka").val()==="")||($("#field-tersangka").val()===null)){                        
                        alert("harap mengisi kolom tersangka. Terimakasih.");
                        $(id).val("");
                        $(id).trigger('liszt:updated');
                        $(".default").focus();                        
                    }
                }

                
            });
</script>


<?php 
foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
</head>
<body>	
    <div style="padding: 10px">
		<?php echo $output; ?>
    </div>
   
</body>
<script type="text/javascript">
$( document ).ready(function() {
 
    var datesCollection = document.getElementsByClassName("numeric");
    var dates = Array.from(datesCollection);

    dates.forEach(function (date) {
        new Cleave(date, {
            numeral: true,
            numeralThousandsGroupStyle: 'thousand',
        })
    });
    $("#kabupaten_id_kab_input_box").css("display", "block");
    $("#kecamatan_id_kecamatan_input_box").css("display", "block");
    $("#kelurahan_id_lurah_input_box").css("display", "block");
    $("#field-id_kawasan").change(function(){
            $("#field-provinsi_id_prov").prop("disabled", false).trigger("liszt:updated");
            //$("#field-id_kawasan").val('').trigger("liszt:updated");
            if($("#field-id_kawasan").val()!==""){
                $("#field-provinsi_id_prov").prop('disabled', true).trigger("liszt:updated");            
                $("#field-provinsi_id_prov").val('').trigger("liszt:updated");
            }
                
    });
    $("#field-hak_akses").change(function(){
        $("#field-id_lk").prop('disabled', false).trigger("liszt:updated");            
            //$("#field-id_kawasan").val('').trigger("liszt:updated");
            if($("#field-hak_akses").val()==="ADMIN"){
                $("#field-id_lk").prop('disabled', true).trigger("liszt:updated");            
                $("#field-id_lk").val('').trigger("liszt:updated");
            }
                
        });
//------------------------------------------------------------------------
 
/*if($("input[type=text]").hasClass("numeric")){
    var cleave = new Cleave('.numeric', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        //delimiters: ['.', '.']
    });
}*/  


   /* var cleave = new Cleave('.numeric', {
        numeral: true,
        numeralThousandsGroupStyle: 'thousand',
        //delimiters: ['.', '.']
    });*/
    
});
</script>
</html>


<?php

?>