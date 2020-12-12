<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class instansi extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function cekInstansi($id_prov){
	//$noRegister=$this->db->escape($noRegister);
	//$nama_instansi = $this->security->xss_clean($nama_instansi);
	$id_prov = $this->security->xss_clean($id_prov);
	//$this->db->select("count('id_instansi') as total_duplikasi");
	$this->db->select("id_instansi");
	$this->db->from("instansi1 i");
	//$this->db->join("refkategoristatus stat","stat.kategoristatus_id=adu.kategoristatus_id");
	//$this->db->where("i.nama_instansi",$nama_instansi);
	//$this->db->where("i.PROPINSI",$id_prov);
	$this->db->like("id_instansi",$id_prov, "after");
	//$this->db->group_by("PROPINSI");
	$this->db->order_by('id_instansi', 'DESC');
	$this->db->limit(1);
	$query = $this->db->get();	
	$result = $id_prov."0";
	foreach ($query->result_array() as $row)
	{
        $result = $row['id_instansi'];
	}
	$result++;
	return $result;
}

public function isDuplicate($id_prov, $namaInstansi){
	$nama_instansi = $this->security->xss_clean($namaInstansi);
	$id_prov = $this->security->xss_clean($id_prov);
	$this->db->select("count('id_instansi') as total_duplikasi");
	$this->db->from("instansi1 i");
	//$this->db->join("refkategoristatus stat","stat.kategoristatus_id=adu.kategoristatus_id");
	$this->db->where("i.nama_instansi",$nama_instansi);
	$this->db->where("i.PROPINSI",$id_prov);
	$this->db->group_by("PROPINSI");
	$query = $this->db->get();
	$temp = 0;
	foreach ($query->result_array() as $row)
	{
		$temp = $row['total_duplikasi'];
	}
	$result = ($temp >= 1)?TRUE:FALSE;
	
	return $result;
}
	
}