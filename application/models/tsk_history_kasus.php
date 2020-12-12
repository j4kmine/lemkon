<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class tsk_history_kasus extends CI_Model {

function __contruct(){
	parent::__construct();
}

function exeInsertIdHK($idHK){
    $idHK = $this->security->xss_clean($idHK);
    $this->db->trans_start();
    $this->db->set('id_histori_kasus', $idHK);
    $this->db->where('id_histori_kasus', "0");
    $this->db->or_where('id_histori_kasus', "");
    $this->db->update('tsk_history_kasus');
    $this->db->trans_complete();
    return;
} //exeUpdateIdHK

function exeUpdateIdHK($idHK, $primaryKey){
    $idHK = $this->security->xss_clean($idHK);
    $primaryKey = $this->security->xss_clean($primaryKey);
    $this->db->trans_start();
    $this->db->set('id_histori_kasus', $idHK);
    $this->db->where('id_histori_kasus', $primaryKey);
    $this->db->update('tsk_history_kasus');
    $this->db->trans_complete();
    return;
}
}