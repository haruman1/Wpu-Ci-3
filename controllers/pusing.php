<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pusing extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    public function index()
    {
        $brd['music'] = 'lagu.mp3'; // simpan di folder asu/lagu/...
        $this->load->view('qimak', $brd);
    }
}
