<?php

class Region extends MY_Controller {

	function Region()
	{
		parent::MY_Controller();	
	}
	
	function index($region_id = '')
	{
        $data['title'] = 'Region';
        $data['region_id'] = $region_id;
		$this->load->view('_head', $data);
		$this->load->view('region', $data);
		$this->load->view('_foot', $data);
	}

}

/* End of file region.php */
/* Location: ./system/application/controllers/region.php */