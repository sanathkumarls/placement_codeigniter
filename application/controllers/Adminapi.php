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

}