<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userapi extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->view('welcome_message');
	}

	public function register()
    {
        $user_name=$this->input->post('user_name');
        $user_email=$this->input->post('user_email');
        $user_usn=$this->input->post('user_usn');
        $user_phone=$this->input->post('user_name');
        $user_password=hash("SHA512",$this->input->post('user_password'));
        $user_token=$this->input->post('user_token');
        $user_device=$this->input->post('user_device');
        $user_otp=rand(1000,9999);


        if($user_name!=null && $user_email!=null && $user_usn!=null && $user_phone!=null && $user_password!=null && $user_token!=null && $user_device!=null && $user_otp!=null)
        {

            $data = array(
                'user_name'=> $user_name,
                'user_email' => $user_email,
                'user_usn' => $user_usn,
                'user_phone'=> $user_phone,
                'user_password' => $user_password,
                'user_token' => $user_token,
                'user_device' => $user_device,
                'user_otp' => $user_otp
            );
            $this->load->model('Users');
            //check email exists
            if($this->Users->email_exists_active($user_email))
            {
                $response['result']="failure";
                $response['message']="Email Already Exists";
                echo json_encode($response);
                return;
            }
            //update otp and send mail
            if($this->Users->email_exists_notactive($user_email))
            {
                $this->Users->update_otp_as_new($data,$user_email);
                //send otp here



                //

                $result=$this->Users->get_user_by_email($user_email);
                foreach ($result as $row)
                {
                    $row['result']="success";
                    echo json_encode($row);
                }
                return;
            }

            //add new user
            $this->users->add_new_user($data);
            //send otp through mail


            //
            $result=$this->Users->get_user_by_email($user_email);
            foreach ($result as $row)
            {
                $row['result']="success";
                echo json_encode($row);
            }
            return;

        }
    }
}
