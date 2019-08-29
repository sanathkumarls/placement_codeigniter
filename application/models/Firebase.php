<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-14
 * Time: 02:09
 */

class Firebase extends CI_Model {

    function add_new_token($data)
    {
        $this->db->insert("firebase",$data);
    }

    function get_token()
    {
        $this->db->select('token');
        $result=$this->db->get("firebase");
        return $result;
    }

}