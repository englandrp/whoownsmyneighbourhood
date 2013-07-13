<?php

class Postcode extends MY_Controller {

	function Postcode()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        $data['title'] = 'Home';
        if (LOCAL) $data['js'] = array('/x/js/whoownsmyneighbourhood.js');
        else $data['js'] = array('http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=993F3ED59109FA3CE0405F0AC86041FE', '/x/js/whoownsmyneighbourhood.js');
        $data['postcode'] = $this->assess_postcode();
		$this->load->view('_head', $data);
		$this->load->view('_header', $data);
		$this->load->view('postcode', $data);
		$this->load->view('_foot', $data);
    }

    private function assess_postcode()
    {
        $this->form_validation->set_rules('postcode', 'postcode', 'trim|xss_clean');
        $success = $this->form_validation->run();
        $postcode = set_value('postcode');
        return $postcode;
    }
}

/* End of file postcode.php */
/* Location: ./system/application/controllers/postcode.php */