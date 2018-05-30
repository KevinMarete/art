<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class County extends MX_Controller {

    public function index() {
        $data['content_view'] = 'pages/admin/county_view';
        $data['page_title'] = 'ART | County';
        $data['page_name'] = 'county';
        $this->load->view('template/template_view', $data);
    }

    public function ajax_list() {
        $list = $this->County_model->get_datatables();
        $data = array();
        $no = '';
        foreach ($list as $county) {
            $no++;
            $row = array();
            $row[] = $county->name;
            //add html for action
            $row[] = '<a class="fa fa-pencil" href="javascript:void(0)" title="Edit" onclick="edit_county(' . "'" . $county->id . "'" . ')"></a> |
				  <a class="fa fa-trash" href="javascript:void(0)" title="Delete" onclick="delete_county(' . "'" . $county->id . "'" . ')"></a>';

            $data[] = $row;
        }

        $output = array(
            "recordsTotal" => $this->County_model->count_all(),
            "recordsFiltered" => $this->County_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->County_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {
        $this->validate();
        $data = array(
            'name' => $this->input->post('name')
        );
        $insert = $this->County_model->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->validate();
        $data = array(
            'name' => $this->input->post('name')
        );
        $this->County_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->County_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('name') == '') {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'County Name is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
