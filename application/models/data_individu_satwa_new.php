<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_individu_satwa_new extends CI_Model {

function __contruct(){
	parent::__construct();
}

function updateLKSatwa($idIndividu, $idLKTujuan){
  $idIndividu = $this->security->xss_clean($idIndividu);
  $idLKTujuan = $this->security->xss_clean($idLKTujuan);
  $this->db->trans_start();
  $this->db->set('informasi_lk_umum_id_lk', $idLKTujuan);
  $this->db->where('id_individu_satwa', $idIndividu);
  $this->db->update('data_individu_satwa_new');
  $this->db->trans_complete();
  return;
}



}