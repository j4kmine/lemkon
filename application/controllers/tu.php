<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class tu extends CI_Controller {
        public function __construct()
	{
            parent::__construct();
            $this->load->database();
            $this->db->db_select("pslh");
            $this->load->helper('url');
            $this->load->library('grocery_CRUD');
            $this->load->model("login_model");
            if($this->login_model->isLogged()){
                $this->nama = $this->login_model->name();
                $this->hakAkses= $this->login_model->hakAkses();
                //echo "HI $name! You are Logged IN!";
            }else{
                redirect("/login");
            }
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            /*if($this->login_model->isLogged()){
                $name = $this->login_model->name();
                echo "HI $name! You are Logged IN!";
            }else{
                redirect("/login");
            }*/
    }
    private  $username;
    private  $nama;
    private  $hakAkses;
        public function tu_ekin(){
            $this->load->model("login_model");
                if(!$this->login_model->isLogged()){
                    redirect("/login");
                }
            $data['page'] = 'import';
            $data['title'] = 'Import XLSX | TechArise';
            $req["hak_akses"] = $this->login_model->hakAkses();
            $req["app"]="pslh";
            $this->load->view("header", $req);
            //$this->load->view("MstInstansiView", $req);
            $this->load->view('xlsImport', $data);
            $this->load->view("footer");
            
        }
        
        public function tu_pgw(){
            $req["judul"]="Informasi Pegawai";
            $req["url"]=site_url('pslh/mst_pegawai_ctl');
            $req["app"]="php";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }
        
        public function tu_pgw_ctl(){
                try{
                    $crud = new Grocery_CRUD();
                    $crud->set_table("mst_pegawai");                
                    $crud->set_relation("golongan", "mst_golongan", "nama_golongan");
                    $crud->set_relation("jabatan", "mst_jabatan", "nama_jabatan");
                    $crud->callback_before_insert(array($this,'capitalLetters'));
                    $crud->callback_before_update(array($this,'capitalLetters'));
                    $output = $crud->render();        
                    $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }                
        }
            
        public function capitalLetters($post_array){            
            $post_array['nama'] = strtoupper($post_array['nama']);
            return $post_array;
        } 
        
        public function displayCRUD($output){
            $this->load->view("gc_view", $output);
        }
            
        public function savexls() {
            
        $this->load->library('excel');
        $this->load->library('upload');
        
        if ($this->input->post('importfile')) {
            $path = "assets/uploads/xls/";
            
            $config['upload_path'] = $path;
            $config['allowed_types'] = 'xlsx|xls';
            $config['remove_spaces'] = TRUE;
            $this->upload->initialize($config);
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload('userfile')) {
                $error = array('error' => $this->upload->display_errors());
            } else {
                $data = array('upload_data' => $this->upload->data());
            }
            
            if (!empty($data['upload_data']['file_name'])) {
                $import_xls_file = $data['upload_data']['file_name'];
            } else {
                $import_xls_file = 0;
            }
            $inputFileName = $path . $import_xls_file;
            try {
                $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
                $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
                        . '": ' . $e->getMessage());
            }
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true); //This will return you an array of the current active sheet
                        
            /*$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
            for($i=2;$i<=$arrayCount;$i++)
            {                   
                $data["product"][$i]=$allDataInSheet[$i]["A"];
                $data["brand"][$i]=$allDataInSheet[$i]["B"];
                $data["standard"][$i]=$allDataInSheet[$i]["C"];
            }*/
            $data["dataPeg"] = $this->generateReport($allDataInSheet);
            
            $object = new PHPExcel();

            $object->setActiveSheetIndex(0);

            $table_columns = array("No", "NAMA", "GOL", "NIP", "JABATAN", 
                                   "E-KIN MANUAL", "SAKIT (HARI)", "IJIN (HARI)", "TANPA KETERANGAN (HARI)", "CUTI (HARI)", "LUPA ABSEN DATANG", "LUPA ABSEN PULANG", 
                                   "JML KEHADIRAN (HARI)","SPT (HARI)", "TERLAMBAT","PULANG CEPAT", "POTONGAN TUKIN (%)", 
                                   "KETERANGAN");
            $style = array(
                        'alignment' => array(
                            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN
                            )
                        )
                    );

                    
            $column = 'A';
            $object->getActiveSheet()->setCellValue('A'."1","REKAPITULASI LAPORAN KINERJA PEGAWAI");
            $object->getActiveSheet()->setCellValue('A'."2","BERBASIS ELEKTRONIK KINERJA (E-KIN)");
            $object->getActiveSheet()->setCellValue('A'."3","NAMA SATKER: ");
            $object->getActiveSheet()->setCellValue('A'."4","JUMLAH PEGAWAI: ".count($data["dataPeg"]["empName"]));
            $object->getActiveSheet()->setCellValue('A'."5","BULAN ".  strtoupper(date("F Y")));
            $object->getActiveSheet()->getStyle( 'A'."1" )->getFont()->setBold( true );
            $object->getActiveSheet()->getStyle('A'."1")->applyFromArray($style);
            $object->getActiveSheet()->getStyle( 'A'."2" )->getFont()->setBold( true );
            $object->getActiveSheet()->getStyle('A'."2")->applyFromArray($style);
            $object->getActiveSheet()->mergeCells('A1:R1');
            $object->getActiveSheet()->mergeCells('A2:R2');
            foreach($table_columns as $field)
            {
             $object->getActiveSheet()->setCellValue($column."6", $field);
             $object->getActiveSheet()->getStyle( $column."6" )->getFont()->setBold( true );
             $object->getActiveSheet()->getStyle($column."6")->applyFromArray($style);             
             $column++;
            }
            
            $baris = '7';
            $kolom='A';
            $i=0;
            foreach ($data["dataPeg"]["empName"] as $nama){
                $object->getActiveSheet()->setCellValue($kolom.$baris,$i+1);$kolom++;
                
                $datapegawai = $this->getPegawai($data["dataPeg"]["fingerId"][$i]);
                $datapegawai["nama"] = ($datapegawai["nama"]=="unknown"?$nama:$datapegawai["nama"]);
                $object->getActiveSheet()->setCellValue($kolom.$baris,$datapegawai["nama"]);$kolom++;
                $object->getActiveSheet()->setCellValue($kolom.$baris,$datapegawai["golongan"]);$kolom++; //gol
                $object->getActiveSheet()->setCellValue($kolom.$baris,$datapegawai["nip"]);$kolom++; //nip
                $object->getActiveSheet()->setCellValue($kolom.$baris,$datapegawai["nama_jabatan"]);$kolom++; //jabatan
                $object->getActiveSheet()->setCellValue($kolom.$baris,"-");$kolom++; //ekin manual
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["sakit"]);$kolom++; //sakit
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["ijin"]);$kolom++; //ijin
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["alpa"]);$kolom++; //tanpa keterangan
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["cuti"]);$kolom++; //cuti
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["lupaAbsenDatang"]);$kolom++; //
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["lupaAbsenPulang"]);$kolom++;
                
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["jmlHadir"]);$kolom++;
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["spt"]);$kolom++;
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["totalTelat"]);$kolom++;
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["totalPulangCepat"]);$kolom++;
                $object->getActiveSheet()->setCellValue($kolom.$baris,$data["dataPeg"]["detil"][$i]["potonganTukin"]);$kolom++;
                $object->getActiveSheet()->setCellValue($kolom.$baris,"-");
                $object->getActiveSheet()->getStyle("A7:".$kolom.$baris)->applyFromArray($style); 
                $baris++;
                $i++;
                $kolom='A';
            }
            
            $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Rekap Ekin '.strtoupper(date("F Y")).'.xls"');
            $object_writer->save('php://output');
            //$this->load->view('testView', $data);
            
            
            } else {
                echo "Please import correct file";
            }
            
        }
        
        private function getPegawai($fingerID=NULL){
            if($fingerID!=NULL){
                $fingerID = $this->security->xss_clean($fingerID);
            }
            $this->load->model("mst_pegawai");
            $list = $this->mst_pegawai->getPegawaiNIPGolJab($fingerID);
            $result = array();
            $result["nama"] = "unknown";
            $result["nip"] = "-";
            $result["golongan"] = "-";
            $result["nama_jabatan"] = "-";   
            foreach($list as $row){
                $result["nama"] = $row->nama;
                $result["nip"] = "'".$row->nip;
                $result["golongan"] = $row->golongan;
                $result["nama_jabatan"] = $row->nama_jabatan;                
            }
            return $result;
        }


        private function generateReport($allDataInSheet){
            //$arrayCount = count($allDataInSheet);  // Here get total count of row in that Excel sheet
            $counter=11;
            $i=0;
            $data["empName"]=array();
            $data["fingerId"] = array();
            $data["detil"]=array();
            do{
                $data["empName"][$i] = $allDataInSheet[$counter]["C"];
                $data["fingerId"][$i] = $allDataInSheet[$counter-3]["C"];
                $data["detil"][$i] = $this->getDetilAbsen($allDataInSheet, $counter);
                $counter += 42;
                $i++;
            }while (isset($allDataInSheet[$counter]["C"])); 
            
            return $data;
        }                
        
        private function getDetilAbsen($allDataInsheet, $startIndex){
            $startIndex += 4;                        
            $data["jmlHadir"]=0;
            $data["lupaAbsenPulang"]=0;
            $data["lupaAbsenDatang"]=0;
            $data["potonganTukin"]=0;
            $data["sakit"]=0;
            $data["ijin"]=0;
            $data["alpa"]=0;
            $data["cuti"]=0;
            $data["spt"]=0;
            $notes = strtolower($allDataInsheet[$startIndex]["K"]);
            $temp = explode("st",$notes);
            if(count($temp)>1){
                $notes = "spt";
            }
            $temp = explode("ct",$notes);
            do{                  
                switch ($notes) {
                    case "working hour":
                        $data["jmlHadir"]++;
                        $data["potonganTukin"] += $this->getPotonganTukin($allDataInsheet, $startIndex);
                        $data["lupaAbsenPulang"] += $this->getLupaAbsenPulang($allDataInsheet, $startIndex);
                        $data["lupaAbsenDatang"] += $this->getLupaAbsenDatang($allDataInsheet, $startIndex);
                        break;
                    case "sakit":
                        $data["sakit"]++;
                        $data["potonganTukin"] += 2;
                        break;
                    case "ijin":
                        $data["ijin"]++;
                        $data["potonganTukin"] += 2;
                        break;
                    case "alpa":
                        $data["alpa"]++;
                        $data["potonganTukin"] += 5;
                        break;
                    case "cuti":
                        $data["cuti"]++;
                        $data["potonganTukin"] += 0;
                        break;
                    case "spt":
                        $data["spt"]++;
                        $data["potonganTukin"] += 0;
                        break;
                    case "absent":
                        if(strtolower($allDataInsheet[$startIndex]["D"])=="work"){
                            $data["alpa"]++;
                            $data["potonganTukin"] += 5;
                        }
                    default:
                        $data["potonganTukin"] += 0;                        
                        break;
                }
                $startIndex++;
            }while(strtolower($allDataInsheet[$startIndex]["D"])!="total");             
            $data["totalTelat"] = $allDataInsheet[$startIndex]["G"];
            $data["totalPulangCepat"] = $allDataInsheet[$startIndex]["H"];
            $data["potonganTukin"] += 1.5 * $data["lupaAbsenPulang"];
            $data["potonganTukin"] += 1.5 * $data["lupaAbsenDatang"];
            return $data;
        }
        
        private function getLupaAbsenPulang($allDataInsheet, $startIndex){                   
            return (!isset($allDataInsheet[$startIndex]["F"]));
        }
        
        private function getLupaAbsenDatang($allDataInsheet, $startIndex){            
            return !isset($allDataInsheet[$startIndex]["E"]);
        }

        private function getPotonganTukin($allDataInsheet, $startIndex){
            $potongan = 0;
            if(isset($allDataInsheet[$startIndex]["G"])){
                $telat = $allDataInsheet[$startIndex]["G"];
                $temp = explode(":", $telat);
                if($temp[0]==0){
                    if($temp[1]<30){
                        $potongan+= 0.5;
                    }else{
                        $potongan+= 1;
                    }
                }else{
                    $potongan+= 1.5;
                }       
            }
            if(isset($allDataInsheet[$startIndex]["H"])){
                $terburuPulang = $allDataInsheet[$startIndex]["H"];
                $temp = explode(":", $terburuPulang);
                if($temp[0]==0){
                    if($temp[1]<30){
                        if($temp[1]>10){
                            $potongan+= 0.5;                        
                        }
                    }else{
                        $potongan+= 1;
                    }
                }else{
                    $potongan+= 1.5;
                }       
            }
            return $potongan;
        }
}