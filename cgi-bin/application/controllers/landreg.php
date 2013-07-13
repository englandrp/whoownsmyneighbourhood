<?php

class LandReg extends MY_Controller {

	var $plot_id;
	var $lr_title;
	var $lr_subtitle;
	
	function LandReg()
	{
		parent::MY_Controller();	
	}
	
	function index($lr_title = '', $lr_subtitle = '')
	{
        $this->lr_title = $lr_title;
        if ($lr_subtitle == '') $this->lr_subtitle = '1';
        else $this->lr_subtitle = $lr_subtitle;

        $data = array();
        if (LOCAL) $data['js'] = array('/x/js/jquery-ui-1.8.7.custom.min.js', '/x/js/whoownsmyneighbourhood.js');
        else $data['js'] = array('/x/js/jquery-ui-1.8.7.custom.min.js',  'http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=993F3ED59109FA3CE0405F0AC86041FE', '/x/js/whoownsmyneighbourhood.js');
        //else $data['js'] = array('/x/js/jquery-ui-1.8.7.custom.min.js', 'http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=94C589BE7F94D044E0405F0ACA606319', '/x/js/whoownsmyneighbourhood.js');
        $data['postcode'] = $_COOKIE['postcode'];
        $data['plot'] = $this->_select_plot_by_landreg();
        if ($data['plot'])
        {
        	$this->plot_id = $data['plot'][0]->id;
			$data['localname'] = $this->_get_plot_contributions('localname');
			$data['history'] = $this->_get_plot_contributions('history');
			$data['news'] = $this->_get_plot_contributions('news');
			$data['orgs'] = $this->_get_plot_contributions('org');
			$this->load->view('_head', $data);
			$this->load->view('_header', $data);
			$this->load->view('plot', $data);
			$this->load->view('_foot', $data);
		}
	}

    private function _select_plot_by_landreg()
    {
        $sql = "
        SELECT *
        FROM all_plots
        WHERE lr_title = ".$this->db->escape($this->lr_title)."
        AND lr_subtitle = ".$this->db->escape($this->lr_subtitle)."
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

}

/* End of file landreg.php */
/* Location: ./system/application/controllers/landreg.php */