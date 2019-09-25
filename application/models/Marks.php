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

	function filter_by_sslc($sslc_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and sslc >= ".$sslc_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function filter_by_puc($puc_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and puc >= ".$puc_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function filter_by_cgpa($cgpa_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and cgpa >= ".$cgpa_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function filter_by_sslc_and_puc($sslc_score,$puc_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and sslc >= ".$sslc_score." and puc >= ".$puc_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function filter_by_sslc_and_cgpa($sslc_score,$cgpa_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and sslc >= ".$sslc_score." and cgpa >= ".$cgpa_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function filter_by_puc_and_cgpa($puc_score,$cgpa_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and puc >= ".$puc_score." and cgpa >= ".$cgpa_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function filter_by_sslc_and_puc_and_cgpa($sslc_score,$puc_score,$cgpa_score)
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and sslc >= ".$sslc_score." and puc >= ".$puc_score." and cgpa >= ".$cgpa_score." and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	function view_all_users()
	{
		$sql="select user_name,user_email,user_usn,user_phone,user_device,sslc,puc,sem1,sem2,sem3,sem4,sem5,sem6,sem7,cgpa from users,marks where marks.user_id=users.id and user_role = 0 order by user_name";
		return $this->db->query($sql);
	}

	//
}
