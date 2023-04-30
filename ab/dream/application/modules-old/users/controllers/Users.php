<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta'); 
		
	}
	
	public function user_status($id,$status)
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login/logout');
		}	
		$deleted_user_master_id = $this->session->userdata('user_master_id');
		
		$update_data = array(
			'is_user_deleted'			=> $status,
			'deleted_user_master_id'	=> $deleted_user_master_id,
			'deleted_date'				=> date('Y-m-d H:i:s')
			);
		$this->common_model->updateData('user_master',$update_data,array('user_master_id'=>$id));
		$this->session->set_flashdata("msg","<font class='success'>Action performed successfully.</font>");
		redirect('users');
		            
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
		$my_id = $this->session->userdata('user_master_id');
		$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		$my_downs =array();
		if(count($my_reports)>0)
		{
			foreach($my_reports as $ids)
			{
				$my_downs[] = $ids->user_master_id;
			}
		}
			
		$data['msg'] = $this->session->flashdata('msg');
		
		$config = array();
		$config["base_url"] = base_url() ."/users/index";
		
		$fusername = $this->input->post('username');		
		if($fusername!="")
		{
			$this->session->set_userdata('fusername',$fusername);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('fusername',$fusername);
			}
		}
		
		$data['username'] = $username = $this->session->userdata('fusername');
	
		if($this->session->userdata('user_type')=='3')
		{
			$chk = $this->common_model->getsingle('user_master',array('user_name'=>$username));
			$user_downs = array();
			if($chk)
			{
				$user_downs = $this->common_model->get_down_list($chk->user_master_id);				
			} 
			$total_row = $this->common_model->record_count_admin('user_master',$username,$user_downs);
		}
		else if($this->session->userdata('user_type')=='2')
		{
			$chk = $this->common_model->getsingle('user_master',array('user_name'=>$username));
			$user_downs = array();
			if($chk)
			{
				$user_downs = $this->common_model->get_down_list($chk->user_master_id);				
			} 
			$total_row = $this->common_model->record_count_distribter2('user_master',$my_downs,$username,$user_downs);
		}
		else if($this->session->userdata('user_type')=='1')
		{
			$total_row = $this->common_model->record_count_shop('user_master',$username);
		}		
		$config["total_rows"] = $total_row;
		$config["per_page"] = 20;		
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
		else{
			$page = 0;
			$data['sno'] = 1;
		}
		$data["links"] = $this->pagination->create_links(); 
		if($this->session->userdata('user_type')=='3')
		{
			$chk = $this->common_model->getsingle('user_master',array('user_name'=>$username));
			$user_downs = array();
			if($chk)
			{
				$user_downs = $this->common_model->get_down_list($chk->user_master_id);				
			} 
			$data['users'] = $this->common_model->getAllwhere_pagination_admin('user_master',$config["per_page"],$page,$username,$user_downs);
		}
		else if($this->session->userdata('user_type')=='2')
		{
			$chk = $this->common_model->getsingle('user_master',array('user_name'=>$username));
			$user_downs = array();
			if($chk)
			{
				$user_downs = $this->common_model->get_down_list($chk->user_master_id);				
			} 
			$data['users'] = $this->common_model->getAllwhere_pagination_distribter2('user_master',$config["per_page"],$page,$my_downs,$username,$user_downs);
		}
		else if($this->session->userdata('user_type')=='1')
		{	
			$data['users'] = $this->common_model->getAllwhere_pagination_shop('user_master',$config["per_page"],$page,$username);
		}
		
		$data['main_content'] = 'index';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function getreporting_user()
	{
		$user_type = trim($_POST['user_type']);
		$user_master_id = trim($_POST['user_master_id']);
		
		$users ="";
		if($user_master_id!="")
		{
			if($user_type=="0"){ 
				$users = $this->common_model->getAllwhere('user_master',array('user_master_id !='=>$user_master_id, 'user_type'=>'1','is_user_deleted'=>'0'));
			}
			else if($user_type=="1")
			{
				$users = $this->common_model->getAllwhere('user_master',array('user_master_id !='=>$user_master_id, 'user_type'=>'2','is_user_deleted'=>'0'));
			}
			else if($user_type=="2")
			{
				$users = $this->common_model->getAllwhere('user_master',array('user_master_id !='=>$user_master_id, 'user_type'=>'3','is_user_deleted'=>'0'));
			}
			
		}else{
			if($user_type=="0"){
				if($this->session->userdata('user_type')=='1')
				{
					$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$this->session->userdata('user_master_id'),'user_type'=>'1','is_user_deleted'=>'0'));
				}
				else if($this->session->userdata('user_type')=='2')
				{
					$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$this->session->userdata('user_master_id'),'user_type'=>'1','is_user_deleted'=>'0'));
				}
				else{
					$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'1','is_user_deleted'=>'0'));
				}
			}
			else if($user_type=="1")
			{
				if($this->session->userdata('user_type')=='2')
				{
					$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$this->session->userdata('user_master_id'),'user_type'=>'2','is_user_deleted'=>'0'));
				}else{
				$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'2','is_user_deleted'=>'0'));
				}
			}
			else if($user_type=="2")
			{
				$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'3','is_user_deleted'=>'0'));
			}
		}
		
		
		
		
		$html = '<option value="">Select Client</option>';
		if($users!=""){ 
			foreach($users as $us){ 
				$html .= '<option  value="'.$us->user_master_id.'" > '.$us->user_name.' ( '.$us->name.' ) </option>';
			}								
		} 
		echo $html;
	}
	
	public function get_user_commision()
	{
		$user_master_id = trim($_POST['user_master_id']);
		$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		echo $user_data->user_comission;
	}
	
	public function alpha_dash_space($fullname)
	{
		if (! preg_match('/^[a-zA-Z ]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters with space.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function alpha_dash_space_new($fullname)
	{
		if (! preg_match('/^[a-zA-Z0-9 ]+$/', $fullname)) {
			$this->form_validation->set_message('alpha_dash_space_new', 'The %s field may only contain alpha characters and number with space.');
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
		if($this->session->userdata('user_type')=='0' OR $this->session->userdata('user_type')=='1')
		{
			redirect('login/logout');
		}
		
		$data['msg'] = $this->session->flashdata('msg');
		
		$name 						= $this->input->post('name',TRUE);
		$user_password 				= $this->input->post('user_password',TRUE);
		$user_type 					= $this->input->post('user_type',TRUE);
		$reporting_user_master_id 	= $this->input->post('reporting_user_master_id',TRUE);
		$point_password 			= $this->input->post('point_password',TRUE);
		$user_comission				= "0.00";
		$user_comission_type		= "0";
		$shop_master_id				= "0";
		$is_user_deleted 			= $this->input->post('is_user_deleted',TRUE);
		$created_user_master_id 	= $this->session->userdata('user_master_id');
		
		if($this->session->userdata('user_type')=='3'){ 
			$max_winning		= $this->input->post('max_winning',TRUE);
			$this->form_validation->set_rules('max_winning', 'Max Won', 'trim');
			$winning_distribution		= $this->input->post('winning_distribution',TRUE);
			$this->form_validation->set_rules('winning_distribution', 'Won Distribution', 'trim');
			$bonus_percent		= $this->input->post('bonus_percent',TRUE);
			$this->form_validation->set_rules('bonus_percent', 'Bonus %', 'trim|required');
		}
		
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_alpha_dash_space_new');
		//$this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[4]');
		//$this->form_validation->set_rules('c_user_password', 'Confirm Password', 'trim|required|matches[user_password]');
		$this->form_validation->set_rules('user_type', 'Role', 'trim|required');
		$this->form_validation->set_rules('reporting_user_master_id', 'Head', 'trim|required');
		//$this->form_validation->set_rules('point_password', 'Point Transfer Password', 'trim|required|min_length[4]');
		
		
		if($user_type=="2" OR $user_type=="1")
		{
			if($user_type=="2")
			{
				$user_comission_type="1";
			}else{
				$user_comission_type="2";
				//$this->form_validation->set_rules('shop_master_id', 'Shop', 'trim|required');
				$shop_master_id = $this->input->post('shop_master_id',TRUE);
			}
			$this->form_validation->set_rules('user_comission', 'Revenue', 'trim|required');			
			$user_comission = $this->input->post('user_comission',TRUE);
		}
		
		
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
				$profile_image = '';
				
				if ($this->upload->do_upload('profile_image'))
				{
					$attachment_data = array('upload_data' => $this->upload->data());
					$profile_image = $attachment_data['upload_data']['file_name'];
				}
				else
				{
					if($_FILES['profile_image']['name']!="")
					{
						$data['errors'] = "Allowed upload type jpg, png and jpeg images only.";
					}			
				}
			// echo $profile_image;die;
			$c_users = $this->common_model->getAllwhere('user_master',array('user_type'=>$user_type));
			if($c_users)
			{
				$sum = count($c_users);
			}else{
				$sum = 0;
			}
			
			if($user_type==0)
			{
				$uno = 60000 + $sum;
				$user_name = $uno;
			}
			else if($user_type==1)
			{
				$uno = 30000 + $sum;
				$user_name = $uno;
			}
			else if($user_type==2)
			{
				$uno = 10000 + $sum;
				$user_name = $uno;
			}
			
			if( $data['errors']==''){			
			$insertArray = array(
				'user_name' 				=> $user_name,
				'name' 						=> $name,
				'user_password' 			=> '123123',
				'user_type' 				=> $user_type,
				'reporting_user_master_id' 	=> $reporting_user_master_id,
				'user_comission_type'		=> $user_comission_type,
				'user_comission'			=> $user_comission,
				'is_user_deleted'			=> $is_user_deleted,
				'created_user_master_id'    => $created_user_master_id,	
				'profile_image'    			=> $profile_image,			
				'point_password'			=> '1010',
				'created_date' 				=> date('Y-m-d H:i:s')
		   );
		   if($this->session->userdata('user_type')=='3'){ 
				$insertArray['max_winning'] = $max_winning;
				$insertArray['winning_distribution'] = $winning_distribution;
				$insertArray['bonus_percent'] = $bonus_percent;
		   }else{
			   $insertArray['winning_distribution'] = null;
			   $insertArray['bonus_percent'] = 0;
		   }
			// print_r($insertArray);die;
		   if($user_type=="1")
			{
				$insertArray['shop_master_id'] = null; //$shop_master_id;
			}
			else if($user_type=="0")
			{
				$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$reporting_user_master_id));
				$insertArray['shop_master_id'] = null; //$user_data->shop_master_id;
			}
			
		   
			$insert_id = $this->common_model->insertData('user_master',$insertArray);
			
			$this->session->set_flashdata("msg","<font class='success'>User Added Successfully.</font>");
			redirect('users');
			}
		}
		
		$data['main_content'] = 'add';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function chk_exist($user_name,$user_master_id)
	{
		$data = $this->common_model->getsingle('user_master',array('user_master_id !='=>$user_master_id,'user_name'=>$user_name));
		if ($data) {
			$this->form_validation->set_message('chk_exist', 'The %s already exist please enter another name.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function edit($user_master_id)
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login/logout');
		}
		$data['user'] 	= $user = $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		$old_data = json_encode($user);
		
		$data['msg'] = $this->session->flashdata('msg');
		
		$name 						= $this->input->post('name',TRUE);
		$user_password 				= $this->input->post('user_password',TRUE);
		$c_user_password 			= $this->input->post('c_user_password',TRUE);
		//$user_type 					= $this->input->post('user_type',TRUE);
		//$reporting_user_master_id 	= $this->input->post('reporting_user_master_id',TRUE);
		$point_password 			= $this->input->post('point_password',TRUE);
		$user_comission				= "0.00";
		$user_comission_type		= "0";
		$shop_master_id				= "0";
		$is_user_deleted 			= $this->input->post('is_user_deleted',TRUE);
		$updated_user_master_id 	= $this->session->userdata('user_master_id');
		
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('name', 'Name', 'trim|required|callback_alpha_dash_space_new');
		$this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[4]');
		if($this->session->userdata('user_type')=='3'){
			$max_winning		= $this->input->post('max_winning',TRUE);
			$this->form_validation->set_rules('max_winning', 'Max Winning', 'trim');
			
			$winning_distribution		= $this->input->post('winning_distribution',TRUE);
			$this->form_validation->set_rules('winning_distribution', 'Winning Distribution', 'trim');
			$bonus_percent 	= $this->input->post('bonus_percent',TRUE);
			$this->form_validation->set_rules('bonus_percent', 'Bonus %', 'trim|required');
		}
				
		if($point_password!="")
		{
			$this->form_validation->set_rules('point_password', 'Point Password Password', 'trim|required|min_length[4]');
		}else{
			$point_password = $user->point_password;
		}
		
		
		
		//$this->form_validation->set_rules('user_type', 'User Type', 'trim|required');
		//$this->form_validation->set_rules('reporting_user_master_id', 'Reporting User', 'trim|required');
		
		$user_type = $user->user_type;
		$reporting_user_master_id  = $user->reporting_user_master_id;
		if($user_type=="2" OR $user_type=="1")
		{
			if($user_type=="2")
			{
				$user_comission_type="1";
			}else{
				$user_comission_type="2";
				//$this->form_validation->set_rules('shop_master_id', 'Shop', 'trim|required');
				$shop_master_id = $this->input->post('shop_master_id',TRUE);
			}
			$this->form_validation->set_rules('user_comission', 'Revenue', 'trim|required');			
			$user_comission = $this->input->post('user_comission',TRUE);
		}
		
		
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
						$data['errors'] = "Allowed upload type jpg, png and jpeg images only.";
					}		
				}

			if( $data['errors']==''){
			$update_data = array(
				'name' 						=> $name,
				'user_password' 			=> $user_password,
				//'user_type' 				=> $user_type,
				//'reporting_user_master_id' 	=> $reporting_user_master_id,
				'user_comission_type'		=> $user_comission_type,
				'user_comission'			=> $user_comission,
				'is_user_deleted'			=> $is_user_deleted,
				'updated_user_master_id'    => $updated_user_master_id,
				//'point_password'    		=> $point_password,	
				'profile_image'				=> $profile_image,
				'updated_date' 				=> date('Y-m-d H:i:s')
		   );
		   if($this->session->userdata('user_type')=='3'){ 
				$update_data['max_winning'] = $max_winning;
				$update_data['winning_distribution'] = $winning_distribution;
				$update_data['bonus_percent'] = $bonus_percent;
		   }
		   if($user_type=="2")
			{
				$update_data['shop_master_id'] = null;
			}
			else if($user_type=="1")
			{
				$update_data['shop_master_id'] = null; //$shop_master_id;
			}else if($user_type=="0")
			{
				$user_data = $this->common_model->getsingle('user_master',array('user_master_id'=>$reporting_user_master_id));
				$update_data['shop_master_id'] = null; //$user_data->shop_master_id;
			}
			
			
			if($user_type==2 && $user_comission!=$user->user_comission)
			{
				$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user->user_master_id, 'user_type'=>'1'));
				if(count($users)>0)
				{
					foreach($users as $u)
					{
						if($user_comission<$u->user_comission)
						{
							$this->common_model->updateData('user_master',array('user_comission'=>$user_comission),array('user_master_id'=>$u->user_master_id));
						}
					}
				}
			}
		    
			$this->common_model->updateData('user_master',$update_data,array('user_master_id'=>$user_master_id));

			$newuser = $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
			$new_data = json_encode($newuser);
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
			
			$historydata = array(
				'user_master_id'	=> $user_master_id,
				'old_data'			=> $old_data,
				'new_data'			=> $new_data,
				'entry_date'		=> date('Y-m-d'),
				'entry_date_time'	=> date('Y-m-d H:i:s'),
				'ipaddress'			=> $ipaddress,
				'edited_by'			=> $this->session->userdata('user_master_id')
			);
			$this->common_model->insertData('users_edited_history',$historydata);
			
			$this->session->set_flashdata("msg","<font class='success'>User Updated Successfully.</font>");
			redirect('users');
			}
		}
		
		$data['main_content'] = 'edit';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function view($user_master_id)
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')=='0')
		{
			redirect('login/logout');
		}
		$data['user'] 	= $user = $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		
		
		
		$data['main_content'] = 'view';
		$this->load->view('includes/template',$data);
		            
	}
}	

?>	