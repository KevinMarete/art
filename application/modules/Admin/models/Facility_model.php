<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Facility_model extends CI_Model {

    var $table = 'tbl_facility';
    var $column_order = array('tbl_facility.name', 'tbl_facility.mflcode');
    var $column_search = array('tbl_facility.name', 'tbl_facility.mflcode');
    var $order = array('tbl_facility.id' => 'desc');

    private function _get_datatables_query() {
        $this->db->select('tbl_facility.id as facility_id,tbl_facility.name,tbl_facility.mflcode,tbl_facility.category,tbl_facility.dhiscode,'
                . 'tbl_facility.longitude,tbl_facility.latitude,tbl_subcounty.name as subcounty_name,tbl_partner.name as partner_name');
        $this->db->from($this->table);
        $this->db->join('tbl_subcounty', 'tbl_facility.subcounty_id=tbl_subcounty.id');
        $this->db->join('tbl_partner', 'tbl_partner.id=tbl_facility.partner_id');

        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables() {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_id($id) {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data) {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    //function get user_names
    public function get_username() {
        $this->db->select('id, name');
        $this->db->from('tbl_user');
        $query = $this->db->get();
        return $query->result();
    }

}