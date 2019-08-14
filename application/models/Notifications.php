<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-14
 * Time: 02:09
 */

class Notifications extends CI_Model {

    function have_notifications()
    {
        $this->db->select('*');
        $result=$this->db->get("notifications");
        if($result->num_rows() > 0)
        {
            return true;
        }
        return false;
    }

    function get_notifications()
    {
        $this->db->select('*');
        $this->db->order_by('timestamp','DESC');
        $result=$this->db->get("notifications");
        return $result;
    }

}