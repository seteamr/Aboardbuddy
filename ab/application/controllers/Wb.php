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
		date_default_timezone_set('Asia/Calcutta'); 
		$this->allowHeaders();
	}
	
	  // Cross site scripting auth
	  function allowHeaders() {
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods:PUT,GET,POST,UPDATE,DELETE");		
		header("Access-Control-Expose-Headers: Origin, X-Requested-With, Content-Type, Accept");
	  }
	
	public function test_get()
	{
		$this->response(['status' => 400, 'message' => 'Gender is required.']);
	}
	public function signup_post()
	{
		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$gender = $this->input->post('gender');
		$age = $this->input->post('age');
		$dob = $this->input->post('dob');
		$password = $this->input->post('password');
		$cpassword = $this->input->post('cpassword');
		$country_id = $this->input->post('country_id');
		$city_id = $this->input->post('city_id');
		$university_id = $this->input->post('university_id');

		$check_username = $this->common_model->getsingle('students', array('username' => $username));
		$check_email = $this->common_model->getsingle('students', array('email' => $email));
		$check_phone = $this->common_model->getsingle('students', array('phone' => $phone));
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 

		if($username == '') {
			$this->response(['status' => 400, 'message' => 'Username is required.']);
		}else if($username != '' && $check_username != '') {
			$this->response(['status' => 400, 'message' => 'Username already taken.']);
		} else if($email == '') {
			$this->response(['status' => 400, 'message' => 'Email ID is required.']);
		} else if($email != '' && $check_email != '') {
			$this->response(['status' => 400, 'message' => 'Email already exists.']);
		} else if(!preg_match($regex, $email)) {
			$this->response(['status' => 400, 'message' => 'Email is invalid.']);
		} else if($phone == '') {
			$this->response(['status' => 400, 'message' => 'Phone Number is required']);
		} else if($phone != '' && $check_phone != '') {
			$this->response(['status' => 400, 'message' => 'Phone Number already exists.']);
		} else if($gender == '') {
			$this->response(['status' => 400, 'message' => 'Gender is required.']);
		} else if($age == '') {
			$this->response(['status' => 400, 'message' => 'Age is required.']);
		} else if($dob == '') {
			$this->response(['status' => 400, 'message' => 'Date of Birth is required.']);
		} else if($password == '') {
			$this->response(['status' => 400, 'message' => 'Password is required']);
		} else if(strlen($password) < 6) {
			$this->response(['status' => 400, 'message' => 'Password need to be more than or equal to 6 characters']);
		} else if($cpassword == '') {
			$this->response(['status' => 400, 'message' => 'Corfirm Password is required']);
		} else if($password != $cpassword) {
			$this->response(['status' => 400, 'message' => 'Password and Confirm Password need to be the same']);
		}
		else if($country_id == '') {
			$this->response(['status' => 400, 'message' => 'country_id is required']);
		}
		else if($city_id == '') {
			$this->response(['status' => 400, 'message' => 'city_id is required']);
		}
		else if($university_id == '') {
			$this->response(['status' => 400, 'message' => 'university_id is required']);
		}		
		else  
		{
			$student_data = [
				'username' 	=> $username,
				'email' 	=> $email,
				'phone' 	=> $phone,
				'gender' 	=> $gender,
				'age' 		=> $age,
				'dob' 		=> $dob,
				'country_id'=> $country_id,
				'city_id'	=> $city_id,
				'university_id'=> $university_id,
				'password' 	=> md5($password),
				'created_at'=> date('Y-m-d')
			];

			$id = $this->common_model->insertData('students', $student_data);
			
			$student_data['student_id'] = $id;
			$this->response(['status' => 200, 'message' => 'Registered Successfully.', 'data' => $student_data]);
		}
	}
	
	public function login_post()
	{
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		if($username == '') {
			$this->response(['status' => 400, 'message' => 'Username is required']);
		} else if($password == '') {
			$this->response(['status' => 400, 'message' => 'Password is required']);
		} 
		else 
		{
			$check_student = $this->common_model->getsingle('students', array('username' => $username, 'password' => md5($password),'status'=>'1'));

			if($check_student)
			{
				
			$students = $this->common_model->getsingle('students',array('student_id'=> $check_student->student_id));
			
			$students->profile_pic = base_url().'uploads/student/'.$students->profile_pic;
			$students->token = bin2hex(random_bytes(16));
			
				$this->response(['status' => 200, 'message' => 'Login Successfull', 'data' => $students]);
			}
			else 
			{
				$this->response(['status' => 404, 'message' => 'Login Failed. Please Check the Login Credentials']);
			}
		}
	}

	public function support_post()
	{
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$country = $this->input->post('country');
		$description = $this->input->post('description');

		if($first_name == '') {
			$this->response(['status' => 400, 'message' => 'First Name is required']);
		} else if($last_name == '') {
			$this->response(['status' => 400, 'message' => 'Last Name is required']);
		} else if($country == '') {
			$this->response(['status' => 400, 'message' => 'Country is required']);
		} else if($description == '') {
			$this->response(['status' => 400, 'message' => 'Description is required']);
		}
		else 
		{
			$support = [
				'first_name' => $first_name,
				'last_name' => $last_name,
				'country' => $country,
				'description' => $description,
				'created_at' => date('Y-m-d')
			];

			$this->common_model->insertData('support', $support);
			$this->response(['status' => 200, 'message' => 'Data Submitted Successfully']);
		}
	}
	
	public function countries_get()
	{
		$countries = $this->common_model->getAllwhere('countries',array());
		$this->response(['status' => 200, 'data' => $countries]);
	}
	
	public function cities_post()
	{
		$country_id = $this->input->post('country_id');
		if($country_id == '') {
			$this->response(['status' => 400, 'message' => 'country_id is required']);
		}else{
			$cities = $this->common_model->getAllwhere('cities',array('country_id'=> $country_id));
			$this->response(['status' => 200, 'data' => $cities]);
		} 
	
	}
	
	public function universities_post()
	{
		$city_id = $this->input->post('city_id');
		if($city_id == '') {
			$this->response(['status' => 400, 'message' => 'city_id is required']);
		}else{
			$universities = $this->common_model->getAllwhere('universities',array('city_id'=> $city_id));
			$this->response(['status' => 200, 'data' => $universities]);
		} 
	
	}
	
	public function profile_post()
	{
		$student_id = $this->input->post('student_id');
		$my_id = $this->input->post('my_id');
		
		$chk1 = $this->common_model->getsingle('message_request',array('from_id'=> $my_id,'to_id'=> $student_id));
		$chk2 = $this->common_model->getsingle('message_request',array('from_id'=> $student_id,'to_id'=> $my_id));					
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}else{
			$students = $this->common_model->getsingle('students',array('student_id'=> $student_id));
			
			$cities = $this->common_model->getsingle('cities',array('city_id'=> $students->city_id));
			
			$students->profile_pic = base_url().'uploads/student/'.$students->profile_pic;
			
			$students->city = $cities->name;
			
			if($chk1->approved==1 || $chk2->approved==1)
			{
				$students->is_connect = '1';
				$students->is_status = 'Approved';
			}
			else if($chk1 && $chk1->approved==2)
			{
				$students->is_connect = '2';
				$students->is_status = 'Rejected';
			}
			else if($chk2 && $chk2->approved==2)
			{
				$students->is_connect = '2';
				$students->is_status = 'Rejected';
			}
			else if($chk1 && $chk1->approved==0)
			{ 
				$students->is_connect = '0';
				$students->is_status = 'Pending';
			}
			else if($chk2 && $chk2->approved==0)
			{ 
				$students->is_connect = '0';
				$students->is_status = 'Pending';
			}else{
				$students->is_connect = '3';
				$students->is_status = 'not';
			}
			
			$this->response(['status' => 200, 'data' => $students]);
		} 
	
	}
	
	
	public function profile_update_post()
	{
		$student_id = $this->input->post('student_id');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$gender = $this->input->post('gender');		
		$dob = $this->input->post('dob');
		
		$country_id = $this->input->post('country_id');
		$city_id = $this->input->post('city_id');
		$university_id = $this->input->post('university_id');
		
		$Linkedin 	= $this->input->post('Linkedin');
		$Facebook 	= $this->input->post('Facebook');
		$Twitter 	= $this->input->post('Twitter');
		$Instagram 	= $this->input->post('Instagram');
		$Education_Level = $this->input->post('Education_Level');
		$Food_Type 	= $this->input->post('Food_Type');
		$Room_Sharing 	= $this->input->post('Room_Sharing');
		$Budget 	= $this->input->post('Budget');
		$Hobbies 	= $this->input->post('Hobbies');
		$bio 		= $this->input->post('bio');
		
		$students = $this->common_model->getsingle('students', array('student_id'=> $student_id));
		
		$check_email = $this->common_model->getsingle('students', array('email' => $email,'student_id !='=> $student_id));
		$check_phone = $this->common_model->getsingle('students', array('phone' => $phone,'student_id !='=> $student_id));
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 

		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required.']);
		}
		else if($first_name == '') {
			$this->response(['status' => 400, 'message' => 'first_name is required.']);
		}
		else if($last_name == '') {
			$this->response(['status' => 400, 'message' => 'last_name is required.']);
		}
		else if($email == '') {
			$this->response(['status' => 400, 'message' => 'Email ID is required.']);
		} else if($email != '' && $check_email != '') {
			$this->response(['status' => 400, 'message' => 'Email already exists.']);
		} else if(!preg_match($regex, $email)) {
			$this->response(['status' => 400, 'message' => 'Email is invalid.']);
		} else if($phone == '') {
			$this->response(['status' => 400, 'message' => 'Phone Number is required']);
		} else if($phone != '' && $check_phone != '') {
			$this->response(['status' => 400, 'message' => 'Phone Number already exists.']);
		} else if($gender == '') {
			$this->response(['status' => 400, 'message' => 'Gender is required.']);
		} else if($dob == '') {
			$this->response(['status' => 400, 'message' => 'Date of Birth is required.']);
		}
		else if($country_id == '') {
			$this->response(['status' => 400, 'message' => 'country_id is required']);
		}
		else if($city_id == '') {
			$this->response(['status' => 400, 'message' => 'city_id is required']);
		}
		else if($university_id == '') {
			$this->response(['status' => 400, 'message' => 'university_id is required']);
		}		
		else  
		{
			$profile_pic = $students->profile_pic;
			 
			$config['upload_path'] = 'uploads/student/';
			$config['allowed_types'] = '*';
			$config['encrypt_name'] = false;
			
			$this->load->library('upload', $config);
			if(isset($_FILES) && $_FILES['profile_pic']['name']!="")
			{
				if ($this->upload->do_upload('profile_pic'))
				{
					$path = './uploads/profile/'.$profile_pic ;
					unlink($path);
					 if(file_exists($path)){
						unlink($path); 
					  }
					$attachment_data = array('upload_data' => $this->upload->data());
					$profile_pic = $attachment_data['upload_data']['file_name'];
				}				
			}
			
			$update_data = [
				'first_name' 	=> $first_name,
				'last_name' 	=> $last_name,
				'email' 		=> $email,
				'phone' 		=> $phone,
				'gender' 		=> $gender,
				'dob' 			=> $dob,
				'Linkedin' 		=> $Linkedin,
				'Facebook' 		=> $Facebook,
				'Twitter' 		=> $Twitter,
				'Instagram' 	=> $Instagram,
				'Education_Level' => $Education_Level,
				'Food_Type' 	=> $Food_Type,
				'Room_Sharing' 	=> $Room_Sharing,
				'Budget' 		=> $Budget,
				'Hobbies' 		=> $Hobbies,
				'country_id'	=> $country_id,
				'city_id'		=> $city_id,
				'university_id'	=> $university_id,
				'profile_pic'	=> $profile_pic,
				'bio'			=> $bio,
			];

			$this->common_model->updateData('students', $update_data,array('student_id'=>$student_id));
			
			$students = $this->common_model->getsingle('students',array('student_id'=> $student_id));			
			$students->profile_pic = base_url().'uploads/student/'.$students->profile_pic;
			
			$this->response(['status' => 200, 'message' => 'Update Successfully.', 'data' => $students]);
		}
	}
	
	public function find_buddies_post()
	{
		$student_id 	= $this->input->post('student_id');
		$country_id 	= $this->input->post('country_id');
		$city_id 		= $this->input->post('city_id');
		$university_id 	= $this->input->post('university_id');
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($country_id == '') {
			$this->response(['status' => 400, 'message' => 'country_id is required']);
		}
		else if($city_id == '') {
			$this->response(['status' => 400, 'message' => 'city_id is required']);
		}
		else if($university_id == '') {
			$this->response(['status' => 400, 'message' => 'university_id is required']);
		}else{
			
			$students = $this->common_model->getAllwhere('students',array('status'=>'1','role'=>'student','student_id !='=> $student_id,'country_id'=> $country_id,'city_id'=> $city_id,'university_id'=> $university_id));
			
			$final_data = array();
			if($students)
			{
				foreach($students as $std)
				{
					$std->profile_pic = base_url().'uploads/student/'.$std->profile_pic;
					$final_data[] = $std;
				}
			}
			
			
			$this->response(['status' => 200, 'data' => $final_data]);
		} 
	
	}
	
	public function post_trip_post()
	{
		$student_id = $this->input->post('student_id');
		$trip_from = $this->input->post('trip_from');
		$trip_to = $this->input->post('trip_to');
		$trip_date = $this->input->post('trip_date');
		$trip_time = $this->input->post('trip_time');
		$no_of_passanger = $this->input->post('no_of_passanger');
		$smoker = $this->input->post('smoker');
		$licend = $this->input->post('licend');
		$description = $this->input->post('description');
		$notes = $this->input->post('notes');
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}else if($trip_from == '') {
			$this->response(['status' => 400, 'message' => 'trip_from is required']);
		}else if($trip_to == '') {
			$this->response(['status' => 400, 'message' => 'trip_to is required']);
		}
		else if($trip_date == '') {
			$this->response(['status' => 400, 'message' => 'trip_date is required']);
		}
		else if($trip_time == '') {
			$this->response(['status' => 400, 'message' => 'trip_time is required']);
		}
		else if($no_of_passanger == '') {
			$this->response(['status' => 400, 'message' => 'no_of_passanger is required']);
		}
		else if($smoker == '') {
			$this->response(['status' => 400, 'message' => 'smoker is required']);
		}
		else if($licend == '') {
			$this->response(['status' => 400, 'message' => 'licend is required']);
		}
		else if($description == '') {
			$this->response(['status' => 400, 'message' => 'description is required']);
		}
		else if($notes == '') {
			$this->response(['status' => 400, 'message' => 'notes is required']);
		}
		else
		{
			$ins_data = [
				'student_id' 	=> $student_id,
				'trip_from' 	=> $trip_from,
				'trip_to' 		=> $trip_to,
				'trip_date' 	=> $trip_date,
				'trip_time' 	=> $trip_time,
				'no_of_passanger' => $no_of_passanger,
				'smoker'		=> $smoker,
				'licend'		=> $licend,
				'description'	=> $description,
				'notes' 		=> $notes,
				'entry_date'	=> date('Y-m-d')
			];

			$this->common_model->insertData('trips', $ins_data);
			$this->response(['status' => 200, 'data' => $ins_data]);
		} 
	
	}
	
	public function message_request_post()
	{
		$from_id 	= $this->input->post('from_id');
		$to_id 		= $this->input->post('to_id');
		
		$chk = $this->common_model->getsingle('message_request',array('from_id'=> $from_id,'to_id'=> $to_id));			
		if($from_id == '') {
			$this->response(['status' => 400, 'message' => 'from_id is required']);
		}
		else if($to_id == '') {
			$this->response(['status' => 400, 'message' => 'to_id is required']);
		}
		else if($chk && $chk->approved==0)
		{
			$this->response(['status' => 400, 'message' => 'already Requested, wait for approval']);
		}
		else if($chk && $chk->approved==1)
		{
			$this->response(['status' => 400, 'message' => 'already request approved, you can directly message']);
		}else{
			$ins_data = [
				'from_id' 		=> $from_id,
				'to_id' 		=> $to_id,				
				'entry_date'	=> date('Y-m-d')
			];

			$this->common_model->insertData('message_request', $ins_data);
			$this->response(['status' => 200,'message' => 'request send successfully.', 'data' => $ins_data]);
		}
	
	}
	
	public function message_request_list_post()
	{
		$my_id 	= $this->input->post('my_id');
		if($my_id == '') {
			$this->response(['status' => 400, 'message' => 'my_id is required']);
		}
		else{
			$message_request = $this->common_model->getAllwhere('message_request',array('to_id'=> $my_id));
			
			$final_data = array();
			if($message_request)
			{
				foreach($message_request as $mr)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$mr->to_id));
					$mr->to_name = $student->first_name.' '.$student->last_name;
					
					$student1 = $this->common_model->getsingle('students',array('student_id'=>$mr->from_id));
					$mr->from_name = $student1->first_name.' '.$student1->last_name;
					
					if($mr->approved==0)
					{
						$mr->status = "Pending";
					}else if($mr->approved==1){
						$mr->status = "Approved";
					}
					else if($mr->approved==1){
						$mr->status = "Rejected";
					}
					$final_data[] = $mr;
				}
			}
			$this->response(['status' => 200,'message' => 'Data get successfully.', 'data' => $final_data]);
		}
	
	}
	
	public function my_request_list_post()
	{
		$my_id 	= $this->input->post('my_id');
		if($my_id == '') {
			$this->response(['status' => 400, 'message' => 'my_id is required']);
		}
		else{
			$message_request = $this->common_model->getAllwhere('message_request',array('from_id'=> $my_id));
			
			$final_data = array();
			if($message_request)
			{
				foreach($message_request as $mr)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$mr->to_id));
					$mr->to_name = $student->first_name.' '.$student->last_name;
					
					$student1 = $this->common_model->getsingle('students',array('student_id'=>$mr->from_id));
					$mr->from_name = $student1->first_name.' '.$student1->last_name;
					
					
					if($mr->approved==0)
					{
						$mr->status = "Pending";
					}else if($mr->approved==1){
						$mr->status = "Approved";
					}
					else if($mr->approved==1){
						$mr->status = "Rejected";
					}
					$final_data[] = $mr;
				}
			}
			
			$message_request = $this->common_model->getAllwhere('message_request',array('to_id'=> $my_id));
			
			
			if($message_request)
			{
				foreach($message_request as $mr)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$mr->to_id));
					$mr->to_name = $student->first_name.' '.$student->last_name;
					
					$student1 = $this->common_model->getsingle('students',array('student_id'=>$mr->from_id));
					$mr->from_name = $student1->first_name.' '.$student1->last_name;
					
					
					if($mr->approved==0)
					{
						$mr->status = "Pending";
					}else if($mr->approved==1){
						$mr->status = "Approved";
					}
					else if($mr->approved==1){
						$mr->status = "Rejected";
					}
					$final_data[] = $mr;
				}
			}
			$this->response(['status' => 200,'message' => 'Data get successfully.', 'data' => $final_data]);
		}
	
	}
	
	public function approve_reject_post()
	{
		$id 	= $this->input->post('id');
		$status = $this->input->post('status');
		
		$chk = $this->common_model->getsingle('message_request',array('id'=> $id));			
		if($id == '') {
			$this->response(['status' => 400, 'message' => 'id is required']);
		}
		else if($status == '') {
			$this->response(['status' => 400, 'message' => 'status is required']);
		}
		else if(!$chk) {
			$this->response(['status' => 400, 'message' => 'invalid record']);
		}
		else{
			$this->common_model->updateData('message_request',array('approved'=> $status),array('id'=> $id));			
			$this->response(['status' => 200,'message' => 'Update successfully.', 'data' => null]);
		}
	
	}
	
	public function send_message_post()
	{
		$from_id 	= $this->post('from_id');
		$to_id 		= $this->post('to_id');
		$message 	= $this->post('message');
		
		$chk_aprove1 = $this->common_model->getsingle('message_request',array('from_id'=>$from_id,'to_id'=>$to_id));
		$chk_aprove2 = $this->common_model->getsingle('message_request',array('from_id'=>$to_id,'to_id'=>$from_id));
		
		if($from_id == '') {
			$this->response(['status' => 400, 'message' => 'from_id is required']);
		}
		else if($to_id == '') {
			$this->response(['status' => 400, 'message' => 'to_id is required']);
		}
		else if(!$chk_aprove1 &&  !$chk_aprove2)
		{
			$this->response(['status' => 400, 'message' => 'Please message request first']);
		}
		else if( $chk_aprove1->approved!=1 && $chk_aprove2->approved!=1)
		{
			$this->response(['status' => 400, 'message' => 'your message request pending or rejected.']);
		}
		else if($message == '') {
			$this->response(['status' => 400, 'message' => 'message is required']);
		}
		else
		{
			 $message 		= json_encode($message);
			 $insertData=array(
				 	'from_id'	=> $from_id,
				 	'to_id'		=> $to_id,
				 	'message'	=> $message,
				 	'entry_date'=>date('Y-m-d'),
				 	'created_date_time'=> date('Y-m-d H:i:s')
				 );
			$this->common_model->insertData('messages',$insertData);
			$this->response(['status' => 200,'message' => 'Message send successfully.', 'data' => null]);
		}
		
		
	}
	
	public function message_list_post()
	{
		$my_id 		= $this->post('my_id');	  
		if($my_id=='')
		{
			$this->response(['status' => 400, 'message' => 'my_id is required']);
		}
		else
		{
			$messages = $this->common_model->getAllUsers_Ofmessages('messages',$my_id,'id','desc','from_id');
		
			$exi = array();
			$finaldata=array();
			if($messages!='')
			{
				foreach ($messages as $value) 
				{
					$v1 = $value->from_id.$value->to_id;
					$v2 = $value->to_id.$value->from_id;

					if(!in_array($v1,$exi))
					{
						if(!in_array($v2,$exi))
						{
							$exi[] = $v2;
							if($my_id==$value->from_id)
							{
								$students_dat = $this->common_model->getsingle('students',array('student_id'=>$value->to_id));
								$read_count= $this->common_model->getAllwhere('messages',array('to_id'=>$my_id,'from_id'=>$value->to_id,'read_message'=>'0'));							
							}else if($my_id==$value->to_id){
								$students_dat = $this->common_model->getsingle('students',array('student_id'=>$value->from_id));
								$read_count= $this->common_model->getAllwhere('messages',array('to_id'=>$my_id,'from_id'=>$value->from_id,'read_message'=>'0'));
							}
							
							$last_message= $this->common_model->last_message_bw_two_users('messages',$value->from_id,$value->to_id,'id','desc');
						
							$d['student_id']	= $students_dat->student_id;
							$d['username']		= $students_dat->username;
							$d['first_name']	= $students_dat->first_name;
							$d['last_name']		= $students_dat->last_name;
							$d['message']		= json_decode($last_message->message);
							$d['time']			= $last_message->created_date_time;
							$d['profile_pic']	= base_url().'uploads/student/'.$students_dat->profile_pic;
							if($read_count!='')
							{
								$d['unread']=count($read_count);
							}
							else
							{
								$d['unread']='0';
							}
						
							$finaldata[]=$d;
						}
					}

				}
			
				usort($finaldata, function($a, $b) {
				  $ad = new DateTime($a['time']);
				  $bd = new DateTime($b['time']);

				  if ($ad == $bd) {
					return 0;
				  }

				  return $ad > $bd ? -1 : 1;
				});
			}
			
			$this->response(['status' => 200,'message' => 'Data get successfully.', 'data' => $finaldata]);
		}
		
	}
	
	public function messages_details_post()
	{
	   $from_id 	= $this->post('from_id');
	   $to_id 		= $this->post('to_id');
	   
		if($from_id=='')
		{
			$this->response(['status' => 400, 'message' => 'from_id is required']);
		}
		else if($to_id==''){
			$this->response(['status' => 400, 'message' => 'to_id is required']);
		}
		else
		{
			$messages=$this->common_model->getTwoUsersMessages($from_id,$to_id,'id','asc');
			$this->common_model->updateData('messages',array('read_message'=>'1'),array('to_id'=>$from_id));
			$finaldata=array();
			if($messages!='')
			{
				foreach ($messages as $value) {
					$d['id']		= $value->id;
					$d['from_id']	= $value->from_id;
					$d['to_id']		= $value->to_id;
					$d['message']	= json_decode($value->message);
					$d['date_time']	= $value->created_date_time;
					$finaldata[]	= $d;
				}
			}
			$response= array('status'=>true, 'message'=>'', 'data'=>$finaldata);
		}
		$this->response($response	, 200);
	}
	
	public function my_post_trip_post()
	{
		$my_id 	= $this->input->post('my_id');
		if($my_id == '') {
			$this->response(['status' => 400, 'message' => 'my_id is required']);
		}
		else{
			$trips = $this->common_model->getAllwhere('trips',array('student_id'=> $my_id));
			
			$final_data = array();
			if($trips)
			{
				foreach($trips as $tr)
				{
					$cities1 = $this->common_model->getsingle('cities',array('city_id '=>$tr->trip_from));
					$tr->trip_from_name = $cities1->name;
					
					$cities2 = $this->common_model->getsingle('cities',array('city_id '=>$tr->trip_to));
					$tr->trip_to_name = $cities2->name;
					
					$final_data[] = $tr;
				}
			}
			$this->response(['status' => 200,'message' => 'Data get successfully.', 'data' => $final_data]);
		}
	
	}
	
	public function post_trips_post()
	{
		$my_id 	= $this->input->post('my_id');
		$students = $this->common_model->getsingle('students',array('student_id'=>$my_id));
		if($my_id == '') {
			$this->response(['status' => 400, 'message' => 'my_id is required']);
		}
		else{
			$trips = $this->common_model->getAllwhere('trips',array('trip_from'=> $students->city_id));
			
			$final_data = array();
			if($trips)
			{
				foreach($trips as $tr)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$tr->student_id));
					$tr->first_name = $student->first_name;
					$tr->last_name  = $student->last_name;
					
					$cities1 = $this->common_model->getsingle('cities',array('city_id '=>$tr->trip_from));
					$tr->trip_from_name = $cities1->name;
					
					$cities2 = $this->common_model->getsingle('cities',array('city_id '=>$tr->trip_to));
					$tr->trip_to_name = $cities2->name;
					
					$final_data[] = $tr;
				}
			}
			$this->response(['status' => 200,'message' => 'Data get successfully.', 'data' => $final_data]);
		}
	
	}
	
	public function seller_update_post()
	{
		$student_id = $this->input->post('student_id');
		$first_name = $this->input->post('first_name');
		$last_name = $this->input->post('last_name');
		$how_many 	= $this->input->post('how_many');
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		
		$country_id = $this->input->post('country_id');
		$city_id = $this->input->post('city_id');
		
		$students = $this->common_model->getsingle('students', array('student_id'=> $student_id));
		
		$check_email = $this->common_model->getsingle('students', array('email' => $email,'student_id !='=> $student_id));
		$check_phone = $this->common_model->getsingle('students', array('phone' => $phone,'student_id !='=> $student_id));
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 

		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required.']);
		}
		else if($first_name == '') {
			$this->response(['status' => 400, 'message' => 'first_name is required.']);
		}
		else if($last_name == '') {
			$this->response(['status' => 400, 'message' => 'last_name is required.']);
		}
		else if($how_many == '') {
			$this->response(['status' => 400, 'message' => 'how_many is required.']);
		}
		else if($email == '') {
			$this->response(['status' => 400, 'message' => 'Email ID is required.']);
		} else if($email != '' && $check_email != '') {
			$this->response(['status' => 400, 'message' => 'Email already exists.']);
		} else if(!preg_match($regex, $email)) {
			$this->response(['status' => 400, 'message' => 'Email is invalid.']);
		} else if($phone == '') {
			$this->response(['status' => 400, 'message' => 'Phone Number is required']);
		} else if($phone != '' && $check_phone != '') {
			$this->response(['status' => 400, 'message' => 'Phone Number already exists.']);
		} 
		else if($country_id == '') {
			$this->response(['status' => 400, 'message' => 'country_id is required']);
		}
		else if($city_id == '') {
			$this->response(['status' => 400, 'message' => 'city_id is required']);
		}		
		else  
		{
			$profile_pic = $students->profile_pic;
			 
			$config['upload_path'] = 'uploads/student/';
			$config['allowed_types'] = '*';
			$config['encrypt_name'] = false;
			
			$this->load->library('upload', $config);
			if(isset($_FILES) && $_FILES['profile_pic']['name']!="")
			{
				if ($this->upload->do_upload('profile_pic'))
				{
					$path = './uploads/profile/'.$profile_pic ;
					unlink($path);
					 if(file_exists($path)){
						unlink($path); 
					  }
					$attachment_data = array('upload_data' => $this->upload->data());
					$profile_pic = $attachment_data['upload_data']['file_name'];
				}				
			}
			
			$update_data = [
				'first_name' 	=> $first_name,
				'last_name' 	=> $last_name,
				'email' 		=> $email,
				'phone' 		=> $phone,
				'how_many' 		=> $how_many,
				'country_id'	=> $country_id,
				'city_id'		=> $city_id,
				'profile_pic'	=> $profile_pic
			];

			$this->common_model->updateData('students', $update_data,array('student_id'=>$student_id));
			
			$students = $this->common_model->getsingle('students',array('student_id'=> $student_id));			
			$students->profile_pic = base_url().'uploads/student/'.$students->profile_pic;
			
			$this->response(['status' => 200, 'message' => 'Update Successfully.', 'data' => $students]);
		}
	}
	
	public function seller_registration_post()
	{
		$username = $this->input->post('username');
		$first_name = $this->input->post('first_name');
		$last_name 	= $this->input->post('last_name');
		$how_many 	= $this->input->post('how_many');
		$country_id = $this->input->post('country_id');
		$city_id = $this->input->post('city_id');		
		$password = $this->input->post('password');
		$cpassword = $this->input->post('cpassword');
		$phone = $this->input->post('phone');
		$email = $this->input->post('email');
		
		
		$check_username = $this->common_model->getsingle('students', array('username' => $username));
		$check_email = $this->common_model->getsingle('students', array('email' => $email));
		$check_phone = $this->common_model->getsingle('students', array('phone' => $phone));
		$regex = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 

		if($username == '') {
			$this->response(['status' => 400, 'message' => 'Username is required.']);
		}else if($username != '' && $check_username) {
			$this->response(['status' => 400, 'message' => 'Username already taken.']);
		}else if($first_name == '') {
			$this->response(['status' => 400, 'message' => 'first_name is required.']);
		} else if($last_name == '') {
			$this->response(['status' => 400, 'message' => 'last_name is required.']);
		}else if($email == '') {
			$this->response(['status' => 400, 'message' => 'Email ID is required.']);
		} else if($email != '' && $check_email) {
			$this->response(['status' => 400, 'message' => 'Email already exists.']);
		} else if(!preg_match($regex, $email)) {
			$this->response(['status' => 400, 'message' => 'Email is invalid.']);
		} else if($phone == '') {
			$this->response(['status' => 400, 'message' => 'Phone Number is required']);
		} else if($phone != '' && $check_phone != '') {
			$this->response(['status' => 400, 'message' => 'Phone Number already exists.']);
		} 
		else if($how_many == '') {
			$this->response(['status' => 400, 'message' => 'how_many is required.']);
		} else if($password == '') {
			$this->response(['status' => 400, 'message' => 'Password is required']);
		} else if(strlen($password) < 6) {
			$this->response(['status' => 400, 'message' => 'Password need to be more than or equal to 6 characters']);
		} else if($cpassword == '') {
			$this->response(['status' => 400, 'message' => 'Corfirm Password is required']);
		} else if($password != $cpassword) {
			$this->response(['status' => 400, 'message' => 'Password and Confirm Password need to be the same']);
		}
		else if($country_id == '') {
			$this->response(['status' => 400, 'message' => 'country_id is required']);
		}
		else if($city_id == '') {
			$this->response(['status' => 400, 'message' => 'city_id is required']);
		}		
		else  
		{
			$student_data = [
				'username' 	 => $username,
				'first_name' => $first_name,
				'email' 	=> $email,
				'phone' 	=> $phone,
				'last_name' => $last_name,
				'how_many' 	=> $how_many,
				'country_id'=> $country_id,
				'city_id'	=> $city_id,
				'password' 	=> md5($password),
				'role' 		=> 'seller',
				'created_at'=> date('Y-m-d')
			];

			$id = $this->common_model->insertData('students', $student_data);
			
			$student_data['student_id'] = $id;
			$this->response(['status' => 200, 'message' => 'Registered Successfully.', 'data' => $student_data]);
		}
	
	}
	
	public function contact_us_post()
	{
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$phone_no = $this->input->post('phone_no');
		$message = $this->input->post('message');

		if($name == '') {
			$this->response(['status' => 400, 'message' => 'Name is required']);
		} else if($email == '') {
			$this->response(['status' => 400, 'message' => 'Email is required']);
		} else if($phone_no == '') {
			$this->response(['status' => 400, 'message' => 'Phone Number is required']);
		} else if($message == '') {
			$this->response(['status' => 400, 'message' => 'Message is required']);
		}
		else 
		{
			$contact_us = [
				'name' => $name,
				'email' => $email,
				'phone_no' => $phone_no,
				'message' => $message,
				'created_at' => date('Y-m-d')
			];

			$this->common_model->insertData('contact_us', $contact_us);
			$this->response(['status' => 200, 'message' => 'Data Submitted Successfully']);
		}
	}
	
	public function post_add_post()
	{
		$student_id = $this->input->post('student_id');		
		$property_type = $this->input->post('property_type');
		$select_option = $this->input->post('select_option');
		$description = $this->input->post('description');
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($property_type == '') {
			$this->response(['status' => 400, 'message' => 'property_type is required']);
		}
		else if($select_option == '') {
			$this->response(['status' => 400, 'message' => 'select_option is required']);
		}
		else if($description == '') {
			$this->response(['status' => 400, 'message' => 'description is required']);
		}
		else
		{
			$students = $this->common_model->getsingle('students',array('student_id'=> $student_id));
			$city_id = $students->city_id;
			
			$cpt = count($_FILES['images']['name']);
			 
			if($cpt == '0' OR $cpt < 0) 
			{
				$this->response(['status' => 400, 'message' => 'images required']);
			}
			else
			{
				$ins_data = [
					'student_id' 	=> $student_id,					
					'city_id' 		=> $city_id,
					'property_type'	=> $property_type,
					'select_option'	=> $select_option,
					'description' 	=> $description,
					'entry_date'	=> date('Y-m-d')
				];

				$post_id  = $this->common_model->insertData('posts', $ins_data);
				
				$files = $_FILES;
								
				if($cpt>0)
				{
					for($i=0; $i<$cpt; $i++)
					{           
						$_FILES['images']['name']= $files['images']['name'][$i];
						$_FILES['images']['type']= $files['images']['type'][$i];
						$_FILES['images']['tmp_name']= $files['images']['tmp_name'][$i];
						$_FILES['images']['error']= $files['images']['error'][$i];
						$_FILES['images']['size']= $files['images']['size'][$i];    
						
						$config['upload_path'] = 'uploads';
						$config['allowed_types'] = '*';
						
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						$this->upload->do_upload('images');
						$attachment_data = $this->upload->data();
						$p_image = $attachment_data['file_name'];
						
						$insdata = array(						
								'post_id' 	=> $post_id,
								'image' 	=> $p_image
							);
							
						$this->common_model->insertData('posts_images',$insdata);
					}
				}
				
				
				$this->response(['status' => 200, 'data' => $ins_data]);
			}
		} 
	
	}
	
	public function my_post_post()
	{
		$student_id = $this->input->post('student_id');		
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else
		{
			$posts = $this->common_model->getAllwhere('posts',array('student_id'=> $student_id));
			
			$final_data = array();
			if($posts)
			{
				foreach($posts as $po)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$po->student_id));
					$po->added_by = $student->first_name.' '.$student->last_name;
					
					$posts_images = $this->common_model->getAllwhere('posts_images',array('post_id'=> $po->post_id));
					$final_images = array();
					if($posts_images)
					{
						foreach($posts_images as $pimg)
						{
							$pimg->image = base_url().'uploads/'.$pimg->image;
							$final_images[] = $pimg;
						}
					}
					$po->images = $final_images;
					
					$final_data[] = $po;
				}
			}	
			$this->response(['status' => 200, 'data' => $final_data]);
		} 
	
	}
	
	public function post_by_city_post()
	{
		$student_id = $this->input->post('student_id');		
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else
		{
			$st = $this->common_model->getsingle('students',array('student_id'=>$student_id));
			$posts = $this->common_model->getAllwhere('posts',array('status'=>'1','city_id'=> $st->city_id));
			
			$final_data = array();
			if($posts)
			{
				foreach($posts as $po)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$po->student_id));
					$po->added_by = $student->first_name.' '.$student->last_name;
					
					$posts_images = $this->common_model->getAllwhere('posts_images',array('post_id'=> $po->post_id));
					$final_images = array();
					if($posts_images)
					{
						foreach($posts_images as $pimg)
						{
							$pimg->image = base_url().'uploads/'.$pimg->image;
							$final_images[] = $pimg;
						}
					}
					$po->images = $final_images;
					$final_data[] = $po;
				}
			}	
			$this->response(['status' => 200, 'data' => $final_data]);
		} 
	
	}
	
	public function post_details_post()
	{
		$student_id = $this->input->post('student_id');
		$post_id 	= $this->input->post('post_id');		
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($post_id == '') {
			$this->response(['status' => 400, 'message' => 'post_id is required']);
		}
		else
		{
			$po = $this->common_model->getsingle('posts',array('post_id'=> $post_id));			
			$student = $this->common_model->getsingle('students',array('student_id'=>$po->student_id));
			$po->added_by = $student->first_name.' '.$student->last_name;
			
			$posts_images = $this->common_model->getAllwhere('posts_images',array('post_id'=> $po->post_id));
			$final_images = array();
			if($posts_images)
			{
				foreach($posts_images as $pimg)
				{
					$pimg->image = base_url().'uploads/'.$pimg->image;
					$final_images[] = $pimg;
				}
			}
			$po->images = $final_images;
			
			$chk_spam = $this->common_model->getsingle('posts_spam',array('post_id'=> $post_id,'student_id'=>$student_id));			
			if($chk_spam)
			{
				$po->is_spam = '1';
			}else{
				$po->is_spam = '0';
			}				
			$this->response(['status' => 200, 'data' => $po]);
		} 
	
	}
	
	public function add_spam_post()
	{
		$student_id = $this->input->post('student_id');
		$post_id 	= $this->input->post('post_id');
		$chk_spam = $this->common_model->getsingle('posts_spam',array('post_id'=> $post_id,'student_id'=>$student_id));					
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($post_id == '') {
			$this->response(['status' => 400, 'message' => 'post_id is required']);
		}
		else if($chk_spam) {
			$this->response(['status' => 400, 'message' => 'Alraedy marked as spam']);
		}
		else
		{
			$posts_spam = $this->common_model->insertData('posts_spam',array('post_id'=> $post_id,'student_id'=>$student_id,'entry_date_time'=> date('Y-m-d H:i:s')));			
						
			$this->response(['status' => 200, 'message' => 'Post added spam successfully.', 'data' => $posts_spam]);
		} 
	
	}
	
	public function test_post()
	{
		$student_id = $my_id =  $this->input->post('student_id');
		$message_request = $this->common_model->getAllwhere('message_request',array('from_id'=> $my_id));
			
		$final_data = array();
		if($message_request)
		{
			foreach($message_request as $mr)
			{
				$final_data[] = $mr->to_id;
			}
		}
			
		$message_request = $this->common_model->getAllwhere('message_request',array('to_id'=> $my_id));
		if($message_request)
		{
			foreach($message_request as $mr)
			{
				if(!in_array($mr->from_id,$final_data))
				{					
					$final_data[] = $mr->from_id;
				}
			}
		}
		
		$students = $this->common_model->getstudentsmyfrnd_new($student_id,$final_data);
		
		echo "<pre>"; print_r($students); die;
	}
	public function home_post()
	{
		$student_id = $my_id = $this->input->post('student_id');
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else
		{
			$final_data = array();
			
			//$students = $this->common_model->getAllwhere_ob_limit('students',array('role'=>'student','student_id !='=> $student_id),'student_id','asc','5');
			//$students = $this->common_model->getstudentsmyfrnd($student_id);
			$message_request = $this->common_model->getAllwhere('message_request',array('from_id'=> $my_id));
			
			$finalstudents = array();
			if($message_request)
			{
				foreach($message_request as $mr)
				{
					if($mr->approved==1){
						$finalstudents[] = $mr->to_id;
					}
					
				}
			}
				
			$message_request = $this->common_model->getAllwhere('message_request',array('to_id'=> $my_id));
			if($message_request)
			{
				foreach($message_request as $mr)
				{
					if(!in_array($mr->from_id,$finalstudents))
					{	
						if($mr->approved==1){
							$finalstudents[] = $mr->from_id;
						}
					}
				}
			}
			
			$students = $this->common_model->getstudentsmyfrnd_new($student_id,$finalstudents);
			
			$f_students = array();
			if($students)
			{
				foreach($students as $std)
				{
					$std->profile_pic = base_url().'uploads/student/'.$std->profile_pic;
					$f_students[] = $std;
				}
			}
			$final_data['buddies'] = $f_students;
			
			
			$st = $this->common_model->getsingle('students',array('student_id'=>$student_id));
			$posts = $this->common_model->getAllwhere_ob_limit('posts',array('city_id'=> $st->city_id),'post_id','asc','5');
			
			$f_posts = array();
			if($posts)
			{
				foreach($posts as $po)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$po->student_id));
					$po->added_by = $student->first_name.' '.$student->last_name;
					
					$posts_images = $this->common_model->getAllwhere('posts_images',array('post_id'=> $po->post_id));
					$final_images = array();
					if($posts_images)
					{
						foreach($posts_images as $pimg)
						{
							$pimg->image = base_url().'uploads/'.$pimg->image;
							$final_images[] = $pimg;
						}
					}
					$po->images = $final_images;
					$f_posts[] = $po;
				}
			}	
			$final_data['ads'] = $f_posts;
			
			$this->response(['status' => 200, 'data' => $final_data]);
		} 
	
	}
	
	public function add_carpool_post()
	{
		$student_id = $this->input->post('student_id');
		$location = $this->input->post('location');
		$driving_experience = $this->input->post('driving_experience');
		$smoking_habit = $this->input->post('smoking_habit');
		$consumed_alcohol = $this->input->post('consumed_alcohol');
		$no_of_passengers = $this->input->post('no_of_passengers');
		$car_description = $this->input->post('car_description');
		$leave_notes = $this->input->post('leave_notes');

		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		} 
		else if($location == '') {
			$this->response(['status' => 400, 'message' => 'location is required']);
		}else if($driving_experience == '') {
			$this->response(['status' => 400, 'message' => 'driving_experience is required']);
		} else if($smoking_habit == '') {
			$this->response(['status' => 400, 'message' => 'smoking_habit is required']);
		} else if($consumed_alcohol == '') {
			$this->response(['status' => 400, 'message' => 'consumed_alcohol is required']);
		}
		else if($no_of_passengers == '') {
			$this->response(['status' => 400, 'message' => 'no_of_passengers is required']);
		}
		else if($car_description == '') {
			$this->response(['status' => 400, 'message' => 'car_description is required']);
		}
		else if($leave_notes == '') {
			$this->response(['status' => 400, 'message' => 'leave_notes is required']);
		}
		else 
		{
			$insdata = [
				'student_id' => $student_id,
				'location' => $location,
				'driving_experience' => $driving_experience,
				'smoking_habit' => $smoking_habit,
				'consumed_alcohol' => $consumed_alcohol,
				'no_of_passengers' => $no_of_passengers,
				'car_description' => $car_description,
				'leave_notes' => $leave_notes,
				'entry_date' => date('Y-m-d')
			];

			$this->common_model->insertData('carpool', $insdata);			
			$this->response(['status' => 200, 'message' => 'Data Submitted Successfully']);
		}
	}
	
	public function carpools_post()
	{
		$student_id = $this->input->post('student_id');
		$search = $this->input->post('search');		
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else
		{
			$carpool = $this->common_model->getcarpolll($search);
			//echo "<pre>"; print_r($carpool); die;
			$final_data = array();
			if($carpool)
			{
				foreach($carpool as $po)
				{
					$student = $this->common_model->getsingle('students',array('student_id'=>$po->student_id));
					$po->added_by = $student->first_name.' '.$student->last_name;					
					
					$chk_already = $this->common_model->getsingle('carpool_join',array('student_id'=>$student_id,'carpool_id'=>$po->carpool_id));
					if($chk_already)
					{
						$po->joined_carpool = '1';
					}else{
						$po->joined_carpool = '0';
					}
					
					$members = $this->common_model->getmembers($po->carpool_id);
					$final_members = array();
					if($members)
					{
						foreach($members as $mm)
						{
							$a['student_id'] = $mm->student_id;
							$a['username'] = $mm->username;
							$a['first_name'] = $mm->first_name;
							$a['last_name'] = $mm->last_name;
							$final_members[] = $a;
						}
					}
					$po->members = $final_members;
					
					$final_data[] = $po;
				}
			}	
			$this->response(['status' => 200, 'data' => $final_data]);
		} 
	
	}
	
	public function join_carpool_post()
	{
		$student_id = $this->input->post('student_id');
		$carpool_id = $this->input->post('carpool_id');
		
		$total_join = $this->common_model->record_count('carpool_join',array('carpool_id'=>$carpool_id));
		
		$chk_already = $this->common_model->getsingle('carpool_join',array('student_id'=>$student_id,'carpool_id'=>$carpool_id));
		$carpool = $this->common_model->getsingle('carpool',array('carpool_id'=>$carpool_id));
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($carpool_id == '') {
			$this->response(['status' => 400, 'message' => 'carpool_id is required']);
		}
		else if($chk_already) {
			$this->response(['status' => 400, 'message' => 'already joined.']);
		}
		else if($total_join >= $carpool->no_of_passengers) {
			$this->response(['status' => 400, 'message' => 'out of No of passengers']);
		}		
		else 
		{
			$insdata = [
				'student_id' => $student_id,
				'carpool_id' => $carpool_id,
				'entry_date' => date('Y-m-d')
			];

			$this->common_model->insertData('carpool_join', $insdata);			
			$this->response(['status' => 200, 'message' => 'Data Submitted Successfully']);
		}
	}
	
	public function carpool_detail_post()
	{
		$student_id = $this->input->post('student_id');	
		$carpool_id = $this->input->post('carpool_id');			
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($carpool_id == '') {
			$this->response(['status' => 400, 'message' => 'carpool_id is required']);
		}
		else
		{
			$po = $this->common_model->getsingle('carpool',array('carpool_id'=> $carpool_id));
			
			$student = $this->common_model->getsingle('students',array('student_id'=>$po->student_id));
			$po->added_by = $student->first_name.' '.$student->last_name;					
			
			$chk_already = $this->common_model->getsingle('carpool_join',array('student_id'=>$student_id,'carpool_id'=>$po->carpool_id));
			if($chk_already)
			{
				$po->joined_carpool = '1';
			}else{
				$po->joined_carpool = '0';
			}
			
			$members = $this->common_model->getmembers($po->carpool_id);
			$final_members = array();
			if($members)
			{
				foreach($members as $mm)
				{
					$a['student_id'] = $mm->student_id;
					$a['username'] = $mm->username;
					$a['first_name'] = $mm->first_name;
					$a['last_name'] = $mm->last_name;
					$final_members[] = $a;
				}
			}
			$po->members = $final_members;
			$this->response(['status' => 200, 'data' => $po]);
		} 
	
	}
	
	public function carpool_delete_post()
	{
		$student_id = $this->input->post('student_id');	
		$carpool_id = $this->input->post('carpool_id');			
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}
		else if($carpool_id == '') {
			$this->response(['status' => 400, 'message' => 'carpool_id is required']);
		}
		else
		{
			$this->common_model->deleteData('carpool',array('carpool_id'=> $carpool_id));
			$this->response(['status' => 200, 'data' => null]);
		} 
	
	}
	
	
	
	
	
	
}
	

