<?php

class Place extends MY_Controller {

	var $plot_id;
	
	function Place()
	{
		parent::MY_Controller();	
	}
	
	function index($plot_id='')
	{
        if ($this->userlogin->user_status == 'live')
		{
			$this->plot_id = $plot_id;
			$data['nav'] = '';
			
			if (LOCAL) $data['js'] = array('/x/js/whoownsmyneighbourhood.js');
			else $data['js'] = array('http://openspace.ordnancesurvey.co.uk/osmapapi/openspace.js?key=993F3ED59109FA3CE0405F0AC86041FE', '/x/js/whoownsmyneighbourhood.js');

			if ($this->_plot_is_owned_by_user()) 
			{
				$data['title'] = 'Edit a place';
				$data['editvalues'] = $this->_process_edit_plot();
			}
			else
			{
				$data['title'] = 'Add a place';
				$data['postvalues'] = $this->_process_create_plot();
			}
			
			$this->load->view('_head', $data);
			$this->load->view('_header', $data);
			if ($this->_plot_is_owned_by_user()) $this->load->view('place_edit', $data);
			else $this->load->view('place', $data);
			$this->load->view('_foot', $data);
		}
		else
		{
			$data['title'] = 'My Page';
			$data['nav'] = 'mypage';
			$this->load->view('_head', $data);
			$this->load->view('_header', $data);
			if ($_COOKIE['user'] == 'registered')
			{
				$this->load->view('mypage_signin', $data);
			}
			else
			{
				$this->load->view('mypage_register', $data);
			}
			$this->load->view('_foot', $data);		
		}
    }

    ###############################################################################################
    ##   PROCESS FUNCTIONS  ##
    ##########################

	private function _plot_is_owned_by_user()
	{
		if ($this->plot_id == '') return FALSE;
		$cnt = $this->_select_plot_count_for_owner();
		if ($cnt[0]->count > 0) return TRUE;
	}
	
    private function _process_edit_plot()
    {
		$d = array();
    	if ($_POST['action'] == 'Submit')
    	{
			$this->form_validation->set_rules('location', 'location', 'trim|required|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|max_length[1000]|xss_clean');
			$this->form_validation->set_rules('lr_title', 'lr_title', 'trim|max_length[20]|alphanumeric|xss_clean');
			$this->form_validation->set_rules('easting', 'easting', 'trim|xss_clean|less_than[394000]|greater_than[434000]');
			$this->form_validation->set_rules('northing', 'northing', 'trim|xss_clean|less_than[397000]|greater_than[435000]');
			$this->form_validation->set_rules('current_owner', 'Current owner', 'trim|max_length[1000]|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[1000]|xss_clean');
			$this->form_validation->set_rules('contact', 'Contact details', 'trim|max_length[1000]|xss_clean');
			$success = $this->form_validation->run();
			$d['location'] = set_value('location');
			$d['description'] = set_value('description');
			$d['lr_title'] = set_value('lr_title');
			$d['current_owner'] = set_value('current_owner');
			$d['address'] = set_value('address');
			$d['contact'] = set_value('contact');
			$d['easting'] = set_value('easting');
			$d['northing'] = set_value('northing');
			if ($success)
			{
				$this->_update_plot($d);
				$this->_update_loci($d['easting'], $d['northing']);
				//header('Location: /plot/' . $this->plot_id);
				header('Location: /landreg/unknown/' . $this->plot_id);
				exit;
			}
		}
		else
		{
			$data = $this->_select_plot_for_editing();
			$d['plot_id'] = $data[0]->id;
			$d['location'] = $data[0]->location;
			$d['description'] = $data[0]->description;
			$d['lr_title'] = $data[0]->lr_title;
			$d['current_owner'] = $data[0]->current_owner;
			$d['address'] = $data[0]->address;
			$d['contact'] = $data[0]->contact;
			$d['easting'] = $data[0]->east;
			$d['northing'] = $data[0]->north;
		}
		return $d;
    }

