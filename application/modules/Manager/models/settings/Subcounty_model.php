<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subcounty_model extends CI_Model {

    var $table = 'tbl_subcounty';
    var $column_order = array('tbl_subcounty.name','tbl_county.name');
    var $column_search = array('tbl_subcounty.name','tbl_county.name');
    var $order = array('tbl_subcounty.id' => 'desc');

    private function _get_datatables_query() {
        $this->db->select('tbl_subcounty.id,tbl_subcounty.name as subcounty_name, tbl_county.name as county_name');
        $this->db->from($this->table);
        $this->db->join('tbl_county', 'tbl_county.id=tbl_subcounty.county_id');

        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($_POST['search']['value']) {
                if ($i === 0) { // first loop
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end();
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
    
    public function read()
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $query=$this->db->get();
        return $query->result();
    }

}
