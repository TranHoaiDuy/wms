<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Issue extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Issue_model');
        $this->load->model('Receipt_model');
        $this->load->model('Material_model');
        $this->load->model('Factory_model');
        $this->load->model('User_model');
        $this->load->model('Inventory_model');
    }

    public function index()
    {
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;
        $this->load->library('pagination');

        $count = $this->Issue_model->getCountGoodsIssue($searchText);
        $returns = $this->paginationCompress("goods-issue/", $count, 10);
        $data['goodsIssue'] = $this->Issue_model->getGoodsIssueGI($searchText, $returns["page"], $returns["segment"]);
        $this->global['pageTitle'] = 'Danh Sách Phiếu Xuất';
        $this->loadViews("goods_issue", $this->global, $data, NULL);
    }

    public function create_goods_issue()
    {
        $data['materials'] = $this->Material_model->getAllMaterials();
        $data['factories'] = $this->Factory_model->getAllFactories();
        $data['currentDate'] = date('m/d/Y');
        $this->global['pageTitle'] = 'Tạo Phiếu Nhận';
        $this->loadViews("create_goods_issue", $this->global, $data, NULL, NULL);
    }

    public function create_new_goods_issue()
    {

        $factoryId = $this->input->post('factory_id');
        $name = $this->input->post('createBy');
        $exportDtm = $this->input->post('exportDtm');
        $note = $this->input->post('note');

        $goodsIssueData = [
            'factoryId' => $factoryId,
            'createdBy' => $name,
            'exportDtm' => $exportDtm,
            'note' => $note,
            'status' => '0'
        ];
        $issueId = $this->Issue_model->insert($goodsIssueData);

        $currentMonth = date('m');
        $currentYear = date('Y');

        $GINumber = $this->Issue_model->getGINumber($currentYear, $currentMonth);

        if ($GINumber) {
            $number = $GINumber->number + 1;
            $updateData = [
                'number' => $number
            ];
            $this->Issue_model->updateGINumber($updateData, $GINumber->id);
        } else {
            $number = 1;
            $newData = [
                'year' => $currentYear,
                'month' => $currentMonth,
                'number' =>  $number
            ];
            $this->Issue_model->insertGINumber($newData);
        }

        $code = "RE{$currentYear}{$currentMonth}" . str_pad($number, 3, '0', STR_PAD_LEFT); // setup mã GR
        $updateData = [
            're_code' => $code,
        ];
        $this->Issue_model->update_issue($updateData, $issueId);


        redirect('receiving-request/detail/' . $issueId);
    }

    function detail($id = NULL)
    {
        $data['goodsIssueInfo'] = $this->Issue_model->get_issue_by_id($id);
        $data['factories'] = $this->Factory_model->getAllFactories();
        $data['materials'] = $this->Material_model->getAllMaterials();
        $data['goodsIssueItems'] = $this->Issue_model->get_goods_issue_detail_Id($id);
        $hintLocations = [];
        foreach ($data['goodsIssueItems'] as $item) {
            $hintLocations[$item->materialId] = $this->Issue_model->getHintLocation($item->materialId, $item->packing_quantity);
        }
        $data['hintLocations'] = $hintLocations;
        $this->global['pageTitle'] = 'Chi tiết phiếu xuất';
        $this->loadViews("goods_issue_details", $this->global, $data);
    }

    public function receiving_request()
    {
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;
        $this->load->library('pagination');

        $count = $this->Issue_model->getCountGoodsIssueRE($searchText);
        $returns = $this->paginationCompress("receiving-request/", $count, 10);
        $data['goodsIssue'] = $this->Issue_model->getGoodsIssueRE($searchText, $returns["page"], $returns["segment"]);
        $this->global['pageTitle'] = 'Danh Sách Phiếu Nhận';
        $this->loadViews("receiving_request", $this->global, $data, NULL);
    }


    function receiving_request_details($id = NULL)
    {
        $data['receivingRequestInfo'] = $this->Issue_model->get_issue_by_id($id);
        $data['factories'] = $this->Factory_model->getAllFactories();
        $data['materials'] = $this->Material_model->getAllMaterials();
        $data['goodsIssueItems'] = $this->Issue_model->get_goods_issue_detail_Id($id);
        $data['isCreateGI'] = true;
        $result = $this->Issue_model->get_goods_issue_detail_Id($id);
        if ($result) {
            foreach ($result as $item) {
                $stock_quantity = $this->Inventory_model->get_stock_quantity($item->materialId);
                if ($stock_quantity < $item->quantity) {
                    $data['isCreateGI'] = false;
                    break;
                }
            }
        }
        $this->global['pageTitle'] = 'Chi tiết phiếu xuất';
        $this->loadViews("receiving_request_details", $this->global, $data);
    }

    function add_material()
    {
        $goods_issue_id = $this->input->post('goods_issue_id');
        $material_id = $this->input->post('material_id');
        $quantity = $this->input->post('material_quantity');
        $packQuantity = $this->input->post('packQuantity');
        
        $data = [
            'goodsIssueId' => $goods_issue_id,
            'materialId' => $material_id,
            'quantity' => $quantity,
            'packing_quantity' =>$packQuantity
        ];

        $result = $this->Issue_model->insert_goods_issue_item($data);

        if ($result > 0) {
            echo (json_encode(array('status' => TRUE, 'id' => $result)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }

    function delete_material()
    {
        $id = $this->input->post('id');
        $result = $this->Issue_model->delete_goods_issue_item($id);
        if ($result > 0) {
            echo (json_encode(array('status' => TRUE)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }

    function edit_material()
    {
        $id = $this->input->post('id');
        $quantity = $this->input->post('material_quantity');
        $packQuantity = $this->input->post('packQuantity');
        $data = [
            'quantity' => $quantity,
            'packing_quantity' =>$packQuantity
        ];

        $result = $this->Issue_model->update_goods_issue_item($data, $id);

        if ($result > 0) {
            echo (json_encode(array('status' => TRUE)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }
    function processForm($id = NULL)
    {
        $action = $this->input->post('action');

        if ($action == 'save') {
            $factory_id = $this->input->post('factory_id');
            $exportDtm = $this->input->post('exportDtm');
            $note = $this->input->post('note');

            $data = [
                'note' => $note
            ];
            $this->Issue_model->update_issue($data, $id);
            redirect('receiving-request/detail/' . $id);
        } elseif ($action == 'send-request') {
            $data = [
                'status' => '1'
            ];
            $this->Issue_model->update_issue($data, $id);
            redirect('receiving-request/detail/' . $id);
        } elseif ($action == 'create-goods') {
            $id = $this->input->post('id');
            $result = $this->Issue_model->get_goods_issue_detail_Id($id);
            $isCreateGI = true;
            if ($result) {
                foreach ($result as $item) {
                    $stock_quantity = $this->Inventory_model->get_stock_quantity($item->materialId);
                    if ($stock_quantity < $item->quantity) {
                        $isCreateGI = false;
                        break;
                    }
                }
            }

            if ($isCreateGI == true) {
                $createdDtm = date('m/d/Y');
                $currentMonth = date('m');
                $currentYear = date('Y');

                $GINumber = $this->Issue_model->getGINumber($currentYear, $currentMonth);
                if ($GINumber) {
                    $number = $GINumber->number;
                    $code = "GI{$currentYear}{$currentMonth}" . str_pad($number, 3, '0', STR_PAD_LEFT);
                }
                $data = [
                    'code' => $code,
                    'createdDtm' => $createdDtm,
                    'status' => '2'
                ];
                $this->Issue_model->update_issue($data, $id);
                redirect('goods-issue/detail/' . $id);
            }
            
            redirect('goods-issue/detail/' . $id);
        } elseif ($action == 'export-goods') {
            $data = [
                'status' => '3'
            ];
            $this->Issue_model->update_issue($data, $id);
            redirect('goods-issue/detail/' . $id);
            
            // Logic Trừ Tồn.
            // Step 1: Query tất cả các Item Trong goods_receipt_item theo goodsIssueItemId tương ứng 
            // Step 2: Xóa vị tri trong bảng qrcode_location_map với goods_receipt_item_id ở step 1.
            // Step 3: Ghi tracking Log theo danh sách Item của xuất hàng  (goods_receipt_item)
            // Ngày xuất, NVL xuất, Số Lượng Xuất typeId = 2, entityId là id của goods_receipt_item
            // Step 4: Trừ Tồn Tương Ứng

        } elseif ($action == 'complete') {
            $data = [
                'status' => '4'
            ];

            $this->Issue_model->update_issue($data, $id);
            redirect('receiving-request/detail/' . $id);
        }
    }

    public function qrcode()
    {
        $id = $this->input->post('id');
        $qrcode = $this->input->post('qrcode');
        // Get Qrcode Get Thông Tin Material
        $result =  $this->Issue_model->getItemByQRcode($id, $qrcode);
        if (!empty($result)) {
            $model = $result[0];
           // echo (json_encode(array('status' => TRUE, 'model' =>  $model)));
           if($model -> goodsIssueItemId == "0"){

                $realQuantity = $model-> realQuantity + $model -> quantity ;

                if($realQuantity > $model-> giQuantity)
                {
                    echo (json_encode(array('status' => FALSE, 'message' => 'Nguyên vật liệu đã đủ số lượng cần xuất.')));
                }
                else{
                    $data = [
                        'goodsIssueItemId' => $model->giId
                    ];
                    
                    $this->Receipt_model->update_goods_receipt_item($data, $model -> grId);

                    $data = [
                        'realQuantity' => $realQuantity
                    ];
                    $this->Issue_model->update_goods_issue_item($data,$model -> giId);
                    echo (json_encode(array('status' => TRUE, 'model' => array('materialCode' => $model -> materialCode, 'realQuantity' => $realQuantity))));
                }
            }
            else {
                echo (json_encode(array('status' => FALSE, 'message' => 'QRCode này đã được scan trước đó. Vui lòng kiểm tra lại.')));
            }
        }   
        else {
            echo (json_encode(array('status' => FALSE, 'message' => 'QRCode này đã không hợp lệ. Vui lòng kiểm tra lại.')));
        }
    }

}