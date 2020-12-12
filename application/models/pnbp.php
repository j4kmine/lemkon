<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class pnbp extends CI_Model {

function __contruct(){
	parent::__construct();
}

function updatePassword($id, $pass){
    $pass =  $this->security->xss_clean($pass);
    $id =  $this->security->xss_clean($id);
    $this->db->trans_start();
    $this->db->set('password', $pass);
    $this->db->where('username', $id);
    $this->db->update('member');
    $this->db->trans_complete();
    if ($this->db->affected_rows() == '1') {
    return TRUE;
    } else {
        // any trans error?
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return true;
    }
}

function cekPassLama($id, $pass){
    $pass =  $this->security->xss_clean($pass);
    $id =  $this->security->xss_clean($id);
    
    $this->db->select("username");
    $this->db->from("member");
    $this->db->where("username", $id);
    $this->db->where("password", $pass);
    
    $query = $this->db->get();	
    $result = $query->num_rows();
    //echo $this->db->last_query();    
    if($result!=1){
        $result=0;
    }
    return $result;    
}

function getKerugian($idPerusahaan){
    /*
    SELECT sum(p.jumlah_pembayaran) as realisasi, 
(hk.kerugian_lingkungan+hk.kerugian_immateriil+hk.biaya_pemulihan) as kewajiban_bayar,
(hk.kerugian_lingkungan+hk.kerugian_immateriil+hk.biaya_pemulihan)-sum(p.jumlah_pembayaran) as sisa
from pnbp p
JOIN histori_kasus hk on p.id_perusahaan = hk.lp_profil_perusahaan_id_perusahaan
WHERE
p.id_perusahaan="ASU" 
and p.cicilan_ke <= 2
and hk.tgl_pelaksanaan = (SELECT MAX(his.tgl_pelaksanaan) FROM histori_kasus his WHERE his.lp_profil_perusahaan_id_perusahaan="ASU")
GROUP BY p.id_perusahaan
     */
    $this->db->select("(hk.kerugian_lingkungan+hk.kerugian_immateriil+hk.biaya_pemulihan) as kewajiban_bayar");
    $this->db->select("sum(p.jumlah_pembayaran) as realisasi");
    $this->db->select("(hk.kerugian_lingkungan+hk.kerugian_immateriil+hk.biaya_pemulihan)-sum(p.jumlah_pembayaran) as sisa");
}

}