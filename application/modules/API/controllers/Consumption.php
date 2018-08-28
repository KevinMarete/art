<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

/**
 *
 * @package         ART
 * @subpackage      API
 * @category        Controller
 * @author          Kevin Marete
 * @license         MIT
 * @link            https://github.com/KevinMarete/ART
 */
class Consumption extends \API\Libraries\REST_Controller  {

    function __construct()
    {
        parent::__construct();
        $this->load->model('consumption_model');
    }

    public function index_get()
    {   
        //Default parameters
        $year = $this->get('year');
        $month = $this->get('month');
        $facility = (int) $this->get('facility');
        $drug = (int) $this->get('drug');

        //Conditions
        $conditions = array(
            'period_year' => $year,
            'period_month' => $month,
            'facility_id' => $facility,
            'drug_id' => $drug
        );
        $conditions = array_filter($conditions);

        // consumption from a data store e.g. database
        $consumptions = $this->consumption_model->read($conditions);

        // If parameters don't exist return all the consumption
        if ($facility <= 0 || $drug <= 0)
        {
            // Check if the consumption data store contains consumption (in case the database result returns NULL)
            if ($consumptions)
            {
                // Set the response and exit
                $this->response($consumptions, \API\Libraries\REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                // Set the response and exit
                $this->response([
                    'status' => FALSE,
                    'message' => 'No consumption was found'
                ], \API\Libraries\REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
        // Find and return a single record for a particular consumption.
        else {
            // Validate the facility/drug.
            if ($facility <= 0 || $drug <= 0)
            {
                // Invalid id, set the response and exit.
                $this->response(NULL, \API\Libraries\REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

            // Get the consumption from the array, using the id as key for retrieval.
            // Usually a model is to be used for this.

            $consumption = NULL;

            if (!empty($consumptions))
            {      
                foreach ($consumptions as $key => $value)
                {   
                    if ($value['period_year'] == $year && $value['period_month'] == $month && $value['facility_id'] == $facility && $value['drug_id'] == $drug)
                    {
                        $consumption = $value;
                    }
                }
            }

            if (!empty($consumption))
            {
                $this->set_response($consumption, \API\Libraries\REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
            }
            else
            {
                $this->set_response([
                    'status' => FALSE,
                    'message' => 'consumption could not be found'
                ], \API\Libraries\REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
            }
        }
    }

    public function index_post()
    {   
        $data = array(
            'total' => $this->post('total'),
            'period_year' => $this->post('period_year'),
            'period_month' => $this->post('period_month'),
            'facility_id' => $this->post('facility_id'),
            'drug_id' => $this->post('drug_id')
        );
        $data = $this->consumption_model->insert($data);
        if($data['status'])
        {
            unset($data['status']);
            $this->set_response($data, \API\Libraries\REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        else
        {
            unset($data['status']);
            $this->set_response([
                'status' => FALSE,
                'message' => 'Error has occurred'
            ], \API\Libraries\REST_Controller::HTTP_BAD_REQUEST); // CREATED (201) being the HTTP response code
        }
    }

    public function index_put()
    {   
        $facility = (int) $this->get('facility');
        $drug = (int) $this->get('drug');

        $conditions = array(
            'period_year' => $this->get('year'),
            'period_month' => $this->get('month'),
            'facility_id' => $facility,
            'drug_id' => $drug
        );

        // Validate facility and drug.
        if ($facility <= 0 || $drug <= 0)
        {
            // Set the response and exit
            $this->response(NULL, \API\Libraries\REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $data = array(
            'total' => $this->put('total')
        );
        $data = $this->consumption_model->update($conditions, $data);
        if($data['status'])
        {
            unset($data['status']);
            $this->set_response($data, \API\Libraries\REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        else
        {
            unset($data['status']);
            $this->set_response([
                'status' => FALSE,
                'message' => 'Error has occurred'
            ], \API\Libraries\REST_Controller::HTTP_BAD_REQUEST); // CREATED (201) being the HTTP response code
        }
    }

    public function index_delete()
    {
        $facility = (int) $this->get('facility');
        $drug = (int) $this->get('drug');

        $conditions = array(
            'period_year' => $this->get('year'),
            'period_month' => $this->get('month'),
            'facility_id' => $facility,
            'drug_id' => $drug
        );

        // Validate facility and drug.
        if ($facility <= 0 || $drug <= 0)
        {
            // Set the response and exit
            $this->response(NULL, \API\Libraries\REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }

        $data = $this->consumption_model->delete($conditions);
        if($data['status'])
        {
            unset($data['status']);
            $this->set_response([
                'status' => TRUE,
                'message' => 'Data is deleted successfully'
            ], \API\Libraries\REST_Controller::HTTP_NO_CONTENT); // NO_CONTENT (204) being the HTTP response code
        }
        else
        {
            unset($data['status']);
            $this->set_response([
                'status' => FALSE,
                'message' => 'Error has occurred'
            ], \API\Libraries\REST_Controller::HTTP_BAD_REQUEST); // CREATED (201) being the HTTP response code
        }
    }

}
