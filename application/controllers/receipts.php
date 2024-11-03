<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/qrcode_utils.php';
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Mpdf\Mpdf;

class Receipts extends BaseController
{
    public function generateQrCode($data)
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<h1 style="text-align:center">Danh sách mã QR</h1>');

        $mpdf->WriteHTML('<table style="width: 100%; border-collapse: collapse;">');
        $colCount = 0;

        foreach ($data as $index => $item) {
            if ($colCount % 3 == 0) {
                $mpdf->WriteHTML('<tr>');
            }

            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($item['qrcode_item'])
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->build();

            $qrCodeDataUri = $qrCode->getDataUri();

            $mpdf->WriteHTML('
                <td style="text-align: center; vertical-align: top; padding: 10px; border: 1px solid black;">
                    <h3 style="text-align: center;">' . htmlspecialchars($item['code']) . ' - ' . htmlspecialchars($item['cargo_area'] . $item['cargo_location']) . '</h3>
                    <img src="' . $qrCodeDataUri . '" />
                    <h3 style="text-align: center;">' . htmlspecialchars($item['qrcode_item']) . '</h3>
                    <h3 style="text-align: center;">' . htmlspecialchars($item['receipt_datetime']) . '</h3>
                    
                </td>
            ');
            $colCount++;

            if ($colCount % 3 == 0) {
                $mpdf->WriteHTML('</tr>');
            }
        }
        if ($colCount % 3 != 0) {
            $mpdf->WriteHTML('</tr>');
        }

        $mpdf->WriteHTML('</table>');
        $mpdf->Output('qr_codes.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }


    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
        $this->load->model('Receipt_model');
        $this->load->model('Material_model');
        $this->load->model('Supplier_model');
        $this->load->model('Location_model');
        $this->load->library('form_validation');
        $this->load->library('QrCodeUtils');
    }

    public function index()
    {
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;

        $this->load->library('pagination');

        $count = $this->Receipt_model->getCountGoodsReceipts($searchText);
        $returns = $this->paginationCompress("goods-reciepts/", $count, 10);
        $data['goodsReceipts'] = $this->Receipt_model->getGoodsReceipts($searchText, $returns["page"], $returns["segment"]);
        $this->global['pageTitle'] = 'Danh Sách Phiếu Nhập Kho';
        $this->loadViews("goods_receipts", $this->global, $data, NULL);
    }

    public function mom_report()
    {
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;
        $this->load->library('pagination');

        $count = $this->Receipt_model->getCountMoMReports($searchText);
        $returns = $this->paginationCompress("mom-reports/", $count, 10);
        $data['goodsReceipts'] = $this->Receipt_model->getMoMReports($searchText, $returns["page"], $returns["segment"]);
        $this->global['pageTitle'] = 'Danh Sách Biên Bản';
        $this->loadViews("mom_reports", $this->global, $data, NULL);
    }

    public function create_goods_receipt()
    {
        $data['suppliers'] = $this->Supplier_model->getAllSuppliers();
        $this->global['pageTitle'] = 'Tạo Phiếu Nhập Kho';
        $this->loadViews("create_goods_receipt", $this->global, $data, NULL, NULL);
    }

    public function create_new_goods_receipt()
    {
        $this->load->library('form_validation');

        $this->form_validation->set_rules('invoice_code', 'Mã chứng từ', 'trim|required|max_length[32]|xss_clean');

        $data['suppliers'] = $this->Supplier_model->getAllSuppliers();
        $invoiceCode = $this->input->post('invoice_code');
        $invoiceDate = $this->input->post('invoice_date');
        $supplierId = $this->input->post('supplier_id');
        $deliveryDate = $this->input->post('delivery_date');
        $note = $this->input->post('note');


        $goodsReceiptData = [
            'invoice_code' => $invoiceCode,
            'invoice_date' => $invoiceDate,
            'supplier_id' => $supplierId,
            'delivery_date' => $deliveryDate,
            'note' => $note,
            'status' => '0'
        ];
        $receiptId = $this->Receipt_model->insert($goodsReceiptData);

        $currentMonth = date('m');
        $currentYear = date('Y');

        $GINumber = $this->Receipt_model->getGINumber($currentYear, $currentMonth);

        if ($GINumber) {
            $number = $GINumber->number + 1;
            $updateData = [
                'number' => $number
            ];
            $this->Receipt_model->updateGINumber($updateData, $GINumber->id);
        } else {
            $number = 1;
            $newData = [
                'year' => $currentYear,
                'month' => $currentMonth,
                'number' =>  $number
            ];
            $this->Receipt_model->insertGINumber($newData);
        }

        $code = "GR{$currentYear}{$currentMonth}" . str_pad($number, 3, '0', STR_PAD_LEFT); // setup mã GR
        $mom_code = "MOM{$currentYear}{$currentMonth}" . str_pad($number, 3, '0', STR_PAD_LEFT); // setup mã MOM
        $updateData = [
            'code' => $code,
            'mom_code' => $mom_code
        ];
        $this->Receipt_model->update_receipt($updateData, $receiptId);

        //$data = array_merge($data, $goodsReceiptData);

        //$nextId  = $this->Receipt_model->getNextId();
        redirect('goods-receipts/detail/' . $receiptId);
    }

    function detail($id = NULL)
    {
        //$this->session->set_userdata('nextId', $Id);
        $data['suppliers'] = $this->Supplier_model->getAllSuppliers();
        $data['materials'] = $this->Material_model->getAllMaterials();
        $data['goodsRecieptInfo'] = $this->Receipt_model->get_receipt_by_id($id);
        $data['goodsReceiptItems'] = $this->Receipt_model->get_goods_receipt_detail_Id($id, true);
        $data['exists'] = $this->Receipt_model->checkIfMapped($id);
        $locationsByStatus = [];
        foreach ($data['goodsReceiptItems'] as $item) {
            $locationsByStatus[$item->material_id] = $this->Location_model->getLocationsByStatus($item->material_id);
        }
        $data['locationsByStatus'] = $locationsByStatus;
        $this->global['pageTitle'] = 'Chi tiết phiếu nhập';
        $this->loadViews("goods_receipt_details", $this->global, $data);
    }


    function mom_detail($id = NULL)
    {
        //$this->session->set_userdata('nextId', $Id);
        $data['suppliers'] = $this->Supplier_model->getAllSuppliers();
        $data['materials'] = $this->Material_model->getAllMaterials();
        $data['goodsRecieptInfo'] = $this->Receipt_model->get_receipt_by_id($id);
        $data['goodsReceiptItems'] = $this->Receipt_model->get_goods_receipt_detail_Id($id);
        $this->global['pageTitle'] = 'Chi tiết biên bản';
        $this->loadViews("mom_report_detail", $this->global, $data);
    }

    function add_material()
    {
        $current_timestamp = time();
        $randomNumber =  str_pad(mt_rand(0, 9999), '0', STR_PAD_LEFT);
        $qrcode = "1{$current_timestamp}{$randomNumber}";
        $goods_receipt_id = $this->input->post('goods_receipt_id');
        $material_id = $this->input->post('material_id');
        $quantity = $this->input->post('material_quantity');
        $code = $this->input->post('code');
        
        $data = [
            'goods_receipt_id' => $goods_receipt_id,
            'material_id' => $material_id,
            'quantity' => $quantity,
            'code' => $code,
            'qrcode_item' => $qrcode
        ];

        $result = $this->Receipt_model->insert_goods_receipt_item($data);

        if ($result > 0) {
            echo (json_encode(array('status' => TRUE, 'id' => $result)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }

    function delete_material()
    {
        $id = $this->input->post('id');
        $result = $this->Receipt_model->delete_goods_receipt_item($id);
        if ($result > 0) {
            echo (json_encode(array('status' => TRUE)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }

    function edit_material()
    {
        $id = $this->input->post('id');
        //$quantity = $this->input->post('material_quantity');
        $code = $this->input->post('code');
        var_dump($id);

        $data = [
            'code' => $code
        ];

        $result = $this->Receipt_model->update_goods_receipt_item($data, $id);
        var_dump($result);
        if ($result > 0) {
            echo (json_encode(array('status' => TRUE)));
        } else {
            echo (json_encode(array('status' => FALSE)));
        }
    }

    function rating_material()
    {
        $id = $this->input->post('id');
        $rating = $this->input->post('rating');
        $note = $this->input->post('note');

        $data = [
            'rating' => $rating,
            'note' => $note
        ];

        $result = $this->Receipt_model->update_goods_receipt_item($data, $id);

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

            $invoice_code = $this->input->post('invoice_code');
            $invoice_date = $this->input->post('invoice_date');
            $supplier_id = $this->input->post('supplier_id');
            $delivery_date = $this->input->post('delivery_date');
            $note = $this->input->post('note');

            $data = [
                'invoice_code' => $invoice_code,
                'invoice_date' => $invoice_date,
                'supplier_id' => $supplier_id,
                'delivery_date' => $delivery_date,
                'note' => $note
            ];
            $this->Receipt_model->update_receipt($data, $id);
        } elseif ($action == 'rating-process') {
            // Đặt múi giờ về giờ địa phương (ví dụ: Asia/Ho_Chi_Minh)
            date_default_timezone_set('Asia/Ho_Chi_Minh');

            // Tạo đối tượng DateTime cho thời gian hiện tại
            $currentDateTime = new DateTime();

            // Chuyển đổi thành chuỗi theo định dạng mong muốn
            $formattedDateTime = $currentDateTime->format('Y-m-d H:i:s');

            $data = [
                'receipt_datetime' => $formattedDateTime,
                'status' => '1'
            ];
        } elseif ($action == 'rating-complete') {
            $data = [
                'status' => '2'
            ];
        } elseif ($action == 'rating-comfirm') {
            $data = [
                'status' => '3'
            ];
        } elseif ($action == 'storage-complete') {
            $data = [
                'status' => '4  '
            ];

            // Đã sử dụng trigger kiểm tra và cập nhật số lượng tồn khi status = 4
        }

        $this->Receipt_model->update_receipt($data, $id);

        if ($action == 'rating-process' || $action == 'rating-complete' || $action == 'rating-comfirm') {
            redirect('mom-reports/detail/' . $id);
        }

        redirect('goods-receipts/detail/' . $id);
    }

    public function generate()
    {
        $arr = [];
        if ($this->input->post('action') === 'export-QRCode') {
            $materials = $this->input->post('materials');
            foreach ($materials as $id => $material) {
                $id = $material['id'];
                $material_name = $material['materialName'];
                $qrcode_item = $material['qrcode_item'];
                $code = $material['code'];
                $locationId = $material['locationId'];
                $receipt_datetime = $material['receipt_datetime'];
                if(!empty($locationId)){
                    $cargo_area = $material['cargo_area'];
                    $cargo_location = $material['cargo_location'];
                }
                else{
                    $location_id = $material['location_id'];
                    $location_detail = $this->Location_model->getById($location_id);
                    $cargo_area = $location_detail ? $location_detail->cargo_area : '';
                    $cargo_location = $location_detail ? $location_detail->cargo_location : '';
                   
                    if (!empty($location_id)) {
                        // Cập nhật trạng thái
                        $this->Location_model->update( $location_id, ['cargo_status' => '2']);
                                                        
                        // Dữ liệu cần lưu vào cơ sở dữ liệu
                        $data = [
                            'goods_receipt_item_id' => $id,
                            'location_id' => $location_id,
                        ];

                        // Lưu vào cơ sở dữ liệu
                        $this->Receipt_model->insertLocationMap($data);
                        
                        /*
                        $exists = $this->Receipt_model->checkIfMapped($id);
                        if ($exists) {
                            // Cập nhật nếu đã tồn tại
                            //$this->Receipt_model->updateLocationMap($id, $location_id);
                        } else {
                        }*/
                            
                    }
                }
                
                // Thêm từng qrcode_item vào mảng $arr
                $arr[] = [
                    'qrcode_item' => $qrcode_item,
                    'material_name' => $material_name,
                    'code' => $code,
                    'receipt_datetime' => $receipt_datetime,
                    'cargo_area' => $cargo_area,
                    'cargo_location' => $cargo_location
                ];

            }

            
            $this->generateQrCode($arr);
        }
    }
}