<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Results extends CI_Controller {

   	public function __construct()
	{
		parent::__construct();
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		date_default_timezone_set('Asia/Calcutta'); 
	}
		
	public function index()
	{
		
		$data['msg'] = $this->session->flashdata('msg');
		
		$fdate = $this->input->post('date');
		
		if($fdate!="")
		{
			$this->session->set_userdata('fdate',$fdate);
		}else{
			if($this->session->userdata('fdate')=="")
			{
				if( strtotime(date('H:i:s')) >= strtotime(date('22:00:01')) )
				{
					$this->session->set_userdata('fdate',date('Y-m-d'));
				}
				else if( strtotime(date('H:i:s')) <= strtotime(date('22:00:01')) && strtotime(date('H:i:s')) >= strtotime(date('10:00:00')) )
				{
					$this->session->set_userdata('fdate',date('Y-m-d'));
				}else{
					$this->session->set_userdata('fdate',date('Y-m-d',strtotime(' -1 days')));
				}
			}
		}
		
		$fdate = $this->session->userdata('fdate');
		$date = date('Y-m-d',strtotime($fdate));
		$data['date'] = date('d-M-Y',strtotime($fdate));
		
		$config = array();
		$config["base_url"] = base_url() ."/results/index";
		$total_row = $this->common_model->record_count_result($date);				
		$config["total_rows"] = $total_row;
		$config["per_page"] = 5000;		
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
			
		$data['results'] = $this->common_model->getAllwhere_pagination_result($config["per_page"],$page,$date);
		//echo "<pre>"; print_r($data);die;
		$data['main_content'] = 'index';
		$this->load->view('includes/template2',$data);
		            
	}
	
}	

?>	