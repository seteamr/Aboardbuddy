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
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		$data['players']=$this->common_model->countAllData('user_master',array('is_user_deleted'=>'0','user_type'=>'0'));
		$data['distributer']=$this->common_model->countAllData('user_master',array('is_user_deleted'=>'0','user_type'=>'2'));
		
		$win=$this->common_model->winning_count_data_for_admin();
		
		if($win[0]->total!=''){
			$data['winning']=$win[0]->total;
		}else{
			$data['winning']=0;
		}
		
		$sale=$this->common_model->sale_count_data_for_admin();
		if($sale[0]->total!=''){
			$data['sales']=$sale[0]->total;
		}else{
			$data['sales']=0;
		}

		$can=$this->common_model->cancle_count_data_for_admin();
		if($can[0]->total!=''){
			$data['cancle']=$can[0]->total;
		}else{
			$data['cancle']=0;
		}
		
		$bonus=$this->common_model->bonus_count_data_for_admin();
		if($bonus[0]->total!=''){
			$data['bonus']=$bonus[0]->total;
		}else{
			$data['bonus']=0;
		}
		
		$distributerrrs=$this->common_model->getAllwhere_field('user_master',array('is_user_deleted'=>'0','user_type'=>'2'),'user_master_id,user_name');
		$data['distributersss']=$distributersss=$this->common_model->getusers_by_type('2');
		$data['retailers']=$retailers=$this->common_model->getusers_by_type('1');
		$data['playersss']= $playersss = $this->common_model->getusers_by_type('0');
		
		$retailers_new=$this->common_model->getusers_by_type_new('1');
		$playersss_new = $this->common_model->getusers_by_type_new('0');
		
		
		$dis_array = "";
		if(count($distributerrrs)>0)
		{
			$dis_array =array();
			foreach($distributerrrs as $d)
			{
				$dis_array[] = $d->user_master_id;
			}
		}		
		$data['total_commision_d'] = $this->common_model->total_comission_data_new($dis_array);
		
		$ret_array = "";
		if(count($retailers_new)>0)
		{
			$ret_array =array();
			foreach($retailers_new as $r)
			{
				$ret_array[] = $r->user_master_id;
			}
		}		
		$data['total_commision_r'] = $this->common_model->total_comission_data_new($ret_array);
		
		$play_array = "";
		if(count($playersss_new)>0)
		{
			$play_array =array();
			foreach($playersss_new as $r)
			{
				$play_array[] = $r->user_master_id;
			}
		}
		
		$currentbalances_d = $this->common_model->all_users_current_balance($dis_array);		
		if($currentbalances_d[0]['balance']!="")
		{
			$data['current_balances_d'] = $currentbalances_d[0]['balance'];
		}else{
			$data['current_balances_d'] =0;
		}
		
		$currentbalances_r = $this->common_model->all_users_current_balance($ret_array);		
		if($currentbalances_r[0]['balance']!="")
		{
			$data['current_balances_r'] = $currentbalances_r[0]['balance'];
		}else{
			$data['current_balances_r'] =0;
		}
		
		$currentbalances_p = $this->common_model->all_users_current_balance($play_array);		
		if($currentbalances_p[0]['balance']!="")
		{
			$data['current_balances_p'] = $currentbalances_p[0]['balance'];
		}else{
			$data['current_balances_p'] =0;
		}
			//echo "<pre>"; print_r($data);die;	
		/*
		$category = array();
		$dataPoints1 = array();
		$dataPoints2 = array();
		$dataPoints3 = array();

		for ($i = 0; $i < 12; $i++) {

			$lastyeardate = date("Y-m-d", strtotime( date( 'Y-m-01' )." -11 months"));
    		$yearmonth = date("Y-m", strtotime( $lastyeardate." +$i months"));
    		$fromdate =  $yearmonth."-"."01";
			$lastd = date("t", strtotime($monthstart));
			$todate = $yearmonth.'-'.$lastd;
			$ab['label'] = date('M Y',strtotime($yearmonth));
			$category[]=$ab;

			$sale_count_for_admin = $this->common_model->sale_count_for_admin($fromdate,$todate);
			if($sale_count_for_admin[0]->total!="")
			{
				$total_sale = $sale_count_for_admin[0]->total;
			}else{
				$total_sale =0;
			}
			
			$mm['value'] 	 = $total_sale;
			$dataPoints1[] = $mm;

			$winning_count_for_admin = $this->common_model->winning_count_for_admin($fromdate,$todate);

			if($winning_count_for_admin[0]->total!="")
			{
				$total_winning = $winning_count_for_admin[0]->total;
			}else{
				$total_winning =0;
			}
			
			$ss['value'] 	 = $total_winning;
			$dataPoints2[] = $ss;

			$cancle_count_for_admin = $this->common_model->cancle_count_for_admin($fromdate,$todate);
			if($cancle_count_for_admin[0]->total!="")
			{
				$total_cancle = $cancle_count_for_admin[0]->total;
			}else{
				$total_cancle =0;
			}

			$dd['value'] 	 = $total_cancle;
			$dataPoints3[] = $dd;

		}
		$data['category']=$category;
		$data['dataPoints1']=$dataPoints1;
		$data['dataPoints2']=$dataPoints2;
		$data['dataPoints3']=$dataPoints3;

		$dataset1=array();
		$dataset2=array();
		$dataset3=array();
		
		
		for($i=0; $i<10; $i++)
		{
			$predate30=date('Y-m-d ', strtotime('-10 days', strtotime(date('Y-m-d'))));
			$date = date('Y-m-d ', strtotime('+'.$i.' days', strtotime($predate30)));
			$day=date('d',strtotime($date));
		    $saleday_count_for_admin = $this->common_model->saleday_count_for_admin($date);
		    if($saleday_count_for_admin[0]->total!="")
			{
				$total_sale_day_wise = $saleday_count_for_admin[0]->total;
			}else{
				$total_sale_day_wise =0;
			}
			$aa['x'] 	 = $day;
			$aa['y'] 	 = $total_sale_day_wise;
			$dataset1[]  = $aa;

			$winningday_count_for_admin = $this->common_model->winningday_count_for_admin($date);
		    if($winningday_count_for_admin[0]->total!="")
			{
				$total_winning_day_wise = $winningday_count_for_admin[0]->total;
			}else{
				$total_winning_day_wise =0;
			}
			$bb['x'] 	 = $day;
			$bb['y'] 	 = $total_winning_day_wise;
			$dataset2[]  = $bb;

			$cancleday_count_for_admin = $this->common_model->cancleday_count_for_admin($date);
		    if($cancleday_count_for_admin[0]->total!="")
			{
				$total_cancle_day_wise = $cancleday_count_for_admin[0]->total;
			}else{
				$total_cancle_day_wise =0;
			}
			$cc['x'] 	 = $day;
			$cc['y'] 	 = $total_cancle_day_wise;
			$dataset3[]  = $cc;
		}
		//print_r($dataset1);die;
		$data['dataset1']=$dataset1;
		$data['dataset2']=$dataset2;
		$data['dataset3']=$dataset3;
		// echo "<pre>";
		// print_r($data);die; */
		$data['msg'] = $this->session->flashdata('msg');		
		$data['main_content'] = 'dashboard';
		$this->load->view('includes/template',$data);
		            
	}
	
	public function setting()
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		$data['msg'] = $this->session->flashdata('msg');		
		
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_rules('cron', 'Current Cron Schedular', 'trim|required');
		$this->form_validation->set_rules('distribute_per', 'Cron Schedular Distribution %', 'trim|required');
		$this->form_validation->set_rules('returning_history_records', 'Cron Schedular Distribution %', 'trim|required');
		$this->form_validation->set_rules('cancle_per_day_limit', 'Cancle Transaction Per Day Limit', 'trim|required');
		$this->form_validation->set_rules('claim_before_day', 'Claim After days not allowed', 'trim|required');
		$this->form_validation->set_rules('manual_result_after_auto', 'If Set Manual result and not declare after minit auto Current Cron Schedular call', 'trim|required');
		$this->form_validation->set_rules('welcome', 'Welcome Message', 'trim|required');
		$this->form_validation->set_rules('result_delay_time', 'Result Display time delay', 'trim|required');
		
		$result_delay_time = $this->input->post('result_delay_time');
		$cron = $this->input->post('cron');
		$returning_history_records = $this->input->post('returning_history_records');
		$distribute_per = $this->input->post('distribute_per');
		$cancle_per_day_limit = $this->input->post('cancle_per_day_limit');
		$claim_before_day = $this->input->post('claim_before_day');
		$manual_result_after_auto = $this->input->post('manual_result_after_auto');
		
		$welcome = $this->input->post('welcome');
		
		if($this->form_validation->run() == TRUE) 
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
						
			
			$updateArray=array(
				'cron'					=> $cron,
				'returning_history_records'		=> $returning_history_records,
				'distribute_per'		=> $distribute_per,
				'cancle_per_day_limit'	=> $cancle_per_day_limit,
				'claim_before_day'		=> $claim_before_day,
				'manual_result_after_auto'=>$manual_result_after_auto,
				'result_delay_time'		=> $result_delay_time
			);
			$this->common_model->updateData('setting',$updateArray,array('id'=>'1'));
			
			$logiinsdata = array(
				'date'				=> date('Y-m-d'),
				'date_time'			=> date('Y-m-d H:i:s'),
				'ip'				=> $ipaddress,
				'data'				=> json_encode($updateArray)
			);
			$this->common_model->insertData('setting_history',$logiinsdata);
			
			$this->common_model->updateData('welcome',array('description'=>$welcome),array('welcome_id'=>'1'));
			
			$this->session->set_flashdata("msg","<font class='success'>General Setting Update Successfully.</font>");
			redirect('admin/setting');
		}
		$data['setting'] = $setting = $this->common_model->getsingle('setting',array('id'=>'1'));
		$data['welcome'] = $welcome = $this->common_model->getsingle('welcome',array('welcome_id'=>'1'));
		
		$setting = $this->common_model->getsingle('setting',array('id'=>'1'));
				
		$data['main_content'] = 'setting';
		$this->load->view('includes/template',$data);
		
	}
	public function results()
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		
		$draw_master_id 	= $this->input->post('draw_master_id',TRUE);
		$this->form_validation->set_error_delimiters('', '');
		$this->form_validation->set_message('is_unique','Draw Already Added.');
		$this->form_validation->set_rules('draw_master_id', 'Select Draw', 'trim|required|is_unique[check_cron.draw_master_id]');
		if($this->form_validation->run() == TRUE) 
		{
			$draw_master = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));
			$setting = $this->common_model->getsingle('setting',array('id'=>'1'));
			
			$fentime = date('H:i',strtotime($draw_master->draw_end_time));
			
			$ctime = strtotime(date('Y-m-d H:i:s'));
			$expiry_date_time =  date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':00 +'.$setting->manual_result_after_auto.' minutes')); 
			$date_time = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':00 +1 seconds')); 
			
			$insertArray = array(
				'draw_master_id' 	=> $draw_master_id,	
				'date_time'			=> $date_time,
				'entry_date' 		=> date('Y-m-d'),
				'expiry_date_time'  => $expiry_date_time
				);
			$insert_id = $this->common_model->insertData('check_cron',$insertArray);		
			$this->session->set_flashdata("msg","<font class='success'>Draw Added Successfully.</font>");
			redirect('admin/results');
		}
		
		$data['records'] = $this->common_model->getAllwhere('draw_master',array('is_draw_active'=>'0'));
		
		$data['results'] = $this->common_model->getAllwhere('check_cron',array());
		
		$data['msg'] = $this->session->flashdata('msg');
		$data['error_msg'] = $this->session->flashdata('error_msg');

		
		$data['main_content'] = 'results';
		$this->load->view('includes/template',$data);
		
	}
	
	public function generate_results($draw_master_id)
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		$result_date = date('Y-m-d');
		
		$draw = $this->common_model->getsingle('check_cron',array('draw_master_id'=>$draw_master_id));
		$draw_master = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));							
		
		$data['draw_master_id'] = $draw_master_id;
		$data['series_master_id'] = $series_master_id = $this->input->post('series_master_id');
		$data['bajar_master_id'] = $bajar_master_id = $this->input->post('bajar_master_id');
		
		$fentime = strtotime($draw->expiry_date_time);
		$ctime = strtotime(date('Y-m-d H:i:s'));
		
		$data['records'] = "";
		
		$this->form_validation->set_rules('series_master_id', 'Series', 'trim|required');
		if($this->form_validation->run() == TRUE) 
		{
			if( ($ctime >= $fentime) OR !$draw)					
			{
				$this->session->set_flashdata("error_msg","<font class='success'>Draw Expires Time.</font>");
				redirect('admin/results');
			}
			else 
			{ 
								
					$this->common_model->deleteData('temp_result_master',array('result_date <'=>date('Y-m-d')));
					
					$setting = $this->common_model->getsingle('setting',array('id'=>'1'));
					
					
					$this->form_validation->set_error_delimiters('', '');
					$this->form_validation->set_rules('series_master_id', 'Series', 'trim|required');
					$this->form_validation->set_rules('bajar_master_id', 'Bajar', 'trim');
					
					if($this->form_validation->run() == TRUE) 
					{
						$fffsql ="SELECT		result_date
							,			draw_master_id
							,			series_master_id
							,			bajar_master_id
							,			bid_akada_number
							,			SUM(bid_units*bid_points_multiplier*bid_points) AS bid_points
							,			SUM(bid_units*bid_points_multiplier*bid_points)*90 AS winning_points
							,			ROUND((
												SELECT	SUM(bid_units*bid_points_multiplier*bid_points)
												FROM	draw_transaction_details dtd1
												WHERE	dtd1.result_date = draw_transaction_details.result_date
												AND		dtd1.draw_master_id = draw_transaction_details.draw_master_id
												AND		dtd1.is_deleted='0'
										) * ".$setting->distribute_per."/100,0) AS total_bid
							,			ROUND(SUM(bid_units*bid_points_multiplier*bid_points)*90/(
												SELECT	SUM(bid_units*bid_points_multiplier*bid_points)
												FROM	draw_transaction_details dtd1
												WHERE	dtd1.result_date = draw_transaction_details.result_date
												AND		dtd1.draw_master_id = draw_transaction_details.draw_master_id
												AND		dtd1.is_deleted='0'
										) * ".$setting->distribute_per."/100,2)*100 AS winning_percent
							,			COUNT(draw_transaction_master_id) AS number_of_bids
							,			COUNT(DISTINCT user_master_id) AS number_of_users
							FROM		draw_transaction_details
							WHERE		result_date =  '".$result_date."'
							AND			draw_master_id = '".$draw_master_id."' 
							AND			series_master_id = '".$series_master_id."' ";
							if($bajar_master_id!="")
							{
								$fffsql .="	AND	bajar_master_id	= '".$bajar_master_id."' ";
							}
							$fffsql .=" AND	is_deleted = 0
							AND			is_winning = 0
							GROUP BY 	result_date
							,			draw_master_id
							,			series_master_id
							,			bajar_master_id
							,			bid_akada_number
							ORDER BY 	series_master_id,bajar_master_id,bid_akada_number ";
						$pppqqq = $this->db->query($fffsql);
						$fffresults = $pppqqq->result();
					
					$data['records'] = $fffresults;
					
					
					}
			} 
		}
		//echo "<pre>"; print_r($data['records']);die;
		
		$data['msg'] = $this->session->flashdata('msg');		
		$data['main_content'] = 'generate_results';
		$this->load->view('includes/template',$data);
	}
	
	public function declare_result($draw_master_id)
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		$result_date = date('Y-m-d');
		
		$draw = $this->common_model->getsingle('check_cron',array('draw_master_id'=>$draw_master_id));
		$draw_master = $this->common_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));							
									
		$fentime = strtotime($draw->expiry_date_time);
		$ctime = strtotime(date('Y-m-d H:i:s'));
		
		if( ($ctime >= $fentime) OR !$draw)		
		{
			$this->session->set_flashdata("error_msg","<font class='success'>Draw Expires Time.</font>");
			redirect('admin/results');
		}else{ 
			
			foreach($_POST as $key=>$value)
			{
				$series_bajar = explode('_',$key);
				$series_master_id = $series_bajar[0];
				$bajar_master_id = $series_bajar[1];
				$bid_akada_number = $value;
				
				$this->common_model->deleteData('temp_result_master',array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id));
				//$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'0'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
					$ins_data = array(
						'result_date' 	 	=> $result_date,
						'draw_master_id' 	=> $draw_master_id,
						'series_master_id' 	=> $series_master_id,
						'bajar_master_id' 	=> $bajar_master_id,
						'bid_akada_number' 	=> $bid_akada_number,
						'type_of_draw'		=> '1',
						'winning'			=> '0'
					);
					$this->common_model->insertData('temp_result_master',$ins_data);
				//	$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
					
			}
			
			$this->session->set_flashdata("msg","<font class='success'>Result Manual Added Successfully.</font>");
			redirect('admin/results'); 
		}			
		
		
	}
	
	public function offlist()
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		
		$off_date = $this->input->post('off_date',TRUE);
		if($_POST)
		{
			$off_date = date('Y-m-d',strtotime($off_date));
			$chk = $this->common_model->getsingle('offlist',array('off_date'=>$off_date));
			if(!$chk)
			{				
				$this->common_model->insertData('offlist',array('off_date'=>$off_date,'entry_date'=> date('Y-m-d')));
				$this->session->set_flashdata("msg","<font class='success'>Off Date Added Successfully.</font>");
				redirect('admin/offlist');
			}
		}
		
		$config = array();
		$config["base_url"] = base_url() ."admin/offlist";
		$total_row = $this->common_model->record_count_off('offlist');				
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
		$data['list'] = $this->common_model->getAllwhere_pagination_off('offlist',$config["per_page"],$page);
		
		$data['msg'] = $this->session->flashdata('msg');		
		$data['main_content'] = 'offlist';
		$this->load->view('includes/template',$data);
		
	}
	
	public function delete_offdate($id)
	{
		if($this->session->userdata('user_type')!='3')
		{
			redirect('login');
		}
		
		$chk = $this->common_model->getsingle('offlist',array('id'=>$id));
		if($chk)
		{
			$this->common_model->deleteData('offlist',array('id'=>$id));
			$this->session->set_flashdata("msg","<font class='success'>Off Date Deleted Successfully.</font>");
			redirect('admin/offlist');
		}else{
			$this->session->set_flashdata("msg","<font class='success'>Record Not Found.</font>");
			redirect('admin/offlist');
		}
	}
	
	
}	

?>	