<?php 
class MY_Form_validation extends CI_Form_validation {  
		
	
	function unique($value, $params) {  
    
            $CI =& get_instance();  
            $CI->load->database();  
      
            $CI->form_validation->set_message('unique',  
                'The %s is already being used.');
      
            list($table, $field) = explode(".", $params, 2);
      
            $query = $CI->db->select($field)->from($table)  
                ->where($field, $value)->limit(1)->get();  
      
            if ($query->row()) {  
                return false;  
            } else {  
                return true;  
            }  
      
        }
		

public function alpha_space($str){
		$CI =& get_instance();	
		if (! preg_match("/^([a-zA-Z ])+$/i", $str)){ 
			$CI->form_validation->set_message('alpha_space', 'The %s field may only contain alphabatic characters with space.'); 
			return FALSE; 
		}else{ 
			return TRUE;
		}
	}
	
	public function number_dot($str){
		$CI =& get_instance();	
		if (! preg_match("/^([0-9'])+$/i", $str)){ 
			$CI->form_validation->set_message('number_dot', 'The %s field may only contain characters numeric.'); 
			return FALSE; 
		}else{ 
			return TRUE;
		}
	}
	
 }  
?>