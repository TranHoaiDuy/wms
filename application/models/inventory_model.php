<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Inventory_model extends CI_Model
{
    public function getInventory()
    {
        $this->db->select('i.id, i.materialId, i.quantityAvailable, n.materialName, n.unit');
        $this->db->from('inventory i');
        $this->db->join('tbl_nvl n', 'i.materialId = n.id');


        $this->db->order_by('n.materialName', 'ASC');

        $query = $this->db->get();
        return $query->result_array();
    }
    public function searchInventory($searchText)
    {
        $this->db->select('i.id, i.materialId, i.quantityAvailable, n.materialName, n.unit');
        $this->db->from('inventory i');
        $this->db->join('tbl_nvl n', 'i.materialId = n.id');
        if (!empty($searchText)) {
            $this->db->like('n.materialName', $searchText);
        }
        $this->db->order_by('n.materialName', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
    public function getInventoryDetails($id)
    {
        $this->db->select('n.materialName, n.unit, l.cargo_area, l.cargo_location, SUM(g.quantity) as total_quantity, g.material_id, g.qrcode_item');
        $this->db->from('qrcode_location_map qr');
        $this->db->join('goods_receipt_item g', 'g.id = qr.goods_receipt_item_id AND g.rating = 1');
        $this->db->join('tbl_location l', 'l.id = qr.location_id');
        $this->db->join('goods_receipt r', 'r.id = g.goods_receipt_id AND r.status = 4');
        $this->db->join('tbl_nvl n', 'n.id = g.material_id');
        $this->db->where('g.material_id', $id);
        $this->db->group_by(['g.material_id', 'l.cargo_area', 'l.cargo_location']);

        $query = $this->db->get();
        return $query->result_array();
    }
    public function get_stock_quantity($materialId)
    {
        $this->db->select('quantityAvailable');
        $this->db->from('inventory');
        $this->db->where('materialId', $materialId);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->quantityAvailable;
        } else {
            return 0;
        }
    }
    public function getStockReport()
    {
        $this->db->select('n.materialCode, n.materialName, n.unit, i.quantityAvailable');

        // Lấy quantity với typeId = 1
        $this->db->select('SUM(CASE WHEN r.typeId = 1 THEN r.quantity ELSE 0 END) AS quantity_type1', FALSE);

        // Lấy quantity với typeId = 2
        $this->db->select('SUM(CASE WHEN r.typeId = 2 THEN r.quantity ELSE 0 END) AS quantity_type2', FALSE);


        $this->db->from('inventory i');
        $this->db->join('tbl_nvl n', 'i.materialId = n.id', 'inner');
        $this->db->join('inventory_tracking r', 'r.materialId = n.id', 'left');

        $this->db->group_by('n.id');

        $query = $this->db->get();
        return $query->result();
    }
}