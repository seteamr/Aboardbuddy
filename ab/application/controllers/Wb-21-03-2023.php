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
			$check_student = $this->common_model->getsingle('students', array('username' => $username, 'password' => md5($password)));

			if($check_student)
			{
				$student_data['student_id'] = $check_student->student_id;
				$student_data['username'] = $check_student->username;
				$student_data['email'] = $check_student->email;
				$student_data['phone'] = $check_student->phone;
				$student_data['gender'] = $check_student->gender;
				$student_data['age'] = $check_student->age;
				$student_data['dob'] = $check_student->dob;
				$student_data['token'] = bin2hex(random_bytes(16));

				$this->response(['status' => 200, 'message' => 'Login Successfull', 'data' => $student_data]);
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
		if($student_id == '') {
			$this->response(['status' => 400, 'message' => 'student_id is required']);
		}else{
			$students = $this->common_model->getsingle('students',array('student_id'=> $student_id));
			
			$students->profile_pic = base_url().'uploads/student/'.$students->profile_pic;
			
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
				'profile_pic'	=> $profile_pic
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
			
			$students = $this->common_model->getAllwhere('students',array('student_id !='=> $student_id,'country_id'=> $country_id,'city_id'=> $city_id,'university_id'=> $university_id));
			
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
	
	
	
	
	
	
}
	

