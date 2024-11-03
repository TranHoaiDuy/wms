<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Issue_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('goods_issue', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function update_issue($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('goods_issue');
    }
    public function get_issue_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('goods_issue')->result();
    }
    public function getGINumber($year, $month)
    {
        // Kiểm tra có cùng năm và tháng trong database
        $this->db->where('year', $year);
        $this->db->where('month', $month);
        $query = $this->db->get('gi_number');

        // Trả về kết quả nếu có, nếu không thì trả về null
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }
    public function insertGINumber($data)
    {
        return $this->db->insert('gi_number', $data);
    }
    public function updateGINumber($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('gi_number');
    }
    public function insert_goods_issue_item($data)
    {
        $this->db->insert('goods_issue_item', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function delete_goods_issue_item($id)
    {
        return $this->db->delete('goods_issue_item', array('id' => $id));
    }
    public function update_goods_issue_item($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('goods_issue_item');
    }

    public function get_goods_issue_item($id)
    {
        $this->db->select('quantity, materialId');
        $this->db->where('goodsIssueId', $id);
        return $this->db->get('goods_issue_item')->row_array();
    }


    public function get_goods_issue_detail_Id($id)
    {
        $this->db->select('i.id , i.materialId, i.quantity, m.unit , m.materialName, m.materialCode, m.quantity as materialQuantity, i.packing_quantity, i.realQuantity');
        $this->db->from('goods_issue_item i');
        $this->db->join('tbl_nvl m', 'm.id = i.materialId');
        $this->db->join('goods_issue g', 'g.id = i.goodsIssueId and g.id = ' . $id);
        // $this->db->where('i.goods_issue_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function getGoodsIssueRE($searchText = '', $page = 0, $segment = 10)
    {
        $this->db->select('g.*, f.name');
        $this->db->from('goods_issue g');
        $this->db->join('factory f', 'g.factoryId = f.id', 'left');
        if (!empty($searchText)) {
            $likeCriteria = "(code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('FIELD(status,0,1,2,3,4)');
        $this->db->order_by('id', 'desc');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    function getCountGoodsIssueRE($searchText = '')
    {
        $this->db->from('goods_issue');
        if (!empty($searchText)) {
            $likeCriteria = "(code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        return count($query->result());
    }

    function getCountGoodsIssue($searchText = '')
    {
        $this->db->from('goods_issue');
        if (!empty($searchText)) {
            $likeCriteria = "(code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }

        $this->db->where('status >=', 2);
        $query = $this->db->get();
        return count($query->result());
    }

    function getGoodsIssueGI($searchText = '', $page = 0, $segment = 10)
    {
        $this->db->select('g.*, f.name');
        $this->db->from('goods_issue g');
        $this->db->join('factory f', 'g.factoryId = f.id', 'left');
        $this->db->where('status >=', 2);
        if (!empty($searchText)) {
            $likeCriteria = "(code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('FIELD(status,1,2,3,4,0)');
        $this->db->order_by('id', 'desc');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    public function getHintLocation($id, $quantity)
    {
        $this->db->select('r.material_id, l.id as locationId, l.cargo_area, l.cargo_location');
        $this->db->from('qrcode_location_map qcm');
        $this->db->join('goods_receipt_item r', 'qcm.goods_receipt_item_id = r.id');
        $this->db->join('tbl_location l', 'l.id = qcm.location_id');
        $this->db->join('goods_receipt d', 'r.goods_receipt_id = d.id');
        //$this->db->where('qcm.goods_receipt_item_id', '30');
        $this->db->where('r.material_id', $id);
        $this->db->order_by('d.receipt_datetime', 'asc');
        $this->db->limit(3);
        //$this->db->where('(d.rating = 1 or d.rating = 0)');
        $query = $this->db->get();
        return $query->result();
    }

    public function getItemByQRcode($id, $qrcode)
    {
        $this->db->select('r.id as grId, i.id as giId, m.materialCode, r.quantity, r.goodsIssueItemId, r.qrcode_item, i.realQuantity, i.quantity as giQuantity');
        $this->db->from('goods_receipt_item r');
        $this->db->join('qrcode_location_map qcm', 'r.id = qcm.goods_receipt_item_id');
        $this->db->join('tbl_nvl m', 'r.material_id = m.id');
        $this->db->join('goods_issue_item i', 'r.material_id = i.materialId');
        $this->db->where('i.goodsIssueId', $id);
        $this->db->where('r.qrcode_item', $qrcode);
        //$this->db->where('i.status != 0');
        //$this->db->order_by('d.receipt_datetime','desc');
        //$this->db->limit($quantity);
        //$this->db->where('(d.rating = 1 or d.rating = 0)');
        $query = $this->db->get();
        return $query->result();
    }
}
