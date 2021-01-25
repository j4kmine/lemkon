<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class lemkon extends CI_Controller {
		public function __construct()
        {
            parent::__construct();
            $this->load->database();
                    $this->db->db_select("db_lemkon");
            $this->load->helper('url');
            $this->load->library('grocery_CRUD');
                    $this->load->model("login_model");
                    /*if(!$this->login_model->isLogged()){
                        redirect("/login");
                    }*/               
                    if($this->login_model->isLogged()){
                        $this->username = strtolower($this->login_model->name());
                        $this->hakAkses= strtolower($this->login_model->hakAkses());
                        $this->nama= strtolower($this->login_model->nama());
                        $this->nip= strtolower($this->login_model->nip());
                        $this->id_lk= $this->login_model->id_lk();
                        //echo "HI $name! You are Logged IN!";
                    }else{
                        redirect("/login");
                    }
        }

        private  $username;
        private  $nama;
        private  $hakAkses;
        private  $nip;
        private  $id_lk;        

//--------------------------------------------- dashboard function -----------------------------------------
private function jmlSatwaDashboard(){
    $this->load->model("data_individu_satwa_new");
    $this->load->model("dinamika_satwa_noniden");

    $dataIndividu = $this->data_individu_satwa->jmlSatwaPerProv();
    $dataKelompok = $this->dinamika_satwa_noniden->getJmlIndividuperProv();
}

public function dashboard(){
    $this->load->model("lk/investasi");
    $this->load->model("lk/vsatwa_per_prov");

    $result["sebaranSatwa"] = $this->vsatwa_per_prov->getJumlahSatwa();
    $result['lahirMatiChart1'] = $this->vsatwa_per_prov->getSatwaLahirMati();
    $result['daftarSatwa'] = $this->vsatwa_per_prov->getAllSatwa();
    $result["daftarSatwaLahirMati"] = $this->vsatwa_per_prov->daftarSatwaLahirMati();
    $result['investasi'] = $this->investasi->reportChart();
    $result['annualInvestasi']= $this->investasi->annualReport("TSI1");
    if($this->hakAkses=="user")
        $result['annualInvestasi']= $this->investasi->annualReport($this->id_lk);

    $result["judul"]="Monitoring pelepasliaran Satwa Berkelompok";
    $result["app"]="lk";
    $result["hak_akses"] = $this->login_model->hakAkses();

    $this->load->view("header", $result);
    $this->load->view("dashboardlemkon", $result);
    $this->load->view("footer", $result);
}

public function getDetilProp(){

    $kodeprop = $this->input->post("key", TRUE);
    $label = $this->input->post("label", TRUE);
    $latin = $this->input->post("latin", TRUE);

    $this->load->model("lk/vsatwa_per_prov");
    $temp = $this->vsatwa_per_prov->getSatwaPerProv($kodeprop, $latin);
    $judulTabel = "Daftar Sebaran Satwa pada Provinsi ".$label;
    $judulKolom = array("Nama LK","Nama Satwa","Jumlah Jantan", "Jumlah Betina", "Jumlah Unknown");
    $konten = array();
    $i=0;
    $newRow=true;
    foreach ($temp as $row) {
        /*if($i>0){
            if($konten[$i-1][0]==$row->nama_lk){
                $konten[$i-1][1].= ", ".$row->nama_satwa;
                $newRow=false;
            }else{
                $newRow=true;
            }            
        }
        if($newRow){*/
            $konten[$i][0] = $row->nama_lk;
            $konten[$i][1] = $row->nama_satwa;
            $konten[$i][2] = $row->jumlah_jantan;
            $konten[$i][3] = $row->jumlah_betina;
            $konten[$i][4] = $row->jumlah_unknown;
            $i++;
        //}        
    }
    $table=$this->createTable($judulTabel, $judulKolom, $konten);    
    echo $table;
}

/*
 * function utk bikin tabel ajax
 * judulTabel adalah string
 * judulKolom adalah array 1 dimensi
 * konten adalah array 2 dimensi
 */
private function createTable($judulTabel, $judulKolom, $konten){
    $kerangka = "<div class='row'>
        <div class='col-xs-12'>                
                <div class='clearfix'>
                        <div class='pull-right tableTools-container'></div>
                </div>
                <div class='table-header'>
                        $judulTabel
                </div>                
                <div>
                        <table id='dynamic-table' class='table table-striped table-bordered table-hover'>
                                <thead>
                                        <tr>";                                            
    foreach($judulKolom as $row){
        $kerangka .="<th>".$row."</th>";
    }
    $kerangka .="
                                        </tr>
                                </thead>
                                <tbody>";
    for($i=0;$i<count($konten);$i++){
        $kerangka .="<tr>";
        for($j=0;$j<count($konten[$i]);$j++){
            $kerangka .= "<td>".$konten[$i][$j]."</td>";
        }
        $kerangka .="</tr>";
    }         
    $kerangka .="
                                </tbody>
                        </table>
                </div>
        </div>
</div>";
    return $kerangka;
}

public function getSebaranSatwa(){
    
    $namaLatin = $this->input->post("lk", TRUE);
    $namaLatinText=($this->input->post("lktext", TRUE));
    $this->load->model("lk/vsatwa_per_prov");
    $values = $this->vsatwa_per_prov->getJumlahSatwa($namaLatin);

    $labelValues=array();
    $jumlahSatwa=0;
    foreach ($values as $row) {
        array_push($labelValues, 
            array(
                "id" => $row->id_map,
                "value" => $row->jml
            )
        ); 
        $jumlahSatwa += $row->jml;
    }
    $subCaption = "Terdapat $jumlahSatwa satwa tersebar";
    //$subCaption .= ($year=="")?"":" di Tahun $year";
    $chartConfig = array("chart"=>
        array( 
            "caption" => "Sebaran Satwa pada Lembaga Konservasi",                                
            "subcaption"=> $subCaption,
            "entityFillHoverColor"=> "#cccccc",
            "numberScaleValue"=> "1,1000,1000",
            "exportEnabled"=> "1",        
            "showLabels"=> "1",
            "useSNameInLabels"=>"1",
            "includeValueInLabels"=>"1",
            "theme"=> "ocean"
        ),
        "colorrange"=>
        array(
            "color"=>array(
                array(
                    "minvalue"=> "1",
                    "maxvalue"=> "3",
                    "displayvalue"=> "1 < 3",
                    "code"=> "#6daa01" 
                ),
                array(
                    "minvalue"=> "4",
                    "maxvalue"=> "6",
                    "displayvalue"=> "4 - 6",
                    "code"=> "#bcb50f"
                ),
                array(
                    "minvalue"=> "7",
                    "maxvalue"=> "9",
                    "displayvalue"=> "7 - 9",
                    "code"=> "#dab914"
                ),
                array(
                    "minvalue"=> "10",
                    "maxvalue"=>"12",
                    "displayvalue"=> "10 - 12",
                    "code"=> "#f3a013"
                ),
                array(
                    "minvalue"=> "13",
                    "maxvalue"=>"15",
                    "displayvalue"=>"13 - 15",
                    "code"=>"#ef890e"
                ),
                array(
                    "minvalue"=> "16",
                    "maxvalue"=> "18",
                    "displayvalue"=> "16 - 18",
                    "code"=> "#ea6e08"
                ),
                array(
                    "minvalue"=> "19",
                    "maxvalue"=> "21",
                    "displayvalue"=> "19 - 21",
                    "code"=> "#e55001"
                )
            )
        ),
        "data"=>
            $labelValues);
            $chartData = json_encode($chartConfig);
            echo($chartData);
}

public function getLahirMatiperSatwa(){
    $namaLatin = strtolower($this->input->post("lk", TRUE));
    $namaLatinText=($this->input->post("lktext", TRUE));
    $this->load->model("lk/vsatwa_per_prov");
    $values = $this->vsatwa_per_prov->cariLahirMatiSatwa($namaLatin);

    if(($namaLatin == "")||(strtolower($namaLatin)=="all")){
        $values = $this->vsatwa_per_prov->getSatwaLahirMati();
    }
    $labelLahirMati=array();
    $valueLahir=array();
    $valueMati=array();
    $jumlahLahir=0;
    $jumlahMati=0;
    $lkAnnual="";
    $modalAwalAnnual="";
    foreach($values as $row){
        array_push($labelLahirMati, 
            array(
                "label" => $row->nama_lk
            )
        ); 
        array_push($valueLahir, 
            array(
                "value" => $row->kelahiran
            )
        ); $jumlahLahir += $row->kelahiran;
        array_push($valueMati, 
            array(
                "value" => $row->kematian
            )
        ); $jumlahMati += $row->kematian;        
    
        $subCaption = (($namaLatin == "")||(strtolower($namaLatin)=="all"))?"":"terdapat $jumlahLahir ekor kelahiran dan $jumlahMati ekor kematian untuk $namaLatinText";
        
    }
    
    $chartConfig =array( "chart"=>
                    array( 
                        "caption" => "Kelahiran vs Kematian Satwa",                                
                        "yaxisname" => "Jumlah Kejadian",
                        "subcaption"=> $subCaption,
                        "showhovereffect"=> "1",    
                        "drawcrossline"=> "1",
                        //"plottooltext"=> '<b>$dataValue</b> telah diinvestasikan untuk $seriesName',
                        "labeldisplay"=>"auto",
                        "exportEnabled"=> "1",        
                        "decimalSeparator"=>',',
                        "thousandSeparator"=>'.',
                        "numberScaleValue"=> "1000,1000,1000",
                        "numberScaleUnit"=> "rb, jt, m",
                        "theme"=> "fusion",
                        "showvalues"=> "1"
                    ),
                    "categories"=>
                    array(
                                "category" => $labelLahirMati
                    ),
                    "dataset"=>
                    
                        array(
                            "seriesname" => "Kelahiran",
                            "data" => $valueLahir
                        ),
                        array(
                            "seriesname" => "Kematian",
                            "data" => $valueMati
                        )
                    

                );
    $chartData = json_encode($chartConfig);
    echo($chartData);
}

public function getAnnualInvest(){
    $kodeLK = strtolower($this->input->post("lk", TRUE));
    $lkText=($this->input->post("lktext", TRUE));
    $this->load->model("lk/investasi");
    $values = $this->investasi->annualReport($kodeLK);
    $labelAnnualLK_investasi=array();
    $valueAnnualPegawai=array();
    $valueAnnualSarana=array();
    $valueAnnualModalAwal=array();
    $lkAnnual="";
    $modalAwalAnnual="";
    foreach($values as $row){
        array_push($labelAnnualLK_investasi, 
            array(
                "label" => $row->tahun
            )
        ); 
        array_push($valueAnnualPegawai, 
            array(
                "value" => $row->investasi_pegawai
            )
        ); 
        array_push($valueAnnualSarana, 
            array(
                "value" => $row->investasi_sarana
            )
        ); 
        array_push($valueAnnualModalAwal, 
            array(
                "value" => $row->modal_awal
            )
        ); 
    
        $lkAnnual = $row->nama_lk;
        $modalAwalAnnual = $row->modal_awal;
        
    }
    $modalAwalAnnual = $this->getEmbel2($modalAwalAnnual);
    
    $chartConfig =array( "chart"=>
                    array( 
                        "caption" => "Rekapitulasi Investasi Tahunan Lembaga Konservasi",                                
                        "yaxisname" => "Besaran Investasi (Rupiah)",
                        "subcaption"=> "$lkAnnual dengan modal awal $modalAwalAnnual",
                        "showhovereffect"=> "1",    
                        "drawcrossline"=> "1",
                        "plottooltext"=> '<b>$dataValue</b> telah diinvestasikan untuk $seriesName',
                        "labeldisplay"=>"auto",
                        "exportEnabled"=> "1",        
                        "decimalSeparator"=>',',
                        "thousandSeparator"=>'.',
                        "numberScaleValue"=> "1000,1000,1000",
                        "numberScaleUnit"=> "rb, jt, m",
                        "theme"=> "fusion"
                    ),
                    "categories"=>
                    array(
                                "category" => $labelAnnualLK_investasi
                    ),
                    "dataset"=>
                    array(
                            "seriesname" => "Investasi Pegawai",
                            "data" => $valueAnnualPegawai
                        ),
                        array(
                            "seriesname" => "Investasi sarana",
                            "data" => $valueAnnualSarana
                        ),
                        array(
                            "seriesname" => "modal awal",
                            "data" => $valueAnnualModalAwal
                        )
                    

                );
    $chartData = json_encode($chartConfig);
    echo($chartData);
}
//--------------------------------------------- end of dashboard function ----------------------------------
//--------------------------------------------- custom function --------------------------------------------
private function gabungKalimat($kalimat,$chara){
    $temp = explode($chara,$kalimat);
    $result="";
    foreach ($temp as $word){
        $result .= $word;
    }
    return $result;
} 

public function resetPass($primary_key) {
    $pass = md5("123456".$primary_key);
    $this->load->model("member");
    $this->member->updatePassword($primary_key, $pass);
    $this->master_member_ctl();
    return $primary_key;
}

public function generateKey($post_array) {
    $this->load->helper('string');
    $val = random_string('alnum', 10);
    $post_array['keyPass']= $val;
    //echo $post_array['keyPass'];
    return $post_array;
}

public function generateMd5($post_array){
    $this->load->helper('string');
    if($post_array['password']=="") {
        $post_array['password']="123456";                
        $post_array['password'] = md5($post_array['password']);
    }else{
        $post_array['password'] = md5($post_array['password']);
    }
    
    return $post_array;
}

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
//--------------------------------------------- End of custom function -------------------------------------
//--------------------------------------------- callback function ------------------------------------------

public function pelepasliaran_unindef_callback($post_array){
    if($post_array['id_kawasan']!=""){
        $post_array['provinsi_id_prov'] = "";
        $post_array['kabupaten_id_kab'] = "";
        $post_array['kecamatan_id_kecamatan'] = "";
        $post_array['kelurahan_id_lurah'] = "";
    }

    $post_array['id_histori_lepas'] = $post_array['id_satwa_noniden']."-".$post_array['tgl_pelepasliaran'];
    $post_array['id_histori_lepas'] = str_replace("/","_",$post_array['id_histori_lepas']);
    $post_array['id_histori_lepas'] = str_replace(" ","-",$post_array['id_histori_lepas']);
    return $post_array;
}
public function tointeger_callback($post_array){
     $post_array['jumlah_jantan'] = (int)$post_array['jumlah_jantan'];
     $post_array['jumlah_betina'] = (int)$post_array['jumlah_betina'];
     $post_array['jumlah_unknown'] = (int)$post_array['jumlah_unknown'];
     return $post_array;
}
public function satwa_kelompok_callback($post_array){
    $post_array['id_satwa_noniden'] = $post_array['informasi_lk_umum_kode_lk']."-".$post_array['master_satwa_nama_latin'];
    $post_array['id_satwa_noniden'] = str_replace("/","_",$post_array['id_satwa_noniden']);
    $post_array['id_satwa_noniden'] = str_replace(" ","-",$post_array['id_satwa_noniden']);
    $post_array['jumlah_jantan'] = (int)$post_array['jumlah_jantan'];
    $post_array['jumlah_betina'] = (int)$post_array['jumlah_betina'];
    $post_array['jumlah_unknown'] = (int)$post_array['jumlah_unknown'];
    // $this->load->model("histori_perolehan_noniden");
    // $tgl_perolehan = date('Y-m-d');
    // $this->histori_perolehan_noniden->insertPerolehanSatwa($post_array['id_satwa_noniden'], $tgl_perolehan, "AWL", $post_array['informasi_lk_umum_kode_lk'], $post_array['informasi_lk_umum_kode_lk'], $post_array['jumlah_jantan'], $post_array['jumlah_betina'], $post_array['jumlah_unknown']);

    return $post_array;
}

public function updateJumlahSatwa($post_array, $id_satwa_noniden, $operator){
    $this->load->model("dinamika_satwa_noniden");
    $this->dinamika_satwa_noniden->updateJmlSatwa($id_satwa_noniden, 
                                                  $post_array['jumlah_jantan'], 
                                                  $post_array['jumlah_betina'], 
                                                  $post_array['jumlah_unknown'], $operator);    
    return $post_array;
}

public function perolehanUpdate_unindef_callback($post_array){
    $post_array['id_perolehan'] = $post_array['id_satwa_noniden']."-".$post_array['tgl_perolehan'];
    $post_array['id_perolehan'] = str_replace("/","_",$post_array['id_perolehan']);
    $post_array['id_perolehan'] = str_replace(" ","-",$post_array['id_perolehan']);
    $post_array['jumlah_jantan'] = (int)$post_array['jumlah_jantan'];
    $post_array['jumlah_betina'] = (int)$post_array['jumlah_betina'];
    $post_array['jumlah_unknown'] = (int)$post_array['jumlah_unknown'];
    return $post_array;
}

public function perolehan_unindef_callback($post_array){
    $post_array['id_perolehan'] = $post_array['id_satwa_noniden']."-".$post_array['tgl_perolehan'];
    $post_array['id_perolehan'] = str_replace("/","_",$post_array['id_perolehan']);
    $post_array['id_perolehan'] = str_replace(" ","-",$post_array['id_perolehan']);

    // $this->load->model("dinamika_satwa_noniden");
    // $temp = explode("-", $post_array['id_satwa_noniden']);
    // $namaLatin = "";
    // $namaLatinStrip="";
    // for($i=1;$i<count($temp);$i++){
    //     $namaLatin .= $temp[$i]." ";
    //     $namaLatinStrip.= $temp[$i]."-";
    // }
    // $namaLatin = $this->pembersih($namaLatin);
    // $namaLatinStrip = $this->pembersih($namaLatinStrip);
    // $temp2 = $this->dinamika_satwa_noniden->cekSatwaExist($post_array['lk_tujuan'], $namaLatin);
    // $id_satwa_noniden_tujuan = $post_array['lk_tujuan']."-".$namaLatinStrip;
    // $ada = "0";
    // foreach($temp2 as $row){
    //     $ada = $row->isExist;
    // }
    // if($ada=="0"){
    //     $this->dinamika_satwa_noniden->insertRow($post_array['lk_tujuan'], $namaLatin, $namaLatinStrip);
    // }
    // $this->updateJumlahSatwa($post_array, $id_satwa_noniden_tujuan, "+");
    // $this->updateJumlahSatwa($post_array, $post_array["id_satwa_noniden"], "-");
    return $post_array;
}
public function kematian_unindef_callback_update($post_array){

    $post_array['jumlah_jantan'] = (int)$post_array['jumlah_jantan'];
    $post_array['jumlah_betina'] = (int)$post_array['jumlah_betina'];
    $post_array['jumlah_unknown'] = (int)$post_array['jumlah_unknown'];
    return $post_array;
}
public function kematian_unindef_callback($post_array){
    $post_array['id_histori_mati'] = $post_array['id_satwa_noniden']."-".$post_array['tgl_kematian'];
    $post_array['id_histori_mati'] = str_replace("/","_",$post_array['id_histori_mati']);
    $post_array['id_histori_mati'] = str_replace(" ","-",$post_array['id_histori_mati']);
    $post_array['jumlah_jantan'] = (int)$post_array['jumlah_jantan'];
    $post_array['jumlah_betina'] = (int)$post_array['jumlah_betina'];
    $post_array['jumlah_unknown'] = (int)$post_array['jumlah_unknown'];
    return $post_array;
}

public function updateMatiSatwa($post_array){
    $this->load->model("dinamika_satwa_noniden");
    $this->load->model("lk/histori_kematian_noniden");

    $post_array = $this->kematian_unindef_callback($post_array);

    $default = $this->histori_kematian_noniden->getDefaultJumlah($post_array['id_histori_mati']);
    $default_jantan = 0;
    $default_betina = 0;
    $default_unknown = 0;
    foreach($default as $row){
        $default_jantan = $row->jumlah_jantan;
        $default_betina = $row->jumlah_betina;
        $default_unknown= $row->jumlah_unknown;
    }

    $this->dinamika_satwa_noniden->updateJmlSatwa($post_array['id_satwa_noniden'], 
                                                  $default_jantan,
                                                  $default_betina,
                                                  $default_unknown, "+");
    $this->kurangSatwaKelompok($post_array);    
    return $post_array;
}

public function deleteMatiSatwa($primary_key){
    $this->load->model("dinamika_satwa_noniden");
    $this->load->model("lk/histori_kematian_noniden");

    $default = $this->histori_kematian_noniden->getDefaultJumlah($primary_key);
    $default_jantan = 0;
    $default_betina = 0;
    $default_unknown = 0;
    $default_idSatwa="";
    foreach($default as $row){
        $default_jantan = $row->jumlah_jantan;
        $default_betina = $row->jumlah_betina;
        $default_unknown= $row->jumlah_unknown;
        $default_idSatwa= $row->id_satwa_noniden;
    }
    $this->dinamika_satwa_noniden->updateJmlSatwa($default_idSatwa, 
                                                  $default_jantan, 
                                                  $default_betina, 
                                                  $default_unknown, "+");    
    return true;
}

public function kelahiran_unindef_callback($post_array){
    
    $post_array['id_histori_lahir'] = $post_array['id_satwa_noniden']."-".$post_array['tgl_kelahiran'];
    $post_array['id_histori_lahir'] = str_replace("/","_",$post_array['id_histori_lahir']);
    $post_array['id_histori_lahir'] = str_replace(" ","-",$post_array['id_histori_lahir']);
    return $post_array;
}

public function tambahSatwaKelompok($post_array){
    $this->load->model("dinamika_satwa_noniden");
    $this->dinamika_satwa_noniden->updateJmlSatwa($post_array['id_satwa_noniden'], 
                                                  $post_array['jumlah_jantan'], 
                                                  $post_array['jumlah_betina'], 
                                                  $post_array['jumlah_unknown'], "+");    
    return $post_array;
}

public function kurangSatwaKelompok($post_array){
    $this->load->model("dinamika_satwa_noniden");
    $this->dinamika_satwa_noniden->updateJmlSatwa($post_array['id_satwa_noniden'], 
                                                  $post_array['jumlah_jantan'], 
                                                  $post_array['jumlah_betina'], 
                                                  $post_array['jumlah_unknown'], "-");    
    return $post_array;
}

public function deleteLahirSatwa($primary_key){
    $this->load->model("dinamika_satwa_noniden");
    $this->load->model("lk/histori_kelahiran_noniden");

    $default = $this->histori_kelahiran_noniden->getDefaultJumlah($primary_key);
    $default_jantan = 0;
    $default_betina = 0;
    $default_unknown = 0;
    $default_idSatwa="";
    foreach($default as $row){
        $default_jantan = $row->jumlah_jantan;
        $default_betina = $row->jumlah_betina;
        $default_unknown= $row->jumlah_unknown;
        $default_idSatwa= $row->id_satwa_noniden;
    }
    $this->dinamika_satwa_noniden->updateJmlSatwa($default_idSatwa, 
                                                  $default_jantan, 
                                                  $default_betina, 
                                                  $default_unknown, "-");    
    return true;
}

public function testQuery(){
    $this->load->model("lk/histori_kelahiran_noniden");
    $default = $this->histori_kelahiran_noniden->getDefaultJumlah("BIZO-Cacatua-sulphurea-20_02_2020");
    foreach($default as $row){
        $default_jantan = $row->jumlah_jantan;
        $default_betina = $row->jumlah_betina;
        $default_unknown= $row->jumlah_unknown;
    }
    echo "jantan = ".$default_jantan."/".$default_betina."/".$default_unknown;
}

public function updateLahirSatwa($post_array){
    $this->load->model("dinamika_satwa_noniden");
    $this->load->model("lk/histori_kelahiran_noniden");

    $post_array = $this->kelahiran_unindef_callback($post_array);

    $default = $this->histori_kelahiran_noniden->getDefaultJumlah($post_array['id_histori_lahir']);
    $default_jantan = 0;
    $default_betina = 0;
    $default_unknown = 0;
    foreach($default as $row){
        $default_jantan = $row->jumlah_jantan;
        $default_betina = $row->jumlah_betina;
        $default_unknown= $row->jumlah_unknown;
    }

    $this->dinamika_satwa_noniden->updateJmlSatwa($post_array['id_satwa_noniden'], 
                                                  $default_jantan,
                                                  $default_betina,
                                                  $default_unknown, "-");
    $this->tambahSatwaKelompok($post_array);    
    return $post_array;
}



function individu_satwa_callback($post_array){
    $post_array['id_individu_satwa'] = $post_array['informasi_lk_umum_id_lk']."-".$post_array['master_satwa_nama_latin']."-".$post_array['no_identifikasi'];  
    $post_array['id_individu_satwa'] = str_replace("/","_",$post_array['id_individu_satwa']);
    $post_array['id_individu_satwa'] = str_replace(" ","-",$post_array['id_individu_satwa']);
        
    // $tanggal_perolehan = date('Y-m-d');
    // $this->load->model("perolehan_satwa");
    // $this->perolehan_satwa->insertPerolehanSatwa($post_array['id_individu_satwa'], "AWL", $tanggal_perolehan, $post_array['informasi_lk_umum_id_lk']);
    return $post_array;
}

function investasi_callback($post_array) {
    /*$this->load->library('encrypt');
    $key = 'super-secret-key';
    $post_array['password'] = $this->encrypt->encode($post_array['password'], $key);*/
    $post_array['modal_awal'] = $this->gabungKalimat($post_array['modal_awal'],",");
    $post_array['pegawai'] = $this->gabungKalimat($post_array['pegawai'],",");
    $post_array['sarana'] = $this->gabungKalimat($post_array['sarana'],",");
    //if(strlen($post_array['id_investasi'])<1){
        $post_array['id_investasi'] = $post_array['informasi_lk_umum_kode_lk']."-".$post_array['tanggal_update'];  
        $post_array['id_investasi'] = str_replace("/","_",$post_array['id_investasi']);
    return $post_array;
}   

public function perolehan_satwa_callback($post_array){
    $this->load->model("data_individu_satwa_new");//$idIndividu, $idLKTujuan
    $this->data_individu_satwa->updateLKSatwa($post_array['id_individu_satwa'], 
                                              $post_array['lk_tujuan_informasi_lk_umum_kode_lk']);
    //if(strlen($post_array['id_perolehan'])<1){
        $post_array['id_perolehan'] = $post_array['id_individu_satwa']."-".$post_array['tanggal_perolehan'];
        $post_array['id_perolehan'] = str_replace("/","_",$post_array['id_perolehan']);
        $post_array['id_perolehan'] = str_replace(" ","-",$post_array['id_perolehan']);
    //}
    return $post_array;
}

public function pelepasliaran_callback($post_array){
    $this->load->model("data_individu_satwa_new");//$idIndividu, $idLKTujuan
    $this->data_individu_satwa->updateLKSatwa($post_array['data_individu_satwa_master_satwa_nama_latin'], 
                                              "LEPAS");
    
    if($post_array['id_kawasan']!=""){
        $post_array['provinsi_id_prov'] = "";
        $post_array['kabupaten_id_kab'] = "";
        $post_array['kecamatan_id_kecamatan'] = "";
        $post_array['kelurahan_id_lurah'] = "";
    }
                                              
    $post_array['id_pelepasliaran'] = $post_array['data_individu_satwa_master_satwa_nama_latin']."-".$post_array['tanggal_pelepasliaran'];
    $post_array['id_pelepasliaran'] = str_replace("/","_",$post_array['id_pelepasliaran']);
    $post_array['id_pelepasliaran'] = str_replace(" ","-",$post_array['id_pelepasliaran']);
    return $post_array;
}

public function currencyFormat($value, $row=null){    
    $result = number_format($value,2,",",".");
    return $result;
}  
//--------------------------------------------- End of callback function -----------------------------------
//--------------------------------------------- Transactional Function -------------------------------------
        public function monitoring_pelepasliaran_unindef(){
            
            $req["judul"]="Monitoring pelepasliaran Satwa Berkelompok";
            $req["url"]=site_url('lemkon/pelepasliaran_unindef_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function pelepasliaran_unindef_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('histori_pelepasliaran_noniden');             
               
                $this->load->library('gc_dependent_select');

                if($this->hakAkses=="user"){
                    $crud->field_type('informasi_lk_umum_id_lk', 'hidden', $this->id_lk);
                    $crud->where("informasi_lk_umum_id_lk", $this->id_lk);
                    $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","{master_satwa_nama_latin}", array('informasi_lk_umum_kode_lk' => $this->id_lk));
                }else{
                    $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","master_satwa_nama_latin");
                    $crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");

                    $fields2 = array(
                        'informasi_lk_umum_id_lk' => array(// first dropdown name
                        'table_name' => 'informasi_lk_umum', // table of country
                        'title' => 'nama_lk', // country title
                        'relate' => null // the first dropdown hasn't a relation
                        //'data-placeholder' => 'Pilih LK'
                        ),
                        'id_satwa_noniden' => array(// second dropdown name
                        'table_name' => 'dinamika_satwa_noniden', // table of state
                        'title' => 'master_satwa_nama_latin', // state title                    
                        'id_field' => 'id_satwa_noniden', // table of state: primary key
                        'relate' => 'informasi_lk_umum_kode_lk', // table of state:
                        'data-placeholder' => 'Pilih Jenis Satwa' //dropdown's data-placeholder:
                        )
                    );
                    $config2 = array(
                        'main_table' => 'histori_pelepasliaran_noniden',
                        'main_table_primary' => 'id_histori_lepas',
                        "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                        'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                    );
                    $categories2 = new gc_dependent_select($crud, $fields2, $config2);
                    $js2 = $categories2->get_js();

                }
                 $crud->callback_before_insert(array($this,'tointeger_callback'));
                 $crud->callback_before_update(array($this,'tointeger_callback'));
                //warning kasih session juga
                //$crud->set_relation("");
                $crud->set_relation("id_kawasan","master_kawasan","nama_kawasan");

                $crud->display_as("id_satwa_noniden","Jenis Satwa")
                ->display_as("id_kawasan","Kawasan")
                ->display_as("informasi_lk_umum_id_lk", "Nama LK")
                ;

                $crud->field_type('id_histori_lepas', 'hidden');

                $crud->columns('id_satwa_noniden',
                               'tgl_pelepasliaran',
                               'jumlah_jantan',
                               'jumlah_betina',
                               'jumlah_unknown'
                );

              
                
                

                $fields = array(
                        'provinsi_id_prov' => array(// first dropdown name
                        'table_name' => 'provinsi', // table of country
                        'title' => 'nama_prov', // country title
                        'relate' => null // the first dropdown hasn't a relation
                        //'data-placeholder' => 'Pilih Provinsi'
                        ),
                        'kabupaten_id_kab' => array(// second dropdown name
                        'table_name' => 'kabupaten', // table of state
                        'title' => 'nama_kab', // state title
                        'id_field' => 'id_kab', // table of state: primary key
                        'relate' => 'provinsi_id_prov', // table of state:
                        'data-placeholder' => 'Pilih Kabupaten/Kota' //dropdown's data-placeholder:
                        ),                        
                        'kecamatan_id_kecamatan' => array(// second dropdown name
                        'table_name' => 'kecamatan', // table of state
                        'title' => 'nama_kecamatan', // state title
                        'id_field' => 'id_kecamatan', // table of state: primary key
                        'relate' => 'kabupaten_id_kab', // table of state:
                        'data-placeholder' => 'Pilih Kecamatan' //dropdown's data-placeholder:
                        ),                    
                        'kelurahan_id_lurah' => array(// second dropdown name
                        'table_name' => 'kelurahan', // table of state
                        'title' => 'nama_lurah', // state title
                        'id_field' => 'id_lurah', // table of state: primary key
                        'relate' => 'kecamatan_id_kecamatan', // table of state:
                        'data-placeholder' => 'Pilih Kelurahan/Desa' //dropdown's data-placeholder:
                        )                        
                    );
                
                $config = array(
                            'main_table' => 'histori_pelepasliaran_noniden',
                            'main_table_primary' => 'id_histori_lepas',
                            "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                            'segment_name' => "loc",
                            'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                        );
                
                        $categories = new gc_dependent_select($crud, $fields, $config);
                        $js = $categories->get_js();
                    
                    $output = $crud->render();
                    //$output->output.= $js2;
                    $output->output.= $js;
                    if(isset($js2)){
                        $output->output.= $js2;
                    }
                    $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_perolehan_unindef(){
            
            $req["judul"]="Monitoring Perolehan Satwa Berkelompok";
            $req["url"]=site_url('lemkon/perolehan_unindef_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function perolehan_unindef_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('histori_perolehan_noniden');       
                
                //warning kasih session juga
                $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","master_satwa_nama_latin");
                $crud->set_relation("cara_perolehan","master_perolehan","cara_perolehan");
                $crud->set_relation("lk_tujuan","informasi_lk_umum","nama_lk");
               
          
                $crud->display_as("id_satwa_noniden","Jenis Satwa");

                $crud->field_type('id_perolehan', 'hidden');

                $crud->unset_columns('id_perolehan');

                $crud->callback_before_insert(array($this,'perolehanUpdate_unindef_callback'));
                $crud->callback_before_update(array($this,'kematian_unindef_callback_update'));
 
                if($this->hakAkses=="user"){
                    $crud->field_type('lk_asal', 'hidden', $this->id_lk);
                    $crud->where("lk_asal", $this->id_lk);
                    $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","{master_satwa_nama_latin}", array('informasi_lk_umum_kode_lk' => $this->id_lk));
                    $output = $crud->render();                        
                    $this->displayCRUD($output);
                }else{
                     $crud->set_relation("lk_asal","informasi_lk_umum","nama_lk");
                    $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","{master_satwa_nama_latin} - {informasi_lk_umum_kode_lk}");
                    $this->load->library('gc_dependent_select');
                
                    $fields = array(
                            'lk_asal' => array(// first dropdown name
                            'table_name' => 'informasi_lk_umum', // table of country
                            'title' => 'nama_lk', // country title
                            'relate' => null, // the first dropdown hasn't a relation
                            'data-placeholder' => 'Pilih LK asal'
                            ),
                            'id_satwa_noniden' => array(// second dropdown name
                            'table_name' => 'dinamika_satwa_noniden', // table of state
                            'title' => 'master_satwa_nama_latin', // state title
                       
                            //'title' => 'concat(nama_panggilan_satwa, " - ", informasi_lk_umum_id_lk, " / ", master_satwa_nama_latin) as no_identifikasi', // state title
                            
                            'id_field' => 'id_satwa_noniden', // table of state: primary key
                            'relate' => 'informasi_lk_umum_kode_lk', // table of state:
                            'data-placeholder' => 'Pilih Jenis Satwa' //dropdown's data-placeholder:
                            )
                        );
                    
                    $config = array(
                                'main_table' => 'histori_perolehan_noniden',
                                'main_table_primary' => 'id_perolehan',
                                "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                                'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                            );
                    $categories = new gc_dependent_select($crud, $fields, $config);
                    $js = $categories->get_js();
                    
                    $output = $crud->render();        
                    $output->output.= $js;
                    $this->displayCRUD($output);
                }

                
                
                
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_kematian_unindef(){
            
            $req["judul"]="Monitoring Kematian Satwa Berkelompok";
            $req["url"]=site_url('lemkon/kematian_unindef_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }        

        public function cekJmlKematianJantan($value){
            if ($value == '1')
            {

                    //$this->form_validation->set_message('username_check', 'The {field} field can not be the word "test"');
                    return FALSE;
            }
            else
            {
                    return TRUE;
            }
    
        }

        public function kematian_unindef_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('histori_kematian_noniden');             

                //$crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","{master_satwa_nama_latin} - {informasi_lk_umum_kode_lk}");
                $crud->set_relation("sebab_kematian","master_sebab_mati","nama_sebab");

                $crud->display_as("id_satwa_noniden","Jenis Satwa");
                $crud->display_as("nekropsi","Laporan Hasil Nekropsi");
                // $crud->display_as("informasi_lk_umum_id_lk","Nama LK");

                $crud->set_field_upload('nekropsi','assets/uploads/lk/nekropsi');

                $crud->field_type('id_histori_mati', 'hidden');

                $crud->unset_columns('id_histori_mati');

                //$crud->set_rules('jumlah_jantan','Jumlah Jantan (Jumlah yg mati terlalu banyak)','callback_cekJmlKematianJantan');

                $crud->callback_before_insert(array($this,'kematian_unindef_callback'));
                $crud->callback_before_update(array($this,'kematian_unindef_callback_update'));
                // $crud->callback_before_update(array($this,'updateMatiSatwa'));
                // $crud->callback_after_insert(array($this,'kurangSatwaKelompok'));
                // $crud->callback_before_delete(array($this,'deleteMatiSatwa'));
                
                /*
                $crud->callback_before_insert(array($this,'kelahiran_unindef_callback'));
                $crud->callback_before_update(array($this,'updateLahirSatwa'));
                $crud->callback_after_insert(array($this,'tambahSatwaKelompok'));
                $crud->callback_before_delete(array($this,'deleteLahirSatwa'));
                 */
                //warning kasih session juga
                if($this->hakAkses=="user"){
                    $crud->field_type('informasi_lk_umum_id_lk', 'hidden', $this->id_lk);
                    $crud->where("informasi_lk_umum_id_lk", $this->id_lk);
                    $crud->field_type('id_histori_mati', 'hidden', $this->id_lk);
                    $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","{master_satwa_nama_latin}", array('informasi_lk_umum_kode_lk' => $this->id_lk));
                    $output = $crud->render();                        
                    $this->displayCRUD($output);
                }else{
                    $crud->set_relation("id_satwa_noniden","dinamika_satwa_noniden","{master_satwa_nama_latin} - {informasi_lk_umum_kode_lk}");
                    $crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");
                    $this->load->library('gc_dependent_select');
                
                    $fields = array(
                            'informasi_lk_umum_id_lk' => array(// first dropdown name
                            'table_name' => 'informasi_lk_umum', // table of country
                            'title' => 'nama_lk', // country title
                            'relate' => null, // the first dropdown hasn't a relation
                            'data-placeholder' => 'Pilih LK'
                            ),
                            'id_satwa_noniden' => array(// second dropdown name
                            'table_name' => 'dinamika_satwa_noniden', // table of state
                            'title' => 'master_satwa_nama_latin', // state title
                            //'title' => 'concat(nama_panggilan_satwa, " - ", informasi_lk_umum_id_lk, " / ", master_satwa_nama_latin) as no_identifikasi', // state title
                            
                            'id_field' => 'id_satwa_noniden', // table of state: primary key
                            'relate' => 'informasi_lk_umum_kode_lk', // table of state:
                            'data-placeholder' => 'Pilih Jenis Satwa' //dropdown's data-placeholder:
                            )
                        );
                    
                    $config = array(
                                'main_table' => 'histori_kematian_noniden',
                                'main_table_primary' => 'id_histori_mati',
                                "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                                'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                            );
                    $categories = new gc_dependent_select($crud, $fields, $config);
                    $js = $categories->get_js();
                    
                    $output = $crud->render();        
                    $output->output.= $js;
                    $this->displayCRUD($output);
                }

                
                

                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_kelahiran_unindef(){
            
            $req["judul"]="Monitoring Kelahiran Satwa Berkelompok";
            $req["url"]=site_url('lemkon/kelahiran_unindef_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function kelahiran_unindef_ctl(){            
            try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('dinamika_satwa_noniden');
                $crud->where('tgl_kelahiran !=',NULL);
                $crud->field_type('id_satwa_noniden', 'hidden');

                //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk");
                $crud->set_relation("master_taksa_id_taksa","master_taksa","nama_taksa");
                $crud->set_relation("master_status_konservasi_satwa_id_status","master_status_konservasi_satwa","nama_status");
                $crud->set_relation("master_satwa_nama_latin","master_satwa","{jenis_satwa} - {nama_latin}");
                //$crud->set_relation("non_lk_nama_instansi","non_lk","nama_instansi");
                //$crud->set_relation("lk_asal","informasi_lk_umum","nama_lk");
                $state = $crud->getState();
                if($state == 'edit')
                {
                    $crud->field_type('informasi_lk_umum_kode_lk', 'readonly');
                    $crud->field_type('master_satwa_nama_latin', 'readonly');
                    $crud->field_type('jumlah_jantan', 'readonly');
                    $crud->field_type('jumlah_betina', 'readonly');
                    $crud->field_type('jumlah_unknown', 'readonly');
                }
                
                $crud->unset_columns('id_satwa_noniden');
             
                $crud->callback_before_insert(array($this,'satwa_kelompok_callback'));
                // $crud->callback_before_insert(array($this,'tointeger_callback'));
                //$crud->callback_before_update(array($this,'satwa_kelompok_callback'));

                $crud->display_as("informasi_lk_umum_kode_lk","Nama LK")
                     ->display_as("master_taksa_id_taksa","Nama Taksa")
                     ->display_as("master_status_konservasi_satwa_id_status","Status Perlindungan")
                     ->display_as("master_satwa_nama_latin","Jenis Satwa")
                    // ->display_as("non_lk_nama_instansi","Nama Instansi")
                ;

                if($this->hakAkses=="user"){               
                    //$crud->unset_fields("id_lk");
                    $crud->field_type('informasi_lk_umum_kode_lk', 'hidden', $this->id_lk);
                    $crud->where("informasi_lk_umum_kode_lk", $this->id_lk);
                    //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk", "");
                }
                else{
                    $crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk");
                }
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_pelepasliaran(){
            
            $req["judul"]="Pelepasliaran Satwa";
            $req["url"]=site_url('lemkon/pelepasliaran_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function pelepasliaran_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('pelepasan_satwa_new');
                
                //warningg tambahin where dr LK session
                
                $crud->set_relation("id_kawasan","master_kawasan","nama_kawasan");
                $crud->set_relation("master_satwa_nama_latin","data_individu_satwa_new","master_satwa_nama_latin");  
                $crud->set_relation("no_identifikasi","data_individu_satwa_new","no_identifikasi");  
                $crud->field_type('id_pelepasan', 'hidden');

                
                $crud->set_field_upload('upload_sk_pelepasan','assets/uploads/lk/fulepas');
                $crud->display_as("id_kawasan","Kawasan");
                if($this->hakAkses=="user"){               
                    //$crud->unset_fields("id_lk");
                   $crud->set_relation("lk_asal_informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk",array('id_lk' => $this->id_lk));
                   $crud->where("lk_asal_informasi_lk_umum_kode_lk", $this->id_lk);     
                   $crud->unset_edit();                            
                    //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk", "");
                }
                else{
                    $crud->set_relation("lk_asal_informasi_lk_umum_kode_lk", "informasi_lk_umum", "nama_lk");
                    //$crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");                    
                }

             
                 $crud->display_as("lk_asal_informasi_lk_umum_kode_lk","LK Asal");
                $this->load->library('gc_dependent_select');
                
                $fields = array(
                    'lk_asal_informasi_lk_umum_kode_lk' => array(// first dropdown name
                    'table_name' => 'informasi_lk_umum', // table of country
                    'title' => 'nama_lk', // country title
                    'relate' => null, // the first dropdown hasn't a relation
                    ),
                    'master_satwa_nama_latin' => array(// second dropdown name
                        'table_name' => 'data_individu_satwa_new', // table of state
                        'title'=>'master_satwa_nama_latin',
                        'id_field' => 'id_individu_satwa', // table of state: primary key
                        'relate' => 'informasi_lk_umum_id_lk', // table of state:
                        'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                    ),
                    'no_identifikasi' => array(// second dropdown name
                        'table_name' => 'data_individu_satwa_new', // table of state
                        'title'=>'no_identifikasi',
                        'id_field' => 'id_individu_satwa', // table of state: primary key
                        'relate' => 'id_individu_satwa', // table of state:
                        'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                    )
                );
                
                $config = array(
                            'main_table' => 'pelepasliaran',
                            'main_table_primary' => 'id_pelepasliaran',
                            "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                            'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                        );
                $categories = new gc_dependent_select($crud, $fields, $config);
                $js = $categories->get_js();
                
                $output = $crud->render();        
                $output->output.= $js;
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_perolehan_satwa(){
            
            $req["judul"]="Perolehan Satwa";
            $req["url"]=site_url('lemkon/perolehan_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function perolehan_satwa_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('perolehan_satwa_new');                

                $crud->set_field_upload('upload_sat_dn','assets/uploads/lk/satdn');
                $crud->set_field_upload('upload_sk_peroleh','assets/uploads/lk/skperolehan');
                     
                
                //WARNING harus pakai session asal LK
                
                $crud->set_relation("lk_tujuan_informasi_lk_umum_kode_lk", "informasi_lk_umum", "nama_lk");
                $crud->set_relation("master_perolehan_id_perolehan","master_perolehan","cara_perolehan");                                  
                $crud->set_relation("master_satwa_nama_latin","data_individu_satwa_new","master_satwa_nama_latin");  
                $crud->unset_columns("id_perolehan");
                $crud->field_type('id_perolehan', 'hidden');

                $crud->display_as("lk_asal_informasi_lk_umum_kode_lk","LK Asal")
                ->display_as("lk_tujuan_informasi_lk_umum_kode_lk","LK Tujuan")
                ->display_as("master_perolehan_id_perolehan","Cara Perolehan")    
                ->display_as("master_satwa_nama_latin","Jenis Satwa") 
                ->display_as("nomor_stad_din","Nomor SATS-DN")    
                ->display_as("tanggal_sat_dn","Tanggal SATS-DN")
                ->display_as("upload_sat_dn","Upload SATS-DN")                                
                ;
                             
                if($this->hakAkses=="user"){                    
                    $crud->display_as("no_identifikasi","Nama satwa");        
                    $crud->set_relation("lk_asal_informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk",array('id_lk' => $this->id_lk));       
      
                    $crud->set_relation("no_identifikasi","data_individu_satwa_new","no_identifikasi", array('informasi_lk_umum_id_lk' => $this->id_lk)); 
                    $crud->unset_edit();                
                    $this->load->library('gc_dependent_select');
                    
                    $fields = array(
                            'lk_asal_informasi_lk_umum_kode_lk' => array(// first dropdown name
                            'table_name' => 'informasi_lk_umum', // table of country
                            'title' => 'nama_lk', // country title
                            'relate' => null, // the first dropdown hasn't a relation
                            ),
                            'master_satwa_nama_latin' => array(// second dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of state
                                'title'=>'master_satwa_nama_latin',
                                'id_field' => 'id_individu_satwa', // table of state: primary key
                                'relate' => 'informasi_lk_umum_id_lk', // table of state:
                                'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                            ),
                            'no_identifikasi' => array(// second dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of state
                                'title'=>'no_identifikasi',
                                'id_field' => 'id_individu_satwa', // table of state: primary key
                                'relate' => 'id_individu_satwa', // table of state:
                                'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                            )
                        );
                    
                    $config = array(
                                'main_table' => 'perolehan_satwa_new',
                                'main_table_primary' => 'id_perolehan',
                                "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                                'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                            );
                    $categories = new gc_dependent_select($crud, $fields, $config);
                    $js = $categories->get_js();
                    
                    $output = $crud->render();        
                    $output->output.= $js;
                    $this->displayCRUD($output);     
                }
                else{
                    $crud->display_as("no_identifikasi","No identifikasi satwa");
                    $crud->set_relation("no_identifikasi","data_individu_satwa_new","no_identifikasi");                 
                    $crud->set_relation("lk_asal_informasi_lk_umum_kode_lk", "informasi_lk_umum", "nama_lk");
                    $this->load->library('gc_dependent_select');
                
                    $fields = array(
                            'lk_asal_informasi_lk_umum_kode_lk' => array(// first dropdown name
                            'table_name' => 'informasi_lk_umum', // table of country
                            'title' => 'nama_lk', // country title
                            'relate' => null, // the first dropdown hasn't a relation
                            ),
                            'master_satwa_nama_latin' => array(// second dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of state
                                'title'=>'master_satwa_nama_latin',
                                'id_field' => 'id_individu_satwa', // table of state: primary key
                                'relate' => 'informasi_lk_umum_id_lk', // table of state:
                                'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                            ),
                            'no_identifikasi' => array(// second dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of state
                                'title'=>'no_identifikasi',
                                'id_field' => 'id_individu_satwa', // table of state: primary key
                                'relate' => 'id_individu_satwa', // table of state:
                                'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                            )
                        );
                    
                    $config = array(
                                'main_table' => 'perolehan_satwa_new',
                                'main_table_primary' => 'id_perolehan',
                                "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                                'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                            );
                    $categories = new gc_dependent_select($crud, $fields, $config);
                    $js = $categories->get_js();
                    
                    $output = $crud->render();        
                    $output->output.= $js;
                    $this->displayCRUD($output);
                }

                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }
        public function monitoring_kematian_satwa(){

            $req["judul"]="Kematian Satwa";
            $req["url"]=site_url('lemkon/kematian_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }
        function id_kematian_satwa_callback($post_array){
            $post_array['id_kematian_satwa'] = $post_array['informasi_lk_umum']."-".$post_array['master_satwa_nama_latin']."-".$post_array['no_identifikasi'];  
            $post_array['id_kematian_satwa'] = str_replace("/","_",$post_array['id_kematian_satwa']);
            $post_array['id_kematian_satwa'] = str_replace(" ","-",$post_array['id_kematian_satwa']);
                

            return $post_array;
        }
        public function kematian_satwa_ctl(){
            
                try{
                
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('data_kematian_satwa_new');
                $crud->set_field_upload('upload_bap','assets/uploads/lk/bapmati');
                 
                $crud->set_relation("master_satwa_nama_latin","data_individu_satwa_new","master_satwa_nama_latin");   
                  
                $crud->set_relation("master_jenis_kematian_id_jenis_kematian","master_sebab_mati","nama_sebab");                
                // $crud->set_relation("no_identifikasi_kematian","data_individu_satwa_new","no_identifikasi");  
                $crud->columns('informasi_lk_umum_id_lk',
                'master_satwa_nama_latin',
                'no_identifikasi_kematian',
                'tanggal_kematian',
                'master_jenis_kematian_id_jenis_kematian',
                'upload_bap'  );
                $this->load->library('gc_dependent_select');
                if($this->hakAkses=="user"){   
                         $crud->unset_edit();
                       $crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk",array('id_lk' => $this->id_lk));
                       $crud->where("data_kematian_satwa_new.informasi_lk_umum_id_lk", $this->id_lk);
                       $state = $crud->getState();
                         $fields = array(
                                'informasi_lk_umum_id_lk' => array(// first dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of country
                                'title' => 'master_satwa_nama_latin', // country title
                                'relate' => null, // the first dropdown hasn't a relation
                                ),
                                'master_satwa_nama_latin' => array(// first dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of country
                                'title' => 'id_individu_satwa', // country title
                                'id_field' => 'master_satwa_nama_latin', // table of state: primary key
                                'relate' => 'informasi_lk_umum_id_lk', // the first dropdown hasn't a relation
                                'data-placeholder' => 'Pilih Jenis Satwa'
                                ),
                                'no_identifikasi_kematian' => array(// second dropdown name
                                'table_name' => 'data_individu_satwa_new', // table of state
                                'title'=>'no_identifikasi',
                                'id_field' => 'id_individu_satwa', // table of state: primary key
                                'relate' => 'id_individu_satwa', // table of state:
                                'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                                )
                        );
                        
                      
                }
                else{
                    $crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");
                    $fields = array(
                            'informasi_lk_umum_id_lk' => array(// first dropdown name
                            'table_name' => 'data_individu_satwa_new', // table of country
                            'title' => 'master_satwa_nama_latin', // country title
                            'relate' => null, // the first dropdown hasn't a relation
                            ),
                            'master_satwa_nama_latin' => array(// first dropdown name
                            'table_name' => 'data_individu_satwa_new', // table of country
                            'title' => 'master_satwa_nama_latin', // country title
                            'id_field' => 'id_individu_satwa', // table of state: primary key
                            'relate' => 'informasi_lk_umum_id_lk', // the first dropdown hasn't a relation
                            'data-placeholder' => 'Pilih Jenis Satwa'
                            ),
                            'no_identifikasi_kematian' => array(// second dropdown name
                            'table_name' => 'data_individu_satwa_new', // table of state
                            'title'=>'no_identifikasi',
                            'id_field' => 'id_individu_satwa', // table of state: primary key
                            'relate' => 'id_individu_satwa', // table of state:
                            'data-placeholder' => 'Pilih no identifikasi satwa' //dropdown's data-placeholder:
                            )
                    );
                                    
                }
                $crud->field_type('id_kematian_satwa', 'hidden');
                $crud->display_as("informasi_lk_umum_id_lk","Nama LK")                
                ->display_as("master_satwa_nama_latin","Jenis Satwa")     
                ->display_as("master_jenis_kematian_id_jenis_kematian","Penyebab Kematian")             
                ;
                $crud->callback_before_insert(array($this,'id_kematian_satwa_callback'));
               
                $config = array(
                    'main_table' => 'data_kematian_satwa_new',
                    'main_table_primary' => 'id_kematian_satwa',
                    "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                    'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                );
                $categories = new gc_dependent_select($crud, $fields, $config);
                $js = $categories->get_js();
                $output = $crud->render();        
                $output->output.= $js;
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_kelahiran_satwa(){
            
            $req["judul"]="Kelahiran Satwa";
            $req["url"]=site_url('lemkon/kelahiran_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function kelahiran_satwa_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('data_individu_satwa_new');
                $crud->set_relation("master_status_hukum_satwa_id_status","master_status_hukum_satwa","nama_status");
                 $crud->callback_before_insert(array($this,'individu_satwa_callback'));
                 $crud->unset_fields('master_status_konservasi_satwa_id_status',
                                'kondisi_satwa',
                                'master_jenis_kelamin_id_jenis_kelamin',
                                 'nomer_sk',
                                 'pdf_upload_sk',
                                 'file_asal_usul_bap'
                );
                $crud->unset_columns('master_status_konservasi_satwa_id_status',
                            'kondisi_satwa',
                            'master_jenis_kelamin_id_jenis_kelamin',
                             'nomer_sk',
                              'pdf_upload_sk',
                               'file_asal_usul_bap'
                );
                // $crud->callback_before_insert(array($this,'individu_satwa_callback'));
                // $crud->fields('informasi_lk_umum_id_lk',
                //                     'master_satwa_nama_latin',
                //                     'no_identifikasi',
                //                     'nama_panggilan_satwa',
                //                     'master_status_hukum_satwa_id_status',
                //                     'tanggal_lahir',
                //                     'nama_induk_jantan',
                //                     'nama_induk_betina',
                //                     'upload_bap');

                // $crud->columns('informasi_lk_umum_id_lk',
                // 'master_satwa_nama_latin',
                // 'master_status_hukum_satwa_id_status',
                // 'nama_panggilan_satwa',
                // 'no_identifikasi',
                // 'tanggal_lahir',
                // 'nama_induk_jantan',
                // 'nama_induk_betina',
                // 'upload_bap');

                if($this->hakAkses=="user"){               
                    //$crud->unset_fields("id_lk");
                    $crud->field_type('informasi_lk_umum_id_lk', 'hidden', $this->id_lk);
                    $crud->where("data_individu_satwa_new.informasi_lk_umum_id_lk", $this->id_lk);
                 

                    //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk", "");
                }
                else{
                    $crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");
                    $crud->set_relation("nama_induk_jantan","data_individu_satwa_new","{nama_panggilan_satwa} - {informasi_lk_umum_id_lk}",array('master_jenis_kelamin_id_jenis_kelamin' => 'J'), 'nama_panggilan_satwa ASC');
                    $crud->set_relation("nama_induk_betina","data_individu_satwa_new","{nama_panggilan_satwa} - {informasi_lk_umum_id_lk}",array('master_jenis_kelamin_id_jenis_kelamin' => 'B'), 'nama_panggilan_satwa ASC');    
                }
                 $crud->field_type('id_individu_satwa', 'hidden');

                //$crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");                
                $crud->set_relation("master_satwa_nama_latin","master_satwa","{jenis_satwa} - {nama_latin}");                                
                $crud->set_rules('no_identifikasi','No Identifikasi','required');
                $crud->display_as("informasi_lk_umum_id_lk","Kode LK")                
                ->display_as("master_satwa_nama_latin","Jenis Satwa")  
                ->display_as("master_status_hukum_satwa_id_status","Status Hukum Satwa")              
                ;

                $crud->set_field_upload('upload_bap','assets/uploads/lk/baplahir');
                // $crud->set_field_upload('upload_foto_utuh','assets/uploads/lk/fulahir');
                // $crud->set_field_upload('upload_foto_spesifik','assets/uploads/lk/fslahir');

                //$crud->unset_add();

                // $crud->change_field_type('informasi_lk_umum_id_lk', 'readonly');
                // $crud->change_field_type('master_satwa_nama_latin', 'readonly');
                // $crud->change_field_type('nama_panggilan_satwa', 'readonly');
                
                $output = $crud->render();                        
                $this->displayCRUD($output);

                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_satwa_kelompok(){
            
            $req["judul"]="Dinamika Satwa Berkelompok (non-Identifikasi)";
            $req["url"]=site_url('lemkon/satwa_kelompok_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function satwa_kelompok_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('dinamika_satwa_noniden');

                $crud->field_type('id_satwa_noniden', 'hidden');

                //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk");
                $crud->set_relation("master_taksa_id_taksa","master_taksa","nama_taksa");
                $crud->set_relation("master_status_konservasi_satwa_id_status","master_status_konservasi_satwa","nama_status");
                $crud->set_relation("master_satwa_nama_latin","master_satwa","{jenis_satwa} - {nama_latin}");
                //$crud->set_relation("non_lk_nama_instansi","non_lk","nama_instansi");
                //$crud->set_relation("lk_asal","informasi_lk_umum","nama_lk");
                $state = $crud->getState();
                if($state == 'edit')
                {
                    $crud->field_type('informasi_lk_umum_kode_lk', 'readonly');
                    $crud->field_type('master_satwa_nama_latin', 'readonly');
                    $crud->field_type('jumlah_jantan', 'readonly');
                    $crud->field_type('jumlah_betina', 'readonly');
                    $crud->field_type('jumlah_unknown', 'readonly');
                }
                
                //$crud->unset_columns('id_satwa_noniden');
                $crud->unset_fields('tgl_kelahiran');
                $crud->callback_before_insert(array($this,'satwa_kelompok_callback'));
                // $crud->callback_before_insert(array($this,'tointeger_callback'));
                //$crud->callback_before_update(array($this,'satwa_kelompok_callback'));

                $crud->display_as("informasi_lk_umum_kode_lk","Nama LK")
                     ->display_as("master_taksa_id_taksa","Nama Taksa")
                     ->display_as("master_status_konservasi_satwa_id_status","Status Perlindungan")
                     ->display_as("master_satwa_nama_latin","Jenis Satwa")
                    // ->display_as("non_lk_nama_instansi","Nama Instansi")
                ;

                if($this->hakAkses=="user"){               
                    //$crud->unset_fields("id_lk");
                    $crud->field_type('informasi_lk_umum_kode_lk', 'hidden', $this->id_lk);
                    $crud->where("informasi_lk_umum_kode_lk", $this->id_lk);
                    //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk", "");
                }
                else{
                    $crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk");
                }
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_individu_satwa(){
            
            $req["judul"]="Monitoring Data Individu Satwa";
            $req["url"]=site_url('lemkon/individu_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function individu_satwa_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('data_individu_satwa_new');

                $crud->display_as("informasi_lk_umum_id_lk","Id LK");

                if($this->hakAkses=="user"){               
                    //$crud->unset_fields("id_lk");
                    $crud->field_type('informasi_lk_umum_id_lk', 'hidden', $this->id_lk);
                    $crud->where("informasi_lk_umum_id_lk", $this->id_lk);
                    //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk", "");
                }
                else{
                    $crud->set_relation("informasi_lk_umum_id_lk","informasi_lk_umum","nama_lk");
                }
                $crud->field_type('kondisi_satwa','dropdown',array('HIDUP' => 'Hidup','DIAWETKAN' => 'Diawetkan'));
                $crud->set_relation("master_status_konservasi_satwa_id_status","master_status_konservasi_satwa","nama_status");
                $crud->set_relation("master_status_hukum_satwa_id_status","master_status_hukum_satwa","nama_status");
               
                $crud->set_relation("master_satwa_nama_latin","master_satwa","{jenis_satwa} - {nama_latin}");
                $crud->set_relation("master_jenis_kelamin_id_jenis_kelamin","master_jenis_kelamin","nama_jenis_kelamin");
                
                $crud->callback_before_insert(array($this,'individu_satwa_callback'));
                //$crud->callback_before_update(array($this,'individu_satwa_callback'));

                $crud->set_field_upload('file_asal_usul_bap','assets/uploads/lk/bapindsat');
                $crud->set_field_upload('pdf_upload_sk','assets/uploads/lk/skindsat');
                $crud->unset_fields('tanggal_lahir',
                                'nama_induk_jantan',
                                'nama_induk_betina',
                                'upload_bap'
                );
                $crud->unset_columns('tanggal_lahir',
                            'nama_induk_jantan',
                            'nama_induk_betina',
                            'upload_bap'
                );
                // $crud->unset_fields('tanggal_lahir',
                //                     'nama_induk_jantan',
                //                     'nama_induk_betina',
                //                     'upload_bap',
                //                     'upload_foto_utuh',
                //                     'upload_foto_spesifik',
                //                     'nekropsi',
                //                     'tanggal_kematian',
                //                     'penyebab_kematian',
                //                     'upload_bap_kematian',
                //                     'upload_foto_utuh_kematian',
                //                     'upload_foto_spesifik_kematian'
                // );
                
                // $crud->unset_columns('id_individu_satwa',
                //                     'tanggal_lahir',
                //                     'nama_induk_jantan',
                //                     'nama_induk_betina',
                //                     'upload_bap',
                //                     'upload_foto_utuh',
                //                     'upload_foto_spesifik',
                //                     'nekropsi',
                //                     'tanggal_kematian',
                //                     'penyebab_kematian',
                //                     'upload_bap_kematian',
                //                     'upload_foto_utuh_kematian',
                //                     'upload_foto_spesifik_kematian');

                $crud->field_type('id_individu_satwa', 'hidden');

                $crud->set_rules('informasi_lk_umum_id_lk','Nama LK','required');
                $crud->set_rules('master_satwa_nama_latin','Nama Latin','required');
                $crud->set_rules('no_Identifikasi','No Identifikasi','required');

                $crud->display_as("informasi_lk_umum_id_lk","Kode LK")
                     ->display_as("master_status_konservasi_satwa_id_status","Status Perlindungan Satwa")
                     ->display_as("master_status_hukum_satwa_id_status","Status Hukum Satwa")
                     ->display_as("master_satwa_nama_latin","Jenis Satwa","{jenis_satwa} - {nama_latin}")
                     ->display_as("master_jenis_kelamin_id_jenis_kelamin","Jenis Kelamin")
                     ->display_as("nomer_sk","No SK")
                     ;
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }
        function field_callback_1($value = '', $primary_key = null)
        {
            return '+30 <input type="text" maxlength="50" value="'.$value.'" name="phone" style="width:462px">';
        }
        public function monitoring_investasi(){
            
            $req["judul"]="Monitoring Investasi";
            $req["url"]=site_url('lemkon/investasi_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function investasi_ctl(){            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('investasi');

                $crud->display_as("informasi_lk_umum_kode_lk","Nama LK");
                if($this->hakAkses=="user"){               
                    //$crud->unset_fields("id_lk");
                    $crud->field_type('informasi_lk_umum_kode_lk', 'hidden', $this->id_lk);
                    $crud->where("informasi_lk_umum_kode_lk", $this->id_lk);
                    //$crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk", "");
                }
                else{
                    $crud->set_relation("informasi_lk_umum_kode_lk","informasi_lk_umum","nama_lk");
                }
                

                /*$crud->set_rules('modal_awal','Modal Awal','integer');
                $crud->set_rules('pegawai','Pegawai','integer');
                $crud->set_rules('sarana','Sarana','integer');*/
                $crud->field_type('id_investasi', 'hidden');

                $crud->callback_before_insert(array($this,'investasi_callback'));
                $crud->callback_before_update(array($this,'investasi_callback'));
                $crud->callback_column('modal_awal',array($this,'currencyFormat'));
                $crud->callback_column('pegawai',array($this,'currencyFormat'));
                $crud->callback_column('sarana',array($this,'currencyFormat'));      
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }

        public function monitoring_informasi_lk_umum() {
            
            $req["judul"]="Informasi Lembaga Konservasi Umum";
            $req["url"]=site_url('lemkon/informasi_lk_umum_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function informasi_lk_umum_ctl(){
            
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('informasi_lk_umum');
                
                if($this->hakAkses=="user"){               
                    $crud->unset_fields("id_lk");
                    //$crud->field_type('master_institusi_lk_id_institusi_lk', 'readonly');
                    $crud->where("id_lk", $this->id_lk);
                    $crud->unset_add();
                    $crud->unset_delete();
                }

                $crud->set_relation("master_institusi_lk_id_institusi_lk", "master_institusi_lk", "nama_institusi");
                $crud->set_relation("master_bentuk_lk_id_bentuk_lk", "master_bentuk_lk", "nama_bentuk_lk");

                $crud->columns("nama_lk", 
                              "master_institusi_lk_id_institusi_lk", 
                              "master_bentuk_lk_id_bentuk_lk",
                              "provinsi_id_prov",
                              "kabupaten_id_kab",
                              "sk_izin",
                              "tanggal_sk",
                              "luas_wilayah_ha");

                $crud->display_as("master_institusi_lk_id_institusi_lk","Institusi LK")
                     ->display_as("master_bentuk_lk_id_bentuk_lk","Bentuk LK")
                     ->display_as("provinsi_id_prov","Provinsi")
                     ->display_as("kabupaten_id_kab","Kabupaten")
                     ->display_as("kecamatan_id_kecamatan","Kecamatan")
                     ->display_as("kelurahan_id_lurah","Kelurahan")
                     ->display_as("luas_wilayah_ha","Luas Wilayah (Ha)")
                     ->display_as("id_lk","Id LK")
                     ->display_as("nama_lk","Nama LK")
                     ->display_as("npwp_lk","NPWP LK")
                     ->display_as("alamat_lk","Alamat LK")                     
                     ;
                
                $crud->set_rules('email','Email','valid_email');
                $crud->set_rules('link_website','Link website','valid_url');

                $crud->set_field_upload('sk_izin','assets/uploads/lk/sklkumum');

                $crud->set_relation("provinsi_id_prov", "provinsi", "nama_prov");
                $crud->set_relation("kabupaten_id_kab", "kabupaten", "nama_kab");
                $crud->set_relation("kecamatan_id_kecamatan", "kecamatan", "nama_kecamatan");
                $crud->set_relation("kelurahan_id_lurah", "kelurahan", "nama_lurah");

                $this->load->library('gc_dependent_select');
                
                $fields = array(
                        'provinsi_id_prov' => array(// first dropdown name
                        'table_name' => 'provinsi', // table of country
                        'title' => 'nama_prov', // country title
                        'relate' => null, // the first dropdown hasn't a relation
                        'data-placeholder' => 'Pilih Provinsi'
                        ),
                        'kabupaten_id_kab' => array(// second dropdown name
                        'table_name' => 'kabupaten', // table of state
                        'title' => 'nama_kab', // state title
                        'id_field' => 'id_kab', // table of state: primary key
                        'relate' => 'provinsi_id_prov', // table of state:
                        'data-placeholder' => 'Pilih Kabupaten/Kota' //dropdown's data-placeholder:
                        ),                        
                        'kecamatan_id_kecamatan' => array(// second dropdown name
                        'table_name' => 'kecamatan', // table of state
                        'title' => 'nama_kecamatan', // state title
                        'id_field' => 'id_kecamatan', // table of state: primary key
                        'relate' => 'kabupaten_id_kab', // table of state:
                        'data-placeholder' => 'Pilih Kecamatan' //dropdown's data-placeholder:
                        ),                    
                        'kelurahan_id_lurah' => array(// second dropdown name
                        'table_name' => 'kelurahan', // table of state
                        'title' => 'nama_lurah', // state title
                        'id_field' => 'id_lurah', // table of state: primary key
                        'relate' => 'kecamatan_id_kecamatan', // table of state:
                        'data-placeholder' => 'Pilih Kelurahan/Desa' //dropdown's data-placeholder:
                        ),
                    );
                
                $config = array(
                            'main_table' => 'informasi_lk_umum',
                            'main_table_primary' => 'id_lk',
                            "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                            'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                        );
                $categories = new gc_dependent_select($crud, $fields, $config);
                $js = $categories->get_js();
                
                $output = $crud->render();        
                $output->output.= $js;
                $this->displayCRUD($output);

                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            
        }    
//--------------------------------------------- End of Transactional Function -------------------------------------
public function index(){
    $this->dashboard();
}
//--------------------------------------------- Data Master Function -------------------------------------
        public function mst_upass() {
            $result["app"]="lk";
            $result["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $result);
            $this->load->view("formUpdatePass", $result);
            $this->load->view("footer");
        }

        private function getUsername(){
            $var = $this->session->userdata;
            $temp = json_decode($var['loginStatus']) ;
            return $temp->username;
        }

        public function mst_upass_ctl(){
            $passLama = $this->input->post("pl", TRUE);  
            $username = $this->getUsername();
            $passBaru = $this->input->post("pb", TRUE);
            $passBaru = md5($passBaru.$username);
            $passLama = md5($passLama.$username);
            $this->load->model("member");
            $isUpdate = $this->member->cekPassLama($username, $passLama, "db_lemkon");
            $update = 2;
            if($isUpdate){
              $update = $this->member->updatePassword($username, $passBaru, "db_lemkon");
            }
            echo $update;
            //return $update;
          }

        public function master_member(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Member/Pengguna Aplikasi";
            $req["url"]=site_url('lemkon/master_member_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_member_ctl(){
            try{
                $crud=new grocery_CRUD();$crud->set_language("indonesian");          
                $crud->set_table('member');
                $crud->set_theme('datatables');
                //$crud->add_action('Reset Password', '', '','ui-icon-lightbulb',array($this,'resetPass'));
                //$crud->add_action('Reset Password', '', 'lemkon/resetPass','ui-icon-image');
                                        
                $crud->set_relation('id_lk', 'informasi_lk_umum', 'nama_lk');
                $crud->set_relation('id_prov', 'provinsi', 'nama_prov');     
                // $crud->field_type('password', 'hidden');
                $crud->field_type('hak_akses','dropdown',array('ADMIN' => 'Admin','SUB ADMIN' => 'Sub Admin', 'USER' => 'User'));
                    //$crud->field_type('username', 'hidden');
                $crud->callback_before_insert(array($this,'generateKey'));
                $crud->callback_before_insert(array($this,'generateMd5'));
                $crud->callback_before_update(array($this,'generateMd5'));                    

                

                /*$crud->callback_edit_field('username', function ($value, $primary_key) {
                    return '<input type="text" maxlength="50" value="'.$value.'" name="phone" readonly="readonly">';
                    }); */
                    $crud->set_rules('username','username','required|min_length[5]|max_length[12]|alpha_dash');
                    $crud->set_rules('nip','nip','required|numeric');
                    //$crud->set_rules('nama','nama','required|alpha');
                                        
                $crud->display_as('ID_INSTANSI','INSTANSI')
                ->display_as('id_lk','Asal Lembaga Konservasi');
                
                   $crud->display_as('ID_PROV','PROVINSI')
                ->display_as('id_prov','Provinsi');
                                
                $output = $crud->render();
                 
                $this->displayCRUD($output);
                    }catch(Exception $e){
                show_error($e->getMessage().' --- '.$e->getTraceAsString());
            }
        }

        public function master_sebab_mati() {
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Penyebab Kematian Satwa";
            $req["url"]=site_url('lemkon/master_sebab_mati_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }        

        public function master_sebab_mati_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_sebab_mati');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }  

        public function master_kawasan_hutan(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Kawasan Hutan";
            $req["url"]=site_url('lemkon/master_kawasan_hutan_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_kawasan_hutan_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_kawasan');
                                
                $crud->display_as("provinsi_id_prov","Provinsi")
                ->display_as("kabupaten_id_kab","Kabupaten")
                ->display_as("kecamatan_id_kecamatan","Kecamatan")
                ->display_as("kelurahan_id_lurah","Kelurahan")
                ->display_as("latitude", "Latitude (decimal format)")                    
                ->display_as("longitude", "Longitude (decimal format)")                    
                ;

                $crud->set_rules('latitude','Latitude (decimal format)','decimal');
                $crud->set_rules('longitude','Latitude (decimal format)','decimal');

                $crud->set_relation("provinsi_id_prov", "provinsi", "nama_prov");
                $crud->set_relation("kabupaten_id_kab", "kabupaten", "nama_kab");
                $crud->set_relation("kecamatan_id_kecamatan", "kecamatan", "nama_kecamatan");
                $crud->set_relation("kelurahan_id_lurah", "kelurahan", "nama_lurah");

                $this->load->library('gc_dependent_select');
                
                $fields = array(
                        'provinsi_id_prov' => array(// first dropdown name
                        'table_name' => 'provinsi', // table of country
                        'title' => 'nama_prov', // country title
                        'relate' => null, // the first dropdown hasn't a relation
                        'data-placeholder' => 'Pilih Provinsi'
                        ),
                        'kabupaten_id_kab' => array(// second dropdown name
                        'table_name' => 'kabupaten', // table of state
                        'title' => 'nama_kab', // state title
                        'id_field' => 'id_kab', // table of state: primary key
                        'relate' => 'provinsi_id_prov', // table of state:
                        'data-placeholder' => 'Pilih Kabupaten/Kota' //dropdown's data-placeholder:
                        ),                        
                        'kecamatan_id_kecamatan' => array(// second dropdown name
                        'table_name' => 'kecamatan', // table of state
                        'title' => 'nama_kecamatan', // state title
                        'id_field' => 'id_kecamatan', // table of state: primary key
                        'relate' => 'kabupaten_id_kab', // table of state:
                        'data-placeholder' => 'Pilih Kecamatan' //dropdown's data-placeholder:
                        ),                    
                        'kelurahan_id_lurah' => array(// second dropdown name
                        'table_name' => 'kelurahan', // table of state
                        'title' => 'nama_lurah', // state title
                        'id_field' => 'id_lurah', // table of state: primary key
                        'relate' => 'kecamatan_id_kecamatan', // table of state:
                        'data-placeholder' => 'Pilih Kelurahan/Desa' //dropdown's data-placeholder:
                        ),
                    );
                
                $config = array(
                            'main_table' => 'master_kawasan',
                            'main_table_primary' => 'id_kawasan',
                            "url" => base_url() .'index.php/'. __CLASS__ . '/' . __FUNCTION__ . '/',
                            'ajax_loader' => base_url() . '/assets/grocery_crud/themes/datatables/css/images/small-loading.gif'
                        );
                $categories = new gc_dependent_select($crud, $fields, $config);
                $js = $categories->get_js();
                
                $output = $crud->render();        
                $output->output.= $js;
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_bentuk_lk() {
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Bentuk Lembaga Konservasi";
            $req["url"]=site_url('lemkon/master_bentuk_lk_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_bentuk_lk_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_bentuk_lk');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }                       

        public function master_institusi_lk(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Bentuk Institusi Lembaga Konservasi";
            $req["url"]=site_url('lemkon/master_institusi_lk_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_institusi_lk_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_institusi_lk');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_jenis_kelamin(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Jenis Kelamin";
            $req["url"]=site_url('lemkon/master_jenis_kelamin_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_jenis_kelamin_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_jenis_kelamin');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_kejadian(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Kejadian";
            $req["url"]=site_url('lemkon/master_kejadian_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_kejadian_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_kejadian');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_perolehan(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Perolehan";
            $req["url"]=site_url('lemkon/master_perolehan_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_perolehan_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_perolehan');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_satwa(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Satwa";
            $req["url"]=site_url('lemkon/master_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_satwa_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_satwa');
                $crud->display_as("jenis_satwa","Nama Lokal");
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }

            }
        }

        public function master_status_hukum_satwa(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Status Hukum Satwa";
            $req["url"]=site_url('lemkon/master_status_hukum_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_status_hukum_satwa_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_status_hukum_satwa');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_status_konservasi_satwa(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Status Konservasi Satwa";
            $req["url"]=site_url('lemkon/master_status_konservasi_satwa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_status_konservasi_satwa_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_status_konservasi_satwa');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }

        public function master_taksa(){
            if(strtolower($this->hakAkses)!="admin"){
                redirect("/login");
            }
            $req["judul"]="Data Master Taksa";
            $req["url"]=site_url('lemkon/master_taksa_ctl');
            $req["app"]="lk";
            $req["hak_akses"] = $this->login_model->hakAkses();
            $this->load->view("header", $req);
            $this->load->view("MstInstansiView", $req);
            $this->load->view("footer");
        }

        public function master_taksa_ctl(){
            if(strtolower($this->hakAkses)=="admin"){
                try{
                $crud=new grocery_CRUD();
                $crud->set_language("indonesian");          
                $crud->set_table('master_taksa');
                                
                $output = $crud->render();        
                $this->displayCRUD($output);
                }catch(Exception $e){
                    show_error($e->getMessage().' --- '.$e->getTraceAsString());
                }
            }
        }
//--------------------------------------------- End of Data Master Function -------------------------------------
        public function displayCRUD($output){
            $this->load->view("gc_view", $output);
        }

}