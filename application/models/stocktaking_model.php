<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Stocktaking_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('stocktaking', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function get_inventory_report_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('stocktaking')->result();
    }

    public function get_inventory_report_detail_Id($id)
    {
        $this->db->select('s.id , s.materialId,s.realQuantity, i.quantityAvailable	, n.unit , n.materialName, n.materialCode');
        $this->db->from('stocktaking_item s');
        $this->db->join('tbl_nvl n', 'n.id = s.materialId');
        $this->db->join('inventory i', 'i.materialId = n.id');
        $this->db->join('stocktaking g', 'g.id = s.stocktakingId and g.id = ' . $id);
        $query = $this->db->get();
        return $query->result();
    }

    public function insert_inventory_item($data)
    {
        $this->db->insert('stocktaking_item', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function delete_inventory_item($id)
    {
        return $this->db->delete('stocktaking_item', array('id' => $id));
    }
    public function update_inventory_item($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('stocktaking_item');
    }
    public function update_inventory_report($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('stocktaking');
    }
    function getCountStocktakingInventory($searchText = '', $userId = 0)
    {
        $this->db->from('stocktaking');
        if (!empty($searchText)) {
            $likeCriteria = "(code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        if($userId > 0){
            $this->db->where('stocktakingById =', $userId);
        }
        $query = $this->db->get();
        return count($query->result());
    }
    function getStocktakingInventory($searchText = '', $userId = 0, $page = 0, $segment = 25)
    {
        $this->db->select('g.*, u.name');
        $this->db->from('stocktaking g');
        $this->db->join('tbl_users u', 'u.userId = g.stocktakingById', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('FIELD(status,3,0,1,2,4)');
        if($userId > 0){
            $this->db->where('stocktakingById =', $userId);
        }
        $this->db->limit($page, $segment);
       
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }
}