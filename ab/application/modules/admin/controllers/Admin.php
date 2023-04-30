<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta'); 		
	} 
	 
	public function index($page = '')
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		
		$data['msg'] = $this->session->flashdata('msg');	
		
		$data['students'] = $this->common_model->record_count('students', array('role'=>'student'));
		$data['seller'] = $this->common_model->record_count('students', array('role'=>'seller'));
		$data['posts'] = $this->common_model->record_count('posts', array());
		$data['support'] = $this->common_model->record_count('support', array());
		
		$data['main_content'] = 'dashboard';
		$this->load->view('includes/template',$data); 
	}
	
	public function delete_student($student_id) 
	{
		$this->common_model->updateData('students', array('status'=>'2'),array('student_id'=>$student_id));
		$this->session->set_flashdata('msg','Student Deleted Successfully.');
						
		redirect('admin/student_list');
	}
	
	public function student_list() 
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}

		$data['msg'] = $this->session->flashdata('msg');
        $total_row = $this->common_model->record_count('students', array('role'=>'student','status'=>'1'));
        
        $config = array();
        $config["base_url"] = base_url() ."/admin/student_list";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 25;       
        $config['num_links'] = 3;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = false;
        $config['reuse_query_string'] = false;       
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
            $data['sno'] = $this->uri->segment(3)+1;
        }
        else{
            $page = 0;
            $data['sno'] = 1;
        }
        $data["links"] = $this->pagination->create_links();
        
        $data['data'] = $dd = $this->common_model->getAllwhere_pagination('students', $config["per_page"], $page,array('role'=>'student','status'=>'1'));
		
		
		$data['main_content'] = 'student_list';
		$this->load->view('includes/template',$data); 
	}
	
	public function delete_seller($student_id) 
	{
		$this->common_model->updateData('students', array('status'=>'2'),array('student_id'=>$student_id));
		$this->session->set_flashdata('msg','Seller Deleted Successfully.');
						
		redirect('admin/sellers');
	}
	
	public function sellers() 
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}

		$data['msg'] = $this->session->flashdata('msg');
        $total_row = $this->common_model->record_count('students', array('role'=>'seller','status'=>'1'));
        
        $config = array();
        $config["base_url"] = base_url() ."/admin/sellers";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 25;       
        $config['num_links'] = 3;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = false;
        $config['reuse_query_string'] = false;       
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
            $data['sno'] = $this->uri->segment(3)+1;
        }
        else{
            $page = 0;
            $data['sno'] = 1;
        }
        $data["links"] = $this->pagination->create_links();
        
        $data['data'] = $dd = $this->common_model->getAllwhere_pagination('students', $config["per_page"], $page,array('role'=>'seller','status'=>'1'));
		
		
		$data['main_content'] = 'sellers';
		$this->load->view('includes/template',$data); 
	}

	public function support_list() 
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}

		$data['msg'] = $this->session->flashdata('msg');
		$total_row = $this->common_model->record_count('support', array());
		
		$config = array();
        $config["base_url"] = base_url() ."/admin/support_list";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 20;       
        $config['num_links'] = 3;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = false;
        $config['reuse_query_string'] = false;       
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
            $data['sno'] = $this->uri->segment(3)+1;
        }
        else{
            $page = 0;
            $data['sno'] = 1;
        }
        $data["links"] = $this->pagination->create_links();
        
        $data['data'] = $dd = $this->common_model->getAllwhere_pagination('support', $config["per_page"], $page);

		$data['main_content'] = 'support_list';
		$this->load->view('includes/template',$data); 
	}
	
	public function delete_ads($post_id) 
	{
		$this->common_model->updateData('posts', array('status'=>'2'),array('post_id'=>$post_id));
		$this->session->set_flashdata('msg','Ads Deleted Successfully.');
						
		redirect('admin/ads');
	}
	
	public function ads() 
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}

		$data['msg'] = $this->session->flashdata('msg');
		$total_row = $this->common_model->record_count('posts', array('status'=>'1'));
		
		$config = array();
        $config["base_url"] = base_url() ."/admin/ads";
        $config["total_rows"] = $total_row;
        $config["per_page"] = 20;       
        $config['num_links'] = 3;
        $config['uri_segment'] = 3;
        $config['use_page_numbers'] = false;
        $config['reuse_query_string'] = false;       
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['attributes'] = ['class' => 'page-link'];
        $config['first_link'] = 'First';
        $config['last_link'] = 'Last';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';
        $config['prev_link'] = 'Previous';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="#" class="page-link">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);
        if($this->uri->segment(3)){
            $page = ($this->uri->segment(3)) ;
            $data['sno'] = $this->uri->segment(3)+1;
        }
        else{
            $page = 0;
            $data['sno'] = 1;
        }
        $data["links"] = $this->pagination->create_links();
        
        $data['data'] = $dd = $this->common_model->getAllwhere_pagination('posts', $config["per_page"], $page,array('status'=>'1'));

		$data['main_content'] = 'ads';
		$this->load->view('includes/template',$data); 
	}

	public function setting()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		$sid = $this->uri->segment(3);
		$data['msg'] = $this->session->flashdata('msg');	
		$data['setting'] = $this->common_model->getsingle('setting', array()); 
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('km', 'KM', 'trim|required');
		
		$data['error'] = "";
		if($this->form_validation->run() == TRUE) 
		{
			//echo $sid; die;
			$dataInsert = array(
						'km' 		=> $this->input->post('km')
				); 
							
			 $this->common_model->updateData('setting', $dataInsert,array('id'=>$sid)); 
			 
			 $this->session->set_flashdata('msg','Setting Updated Successfully.');						
			 redirect('admin/setting/'.$sid);
			
		}
		
		$data['main_content'] = 'setting';
		$this->load->view('includes/template',$data);
	}
	
	public function chkpassword($old_password,$u_id='')
	{
		$chk = $this->common_model->getsingle('admin', array('password'=>$old_password,'admin_id'=>$u_id));			
		if (!$chk)
		{			
			$this->form_validation->set_message('chkpassword', 'Old Password Not Match.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function change_password()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login/logout');
		}
		$data['msg'] = $this->session->flashdata('msg');	
		$u_id ='1';
		$data['udata'] = $this->common_model->getsingle('admin', array('admin_id'=>$u_id)); 
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('old_password', 'Old Password', 'trim|required|callback_chkpassword['.$u_id.']');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('c_password', 'New Confirm Password', 'trim|required|matches[new_password]');
		
		$data['error'] = "";
		if($this->form_validation->run() == TRUE) 
		{
			
			$dataInsert = array(
						'password' 		=> $this->input->post('new_password')
				); 
							
			 $this->common_model->updateData('admin', $dataInsert,array('admin_id'=>$u_id)); 
			 
			 $this->session->set_flashdata('msg','Password Changed Successfully.');						
			 redirect('admin/change_password');
			
		}
		
		$data['main_content'] = 'change_password';
		$this->load->view('includes/template',$data);
	}
	
	public function add_category()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		
		$data['msg'] = $this->session->flashdata('msg');	
		$this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');
		if (empty($_FILES['images']['name']))
		{
			$this->form_validation->set_rules('images', 'Image', 'required');
		}
		if($this->form_validation->run() == TRUE) 
		{
			$config['upload_path'] = 'uploads/category';
			$config['allowed_types'] = 'gif|jpg|png';			
			$this->load->library('upload', $config);
			
			if ($this->upload->do_upload('images'))
			{
				$uploadData = $this->upload->data();
				$image = $uploadData['file_name'];
				$dataInsert = array(
							'category_name' 	=> $this->input->post('cat_name'),
							'image' 			=> $image,	
							'status'			=> 1
						); 	
				$this->common_model->insertData('categories', $dataInsert); 	 
				$this->session->set_flashdata('msg','Category Added Successfully.');
			}			
				redirect('admin/category_list');
		}
		$data['main_content'] = 'add_category';
		$this->load->view('includes/template',$data);            
	}

    public function category_list()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}

		$data['msg'] = $this->session->flashdata('msg');	
		$data['title'] = "";
		
		$config = array();
		$config["base_url"] = base_url() ."/admin/category_list";		
		$total_row = $this->common_model->record_count('categories',array());
		
		$config["total_rows"] = $total_row;
		$config["per_page"] = 25;		
		$config['num_links'] = 3;
		$config['use_page_numbers'] = false;
		$config['reuse_query_string'] = false;		 
		$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
		$config['full_tag_close'] = '</ul>';		 
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<span class="firstlink">';
		$config['first_tag_close'] = '</span>';		 
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<span class="lastlink">';
		$config['last_tag_close'] = '</span>';		 
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<span class="nextlink">';
		$config['next_tag_close'] = '</span>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<span class="prevlink">';
		$config['prev_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span class="curlink">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span class="numlink">';
		$config['num_tag_close'] = '</span>';
		$this->pagination->initialize($config);
		if($this->uri->segment(3))
		{
			$page = ($this->uri->segment(3)) ;
			$data['sno'] = $this->uri->segment(3)+1;
		}
		else
		{
			$page = 0;
			$data['sno'] = 1;
		}
		$data["links"] = $this->pagination->create_links(); 		
		$data['records'] = $this->common_model->getAllwhere_pagination('categories',$config["per_page"],$page,array('status!='=>2));
				
		$data['main_content'] = 'category_list';
		$this->load->view('includes/template',$data);
	}
	
	public function category_status($id,$status)
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		$datas = array(
					'status' 	=> $status,
			); 		
		 $this->common_model->updateData('categories', $datas,array('id'=>$id)); 
		 
		 if($status=="1")
		 {
			$this->session->set_flashdata('msg','Category Activate Successfully.');	
		 }
		 else
		 {
			$this->session->set_flashdata('msg','Category Deactivate Successfully.');		 
		 }		 
			redirect('admin/category_list');	            
	}
	
	public function delete_category($id)
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		$this->common_model->updateData('categories',array('status'=>2),array('id'=>$id)); 
		
		$this->session->set_flashdata('msg','Category Deleted Successfully.');						
		
		redirect('admin/category_list');	          
	}
	
	public function edit_category($id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		$data['cdata'] =$cdata= $this->common_model->getsingle('categories', array('id'=>$id)); 
		$this->form_validation->set_rules('cat_name', 'Category Name', 'trim|required');
		
		if($this->form_validation->run() == TRUE) 
		{
			if($_FILES['images']['name']!='')
			{
				$config['upload_path'] = 'uploads/category';
				$config['allowed_types'] = 'gif|jpg|png';
				$this->load->library('upload', $config);
				if ($this->upload->do_upload('images')) 
				{
					 $uploadData = $this->upload->data();
					$image = $uploadData['file_name'];
				}
			}
			else
			{
				$image=$cdata->image; 
			}
			    $dataupdate = array(
							'category_name' 	=> $this->input->post('cat_name'),
							'image'			    => $image
				); 	
					
				 $this->common_model->updateData('categories', $dataupdate,array('id'=>$id)); 
				 $this->session->set_flashdata('msg','Category Update Successfully.');	
								 
			redirect('admin/category_list');
		}
		$data['main_content'] = 'edit_category';
		$this->load->view('includes/template',$data);	            
	}
	
	public function chk_admin_email($email,$id='')
	{
		if($id!="")
		{
			$email1 = $this->common_model->getsingle('admin', array('email'=>$email,'admin_id !='=>$id));			
		}else{
			$email1 = $this->common_model->getsingle('admin', array('email'=>$email));			
		}
		
		if ($email1)
		{			
			$this->form_validation->set_message('chk_admin_email', 'Email Id already exist.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function chk_admin_mobile($mobile,$id='')
	{
		if($id!="")
		{
			$mobile_no = $this->common_model->getsingle('admin', array('mobile_no'=>$mobile,'admin_id !='=>$id));			
		}else{
			$mobile_no = $this->common_model->getsingle('admin', array('mobile_no'=>$mobile));			
		}
		
		if ($mobile_no)
		{			
			$this->form_validation->set_message('chk_admin_mobile', 'Mobile no already exist.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function add_admin()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		$data['msg'] = $this->session->flashdata('msg');	
		$data['categories'] =$categories= $this->common_model->getAllwhere('categories', array('status'=>1)); 
		
		$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_chk_admin_email');
		//$this->form_validation->set_rules('mobile_no', 'Mobile number', 'trim|required|regex_match[/^[0-9]{10}$/]|callback_chk_admin_mobile');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]');
		$this->form_validation->set_rules('c_password', 'Confirm Password', 'trim|required|matches[password]');
		
		if($this->form_validation->run() == TRUE) 
		{
			$dataInsert = array(
						'cat_id' 	=> $this->input->post('category_id'),
						'name' 			=> $this->input->post('name'),
						'email' 		=> $this->input->post('email'),
						//'mobile_no' 		=> $this->input->post('mobile_no'),
						'password' 		=> $this->input->post('password'),
						'type' 			=> "admin"
					);
							
				$insrt = $this->common_model->insertData('admin', $dataInsert);
				
				$this->session->set_flashdata('msg','Admin Added Successfully.');
						
				redirect('admin/admin_list');
		}
		
		$data['main_content'] = 'add_admin';
		$this->load->view('includes/template',$data);            
	}
	
	public function edit_admin($id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		
		$data['categories'] = $this->common_model->getAllwhere('categories', array('status'=>1)); 
		$data['admin'] = $this->common_model->getsingle('admin', array('admin_id'=>$id)); 
			
		$this->form_validation->set_rules('category_id', 'Category', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_chk_admin_email['.$id.']');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]');
		
		if($this->form_validation->run() == TRUE) 
		{
			$dataInsert = array(
						'cat_id' 		=> $this->input->post('category_id'),
						'name' 			=> $this->input->post('name'),
						'email' 		=> $this->input->post('email'),
						'password' 		=> $this->input->post('password')
					);
								
				$insrt_id = $this->common_model->updateData('admin', $dataInsert,array('admin_id'=>$id)); 
			
				$this->session->set_flashdata('msg','Admin Updated Successfully.');
						
				redirect('admin/admin_list');
		}
		
		$data['main_content'] = 'edit_admin';
		$this->load->view('includes/template',$data);	            
	}
	
	public function admin_list()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		$data['msg'] = $this->session->flashdata('msg');	
		$data['title'] = "BM Mart";
		
		$config = array();
		$config["base_url"] = base_url() ."/admin/admin_list";		
		$total_row = $this->common_model->record_count('admin',array('type'=>'admin'));
		
		$config["total_rows"] = $total_row;
		$config["per_page"] = 25;		
		$config['num_links'] = 3;
		$config['use_page_numbers'] = false;
		$config['reuse_query_string'] = false;		 
		$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
		$config['full_tag_close'] = '</ul>';		 
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<span class="firstlink">';
		$config['first_tag_close'] = '</span>';		 
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<span class="lastlink">';
		$config['last_tag_close'] = '</span>';		 
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<span class="nextlink">';
		$config['next_tag_close'] = '</span>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<span class="prevlink">';
		$config['prev_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span class="curlink">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span class="numlink">';
		$config['num_tag_close'] = '</span>';
		$this->pagination->initialize($config);
		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
			$data['sno'] = $this->uri->segment(3)+1;
		}
		else
		{
			$page = 0;
			$data['sno'] = 1;
		}
		$data["links"] = $this->pagination->create_links(); 		
		
		$data['records'] =$record = $this->common_model->getAllwhere_pagination('admin',$config["per_page"],$page,array('type'=>'admin'));
		
		$ex = $this->uri->segment(3);	

		$data['main_content'] = 'admin_list';
		$this->load->view('includes/template',$data);
	}
	
	public function delete_admin($id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		 
		$id = $this->uri->segment(3);
		
		$this->common_model->deleteData('admin',array('admin_id'=>$id));
		
		$this->session->set_userdata('msg', array('msg' => 'Admin Deleted Successfully'));
		
		redirect('admin/admin_list');
	}
	
	public function admin_status($id,$status)
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		$datas = array(
					'status' 	=> $status,
			); 		
		 $this->common_model->updateData('admin', $datas,array('admin_id'=>$id,'type'=>'admin')); 
		 
		 if($status=="1")
		 {
			$this->session->set_flashdata('msg','Admin Activate Successfully.');	
		 }
		 else
		 {
			$this->session->set_flashdata('msg','Admin Deactivate Successfully.');		 
		 }		 
			redirect('admin/admin_list');	            
	}
	
	public function getadmin()
	{
		$category_id = $_POST["category_id"];
		$admin = $this->common_model->getAllwhere('admin',array('type'=>'admin','cat_id'=>$category_id));
		$subcat = '<option value="" >Select Admin </option>';
		if(count($admin)>0)
		{
			foreach($admin as $c)
			{
				$subcat .= '<option value="'.$c->admin_id.'">'.$c->name.'</option>';
			} 
		}
		echo $subcat;			
	}
	
	public function add_product()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		$data['msg'] = $this->session->flashdata('msg');	
		$data['categories'] =$categories= $this->common_model->getAllwhere('categories', array('status'=>1));
		$data['p_type'] =$p_type= $this->common_model->getAllwhere('p_type', array());
		$data['admin'] = $this->common_model->getAllwhere('admin', array('type'=>'admin'));		
		$admin= $this->common_model->getsingle('admin', array('admin_id'=>$this->session->userdata('admin_id'),'type'=>'admin'));
		if($this->session->userdata('type') == 'super')
		{
			$this->form_validation->set_rules('category_id', 'category', 'trim|required');
			$this->form_validation->set_rules('admin_id', 'admin', 'trim|required');
			$cat_id = $this->input->post('category_id');
			$admin_id = $this->input->post('admin_id');
		}
		else{
			$cat_id = $admin->cat_id;
			$admin_id = $admin->admin_id;
		}
		
		//echo "<pre>"; print_r($cat_id); die;
			$this->form_validation->set_rules('name', 'product name', 'trim|required');
			$this->form_validation->set_rules('price', 'price', 'trim|required');
			$this->form_validation->set_rules('discount_price', 'discount price', 'trim|required');
			$this->form_validation->set_rules('delivery_charge', 'delivery charge', 'trim|required');
			$this->form_validation->set_rules('p_type', 'product type', 'trim|required');
			$this->form_validation->set_rules('description', 'description', 'trim|required');
			$this->form_validation->set_rules('lat', 'Lat', 'trim|required');
			$this->form_validation->set_rules('long', 'Long', 'trim|required');
		
		if (empty($_FILES['images']['name']))
		{
			$this->form_validation->set_rules('images', 'Image', 'required');
		}
		
		if($this->form_validation->run() == TRUE) 
		{
				$dataInsert = array(
							'cat_id' 			=> $cat_id,
							'name' 				=> $this->input->post('name'),
							'price' 			=> $this->input->post('price'),
							'p_type' 			=> $this->input->post('p_type'),
							'p_type_qty' 		=> $this->input->post('p_type_qty'),
							'discount_price' 	=> $this->input->post('discount_price'),
							'delivery_charge' 	=> $this->input->post('delivery_charge'),
							'qty' 				=> $this->input->post('qty'),
							'description' 		=> $this->input->post('description'),
							'p_lat' 			=> $this->input->post('lat',TRUE),
							'p_long' 			=> $this->input->post('long',TRUE),
							'admin_id' 			=> $admin_id,
							'added_by' 			=> $this->session->userdata('admin_id'),
							'status' 			=> 1,	
							'created_date'		=> date('Y-m-d H:i:s')
						);
						
				$insrt_id = $this->common_model->insertData('products', $dataInsert);
				
				$qtyInsert = array(
							'p_id' 				=> $insrt_id,
							'qty' 				=> $this->input->post('qty'),
							'type' 				=> 'Cr',
							'created_date'		=> date('Y-m-d H:i:s')
						);
				$this->common_model->insertData('qty_history', $qtyInsert);
				
				$cpt = count($_FILES['images']['name']);
			    $files = $_FILES;
					
					if($cpt>0)
					{
						for($i=0; $i<$cpt; $i++)
						{           
							$_FILES['images']['name']= $files['images']['name'][$i];
							$_FILES['images']['type']= $files['images']['type'][$i];
							$_FILES['images']['tmp_name']= $files['images']['tmp_name'][$i];
							$_FILES['images']['error']= $files['images']['error'][$i];
							$_FILES['images']['size']= $files['images']['size'][$i];    
							
							$config['upload_path'] = 'uploads/products';
							$config['allowed_types'] = 'gif|jpg|png';
							
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							$this->upload->do_upload('images');
							$attachment_data = $this->upload->data();
							$p_image = $attachment_data['file_name'];
							
							$insdata = array(						
									'p_id' 			=> $insrt_id,
									'images' 		=> $p_image,
									'status' 		=> 1,											
									'entry_date'	=> date('Y-m-d')
								);
								
							$this->common_model->insertData('p_images',$insdata);
						}
					}
					
				$this->session->set_flashdata('msg','Product Added Successfully.');
				redirect('admin/product_list');
		}
		
		$data['main_content'] = 'add_product';
		$this->load->view('includes/template',$data);            
	}
	
	public function edit_product($id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		
		$data['categories'] = $this->common_model->getAllwhere('categories', array('status'=>1));
		$data['admin'] = $this->common_model->getAllwhere('admin', array('type'=>'admin'));	
		$data['p_type'] =$this->common_model->getAllwhere('p_type', array());
		$data['product'] = $product =  $this->common_model->getsingle('products', array('id'=>$id));			
		$data['p_images'] = $this->common_model->getAllwhere('p_images', array('p_id'=>$id)); 
		
		if($this->session->userdata('type') == 'super')
		{
			$this->form_validation->set_rules('category_id', 'category', 'trim|required');
			$this->form_validation->set_rules('admin_id', 'admin', 'trim|required');
			$cat_id = $this->input->post('category_id');
			$admin_id = $this->input->post('admin_id');
		}
		else{
			$cat_id = 0;
			$admin_id = 0;
		}
			$this->form_validation->set_rules('name', 'product name', 'trim|required');
			$this->form_validation->set_rules('price', 'price', 'trim|required');
			$this->form_validation->set_rules('p_type', 'product type', 'trim|required');
			$this->form_validation->set_rules('discount_price', 'discount price', 'trim|required');
			$this->form_validation->set_rules('delivery_charge', 'delivery charge', 'trim|required');
			$this->form_validation->set_rules('description', 'description', 'trim|required');
			$this->form_validation->set_rules('lat', 'Lat', 'trim|required');
			$this->form_validation->set_rules('long', 'Long', 'trim|required');
		
		if($this->form_validation->run() == TRUE) 
		{
			$dataInsert = array(
							'cat_id' 			=> $cat_id,
							'name' 				=> $this->input->post('name'),
							'price' 			=> $this->input->post('price'),
							'p_type' 			=> $this->input->post('p_type'),
							'p_type_qty' 		=> $this->input->post('p_type_qty'),
							'discount_price' 	=> $this->input->post('discount_price'),
							'delivery_charge' 	=> $this->input->post('delivery_charge'),
							'p_lat' 			=> $this->input->post('lat',TRUE),
							'p_long' 			=> $this->input->post('long',TRUE),
							'description' 		=> $this->input->post('description'),
							'admin_id' 			=> $admin_id,
							'update_date'		=> date('Y-m-d H:i:s')
						);
				//echo "<pre>"; print_r($dataInsert); die;					
			$product_id = $this->common_model->updateData('products', $dataInsert,array('id'=>$id)); 
				
			if($_FILES['images']['name'][0]!='')
			{
				$cpt = count($_FILES['images']['name']);
				$files = $_FILES;
					
					if($cpt>0)
					{
						for($i=0; $i<$cpt; $i++)
						{           
							$_FILES['images']['name']= $files['images']['name'][$i];
							$_FILES['images']['type']= $files['images']['type'][$i];
							$_FILES['images']['tmp_name']= $files['images']['tmp_name'][$i];
							$_FILES['images']['error']= $files['images']['error'][$i];
							$_FILES['images']['size']= $files['images']['size'][$i];    
							$config['upload_path'] = 'uploads/products';
							$config['allowed_types'] = 'gif|jpg|png';
							
							$this->load->library('upload', $config);
							$this->upload->initialize($config);
							$this->upload->do_upload('images');
							$attachment_data = $this->upload->data();
							$p_image = $attachment_data['file_name'];
							
							$insdata = array(						
									'p_id' 	=> $id,
									'images' 		=> $p_image,							
									'entry_date'	=> date('Y-m-d')
								);
							$this->common_model->insertData('p_images',$insdata);
						}
					}
			}
		
			$this->session->set_flashdata('msg','Product Added Successfully.');
					
			redirect('admin/product_list');
		}
		
		$data['main_content'] = 'edit_product';
		$this->load->view('includes/template',$data);	            
	}
	
	public function delete_product($id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		
		$id = $this->uri->segment(3);
		
		//$this->common_model->deleteData('products',array('id'=>$id));
		$this->common_model->updateData('products',array('status'=>2),array('id'=>$id));
		
		$this->session->set_userdata('msg', array('msg' => 'Product Deleted Successfully'));
		
		redirect('admin/product_list');
	}
	
	public function product_list()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		$data['msg'] = $this->session->flashdata('msg');	
		$data['title'] = "BM";
		
		if($this->session->userdata('type')=='super')
		{
			$where = array('status!='=>2);
		}
		else
		{
			$where = array('status!='=>2,'admin_id'=>$this->session->userdata('admin_id'));
		}
		
		$config = array();
		$config["base_url"] = base_url() ."/admin/product_list";		
		$total_row = $this->common_model->record_count('products',$where);
		
		$config["total_rows"] = $total_row;
		$config["per_page"] = 25;		
		$config['num_links'] = 3;
		$config['use_page_numbers'] = false;
		$config['reuse_query_string'] = false;		 
		$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
		$config['full_tag_close'] = '</ul>';		 
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<span class="firstlink">';
		$config['first_tag_close'] = '</span>';		 
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<span class="lastlink">';
		$config['last_tag_close'] = '</span>';		 
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<span class="nextlink">';
		$config['next_tag_close'] = '</span>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<span class="prevlink">';
		$config['prev_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span class="curlink">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span class="numlink">';
		$config['num_tag_close'] = '</span>';
		$this->pagination->initialize($config);
		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
			$data['sno'] = $this->uri->segment(3)+1;
		}
		else
		{
			$page = 0;
			$data['sno'] = 1;
		}
		$data["links"] = $this->pagination->create_links(); 		
		
		$data['records'] =$record = $this->common_model->getAllwhere_pagination('products',$config["per_page"],$page,$where);
		
		$data['main_content'] = 'product_list';
		$this->load->view('includes/template',$data);
	}
	
	public function view_product_details($id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		
		$data['records'] = $this->common_model->getsingle('products', array('id'=>$id));
		
		$data['p_images'] = $this->common_model->getAllwhere('p_images', array('p_id'=>$id)); 		
		
		$data['main_content'] = 'view_product_details';
		$this->load->view('includes/template',$data);	            
	}
	
	public function product_status($id,$status)
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		
		$datas = array(
					'status' 	=> $status,
			);
			
		 $this->common_model->updateData('products', $datas,array('id'=>$id)); 
		 
		 if($status=="1")
		 {
			$this->session->set_flashdata('msg','Product Activate Successfully.');	
		 }
		 else
		 {
			$this->session->set_flashdata('msg','Product Deactivate Successfully.');		 
		 }		 
			redirect('admin/product_list');	            
	}
	
	public function delete_pimg($id,$p_id)
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		
		$pimage = $this->common_model->getsingle('p_images',array('id'=>$id,'p_id'=>$p_id));
		$unlink=unlink("uploads/products/".$pimage->images);
		
		$dlt=$this->common_model->deleteData('p_images',array('id'=>$id));
		//$this->common_model->updateData('p_images', array('status'=>2), array('id'=>$id));
		
		$this->session->set_userdata('msg','Deleted successfully!');
		
		redirect(base_url().'admin/edit_product/'.$pimage->p_id, 'refresh');
	}
	
	public function add_qty($p_id)
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}
		
		$data['msg'] = $this->session->flashdata('msg');	
		$data['product'] = $product = $this->common_model->getsingle('products', array('id'=>$p_id)); 
		
		$this->form_validation->set_rules('add_qty', 'add more qty', 'trim|required');
		
		if($this->form_validation->run() == TRUE) 
		{
			$new_qty = $product->qty + $this->input->post('add_qty');
						
			$this->common_model->updateData('products',array('qty'=>$new_qty),array('id'=>$p_id));
			
			$qtyInsert = array(
							'p_id' 				=> $p_id,
							'qty' 				=> $this->input->post('add_qty'),
							'type' 				=> 'Cr',
							'created_date'		=> date('Y-m-d H:i:s')
						);
				$this->common_model->insertData('qty_history', $qtyInsert);
			
			$this->session->set_flashdata('msg','Quantity Added Successfully.');
					
			redirect('admin/product_list');
		}
		
		$data['main_content'] = 'add_qty';
		$this->load->view('includes/template',$data);            
	}
	
	public function delivered_chk()
	{
		$chkId = $_POST["chkId"];
		
		if($chkId!='')
		{
			$ex_chk = explode(',',$chkId);
			foreach($ex_chk as $chk_orderno)
			{
				echo $chk_orderno;
				if($chk_orderno!='on')
				{
					$datas = array(
						'delivered' 	=> 1,
					);
					$this->common_model->updateData('orders', $datas,array('order_no'=>$chk_orderno)); 
				}
			} 
		}	
	}
	
	public function orders_list()
	{
		if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		/*if($this->session->userdata('type')!='super')
		{
			redirect('admin');
		}*/
		
		if($this->session->userdata('type')=='super')
		{
			$admin_id = '';
		}
		else
		{
			$admin_id = $this->session->userdata('admin_id');
		}
		
		$from_date='';
		$to_date='';
		
		$data['msg'] = $this->session->flashdata('msg');	
		$data['title'] = "BM Mart";
		
		$from_date = $this->input->post('from_date');
		$to_date = $this->input->post('to_date');
		
		if($from_date!="")
		{
			$this->session->set_userdata('from_date',$from_date);
		}else{ 
			if($_POST)
			{ 
				$this->session->set_userdata('from_date',$from_date);
			}
		}
		if($to_date!="")
		{
			$this->session->set_userdata('to_date',$to_date);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('to_date',$to_date);
			}
		}
		$data['from_date'] = $from_date = $this->session->userdata('from_date');
		
		$data['to_date'] = $to_date = $this->session->userdata('to_date');
		
		//echo $from_date."--".$to_date;die;
		
		$config = array();
		$config["base_url"] = base_url() ."/admin/orders_list";		
		$total_row = $this->common_model->search_order_bydate_record_count($from_date,$to_date,$admin_id);
		
		$config["total_rows"] = $total_row;
		$config["per_page"] = 25;		
		$config['num_links'] = 3;
		$config['use_page_numbers'] = false;
		$config['reuse_query_string'] = false;		 
		$config['full_tag_open'] = '<ul class="pagination pagination-sm">';
		$config['full_tag_close'] = '</ul>';		 
		$config['first_link'] = '<<';
		$config['first_tag_open'] = '<span class="firstlink">';
		$config['first_tag_close'] = '</span>';		 
		$config['last_link'] = '>>';
		$config['last_tag_open'] = '<span class="lastlink">';
		$config['last_tag_close'] = '</span>';		 
		$config['next_link'] = '>';
		$config['next_tag_open'] = '<span class="nextlink">';
		$config['next_tag_close'] = '</span>';
		$config['prev_link'] = '<';
		$config['prev_tag_open'] = '<span class="prevlink">';
		$config['prev_tag_close'] = '</span>';
		$config['cur_tag_open'] = '<span class="curlink">';
		$config['cur_tag_close'] = '</span>';
		$config['num_tag_open'] = '<span class="numlink">';
		$config['num_tag_close'] = '</span>';
		$this->pagination->initialize($config);
		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3)) ;
			$data['sno'] = $this->uri->segment(3)+1;
		}
		else
		{
			$page = 0;
			$data['sno'] = 1;
		}
		$data["links"] = $this->pagination->create_links(); 		
		
		$data['records'] =$record = $this->common_model->search_order_bydate($from_date,$to_date,$config["per_page"],$page,$admin_id);
		
		$ex = $this->uri->segment(3);	

		if($record && $ex=='export')
		{ 
			$delimiter = ","; 
			$filename = 'OrderList'.date('dmY').'.csv'; 
			header("Content-Description: File Transfer"); 
			header("Content-Disposition: attachment; filename=$filename"); 
			header("Content-Type: application/csv; ");

			$file = fopen('php://output', 'w');

			$header = array('ORDERNO', 'USER NAME', 'ORDER AMT', 'PAYMENT MODE', 'ORDER DATE', 'DELIVER STATUS');
			fputcsv($file, $header);
			foreach ($record as $rec)
			{ 
				$allp='';
						$user = $this->common_model->getsingle('users', array('user_id'=>$rec->user_id));
						$products = $this->common_model->getsingle('products', array('id'=>$rec->p_id)); 
						$payment = $this->common_model->getsingle('payment', array('order_no'=>$rec->order_no));
						
						$all_for_admin = $this->common_model->getAll_order('orders', array('order_no'=>$rec->order_no),$this->session->userdata('admin_id'));
						foreach($all_for_admin as $all_rec)
						{
							$total_price = number_format((float)($all_rec->price+$all_rec->delivery_charge)-$all_rec->discount_price,2,'.','');
							$allp = $allp+$total_price;
						}
						if($this->session->userdata('type')=='super')
						{ 
							$ttl_payment = number_format((float)$payment->price,2,'.','');
						}
						else
						{
							$ttl_payment = $allp;
						}    
						$deliveredStatus = $rec->delivered==1?"Delivered":"Pending";
				$lineData = array($rec->order_no, $user->user_name, $ttl_payment.' Rs', ucfirst($payment->type), date('d-m-Y',strtotime($rec->created_date)), $deliveredStatus); 
				fputcsv($file,$lineData,$delimiter); 
			}
				fclose($file);
				exit; 
				redirect('admin/orders_list');	
		}
		//echo "<pre>"; print_r($data['records']); die;
		
		
		$data['main_content'] = 'orders_list';
		$this->load->view('includes/template',$data);
	}
	
	public function view_order_details($id,$order_no='')
	{
	    if($this->session->userdata('admin_id')=='')
		{
			redirect('login');
		}
		if($this->session->userdata('type')=='super')
		{
			$admin_id = '';
		}
		else
		{
			$admin_id = $this->session->userdata('admin_id');
		}
		
		$data['records'] = $this->common_model->getAll_order('orders', array('order_no'=>$order_no),$admin_id);
		
		$data['main_content'] = 'view_order_details';
		$this->load->view('includes/template',$data);	            
	}
	
	
	
		
}	

?>	