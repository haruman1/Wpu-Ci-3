<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        MasukPakEko();
    }

    public function index()
    {
        $data['nibba'] = 'BreakDown';
        $data['title'] = 'Dashboard';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/index');
        $this->load->view('templates/footer');
    }

    public function role()
    {
        $data['nibba'] = 'BreakDown';
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get('user_role')->result_array();


        $this->form_validation->set_rules('role', 'Role', 'required');
        if ($this->form_validation->run() == false) {


            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('templates/topbar');
            $this->load->view('admin/Role');
            $this->load->view('templates/footer');
        } else {
            $this->db->insert('user_role', ['role' => $this->input->post('role')]);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           New Role Added </div>');
            redirect('admin/role');
        }
    }
    public function roleaccess($role_id)
    {
        $data['nibba'] = 'BreakDown';
        $data['title'] = 'Role';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['role'] = $this->db->get_where('user_role', ['id' => $role_id])->row_array();
        $this->db->where('id !=', 1);
        $data['menu'] = $this->db->get('user_menu')->result_array();
        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('admin/roleakses');
        $this->load->view('templates/footer', $data);
    }

    //belum Selesai,Benerin ini ada di helpers dan footer
    public function gantiakses()
    {
        $menu_id = $this->input->post('menuId');
        $role_id = $this->input->post('roleId');
        $data = [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ];

        $result = $this->db->get_where('user_access_menu', $data);
        if ($result->num_rows() < 1) {
            $this->db->insert('user_access_menu', $data);
        } else {
            $this->db->delete('user_access_menu', $data);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Akses telah di ubah!</div>');
    }

    public function hapusrole($id)
    {
        $this->load->model('Menu_model', 'menu');
        $this->menu->HapusDataRole($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Data Telah Di Hapus </div>');
        redirect('admin/role');
    }
}
