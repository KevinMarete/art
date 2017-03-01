<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MX_Controller {

	public function index()
	{	
		$data['page_title'] = 'Commodities | Dashboard';
		$this->load->view('dashboard_view', $data);
	}

	public function load_chart()
	{	
		$titles = array('desc'=>'Top','asc'=>'Bottom');
		$chart_name = $this->input->post('name');
		$metric = $this->input->post('metric');
		$selectedfilters = $this->input->post('selectedfilters');
		$order = $this->input->post('order');
		$limit = $this->input->post('limit');

		#Load chart config
		$chart_type = $this->config->item($chart_name.'_chart_type');
		$metric_title = $this->config->item($chart_name.'_'.$metric.'_chart_metric_title');
		$metric_units = $this->config->item($chart_name.'_'.$metric.'_metric_prefix');
		$view_name = $this->config->item($chart_name.'_view_name');
		$color_point = $this->config->item($chart_name.'_color_point');
		$chart_x_variable = $this->config->item($chart_name.'_x_variable');

		#Get view data                                                                             
		$view_data = $this->dashboard_model->get_view_data($view_name, $chart_x_variable, $metric_title, $selectedfilters, $order, $limit);
		$chart_columns = array();
		$chart_data = array();
		foreach ($view_data as $row) {
			foreach ($row as $i => $v) {
				if ($i == $chart_x_variable){
					$chart_columns[] = $v;
				}else{
					if($chart_type == 'pie'){
						$chart_data[] = array('name' => $row[$chart_x_variable], 'y' => floatval($v));
					}else{
						$chart_data[] = floatval($v);
					}
				}
			}
		}
		#Build chart
		$data['chart_name'] = $chart_name;
		$data['chart_type'] = $chart_type;
		$data['chart_title'] = ucwords(str_replace('_', ' ', $chart_name)).' '.$titles[$order].' '.$limit.' Summary';
		$data['chart_metric_title'] = ucwords($metric.$metric_units);
		$data['chart_columns'] = json_encode($chart_columns);
		$data['chart_data'] = json_encode(array(array('name' => $chart_name, 'colorByPoint' => $color_point, 'data' => array_values($chart_data))));
		$data['chart_source'] = 'Source: www.nascop.org' ;

		$this->load->view('chart_view', $data);
	}

	public function get_filter($chart_name)
	{	
		$data = array();
		//Get filters from chart cfg
		$filters = $this->config->item($chart_name.'_filters');
		$view_name = $this->config->item($chart_name.'_view_name');
		foreach ($filters as $column) {
			$filter_data = $this->dashboard_model->get_filters($column, $view_name);
			foreach ($filter_data as $item) {
				$data[$column][] = array('id'=> $item['filter'], 'text' =>  strtoupper($item['filter']));
			}
		}
		echo json_encode($data);
	}

}
