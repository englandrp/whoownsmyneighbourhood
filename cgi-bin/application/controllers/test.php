<?php

class Test extends MY_Controller {

	function Test()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
		//$this->_upgrade_aggregates(); // add plot_url (varchar 25) to `aggregates`
		//$this->_create_number_list(); // add lr_subtitle (varchar 4) to `all_plots` THEN - UPDATE lr_subtitle = '1'  
		//echo 'testing';
	}

	private function _upgrade_aggregates()
	{
		$data = $this->_select_many_aggregates();

		foreach ($data as $agg)
		{
			$this->_update_agg($agg);
		}	

	}
	
	private function _create_number_list()
	{
		$data = $this->_select_many_plots();
		
		$i = 1;
		$j = 0;
		foreach ($data as $plot)
		{
			if ($plot->lr_title == $last_plot->lr_title) 
			{
				//echo $last_plot->id . "<br />\n";
				$i++;
				$j++;
				echo $plot->id . ": " . $i . "<br />\n";
				$this->_update_plot_subtitle_number($plot->id, $i);
			}
			else
			{
				$i = 1;
			}
			$last_plot = $plot;
		}	
		echo 'Total: ' . $j;
		
	}
	
	private function _select_many_aggregates()
	{
        $sql = "
        SELECT a.ag_id, a.plot_id, p.lr_title, p.lr_subtitle FROM `aggregates` a, `all_plots` p
        WHERE p.id = a.plot_id
        ";
        $query = $this->db->query($sql);
        return $query->result();
	
	}
		
	private function _select_many_plots()
	{
        $sql = "
        SELECT id, lr_title FROM `all_plots`
        WHERE lr_title != ''
        ORDER BY lr_title
        ";
        $query = $this->db->query($sql);
        return $query->result();
	
	}
		
	private function _update_agg($agg)
	{
		if ($agg->lr_subtitle == '1') $url = '/landreg/' . $agg->lr_title;
		else $url = '/landreg/' . $agg->lr_title . '/' . $agg->lr_subtitle;
		//echo $url;
        $sql = "
        UPDATE `aggregates`
        SET plot_url = '" . $url."'
        WHERE ag_id = ".$agg->ag_id.";
        ";
        echo $sql . "<br />\n";
        $this->db->query($sql);
	}
	
	private function _update_plot_subtitle_number($id, $lr_subtitle)
	{
        $sql = "
        UPDATE `all_plots`
        SET lr_subtitle = '".$lr_subtitle."'
        WHERE id = ".$id.";
        ";
        //echo $sql . "<br />\n";
        $this->db->query($sql);
	}
	
	################################################################################################
	
	
	private function zz_email($page = '')
	{
        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = '';
        $config['smtp_user'] = ''; #No Default	None	SMTP Username.
        $config['smtp_pass'] = 	''; #No Default	None	SMTP Password.

        $this->email->initialize($config);
        $this->email->from('', 'Who Owns My Neighbourhood');
        $this->email->to(''); 
        $this->email->cc(''); 
        $this->email->subject('Test message from website');
        $this->email->message('This was an SMTP test message');

        $this->email->send();
    }
}

/* End of file test.php */
/* Location: ./system/application/controllers/test.php */