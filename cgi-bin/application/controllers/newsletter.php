<?php

class Newsletter extends MY_Controller {

    var $active_plots;

	function Newsletter()
	{
		parent::MY_Controller();	
	}
	
	function index($codename, $password)
	{
        if ($codename == '' && $password == '')
        {
            $this->_fetch_active_plots();
        }
    }


    ###########################################################################################
    ### PROCESS FUNCTIONS ###
    #########################

    
    ###########################################################################################
    ### FETCH FUNCTIONS ###
    #######################

    private function _fetch_active_plots()
    {
        $plots = $this->_select_contact_msgs_plots_recent();
        if (is_array($plots) && count($plots) > 0)
        {
            foreach ($plots as $plot)
            {
                $msgs = $this->_select_contact_msgs_for_plot_recent($plot->plot_id);
                if (is_array($msgs) && count($msgs) > 0)
                {
                    $subject = 'Who Owns My Neighbourhood > plot ' . $plot->plot_id . ' enquiries';
                    $body = 'Recent messages about plot ' . $plot->plot_id . ' on the Who Owns My Neighbourhood website that may be of interest' . "\n\n";
                    foreach($msgs as $msg)
                    {
                        $body .= date('l jS F H:i', $msg->timestamp);
                        $body .= "\n" . $msg->msg . "\n\n";
                    }
                    $body .= 'You are receiving these messages because you are subscribed as a community contact for this plot.' . "\n";
                    #$body .= 'To unsubscribe go to .' . "\n";
                    $subscribers = $this->_select_community_contacts_for_plot($plot->plot_id);
                    if (is_array($subscribers) && count($subscribers) > 0)
                    {
                        foreach($subscribers as $subscriber)
                        {
                            $this->_send_email($subscriber->email, $subject, $body, 'newsletter');
                        }
                    }
                }
            }
        }
    }

    ###########################################################################################
    ### DATABASE FUNCTIONS ###
    ##########################

    private function _select_community_contacts_for_plot($plot_id)
    {
        $sql = "
        SELECT u.email
        FROM community_contacts c, users u
        WHERE u.user_id = c.user_id
        AND c.plot_id = ".$this->db->escape($plot_id)."
        AND c.status = 'live'
        AND u.user_status = 'live'
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_contact_msgs_for_plot_recent($plot_id)
    {
        $time = time() - 86400; # one day ago
        $sql = "
        SELECT msg, timestamp
        FROM contact_msgs
        WHERE plot_id = $plot_id
        AND timestamp > $time
        AND status = 'live'
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_contact_msgs_plots_recent()
    {
        $time = time() - 86400; # one day ago
        $sql = "
        SELECT distinct(plot_id)
        FROM contact_msgs
        WHERE timestamp > $time
        AND status = 'live'
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

}

/* End of file newsletter.php */
/* Location: ./system/application/controllers/newsletter.php */