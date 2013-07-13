<?php

class Signout extends MY_Controller {

	function Signout()
	{
		parent::MY_Controller();	
	}
	
    function index()
    {
        $this->userlogin->logout();
        header('Location: /');
        exit;
	}
}

/* End of file signout.php */
/* Location: ./system/application/controllers/signout.php */