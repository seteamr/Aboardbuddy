<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

   function __construct()
	{
		parent::__construct();
		$this->load->model('admin/common_model');
	}
	
	public function index()
	{ 
		if($this->session->userdata('user_master_id')!='')
		{
			if($this->session->userdata('user_type')=='3')
			{
				redirect('admin');
				//redirect('reports/play_history');
			}
			else if($this->session->userdata('user_type')=='2')
			{
				redirect('distributor');
				//redirect('reports/play_history');
			}
			else if($this->session->userdata('user_type')=='1')
			{
				redirect('retailer');
				//redirect('reports/play_history');
			}
			else if($this->session->userdata('user_type')=='0')
			{
				redirect('player');
				//redirect('reports/play_history');
			}else{					
				redirect('login');
			}	
		}
		$config['title'] = 'Login';
		$config['errors'] ='';
		$data['msg'] = $this->session->flashdata('msg');
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('username', 'Client Code', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
		   
		$username = $this->input->post('username',TRUE);
		$password = ($this->input->post('password',TRUE));
		
		  // if form validation true
		if($this->form_validation->run() == TRUE) 
		{
			$wheres = array('user_name'=>$username,'user_password'=>$password);
			$users = $this->common_model->getsingle('user_master',$wheres);
		      
			if($users)
			{
				if($users->is_user_deleted=='0')
				{
					$newdata = array( 	
					'user_master_id' 	=> $users->user_master_id,
					'user_name' 		=> $users->user_name,
					'user_type' 		=> $users->user_type,
					'lgin' 				=> TRUE,
					);	
					$this->session->set_userdata($newdata);
					
					if (isset($_SERVER['HTTP_CLIENT_IP']))
					{
						$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
					}
					else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
					{
						$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
					}
					else if(isset($_SERVER['HTTP_X_FORWARDED']))
					{
						$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
					}
					else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
					{
						$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
					}
					else if(isset($_SERVER['HTTP_FORWARDED']))
					{
						$ipaddress = $_SERVER['HTTP_FORWARDED'];
					}
					else if(isset($_SERVER['REMOTE_ADDR']))
					{
						$ipaddress = $_SERVER['REMOTE_ADDR'];
					}
					else
					{
						$ipaddress = 'UNKNOWN';
					}
					
					$logiinsdata = array(
						'user_master_id' 	=> $users->user_master_id,
						'date'				=> date('Y-m-d'),
						'type'				=> 'Login',
						'date_time'			=> date('Y-m-d H:i:s'),
						'ip'				=> $ipaddress
					);
					$this->common_model->insertData('login_history',$logiinsdata);
					
					$this->session->set_flashdata('msg','Your Login Successfully');
					if($users->user_type=='3')
					{
						redirect('admin');
						//redirect('reports/play_history');
					}
					else if($users->user_type=='2')
					{
						redirect('distributor');
						//redirect('reports/play_history');
					}
					else if($users->user_type=='1')
					{
						redirect('retailer');
						//redirect('reports/play_history');
					}
					else if($users->user_type=='0')
					{
						redirect('player');
						//redirect('reports/play_history');
					}else{					
						redirect('login');
					}
				}					
				else
				{
					$config['errors'] =  'Your Account Deactivated, Please Contact Us Administrator';
				}			
			}
			else
			{
				$config['errors'] =  'Wrong Client Code or Password. Please Try again.';
			}
		
		}
	
		$config['main_content'] = 'login';
		$this->load->view('login',$config);
	
	}
	
	
	// logout 
	public function logout() 
	{
		if (isset($_SERVER['HTTP_CLIENT_IP']))
		{
			$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
		{
			$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}
		else if(isset($_SERVER['HTTP_X_FORWARDED']))
		{
			$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
		}
		else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
		{
			$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
		}
		else if(isset($_SERVER['HTTP_FORWARDED']))
		{
			$ipaddress = $_SERVER['HTTP_FORWARDED'];
		}
		else if(isset($_SERVER['REMOTE_ADDR']))
		{
			$ipaddress = $_SERVER['REMOTE_ADDR'];
		}
		else
		{
			$ipaddress = 'UNKNOWN';
		}
		
		$logiinsdata = array(
			'user_master_id' 	=> $this->session->userdata('user_master_id'),
			'date'				=> date('Y-m-d'),
			'type'				=> 'Logout',
			'date_time'			=> date('Y-m-d H:i:s'),
			'ip'				=> $ipaddress
		);
		$this->common_model->insertData('login_history',$logiinsdata);
		
		$array_items = array('user_master_id' => '','user_name' => '', 'user_type'  => '','lgin' => '');		
		$this->session->unset_userdata($array_items);
		$this->session->sess_destroy(); 
		$url = base_url();
		header("location:$url");
   }

   public function profile(){
   		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		
		$data['user']=$this->common_model->getsingle('user_master',array('user_master_id'=>$this->session->userdata('user_master_id')));
		// print_r($data['user']);die;
		$data['msg'] = $this->session->flashdata('msg');
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('name', 'Name', 'trim|required');

			$name = $this->input->post('name');

			$data['errors']='';
			if($this->form_validation->run() == TRUE) 
			{
				$data['upload_path'] = 'uploads/profile_image/';
				$data['allowed_types'] = 'gif|jpg|png|JPG|jpeg|JPEG';
				$data['max_size'] = '20480000';
				$data['max_width'] = '10240000';
				$data['max_height'] = '7680000';
				$data['encrypt_name'] = true;
		       
				$this->load->library('upload', $data);
				$profile_image = $data['user']->profile_image;
				
				if ($this->upload->do_upload('profile_image'))
				{
					$attachment_data = array('upload_data' => $this->upload->data());
					$profile_image = $attachment_data['upload_data']['file_name'];
				}
				else
				{
					if($_FILES['profile_image']['name']!="")
					{
						//$data['errors'] = $this->upload->display_errors();
						$data['errors'] = "Allowed upload type jpg, png and jpeg images only.";
					}	
							
				}

				if( $data['errors']==''){
					$updateArray=array(
						'name'=>$name,
						'profile_image'=>$profile_image,
						'updated_user_master_id'=>$this->session->userdata('user_master_id'),
						'updated_date'=>date('Y-m-d h:i:s'),
					);
					//print_r($updateArray);die;
					$this->common_model->updateData('user_master',$updateArray,array('user_master_id'=>$this->session->userdata('user_master_id')));
				}
				$this->session->set_flashdata("msg","<font class='success'>Profile Update Successfully.</font>");
				redirect('login/profile');
			}
   		
   		$data['main_content'] = 'profile';
		$this->load->view('includes/template',$data);
   }

   public function change_password(){
   	if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
   	// $data['user']=$this->common_model->getsingle('user_master',array('user_master_id'=>$this->session->userdata('user_master_id')));
	$data['msg'] = $this->session->flashdata('msg');
   	$this->form_validation->set_error_delimiters('', '');
	$this->form_validation->set_rules('current_password', 'Current Password', 'trim|required|callback_password_chk');
	$this->form_validation->set_rules('new_password', 'New Password', 'trim|required');
	$this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|matches[new_password]');

	
	$new_password = $this->input->post('new_password');

	if($this->form_validation->run() == TRUE) 
	{
		
		$updateArray=array(
			'user_password'=>$new_password,
		);
		$this->common_model->updateData('user_master',$updateArray,array('user_master_id'=>$this->session->userdata('user_master_id')));
		$this->session->set_flashdata("msg","<font class='success'>Password Changed Successfully.</font>");
		redirect('login/change_password');
		
	}

   	$data['main_content'] = 'change_password';
	$this->load->view('includes/template',$data);
   }

   function password_chk($password) {
		
		$users = $this->common_model->getsingle('user_master',array('user_password'=>$password,'user_master_id'=>$this->session->userdata('user_master_id')));
		
		
		if($users==''){
			 $this->form_validation->set_message('password_chk', 'Incorrect Password.');
			 return FALSE;
		}
		else {
			return TRUE;
		}
	}
	
}
