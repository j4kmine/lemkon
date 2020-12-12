<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class histori_perolehan_noniden extends CI_Model {

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
}
}