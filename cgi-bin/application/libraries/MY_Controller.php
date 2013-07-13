<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends Controller {

    function MY_Controller()
    {
        parent::Controller();
        $this->load->database();
		$this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="alert">', '</p>');
        $this->load->library('UserLogIn');
        $this->userlogin->check_logged_in($this->db);
    }

    ###############################################################################################
    ##   PROCESS FUNCTIONS   ##
    ###########################

    function _send_email($to, $subject, $body, $type)
    {
        if (DEBUG)
        {
            echo 'To: ' . $to . '<br />' . "\n";
            echo 'Subject: ' . $subject . '<br />' . "\n";
            echo 'Body: ' . $body . '<br />' . "\n";
        }
        else
        {
            $this->load->library('email');
            $this->email->from('no-reply@whoownsmyneighbourhood.org.uk', 'Who Owns My Neighbourhood');
            $this->email->to($to);
            #$this->email->cc('p_c_j_f@hotmail.com');
            #$this->email->bcc('them@their-example.com');
            $this->email->subject($subject);
            $this->email->message($body);
            $this->email->send();
            #echo $this->email->print_debugger();
            
            
        }
        $this->_insert_email_outbox($to, $subject, $body, $type);
    }

    function _captcha_suitable($captcha)
    {
        $path = BASEPATH . 'application/libraries/securimage/';
        if (file_exists($path . 'securimage.php'))
        {
            require_once $path . 'securimage.php';
		    $securimage = new Securimage();
            if ( ! $securimage->check($captcha))
            {
                $this->form_validation->set_message('_captcha_suitable', 'Jumbled letters incorrect');
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }
    }

    function _create_url_string($string, $limit = '')
    {
        $new_str = preg_replace('/[^a-z0-9]/', '', strtolower($string));
        if ($limit != '') $new_str = substr($new_str, 0, $limit);
        return $new_str;
    }

    function _create_url_string_with_dashes($string, $limit = '')
    {
        $str1 = preg_replace('/[\s]/', '\-', strtolower(trim($string)));
        $new_str = preg_replace('/[^a-z0-9\-]/', '', strtolower($str1));
        if ($limit != '') $new_str = substr($new_str, 0, $limit);
        return $new_str;
    }

    function _make_random_string($length = 8)
    {
        $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
        $strlen = strlen($chars) - 1;
        $str = '' ;
        for ($i = 0; $i < $length; $i++)
        {
            $num = mt_rand(0, $strlen);
            $str .= substr($chars, $num, 1);
        }
        return $str;
    }
    
    function _add_aggregate($plot_id, $plot_url, $content)
    {
    	// 65 is a totally abitrary number !
		$string = $this->_curtail_sentence($content, 80);
		$this->_insert_aggregate($plot_id, $plot_url, $string);
    }

	function _curtail_sentence($sentence, $length)
	{
		$bits = explode(' ', trim($sentence));
		$output = '';
		if (is_array($bits))
		{
			foreach($bits as $bit)
			{
				if (strlen($output) >= $length)
				{
					$finito = substr($output, 0, strlen($output)-1) . '...';
					return $finito;
				} 
				else
				{
					$output .= $bit . ' ';
				}
			}
		}
		return $output;
	}
	
	function _get_plot_info($plot_id)
    {
        $data = $this->_select_plot_info($plot_id);
        $data[0]->timestamp = $this->_make_timestamp($data[0]->date);
        return $data;
    }

    function _make_timestamp($stringdate)
    {
        if (strlen($stringdate) == 8)
        {
            return mktime(0, 0, 0, substr($stringdate, 4, 2), substr($stringdate, 6, 2), substr($stringdate, 0, 4));
        }
    }

    function _get_plot_contributions($type)
    {
        $data = $this->_select_contributions_for_plot_by_type($type);
        return $data;
    }

    function _get_wards()
    {
        $data = $this->_select_wards();
        return $data;
    }

    ###############################################################################################
    ##   DATABASE FUNCTIONS  ##
    ###########################

    function _insert_aggregate($plot_id, $plot_url, $content)
    {
        $sql = "
        INSERT INTO `aggregates`
        (`ag_id`, `plot_id`, `plot_url`, `content`, `timestamp`, `status`)
        VALUES
        (NULL,
        " . $this->db->escape($plot_id) . ",
        " . $this->db->escape($plot_url) . ",
        " . $this->db->escape($content) . ",
        " . time() . ",
        'live'
        )
        ";
        $query = $this->db->query($sql);
    }

    function _insert_email_outbox($eo_recipient, $eo_subject, $eo_body, $eo_type)
    {
        $sql = "
        INSERT INTO `email_outbox`
        (`eo_id`, `eo_recipient`, `eo_subject`, `eo_body`, `eo_type`, `eo_timestamp`, `eo_status`)
        VALUES
        (NULL,
        " . $this->db->escape($eo_recipient) . ",
        " . $this->db->escape($eo_subject) . ",
        " . $this->db->escape($eo_body) . ",
        " . $this->db->escape($eo_type) . ",
        " . time() . ",
        'sent'
        )
        ";
        $query = $this->db->query($sql);
    }

    function _insert_user($name, $email, $password, $code_check, $status)
    {
        $sql = "
        INSERT INTO `users`
        (`user_id`, `fb_id`, `name`, `MSISDN`, `email`, `password`, `new_password`, `code_check`, `registration_timestamp`, `user_status`)
        VALUES
        (NULL,
        '',
        " . $this->db->escape($name) . ",
        '',
        " . $this->db->escape($email) . ",
        " . $this->db->escape($password) . ",
        '',
        " . $this->db->escape($code_check) . ",
        " . time() . ",
        " . $this->db->escape($status) . "
        )
        ";
        $query = $this->db->query($sql);
        $id = $this->db->insert_id();
        return $id;
    }

    function _select_contributions_for_plot_by_type($type)
    {
        $sql = "
        SELECT msg, timestamp
        FROM `contributions`
        WHERE plot_id = ".$this->db->escape($this->plot_id)."
        AND status = 'live'
        AND type = ".$this->db->escape($type)."
        ORDER BY timestamp DESC
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

	function _select_plot_info($plot_id)
    {
        $sql = "
        SELECT *
        FROM all_plots
        WHERE id = ".$this->db->escape($plot_id)."
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

	function _select_wards()
	{
       $sql = "
        SELECT w_centre_x, w_centre_y, w_name, w_code
        FROM `wards`
        ORDER BY w_name
        ";
        $query = $this->db->query($sql);
        return $query->result();
	}
	
}