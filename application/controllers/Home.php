<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-13
 * Time: 22:52
 */

class Home extends CI_Controller {

    public  function index()
    {
        $this->load->view('welcome_message');
    }
}