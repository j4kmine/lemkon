<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class investasi extends CI_Model {

function __contruct(){
	parent::__construct();
}

function annualReport($lemkon=null){
    /*
    SELECT lk.nama_lk, i.informasi_lk_umum_kode_lk, modal_awal, SUM(pegawai) as investasi_pegawai, sum(sarana) AS investasi_sarana, year(i.tanggal_update) as tahun
FROM investasi i
JOIN informasi_lk_umum lk on i.informasi_lk_umum_kode_lk = lk.id_lk
WHERE i.informasi_lk_umum_kode_lk='TSI1'
GROUP BY year(i.tanggal_update)
     */
    $this->db->select("lk.nama_lk");
    $this->db->select("i.informasi_lk_umum_kode_lk");
    $this->db->select("modal_awal");
    $this->db->select("SUM(pegawai) as investasi_pegawai");
    $this->db->select("sum(sarana) AS investasi_sarana");
    $this->db->select("year(i.tanggal_update) as tahun");
    
    $this->db->from("investasi i");
    
    $this->db ->join("informasi_lk_umum lk", "i.informasi_lk_umum_kode_lk = lk.id_lk");
        
    //$this->db->where("t1.id_metpen", "LP");
    //$this->db->where("t.nama_tahapan", $namaTahapan);
    //$this->db->where('`tgl_pelaksanaan` IN (SELECT max(`tgl_pelaksanaan`) FROM `histori_kasus` group by `lp_profil_perusahaan_id_perusahaan`)', NULL, FALSE);
    //$this->db->where('t1.tgl_pelaksanaan = (SELECT 		 MAX(t2.tgl_pelaksanaan)
      //           FROM histori_kasus t2
        //         WHERE t2.lp_profil_perusahaan_id_perusahaan = t1.lp_profil_perusahaan_id_perusahaan)', NULL, FALSE);
    if(($lemkon!=null)&&($lemkon!="all")&&($lemkon!="")){
        $lemkon = $this->security->xss_clean($lemkon);
        $this->db->where("i.informasi_lk_umum_kode_lk", $lemkon);
    }
    /*if(($bidang!=null)&&($bidang!="all")&&($bidang!="")){
        $this->db ->join("lp l", "t1.lp_profil_perusahaan_id_perusahaan = l.profil_perusahaan_id_perusahaan");
        $this->db->where("l.bidang", $bidang);
    }*/
    
    $this->db->group_by("year(i.tanggal_update)");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

function reportChart($year=null){
/*
SELECT lk.nama_lk, i.informasi_lk_umum_kode_lk, modal_awal, SUM(pegawai) as investasi_pegawai, sum(sarana) AS investasi_sarana
FROM investasi i
JOIN informasi_lk_umum lk on i.informasi_lk_umum_kode_lk = lk.id_lk
WHERE YEAR(i.tanggal_update)='2019'
GROUP BY i.informasi_lk_umum_kode_lk
*/

    $this->db->select("lk.nama_lk");
    $this->db->select("i.informasi_lk_umum_kode_lk");
    $this->db->select("modal_awal");
    $this->db->select("SUM(pegawai) as investasi_pegawai");
    $this->db->select("sum(sarana) AS investasi_sarana");
    
    $this->db->from("investasi i");
    
    $this->db ->join("informasi_lk_umum lk", "i.informasi_lk_umum_kode_lk = lk.id_lk");
        
    //$this->db->where("t1.id_metpen", "LP");
    //$this->db->where("t.nama_tahapan", $namaTahapan);
    //$this->db->where('`tgl_pelaksanaan` IN (SELECT max(`tgl_pelaksanaan`) FROM `histori_kasus` group by `lp_profil_perusahaan_id_perusahaan`)', NULL, FALSE);
    //$this->db->where('t1.tgl_pelaksanaan = (SELECT 		 MAX(t2.tgl_pelaksanaan)
      //           FROM histori_kasus t2
        //         WHERE t2.lp_profil_perusahaan_id_perusahaan = t1.lp_profil_perusahaan_id_perusahaan)', NULL, FALSE);
    if(($year!=null)&&($year!="all")&&($year!="")){
        $year = $this->security->xss_clean($year);
        $this->db->where("year(i.tanggal_update)", $year);
    }
    /*if(($bidang!=null)&&($bidang!="all")&&($bidang!="")){
        $this->db ->join("lp l", "t1.lp_profil_perusahaan_id_perusahaan = l.profil_perusahaan_id_perusahaan");
        $this->db->where("l.bidang", $bidang);
    }*/
    
    $this->db->group_by("i.informasi_lk_umum_kode_lk");
    
    $query = $this->db->get();	
    $result = $query->result();
    //echo $this->db->last_query();
    return $result;
}

}