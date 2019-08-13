<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-13
 * Time: 22:18
 */

class Users extends CI_Model {

// function register

    function email_exists_active($email)
    {
        $this->db->where('user_email',$email);
        $this->db->where('isactive',1);
        $query = $this->db->get('users');
        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function email_exists_notactive($email)
    {
        $this->db->where('user_email',$email);
        $this->db->where('isactive',0);
        $query = $this->db->get('users');
        if($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function update_otp_as_new($data,$email)
    {
        $this->db->where('user_email',$email);
        $this->db->update("users",$data);
    }

    function get_user_by_email($email)
    {
        $this->db->select('*');
        $this->db->where('user_email',$email);
        $result=$this->db->get("users");
        return $result->result();
    }

    function add_new_user($data)
    {
        $this->db->insert("users",$data);
    }


    // function activate_user


    function activate($data,$email)
    {
        $this->db->where('user_email',$email);
        $this->db->update("users",$data);
    }

    //function session

    function is_token_exists($token)
    {
        $this->db->select('*');
        $this->db->where('user_token',$token);
        $this->db->where('isactive',1);
        $result=$this->db->get("users");
        if($result->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function get_user_by_token($token)
    {
        $this->db->select('*');
        $this->db->where('user_token',$token);
        $this->db->where('isactive',1);
        $result=$this->db->get("users");
        return $result->result();
    }

    //function login

    function can_login($email,$password)
    {
        $this->db->select('*');
        $this->db->where('user_email',$email);
        $this->db->where('user_password',$password);
        $this->db->where('isactive',1);
        $result=$this->db->get("users");
        if($result->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    function update_token($data,$email)
    {
        $this->db->where('user_email',$email);
        $this->db->update("users",$data);
    }

}