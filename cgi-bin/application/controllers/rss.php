<?php

class Rss extends MY_Controller {

	function Rss()
	{
		parent::MY_Controller();	
	}
	
	function index($ward='')
	{
		if ($ward == '')
		{
			$data['nav'] = 'rss';
			$data['wards'] = $this->_get_wards();
			$this->load->view('_head', $data);
			$this->load->view('_header', $data);
			$this->load->view('rsshome', $data);
			$this->load->view('_foot', $data);
		}
		else
		{
			$data['ward'] = $this->_get_ward($ward);
			if ($data['ward'])
			{
				$data['aggregates'] = $this->_get_rss_aggregates($data['ward'][0]);
				//var_dump($data);
				$this->load->view('rss', $data);
			}
		}
    }

    private function _get_ward($ward)
    {
        $data = $this->_select_ward($ward);
        return $data;
    }

    private function _get_rss_aggregates($ward)
    {
        $data = $this->_select_rss_aggregates($ward->w_min_x, $ward->w_max_x, $ward->w_min_y, $ward->w_max_y, 0, 10);
        return $data;
    }

	private function _select_rss_aggregates($min_x, $max_x, $min_y, $max_y, $start, $finish)
	{
        $sql = "
        SELECT a.plot_id, a.plot_url, a.content, a.timestamp
        FROM `aggregates` a, `all_plots` p
        WHERE a.plot_id = p.id
        AND p.east > $min_x
        AND p.east < $max_x
        AND p.north > $min_y
        AND p.north <  $max_y
        AND a.status = 'live'
        ORDER BY a.timestamp DESC
        LIMIT $start, $finish
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
	private function _select_ward($w_code)
	{
        $sql = "
        SELECT w_name, w_code, w_min_x, w_min_y, w_max_x, w_max_y
        FROM `wards`
        WHERE w_code = ".$this->db->escape($w_code)."
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
}

/* End of file rss.php */
/* Location: ./system/application/controllers/rss.php */