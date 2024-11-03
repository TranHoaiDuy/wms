<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Qrcode extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->model('user_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'DH-WMS : Dashboard';
        
        $this->loadViews("qrcode", $this->global, NULL , NULL);
    }
}

?>