<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Stocktaking extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Stocktaking_model');
        $this->load->model('User_model');
        $this->load->model('Material_model');
    }
    public function index()
    {
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;
        $this->load->library('pagination');

        $count = $this->Stocktaking_model->getCountStocktakingInventory($searchText,0);
        $returns = $this->paginationCompress("stocktaking/", $count, 10);
        $data['inventoryReports'] = $this->Stocktaking_model->getStocktakingInventory($searchText,0, $returns["page"], $returns["segment"]);

        $this->global['pageTitle'] = 'Danh Yêu Cầu';
        $this->loadViews("stocktaking_inventory", $this->global, $data);
    }

    public function detail($id = null)
    {
       // $data['materials'] = $this->Material_model->getAllMaterials();
       $data['stocktaking'] = $this->Material_model->getAllMaterialsWithInventory();
       $data['usersbyrole'] = $this->User_model->getUserByRole(ROLE_EMPLOYEE);
       $data['inventoryInfo'] = $this->Stocktaking_model->get_inventory_report_by_id($id);
       $data['inventoryItems'] = $this->Stocktaking_model->get_inventory_report_detail_Id($id);
       $this->global['pageTitle'] = 'Danh Sách Phiếu Kiểm Kê';
       $this->loadViews("stocktaking_inventory_details", $this->global, $data);
    }

    public function stocktaking_inventory()
    {
        $userId = $this->session->userdata ( 'userId' );
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;
        $this->load->library('pagination');

        $count = $this->Stocktaking_model->getCountStocktakingInventory($searchText,$userId);
        $returns = $this->paginationCompress("my-stocktaking/", $count, 10);
        $data['inventoryReports'] = $this->Stocktaking_model->getStocktakingInventory($searchText, $userId, $returns["page"], $returns["segment"]);

        $this->global['pageTitle'] = 'Danh Sách Phiếu Kiểm Kê';
        $this->loadViews("my-stocktaking", $this->global, $data);
    }
    
    public function create_request_inventory($id = null)
    {
        // $data['materials'] = $this->Material_model->getAllMaterials();
        $data['stocktaking'] = $this->Material_model->getAllMaterialsWithInventory();
        $data['usersbyrole'] = $this->User_model->getUserByRole(ROLE_EMPLOYEE);
        $data['createdDtm'] = date('m/d/Y');
        $this->global['pageTitle'] = 'Tạo Phiếu Yêu Cầu';
        $this->loadViews("create_request_inventory", $this->global, $data, NULL);
    }
    public function create_new_request_inventory()
    {
        $users = $this->input->post('stocktakingById');
        $stocktakingdtm = $this->input->post('stocktakingdtm');
        $note = $this->input->post('note');
        $currentDate = date('m/d/Y');
        $name = $this->session->userdata ( 'name' );
        $stockdata = [
            'stocktakingById' => $users,
            'stocktakingdtm	' => $stocktakingdtm,
            'note' => $note,
            'status' => 0,
            'createdDtm' => $currentDate,
            'createdBy' => $name
        ];
        $stockReportId = $this->Stocktaking_model->insert($stockdata);


        redirect('stocktaking/detail/' . $stockReportId);
    }
    function add_material()
    {

        $stocktakingId = $this->input->post('stocktakingId');
        $materialId = $this->input->post('material_id');
        $quantityAvailable = $this->input->post('material_quantityAvailable');

        $data = [
            'stocktakingId' => $stocktakingId,
            'materialId' => $materialId,
            'quantity' => $quantityAvailable,

        ];

        $result = $this->Stocktaking_model->insert_inventory_item($data);

        if ($result > 0) {
            echo (json_encode(array('status' => TRUE, 'id' => $result)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }

    function delete_material()
    {
        $id = $this->input->post('id');
        $result = $this->Stocktaking_model->delete_inventory_item($id);
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
            $users = $this->input->post('stocktakingById');
            $stocktakingdtm = $this->input->post('stocktakingdtm');
            $note = $this->input->post('note');

            $data = [
                'stocktakingById' => $users,
                'stocktakingdtm	' => $stocktakingdtm,
                'note' => $note


            ];
            $this->Stocktaking_model->update_inventory_report($data, $id);
            redirect('request-inventory/detail/' . $id);
        } elseif ($action == 'send-request') {
            $data = [
                'status' => '1'
            ];
            $this->Stocktaking_model->update_inventory_report($data, $id);
            redirect('request-inventory/detail/' . $id);
        } elseif ($action == 'start-audit') {
            $data = [
                'status' => '2'
            ];
            $this->Stocktaking_model->update_inventory_report($data, $id);
            redirect('stocktaking-inventory/detail/' . $id);
        }
        // } elseif ($action == 'create-goods') {
        //     $id = $this->input->post('id');
        //     $createdDtm = date('m/d/Y');
        //     $result = $this->Issue_model->get_goods_issue_item($id);
        //     if ($result) {
        //         $material_id = $result['materialId'];
        //         $required_quantity = $result['quantity'];
        //     }
        //     $stock_quantity = $this->Inventory_model->get_stock_quantity($material_id);

        //     if ($stock_quantity >= $required_quantity) {
        //         $currentMonth = date('m');
        //         $currentYear = date('Y');

        //         $GINumber = $this->Issue_model->getGINumber($currentYear, $currentMonth);
        //         if ($GINumber) {
        //             $number = $GINumber->number;
        //             $code = "GI{$currentYear}{$currentMonth}" . str_pad($number, 3, '0', STR_PAD_LEFT);
        //         }
        //         $data = [
        //             'code' => $code,
        //             'createdDtm' => $createdDtm,
        //             'status' => '2'
        //         ];
        //         $this->Issue_model->update_issue($data, $id);
        //         redirect('goods-issue/detail/' . $id);
        //     }
        //     redirect('goods-issue/detail/' . $id);
        // } elseif ($action == 'export-goods') {
        //     $data = [
        //         'status' => '3'
        //     ];
        //     $this->Issue_model->update_issue($data, $id);
        //     redirect('goods-issue/detail/' . $id);
        //     // Trừ Tồn Kho
        //     // Tracking Log
        // } elseif ($action == 'complete') {
        //     $data = [
        //         'status' => '4  '
        //     ];

        //     $this->Issue_model->update_issue($data, $id);
        //     redirect('goods-issue/detail/' . $id);
        // }
    }
}