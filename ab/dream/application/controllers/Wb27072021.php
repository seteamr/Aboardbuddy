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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}		
		else
		{
			$user = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));		
			if($user)
			{ 
				$this->wb_model->updateData('user_master',array('device_token'=>'123456'),array('user_master_id'=>$user->user_master_id));
				$response= array('status'=>'200', 'message'=>'Logout Successfully.', 'data'=>null);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function getcurrent_balance($user_master_id)
	{
		$records = $this->wb_model->getcurrent_balance_new($user_master_id);
		if($records==0)
		{
			$fclosing_points = 0;
		}
		else if($records->transactions_date!=date('Y-m-d'))
		{
			$fclosing_points = $records->closing_points;
		}else{
			$transactions_date = date('Y-m-d');
			$query1 = "SELECT	MIN(pt.points_transactions_id)	AS	first_points_transactions_id
						,		MAX(pt.points_transactions_id)	AS	last_points_transactions_id
						,		 ROUND(SUM(
							CASE
								WHEN pt.transaction_nature = 0 AND pt.transaction_type = 0 THEN -pt.points_transferred
								WHEN pt.transaction_nature = 0 AND pt.transaction_type = 1 THEN pt.points_transferred
								WHEN pt.transaction_nature = 1 THEN pt.points_transferred
								WHEN pt.transaction_nature = 2 THEN -pt.points_transferred
								WHEN pt.transaction_nature = 3 THEN pt.points_transferred
								WHEN pt.transaction_nature = 4 AND pt.transaction_type = 0 THEN -pt.points_transferred
	                            WHEN pt.transaction_nature = 4 AND pt.transaction_type = 1 THEN pt.points_transferred
	                            WHEN pt.transaction_nature = 5 THEN pt.points_transferred
								WHEN pt.transaction_nature = 6 THEN -pt.points_transferred
								ELSE pt.points_transferred
							END
							),2) AS balance_points
						FROM	points_transactions pt
						WHERE	transactions_date = '".$transactions_date."'
						AND		from_user_master_id = '".$user_master_id."' ";
						
			$q1 = $this->db->query($query1);
			$result1 = $q1->result();
			
			$first_points_transactions_id = $result1[0]->first_points_transactions_id;
			$last_points_transactions_id = $result1[0]->last_points_transactions_id;
			$balance_points = $result1[0]->balance_points;
			
			$query2 = "SELECT	ROUND(pt.opening_points,2) AS opening_points
						FROM	points_transactions pt
						WHERE	transactions_date = '".$transactions_date."'
						AND		from_user_master_id = '".$user_master_id."'
						AND		pt.points_transactions_id = '".$first_points_transactions_id."' ";
						
			$q2 = $this->db->query($query2);
			$result2 = $q2->result();
			
			$query3 = "SELECT	ROUND(pt.closing_points,2) AS closing_points
						FROM	points_transactions pt
						WHERE	transactions_date = '".$transactions_date."'
						AND		from_user_master_id = '".$user_master_id."'
						AND		pt.points_transactions_id = '".$last_points_transactions_id."' ";
						
			$q3 = $this->db->query($query3);
			$result3 = $q3->result();
			
			if($result2[0]->opening_points+$balance_points==$result3[0]->closing_points)
			{
				$fclosing_points =  $result3[0]->closing_points;
			}else{
				$fclosing_points =  $result2[0]->opening_points+$balance_points;
			}
			
		}
		
		return number_format((float)$fclosing_points, 2, '.', '');
		
	}
	
	function login_post()
	{
		$user_name = $this->post('user_name');
		$user_password = $this->post('user_password');
		$device_token = $this->post('device_token');
		if($user_name=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if($user_password=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Passcode.', 'data'=>null);	
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
					$getcurrent_balance = $this->getcurrent_balance($user->user_master_id);
						
					$transactions = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user->user_master_id),'draw_transaction_master_id','desc','1');
					$bid_amount =  0;
					if($transactions)
					{
						$bar_code_number = $transactions[0]->bar_code_number;
						$totalbid = $this->wb_model->totalbidamount($transactions[0]->draw_transaction_master_id);
						if($totalbid[0]->total!="")
						{
							$bid_amount = $totalbid[0]->total;
						}else{
							$bid_amount =  0;
						}
						
					}else{
						$bar_code_number = "";
					}
					
					$welcome_message = $this->wb_model->getsingle('welcome',array('welcome_id'=>'1'));
					$WelcomeMessage = $welcome_message->description;
					//$response= array('status'=>'200', 'message'=>'Data Get Successfully.', 'data'=>$viewdata);
					
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
						'android_version'			=> NULL,
						'window_version'			=> NULL,
						'bar_code_number'			=> $bar_code_number,
						'bid_amount'				=> $bid_amount,
						'WelcomeMessage'			=> $WelcomeMessage,
						'current_date_time'			=> date('d-M-Y H:i:s'),
						'server_date_time'			=> date('Y-m-d H:i:s')
						);
					$this->wb_model->updateData('user_master',array('device_token'=>$device_token),array('user_master_id'=>$user->user_master_id));
					$response= array('status'=>'200', 'message'=>'Login Successfully.', 'data'=>$viewdata);
				}else{
					$response= array('status'=>'201', 'message'=>'Please contact administrator.', 'data'=>null);
				}
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Passcode OR Client OR Device Token Not match.', 'data'=>null);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function login1_post()
	{
		$user_name = $this->post('user_name');
		$user_password = $this->post('user_password');
		$device_token = $this->post('device_token');
		$app_version = $this->post('app_version');
		$window_version = $this->post('window_version');
		$ipaddress = $this->post('ipaddress');
		
		$setting = $this->wb_model->getsingle('setting',array('id'=>'1'));
		
		if($user_name=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if($user_password=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Password.', 'data'=>null);	
		}
		else if($device_token=='')
		{
			$response= array('status'=>'201', 'message'=>'Device Token Required.', 'data'=>null);	
		}		
		else
		{
			$error = "0";
			if($app_version!="")
			{
				
				if($app_version!=$setting->version)
				{
					$error = "1";
					$response= array(
							'status'=>'202', 
							'message'=>'Please update you App',
							'window_app_link' => base_url('App/KuberaWindowSetup.msi'),
							'android_app_link'=> base_url('App/KuberaAndroidApp.apk'),
							'data'=>null
						);
				}
			}
			if($window_version!="")
			{
				
				if($window_version!=$setting->window_version)
				{
					$error = "1";
					$response= array(
							'status'=>'202', 
							'message'=>'Please update you App',
							'window_app_link' => base_url('App/Aplication.msi'),
							'android_app_link'=> base_url('App/Aplication.msi'),
							'data'=>null
						);
				}
			}
			
			if($error=="0")
			{
				//$device_token = '123456';
				
				$users = $this->wb_model->login($user_name,$user_password,$device_token);
				if($users)
				{ 
					$user = $users[0];
					
					if($user->is_user_deleted==0)
					{
						$getcurrent_balance = $this->getcurrent_balance($user->user_master_id);
						
						$transactions = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user->user_master_id),'draw_transaction_master_id','desc','1');
						$bid_amount =  0;
						$bar_code_number = '';
						$bid_amount = 0;
						if($transactions)
						{
							$bar_code_number = $transactions[0]->bar_code_number;
							$totalbid = $this->wb_model->totalbidamount($transactions[0]->draw_transaction_master_id);
							if($totalbid[0]->total!="")
							{
								$bid_amount = $totalbid[0]->total;
							}else{
								$bid_amount =  0;
							}
							
						}else{
							$bar_code_number = "";
						}
						$welcome_message = $this->wb_model->getsingle('welcome',array('welcome_id'=>'1'));
						$WelcomeMessage = $welcome_message->description;
						
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
							'android_version'			=> $setting->version,
							'window_version'			=> $setting->window_version,
							'bar_code_number'			=> $bar_code_number,
							'bid_amount'				=> $bid_amount,
							'current_date_time'			=> date('d-M-Y H:i:s'),
							'WelcomeMessage'			=> $WelcomeMessage,
							'server_date_time'			=> date('Y-m-d H:i:s')
						);
						$this->wb_model->updateData('user_master',array('device_token'=>$device_token),array('user_master_id'=>$user->user_master_id));
						$response= array('status'=>'200', 'message'=>'Login Successfully.', 'data'=>$viewdata);
						
						$logiinsdata = array(
							'user_master_id' 	=> $user->user_master_id,
							'date'				=> date('Y-m-d'),
							'type'				=> 'Login',
							'date_time'			=> date('Y-m-d H:i:s'),
							'ip'				=> $ipaddress
						);
						$this->wb_model->insertData('login_history',$logiinsdata);
					}else{
					$response= array('status'=>'201', 'message'=>'Your account is not active. Please contact us.', 'data'=>null);		
					}
				}
				else
				{
					$response= array('status'=>'201', 'message'=>'Passcode OR Client Code OR Device Id Not Avtive.', 'data'=>null);	
				}
			}
		}
		$this->response($response, 200); // 200 being the HTTP response code
	}
	
	function change_password_post()
	{
		$user_master_id = $this->post('user_master_id');
		$old_password = $this->post('old_password');
		$new_password = $this->post('new_password');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if($old_password=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Old Passcode.', 'data'=>null);	
		}
		else if($new_password=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter New Passcode.', 'data'=>null);	
		}
		else
		{
			$users = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id,'user_password'=>$old_password));
		
			if($users)
			{ 				
				$this->wb_model->updateData('user_master',array('user_password'=>$new_password),array('user_master_id'=>$users->user_master_id));
				$response= array('status'=>'200', 'message'=>'Passcode Update Successfully.', 'data'=>null);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Old Passcode not match.', 'data'=>null);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	
	
	function play_post()
	{		
		$user_master_id = $this->post('user_master_id');
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		//$last_query =  "/*Start Play API */" . " " . $user_master_id;
		//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
		
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		$getcurrent_balance = $this->getcurrent_balance($user_master_id);
		
		$result_date	= Date('Y-m-d'); //$this->post('result_date');
		$draw_master_id	= $this->post('draw_master_id');
		$chkoffdate = $this->common_model->getsingle('offlist',array('off_date'=>$result_date));
		
		$currdrawmasterid = $this->wb_model->getcurrentdrawid();
		
		$is_result = $this->wb_model->getsingle('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));
		if($chkoffdate)
		{
			$response= array('status'=>'201', 'message'=>'Work in Progress. Try Later ', 'data'=>null);	
		}
		
		if($is_result)
		{
			$response= array('status'=>'201', 'message'=>'Draw is Over, Try Again', 'data'=>null);	
		}
		
		if($draw_master_id < $currdrawmasterid)
		{
			$response= array('status'=>'201', 'message'=>'Draw is Over. Please login again', 'data'=>null);	
		}
		
		if($draw_master_id == 0)
		{
			$response= array('status'=>'201', 'message'=>'.Please try on working day between 10am to 10pm', 'data'=>null);	
		}
		
		if($draw_master_id<10)
		{
			$st1 = "0".$draw_master_id;
		}else{
			$st1 = $draw_master_id;
		}
		
		$exists = 1;   
		while($exists > 0)
		{
			$characters = '0123456789';
			$charactersLength = strlen($characters);
			$bar_code_number = '';
			for ($i = 0; $i < 10; $i++) {
				$bar_code_number .= $characters[rand(0, $charactersLength - 1)];
			}
			
			$chkcode1 = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
			if(!$chkcode1)
			{
				$exists = 0;
			}
		}
		
		/*
		$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
		$bar_code_number	= substr(str_shuffle($str_result), 0,10); 
		for($t=0; $t<10;$t++)
		{
			$chk_exist = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
			if($chk_exist)
			{
				$bar_code_number	= "KM".$st1.substr(str_shuffle($str_result), 0,6); 
			}else{
				$t=15;
			}
		} */
		
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
			$response= array('status'=>'201', 'message'=>'Please Enter Coupon Details', 'data'=>null);	
		}
		
		if($getcurrent_balance < $totalbids)
		{
			$response= array('status'=>'201', 'message'=>'Your Coins Balance is Low.', 'data'=>null);
		}
		else if($totalbids < 10)
		{
			$response= array('status'=>'201', 'message'=>'Please Bet minimum 10 Coins.', 'data'=>null);
		}
		
		if($user_data->user_type!=0 and $user_data->user_type!=1)
		{
			$response= array('status'=>'201', 'message'=>'Contact Support.', 'data'=>null);
		}
		
		if($user_data->is_user_deleted==1)
		{
			$response= array('status'=>'201', 'message'=>'Please Contact Your Agent', 'data'=>null);
		}
		
		if($user_data->user_type==0)
		{
			$retailer_user_master_id = $user_data->reporting_user_master_id;
		}else{
			$retailer_user_master_id = $user_data->user_master_id;
		}
		
		
		$last10seconds = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user_master_id,'draw_master_id'=>$draw_master_id),'draw_transaction_master_id','desc','1');
		$ifnoerror = 0;
		if($last10seconds)
		{
			$lastplaytime = $last10seconds[0]->created_date;
			$seconds = 7;
			$fff_date = date("Y-m-d H:i:s", (strtotime(date($lastplaytime)) + $seconds));
			
			if(strtotime(date('Y-m-d H:i:s')) <= strtotime($fff_date))
			{
				$ifnoerror = 1;
				$response= array('status'=>'201', 'message'=>'Created', 'data'=>null);
			}
		}
		
			
		//if(!$is_result && !$chkoffdate && $user_master_id!="" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
		
		//if($ifnoerror == 0 && !$is_result && $chkoffdate && $user_master_id=="4" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
	
		if($ifnoerror == 0 && !$is_result && !$chkoffdate && $user_master_id!="" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
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
							'transaction_narration'		=> 'Coupon Create',
							'transaction_type'			=> '0',
							'transaction_nature'		=> '2',
							'draw_transaction_master_id'=> $insert_id,						
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			$this->wb_model->insertData('points_transactions',$insdata);
			
			$last_query =  "/*Coupon Create R */" . " " . $this->db->last_query();
			$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			
			//$admin_balance = $this->getcurrent_balance('1');
			//$last_query =  "/*admin_balance */" . $insert_id . " AdminBalance " . $admin_balance;
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			$cp = $admin_balance + 	$points_transferred;	
			// point transaction  admin
			$insdata = array(
							'transactions_date' 		=> date('Y-m-d'),						
							'from_user_master_id' 		=> '1',
							'to_user_master_id' 		=> $user_master_id,
							'points_transferred' 		=> $points_transferred,
							'opening_points' 			=> $admin_balance,
							'closing_points' 			=> $cp,
							'transaction_narration'		=> 'Coupon Create',
							'transaction_type'			=> '1',
							'transaction_nature'		=> '2',	
							'draw_transaction_master_id'=> $insert_id,	
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			//$last_query =  "/*B Coupon Create A */" . " " . json_encode($insdata);
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			//$this->wb_model->insertData('points_transactions',$insdata);
			
			//$last_query =  "/*A Coupon Create A */" . " " . $this->db->last_query();
			//this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			if($user_data->user_type=="0")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));				
				$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Client revenue R */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $Retailer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $Retailer_data->user_master_id,
								'created_user_master_id'	=> $Retailer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Client revenue A */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Agent revenue D */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $distributer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $distributer_data->user_master_id,
								'created_user_master_id'	=> $distributer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Agent revenue A */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
			}
			
			if($user_data->user_type=="1")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->user_master_id));				
				$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Client revenue R1 */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $Retailer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $Retailer_data->user_master_id,
								'created_user_master_id'	=> $Retailer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Client revenue A1 */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Agent Revenue D1 */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $distributer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $distributer_data->user_master_id,
								'created_user_master_id'	=> $distributer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Agent Revenue A1 */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
			}		
			
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
			
			$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));
			$fentime = date('H:i',strtotime($draw_data->draw_start_time));
			$insert_data['draw_transaction_master_id']	= $insert_id;			
			$insert_data['draw_start_time'] 			= $draw_data->draw_start_time;
			$insert_data['draw_end_time'] 				= $draw_data->draw_end_time;
			$insert_data['draw_start_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
			$insert_data['draw_end_time_full'] 			= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
			$insert_data['totalbids'] = $totalbids;
			$insert_data['current_balance'] = $getcurrent_balance;
			
			//$last_query =  "/*End Play API */" . " " . $insert_id;
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$insert_data);	
		}
					
        $this->response($response, 200); // 200 being the HTTP response code		
	}
	
	function play_advance_post()
	{		
		$user_master_id = $this->post('user_master_id');
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		//echo "<pre>"; print_r($this->post());die;
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		$getcurrent_balance = $this->getcurrent_balance($user_master_id);
		
		$result_date	= Date('Y-m-d'); //$this->post('result_date');
		
		$draw_master_id_f	= $this->post('draw_master_id');
		$draw_master_ids = explode(',',$draw_master_id_f);
		$chkoffdate = $this->common_model->getsingle('offlist',array('off_date'=>$result_date));
		if($chkoffdate)
		{
			$response= array('status'=>'201', 'message'=>'Contact Your Server Admin', 'data'=>null);	
		}
		
		$ticket_type	= $this->post('ticket_type');
		$is_claim		= $this->post('is_claim');
		$play_data		= $this->post('play_data');
		
		$play_device_type	= $this->post('play_device_type');
		if($play_device_type=="")
		{
			$play_device_type = 0;
		}
		
		if($draw_master_id<10)
		{
			$st1 = "0".$draw_master_id;
		}else{
			$st1 = $draw_master_id;
		}
		if($user_data->user_type==0)
		{
			$retailer_user_master_id = $user_data->reporting_user_master_id;
		}else{
			$retailer_user_master_id = $user_data->user_master_id;
		}
		
		
		//check errors
		$countinue=0;
		$totalbids = "0";
		foreach($draw_master_ids as $draw_master_id)
		{
			$is_result = $this->wb_model->getsingle('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));	
			if($is_result)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'This Draw is Over, Try Again', 'data'=>null);	
			}
			
			$currdrawmasterid = $this->wb_model->getcurrentdrawid();
			if($draw_master_id < $currdrawmasterid)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'This Draw is Over. Please login again', 'data'=>null);
			}
			if($draw_master_id == 0)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Please try on working day between 10am to 10pm', 'data'=>null);	
			}
		
			if(count($play_data)>0)
			{
				$number_of_bids = 0;
				foreach($play_data as $p)
				{
					$totalbids = $totalbids + ( $p['bid_units']*$p['bid_points']*$p['bid_points_multiplier'] );
					//$totalbids = ($p['bid_units']*$p['bid_points']*$p['bid_points_multiplier']);
					$number_of_bids = $number_of_bids + 1;
				}
				
			}else{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Please Enter Coupon Details', 'data'=>null);	
			}
			
			
			if($user_data->user_type!=0 and $user_data->user_type!=1)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Contact Support.', 'data'=>null);
			}
			
			if($user_data->is_user_deleted==1)
			{
				$response= array('status'=>'201', 'message'=>'Please Contact Your Agent', 'data'=>null);
			}
			
			$last10seconds = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user_master_id,'draw_master_id'=>$draw_master_id),'draw_transaction_master_id','desc','1');
			
			if($last10seconds)
			{
				$lastplaytime = $last10seconds[0]->created_date;
				$seconds = 7;
				$fff_date = date("Y-m-d H:i:s", (strtotime(date($lastplaytime)) + $seconds));
				
				if(strtotime(date('Y-m-d H:i:s')) <= strtotime($fff_date))
				{
					$countinue++;
					$response= array('status'=>'201', 'message'=>'Current Draw Time Over', 'data'=>null);
				}
			}
			
		
		}
		
		if($getcurrent_balance < $totalbids)
		{
			$countinue++;
			$response= array('status'=>'201', 'message'=>'Your Coins Balance is Low.', 'data'=>null);
		}
		else if($totalbids < 10)
		{
			$countinue++;
			$response= array('status'=>'201', 'message'=>'Please Bet at least 10 Coins.', 'data'=>null);
		}
			
			
		//print_r($response); die;	
		if(!$chkoffdate && $user_master_id!="" && $countinue==0 )
		{
		$final_insert_data = array();
		foreach($draw_master_ids as $draw_master_id)
		{
			$exists = 1;   
			while($exists > 0)
			{
				$characters = '0123456789';
				$charactersLength = strlen($characters);
				$bar_code_number = '';
				for ($i = 0; $i < 10; $i++) {
					$bar_code_number .= $characters[rand(0, $charactersLength - 1)];
				}
				
				$chkcode1 = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
				if(!$chkcode1)
				{
					$exists = 0;
				}
			} 
			
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
			
			$f_totalbids = 0;
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
				$f_totalbids = $f_totalbids + ( $p['bid_units']*$p['bid_points']*$p['bid_points_multiplier'] );
			}
			
			$points_transferred = $f_totalbids;
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
							'transaction_narration'		=> 'Coupon Create',
							'transaction_type'			=> '0',
							'transaction_nature'		=> '2',
							'draw_transaction_master_id'=> $insert_id,						
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			$this->wb_model->insertData('points_transactions',$insdata);
			
			$last_query =  "/*Coupon Create R */" . " " . $this->db->last_query();
			$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			
			//$admin_balance = $this->getcurrent_balance('1');
			//$last_query =  "/*admin_balance */" . $insert_id . " AdminBalance " . $admin_balance;
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			$cp = $admin_balance + 	$points_transferred;	
			// point transaction  admin
			$insdata = array(
							'transactions_date' 		=> date('Y-m-d'),						
							'from_user_master_id' 		=> '1',
							'to_user_master_id' 		=> $user_master_id,
							'points_transferred' 		=> $points_transferred,
							'opening_points' 			=> $admin_balance,
							'closing_points' 			=> $cp,
							'transaction_narration'		=> 'Coupon Create',
							'transaction_type'			=> '1',
							'transaction_nature'		=> '2',	
							'draw_transaction_master_id'=> $insert_id,	
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			//$last_query =  "/*B Coupon Create A */" . " " . json_encode($insdata);
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			//$this->wb_model->insertData('points_transactions',$insdata);
			
			//$last_query =  "/*A Coupon Create A */" . " " . $this->db->last_query();
			//this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			if($user_data->user_type=="0")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));				
				$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Client revenue R */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $Retailer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $Retailer_data->user_master_id,
								'created_user_master_id'	=> $Retailer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Client revenue A */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Agent Revenue D */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $distributer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Agent revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $distributer_data->user_master_id,
								'created_user_master_id'	=> $distributer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Agent Revenue A */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
			}
			
			if($user_data->user_type=="1")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->user_master_id));				
				$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Client revenue R1 */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $Retailer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Client revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $Retailer_data->user_master_id,
								'created_user_master_id'	=> $Retailer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Client revenue A1 */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
				
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
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> '1',
								'created_user_master_id'	=> '1',
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$last_query =  "/*Agent Revenue D1 */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
				$cpp = $admin_balance - $comm_points;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $distributer_data->user_master_id,
								'points_transferred' 		=> $comm_points,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cpp,
								'transaction_narration'		=> 'Agent Revenue',
								'transaction_type'			=> '0',
								'transaction_nature'		=> '1',							
								'user_master_id' 			=> $distributer_data->user_master_id,
								'created_user_master_id'	=> $distributer_data->user_master_id,
								'draw_transaction_master_id'=> $insert_id,	
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Agent revenue A1 */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
			}		
			
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
			
			$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));
			$fentime = date('H:i',strtotime($draw_data->draw_start_time));
			$insert_data['draw_transaction_master_id']	= $insert_id;			
			$insert_data['draw_start_time'] 			= $draw_data->draw_start_time;
			$insert_data['draw_end_time'] 				= $draw_data->draw_end_time;
			$insert_data['draw_start_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
			$insert_data['draw_end_time_full'] 			= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
			$insert_data['totalbids'] = $totalbids;
			$insert_data['current_balance'] = $getcurrent_balance;
			
			//$last_query =  "/*End Play API */" . " " . $insert_id;
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			$final_insert_data[] = $insert_data;
		}
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$final_insert_data);	
		}
					
        $this->response($response, 200); // 200 being the HTTP response code		
	}
	
	function advance_play121_post()
	{		
		$user_master_id = $this->post('user_master_id');
		$play_device_type	= $this->post('play_device_type');
		
		if($play_device_type=="")
		{
			$play_device_type = 0;
		}
		
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		$getcurrent_balance = $this->getcurrent_balance($user_master_id);
		
		$result_date	= Date('Y-m-d'); 		
		$chkoffdate = $this->common_model->getsingle('offlist',array('off_date'=>$result_date));
		if($chkoffdate)
		{
			$response= array('status'=>'201', 'message'=>'It is Holiday Today', 'data'=>null);	
		}
						
		//check errors
		$countinue=0;
		$totalbids = "0";
		
		$drawsdata = $this->post('draw_data');
		
		foreach($drawsdata as $draw)
		{
			$draw_master_id	= $draw['draw_master_id'];
			$ticket_type	= $draw['ticket_type'];
			$is_claim	= $draw['is_claim'];
			$play_data	= $draw['play_data'];
			
			$currdrawmasterid = $this->wb_model->getcurrentdrawid();
			$is_result = $this->wb_model->getsingle('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));
			if($is_result)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Draw is Over, Try Again', 'data'=>null);	
			}
			
			if($draw_master_id < $currdrawmasterid)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Current Draw Over. Please login again', 'data'=>null);	
			}
			
			if($draw_master_id == 0)
			{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Please try on working day between 10am to 10pm', 'data'=>null);	
			}
			
			if(count($play_data)>0)
			{
				foreach($play_data as $p)
				{
					$totalbids = $totalbids + ( $p['bid_units']*$p['bid_points']*$p['bid_points_multiplier'] );
				}
				
			}else{
				$countinue++;
				$response= array('status'=>'201', 'message'=>'Please Enter Coupon Details', 'data'=>null);	
			}
			
		}
				
		if($getcurrent_balance < $totalbids)
		{
			$countinue++;
			$response= array('status'=>'201', 'message'=>'Your Coins Balance is Low.', 'data'=>null);
		}
		else if($totalbids < 10)
		{
			$countinue++;
			$response= array('status'=>'201', 'message'=>'Please Bet minimum 10 Coins.', 'data'=>null);
		}
		
		if($user_data->user_type!=0 and $user_data->user_type!=1)
		{
			$countinue++;
			$response= array('status'=>'201', 'message'=>'Contact Support.', 'data'=>null);
		}
		
		
		
		if( $user_master_id!="" && !$chkoffdate && $countinue==0)
		{
			$final_insert_data = array();
			foreach($drawsdata as $draw)
			{
				$draw_master_id	= $draw['draw_master_id'];
				$ticket_type	= $draw['ticket_type'];
				$is_claim	= $draw['is_claim'];
				$play_data	= $draw['play_data'];
				
					$totalbids = 0;
					foreach($play_data as $p)
					{
						$totalbids = $totalbids + ( $p['bid_units']*$p['bid_points']*$p['bid_points_multiplier'] );
					}
					if($draw_master_id<10)
					{
						$st1 = "0".$draw_master_id;
					}else{
						$st1 = $draw_master_id;
					}
					$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';			
					/*
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
					} */
					
					$exists = 1;   
					while($exists > 0)
					{
						$characters = '0123456789';
						$charactersLength = strlen($characters);
						$bar_code_number = '';
						for ($i = 0; $i < 10; $i++) {
							$bar_code_number .= $characters[rand(0, $charactersLength - 1)];
						}
						
						$chkcode1 = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
						if(!$chkcode1)
						{
							$exists = 0;
						}
					} 
					
					if($user_data->user_type==0)
					{
						$retailer_user_master_id = $user_data->reporting_user_master_id;
					}else{
						$retailer_user_master_id = $user_data->user_master_id;
					}
					
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
									'transaction_narration'		=> 'Coupon Create',
									'transaction_type'			=> '0',
									'transaction_nature'		=> '2',
									'draw_transaction_master_id'=> $insert_id,						
									'user_master_id' 			=> $user_master_id,
									'created_user_master_id'	=> $user_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					//$admin_balance = $this->getcurrent_balance('1');
					//$cp = $admin_balance + 	$points_transferred;	
					// point transaction  admin
					/*$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $user_master_id,
									'points_transferred' 		=> $points_transferred,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Coupon Create',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '2',	
									'draw_transaction_master_id'=> $insert_id,	
									'user_master_id' 			=> $user_master_id,
									'created_user_master_id'	=> $user_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);*/
					
					if($user_data->user_type=="0")
					{
						$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));				
						$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
						
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
										'transaction_narration'		=> 'Client revenue',
										'transaction_type'			=> '1',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> '1',
										'created_user_master_id'	=> '1',
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);
						
						//admin
						//$admin_balance = $this->getcurrent_balance('1');
						//$cpp = $admin_balance - $comm_points;	
						// point transaction  admin
						/*$insdata = array(
										'transactions_date' 		=> date('Y-m-d'),						
										'from_user_master_id' 		=> '1',
										'to_user_master_id' 		=> $Retailer_data->user_master_id,
										'points_transferred' 		=> $comm_points,
										'opening_points' 			=> $admin_balance,
										'closing_points' 			=> $cpp,
										'transaction_narration'		=> 'Client revenue',
										'transaction_type'			=> '0',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> $Retailer_data->user_master_id,
										'created_user_master_id'	=> $Retailer_data->user_master_id,
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);*/
						
						
						$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
						$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
						
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
										'transaction_narration'		=> 'Agent Revenue',
										'transaction_type'			=> '1',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> '1',
										'created_user_master_id'	=> '1',
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);
						
						//admin
						//$admin_balance = $this->getcurrent_balance('1');
						$cpp = $admin_balance - $comm_points;	
						// point transaction  admin
						/*$insdata = array(
										'transactions_date' 		=> date('Y-m-d'),						
										'from_user_master_id' 		=> '1',
										'to_user_master_id' 		=> $distributer_data->user_master_id,
										'points_transferred' 		=> $comm_points,
										'opening_points' 			=> $admin_balance,
										'closing_points' 			=> $cpp,
										'transaction_narration'		=> 'Agent Revenue',
										'transaction_type'			=> '0',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> $distributer_data->user_master_id,
										'created_user_master_id'	=> $distributer_data->user_master_id,
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);*/
					}
					
					if($user_data->user_type=="1")
					{
						$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->user_master_id));				
						$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
						
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
										'transaction_narration'		=> 'Client revenue',
										'transaction_type'			=> '1',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> '1',
										'created_user_master_id'	=> '1',
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);
						
						//admin
						//$admin_balance = $this->getcurrent_balance('1');
						//$cpp = $admin_balance - $comm_points;	
						// point transaction  admin
						/*$insdata = array(
										'transactions_date' 		=> date('Y-m-d'),						
										'from_user_master_id' 		=> '1',
										'to_user_master_id' 		=> $Retailer_data->user_master_id,
										'points_transferred' 		=> $comm_points,
										'opening_points' 			=> $admin_balance,
										'closing_points' 			=> $cpp,
										'transaction_narration'		=> 'Client revenue',
										'transaction_type'			=> '0',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> $Retailer_data->user_master_id,
										'created_user_master_id'	=> $Retailer_data->user_master_id,
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);*/
						
						
						$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
						$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
						
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
										'transaction_narration'		=> 'Agent Revenue',
										'transaction_type'			=> '1',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> '1',
										'created_user_master_id'	=> '1',
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);
						
						//admin
						//$admin_balance = $this->getcurrent_balance('1');
						//$cpp = $admin_balance - $comm_points;	
						// point transaction  admin
						/*$insdata = array(
										'transactions_date' 		=> date('Y-m-d'),						
										'from_user_master_id' 		=> '1',
										'to_user_master_id' 		=> $distributer_data->user_master_id,
										'points_transferred' 		=> $comm_points,
										'opening_points' 			=> $admin_balance,
										'closing_points' 			=> $cpp,
										'transaction_narration'		=> 'Agent Revenue',
										'transaction_type'			=> '0',
										'transaction_nature'		=> '1',							
										'user_master_id' 			=> $distributer_data->user_master_id,
										'created_user_master_id'	=> $distributer_data->user_master_id,
										'draw_transaction_master_id'=> $insert_id,	
										'created_date' 				=> date('Y-m-d H:i:s')
								);
						$this->wb_model->insertData('points_transactions',$insdata);*/				
					}		
					
					$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));
					$fentime = date('H:i',strtotime($draw_data->draw_start_time));
					$insert_data['draw_transaction_master_id']	= $insert_id;			
					$insert_data['draw_start_time'] 			= $draw_data->draw_start_time;
					$insert_data['draw_end_time'] 				= $draw_data->draw_end_time;
					$insert_data['draw_start_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$insert_data['draw_end_time_full'] 			= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
					$insert_data['totalbids'] = $totalbids;
					
					$getcurrent_balance = $this->getcurrent_balance($user_master_id);
					$insert_data['balance_points'] = $getcurrent_balance;
					
					$final_insert_data[] = $insert_data;
			}
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
			
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$final_insert_data,'balance_points'=>$getcurrent_balance);	
		}
					
		$this->response($response, 200); // 200 being the HTTP response code		
	}

	function last_transaction_all_post()
	{		
		$user_master_id = $this->post('user_master_id');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}else{
			$setting = $this->common_model->getsingle('setting',array('id'=>'1'));	
			$limit = $setting->returning_history_records*10;
			$data = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user_master_id),'draw_transaction_master_id','desc',$limit);
			
			$final_data = array();
			//$no=1;
			for($i=0;$i<count($data);$i++)
			{
				
				
				$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$data[$i]->draw_master_id));
				$total_play_points = $this->wb_model->getsumplay($data[$i]->draw_transaction_master_id);
				
				$fftime = date('H:i:s',strtotime($draw_data->draw_start_time)); 
				$ctime = strtotime(date('Y-m-d H:i:s'));
				$drawtime = strtotime(date($data[$i]->result_date.' '.$fftime));
				//if($ctime >= $drawtime)
				//{
					
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
					$f['is_winning'] 						= $data[$i]->is_winning;	
					$f['created_date'] 						= $data[$i]->created_date;	
					$f['draw_start_time'] 					= $draw_data->draw_start_time;
					$f['draw_end_time'] 					= $draw_data->draw_end_time;
					$f['draw_start_time_full'] 				= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
					$f['draw_end_time_full'] 				= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));						
					$final_data[] 							= $f;
				
					/*if($no >=$setting->returning_history_records)
					{
						$i = $limit+100;
					}*/
				//$no++;
				//}
			}
			
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$final_data);
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code		
		
	}

	function play_carpenter_post()
	{		
		$user_master_id = $this->post('user_master_id');
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		//$last_query =  "/*Start Play API */" . " " . $user_master_id;
		//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
		
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		$getcurrent_balance = $this->getcurrent_balance($user_master_id);
		
		$result_date	= Date('Y-m-d'); //$this->post('result_date');
		$draw_master_id	= $this->post('draw_master_id');
		$chkoffdate = $this->common_model->getsingle('offlist',array('off_date'=>$result_date));
		
		$currdrawmasterid = $this->wb_model->getcurrentdrawid();
		
		$is_result = $this->wb_model->getsingle('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));
		if($chkoffdate)
		{
			$response= array('status'=>'201', 'message'=>'Contact Your Server Admin', 'data'=>null);	
		}
		
		if($is_result)
		{
			$response= array('status'=>'201', 'message'=>'Draw is Over, Try Again', 'data'=>null);	
		}
		
		if($draw_master_id < $currdrawmasterid)
		{
			$response= array('status'=>'201', 'message'=>'Draw is Over. Please login again', 'data'=>null);	
		}
		
		if($draw_master_id == 0)
		{
			$response= array('status'=>'201', 'message'=>'Please login next day between 10am to 10pm', 'data'=>null);	
		}
		
		if($draw_master_id<10)
		{
			$st1 = "0".$draw_master_id;
		}else{
			$st1 = $draw_master_id;
		}
	
		$exists = 1;   
		while($exists > 0)
		{
			$characters = '0123456789';
			$charactersLength = strlen($characters);
			$bar_code_number = '';
			for ($i = 0; $i < 10; $i++) {
				$bar_code_number .= $characters[rand(0, $charactersLength - 1)];
			}
			
			$chkcode1 = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number));
			if(!$chkcode1)
			{
				$exists = 0;
			}
		} 
		
		/*$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		
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
		}*/
		
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
			$response= array('status'=>'201', 'message'=>'Please Enter Coupon Details', 'data'=>null);	
		}
		
		if($getcurrent_balance < $totalbids)
		{
			$response= array('status'=>'201', 'message'=>'Your Coins Balance is Low.', 'data'=>null);
		}
		else if($totalbids < 10)
		{
			$response= array('status'=>'201', 'message'=>'Please Bet at least 10 Coins.', 'data'=>null);
		}
		
		if($user_data->user_type!=0 and $user_data->user_type!=1)
		{
			$response= array('status'=>'201', 'message'=>'Contact Support.', 'data'=>null);
		}
		
		if($user_data->is_user_deleted==1)
		{
			$response= array('status'=>'201', 'message'=>'Please Contact Your Agent', 'data'=>null);
		}
		
		if($user_data->user_type==0)
		{
			$retailer_user_master_id = $user_data->reporting_user_master_id;
		}else{
			$retailer_user_master_id = $user_data->user_master_id;
		}
		
		
		$last10seconds = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user_master_id,'draw_master_id'=>$draw_master_id),'draw_transaction_master_id','desc','1');
		$ifnoerror = 0;
		if($last10seconds)
		{
			$lastplaytime = $last10seconds[0]->created_date;
			$seconds = 5;
			$fff_date = date("Y-m-d H:i:s", (strtotime(date($lastplaytime)) + $seconds));
			
			if(strtotime(date('Y-m-d H:i:s')) <= strtotime($fff_date))
			{
				$ifnoerror = 1;
				//$response= array('status'=>'201', 'message'=>'Done', 'data'=>null);
			}
		}
		
			
		//if(!$is_result && !$chkoffdate && $user_master_id!="" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
		
		//if($ifnoerror == 0 && !$is_result && $chkoffdate && $user_master_id=="4" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
	
		if($ifnoerror == 0 && !$is_result && !$chkoffdate && $user_master_id!="" && count($play_data)>0 && $totalbids >= 10 && $getcurrent_balance >= $totalbids && ($user_data->user_type==0 or $user_data->user_type==1) && $draw_master_id >= $currdrawmasterid && $draw_master_id>0)
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
							'transaction_narration'		=> 'Coupon Generated',
							'transaction_type'			=> '0',
							'transaction_nature'		=> '2',
							'draw_transaction_master_id'=> $insert_id,						
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			$this->wb_model->insertData('points_transactions',$insdata);
			
			$last_query =  "/*Coupon Generated R */" . " " . $this->db->last_query();
			$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			
			//$admin_balance = $this->getcurrent_balance('1');
			//$last_query =  "/*admin_balance */" . $insert_id . " AdminBalance " . $admin_balance;
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			$cp = $admin_balance + 	$points_transferred;	
			// point transaction  admin
			$insdata = array(
							'transactions_date' 		=> date('Y-m-d'),						
							'from_user_master_id' 		=> '1',
							'to_user_master_id' 		=> $user_master_id,
							'points_transferred' 		=> $points_transferred,
							'opening_points' 			=> $admin_balance,
							'closing_points' 			=> $cp,
							'transaction_narration'		=> 'Coupon Generated',
							'transaction_type'			=> '1',
							'transaction_nature'		=> '2',	
							'draw_transaction_master_id'=> $insert_id,	
							'user_master_id' 			=> $user_master_id,
							'created_user_master_id'	=> $user_master_id,
							'created_date' 				=> date('Y-m-d H:i:s')
					);
			//$last_query =  "/*B Coupon Generated A */" . " " . json_encode($insdata);
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			//$this->wb_model->insertData('points_transactions',$insdata);
			
			//$last_query =  "/*A Coupon Generated A */" . " " . $this->db->last_query();
			//this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			if($user_data->user_type=="0")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->reporting_user_master_id));				
				$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
				
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
				
				$last_query =  "/*Retailer commission R */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
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
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Retailer commission A */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
				
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
				
				$last_query =  "/*Distributor Commission D */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
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
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Distributor Commission A */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
			}
			
			if($user_data->user_type=="1")
			{
				$Retailer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_data->user_master_id));				
				$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
				
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
				
				$last_query =  "/*Retailer commission R1 */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
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
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Retailer commission A1 */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				$distributer_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$Retailer_data->reporting_user_master_id));				
				$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
				
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
				
				$last_query =  "/*Distributor Commission D1 */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
				//admin
				//$admin_balance = $this->getcurrent_balance('1');
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
				//$this->wb_model->insertData('points_transactions',$insdata);
				
				//$last_query =  "/*Distributor Commission A1 */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
				
			}		
			
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
			
			$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$draw_master_id));
			$fentime = date('H:i',strtotime($draw_data->draw_start_time));
			$insert_data['draw_transaction_master_id']	= $insert_id;			
			$insert_data['draw_start_time'] 			= $draw_data->draw_start_time;
			$insert_data['draw_end_time'] 				= $draw_data->draw_end_time;
			$insert_data['draw_start_time_full'] 		= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':01'));
			$insert_data['draw_end_time_full'] 			= date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$draw_data->draw_end_time));
			$insert_data['totalbids'] = $totalbids;
			$insert_data['current_balance'] = $getcurrent_balance;
			
			//$last_query =  "/*End Play API */" . " " . $insert_id;
			//$this->common_model->insertData('q_info_18082020',array('info'=>$last_query));
			
			$response= array('status'=>'200', 'message'=>'Successfully.', 'data'=>$insert_data);	
		}
					
        $this->response($response, 200); // 200 being the HTTP response code		
	}
	
	function cancel_transaction_banana_post()
	{
		$setting = $this->wb_model->getsingle('setting',array('id'=>'1'));
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}
		else if($bar_code_number=='')
		{
			$response= array('status'=>'201', 'message'=>'Coupon Code/BARCode Code Required.', 'data'=>null);	
		}
		else if(!$bar_code_data)
		{
			$response= array('status'=>'201', 'message'=>'Coupon Code/BARCode Code Does Not Exist.', 'data'=>null);	
		}
		else if($chkalrady)
		{
			//$response= array('status'=>'201', 'message'=>'Alrady Cancel This Transaction.', 'data'=>null);
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
			//$insert_data['current_balance'] = $getcurrent_balance;
			$response= array('status'=>'201', 'message'=>'This Coupon/BARCode is Removed', 'current_balance'=> $getcurrent_balance);
		}
		else if($ctime > $en_date_time)
		{
			$response= array('status'=>'201', 'message'=>'Current Draw is Over. Coupon/BARCode Removal is Not Allowed', 'data'=>null);	
		}
		else if($results)
		{
			$response= array('status'=>'201', 'message'=>'Draw Result Declared.', 'data'=>null);	
		}
		else if(count($countdeletes)>=$setting->cancle_per_day_limit)
		{
			$response= array('status'=>'201', 'message'=>'You Have Already Removed Maximum Tickets. Can not Remove More', 'data'=>null);	
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
				
				$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);
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
					$admin_balance = $this->getcurrent_balance('1');
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
					
					
					$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
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
					$admin_balance = $this->getcurrent_balance('1');
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
					
					$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
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
					$admin_balance = $this->getcurrent_balance('1');
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
					
					$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
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
					$admin_balance = $this->getcurrent_balance('1');
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
					
					$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
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
				
				
				$admin_balance = $this->getcurrent_balance('1');
				$cp = $admin_balance - 	$points_transferred;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $bar_code_data->user_master_id,
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Coupon Cancelled',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '7',	
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,	
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);				
				$closing_points		= $getcurrent_balance + $points_transferred;
				// point transaction 
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $bar_code_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $getcurrent_balance,
								'closing_points' 			=> $closing_points,
								'transaction_narration'		=> 'Coupon Cancelled',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '7',
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,						
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance = $this->getcurrent_balance($user_master_id);
				$insert_data['current_balance'] = $getcurrent_balance;
							
				//$response= array('status'=>'200', 'message'=>'Transaction Canceld Successfully.', 'data'=>$insert_data);
				$response= array('status'=>'200', 'message'=>'Coupon/BARCode Removed Successfully.', 'current_balance'=> $getcurrent_balance);
			}else{
				
				$response= array('status'=>'201', 'message'=>'No Such Coupon/BARCode.', 'data'=>null);
			}
			
		}		
		
		$this->response($response, 200); // 200 being the HTTP response code		
		
	}

	function last_transaction_post()
	{		
		$user_master_id = $this->post('user_master_id');
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}else{
			$setting = $this->common_model->getsingle('setting',array('id'=>'1'));	
			$limit = $setting->returning_history_records*10;
			$data = $this->wb_model->getAllwhere_order_by('draw_transaction_master',array('user_master_id'=>$user_master_id),'draw_transaction_master_id','desc',$limit);
			
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
				
					if($no >=$setting->returning_history_records)
					{
						$i = $limit+100;
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
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}
		else if($bar_code_number=='')
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Required.', 'data'=>null);	
		}
		else if(!$bar_code_data)
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Does Not Exist.', 'data'=>null);
		}
		else if($chkalrady)
		{
			//$response= array('status'=>'201', 'message'=>'Alrady Cancel This Transaction.', 'data'=>null);
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
			//$insert_data['current_balance'] = $getcurrent_balance;
			$response= array('status'=>'201', 'message'=>'Already Removed This Coupon/BARCode.', 'current_balance'=> $getcurrent_balance);
		}
		else if($ctime > $en_date_time)
		{
			$response= array('status'=>'201', 'message'=>'Current Draw is Finished. Removal of Coupon/BARCode is not allowed after Draw is over.', 'data'=>null);	
		}
		else if($results)
		{
			$response= array('status'=>'201', 'message'=>'Result Declared.', 'data'=>null);	
		}
		else if(count($countdeletes)>=$setting->cancle_per_day_limit)
		{
			$response= array('status'=>'201', 'message'=>'Maximum Removal Is Done For Today.', 'data'=>null);	
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
				
				$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);
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
					$admin_balance = $this->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $distributer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Agent Revenue Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					
					$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
					$cp = $distributer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $distributer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $distributer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Agent Revenue Remove',
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
					$admin_balance = $this->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $Retailer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Client revenue Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
					$cp = $retailer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $Retailer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $retailer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Client revenue Remove',
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
					$admin_balance = $this->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $distributer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Agent Revenue Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$distributer_balance = $this->getcurrent_balance($distributer_data->user_master_id);
					$cp = $distributer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $distributer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $distributer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Agent Revenue Remove',
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
					$admin_balance = $this->getcurrent_balance('1');
					$cpp = $admin_balance + $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> '1',
									'to_user_master_id' 		=> $Retailer_data->user_master_id,
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $admin_balance,
									'closing_points' 			=> $cpp,
									'transaction_narration'		=> 'Client revenue Remove',
									'transaction_type'			=> '1',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> '1',
									'created_user_master_id'	=> '1',
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
					$retailer_balance = $this->getcurrent_balance($Retailer_data->user_master_id);
					$cp = $retailer_balance - $comm_points;	
					// point transaction  admin
					$insdata = array(
									'transactions_date' 		=> date('Y-m-d'),						
									'from_user_master_id' 		=> $Retailer_data->user_master_id,
									'to_user_master_id' 		=> '1',
									'points_transferred' 		=> $comm_points,
									'opening_points' 			=> $retailer_balance,
									'closing_points' 			=> $cp,
									'transaction_narration'		=> 'Client revenue Remove',
									'transaction_type'			=> '0',
									'transaction_nature'		=> '6',							
									'user_master_id' 			=> $Retailer_data->user_master_id,
									'created_user_master_id'	=> $Retailer_data->user_master_id,
									'draw_transaction_master_id'=> $draw_transaction_master_id,
									'created_date' 				=> date('Y-m-d H:i:s')
							);
					$this->wb_model->insertData('points_transactions',$insdata);
					
									
				}
				
				
				$admin_balance = $this->getcurrent_balance('1');
				$cp = $admin_balance - 	$points_transferred;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $bar_code_data->user_master_id,
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Coupon remove',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '7',	
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,	
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);				
				$closing_points		= $getcurrent_balance + $points_transferred;
				// point transaction 
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> $bar_code_data->user_master_id,
								'to_user_master_id' 		=> '1',
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $getcurrent_balance,
								'closing_points' 			=> $closing_points,
								'transaction_narration'		=> 'Coupon remove',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '7',
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,						
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$getcurrent_balance = $this->getcurrent_balance($user_master_id);
				$insert_data['current_balance'] = $getcurrent_balance;
							
				//$response= array('status'=>'200', 'message'=>'Transaction Canceld Successfully.', 'data'=>$insert_data);
				$response= array('status'=>'200', 'message'=>'Coupon/BARCode Removed Successfully.', 'current_balance'=> $getcurrent_balance);
			}else{
				
				$response= array('status'=>'201', 'message'=>'No Coupon/BARCode Exists.', 'data'=>null);
			}
			
		}		
		
		$this->response($response	, 200); // 200 being the HTTP response code		
		
	} 
	
	function claim_post()
	{
		$setting = $this->wb_model->getsingle('setting',array('id'=>'1'));	
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($bar_code_number=='')
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Required.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if(!$bar_code_data)
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Not Exist OR Coupon/BARCode not created by Us.', 'current_balance'=> $getcurrent_balance, 'data'=>null);
		}
		else if($bar_code_data->is_deleted=="1")
		{
			$response= array('status'=>'201', 'message'=>'This Coupon/BARCode Is Already Removed. Can not claimed', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
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
				
				//$response= array('status'=>'201', 'message'=>'Claim already settled on '.$claim_date_time.'. Winning amount is '.$totalbids.'.', 'data'=>null);
				$response= array('status'=>'201', 'message'=>'Coupon/BARCode already claimed on '.$claim_date_time, 'current_balance'=> $getcurrent_balance, 'data'=>null);
			}else{
				//$response= array('status'=>'201', 'message'=>'Claim already settled on '.$claim_date_time.'. No Winning amount is '.$totalbids.'.', 'data'=>null);
				$response= array('status'=>'201', 'message'=>'Sorry No Winning Today. Hard luck. Please Play Again To Win More.');	
			}
		}
		else if($ctime < $entime )
		{
			$response= array('status'=>'201', 'message'=>'Current Draw Is Yet To Finish.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($bar_code_data->is_result==0)
		{
			$response= array('status'=>'201', 'message'=>'Current Draw Is Yet To Finish. Please Claim after some time.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($ctime > $addtendays )
		{
			$response= array('status'=>'201', 'message'=>$ctime .'-'.$addtendays.'Coupon/BARCode Expired, Claim Date/Time Over.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
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
				
				$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);
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
								'transaction_narration'		=> 'Won',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '3',
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,						
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$admin_balance = $this->getcurrent_balance('1');
				$cp = $admin_balance - 	$points_transferred;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $bar_code_data->user_master_id,
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Won',
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
					$response= array('status'=>'200', 'message'=>'Sorry No Winning Today. Play More to Win More. Please Bet Again', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
				}else{
					$getcurrent_balance = $this->getcurrent_balance($user_master_id);
					//$response= array('status'=>'201', 'message'=>'Already Cancel This Transaction.', 'current_balance'=> $getcurrent_balance);
					
					$response= array('status'=>'200', 'message'=>'Many Many Congratulations. Today You Won '.$totalbids.'/-!  Play More to Win More.', 'current_balance'=> $getcurrent_balance, 'data'=>$final_d);
				}
			}else{
				$this->common_model->updateData('draw_transaction_details',array('is_claim'=>'1'),array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id));				
				$this->common_model->updateData('draw_transaction_master',array('claim_date_time'=>date('Y-m-d H:i:s'),'is_claim'=>'1','claim_user_master_id'=>$user_master_id),array('bar_code_number'=>$bar_code_number));
			
				$response= array('status'=>'200', 'message'=>'Sorry No Winning Today. Play More to Win More. Please Bet Again', 'current_balance'=> $getcurrent_balance, 'data'=>null);
			}
		}

		$this->response($response, 200); // 200 being the HTTP response code		
	} 
	
	function new_claim_post()
	{
		$setting = $this->wb_model->getsingle('setting',array('id'=>'1'));	
		
		$ipaddress = $this->post('ipaddress');
		$device_token = $this->post('device_token');
		
		$user_master_id = $this->post('user_master_id');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		//draw_master_id
		
		$bar_code_number = $this->post('bar_code_number');
		$bar_code_data = $this->wb_model->getsingle('draw_transaction_master',array('bar_code_number'=>$bar_code_number,'user_master_id'=>$user_master_id));
		
		$play_data = $this->wb_model->getclaimdatat($bar_code_number);
		
		//echo "<pre>"; print_r($play_data); die;
		
		$draw_data = $this->wb_model->getsingle('draw_master',array('draw_master_id'=>$bar_code_data->draw_master_id));
		
		$ctime 			= strtotime(date('Y-m-d H:i:s'));
		$entime 		= strtotime($bar_code_data->result_date.' '.$draw_data->draw_end_time);
		$addtenminit 	= strtotime(date('Y-m-d H:i:s', strtotime('+10 minutes', $entime)));
		$addtendays 	= strtotime(date('Y-m-d', strtotime('+'.$setting->claim_before_day.' day', $entime)));
			
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($bar_code_number=='')
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Required.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if(!$bar_code_data)
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Does Not Exist OR Coupon/BARCode is created by Us', 'current_balance'=> $getcurrent_balance, 'data'=>null);
		}
		else if($bar_code_data->is_deleted=="1")
		{
			$response= array('status'=>'201', 'message'=>'This Coupon/BARCode Is Already Removed', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($bar_code_data->is_claim=="1")
		{
			$play_data = $this->wb_model->getclaimdatat($bar_code_number);
			//$play_data = $this->wb_model->getAllwhere('draw_transaction_details',array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id,'is_winning'=>'1'));
			$claim_date_time = date('d-M-Y h:i A',strtotime($bar_code_data->claim_date_time));
			
			$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);
			
			$totalbids = 0;
			$final_d = array();
			if(count($play_data)>0)
			{
				//$response= array('status'=>'201', 'message'=>'Claim already settled on '.$claim_date_time.'. Winning amount is '.$totalbids.'.', 'data'=>null);
				$response= array('status'=>'201', 'message'=>'Coupon/BARCode already claimed on '.$claim_date_time, 'current_balance'=> $getcurrent_balance, 'data'=>null);
			}else{
				//$response= array('status'=>'201', 'message'=>'Claim already settled on '.$claim_date_time.'. No Winning amount is '.$totalbids.'.', 'data'=>null);
				$response= array('status'=>'201', 'message'=>'Sorry No Winning Today. Play More To Win More. Try Again.');	
			}
		}
		else if($ctime < $entime )
		{
			$response= array('status'=>'201', 'message'=>'Currently draw is not over yet.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($bar_code_data->is_result==0)
		{
			$response= array('status'=>'201', 'message'=>'Result is not declared. Please try after some time.', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else if($ctime > $addtendays )
		{
			$response= array('status'=>'201', 'message'=>$ctime .'-'.$addtendays.'Claim Date/Time is Over', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
		}
		else
		{
			$play_data = $this->wb_model->getclaimdatat($bar_code_number);
			//$play_data = $this->wb_model->getAllwhere('draw_transaction_details',array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id,'is_winning'=>'1'));			
				
			if(count($play_data)>0)
			{
				$this->common_model->updateData('draw_transaction_master',array('is_winning'=>'1'),array('bar_code_number'=>$bar_code_number));
				
				$totalbids = 0;
				$final_d = array();
				foreach($play_data as $p)
				{
					$totalbids = $totalbids + ( $p->bid_units*$p->bid_points*$p->bid_points_multiplier*90 );
					$this->common_model->updateData('draw_transaction_details',array('claim_user_master_id'=>$user_master_id,'is_winning'=>'1'),array('draw_transaction_details_id'=>$p->draw_transaction_details_id));
					
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
				
				$getcurrent_balance = $this->getcurrent_balance($bar_code_data->user_master_id);
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
								'transaction_narration'		=> 'Won',
								'transaction_type'			=> '1',
								'transaction_nature'		=> '3',
								'draw_transaction_master_id'=> $bar_code_data->draw_transaction_master_id,						
								'user_master_id' 			=> $bar_code_data->user_master_id,
								'created_user_master_id'	=> $bar_code_data->user_master_id,
								'created_date' 				=> date('Y-m-d H:i:s')
						);
				$this->wb_model->insertData('points_transactions',$insdata);
				
				$admin_balance = $this->getcurrent_balance('1');
				$cp = $admin_balance - 	$points_transferred;	
				// point transaction  admin
				$insdata = array(
								'transactions_date' 		=> date('Y-m-d'),						
								'from_user_master_id' 		=> '1',
								'to_user_master_id' 		=> $bar_code_data->user_master_id,
								'points_transferred' 		=> $points_transferred,
								'opening_points' 			=> $admin_balance,
								'closing_points' 			=> $cp,
								'transaction_narration'		=> 'Won',
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
					$response= array('status'=>'200', 'message'=>'Sorry No Winning Today. Play More to Win More. Please bet again', 'current_balance'=> $getcurrent_balance, 'data'=>null);	
				}else{
					$getcurrent_balance = $this->getcurrent_balance($user_master_id);
					//$response= array('status'=>'201', 'message'=>'Already Cancel This Transaction.', 'current_balance'=> $getcurrent_balance);
					
					$response= array('status'=>'200', 'message'=>'Many Many Congratulations. Today You Won'.$totalbids.'/-!  Play More to Win More.', 'current_balance'=> $getcurrent_balance, 'data'=>$final_d);
				}
			}else{
				$this->common_model->updateData('draw_transaction_details',array('is_claim'=>'1'),array('draw_transaction_master_id'=>$bar_code_data->draw_transaction_master_id));				
				$this->common_model->updateData('draw_transaction_master',array('claim_date_time'=>date('Y-m-d H:i:s'),'is_claim'=>'1','claim_user_master_id'=>$user_master_id),array('bar_code_number'=>$bar_code_number));
			
				$response= array('status'=>'200', 'message'=>'Sorry No Winning Today. Play More to Win More. Please Bet Again', 'current_balance'=> $getcurrent_balance, 'data'=>null);
			}
		}

		$this->response($response, 200); // 200 being the HTTP response code		
	}
	
	function transaction_details_post()
	{		
		$draw_transaction_master_id = $this->post('draw_transaction_master_id');
		if($draw_transaction_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Coupon/BARCode Required.', 'bar_code_number'=>null,'data'=>null);	
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}		
		else
		{
			$getcurrent_balance = $this->getcurrent_balance($user_master_id);
		
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
			$response= array('status'=>'201','current_date_time'=>$current_date_time, 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if($type=='')
		{
			$response= array('status'=>'201','current_date_time'=>$current_date_time, 'message'=>'Type Required.', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201','current_date_time'=>$current_date_time, 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
	
	//$final_data = $this->wb_model->getAllwhere_result_join($draw_master_id,$result_date);			
	function result2_post()
	{
		$user_master_id = $this->post('user_master_id');
		$draw_master_id = $this->post('draw_master_id');
		$result_date 	= $this->post('result_date');
		
		//$result_date = date("Y-m-d", strtotime("- 1 day"));
		
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}		
		else
		{
			if($draw_master_id!='')
			{
				$final_data = $this->wb_model->getAllwhere_result_join($result_date,$draw_master_id);			
				//$results = $this->wb_model->getAllwhere_result('result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));	
				
				/*$final_data = array();
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
				} */
			}else{
				$final_data = $this->wb_model->getAllwhere_result_join($result_date);			
				/*$results = $this->wb_model->getAllwhere_result('result_master',array('result_date'=>$result_date));			
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
				} */
			}
			
			$response= array('status'=>'201', 'message'=>'Rsults Get Successfully.', 'data'=>$final_data);
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
		$mobile_no 	= $this->post('mobile_no');
		$problem_related 	= $this->post('problem_related');
		$user_data = $this->wb_model->getsingle('user_master',array('user_master_id'=>$user_master_id));
		if($user_master_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
				'mobile_no'			=> $mobile_no,
				'problem_related'	=> $problem_related,
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}
		else if($user_data->user_type==0)
		{
			$response= array('status'=>'201', 'message'=>'Client Not Allowed.', 'data'=>null);	
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
				//$total_win = $this->wb_model->total_bid_counter_sale($tr->user_master_id,'1',$fromdate,$todate);				
				$total_win = $this->wb_model->total_bid_counter_sale_cuurentclaim($tr->user_master_id,'1',$fromdate,$todate);				
				
				//$total_comissionsss = $this->common_model->total_comission_new2($tr->user_master_id,$fromdate,$todate);
				//$total_comission = $total_comissionsss['0']['Retailer_commission'];
				
				$total_bonus = $this->wb_model->total_bonus($tr->user_master_id,$fromdate,$todate);
				
				if(count($total_bid)>0)
				{
					$total_comission  = ($total_bid[0]->total*$user_data->user_comission)/100;
					$net_pay_t = $total_bid[0]->total - ($total_win[0]->total*90) - $total_comission - $total_bonus[0]->total;
					$t_bid = $total_bid[0]->total;

				}else{
					$total_comission  = 0;
					$net_pay_t = 0 - ($total_win[0]->total*90) - $total_comission - $total_bonus[0]->total;
					$t_bid = 0;
				}
				
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}
		else if($user_data->user_type==0)
		{
			$response= array('status'=>'201', 'message'=>'Client Not Allowed.', 'data'=>null);	
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
				$total_win = $this->wb_model->total_bid_counter_sale_cuurentclaim($tr->user_master_id,'1',$fromdate,$todate);				
				//$total_win = $this->wb_model->total_bid_counter_sale($tr->user_master_id,'1',$fromdate,$todate);				
				
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
		}
		else if($user_data->user_type==0)
		{
			$response= array('status'=>'201', 'message'=>'Client Not Allowed.', 'data'=>null);	
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
			$response= array('status'=>'201', 'message'=>'Please Enter Client', 'data'=>null);	
		}
		else if(!$user_data)
		{
			$response= array('status'=>'201', 'message'=>'Client Does Not Exist.', 'data'=>null);	
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
								
								//transaction point to Client
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
	
