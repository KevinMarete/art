<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MX_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->model('Orders_model');
    }

    public function assign_scope(){
    	$post_data['scope_id'] = $this->input->post('scope');
    	$post_data['role_id'] = $this->session->userdata('role_id');
    	$post_data['user_id'] = $this->session->userdata('id');

    	$response = $this->Orders_model->update_user_scope($post_data);
    	if($response['status']){
			$this->session->set_userdata('scope', $post_data['scope_id']);
			$message = '<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						'.$response['message'].'</div>';
			
		}else{
			$message = '<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Error!</strong> '.$response['message'].'</div>';
		}
		$this->session->set_flashdata('orders_msg', $message);
		redirect('manager/orders/assign');
    }
	public function updateOrder($orderid)
	{	
		$updateArray = array();
		foreach ($_POST as $key => $value) {
		$vals = array(explode('-', $key)[0] => $value, 'id'=>explode('-', $key)[1]+0 );
		array_push($updateArray, $vals);

		// var_dump($vals);
		// die;
		 // $this->Orders_model->updateOrder($orderid,$vals,$this->session->userdata('id'));
		 
		
		}
		$response = $this->Orders_model->updateOrder($orderid,$updateArray,$this->session->userdata('id'));
var_dump($response);

		// var_dump($this->session->all_userdata());
		// $response = $this->Orders_model->get_order_data($this->session->userdata('scope'),$this->session->userdata('role'));
		// 	echo json_encode(array('data' => $response['data']));
	}
	public function actionOrder($orderid,$action)
	{	

		$response = $this->Orders_model->actionOrder($orderid,$action,$this->session->userdata('id'));
			echo $response['message'];
	}

	public function get_orders()
	{	
		$response = $this->Orders_model->get_order_data($this->session->userdata('scope'),$this->session->userdata('role'));
			echo json_encode(array('data' => $response['data']));
	}

	public function get_reporting_rates(){
		$response = $this->Orders_model->get_reporting_data($this->session->userdata('scope'), $this->session->userdata('role'), date('Y-m-d', strtotime('first day of last month')), date('Y-m-d', strtotime('last day of last month')));
		echo json_encode(array('data' => $response['data']));
	}
        
    public function get_allocation(){
		$response = $this->Orders_model->get_allocation_data($this->session->userdata('scope'), $this->session->userdata('role'), date('Y-m-d', strtotime('first day of last month')), date('Y-m-d', strtotime('last day of last month')));
		echo json_encode(array('data' => $response['data']));
	}

	public function get_county_allocation($period_begin){
		$response = $this->Orders_model->get_county_allocation_data($this->session->userdata('scope'), $this->session->userdata('role'), $period_begin, date('Y-m-t', strtotime($period_begin)));
		echo json_encode(array('data' => $response['data']));
	}
		
}