<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Factory_model extends CI_Model
{
    public function insert($data)
    {
        return $this->db->insert('factory', $data);
    }

    public function getAllFactories()
    {
        $query = $this->db->get('factory');
        return $query->result();
    }

    public function getById($id)
    {
        $query = $this->db->get_where('factory', array('id' => $id));
        return $query->row();
    }

    public function delete($id)
    {
        return $this->db->delete('factory', array('id' => $id));
    }

    public function update($id, $data)
    {
        return $this->db->where('id', $id)->update('factory', $data);
    }
    // public function getFactories($limit, $start)
    // {
    //     $this->db->limit($limit, $start);
    //     $query = $this->db->get('factory');
    //     return $query->result();
    // }

    // New method to count total materials
    // public function getFactoryCount()
    // {
    //     return $this->db->count_all('factory');
    // }

    // function getFactoryPagination($page = 0, $segment = 25)
    // {
    //     $this->db->from('factory');
    //     // if (!empty($searchText)) {
    //     //     $likeCriteria = "(invoice_code  LIKE '%" . $searchText . "%'
    //     //                     OR  code  LIKE '%" . $searchText . "%')";
    //     //     $this->db->where($likeCriteria);
    //     // }
    //     $this->db->limit($page, $segment);
    //     $query = $this->db->get();

    //     $result = $query->result();
    //     return $result;
    // }


    function getCountFactory($searchText = '')
    {
        $this->db->from('factory');
        if (!empty($searchText)) {
            $likeCriteria = "(name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        return count($query->result());
    }

    function getFactories($searchText = '', $page = 0, $segment = 25)
    {
        $this->db->from('factory');
        if (!empty($searchText)) {
            $likeCriteria = "(name  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        // $this->db->order_by('FIELD(status,3,0,1,2,4)');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
}