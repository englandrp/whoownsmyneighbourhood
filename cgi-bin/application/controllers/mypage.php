<?php

class Mypage extends MY_Controller {

	function Mypage()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        $data['title'] = 'My Page';
        $data['nav'] = 'mypage';
		$this->load->view('_head', $data);
		$this->load->view('_header', $data);
        if ($this->userlogin->user_status == 'live')
        {
            $data['ccontacts'] = $this->_get_community_contacts();
            $data['contribs'] = $this->_get_contribs();
            $data['c_msgs'] = $this->_get_contact_msgs();
            $data['places'] = $this->_get_places();
            $this->load->view('mypage', $data);
        }
        elseif ($_COOKIE['user'] == 'registered')
        {
            $this->load->view('mypage_signin', $data);
        }
        else
        {
            $this->load->view('mypage_register', $data);
        }
		$this->load->view('_foot', $data);
    }

    ###########################################################################################
    ### FETCH FUNCTIONS ###
    #######################

    private function _get_places()
    {
        $data = $this->_select_plots_for_user();
        return $data;
    }
     private function _get_contact_msgs()
    {
        $data = $this->_select_contact_msgs_for_user();
        return $data;
    }
    
    private function _get_community_contacts()
    {
        $data = $this->_select_community_contacts_for_user();
        return $data;
    }
    
    private function _get_contribs()
    {
        $data = $this->_select_contributions_for_user();
        return $data;
    }
    
    ###########################################################################################
    ### DATABASE FUNCTIONS ###
    ##########################

    private function _select_contact_msgs_for_user()
    {
        $sql = "
        SELECT c.msg, c.timestamp, p.id, p.lr_title, p.lr_subtitle, p.location
        FROM contact_msgs c, all_plots p
        WHERE c.plot_id = p.id
        AND c.user_id = ".$this->userlogin->user_id."
        AND c.status = 'live'
        ORDER BY c.timestamp DESC
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_contributions_for_user()
    {
        $sql = "
        SELECT DISTINCT p.id, p.lr_title, p.lr_subtitle, p.location
        FROM contributions c, all_plots p
        WHERE c.plot_id = p.id
        AND c.user_id = ".$this->userlogin->user_id."
        AND c.status = 'live'
        ORDER BY c.timestamp DESC
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_community_contacts_for_user()
    {
        $sql = "
        SELECT DISTINCT p.id, p.lr_title, p.lr_subtitle, p.location
        FROM community_contacts c, all_plots p
        WHERE c.plot_id = p.id
        AND c.user_id = ".$this->userlogin->user_id."
        AND c.status = 'live'
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

	private function _select_plots_for_user()
	{
        $sql = "
        SELECT id, location
        FROM `all_plots`
        WHERE user_id = ".$this->userlogin->user_id."
        AND owner = 'private'
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
}

/* End of file mypage.php */
/* Location: ./system/application/controllers/mypage.php */