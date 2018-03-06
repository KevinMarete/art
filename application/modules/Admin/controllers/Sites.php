<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sites extends MX_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Install_model');
        $this->load->model('User_model');
        $this->load->model('Partner_model');
    }

    public function index() {
        $data['content_view'] = 'pages/sites_view';
        $data['page_title'] = 'ART Dashboard | Sites';
        $this->Install_model->read();
        $data['Installed_sites'] = $this->Install_model->read();
        $this->load->view('template/template_view', $data);
    }

    //function install Site
    public function saveSite() {
        $emrs_used = implode(',', $this->input->post('emrs_used'));
        $data = array(
            'version' => $this->input->post('version'),
            'setup_date' => $this->input->post('setup_date'),
            'upgrade_date' => $this->input->post('update_date'),
            'comments' => $this->input->post('category'),
            'contact_name' => $this->input->post('contact_name'),
            'contact_phone' => $this->input->post('contact_phone'),
            'emrs_used' => $emrs_used,
            'active_patients' => $this->input->post('active_patients'),
            'is_internet' => $this->input->post('is_internet'),
            'is_usage' => $this->input->post('is_usage'),
            'facility_id' => $this->input->post('facility_id'),
            'user_id' => $this->input->post('user_id')
        );

        $result = $this->Install_model->insert($data);
        if ($result == TRUE) {
            $this->session->set_flashdata('msg', 'Installation Success');
        } else {
            
        }

        redirect('Admin/Sites');
    }

    //function update site
    public function editSite($id = Null) {
        $data['content_view'] = 'pages/site_update';
        $data['page_title'] = 'ART Dashboard | Sites';
        $data['get_siteInfo'] = $this->Install_model->get_siteInfo($id);
        $data['assigned_username'] = $this->User_model->get_username();
        $data['get_partner'] = $this->Partner_model->read();
        $this->load->view('template/template_view', $data);
    }

    //function updateSite
    public function updateSite() {
        $id = $this->input->post('install_id');
        $emrs_used = implode(',', $this->input->post('emrs_used'));
        $data = array(
            'version' => $this->input->post('version'),
            'setup_date' => $this->input->post('setup_date'),
            'upgrade_date' => $this->input->post('update_date'),
            'comments' => $this->input->post('comments'),
//            'partner' => $this->input->post('partner_id'),
            'contact_name' => $this->input->post('contact_name'),
            'contact_phone' => $this->input->post('contact_phone'),
            'emrs_used' => $emrs_used,
            'active_patients' => $this->input->post('active_patients'),
            'is_internet' => $this->input->post('is_internet'),
            'is_usage' => $this->input->post('is_usage'),
            'user_id' => $this->input->post('user_id'),
        );

        $result = $this->Install_model->update($id, $data);
        if ($result) {
            redirect('Admin/Sites');
        } else {
            return FALSE;
        }
    }

    //function ajax_delete
    public function delete_site($id) {
        $this->Install_model->delete($id);
        echo json_encode(array("status" => TRUE));
    }

//    public function save() {
//
//        //Extract data from the post and set post variables
//        $url = base_url('API/install/index_post');
//
//        $fields = array(
//            'version' => urlencode($_POST['version']),
//            'setup_date' => urlencode($_POST['setup_date']),
//            'upgrade_date' => urlencode($_POST['update_date']),
//            'comments' => urlencode($_POST['category']),
//            'contact_name' => urlencode($_POST['contact_name']),
//            'contact_phone' => urlencode($_POST['contact_phone']),
//            'emrs_used' => urlencode($_POST['emrs_used']),
//            'active_patients' => urlencode($_POST['active_patients']),
//            'is_internet' => urlencode($_POST['is_internet']),
//            'is_usage' => urlencode($_POST['is_usage']),
//            'facility_id' => urlencode($_POST['facility_id']),
//            'user_id' => urlencode($_POST['user_id'])
//        );
//
//        //urlify the data
//        $fields_string = '';
//        foreach ($fields as $key => $value) {
//            $fields_string .= $key . '=' . $value . '&';
//        }
//        rtrim($fields_string, '&');
//
//        //open connection
//        $ch = curl_init();
//
//        //set Url, number of post and post data
//
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, count($fields));
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
//
//        //execute post
//        $result = curl_exec($ch);
//
//        //close connection        
//        curl_close($ch);
//
//        //redirect
//        redirect('Admin/Sites');
//    }
//
//    public function update() {
//        //Stub to update install
////        $data['content_view'] = 'pages/sites_view';
//        $data['page_title'] = 'ART Dashboard | Update';
//        $this->load->view('pages/update_site', $data);
//        redirect('sites');
//    }
}