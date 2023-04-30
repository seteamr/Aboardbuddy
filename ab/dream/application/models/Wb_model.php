<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class WB_model extends CI_Model
{   
	function login($user_name,$user_password,$device_token){
		 $str_sql = "SELECT	* 
					FROM	user_master 
					WHERE	user_name ='".$user_name."' 
					AND		user_password ='".$user_password."' 
					AND	(		device_token ='".$device_token."' 
							OR	device_token =''
							OR	device_token ='123456'
						)";
		 $q = $this->db->query($str_sql);
		 $result = $q->result();
		 return $result;    
    }
	 /*---GET SINGLE RECORD---*/
    function getsingle($table, $where)
    {
        $q = $this->db->get_where($table, $where);
        return $q->row();
    }
	
	 /*<!--INSERT RECORD FROM SINGLE TABLE-->*/
    function insertData($table, $dataInsert)
    {
        $this->db->insert($table, $dataInsert);
        return $this->db->insert_id();
    }
	
	 /*<!--UPDATE RECORD FROM SINGLE TABLE-->*/
    function updateData($table, $data, $where) 
    {
        $this->db->update($table, $data, $where);
        return;
    }
	 /*<!--DELETE RECORD FROM SINGLE TABLE-->*/
    function deleteData($table, $where)
    {
        //$this->db->delete('mytable', array('id' => $id));
        $this->db->delete($table, $where);
        return;
    }
	
	 /*<!--GET ALL RECORD FROM SINGLE TABLE WITHOUT CONDITION-->*/
    function getAllrecord($table)
    {
        $this->db->select('*');
        $q = $this->db->get($table);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
		
	  /*---GET MULTIPLE RECORD---*/
    function getAllwhere($table, $where)
    {
        $this->db->select('*');
        $q = $this->db->get_where($table, $where);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
	
	function getAllwhere_order_by($table, $where ,$colomn,$type ,$limit='')
    {
        $this->db->select('*');
		$this->db->order_by($colomn,$type);
		if($limit!="")
		{
			$this->db->limit($limit);
		}
        $q = $this->db->get_where($table, $where);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }

    function getlastrecord($table, $where)
    {
        $this->db->get_where($table, $where);
        $q = $this->db->order_by('', $where);
        return $q->row();
    }
	
	public function getcurrent_balance($user_master_id)
	{
      $this->db->select('closing_points');
      $this->db->from('points_transactions');
	  $this->db->where('from_user_master_id',$user_master_id);
      $this->db->order_by('points_transactions_id','desc');
      $query=$this->db->get();
      $row=$query->row();
	  if($row)
	  {
		 return $row->closing_points; 
	  }else{
		  return 0;
	  }
      
	}
	
	public function getcurrent_balance_new($user_master_id)
	{
      $this->db->select('closing_points,transactions_date');
      $this->db->from('points_transactions');
	  $this->db->where('from_user_master_id',$user_master_id);
      $this->db->order_by('points_transactions_id','desc');
      $query=$this->db->get();
      $row=$query->row();
	  if($row)
	  {
		 return $row; 
	  }else{
		  return 0;
	  }
      
	}
	
	public function getsumplay($draw_transaction_master_id)
	{
		$query = $this->db->query("SELECT sum(bid_units*bid_points*bid_points_multiplier) as total FROM draw_transaction_details where draw_transaction_master_id='$draw_transaction_master_id' and is_deleted='0' ");
		$sum = $query->row()->total;
		if($sum)
		{
			return $sum;
		}else{
			return 0;
		}
	}
	
	public function getcurrentdrawid()
    {
    	$query = $this->db->query("SELECT draw_master_id
    			FROM draw_master
    			WHERE TIME_FORMAT(ADDTIME(NOW(),'5:30'), '%h:%i %p')
    			BETWEEN TIME_FORMAT(draw_start_time, '%h:%i %p') AND TIME_FORMAT(draw_end_time, '%h:%i %p')");
    			
    	$drawmasterid = $query->row()->draw_master_id;
    	if($drawmasterid)
    	{
    		return $drawmasterid;
    	}else{
    		return -1;
    	}
    }
	
	
	
	function getAllwhere_result($table, $where)
    { 
        $this->db->select('*');
        $this->db->where('is_result_declare','1');
		$this->db->order_by('draw_master_id desc , series_master_id asc , bajar_master_id asc');
        $q = $this->db->get_where($table, $where);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
	
	function getAllwhere_result_join($result_date,$draw_master_id="")
    { 
        $this->db->select('result_master.result_date,result_master.draw_master_id,result_master.series_master_id,result_master.bajar_master_id,result_master.bid_akada_number,draw_master.draw_start_time,draw_master.draw_end_time');
		 $this->db->from('result_master');
        $this->db->where('result_master.result_date',$result_date);
		if($draw_master_id!="")
		{
			$this->db->where('result_master.draw_master_id',$draw_master_id);
		}
       
		$this->db->join('draw_master', 'draw_master.draw_master_id = result_master.draw_master_id');
        $this->db->where('result_master.is_result_declare','1');
		$this->db->order_by('result_master.draw_master_id desc , result_master.series_master_id asc , result_master.bajar_master_id asc');	$query = $this->db->get();
		return $query->result(); 
    }
	
	function getAllwhere_result_new($table, $where)
    { 
        $this->db->select('*');
		$this->db->order_by('draw_master_id desc , series_master_id asc , bajar_master_id asc','result_master_id desc');
		$this->db->limit('100');
        $q = $this->db->get_where($table, $where);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
	
	function totalbidamount($draw_transaction_master_id)
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');
		$this->db->where('draw_transaction_master_id', $draw_transaction_master_id);		
		$this->db->where('is_deleted', '0');		
		$query = $this->db->get();
		return $query->result(); 
	}

    function getAllResult(){
        $this->db->select('*');
        $this->db->from('result_master');
        $this->db->where('is_result_declare','1');
        $this->db->order_by('result_date desc , draw_master_id desc , series_master_id asc , bajar_master_id asc');
        $this->db->limit('100');
        $q=$this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
	
	function getAllwhere_net_to_pay($user_type,$my_downs='',$fromdate='',$todate='')
    {
        $this->db->select('draw_transaction_details.user_master_id,user_master.user_name,user_master.name,user_master.user_type'); 
		$this->db->from('draw_transaction_details');
		$this->db->where('draw_transaction_details.is_deleted','0');
		if( $fromdate!='' && $todate!='' ){			
			$this->db->where('draw_transaction_details.result_date >=', $fromdate);
			$this->db->where('draw_transaction_details.result_date <=', $todate);
		
		}
		if($user_type!='3')
		{
			if($my_downs!="")
			{
				$this->db->where_in('draw_transaction_details.user_master_id',$my_downs);
			}			
		}		
		$this->db->join('user_master', 'draw_transaction_details.user_master_id = user_master.user_master_id');
		$this->db->group_by('draw_transaction_details.user_master_id');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function total_bid_counter_sale($user_master_id,$type='',$fromdate='',$todate='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');
		$this->db->where('is_deleted','0');
		$this->db->where('user_master_id', $user_master_id);
		if( $fromdate!='' && $todate!='' ){			
			$this->db->where('draw_transaction_details.result_date >=', $fromdate);
			$this->db->where('draw_transaction_details.result_date <=', $todate);
		
		}
		if($type!='')
		{
			$this->db->where('is_deleted', '0');
			$this->db->where('is_winning', '1');
			$this->db->where('is_claim', '1');
		}
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function total_bid_counter_sale_cuurentclaim($user_master_id,$type='',$fromdate='',$todate='')
    {
		$this->db->select('sum(`draw_transaction_details`.`bid_units`*`draw_transaction_details`.`bid_points`*`draw_transaction_details`.`bid_points_multiplier`) as total', FAlSE);
		
		$this->db->from('draw_transaction_details');
		$this->db->join('draw_transaction_master', 'draw_transaction_details.draw_transaction_master_id = draw_transaction_master.draw_transaction_master_id');
		$this->db->where('draw_transaction_details.is_deleted','0');
		$this->db->where('draw_transaction_details.user_master_id', $user_master_id);
		if( $fromdate!='' && $todate!='' ){			
			$this->db->where('draw_transaction_master.claim_date_time >=', $fromdate.' 00:00:00');
			$this->db->where('draw_transaction_master.claim_date_time <=', $todate.' 23:59:59');
			
			//$this->db->where('draw_transaction_details.result_date >=', $fromdate);
			//$this->db->where('draw_transaction_details.result_date <=', $todate);
		
		}
		if($type!='')
		{
			$this->db->where('draw_transaction_details.is_deleted', '0');
			$this->db->where('draw_transaction_details.is_winning', '1');
			$this->db->where('draw_transaction_details.is_claim', '1');
		}
		
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function total_comission($user_master_id,$fromdate='',$todate='')
    {
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');
		$this->db->where('from_user_master_id', $user_master_id);
		if( $fromdate!='' && $todate!='' ){			
			$this->db->where('points_transactions.transactions_date >=', $fromdate);
			$this->db->where('points_transactions.transactions_date <=', $todate);
		
		}
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function total_comission_new($user_master_id,$from_date='',$to_date='')
    {
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');
		$this->db->where('from_user_master_id', $user_master_id);
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);		
		}	
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}	
		$query = $this->db->get();
		$total_comission =  $query->result(); 
		if($total_comission[0]->total!="")
		{
			$com = $total_comission[0]->total;
		}else{
			$com = 0;
		}
		
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','0');
		$this->db->where('transaction_nature','6');
		$this->db->where('from_user_master_id', $user_master_id);
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);		
		}	
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}			
		$query = $this->db->get();
		$total_comission =  $query->result(); 
		if($total_comission[0]->total!="")
		{
			$com1 = $total_comission[0]->total;
		}else{
			$com1 = 0;
		}
		
		return $com - $com1;
		
	}
	
	function total_comission_new2($user_master_id,$from_date='',$to_date='')
    {
		$from_date = date("Y-m-d", strtotime($from_date));
		$to_date = date("Y-m-d", strtotime($to_date));
		
		$query = "SELECT SUM(IF(pt.transaction_nature = 1, pt.points_transferred, -pt.points_transferred)) AS Retailer_commission
				FROM	points_transactions pt
				WHERE	pt.transaction_type = 0 ";
		if($from_date!="" && $to_date!="")
		{			
			$query .= "		AND		pt.transactions_date BETWEEN '$from_date' AND '$to_date' ";
		}
		$query .= "		AND		pt.transaction_nature IN (1,6)
				AND		pt.transaction_narration IN('Retailer commission','Retailer Commission Remove')
				AND		EXISTS
						(
							SELECT	1
							FROM	draw_transaction_master dtm
							WHERE	dtm.draw_transaction_master_id = pt.draw_transaction_master_id
							AND		dtm.result_date = pt.transactions_date
							AND		dtm.user_master_id = '$user_master_id'
						)
				Group BY pt.user_master_id";
		
		$q = $this->db->query($query);
		$result = $q->result_array();
		return $result;    
	}
	
	function total_bonus($user_master_id,$fromdate='',$todate='')
    {
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','5');
		$this->db->where('from_user_master_id', $user_master_id);
		if( $fromdate!='' && $todate!='' ){			
			$this->db->where('points_transactions.transactions_date >=', $fromdate);
			$this->db->where('points_transactions.transactions_date <=', $todate);
		
		}
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function getAllwhere_bonus_history($user_type,$my_downs='',$fromdate='',$todate=''){
		$this->db->select('points_transactions.points_transferred,points_transactions.from_user_master_id,user_master.user_name,user_master.name,points_transactions.transactions_date');
		$this->db->from('points_transactions');
		$this->db->where('points_transactions.transaction_type','1');
		$this->db->where('points_transactions.transaction_nature','5');
		if($user_type!='3'){
		if($my_downs!="")
			{
			$this->db->where_in('points_transactions.from_user_master_id', $my_downs);
			}
		}
		if( $fromdate!='' && $todate!='' ){			
			$this->db->where('points_transactions.transactions_date >=', $fromdate);
			$this->db->where('points_transactions.transactions_date <=', $todate);
		
		}
		$this->db->join('user_master', 'points_transactions.from_user_master_id = user_master.user_master_id');
		$this->db->order_by('points_transactions.transactions_date','desc');
		$this->db->limit($limit, $start);	
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function getAllwhere_result_new2($table, $where)
    { 
        $this->db->select('*');
		$this->db->order_by('draw_master_id desc , series_master_id asc , bajar_master_id asc','result_master_id desc');
		$this->db->limit('1');
        $q = $this->db->get_where($table, $where);
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }
    }
	
	public function getclaimdatat($bar_code_number)
    {
    	$claimSql = "SELECT	*
					FROM	draw_transaction_details  dtd
					WHERE	dtd.draw_transaction_master_id = 
					(		SELECT	draw_transaction_master_id 
							FROM	draw_transaction_master  
							WHERE	bar_code_number = '".$bar_code_number."'
					)
					AND		EXISTS
					(
						SELECT	1
						FROM	result_master rm1
						WHERE	rm1.result_date = dtd.result_date
						AND		rm1.draw_master_id = dtd.draw_master_id
						AND		rm1.series_master_id = dtd.series_master_id
						AND		rm1.bajar_master_id = dtd.bajar_master_id
						AND		rm1.bid_akada_number = dtd.bid_akada_number
					)";
		$query = $this->db->query($claimSql);    			
		$result = $query->result();
		return $result;   
    }
}