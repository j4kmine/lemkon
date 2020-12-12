<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class perolehan_satwa extends CI_Model {

function __contruct(){
	parent::__construct();
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

function insertPerolehanSatwa($id_individu_satwa, $master_perolehan_id_perolehan, $tanggal_perolehan, $lk_tujuan_informasi_lk_umum_kode_lk){
    $id_individu_satwa = $this->security->xss_clean($id_individu_satwa);
    $master_perolehan_id_perolehan = $this->security->xss_clean($master_perolehan_id_perolehan);
    $tanggal_perolehan = $this->security->xss_clean($tanggal_perolehan);
    $tanggal_id = str_replace("/","_",$tanggal_perolehan);
    $tanggal_id = str_replace("-","_",$tanggal_perolehan);
    $lk_tujuan_informasi_lk_umum_kode_lk = $this->security->xss_clean($lk_tujuan_informasi_lk_umum_kode_lk);
    
    $id_perolehan = $id_individu_satwa."-".$tanggal_id;
    $data = array(
        'id_perolehan' => $id_perolehan,
        'id_individu_satwa' => $id_individu_satwa,
        'master_perolehan_id_perolehan' => $master_perolehan_id_perolehan,
        'tanggal_perolehan' => $tanggal_perolehan,
        'lk_tujuan_informasi_lk_umum_kode_lk' => $lk_tujuan_informasi_lk_umum_kode_lk
    );
    $this->db->trans_start();
    $this->db->insert('perolehan_satwa', $data);
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
}
}