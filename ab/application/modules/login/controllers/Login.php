<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

   function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{ 
		if($this->session->userdata('admin_id')!='')
		{
			redirect('admin');	
		}
		ini_set('display_errors', 1);
		$config['title'] = 'Login';
		$config['errors'] ='';
		$data['msg'] = $this->session->flashdata('msg');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('email', 'Login Name', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
		   
		$username = $this->input->post('email',TRUE);
		$password = $this->input->post('password',TRUE);
		
		  // if form validation true
		if($this->form_validation->run() == TRUE) 
		{
			$wheres = array('email'=>$username,'password'=>$password);
			$users = $this->common_model->getsingle('admin',$wheres);
		   
			if($users)
			{
				if($users->status==1)
				{
					$newdata = array( 	
						'admin_id' 	=> $users->admin_id,
						'email' 	=> $users->email,
						'type' 		=> $users->type,
						'login' 	=> TRUE,
					);	
					
					$this->session->set_userdata($newdata);
					 //echo "<pre>"; print_r($newdata); die;
					$this->session->set_flashdata('msg','Your Login Successfully');
					redirect('admin');
				}
				else
				{
					$config['errors'] =  'Your account is deactivated. Please contact to superadmin..';
				}					
			}
			else
			{
				$config['errors'] =  'Wrong Email or Password. Please Try again.';
			}
		
		}
	
		$config['main_content'] = 'login';
		$this->load->view('login',$config);
	}
	
	public function privacy_policy()
	{
		$data['msg'] = $this->session->flashdata('msg');	
		$data['title'] = "BM Mart";
	
		$data['main_content'] = 'privacy_policy';
		$this->load->view('login/privacy_policy',$data);
	}
	
// logout 
	public function logout() 
	{
		$array_items = array('admin_id' => '','email' => '','type' => '', 'login' => '');		
		$this->session->unset_userdata($array_items);
		$this->session->sess_destroy(); 
		$url = base_url('login');
		header("location:$url");
   }

  

	
}
