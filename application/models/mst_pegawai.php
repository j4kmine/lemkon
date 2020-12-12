<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mst_pegawai extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function getPegawaiNIPGolJab($fingerId=NULL){
    $this->db->select("peg.nama");
    $this->db->select("peg.nip");
    $this->db->select("peg.golongan");
    $this->db->select("jab.nama_jabatan");

    $this->db->from("mst_pegawai peg");

    $this->db->join("mst_jabatan jab","peg.jabatan = jab.id_jabatan");
    //$this->db->join("provinces p","i.PROPINSI = p.ID");

    if($fingerId!=NULL){
        $this->db->where("peg.finger_print_ID", $fingerId);
    }
    

    //$this->db->group_by("s.ID_INSTANSI");

    $query = $this->db->get();	
    $result = $query->result();

    return $result;
}
}