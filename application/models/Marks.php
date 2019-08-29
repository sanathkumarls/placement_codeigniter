<?php


class Marks extends CI_Model
{
	function create_marks($data)
	{
		$this->db->insert("marks",$data);
	}

}
