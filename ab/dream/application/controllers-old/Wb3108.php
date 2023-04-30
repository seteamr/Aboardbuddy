<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Wb extends REST_Controller
{
	function __construct()
    {
        // Construct our parent class
        parent::__construct(); 
		$this->load->model('wb_model');	
		date_default_timezone_set('Asia/Calcutta'); 
    }
	
	//ganerat token no.
	function test_get(){
		$response= array('status'=>'200', 'message'=>'hiii', 'data'=>null);	
        $this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function logout_post()
	{
		$user_master_id = $this->post('user_master_id');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}		
		else
		{
			$user = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));		
			if($user)
			{ 
				$this->wb_model->updateData('user_master',array('device_token'=>''),array('user_master_id'=>$user->user_master_id));
				$response= array('status'=>'200', 'message'=>'Logout Successfully.', 'data'=>null);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function login_post()
	{
		$user_name = $this->post('user_name');
		$user_password = $this->post('user_password');
		$device_token = $this->post('device_token');
		if($user_name=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if($user_password=='')
		{
			$response= array('status'=>'201', 'message'=>'Password Required.', 'data'=>null);	
		}
		else if($device_token=='')
		{
			$response= array('status'=>'201', 'message'=>'Device Token Required.', 'data'=>null);	
		}
		else
		{
			$users = $this->wb_model->login($user_name,$user_password,$device_token);
		
			if($users)
			{ 
				$user = $users[0];
				
				if($user->is_user_deleted==0)
				{
					$getcurrent_balance = $this->wb_model->getcurrent_balance($user->user_master_id);
					
					$viewdata = array(
						'user_master_id' 			=> $user->user_master_id,
						'name' 	 					=> $user->name,
						'user_name' 	 			=> $user->user_name,
						'user_type' 	 			=> $user->user_type,
						'reporting_user_master_id' 	=> $user->reporting_user_master_id,
						'user_comission_type' 		=> $user->user_comission_type,
						'user_comission' 			=> $user->user_comission,
						'device_token'				=> $device_token,
						'current_points'			=> $getcurrent_balance,
						'current_date_time'			=> date('d-M-Y H:i:s')
					);
					$this->wb_model->updateData('user_master',array('device_token'=>$device_token),array('user_master_id'=>$user->user_master_id));
					$response= array('status'=>'200', 'message'=>'Login Successfully.', 'data'=>$viewdata);
				}else{
				$response= array('status'=>'201', 'message'=>'Your account is deactivated please contact us administrator.', 'data'=>null);		
				}
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Password OR User OR Device Token Not match.', 'data'=>null);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function change_password_post()
	{
		$user_master_id = $this->post('user_master_id');
		$old_password = $this->post('old_password');
		$new_password = $this->post('new_password');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if($old_password=='')
		{
			$response= array('status'=>'201', 'message'=>'Old Password Required.', 'data'=>null);	
		}
		else if($new_password=='')
		{
			$response= array('status'=>'201', 'message'=>'New Password Required.', 'data'=>null);	
		}
		else
		{
			$users = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id,'user_password'=>$old_password));
		
			if($users)
			{ 				
				$this->wb_model->updateData('user_master',array('user_password'=>$new_password),array('user_master_id'=>$users->user_master_id));
				$response= array('status'=>'200', 'message'=>'Password Update Successfully.', 'data'=>null);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Old Password not match.', 'data'=>null);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function play_post()
	{		
		$user_master_id = $this->post('user_master_id');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		$getcurrent_balance = $this->wb_model->getcurrent_balance($user_master_id);
		
		$result_date	= Date('Y-m-d'); //$this->post('result_date');
		$draw_master_id	= $this->post('draw_master_id');
		$chkoffdate = $this->common_model->getsingle('offlist',array('off_date'=>$result_date));
		
		$currdrawmasterid = $this->wb_model->getcurrentdrawid();
		
		$is_result = $this->wb_model->getsingle('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));
		if($chkoffdate)
		{
			$response= array('status'=>'201', 'message'=>'Today is Off', 'data'=>null);	
		}
		
		if($is_result)
		{
			$response= array('status'=>'201', 'message'=>'Draw is Over, Try Again', 'data'=>null);	
		}
		
		if($draw_master_id < $currdrawmasterid)
		{
			$response= array('status'=>'201', 'message'=>'Current Draw Over/Advance Draw is not allowed. Please login again', 'data'=>null);	
		}
		
		if($draw_master_id == 0)
		{
			$response= array('status'=>'201', 'message'=>'Kubera Market Close. Please login next day between 10am to 10pm', 'data'=>null);	
		}
		
		if($draw_master_id<10)
		{
			$st1 = "0".$draw_master_id;
		}else{
			$st1 = $draw_master_id;
		}
		
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		$bar_code_number	= "KM".$st1.substr(str_shuffle($str_result), 0,6); 
		for($t=0; $t<10;$t++)
		{
			$chk_exist = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
			if($chk_exist)
			{
				$bar_code_number	= "KM".$st1.substr(str_shuffle($str_result), 0,6); 
			}else{
				$t=15;
			}
		}
		
		$ticket_type	= $this->post('ticket_type');
		$is_claim		= $this->post('is_claim');
		$play_data		= $this->post('play_data');
		
		$play_device_type	= $this->post('play_device_type');
		if($play_device_type=="")
		{
			$play_device_type = 0;
		}
		
		$totalbids = "0";
		if(count($play_data)>0)
		{
			foreach($play_data as $p)
			{
				$totalbids = $totalbids + ( $p['bid_units']*$p['bid_points']*$p['bid_points_multiplier'] );
			}
			
		}else{
			$response= array('status'=>'201', 'message'=>'Play Data Required.', 'data'=>null);	
		}
		
		if($getcurrent_balance < $totalbids)
		{
			$response= array('status'=>'201', 'message'=>'Insufficient Points.', 'data'=>null);
		}
		else if($totalbids < 10)
		{
			$response= array('status'=>'201', 'message'=>'Please Bid more then 10 Points.', 'data'=>null);
		}
		
		if($user_data->user_type!=0 and $user_data->user_type!=1)
		{
			$response= array('status'=>'201', 'message'=>'You Are Not Allowed.', 'data'=>null);
		}
		
		if($user_data->user_type==0)
		{
			$retailer_user_master_id = $user_data->reporting_user_master_id;
		}else{
			$retailer_user_master_id = $user_data->user_master_id;
		}
		
		if(!$is_result && !$chkoffdate && $user_master_id!="" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
		{
			$insert_data = array(
							'result_date' 			=> $result_date,						
							'draw_master_id' 		=> $draw_master_id,
							'bar_code_number' 		=> $bar_code_number,
							'ticket_type' 			=> $ticket_type,
							'play_device_type'		=> $play_device_type,
							'is_claim' 				=> $is_claim,
							'shop_master_id' 		=> $user_data->shop_master_id,
							'user_master_id' 		=> $user_master_id,
							'created_user_master_id'=> $user_master_id,
							'created_date' 			=> date('Y-m-d H:i:s')
					);
			$insert_id = $this->wb_model->insertData('draw_transaction_master',$insert_data);
			
			foreach($play_data as $p)
			{
				$insdata = array(
							'result_date' 					=> $result_date,						
							'draw_master_id' 				=> $draw_master_id,
							'series_master_id' 				=> $p['series_master_id'],
							'bajar_master_id' 				=> $p['bajar_master_id'],
							'bid_akada_number' 				=> $p['bid_akada_number'],
							'bid_units' 					=> $p['bid_units'],
							'bid_points' 					=> $p['bid_points'],
							'bid_points_multiplier' 		=> $p['bid_points_multiplier'],
							'shop_master_id' 				=> $user_data->shop_master_id,
							'retailer_user_master_id'		=> $retailer_user_master_id,
							'user_master_id' 				=> $user_master_id,
							'draw_transaction_master_id'	=> $insert_id,
							'created_user_master_id'		=> $user_master_id,
							'created_date' 					=> date('Y-m-d H:i:s')
					);
				$this->wb_model->insertData('draw_transaction_details',$insdata);				
			}
			
			$instempdata = array(
							'result_date' 					=> $result_date,						
							'draw_master_id' 				=> $draw_master_id,
							'series_master_id' 				=> $p['series_master_id'],
							'bajar_master_id' 				=> $p['bajar_master_id'],
							'bid_akada_number' 				=> $p['bid_akada_number'],
							'bid_units' 					=> $p['bid_units'],
							'bid_points' 					=> $p['bid_points'],
							'bid_points_multiplier' 		=> $p['bid_points_multiplier'],
							'retailer_user_master_id'		=> $retailer_user_master_id,
							'user_master_id' 				=> $user_master_id,
							'draw_transaction_master_id'	=> $insert_id
					);
			// $this->wb_model->insertData('temp_draw_transaction_details',$instempdata);
			
			$points_transferred = $totalbids;
			$opening_points 	= $getcurrent_balance;
			$closing_points		= $getcurrent_balance - $points_transferred;
			
			// point transaction 
			$insdata = array(
							'transactions_date' 		=> date('Y-m-d'),						
							'from_user_master_id' 		=> $user_master_id,
							'to_user_master_id' 		=> '1',
							'points_transferred' 		=> $points_transferred,
							'opening_points' 			=> $opening_points,
							'closing_points' 			=> $closing_points,
							'transaction_narration'		=> 'Ticket Generated',
							'transaction_type'			=> '0',
							'transaction_nature'		=> '2',
							'draw_transaction_master_id'=> $insert_id,						
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			$this->wb_model->insertData('points_transactions',$insdata);
			
			
			
			$admin_balance = $this->wb_model->getcurrent_balance('1');
			$cp = $admin_balance + 	$points_transferred;	
			// point transaction  admin
			$insdata = array(
							'transactions_date' 		=> date('Y-m-d'),						
							'from_user_master_id' 		=> '1',
							'to_user_master_id' 		=> $user_master_id,
							'points_transferred' 		=> $points_transferred,
							'opening_points' 			=> $admin_balance,
							'closing_points' 			=> $cp,
							'transaction_narration'		=> 'Ticket Generated',
							'transaction_type'			=> '1',
							'transaction_nature'		=> '2',	
							'draw_transaction_master_id'=> $insert_id,	
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			$this->wb_model->insertData('points_transactions',$insdata);
			
			if($user_data->user_type=="0")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));				
				$retailer_balance = $this->wb_model->getcurrent_balance($Retailer_data->user_master_id);
				
				$comm_points = ($points_transferred*$Retailer_data->user_comission)/100;
				$cp = $retailer_balance + $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $Retailer_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $retailer_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Retailer commission',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				//admin
				$admin_balance = $this->wb_model->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $Retailer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Retailer commission',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $Retailer_data->user_master_id,
								'created_user_master_id'	=> $Retailer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->wb_model->getcurrent_balance($distributer_data->user_master_id);
				
				$comision = $distributer_data->user_comission - $Retailer_data->user_comission;
				
				$comm_points = ($points_transferred*$comision)/100;
				$cp = $distributer_balance + $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $distributer_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $distributer_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Distributor Commission',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				//admin
				$admin_balance = $this->wb_model->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $distributer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Distributor Commission',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $distributer_data->user_master_id,
								'created_user_master_id'	=> $distributer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
			}
			
			if($user_data->user_type=="1")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->user_master_id));				
				$retailer_balance = $this->wb_model->getcurrent_balance($Retailer_data->user_master_id);
				
				$comm_points = ($points_transferred*$Retailer_data->user_comission)/100;
				$cp = $retailer_balance + $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $Retailer_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $retailer_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Retailer commission',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				//admin
				$admin_balance = $this->wb_model->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $Retailer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Retailer commission',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $Retailer_data->user_master_id,
								'created_user_master_id'	=> $Retailer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->wb_model->getcurrent_balance($distributer_data->user_master_id);
				
				$comision = $distributer_data->user_comission - $Retailer_data->user_comission;
				
				$comm_points = ($points_transferred*$comision)/100;
				$cp = $distributer_balance + $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $distributer_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $distributer_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Distributor Commission',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				//admin
				$admin_balance = $this->wb_model->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $distributer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Distributor Commission',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $distributer_data->user_master_id,
								'created_user_master_id'	=> $distributer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);				
			}		
			
			$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));
			$fentime = date('H:i',strtotime($draw_data->draw_start_time));
			$insert_data['draw_transaction_master_id']	= $insert_id;			
			$insert_data['draw_start_time'] 			= $draw_data->draw_start_time;
			$insert_data['draw_end_time'] 				= $draw_data->draw_end_time;
			$insert_data['draw_start_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
			$insert_data['draw_end_time_full'] 			= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
			$insert_data['totalbids'] = $totalbids;
			
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$insert_data);	
		}
					
        $this->response($response	, 200); // 200 being the HTTP response code		
	}
	
	function last_transaction_post()
	{		
		$user_master_id = $this->post('user_master_id');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}else{
			$data = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user_master_id),'draw_transaction_master_id','desc','100');
			
			$final_data = array();
			$no=1;
			for($i=0;$i<count($data);$i++)
			{
				$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$data[$i]->draw_master_id));
				$total_play_points = $this->wb_model->getsumplay($data[$i]->draw_transaction_master_id);
				
				$fftime = date('H:i:s',strtotime($draw_data->draw_start_time)); 
				$ctime = strtotime(date('Y-m-d H:i:s'));
				$drawtime = strtotime(date($data[$i]->result_date.' '.$fftime));
				if($ctime >= $drawtime)
				{
					
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					if($data[$i]->is_deleted==1)
					{
						$f['status'] = "Canceled";
					}
					else if($data[$i]->is_claim==1 && $data[$i]->is_winning==1)
					{
						$f['status'] = "Claimed";
					}
					else if($data[$i]->is_claim==1 && $data[$i]->is_winning==0)
					{
						$f['status'] = "Claimed No Winning";
					}
					else
					{
						$f['status'] = "Not Claim";
					}
					$f['draw_transaction_master_id'] 		= $data[$i]->draw_transaction_master_id;
					$f['result_date'] 						= $data[$i]->result_date;
					$f['draw_master_id'] 					= $data[$i]->draw_master_id;
					$f['bar_code_number'] 					= $data[$i]->bar_code_number;
					$f['total_play_points'] 				= $total_play_points;	
					$f['ticket_type'] 						= $data[$i]->ticket_type;
					$f['is_claim'] 							= $data[$i]->is_claim;	
					$f['created_date'] 						= $data[$i]->created_date;	
					$f['draw_start_time'] 					= $draw_data->draw_start_time;
					$f['draw_end_time'] 					= $draw_data->draw_end_time;
					$f['draw_start_time_full'] 				= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_end_time_full'] 				= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));						
					$final_data[] 							= $f;
				
					if($no >=10)
					{
						$i = "200";
					}
				$no++;
				}
			}
			
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$final_data);
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code		
		
	}
	
	function cancle_transaction_post()
	{
		$setting = $this->wb_model->getsingle('setting',array('id'=>'1'));
		
		$user_master_id = $this->post('user_master_id');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		
		$bar_code_number = $this->post('bar_code_number');
		$bar_code_data = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
		
		$records = $this->wb_model->getsingle('draw_master',array('is_draw_active'=>'0','draw_master_id'=>$bar_code_data->draw_master_id));
		$fentime = date('H:i',strtotime($records->draw_end_time));
			
		$ctime = strtotime(date('Y-m-d H:i:s'));
		$en_date_time =  strtotime ( date('Y-m-d H:i:s',strtotime($bar_code_data->result_date.' '.$fentime.':00 '))); 
		
		$results = $this->wb_model->getsingle('result_master',array('draw_master_id'=>$bar_code_data->draw_master_id,'result_date'=>date('Y-m-d')));			
		
		$chkalrady = $this->common_model->getsingle('draw_transaction_master',array('is_deleted'=>'1','bar_code_number'=>$bar_code_number));
		
		$countdeletes = $this->common_model->getAllwhere('draw_transaction_master',array('result_date'=>date('Y-m-d'),'is_deleted'=>'1','user_master_id'=>$user_master_id));
		
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User NOt Exist.', 'data'=>null);	
		}
		else if($bar_code_number=='')
		{
			$response= array('status'=>'201', 'message'=>'Bar Code Required.', 'data'=>null);	
		}
		else if(!$bar_code_data)
		{
			$response= array('status'=>'201', 'message'=>'Bar Code NOt Exist.', 'data'=>null);	
		}
		else if($chkalrady)
		{
			$response= array('status'=>'201', 'message'=>'Alrady Cancel This Transaction.', 'data'=>null);	
		}
		else if($ctime > $en_date_time)
		{
			$response= array('status'=>'201', 'message'=>'Cancel Transaction End.', 'data'=>null);	
		}
		else if($results)
		{
			$response= array('status'=>'201', 'message'=>'Result Declared.', 'data'=>null);	
		}
		else if(count($countdeletes)>$setting->cancle_per_day_limit)
		{
			$response= array('status'=>'201', 'message'=>'Today Your Cancel Limit Finished.', 'data'=>null);	
		}
		else{
			$draw_transaction_master_id = $bar_code_data->draw_transaction_master_id;
			$play_data = $this->wb_model->getAllwhere('draw_transaction_details',array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id));
			$totalbids = "0";
			if(count($play_data)>0)
			{ 
				$this->common_model->updateData('draw_transaction_master',array('is_deleted'=>'1'),array('bar_code_number'=>$bar_code_number));
				
				foreach($play_data as $p)
				{
					$totalbids = $totalbids + ( $p->bid_units*$p->bid_points*$p->bid_points_multiplier );
					$this->common_model->updateData('draw_transaction_details',array('is_deleted'=>'1'),array('draw_transaction_details_id'=>$p->draw_transaction_details_id));
				}
				
				$getcurrent_balance = $this->wb_model->getcurrent_balance($bar_code_data->user_master_id);
				$points_transferred = $totalbids;
				$opening_points 	= $getcurrent_balance;
				$closing_points		= $getcurrent_balance + $points_transferred;
				
								
				if($user_data->user_type=="0")
				{
					$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));				
					$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
					
					$comision = $distributer_data->user_comission - $Retailer_data->user_comission;					
					$comm_points = ($points_transferred*$comision)/100;
					
					//admin
					$admin_balance = $this->wb_model->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $distributer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Distributor Commission Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					
					$distributer_balance = $this->wb_model->getcurrent_balance($distributer_data->user_master_id);
					$cp = $distributer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $distributer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $distributer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Distributor Commission Remove',
									'transaction_type'			=> '0',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> $distributer_data->user_master_id,
									'created_user_master_id'	=> $distributer_data->user_master_id,
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					
					$comm_points = ($points_transferred*$Retailer_data->user_comission)/100;
					
					//admin
					$admin_balance = $this->wb_model->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $Retailer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Retailer Commission Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$retailer_balance = $this->wb_model->getcurrent_balance($Retailer_data->user_master_id);
					$cp = $retailer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $Retailer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $retailer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Retailer Commission Remove',
									'transaction_type'			=> '0',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> $Retailer_data->user_master_id,
									'created_user_master_id'	=> $Retailer_data->user_master_id,
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
				}
				
				if($user_data->user_type=="1")
				{
					$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->user_master_id));				
					$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
					
					$comision = $distributer_data->user_comission - $Retailer_data->user_comission;
					$comm_points = ($points_transferred*$comision)/100;
					
					//admin
					$admin_balance = $this->wb_model->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $distributer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Distributor Commission Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$distributer_balance = $this->wb_model->getcurrent_balance($distributer_data->user_master_id);
					$cp = $distributer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $distributer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $distributer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Distributor Commission Remove',
									'transaction_type'			=> '0',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> $distributer_data->user_master_id,
									'created_user_master_id'	=> $distributer_data->user_master_id,
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$comm_points = ($points_transferred*$Retailer_data->user_comission)/100;
					
					//admin
					$admin_balance = $this->wb_model->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $Retailer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Retailer Commission Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$retailer_balance = $this->wb_model->getcurrent_balance($Retailer_data->user_master_id);
					$cp = $retailer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $Retailer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $retailer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Retailer Commission Remove',
									'transaction_type'			=> '0',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> $Retailer_data->user_master_id,
									'created_user_master_id'	=> $Retailer_data->user_master_id,
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
									
				}
				
				
				$admin_balance = $this->wb_model->getcurrent_balance('1');
				$cp = $admin_balance - 	$points_transferred;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $bar_code_data->user_master_id,
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Ticket Cancelled',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '7',	
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,	
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance = $this->wb_model->getcurrent_balance($bar_code_data->user_master_id);				
				$closing_points		= $getcurrent_balance + $points_transferred;
				// point transaction 
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $bar_code_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $getcurrent_balance,
								'closing_points' 			=> $closing_points,
								'transaction_narration'		=> 'Ticket Cancelled',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '7',
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,						
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				
				$response= array('status'=>'200', 'message'=>'Transaction Canceld Successfully.', 'data'=>null);
			}else{
				$response= array('status'=>'201', 'message'=>'Not Transaction Available.', 'data'=>null);
			}
			
		}		
		
		$this->response($response	, 200); // 200 being the HTTP response code		
		
	} 
	
	function claim_post()
	{
		$setting = $this->wb_model->getsingle('setting',array('id'=>'1'));	
		
		$user_master_id = $this->post('user_master_id');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		//draw_master_id
		
		$bar_code_number = $this->post('bar_code_number');
		$bar_code_data = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number,'user_master_id'=>$user_master_id));
		
		$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$bar_code_data->draw_master_id));
		
		$ctime 			= strtotime(date('Y-m-d H:i:s'));
		$entime 		= strtotime($bar_code_data->result_date.' '.$draw_data->draw_end_time);
		$addtenminit 	= strtotime(date('Y-m-d H:i:s', strtotime('+10 minutes', $entime)));
		$addtendays 	= strtotime(date('Y-m-d', strtotime('+'.$setting->claim_before_day.' day', $entime)));
			
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User NOt Exist.', 'data'=>null);	
		}
		else if($bar_code_number=='')
		{
			$response= array('status'=>'201', 'message'=>'Bar Code Required.', 'data'=>null);	
		}
		else if(!$bar_code_data)
		{
			$response= array('status'=>'201', 'message'=>'Bar Code NOt Exist OR Bar Code and User not match.', 'data'=>null);	
		}
		else if($bar_code_data->is_claim=="1")
		{
			$play_data = $this->wb_model->getAllwhere('draw_transaction_details',array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id,'is_winning'=>'1'));
			$claim_date_time = date('d-M-Y h:i A',strtotime($bar_code_data->claim_date_time));
			
			$totalbids = 0;
			$final_d = array();
			if(count($play_data)>0)
			{
				foreach($play_data as $p)
				{
					$totalbids = $totalbids + ( $p->bid_units*$p->bid_points*$p->bid_points_multiplier*90 );
					$this->common_model->updateData('draw_transaction_details',array('claim_user_master_id'=>$user_master_id),array('draw_transaction_details_id'=>$p->draw_transaction_details_id));
					
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$p->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$ddd['draw_master_id'] 			= $p->draw_master_id;
					$ddd['draw_start_time'] 		= $draw_data->draw_start_time;
					$ddd['draw_end_time'] 			= $draw_data->draw_end_time;
					$ddd['draw_start_time_full'] 	= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$ddd['draw_end_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
					
					$ddd['series_master_id'] 	= $p->series_master_id;
					$ddd['bajar_master_id'] 	= $p->bajar_master_id;
					$ddd['bid_akada_number'] 	= $p->bid_akada_number;
					$ddd['bid_units'] 			= $p->bid_units;
					$ddd['bid_points'] 			= $p->bid_points;
					$ddd['bid_points_multiplier'] = $p->bid_points_multiplier;
					$ddd['winning'] 			= $p->bid_units*$p->bid_points*$p->bid_points_multiplier*90;
					
					$final_d[] = $ddd;
				}
				
				$response= array('status'=>'201', 'message'=>'Claim already settled on '.$claim_date_time.'. Winning amount is '.$totalbids.'.', 'data'=>null);	
			}else{
				$response= array('status'=>'201', 'message'=>'Claim already settled on '.$claim_date_time.'. No Winning amount is '.$totalbids.'.', 'data'=>null);				
			}
			
			
		}
		else if($ctime < $entime )
		{
			$response= array('status'=>'201', 'message'=>'Currently draw not end.', 'data'=>null);	
		}
		else if($bar_code_data->is_result==0)
		{
			$response= array('status'=>'201', 'message'=>'Under Proccess result Please claim after some time.', 'data'=>null);	
		}
		else if($ctime > $addtendays )
		{
			$response= array('status'=>'201', 'message'=>$ctime .'-'.$addtendays.'Not Eligible, claim time expired.', 'data'=>null);	
		}
		else
		{
			$play_data = $this->wb_model->getAllwhere('draw_transaction_details',array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id,'is_winning'=>'1'));			
				
			if(count($play_data)>0)
			{
				$this->common_model->updateData('draw_transaction_master',array('is_winning'=>'1'),array('bar_code_number'=>$bar_code_number));
				
				$totalbids = 0;
				$final_d = array();
				foreach($play_data as $p)
				{
					$totalbids = $totalbids + ( $p->bid_units*$p->bid_points*$p->bid_points_multiplier*90 );
					$this->common_model->updateData('draw_transaction_details',array('claim_user_master_id'=>$user_master_id),array('draw_transaction_details_id'=>$p->draw_transaction_details_id));
					
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$p->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$ddd['draw_master_id'] 			= $p->draw_master_id;
					$ddd['draw_start_time'] 		= $draw_data->draw_start_time;
					$ddd['draw_end_time'] 			= $draw_data->draw_end_time;
					$ddd['draw_start_time_full'] 	= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$ddd['draw_end_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
					
					$ddd['series_master_id'] 	= $p->series_master_id;
					$ddd['bajar_master_id'] 	= $p->bajar_master_id;
					$ddd['bid_akada_number'] 	= $p->bid_akada_number;
					$ddd['bid_units'] 			= $p->bid_units;
					$ddd['bid_points'] 			= $p->bid_points;
					$ddd['bid_points_multiplier'] = $p->bid_points_multiplier;
					$ddd['winning'] 			= $p->bid_units*$p->bid_points*$p->bid_points_multiplier*90;
					
					$final_d[] = $ddd;
				}
				
				$getcurrent_balance = $this->wb_model->getcurrent_balance($bar_code_data->user_master_id);
				$points_transferred = $totalbids;
				$opening_points 	= $getcurrent_balance;
				$closing_points		= $getcurrent_balance + $points_transferred;
				
				// point transaction 
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $bar_code_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $opening_points,
								'closing_points' 			=> $closing_points,
								'transaction_narration'		=> 'Winning',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '3',
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,						
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$admin_balance = $this->wb_model->getcurrent_balance('1');
				$cp = $admin_balance - 	$points_transferred;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $bar_code_data->user_master_id,
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Winning',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '3',	
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,	
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$this->common_model->updateData('draw_transaction_details',array('is_claim'=>'1'),array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id));				
				$this->common_model->updateData('draw_transaction_master',array('claim_date_time'=>date('Y-m-d H:i:s'),'is_claim'=>'1','claim_user_master_id'=>$user_master_id),array('bar_code_number'=>$bar_code_number));
			
			
				if($totalbids==0)
				{
					$response= array('status'=>'200', 'message'=>'No Winning. Better luck. Try Next time.', 'data'=>$final_d);	
				}else{
					$response= array('status'=>'200', 'message'=>'Congratulations You Win '.$totalbids.'/-!!!', 'data'=>$final_d);	
				}
				
				
			}else{
				$this->common_model->updateData('draw_transaction_details',array('is_claim'=>'1'),array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id));				
				$this->common_model->updateData('draw_transaction_master',array('claim_date_time'=>date('Y-m-d H:i:s'),'is_claim'=>'1','claim_user_master_id'=>$user_master_id),array('bar_code_number'=>$bar_code_number));
			
				$response= array('status'=>'200', 'message'=>'No Winning. Better luck. Try Next time.', 'data'=>null);
			}
	
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code		
	} 
	
	function transaction_details_post()
	{		
		$draw_transaction_master_id = $this->post('draw_transaction_master_id');
		if($draw_transaction_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Transaction Required.', 'bar_code_number'=>null,'data'=>null);	
		}else{
			$data = $this->wb_model->getAllwhere_order_by('draw_transaction_details',array('draw_transaction_master_id'=>$draw_transaction_master_id),'draw_transaction_details_id','asc');
			
			$tr_data = $this->wb_model->getsingle('draw_transaction_master',array('draw_transaction_master_id'=>$draw_transaction_master_id));
			if($tr_data)
			{
				$bar_code_number = $tr_data->bar_code_number;
			}else{
				$bar_code_number='';
			}
			$final_data = array();
			if($data!="")
			{
				foreach($data as $d)
				{
					$f['result_date'] = $d->result_date;
					$f['draw_master_id'] = $d->draw_master_id;
					$f['series_master_id'] = $d->series_master_id;
					$f['bajar_master_id'] = $d->bajar_master_id;
					$f['bid_akada_number'] = $d->bid_akada_number;
					$f['bid_units'] = $d->bid_units;
					$f['bid_points'] = $d->bid_points;
					$f['bid_points_multiplier'] = $d->bid_points_multiplier;
					$f['created_date'] = $d->created_date;
					$final_data[] = $f;
				}
			}
			$response= array('status'=>'200', 'message'=>'Successfully.','bar_code_number'=>$bar_code_number, 'data'=>$final_data);
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code		
		
	}
	
	function point_balance_post()
	{
		$user_master_id = $this->post('user_master_id');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			$getcurrent_balance = $this->wb_model->getcurrent_balance($user_master_id);
		
			$viewdata = array(
				'current_points'			=> $getcurrent_balance
			);			
			$response= array('status'=>'200', 'message'=>'Balance Get Successfully.', 'data'=>$viewdata);
				
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function draw_time_post()
	{
		$current_date_time = date('d-M-Y H:i:s');
		
		$user_master_id = $this->post('user_master_id');
		$type = $this->post('type');
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201','current_date_time'=>$current_date_time, 'message'=>'User Required.', 'data'=>null);	
		}
		else if($type=='')
		{
			$response= array('status'=>'201','current_date_time'=>$current_date_time, 'message'=>'Type Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201','current_date_time'=>$current_date_time, 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			if($type=="0")
			{
				$records = $this->wb_model->getAllwhere('draw_master',array('is_draw_active'=>'0'));
				$final_data = array();
				foreach($records as $r)
				{
					
					$fentime = date('H:i',strtotime($r->draw_start_time));
					
					$dd['draw_master_id'] = $r->draw_master_id;
					$dd['draw_start_time'] = $r->draw_start_time;
					$dd['draw_end_time'] = $r->draw_end_time;
					$dd['is_draw_active'] = $r->is_draw_active;
					//$dd['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$dd['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
					$dd['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_end_time));					
					$final_data[] = $dd;					
				}
				$draw_times = $final_data;
			}
			else if($type=="1")
			{
				
				$records = $this->wb_model->getAllwhere('draw_master',array('is_draw_active'=>'0'));
				$final_data = array();
				foreach($records as $r)
				{
					
					
					$ctime = strtotime(date('Y-m-d H:i:s'));
					$fentime = date('H:i',strtotime($r->draw_start_time));
					$sttime = strtotime(date('Y-m-d').' '.$r->draw_start_time);
					$entime = strtotime(date('Y-m-d').' '.$r->draw_end_time);
					
					if($sttime <= $ctime && $entime>=$ctime)
					{
						$dd['draw_master_id'] = $r->draw_master_id;
						$dd['draw_start_time'] = $r->draw_start_time;
						$dd['draw_end_time'] = $r->draw_end_time;
						$dd['is_draw_active'] = $r->is_draw_active;
						//$dd['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
						$dd['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
						$dd['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_end_time));					
						$final_data[] = $dd;
					}
				}
				$draw_times = $final_data;
				
			}
				
			$response= array('status'=>'200','current_date_time'=>$current_date_time, 'message'=>'Data Get Successfully.', 'data'=>$draw_times);
				
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function result_post()
	{
		$user_master_id = $this->post('user_master_id');
		$draw_master_id = $this->post('draw_master_id');
		$result_date 	= $this->post('result_date');
		
		//$result_date = date("Y-m-d", strtotime("- 1 day"));
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			if($draw_master_id!='')
			{
				$results = $this->wb_model->getAllwhere_result('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));			
				$final_data = array();
				foreach($results as $r)
				{
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$f['result_date'] 		= $r->result_date;
					$f['draw_master_id'] 	= $r->draw_master_id;
					$f['series_master_id'] 	= $r->series_master_id;
					$f['bajar_master_id'] 	= $r->bajar_master_id;
					$f['bid_akada_number'] 	= $r->bid_akada_number;
					$f['draw_start_time'] 	= $draw_data->draw_start_time;
					$f['draw_end_time'] 	= $draw_data->draw_end_time;
					//$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
					$f['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
					$final_data[] = $f;
				}
			}else{
				$results = $this->wb_model->getAllwhere_result('result_master',array('result_date'=>$result_date));			
				$final_data = array();
				foreach($results as $r)
				{
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$f['result_date'] 		= $r->result_date;
					$f['draw_master_id'] 	= $r->draw_master_id;
					$f['series_master_id'] 	= $r->series_master_id;
					$f['bajar_master_id'] 	= $r->bajar_master_id;
					$f['bid_akada_number'] 	= $r->bid_akada_number;
					$f['draw_start_time'] 	= $draw_data->draw_start_time;
					$f['draw_end_time'] 	= $draw_data->draw_end_time;
					//$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
					$f['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));						
					$final_data[] = $f;
				}
			}
			
			$response= array('status'=>'201', 'message'=>'Rsults Get Successfully.', 'data'=>$final_data);
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}

	function result1_post()
	{
		$user_master_id = $this->post('user_master_id');
		$draw_master_id = $this->post('draw_master_id');
		$result_date 	= $this->post('result_date');
		
		//$result_date = date("Y-m-d", strtotime("- 1 day"));
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));

		$draw_data_details = $this->wb_model->getAllwhere('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));

		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{

			if($draw_master_id!='' && count($draw_data_details)=="100")
			{
				$results = $this->wb_model->getAllwhere_result('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));			
				$final_data = array();
				foreach($results as $r)
				{
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$f['result_date'] 		= $r->result_date;
					$f['draw_master_id'] 	= $r->draw_master_id;
					$f['series_master_id'] 	= $r->series_master_id;
					$f['bajar_master_id'] 	= $r->bajar_master_id;
					$f['bid_akada_number'] 	= $r->bid_akada_number;
					$f['draw_start_time'] 	= $draw_data->draw_start_time;
					$f['draw_end_time'] 	= $draw_data->draw_end_time;
					//$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
					$f['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
					$final_data[] = $f;
					$newdate = $draw_data->draw_end_time.' '.date('d-M-Y',strtotime($r->result_date));
				}
			}
			elseif($draw_master_id!='' && count($draw_data_details)!="100")
			{
				$results = $this->wb_model->getAllResult();	
				
				$final_data = array();
				foreach($results as $r)
				{
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$f['result_date'] 		= $r->result_date;
					$f['draw_master_id'] 	= $r->draw_master_id;
					$f['series_master_id'] 	= $r->series_master_id;
					$f['bajar_master_id'] 	= $r->bajar_master_id;
					$f['bid_akada_number'] 	= $r->bid_akada_number;
					$f['draw_start_time'] 	= $draw_data->draw_start_time;
					$f['draw_end_time'] 	= $draw_data->draw_end_time;
					//$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
					$f['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));						
					$final_data[] = $f;
					$newdate = $draw_data->draw_end_time.' '.date('d-M-Y',strtotime($r->result_date));
				}
			}
			else{
				$results = $this->wb_model->getAllwhere_result_new('result_master',array('result_date'=>$result_date));			
				$final_data = array();
				foreach($results as $r)
				{
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					
					$f['result_date'] 		= $r->result_date;
					$f['draw_master_id'] 	= $r->draw_master_id;
					$f['series_master_id'] 	= $r->series_master_id;
					$f['bajar_master_id'] 	= $r->bajar_master_id;
					$f['bid_akada_number'] 	= $r->bid_akada_number;
					$f['draw_start_time'] 	= $draw_data->draw_start_time;
					$f['draw_end_time'] 	= $draw_data->draw_end_time;
					//$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_start_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$r->draw_start_time));
					$f['draw_end_time_full'] = date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));						
					$final_data[] = $f;
					$newdate = $draw_data->draw_end_time.' '.date('d-M-Y',strtotime($r->result_date));
				}
			}
			
			$response= array('status'=>'201', 'newdate'=>$newdate, 'message'=>'Rsults Get Successfully.', 'data'=>$final_data);
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}

	
	
	function welcome_post()
	{
		$user_master_id = $this->post('user_master_id');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			$viewdata = $this->wb_model->getsingle('welcome',array('welcome_id'=>'1'));
				
			$response= array('status'=>'200', 'message'=>'Data Get Successfully.', 'data'=>$viewdata);
				
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function support_post()
	{
		$user_master_id = $this->post('user_master_id');
		$title 			= $this->post('title');
		$description 	= $this->post('description');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}
		else if($title=="")
		{
			$response= array('status'=>'201', 'message'=>'Title Required.', 'data'=>'');	
		}
		else if($description=="")
		{
			$response= array('status'=>'201', 'message'=>'Description Required.', 'data'=>'');	
		}		
		else{
			
			if($_FILES['attachment']['name']=="")
			{
				$attachment = "";
			}
			else
			{
				$config['upload_path'] = 'uploads/';
				$config['allowed_types'] = '*';			
				$config['encrypt_name'] = false;
				
				$this->load->library('upload', $config);
				$this->upload->do_upload('attachment');
				$attachment_data = array('upload_data' => $this->upload->data());
				$attachment = $attachment_data['upload_data']['file_name'];
			}
		   	
			$ins_data = array(
				'user_master_id'	=> $user_master_id,
				'title'				=> $title,
				'description'		=> $description,
				'attachment'		=> $attachment,
				'entry_date'		=> date('Y-m-d')
			);
		
		   $this->wb_model->insertData('support',$ins_data);
		   $response= array('status'=>'200', 'message'=>'Submit Successfully','data'=>$ff);  
			
		
		}  
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function net_to_pay_post()
	{		
		$user_master_id = $this->post('user_master_id');
		$fromdate = $this->post('fromdate');
		$todate = $this->post('todate');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}
		else if($user_data->user_type==0)
		{
			$response= array('status'=>'201', 'message'=>'User Not Allowed.', 'data'=>null);	
		}
		else if($fromdate=='')
		{
			$response= array('status'=>'201', 'message'=>'Fromdate Required.', 'data'=>null);	
		}
		else if($todate=='')
		{
			$response= array('status'=>'201', 'message'=>'todate Required.', 'data'=>null);	
		}
		else
		{
			$my_id = $user_master_id;
			$my_reports = $this->wb_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
			
			$my_downs =array();
			if(count($my_reports)>0)
			{
				foreach($my_reports as $ids)
				{
					$my_downs[] = $ids->user_master_id;
					if($user_data->user_type=='2')
					{
						$my_reports2 = $this->wb_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
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
			
			$transactions = $this->wb_model->getAllwhere_net_to_pay($user_data->user_type,$my_downs,$fromdate,$todate);
			
			$r_t_bid = 0;
			$p_t_bid = 0;
			$r_t_win = 0;
			$p_t_win = 0;
			$r_t_c = 0;
			$p_t_c = 0;
			$r_t_b = 0;
			$p_t_b = 0;
			$r_net_pay_t = 0;
			$p_net_pay_t = 0;
			
			$final_data = array();
			if(count($transactions)>0){ 
				foreach($transactions as $tr){
				$total_bid = $this->wb_model->total_bid_counter_sale($tr->user_master_id,'',$fromdate,$todate);
				$total_win = $this->wb_model->total_bid_counter_sale($tr->user_master_id,'1',$fromdate,$todate);				
				
				$total_comissionsss = $this->common_model->total_comission_new2($tr->user_master_id,$fromdate,$todate);
				$total_comission = $total_comissionsss['0']['Retailer_commission'];
				
				$total_bonus = $this->wb_model->total_bonus($tr->user_master_id,$fromdate,$todate);
				
				$net_pay_t = $total_bid[0]->total - ($total_win[0]->total*90) - $total_comission - $total_bonus[0]->total;
				
				$t_bid = $total_bid[0]->total;
				$t_win = $total_win[0]->total*90;
				$t_c = $total_comission;
				$t_b = $total_bonus[0]->total;
				if($tr->user_type=="1")
				{
					$r_t_bid = $r_t_bid + $t_bid;
					$r_t_win = $r_t_win + $t_win;
					$r_t_c = $r_t_c + $t_c;
					$r_t_b = $r_t_b + $t_b;
					$r_net_pay_t = $r_net_pay_t + $net_pay_t;
				}
				if($tr->user_type=="0")
				{
					$p_t_bid = $p_t_bid + $t_bid;
					$p_t_win = $p_t_win + $t_win;
					$p_t_c = $p_t_c + $t_c;
					$p_t_b = $p_t_b + $t_b;
					$p_net_pay_t = $p_net_pay_t + $net_pay_t;
				}
				
				
				$dd['fromdate'] = $fromdate;
				$dd['todate'] = $todate;
				$dd['name'] = $tr->name;
				$dd['username'] = $tr->user_name;
				$dd['salespoints'] = number_format((float)$t_bid, 2, '.', '');
				$dd['winningpoints'] = number_format((float)$t_win, 2, '.', '');
				$dd['commission'] = number_format((float)$t_c, 2, '.', '');
				$dd['retailbonus'] = number_format((float)$t_b, 2, '.', ''); 
				$dd['netpoints'] = number_format((float)$net_pay_t, 2, '.', '');
				
				$final_data[] = $dd;
				}
			}
			$total = array();
			$total['player_salespoints'] = number_format((float)$p_t_bid, 2, '.', '');
			$total['player_winningpoints'] = number_format((float)$p_t_win, 2, '.', '');
			$total['player_commission'] = number_format((float)$p_t_c, 2, '.', '');
			$total['player_retailbonus'] = number_format((float)$p_t_b, 2, '.', '');
			$total['player_netpoints'] = number_format((float)$p_net_pay_t, 2, '.', '');
			
			$total['retailer_salespoints'] = number_format((float)$r_t_bid, 2, '.', '');
			$total['retailer_winningpoints'] = number_format((float)$r_t_win, 2, '.', '');
			$total['retailer_commission'] = number_format((float)$r_t_c, 2, '.', '');
			$total['retailer_retailbonus'] = number_format((float)$r_t_b, 2, '.', '');
			$total['retailer_netpoints'] = number_format((float)$r_net_pay_t, 2, '.', '');
			
			$total['salespoints'] = number_format((float)$r_t_bid+$p_t_bid, 2, '.', '');
			$total['winningpoints'] = number_format((float)$r_t_win+$p_t_win, 2, '.', '');
			$total['commission'] = number_format((float)$r_t_c+$p_t_c, 2, '.', '');
			$total['retailbonus'] = number_format((float)$r_t_b+$p_t_b, 2, '.', '');
			$total['netpoints'] = number_format((float)$r_net_pay_t+$p_net_pay_t, 2, '.', '');
			
			$response= array('status'=>'200', 'message'=>'Data Get Successfully.','data'=>$final_data,'total'=>$total);  
		}
	
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function counter_sale_post()
	{		
		$user_master_id = $this->post('user_master_id');
		$fromdate = $this->post('fromdate');
		$todate = $this->post('todate');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}
		else if($user_data->user_type==0)
		{
			$response= array('status'=>'201', 'message'=>'User Not Allowed.', 'data'=>null);	
		}
		else if($fromdate=='')
		{
			$response= array('status'=>'201', 'message'=>'Fromdate Required.', 'data'=>null);	
		}
		else if($todate=='')
		{
			$response= array('status'=>'201', 'message'=>'todate Required.', 'data'=>null);	
		}
		else
		{
			$my_id = $user_master_id;
			$my_reports = $this->wb_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
			
			$my_downs =array();
			if(count($my_reports)>0)
			{
				foreach($my_reports as $ids)
				{
					$my_downs[] = $ids->user_master_id;
					if($user_data->user_type=='2')
					{
						$my_reports2 = $this->wb_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
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
			
			$transactions = $this->wb_model->getAllwhere_net_to_pay($user_data->user_type,$my_downs,$fromdate,$todate);
			
			$final_data = array();
			if(count($transactions)>0){ 
				foreach($transactions as $tr){
				$total_bid = $this->wb_model->total_bid_counter_sale($tr->user_master_id,'',$fromdate,$todate);
				$total_win = $this->wb_model->total_bid_counter_sale($tr->user_master_id,'1',$fromdate,$todate);				
				
				$net_pay_t = $total_bid[0]->total - ($total_win[0]->total*90);
				
				$dd['fromdate'] = $fromdate;
				$dd['todate'] = $todate;
				$dd['name'] = $tr->name;
				$dd['username'] = $tr->user_name;
				$dd['salespoints'] = number_format((float)$total_bid[0]->total, 2, '.', '');
				$dd['winningpoints'] = number_format((float)$total_win[0]->total*90, 2, '.', '');
				$dd['netpoints'] = number_format((float)$net_pay_t, 2, '.', '');
				
				$final_data[] = $dd;
				}
			}
			
			$response= array('status'=>'200', 'message'=>'Data Get Successfully.','data'=>$final_data);  
		}
	
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function bonus_history_post()
	{		
		$user_master_id = $this->post('user_master_id');
		$fromdate = $this->post('fromdate');
		$todate = $this->post('todate');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}
		else if($user_data->user_type==0)
		{
			$response= array('status'=>'201', 'message'=>'User Not Allowed.', 'data'=>null);	
		}
		else if($fromdate=='')
		{
			$response= array('status'=>'201', 'message'=>'Fromdate Required.', 'data'=>null);	
		}
		else if($todate=='')
		{
			$response= array('status'=>'201', 'message'=>'todate Required.', 'data'=>null);	
		}
		else
		{
			$my_id = $user_master_id;
			$my_reports = $this->wb_model->getAllwhere('user_master',array('reporting_user_master_id'=>$my_id));
			
			$my_downs =array();
			if(count($my_reports)>0)
			{
				foreach($my_reports as $ids)
				{
					$my_downs[] = $ids->user_master_id;
					if($user_data->user_type=='2')
					{
						$my_reports2 = $this->wb_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id));
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
			
			$transactions = $this->wb_model->getAllwhere_bonus_history($user_data->user_type,$my_downs,$fromdate,$todate);
			
			$final_data = array();
			if(count($transactions)>0){ 
				foreach($transactions as $tr){
				$dd['fromdate'] = $fromdate;
				$dd['todate'] = $todate;
				$dd['date'] = $tr->transactions_date;
				$dd['name'] = $tr->name;
				$dd['username'] = $tr->user_name;
				$dd['bonus'] = number_format((float)$tr->points_transferred, 2, '.', '');
				$final_data[] = $dd;
				}
			}
			
			$response= array('status'=>'200', 'message'=>'Data Get Successfully.','data'=>$final_data);  
		}
	
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function result_time_post()
	{
		$user_master_id = $this->post('user_master_id');
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));

		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			
			$cutime = date('Y-m-d H:i:s');
			$rtime = date('Y-m-d').' 10:17:00';
			if(strtotime($cutime) >= strtotime($rtime) )
			{
				$result_date = date('Y-m-d');
			}else{
				$result_date = date('Y-m-d ', strtotime('-1 days', strtotime(date('Y-m-d'))));
			}
			
			$results = $this->wb_model->getAllwhere_result_new2('result_master',array('result_date'=>$result_date));			
			$final_data = array();
			foreach($results as $r)
			{
				$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$r->draw_master_id));					
				$newdate = $draw_data->draw_end_time.' '.date('d-M-Y',strtotime($r->result_date));
			}
			
			$response= array('status'=>'200', 'newdate'=>$newdate, 'message'=>'Rsults Time Get Successfully.', 'data'=>null);
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function check_bonus_post()
	{
		$user_master_id = $this->post('user_master_id');
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));

		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			$my_reports = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$user_master_id,'user_type'=>'1','is_user_deleted'=>'0'));		
			$final_array =array();
			if(count($my_reports)>0)
			{
				foreach($my_reports as $ids)
				{
					$f['user_master_id'] = $ids->user_master_id;
					
					$my_downs = array();
					$my_downs[] = $ids->user_master_id;
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id,'is_user_deleted'=>'0'));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
					$f['my_down'] = $my_downs;
					$final_array[] = $f;
				}
				
			}
			
			$bonus_amount = 0;
			if($final_array)
			{
				foreach($final_array as $final)
				{
					$user_master_id = $final['user_master_id'];
					$my_down 		= $final['my_down'];
					
					$sql ='select 
							sum(bid_units*bid_points_multiplier* bid_points) as total_play from draw_transaction_details 
						where is_deleted=0 and user_master_id IN (' . implode(',', array_map('intval', $my_down)) . ')';
					$query = $this->db->query($sql);
					$results = $query->result();
					
					if($results[0]->total_play!="")
					{	
						$total_play 	= $results[0]->total_play;
						
						$sql2 ="select * from bonus where bonas<='$total_play' and bonus_master_id not in
						( select bonus_master_id from bonus_distribution where user_master_id='$user_master_id' ) ";
						$query2 = $this->db->query($sql2);
						$results2 = $query2->result();
						if($results2)
						{
							foreach($results2 as $r)
							{
								$amt = ($r->bonas*$user_data->bonus_percent)/100;
								$bonus_amount = $bonus_amount + $amt;									
							}
						}
							
					} 
				}
			}
			$final_data['bonus_amount'] = $bonus_amount;
			$response= array('status'=>'200', 'message'=>'Data Get Successfully.','data'=>$final_data); 
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function claim_bonus_post()
	{
		$user_master_id = $this->post('user_master_id');
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));

		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'User Not Exist.', 'data'=>null);	
		}		
		else
		{
			$my_reports = $this->common_model->getAllwhere('user_master',array('user_master_id'=>$user_master_id,'user_type'=>'1','is_user_deleted'=>'0'));		
			$final_array =array();
			if(count($my_reports)>0)
			{
				foreach($my_reports as $ids)
				{
					$f['user_master_id'] = $ids->user_master_id;
					
					$my_downs = array();
					$my_downs[] = $ids->user_master_id;
					$my_reports2 = $this->common_model->getAllwhere('user_master',array('reporting_user_master_id'=>$ids->user_master_id,'is_user_deleted'=>'0'));
					if(count($my_reports2)>0)
					{
						foreach($my_reports2 as $ids2)
						{
							$my_downs[] = $ids2->user_master_id;
						}
					}
					$f['my_down'] = $my_downs;
					$final_array[] = $f;
				}
				
			}
			
			$bonus_amount = 0;
			$final_data = array();
			if($final_array)
			{
				foreach($final_array as $final)
				{
					$user_master_id = $final['user_master_id'];
					$my_down 		= $final['my_down'];
					
					$sql ='select 
							sum(bid_units*bid_points_multiplier* bid_points) as total_play from draw_transaction_details 
						where is_deleted=0 and user_master_id IN (' . implode(',', array_map('intval', $my_down)) . ')';
					$query = $this->db->query($sql);
					$results = $query->result();
					
					if($results[0]->total_play!="")
					{	
						$total_play 	= $results[0]->total_play;
						
						$sql2 ="select * from bonus where bonas<='$total_play' and bonus_master_id not in
						( select bonus_master_id from bonus_distribution where user_master_id='$user_master_id' ) ";
						$query2 = $this->db->query($sql2);
						$results2 = $query2->result();
						if($results2)
						{
							foreach($results2 as $r)
							{
								$amt = ($r->bonas*$user_data->bonus_percent)/100;
								$bonus_amount = $bonus_amount + $amt;
								
								$ins_data = array(
								'bonus_master_id'	=> $r->bonus_master_id,
								'user_master_id'	=> $user_master_id,
								'entry_date'		=> date('Y-m-d')
								);
								$this->common_model->insertData('bonus_distribution',$ins_data);
								
								//transaction point to user
								$retailer_balance = $this->common_model->getcurrent_balance($user_master_id);				
								$cp = $retailer_balance + $amt;	
								
								$insdata = array(
												'transactions_date' 		=> date('Y-m-d'),						
												'from_user_master_id' 		=> $user_master_id,
												'to_user_master_id' 		=> '1',
												'points_transferred' 		=> $amt,
												'opening_points' 			=> $retailer_balance,
												'closing_points' 			=> $cp,
												'transaction_narration'		=> 'Received',
												'transaction_type'			=> '1',
												'transaction_nature'		=> '5',							
												'user_master_id' 			=> '1',
												'created_user_master_id'	=> '1',
												'created_date' 				=> date('Y-m-d H:i:s')
										);
								$this->common_model->insertData('points_transactions',$insdata);
								
								$rdata = array(	
												'opening_points' 			=> $retailer_balance,
												'points_transferred' 		=> $amt,
												'closing_points' 			=> $cp
										);
								
								$final_data[] = $rdata; 
								//admin
								$admin_balance = $this->common_model->getcurrent_balance('1');
								$cpp = $admin_balance - $amt;	
								
								$insdata = array(
												'transactions_date' 		=> date('Y-m-d'),						
												'from_user_master_id' 		=> '1',
												'to_user_master_id' 		=> $user_master_id,
												'points_transferred' 		=> $amt,
												'opening_points' 			=> $admin_balance,
												'closing_points' 			=> $cpp,
												'transaction_narration'		=> 'Send',
												'transaction_type'			=> '0',
												'transaction_nature'		=> '5',							
												'user_master_id' 			=> $user_master_id,
												'created_user_master_id'	=> $user_master_id,
												'created_date' 				=> date('Y-m-d H:i:s')
										);
								$this->common_model->insertData('points_transactions',$insdata);
							}
						}
							
					} 
				}
			}
			if($bonus_amount==0)
			{
				$final_records['bonus_amount'] = $bonus_amount;
				$final_records['records'] = $final_data;
				$response= array('status'=>'200', 'message'=>'Bonus Claim Already Transferd.','data'=>$final_records); 
			}else{
				$final_records['bonus_amount'] = $bonus_amount;
				$final_records['records'] = $final_data;
				$response= array('status'=>'200', 'message'=>'Bonus Claim Successfully.','data'=>$final_records); 
			}
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}

	
	
}
	

