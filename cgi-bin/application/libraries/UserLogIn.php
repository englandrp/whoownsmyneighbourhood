<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class UserLogIn {

    # NB: requires   $this->load->database();   to have already been called

    var $CI;
    var $logged_in = FALSE;
    var $log_in_errors = array();
    var $user_id;
    var $name;
    var $email;
    var $user_status;

    function process_password($db)
    {
        if ($_POST['register'] == 'getnewpassword')
        {
            $this->CI =& get_instance();
            $this->db = $db;
		    $this->CI->load->library('form_validation');
            $this->CI->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
            $this->CI->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[20]|xss_clean');
            $this->CI->form_validation->set_rules('confirmpassword', 'Confirm password', 'trim|required|min_length[6]|max_length[20]|matches[password]|xss_clean');
            $this->CI->form_validation->set_rules('remember', 'Remember', 'xss_clean');

            $success = $this->CI->form_validation->run();

            $this->email = set_value('email');
            $this->password = set_value('password');
            $this->confirmpassword = set_value('confirmpassword');
            $this->remember = set_value('remember');

            if ($success)
            {
                $user_data = $this->_select_user_by_email($this->email);
                if ($user_data)
                {
                    $user_id = $user_data[0]->user_id;
                    $code_check = $this->CI->_make_random_string(6);
                    $this->_update_user_code_check_and_new_password($user_id, $code_check, md5($this->password));
                    #$this->CI->_send_email(0, $user_id, $this->mobilesign, $msg, '', '', 'process_password');
                    #$this->CI->_send_email($to, $subject, $body, $type);
                    $body = EMAIL_NEW_PASSWORD_1 . $code_check . EMAIL_NEW_PASSWORD_2;
                    $this->CI->_send_email($user_data[0]->email, 'Who Owns My Neighbourhood > Change password', $body, 'new password code');
                }
                return 'codecheck';
            }   
        }
        elseif ($_POST['register'] == 'confirm')
        {
            $this->CI =& get_instance();
            $this->db = $db;
		    $this->CI->load->library('form_validation');
            $this->CI->form_validation->set_rules('checkcode', 'Check code', 'trim|required|min_length[6]|max_length[6]|xss_clean');
            $this->CI->form_validation->set_rules('email', 'Email address', 'trim|valid_email|xss_clean');
            $this->CI->form_validation->set_rules('remember', 'Remember', 'xss_clean');

            $success = $this->CI->form_validation->run();

            $checkcode = set_value('checkcode');
            $this->email = set_value('email');
            $this->remember = set_value('remember');
            if ($success)
            {
                $user_data = $this->_select_user_by_email($this->email);
                if ($user_data)
                {
                    $user_id = $user_data[0]->user_id;
                    $newcheckcode = $this->CI->_make_random_string(6);
                    $this->_update_user_password_by_checkcode($user_id, $checkcode, $newcheckcode);
                    $this->_log_in_user($user_data);
                    if ($this->remember == 'Y') 
                    {
                        $cookie_code = md5($mobilesign) . md5($password . 'unlikely thing');
                        setcookie('remember', $cookie_code, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                        $this->_update_user_new_password($cookie_code);
                    }
                    $this->logged_in = TRUE;
                    $this->_put_content_live();
                }               
                return 'signin';
            }
            else
            {
                return 'codecheck';
            }
        }
    }

    function process_registration($db)
    {
        if ($_POST['register'] == 'continue')
        {
            $this->CI =& get_instance();
            $this->db = $db;
		    $this->CI->load->library('form_validation');
            #$this->CI->form_validation->set_rules('username', 'Name', 'trim|required|min_length[6]|max_length[20]|xss_clean');
            $this->CI->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|xss_clean');
            $this->CI->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[20]|xss_clean');
            $this->CI->form_validation->set_rules('confirmpassword', 'Confirm password', 'trim|required|min_length[6]|max_length[20]|matches[password]|xss_clean');
            #$this->CI->form_validation->set_rules('confirm', 'Agreement to terms &amp; conditions', 'required|xss_clean');
            $this->CI->form_validation->set_rules('remember', 'Remember', 'xss_clean');
            $success = $this->CI->form_validation->run();

            #$this->username = set_value('username');
            $this->email = set_value('email');
            $this->password = set_value('password');
            $this->confirmpassword = set_value('confirmpassword');
            #$this->confirm = set_value('confirm');
            $this->remember = set_value('remember');

            if ($success)
            {
                $user_data = $this->_select_user($this->email, md5($this->password));
                if ($user_data)
                {
                    $this->_log_in_user($user_data);
                    if ($this->remember == 'Y') 
                    {
                        $cookie_code = md5($email) . md5($password . 'unlikely thing');
                        setcookie('remember', $cookie_code, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                        $this->_update_user_new_password($cookie_code);
                    }
                    $this->logged_in = TRUE;
                    #$this->_put_content_live();
                    return 'logged in';
                }
                else
                {
                    $code_check = $this->CI->_make_random_string(6);
                    #$this->CI->_send_email($to, $subject, $body, $type);
                    $body = EMAIL_REGISTER_1 . $code_check . EMAIL_REGISTER_2;
                    $user_id = $this->CI->_insert_user('', $this->email, md5($this->password), $code_check, 'applicant');
                    $this->CI->_send_email($this->email, 'Who Owns My Neighbourhood > Welcome', $body, 'registration code');

                    # what to do if user has already registered with a different password?
                    #$user_data = $this->_select_user_by_email($this->email);
                    #if ($user_data)
                    #{
                    #    #$this->_update_user_code_check_and_new_password($user_data[0]->user_id, $code_check, md5($this->password));
                    #    $this->CI->_send_email(0, $user_data[0]->user_id, $this->mobilesign, $msg, '', '', 'process_registration - existing user');
                    #}
                    #else
                    #{
                    #    $user_id = $this->CI->_insert_user('', $this->email, md5($this->password), $code_check, 'applicant');
                    #    $this->CI->_send_email(0, $user_id, $this->mobilesign, $msg, '', '', 'process_registration - new user');
                    #}
                    return 'codecheck';
                }
            }
        }
        elseif ($_POST['register'] == 'confirm')
        {
            $this->CI =& get_instance();
            $this->db = $db;
		    $this->CI->load->library('form_validation');
            $this->CI->form_validation->set_rules('checkcode', 'Code', 'trim|required|min_length[6]|max_length[6]|xss_clean');
            $this->CI->form_validation->set_rules('email', 'Email', 'trim|xss_clean');
            $this->CI->form_validation->set_rules('remember', 'Remember', 'xss_clean');
            $success = $this->CI->form_validation->run();
            $checkcode = set_value('checkcode');
            $this->email = set_value('email');
            $this->remember = set_value('remember');

            if ($success)
            {
                $user_data = $this->_select_user_by_email($this->email);
                # if Y - they have already registered
                if ($user_data)
                {
                    $newcheckcode = $this->CI->_make_random_string(6);
                    $this->_update_user_password_by_checkcode($user_data[0]->user_id, $checkcode, $newcheckcode);
                }
                else
                {
                    $this->_update_user_live($this->email, $checkcode);
                    $user_data = $this->_select_user_by_email($this->email);
                }
                $this->_log_in_user($user_data);
                if ($this->remember == 'Y') 
                {
                    $cookie_code = md5($email) . md5($password . 'unlikely thing');
                    setcookie('remember', $cookie_code, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                    $this->_update_user_new_password($cookie_code);
                }
                $this->logged_in = TRUE;
                $this->_put_content_live();
                return 'logged in';
            }
            else
            {
                return 'codecheck';
            }
        }
    }

    function check_logged_in($db)
    {
        if ($_POST['sign_in'] == 'sign in')
        {
            $this->CI =& get_instance();
            $this->db = $db;
		    $this->CI->load->library('form_validation');
            $this->CI->form_validation->set_rules('email', 'Email address', 'trim|required|valid_email|xss_clean');
            $this->CI->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|max_length[20]|xss_clean');
            $this->CI->form_validation->set_rules('captcha', 'Jumbled letters', 'trim|strtoupper|required|min_length[4]|max_length[4]|callback__captcha_suitable|xss_clean');
            $this->CI->form_validation->set_rules('remember', 'Remember', 'xss_clean');

            $success = $this->CI->form_validation->run();

            $email = set_value('email');
            $password = set_value('password');
            $captcha = set_value('captcha');
            $this->remember = set_value('remember');


            if ($success)
            {
                $user_data = $this->_select_user($email, md5($password));
                if ($user_data)
                {
                    $this->_log_in_user($user_data);
                    if ($this->remember == 'Y') 
                    {
                        $cookie_code = md5($email) . md5($password . 'unlikely thing');
                        setcookie('remember', $cookie_code, time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
                        $this->_update_user_new_password($cookie_code);
                    }
                    $this->logged_in = TRUE;
                    $this->_put_content_live();
                    $this->sign_in_errors = 'signed in';
                }
                else
                {
                    $this->sign_in_errors[] = 'Email address and/or password not recognised';
                }
            }
            return;
        }

        if (isset($_COOKIE['PHPSESSID']))
        {
            session_start();
            $this->user_id = $_SESSION['user_id'];
            #$this->name = $_SESSION['name'];
            $this->email = $_SESSION['email'];
            $this->user_status = $_SESSION['user_status'];
            if ($this->user_status == 'live' || $this->user_status == 'admin') $this->logged_in = TRUE;
            return;
        }

        if (isset($_COOKIE['remember']) && strlen($_COOKIE['remember']) == 64)
        {
            $this->db = $db;
            $user_data = $this->_select_user_by_cookie_code($_COOKIE['remember']);
            if ($user_data)
            {
                $this->_log_in_user($user_data);
                $this->logged_in = TRUE;
            }
        }

    }

	function logout()
	{
        session_start();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        setcookie('remember', '', time() - 42000, '/', COOKIE_DOMAIN);
        session_destroy();
        $this->logged_in = FALSE;
	}

    function _put_content_live()
    {
        $contributions = $_COOKIE['c_ids'];
        $j = 0;
        if (is_array($contributions) && count($contributions) > 0)
        {
            foreach ($contributions as $md5codestring) {
                $md5code = substr($md5codestring, 0, 32);
                $c_id = substr($md5codestring, 32);
                if (is_numeric($c_id)) $this->_update_contribution_live($c_id, $md5code);
                setcookie('c_ids[' . $j . ']', '', time() - 3600, '/', COOKIE_DOMAIN);
                $j++;
            }
        }
        $_cc = $_COOKIE['cc_id'];
        if (strlen($_cc) > 32)
        {
            $md5code = substr($_cc, 0, 32);
            $cc_id = substr($_cc, 32);
            if (is_numeric($cc_id)) $this->_update_community_contact_live($cc_id, $md5code);
            setcookie('cc_id', '', time() - 3600, '/', COOKIE_DOMAIN);
        }
        $_nc = $_COOKIE['nc_id'];
        if (strlen($_nc) > 32)
        {
            $md5code = substr($_nc, 0, 32);
            $nc_id = substr($_nc, 32);
            if (is_numeric($nc_id)) $this->_update_newsletter_live($nc_id, $md5code);
            setcookie('nc_id', '', time() - 3600, '/', COOKIE_DOMAIN);
        }
        $_cm = $_COOKIE['cm_id'];
        if (strlen($_cm) > 32)
        {
            $md5code = substr($_cm, 0, 32);
            $cm_id = substr($_cm, 32);
            if (is_numeric($cm_id)) $this->_update_contact_message_live($cm_id, $md5code);
            setcookie('cm_id', '', time() - 3600, '/', COOKIE_DOMAIN);
        }
    }

    private function _log_in_user($user_data)
    {
        session_start();
        $_SESSION['user_id'] = $user_data[0]->user_id;
        #$_SESSION['name'] = $user_data[0]->name;
        $_SESSION['email'] = $user_data[0]->email;
        $_SESSION['password'] = $user_data[0]->password;
        $_SESSION['user_status'] = $user_data[0]->user_status;
        $this->user_id = $user_data[0]->user_id;
        $this->name = $user_data[0]->name;
        $this->email = $user_data[0]->email;
        $this->user_status = $user_data[0]->user_status;
        setcookie('user', 'registered', time() + COOKIE_LIFE, '/', COOKIE_DOMAIN);
    }

    function _assemble_cookie_string()
    {
        $contributions = $_COOKIE['c_ids'];
        if ((is_array($contributions) && count($contributions) > 0) || $_COOKIE['cc_id'] != '' || $_COOKIE['cm_id'] != '')
        {
            return 'Information you have added to the Who Owns My Neighbourhood website will be shown once you have signed in';
        }
    }

    ###############################################################################################
    ##   DATABASE FUNCTIONS  ##
    ###########################

    private function _select_user($email, $password)
    {
        $sql = "
        SELECT *
        FROM users
        WHERE email = ".$this->db->escape($email)."
        AND password = ".$this->db->escape($password)."
        AND user_status IN ('admin', 'live');
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_user_by_cookie_code($new_password)
    {
        $sql = "
        SELECT *
        FROM users
        WHERE new_password = ".$this->db->escape($new_password)."
        AND user_status IN ('admin', 'live');
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _select_user_by_email($email)
    {
        $sql = "
        SELECT *
        FROM users
        WHERE email = ".$this->db->escape($email)."
        AND user_status IN ('admin', 'live');
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function ZZ_select_message_by_md5code($md5code)
    {
        $sql = "
        SELECT message_id, status
        FROM messages
        WHERE md5code = ".$this->db->escape($md5code)."
        AND md5code != ''
        AND status IN ('hold', 'holdpremod');
        ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    private function _update_community_contact_live($cc_id, $md5code)
    {
        $sql = "
        UPDATE `community_contacts` SET
        user_id =  $this->user_id,
        md5code = '',
        status = 'live'
        WHERE cc_id = $cc_id
        AND md5code = ".$this->db->escape($md5code)."
        AND status = 'hold'
        ";
        $query = $this->db->query($sql);
    }

    private function _update_contact_message_live($cm_id, $md5code)
    {
        $sql = "
        UPDATE `contact_msgs` SET
        user_id =  $this->user_id,
        md5code = '',
        status = 'live'
        WHERE cm_id = $cm_id
        AND md5code = ".$this->db->escape($md5code)."
        AND status = 'hold'
        ";
        $query = $this->db->query($sql);
    }

    private function _update_contribution_live($c_id, $md5code)
    {
        $sql = "
        UPDATE `contributions` SET
        user_id =  $this->user_id,
        md5code = '',
        status = 'live'
        WHERE c_id = $c_id
        AND md5code = ".$this->db->escape($md5code)."
        AND status = 'hold'
        ";
        $query = $this->db->query($sql);
    }

    private function _update_newsletter_live($nc_id, $md5code)
    {
        $sql = "
        UPDATE `newsletters` SET
        user_id =  $this->user_id,
        md5code = '',
        status = 'live'
        WHERE n_id = $nc_id
        AND md5code = ".$this->db->escape($md5code)."
        AND status = 'hold'
        ";
        $query = $this->db->query($sql);
    }

    private function ZZ_update_message_held($user_id, $md5code)
    {
        $sql = "
        UPDATE `messages` SET
        user_id =  ".$this->db->escape($user_id).",
        md5code = '',
        status = 'live'
        WHERE md5code = ".$this->db->escape($md5code)."
        AND status = 'hold'
        ";
        $query = $this->db->query($sql);
    }

    private function ZZ_update_message_held_premod($user_id, $md5code)
    {
        $sql = "
        UPDATE `messages` SET
        user_id =  ".$this->db->escape($user_id).",
        md5code = '',
        status = 'premod'
        WHERE md5code = ".$this->db->escape($md5code)."
        AND status = 'holdpremod'
        ";
        $query = $this->db->query($sql);
    }

    private function ZZ_update_page_held($page_id, $user_id, $md5code)
    {
        $sql = "
        UPDATE `pages` SET
        user_id =  ".$this->db->escape($user_id).",
        md5code = '',
        status = 'live'
        WHERE page_id = ".$this->db->escape($page_id)."
        AND md5code = ".$this->db->escape($md5code)."
        AND status = 'hold'
        ";
        $query = $this->db->query($sql);
    }

    private function _update_user_code_check_and_new_password($user_id, $code_check, $new_password)
    {
        $sql = "
        UPDATE `users` SET
        code_check =  ".$this->db->escape($code_check).",
        new_password =  ".$this->db->escape($new_password)."
        WHERE user_id = ".$this->db->escape($user_id)."
        ";
        $query = $this->db->query($sql);
    }

    private function _update_user_live($email, $code_check)
    {
        $sql = "
        UPDATE `users` SET
        new_password =  '',
        user_status = 'live'
        WHERE email = ".$this->db->escape($email)."
        AND code_check = ".$this->db->escape($code_check)."
        AND user_status = 'applicant'
        ";
        $query = $this->db->query($sql);
    }

    private function _update_user_new_password($new_password)
    {
        $sql = "
        UPDATE `users` SET
        new_password =  ".$this->db->escape($new_password)."
        WHERE user_id = ".$this->db->escape($this->user_id)."
        ";
        $query = $this->db->query($sql);
    }

    private function _update_user_password_by_checkcode($user_id, $code_check, $new_code_check)
    {
        $sql = "
        UPDATE `users` SET
        code_check =  ".$this->db->escape($new_code_check).",
        password = new_password,
        new_password = ''
        WHERE user_id = ".$this->db->escape($user_id)."
        AND code_check = ".$this->db->escape($code_check)."
        AND user_status IN ('live', 'admin')
        ";
        $query = $this->db->query($sql);
    }

}
