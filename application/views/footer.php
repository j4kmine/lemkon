
<!-- PAGE CONTENT ENDS -->
<?php echo
                $title="";
				$direktorat="";
				$ditjen="Ditjen Penegakan Hukum Lingkungan Hidup dan Kehutanan";
                $dashboardLink="";
                
                $aplikasi = (isset($app))?$app:"senpi";
                $app =$aplikasi;
                    switch ($aplikasi){
						case "php": $title="Direktorat Penegakan Hukum Pidana";
									$direktorat = "Direktorat Penegakan Hukum Pidana";
                                     $dashboardLink = site_url("php/dashboardphp");
                            break;
                        case "senpi": $title="SENPI - PPH";
                                     $dashboardLink = site_url("dashboard");
							break;
						case "lk": $title="Sistem Informasi dan Monitoring Lembaga Konservasi Indonesia - SIMILKI";
							$dashboardLink = site_url("lemkon/dashboard");
							$ditjen="Ditjen Konservasi Sumber Daya Alam Ekosistem";
				   			break;
                        default : $title="Kementerian Lingkungan Hidup dan Kehutanan";
                            break;
                    }
                
                ?>
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120" style="line-height: 20px">
							Kementerian Lingkungan Hidup dan Kehutanan<br />
							<?php echo $ditjen?>
							<br />
							
							
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								
								<i class="ace-icon fab fa-instagram orange bigger-150" ></i>
							</a>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		

		

		<!-- ace scripts -->
		<script src="<?php echo base_url()."html/"?>assets/js/ace-elements.min.js"></script>
		<script src="<?php echo base_url()."html/"?>assets/js/ace.min.js"></script>
		<script src="<?php echo base_url("assets/html/js/cleave.js")?>"></script>

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
	
			jQuery(function($) {
			
				
				if(!ace.vars['touch']) {
					$('.chosen-select').chosen({allow_single_deselect:true}); 
					//resize the chosen on window resize
			
					$(window)
					.off('resize.chosen')
					.on('resize.chosen', function() {
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': $this.parent().width()});
						})
					}).trigger('resize.chosen');
					//resize chosen on sidebar collapse/expand
					$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
						if(event_name != 'sidebar_collapsed') return;
						$('.chosen-select').each(function() {
							 var $this = $(this);
							 $this.next().css({'width': $this.parent().width()});
						})
					});
			
			
					$('#chosen-multiple-style .btn').on('click', function(e){
						var target = $(this).find('input[type=radio]');
						var which = parseInt(target.val());
						if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
						 else $('#form-field-select-4').removeClass('tag-input-style');
					});
				}	
				});
				</script>
				<!--script type="text/javascript">
				$(document).ready(function() {
					var provinsi_id_prov = $('select[name="provinsi_id_prov"]');
					$('#provinsi_id_prov_input_box').append('<img src="http://localhost/php//assets/grocery_crud/themes/datatables/css/images/small-loading.gif" border="0" id="provinsi_id_prov_ajax_loader" class="dd_ajax_loader" style="display: none;">');
					var kabupaten_id_kab = $('select[name="kabupaten_id_kab"]');
					$('#kabupaten_id_kab_input_box').append('<img src="http://localhost/php//assets/grocery_crud/themes/datatables/css/images/small-loading.gif" border="0" id="kabupaten_id_kab_ajax_loader" class="dd_ajax_loader" style="display: none;">');
					kabupaten_id_kab.children().remove().end();
					var kecamatan_id_kecamatan = $('select[name="kecamatan_id_kecamatan"]');
					$('#kecamatan_id_kecamatan_input_box').append('<img src="http://localhost/php//assets/grocery_crud/themes/datatables/css/images/small-loading.gif" border="0" id="kecamatan_id_kecamatan_ajax_loader" class="dd_ajax_loader" style="display: none;">');
					kecamatan_id_kecamatan.children().remove().end();
					var kelurahan_id_lurah = $('select[name="kelurahan_id_lurah"]');
					kelurahan_id_lurah.children().remove().end();

					provinsi_id_prov.change(function() {
						var select_value = this.value;$('#provinsi_id_prov_ajax_loader').show();
						kabupaten_id_kab.find('option').remove();
						var myOptions = "";
						$.getJSON('http://localhost/php/index.php/lemkon/pelepasliaran_unindef_ctl/get_items/kabupaten_id_kab/'+select_value, function(data) {
							if(data==''){
								kabupaten_id_kab.children().remove().end();
								kabupaten_id_kab.attr('disabled', true);
								kabupaten_id_kab.find('option').remove();
								kabupaten_id_kab.append('<option value=""></option>');
								kabupaten_id_kab.trigger("liszt:updated");
							}else{
								kabupaten_id_kab.append('<option value=""></option>');
								$.each(data, function(key, val) {
									kabupaten_id_kab.append($('<option></option>').val(val.value).html(val.property));
								});
								kabupaten_id_kab.removeAttr('disabled');
								kabupaten_id_kab.trigger("liszt:updated");
							}
							kecamatan_id_kecamatan.children().remove().end();
							kecamatan_id_kecamatan.attr('disabled', true);
							kecamatan_id_kecamatan.find('option').remove();
							kecamatan_id_kecamatan.trigger("liszt:updated");
							kelurahan_id_lurah.children().remove().end();
							kelurahan_id_lurah.attr('disabled', true);
							kelurahan_id_lurah.find('option').remove();
							kelurahan_id_lurah.trigger("liszt:updated");
							provinsi_id_prov.each(function(){
								$(this).trigger("liszt:updated");
								});
							kabupaten_id_kab.each(function(){
								$(this).trigger("liszt:updated");
								});
							$('#provinsi_id_prov_ajax_loader').hide();
							});
					});
					kabupaten_id_kab.change(function() {
						var select_value = this.value;
						$('#kabupaten_id_kab_ajax_loader').show();
						kecamatan_id_kecamatan.find('option').remove();
						var myOptions = "";
						$.getJSON('http://localhost/php/index.php/lemkon/pelepasliaran_unindef_ctl/get_items/kecamatan_id_kecamatan/'+select_value, function(data) {
							if(data==''){kecamatan_id_kecamatan.children().remove().end();
								kecamatan_id_kecamatan.attr('disabled', true);kecamatan_id_kecamatan.find('option').remove();
								kecamatan_id_kecamatan.append('<option value=""></option>');
								kecamatan_id_kecamatan.trigger("liszt:updated");
							}else{
								kecamatan_id_kecamatan.append('<option value=""></option>');
								$.each(data, function(key, val) {
									kecamatan_id_kecamatan.append($('<option></option>').val(val.value).html(val.property));
								});
								kecamatan_id_kecamatan.removeAttr('disabled');
								kecamatan_id_kecamatan.trigger("liszt:updated");
							}
							kelurahan_id_lurah.children().remove().end();
							kelurahan_id_lurah.attr('disabled', true);
							kelurahan_id_lurah.find('option').remove();
							kelurahan_id_lurah.trigger("liszt:updated");
							kabupaten_id_kab.each(function(){
								$(this).trigger("liszt:updated");
							});
							kecamatan_id_kecamatan.each(function(){
								$(this).trigger("liszt:updated");
							});
							$('#kabupaten_id_kab_ajax_loader').hide();
							});
						});
					kecamatan_id_kecamatan.change(function() {
						var select_value = this.value;
						$('#kecamatan_id_kecamatan_ajax_loader').show();
						kelurahan_id_lurah.find('option').remove();
						var myOptions = "";
						$.getJSON('http://localhost/php/index.php/lemkon/pelepasliaran_unindef_ctl/get_items/kelurahan_id_lurah/'+select_value, function(data) {
							if(data==''){
								kelurahan_id_lurah.children().remove().end();
								kelurahan_id_lurah.attr('disabled', true);
								kelurahan_id_lurah.find('option').remove();
								kelurahan_id_lurah.append('<option value=""></option>');
								kelurahan_id_lurah.trigger("liszt:updated");
							}else{
								kelurahan_id_lurah.append('<option value=""></option>');
								$.each(data, function(key, val) {
									kelurahan_id_lurah.append($('<option></option>').val(val.value).html(val.property));
									});
								kelurahan_id_lurah.removeAttr('disabled');
								kelurahan_id_lurah.trigger("liszt:updated");
							}
							kecamatan_id_kecamatan.each(function(){
								$(this).trigger("liszt:updated");
							});
							kelurahan_id_lurah.each(function(){
								$(this).trigger("liszt:updated");
							});
							$('#kecamatan_id_kecamatan_ajax_loader').hide();
						});
					});
					});</script>
					
					<script type="text/javascript">
					$(document).ready(function() {
						var informasi_lk_umum_id_lk = $('select[name="informasi_lk_umum_id_lk"]');
						$('#informasi_lk_umum_id_lk_input_box').append('<img src="http://localhost/php//assets/grocery_crud/themes/datatables/css/images/small-loading.gif" border="0" id="informasi_lk_umum_id_lk_ajax_loader" class="dd_ajax_loader" style="display: none;">');
						var id_satwa_noniden = $('select[name="id_satwa_noniden"]');
						id_satwa_noniden.children().remove().end();
						informasi_lk_umum_id_lk.change(function() {
							var select_value = this.value;
							$('#informasi_lk_umum_id_lk_ajax_loader').show();
							id_satwa_noniden.find('option').remove();
							var myOptions = "";
							$.getJSON('http://localhost/php/index.php/lemkon/pelepasliaran_unindef_ctl/get_items/id_satwa_noniden/'+select_value, function(data) {
								if(data==''){
									id_satwa_noniden.children().remove().end();
									id_satwa_noniden.attr('disabled', true);
									id_satwa_noniden.find('option').remove();
									id_satwa_noniden.append('<option value=""></option>');
									id_satwa_noniden.trigger("liszt:updated");
								}else{
									id_satwa_noniden.append('<option value=""></option>');
									$.each(data, function(key, val) {
										id_satwa_noniden.append($('<option></option>').val(val.value).html(val.property));
									});
									id_satwa_noniden.removeAttr('disabled');
									id_satwa_noniden.trigger("liszt:updated");
								}
							informasi_lk_umum_id_lk.each(function(){
								$(this).trigger("liszt:updated");
							});
							id_satwa_noniden.each(function(){
								$(this).trigger("liszt:updated");
							});
							$('#informasi_lk_umum_id_lk_ajax_loader').hide();
							});
						});
					});</script-->    </div>

	</body>
</html>
