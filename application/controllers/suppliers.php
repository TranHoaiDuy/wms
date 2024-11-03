<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Suppliers extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();
       
        $this->load->library('pagination');
        $this->load->model('Supplier_model');
    }

    public function index()
    {
        // Pagination configuration
        $config['base_url'] = base_url('suppliers/index');
        $config['total_rows'] = $this->Supplier_model->getSupplierCount();
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

        $data['suppliers'] = $this->Supplier_model->getSuppliers($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();
        $this->global['pageTitle'] = 'Danh sách nhà cung cấp';
        $this->loadViews("supplier/listSupplier", $this->global, $data);
    }
    public function store()
    {
        $supplierName = $this->input->post('supplierName');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        $data = array(
            'supplierName' => $supplierName,
            'address' => $address,
            'phone' => $phone,
            'email' => $email
        );

        $this->Supplier_model->insert($data);
        redirect('suppliers/index'); 
    }

    public function update()
    {
        $id = $this->input->post('id');
        $supplierName = $this->input->post('supplierName');
        $address = $this->input->post('address');
        $phone = $this->input->post('phone');
        $email = $this->input->post('email');

        $data = array(
            'supplierName' => $supplierName,
            'address' => $address,
            'phone' => $phone,
            'email' => $email
        );
        $this->Supplier_model->update($id, $data);
        redirect('suppliers/index'); 
    }

    public function delete($id)
    {
        $this->Supplier_model->delete($id);
        redirect('suppliers/index');
    }
}
?>
