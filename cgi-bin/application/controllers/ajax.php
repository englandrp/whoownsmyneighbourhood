<?php

class Ajax extends MY_Controller {

    var $plot_id;
    var $east;
    var $north;
    var $ward_code;
    var $w_min_x;
    var $w_min_y;
    var $w_max_x;
    var $w_max_y;

	function Ajax()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{

    }

    function bboxplots()
    {
        parse_str(substr($_SERVER['REQUEST_URI'], 16), $_GET);
        $data['plots'] = $this->_get_bb_plots();
    	$this->load->view('ajax/bboxplots', $data);
    }

    function bboxpoints()
    {
        parse_str(substr($_SERVER['REQUEST_URI'], 17), $_GET);
        $data['plots'] = $this->_get_bb_plots();
    	//$data['plots'] = $this->_select_plots_for_bbox('411115.78', '412315.78', '415988.44', '416788.44');
    	$this->load->view('ajax/bboxpoints', $data);
    }

	function contactmsg($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['contact_msg'] = $this->_process_contact_msg();
        $this->load->view('ajax/contact_msgs', $data);
	}

	function contacts($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['plot'] = $this->_get_plot_info($plot_id);
        $data['contactcount'] = $this->_get_plot_contact_statement();
        $data['contactstatus'] = $this->_get_contact_status_for_plot();
        $data['orgs'] = $this->_get_plot_contributions('org');
        $this->load->view('ajax/contacts', $data);
	}

	function contribute($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['localname'] = $this->_get_plot_contributions('localname');
        $data['history'] = $this->_get_plot_contributions('history');
        $data['news'] = $this->_get_plot_contributions('news');
        $data['orgs'] = $this->_get_plot_contributions('org');
        $this->load->view('ajax/contribute', $data);
	}

	function flickr()
	{
        $data = array();
        $tags = 'Newsome, Huddersfield, UK';
        $data['flickr_images'] = $this->_xml_get_flickr_image_data_for_tags($tags);
		$this->load->view('ajax/flickr', $data);
	}

    function info($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['plot'] = $this->_get_plot_info($plot_id);
        $this->load->view('ajax/info', $data);
	}

    function localhistory($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['localhistory'] = $this->_process_contribute('history');
        $this->load->view('ajax/localhistory', $data);
	}

    function localname($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['localname'] = $this->_process_contribute('localname');
        $this->load->view('ajax/localname', $data);
	}

    function localnews($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['localnews'] = $this->_process_contribute('news');
        $this->load->view('ajax/localnews', $data);
	}

    function localorg($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['localorg'] = $this->_process_contribute_org();
        $this->load->view('ajax/localorg', $data);
	}

	function mycontact($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['my_contact'] = $this->_process_my_contact();
        $this->load->view('ajax/my_contact', $data);
	}

	function myupdates($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['my_updates'] = $this->_process_my_updates();
        $this->load->view('ajax/my_updates', $data);
	}

	function plot($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['points'] = $this->_get_plot_points();
		$this->load->view('ajax/plot', $data);
	}

    function textfile()
    {
        $op = 'point	title	description	icon' . "\n";
        $op .= '10,20	my orange title	my orange description' . "\n";	
        $op .= '2,4	my aqua title	my aqua description	' . "\n";
        $op .= '42,-71	my purple title	my purple description<br/>is great.	http://www.openlayers.org/api/img/zoom-world-mini.png' . "\n";
        echo $op;
    }

    function update($plot_id = '')
	{
        $this->_set_plot_id($plot_id);
        $data['newsletterstatus'] = $this->_get_newsletter_status_for_plot();
        $this->load->view('ajax/update', $data);
	}

    function uri()
    {
        parse_str(substr($_SERVER['REQUEST_URI'], 10), $_GET);
        echo (substr($_SERVER['REQUEST_URI'], 10));
        $this->_insert_get_string(substr($_SERVER['REQUEST_URI'], 10));
        #var_dump($_GET);
        #var_dump($_GET);
    }

