<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
        $this->load->view('user/index');
        $this->load->view('templates/footer');
    }

    public function edit()
    {
        $data['nibba'] = 'BreakDown';
        $data['title'] = 'Edit Profile';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('templates/topbar');
            $this->load->view('user/tempik');
            $this->load->view('templates/footer');
            //ini script ubah nama
        } else {
            $fathur = $this->input->post('name');
            $adel = $this->input->post('email');
            $this->db->set('name', $fathur);
            $this->db->where('email', $adel);

            $masukin_foto = $_FILES['image']['name'];
            if ($masukin_foto) {
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = '500';
                $config['upload_path'] = './asu/img/muka/';
                $this->load->library('upload', $config);

                if ($this->upload->do_upload('image')) {
                    $foto_lama = $data['user']['image'];
                    if ($foto_lama != 'fotodobleh.jpg') {
                        unlink(FCPATH . 'asu/img/muka/' . $foto_lama);
                    }

                    $foto_baru = $this->upload->data('file_name');
                    $this->db->set('image', $foto_baru);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            $this->db->update('user');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
            Profile mu sudah di update</div>');
            redirect('user');
        }
    }



    public function gantipassword()
    {


        $data['nibba'] = 'BreakDown';
        $data['title'] = 'Ganti Password';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();


        $this->form_validation->set_rules('psekarang', 'Password Sekarang', 'required|trim');
        $this->form_validation->set_rules('psbaru1', 'Password Baru', 'required|trim|min_length[6]|matches[psbaru2]');
        $this->form_validation->set_rules('psbaru2', 'Password Baru', 'required|trim|min_length[6]|matches[psbaru1]');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/header', $data);
            $this->load->view('templates/sidebar');
            $this->load->view('templates/topbar');
            $this->load->view('user/fathur&adel');
            $this->load->view('templates/footer');
        } else {
            $passwordsekarang = $this->input->post('psekarang');
            $password_baru = $this->input->post('psbaru1');
            if (!password_verify($passwordsekarang, $data['user']['password'])) {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
               Maaf Password Sekarang Salah!</div>');
                redirect('user/gantipassword');
            } else {
                if ($passwordsekarang == $password_baru) {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Password lama mu dan baru sama mas!</div>');
                    redirect('user/gantipassword');
                } else {
                    //password sudah ok
                    $putus_cinta = password_hash($password_baru, PASSWORD_DEFAULT);
                    $this->db->set('password', $putus_cinta);
                    $this->db->where('email', $this->session->userdata('email'));
                    $this->db->update('user');

                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                   Password Berhasil Di Ganti</div>');
                    redirect('user/gantipassword');
                }
            }
        }
    }

    public function Cheat()
    {
        $data['nibba'] = 'BreakDown';
        $data['title'] = 'Cheat Game';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

        $this->load->view('templates/header', $data);
        $this->load->view('templates/sidebar');
        $this->load->view('templates/topbar');
        $this->load->view('cheat/index');
        $this->load->view('templates/footer');
    }
}
