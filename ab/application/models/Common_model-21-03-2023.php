<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Common_model extends CI_Model
{
		
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
	
	function getAllwhere_ob_limit($table,$where,$colomn,$type,$limit)
    {
        $this->db->select('*');
		//$this->db->order_by('rand()');
		$this->db->order_by($colomn,$type);
		$this->db->limit($limit);
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
	
	function mycart_data($user_id)
    {
        $this->db->select('cart.p_id,cart.id as cart_id,cart.qty,cart.discount_price,cart.price,cart.user_id,products.name,products.p_type,products.p_type_qty,products.delivery_charge,products.id,products.description'); 
		$this->db->from('cart');
		$this->db->where('cart.user_id',$user_id);
		$this->db->join('products','products.id=cart.p_id');
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function product_nearby_filter($cat_id='',$search_key='',$my_lat,$my_lng,$from_dist,$to_dist)
    {
        $this->db->select("products.*,
					(
					   6371 *
					   acos(cos(radians(".$my_lat.")) * 
					   cos(radians(products.p_lat)) * 
					   cos(radians(products.p_long) - 
					   radians(".$my_lng.")) + 
					   sin(radians(".$my_lat.")) * 
					   sin(radians(products.p_lat )))
					) AS distance"); 
		$this->db->from('products');
		$this->db->where('status',1);
		if($cat_id!=''){
			$this->db->where('products.cat_id',$cat_id);
		}
		if($search_key!='')
		{
		    $this->db->like('products.name',$search_key);
		}
		if($cat_id=='' && $search_key=='')
		{
			//$this->db->where('products.status',1);
		}
		$this->db->group_by('cat_id');
		$this->db->order_by('distance','asc');
		$this->db->having("distance >= ".$from_dist." and distance <= ".$to_dist);
		
		$this->db->limit(100);
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function home_product_nearby_filter_limit($colomn,$type,$limit,$my_lat,$my_lng,$from_dist,$to_dist)
    {
		 $this->db->select("products.*,
					(
					   6371 *
					   acos(cos(radians(".$my_lat.")) * 
					   cos(radians(products.p_lat)) * 
					   cos(radians(products.p_long) - 
					   radians(".$my_lng.")) + 
					   sin(radians(".$my_lat.")) * 
					   sin(radians(products.p_lat )))
					) AS distance"); 
		$this->db->from('products');
		$this->db->where('status',1);
		$this->db->group_by('cat_id');
		$this->db->order_by($colomn,$type);
		$this->db->having("distance >= ".$from_dist." and distance <= ".$to_dist);
		
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
		
    }
	
	public function product_filter($cat_id='',$search_key='',$limit, $start) 
	{		
		$this->db->select('*'); 
		$this->db->from('products');
		
		if($cat_id!=''){
			$this->db->where('products.cat_id',$cat_id);
		}
		if($search_key!='')
		{
		    $this->db->like('products.name',$search_key);
		}
		if($cat_id=='' && $search_key=='')
		{
			$this->db->where('products.status',1);
		}
		$this->db->limit($limit, $start);
		$query = $this->db->get();
		return $query->result();  	
	}
	
	public function category_filter($table, $search_key='') 
	{		
		$this->db->select('*'); 
		$this->db->from('categories');
		
		if($search_key!='')
		{
		    $this->db->like('categories.category_name',$search_key);
		}
		if($search_key=='')
		{
			$this->db->where('categories.status',1);
		}
		
		$query = $this->db->get();
		return $query->result(); 	
	}
	
   function search_intro($key){
     $q = $this->db->query("SELECT * FROM users where ( login_id LIKE '%".$key."%' or full_name LIKE '%".$key."%' ) AND status = '0' ");
     $result = $q->result_array();
     return $result;    
    }
	
	 function getjobs($status='',$search='',$start_date='',$end_date=''){
		$query = 'SELECT jobs.* , company.company FROM jobs,company where jobs.company_id=company.company_id';
		if($status!="")
		{
			$query .= " AND jobs.status='".$status."'";
		}
		if($search!="")
		{
			$query .= " AND ( company.company LIKE '%".$search."%' OR jobs.town LIKE '%".$search."%' OR jobs.county LIKE '%".$search."%' OR jobs.postcode LIKE '%".$search."%' ) ";
		}
		
		if($start_date!="")
		{
			$query .= " AND jobs.start_date >='".$start_date."' ";
		}
		if($end_date!="")
		{
			$query .= " AND jobs.end_date <='".$end_date."' AND jobs.end_date !='0000-00-00' ";
		}
	 
	 $q = $this->db->query($query);
     $result = $q->result();
     return $result;    
    }
	
	
	function sum_records($table){
     $q = $this->db->query("SELECT user_id,trump,SUM(point) as point FROM ".$table."  GROUP BY user_id order by point desc limit 50");
     $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }   
    }
	
	function sum_records_daily($table){
     $q = $this->db->query("SELECT user_id,trump,SUM(point) as point FROM ".$table." where match_date=current_date GROUP BY user_id order by point desc limit 50");
     $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }   
    }
	
	function sum_records_weekly($table){  
	 $current_date = date('Y-m-d');
     $q = $this->db->query("SELECT user_id,trump,SUM(point) as point FROM ".$table." where match_date>current_date - interval 7 day GROUP BY user_id order by point desc limit 50");
     $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
            return $data;
        }   
    }
	
	function getAllwhere_ob($table,$where,$colomn,$type)
    {
        $this->db->select('*');
		$this->db->order_by($colomn,$type);
        $q = $this->db->get_where($table, $where);
        $num_rows = $q->num_rows();
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
	
	public function record_count($table,$where='') 
	{		
		$this->db->from($table);
		if($where!=''){
		$this->db->where($where);
		}
		return $this->db->count_all_results();		
	}
	
	function getAllwhere_pagination($table,$limit,$start,$where='',$column='',$type='')
    {
        $this->db->select('*'); 
		$this->db->from($table);
		if($where!=''){
			$this->db->where($where);
		}		
		$this->db->limit($limit, $start);
		if($column!="")
		{
		$this->db->order_by($column,$type);	
		}
		$query = $this->db->get();
		return $query->result(); 
    }
	function sum_of_cloumn($table,$where='',$column){
		$this->db->select_sum('rating');
		$this->db->from($table);
		if($where!=''){
			$this->db->where($where);
		}
		$query = $this->db->get();
		return $query->result(); 
	}
	
	function my_rank($user_id){
     $q = $this->db->query("select user_id, point, rank
				from (
					  select user_id, point, @rank := @rank + 1 as rank
					  from (
							select user_id, sum(point) point
							from   user_prediction
							group by user_id
							order by sum(point) desc
						   ) t1, (select @rank := 0) t2
					 ) t3
				where user_id = '".$user_id."'");
     $result = $q->result_array();
     return $result;    
    }
	
	function mycart_nearby_data_new($user_id,$my_lat,$my_lng,$from_dist,$to_dist)
    {
        $this->db->select("cart.p_id,cart.id as cart_id,cart.qty,cart.discount_price,cart.price,cart.user_id,products.name,products.id,products.delivery_charge,products.description,
					(
					   6371 *
					   acos(cos(radians(".$my_lat.")) * 
					   cos(radians(products.p_lat)) * 
					   cos(radians(products.p_long) - 
					   radians(".$my_lng.")) + 
					   sin(radians(".$my_lat.")) * 
					   sin(radians(products.p_lat )))
					) AS distance"); 
		$this->db->from('cart');
		$this->db->where('cart.user_id',$user_id);
		$this->db->having("distance >= ".$from_dist." and distance <= ".$to_dist);
		
		$this->db->join('products','products.id=cart.p_id');
		
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function search_order_bydate($from_date='',$to_date='',$limit='',$start='',$admin_id='')
	{
		$today = date('Y-m-d');
		//$where = "orders.created_date >= '".$today."'";
		$where = "orders.created_date != ''";
		$this->db->select('*'); 
		$this->db->from('orders');
		$this->db->join('products','products.id=orders.p_id');
		if($from_date!="" && $to_date!="")
		{
			$this->db->where('orders.created_date>=',$from_date);
			$this->db->where('orders.created_date<=',$to_date);
		}
		if($admin_id!="")
		{
			$this->db->where('products.admin_id',$admin_id);
		}
		$this->db->where($where);
		
		$this->db->group_by('orders.order_no');
		$this->db->order_by('orders.id','asc');
		$this->db->limit($limit, $start);
		
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result();
		  
    }

	
	public function search_order_bydate_record_count($from_date='',$to_date='',$admin_id='') 
	{		
		$today = date('Y-m-d');
		//$where = "created_date <= '".$today."'";
		$where = "orders.created_date != ''";
		$this->db->from('orders');
		$this->db->join('products','products.id=orders.p_id');
		if($from_date!="" && $to_date!="")
		{
			$this->db->where('orders.created_date>=',$from_date);
			$this->db->where('orders.created_date<=',$to_date);
		}
		if($admin_id!="")
		{
			$this->db->where('products.admin_id',$admin_id);
		}
	
		$this->db->where($where);
		
		$this->db->group_by('orders.order_no');
		return $this->db->count_all_results();		
	}
	
	function getAll_order($table,$where,$admin_id='')
	{
		$this->db->select('orders.*,products.*,orders.qty as order_qty'); 
		$this->db->from($table);
		$this->db->join('products','products.id=orders.p_id');
		
		if($admin_id!="")
		{
			$this->db->where('products.admin_id',$admin_id);
		}
		$this->db->where($where);
		
		$query = $this->db->get();
		//echo $this->db->last_query();die;
		return $query->result();
		  
    }
	
	
}