	function ward($ward_code = '')
	{
        $this->ward_code = $ward_code;
        $ward = $this->_select_ward();
        if ($ward)
        {
            $this->w_min_x = $ward[0]->w_min_x;
            $this->w_min_y = $ward[0]->w_min_y;
            $this->w_max_x = $ward[0]->w_max_x;
            $this->w_max_y = $ward[0]->w_max_y;
            $data['markers'] = $this->_select_plots_ward();
    		$this->load->view('ajax/ward', $data);
        }
	}

	function block($y, $x)
	{
            $this->w_min_x = $x - 800;
            $this->w_min_y = $y - 400;
            $this->w_max_x = $x + 800;
            $this->w_max_y = $y + 400;
            $data['markers'] = $this->_select_plots_ward();
    		$this->load->view('ajax/ward', $data);
	}

    ###########################################################################################
    ### PROCESS FUNCTIONS ###
    #########################

    private function _get_bb_plots()
    {
        if ($_GET['bbox'] != '')
        {
            //$this->ward_code = 'newsome';
            //$ward = $this->_select_ward();
            //if ($ward)
            //{
                $this->w_min_x = $ward[0]->w_min_x;
                $this->w_min_y = $ward[0]->w_min_y;
                $this->w_max_x = $ward[0]->w_max_x;
                $this->w_max_y = $ward[0]->w_max_y;
                $bb_pts = explode(',', $_GET['bbox']);

                /*
                if ($bb_pts[0] < $ward[0]->w_min_x) $bb_pts[0] = $ward[0]->w_min_x;
                if ($bb_pts[1] < $ward[0]->w_min_y) $bb_pts[1] = $ward[0]->w_min_y;
                if ($bb_pts[2] > $ward[0]->w_max_x) $bb_pts[2] = $ward[0]->w_max_x;
                if ($bb_pts[3] < $ward[0]->w_max_y) $bb_pts[3] = $ward[0]->w_max_y;

                $min_x = max($bb_pts[0], $ward[0]->w_min_x);
                $min_y = max($bb_pts[1], $ward[0]->w_min_y);
                $max_x = min($bb_pts[2], $ward[0]->w_max_x);
                $max_y = min($bb_pts[3], $ward[0]->w_max_y);
                $data = $this->_select_plots_for_bbox($min_x, $max_x, $min_y, $max_y);

                */

                $data = $this->_select_plots_for_bbox($bb_pts[0], $bb_pts[2], $bb_pts[1], $bb_pts[3]);
                return $data;

            //}
        }
    }

    private function _set_plot_id($plot_id)
    {
        if (is_numeric($plot_id)) $this->plot_id = $plot_id;
    }

    private function _process_contribute_org()
    {
        $d = array();
        if ($this->input->post('org') != '' || $this->input->post('url') != '')
        {
            $this->form_validation->set_rules('org', 'Organisation name', 'trim|required|min_length[1]|xss_clean');
            $this->form_validation->set_rules('url', 'Web Address', 'trim|required|min_length[1]|xss_clean');
            $success = $this->form_validation->run();
            $d['org'] = set_value('org');
            $d['url'] = set_value('url');
            if ($success)
            {
                $_ser = serialize(array($d['org'], $d['url']));
                if ($this->userlogin->user_status == 'live')
                {
                    $this->_insert_contribution('org', $_ser, $this->userlogin->user_id, '', 'live');
					$plot_data = $this->_get_plot_info($this->plot_id);
					if ($plot_data[0]->owner == 'council' && $plot_data[0]->lr_title != '')
					{
						$url = '/landreg/' . $plot_data[0]->lr_title;
						if ($plot_data[0]->lr_subtitle != '1') $url .= '/' . $plot_data[0]->lr_subtitle;
					}
					else
					{
						$url = '/landreg/unknown/' . $this->plot_id;
					}
                    
					$this->_add_aggregate($this->plot_id, $url, $intro . 'Local organisation: ' . $d['org']);
                    $d['li'] = '<li><a href="' . $d['url'] . '">' . $d['org'] . '</a></li>';
                    $d['org'] = '';
                    $d['url'] = '';
                    $d['alert'] = 'Your contribution has been added on the right';
                }
                else
                {
                    $md5code = md5('gs3H6igh' . $_ser . time());
                    $c_id = $this->_insert_contribution('org', $_ser, 0, $md5code, 'hold');
                    $this->_store_c_id_as_cookie($md5code . $c_id);
                    if ($_COOKIE['user'] == 'registered') $d['redirect'] = 'signin';
                    else $d['redirect'] = 'register';
                }
            }
        }
        return $d;
    }

