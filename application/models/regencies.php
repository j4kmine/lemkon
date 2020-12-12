<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class regencies extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function showKabupaten($idProv){
	//$noRegister=$this->db->escape($noRegister);
	//$cleanTxt = $this->db->escape($idProv);
	$this->db->select("id, name");
	$this->db->from("regencies reg");
	//$this->db->join("refkategoristatus stat","stat.kategoristatus_id=adu.kategoristatus_id");	
	$this->db->where("reg.id_provinsi", $idProv);
	$query = $this->db->get();
/*	$i=0;
	foreach ($query->result_array() as $row)
{
        $result['id'][$i] = $row['id'];
        $result['name'][$i] = $row['name'];        
        $i++;
}*/
	$result = $query->result();
	//$result = $this->db->last_query();
	return $result;
}
	
}