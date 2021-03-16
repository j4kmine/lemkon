<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class perolehan_satwa_new extends CI_Model {

function __contruct(){
	parent::__construct();
}
    public function getdata($id){
        //$noRegister=$this->db->escape($noRegister);
        
        $this->db->select("*");
        $this->db->from("perolehan_satwa_new per");
        $this->db->where("per.id_perolehan", $id);
        $query = $this->db->get();
        $result = $query->result();
        return $result;

    }

}