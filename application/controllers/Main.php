<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_Controller {
		public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}
	
	public function modifKodeSumber($post_array){                
        //$post_array["KODE_SENGKETA"] = md5($post_array["SUMBER_SENGKETA"]);
        $this->load->model("Instansi");
        $isduplicate = $this->Instansi->isDuplicate($post_array["PROPINSI"], $post_array["NAMA_INSTANSI"]);
        $post_array["ID_INSTANSI"] ="";
        if(!$isduplicate){
			$post_array["ID_INSTANSI"] = $this->Instansi->cekInstansi($post_array["PROPINSI"]);        	
		}        
        return $post_array;
    }
    
    //-------------------------callback funtion---------------------------
    public function isInstansiDuplicate($post_array){
		$this->load->model("Instansi");
        $isduplicate = $this->Instansi->isDuplicate($post_array["PROPINSI"], $post_array["NAMA_INSTANSI"]);
        if($isduplicate){
			return FALSE;
		}else{
			return TRUE;
		}
	}

	public function isiTglSave($post_array){
		$post_array["TGL_SAVE"]=date("Y-m-d");
		return $post_array;
	}
    
    public function statusBukuPAS($value, $row){
		$now = date("Y-m-d");
		$value = ($now<=$row->TGL_HABIS_PAS)?"Berlaku":"Masa Berlaku Habis";
		return($value);
	}

	public function hitungJmlSenpi($value, $row){
		$value = $row->JML_SENAPAN+$row->JML_SHOTGUN+$row->JML_PISTOL+$row->JML_BIUS;
		return($value);
	}
	
	public function resetPass($value , $row){
		echo '<script>alert("You Have Successfully updated this Record! '.$primary_key.'");</script>';
               //redirect('main/userList_ctl', 'refresh');
	}
	
	public function generateMd5($value , $row){
		
	}
    //-------------------------end of callback funtion---------------------------
	public function kondisiBrg_ctl(){
		try{
        $crud=new grocery_CRUD();        
        $crud->set_table('kondisi_barang');
		$crud->required_fields("id_kondisi", "nama_kondisi");        
		$output = $crud->render();
        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function jenisSenjata_ctl(){
		try{
        $crud=new grocery_CRUD();        
        $crud->set_table('jenis_senjata');
		$crud->required_fields("id_senjata", "nama_senjata");        
		$output = $crud->render();
        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function instansi_ctl(){
		try{
        $crud=new grocery_CRUD();        
        $crud->set_table('instansi1');
        //$crud->add_action('Selengkapnya', '', 'master_control/detilSengketaPengadilan','ui-icon-lightbulb');
        //$crud->add_action('Tambah','','master_control/formTambahSengketa','ui-icon-circle-plus');
		//$crud->fields("PROPINSI", "KABUPATEN", "NAMA_INSTANSI");            
		$crud->required_fields("PROPINSI", "NAMA_INSTANSI");        
                
        $crud->set_relation('PROPINSI','provinces','NAME');
        $crud->set_relation('KABUPATEN','regency','NAME');
                                
        //$crud->unset_add();
        //$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
        $crud->callback_before_insert(array($this,'modifKodeSumber'));
        $crud->callback_before_update(array($this,'isInstansiDuplicate'));
        $crud->field_type("ID_INSTANSI","hidden");
                
        //$crud->display_as('ID_KABUPATEN','KABUPATEN/KOTA');
        
        $this->load->library('gc_dependent_select');        
        
        $fields = array(
                        'PROPINSI' => array(// first dropdown name
                        'table_name' => 'provinces', // table of country
                        'title' => 'NAME', // country title
                        'relate' => null // the first dropdown hasn't a relation
                        ),
                        'KABUPATEN' => array(// second dropdown name
                        'table_name' => 'regency', // table of state
                        'title' => 'NAME', // state title
                        'id_field' => 'ID', // table of state: primary key
                        'relate' => 'ID_PROVINSI', // table of state:
                        'data-placeholder' => 'Pilih Kabupaten/Kota' //dropdown's data-placeholder:
                        ));
        
        $config = array(
                        'main_table' => 'instansi1',
                        'main_table_primary' => 'ID_INSTANSI',
                        "url" => base_url() .'/index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                        'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
        );
        $categories = new gc_dependent_select($crud, $fields, $config);
        $js = $categories->get_js();
        
        $output = $crud->render();
        //$output->output.= $js . $js2;
        $output->output.= $js;
        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function monitoringSenpi_ctl(){
		try{
        $crud=new grocery_CRUD();        
        $crud->set_table('mst_senpi');
        //$crud->add_action('Selengkapnya', '', 'master_control/detilSengketaPengadilan','ui-icon-lightbulb');
        //$crud->add_action('Tambah','','master_control/formTambahSengketa','ui-icon-circle-plus');
		$crud->columns("NO_PABRIK", "JENIS_SENPI", "KONDISI", "ID_INSTANSI", "PEMEGANG", "NO_BUKU_PAS", "STATUS_BUKU_PAS");            
		//$crud->required_fields("PROPINSI", "KABUPATEN", "NAMA_INSTANSI");        
                
        $crud->set_relation('JENIS_SENPI','jenis_senjata','nama_senjata');
        $crud->set_relation('ID_INSTANSI','instansi1','NAMA_INSTANSI');
        $crud->set_relation('KONDISI','kondisi_barang','nama_kondisi');
        $crud->set_relation('NOMOR_BAP','bap','NOMOR_BAP');
                                
    	$crud->display_as('ID_INSTANSI','INSTANSI');
    	$crud->display_as('TGL_REG_PAS','TGL BERLAKU BUKU PAS');
    	$crud->display_as('TGL_HABIS_PAS','TGL HABIS BUKU PAS');
    	//$crud->display_as('NO_PENG_PIN','INSTANSI');
    	$crud->display_as('TGL_REG_PENG','TGL BERLAKU PENGPIN');
    	$crud->display_as('TGL_HABIS_PENG','TGL HABIS PENGPIN');
        //$crud->unset_add();
        //$crud->set_relation_n_n('actors', 'film_actor', 'actor', 'film_id', 'actor_id', 'fullname','priority');
        $crud->callback_before_insert(array($this,'isiTglSave'));
        $crud->callback_column('STATUS_BUKU_PAS',array($this,'statusBukuPAS'));
        
        $crud->field_type("TGL_SAVE","hidden");
                
        $output = $crud->render();        
        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function monitoringBAP_ctl(){
		try{
        $crud=new grocery_CRUD();        
        $crud->set_table('bap');
        //$crud->add_action('Selengkapnya', '', 'master_control/detilSengketaPengadilan','ui-icon-lightbulb');
        //$crud->add_action('Tambah','','master_control/formTambahSengketa','ui-icon-circle-plus');
		$crud->columns("ID_INSTANSI", "NOMOR_BAP", "TGL_BAP", "PEMERIKSA", "PERMASALAHAN");            
		//$crud->required_fields("PROPINSI", "KABUPATEN", "NAMA_INSTANSI");        
                
        //$crud->set_relation('JENIS_SENPI','jenis_senjata','nama_senjata');
        $crud->set_relation('ID_INSTANSI','instansi1','NAMA_INSTANSI');
        //$crud->set_relation('KONDISI','kondisi_barang','nama_kondisi');
                                
    	$crud->display_as('ID_INSTANSI','INSTANSI');
    	                
        $output = $crud->render();        
        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function monitoringAmunisi_ctl(){
		try{
        $crud=new grocery_CRUD();        
        $crud->set_table('amunisi');
        //$crud->add_action('Selengkapnya', '', 'master_control/detilSengketaPengadilan','ui-icon-lightbulb');
        //$crud->add_action('Tambah','','master_control/formTambahSengketa','ui-icon-circle-plus');
		//$crud->unset_columns("BA", "KETERANGAN_AMUNISI");            
		$crud->columns("SUMBER", "NO_BA", "ID_INSTANSI", "TGL_TERIMA", "TGL_KADALUARSA", "JML_SENAPAN", "JML_SHOTGUN", "JML_PISTOL", "JML_BIUS", "TOTAL_SENJATA");
		//$crud->required_fields("PROPINSI", "KABUPATEN", "NAMA_INSTANSI");        
        $crud->callback_column('TOTAL_SENJATA',array($this,'hitungJmlSenpi'));
        //$crud->set_relation('JENIS_SENPI','jenis_senjata','nama_senjata');
        $crud->set_relation('ID_INSTANSI','instansi1','NAMA_INSTANSI');
        //$crud->set_relation('KONDISI','kondisi_barang','nama_kondisi');
                                
    	$crud->display_as('ID_INSTANSI','INSTANSI');
    	                
        $output = $crud->render();        
        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function mutasiSenpi_ctl(){
		try{
	        $crud=new grocery_CRUD();        
	        $crud->set_table('mutasi_senpi');
	        //$crud->add_action('Selengkapnya', '', 'master_control/detilSengketaPengadilan','ui-icon-lightbulb');
	        //$crud->add_action('Tambah','','master_control/formTambahSengketa','ui-icon-circle-plus');
			//$crud->unset_columns("BA", "KETERANGAN_AMUNISI");            
			$crud->columns("NO_PABRIK", "TGL_MUTASI","ID_INSTANSI_ASAL", "ID_INSTANSI_PENERIMA", "NO_BA_MUTASI", "KETERANGAN_MUTASI");
			//$crud->required_fields("PROPINSI", "KABUPATEN", "NAMA_INSTANSI");        
	        //$crud->callback_column('TOTAL_SENJATA',array($this,'hitungJmlSenpi'));
	        //$crud->set_relation('JENIS_SENPI','jenis_senjata','nama_senjata');
	        
	        $crud->set_relation('ID_INSTANSI_ASAL','instansi1','NAMA_INSTANSI');
	        $crud->set_relation('ID_INSTANSI_PENERIMA','instansi1','NAMA_INSTANSI');
	        $crud->set_relation('NO_PABRIK','mst_senpi','NO_PABRIK');
	        
	                                
	    	$crud->display_as('ID_INSTANSI_ASAL','INSTANSI ASAL');
	    	$crud->display_as('ID_INSTANSI_PENERIMA','INSTANSI PENERIMA');
	    	                
	        $output = $crud->render();        
	        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function userList_ctl(){
		try{
	        $crud=new grocery_CRUD();        
	        $crud->set_table('crud_users');
	        $crud->set_theme('datatables');
	        $crud->add_action('Reset Password', '', '','ui-icon-lightbulb',array($this,'resetPass'));
	        
	        
	        $crud->set_relation('id_instansi','instansi1','NAMA_INSTANSI');
	        //$crud->set_relation('ID_INSTANSI_PENERIMA','instansi1','NAMA_INSTANSI');
	        //$crud->set_relation('NO_PABRIK','mst_senpi','NO_PABRIK');	        
	        
	        $crud->callback_before_insert(array($this,'generateMd5'));
	        $crud->callback_before_update(array($this,'generateMd5'));
	                                
	    	$crud->display_as('ID_INSTANSI','INSTANSI');	    	
	    	                
	        $output = $crud->render();        
	        $this->displayCRUD($output);
        }catch(Exception $e){
			show_error($e->getMessage().' --- '.$e->getTraceAsString());
		}
	}
	
	public function index(){		
		$req["judul"]="Mutasi SENPI";
		$req["url"]=site_url('main/mutasiSenpi_ctl');
		$this->load->view("header");
		$this->load->view("MstInstansiView", $req);
		$this->load->view("footer");
		
		//$this->mst_instansi();
	}
	
//----------------------MENU-------------------
public function monitoring_mutasiSenpi(){
	$req["judul"]="Mutasi SENPI";
	$req["url"]=site_url('main/mutasiSenpi_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function UserList(){
	$req["judul"]="Member SENPI";
	$req["url"]=site_url('main/userList_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function monitoring_Amo(){
	$req["judul"]="Monitoring Amunisi";
	$req["url"]=site_url('main/monitoringAmunisi_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function monitoring_BAP(){
	$req["judul"]="Berita Acara Pemeriksaan";
	$req["url"]=site_url('main/monitoringBAP_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function monitoring_Senpi(){
	$req["judul"]="Monitoring SENPI";
	$req["url"]=site_url('main/monitoringSenpi_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function mst_kondisiBrg(){
	$req["judul"]="Kondisi Barang";
	$req["url"]=site_url('main/kondisiBrg_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function mst_jenisSenjata(){
	$req["judul"]="Jenis Senjata";
	$req["url"]=site_url('main/jenisSenjata_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}

public function mst_instansi(){
	$req["judul"]="Instansi/UPT";
	$req["url"]=site_url('main/instansi_ctl');
	$this->load->view("header");
	$this->load->view("MstInstansiView", $req);
	$this->load->view("footer");
}
//----------------------TUTUP MENU-------------------	

	public function displayCRUD($output){
		$this->load->view("gc_view", $output);
	}
	
	function create_csv(){
        $query = $this->db->query('SELECT * FROM <tablename>');
        $num = $query->num_fields();
        $var =array();
        $i=1;
        $fname="";
        while($i <= $num){
            $test = $i;
            $value = $this->input->post($test);

            if($value != ''){
                    $fname= $fname." ".$value;
                    array_push($var, $value);

                }
             $i++;
        }

        $fname = trim($fname);

        $fname=str_replace(' ', ',', $fname);

        $this->db->select($fname);
        $quer = $this->db->get('<tablename>');
        
        query_to_csv($quer,TRUE,'Products_'.date('dMy').'.csv');
        
    }
	
	
	public function getkabupaten($idprov){
		
		//$idprov = $this->input->post('prov', TRUE);
		$idprov = $this->security->xss_clean($idprov);
		$this->load->model('regencies');
		$result = $this->regencies->showKabupaten($idprov);
		echo json_encode($result);
		//echo "idnya = ".$idprov;
	}
	
	public function insertInstansi(){
		$idprov = $this->input->post('prop', TRUE);
		$idkab = $this->input->post('kabkot', TRUE);
		$nama = $this->input->post('nama', TRUE);		
	}
	
	private function createInstansiID($nama, $idprov, $idkab){
		//$this->load->model("ins")
	}
	
	
	
	public function index1()
	{
		//$this->_example_output();
		
		$crud = new grocery_CRUD();

			$crud->set_theme('datatables');
			$crud->set_table('instansi');
			$crud->set_relation('ID_PROV','PROVINCES','NAME');
			$crud->set_relation('ID_KABUPATEN','regencies','name');
			
			$this->load->library('gc_dependent_select');
			
			$fields = array(

				// first field:
				'ID_PROV' => array( // first dropdown name
									'table_name' => 'PROVINCES', // table of country
									'title' => 'NAME', // country title
									'relate' => null, // the first dropdown hasn't a relation
									'data-placeholder' => 'Select Provinsi' //dropdown's data-placeholder:
				),
				// second field
				'ID_KABUPATEN' => array( // second dropdown name
										'table_name' => 'regencies', // table of state
										'title' => 'name', // state title
										'id_field' => 'id', // table of state: primary key
										'relate' => 'id_provinsi', // table of state:
										'data-placeholder' => 'Select Kabupaten/Kota' //dropdown's data-placeholder:
				)
			);
			
			$config = array(
				'main_table' => 'instansi',
				'main_table_primary' => 'ID_INSTANSI',				
				'url'=>base_url().'index.php/'.__CLASS__.'/'.__METHOD__.'/' 
				//"url" => base_url() . __CLASS__ . '/' . __FUNCTION__ . '/'//, path to method
				//'ajax_loader' => base_url() . 'ajax-loader.gif' // path to ajax-loader image. It's an optional parameter
				//'segment_name' =>'Your_segment_name' // It's an optional parameter. by default "get_items"
			);
			
			//var_dump($config);
			$categories = new gc_dependent_select($crud, $fields, $config);
			$js = $categories->get_js();
			$output = $crud->render();
			$output->output.= $js;
			$this->_example_output($output);
/*			
			
			
			$crud->display_as('officeCode','Office City');
			$crud->set_subject('Employee');
			
			

			$crud->required_fields('lastName');

			$crud->set_field_upload('file_url','assets/uploads/files');

			$output = $crud->render();

			$this->_example_output($output);
*/	}
	
	public function _example_output($output = null)
	{
		$this->load->view('header.php',(array)$output);
		$this->load->view('mainView.php',(array)$output);
		$this->load->view('footer.php');		
	}
}