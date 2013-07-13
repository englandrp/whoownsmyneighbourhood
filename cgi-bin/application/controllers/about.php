<?php

class About extends MY_Controller {

	function About()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        $data['title'] = 'About';
        $data['nav'] = 'about';
		$this->load->view('_head', $data);
		$this->load->view('_header', $data);
        $this->load->view('about', $data);
		$this->load->view('_foot', $data);
    }

    ###########################################################################################
    ### FETCH FUNCTIONS ###
    #######################

    
    ###########################################################################################
    ### DATABASE FUNCTIONS ###
    ##########################

}

/* End of file about.php */
/* Location: ./system/application/controllers/about.php */