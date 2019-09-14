<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-17
 * Time: 11:16
 */


defined('BASEPATH') OR exit('No direct script access allowed');

class Adminapi extends CI_Controller
{

    public function index()
    {
//        $data['a']="1";
//        $data['b']="2";
//        $data['c']="3";
//
//        echo $t=json_encode($data,);
//
//        $v=json_decode($t);
//        foreach ($v as $row)
//        {
//            echo $row;
//        }


    }

    public function send_notification()
    {
        $user_email=$this->input->post('user_email');
        $title=$this->input->post('title');
        $description=$this->input->post('description');
        $link=$this->input->post('link');

        if($user_email != null && $title !=null && $description != null &&  $link != null)
        {
            $this->load->Model('Users');
            if($this->Users->is_admin($user_email))
            {
                $data = array(
                    'title' => $title,
                    'description' => $description,
                    'link' =>$link
                );
                $this->load->Model('Notifications');
                $this->Notifications->put_notification($data);
                $response['result']="success";
                echo json_encode($response);
                $this->load->Model('Firebase');
                $result=$this->Firebase->get_token();
                foreach ($result->result() as $row)
                {
                    $this->firebase_notification($row->token,$title);
                }
            }
            else
            {
                $response['result']="failure";
                $response['message']="Admin Can Only Access";
                echo json_encode($response);
                return;
            }
        }
        else
        {
            $response['result']="failure";
            $response['message']="Fill All Fields";
            echo json_encode($response);
        }
    }


    public function firebase_notification($device_id,$message)
    {


        $url = 'https://fcm.googleapis.com/fcm/send';

        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    $api_key = 'AAAAZgRutYk:APA91bFsQPJG2wfitpyCI3qzpTgV7-G5wObbgwkteAA9TbIInRnziimiZtbmSPe0HTc9KtsalzUbMTY4n0rqa7uxf6-0vdN4DEwhIWsjIomLdsbgux3dyPINKg4ijzsopSjE_UEmvtlR';

        $fields = array (
            'registration_ids' => array (
                $device_id
            ),
            'data' => array (
                "message" => $message,
                "priority"=> "high",
                "title"=> "New Alert"
            )
        );

        //header includes Content type and api key
        $headers = array(
            'Content-Type:application/json',
            'Authorization:key='.$api_key
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        echo $result;

    }

	public function filter_users()
	{
		$admin_email=$this->input->post('admin_email');
		$sslc=$this->input->post('sslc');
		$sslc_score=$this->input->post('sslc_score');
		$puc=$this->input->post('puc');
		$puc_score=$this->input->post('puc_score');
		$cgpa=$this->input->post('cgpa');
		$cgpa_score=$this->input->post('cgpa_score');


		if($admin_email != null)
		{
			$this->load->Model('Users');
			if ($this->Users->is_admin($admin_email))
			{
				$this->load->Model('Marks');
				if($sslc == "yes" && $puc == "no" && $cgpa == "no")
				{
					//filter by sslc only
//					$response['result']="failure";
//					$response['message']="filter by sslc only";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_sslc($sslc_score);
				}
				elseif ($sslc == "no" && $puc == "yes" && $cgpa == "no")
				{
					//filter by puc only
//					$response['result']="failure";
//					$response['message']="filter by puc only";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_puc($puc_score);
				}
				elseif ($sslc == "no" && $puc == "no" && $cgpa == "yes")
				{
					//filter by cgpa only
//					$response['result']="failure";
//					$response['message']="filter by cgpa only";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_cgpa($cgpa_score);

				}
				elseif ($sslc == "yes" && $puc == "yes" && $cgpa == "no")
				{
					//filter by sslc and puc only
//					$response['result']="failure";
//					$response['message']="filter by sslc and puc only";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_sslc_and_puc($sslc_score,$puc_score);
				}
				elseif ($sslc == "yes" && $puc == "no" && $cgpa == "yes")
				{
					//filter by sslc and cgpa only
//					$response['result']="failure";
//					$response['message']="filter by sslc and cgpa only";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_sslc_and_cgpa($sslc_score,$cgpa_score);
				}
				elseif ($sslc == "no" && $puc == "yes" && $cgpa == "yes")
				{
					//filter by puc and cgpa only
//					$response['result']="failure";
//					$response['message']="filter by puc and cgpa only";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_puc_and_cgpa($puc_score,$cgpa_score);
				}
				elseif ($sslc == "yes" && $puc == "yes" && $cgpa == "yes")
				{
					//filter by sslc , puc and cgpa
//					$response['result']="failure";
//					$response['message']="filter by sslc , puc and cgpa";
//					echo json_encode($response);

					$result=$this->Marks->filter_by_sslc_and_puc_and_cgpa($sslc_score,$puc_score,$cgpa_score);
				}
				elseif($sslc == "no" && $puc == "no" && $cgpa == "no")
				{
					//view all users
//					$response['result']="failure";
//					$response['message']="view all users";
//					echo json_encode($response);

					$result=$this->Marks->view_all_users();
				}
				else
				{
					//invalid params
					$response['result']="failure";
					$response['message']="Invalid Params";
					echo json_encode($response);
					return;
				}
				$i=0;
				$response['result']="success";
				foreach ($result->result() as $row)
				{
					$response['user_name'.$i]=$row->user_name;
					$response['user_email'.$i]=$row->user_email;
					$response['user_usn'.$i]=$row->user_usn;
					$response['user_phone'.$i]=$row->user_phone;
					$response['user_device'.$i]=$row->user_device;
					$response['sslc'.$i]=$row->sslc;
					$response['puc'.$i]=$row->puc;
					$response['sem1'.$i]=$row->sem1;
					$response['sem2'.$i]=$row->sem2;
					$response['sem3'.$i]=$row->sem3;
					$response['sem4'.$i]=$row->sem4;
					$response['sem5'.$i]=$row->sem5;
					$response['sem6'.$i]=$row->sem6;
					$response['sem7'.$i]=$row->sem7;
					$response['cgpa'.$i]=$row->cgpa;
					$i++;
				}
				$response['size']=$i;
				echo json_encode($response);
			}
			else
			{
				$response['result']="failure";
				$response['message']="Access Is Denied";
				echo json_encode($response);
			}
		}
		else
		{
			$response['result']="failure";
			$response['message']="Empty Request";
			echo json_encode($response);
		}
	}

	public function version()
	{
		$result['versioncode']=1;
		echo json_encode($result);
	}

}
