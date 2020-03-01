<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cheat extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        MasukPakEko();
    }


    public function index()
    {
        $data['nibba'] = 'BreakDown';
        $data['title'] = 'My Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('cheat/index');
        $this->load->view('templates/footer');
    }
}
