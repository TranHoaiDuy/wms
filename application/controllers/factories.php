<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Factories extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->isLoggedIn();

        $this->load->library('pagination');
        $this->load->model('Factory_model');
    }

    public function index()
    {
        $searchText = $this->input->post('searchText');
        $data['searchText'] = $searchText;

        $count = $this->Factory_model->getCountFactory($searchText);
        $returns = $this->paginationCompress("factory/listFactory/", $count, 10);
        $data['factories'] = $this->Factory_model->getFactories($searchText, $returns["page"], $returns["segment"]);
        $this->global['pageTitle'] = 'Danh sách phân xưởng';
        $this->loadViews("factory/listFactory", $this->global, $data);
    }
    public function store()
    {
        $name = $this->input->post('name');
        $description = $this->input->post('description');

        $data = array(
            'name' => $name,
            'description' => $description
        );

        $this->Factory_model->insert($data);
        redirect('factories/index');
    }

    public function update()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $description = $this->input->post('description');
        $data = array(
            'name' => $name,
            'description' => $description
        );
        $this->Factory_model->update($id, $data);
        redirect('factories/index');
    }

    public function delete($id)
    {
        $this->Factory_model->delete($id);
        redirect('factories/index');
    }
}