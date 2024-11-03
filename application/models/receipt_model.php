<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Receipt_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('goods_receipt', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function update_receipt($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('goods_receipt');
    }

    public function getGINumber($year, $month)
    {
        // Kiểm tra có cùng năm và tháng trong database
        $this->db->where('year', $year);
        $this->db->where('month', $month);
        $query = $this->db->get('gr_number');

        // Trả về kết quả nếu có, nếu không thì trả về null
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return null;
        }
    }

    public function insertGINumber($data)
    {
        // Hàm để thêm bản ghi mới vào bảng gr_number
        return $this->db->insert('gr_number', $data);
    }

    public function updateGINumber($data, $id)
    {
        // Hàm cập nhật bản ghi nếu có cùng năm và tháng
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('gr_number');
    }

    // public function getNextId()
    // {
    //     $this->db->select_max('id');
    //     $query = $this->db->get('tbl_receipt');
    //     $result = $query->row();

    //     return $result->id;
    // }

    public function insert_goods_receipt_item($data)
    {
        $this->db->insert('goods_receipt_item', $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    public function delete_goods_receipt_item($id)
    {
        return $this->db->delete('goods_receipt_item', array('id' => $id));
    }
    // public function delete_goods_receipt_item($id)
    // {

    //     $item_tmp = $this->db->get_where('goods_receipt_item', ['id' => $id])->row();
    //     return $this->db->delete('qrcode', ['id' => $item_tmp->qrcode_id]);
    // }

    public function update_goods_receipt_item($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('goods_receipt_item');
    }

    // public function insert_qrcode($data)
    // {
    //     $this->db->insert('qrcode', $data);
    //     $insert_id = $this->db->insert_id();
    //     return $insert_id;
    // }

    // public function insert_receipt_details($materialsData)
    // {
    //     foreach ($materialsData as $data) {
    //         $this->db->where('receipt_code', $data['receipt_code']);
    //         $this->db->where('material_id', $data['material_id']);
    //         $query = $this->db->get('tbl_receipt_details');

    //         if ($query->num_rows() == 0) {
    //             $this->db->insert('tbl_receipt_details', $data);
    //         }
    //     }
    // }
    public function get_receipt_by_id($id)
    {
        $this->db->where('id', $id);
        return $this->db->get('goods_receipt')->result();
    }

    public function get_goods_receipt_detail_Id($id, $isGR = false)
    {
        $this->db->select('d.id, d.material_id, d.quantity, d.note, d.rating, d.qrcode_item, m.unit , m.materialName, m.materialCode, d.code, l.id as locationId, l.cargo_area, l.cargo_location');
        $this->db->from('goods_receipt_item d');
        $this->db->join('goods_receipt r', 'd.goods_receipt_id = r.id');
        $this->db->join('tbl_nvl m', 'm.id = d.material_id');
        $this->db->join('qrcode_location_map qcm', 'qcm.goods_receipt_item_id = d.id', 'left');
        $this->db->join('tbl_location l', 'l.id = qcm.location_id', 'left');

        $this->db->where('r.id', $id);
        if ($isGR) {
            $this->db->where('(d.rating = 1 or d.rating = 0)');
        }
        //$this->db->where('(d.rating = 1 or d.rating = 0)');
        $query = $this->db->get();
        return $query->result();
    }

    // public function get_receipt_details_check($receipt_code)
    // {
    //     $this->db->select('d.quantity, s.materialCode, s.unit, s.materialName, d.note, d.status,d.material_id');
    //     $this->db->from('tbl_receipt_details d');
    //     $this->db->join('tbl_receipt r', 'd.receipt_code = r.receipt_code');
    //     $this->db->join('tbl_nvl s', 's.id = d.material_id');
    //     $this->db->where('d.receipt_code', $receipt_code);
    //     $query = $this->db->get();
    //     return $query->result();
    // }

    // public function updateMaterialStatus($material_id, $data)
    // {
    //     $this->db->where('material_id', $material_id);
    //     if (!$this->db->update('tbl_receipt_details', $data)) {
    //         log_message('error', 'Database update failed: ' . $this->db->last_query());
    //         return false;
    //     }
    //     return true;
    // }

    function getCountGoodsReceipts($searchText = '')
    {
        $this->db->from('goods_receipt');
        if (!empty($searchText)) {
            $likeCriteria = "(invoice_code  LIKE '%" . $searchText . "%'
                            OR  code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $query = $this->db->get();
        return count($query->result());
    }

    function getGoodsReceipts($searchText = '', $page = 0, $segment = 10)
    {
        $this->db->from('goods_receipt');
        if (!empty($searchText)) {
            $likeCriteria = "(invoice_code  LIKE '%" . $searchText . "%'
                            OR  code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->order_by('FIELD(status,3,0,1,2,4)');
        $this->db->order_by('id','desc');
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }


    function getCountMoMReports($searchText = '')
    {
        $this->db->from('goods_receipt');
        if (!empty($searchText)) {
            $likeCriteria = "(invoice_code  LIKE '%" . $searchText . "%'
                            OR  code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }

        $query = $this->db->get();
        return count($query->result());
    }

    function getMoMReports($searchText = '', $page = 0, $segment = 10)
    {
        $this->db->from('goods_receipt');
        $this->db->order_by('status');
        $this->db->order_by('id','desc');
        if (!empty($searchText)) {
            $likeCriteria = "(invoice_code  LIKE '%" . $searchText . "%'
                            OR  code  LIKE '%" . $searchText . "%')";
            $this->db->where($likeCriteria);
        }
        $this->db->limit($page, $segment);
        $query = $this->db->get();

        $result = $query->result();
        return $result;
    }

    public function getMoMGoodsReceipts()
    {
        $this->db->where('status >= ', 1);
        $query = $this->db->get('tbl_receipt');
        return $query->result();
    }
    public function insertLocationMap($data)
    {
        $insert_id = $this->db->insert('qrcode_location_map', $data);
        return $insert_id;
    }
    public function  update_receipt_details_qrID($data, $id)
    {
        $this->db->set($data);
        $this->db->where('id', $id);
        return $this->db->update('goods_receipt_item');
    }

    
    public function checkIfMapped($id)
    {
        $this->db->from('goods_receipt r');
        $this->db->join('goods_receipt_item d', 'r.id = d.goods_receipt_id');
        $this->db->join('qrcode_location_map qcm', 'd.id = qcm.goods_receipt_item_id');
        $this->db->where('r.id', $id);
        
        $query = $this->db->get();

        return $query->num_rows() > 0;
    }


    public function updateLocationMap($goods_receipt_item_id, $location_id)
    {
        $this->db->where('goods_receipt_item_id', $goods_receipt_item_id);
        $this->db->update('qrcode_location_map', ['location_id' => $location_id]);
    }
}