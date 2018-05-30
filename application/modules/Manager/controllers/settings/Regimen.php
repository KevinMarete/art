<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Regimen extends MX_Controller {

    public function index() {
        $data['content_view'] = 'pages/admin/regimen_view';
        $data['page_title'] = 'ART | Regimen';
        $data['page_name']='regimen';
        $data['get_category'] = $this->Category_model->read();
        $data['get_service'] = $this->Service_model->read();
        $data['get_line'] = $this->Line_model->read();
        $this->load->view('template/template_view', $data);
    }

    public function ajax_list() {
        $list = $this->Regimen_model->get_datatables();
        $data = array();
        $no = '';
        foreach ($list as $regimen) {
            $no++;
            $row = array();
            $row[] = $regimen->code;
            $row[] = $regimen->name;
            $row[] = $regimen->description;
            $row[] = $regimen->category_name;
            $row[] = $regimen->service_name;
            $row[] = $regimen->line_name;

            //add html for action
            $row[] = '<a class="fa fa-pencil" href="javascript:void(0)" title="Edit" onclick="edit_regimen(' . "'" . $regimen->id . "'" . ')"></a> |
				  <a class="fa fa-trash" href="javascript:void(0)" title="Delete" onclick="delete_regimen(' . "'" . $regimen->id . "'" . ')"></a>';

            $data[] = $row;
        }

        $output = array(
            "recordsTotal" => $this->Regimen_model->count_all(),
            "recordsFiltered" => $this->Regimen_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id) {
        $data = $this->Regimen_model->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add() {
        $this->_validate();
        $data = array(
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'category_id' => $this->input->post('category_id'),
            'service_id' => $this->input->post('service_id'),
            'line_id' => $this->input->post('line_id')
        );
        $insert = $this->Regimen_model->save($data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_update() {
        $this->_validate();
        $data = array(
            'code' => $this->input->post('code'),
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'category_id' => $this->input->post('category_id'),
            'service_id' => $this->input->post('service_id'),
            'line_id' => $this->input->post('line_id')
        );
        $this->Regimen_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => TRUE));
    }

    public function ajax_delete($id) {
        $this->Regimen_model->delete_by_id($id);
        echo json_encode(array("status" => TRUE));
    }

    private function _validate() {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('code') == '') {
            $data['inputerror'][] = 'code';
            $data['error_string'][] = 'Regimen Code is required';
            $data['status'] = FALSE;
        }

        if ($this->input->post('name') == '') {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Regimen Name is required';
            $data['status'] = FALSE;
        }
        if ($this->input->post('description') == '') {
            $data['inputerror'][] = 'description';
            $data['error_string'][] = 'Description is required';
            $data['status'] = FALSE;
        }
        if ($this->input->post('category_id') == '') {
            $data['inputerror'][] = 'category_id';
            $data['error_string'][] = 'Category Name is required';
            $data['status'] = FALSE;
        }

        if ($this->input->post('service_id') == '') {
            $data['inputerror'][] = 'service_id';
            $data['error_string'][] = 'Service Name is required';
            $data['status'] = FALSE;
        }

        if ($this->input->post('line_id') == '') {
            $data['inputerror'][] = 'line_id';
            $data['error_string'][] = 'Line Name is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

}
