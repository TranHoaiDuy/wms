<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Location_model extends CI_Model
{
    public function insert($data)
    {
        return $this->db->insert('tbl_location', $data);
    }

    public function getAllLocations()
    {
        $query = $this->db->get('tbl_location');
        return $query->result();
    }

    public function getById($id)
    {
        $query = $this->db->get_where('tbl_location', array('id' => $id));
        return $query->row();
    }

    public function delete($id)
    {
        return $this->db->delete('tbl_location', array('id' => $id));
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('tbl_location', $data);
    }
    
    public function getLocations($limit, $start, $orderBy = 'cargo_area', $orderDirection = 'ASC')
    {
        $this->db->select('l.*, n.materialName');
        $this->db->from('tbl_location l');
        $this->db->join('tbl_nvl n', 'l.materialId = n.id');
        $this->db->order_by('cargo_area', 'ASC');
        $this->db->order_by('cargo_location', 'ASC');
        $this->db->order_by($orderBy, $orderDirection);
        $this->db->limit($limit, $start);
        $query = $this->db->get();
        return $query->result();
    }



    public function getLocationsCount()
    {
        return $this->db->count_all('tbl_location');
    }
    public function getLocationsByStatus($materialId)
    {
        $this->db->select('l.*, n.materialName');
        $this->db->from('tbl_location l');
        $this->db->join('tbl_nvl n', 'l.materialId = n.id');
        $this->db->where('l.materialId', $materialId);
        $this->db->where('l.cargo_status', 1);

        $query = $this->db->get();

        return $query->result();
    }
}