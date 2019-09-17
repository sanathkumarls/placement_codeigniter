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
        $user_phone=$this->input->post('user_phone');
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

            $sub="Thank You For Registering";
            $msg="Your OTP For Registration Is : ".$user_otp;

            //update otp and send mail
            if($this->Users->email_exists_notactive($user_email))
            {
                $this->Users->update_otp_as_new($data,$user_email);
                //send otp here
                $this->load->model('Email');
                if(!$this->Email->sendmail($user_email,$sub,$msg))
                {
                    $response['result']="failure";
                    $response['message']="Server Error Try Later";
                    echo json_encode($response);
                    return;
                }
                $result[]=$this->Users->get_user_by_email($user_email);
                foreach ($result as $row)
                {
                    $row['result']="success";
                    echo json_encode($row);
                }
                return;
            }

            //add new user
            $this->Users->add_new_user($data);
            //create marks row by userid
			$values[]=$this->Users->get_user_by_email($user_email);
			foreach ($values as $row)
			{
				$id = $row[0]->id;
			}
			$var = array(
				'user_id' => $id
			);
			$this->load->model('Marks');
			$this->Marks->create_marks($var);
            //send otp through mail
            $this->load->model('Email');
            if(!$this->Email->sendmail($user_email,$sub,$msg))
            {
                $response['result']="failure";
                $response['message']="Server Error Try Later";
                echo json_encode($response);
                return;
            }

            //
            $result[]=$this->Users->get_user_by_email($user_email);
            foreach ($result as $row)
            {
                $row['result']="success";
                echo json_encode($row);
            }

        }
    }

    public function activate_user()
    {
        $user_email=$this->input->post('user_email');

        if($user_email != null)
        {
            $this->load->model('Users');
            $data = array(
                'isactive'=> 1
            );
            $this->Users->activate($data,$user_email);
            $response['result']="success";
            echo json_encode($response);

        }
    }

    public  function session()
    {
        $user_token=$this->input->post('user_token');
        if($user_token != null)
        {
            $this->load->model('Users');
            if($this->Users->is_token_exists($user_token))
            {
                $result[]=$this->Users->get_user_by_token($user_token);
                foreach ($result as $row)
                {
                    $row['result']="success";
                    echo json_encode($row);
                }
            }
            else
            {
                $row['result']="failure";
                echo json_encode($row);
            }

        }
    }

    public  function login()
    {
        $user_email=$this->input->post('user_email');
        $user_password=hash("SHA512",$this->input->post('user_password'));
        $user_token=$this->input->post('user_token');
        if($user_email != null && $user_password != null && $user_token != null)
        {
            $this->load->model('Users');
            if($this->Users->can_login($user_email,$user_password))
            {
                $data = array(
                    'user_token'=> $user_token
                );
                $this->Users->update_token($data,$user_email);
                $result[]=$this->Users->get_user_by_email($user_email);
                foreach ($result as $row)
                {
                    $row['result']="success";
                    echo json_encode($row);
                }
            }
            else
            {
                $response['result']="failure";
                $response['message']="Invalid Username Or Password";
                echo json_encode($response);
            }
        }
    }

    public  function logout()
    {
        $user_email=$this->input->post('user_email');
        if($user_email != null)
        {
            $this->load->model('Users');
            $data = array(
                'user_token'=> ''
            );
            $this->Users->update_token($data,$user_email);
            $response['result']="success";
            echo json_encode($response);
        }
        else
        {
            $response['result']="failure";
            $response['message']="Logout Failed";
            echo json_encode($response);
        }
    }


    public function firebase()
    {
        $user_token=$this->input->post('user_token');
        $user_device=$this->input->post('user_device');
        $data = array(

            'token' => $user_token,
            'device' => $user_device
        );
        $this->load->model('Firebase');
        $this->Firebase->add_new_token($data);
    }


    public function notifications()
    {
        $user_email=$this->input->post('user_email');
        //$user_email="sanathlslokanathapura@gmail.com";
        $this->load->model('Users');
        if($user_email != null)
        {
            if($this->Users->email_exists_active($user_email))
            {
                $this->load->model('Notifications');
                if($this->Notifications->have_notifications())
                {
                    $result=$this->Notifications->get_notifications();
                    $i=0;
                    $response['result']="success";
                    foreach ($result->result() as $row)
                    {
                        $response['title'.$i]=$row->title;
                        $response['description'.$i]=$row->description;
                        $response['link'.$i]=$row->link;
						$response['id'.$i]=$row->id;
                        $i++;
                    }
                    $response['size']=$i;
					$this->load->model('Users');
					if($this->Users->is_admin($user_email))
						$response['user_role']="1";
					else
						$response['user_role']="0";
                    echo json_encode($response);
                }
                else
                {
                    $response['result']="failure";
                    $response['message']="No Notifications";
                    echo json_encode($response);
                }
            }
            else
            {
                $response['result']="failure";
                $response['message']="Login Again";
                echo json_encode($response);
            }
        }
        else
        {
            echo "Data is Secure";
        }
    }

    public function forgot_password()
    {
        $user_email=$this->input->post('user_email');
        $user_otp=rand(1000,9999);
        $sub="Password Reset";
        $msg="Your OTP For Password Reset Is : ".$user_otp;
        if($user_email != null)
        {
            $this->load->model('Users');
            if($this->Users->email_exists_active($user_email))
            {
                $data = array(
                    'user_otp' => $user_otp
                );
                $this->Users->update_otp_as_new($data,$user_email);
                //send otp here
                $this->load->model('Email');
                if(!$this->Email->sendmail($user_email,$sub,$msg))
                {
                    $response['result']="failure";
                    $response['message']="Server Error Try Later";
                    echo json_encode($response);
                    return;
                }
                $result[]=$this->Users->get_user_by_email($user_email);
                foreach ($result as $row)
                {
                    $row['result']="success";
                    echo json_encode($row);
                }
            }
            else
            {
                $response['result']="failure";
                $response['message']="Email Not Registered";
                echo json_encode($response);
            }
        }
        else
        {
            echo "Data is Secure";
        }
    }

    public function update_password()
    {
        $user_email=$this->input->post('user_email');
        $user_password=hash("SHA512",$this->input->post('user_password'));
        $data = array(
            'user_email' => $user_email,
            'user_password' => $user_password
        );
        if($user_email != null && $user_password != null)
        {
            $this->load->model('Users');
            $this->Users->update_password($data,$user_email);
            $response['result']="success";
            echo json_encode($response);
        }
        $sub="Password Updated Successfully";
        $msg="You Have Successfully Updated Password For SDMIT Placement";
        $this->load->model('Email');
        $this->Email->sendmail($user_email,$sub,$msg);

    }

    public function change_password()
    {
        $user_email=$this->input->post('user_email');
        $user_password=hash("SHA512",$this->input->post('user_password'));
        $new_password=hash("SHA512",$this->input->post('new_password'));
        $this->load->model('Users');
        if($this->Users->check_old_password($user_email,$user_password))
        {
            $data = array(
                'user_password' => $new_password
            );
            $this->Users->update_password($data,$user_email);
            $response['result']="success";
            echo json_encode($response);
            $sub="Password Updated Successfully";
            $msg="You Have Successfully Updated Password For SDMIT Placement";
            $this->load->model('Email');
            $this->Email->sendmail($user_email,$sub,$msg);
        }
        else
        {
            $response['result']="failure";
            $response['message']="Old Password Not Matching";
            echo json_encode($response);
        }
    }

    public function get_marks()
	{
		$user_email=$this->input->post('user_email');
		if($user_email != null)
		{
			$this->load->model('Users');
			if ($this->Users->email_exists_active($user_email))
			{
				$this->load->model('Marks');
				$response[]=$this->Marks->get_marks($user_email);
				foreach ($response as $row)
				{
					$row['result']="success";
					echo json_encode($row);
				}
			}
			else
			{
				$response['result']="failure";
				$response['message']="Email Does Not Exist";
				echo json_encode($response);
			}
		}
		else
		{
			echo "data is secure";
		}
	}

	public function update_marks_and_name()
	{
		$user_email=$this->input->post('user_email');
		$user_name=$this->input->post('user_name');
		$user_usn=$this->input->post('user_usn');
		$sslc=$this->input->post('sslc');
		$puc=$this->input->post('puc');
		$sem1=$this->input->post('sem1');
		$sem2=$this->input->post('sem2');
		$sem3=$this->input->post('sem3');
		$sem4=$this->input->post('sem4');
		$sem5=$this->input->post('sem5');
		$sem6=$this->input->post('sem6');
		$sem7=$this->input->post('sem7');
		$cgpa=$this->input->post('cgpa');
		$phone=$this->input->post('phone');


		if($user_email != null && $user_name != null && $user_usn != null && $sslc != null && $puc != null && $sem1 != null && $sem2 != null && $sem3 != null && $sem4 != null && $sem5 != null && $sem6 != null && $sem7 != null && $cgpa != null && $phone != null)
		{
			//update name and usn
			$data = array(
				'user_name'=> $user_name,
				'user_usn' => $user_usn,
				'user_phone' => $phone
			);
			$this->load->model('Users');
			$this->Users->update_name_and_usn($data,$user_email);

			//get id and pass
			$result=$this->Users->get_user_by_email($user_email);
			foreach ($result as $row)
			$user_id=$row->id;
			$this->load->model('Marks');
			$marks = array(
				'sslc' => $sslc,
				'puc' => $puc,
				'sem1' => $sem1,
				'sem2' => $sem2,
				'sem3' => $sem3,
				'sem4' => $sem4,
				'sem5' => $sem5,
				'sem6' => $sem6,
				'sem7' => $sem7,
				'cgpa' => $cgpa
			);
			$this->Marks->update_marks($marks,$user_id);
			$response['result']="success";
			echo json_encode($response);
		}
		else
		{
			$response['result']="failure";
			$response['message']="All Fields Are Mandatory";
			echo json_encode($response);
		}

	}

}
