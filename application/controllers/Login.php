<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $isLoggedIn = $this->session->userdata('isLoggedIn');

        if (!isset($isLoggedIn) || $isLoggedIn != TRUE) {
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('pass', 'Password', 'required');

            if ($this->form_validation->run() == false) {
                $data['title'] = 'Login Loker';
                $this->load->view("login/login", $data);
            } else {
                $user = strtoupper($this->input->post('username'));
                $data = array(
                    'username' => $user,
                    'pass' => md5($this->input->post('pass')),
                );
                $check_user = $this->user_model->checkUser($user);
                if ($check_user) {
                    $timeout = 300;
                    $sessionArray = array(
                        'username' => $check_user->inisial,
                        'nama' => $check_user->nama,
                        'isLoggedIn' => TRUE,
                        'expires_time' => time() + $timeout
                    );
                    $this->session->set_userdata($sessionArray);
                    redirect('admin/dashboard');
                } else {
                    $this->session->set_flashdata('message', 'Data email dan password tidak valid!!');
                    redirect('login');
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
