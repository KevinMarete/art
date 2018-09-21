<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

/** load the CI class for Modular Extensions * */
require dirname(__FILE__) . '/Base.php';

/**
 * Modular Extensions - HMVC
 *
 * Adapted from the CodeIgniter Core Classes
 * @link	http://codeigniter.com
 *
 * Description:
 * This library replaces the CodeIgniter Controller class
 * and adds features allowing use of modules and the HMVC design pattern.
 *
 * Install this file as application/third_party/MX/Controller.php
 *
 * @copyright	Copyright (c) 2015 Wiredesignz
 * @version 	5.5
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 * */
class MX_Controller {

    public $autoload = array();

    public function __construct() {
        $class = str_replace(CI::$APP->config->item('controller_suffix'), '', get_class($this));
        log_message('debug', $class . " MX_Controller Initialized");
        Modules::$registry[strtolower($class)] = $this;

        /* copy a loader instance and initialize */
        $this->load = clone load_class('Loader');
        $this->load->initialize($this);

        /* autoload module items */
        $this->load->_autoloader($this->autoload);

        /* Add default timezone */
        date_default_timezone_set("Africa/Nairobi");
    }

    public function __get($class) {
        return CI::$APP->$class;
    }

    function sendReminder() {
        $this->load->library('email_sender');
        $date = date('d');
        $list = '';
        $mailing_list = $this->db->where('email_type', '3')->get('tbl_mailing_list')->result();
        foreach ($mailing_list as $m) {
            $list .= $m->email . ',';
        }
        $mails = rtrim($list, ",");

        if ($date == 14 || $date == 15 || $date == 18 || $date == 20) {
            $data = '<p><strong>ALLOCATION REMINDER NOTICE</strong></p>';
            $data .= '<p>This is to remind you of your pending drug allocations report. Kindly do allocation befor the due date of 20th.</p>';
           // $this->email_sender->send_email_reminders('Allocation Manager', 'Allocate', $mails, $names = '', $data);
        }
    }

    function getCountySubcounty() {
        $select = '';
        $county = $this->db->order_by('id', 'asc')->get('tbl_county')->result();
        foreach ($county as $c):
            $select .= '<optgroup data-id="' . $c->id . '" label="' . ucfirst($c->name) . '"></optgroup>';
            $subcounty = $this->db->where('county_id', $c->id)->order_by('id', 'asc')->get('tbl_subcounty')->result();
            foreach ($subcounty as $sc):
                $select .= '<option value="' . $sc->id . '">' . ucfirst($sc->name) . '</option>';
            endforeach;
        endforeach;

        return $select;
    }

}
