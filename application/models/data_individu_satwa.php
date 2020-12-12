<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class data_individu_satwa extends CI_Model {

function __contruct(){
	parent::__construct();
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

function jmlSatwaPerProv($idprov=null){
  //yg mati, yang lepasliar, yg pindah gausah diitung
 /*
SELECT COUNT(informasi_lk_umum_id_lk) as jml_individu, prov.id_map, prov.nama_prov 
FROM data_individu_satwa dis
JOIN informasi_lk_umum lk on dis.informasi_lk_umum_id_lk = lk.id_lk
JOIN provinsi prov on lk.provinsi_id_prov = prov.id_prov
where dis.tanggal_kematian = "0000-00-00" AND informasi_lk_umum_id_lk != "LEPAS"
GROUP BY id_map
  */   
$this->db->select("COUNT(informasi_lk_umum_id_lk) as jml_individu");
//$this->db->select("informasi_lk_umum_id_lk");
$this->db->select("prov.id_map");
$this->db->select("prov.nama_prov");

$this->db->from("data_individu_satwa dis");

$this->db->join("informasi_lk_umum lk", "dis.informasi_lk_umum_id_lk = lk.id_lk");
$this->db->join("provinsi prov", "lk.provinsi_id_prov = prov.id_prov");

$this->db->where("dis.tanggal_kematian", "0000-00-00");
$this->db->where("informasi_lk_umum_id_lk !=", "LEPAS");

if(($idprov!=null)&&($idprov!="all")&&($idprov!="")){
  $idprov = $this->security->xss_clean($idprov);
  $this->db->where("prov.id_prov", $idprov);
}

$this->db->group_by("id_map");
$query = $this->db->get();	
$result = $query->result();
//echo $this->db->last_query();
return $result;
}

function jmlIndividuSatwa($idLK){
/*
SELECT CONCAT(nlat.jenis_satwa, " - ", nlat.nama_latin) AS satwa, dis.informasi_lk_umum_id_lk,
sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'J' THEN 1 ELSE 0 END) as jml_jantan,
sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'B' THEN 1 ELSE 0 END) as jml_betina,
sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'U' THEN 1 ELSE 0 END) as jml_unknown
FROM data_individu_satwa dis
WHERE dis.tanggal_kematian = 0000-00-00
JOIN master_satwa nlat on dis.master_satwa_nama_latin = nlat.nama_latin
GROUP BY informasi_lk_umum_id_lk, dis.master_satwa_nama_latin
*/

    $this->db->select("CONCAT(nlat.jenis_satwa, " - ", nlat.nama_latin) AS satwa", FALSE);
    $this->db->select("dis.informasi_lk_umum_id_lk");
    $this->db->select("sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'J' THEN 1 ELSE 0 END) as jml_jantan");
    $this->db->select("sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'B' THEN 1 ELSE 0 END) as jml_betina");
    $this->db->select("sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'U' THEN 1 ELSE 0 END) as jml_unknown");
    $this->db->from("data_individu_satwa dis");
    $this->db->join("master_satwa nlat", "dis.master_satwa_nama_latin = nlat.nama_latin");
    $this->db->where("dis.tanggal_kematian", "0000-00-00");
    $this->db->group_by("informasi_lk_umum_id_lk");
    $this->db->group_by("dis.master_satwa_nama_latin");

    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

}