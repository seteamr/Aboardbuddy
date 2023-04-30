<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distributor extends CI_Controller {

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
		if($this->session->userdata('user_type')!='2')
		{
			redirect('login');
		}
		
		$my_id = $this->session->userdata('user_master_id');
		$retailer = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
		
		$my_downs =array();
		$my_array = array();
		$ret_array ="";
		$play_array ="";
		$tot_retailer=0;
		$tot_player=0;
		$my_players = array();
		if(count($retailer)>0)
		{
			$ret_array =array();
			foreach($retailer as $ids)
			{
				$tot_retailer++;
				$my_downs[] = $ids->user_master_id;	
				$ret_array[] = $ids->user_master_id;
				
				$player = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
				if(count($player)>0)
				{	if($play_array=="")
					{
						$play_array = array();
					}
					foreach($player as $ids2)
					{
						$tot_player++;
						$my_downs[] = $ids2->user_master_id;
						$my_players[] = $ids2->user_master_id;
						$play_array[] = $ids2->user_master_id;
					}
				}
			
			}
		}		
		$my_downs[] = $my_id;
		$my_array[] = $my_id;
		
		$dis_array =array();
		$dis_array[] = $my_id;
		
		// print_r($my_downs);die;
		$data['tot_player']=$tot_player;
		$data['tot_retailer']=$tot_retailer;

		$win=$this->common_model->winning_count_data_for_admin($my_downs);
		if($win[0]->total!=''){
			$data['winning']=$win[0]->total;
		}else{
			$data['winning']=0;
		}
		
		$sale=$this->common_model->sale_count_data_for_admin($my_downs);
		if($sale[0]->total!=''){
			$data['sales']=$sale[0]->total;
		}else{
			$data['sales']=0;
		}

		$can=$this->common_model->cancle_count_data_for_admin($my_downs);
		if($can[0]->total!=''){
			$data['cancle']=$can[0]->total;
		}else{
			$data['cancle']=0;
		}
		
		$bonus=$this->common_model->bonus_count_data_for_admin($my_downs);
		if($bonus[0]->total!=''){
			$data['bonus']=$bonus[0]->total;
		}else{
			$data['bonus']=0;
		}
		
		$data['total_commision_d'] = $this->common_model->total_comission_data_new($dis_array);
		$data['total_commision_r'] = $this->common_model->total_comission_data_new($ret_array);
		
		$currentbalances_d = $this->common_model->all_users_current_balance($my_array);		
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
		
		$data['retailers']=$retailers= $this->common_model->getusers_by_type('1',$my_id);
		$data['players']=$players=$this->common_model->getusers_by_type_in('0',$my_players);
		
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

			$sale_count_for_admin = $this->common_model->sale_count_for_admin($fromdate,$todate,$my_downs);
			if($sale_count_for_admin[0]->total!="")
			{
				$total_sale = $sale_count_for_admin[0]->total;
			}else{
				$total_sale =0;
			}
			
			$mm['value'] 	 = $total_sale;
			$dataPoints1[] = $mm;

			$winning_count_for_admin = $this->common_model->winning_count_for_admin($fromdate,$todate,$my_downs);

			if($winning_count_for_admin[0]->total!="")
			{
				$total_winning = $winning_count_for_admin[0]->total;
			}else{
				$total_winning =0;
			}
			
			$ss['value'] 	 = $total_winning;
			$dataPoints2[] = $ss;

			$cancle_count_for_admin = $this->common_model->cancle_count_for_admin($fromdate,$todate,$my_downs);
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
		    $saleday_count_for_admin = $this->common_model->saleday_count_for_admin($date,$my_downs);
		    if($saleday_count_for_admin[0]->total!="")
			{
				$total_sale_day_wise = $saleday_count_for_admin[0]->total;
			}else{
				$total_sale_day_wise =0;
			}
			$aa['x'] 	 = $day;
			$aa['y'] 	 = $total_sale_day_wise;
			$dataset1[]  = $aa;

			$winningday_count_for_admin = $this->common_model->winningday_count_for_admin($date,$my_downs);
		    if($winningday_count_for_admin[0]->total!="")
			{
				$total_winning_day_wise = $winningday_count_for_admin[0]->total;
			}else{
				$total_winning_day_wise =0;
			}
			$bb['x'] 	 = $day;
			$bb['y'] 	 = $total_winning_day_wise;
			$dataset2[]  = $bb;

			$cancleday_count_for_admin = $this->common_model->cancleday_count_for_admin($date,$my_downs);
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
		*/
		$data['msg'] = $this->session->flashdata('msg');		
		$data['main_content'] = 'dashboard';
		$this->load->view('includes/template',$data);
		            
	}
}	

?>	