<?php

class Contact extends MY_Controller {

	function Contact()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        $data['title'] = 'Contact';
        $data['nav'] = 'contact';
		$this->load->view('_head', $data);
		$this->load->view('_header', $data);
        $this->load->view('contact', $data);
		$this->load->view('_foot', $data);
    }

    ###########################################################################################
    ### FETCH FUNCTIONS ###
    #######################

    
    ###########################################################################################
    ### DATABASE FUNCTIONS ###
    ##########################

}

/* End of file contact.php */
/* Location: ./system/application/controllers/contact.php */