<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class provinces extends CI_Model {

function __contruct(){
	parent::__construct();
}

public function showAllProvinces(){
	//$noRegister=$this->db->escape($noRegister);
	
	$this->db->select("id, name");
	$this->db->from("provinces adu");
	//$this->db->join("refkategoristatus stat","stat.kategoristatus_id=adu.kategoristatus_id");
	//$this->db->where("adu.pengaduan_no",$noRegister);
	$query = $this->db->get();
	$i=0;
	foreach ($query->result_array() as $row)
{
        $result['id'][$i] = $row['id'];
        $result['name'][$i] = $row['name'];        
        $i++;
}
	return $result;
}
	
}