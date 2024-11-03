<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
require APPPATH . '/libraries/qrcode_utils.php';
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Writer\PngWriter;
use Mpdf\Mpdf;

class WmsLayout extends BaseController
{
    public function generateQrCode($data)
    {
        $mpdf = new Mpdf();
        $mpdf->WriteHTML('<h1 style="text-align:center">QR Vị Trí</h1>');

        // Kiểm tra xem có dữ liệu không
        if (!empty($data)) {
            // Chỉ tạo mã QR cho phần tử đầu tiên
            $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->writerOptions([])
                ->data($data[0])
                ->encoding(new Encoding('UTF-8'))
                ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
                ->build();

            // Lưu mã QR vào file tạm thời
            $qrImagePath = "qrcode.png";
            $qrCode->saveToFile($qrImagePath);


            $mpdf->WriteHTML('<div style="text-align: center; padding: 10px; ">');
            $mpdf->WriteHTML('<h2 style="text-align: center;">Mã: ' . $data[0] . '</h2>');
            $qrCodeDataUri = $qrCode->getDataUri();

            $mpdf->WriteHTML('<table style="width: 100%; "><tr><td style="text-align: center;">');
            $mpdf->WriteHTML('<img src="' . $qrCodeDataUri . '" />');
            $mpdf->WriteHTML('</td></tr></table>');

            $mpdf->WriteHTML('</div>');

            // Xóa file tạm sau khi đã thêm vào PDF
            unlink($qrImagePath);
        } else {
            $mpdf->WriteHTML('<p style="text-align:center">Không có dữ liệu để tạo mã QR.</p>');
        }

        // Lưu file PDF vào bộ nhớ tạm và gửi cho người dùng tải xuống
        $mpdf->Output('qrcode_location.pdf', \Mpdf\Output\Destination::DOWNLOAD); // Đổi tên file tại đây
    }

    public function generate()
    {
        $arr = [];
        $qrCode = $this->input->post('qrcode_location');
        $arr[] = $qrCode;
        $this->generateQrCode($arr);
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Location_model');
        $this->load->library('pagination');
        $this->isLoggedIn();
        $this->global['pageTitle'] = 'DH-WMS : Dashboard';
        $this->load->model('Material_model');
    }

    public function index()
    {
        $this->loadViews("warehouse_diagram", $this->global, NULL, NULL);
    }

    public function view()
    {

        $config['base_url'] = base_url('wmsLayout/view');
        $config['total_rows'] = $this->Location_model->getLocationsCount();
        $config['per_page'] = 8;
        $config['uri_segment'] = 3;
        $config['full_tag_open'] = '<nav aria-label="Page navigation"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></nav>';
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['next_link'] = 'Next';
        $config['prev_link'] = 'Previous';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><span class="page-link">';
        $config['cur_tag_close'] = '</span></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['materials'] = $this->Material_model->getAllMaterials('object');
        $data['locations'] = $this->Location_model->getLocations($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $this->loadViews("warehouse_detail", $this->global, $data);
    }
    public function store()
    {
        $current_timestamp = time();
        $randomNumber =  str_pad(mt_rand(0, 9999), '0', STR_PAD_LEFT);
        $qrcode = "3{$current_timestamp}{$randomNumber}";
        $cargo_area = $this->input->post('cargo_area');
        $cargo_location = $this->input->post('cargo_location');
        $materialId = $this->input->post('materialId');
        $cargo_status = $this->input->post('cargo_status');

        $data = array(
            'cargo_area' => $cargo_area,
            'cargo_location' => $cargo_location,
            'cargo_status' => $cargo_status,
            'materialId' => $materialId,
            'qrcode_location' => $qrcode


        );
        $this->Location_model->insert($data);
        redirect('/wmsLayout/view');
    }

    public function update()
    {

        $id = $this->input->post('id');
        $cargoArea = $this->input->post('cargo_area');
        $cargoLocation = $this->input->post('cargo_location');
        $materialId = $this->input->post('materialId');
        $cargoStatus = $this->input->post('cargo_status');


        $data = array(
            'cargo_area' => $cargoArea,
            'cargo_location' => $cargoLocation,
            'materialId' => $materialId,
            'cargo_status' => $cargoStatus

        );

        $this->Location_model->update($id, $data);
        redirect('/wmsLayout/view');
    }

    public function delete($id)
    {
        $this->Location_model->delete($id);
        redirect('/wmsLayout/view');
    }



    // public function Shelves()
    // {
    //     $this->loadViews("warehouse_shelves_detail", $this->global, NULL, NULL);
    // }
}