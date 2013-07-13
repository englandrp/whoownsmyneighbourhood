<?php

class Register extends MY_Controller {

	function Register()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        if ($this->userlogin->user_status == 'live' || $this->userlogin->user_status == 'admin')
        {
            header('Location: /');
            exit;
        }
        else
        {
            $data = array();
            $data['success'] = $this->userlogin->process_registration($this->db);
            $data['cookiestring'] = $this->userlogin->_assemble_cookie_string();
            $data['title'] = 'Register';
			$data['nav'] = 'mypage';
            $data['username'] = $this->userlogin->username;
            $data['email'] = $this->userlogin->email;
            $data['password'] = $this->userlogin->password;
            $data['confirmpassword'] = $this->userlogin->confirmpassword;
            #$data['confirm'] = $this->userlogin->confirm;
            $data['remember'] = $this->userlogin->remember;

            if ($data['success'] == 'codecheck') 
            {
                $this->load->view('_head', $data);
                $this->load->view('_header', $data);
                $this->load->view('register2', $data);
                $this->load->view('_foot', $data);
            }
            elseif ($data['success'] == 'logged in') 
            {
                header('Location: /mypage');
                exit;
            }
            else
            {
                $this->load->view('_head', $data);
                $this->load->view('_header', $data);
                $this->load->view('register', $data);
                $this->load->view('_foot', $data);
            }
        }
    }

}

/* End of file register.php */
/* Location: ./system/application/controllers/register.php */