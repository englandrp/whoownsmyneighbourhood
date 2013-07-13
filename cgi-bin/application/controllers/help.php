<?php

class Help extends MY_Controller {

	function Help()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        $data['title'] = 'Help';
        $data['nav'] = 'help';
		$this->load->view('_head', $data);
		$this->load->view('_header', $data);
        $this->load->view('help', $data);
		$this->load->view('_foot', $data);
    }

    ###########################################################################################
    ### FETCH FUNCTIONS ###
    #######################

    
    ###########################################################################################
    ### DATABASE FUNCTIONS ###
    ##########################

}

/* End of file help.php */
/* Location: ./system/application/controllers/help.php */