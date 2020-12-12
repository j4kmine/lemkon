<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class member extends CI_Model {

function __contruct(){
	parent::__construct();
}

function updatePassword($id, $pass, $selectDB = null){
    $this->db->db_select("php");
    if(isset($selectDB))
        $this->db->db_select($selectDB);
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

function cekPassLama($id, $pass, $selectDB = null){
    $pass =  $this->security->xss_clean($pass);
    $id =  $this->security->xss_clean($id);
    $this->db->db_select("php");
    if(isset($selectDB))
        $this->db->db_select($selectDB);
    
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

}