<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-14
 * Time: 02:19
 */

class Email extends CI_Model {


    public function sendmail($to)
    {

        $from="internship@manyathy.com";
        $name="Manyathy Business Solutions";
        $subject="Thank You For Registering";
        $body="";
        //mail
        $config=array(
            'protocol' => 'smtp',
            'smtp_host' => 'mail.manyathy.com',
            'smtp_port' => '587',
            'smtp_user' => 'internship@manyathy.com',
            'smtp_pass' => 'manyathy@admin',
            'mailtype' => 'html',
            'charset' => 'iso-8859-1',
            'wordwrap' => TRUE
        );
        $this->load->library('email',$config);
        $this->email->set_newline("\r\n");
        $this->email->from($from,$name);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($body);
        if($this->email->send())
        {
            //redirect(base_url()."internship/registered");
        }
        redirect(base_url()."internship/registered");

    }
}