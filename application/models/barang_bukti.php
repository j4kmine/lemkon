<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class barang_bukti extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function getMap(){
    $this->db->select("COUNT(bb.nomor_perkara) as map_val");
    $this->db->select("prov.id_map");
    
    $this->db->from("barang_bukti bb");
    
    $this->db ->join("provinsi prov", "bb.provinsi_id_prov = prov.id_prov");
    $this->db ->join("histori_kasus hk", "hk.id_histori_kasus = bb.nomor_perkara");
    
    $this->db->where("hk.tahapan_id_tahapan", "P21");
    $this->db->group_by("bb.provinsi_id_prov");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

public function getLokasiTipologi(){
    /*
    SELECT 
prov.nama_prov, prov.id_map,
sum(CASE WHEN hk.tipologi_kasus = 'BALLI'THEN 1 ELSE 0 END) as pembalakan_liar,
sum(CASE WHEN hk.tipologi_kasus = 'KARHL' THEN 1 ELSE 0 END) as karhutla,
sum(CASE WHEN hk.tipologi_kasus = 'PLHID' THEN 1 ELSE 0 END) as pencemaran,
sum(CASE WHEN hk.tipologi_kasus = 'RAMHU' THEN 1 ELSE 0 END) as perambahan,
sum(CASE WHEN hk.tipologi_kasus = 'TSL' THEN 1 ELSE 0 END) as tsl,
sum(CASE WHEN hk.tipologi_kasus = 'KLHID' THEN 1 ELSE 0 END) as kerusakan
FROM barang_bukti bb
JOIN histori_kasus hk on bb.nomor_perkara = hk.id_histori_kasus
JOIN provinsi prov on bb.provinsi_id_prov = prov.id_prov
WHERE hk.tahapan_id_tahapan="P21"
group BY prov.nama_prov
    */
    $this->db->select("prov.nama_prov");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'BALLI'THEN 1 ELSE 0 END) as pembalakan_liar");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'KARHL' THEN 1 ELSE 0 END) as karhutla");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'KLHID' THEN 1 ELSE 0 END) as kerusakan");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'PLHID' THEN 1 ELSE 0 END) as pencemaran");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'RAMHU' THEN 1 ELSE 0 END) as perambahan");
    $this->db->select("sum(CASE WHEN hk.tipologi_kasus = 'TSL' THEN 1 ELSE 0 END) as tsl");

    $this->db->from("barang_bukti bb");

    $this->db->join("histori_kasus hk", "bb.nomor_perkara = hk.id_histori_kasus");
    $this->db->join("provinsi prov", "bb.provinsi_id_prov = prov.id_prov");

    $this->db->where("hk.tahapan_id_tahapan", "P21");

    $this->db->group_by("prov.nama_prov");

    $this->db->order_by("prov.nama_prov");

    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;

}
	
}