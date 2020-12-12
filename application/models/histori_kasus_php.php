<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class histori_kasus_php extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function cariProfilPerusahaan($keyword){
    /*
    SELECT pp.nama_perusahaan, kp.nama_kategori, prov.nama_prov, kab.nama_kab, 
    kec.nama_kecamatan, kel.nama_lurah, nama_jalan, No_Telp, Email
    FROM profil_perusahaan pp
    JOIN kategori_perusahaan kp on pp.kategori_perusahaan_id_kategori = kp.id_kategori
    JOIN provinsi prov on pp.provinsi_id_prov = prov.id_prov
    JOIN kabupaten kab on kab.id_kab = pp.kabupaten_id_kab
    join kecamatan kec on kec.id_kecamatan = pp.kecamatan_id_kecamatan
    JOIN kelurahan kel on kel.id_lurah = pp.kelurahan_id_lurah
    WHERE nama_perusahaan LIKE "%jaya%"
    */
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("kp.nama_kategori");
    $this->db->select("prov.nama_prov");
    $this->db->select("kab.nama_kab");
    $this->db->select("kec.nama_kecamatan");
    $this->db->select("kel.nama_lurah");
    $this->db->select("nama_jalan");
    $this->db->select("No_Telp");
    $this->db->select("Email");

    $this->db->from("profil_perusahaan pp");

    $this->db->join("kategori_perusahaan kp","pp.kategori_perusahaan_id_kategori = kp.id_kategori");
    $this->db->join("provinsi prov","pp.provinsi_id_prov = prov.id_prov");
    $this->db->join("kabupaten kab","kab.id_kab = pp.kabupaten_id_kab");
    $this->db->join("kecamatan kec","kec.id_kecamatan = pp.kecamatan_id_kecamatan");
    $this->db->join("kelurahan kel","kel.id_lurah = pp.kelurahan_id_lurah");

    $this->db->like("nama_perusahaan", $keyword);

    $query = $this->db->get();	
    $result = $query->result();
    //$this->db->last_query();
    return $result;
}

public function getRecentPT($namaPT){
    /*SELECT pp.nama_perusahaan, hk.nomor_perkara, t.nama_tahapan, DATE_FORMAT(tgl_pelaksanaan,'%d-%b-%Y') as tgl_pelaksanaan, hk.ketua_pelaksana,  prov.nama_prov, kab.nama_kab
FROM histori_kasus hk
JOIN tsk_history_kasus thk on thk.id_histori_kasus = hk.id_histori_kasus
JOIN profil_perusahaan pp on pp.id_perusahaan = thk.id_tsk
JOIN tahapan t on t.id_tahapan = hk.tahapan_id_tahapan
LEFT OUTER JOIN provinsi prov on hk.provinsi_p21 = prov.id_prov
LEFT OUTER JOIN kabupaten kab on hk.kabupaten_id_kab = kab.id_kab
where hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
    FROM histori_kasus t2
    WHERE t2.nomor_perkara = hk.nomor_perkara)
AND pp.nama_perusahaan LIKE "%indah jaya%"*/
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("hk.nomor_perkara");
    $this->db->select("t.nama_tahapan");
    $this->db->select("DATE_FORMAT(tgl_pelaksanaan,'%d-%b-%Y') as tgl_pelaksanaan");
    $this->db->select("hk.ketua_pelaksana");
    $this->db->select("prov.nama_prov");
    $this->db->select("kab.nama_kab");

    $this->db->from("histori_kasus hk");
    $this->db->join("tsk_history_kasus thk", "thk.id_histori_kasus = hk.id_histori_kasus");
    $this->db->join("profil_perusahaan pp", "pp.id_perusahaan = thk.id_tsk");
    $this->db->join("tahapan t", "t.id_tahapan = hk.tahapan_id_tahapan");
    $this->db->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov", "left");
    $this->db->join("kabupaten kab", "hk.kabupaten_id_kab = kab.id_kab", "left");

    $this->db->where("hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
    FROM histori_kasus t2
    WHERE t2.nomor_perkara = hk.nomor_perkara)", NULL, FALSE);

    $this->db->like("pp.nama_perusahaan", "$namaPT");

    $query = $this->db->get();	
    $result = $query->result();
    //$this->db->last_query();
    return $result;
}

