<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pelepasliaran extends CI_Model {

function __contruct(){
	parent::__construct();
}

function jmllepasliar($idLK){
/*
SELECT CONCAT(nlat.jenis_satwa, " - ", nlat.nama_latin) AS satwa, dis.informasi_lk_umum_id_lk,
sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'J' THEN 1 ELSE 0 END) as jml_jantan,
sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'B' THEN 1 ELSE 0 END) as jml_betina,
sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'U' THEN 1 ELSE 0 END) as jml_unknown
FROM pelepasliaran lps
JOIN data_individu_satwa dis on lps.data_individu_satwa_master_satwa_nama_latin = dis.id_individu_satwa
JOIN master_satwa nlat on dis.master_satwa_nama_latin = nlat.nama_latin
GROUP BY dis.informasi_lk_umum_id_lk, dis.master_satwa_nama_latin
*/

    $this->db->select("CONCAT(nlat.jenis_satwa, " - ", nlat.nama_latin) AS satwa", FALSE);
    $this->db->select("dis.informasi_lk_umum_id_lk");
    $this->db->select("sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'J' THEN 1 ELSE 0 END) as jml_jantan");
    $this->db->select("sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'B' THEN 1 ELSE 0 END) as jml_betina");
    $this->db->select("sum(CASE WHEN dis.master_jenis_kelamin_id_jenis_kelamin = 'U' THEN 1 ELSE 0 END) as jml_unknown");
    $this->db->from("pelepasliaran lps");
    $this->db->join("data_individu_satwa dis", "lps.data_individu_satwa_master_satwa_nama_latin = dis.id_individu_satwa");
    $this->db->join("master_satwa nlat", "dis.master_satwa_nama_latin = nlat.nama_latin");
    //$this->db->where("dis.tanggal_kematian", "0000-00-00");
    $this->db->group_by("informasi_lk_umum_id_lk");
    $this->db->group_by("dis.master_satwa_nama_latin");

    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

}