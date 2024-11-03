<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Materials extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();

        $this->load->library('pagination');
        $this->load->model('Material_model');
    }

    public function index()
    {
        // Pagination configuration
        $config['base_url'] = base_url('materials/index');
        $config['total_rows'] = $this->Material_model->getMaterialCount();
        $config['per_page'] = 8; // Number of items per page
        $config['uri_segment'] = 3; // The segment in the URI for page number

        // Pagination styling (Bootstrap 4)
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

        $data['materials'] = $this->Material_model->getMaterials($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $this->global['pageTitle'] = 'Danh sách nguyên vật liệu';
        $this->loadViews("material/listMaterial", $this->global, $data);
    }


    // public function addMaterial()
    // {
    //     $this->loadViews("material/addMaterial", $this->global, NULL, NULL);
    // }

    public function store()
    {
        $materialCode = $this->input->post('materialCode');
        $materialName = $this->input->post('materialName');
        $unit = $this->input->post('unit');
        $quantity =  $this->input->post('quantity');
        // $stockQuantity = $this->input->post('stockQuantity');
        // $supplier = $this->input->post('supplier');

        $data = array(
            'materialCode' => $materialCode,
            'materialName' => $materialName,
            'unit' => $unit,
            'quantity' => $quantity
        );

        $this->Material_model->insert($data);
        redirect('material/listMaterial');
    }

    public function update()
    {
        $id = $this->input->post('id');
        $materialCode = $this->input->post('materialCode');
        $materialName = $this->input->post('materialName');
        $unit = $this->input->post('unit');
        $quantity =  $this->input->post('quantity');

        $data = array(
            'materialCode' => $materialCode,
            'materialName' => $materialName,
            'unit' => $unit,
            'quantity' => $quantity
        );

        $this->Material_model->update($id, $data);
        redirect('material/listMaterial');
    }

    public function delete($id)
    {
        $this->Material_model->delete($id);
        redirect('material/listMaterial');
    }
}