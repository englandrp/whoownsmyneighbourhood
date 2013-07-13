<?php

class Captcha extends MY_Controller {

	function Captcha()
	{
		parent::MY_Controller();	
	}
	
	function index()
	{
        $path = BASEPATH . 'application/libraries/securimage/';
        if (file_exists($path . 'securimage.php'))
        {
            require_once $path . 'securimage.php';
            $img = new securimage();
            $img->show();
        }
	}

    ###############################################################################################
    ##   PROCESS FUNCTIONS  ##
    ##########################


    ###############################################################################################
    ##   DATABASE FUNCTIONS  ##
    ###########################



}

/* End of file captcha.php */
/* Location: ./system/application/controllers/captcha.php */