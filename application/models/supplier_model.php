<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Supplier_model extends CI_Model
{
    public function insert($data) {
        return $this->db->insert('tbl_supplier', $data);
    }

    public function getAllSuppliers() {
        $query = $this->db->get('tbl_supplier');
        return $query->result();
    }

    public function getById($id) {
        $query = $this->db->get_where('tbl_supplier', array('id' => $id));
        return $query->row();
    }

    public function delete($id) {
        return $this->db->delete('tbl_supplier', array('id' => $id));
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('tbl_supplier', $data);
    }

    public function getSuppliers($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get('tbl_supplier');
        return $query->result();
    }

    // Method to count total suppliers
    public function getSupplierCount()
    {
        return $this->db->count_all('tbl_supplier');
    }
    public function getSupplierById($id) {
        $this->db->where('id', $id);
        $query = $this->db->get('tbl_supplier');
        return $query->row();
    }
}
?>
