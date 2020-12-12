<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class histori_kelahiran_noniden extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function getDefaultJumlah($primary_key){
    $primary_key = $this->security->xss_clean($primary_key);
    $this->db->select("jumlah_jantan");
    $this->db->select("jumlah_betina");
    $this->db->select("jumlah_unknown");
    $this->db->select("id_satwa_noniden");
    $this->db->from("histori_kelahiran_noniden");
    $this->db->where("id_histori_lahir", $primary_key);
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function jumlahKelahiran($kode_lk = null){
/*
 SELECT id_satwa_noniden, sum(jumlah_jantan) as lhr_jantan, sum(jumlah_betina) as lhr_betina, sum(jumlah_unknown) as lhr_unknown
FROM `histori_kelahiran_noniden`
WHERE id_satwa_noniden LIKE "BIZO%"
GROUP BY id_satwa_noniden
 */
    $this->db->select("id_satwa_noniden");
    $this->db->select("sum(jumlah_jantan) as lhr_jantan");
    $this->db->select("sum(jumlah_betina) as lhr_betina");
    $this->db->select("sum(jumlah_unknown) as lhr_unknown");
    //$this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BKALI' THEN 1 ELSE 0 END) as kalimantan");
    //$this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BSULA' THEN 1 ELSE 0 END) as sulawesi");
    //$this->db->select("sum(CASE WHEN hk.unit_pelaksana = 'BMAPA' THEN 1 ELSE 0 END) as maluku");

    $this->db->from("histori_kelahiran_noniden");

    //$this->db->where("hk.tahapan_id_tahapan", "P21");
    if(($kode_lk!=null)&&($kode_lk!="all")&&($kode_lk!="")){
        $this->db->like('id_satwa_noniden', $kode_lk, 'after'); 
    }

    $this->db->group_by("id_satwa_noniden");

    //$this->db->order_by("year(hk.tgl_pelaksanaan)");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}
/*
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

function exeInsertIdTracker($idTracker, $nomorPerkara){
    $idTracker = $this->security->xss_clean($idTracker);
    $nomorPerkara = $this->security->xss_clean($nomorPerkara);
    $data = array(
        'id_tracker' => $idTracker,
        'nomor_perkara' => $nomorPerkara
    );
    $this->db->trans_start();
    $this->db->insert('hk_tracker', $data);
    $this->db->trans_complete();
    return;
} //exeUpdateIdHK

function insertPerolehanSatwa($id_satwa_noniden, $tgl_perolehan, $cara_perolehan, $lk_asal, $lk_tujuan, 
                              $jumlah_jantan, $jumlah_betina, $jumlah_unknown){
    $id_satwa_noniden = $this->security->xss_clean($id_satwa_noniden);
    $cara_perolehan = $this->security->xss_clean($cara_perolehan);
    $tgl_perolehan = $this->security->xss_clean($tgl_perolehan);
    $lk_asal = $this->security->xss_clean($lk_asal);
    $lk_tujuan = $this->security->xss_clean($lk_tujuan);
    $jumlah_jantan = $this->security->xss_clean($jumlah_jantan);
    $jumlah_betina = $this->security->xss_clean($jumlah_betina);
    $jumlah_unknown = $this->security->xss_clean($jumlah_unknown);
    
    
    $id_perolehan = $id_satwa_noniden."-".date('d_m_Y');
    $data = array(
        'id_perolehan' => $id_perolehan,
        'id_satwa_noniden' => $id_satwa_noniden,
        'cara_perolehan' => $cara_perolehan,
        'tgl_perolehan' => $tgl_perolehan,
        'lk_asal' => $lk_asal,
        'lk_tujuan' => $lk_tujuan,
        'jumlah_jantan' => $jumlah_jantan,
        'jumlah_betina' => $jumlah_betina,
        'jumlah_unknown' => $jumlah_unknown,
    );
    $this->db->trans_start();
    $this->db->insert('histori_perolehan_noniden', $data);

    $this->db->trans_complete();
    return;
} 

function updateLKSatwa($idIndividu, $idLKTujuan){
    $idIndividu = $this->security->xss_clean($idIndividu);
    $idLKTujuan = $this->security->xss_clean($idLKTujuan);
    $this->db->trans_start();
    $this->db->set('informasi_lk_umum_id_lk', $idLKTujuan);
    $this->db->where('id_individu_satwa', $idIndividu);
    $this->db->update('data_individu_satwa');
    $this->db->trans_complete();
    return;
}

function deleteTracker($idTracker){
    $idHK = $this->security->xss_clean($idTracker);
    $this->db->where('id_tracker', $idTracker);
    $this->db->delete('hk_tracker');
}*/
}