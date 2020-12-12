<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class dinamika_satwa_noniden extends CI_Model {

function __contruct(){
	parent::__construct();
}

function insertRow($lk, $satwa, $satwaStripChar){
    $lk = $this->security->xss_clean($lk);
    $satwa = $this->security->xss_clean($satwa);
    $satwaStripChar = $this->security->xss_clean($satwaStripChar);
    $data = array(
        'id_satwa_noniden' => $lk."-",$satwaStripChar,
        'informasi_lk_umum_kode_lk' => $lk,
        'master_satwa_nama_latin' => $satwa
    );
    $this->db->trans_start();
    $this->db->insert('dinamika_satwa_noniden', $data);
    $this->db->trans_complete();
    return;
}

function cekSatwaExist($lk, $satwa){
    $lk = $this->security->xss_clean($lk);
    $satwa = $this->security->xss_clean($satwa);
    $this->db->select("count(id_satwa_noniden) as isExist", FALSE);
    $this->db->from("dinamika_satwa_noniden");
    $this->db->where("informasi_lk_umum_kode_lk", $lk);
    $this->db->where("master_satwa_nama_latin", $satwa);
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

function updateJmlSatwa($id_satwa_noniden, $jumlah_jantan, $jumlah_betina, $jumlah_unknown, $operator){
    $id_satwa_noniden = $this->security->xss_clean($id_satwa_noniden);
    $jumlah_jantan = $this->security->xss_clean($jumlah_jantan);
    $jumlah_betina = $this->security->xss_clean($jumlah_betina);
    $jumlah_unknown = $this->security->xss_clean($jumlah_unknown);  
    
    $jumlah_jantan = (is_numeric($jumlah_jantan)) ? $jumlah_jantan:"0";
    $jumlah_betina = (is_numeric($jumlah_betina)) ? $jumlah_betina:"0";
    $jumlah_unknown = (is_numeric($jumlah_unknown)) ? $jumlah_unknown:"0";

    $this->db->trans_start();
    
    $this->db->set('jumlah_jantan', 'jumlah_jantan '.$operator.' '.$jumlah_jantan, false);
    $this->db->set('jumlah_betina', 'jumlah_betina '.$operator.' '.$jumlah_betina, false);
    $this->db->set('jumlah_unknown', 'jumlah_unknown '.$operator.' '.$jumlah_unknown, false);
    $this->db->where('id_satwa_noniden', $id_satwa_noniden);
    $this->db->update('dinamika_satwa_noniden');    
    $this->db->trans_complete();
    return;
}

function getJmlIndividuperProv($idprov=null){
    /*
    SELECT sum(jumlah_jantan)+SUM(jumlah_betina)+SUM(jumlah_unknown) as jml_individu  , prov.id_map, prov.nama_prov
FROM dinamika_satwa_noniden dsn
JOIN informasi_lk_umum lk on dsn.informasi_lk_umum_kode_lk = lk.id_lk
JOIN provinsi prov on lk.provinsi_id_prov = prov.id_prov
GROUP BY prov.id_map
    */
    $this->db->select("sum(jumlah_jantan)+SUM(jumlah_betina)+SUM(jumlah_unknown) as jml_individu", FALSE);
    $this->db->select("prov.id_map");
    $this->db->select("prov.nama_prov");
    
    $this->db->from("dinamika_satwa_noniden dsn");
    
    $this->db->join("informasi_lk_umum lk", "dsn.informasi_lk_umum_kode_lk = lk.id_lk");
    $this->db->join("provinsi prov", "lk.provinsi_id_prov = prov.id_prov");

    if(($idprov!=null)&&($idprov!="all")&&($idprov!="")){
        $idprov = $this->security->xss_clean($idprov);
        $this->db->where("prov.id_prov", $idprov);
    }

   // $this->db->where("dis.tanggal_kematian", "0000-00-00");
    $this->db->group_by("prov.id_map");
    //$this->db->group_by("dis.master_satwa_nama_latin");

    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

}