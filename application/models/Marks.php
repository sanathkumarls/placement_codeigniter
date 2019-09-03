<?php


class Marks extends CI_Model
{
	function create_marks($data)
	{
		$this->db->insert("marks",$data);
	}

	function get_marks($email)
	{
		$sql="select * from users as u,marks as m where u.id=m.user_id and user_email='$email'";
		$result=$this->db->query($sql);
		return $result->result();
	}

	function update_marks($data,$user_id)
	{
		$this->db->where('user_id',$user_id);
		$this->db->update("marks",$data);
	}


	//
}
