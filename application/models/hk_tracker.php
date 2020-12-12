<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class hk_tracker extends CI_Model {

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

function exeUpdateIdHK($idHK, $primaryKey){
    $idHK = $this->security->xss_clean($idHK);
    $primaryKey = $this->security->xss_clean($primaryKey);
    $this->db->trans_start();
    $this->db->set('id_hk', $idHK);
    $this->db->where('id_hk', $primaryKey);
    $this->db->update('tsk_history_kasus');
    $this->db->trans_complete();
    return;
}

function deleteTracker($idTracker){
    $idHK = $this->security->xss_clean($idTracker);
    $this->db->where('id_tracker', $idTracker);
    $this->db->delete('hk_tracker');
}
}