<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model
{
   function search_intro($key){
     $q = $this->db->query("SELECT * FROM users where ( login_id LIKE '%".$key."%' or full_name LIKE '%".$key."%' ) AND status = '0' ");
     $result = $q->result_array();
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
	
	function getAllwhere_field($table, $where,$filed)
    {
        $this->db->select($filed);
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
	
	function getAllwhere_in_data($table, $where,$my_downs)
    {
        $this->db->select('*');
		if(count($my_downs)>0)
		{
			$this->db->where_in('user_master_id',$my_downs);
		}else{
			$user_master_id = $this->session->userdata('user_master_id');
			$this->db->where('reporting_user_master_id',$user_master_id);			
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
	
	function all_users_current_balance($my_downs="")
    {  
		if($my_downs!="")
		{
		$query = 'SELECT SUM(closing_points) as balance FROM points_transactions 
				WHERE points_transactions_id IN (SELECT MAX(points_transactions_id) AS id
							 FROM points_transactions where from_user_master_id IN (' . implode(',', array_map('intval', $my_downs)) . ') GROUP BY from_user_master_id
							 ) 
				ORDER BY points_transactions_id ';
		}else{
			$user_master_id = "000";
		$query = "SELECT SUM(closing_points) as balance FROM points_transactions 
				WHERE points_transactions_id IN (SELECT MAX(points_transactions_id) AS id
							 FROM points_transactions where from_user_master_id='".$user_master_id."' GROUP BY from_user_master_id
							 ) 
				ORDER BY points_transactions_id ";	
		}
		
		$q = $this->db->query($query);
		$result = $q->result_array();
		return $result;    
    }
	
	
	public function total_record($table,$result_date,$draw_master_id) 
	{	
		
		$this->db->from($table);
		$this->db->where('result_date',$result_date);
		$this->db->where('draw_master_id',$draw_master_id);		
		return $this->db->count_all_results();		
	}
	
	function gettransaction_user($from_user_master_id)
    {        
		$this->db->select('user_master.*,points_transactions.to_user_master_id'); 
		$this->db->from('user_master');
		$this->db->where('points_transactions.from_user_master_id',$from_user_master_id);
		$this->db->join('points_transactions', 'points_transactions.to_user_master_id = user_master.user_master_id');
		$this->db->group_by('points_transactions.to_user_master_id');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_admin($table,$username='') 
	{		
		$this->db->from($table);
		$this->db->where('user_type !=','3');
		if($username!=''){
		$this->db->like('user_name', $username);
		}
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_admin($table,$limit,$start,$username='')
    {
        $this->db->select('*'); 
		$this->db->from($table);
		$this->db->where('user_type !=','3');	
		if($username!=''){
		$this->db->like('user_name', $username);
		}
		$this->db->order_by('user_master_id','desc');		
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_distribter($table,$my_downs,$username='') 
	{	
		$user_master_id = $this->session->userdata('user_master_id');
		$this->db->from($table);
		if(count($my_downs)>0)
		{
			$this->db->where_in('reporting_user_master_id',$my_downs);
		}
		$this->db->or_where('reporting_user_master_id',$user_master_id);
		if($username!=''){
		$this->db->like('user_name', $username);
		}
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_distribter($table,$limit,$start,$my_downs,$username='')
    {
		$user_master_id = $this->session->userdata('user_master_id');
        $this->db->select('*'); 
		$this->db->from($table);		
		if(count($my_downs)>0)
		{
			$this->db->where_in('reporting_user_master_id',$my_downs);
		}
		$this->db->or_where('reporting_user_master_id',$user_master_id);
		if($username!=''){
		$this->db->like('user_name', $username);
		}
		$this->db->order_by('user_master_id','desc');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_shop($table,$username='') 
	{	
		$user_master_id = $this->session->userdata('user_master_id');
		$this->db->from($table);		
		$this->db->where('reporting_user_master_id',$user_master_id);
		if($username!=''){
		$this->db->like('user_name', $username);
		}
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_shop($table,$limit,$start)
    {
		$user_master_id = $this->session->userdata('user_master_id');
        $this->db->select('*'); 
		$this->db->from($table);	
		$this->db->where('reporting_user_master_id',$user_master_id);
		if($username!=''){
		$this->db->like('user_name', $username);
		}
		$this->db->order_by('user_master_id','desc');
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result(); 
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
	
	public function record_count_transaction($from_date='',$to_date='') 
	{	
		$user_master_id = $this->session->userdata('user_master_id');
		$this->db->from('points_transactions');		
		//$this->db->where('from_user_master_id',$user_master_id);		
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);
		}
		if($to_date!=''){	
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}
		$this->db->where("from_user_master_id='$user_master_id' and ( transaction_nature=0 or transaction_nature=4)" );
		//$this->db->where('transaction_nature','0');
		//$this->db->or_where('transaction_nature','4');
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_transaction($limit,$start,$from_date='',$to_date='')
    {
		$user_master_id = $this->session->userdata('user_master_id');
        $this->db->select('*'); 
		$this->db->from('points_transactions');	
		//$this->db->where('from_user_master_id',$user_master_id);
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);
		}
		if($to_date!=''){	
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}
		$this->db->where("from_user_master_id='$user_master_id' and ( transaction_nature=0 or transaction_nature=4)" );
		//$this->db->or_where('transaction_nature','4');
		$this->db->limit($limit, $start);
		$this->db->order_by('points_transactions_id', 'desc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_transaction_all($from_date='',$to_date='',$from_user_master_id='',$to_user_master_id='',$transaction_nature='') 
	{	
		$this->db->from('points_transactions');
		if($from_user_master_id!='')
		{
			$this->db->where('from_user_master_id', $from_user_master_id);
		}
		if($to_user_master_id!='')
		{
			$this->db->where('to_user_master_id', $to_user_master_id);
		}
		if($transaction_nature!='')
		{
			$this->db->where('transaction_nature', $transaction_nature);
		}
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);
		}
		if($to_date!=''){	
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}		
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_transaction_all($limit,$start,$from_date='',$to_date='',$from_user_master_id='',$to_user_master_id='',$transaction_nature='')
    {
        $this->db->select('*'); 
		$this->db->from('points_transactions');	
		if($from_user_master_id!='')
		{
			$this->db->where('from_user_master_id',$from_user_master_id);
		}
		if($to_user_master_id!='')
		{
			$this->db->where('to_user_master_id', $to_user_master_id);
		}
		if($transaction_nature!='')
		{
			$this->db->where('transaction_nature', $transaction_nature);
		}
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);
		}
		if($to_date!=''){	
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}	
		$this->db->limit($limit, $start);
		$this->db->order_by('points_transactions_id', 'desc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_point_transaction_all($from_date='',$to_date='',$from_user_master_id='',$to_user_master_id='',$transaction_nature='') 
	{	
		$this->db->from('points_transactions');
		if($from_user_master_id!='')
		{
			$this->db->where('from_user_master_id', $from_user_master_id);
		}
		if($to_user_master_id!='')
		{
			$this->db->where('to_user_master_id', $to_user_master_id);
		}
		if($transaction_nature!='')
		{
			$this->db->where('transaction_nature', $transaction_nature);
		}		
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);
		}
		if($to_date!=''){	
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}	
		$this->db->where("transaction_nature='0' OR transaction_nature='4' ");
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_poin_transaction_all($limit,$start,$from_date='',$to_date='',$from_user_master_id='',$to_user_master_id='',$transaction_nature='')
    {
        $this->db->select('*'); 
		$this->db->from('points_transactions');	
		if($from_user_master_id!='')
		{
			$this->db->where('from_user_master_id',$from_user_master_id);
		}
		if($to_user_master_id!='')
		{
			$this->db->where('to_user_master_id', $to_user_master_id);
		}
		if($transaction_nature!='')
		{
			$this->db->where('transaction_nature', $transaction_nature);
		}
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));
			$this->db->where('transactions_date >=', $fromdate);
		}
		if($to_date!=''){	
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('transactions_date <=', $todate);		
		}	
		$this->db->where("transaction_nature='0' OR transaction_nature='4' ");
		$this->db->limit($limit, $start);
		$this->db->order_by('points_transactions_id', 'desc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function count_collection($result_date,$draw_master_id,$series_master_id,$bajar_master_id,$bid_akada_number)
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('result_date', $result_date);
		$this->db->where('draw_master_id', $draw_master_id);
		$this->db->where('series_master_id', $series_master_id);
		$this->db->where('bajar_master_id', $bajar_master_id);
		$this->db->where('bid_akada_number', $bid_akada_number);
		$query = $this->db->get();
		return $query->result(); 
	}
	
	public function record_count_result($date='') 
	{
		$this->db->from('result_master');
		$this->db->where('is_result_declare', '1');
		if($date!=''){
			$this->db->where('result_date', $date);
		}else{
			$this->db->where('result_date', date('Y-m-d'));
		}
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_result($limit,$start,$date='')
    {		
        $this->db->select('*'); 
		$this->db->from('result_master');
		$this->db->where('is_result_declare', '1');
		if($date!=''){
			$this->db->where('result_date', $date);
		}else{
			$this->db->where('result_date', date('Y-m-d'));
		}
		$this->db->limit($limit, $start);
		$this->db->order_by('draw_master_id desc , series_master_id asc , bajar_master_id asc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_supports($username='') 
	{	
		$this->db->from('support');
		if($username!=''){
		$this->db->like('user_master.user_name', $username);
		$this->db->join('user_master', 'support.user_master_id = user_master.user_master_id');
		}
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_supports($limit,$start,$username='')
    {
        $this->db->select('support.*,user_master.user_name,user_master.name,user_master.user_type'); 
		$this->db->from('support');
		if($username!='')
		{
			$this->db->like('user_master.user_name', $username);
		}
		$this->db->join('user_master', 'support.user_master_id = user_master.user_master_id');
		$this->db->limit($limit, $start);
		$this->db->order_by('support.support_id', 'desc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_play_history($username='',$from_date='',$to_date='',$my_downs='') 
	{	
		$user_master_id = $this->session->userdata('user_master_id');		
		$this->db->from('draw_transaction_master');
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('draw_transaction_master.result_date >=', $fromdate);	
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('draw_transaction_master.result_date <=', $todate);	
		}
		if($this->session->userdata('user_type')!='3')
		{
			if($my_downs!="")
			{
				$this->db->where_in('draw_transaction_master.user_master_id',$my_downs);
			}
		}
		
		if($username!=''){
			$this->db->like('user_master.user_name', $username);
		}
		
		$this->db->join('user_master', 'draw_transaction_master.user_master_id = user_master.user_master_id');
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination_play_history($limit,$start,$username='',$from_date='',$to_date='',$my_downs='')
    {
        $this->db->select('draw_transaction_master.*,user_master.user_name,user_master.name,user_master.user_type,user_master.user_master_id'); 
		$this->db->from('draw_transaction_master');
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('draw_transaction_master.result_date >=', $fromdate);	
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('draw_transaction_master.result_date <=', $todate);	
		}
		if($this->session->userdata('user_type')!='3')
		{
			if($my_downs!="")
			{
				$this->db->where_in('draw_transaction_master.user_master_id',$my_downs);
			}
		}
		
		if($username!=''){
			$this->db->like('user_master.user_name', $username);
		}
		$this->db->join('user_master', 'draw_transaction_master.user_master_id = user_master.user_master_id');
		$this->db->limit($limit, $start);
		$this->db->order_by('draw_transaction_master.draw_transaction_master_id', 'desc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function total_bid_winning($result_date,$draw_transaction_master_id,$type='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('result_date', $result_date);
		$this->db->where('draw_transaction_master_id', $draw_transaction_master_id);
		if($type!='')
		{
			$this->db->where('is_winning', $type);
			$this->db->where('claim_user_master_id !=', null);
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	
	
	function getAllwhere_view($draw_transaction_master_id)
    {		
        $this->db->select('*'); 
		$this->db->from('draw_transaction_details');
		$this->db->where('draw_transaction_master_id', $draw_transaction_master_id);
		$this->db->order_by('draw_master_id desc , series_master_id asc , bajar_master_id asc , bid_akada_number asc');
		//$this->db->group_by('series_master_id');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function record_count_counter_sale($username='',$from_date='',$to_date='',$my_downs='') 
	{	
		$this->db->select('draw_transaction_details.user_master_id,user_master.user_name,user_master.name,user_master.user_type'); 
		$this->db->from('draw_transaction_details');
		$this->db->where('draw_transaction_details.is_deleted','0');
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('draw_transaction_details.result_date >=', $fromdate);		
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('draw_transaction_details.result_date <=', $todate);		
		}
		if($this->session->userdata('user_type')!='3')
		{
			if($my_downs!="")
			{
				$this->db->where_in('draw_transaction_details.user_master_id',$my_downs);
			}
		}
		
		if($username!=''){
			$this->db->like('user_master.user_name', $username);
		}
		$this->db->join('user_master', 'draw_transaction_details.user_master_id = user_master.user_master_id');
		if($username!=''){
		$this->db->group_by('draw_transaction_details.user_master_id,draw_transaction_details.result_date');
		}else{
		$this->db->group_by('draw_transaction_details.user_master_id');
		}
		
		$query = $this->db->get();
		return $query->num_rows(); 	
	}
	
	function getAllwhere_pagination_counter_sale($limit,$start,$username='',$from_date='',$to_date='',$my_downs='')
    {
        $this->db->select('draw_transaction_details.result_date,draw_transaction_details.user_master_id,user_master.user_name,user_master.name,user_master.user_type'); 
		$this->db->from('draw_transaction_details');
		$this->db->where('draw_transaction_details.is_deleted','0');
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('draw_transaction_details.result_date >=', $fromdate);		
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('draw_transaction_details.result_date <=', $todate);		
		}
		if($this->session->userdata('user_type')!='3')
		{
			if($my_downs!="")
			{
				$this->db->where_in('draw_transaction_details.user_master_id',$my_downs);
			}			
		}
		
		if($username!=''){
			$this->db->like('user_master.user_name', $username);
		}
		$this->db->join('user_master', 'draw_transaction_details.user_master_id = user_master.user_master_id');
		if($username!=''){
		$this->db->group_by('draw_transaction_details.user_master_id,draw_transaction_details.result_date');
		}else{
		$this->db->group_by('draw_transaction_details.user_master_id');
		}
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result(); 
    }
	

	public function read_count_bonus_history($username='',$from_date='',$to_date='',$my_downs=''){
		$this->db->select('points_transactions.points_transferred,points_transactions.from_user_master_id,user_master.user_name,user_master.name,points_transactions.transactions_date');
		$this->db->from('points_transactions');
		$this->db->where('points_transactions.transaction_type','1');
		$this->db->where('points_transactions.transaction_nature','5');
		if($this->session->userdata('user_type')!='3'){
			if($my_downs!="")
			{
			$this->db->where_in('points_transactions.from_user_master_id', $my_downs);
			}
		}
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('points_transactions.transactions_date >=', $fromdate);	
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('points_transactions.transactions_date <=', $todate);
		}
		
		if($username!=''){
			$this->db->like('user_master.user_name', $username);
		}
		$this->db->join('user_master', 'points_transactions.from_user_master_id = user_master.user_master_id');	
		$this->db->order_by('points_transactions.transactions_date','desc');
		$query = $this->db->get();
		return $query->num_rows(); 	
	}
	
	function getAllwhere_pagination_bonus_history($limit,$start,$username='',$from_date='',$to_date='',$my_downs=''){
		$this->db->select('points_transactions.points_transferred,points_transactions.from_user_master_id,user_master.user_name,user_master.name,points_transactions.transactions_date');
		$this->db->from('points_transactions');
		$this->db->where('points_transactions.transaction_type','1');
		$this->db->where('points_transactions.transaction_nature','5');
		if($this->session->userdata('user_type')!='3'){
		if($my_downs!="")
			{
			$this->db->where_in('points_transactions.from_user_master_id', $my_downs);
			}
		}
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('points_transactions.transactions_date >=', $fromdate);	
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('points_transactions.transactions_date <=', $todate);
		}
		if($username!=''){
			$this->db->like('user_master.user_name', $username);
		}
		$this->db->join('user_master', 'points_transactions.from_user_master_id = user_master.user_master_id');
		$this->db->order_by('points_transactions.transactions_date','desc');
		$this->db->limit($limit, $start);	
		$query = $this->db->get();
		return $query->result(); 
	}

	
	function total_bid_counter_sale($user_master_id,$from_date,$to_date,$type='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');
		$this->db->where('is_deleted','0');
		$this->db->where('user_master_id', $user_master_id);
		if($from_date!=''){		
			$fromdate = date("Y-m-d", strtotime($from_date));			
			$this->db->where('result_date >=', $fromdate);		
		}
		if($to_date!=''){
			$todate = date("Y-m-d", strtotime($to_date)); 
			$this->db->where('result_date <=', $todate);		
		}
		if($type!='')
		{
			$this->db->where('is_winning', $type);
			$this->db->where('claim_user_master_id', $user_master_id);
		}
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function total_comission($user_master_id,$from_to_date)
    {
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');
		$this->db->where('from_user_master_id', $user_master_id);
		if($from_to_date!=''){
			$date=explode(" - ",$from_to_date);			
			$fromdate = date("Y-m-d", strtotime($date[0]));
			$todate = date("Y-m-d", strtotime($date[1])); 
			
			$this->db->where('transactions_date >=', $fromdate);
			$this->db->where('transactions_date <=', $todate);
		
		}		
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function total_comission_new($user_master_id,$from_date,$to_date)
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
	
	function total_bonus($user_master_id,$from_date,$to_date)
    {
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','5');
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
		return $query->result(); 
	}

	function cancle_count_for_admin($fromdate,$todate,$my_downs='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '1');
		$this->db->where('result_date >=', $fromdate);
		$this->db->where('result_date <=', $todate);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	function sale_count_for_admin($fromdate,$todate,$my_downs='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('result_date >=', $fromdate);
		$this->db->where('result_date <=', $todate);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	function winning_count_for_admin($fromdate,$todate,$my_downs='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`*90) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('is_winning', '1');
		$this->db->where('is_claim', '1');
		$this->db->where('result_date >=', $fromdate);
		$this->db->where('result_date <=', $todate);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	function saleday_count_for_admin($date,$my_downs='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('result_date', $date);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	function winningday_count_for_admin($date,$my_downs='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`*90) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('is_winning', '1');
		$this->db->where('is_claim', '1');
		$this->db->where('result_date', $date);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	function cancleday_count_for_admin($date,$my_downs='')
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '1');
		$this->db->where('result_date', $date);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 
	}

	public function countAllData($table,$where,$my_downs='') 
	{		
		$this->db->from($table);
		$this->db->where($where);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);
		}
		return $this->db->count_all_results();		
	}
	public function cancle_count_data_for_admin($my_downs='') 
	{	
		$result_date = date('Y-m-d');
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '1');
		$this->db->where('result_date', $result_date);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 	
	}

	public function sale_count_data_for_admin($my_downs='') 
	{
		$result_date = date('Y-m-d');
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('result_date', $result_date);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 	
	}
	
	public function bonus_count_data_for_admin($my_downs='') 
	{
		$result_date = date('Y-m-d');
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transactions_date', $result_date);
		$this->db->where('transaction_nature', '5');
		$this->db->where('transaction_type', '1');
		if($my_downs!=''){
		$this->db->where_in('from_user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result(); 	
	}

	public function winning_count_data_for_admin($my_downs='') 
	{
		$result_date = date('Y-m-d');		
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`*90) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('is_winning', '1');
		$this->db->where('is_claim', '1');
		$this->db->where('result_date', $result_date);
		if($my_downs!=''){
		$this->db->where_in('user_master_id',$my_downs);	
		}
		$query = $this->db->get();
		return $query->result();
	}

	public function countAllDataDistributer($table,$where,$my_downs) 
	{		
		$this->db->from($table);
		$this->db->where($where);
		$this->db->where_in('user_master_id',$my_downs);
		return $this->db->count_all_results();		
	}
	
	function getallcurrentbalance($my_downs)
    {
		$this->db->select('*');
		$this->db->from('points_transactions');
		//		
		$this->db->distinct("from_user_master_id");
		$this->db->group_by('from_user_master_id');
		$this->db->order_by('points_transactions_id','desc');
		if($my_downs!='')
		{
			$this->db->where_in('user_master_id',$my_downs);
		}
		
		$query = $this->db->get();
		return $query->result(); 
	}

	function transaction_detail_total_sale($my_downs)
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where_in('user_master_id',$my_downs);
		$query = $this->db->get();
		return $query->result(); 
	}

	public function transaction_detail_total_winning($my_downs){
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`*90) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('is_winning', '1');
		$this->db->where('is_claim', '1');
		$this->db->where_in('user_master_id',$my_downs);
		$query = $this->db->get();
		return $query->result(); 
	}

	public function transaction_detail_total_cancle($my_downs){
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '1');
		$this->db->where_in('user_master_id',$my_downs);
		$query = $this->db->get();
		return $query->result(); 
	}

	public function total_comission_data($my_downs=''){
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');
		if($my_downs!="")
		{
			$this->db->where_in('from_user_master_id',$my_downs);
		}
		$query = $this->db->get();
		return $query->result();
	}
	
	public function total_comission_data_new($my_downs=''){
		$transactions_date = date('Y-m-d');
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');		
		$this->db->where('transactions_date',$transactions_date);
		if($my_downs!="")
		{
			$this->db->where_in('from_user_master_id',$my_downs);
		}
		$query = $this->db->get();		
		$r1= $query->result();
		if($r1[0]->total!="")
		{
			$total_commision = $r1[0]->total;
		}else{
			$total_commision =0;
		}
		
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','0');
		$this->db->where('transaction_nature','6');
		$this->db->where('transactions_date',$transactions_date);
		if($my_downs!="")
		{
			$this->db->where_in('from_user_master_id',$my_downs);
		}
		$query = $this->db->get();		
		$r1= $query->result();
		if($r1[0]->total!="")
		{
			$total_commision1 = $r1[0]->total;
		}else{
			$total_commision1 =0;
		}
		
		
		return $total_commision - $total_commision1;
		
	}

	public function total_bonus_data($my_downs){
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','5');
		$this->db->where_in('from_user_master_id', $my_downs);
		$query = $this->db->get();
		return $query->result(); 
	}

	public function total_report_winning($draw_transaction_master_id){
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`*90) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('is_winning', '1');
		$this->db->where('draw_transaction_master_id', $draw_transaction_master_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function total_report_sales($draw_transaction_master_id){
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('draw_transaction_master_id', $draw_transaction_master_id);
		$query = $this->db->get();
		return $query->result();
	}

	function total_winning_week($user_master_id,$fromdate,$todate)
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`*90) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('is_winning', '1');
		$this->db->where('result_date >=', $fromdate);
		$this->db->where('result_date <=', $todate);
		$this->db->where('user_master_id',$user_master_id);
		$query = $this->db->get();
		return $query->result(); 
	}

	function total_sale_week($user_master_id,$fromdate,$todate)
    {
		$this->db->select('sum(`bid_units`*`bid_points`*`bid_points_multiplier`) as total', FAlSE);
		$this->db->from('draw_transaction_details');	
		$this->db->where('is_deleted', '0');
		$this->db->where('result_date >=', $fromdate);
		$this->db->where('result_date <=', $todate);
		$this->db->where('user_master_id',$user_master_id);
		$query = $this->db->get();
		return $query->result(); 
	}

	function total_commision_week($user_master_id,$fromdate,$todate){
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');
		$this->db->where('from_user_master_id', $user_master_id);
		$this->db->where('transactions_date >=', $fromdate);
		$this->db->where('transactions_date <=', $todate);
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function total_commision_week_new($user_master_id,$fromdate,$todate){
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','1');
		$this->db->where('transaction_nature','1');
		$this->db->where('from_user_master_id', $user_master_id);
		$this->db->where('transactions_date >=', $fromdate);
		$this->db->where('transactions_date <=', $todate);
		$query = $this->db->get();
		$r = $query->result();
		if($r[0]->total=='')
		{
			$commision=0;
		}
		else
		{
			$commision=$r[0]->total;
		}
		
		$this->db->select('sum(`points_transferred`) as total', FAlSE);
		$this->db->from('points_transactions');
		$this->db->where('transaction_type','0');
		$this->db->where('transaction_nature','6');
		$this->db->where('from_user_master_id', $user_master_id);
		$this->db->where('transactions_date >=', $fromdate);
		$this->db->where('transactions_date <=', $todate);
		$query = $this->db->get();
		$r = $query->result();
		if($r[0]->total=='')
		{
			$commision1=0;
		}
		else
		{
			$commision1=$r[0]->total;
		}
		
		return $commision - $commision1;
	}
	
	function get_tempd_ata($draw_master_id)
    {
		$this->db->select('*'); 
		$this->db->from('temp_result_master');
		$this->db->where('draw_master_id', $draw_master_id);
		$this->db->order_by('draw_master_id desc , series_master_id asc , bajar_master_id asc');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	public function getheighest_no() {
		$this->db->select_max('tr_no');
		$query = $this->db->get('points_transactions');
		$result = $query->row();
		$data = $result->tr_no + 1;
		return $data;
	}
	
	public function record_count_off($table) 
	{	
		$this->db->from($table);	
		return $this->db->count_all_results();		
	}
	function getAllwhere_pagination_off($table,$limit,$start)
    {
	    $this->db->select('*'); 
		$this->db->from($table);
		$this->db->order_by('off_date','desc');
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
	
	public function checkdraw_play($user_master_id,$draw_array,$result_date){
		$this->db->select('*');
		$this->db->from('draw_transaction_master');	
		$this->db->where('result_date', $result_date);
		$this->db->where('user_master_id', $user_master_id);
		$this->db->where_in('draw_master_id',$draw_array);
		$query = $this->db->get();
		return $query->result(); 
	}
	
	public function check_bid_akada_number($result_date,$draw_master_id,$bid_akada_number,$series_master_id,$bajar_master_id){
		
		$qyeury = "SELECT COUNT(bid_akada_number) as bid_akada_number
				FROM       result_master
				WHERE      result_date = '".$result_date."'
				AND        draw_master_id = '".$draw_master_id."' 
				AND        bid_akada_number = '".$bid_akada_number."'
				AND       (series_master_id = '".$series_master_id."' OR bajar_master_id = '".$bajar_master_id."')
				";
		 $q = $this->db->query($qyeury);
		 $result = $q->result_array();
		 return $result;
	}

		
	
}