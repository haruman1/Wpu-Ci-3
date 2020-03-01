<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }
    public function  index()
    {
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {
            $data['nibba'] = 'BreakDown Login Page';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/login');
            $this->load->view('templates/auth_footer');
        } else {
            //valid success
            $this->_login();
        }
    }

    private function _login()
    {
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $user = $this->db->get_where('user', ['email' => $email])->row_array();
        //jika user ada
        if ($user) {
            //jika usernya aktif
            if ($user['is_active'] == 1) {
                //cek password
                if (password_verify($password, $user['password'])) {
                    $data = [
                        'email' => $user['email'],
                        'role_id' => $user['role_id']
                    ];
                    $this->session->set_userdata($data);
                    if ($user['role_id'] == 1) {
                        redirect('admin');
                    } else {
                        redirect('user');
                    }
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                   Salah Password! </div>');
                    redirect('auth');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Email Mu Belum Aktif! </div>');
                redirect('auth');
            }
        }
    }


    public function registration()
    {
        $this->load->model('Menu_model', 'menu');
        if ($this->session->userdata('email')) {
            redirect('user');
        }
        $this->form_validation->set_rules('name', 'Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[user.email]', [
            'is_unique' => 'Email ini sudah tedaftar'
        ]);
        $this->form_validation->set_rules('password1', 'Password', 'required|trim|min_length[3]|matches[password2]', [
            'matches' => 'password Tidak Sama!',
            'min_length' => 'Password Kependekan'
        ]);
        $this->form_validation->set_rules('password2', 'Password', 'required|trim|matches[password1]');
        if ($this->form_validation->run() == false) {
            $data['nibba'] = 'BreakDown User Registration';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/registration');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email', true);
            $data = [
                'name' => htmlspecialchars($this->input->post('name', true)),
                'email' => htmlspecialchars($email),
                'image' => 'fotodobleh.jpg',
                'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ];

            //siapkan Token
            $token = base64_encode(random_bytes(32));
            $user_token = [
                'email' => $email,
                'token' => $token,
                'date_created' => time()
            ];
            $this->db->insert('user', $data);
            $this->db->insert('user_token', $user_token);

            $this->_KirimJodoh($token, 'verif');
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
           Terimakasih,Tolong Aktivasi Akun anda! </br> Jangan Lupa Check Folder Spam!</div>');
            redirect('auth');
        }
    }

    private function _kirimJodoh($token, $type)
    {

        $configjodoh =
            [
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://mail.tsukinohikari.id',
                'smtp_user' => 'no_reply@tsukinohikari.id',
                'smtp_pass' => 'wanasigra123',
                'smtp_port' => 465,
                'mailtype' => 'html',
                'charset' => 'utf-8',
                'newline' => "\r\n",
            ];

        $this->load->library('email', $configjodoh);
        $this->email->initialize($configjodoh);
        $this->email->from('no_reply@tsukinohikari.id', 'Break Down');
        $this->email->to($this->input->post('email'));

        if ($type == 'verif') {
            $this->email->subject('Verifikasi Akun');
            $this->email->message('Click  Link Ini Untuk Verif Akun Mu :
                 <a href="' . base_url() . 'auth/verif?email='
                . $this->input->post('email') .
                '&token=' . urldecode($token) . '">
                 Activate</a>');
        } else if ($type == 'restpw') {

            $this->email->subject('Lupa Password');
            $this->email->message('Click Ini Link Reset Password Anda : 
                    <a href="' . base_url() . 'auth/restpw?email='
                . $this->input->post('email') . '&restpw='
                . urldecode($token) . '">
                      Reset Password</a>');
        }




        if ($this->email->send()) {
            return true;
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Internet Anda Mati atau Internet Anda Sedang Gangguan!</div>');
            redirect('auth');
        }
    }



    public function verif()
    {
        $ari = $this->input->get('email');
        $tokek = $this->input->get('token');

        $nina = $this->db->get_where('user', ['email' => $ari])->row_array();

        if ($nina) {
            $fathur = $this->db->get_where('user_token', ['token' => $tokek])->row_array();

            if ($fathur) {
                if (time() - $fathur['date_created'] < (60 * 60 * 24)) {
                    $this->db->set('is_active', 1);
                    $this->db->where('email', $ari);
                    $this->db->update('user');
                    $this->db->delete('user_token', ['email' => $ari]);


                    $this->db->delete('user_token', ['email' => $ari]);
                    $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                    Email anda ' . $ari . ' Sudah Aktif,Silahkan Login!</div>');
                    redirect('auth');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                    Aktivasi akun gagal! Token Mati!</div>');
                    redirect('auth');
                    $this->db->delete('user', ['email' => $ari]);
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Aktivasi akun gagal! Salah Token!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
           Aktivasi akun gagal! Email Salah!</div>');
            redirect('auth');
        }
    }
    public function keluar()
    {
        $this->session->unset_userdata('email');
        $this->session->unset_userdata('role_id');
        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
        Kamu Sudah Keluar :D</div>');
        redirect('auth');
    }

    public function larang()
    {
        $data['musik'] = 'lagu.mp3'; //simpan lagu di folder asu/lagu/
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('paleng', $data);
    }


    public function lupapassword()
    {

        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        if ($this->form_validation->run() == false) {
            $data['nibba'] = 'Lupa Password';
            $this->load->view('templates/auth_header', $data);
            $this->load->view('auth/lp2w');
            $this->load->view('templates/auth_footer');
        } else {
            $email = $this->input->post('email');
            $mamat = $this->db->get_where('user', ['email' => $email, 'is_active' => 1])->row_array();

            if ($mamat) {
                $dobleh = base64_encode(random_bytes(32));
                $simalakama = [
                    'email' => $email,
                    'token' => $dobleh,
                    'date_created' => time()

                ];
                $this->db->insert('user_token', $simalakama);
                $this->_kirimJodoh($dobleh, 'restpw');
                $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
                Tolong Check Email Untuk Check Password Anda!</div>');
                redirect('auth/lupapassword');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                 Email Tidak Tedaftar Disini atau Belum Teraktivasi!</div>');
                redirect('auth/lupapassword');
            }
        }
    }

    public function restpw()
    {
        $email = $this->input->get('email');
        $token = $this->input->get('token');

        $user = $this->db->get_where('user', ['email', $email])->row_array();


        if ($user) {
            $user_token = $this->db->get_where('user_token', ['token' => $token])->row_array();

            if ($user_token) {
                $this->session->set_userdata('reset_email', $email);
                $this->GantiPassword();
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Reset Password Gagal! Salah Token!</div>');
                redirect('auth');
            }
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            Reset Password Gagal! Salah Email</div>');
            redirect('auth');
        }
    }
    public function GantiPassword()
    {
        $data['nibba'] = 'Reset Password';
        $this->load->view('templates/auth_header', $data);
        $this->load->view('auth/changepw');
        $this->load->view('templates/auth_footer');
    }
}