    private function _process_contribute($type)
    {
        $d = array();
        if ($this->input->post('msg') != '')
        {
            $this->form_validation->set_rules('msg', 'Message', 'trim|required|min_length[1]|xss_clean');
            $success = $this->form_validation->run();
            $d['msg'] = set_value('msg');
            if ($success)
            {
                if ($this->userlogin->user_status == 'live')
                {
                    $this->_insert_contribution($type, $d['msg'], $this->userlogin->user_id, '', 'live');
                    $this->_add_contribution_aggregate($type, $d['msg']);
                    $d['li'] = '<li>' . $d['msg'] . '<br /><span class="quietly">' . date('jS F Y, H:i', time()) . '</span></li>';
                    $d['msg'] = '';
                    $d['alert'] = 'Your contribution has been added on the right';
                }
                else
                {
                    $md5code = md5('gs3H6igh' . $d['msg'] . time());
                    $c_id = $this->_insert_contribution($type, $d['msg'], 0, $md5code, 'hold');
                    $this->_store_c_id_as_cookie($md5code . $c_id);
                    if ($_COOKIE['user'] == 'registered') $d['redirect'] = 'signin';
                    else $d['redirect'] = 'register';
                }
            }
        }
        return $d;
    }
    
    private function _add_contribution_aggregate($type, $msg)
    {
		$plot_data = $this->_get_plot_info($this->plot_id);
		if ($plot_data[0]->owner == 'council' && $plot_data[0]->lr_title != '')
		{
			$url = '/landreg/' . $plot_data[0]->lr_title;
			if ($plot_data[0]->lr_subtitle != '1') $url .= '/' . $plot_data[0]->lr_subtitle;
		}
		else
		{
			$url = '/landreg/unknown/' . $this->plot_id;
		}

    	switch ($type)
    	{
    		case 'localname':
				$this->_add_aggregate($this->plot_id, $url, $intro . 'Local names: ' . $msg);
    			break;
    		case 'history':
				$this->_add_aggregate($this->plot_id, $url, $intro . 'Local history: ' . $msg);
    			break;
    		case 'news':
				$this->_add_aggregate($this->plot_id, $url, $intro . 'Local news: ' . $msg);
    			break;
    	}
    }


