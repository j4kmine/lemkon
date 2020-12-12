<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class mst_senpi extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function getKondisiSenpi($idMap){
	$this->db->select("concat(js.nama_senjata, ' pada ', i.nama_instansi) as legend",FALSE);
	$this->db->select("sum(CASE WHEN s.KONDISI = 'BAI' THEN 1 ELSE 0 END) as baik");
	$this->db->select("sum(CASE WHEN s.KONDISI = 'HIL' THEN 1 ELSE 0 END) as hilang");
	$this->db->select("sum(CASE WHEN s.KONDISI = 'RBE' THEN 1 ELSE 0 END) as rusak_berat");
	$this->db->select("sum(CASE WHEN s.KONDISI = 'RRI' THEN 1 ELSE 0 END) as rusak_ringan");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");
	$this->db->join("jenis_senjata js","js.id_senjata=s.JENIS_SENPI");
	
	$this->db->where("p.ID_MAP", $idMap);
	
	$this->db->group_by("s.JENIS_SENPI");
	$this->db->order_by("legend");
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapSenpiRusak(){
	$this->db->select("p.ID_MAP");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS >= curdate() THEN 1 ELSE 0 END) as PAS_berlaku");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS < curdate() THEN 1 ELSE 0 END) as PAS_kadaluarsa");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");
        
    $this->db->where("YEAR(s.TGL_HABIS_PAS)", date('Y'));
	
	$this->db->group_by("p.ID");
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapBukuPas(){
	
	$this->db->select("p.NAME as Provinsi");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS >= curdate() THEN 1 ELSE 0 END) as PAS_berlaku");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS < curdate() THEN 1 ELSE 0 END) as PAS_kadaluarsa");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");
        
    $this->db->where("YEAR(s.TGL_HABIS_PAS)", date('Y'));
	
	$this->db->group_by("p.ID");
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapBukuPasProv($idProv){
    $this->db->select("i.NAMA_INSTANSI");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS >= curdate() THEN 1 ELSE 0 END) as PAS_berlaku");
	$this->db->select("sum(CASE WHEN s.TGL_HABIS_PAS < curdate() THEN 1 ELSE 0 END) as PAS_kadaluarsa");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	//$this->db->join("provinces p","i.PROPINSI = p.ID");
        
        $this->db->where("YEAR(pas.TGL_HABIS_PAS)", "YEAR(curdate())");
        $this->db->like("s.ID_INSTANSI", $idProv, "after");
	
	$this->db->group_by("s.ID_INSTANSI");
	
	$query = $this->db->get();	
	$result = $query->result();
	
	return $result;
}

public function getAllKondisi(){
	$this->db->select("p.NAME as Provinsi");
	//$this->db->select("kb.nama_kondisi as kondisi");
	//$this->db->select("count(KONDISI) as jml_kondisi");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Baik' THEN 1 ELSE 0 END) as kondisi_baik");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Hilang' THEN 1 ELSE 0 END) as kondisi_hilang");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Rusak Berat' THEN 1 ELSE 0 END) as kondisi_rb");
	$this->db->select("sum(CASE WHEN kb.nama_kondisi = 'Rusak Ringan' THEN 1 ELSE 0 END) as kondisi_rr");
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
	$this->db->join("provinces p","i.PROPINSI = p.ID");	
	$this->db->join("kondisi_barang kb", "kb.id_kondisi = s.kondisi");
	
	$this->db->group_by("s.ID_INSTANSI");
	$this->db->order_by("p.Name");
	
	
	$query = $this->db->get();	
	$result = $query->result();
	//echo $this->db->last_query();
	return $result;
}

public function getRekapSenpi() {
    //apakah jumlah senpi selalu satu per BAP??
    $this->db->select("p.NAME, s.JENIS_SENPI, COUNT(s.JENIS_SENPI) as jml_senpi");
    $this->db->from("mst_senpi s");
	
    $this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
    $this->db->join("provinces p","i.PROPINSI = p.ID");	

    $this->db->group_by("s.JENIS_SENPI, p.ID");
    
    $this->db->order_by("p.name");

    $query = $this->db->get();	
    $result = $query->result();
    return $result;
}

public function getSenpiMap(){
	$this->db->select("p.ID_MAP");
	$this->db->select("COUNT(s.ID_INSTANSI) as jml_senpi");	
	
	$this->db->from("mst_senpi s");
	
	$this->db->join("instansi1 i", "s.ID_INSTANSI=i.ID_INSTANSI");
	$this->db->join("provinces p", "i.PROPINSI=p.ID");
	
	$this->db->group_by("p.ID");
	
	$query = $this->db->get();	
    $result = $query->result();
    return $result;
}

public function getSenpiPerProp($idProv){
    $this->db->select("i.NAMA_INSTANSI, s.JENIS_SENPI, COUNT(s.JENIS_SENPI) as jml_senpi");
    $this->db->from("mst_senpi s");
	
    $this->db->join("instansi1 i","i.ID_INSTANSI = s.ID_INSTANSI");
    //$this->db->join("provinces p","i.PROPINSI = p.ID");	
    
    $this->db->like("s.ID_INSTANSI", $idProv, "after");

    $this->db->group_by("s.ID_INSTANSI");
    
    $this->db->order_by("i.NAMA_INSTANSI");

    $query = $this->db->get();	
    $result = $query->result();
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