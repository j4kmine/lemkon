<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class vsatwa_per_prov extends CI_Model {

    function __contruct(){
        parent::__construct();
    }
    function getAllSatwa(){
        $this->db->select("master_satwa_nama_latin");
        $this->db->select("jenis_satwa");

        $this->db->from("vdaftarsatwa");
        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }

//vCariLahirMatiSatwa
    function cariLahirMatiSatwa($satwa=null){
        /*        
        SELECT id_lk, nama_lk, master_satwa_nama_latin, jenis_satwa, sum(c.jml_lahir) as kelahiran, sum(c.jml_mati) as kematian
FROM vcarilahirmati c
GROUP by id_lk, master_satwa_nama_latin
         */
        $this->db->select("id_lk");
        $this->db->select("nama_lk");
        $this->db->select("master_satwa_nama_latin");
        $this->db->select("jenis_satwa");
        $this->db->select("sum(c.jml_lahir) as kelahiran", FALSE);
        $this->db->select("sum(c.jml_mati) as kematian", FALSE);

        $this->db->from("vcarilahirmati c");

        if(($satwa!=null)&&($satwa!="all")&&($satwa!="")){
            $satwa = $this->security->xss_clean($satwa);
            $this->db->where('master_satwa_nama_latin', $satwa); 
            
        }
        $this->db->group_by("master_satwa_nama_latin");
        $this->db->group_by("id_lk");
        //$this->db->group_by("master_satwa_nama_latin");

        $this->db->order_by("nama_lk");
        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }

    function daftarSatwaLahirMati(){
        /*
         SELECT id_lk, nama_lk, master_satwa_nama_latin, jenis_satwa 
         FROM vhidupmatisatwa1 
         GROUP BY id_lk, master_satwa_nama_latin
         */
        $this->db->select("id_lk");
        $this->db->select("nama_lk");
        $this->db->select("master_satwa_nama_latin");
        $this->db->select("jenis_satwa");
        
        $this->db->from("vhidupmatisatwa1");

        $this->db->group_by("master_satwa_nama_latin");

        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }
/*
SELECT * FROM `coba`
UNION
SELECT id_lk, nama_lk, master_satwa_nama_latin, jenis_satwa, 
sum(CASE WHEN dis.tanggal_lahir <> '0000-00-00' THEN 1 ELSE 0 END) as jml_lahir,
sum(CASE WHEN dis.tanggal_kematian <> '0000-00-00' THEN 1 ELSE 0 END) as jml_mati
FROM data_individu_satwa dis
JOIN informasi_lk_umum lk on lk.id_lk = dis.informasi_lk_umum_id_lk
JOIN master_satwa ms on ms.nama_latin = dis.master_satwa_nama_latin
WHERE id_lk <> "LEPAS"
GROUP BY id_lk

*/

    function getSatwaLahirMati($satwa = null, $id_lk=null){
        /*
        SELECT c.id_lk, c.nama_lk, c.master_satwa_nama_latin, c.jenis_satwa, sum(c.jml_lahir) as kelahiran, sum(c.jml_mati) as kematian
FROM coba c
GROUP BY id_lk, c.master_satwa_nama_latin

SELECT id_lk, nama_lk, master_satwa_nama_latin, jenis_satwa,
SUM(jml_lahir) as kelahiran, SUM(jml_mati) as kematian
FROM vhidupmatisatwa1
GROUP BY id_lk
         */
        $this->db->select("id_lk");
        $this->db->select("nama_lk");
        $this->db->select("master_satwa_nama_latin");
        $this->db->select("jenis_satwa");
        $this->db->select("sum(c.jml_lahir) as kelahiran", FALSE);
        $this->db->select("sum(c.jml_mati) as kematian", FALSE);

        $this->db->from("vhidupmatisatwa1 c");

        if(($satwa!=null)&&($satwa!="all")&&($satwa!="")){
            $satwa = $this->security->xss_clean($satwa);
            $this->db->where('master_satwa_nama_latin', $satwa); 
            $this->db->group_by("master_satwa_nama_latin");
        }

        if(($id_lk!=null)&&($id_lk!="all")&&($id_lk!="")){
            $id_lk = $this->security->xss_clean($id_lk);
            $this->db->where('id_lk', $id_lk); 
        }

        $this->db->group_by("id_lk");
        //$this->db->group_by("master_satwa_nama_latin");

        $this->db->order_by("nama_lk");
        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;

    }

    function getSatwaMatiChart1($satwa=null, $thnMati=null){
        /*
        SELECT YEAR(dis.tanggal_kematian) as tahun_mati, dis.master_satwa_nama_latin, lk.nama_lk, COUNT(dis.id_individu_satwa) as jumlah, ms.jenis_satwa 
FROM data_individu_satwa dis
JOIN informasi_lk_umum lk on lk.id_lk = dis.informasi_lk_umum_id_lk
JOIN master_satwa ms on dis.master_satwa_nama_latin = ms.nama_latin
WHERE dis.tanggal_kematian <> '0000-00-00'
AND dis.informasi_lk_umum_id_lk !="LEPAS"
GROUP BY master_satwa_nama_latin, dis.tanggal_kematian

union
SELECT YEAR(mati.tgl_kematian) as tahun_mati, dsn.master_satwa_nama_latin, lk.nama_lk, COUNT(dsn.id_satwa_noniden) as jumlah, ms.jenis_satwa 
FROM histori_kematian_noniden mati
JOIN dinamika_satwa_noniden dsn on dsn.id_satwa_noniden = mati.id_satwa_noniden
JOIN informasi_lk_umum lk on lk.id_lk = dsn.informasi_lk_umum_kode_lk
JOIN master_satwa ms on dsn.master_satwa_nama_latin = ms.nama_latin
WHERE dsn.informasi_lk_umum_kode_lk  <> "LEPAS"
GROUP BY master_satwa_nama_latin, mati.tgl_kematian

         */
        $this->db->select("tahun_mati");
        $this->db->select("master_satwa_nama_latin");
        $this->db->select("nama_lk");
        $this->db->select("jumlah");
        $this->db->select("jenis_satwa");

        $this->db->from("vkematian1");

        if(($satwa!=null)&&($satwa!="all")&&($satwa!="")){
            $satwa = $this->security->xss_clean($satwa);
            $this->db->where('master_satwa_nama_latin', $satwa); 
        }

        if(($thnMati!=null)&&($thnMati!="all")&&($thnMati!="")){
            $thnMati = $this->security->xss_clean($thnMati);
            $this->db->where('tahun_mati', $thnMati); 
        }

        $this->db->order_by("nama_lk");
        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }
    function getsatwaLahirChart1($satwa=null, $thnLahir=null){
        /*
         SELECT YEAR(dis.tanggal_lahir) as tahun_lahir, dis.master_satwa_nama_latin, lk.nama_lk, COUNT(dis.id_individu_satwa) as jumlah, ms.jenis_satwa 
FROM data_individu_satwa dis
JOIN informasi_lk_umum lk on lk.id_lk = dis.informasi_lk_umum_id_lk
JOIN master_satwa ms on dis.master_satwa_nama_latin = ms.nama_latin
WHERE dis.tanggal_lahir <> '0000-00-00'
GROUP BY master_satwa_nama_latin, tahun_lahir
ORDER BY informasi_lk_umum_id_lk
union
SELECT YEAR(lhr.tgl_kelahiran) as tahun_lahir, dsn.master_satwa_nama_latin, lk.nama_lk, COUNT(lhr.id_satwa_noniden) as jumlah, ms.jenis_satwa  
FROM histori_kelahiran_noniden lhr
JOIN dinamika_satwa_noniden dsn on dsn.id_satwa_noniden = lhr.id_satwa_noniden
JOIN informasi_lk_umum lk on lk.id_lk = lhr.informasi_lk_umum_id_lk
JOIN master_satwa ms on dsn.master_satwa_nama_latin = ms.nama_latin
GROUP BY master_satwa_nama_latin, tahun_lahir
ORDER BY informasi_lk_umum_id_lk
         */
        $this->db->select("nama_lk");
        $this->db->select("jumlah");
        $this->db->select("master_satwa_nama_latin");
        $this->db->select("jenis_satwa");
        $this->db->select("master_satwa_nama_latin");

        $this->db->from("vkelahiran1");

        if(($satwa!=null)&&($satwa!="all")&&($satwa!="")){
            $satwa = $this->security->xss_clean($satwa);
            $this->db->where('master_satwa_nama_latin', $satwa); 
        }

        if(($thnLahir!=null)&&($thnLahir!="all")&&($thnLahir!="")){
            $thnLahir = $this->security->xss_clean($thnLahir);
            $this->db->where('tahun_lahir', $thnLahir); 
        }

        $this->db->order_by("nama_lk");
        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }

    function getSatwaPerProv($idMap, $namaLatin=null){
        /*
        SELECT nama_lk, nama_satwa, jumlah_jantan, jumlah_betina, jumlah_unknown 
        FROM `sebaransatwaperprov` 
        WHERE id_map="30" 
        ORDER BY nama_lk, nama_satwa
         */
        $idMap = $this->security->xss_clean($idMap);
        $this->db->select("nama_lk");
        $this->db->select("nama_satwa");
        $this->db->select("jumlah_jantan");
        $this->db->select("jumlah_betina");
        $this->db->select("jumlah_unknown");

        $this->db->from("sebaransatwaperprov");

        $this->db->where("id_map", $idMap);
        if(count($namaLatin)>0){
            $this->db->where_in("master_satwa_nama_latin", $namaLatin);
        }

        $this->db->order_by("nama_lk");
        $this->db->order_by("nama_satwa");

        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }

    function getJumlahSatwa($namalatin=null, $idMap=null){
        /*
         SELECT sum(jml_individu) as jml, id_map, nama_prov FROM `satwa_per_prov` GROUP BY id_map
         */
        
        $this->db->select("sum(jml_individu) as jml");
        $this->db->select("id_map");
        $this->db->select("nama_prov");
        
        $this->db->from("satwa_per_prov");

        if(count($namalatin)>0){
            $namalatin = $this->security->xss_clean($namalatin);
            $this->db->where_in('master_satwa_nama_latin', $namalatin); 
        }

        if(($idMap!=null)&&($idMap!="all")&&($idMap!="")){
            $idMap = $this->security->xss_clean($idMap);
            $this->db->where('id_map', $id_map); 
        }

        
        $this->db->group_by("id_map");

        $query = $this->db->get();	
        $result = $query->result();
        //echo $this->db->last_query();
        return $result;
    }
}