public function getTabelDetilTahapan($labelTahapan = null, $idTahapan = null){
    /*
SELECT hk.nomor_perkara, pp.nama_perusahaan, tk.nama_tipologi
FROM histori_kasus hk
JOIN tsk_history_kasus thk on hk.id_histori_kasus = thk.id_histori_kasus
JOIN profil_perusahaan pp on thk.id_tsk = pp.id_perusahaan
JOIN tipologi_kasus tk on tk.id_tipologi = hk.tipologi_kasus
JOIN tahapan t on t.id_tahapan = hk.tahapan_id_tahapan
WHERE
hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
                      FROM histori_kasus t2
                      WHERE t2.id_tracker = hk.id_tracker)
AND t.nama_tahapan ="P19 - PENYIDIKAN"
     */
    $this->db->select("hk.nomor_perkara");
    $this->db->select("hk.ketua_pelaksana");
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("tk.nama_tipologi");
    $this->db->select("DATE_FORMAT(hk.tgl_pelaksanaan,'%d-%b-%Y') as tgl_pelaksanaan");
    if(($labelTahapan=="P21 - PENYIDIKAN")||($idTahapan=="P21")){
        $this->db->select("prov.nama_prov");
    }

    $this->db->from("histori_kasus hk");

    $this->db->join("tsk_history_kasus thk", "hk.id_histori_kasus = thk.id_histori_kasus", "left");
    $this->db->join("profil_perusahaan pp", "thk.id_tsk = pp.id_perusahaan", "left");
    $this->db->join("tipologi_kasus tk", "tk.id_tipologi = hk.tipologi_kasus");
    $this->db->join("tahapan t", "t.id_tahapan = hk.tahapan_id_tahapan");
    if(($labelTahapan=="P21 - PENYIDIKAN")||($idTahapan=="P21")){
        $this->db->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov");
    }

    $this->db->where("hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
    FROM histori_kasus t2
    WHERE t2.id_tracker = hk.id_tracker)", NULL, FALSE);
    if($labelTahapan != null){
        $this->db->where("t.nama_tahapan", $labelTahapan);
    }

    if($idTahapan!=null){
        $this->db->where("t.id_tahapan", $idTahapan);
    }
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function rekapRecentTahapan($tahapan = null){
    /*
    SELECT hk.tahapan_id_tahapan, COUNT(tahapan_id_tahapan) as jumlah 
    FROM histori_kasus hk
    WHERE
    hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
                        FROM histori_kasus t2
                        WHERE t2.id_tracker = hk.id_tracker)
    GROUP BY hk.tahapan_id_tahapan
    */
    $this->db->select("t.nama_tahapan");
    $this->db->select("COUNT(tahapan_id_tahapan) as jumlah");

    $this->db->from("histori_kasus hk");
    $this->db->join("tahapan t", "hk.tahapan_id_tahapan = t.id_tahapan");

    $this->db->where("hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
    FROM histori_kasus t2
    WHERE t2.id_tracker = hk.id_tracker)", NULL, FALSE);

    if($tahapan!=null){
        $this->db->where("hk.tahapan_id_tahapan",$tahapan);
    }

    $this->db->group_by("hk.tahapan_id_tahapan");
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getTabelDetilP21($idMap = null, $namaProvinsi=null){
    /*
    SELECT pp.nama_perusahaan, tk.nama_tipologi, DATE_FORMAT(hk.tgl_pelaksanaan ,'%d/%b/%Y') AS tgl_p21
FROM histori_kasus hk
JOIN provinsi prov on prov.id_prov = hk.provinsi_p21
JOIN tsk_history_kasus tsk on tsk.id_histori_kasus = hk.id_histori_kasus
JOIN profil_perusahaan pp on tsk.id_tsk = pp.id_perusahaan
JOIN tipologi_kasus tk on hk.tipologi_kasus = tk.id_tipologi
WHERE prov.id_map='08'
     */
    $this->db->select("hk.nomor_perkara");
    $this->db->select("hk.ketua_pelaksana");
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("tk.nama_tipologi");
    $this->db->select("DATE_FORMAT(hk.tgl_pelaksanaan ,'%d/%b/%Y') AS tgl_p21");
    $this->db->select("hk.ketua_pelaksana");
    //$this->db->select("prov.nama_prov");
    $this->db->select("kab.nama_kab");

    $this->db->from("histori_kasus hk");

    $this->db ->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov");
    $this->db ->join("tsk_history_kasus tsk", "tsk.id_histori_kasus = hk.id_histori_kasus", "left");
    $this->db ->join("profil_perusahaan pp", "tsk.id_tsk = pp.id_perusahaan", "left");
    $this->db ->join("tipologi_kasus tk", "hk.tipologi_kasus = tk.id_tipologi");
    $this->db ->join("kabupaten kab", "hk.kabupaten_id_kab = kab.id_kab");

    if(($idMap != null)||($idMap!="")){
        $this->db->where("prov.id_map", $idMap);
    }
    if(($namaProvinsi != null)||($namaProvinsi!="")){
        $this->db->where("prov.nama_prov", $namaProvinsi);
    }
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getProvP21(){
    $this->db->select("DISTINCT prov.id_prov", FALSE);
    $this->db->select("prov.nama_prov");

    $this->db->from("histori_kasus hk");

    $this->db ->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov");
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getMap(){
    $this->db->select("count(id_histori_kasus) as map_val");
    $this->db->select("prov.id_map");
    
    $this->db->from("histori_kasus hk");
    
    $this->db ->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov");
    
    $this->db->where("hk.tahapan_id_tahapan", "P21");
    $this->db->group_by("hk.id_histori_kasus");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getLokasiTipologi($provinsi=null){
    $this->db->select("tk.nama_tipologi");
    $this->db->select("COUNT(tahapan_id_tahapan) as jml_p21");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("tipologi_kasus tk", "hk.tipologi_kasus = tk.id_tipologi");
    $this->db ->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov");
    
    //$this->db->where("hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
    //FROM histori_kasus t2
    //WHERE t2.nomor_perkara = hk.nomor_perkara)", NULL, FALSE);
    
    $this->db->where("tahapan_id_tahapan", "P21");
    
    if(($provinsi!=null)&&($provinsi!="all")&&($provinsi!="")){
        $this->db->where("hk.provinsi_p21", $provinsi);
        $this->db->group_by("hk.provinsi_p21");
    }
    $this->db->group_by("tipologi_kasus");
    
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function rekapP21($unitPelaksana=null, $prov=null){
    $this->db->select("tk.nama_tipologi");
    $this->db->select("COUNT(tahapan_id_tahapan) as jml_p21");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("tipologi_kasus tk", "hk.tipologi_kasus = tk.id_tipologi");
    $this->db ->join("provinsi prov", "hk.provinsi_p21 = prov.id_prov");
    
    //$this->db->where("hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
    //FROM histori_kasus t2
    //WHERE t2.nomor_perkara = hk.nomor_perkara)", NULL, FALSE);
    
    $this->db->where("tahapan_id_tahapan", "P21");
    
    if(($unitPelaksana!=null)&&($unitPelaksana!="all")&&($unitPelaksana!="")){
        $this->db->where("unit_pelaksana", $unitPelaksana);
        $this->db->group_by("unit_pelaksana");
    }
    if(($prov!=null)&&($prov!="all")&&($prov!="")){
        $this->db->where("provinsi_p21", $prov);
        $this->db->group_by("provinsi_p21");
    }

    $this->db->group_by("tipologi_kasus");
    
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    
    return $result;
}

public function getLatestTrackerId($unitPelaksana){
    /*
    select max(id_tracker) as id_tracker
    from histori_kasus hk
    where unit_pelaksana like "%"
    */
    /*
    select max(t.id_tracker) as id_tracker
    from hk_tracker t
    where id_tracker like "bsuma%"
     */
    $this->db->select("max(t.id_tracker) as id_tracker");
    $this->db->from("hk_tracker t");
    $this->db->like("id_tracker", $unitPelaksana, "after");
    $query = $this->db->get();	
    $result = $query->result();
    
    $idTracker="";
    foreach($result as $row){
        $idTracker = $row->id_tracker;
    }
    $t="0000";    
    $temp = explode("-",$idTracker);    
    $temp[1] = (!isset($temp[1]))? "1": $temp[1]+1;       
    $t = $t.$temp[1];
    $t = substr($t, strlen($temp[1]), 4);
    $idTracker = $unitPelaksana."-".$t;    
    return $idTracker;
}

public function rekapP21byUnitKerja(){

    $this->db->select("year(hk.tgl_pelaksanaan) as tahun");
    $this->db->select("sum(CASE WHEN hk.unit_pelaksana in ('RAMBAH', 'KARHUTLA', 'CEMAR', 'BALAK') THEN 1 ELSE 0 END) as dit_PHP");
    $this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BSUMA' THEN 1 ELSE 0 END) as sumatera");
    $this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BJABA' THEN 1 ELSE 0 END) as jawa");
    $this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BKALI' THEN 1 ELSE 0 END) as kalimantan");
    $this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BSULA' THEN 1 ELSE 0 END) as sulawesi");
    $this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BMAPA' THEN 1 ELSE 0 END) as maluku");

    $this->db->from("histori_kasus hk");

    $this->db->where("hk.tahapan_id_tahapan", "P21");

    $this->db->group_by("year(hk.tgl_pelaksanaan)");

    $this->db->order_by("year(hk.tgl_pelaksanaan)");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function rekapP21byTipologi(){
    /*
    SELECT year(hk.tgl_pelaksanaan) as tahun,
    sum(CASE WHEN hk.tipologi_kasus = 'BALLI' THEN 1 ELSE 0 END) as pembalakan_liar,
    sum(CASE WHEN hk.tipologi_kasus = 'KARHL' THEN 1 ELSE 0 END) as karhutla,
    sum(CASE WHEN hk.tipologi_kasus = 'KLHID' THEN 1 ELSE 0 END) as kerusakan,
    sum(CASE WHEN hk.tipologi_kasus = 'PLHID' THEN 1 ELSE 0 END) as pencemaran,
    sum(CASE WHEN hk.tipologi_kasus = 'RAMHU' THEN 1 ELSE 0 END) as perambahan,
    sum(CASE WHEN hk.tipologi_kasus = 'TSL' THEN 1 ELSE 0 END) as tsl
    FROM histori_kasus hk
    WHERE HK.tahapan_id_tahapan = "P21"
    GROUP BY year(hk.tgl_pelaksanaan)
    ORDER BY year(hk.tgl_pelaksanaan)
     */
    $this->db->select("year(hk.tgl_pelaksanaan) as tahun");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'BALLI'THEN 1 ELSE 0 END) as pembalakan_liar");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'KARHL' THEN 1 ELSE 0 END) as karhutla");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'KLHID' THEN 1 ELSE 0 END) as kerusakan");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'PLHID' THEN 1 ELSE 0 END) as pencemaran");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'RAMHU' THEN 1 ELSE 0 END) as perambahan");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'TSL' THEN 1 ELSE 0 END) as tsl");

    $this->db->from("histori_kasus hk");

    $this->db->where("hk.tahapan_id_tahapan", "P21");

    $this->db->group_by("year(hk.tgl_pelaksanaan)");

    $this->db->order_by("year(hk.tgl_pelaksanaan)");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}
//----------------------------------------------END OF PHP QUERY------------------------------------
public function rekapTahapanLP($year=null, $bidang=null){
    $this->db->select("t.nama_tahapan");
    $this->db->select("COUNT(t1.tahapan_id_tahapan) as jumlah");
    
    $this->db->from("histori_kasus t1");
    
    $this->db ->join("tahapan t", "t1.tahapan_id_tahapan = t.id_tahapan");
        
    $this->db->where("t1.id_metpen", "LP");
    //$this->db->where("t.nama_tahapan", $namaTahapan);
    //$this->db->where('`tgl_pelaksanaan` IN (SELECT max(`tgl_pelaksanaan`) FROM `histori_kasus` group by `lp_profil_perusahaan_id_perusahaan`)', NULL, FALSE);
    $this->db->where('t1.tgl_pelaksanaan = (SELECT 		 MAX(t2.tgl_pelaksanaan)
                 FROM histori_kasus t2
                 WHERE t2.lp_profil_perusahaan_id_perusahaan = t1.lp_profil_perusahaan_id_perusahaan)', NULL, FALSE);
    if(($year!=null)&&($year!="all")&&($year!="")){
        $this->db->where("year(t1.tgl_pelaksanaan)", $year);
    }
    if(($bidang!=null)&&($bidang!="all")&&($bidang!="")){
        $this->db ->join("lp l", "t1.lp_profil_perusahaan_id_perusahaan = l.profil_perusahaan_id_perusahaan");
        $this->db->where("l.bidang", $bidang);
    }
    
    $this->db->group_by("t1.tahapan_id_tahapan");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

/*
 *  menghasilkan nama perusahaan dan tgl pelaksanaan pada tahapan tertentu
 */
public function detilTahapan($namaTahapan) {
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("DATE_FORMAT(tgl_pelaksanaan,'%d-%b-%Y') as tgl_pelaksanaan");
    $this->db->select("(kerugian_lingkungan + biaya_pemulihan + kerugian_immateriil) as kerugian_lingkungan", FALSE);
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("profil_perusahaan pp", "pp.id_perusahaan = lp_profil_perusahaan_id_perusahaan");
    $this->db->join("tahapan t", "t.id_tahapan = tahapan_id_tahapan");
    
    $this->db->where("hk.id_metpen", "MP");
    $this->db->where("t.nama_tahapan", $namaTahapan);
    //$this->db->where('`tgl_pelaksanaan` IN (SELECT max(`tgl_pelaksanaan`) FROM `histori_kasus` group by `lp_profil_perusahaan_id_perusahaan`)', NULL, FALSE);
    $this->db->where('hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
                 FROM histori_kasus t2
                 WHERE t2.lp_profil_perusahaan_id_perusahaan = hk.lp_profil_perusahaan_id_perusahaan)', NULL, FALSE);
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function detilTahapanLP($namaTahapan){
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("DATE_FORMAT(tgl_pelaksanaan,'%d-%b-%Y') as tgl_pelaksanaan");
    $this->db->select("(kerugian_lingkungan + biaya_pemulihan + kerugian_immateriil) as kerugian_lingkungan", FALSE);
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("profil_perusahaan pp", "pp.id_perusahaan = lp_profil_perusahaan_id_perusahaan");
    $this->db->join("tahapan t", "t.id_tahapan = tahapan_id_tahapan");
    
    $this->db->where("hk.id_metpen", "LP");
    $this->db->where("t.nama_tahapan", $namaTahapan);
    //$this->db->where('`tgl_pelaksanaan` IN (SELECT max(`tgl_pelaksanaan`) FROM `histori_kasus` group by `lp_profil_perusahaan_id_perusahaan`)', NULL, FALSE);
    $this->db->where('hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
                 FROM histori_kasus t2
                 WHERE t2.lp_profil_perusahaan_id_perusahaan = hk.lp_profil_perusahaan_id_perusahaan)', NULL, FALSE);
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function rekapKasusMP() {
    $this->db->select("t.nama_tahapan");
    $this->db->select("COUNT(tahapan_id_tahapan) as jumlah");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("tahapan t", "tahapan_id_tahapan = t.id_tahapan");
    
    $this->db->where("hk.id_metpen","MP");    
    //$this->db->where('`tgl_pelaksanaan` IN (SELECT max(`tgl_pelaksanaan`) FROM `histori_kasus` group by `lp_profil_perusahaan_id_perusahaan`)', NULL, FALSE);
    $this->db->where('hk.tgl_pelaksanaan = (SELECT MAX(t2.tgl_pelaksanaan)
                 FROM histori_kasus t2
                 WHERE t2.lp_profil_perusahaan_id_perusahaan = hk.lp_profil_perusahaan_id_perusahaan)', NULL, FALSE);
    
    $this->db->group_by("tahapan_id_tahapan");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getPerusahaanLP() {
    $this->db->select("hk.lp_profil_perusahaan_id_perusahaan as id");
    $this->db->select("pp.nama_perusahaan as nama");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("profil_perusahaan pp","hk.lp_profil_perusahaan_id_perusahaan = pp.id_perusahaan");
    
    $this->db->where("hk.id_metpen", "LP");
    
    $tahapanTakDihitung = array('KLAR2', 'KLAR3', 'KLARI', 'TELAA', 'VERL2', 'VERLP');
    $this->db->where_not_in("tahapan_id_tahapan", $tahapanTakDihitung);
    $this->db->group_by("hk.lp_profil_perusahaan_id_perusahaan");
    $this->db->order_by("pp.nama_perusahaan");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getPerusahaan() {
    $this->db->select("hk.lp_profil_perusahaan_id_perusahaan as id");
    $this->db->select("pp.nama_perusahaan as nama");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("profil_perusahaan pp","hk.lp_profil_perusahaan_id_perusahaan = pp.id_perusahaan");
    
    $this->db->where("hk.id_metpen", "MP");
    
    $tahapanTakDihitung = array('TELMP', 'VERLA', 'MPPGU');
    $this->db->where_not_in("tahapan_id_tahapan", $tahapanTakDihitung);
    $this->db->group_by("hk.lp_profil_perusahaan_id_perusahaan");
    $this->db->order_by("pp.nama_perusahaan");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getAllKerugianMP(){
    $this->db->select("pp.nama_perusahaan");
    $this->db->select("sum(CASE WHEN hk.tahapan_id_tahapan = 'MPDGU' THEN kerugian_lingkungan + biaya_pemulihan + kerugian_immateriil ELSE 0 END) as kerugian_gugatan");
    $this->db->select("sum(CASE WHEN hk.tahapan_id_tahapan not in ('MPDGU', 'MPPGU') AND id_metpen='MP' AND tgl_pelaksanaan IN (SELECT max(t2.tgl_pelaksanaan) FROM histori_kasus t2 WHERE t2.lp_profil_perusahaan_id_perusahaan = hk.lp_profil_perusahaan_id_perusahaan) THEN kerugian_lingkungan + biaya_pemulihan + kerugian_immateriil ELSE 0 END) as kerugian_putusan");        
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("profil_perusahaan pp","hk.lp_profil_perusahaan_id_perusahaan = pp.id_perusahaan");
    
    $this->db->where("kerugian_lingkungan >", "0");
    $this->db->where("hk.id_metpen", "MP");
    $this->db->or_where("biaya_pemulihan >", "0");
    //$this->db->where("lp_profil_perusahaan_id_perusahaan", $idPerusahaan);
    
    $this->db->group_by("lp_profil_perusahaan_id_perusahaan");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function perkembanganKerugianLP($id_perusahaan = null){
    $this->db->select("t.nama_tahapan");
    $this->db->select("sum(kerugian_lingkungan) as total");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("tahapan t", "tahapan_id_tahapan = t.id_tahapan");
    
    $this->db->where("hk.id_metpen", "LP");
    if(($id_perusahaan != null)&&($id_perusahaan != "all")){
        $this->db->where("lp_profil_perusahaan_id_perusahaan", $id_perusahaan);
    }
    $tahapanTakDihitung = array('KLAR2', 'KLAR3', 'KLARI', 'TELAA', 'VERL2', 'VERLP');
    $this->db->where_not_in("tahapan_id_tahapan", $tahapanTakDihitung);
    $this->db->group_by("t.nama_tahapan");
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function perkembanganKerugianMP($id_perusahaan = null){
    $this->db->select("t.nama_tahapan");
    $this->db->select("sum(kerugian_lingkungan) as total");
    
    $this->db->from("histori_kasus hk");
    
    $this->db->join("tahapan t", "tahapan_id_tahapan = t.id_tahapan");
    
    $this->db->where("hk.id_metpen", "MP");
    if(($id_perusahaan != null)&&($id_perusahaan != "all")){
        $this->db->where("lp_profil_perusahaan_id_perusahaan", $id_perusahaan);
    }
    $tahapanTakDihitung = array('TELMP', 'VERLA', 'MPPGU');
    $this->db->where_not_in("tahapan_id_tahapan", $tahapanTakDihitung);
    $this->db->group_by("t.nama_tahapan");
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}
/*

 * SELECT pp.nama_perusahaan, t.nama_tahapan,
CASE WHEN tahapan_id_tahapan = 'MPDGU' THEN (kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) ELSE '0' END AS Pendaftaran_gugatan,
CASE WHEN tahapan_id_tahapan = 'MPBAN' THEN (kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) ELSE '0' END AS banding,
CASE WHEN tahapan_id_tahapan = 'MPKAS' THEN (kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) ELSE '0' END AS kasasi,
CASE WHEN tahapan_id_tahapan = 'MPPK' THEN (kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) ELSE '0' END AS PK,
CASE WHEN tahapan_id_tahapan = 'MPSID' THEN (kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) ELSE '0' END AS PN,
CASE WHEN tahapan_id_tahapan = 'MPEKS' THEN (kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) ELSE '0' END AS Eksekusi
FROM `histori_kasus` hk
JOIN profil_perusahaan pp on hk.lp_profil_perusahaan_id_perusahaan = pp.id_perusahaan
JOIN tahapan t on hk.tahapan_id_tahapan = t.id_tahapan
WHERE hk.id_metpen = "MP" AND tahapan_id_tahapan NOT IN ('TELMP', 'VERLA', 'MPPGU')
 * 
 * 
 * 
 * SELECT pp.nama_perusahaan, t.nama_tahapan, SUM(kerugian_lingkungan+biaya_pemulihan+kerugian_immateriil) as kerugian
FROM `histori_kasus` hk
JOIN profil_perusahaan pp on hk.lp_profil_perusahaan_id_perusahaan = pp.id_perusahaan
JOIN tahapan t on hk.tahapan_id_tahapan = t.id_tahapan
WHERE hk.id_metpen = "MP" AND tahapan_id_tahapan NOT IN ('TELMP', 'VERLA', 'MPPGU')
 *  
 * SELECT pp.nama_perusahaan, hk.tgl_pelaksanaan 
 * FROM histori_kasus hk 
 * JOIN profil_perusahaan pp on pp.id_perusahaan = lp_profil_perusahaan_id_perusahaan 
 * JOIN tahapan t on t.id_tahapan = tahapan_id_tahapan 
 * WHERE hk.id_metpen = 'MP' 
 * AND tgl_pelaksanaan IN (SELECT max(tgl_pelaksanaan) FROM histori_kasus group by lp_profil_perusahaan_id_perusahaan) 
 * AND t.nama_tahapan="Pendaftaran Gugatan" 
 * 
 * 
 * SELECT lp_profil_perusahaan_id_perusahaan, tahapan_id_tahapan,
max(tgl_pelaksanaan) 
FROM histori_kasus
WHERE tahapan_id_tahapan='xxxxx'
group by lp_profil_perusahaan_id_perusahaan
 * 
 */

//-==-----------------------------------------------------------------------------
public function getKondisiSenpi($idMap){
	$this->db->select("concat(js.nama_senjata, ' pada ', i.nama_instansi) as legend",FALSE);
	$this->db->select("sum(CASE WHEN s.KONDISI = 'BAI' THEN 1 ELSE 0 END) as baik");
	$this->db->select("sum(CASE WHEN s.KONDISI = 'HIL' THEN 1 ELSE 0 END) as hilang");
	$this->db->select("sum(CASE WHEN s.KONDISI = 'RBE' THEN 1 ELSE 0 END) as rusak_berat");
	$this->db->select("sum(CASE WHEN s.KONDISI = 'RRI' THEN 1 ELSE 0 END) as rusak_ringan");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");
	$this->db->join("jenis_senjata js","js.id_senjata=s.JENIS_SENPI");
	
	$this->db->where("p.ID_MAP", $idMap);
	
	$this->db->group_by("s.JENIS_SENPI");
	$this->db->order_by("legend");
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapSenpiRusak(){
	$this->db->select("p.ID_MAP");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS >= curdate() THEN 1 ELSE 0 END) as PAS_berlaku");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS < curdate() THEN 1 ELSE 0 END) as PAS_kadaluarsa");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");
        
    $this->db->where("YEAR(s.TGL_HABIS_PAS)", date('Y'));
	
	$this->db->group_by("p.ID");
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapBukuPas(){
	
	$this->db->select("p.NAME as Provinsi");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS >= curdate() THEN 1 ELSE 0 END) as PAS_berlaku");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS < curdate() THEN 1 ELSE 0 END) as PAS_kadaluarsa");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");
        
    $this->db->where("YEAR(s.TGL_HABIS_PAS)", date('Y'));
	
	$this->db->group_by("p.ID");
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapBukuPasProv($idProv){
    $this->db->select("i.NAMA_INSTANSI");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS >= curdate() THEN 1 ELSE 0 END) as PAS_berlaku");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS < curdate() THEN 1 ELSE 0 END) as PAS_kadaluarsa");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	//$this->db->join("provinces p","i.PROPINSI = p.ID");
        
        $this->db->where("YEAR(pas.TGL_HABIS_PAS)", "YEAR(curdate())");
        $this->db->like("s.ID_INSTANSI", $idProv, "after");
	
	$this->db->group_by("s.ID_INSTANSI");
	
	$query = $this->db->get();	
	$result = $query->result();
	
	return $result;
}

public function getAllKondisi(){
	$this->db->select("p.NAME as Provinsi");
	//$this->db->select("kb.nama_kondisi as kondisi");
	//$this->db->select("count(KONDISI) as jml_kondisi");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Baik' THEN 1 ELSE 0 END) as kondisi_baik");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Hilang' THEN 1 ELSE 0 END) as kondisi_hilang");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as kondisi_rb");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as kondisi_rr");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");	
	$this->db->join("kondisi_barang kb", "kb.id_kondisi = s.kondisi");
	
	$this->db->group_by("s.ID_INSTANSI");
	$this->db->order_by("p.Name");
	
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapSenpi() {
    //apakah jumlah senpi selalu satu per BAP??
    $this->db->select("p.NAME, s.JENIS_SENPI, COUNT(s.JENIS_SENPI) as jml_senpi");
    $this->db->from("mst_senpi s");
	
    $this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
    $this->db->join("provinces p","i.PROPINSI = p.ID");	

    $this->db->group_by("s.JENIS_SENPI, p.ID");
    
    $this->db->order_by("p.name");

    $query = $this->db->get();	
    $result = $query->result();
    return $result;
}

public function getSenpiMap(){
	$this->db->select("p.ID_MAP");
	$this->db->select("COUNT(s.ID_INSTANSI) as jml_senpi");	
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i", "s.ID_INSTANSI=i.ID_INSTANSI");
	$this->db->join("provinces p", "i.PROPINSI=p.ID");
	
	$this->db->group_by("p.ID");
	
	$query = $this->db->get();	
    $result = $query->result();
    return $result;
}

public function getSenpiPerProp($idProv){
    $this->db->select("i.NAMA_INSTANSI, s.JENIS_SENPI, COUNT(s.JENIS_SENPI) as jml_senpi");
    $this->db->from("mst_senpi s");
	
    $this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
    //$this->db->join("provinces p","i.PROPINSI = p.ID");	
    
    $this->db->like("s.ID_INSTANSI", $idProv, "after");

    $this->db->group_by("s.ID_INSTANSI");
    
    $this->db->order_by("i.NAMA_INSTANSI");

    $query = $this->db->get();	
    $result = $query->result();
    return $result;
}

public function isDuplicate($id_prov, $namaInstansi){
	$nama_instansi = $this->security->xss_clean($namaInstansi);
	$id_prov = $this->security->xss_clean($id_prov);
	$this->db->select("count('id_instansi') as total_duplikasi");
	$this->db->from("instansi1 i");
	//$this->db->join("refkategoristatus stat","stat.kategoristatus_id=adu.kategoristatus_id");
	$this->db->where("i.nama_instansi",$nama_instansi);
	$this->db->where("i.PROPINSI",$id_prov);
	$this->db->group_by("PROPINSI");
	$query = $this->db->get();
	$temp = 0;
	foreach ($query->result_array() as $row)
	{
		$temp = $row['total_duplikasi'];
	}
	$result = ($temp >= 1)?TRUE:FALSE;
	
	return $result;
}
	
}