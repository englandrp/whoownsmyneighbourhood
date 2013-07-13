<?php

class Signin extends MY_Controller {

	function Signin()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        if ($this->userlogin->user_status == 'live')
        {
            header('Location: /mypage');
            exit;
        }
        else
        {
            $data = array();
            $data['cookiestring'] = $this->userlogin->_assemble_cookie_string();
            $data['title'] = 'Sign in';
            $data['nav'] = 'mypage';
            $data['sign_in_errors'] = $this->userlogin->sign_in_errors;
            $data['remember'] = $this->userlogin->remember;
            $data['confirm'] = $this->userlogin->confirm;
            $data['js'] = array('/x/js/whoownsmyneighbourhood.js');
            $this->load->view('_head', $data);
            $this->load->view('_header', $data);
            $this->load->view('signin', $data);
            $this->load->view('_foot', $data);
        }

    }

}

/* End of file signin.php */
/* Location: ./system/application/controllers/signin.php */