<?php

class Password extends MY_Controller {

	function Password()
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
            $data['success'] = $this->userlogin->process_password($this->db);

            $data['title'] = 'Password';
			$data['nav'] = 'mypage';
            $data['email'] = $this->userlogin->email;
            $data['password'] = $this->userlogin->password;
            $data['confirmpassword'] = $this->userlogin->confirmpassword;
            $data['remember'] = $this->userlogin->remember;


            if ($data['success'] == 'codecheck') 
            {
                $this->load->view('_head', $data);
                $this->load->view('_header', $data);
                $this->load->view('password2', $data);
                $this->load->view('_foot', $data);
            }
            elseif ($data['success'] == 'signin') 
            {
                header('Location: /signin');
                exit;
            }
            else
            {
                $this->load->view('_head', $data);
                $this->load->view('_header', $data);
                $this->load->view('password', $data);
                $this->load->view('_foot', $data);
            }
        }
	}
}

/* End of file password.php */
/* Location: ./system/application/controllers/password.php */