    private function _process_create_plot()
    {
    	//var_dump($_POST);
		$d = array();
    	if ($_POST['action'] == 'Submit')
    	{
			$this->form_validation->set_rules('location', 'location', 'trim|required|xss_clean');
			$this->form_validation->set_rules('description', 'description', 'trim|max_length[1000]|xss_clean');
			$this->form_validation->set_rules('lr_title', 'lr_title', 'trim|max_length[20]|alphanumeric|xss_clean');
			$this->form_validation->set_rules('easting', 'easting', 'trim|xss_clean|less_than[394000]|greater_than[434000]');
			$this->form_validation->set_rules('northing', 'northing', 'trim|xss_clean|less_than[397000]|greater_than[435000]');

			$this->form_validation->set_rules('current_owner', 'Current owner', 'trim|max_length[1000]|xss_clean');
			$this->form_validation->set_rules('address', 'Address', 'trim|max_length[1000]|xss_clean');
			$this->form_validation->set_rules('contact', 'Contact details', 'trim|max_length[1000]|xss_clean');
			
			
			$success = $this->form_validation->run();
			$d['location'] = set_value('location');
			$d['description'] = set_value('description');
			$d['lr_title'] = set_value('lr_title');
			$d['easting'] = set_value('easting');
			$d['northing'] = set_value('northing');
			$d['current_owner'] = set_value('current_owner');
			$d['address'] = set_value('address');
			$d['contact'] = set_value('contact');
			if ($success)
			{
				$plot_id = $this->_insert_plot($d);
				$this->_insert_loci($plot_id, $d['easting'], $d['northing']);
				//header('Location: /plot/' . $plot_id);
				header('Location: /landreg/unknown/' . $plot_id);
				exit;
			}	
		}
    	elseif ($_POST['submit'] == 'Add a place')
    	{
			$this->form_validation->set_rules('easting', 'easting', 'trim|xss_clean|less_than[394000]|greater_than[434000]');
			$this->form_validation->set_rules('northing', 'northing', 'trim|xss_clean|less_than[397000]|greater_than[435000]');
			$success = $this->form_validation->run();
			if ($success)
			{
				$d['easting'] = set_value('easting');
				$d['northing'] = set_value('northing');
			}
		}
		else
		{
			$d['easting'] = '414418.1';
			$d['northing'] = '416884.6';		
		}
		return $d;		
    }
	
    ###############################################################################################
    ##   DATABASE FUNCTIONS  ##
    ###########################

	private function _insert_loci($plot_id, $easting, $northing)
	{
        $sql = "
        INSERT INTO `all_loci`
        (
            al_id,
            al_plot_id,
            al_east,
            al_north,
            al_line,
            al_coords
        ) 
        VALUES
        (
        	NULL,
            ".$plot_id.",
            ".$easting.",
            ".$northing.",
            '',
            '".$easting.",".$northing."'
        );
        ";
        $this->db->query($sql);
	}

	private function _insert_plot($data)
	{
        $sql = "
        INSERT INTO `all_plots`
        (
            id,
            lr_title,
            lr_subtitle,
            current_owner,
            address,
            contact,
            deed_no,
            location,
            date,
            nature,
            website,
            east,
            north,
            grid_ref,
            description,
            user_id,
            owner
        ) 
        VALUES
        (
        	NULL,
            ".$this->db->escape($data['lr_title']).",
            1,
            ".$this->db->escape($data['current_owner']).",
            ".$this->db->escape($data['address']).",
            ".$this->db->escape($data['contact']).",
            '',
            ".$this->db->escape($data['location']).",
            '',
            '',
            '',
            ".$data['easting'].",
            ".$data['northing'].",
            '',
            ".$this->db->escape($data['description']).",
            ".$this->userlogin->user_id.",
            'private'            
        );
        ";
        $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;	
	}
	
	private function _select_plot_count_for_owner()
	{
        $sql = "
        SELECT COUNT(id) AS count FROM `all_plots` 
        WHERE id = ".$this->plot_id."
        AND user_id = ".$this->userlogin->user_id."
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
	private function _select_plot_for_editing()
	{
        $sql = "
        SELECT * FROM `all_plots` 
        WHERE id = ".$this->plot_id."
        AND user_id = ".$this->userlogin->user_id."
        AND owner = 'private'
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
	private function _update_loci($easting, $northing)
	{
        $sql = "
        UPDATE `all_loci`
        SET al_east = ".$easting.",
        al_north = ".$northing.",
        al_coords = '".$easting.",".$northing."'
        WHERE al_plot_id = ".$this->plot_id."
        ";
        $this->db->query($sql);
	}
	
	private function _update_plot($data)
	{
        $sql = "
        UPDATE `all_plots`
        SET east = ".$data['easting'].",
        north = ".$data['northing'].",
        location = ".$this->db->escape($data['location']).",
        description = ".$this->db->escape($data['description']).",
        lr_title = ".$this->db->escape($data['lr_title']).",
        current_owner = ".$this->db->escape($data['current_owner']).",
        address = ".$this->db->escape($data['address']).",
        contact = ".$this->db->escape($data['contact'])."
        WHERE id = ".$this->plot_id."
        AND owner = 'private'
        AND user_id = ".$this->userlogin->user_id."
        ";
        $this->db->query($sql);
	}
	
}

/* End of file place.php */
/* Location: ./system/application/controllers/place.php */