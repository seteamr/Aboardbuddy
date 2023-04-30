<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Points extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta'); 
	}
		
	public function index()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}

		
		$data['msg'] = $this->session->flashdata('msg');
		
		$ff_from_date = $this->input->post('from_date');
		$ff_to_date = $this->input->post('to_date');
		
		if($ff_from_date!="")
		{
			$this->session->set_userdata('ff_from_date',$ff_from_date);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('ff_from_date',$ff_from_date);
			}else{
				if($this->session->userdata('ff_from_date')=="")
				{
					$this->session->set_userdata('ff_from_date',date('Y-m-d'));
				}
			}
		}
		if($ff_to_date!="")
		{
			$this->session->set_userdata('ff_to_date',$ff_to_date);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('ff_to_date',$ff_to_date);
			}
			else{
				if($this->session->userdata('ff_to_date')=="")
				{
					$this->session->set_userdata('ff_to_date',date('Y-m-d'));
				}
			}
		}
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
		
		$data['from_date'] = $from_date = $this->session->userdata('ff_from_date');
		$data['to_date'] = $to_date = $this->session->userdata('ff_to_date');
		$data['username'] = $username = $this->session->userdata('fusername');
		
		$chk = $this->common_model->getsingle('user_master',array('user_name'=>$username));
		$user_downs = "";
		if($chk)
		{
			/*$my_reports_new = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$chk->user_master_id));		
			
			if(count($my_reports_new)>0)
			{
				foreach($my_reports_new as $ids)
				{
					$user_downs[] = $ids->user_master_id;
					if($this->session->userdata('user_type')=='2')
					{
						$my_reports2_new = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
						if(count($my_reports2_new)>0)
						{
							foreach($my_reports2_new as $ids2)
							{
								$user_downs[] = $ids2->user_master_id;
							}
						}
					}
				}
			}*/		
			$user_downs = $chk->user_master_id;
			//$user_downs[] = $this->session->userdata('user_master_id');
		} 
				
		$config = array();
		$config["base_url"] = base_url() ."/points/index";
		$total_row = $this->common_model->record_count_transaction_new($from_date,$to_date,$user_downs);				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_transaction_new($config["per_page"],$page,$from_date,$to_date,$user_downs);
