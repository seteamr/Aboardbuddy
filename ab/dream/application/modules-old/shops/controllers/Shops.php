<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shops extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta');
	}
	
	public function shop_status($id,$status)
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')=='0')
		{
			redirect('login/logout');
		}	
		$deleted_user_master_id = $this->session->userdata('user_master_id');
		
		$update_data = array(
			'is_shop_deleted'			=> $status,
			'deleted_user_master_id'	=> $deleted_user_master_id,
			'deleted_date'				=> date('Y-m-d H:i:s')
			);
		$this->common_model->updateData('shop_master',$update_data,array('shop_master_id'=>$id));
		$this->session->set_flashdata("msg","<font class='success'>Action performed successfully.</font>");
		redirect('shops');
		            
	}
	
	public function index()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')=='0')
		{
			redirect('login/logout');
		}
		
		$data['shops'] = $this->common_model->getAllwhere('shop_master',array());
		
		$data['msg'] = $this->session->flashdata('msg');
		
		$data['main_content'] = 'index';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function alpha_dash_space($fullname)
	{
		if (! preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & Whith space');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function add()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')=='0')
		{
			redirect('login/logout');
		}
		
		$data['msg'] = $this->session->flashdata('msg');
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('shop_name', 'Shop Name', 'trim|required|unique[shop_master.shop_name]|callback_alpha_dash_space');
		$this->form_validation->set_rules('shop_contact_person', 'Shop Contact Person', 'trim|required');
		$this->form_validation->set_rules('shop_contact_number', 'Shop Contact Number', 'trim|numeric|required');
		$this->form_validation->set_rules('shop_address', 'Shop Address', 'trim|required');
		$this->form_validation->set_rules('is_shop_deleted', 'Active Status', 'trim|required');
		
		$shop_name 				= $this->input->post('shop_name',TRUE);
		$shop_contact_person 	= $this->input->post('shop_contact_person',TRUE);
		$shop_contact_number 	= $this->input->post('shop_contact_number',TRUE);
		$shop_address 			= $this->input->post('shop_address',TRUE);
		$is_shop_deleted 		= $this->input->post('is_shop_deleted',TRUE);
		$created_user_master_id = $this->session->userdata('user_master_id');
		
		if($this->form_validation->run() == TRUE) 
		{
			$insertArray = array(
				'shop_name' 			=> $shop_name,
				'shop_contact_person' 	=> $shop_contact_person,
				'shop_contact_number' 	=> $shop_contact_number,
				'shop_address' 			=> $shop_address,
				'is_shop_deleted' 		=> $is_shop_deleted,
				'created_user_master_id' => $created_user_master_id,
				'created_date' 			=> date('Y-m-d H:i:s')
		   );
		   
			$insert_id = $this->common_model->insertData('shop_master',$insertArray);
			$this->session->set_flashdata("msg","<font class='success'>Shop Added Successfully.</font>");
			redirect('shops');
		}
		
		$data['main_content'] = 'add';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function chk_exist($shop_name,$shop_master_id)
	{
		$data = $this->common_model->getsingle('shop_master',array('shop_master_id !='=>$shop_master_id,'shop_name'=>$shop_name));
		if ($data) {
			$this->form_validation->set_message('chk_exist', 'The %s already exist please enter another name.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function edit($shop_master_id)
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login/logout');
		}
		$data['shop'] 	= $this->common_model->getsingle('shop_master',array('shop_master_id'=>$shop_master_id));
		$data['msg'] = $this->session->flashdata('msg');
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('shop_name', 'Shop Name', 'trim|required|callback_chk_exist['.$shop_master_id.']|callback_alpha_dash_space');
		$this->form_validation->set_rules('shop_contact_person', 'Shop Contact Person', 'trim|required');
		$this->form_validation->set_rules('shop_contact_number', 'Shop Contact Number', 'trim|numeric|required');
		$this->form_validation->set_rules('shop_address', 'Shop Address', 'trim|required');
		$this->form_validation->set_rules('is_shop_deleted', 'Active Status', 'trim|required');
		
		$shop_name 				= $this->input->post('shop_name',TRUE);
		$shop_contact_person 	= $this->input->post('shop_contact_person',TRUE);
		$shop_contact_number 	= $this->input->post('shop_contact_number',TRUE);
		$shop_address 			= $this->input->post('shop_address',TRUE);
		$is_shop_deleted 		= $this->input->post('is_shop_deleted',TRUE);
		$updated_user_master_id = $this->session->userdata('user_master_id');
		
		if($this->form_validation->run() == TRUE) 
		{
			$update_data = array(
				'shop_name' 			=> $shop_name,
				'shop_contact_person' 	=> $shop_contact_person,
				'shop_contact_number' 	=> $shop_contact_number,
				'shop_address' 			=> $shop_address,
				'is_shop_deleted' 		=> $is_shop_deleted,
				'updated_user_master_id' => $updated_user_master_id,
				'updated_date' 			=> date('Y-m-d H:i:s')
		   );
		   
			$this->common_model->updateData('shop_master',$update_data,array('shop_master_id'=>$shop_master_id));
			$this->session->set_flashdata("msg","<font class='success'>Shop Updated Successfully.</font>");
			redirect('shops');
		}
		
		$data['main_content'] = 'edit';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function view($shop_master_id)
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')=='0')
		{
			redirect('login/logout');
		}
		
		$data['users'] 	= $this->common_model->getAllwhere('user_master',array('user_type !='=>'0'));
		$data['shop'] 	= $this->common_model->getsingle('shop_master',array('shop_master_id'=>$shop_master_id));
		
		$data['main_content'] = 'view';
		$this->load->view('includes/template',$data);
		            
	}
}	

?>	