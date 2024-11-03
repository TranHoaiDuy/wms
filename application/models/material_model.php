<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Material_model extends CI_Model
{
    public function insert($data)
    {
        return $this->db->insert('tbl_nvl', $data);
    }

    public function getAllMaterials($type = 'array')
    {
        $query = $this->db->get('tbl_nvl');

        if ($type === 'array') {
            return $query->result_array();
        } else {
            return $query->result();
        }
    }

    public function getById($id)
    {
        $query = $this->db->get_where('tbl_nvl', array('id' => $id));
        return $query->row();
    }

    public function delete($id)
    {
        return $this->db->delete('tbl_nvl', array('id' => $id));
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('tbl_nvl', $data);
    }
    public function getMaterials($limit, $start)
    {
        $this->db->limit($limit, $start);
        $query = $this->db->get('tbl_nvl');
        return $query->result();
    }

    // New method to count total materials
    public function getMaterialCount()
    {
        return $this->db->count_all('tbl_nvl');
    }
    function getMaterialPagination($page = 0, $segment = 25)
    {
        $this->db->from('tbl_nvl');
        // if (!empty($searchText)) {
        //     $likeCriteria = "(invoice_code  LIKE '%" . $searchText . "%'
        //                     OR  code  LIKE '%" . $searchText . "%')";
        //     $this->db->where($likeCriteria);
        // }
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
    public function getAllMaterialsWithInventory($type = 'array')
    {
        $this->db->select('n.*, i.quantityAvailable');
        $this->db->from('tbl_nvl n');
        $this->db->join('inventory i', 'i.materialId = n.id', 'left');

        $query = $this->db->get();


        if ($type === 'array') {
            return $query->result_array();
        } else {
            return $query->result();
        }
    }
}