<?php

class Home extends MY_Controller {

	function Home()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        if (LOCAL) $data['title'] = 'LOCAL: Home';
        else $data['title'] = 'Home';
        if (LOCAL) $data['js'] = array('/x/js/whoownsmyneighbourhood.js');
        
        else $data['js'] = array('http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=993F3ED59109FA3CE0405F0AC86041FE', '/x/js/whoownsmyneighbourhood.js');
        $data['nav'] = 'home';
        $data['postvalues'] = $this->_assess_post_values();
        $data['aggregates'] = $this->_get_aggregates();
        $data['wards'] = $this->_get_wards();
		$this->load->view('_head', $data);
		$this->load->view('_header', $data);
		$this->load->view('home', $data);
		$this->load->view('_foot', $data);
    }

    private function _assess_post_values()
    {
    	$d = array();
        $this->form_validation->set_rules('postcode', 'postcode', 'trim|xss_clean');
        $this->form_validation->set_rules('js_easting', 'js_easting', 'trim|xss_clean');
        $this->form_validation->set_rules('js_northing', 'js_northing', 'trim|xss_clean');
        $success = $this->form_validation->run();
        $d['postcode'] = set_value('postcode');
        $d['easting'] = set_value('js_easting');
        $d['northing'] = set_value('js_northing');
        return $d;
    }

    private function _get_aggregates()
    {
        $data = $this->_select_aggregates(0, 10);
        return $data;
    }

	private function _select_aggregates($start, $finish)
	{
       $sql = "
        SELECT plot_id, plot_url, content
        FROM `aggregates`
        WHERE status = 'live'
        ORDER BY timestamp DESC
        LIMIT $start, $finish
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
}

/* End of file home.php */
/* Location: ./system/application/controllers/home.php */