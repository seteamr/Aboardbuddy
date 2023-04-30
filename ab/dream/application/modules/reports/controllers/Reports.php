<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta');
		$this->load->model('wb_model');			
	}
	
	public function index()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login/logout');
		}
		redirect('reports/transactions');
	}
	
	public function gettransaction_user()
	{		
		$from_user_master_id = trim($_POST['from_user_master_id']);
		
		$users = $this->common_model->gettransaction_user($from_user_master_id);		
		$html = '<option value="">Select To Name</option>';
		if($users!=""){ 
			foreach($users as $us){ 
				$html .= '<option  value="'.$us->user_master_id.'" > '.$us->user_name.' ( '.$us->name.' ) </option>';
			}								
		} 
		echo $html;
	}
	
	public function transactions()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login/logout');
		}

		
		$data['msg'] = $this->session->flashdata('msg');
		
		$ff_from_date = $this->input->post('from_date');
		$ff_to_date = $this->input->post('to_date');
		$ffrom_user_master_id = $this->input->post('from_user_master_id');
		$fto_user_master_id = $this->input->post('to_user_master_id');
		$ftransaction_nature = $this->input->post('transaction_nature');
		if($ff_from_date!="")
		{
			$this->session->set_userdata('ff_from_date',$ff_from_date);
			$this->session->set_userdata('ff_to_date',$ff_to_date);
			$this->session->set_userdata('ffrom_user_master_id',$ffrom_user_master_id);
			$this->session->set_userdata('fto_user_master_id',$fto_user_master_id);
			$this->session->set_userdata('ftransaction_nature',$ftransaction_nature);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('ff_from_date',$ff_from_date);
				$this->session->set_userdata('ff_to_date',$ff_to_date);
				$this->session->set_userdata('ffrom_user_master_id',$ffrom_user_master_id);
				$this->session->set_userdata('fto_user_master_id',$fto_user_master_id);
				$this->session->set_userdata('ftransaction_nature',$ftransaction_nature);
			}
		}
		
		$data['from_date'] = $from_date = $this->session->userdata('ff_from_date');
		$data['to_date'] = $to_date = $this->session->userdata('ff_to_date');
		$data['from_user_master_id'] = $from_user_master_id = $this->session->userdata('ffrom_user_master_id');
		$data['to_user_master_id'] = $to_user_master_id = $this->session->userdata('fto_user_master_id');
		$data['transaction_nature'] = $transaction_nature = $this->session->userdata('ftransaction_nature');

		$config = array();
		$config["base_url"] = base_url() ."/reports/transactions";
		$total_row = $this->common_model->record_count_transaction_all($from_date,$to_date,$from_user_master_id,$to_user_master_id,$transaction_nature);				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_transaction_all($config["per_page"],$page,$from_date,$to_date,$from_user_master_id,$to_user_master_id,$transaction_nature);

		$data['user_master'] = $this->common_model->getAllwhere('user_master',array());
		$data['main_content'] = 'transactions';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function point_managment()
	{
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login/logout');
		}

		
		$data['msg'] = $this->session->flashdata('msg');
		
		$ff_from_date = $this->input->post('from_date');
		$ff_to_date = $this->input->post('to_date');
		$ffrom_user_master_id = $this->input->post('from_user_master_id');
		$fto_user_master_id = $this->input->post('to_user_master_id');
		$ftransaction_nature = $this->input->post('transaction_nature');
		if($ff_from_date!="")
		{
			$this->session->set_userdata('ff_from_date',$ff_from_date);
			$this->session->set_userdata('ff_to_date',$ff_to_date);
			$this->session->set_userdata('ffrom_user_master_id',$ffrom_user_master_id);
			$this->session->set_userdata('fto_user_master_id',$fto_user_master_id);
			$this->session->set_userdata('ftransaction_nature',$ftransaction_nature);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('ff_from_date',$ff_from_date);
				$this->session->set_userdata('ff_to_date',$ff_to_date);
				$this->session->set_userdata('ffrom_user_master_id',$ffrom_user_master_id);
				$this->session->set_userdata('fto_user_master_id',$fto_user_master_id);
				$this->session->set_userdata('ftransaction_nature',$ftransaction_nature);
			}
		}
		
		$data['from_date'] = $from_date = $this->session->userdata('ff_from_date');
		$data['to_date'] = $to_date = $this->session->userdata('ff_to_date');
		$data['from_user_master_id'] = $from_user_master_id = $this->session->userdata('ffrom_user_master_id');
		$data['to_user_master_id'] = $to_user_master_id = $this->session->userdata('fto_user_master_id');
		$data['transaction_nature'] = $transaction_nature = $this->session->userdata('ftransaction_nature');

		$config = array();
		$config["base_url"] = base_url() ."/reports/point_managment";
		$total_row = $this->common_model->record_count_point_transaction_all($from_date,$to_date,$from_user_master_id,$to_user_master_id,$transaction_nature);				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_poin_transaction_all($config["per_page"],$page,$from_date,$to_date,$from_user_master_id,$to_user_master_id,$transaction_nature);

		$data['user_master'] = $this->common_model->getAllwhere('user_master',array());
		$data['main_content'] = 'point_managment';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function commission()
	{		
		if($this->session->userdata('user_type')!='1' && $this->session->userdata('user_type')!='2')
		{
			redirect('reports');
		}

		
		$data['msg'] = $this->session->flashdata('msg');
		
		$fffrom_to_date = $this->input->post('from_to_date');
		if($fffrom_to_date!="")
		{
			$this->session->set_userdata('fffrom_to_date',$fffrom_to_date);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('fffrom_to_date',$fffrom_to_date);
			}
		}
		
		$data['from_to_date'] = $from_to_date = $this->session->userdata('fffrom_to_date');
		$from_user_master_id = $this->session->userdata('user_master_id');

		$config = array();
		$config["base_url"] = base_url() ."/reports/commission";
		$total_row = $this->common_model->record_count_transaction_all($from_to_date,$from_user_master_id,'','1');				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_transaction_all($config["per_page"],$page,$from_to_date,$from_user_master_id,'','1');

		$data['main_content'] = 'commission';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function play_history()
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
		
		$fdraw_type = $this->input->post('draw_type');		
		if($fdraw_type!="")
		{
			$this->session->set_userdata('fdraw_type',$fdraw_type);
		}else{
			if($_POST)
			{ 
				$this->session->set_userdata('fdraw_type',$fdraw_type);
			}
		}
		
		$data['from_date'] = $from_date = $this->session->userdata('ff_from_date');
		$data['to_date'] = $to_date = $this->session->userdata('ff_to_date');
		$data['username'] = $username = $this->session->userdata('fusername');
		if($this->session->userdata('fdraw_type')=="")
		{
			$this->session->set_userdata('fdraw_type','All');
		}
		
		$data['draw_type'] = $draw_type = $this->session->userdata('fdraw_type');
		
		$my_id = $this->session->userdata('user_master_id');
		$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		
		$my_downs =array();
		if(count($my_reports)>0)
		{
			foreach($my_reports as $ids)
			{
				$my_downs[] = $ids->user_master_id;
				if($this->session->userdata('user_type')=='2')
				{
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
				}
			}
		}		
		$my_downs[] = $my_id;
		
		$config = array();
		$config["base_url"] = base_url() ."/reports/play_history";
		$total_row = $this->common_model->record_count_play_history($username,$from_date,$to_date,$my_downs,$draw_type);				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_play_history($config["per_page"],$page,$username,$from_date,$to_date,$my_downs,$draw_type);

		$data['main_content'] = 'play_history';
		$this->load->view('includes/template',$data);
		            
	}

	public function view_details($user_master_id){
		//take the last monday
		if(date('D')!='Mon')
		{    
		  	$current_fromdate = date('Y-m-d',strtotime('last Monday'));  
		}else{
		    $current_fromdate = date('Y-m-d');   
		}
		//always next saturday
		if(date('D')!='Sat')
		{
		    $current_todate = date('Y-m-d',strtotime('next Sunday'));
		}else{

		    $current_todate = date('Y-m-d');
		}
		
		$last_fromdate=date('Y-m-d',(strtotime ( '-7 day' , strtotime ( $current_fromdate) ) ));
		$last_todate=date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $current_fromdate) ) ));

		$data['user']=$this->common_model->getsingle('user_master',array('user_master_id'=>$user_master_id));

		$data['credit']=$this->common_model->getcurrent_balance($user_master_id);

		$current_week_winning=$this->common_model->total_winning_week($user_master_id,$current_fromdate,$current_todate);
		$current_week_sale=$this->common_model->total_sale_week($user_master_id,$current_fromdate,$current_todate);
		$current_week_bonus=$this->common_model->total_bonus_week($user_master_id,$current_fromdate,$current_todate);
		
		$last_week_winning=$this->common_model->total_winning_week($user_master_id,$last_fromdate,$last_todate);
		$last_week_sale=$this->common_model->total_sale_week($user_master_id,$last_fromdate,$last_todate);
		$last_week_bonus=$this->common_model->total_bonus_week($user_master_id,$last_fromdate,$last_todate);

		if($current_week_winning[0]->total=='')
		{
			$data['current_week_winning']=0;
		}
		else
		{
			$data['current_week_winning']=$current_week_winning[0]->total;
		}

		if($last_week_winning[0]->total=='')
		{
			$data['last_week_winning']=0;
		}
		else
		{
			$data['last_week_winning']=$last_week_winning[0]->total;
		}

		if($last_week_sale[0]->total=='')
		{
			$data['last_week_sale']=0;
		}
		else
		{
			$data['last_week_sale']=$last_week_sale[0]->total;
		}


		if($current_week_sale[0]->total=='')
		{
			$data['current_week_sale']=0;
		}
		else
		{
			$data['current_week_sale']=$current_week_sale[0]->total;
		}
		
		if($current_week_bonus[0]->total=='')
		{
			$data['current_week_bonus']=0;
		}
		else
		{
			$data['current_week_bonus']=$current_week_bonus[0]->total;
		}
		
		if($last_week_bonus[0]->total=='')
		{
			$data['last_week_bonus']=0;
		}
		else
		{
			$data['last_week_bonus']=$last_week_bonus[0]->total;
		}

		$data['current_week_commision']=$this->common_model->total_commision_week_new($user_master_id,$current_fromdate,$current_todate);
		$data['last_week_commision']=$this->common_model->total_commision_week_new($user_master_id,$last_fromdate,$last_todate);
		
		$data['rusers'] = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$user_master_id));
		$data['main_content'] = 'view_details';
		$this->load->view('includes/template',$data);
	}
	
	public function view($bar_code_number)
	{		
		if($this->session->userdata('user_master_id')=='')
		{
			redirect('login/logout');
		}
		/*if($this->session->userdata('user_type')=='0')
		{
			redirect('login/logout');
		}*/
		
		$data['chk_data'] = $chk_data = $this->common_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
		//echo "<pre>"; print_r($chk_data);die;
		if(!$chk_data)
		{
			redirect('login/logout');
		}
		
		$data['chk_data'] 	  = $chk_data;
		$data['transactions'] = $this->common_model->getAllwhere_view($chk_data->draw_transaction_master_id);
		$data['user_detail']=$this->common_model->getsingle('user_master',array('user_master_id'=>$chk_data->user_master_id));
		$data['draw_master_detail']=$this->common_model->getsingle('draw_master',array('draw_master_id'=>$chk_data->draw_master_id));

		$total_winning=$this->common_model->total_report_winning($chk_data->draw_transaction_master_id);
		$total_sales=$this->common_model->total_report_sales($chk_data->draw_transaction_master_id);
		$total_cancles=$this->common_model->total_report_cancles($chk_data->draw_transaction_master_id);
		
		if($total_winning[0]->total!=''){
			$data['total_winning']=$total_winning[0]->total;
		}else{
			$data['total_winning']=0;
		}
		if($total_sales[0]->total!=''){
			$data['total_sales']=$total_sales[0]->total;
		}else{
			$data['total_sales']=0;
		}
		if($total_cancles[0]->total!=''){
			$data['total_cancles']=$total_cancles[0]->total;
		}else{
			$data['total_cancles']="";
		}
		$data['main_content'] = 'view';
		$this->load->view('includes/template',$data);
		
	}
	
	public function counter_sale()
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
		
		$my_id = $this->session->userdata('user_master_id');
		$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		
		$my_downs =array();
		if(count($my_reports)>0)
		{
			foreach($my_reports as $ids)
			{
				$my_downs[] = $ids->user_master_id;
				if($this->session->userdata('user_type')=='2')
				{
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
				}
			}
		}		
		$my_downs[] = $my_id;
		
		$config = array();
		$config["base_url"] = base_url() ."/reports/counter_sale";
		$total_row = $this->common_model->record_count_counter_sale($username,$from_date,$to_date,$my_downs);				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_counter_sale($config["per_page"],$page,$username,$from_date,$to_date,$my_downs);

		$data['main_content'] = 'counter_sale';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function net_to_pay()
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
		
		$my_id = $this->session->userdata('user_master_id');
		$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		
		$my_downs =array();
		if(count($my_reports)>0)
		{
			foreach($my_reports as $ids)
			{
				$my_downs[] = $ids->user_master_id;
				if($this->session->userdata('user_type')=='2')
				{
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
				}
			}
		}		
		$my_downs[] = $my_id;
		
		if($this->session->userdata('user_type')!='3')
		{
			$user_master_id = $my_id;
		}else{
			$user_master_id = "";
		}
		
		$chksearchidss = $this->common_model->getsingle('user_master',array('user_name'=>$username));
		if($chksearchidss)
		{
			$user_master_id = $chksearchidss->user_master_id;
			if($this->session->userdata('user_type')!='3')
			{
				if(!in_array($user_master_id,$my_downs))
				{
					$user_master_id = "00";
				}
			}
		}else if($username!="" && !$chksearchidss)
		{
			$user_master_id ="000";
		}
		
		
		
		$config = array();
		$config["base_url"] = base_url() ."/reports/net_to_pay";
		$total_row = $this->common_model->record_count_counter_sale($username,$from_date,$to_date,$my_downs);				
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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_counter_sale($config["per_page"],$page,$username,$from_date,$to_date,$my_downs);
		
		//echo "<pre>"; print_r($data['transactions']); die;
		
		$data['main_content'] = 'net_to_pay';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function net_to_pay_user()
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
		
		$my_id = $this->session->userdata('user_master_id');
		$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		
		$my_downs =array();
		if(count($my_reports)>0)
		{
			foreach($my_reports as $ids)
			{
				$my_downs[] = $ids->user_master_id;
				if($this->session->userdata('user_type')=='2')
				{
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
				}
			}
		}		
		$my_downs[] = $my_id;
		
		if($this->session->userdata('user_type')!='3')
		{
			$user_master_id = $my_id;
		}else{
			$user_master_id = "";
		}
		
		$chksearchidss = $this->common_model->getsingle('user_master',array('user_name'=>$username));
		if($chksearchidss)
		{
			$user_master_id = $chksearchidss->user_master_id;
			if($this->session->userdata('user_type')!='3')
			{
				if(!in_array($user_master_id,$my_downs))
				{
					$user_master_id = "00";
				}
			}
		}else if($username!="" && !$chksearchidss)
		{
			$user_master_id ="000";
		}
		
		$data['transactions'] = $this->common_model->net_to_pay($user_master_id,$from_date,$to_date);
		
		//echo "<pre>"; print_r($data['transactions']); die;
		
		$data['main_content'] = 'net_to_pay_user';
		$this->load->view('includes/template',$data);
		            
	}

	public function bonus_history()
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
		
		$my_id = $this->session->userdata('user_master_id');
		$my_reports = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		
		$my_downs =array();
		if(count($my_reports)>0)
		{
			foreach($my_reports as $ids)
			{
				$my_downs[] = $ids->user_master_id;
				if($this->session->userdata('user_type')=='2')
				{
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
				}
			}
		}		
		$my_downs[] = $my_id;
		
		$config = array();
		$config["base_url"] = base_url() ."/reports/bonus_history";
		$total_row = $this->common_model->read_count_bonus_history($username,$from_date,$to_date,$my_downs);	

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
			
		$data['transactions'] = $this->common_model->getAllwhere_pagination_bonus_history($config["per_page"],$page,$username,$from_date,$to_date,$my_downs);
		// echo "<pre>";
	 //   print_r($data['transactions']);die;
		$data['main_content'] = 'bonus_history';
		$this->load->view('includes/template',$data);
		            
	}
	
	
}	

?>	