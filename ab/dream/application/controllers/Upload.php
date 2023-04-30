<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Upload extends CI_Controller {
	
    protected $path_img_upload_folder;
    protected $path_img_thumb_upload_folder;
    protected $path_url_img_upload_folder;
    protected $path_url_img_thumb_upload_folder;

    protected $delete_img_url;

  function __construct() {
        parent::__construct();
        $this->load->model('registration/common_model');
        $this->load->helper(array('form', 'url'));

//Set relative Path with CI Constant
        $this->setPath_img_upload_folder("upload_profile/users/");
        $this->setPath_img_thumb_upload_folder("upload_profile/users/thumb/");

        
//Delete img url
        $this->setDelete_img_url(base_url() . 'upload/deleteImage/');
 

//Set url img with Base_url()
        $this->setPath_url_img_upload_folder(base_url() . "upload_profile/users/");
        $this->setPath_url_img_thumb_upload_folder(base_url() . "upload_profile/users/thumb/");
  }


    public function index($id = '') 
	{
		if($this->session->userdata('userid') == '')
		{
			redirect('login');
		}
		else if($id == '')
		{
			redirect('profile');
		}
		else
		{
                    $da=$this->common_model->getsingle('registration',array('id'=>$id));
                    $data['current_profile'] = $this->common_model->getsingle('current_profile',array('user_id' => $this->session->userdata('userid')));	
                    $data['profile_status']=$da->profile_status; 
                    $data['candidate_name']=$da->candidate_name;
                    $data['registration_no']=$da->registration_no;
                    
                $data['photos']=$this->common_model->getAllwhere_decrement('photos',array('registration_id'=>$id));
                
                
      		$data['id'] = $id;
			$data['main_content'] = 'upload_view';		
	 		$this->load->view('includes/template-upload',$data);
			//$this->load->view('upload_v/upload_view');
		}
   }

  

    public function upload_img($id = "") 
	{
	
		if($this->session->userdata('userid') == '')
		{
			redirect('login');
		}
		else if($id == '')
		{
			redirect('profile');
		}
		else
		{
      		$name = $_FILES['userfile']['name'];
        	$name = strtr($name, 'À�?ÂÃÄÅÇÈÉÊËÌ�?Î�?ÒÓÔÕÖÙÚÛÜ�?àáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');

			// remplacer les caracteres autres que lettres, chiffres et point par _

			$name = preg_replace('/([^.a-z0-9]+)/i', '_', $name);
			
			//Your upload directory, see CI user guide
			$config['upload_path'] = $this->getPath_img_upload_folder();
			
			$config['allowed_types'] = 'gif|jpg|png|JPG|GIF|PNG';
			$config['max_size'] = '50000';
			$config['file_name'] = $name;
			
			//Load the upload library
			$this->load->library('upload', $config);
			//$this->load->view('upload_v/upload_view');
			
			if ($this->do_upload()) 
			{
            
				$data = $this->upload->data();
				/*
				Array
(
    [file_name] => Desert_Landscape.jpg
    [file_type] => image/jpeg
    [file_path] => C:/xampp/htdocs/matrimonial/images/users/
    [full_path] => C:/xampp/htdocs/matrimonial/images/users/Desert_Landscape.jpg
    [raw_name] => Desert_Landscape
    [orig_name] => Desert_Landscape.jpg
    [client_name] => Desert Landscape.jpg
    [file_ext] => .jpg
    [file_size] => 223.5
    [is_image] => 1
    [image_width] => 1024
    [image_height] => 768
    [image_type] => jpeg
    [image_size_str] => width="1024" height="768"
)
				*/
				//If you want to resize 
				$config['new_image'] = $this->getPath_img_thumb_upload_folder();
				$config['image_library'] = 'gd2';
				$config['source_image'] = $this->getPath_img_upload_folder() . $data['file_name'];
				$config['create_thumb'] = FALSE;
				$config['maintain_ratio'] = TRUE;
				$config['width'] = 193;
				$config['height'] = 94;
	
				$this->load->library('image_lib', $config);
	
				$this->image_lib->resize();

          		
            	$this->load->model('registration/photos_model');
				$photos_data = array(
										'registration_id' => $id,
										'file_path'	=> $data['file_path'],
										'full_path'	=> $data['full_path'],
										'file_name'	=> $data['file_name'],
										'file_size' => $data['file_size']
										);
				$photo_id = $this->photos_model->insertData('photos',$photos_data);
				
				//Get info 
				$info = new stdClass();
				
				$info->name = $data['file_name'];
				$info->size = $data['file_size'];
				$info->type = $data['file_type'];
				$info->image_id = $photo_id;
				$info->url = $this->getPath_url_img_upload_folder() . $data['file_name'];
				$info->thumbnail_url = $this->getPath_url_img_thumb_upload_folder() . $data['file_name']; //I set this to original file since I did not create thumbs.  change to thumbnail directory if you do = $upload_path_url .'/thumbs' .$name
				$info->delete_url = $this->getDelete_img_url() . $data['file_name'].'/'.$photo_id;
                                $info->id = $photo_id;
				$info->delete_type = 'DELETE';
			

			   //Return JSON data
			   if (IS_AJAX) {   //this is why we put this in the constants to pass only json data
                                        
					echo json_encode(array($info));
					//this has to be the only the only data returned or you will get an error.
					//if you don't give this a json array it will give you a Empty file upload result error
					//it you set this without the if(IS_AJAX)...else... you get ERROR:TRUE (my experience anyway)
				} else {   // so that this will still work if javascript is not enabled
					$file_data['upload_data'] = $this->upload->data();
					echo json_encode(array($info));
				}
        
			} 
			else 
			{
           // the display_errors() function wraps error messages in <p> by default and these html chars don't parse in
           // default view on the forum so either set them to blank, or decide how you want them to display.  null is passed.
            $error = array('error' => $this->upload->display_errors('',''));
            
                            if(!empty($name))  // this condition use search profile or disable profile click (upload_image) and load page error not show
                            {
                                echo json_encode(array($error));
                            }
			}
		}
        

      


    }
 // }


//Function for the upload : return true/false
  public function do_upload() {

        if (!$this->upload->do_upload()) {

            return false;
        } else {
            //$data = array('upload_data' => $this->upload->data());

            return true;
        }
     }

	public function deleteImage($file = "",$id = "") 
	{

        if($this->session->userdata('userid') == '')
		{
			redirect('login');
		}
		else if($id == '' && $file != '')
		{
			redirect('profile');
		}
		else
		{
			$this->load->model('registration/photos_model');
			$image_detail = $this->photos_model->deleteData('photos',array('id' => $id));
			
			$success = unlink($this->getPath_img_upload_folder() . $file);
			$success_th = unlink($this->getPath_img_thumb_upload_folder() . $file);
	
			//info to see if it is doing what it is supposed to 
			$info = new stdClass();
			$info->sucess = $success;
			$info->path = $this->getPath_url_img_upload_folder() . $file;
			$info->file = is_file($this->getPath_img_upload_folder() . $file);
			if (IS_AJAX) {//I don't think it matters if this is set but good for error checking in the console/firebug
				echo json_encode(array($info));
			} else {     //here you will need to decide what you want to show for a successful delete
				var_dump($file);
			}
		}
		//Get the name in the url
       
    }

  /*  public function get_files() {

        $this->get_scan_files();
    }

    public function get_scan_files() {

        $file_name = isset($_REQUEST['file']) ?
                basename(stripslashes($_REQUEST['file'])) : null;
        if ($file_name) {
            $info = $this->get_file_object($file_name);
        } else {
            $info = $this->get_file_objects();
        }
        header('Content-type: application/json');
        echo json_encode($info);
    }

    protected function get_file_object($file_name) {
        $file_path = $this->getPath_img_upload_folder() . $file_name;
        if (is_file($file_path) && $file_name[0] !== '.') {

            $file = new stdClass();
            $file->name = $file_name;
            $file->size = filesize($file_path);
            $file->url = $this->getPath_url_img_upload_folder() . rawurlencode($file->name);
            $file->thumbnail_url = $this->getPath_url_img_thumb_upload_folder() . rawurlencode($file->name);
            //File name in the url to delete 
            $file->delete_url = $this->getDelete_img_url() . rawurlencode($file->name);
            $file->delete_type = 'DELETE';
            
            return $file;
        }
        return null;
    }

    protected function get_file_objects() {
        return array_values(array_filter(array_map(
             array($this, 'get_file_object'), scandir($this->getPath_img_upload_folder())
                   )));
    }*/


    public function getPath_img_upload_folder() {
        return $this->path_img_upload_folder;
    }

    public function setPath_img_upload_folder($path_img_upload_folder) {
        $this->path_img_upload_folder = $path_img_upload_folder;
    }

    public function getPath_img_thumb_upload_folder() {
        return $this->path_img_thumb_upload_folder;
    }

    public function setPath_img_thumb_upload_folder($path_img_thumb_upload_folder) {
        $this->path_img_thumb_upload_folder = $path_img_thumb_upload_folder;
    }

    public function getPath_url_img_upload_folder() {
        return $this->path_url_img_upload_folder;
    }

    public function setPath_url_img_upload_folder($path_url_img_upload_folder) {
        $this->path_url_img_upload_folder = $path_url_img_upload_folder;
    }

    public function getPath_url_img_thumb_upload_folder() {
        return $this->path_url_img_thumb_upload_folder;
    }

    public function setPath_url_img_thumb_upload_folder($path_url_img_thumb_upload_folder) {
        $this->path_url_img_thumb_upload_folder = $path_url_img_thumb_upload_folder;
    }

    public function getDelete_img_url() {
        return $this->delete_img_url;
    }

    public function setDelete_img_url($delete_img_url) {
        $this->delete_img_url = $delete_img_url;
    }

    public function deleteimg() 
	{
            $id=$this->input->post('img_id');
            $da=$this->common_model->getsingle('photos',array('id'=>$id));
            $file= $da->file_name;
			$this->load->model('registration/photos_model');
			$image_detail = $this->photos_model->deleteData('photos',array('id' => $id));
			
			$success = unlink($this->getPath_img_upload_folder() . $file);
			$success_th = unlink($this->getPath_img_thumb_upload_folder() . $file);
	echo $success_th;
		
       
       }



}

?>
