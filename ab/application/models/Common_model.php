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
	
	function getAllUsers_Ofmessages($table, $user_id,$column,$type,$group)
    {
    
        $this->db->select('*'); 
        $this->db->from($table);
        $squery = " ( to_id='".$user_id."'OR from_id='".$user_id."' ) and Not FIND_IN_SET('".$user_id."', deleted_by) "; 
        $this->db->where($squery, NULL, FALSE);
        
        $this->db->order_by($column,$type); 
        $this->db->group_by('to_id,from_id');
        $query = $this->db->get();
        return $query->result(); 
    }
	
	function last_message_bw_two_users($table, $from_id,$to_id,$column,$type)
    {
    
        $this->db->select('*'); 
        $this->db->from($table);
        $squery = " ( (from_id='".$from_id."'AND to_id='".$to_id."') OR (from_id='".$to_id."'AND to_id='".$from_id."') ) and  Not FIND_IN_SET('".$to_id."', deleted_by) "; 
        $this->db->where($squery, NULL, FALSE);
        
        $this->db->order_by($column,$type); 
        $query = $this->db->get();
        return $query->row(); 
    }
	public function getTwoUsersMessages($user_id,$to_id,$column,$type){
        $this->db->select('*');
        $this->db->from('messages');
        $squery = " ( (from_id='".$user_id."'AND to_id='".$to_id."') OR (from_id='".$to_id."'AND to_id='".$user_id."') ) and Not FIND_IN_SET('".$user_id."', deleted_by) "; 
        $this->db->where($squery, NULL, FALSE);
        
        $this->db->order_by($column,$type); 
        //$this->db->group_by('to_id,from_id');
        $query = $this->db->get();
        return $query->result(); 
    }
	
	function getmembers($carpool_id)
    {
        $this->db->select('students.*'); 
		$this->db->from('carpool_join');
		$this->db->where(array('carpool_join.carpool_id'=>$carpool_id));	
		$this->db->join('students', 'carpool_join.student_id = students.student_id');		
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function getcarpolll($seacrh='')
    {
        $this->db->select('carpool.*'); 
		$this->db->from('carpool');	
		if($seacrh!='')
		{
			$this->db->like('carpool.location',$seacrh);
			$this->db->or_like('students.first_name',$seacrh);
			$this->db->or_like('students.last_name',$seacrh);
		}
		$this->db->join('students', 'carpool.student_id = students.student_id');		
		$query = $this->db->get();
		return $query->result(); 
    }
	
	function getstudentsmyfrnd($student_id)
	{
		$q = $this->db->query("SELECT * FROM students where ( student_id IN ( SELECT from_id from messages where to_id='".$student_id."') OR student_id IN ( SELECT to_id from messages where from_id='".$student_id."') ) Limit 5 ");
		$result = $q->result();
		return $result; 
	}
	function getstudentsmyfrnd_new($student_id,$student_ids)
	{
		$q = $this->db->query("SELECT * FROM students where student_id IN (". implode(',', $student_ids) .") Limit 5 ");
		$result = $q->result();
		return $result; 
	}
	
}