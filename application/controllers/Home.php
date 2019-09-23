<?php
/**
 * Created by PhpStorm.
 * User: sanathls
 * Date: 2019-08-13
 * Time: 22:52
 */

class Home extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('download');
	}
    public  function index()
    {
        $this->load->view('home');
    }

	public function download($fileName = NULL)
	{
		if ($fileName)
		{
			$file = FCPATH.'download/'.$fileName;
			// check file exists
			if (file_exists ( $file ))
			{
				// get file content
				$data = file_get_contents ( $file );
				//force download
				force_download ( $fileName, $data );
			}
			else
				{
				// Redirect to base url
				redirect ( base_url () );
			}
		}
	}
}
