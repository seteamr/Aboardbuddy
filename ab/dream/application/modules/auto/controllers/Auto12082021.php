<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta'); 
		ini_set('max_execution_time', 600);
	}

	public function test()
	{
		$chkexist = $this->common_model->check_bid_akada_number('2020-03-18','1','01','0','0');
		$r_chk_exist = $chkexist[0]['bid_akada_number'];
		
	}
	public function verify($user_master_id,$device_token,$action,$bar_code_number='')
	{
		if($user_master_id!="" && $device_token!="" && $action!="")
		{			
			$wheres = array('user_master_id'=>$user_master_id,'device_token'=>$device_token);
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
					if($action=="add_player")
					{
						redirect('users/add');
					}
					else if($action=="users")
					{
						redirect('users');
					}
					else if($action=="player_list")
					{
						redirect('reports/play_history');
					}
					else if($action=="commission")
					{
						redirect('reports/commission');
					}
					else if($action=="counter_sale")
					{
						redirect('reports/counter_sale');
					}
					else if($action=="net_to_pay")
					{
						redirect('reports/net_to_pay');
					}
					else if($action=="bonus_history")
					{
						redirect('reports/bonus_history');
					}
					else if($action=="point_managment")
					{
						redirect('points');
					}
					else if($action=="view_transaction")
					{
						redirect('reports/view/'.$bar_code_number);
					}else{
						redirect('login');
					}
				}					
				else
				{
					//not user active
					redirect('login');
				}			
			}
			else
			{
				//not user over site
				redirect('login');
			}
			
		}else{
			//all field any blank
			redirect('login');
		}
	}

	public function win_calculation()
	{
		$expiry_date_time =  date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +1 minutes')); 
		$this->common_model->deleteData('check_cron',array('expiry_date_time <'=>$expiry_date_time));
		
		$expiry_date_time2 =  date('Y-m-d H:i:s'); 
		//$this->common_model->deleteData('auto_cron',array('created_date <'=>$expiry_date_time2));
		
		$chkoffdate = $this->common_model->getsingle('offlist',array('off_date'=>date('Y-m-d')));
		$check_cron = $this->common_model->getsingle('setting',array('id'=>'1'));
		
		$last_query =  "check_cron: " . " " .  $check_cron->cron;
		//$this->common_model->insertData('q_info',array('info'=>$last_query));
		
		$check_cron_already = $this->common_model->getsingle('auto_cron',array('auto'=>'1'));
		
		$last_query =  "check_cron_already: " . " " .  $check_cron_already;
		//$this->common_model->insertData('q_info',array('info'=>$last_query));
		
		//$chkoffdate = 0;
		$inside_proc = 0;
		
		if($chkoffdate)
		{
			echo "Off";
		}else{
			if(!$check_cron_already)
			{
				if($check_cron->cron=="1")
				{
					$inside_proc = $inside_proc + 1;
					$last_query =  "win_calculation2 " . " " .  $inside_proc;
					$this->common_model->insertData('q_info',array('info'=>$last_query));
					$this->win_calculation1();
				}
				/*else if($check_cron->cron=="0")
				{
					$this->win_calculation0();
				}*/
			}
		}
		
		
	}
	
	public function win_calculation1()
	{
		$result_date = date('Y-m-d');
		$records = $this->common_model->getAllwhere('draw_master',array('is_draw_active'=>'0'));
		
		foreach($records as $r)
		{
			$draw_master_id = $r->draw_master_id;
			
			
			$fentime = date('H:i',strtotime($r->draw_end_time));
			
			$ctime = strtotime(date('Y-m-d H:i:s'));
			$wining_date_time =  strtotime ( date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':00 -20 seconds'))); 
			
			$check_manual = $this->common_model->getsingle('check_cron',array('entry_date'=>$result_date,'draw_master_id'=>$draw_master_id));
			if(!$check_manual)
			{
				$manuale=0;
			}else{
				if($ctime>strtotime($check_manual->expiry_date_time))
				{
					$manuale=0;
				}else{
					$manuale=1;
				}
			}
			
			//$win_data = $this->common_model->total_record('result_master',$result_date,$draw_master_id, $is_result_declare);
			$win_data = $this->common_model->total_record('result_master',$result_date,$draw_master_id);
			$setting = $this->common_model->getsingle('setting',array('id'=>'1'));
            
			$chek_temp_results = $this->common_model->getsingle('temp_result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));
			if($chek_temp_results)
			{
				$manual_r_date_time =  strtotime ( date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':00 +'.$setting->manual_result_after_auto.' minutes')));
				if($ctime >= $manual_r_date_time){
					$no_manual = 0;
				}else{
					$no_manual = 1;
				}
			}else{
				$no_manual = 0;
			}
		
			if($ctime >= $wining_date_time && $win_data<"100" && $manuale=="0" && $no_manual==0)
			{ 
				$created_date =  date('Y-m-d H:i:s',strtotime(date('Y-m-d H:i:s').' +3 minutes')); 
				$this->common_model->insertData('auto_cron',array('auto'=>'1','created_date'=>$created_date));
				
				$this->common_model->updateData('draw_transaction_details',array('winning_per'=>$setting->distribute_per),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
				
				////////////////////////////////////////////////////
				// Update userwise winning_per in draw_transaction_details
				$sqlupdate_winning_per =
				"UPDATE	draw_transaction_details dtd
				SET		winning_per =
						(
							SELECT	um.winning_distribution
							FROM	user_master um
							WHERE	um.user_master_id = dtd.user_master_id
						)
				WHERE	dtd.result_date = '".$result_date."'
				AND		dtd.draw_master_id = '".$draw_master_id."'
				AND		EXISTS
				(
						SELECT	1
						FROM	user_master um1
						WHERE	um1.user_master_id = dtd.user_master_id
						AND		um1.winning_distribution>0
				)";
				$qqupdtd_winning_per = $this->db->query($sqlupdate_winning_per);
				$last_query =  "/*Update userwise winning_per in draw_transaction_details*/" . " " .  $this->db->last_query();
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				// Update userwise winning_per in draw_transaction_details
				/////////////////////////////////////////////////

				$temp_results = $this->common_model->getAllwhere('temp_result_master',array('draw_master_id'=>$draw_master_id,'result_date'=>$result_date));
				if($temp_results)
				{
					foreach($temp_results as $temp)
					{
						$ins_data = array(
							'result_date' 	 	=> $result_date,
							'draw_master_id' 	=> $draw_master_id,
							'series_master_id' 	=> $temp->series_master_id,
							'bajar_master_id' 	=> $temp->bajar_master_id,
							'bid_akada_number' 	=> $temp->bid_akada_number,
							'type_of_draw'		=> '1',
							'winning'			=> '0'
						);
						$chkexist = $this->common_model->check_bid_akada_number($result_date,$draw_master_id,$bid_akada_number,$series_master_id,$bajar_master_id,-1);
						if($chkexist==0)
						{
							$this->common_model->insertData('result_master',$ins_data);
							$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$temp->series_master_id,'bajar_master_id'=>$temp->bajar_master_id,'bid_akada_number'=>$temp->bid_akada_number,'is_deleted'=>'0'));
						}
					}
				}
				
				//query 4th block Insert when bidding done on 100 records========================
				/* Start Block 4*/
				$sql4a =
				"SELECT 	result_date
				,			draw_master_id
				,			series_master_id
				,			bajar_master_id
				,			COUNT(DISTINCT bid_akada_number) as tot
				,			SUM(bid_units*bid_points_multiplier*bid_points)*90/100 AS bid_points
				FROM 		draw_transaction_details
				WHERE		result_date = '".$result_date."'
				AND			draw_master_id = '".$draw_master_id."'
				AND			is_deleted = 0
				GROUP BY 	result_date
				,			draw_master_id
				,			series_master_id
				,			bajar_master_id
				HAVING		COUNT(DISTINCT bid_akada_number) = 100
				";
				$r4a = $this->db->query($sql4a);
				$result4a = $r4a->result();
				
				$num4a = 0;
				
				$num4a = $r4a->num_rows();
				$last_query = "/*4th block*/ " . " " . $this->db->last_query();
				$this->common_model->insertData('q_info',array('info'=>$last_query));

				if($num4a>0)
				{
					for($j=0;$j<count($result4a);$j++)
					{
						$draw_master_id_100 	= $result4a[$j]->draw_master_id;
						$series_master_id_100 	= $result4a[$j]->series_master_id;
						$bajar_master_id_100 	= $result4a[$j]->bajar_master_id;
						$bid_points_100 		= $result4a[$j]->bid_points;
						
						//----------------------------------------------------------------------------
						// To find user having Maximum collection
						$sqlusercollection = 
						"SELECT	tdtd.result_date
						,		tdtd.user_master_id
						,		SUM(bid_units*bid_points_multiplier*bid_points) AS bid_points
						FROM	draw_transaction_details tdtd
						WHERE	tdtd.result_date = '".$result_date."'
						AND		tdtd.draw_master_id = '".$draw_master_id_100."'
						AND		tdtd.series_master_id = '".$series_master_id_100."'
						AND		tdtd.bajar_master_id = '".$bajar_master_id_100."'
						GROUP BY result_date
						,		draw_master_id
						,		series_master_id
						,		bajar_master_id
						,		tdtd.user_master_id
						ORDER BY bid_points DESC
						LIMIT 1";
						
						$r4a1 = $this->db->query($sqlusercollection);
						$result4a1 = $r4a1->result();
						
						$num4a1 = 0;
						
						$num4a1 = $r4a1->num_rows();
						$last_query = "/*4th block*/ " . " " . $this->db->last_query();
						$this->common_model->insertData('q_info',array('info'=>$last_query));
						$max_collection_user_id = 0;
						if($num4a1>0)
						{
							for($j=0;$j<count($result4a1);$j++)
							{
								$max_collection_user_id = $result4a1[$j]->user_master_id;
							}//For $result4a1
						}// If $num4a1
						// To find user having Maximum collection
						
						//----------------------------------------------------------------------------
						$sql4 =
						"SELECT	tdtd.result_date
						,		tdtd.draw_master_id
						,		tdtd.series_master_id
						,		tdtd.bajar_master_id
						,		tdtd.bid_akada_number
						,		SUM(bid_units*bid_points_multiplier*bid_points) AS bid_points
						,		SUM(bid_units*bid_points_multiplier*bid_points) AS winning_points
						,		COUNT(tdtd.draw_transaction_master_id) AS number_of_bids
						,		COUNT(DISTINCT tdtd.user_master_id) AS number_of_users
						FROM 	draw_transaction_details tdtd
						WHERE	tdtd.result_date = '".$result_date."'
						AND		tdtd.draw_master_id = '".$draw_master_id_100."'
						AND		tdtd.series_master_id = '".$series_master_id_100."'
						AND		tdtd.bajar_master_id = '".$bajar_master_id_100."'
						AND		tdtd.user_master_id = '".$max_collection_user_id."'
						AND		is_deleted = 0
						GROUP BY	tdtd.result_date
						,			tdtd.draw_master_id
						,			tdtd.series_master_id
						,			tdtd.bajar_master_id
						,			tdtd.bid_akada_number
						HAVING	SUM(bid_units*bid_points_multiplier*bid_points) <= $bid_points_100
						ORDER BY bid_points";
						$r4 = $this->db->query($sql4);
						$result4 = $r4->result();
						
						//ORDER BY bid_points
						//ORDER BY rand()
						//ORDER BY COUNT(DISTINCT tdtd.user_master_id) DESC, bid_points
						
						$num4 = $r4->num_rows();
						$last_query =  "/*4th block inner*/ " . " " .  $this->db->last_query();
						$this->common_model->insertData('q_info',array('info'=>$last_query));
						if($num4>0)
						{
							for($i=0;$i<count($result4);$i++)
							{
								$winning_points 	= $result4[$i]->winning_points;
								$series_master_id 	= $result4[$i]->series_master_id;
								$bajar_master_id 	= $result4[$i]->bajar_master_id;
								$bid_akada_number 	= $result4[$i]->bid_akada_number;
								$number_of_bids 	= $result4[$i]->number_of_bids;
								
								$chkexist = $this->common_model->check_bid_akada_number($result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids);
								
								//$chkexist = $this->common_model->check_bid_akada_number_count($result_date,$draw_master_id,$bid_akada_number,$series_master_id,$bajar_master_id, $number_of_bids);
								
								if($chkexist==0)
								{							
									if($winning_points <= $bid_points_100)
									{
										$bid_points_100 = $bid_points_100 - $winning_points;
										$ins_data = array(
											'result_date' 	 	=> $result_date,
											'draw_master_id' 	=> $draw_master_id,
											'series_master_id' 	=> $series_master_id,
											'bajar_master_id' 	=> $bajar_master_id,
											'bid_akada_number' 	=> $bid_akada_number,
											'type_of_draw'		=> '0',
											'winning'			=> '0',
											'created_user_master_id'=>'2'
										);
										$this->common_model->insertData('result_master',$ins_data);
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'2'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
										$i = $num4+100;
										// $j = $num4a+100;
									}else{
										$i = $num4+100;
										// $j = $num4a+100;
									}	
								}
							} // for end result4
						} //if end $num4
						//----------------------------------------------------------------------------
						
					} // for end $result4a
				} //if end $num4a
				/* End Block 4*/
				//query 4th block Insert when bidding done on 100 records=======================
				
				////////////////////////////////////////////////////////////////
				/* Start Winning Distribution as per user Percentage*/
				$advanceDrawBalance = 0;
				$sqlAdvanceDraw = 
				"SELECT		dtd.user_master_id	AS	user_master_id
				,			um.winning_distribution AS winning_distribution
				,			um.max_winning AS max_winning
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)))	AS Sales_winning_distribution
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier))+um.max_winning)	AS Sales_max_winning
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100))) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0))) AS total_diff
				,			SUM(bid_units*bid_points_multiplier*bid_points)	AS Sales
				,			ROUND(SUM(IF(dtd.is_winning=1,bid_units*bid_points_multiplier*bid_points*90,0)),2)	AS winning
				FROM	draw_transaction_details dtd
				JOIN	user_master um
				ON		um.user_master_id = dtd.user_master_id AND	um.winning_distribution > 0	
				WHERE	dtd.result_date BETWEEN  ADDDATE('".$result_date."', INTERVAL -0 DAY) AND '".$result_date."'
				AND		dtd.is_deleted = 0
				AND		dtd.user_master_id IN
					(
						SELECT	user_master_id 
						FROM	draw_transaction_details dtd1
						WHERE	dtd1.result_date = '".$result_date."'
						AND		dtd1.draw_master_id = '".$draw_master_id."'
					)
				GROUP BY dtd.user_master_id, um.winning_distribution
				HAVING	MIN(bid_units*bid_points*bid_points_multiplier)*90 <= ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100))) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))
				AND	ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100))) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))>=180
				ORDER BY ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100))) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))
				DESC
				LIMIT 7";
				//$r4aAdvanceDraw = $this->db->query($sqlAdvanceDraw);
				//$result4aAdvanceDraw = $r4aAdvanceDraw->result();
				
				$num4aAdvanceDraw = 0;
				
				//$num4aAdvanceDraw = $r4aAdvanceDraw->num_rows();
				//$last_query = "/*Userwise Distribution*/ " . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num4aAdvanceDraw>0)
				{
					for($j=0;$j<count($result4aAdvanceDraw);$j++)
					{
						$user_master_id 	= $result4aAdvanceDraw[$j]->user_master_id;
						$total_diff 		= $result4aAdvanceDraw[$j]->total_diff;
						$Sales1 			= $result4aAdvanceDraw[$j]->Sales;
						$winning1 			= $result4aAdvanceDraw[$j]->winning;

						$excesswin = $winning1 - $Sales1;
						$sales10k = $Sales1 + 10000 - $winning1;
						
						if($excesswin>10000)
						{
							$total_diff = 0;
						}
						
						if($excesswin<=10000 && $total_diff>=10000 && $winning1>$Sales1)
						{
							$total_diff = $sales10k;
						}
						
						$Sales_winning_distribution 	= $result4aAdvanceDraw[$j]->Sales_winning_distribution;
						$Sales_max_winning 				= $result4aAdvanceDraw[$j]->Sales_max_winning;
						$winning_distribution 			= $result4aAdvanceDraw[$j]->winning_distribution;
						
						if($winning_distribution>=99 && $Sales_winning_distribution >= $Sales_max_winning)
						{
							$total_diff = $Sales_max_winning-$winning1;
						}
						
						$sql4Advanced =
						"SELECT	result_date
						,		draw_master_id
						,		series_master_id
						,		bajar_master_id
						,		bid_akada_number
						,		SUM(bid_units*bid_points_multiplier*bid_points) AS bid_points
						,		SUM(bid_units*bid_points_multiplier*bid_points)*90 AS winning_points
						,		COUNT(tdtd.draw_transaction_master_id) AS number_of_bids
						FROM	draw_transaction_details tdtd
						JOIN	user_master um
						ON		um.user_master_id = tdtd.user_master_id AND	um.winning_distribution > 0	
						JOIN
						(
							SELECT	dtdinline.draw_transaction_master_id
							,		SUM(dtdinline.bid_units*dtdinline.bid_points*dtdinline.bid_points_multiplier) AS total_bid_points
							FROM	draw_transaction_details dtdinline
							WHERE	dtdinline.result_date = '".$result_date."'
							AND		dtdinline.draw_master_id = '".$draw_master_id."'
							AND		dtdinline.is_deleted = 0
							AND		dtdinline.is_winning = 0
							GROUP BY dtdinline.draw_transaction_master_id
						) bid_master
						ON		bid_master.draw_transaction_master_id = tdtd.draw_transaction_master_id
						WHERE	result_date = '".$result_date."'
						AND		draw_master_id = '".$draw_master_id."' 
						AND		is_deleted = 0
						AND		is_winning = 0
						AND		EXISTS
							(
								SELECT		1
								FROM		draw_transaction_details dtd1
								WHERE		dtd1.result_date =  tdtd.result_date
								AND			dtd1.draw_master_id = tdtd.draw_master_id
								AND			dtd1.series_master_id = tdtd.series_master_id
								AND			dtd1.bajar_master_id = tdtd.bajar_master_id
								AND			dtd1.bid_akada_number = tdtd.bid_akada_number
								AND			dtd1.user_master_id = '".$user_master_id."'
							)
						GROUP BY 	result_date
						,			draw_master_id
						,			series_master_id
						,			bajar_master_id
						,			bid_akada_number
						HAVING		SUM(bid_units*bid_points_multiplier*bid_points)*90 < $total_diff
						AND			COUNT(tdtd.draw_transaction_master_id) = 1
						AND			MAX(tdtd.user_master_id) =  '".$user_master_id."' ";
						
						$to_random_orderby = rand (1,4);
						
						if ($to_random_orderby==1)
						{
							$sql4Advanced .= "
							ORDER BY bid_points DESC
							LIMIT 5";
						}
						elseif ($to_random_orderby==2)
						{
							$sql4Advanced .= "
							ORDER BY total_bid_points DESC, bid_points DESC
							LIMIT 5";
						}
						elseif ($to_random_orderby==3)
						{
							$sql4Advanced .= "
							ORDER BY total_bid_points, bid_points DESC
							LIMIT 5";
						}
						else
						{
							$sql4Advanced .= "
							ORDER BY rand()
							LIMIT 10";
						}
						
						//ORDER BY 	bid_points DESC
						//ORDER BY 	total_bid_points DESC, bid_points DESC
						//ORDER BY 	rand()
						//ORDER BY 	total_bid_points, bid_points DESC
						
						$r4Advanced = $this->db->query($sql4Advanced);
						$result4Advanced = $r4Advanced->result();
						$num4Advanced = $r4Advanced->num_rows();
						$last_query =  "/*Userwise Distribution inner*/ " . " " . $this->db->last_query();
						$this->common_model->insertData('q_info',array('info'=>$last_query));
						if($num4Advanced>0)
						{
							for($i=0;$i<count($result4Advanced);$i++)
							{
								$winning_points 	= $result4Advanced[$i]->winning_points;
								$series_master_id 	= $result4Advanced[$i]->series_master_id;
								$bajar_master_id 	= $result4Advanced[$i]->bajar_master_id;
								$bid_akada_number 	= $result4Advanced[$i]->bid_akada_number;
								$number_of_bids 	= $result4Advanced[$i]->number_of_bids;
							   
								$chkexist = $this->common_model->check_bid_akada_number( $result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids );
								
								//$chkexist = $this->common_model->check_bid_akada_number_count($result_date,$draw_master_id,$bid_akada_number,$series_master_id,$bajar_master_id, $number_of_bids);
								
								if($chkexist==0)
								{    					                											
									if($winning_points <= $total_diff)
									{
										$total_diff = $total_diff - $winning_points;
										$advanceDrawBalance = $advanceDrawBalance + $winning_points;
										$ins_data = array(
											'result_date' 	 	=> $result_date,
											'draw_master_id' 	=> $draw_master_id,
											'series_master_id' 	=> $series_master_id,
											'bajar_master_id' 	=> $bajar_master_id,
											'bid_akada_number' 	=> $bid_akada_number,
											'type_of_draw'		=> '0',
											'winning'			=> '0',
											'created_user_master_id'=>'1'
										);
										$this->common_model->insertData('result_master',$ins_data);
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'2'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
									}else{
										$i = $num4Advanced+100;
									//	$j = $num4aAdvanceDraw+100;
									}
								}
							} // End inner for loop
						} //End if innner query
					} // End main for loop  
				}// End if main Query
				
				$last_query =  "Userwise Distribution" . " " . $advanceDrawBalance;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				/* End Winning Distribution as per user Percentage*/
				/////////////////////////////////////////////////////////////////
				
				$sql =
				"SELECT	SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points*dtd.winning_per/100) AS total_bid_points
				FROM	draw_transaction_details dtd
				JOIN	user_master um
				ON		um.user_master_id = dtd.user_master_id AND	um.winning_distribution = 0	
				WHERE	dtd.result_date='".$result_date."' 
				AND		dtd.draw_master_id='".$draw_master_id."'
				AND		dtd.is_deleted='0' ";
				
				$qq = $this->db->query($sql);
				$results = $qq->result();
				$total_bid_points_40extra = $results[0]->total_bid_points*0.5;
				
				$last_query =  "/*total_bid_points_40extra */" . " " . $total_bid_points_40extra;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				//=======Start Give winning to user whose winning is less than 50% of days sales=============
				//
				/*
						JOIN	user_master um
						ON		um.user_master_id = dtd1.user_master_id AND	um.winning_distribution = 0	
				*/
				$points_used_for_50_percentage = 0;
				$sql5a =
				"SELECT		dtdinline.user_master_id
				,			IF(um.winning_distribution>0, um.winning_distribution, ".$setting->distribute_per.") AS winning_distribution
				,			IF(um.max_winning>0, um.max_winning, 5000) AS max_winning
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)))	AS Sales_winning_distribution
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier))+ 5000)	AS Sales_max_winning
				,			ROUND(SUM(bid_units*bid_points*bid_points_multiplier)) AS Sales
				,			ROUND(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) AS Sales_distribution
				,			ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) AS win_Amount
				,			(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) AS advance_total_bid_points
				FROM	draw_transaction_details dtdinline
				JOIN	user_master um
				ON		um.user_master_id = dtdinline.user_master_id AND um.winning_distribution >= 0
				WHERE	dtdinline.result_date = '".$result_date."'
				AND		dtdinline.draw_master_id BETWEEN 1 AND '".$draw_master_id."'
				AND		dtdinline.is_deleted = 0
				AND		dtdinline.user_master_id IN
					(
						SELECT	dtd1.user_master_id 
						FROM	draw_transaction_details dtd1
						WHERE	dtd1.result_date = '".$result_date."'
						AND		dtd1.draw_master_id = '".$draw_master_id."'
					)
				GROUP BY dtdinline.user_master_id
				HAVING	ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))/ROUND(SUM(bid_units*bid_points*bid_points_multiplier))*100 <= ".$setting->distribute_per."
				AND		(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) BETWEEN 180 AND 999999
				ORDER BY advance_total_bid_points DESC
				LIMIT 10";
				
				$r5ab = $this->db->query($sql5a);
				$result5ab = $r5ab->result();
				
				$num5a = 0;
				$num5a = $r5ab->num_rows();
				$last_query =  "/*Greater than 1800 collection Query */" . " " . $this->db->last_query();
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num5a>0)
				{
					$all_draw_transaction_master_id = array();
					for($j=0;$j<count($result5ab);$j++)
					{
						$user_master_id = $result5ab[$j]->user_master_id;
						$Sales1 = $result5ab[$j]->Sales;
						$winning1 = $result5ab[$j]->win_Amount;
						$advance_total_bid_points = $result5ab[$j]->advance_total_bid_points;
						
						$excesswin = $winning1 - $Sales1;
						$sales10k = $Sales1 + 5000 - $winning1;
						
						if($excesswin>5000)
						{
							$advance_total_bid_points = 0;
						}
						
						if($excesswin<=5000 && $advance_total_bid_points>=5000 && $winning1>$Sales1)
						{
							$advance_total_bid_points = $sales10k;
						}
						
						$Sales_winning_distribution 	= $result5ab[$j]->Sales_winning_distribution;
						$Sales_max_winning 				= $result5ab[$j]->Sales_max_winning;
						$winning_distribution 			= $result5ab[$j]->winning_distribution;
						
						$last_query =  "/*advance_total_bid_points */" . " " . $advance_total_bid_points. " + " . ($total_bid_points_40extra*0.10);
						$this->common_model->insertData('q_info',array('info'=>$last_query));
						
						if($winning_distribution != $setting->distribute_per)
						{
							$advance_total_bid_points = $advance_total_bid_points + ($total_bid_points_40extra*0.10);
						}
						
						if($winning_distribution>=99 && $Sales_winning_distribution >= $Sales_max_winning)
						{
							$advance_total_bid_points = $Sales_max_winning-$winning1;
						}
						
						if($advance_total_bid_points>=30000)
						{
							$advance_total_bid_points = 30000;
						}

						$all_draw_transaction_master_id[] = $user_master_id;
						$sql5 =
						"SELECT		dts.result_date
						,			dts.draw_master_id
						,			dts.series_master_id
						,			dts.bajar_master_id
						,			dts.bid_akada_number
						,			SUM(dts.bid_units*dts.bid_points_multiplier*dts.bid_points) AS bid_points
						,			SUM(dts.bid_units*dts.bid_points_multiplier*dts.bid_points)*90 AS winning_points
						,			COUNT(dts.draw_transaction_master_id) AS number_of_bids
						FROM		draw_transaction_details dts
						JOIN		user_master um
						ON			um.user_master_id = dts.user_master_id AND um.winning_distribution >= 0
						JOIN
						(
							SELECT	dtdinline.draw_transaction_master_id
							,		SUM(dtdinline.bid_units*dtdinline.bid_points*dtdinline.bid_points_multiplier) AS total_bid_points
							FROM	draw_transaction_details dtdinline
							WHERE	dtdinline.result_date = '".$result_date."'
							AND		dtdinline.draw_master_id = '".$draw_master_id."'
							AND		dtdinline.is_deleted = 0
							AND		dtdinline.is_winning = 0
							GROUP BY dtdinline.draw_transaction_master_id
						) bid_master
						ON			bid_master.draw_transaction_master_id = dts.draw_transaction_master_id
						WHERE		dts.result_date =  '".$result_date."'
						AND			dts.draw_master_id = '".$draw_master_id."'  
						AND			dts.is_deleted = 0
						AND			dts.is_winning = 0
						AND			dts.bid_units*dts.bid_points*dts.bid_points_multiplier BETWEEN 2 AND 300
						AND 		EXISTS 
							(
								SELECT	1
								FROM	draw_transaction_details dts1
								WHERE	dts1.result_date = dts.result_date
								AND		dts1.draw_master_id = dts.draw_master_id
								AND		dts1.series_master_id = dts.series_master_id
								AND		dts1.bid_akada_number = dts.bid_akada_number
								AND		dts1.is_deleted = 0
								AND		dts1.is_winning = 0
								AND		dts1.user_master_id = '".$user_master_id."'  
							)
						GROUP BY 	dts.result_date
						,			dts.draw_master_id
						,			dts.series_master_id
						,			dts.bajar_master_id
						,			dts.bid_akada_number
						HAVING		SUM(dts.bid_units*dts.bid_points_multiplier*dts.bid_points)*90 <= $advance_total_bid_points
						AND			COUNT(dts.draw_transaction_master_id) = 1
						AND			MAX(dts.user_master_id) =  '".$user_master_id."' ";
						
						$to_random_orderby = rand (1,4);
						
						if ($to_random_orderby==1)
						{
							$sql5 .= "
							ORDER BY bid_points DESC
							LIMIT 5";
						}
						elseif ($to_random_orderby==2)
						{
							$sql5 .= "
							ORDER BY total_bid_points DESC, bid_points DESC
							LIMIT 5";
						}
						elseif ($to_random_orderby==3)
						{
							$sql5 .= "
							ORDER BY total_bid_points, bid_points DESC
							LIMIT 5";
						}
						else
						{
							$sql5 .= "ORDER BY rand()
							LIMIT 10";
						}
						
						//ORDER BY 	bid_points DESC
						//ORDER BY 	total_bid_points DESC, bid_points DESC
						//ORDER BY 	rand()
						//ORDER BY 	total_bid_points, bid_points DESC
						
						$r5 = $this->db->query($sql5);
						$result5 = $r5->result();
						$num5 = $r5->num_rows();
						$last_query = "/*INNER : Greater than 1800 collection */ " . " " .  $this->db->last_query();
						$this->common_model->insertData('q_info',array('info'=>$last_query));
						
						if($num5>0)
						{
							for($i=0;$i<count($result5);$i++)
							{
								$winning_points 	= $result5[$i]->winning_points;
								$series_master_id 	= $result5[$i]->series_master_id;
								$bajar_master_id 	= $result5[$i]->bajar_master_id;
								$bid_akada_number 	= $result5[$i]->bid_akada_number;
								$number_of_bids 	= $result5[$i]->number_of_bids;
								
								$chkexist = $this->common_model->check_bid_akada_number( $result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids );
								
								//$chkexist = $this->common_model->check_bid_akada_number_count($result_date,$draw_master_id,$bid_akada_number,$series_master_id,$bajar_master_id, $number_of_bids);
								
								if($chkexist==0)
								{    					                    											
									if($winning_points <= $advance_total_bid_points)
									{ 
										$advance_total_bid_points = $advance_total_bid_points - $winning_points;
										$points_used_for_50_percentage = $points_used_for_50_percentage + $winning_points;
										$ins_data = array(
											'result_date' 	 	=> $result_date,
											'draw_master_id' 	=> $draw_master_id,
											'series_master_id' 	=> $series_master_id,
											'bajar_master_id' 	=> $bajar_master_id,
											'bid_akada_number' 	=> $bid_akada_number,
											'type_of_draw'		=> '0',
											'winning'			=> '0',
											'created_user_master_id'=>'15'
										);
										$this->common_model->insertData('result_master',$ins_data);
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'2'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
									
									}else{
										$i = $num5+100;
										// $j = $num5a+100;
									}
								} //$chkexist
							} // for i end
						} //if end
					} // for j end
				} //$num5a
				//=======End Give winning to user whose winning is less than 50% of days sales=============
				
				
				//=======Start Give winning to user whose winning is less than 80% of days sales=============
				//
				/*
						JOIN	user_master um
						ON		um.user_master_id = dtd1.user_master_id AND	um.winning_distribution = 0	
				*/
				$draw_master_id_3 = $draw_master_id - 2;
				$points_used_for_80_percentage = 0;
				
				$sql5a =
				"SELECT	dtdinline.user_master_id
				,			IF(um.winning_distribution>0, um.winning_distribution, ".$setting->distribute_per.") AS winning_distribution
				,			IF(um.max_winning>0, um.max_winning, 5000) AS max_winning
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)))	AS Sales_winning_distribution
				,			ROUND((SUM(bid_units*bid_points*bid_points_multiplier))+ 5000)	AS Sales_max_winning
				,			ROUND(SUM(bid_units*bid_points*bid_points_multiplier)) AS Sales
				,			ROUND(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) AS Sales_distribution
				,			ROUND(SUM(bid_units*bid_points*bid_points_multiplier)) AS Sales
				,			ROUND(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) AS Sales_distribution
				,			ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) AS win_Amount
				,			(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) AS advance_total_bid_points
				FROM	draw_transaction_details dtdinline
				JOIN	user_master um
				ON		um.user_master_id = dtdinline.user_master_id AND um.winning_distribution >= 0
				WHERE	dtdinline.result_date = '".$result_date."'
				AND		dtdinline.draw_master_id BETWEEN 1 AND '".$draw_master_id."'
				AND		dtdinline.is_deleted = 0
				AND		dtdinline.user_master_id IN
					(
						SELECT	dtd1.user_master_id 
						FROM	draw_transaction_details dtd1
						WHERE	dtd1.result_date = '".$result_date."'
						AND		dtd1.draw_master_id = '".$draw_master_id."'
					)
				GROUP BY dtdinline.user_master_id
				HAVING	ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))/ROUND(SUM(bid_units*bid_points*bid_points_multiplier))*100 <= 50
				AND		(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) BETWEEN 180 AND 2700
				ORDER BY advance_total_bid_points DESC
				LIMIT 10";
				
				//$r5a = $this->db->query($sql5a);
				//$result5a = $r5a->result();
				
				$num5a = 0;
				//$num5a = $r5a->num_rows();
				//$last_query =  "/*Less than 1800 collection Query */" . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num5a>0)
				{
					$all_draw_transaction_master_id = array();
					for($j=0;$j<count($result5a);$j++)
					{
						$user_master_id = $result5a[$j]->user_master_id;
						$Sales1 = $result5a[$j]->Sales;
						$winning1 = $result5a[$j]->win_Amount;
						$advance_total_bid_points = $result5a[$j]->advance_total_bid_points;

						$excesswin = $winning1 - $Sales1;
						$sales10k = $Sales1 + 5000 - $winning1;
						
						if($excesswin>5000)
						{
							$advance_total_bid_points = 0;
						}
						
						if($excesswin<=5000 && $advance_total_bid_points>=5000 && $winning1>$Sales1)
						{
							$advance_total_bid_points = $sales10k;
						}
						
						$Sales_winning_distribution 	= $result5a[$j]->Sales_winning_distribution;
						$Sales_max_winning 				= $result5a[$j]->Sales_max_winning;
						$winning_distribution 			= $result5a[$j]->winning_distribution;
						
						if($winning_distribution>=99 && $Sales_winning_distribution >= $Sales_max_winning)
						{
							$advance_total_bid_points = $Sales_max_winning-$winning1;
						}

						$all_draw_transaction_master_id[] = $user_master_id;
						$sql5 =
						"SELECT		dts.result_date
						,			dts.draw_master_id
						,			dts.series_master_id
						,			dts.bajar_master_id
						,			dts.bid_akada_number
						,			SUM(dts.bid_units*dts.bid_points_multiplier*dts.bid_points) AS bid_points
						,			SUM(dts.bid_units*dts.bid_points_multiplier*dts.bid_points)*90 AS winning_points
						,			COUNT(dts.draw_transaction_master_id) AS number_of_bids
						FROM		draw_transaction_details dts
						JOIN		user_master um
						ON			um.user_master_id = dts.user_master_id AND um.winning_distribution >= 0
						JOIN
						(
							SELECT	dtdinline.draw_transaction_master_id
							,		SUM(dtdinline.bid_units*dtdinline.bid_points*dtdinline.bid_points_multiplier) AS total_bid_points
							FROM	draw_transaction_details dtdinline
							WHERE	dtdinline.result_date = '".$result_date."'
							AND		dtdinline.draw_master_id = '".$draw_master_id."'
							AND		dtdinline.is_deleted = 0
							AND		dtdinline.is_winning = 0
							GROUP BY dtdinline.draw_transaction_master_id
						) bid_master
						ON		bid_master.draw_transaction_master_id = dts.draw_transaction_master_id
						WHERE		dts.result_date =  '".$result_date."'
						AND			dts.draw_master_id = '".$draw_master_id."'  
						AND			dts.is_deleted = 0
						AND			dts.is_winning = 0
						AND 		EXISTS 
							(
								SELECT	1
								FROM	draw_transaction_details dts1
								WHERE	dts1.result_date = dts.result_date
								AND		dts1.draw_master_id = dts.draw_master_id
								AND		dts1.series_master_id = dts.series_master_id
								AND		dts1.bid_akada_number = dts.bid_akada_number
								AND		dts1.is_deleted = 0
								AND		dts1.is_winning = 0
								AND		dts1.user_master_id = '".$user_master_id."'  
							)
						GROUP BY 	dts.result_date
						,			dts.draw_master_id
						,			dts.series_master_id
						,			dts.bajar_master_id
						,			dts.bid_akada_number
						HAVING		SUM(dts.bid_units*dts.bid_points_multiplier*dts.bid_points)*90 <= $advance_total_bid_points
						AND			COUNT(dts.draw_transaction_master_id) = 1
						AND			MAX(dts.user_master_id) =  '".$user_master_id."' ";
						
						$to_random_orderby = rand (1,4);
						
						if ($to_random_orderby==1)
						{
							$sql5 .= "
							ORDER BY bid_points DESC
							LIMIT 5";
						}
						elseif ($to_random_orderby==2)
						{
							$sql5 .= "
							ORDER BY total_bid_points DESC, bid_points DESC
							LIMIT 5";
						}
						elseif ($to_random_orderby==3)
						{
							$sql5 .= "
							ORDER BY total_bid_points, bid_points DESC
							LIMIT 5";
						}
						else
						{
							$sql5 .= "
							ORDER BY rand()
							LIMIT 5";
						}
						
						//ORDER BY 	bid_points DESC
						//ORDER BY 	total_bid_points DESC, bid_points DESC
						//ORDER BY 	rand()
						//ORDER BY 	total_bid_points, bid_points DESC
						
						$last_query = "/*INNER : Less than 1800 collection Query */ " . " " .  $sql5;
						$this->common_model->insertData('q_info',array('info'=>$last_query));
						
						
						//$r5 = $this->db->query($sql5);
						//$result5 = $r5->result();
						
						$num5 = 0;
						
						//$num5 = $r5->num_rows();
						//$last_query = "/*INNER : Query for Less than 80 */ " . " " .  $this->db->last_query();
						//$this->common_model->insertData('q_info',array('info'=>$last_query));
						
						if($num5>0)
						{
							for($i=0;$i<count($result5);$i++)
							{
								$winning_points 	= $result5[$i]->winning_points;
								$series_master_id 	= $result5[$i]->series_master_id;
								$bajar_master_id 	= $result5[$i]->bajar_master_id;
								$bid_akada_number 	= $result5[$i]->bid_akada_number;
								$number_of_bids 	= $result5[$i]->number_of_bids;
								
								//$chkexist = $this->common_model->check_bid_akada_number( $result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids );
								
								$chkexist = $this->common_model->check_bid_akada_number_count($result_date,$draw_master_id,$bid_akada_number,$series_master_id,$bajar_master_id, $number_of_bids);
								
								if($chkexist==0)
								{    					                    											
									if($winning_points <= $advance_total_bid_points)
									{ 
										$advance_total_bid_points = $advance_total_bid_points - $winning_points;
										$points_used_for_80_percentage = $points_used_for_80_percentage + $winning_points;
										$ins_data = array(
											'result_date' 	 	=> $result_date,
											'draw_master_id' 	=> $draw_master_id,
											'series_master_id' 	=> $series_master_id,
											'bajar_master_id' 	=> $bajar_master_id,
											'bid_akada_number' 	=> $bid_akada_number,
											'type_of_draw'		=> '0',
											'winning'			=> '0',
											'created_user_master_id'=>'16'
										);
										$this->common_model->insertData('result_master',$ins_data);
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'2'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
									
									}else{
										$i = $num5+100;
										// $j = $num5a+100;
									}
								} //$chkexist
							} // for i end
						} //if end
					} // for j end
				} //$num5a
				//=======End Give winning to user whose winning is less than 80% of days sales=============
				
				$last_query =  "/*Winnning given to less than 80% */" . " " . $points_used_for_80_percentage;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				/////////////////////////////////////////////////////////////////////
				//original draw_transaction_details
				$sql =
				"SELECT	SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points*winning_per/100) AS total_bid_points
				FROM	draw_transaction_details dtd
				JOIN	user_master um
				ON		um.user_master_id = dtd.user_master_id AND	um.winning_distribution = 0	
				WHERE	dtd.result_date='".$result_date."' 
				AND		dtd.draw_master_id='".$draw_master_id."'
				AND		dtd.is_deleted='0' ";
				
				$qq = $this->db->query($sql);
				$results = $qq->result();
				$total_bid_points = $results[0]->total_bid_points;
				
				if($draw_master_id==1)
				{
					$did = "48";
				}else{
					$did = $draw_master_id - 1;
				}
				//$prev				
				
				//$prev	old_sales_balance //original draw_transaction_details
				$draw_master_id_last3 = $draw_master_id-2;
				$draw_master_id_last2 = $draw_master_id-1;
				
				$prevq =	
				"SELECT SUM(bid_units*bid_points_multiplier*bid_points*winning_per/100) AS old_sales_balance
				FROM 	draw_transaction_details dtd
				JOIN	user_master um
				ON		um.user_master_id = dtd.user_master_id AND	um.winning_distribution = 0	
				WHERE	dtd.result_date = '".$result_date."'
				AND		dtd.draw_master_id BETWEEN  '".$draw_master_id_last3."' AND '".$draw_master_id_last2."'
				AND		dtd.is_deleted = '0'
				";
				$pqq = $this->db->query($prevq);
				$presults = $pqq->result();					
				$num = $pqq->num_rows();				
				if($presults)
				{
					$prebalance = $presults[0]->old_sales_balance;						
				}else{
					$prebalance = 0;
				}
				
				//$prev	old_win_points //// original
				$prevq1 =
				"SELECT SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 AS old_win_points
				FROM 	draw_transaction_details dtd
				JOIN	user_master um
				ON		um.user_master_id = dtd.user_master_id AND	um.winning_distribution = 0	
				WHERE 	result_date = '".$result_date."'
				AND 	dtd.draw_master_id BETWEEN  '".$draw_master_id_last3."' AND '".$draw_master_id."'
				AND 	dtd.is_deleted = '0'
				AND		dtd.is_winning = 1
				";
				$pqq1 = $this->db->query($prevq1);
				$presults1 = $pqq1->result();					
				$num1 = $pqq1->num_rows();	
				if($presults1)
				{
					$prebalance1 = $presults1[0]->old_win_points;						
				}else{
					$prebalance1 = 0;
				}
				
				$total_collection1 =  $total_bid_points + $prebalance - $prebalance1;
				if($total_collection1 < 1200)
				{
					$total_collection1 = 0;					
				}
				
				$last_query =  "/*Collection after less than 50% */" . " " . $total_collection1;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				/////////////////////////////////////////////////////////////////////
				
				//$total_collection1 =  $total_collection1 - $points_used_for_80_percentage ;
				//$last_query =  "/*Total Collection after less than 50% */" . " " . $total_collection1;
				//$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				//=====Start Winning for Bigger bidding numbers=======================
				$block_points31 = 0; //$total_collection1*0.0001;
				
				$last_query =  "Winning for less than 50 collective" . " " . $block_points31;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				$sql31 =
				"SELECT	dtd.result_date
				,		dtd.draw_master_id
				,		dtd.series_master_id
				,		dtd.bajar_master_id
				,		dtd.bid_akada_number
				,		SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points) AS bid_points
				,		SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 AS winning_points
				,		COUNT(dtd.draw_transaction_master_id) AS number_of_bids
				FROM	draw_transaction_details dtd
				JOIN	user_master um
				ON		um.user_master_id = dtd.user_master_id AND um.winning_distribution = 0
				WHERE		dtd.result_date =  '".$result_date."'
				AND			dtd.draw_master_id ='".$draw_master_id."' 
				AND			dtd.is_deleted = 0
				AND			dtd.is_winning = 0
				AND			dtd.bid_units*dtd.bid_points*dtd.bid_points_multiplier <=10
				AND			dtd.user_master_id IN
					(
						SELECT	dtd1.user_master_id
						FROM	draw_transaction_details dtd1
						JOIN	user_master um
						ON		um.user_master_id = dtd1.user_master_id AND um.winning_distribution = 0
						WHERE	dtd1.result_date BETWEEN  ADDDATE('".$result_date."', INTERVAL -0 DAY) AND '".$result_date."'
						AND		dtd1.is_deleted = 0
						AND		dtd1.user_master_id IN 
							(
								SELECT	dtd2.user_master_id 
								FROM	draw_transaction_details dtd2
								WHERE	dtd2.result_date = '".$result_date."'
								AND		dtd2.draw_master_id = '".$draw_master_id."'
							)
						GROUP BY dtd1.user_master_id
						HAVING	ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))/ROUND(SUM(bid_units*bid_points*bid_points_multiplier))*100 <=50
						AND		(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) >= 180
					)
				GROUP BY 	dtd.result_date
				,			dtd.draw_master_id
				,			dtd.series_master_id
				,			dtd.bajar_master_id
				,			dtd.bid_akada_number
				HAVING		SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 < $block_points31
				AND         SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points) >= 2
				ORDER BY 	number_of_bids DESC, bid_points DESC
				LIMIT 60";
				
				//ORDER BY 	bid_points DESC
				//ORDER BY 	number_of_bids DESC, bid_points DESC
				//ORDER BY 	rand()
				
				//$r31 = $this->db->query($sql31);
				//$result31 = $r31->result();
				$num3 = 0;
				
				//$num3 = $r31->num_rows();
				//$last_query =  "/* Winning for less than 50 : 3rd block*/ " . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num3>0)
				{
					for($i=0;$i<count($result31);$i++)
					{
						$winning_points 	= $result31[$i]->winning_points;
						$series_master_id 	= $result31[$i]->series_master_id;
						$bajar_master_id 	= $result31[$i]->bajar_master_id;
						$bid_akada_number 	= $result31[$i]->bid_akada_number;
						$number_of_bids 	= $result31[$i]->number_of_bids;
						
						$chkexist = $this->common_model->check_bid_akada_number( $result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids );
						
						//$chkexist = $this->common_model->check_bid_akada_number_count($result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids);
						
						if($chkexist==0)
						{
							if($winning_points <= $block_points31)
							{
								$block_points31 = $block_points31 - $winning_points;
								$ins_data = array(
									'result_date' 	 	=> $result_date,
									'draw_master_id' 	=> $draw_master_id,
									'series_master_id' 	=> $series_master_id,
									'bajar_master_id' 	=> $bajar_master_id,
									'bid_akada_number' 	=> $bid_akada_number,
									'type_of_draw'		=> '0',
									'winning'			=> '0',
									'created_user_master_id'=>'13'
								);
								$this->common_model->insertData('result_master',$ins_data);
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'2'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
							}else{
								$i = $num3+100;
							}	
    					}
					}
				}
				
				$last_query =  "Balance After less than 50" . " " . $block_points31;
				$this->common_model->insertData('q_info',array('info'=>$last_query));				
				//=====End Winning for Bigger bidding numbers=======================
				
				//=====Start Winning for Bigger bidding numbers=======================
				$block_points3 = $total_collection1*0.4;
				
				$last_query =  "Winning for less than 50 collective" . " " . $block_points3;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				$draw_master_id_5 = $draw_master_id-4;
				$max_bid_units = rand(40,150);
				
				$sql3 =
				"SELECT		dtd.result_date
				,			dtd.draw_master_id
				,			dtd.series_master_id
				,			dtd.bajar_master_id
				,			dtd.bid_akada_number
				,			SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points) AS bid_points
				,			SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 AS winning_points
				,			COUNT(dtd.draw_transaction_master_id) AS number_of_bids
				FROM		draw_transaction_details dtd
				JOIN		user_master um
				ON			um.user_master_id = dtd.user_master_id AND um.winning_distribution = 0
				WHERE		dtd.result_date =  '".$result_date."'
				AND			dtd.draw_master_id ='".$draw_master_id."' 
				AND			dtd.is_deleted = 0
				AND			dtd.is_winning = 0
				AND			dtd.bid_units*dtd.bid_points*dtd.bid_points_multiplier BETWEEN 2 AND ".$max_bid_units."
				AND			dtd.user_master_id IN
					(
						SELECT	dtd1.user_master_id
						FROM	draw_transaction_details dtd1
						JOIN	user_master um
						ON		um.user_master_id = dtd1.user_master_id AND um.winning_distribution = 0
						WHERE	dtd1.result_date BETWEEN  ADDDATE('".$result_date."', INTERVAL -0 DAY) AND '".$result_date."'
						AND		dtd1.is_deleted = 0
						AND		dtd1.draw_master_id BETWEEN '".$draw_master_id_5."' AND '".$draw_master_id."'
						AND		dtd1.user_master_id IN 
							(
								SELECT	dtd2.user_master_id 
								FROM	draw_transaction_details dtd2
								WHERE	dtd2.result_date = '".$result_date."'
								AND		dtd2.draw_master_id = '".$draw_master_id."'
							)
						GROUP BY dtd1.user_master_id
						HAVING	SUM( dtd1.bid_units*dtd1.bid_points*dtd1.bid_points_multiplier) >= 15000
						AND		(SUM(bid_units*bid_points*bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90,0))) >= 180
					)
				GROUP BY 	dtd.result_date
				,			dtd.draw_master_id
				,			dtd.series_master_id
				,			dtd.bajar_master_id
				,			dtd.bid_akada_number
				HAVING		SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 < $block_points3
				AND			COUNT(DISTINCT dtd.user_master_id) >=1";

				$to_random_orderby = rand (1,4);
				
				if ($to_random_orderby==1)
				{
					$sql3 .= "
					ORDER BY bid_points DESC
					LIMIT 80";
				}
				elseif ($to_random_orderby==2)
				{
					$sql3 .= "
					ORDER BY number_of_bids DESC, bid_points DESC
					LIMIT 80";
				}
				elseif ($to_random_orderby==3)
				{
					$sql3 .= "
					ORDER BY bid_points DESC, number_of_bids DESC
					LIMIT 80";
				}
				else
				{
					$sql3 .= "
					ORDER BY rand()
					LIMIT 80";
				}
				
				//ORDER BY 	bid_points DESC
				//ORDER BY 	number_of_bids DESC, bid_points DESC
				//ORDER BY number_of_bids DESC, bid_points
				//ORDER BY 	rand()
				
				//$r3 = $this->db->query($sql3);
				//$result3 = $r3->result();
				$num3 = 0;
				
				//$num3 = $r3->num_rows();
				//$last_query =  "/* Winning for less than 50 : 3rd block*/ " . " " . $this->db->last_query();
				//$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num3>0)
				{
					for($i=0;$i<count($result3);$i++)
					{
						$winning_points 	= $result3[$i]->winning_points;
						$series_master_id 	= $result3[$i]->series_master_id;
						$bajar_master_id 	= $result3[$i]->bajar_master_id;
						$bid_akada_number 	= $result3[$i]->bid_akada_number;
						$number_of_bids 	= $result3[$i]->number_of_bids;
						
						$chkexist = $this->common_model->check_bid_akada_number( $result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids );
						
						//$chkexist = $this->common_model->check_bid_akada_number_count($result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids);
						
						if($chkexist==0)
						{
							if($winning_points <= $block_points3)
							{
								$block_points3 = $block_points3 - $winning_points;
								$ins_data = array(
									'result_date' 	 	=> $result_date,
									'draw_master_id' 	=> $draw_master_id,
									'series_master_id' 	=> $series_master_id,
									'bajar_master_id' 	=> $bajar_master_id,
									'bid_akada_number' 	=> $bid_akada_number,
									'type_of_draw'		=> '0',
									'winning'			=> '0',
									'created_user_master_id'=>'14'
								);
								$this->common_model->insertData('result_master',$ins_data);
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'2'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'is_deleted'=>'0'));
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
							}else{
								$i = $num3+100;
							}	
    					}
					}
				}
				
				$last_query =  "Balance After less than 50" . " " . $block_points3;
				$this->common_model->insertData('q_info',array('info'=>$last_query));				
				//=====End Winning for Bigger bidding numbers=======================
				
				//=====Start Winning for small bidding numbers=======================
				$block_points6 = $total_collection1*0.6 + $block_points3;
				
				$last_query =  "Balance for collective winning" . " " . $block_points6;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				$max_bid_units = rand(40,150);
				
				$sql1 =
				"SELECT		dtd.result_date
				,			dtd.draw_master_id
				,			dtd.series_master_id
				,			dtd.bajar_master_id
				,			dtd.bid_akada_number
				,			SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points) AS bid_points
				,			SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 AS winning_points
				,			COUNT(dtd.draw_transaction_master_id) AS number_of_bids
				FROM		draw_transaction_details dtd
				JOIN		user_master um
				ON			um.user_master_id = dtd.user_master_id AND um.winning_distribution >= 0
				WHERE		dtd.result_date =  '".$result_date."'
				AND			dtd.draw_master_id = '".$draw_master_id."' 
				AND			dtd.is_deleted = 0
				AND			dtd.is_winning = 0
				AND			dtd.bid_units*dtd.bid_points*dtd.bid_points_multiplier BETWEEN 2 AND ".$max_bid_units."
				AND			dtd.user_master_id IN 
					(
						SELECT	dtd1.user_master_id
						FROM	draw_transaction_details dtd1
						JOIN	user_master um
						ON		um.user_master_id = dtd1.user_master_id AND um.winning_distribution >= 0
						WHERE	dtd1.result_date BETWEEN  ADDDATE('".$result_date."', INTERVAL -0 DAY) AND '".$result_date."'
						AND		dtd1.is_deleted = 0
						AND		dtd1.user_master_id IN 
							(
								SELECT	dtd2.user_master_id 
								FROM	draw_transaction_details dtd2
								WHERE	dtd2.result_date = '".$result_date."'
								AND		dtd2.draw_master_id = '".$draw_master_id."'
							)
						GROUP BY dtd1.user_master_id
						HAVING	(SUM( dtd1.bid_units*dtd1.bid_points*dtd1.bid_points_multiplier*winning_per/100)) - ROUND(SUM(IF(dtd1.is_winning=1, dtd1.bid_units*dtd1.bid_points*dtd1.bid_points_multiplier*90,0)))>= 180
						AND		ROUND(SUM(IF(is_winning=1, bid_units*bid_points*bid_points_multiplier*90, 0)))/ROUND(SUM(bid_units*bid_points*bid_points_multiplier))*100 <= 50
					)
				GROUP BY 	dtd.result_date
				,			dtd.draw_master_id
				,			dtd.series_master_id
				,			dtd.bajar_master_id
				,			dtd.bid_akada_number
				HAVING		SUM(dtd.bid_units*dtd.bid_points_multiplier*dtd.bid_points)*90 < $block_points6
				AND			COUNT(DISTINCT dtd.user_master_id) >=1";
						
				$to_random_orderby = rand (1,3);
				
				if ($to_random_orderby==1)
				{
					$sql1 .= "
					ORDER BY bid_points DESC
					LIMIT 80";
				}
				elseif ($to_random_orderby==2)
				{
					$sql1 .= "
					ORDER BY number_of_bids DESC, bid_points DESC
					LIMIT 80";
				}
				elseif ($to_random_orderby==3)
				{
					$sql1 .= "
					ORDER BY bid_points DESC, number_of_bids DESC
					LIMIT 80";
				}
				else
				{
					$sql1 .= "
					ORDER BY rand()
					LIMIT 80";
				}
				
				//ORDER BY 	bid_points DESC
				//ORDER BY 	number_of_bids DESC, bid_points DESC
				//ORDER BY number_of_bids DESC, bid_points
				//ORDER BY 	rand()

				//$r1 = $this->db->query($sql1);
				//$result1 = $r1->result();
				
				$num6 = 0;
				
				//$num6 = $r1->num_rows();
				//$last_query =  "/* Query for less than 80 : 6th block*/ " . " " .  $this->db->last_query();
				//$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num6>0)
				{
					for($i=0;$i<count($result1);$i++)
					{
						$winning_points 	= $result1[$i]->winning_points;
						$series_master_id 	= $result1[$i]->series_master_id;
						$bajar_master_id 	= $result1[$i]->bajar_master_id;
						$bid_akada_number 	= $result1[$i]->bid_akada_number;
						$number_of_bids 	= $result1[$i]->number_of_bids;
						
						$chkexist = $this->common_model->check_bid_akada_number( $result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids );
						
						//$chkexist = $this->common_model->check_bid_akada_number_count($result_date, $draw_master_id, $bid_akada_number, $series_master_id, $bajar_master_id, $number_of_bids);

						if($chkexist==0)
						{                           								
							if($winning_points <= $block_points6)
							{
								$block_points6 = $block_points6 - $winning_points;
								$ins_data = array(
									'result_date' 	 	=> $result_date,
									'draw_master_id' 	=> $draw_master_id,
									'series_master_id' 	=> $series_master_id,
									'bajar_master_id' 	=> $bajar_master_id,
									'bid_akada_number' 	=> $bid_akada_number,
									'type_of_draw'		=> '0',
									'winning'			=> '0',
									'created_user_master_id'=>'17'
								);
								$this->common_model->insertData('result_master',$ins_data);
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$bid_akada_number,'is_deleted'=>'0'));
							}else{
								$i = $num1+100;
							}
    					}
					}
				}
				
				$last_query =  "Balance After Smaller Bidding Numbers" . " " . $block_points6;
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				//=====End Winning for small bidding numbers=======================
				
				$is_result_old = $this->common_model->getsingle('result_master',array('draw_master_id'=>$draw_master_id, 'result_date'=>$result_date, 'created_user_master_id'=>'8'));
				
				if(!$is_result_old)
				{
					$draw_master_id_Zero  = 1;
					$date_interval = 3;
					
					//This loop will ensure that there are different Result data on same date for different draw 
					for($iold=0;$iold<=100;$iold++) 
					{
						$draw_master_id_Zero = rand (1,48);
						$date_interval = rand (200,350);
						$old_result_date_diff = $date_interval;
						$is_result_old_used = $this->common_model->getsingle('zero_win_result_reference',array('current_result_date'=>$result_date, 'old_result_date_diff'=>$old_result_date_diff, 'old_draw_master_id'=>$draw_master_id_Zero));
						if(!$is_result_old_used)
						{
							$iold = 101;
						}
					}
					//This loop will ensure that there are different Result data on same date for different draw 

					if(!$is_result_old_used)
					{
						$zero_win_result_reference = array(
							'current_result_date' 		=> $result_date,
							'current_draw_master_id' 	=> $draw_master_id,
							'old_result_date_diff' 		=> $old_result_date_diff,
							'old_draw_master_id' 		=> $draw_master_id_Zero
						);
						$this->common_model->insertData('zero_win_result_reference',$zero_win_result_reference);
						
						$sqlInsertZero = "INSERT INTO result_master
						(
								result_date
						,		draw_master_id
						,		series_master_id
						,		bajar_master_id
						,		bid_akada_number
						,		type_of_draw
						,		created_user_master_id
						)
						SELECT	'".$result_date."'
						,		'".$draw_master_id."'
						,		series_master_id
						,		bajar_master_id
						,		bid_akada_number
						,		0
						,		8
						FROM 	result_master rm_old
						WHERE 	result_date = ADDDATE('".$result_date."', INTERVAL -".$date_interval." DAY)
						AND		draw_master_id = '".$draw_master_id_Zero."'
						AND		is_result_declare = 1
						AND		NOT	EXISTS
								(
									SELECT	1
									FROM	draw_transaction_details dtd
									WHERE	dtd.result_date = '".$result_date."'
									AND		dtd.draw_master_id = '".$draw_master_id."'
									AND		dtd.series_master_id = rm_old.series_master_id
									AND		dtd.bajar_master_id = rm_old.bajar_master_id
									AND		dtd.bid_akada_number = rm_old.bid_akada_number
								)
						AND		NOT	EXISTS
						(
							SELECT	1
							FROM	result_master rm_winning_given
							WHERE	rm_winning_given.result_date =  '".$result_date."'
							AND		draw_master_id =  '".$draw_master_id."'
							AND		rm_winning_given.series_master_id = rm_old.series_master_id
							AND		rm_winning_given.bajar_master_id = rm_old.bajar_master_id
						)";
						$InsertZero = $this->db->query($sqlInsertZero);
						$last_query =  "/*Insert Zero*/" . " " .  $this->db->last_query();
						$this->common_model->insertData('q_info',array('info'=>$last_query));
					}
				}
				/////////////////////////////////////////////////By /////////Sachin sir

				//all other
				/*
				$olddatas = $this->common_model->getprevdate();
				$newquery123 = 
				"SELECT 	series_master_id
				,		bajar_master_id
				FROM	result_master rm1
				WHERE	result_date	 = '".$olddatas[0]->result_date."' 
				AND		draw_master_id = '".$draw_master_id."'
				AND		NOT	EXISTS
				(
					SELECT	1
					FROM	result_master RM
					WHERE	RM.result_date = '".$result_date."'
					AND		RM.draw_master_id =  '".$draw_master_id."'
					AND		RM.series_master_id = rm1.series_master_id
					AND		RM.bajar_master_id = rm1.bajar_master_id
				) ";
				
				$resss345 = $this->db->query($newquery123);
				$result2123 = $resss345->result();
				$num2123 = $resss345->num_rows();
				$last_query =  "all other block " . " " . $this->db->last_query();
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				
				if($num2123>0)
				{
					for($i=0;$i<count($result2123);$i++)
					{
						$bajar_master_id = $result2123[$i]->bajar_master_id;
						$series_master_id = $result2123[$i]->series_master_id;
						
						$fsql2 =
						"SELECT bid_akada_number 
						FROM 	all_bid_akada 
						WHERE 	bid_akada_number NOT IN 
						( 
							SELECT 	bid_akada_number 
							FROM	draw_transaction_details 
							WHERE	result_date	= '".$result_date."' 
							AND 	draw_master_id='".$draw_master_id."'
							AND 	bajar_master_id	= '".$bajar_master_id."' 
							AND 	series_master_id='".$series_master_id."'
						) ORDER BY rand() LIMIT 1 ";
						$qqq2 = $this->db->query($fsql2);
						$fresults2 = $qqq2->result();
						$rnd = $fresults2[0]->bid_akada_number;
						if($rnd)
						{
							$ins_data = array(
								'result_date' 	 	=> $result_date,
								'draw_master_id' 	=> $draw_master_id,
								'series_master_id' 	=> $series_master_id,
								'bajar_master_id' 	=> $bajar_master_id,
								'bid_akada_number' 	=> $rnd,
								'type_of_draw'		=> '0',
								'winning'			=> '0',
								'created_user_master_id'=>'9'
							);
							$this->common_model->insertData('result_master',$ins_data);
							$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$rnd,'is_deleted'=>'0'));
						}else
						{
							$fsql22 =
							"SELECT		bid_akada_number
							,			(bid_units*bid_points_multiplier* bid_points) 
							FROM 		draw_transaction_details 
							WHERE		result_date = '" .$result_date."' 
							AND 		draw_master_id = '" .$draw_master_id."' 
							AND 		bajar_master_id = '" .$bajar_master_id."'
							AND			series_master_id='".$series_master_id."'							
							ORDER BY 	(bid_units*bid_points_multiplier* bid_points) ASC 
							LIMIT 1 ";
							
							$qqq22 = $this->db->query($fsql22);
							$fresults22 = $qqq22->result();
							$rnd = $fresults22[0]->bid_akada_number;
							$ins_data = array(
								'result_date' 	 	=> $result_date,
								'draw_master_id' 	=> $draw_master_id,
								'series_master_id' 	=> $series_master_id,
								'bajar_master_id' 	=> $bajar_master_id,
								'bid_akada_number' 	=> $rnd,
								'type_of_draw'		=> '0',
								'winning'			=> '0',
								'created_user_master_id'=>'10'
							);
							$this->common_model->insertData('result_master',$ins_data);
							$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$rnd,'is_deleted'=>'0'));
						}
					}
				} */
				
				
				for($series_master_id=0;$series_master_id<10;$series_master_id++)
				{
					//bajar_master_id
					for($bajar_master_id=0;$bajar_master_id<10;$bajar_master_id++)
					{					
						$chk_all = $this->common_model->getsingle('result_master',array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id));
						if(!$chk_all)
						{ 
							$fsql2 ="Select bid_akada_number from all_bid_akada where bid_akada_number NOT IN 
										( Select bid_akada_number from draw_transaction_details where
										result_date='".$result_date."' AND draw_master_id='".$draw_master_id."'
										AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."'
										) ORDER BY rand() LIMIT 1 ";
							$qqq2 = $this->db->query($fsql2);
							$fresults2 = $qqq2->result();
							$rnd = $fresults2[0]->bid_akada_number;
							if($rnd)
							{
								$ins_data = array(
									'result_date' 	 	=> $result_date,
									'draw_master_id' 	=> $draw_master_id,
									'series_master_id' 	=> $series_master_id,
									'bajar_master_id' 	=> $bajar_master_id,
									'bid_akada_number' 	=> $rnd,
									'type_of_draw'		=> '0',
									'winning'			=> '0',
									'created_user_master_id'=>'9'
								);
								$this->common_model->insertData('result_master',$ins_data);
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$rnd,'is_deleted'=>'0'));
							}else{
								if($yesnorand==0)
								{
									$fsql22 ="select
									bid_akada_number,
									(bid_units*bid_points_multiplier* bid_points) from draw_transaction_details where
									result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' 
									AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."' 
									order by rand() limit 1 ";
								}else{
									$fsql22 ="select
									bid_akada_number,
									(bid_units*bid_points_multiplier* bid_points) from draw_transaction_details where
									result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' 
									AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."' 
									order by (bid_units*bid_points_multiplier* bid_points) asc limit 1 ";
								}
								
								$qqq22 = $this->db->query($fsql22);
								$fresults22 = $qqq22->result();
								$rnd = $fresults22[0]->bid_akada_number;
								$ins_data = array(
									'result_date' 	 	=> $result_date,
									'draw_master_id' 	=> $draw_master_id,
									'series_master_id' 	=> $series_master_id,
									'bajar_master_id' 	=> $bajar_master_id,
									'bid_akada_number' 	=> $rnd,
									'type_of_draw'		=> '0',
									'winning'			=> '0',
									'created_user_master_id'=>'10'
								);
								$this->common_model->insertData('result_master',$ins_data);
								$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$rnd,'is_deleted'=>'0'));
							}
						}
					}
				}
				
				// Original draw_transaction_details
				$this->common_model->updateData('draw_transaction_details',array('is_result'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
				$this->common_model->updateData('draw_transaction_master',array('is_result'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
				
				////////////////////////////////////////
				////////////////////////////////////////
				if($draw_master_id == 48)
				{											
					$sqluser_master = "UPDATE	user_master 
						SET		winning_distribution = 0
						,		max_winning = 0
						WHERE 	winning_distribution  > '0'";
					$qquser_master = $this->db->query($sqluser_master);
				}

				$sqluser_master1 = "UPDATE	user_master 
						SET		winning_distribution = 0
						WHERE 	winning_distribution is NULL";
				$qquser_master1 = $this->db->query($sqluser_master1);
				
				//update balance and is_result_declare
				$this->common_model->updateData('draw_transaction_master',array('is_result'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
				
				$this->common_model->updateData('result_master',array('is_result_declare'=>'0','balance'=>$block_points6),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
				
				$this->common_model->deleteData('auto_cron',array('auto'=>'1'));
				////////////////////////////////////////
				// Update Duplicate Result in result_master
				$sqlupdate_double =
				"UPDATE	result_master 
				SET 	is_result_declare = 1
				,		updated_date = now()
				WHERE 	result_date  = '".$result_date."'
				AND	 	draw_master_id  = '".$draw_master_id."'
				AND 	result_master_id IN 
					(
						SELECT		MIN(result_master_id)
						FROM 		result_master 
						WHERE		result_date = '".$result_date."' 
						AND			draw_master_id  = '".$draw_master_id."'
						GROUP BY	series_master_id
						,			bajar_master_id
					)";
				$qqupd = $this->db->query($sqlupdate_double);
				$last_query =  "/*Update Duplicate Result*/" . " " .  $this->db->last_query();
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				// Update Duplicate Result in result_master
				////////////////////////////////////////////////////
				// Update Duplicate Result in draw_transaction_details
				$sqlupdate_dtd =
				"UPDATE	draw_transaction_details tdtd 
				SET		is_winning = 0
				WHERE	tdtd.result_date = '".$result_date."'
				AND		tdtd.draw_master_id  = '".$draw_master_id."'
				AND		EXISTS
						(
							SELECT	1
							FROM	result_master rm
							WHERE	rm.result_date = '".$result_date."'
							AND		rm.draw_master_id = '".$draw_master_id."'
							AND		rm.is_result_declare = 0
							AND		tdtd.series_master_id = rm.series_master_id
							AND 	tdtd.bajar_master_id = rm.bajar_master_id
							AND		tdtd.bid_akada_number = rm.bid_akada_number
						)
				AND	NOT EXISTS
						(
							SELECT	1
							FROM	result_master rm
							WHERE	tdtd.result_date = '".$result_date."'
							AND		rm.draw_master_id = '".$draw_master_id."'
							AND		rm.is_result_declare = 1
							AND		tdtd.series_master_id = rm.series_master_id
							AND 	tdtd.bajar_master_id = rm.bajar_master_id
							AND		tdtd.bid_akada_number = rm.bid_akada_number
						)";
				$qqupdtd = $this->db->query($sqlupdate_dtd);
				$last_query =  "/*Update Duplicate in draw_transaction_details*/" . " " .  $this->db->last_query();
				$this->common_model->insertData('q_info',array('info'=>$last_query));
				// Update Duplicate Result in draw_transaction_details
				////////////////////////////////////////////////
				
				////////////////////////////////////////
				// Original draw_transaction_details
				$sqlupd =
				"UPDATE     draw_transaction_details DTD 
				SET 		DTD.is_winning = 1
				WHERE 		DTD.result_date = '".$result_date."' 
				AND 		DTD.draw_master_id = '".$draw_master_id."'
				AND			DTD.is_winning = 0
				AND			DTD.is_deleted IN (0,2)
				AND 		EXISTS
				(
						SELECT	1
						FROM	result_master RM
						WHERE	RM.result_date = '".$result_date."' 
						AND		RM.draw_master_id = '".$draw_master_id."'
						AND		RM.series_master_id = DTD.series_master_id
						AND		RM.bajar_master_id = DTD.bajar_master_id
						AND		RM.bid_akada_number = DTD.bid_akada_number
				)";
				$qqupd = $this->db->query($sqlupd);
				///////////////////////////////////////////////////
				$this->common_model->updateData('draw_transaction_master',array('is_result'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
			}//if($ctime >= $wining_date_time && $win_data<"100" && $manuale=="0" && $no_manual==0)
		}//foreach($records as $r)
		
		////////////////////////////////////////
		// Update Duplicate Result 
		$sqlupdate_double =
		"UPDATE	result_master 
		SET 	is_result_declare = 0
		,		updated_date = now()
		WHERE 	result_date  = '".$result_date."'
		AND	 	draw_master_id  = (SELECT MAX(draw_master_id) from result_master WHERE	result_date = '".$result_date."')
		AND 	result_master_id IN 
			(
				SELECT		MAX(result_master_id)
				FROM 		result_master 
				WHERE		result_date = '".$result_date."' 
				AND			draw_master_id  = (SELECT MAX(draw_master_id) from result_master WHERE	result_date = '".$result_date."')
				AND			is_result_declare = 1
				GROUP BY	series_master_id
				,			bajar_master_id
				HAVING		COUNT(1)>1
			)";
		$qqupd = $this->db->query($sqlupdate_double);
		//$last_query =  "/*Update Duplicate Result again*/" . " " .  $this->db->last_query();
		//$this->common_model->insertData('q_info',array('info'=>$last_query));
		// Update Duplicate Result 
		////////////////////////////////////////////////////
		
		echo "Done";
	} // win_calculation1
	
	public function win_calculation0()
	{
		$result_date = date('Y-m-d');
		$chk = $this->common_model->getsingle('bid_amount_collection',array('result_date'=>$result_date));
		if(!$chk)
		{
			$this->db->truncate('bid_amount_collection');
		}
		
		$records = $this->common_model->getAllwhere('draw_master',array('is_draw_active'=>'0'));
		foreach($records as $r)
		{
			$draw_master_id = $r->draw_master_id;
			$fentime = date('H:i',strtotime($r->draw_end_time));
			
			$ctime = strtotime(date('Y-m-d H:i:s'));
			$wining_date_time =  strtotime ( date('Y-m-d H:i:s',strtotime(date('Y-m-d').' '.$fentime.':00 +5 seconds'))); 
			
			$win_data = $this->common_model->getsingle('result_master',array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
			
			$check_manual = $this->common_model->getsingle('check_cron',array('entry_date'=>$result_date,'draw_master_id'=>$draw_master_id));
			if(!$check_manual)
			{
				$manuale=0;
			}else{
				if($ctime>strtotime($check_manual->expiry_date_time))
				{
					$manuale=0;
				}else{
					$manuale=1;
				}
			}
			
			if($ctime >= $wining_date_time && !$win_data && $manuale=="0")	
			{
				$this->common_model->insertData('auto_cron',array('auto'=>'1','created_date'=>date('Y-m-d H:i:s')));
				
				$chhhkdataa = array(
					'date_time' => date('Y-m-d H:i:s'),
					'draw_id' 	=> $draw_master_id
				);
				$this->common_model->insertData('check_cron',$chhhkdataa);
				
				$this->common_model->updateData('draw_transaction_details',array('is_result'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
				$this->common_model->updateData('draw_transaction_master',array('is_result'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id));
					
					$sql = "INSERT INTO bid_amount_collection
						(
							result_date,
							draw_master_id,
							series_master_id,
							bajar_master_id,
							bid_akada_number,
							bid_points_collection
						) 
						select 
							result_date,
							draw_master_id,
							series_master_id,
							bajar_master_id,
							bid_akada_number,
							sum(bid_units*bid_points_multiplier* bid_points) from draw_transaction_details
							";
						$sql .=" where result_date='".$result_date."' AND draw_master_id NOT IN ( select draw_master_id from bid_amount_collection where result_date='".$result_date."' )";		
						$sql .=" AND draw_master_id='".$draw_master_id."' group by result_date,
									draw_master_id,
									series_master_id,
									bajar_master_id,
									bid_akada_number "; 
						$q = $this->db->query($sql);
						
						
						//series_master_id
						for($series_master_id=0;$series_master_id<10;$series_master_id++)
						{
							//bajar_master_id
							for($bajar_master_id=0;$bajar_master_id<10;$bajar_master_id++)
							{ 
								//Update
								$sql2 ="SELECT sum(bid_points_collection) as total_points_collection from bid_amount_collection where
								result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."' ";
								$qq = $this->db->query($sql2);
								$results = $qq->result();
								$total_points_collection = $results[0]->total_points_collection;		
								
								$sql3 = "UPDATE bid_amount_collection SET total_points_collection = '".$total_points_collection."' WHERE result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."' ";
								$qq = $this->db->query($sql3);
												
								$fsql ="select 
								draw_master_id,
								series_master_id,
								bajar_master_id,
								bid_akada_number,
								bid_points_collection,
								total_points_collection,
								(bid_points_collection*90) as winning,
								((bid_points_collection*90/total_points_collection)*100 ) as per
								from bid_amount_collection
								where result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."'
								AND	(bid_points_collection*90/total_points_collection)*100 <= 80
								order by (bid_points_collection*90/total_points_collection)*100 desc limit 1 ";
								$qqq = $this->db->query($fsql);
								$fresults = $qqq->result();
								if($fresults)
								{  
									$balance = $fresults[0]->total_points_collection - $fresults[0]->winning;
									$ins_data = array(
										'result_date' 	 	=> $result_date,
										'draw_master_id' 	=> $draw_master_id,
										'series_master_id' 	=> $series_master_id,
										'bajar_master_id' 	=> $bajar_master_id,
										'bid_akada_number' 	=> $fresults[0]->bid_akada_number,
										'type_of_draw'		=> '0',
										'balance'			=> $balance,
										'winning'			=> $fresults[0]->winning
									);
									$this->common_model->insertData('result_master',$ins_data);
									$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$fresults[0]->bid_akada_number,'is_deleted'=>'0'));										
									
									$str = $this->db->last_query();
									echo $str.'</br>';
								}else{
									
									if($draw_master_id==1)
									{
										$did = "48";
									}else{
										$did = $draw_master_id - 1;
									}
									//$prev				
									$prevq ="SELECT balance from result_master where draw_master_id='".$did."' AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."' order by result_master_id desc limit 1";
									$pqq = $this->db->query($prevq);
									$presults = $pqq->result();					
									$num = $pqq->num_rows();
									
									if($presults)
									{
										$prebalance = $presults[0]->balance;						
									}else{
										$prebalance = 0;
									}
									
									$fsql ="select 
									draw_master_id,
									series_master_id,
									bajar_master_id,
									bid_akada_number,
									bid_points_collection,
									(total_points_collection+".$prebalance.") as total_points_collection,
									(bid_points_collection*90) as winning,
									((bid_points_collection*90/total_points_collection)*100 ) as per
									from bid_amount_collection
									where result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."'
									AND	(bid_points_collection*90/total_points_collection+".$prebalance.")*100 <= 50
									order by (bid_points_collection*90/total_points_collection+".$prebalance.")*100 desc limit 1 ";
									$qqq = $this->db->query($fsql);
									$fresults = $qqq->result();
									if($fresults)
									{  
										$balance = $fresults[0]->total_points_collection - $fresults[0]->winning;
										$ins_data = array(
											'result_date' 	 	=> $result_date,
											'draw_master_id' 	=> $draw_master_id,
											'series_master_id' 	=> $series_master_id,
											'bajar_master_id' 	=> $bajar_master_id,
											'bid_akada_number' 	=> $fresults[0]->bid_akada_number,
											'type_of_draw'		=> '0',
											'balance'			=> $balance,
											'winning'			=> $fresults[0]->winning
										);
										$this->common_model->insertData('result_master',$ins_data);
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$fresults[0]->bid_akada_number,'is_deleted'=>'0'));										
										$str = $this->db->last_query();
										echo $str.'</br>';
									}else{
										
										$fsql2 ="Select bid_akada_number from all_bid_akada where bid_akada_number NOT IN 
													( Select bid_akada_number from draw_transaction_details where
													result_date='".$result_date."' AND draw_master_id='".$draw_master_id."'
													AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."'
													) ORDER BY rand() LIMIT 1 ";
										$qqq2 = $this->db->query($fsql2);
										$fresults2 = $qqq2->result();
										$rndss = $fresults2[0]->bid_akada_number;
										if($rndss)
										{
											$rnd = $rndss;
										}
										else
										{
											$fsql22 ="select
											bid_akada_number,
											(bid_units*bid_points_multiplier* bid_points) from draw_transaction_details where
											result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' 
											AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."' 
											order by (bid_units*bid_points_multiplier* bid_points) asc limit 1 ";
											$qqq22 = $this->db->query($fsql22);
											$fresults22 = $qqq22->result();
											$rnd = $fresults22[0]->bid_akada_number;
										}
										
										$fsql ="select 
										draw_master_id,
										series_master_id,
										bajar_master_id,
										bid_akada_number,
										bid_points_collection
										from bid_amount_collection
										where result_date='".$result_date."' AND draw_master_id='".$draw_master_id."' AND bajar_master_id='".$bajar_master_id."'AND series_master_id='".$series_master_id."'
										limit 1 ";
										$qqq = $this->db->query($fsql);
										$fresults = $qqq->result();
										if($fresults)
										{
											$balance = $fresults[0]->bid_points_collection;						
										}else{$balance=0;}
										
										$ins_data = array(
											'result_date' 	 	=> $result_date,
											'draw_master_id' 	=> $draw_master_id,
											'series_master_id' 	=> $series_master_id,
											'bajar_master_id' 	=> $bajar_master_id,
											'bid_akada_number' 	=> $rnd,
											'type_of_draw'		=> '0',
											'balance'			=> $balance,
											'winning'			=> '0'
										);
										$this->common_model->insertData('result_master',$ins_data);
										$this->common_model->updateData('draw_transaction_details',array('is_winning'=>'1'),array('result_date'=>$result_date,'draw_master_id'=>$draw_master_id,'series_master_id'=>$series_master_id,'bajar_master_id'=>$bajar_master_id,'bid_akada_number'=>$rnd,'is_deleted'=>'0'));
										$str = $this->db->last_query();
										echo $str.'</br>';
									}
									
									
								}
								
							}
					
					}	

				$this->common_model->deleteData('auto_cron',array('auto'=>'1'));			
			}
		//end foreach 
		} 
				
		echo "done";
		
	}
	
	public function bonus_distribution()
	{
		/*$my_reports = $this->common_model->getAllwhere('user_master',array('user_type'=>'1','is_user_deleted'=>'0'));		
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
							$ins_data = array(
								'bonus_master_id'	=> $r->bonus_master_id,
								'user_master_id'	=> $user_master_id,
								'entry_date'		=> date('Y-m-d')
								);
							$this->common_model->insertData('bonus_distribution',$ins_data);
							
							//transaction point to user
							$retailer_balance = $this->common_model->getcurrent_balance($user_master_id);				
							$cp = $retailer_balance + $r->amount;	
							
							$insdata = array(
											'transactions_date' 		=> date('Y-m-d'),						
											'from_user_master_id' 		=> $user_master_id,
											'to_user_master_id' 		=> '1',
											'points_transferred' 		=> $r->amount,
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
							
							//admin
							$admin_balance = $this->common_model->getcurrent_balance('1');
							$cpp = $admin_balance - $r->amount;	
							
							$insdata = array(
											'transactions_date' 		=> date('Y-m-d'),						
											'from_user_master_id' 		=> '1',
											'to_user_master_id' 		=> $user_master_id,
											'points_transferred' 		=> $r->amount,
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
		
		echo "done";
		*/
	}
	
}	

?>	