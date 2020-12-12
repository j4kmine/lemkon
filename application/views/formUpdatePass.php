<h3 class="header smaller lighter blue">Form Pembaruan Password</h3>
<form class="form-horizontal" role="form" id="form-pass">
<div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Input Password Lama </label>
        <div class="col-sm-9">
                <input type="password" id="pass" placeholder="Input Password Lama" class="col-xs-10 col-sm-5">                
        </div>
</div>
<div class="space-4"></div>
<div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Input Password Baru </label>
        <div class="col-sm-9">
                <input type="password" id="form-newPass" placeholder="Input Password Baru" class="col-xs-10 col-sm-5">                
        </div>
</div>
<div class="space-4"></div>
<div class="form-group">
        <label class="col-sm-3 control-label no-padding-right" for="form-field-2"> Ketik Ulang Password Baru </label>
        <div class="col-sm-9">
                <input type="password" id="form-rePass" placeholder="Ketik Ulang Password Baru" class="col-xs-10 col-sm-5">
        </div>
</div>
<div class="space-4"></div>
<div class="clearfix form-actions">
        <div class="col-md-offset-3 col-md-9">
                <button class="btn btn-info" type="button" id="submitBtn">
                        <i class="ace-icon fa fa-check bigger-110"></i>
                        Perbarui Password
                </button>

                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i>
                        Batal
                </button>
        </div>
</div>
</form>

<script>
    $( document ).ready(function() {
        $("#submitBtn").click(function(){
            var isianOke = true;
            var passLama = $("#pass").val();
            var passBaru = $("#form-newPass").val();
            var rePass = $("#form-rePass").val();
            if((passLama === "")||(passLama=== undefined)||(passLama===null)){
                alert("silakan isi password lama Anda terlebih dahulu. Terimakasih.");
                isianOke = false;
                $("#pass").focus();
            }
            if(passBaru!==rePass){
                alert("Password baru dan pengulangan password tidak sama. Silakan ulangi pengisian Password. Terimakasih.");
                $("#form-newPass").val("");
                $("#form-rePass").val("");
                $("#form-newPass").focus("");
                isianOke = false;
            }
            if(isianOke){
                if((passBaru === "")||(passBaru=== undefined)||(passBaru===null)){
                    alert("silakan isi password baru Anda terlebih dahulu. Terimakasih.");
                    isianOke = false;
                    $("#form-newPass").focus();
                }
                if(isianOke){
                    $.ajax({
                        type: 'POST',
                        url: "<?php 
                        $url = site_url('php/mst_upass_ctl');
                        if(strtolower($this->uri->segment(1))=="lemkon"){
                            $url = site_url('lemkon/mst_upass_ctl');   
                        }
                        echo $url;
                        ?>/",
                        //contentType: "application/json; charset=utf-8",

                        //dataType: "json", 
                        dataType: "text", 
                        data: {pl: passLama, pb: passBaru},

                        beforeSend: function(){			                	
                                $("#form-pass").LoadingOverlay("show", {
                                                    image       : "",
                                                    fontawesome : "fa fa-cog fa-spin"});                                
                                        },
                        success: function (hasil) {			                	                    
                            switch(hasil){
                                case "1": alert ("Proses perubahan Password SUKSES. Terimakasih");
                                    break;
                                case "2": alert ("Password lama tidak cocok. Silakan ulangi pengisian. Terimakasih");
                                        $("#form-newPass").val("");
                                        $("#form-rePass").val("");
                                        $("#pass").val("");
                                        $("#pass").focus();
                                    break;
                                case "0": alert ("Proses perubahan Password Gagal. Silakan cek koneksi atau reload halaman ini. Terimakasih");
                                        $("#form-newPass").val("");
                                        $("#form-rePass").val("");
                                        $("#pass").val("");
                                        $("#pass").focus();
                                    break;
                            }                            
                            $("#form-pass").LoadingOverlay("hide", true);                            
                        },
                        error: function (result) {
                            console.log(result);
                        }
                    });
                }
            }
        });
    });
</script>