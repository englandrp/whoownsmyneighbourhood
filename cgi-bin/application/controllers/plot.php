<?php

class Plot extends MY_Controller {

	var $plot_id;
	
	function Plot()
	{
		parent::MY_Controller();	
	}
	
	function index($plot_id = '')
	{
        $this->plot_id = $plot_id;
        $data = array();
        if (LOCAL) $data['js'] = array('/x/js/jquery-ui-1.8.7.custom.min.js', '/x/js/whoownsmyneighbourhood.js');
        else $data['js'] = array('/x/js/jquery-ui-1.8.7.custom.min.js',  'http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=993F3ED59109FA3CE0405F0AC86041FE', '/x/js/whoownsmyneighbourhood.js');
        //else $data['js'] = array('/x/js/jquery-ui-1.8.7.custom.min.js', 'http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=94C589BE7F94D044E0405F0ACA606319', '/x/js/whoownsmyneighbourhood.js');
        $data['postcode'] = $_COOKIE['postcode'];
        $data['plot'] = $this->_select_plot();
        $data['localname'] = $this->_get_plot_contributions('localname');
        $data['history'] = $this->_get_plot_contributions('history');
        $data['news'] = $this->_get_plot_contributions('news');
        $data['orgs'] = $this->_get_plot_contributions('org');
        $this->load->view('_head', $data);
		$this->load->view('_header', $data);
		$this->load->view('plot', $data);
		$this->load->view('_foot', $data);
	}

    private function _select_plot()
    {
        $sql = "
        SELECT *
        FROM all_plots
        WHERE id = ".$this->db->escape($this->plot_id)."
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

}

/* End of file plot.php */
/* Location: ./system/application/controllers/plot.php */