//echo "<pre>"; print_r($data['transactions']);die;
		$data['user_master'] = $this->common_model->getAllrecord('user_master');
		$data['main_content'] = 'index';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function get_balance()
	{
		$user_master_id 		= trim($_POST['to_user_master_id']);
		$getcurrent_balance 	= $this->common_model->getcurrent_balance($user_master_id);
		echo trim($getcurrent_balance);
	}

	public function get_users()
	{
		$user_type 			= trim($_POST['user_type']);
		$user_master_id 	= $this->session->userdata('user_master_id');
		$mydata 			= $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		
		$users ="";
		//if you admin
		if($this->session->userdata('user_type')=='3'){
			$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'2','is_user_deleted'=>'0'));
		}
		
		//if you distributer
		if($this->session->userdata('user_type')=='2'){
			if($user_type==3)
			{
				$users = $this->common_model->getAllwhere('user_master',array('user_type'=>'3','is_user_deleted'=>'0'));
			}else{
				$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'1','is_user_deleted'=>'0'));
			}
		
		}
		
		//if you Retails
		if($this->session->userdata('user_type')=='1'){
			if($user_type==2)
			{
				$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$mydata->reporting_user_master_id,'user_type'=>'2','is_user_deleted'=>'0'));
			}else{
				$users = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id, 'user_type'=>'0','is_user_deleted'=>'0'));
			}
		
		}
		
		//if you Player
		if($this->session->userdata('user_type')=='0'){			
				$users = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$mydata->reporting_user_master_id,'user_type'=>'1','is_user_deleted'=>'0'));			
		}
				
		$html = '<option value="">Select Client</option>';
		if($users!=""){ 
			foreach($users as $us){ 
				$html .= '<option  value="'.$us->user_master_id.'" > '.$us->user_name.' ( '.$us->name.' ) </option>';
			}								
		} 
		echo $html;
	}
	
	public function chk_password($point_password)
	{
		$user_master_id 		= $this->session->userdata('user_master_id');
		$chk = $this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id,'point_password'=>$point_password));
		if (!$chk) {
			$this->form_validation->set_message('chk_password', 'You entered Coin password not match.');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function transfer()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		
		$data['msg'] = $this->session->flashdata('msg');
		
		if($this->session->userdata('point_password')=="")
		{
			$point_password 	= $this->input->post('point_password',TRUE);			
			
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('point_password', 'Coin Password', 'trim|required|callback_chk_password');
			if($this->form_validation->run() == TRUE) 
			{
				$this->session->set_userdata('point_password',$point_password);
				$this->session->set_flashdata("msg","<font class='success'>Coin Password Match Successfully.</font>");
				redirect('points/transfer');
			}
		}
		else
		{			
			$data['user_master_id'] = $user_master_id 		= $this->session->userdata('user_master_id');
			$data['balance'] 		= $getcurrent_balance 	= $this->common_model->getcurrent_balance($user_master_id);
			
			$user_type 					= $this->input->post('user_type',TRUE);
			$to_user_master_id 			= $this->input->post('to_user_master_id',TRUE);
			$transfer_points 			= $this->input->post('transfer_points',TRUE);
			
			$data['to_c_balance'] 		= $to_c_balance 	= $this->common_model->getcurrent_balance($to_user_master_id);
			
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('user_type', 'Role', 'trim|required');
			$this->form_validation->set_rules('to_user_master_id', 'Head', 'trim|required');
			$this->form_validation->set_rules('transfer_points', 'Transfer Points', 'trim|required');
					
			$tr_no = $this->common_model->getheighest_no();
			
			if($this->form_validation->run() == TRUE) 
			{
				$opening_points 	= $getcurrent_balance;
				$closing_points		= $getcurrent_balance - $transfer_points;			
				// point transaction 
				$insdata = array(
						'transactions_date' 		=> date('Y-m-d'),						
						'from_user_master_id' 		=> $user_master_id,
						'to_user_master_id' 		=> $to_user_master_id,
						'points_transferred' 		=> $transfer_points,
						'opening_points' 			=> $opening_points,
						'closing_points' 			=> $closing_points,
						'transaction_narration'		=> 'Transfer Coins',
						'transaction_type'			=> '0',
						'transaction_nature'		=> '0',
						'tr_no'						=> $tr_no,
						'user_master_id' 			=> $user_master_id,
						'created_user_master_id'	=> $user_master_id,
						'created_date' 				=> date('Y-m-d H:i:s')
				);
				$this->common_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance 	= $this->common_model->getcurrent_balance($to_user_master_id);
				$opening_points 	= $getcurrent_balance;
				$closing_points		= $getcurrent_balance + $transfer_points;			
				// point transaction 
				$insdata = array(
						'transactions_date' 		=> date('Y-m-d'),						
						'from_user_master_id' 		=> $to_user_master_id,
						'to_user_master_id' 		=> $user_master_id,
						'points_transferred' 		=> $transfer_points,
						'opening_points' 			=> $opening_points,
						'closing_points' 			=> $closing_points,
						'transaction_narration'		=> 'Withdraw Coins',
						'transaction_type'			=> '1',
						'transaction_nature'		=> '0',	
						'tr_no'						=> $tr_no,
						'user_master_id' 			=> $user_master_id,
						'created_user_master_id'	=> $user_master_id,
						'created_date' 				=> date('Y-m-d H:i:s')
				);
				$this->common_model->insertData('points_transactions',$insdata);
				
				$this->session->set_userdata('point_password','');
				$this->session->set_flashdata("msg","<font class='success'>Point Transfer Successfully.</font>");
				redirect('points');
			}
		}
		
		
		$data['main_content'] = 'transfer';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function withdraw()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')=='0'){	
			redirect('points');
		}
		$data['msg'] = $this->session->flashdata('msg');
		
		if($this->session->userdata('point_password_w')=="")
		{
			$point_password 	= $this->input->post('point_password',TRUE);			
			
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('point_password', 'Point Password', 'trim|required|callback_chk_password');
			if($this->form_validation->run() == TRUE) 
			{
				$this->session->set_userdata('point_password_w',$point_password);
				$this->session->set_flashdata("msg","<font class='success'>Point Password Match Successfully.</font>");
				redirect('points/withdraw');
			}
		}
		else
		{			
			$data['user_master_id'] = $user_master_id 		= $this->session->userdata('user_master_id');
			$data['balance'] 		= $getcurrent_balance 	= $this->common_model->getcurrent_balance($user_master_id);
			
			$to_user_master_id 			= $this->input->post('to_user_master_id',TRUE);
			$withdraw_points 			= $this->input->post('withdraw_points',TRUE);
			
			
			$this->form_validation->set_error_delimiters('', '');
			$this->form_validation->set_rules('to_user_master_id', 'Select User', 'trim|required');
			$this->form_validation->set_rules('withdraw_points', 'Withdraw Points', 'trim|required');
					
			$tr_no = $this->common_model->getheighest_no();
			
			if($this->form_validation->run() == TRUE) 
			{
				$opening_points 	= $getcurrent_balance;
				$closing_points		= $getcurrent_balance + $withdraw_points;			
				// point transaction 
				$insdata = array(
						'transactions_date' 		=> date('Y-m-d'),						
						'from_user_master_id' 		=> $user_master_id,
						'to_user_master_id' 		=> $to_user_master_id,
						'points_transferred' 		=> $withdraw_points,
						'opening_points' 			=> $opening_points,
						'closing_points' 			=> $closing_points,
						'transaction_narration'		=> 'Coins Received (Remove)',
						'transaction_type'			=> '1',
						'transaction_nature'		=> '4',
						'tr_no'						=> $tr_no,
						'user_master_id' 			=> $user_master_id,
						'created_user_master_id'	=> $user_master_id,
						'created_date' 				=> date('Y-m-d H:i:s')
				);
				$this->common_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance 	= $this->common_model->getcurrent_balance($to_user_master_id);
				$opening_points 	= $getcurrent_balance;
				$closing_points		= $getcurrent_balance - $withdraw_points;			
				// point transaction 
				$insdata = array(
						'transactions_date' 		=> date('Y-m-d'),						
						'from_user_master_id' 		=> $to_user_master_id,
						'to_user_master_id' 		=> $user_master_id,
						'points_transferred' 		=> $withdraw_points,
						'opening_points' 			=> $opening_points,
						'closing_points' 			=> $closing_points,
						'transaction_narration'		=> 'Transfer Coins',
						'transaction_type'			=> '0',
						'transaction_nature'		=> '4',	
						'tr_no'						=> $tr_no,
						'user_master_id' 			=> $user_master_id,
						'created_user_master_id'	=> $user_master_id,
						'created_date' 				=> date('Y-m-d H:i:s')
				);
				$this->common_model->insertData('points_transactions',$insdata);
				
				$this->session->set_userdata('point_password_w','');
				$this->session->set_flashdata("msg","<font class='success'>Point Withdraw Successfully.</font>");
				redirect('points');
			}
		}
		
		
		$data['main_content'] = 'withdraw';
		$this->load->view('includes/template',$data);
		            
	}
	
}	

?>	