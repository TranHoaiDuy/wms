<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Inventory extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Inventory_model');
    }
    public function index()
    {

        $searchText = $this->input->get('searchText');
        if (!empty($searchText)) {
            $data['getInventory'] = $this->Inventory_model->searchInventory($searchText);
        } else {
            $data['getInventory'] = $this->Inventory_model->getInventory();
        }

        $this->global['pageTitle'] = 'Danh Sách Nguyên Vật Liệu Tồn Kho';
        $this->loadViews("residual", $this->global, $data);
    }
    public function detail($id)
    {
        $data['inventoryDetails'] = $this->Inventory_model->getInventoryDetails($id);

        $this->global['pageTitle'] = 'Chi tiết tồn kho';
        $this->loadViews("inventory_details", $this->global, $data);
    }
    public function stock_report()
    {
        $data['inventoryStockReport'] = $this->Inventory_model->getStockReport();
        $this->global['pageTitle'] = 'Báo Cáo';
        $this->loadViews("stockReport", $this->global, $data);
    }
}