    private function _store_c_id_as_cookie($new_c_id)
    {
        $c_ids = $_COOKIE['c_ids'];
        setcookie('c_ids[0]', $new_c_id, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
        $i = 1;
        if (is_array($c_ids)) {
            foreach ($c_ids as $key => $value) {
                if ($value != $c_ids  && $i < 5) {
                    setcookie('c_ids['.$i.']', $value, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                    $i++;
                }
            }
        }
    }

    private function _store_cc_id_as_cookie($new_cc_id)
    {
        $c_ids = $_COOKIE['c_ids'];
        setcookie('cc_ids', $new_c_id, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
        $i = 1;
        if (is_array($c_ids)) {
            foreach ($c_ids as $key => $value) {
                if ($value != $c_ids  && $i < 5) {
                    setcookie('c_ids['.$i.']', $value, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                    $i++;
                }
            }
        }
    }

    private function _get_contact_status_for_plot()
    {
        if ($this->userlogin->user_status == 'live' && $this->_is_community_contact())
        {
            return 'Remove me from the list of community contacts for this plot';
        }
        else
        {
            return 'Add me as a community contact for this plot';
        }
    }

    private function _get_newsletter_status_for_plot()
    {
        if ($this->userlogin->user_status == 'live' && $this->_is_on_newsletter_list())
        {
            return 'Unsubscribe me from email updates for this plot';
        }
        else
        {
            return 'Subscribe to email updates for this plot';
        }
    }

    private function _get_plot_contact_statement()
    {
        $_ccnt = $this->_select_community_contact_count();
        if ($_ccnt == '0') return 'This plot of land has no community contacts.';
        elseif ($_ccnt == '1') return 'This plot of land has one community contact.';
        else return 'This plot of land has ' . $_ccnt . ' community contacts.';
    }

    private function _process_my_contact()
    {
        $d = array();
        if ($this->userlogin->user_status == 'live')
        {
            if ($this->_is_community_contact())
            {
                $this->_update_community_contact_deleted();
                $d['alert'] = 'You are no longer a community contact for this plot';
                $d['statement'] = $this->_get_plot_contact_statement();
                $d['contactcount'] = $this->_get_contact_status_for_plot();
            }
            else
            {
                $this->_insert_community_contact('', 'live');
                
                $plot_data = $this->_get_plot_info($this->plot_id);
                if ($plot_data[0]->lr_title == '') $plot_name = $plot_data[0]->location;
                else $plot_name = $plot_data[0]->lr_title;
                if ($plot_data[0]->owner == 'council' && $plot_data[0]->lr_title != '')
                {
                	$url = '/landreg/' . $plot_data[0]->lr_title;
                	if ($plot_data[0]->lr_subtitle != '1') $url .= '/' . $plot_data[0]->lr_subtitle;
                }
                else
                {
                	$url = '/landreg/unknown/' . $this->plot_id;
                }
                $this->_add_aggregate($this->plot_id, $url, 'A community contact has been added to plot ' . $plot_name);
				
                $d['alert'] = 'You are now a community contact for this plot';
                $d['statement'] = $this->_get_plot_contact_statement();
                $d['contactcount'] = $this->_get_contact_status_for_plot();
            }
        }
        else
        {
            $md5code = md5(time() . $this->plot_id . 'ground slump');
            $cc_id = $this->_insert_community_contact($md5code, 'hold');
            setcookie('cc_id', $md5code.$cc_id, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
            if ($_COOKIE['user'] == 'registered') $d['redirect'] = 'signin';
            else $d['redirect'] = 'register';
        }
        return $d;
    }
        
    private function _process_my_updates()
    {
        $d = array();
        if ($this->userlogin->user_status == 'live')
        {
            if ($this->_is_on_newsletter_list())
            {
                $this->_update_newsletter_contact_deleted();
                $d['alert'] = 'You are no longer on the updates list for this plot';
                $d['linkwording'] = 'Subscribe to email updates for this plot';
            }
            else
            {
                $this->_insert_newsletter_contact('', 'live');
                
                $plot_data = $this->_get_plot_info($this->plot_id);
                if ($plot_data[0]->lr_title == '') $plot_name = $plot_data[0]->location;
                else $plot_name = $plot_data[0]->lr_title;
                if ($plot_data[0]->owner == 'council' && $plot_data[0]->lr_title != '')
                {
                	$url = '/landreg/' . $plot_data[0]->lr_title;
                	if ($plot_data[0]->lr_subtitle != '1') $url .= '/' . $plot_data[0]->lr_subtitle;
                }
                else
                {
                	$url = '/landreg/unknown/' . $this->plot_id;
                }
				$this->_add_aggregate($this->plot_id, $url, 'Another user has signed up for updates about plot ' . $plot_name);
                $d['alert'] = 'You are now on the updates list for this plot';
                $d['linkwording'] = 'Unsubscribe me from email updates for this plot';
            }
        }
        else
        {
            $md5code = md5(time() . $this->plot_id . 'open albukirkee');
            $nc_id = $this->_insert_newsletter_contact($md5code, 'hold');
            setcookie('nc_id', $md5code.$nc_id, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
            if ($_COOKIE['user'] == 'registered') $d['redirect'] = 'signin';
            else $d['redirect'] = 'register';
        }
        return $d;
    }

    private function _is_on_newsletter_list()
    {
        $_nc = $this->_select_newsletter_contact();
        if ($_nc == '0') return FALSE;
        else return TRUE;
    }

    private function _is_community_contact()
    {
        $_cc = $this->_select_communty_contact();
        if ($_cc == '0') return FALSE;
        else return TRUE;
    }

    private function _process_contact_msg()
    {
        $d = array();
        if ($_POST['msg'] != '')
        {
            $this->form_validation->set_rules('msg', 'Message', 'trim|required|min_length[1]|xss_clean');
            $success = $this->form_validation->run();
            $d['msg'] = set_value('msg');
            if ($success)
            {
                if ($this->userlogin->user_status == 'live')
                {
                    $this->_insert_contact_msg($d['msg'], '', 'live');
                    $plot_data = $this->_get_plot_info($this->plot_id);
                	if ($plot_data[0]->lr_title == '') $plot_name = $plot_data[0]->location;
                	else $plot_name = $plot_data[0]->lr_title;

					if ($plot_data[0]->owner == 'council' && $plot_data[0]->lr_title != '')
					{
						$url = '/landreg/' . $plot_data[0]->lr_title;
						if ($plot_data[0]->lr_subtitle != '1') $url .= '/' . $plot_data[0]->lr_subtitle;
					}
					else
					{
						$url = '/landreg/unknown/' . $this->plot_id;
					}
	                $this->_add_aggregate($this->plot_id, $url, 'A new message for community contacts of plot ' . $plot_name);
                    $d['msg'] = '';
                    $d['alert'] = 'Message sent';
                }
                else
                {
                    $md5code = md5(time() . $this->plot_id . 'sillybigot');
                    $cm_id = $this->_insert_contact_msg($d['msg'], $md5code, 'hold');
                    setcookie('cm_id', $md5code.$cm_id, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                    if ($_COOKIE['user'] == 'registered') $d['redirect'] = 'signin';
                    else $d['redirect'] = 'register';
                }
            }
        }
        return $d;
    }
    
    ###########################################################################################
    ### FETCH FUNCTIONS ###
    #######################

    private function _xml_get_flickr_image_data_for_tags($tags)
    {
        # add in thumbprint, at some point?
        #$url = 'http://api.flickr.com/services/feeds/photos_public.gne?tagmode=all&tags=' . urlencode($tags) . '&format=rss2';
        $_tags = array(
            0 => 'Huddersfield',
            1 => 'Dewsbury',
            2 => 'Batley',
            3 => 'Heckmondwike',
            4 => 'Kirklees',
            5 => 'Birstall',
            6 => 'Cleckheaton', 
            7 => urlencode('Denby Dale'), 
            8 => 'Holmfirth', 
            9 => 'Kirkburton', 
            10 => 'Marsden', 
            11 => 'Meltham', 
            12 => 'Mirfield', 
            13 => 'Slaithwaite', 
            14 => 'Newsome',
            15 => urlencode('Huddersfield Town'),
            16 => urlencode('Huddersfield Giants'),
            17 => urlencode('Castle Hill Huddersfield'),
            18 => urlencode('St George\'s Square Huddersfield'),
            19 => urlencode('Messiah Huddersfield'),
            20 => urlencode('Greenhead Park Huddersfield'),
            21 => urlencode('Crows Nest Park Huddersfield')
        );
        shuffle($_tags);
        $url = 'http://api.flickr.com/services/feeds/photos_public.gne?tagmode=any&tags=' . $_tags[0] . ',' . $_tags[1] . ',' . $_tags[2] . '&format=rss2';
        ini_set('display_errors', 0);
        $file_contents = file_get_contents($url);
        if ($file_contents)
        {
    		$this->load->library('Xml');
            $xml_parser = new xml();
            $xml_parser->parse($file_contents);
            $dom = $xml_parser->dom;
            $image_data = $this->_xml_get_image_data_from_DOM($dom);
            return $image_data;
        }
    }

    private function _xml_get_image_data_from_DOM($dom)
    {
        # get to the right page in the array
        $item_level = ($dom["child_nodes"][0]["child_nodes"][0]["child_nodes"]);
        $il_count = count($item_level);
        $i = 0;
        $img_array = array();
        if (is_array($item_level)) {
            foreach ($item_level as $key => $value) {
                if ($value["tag_name"] == 'item') {
                    if (is_array($value["child_nodes"])) {
                        $jpg = FALSE;
                        $gif = FALSE;
                        $png = FALSE;
                        foreach($value["child_nodes"] as $k => $v) {
                            if ($v["tag_name"] == "link") {
                                $img_array[$i]['link'] = $v["child_nodes"][0];
                            }

                            # check that the content is an image...
                            if ($v["tag_name"] == "media:content") {
                                switch ($v["attributes"]["type"]) {
                                    case "image/jpeg":
                                        $jpg = TRUE;
                                        break;
                                    case "image/png":
                                        $png = TRUE;
                                        break;
                                    case "image/gif":
                                        $gif = TRUE;
                                        break;
                                }
                            }

                            # ...if it is then grab the description and thumbnail image attributes
                            if ($jpg || $png || $gif) {
                                if ($v["tag_name"] == "media:description") {
                                    if (is_array($v["child_nodes"])) {
                                        $description = '';
                                        foreach ($v["child_nodes"] as $num => $data) {
                                            $description .= $data;
                                        }
                                        $alt = (strip_tags($description));
                                        $img_array[$i]['alt'] = $alt;
                                    }
                                }
                                if ($v["tag_name"] == "media:thumbnail") {
                                    $img_array[$i]['thmb_src'] = $v["attributes"]["url"];
                                    $img_array[$i]['thmb_wid'] = $v["attributes"]["width"];
                                    $img_array[$i]['thmb_hgh'] = $v["attributes"]["height"];

                                    # flickr image URLs use _s for thumbnails (small) and _m for medium
                                    if (substr($v["attributes"]["url"], -6, 2) == '_s') {
                                        $med_src = substr($v["attributes"]["url"], 0, -6) . '_m.';
                                        if ($jpg) $med_src .= 'jpg';
                                        elseif ($png) $med_src .= 'png';
                                        elseif ($gif) $med_src .= 'gif';
                                        $img_array[$i]['med_src'] = $med_src;
                                    }
                                    if ($v["attributes"]["url"] != '') $i++;
                                }
                            }
                        }
                    }
                }
            }
        }
        return $img_array;
    }

    private function _get_plot_points()
    {
        $plot = $this->_select_plot();
        if ($plot)
        {
            $this->east = $plot[0]->east;
            $this->north = $plot[0]->north;
            $data = $this->_select_plot_points();
            if (is_array($data) && count($data) > 0)
            {
                $output = array();
                foreach ($data as $key => $plot)
                {
                    $lines = explode("\n", $plot->al_coords);
                    if (is_array($lines) && count($lines) > 0)
                    {
                        foreach($lines as $line)
                        {
                            if ($line != '') 
                            {
                                $coords = explode(',', $line);
                                if (count($coords) == 2)
                                {
                                    $output[$key]['point'][] = array(trim($coords[0]), trim($coords[1]));
                                }
                            }
                        }
                    }
                }
                return $output;
            }
        }
    }

    ###########################################################################################
    ### DATABASE FUNCTIONS ###
    ##########################

    private function _insert_get_string($string)
    {
        $sql = "
        INSERT INTO get_string
        (
            gs_id,
            gs_data
        )
        VALUES
        (
            NULL,
            ".$this->db->escape($string)."
        );
        ";
        echo $sql;
        $this->db->query($sql);
    }

    private function _insert_community_contact($md5code, $status)
    {
        $_uid = $this->userlogin->user_id;
        if ($_uid == '') $_uid = 0;
        $sql = "
        INSERT INTO community_contacts
        (
            cc_id,
            plot_id,
            user_id,
            md5code,
            status
        )
        VALUES
        (
            NULL,
            ".$this->db->escape($this->plot_id).",
            ".$_uid.",
            ".$this->db->escape($md5code).",
            ".$this->db->escape($status)."
        );
        ";
#        echo $sql;
        $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;
    }

    private function _insert_contribution($type, $msg, $user_id, $md5code, $status)
    {
        $sql = "
        INSERT INTO contributions
        (
            c_id,
            plot_id,
            user_id,
            msg,
            type,
            md5code,
            timestamp,
            status
        )
        VALUES
        (
            NULL,
            $this->plot_id,
            $user_id,
            ".$this->db->escape($msg).",
            ".$this->db->escape($type).",
            ".$this->db->escape($md5code).",
            " .time().",
            ".$this->db->escape($status)."
        );
        ";
        $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;
    }

    private function _insert_contact_msg($msg, $md5code, $status)
    {
        $_uid = $this->userlogin->user_id;
        if ($_uid == '') $_uid = 0;
        $sql = "
        INSERT INTO contact_msgs
        (
            cm_id,
            plot_id,
            user_id,
            msg,
            md5code,
            timestamp,
            status
        )
        VALUES
        (
            NULL,
            $this->plot_id,
            $_uid,
            ".$this->db->escape($msg).",
            ".$this->db->escape($md5code).",
            " .time().",
            ".$this->db->escape($status)."
        );
        ";
        $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;
    }

	private function _insert_newsletter_contact($md5code, $status)
	{
        $_uid = $this->userlogin->user_id;
        if ($_uid == '') $_uid = 0;
        $sql = "
        INSERT INTO newsletters
        (
            n_id,
            plot_id,
            user_id,
            md5code,
            status
        )
        VALUES
        (
            NULL,
            ".$this->db->escape($this->plot_id).",
            ".$_uid.",
            ".$this->db->escape($md5code).",
            ".$this->db->escape($status)."
        );
        ";
#        echo $sql;
        $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;
	}

    private function _select_communty_contact()
    {
        $sql = "
        SELECT COUNT(cc_id) AS `count`
        FROM community_contacts
        WHERE plot_id = ".$this->db->escape($this->plot_id)."
        AND user_id = ".$this->userlogin->user_id."
        AND status = 'live'
        ";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result[0]->count;
    }

    private function _select_newsletter_contact()
    {
        $sql = "
        SELECT COUNT(n_id) AS `count`
        FROM newsletters
        WHERE plot_id = ".$this->db->escape($this->plot_id)."
        AND user_id = ".$this->userlogin->user_id."
        AND status = 'live'
        ";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result[0]->count;
    }

    private function _select_community_contact_count()
    {
        $sql = "
        SELECT COUNT(cc_id) AS `count`
        FROM community_contacts
        WHERE plot_id = ".$this->db->escape($this->plot_id)."
        AND status = 'live'
        ";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result[0]->count;
    }

    private function _select_plot()
    {
        $sql = "
        SELECT east, north
        FROM all_plots
        WHERE id = ".$this->db->escape($this->plot_id)."
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_plot_points()
    {
        $sql = "
        SELECT al_coords
        FROM all_loci
        WHERE al_east = $this->east
        AND al_north = $this->north
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_plots_for_bbox($min_x, $max_x, $min_y, $max_y)
    {
        $sql = "
        SET SQL_BIG_SELECTS=1;
        ";
        $query = $this->db->query($sql);
        $sql = "
        SELECT al.al_coords, ap.id, ap.lr_title, ap.lr_subtitle, ap.location, ap.owner
        FROM all_loci al, all_plots ap
        WHERE ap.east = al.al_east AND ap.north = al.al_north
        AND $min_x < ap.east AND ap.east < $max_x AND $min_y < ap.north AND ap.north < $max_y
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_plots_ward()
    {
        $sql = "
        SELECT id, lr_title, east, north, location
        FROM all_plots
        WHERE $this->w_min_x < east
        AND east < $this->w_max_x
        AND $this->w_min_y < north
        AND north < $this->w_max_y
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_ward()
    {
        $sql = "
        SELECT w_min_x, w_min_y, w_max_x, w_max_y
        FROM wards
        WHERE w_code = ".$this->db->escape($this->ward_code)."
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _update_community_contact_deleted()
    {
        $sql = "
        UPDATE `community_contacts` SET
        status =  'dead'
        WHERE user_id = ".$this->userlogin->user_id."
        AND plot_id = ".$this->plot_id."
        AND status IN ('live', 'hold')
        ";
        $query = $this->db->query($sql);
    }

    private function _update_newsletter_contact_deleted()
    {
        $sql = "
        UPDATE `newsletters` SET
        status =  'dead'
        WHERE user_id = ".$this->userlogin->user_id."
        AND plot_id = ".$this->plot_id."
        AND status IN ('live', 'hold')
        ";
        $query = $this->db->query($sql);
    }

}

/* End of file ajax.php */
/* Location: ./system/application/controllers/ajax.php */