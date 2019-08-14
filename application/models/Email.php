<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-14
 * Time: 02:19
 */

class Email extends CI_Model {


    public function sendmail($to,$sub,$msg)
    {

        $from="";
        $name="";
        //mail
        $config=array(
            'protocol' => 'smtp',
            'smtp_host' => '',
            'smtp_port' => '587',
            'smtp_user' => '',
            'smtp_pass' => '',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );
        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from($from,$name);
        $this->email->to($to);
        $this->email->subject($sub);
        $this->email->message($msg);
        if($this->email->send())
        {
            return true;
        }
        return false;
    }
}