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
    }
	
	//ganerat token no.
	function test_get()
	{
		$response= array('status'=>'200', 'message'=>'hiii', 'data'=>null);	
        $this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function login_post()
	{
		$mobile_no = $this->post('mobile_no');		
		$device_id = $this->post('device_id');
		
		if($mobile_no=='')
		{
			$response= array('status'=>'201', 'message'=>'Mobile number Required.', 'data'=>null);	
		}
		else if($device_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Device ID Required.', 'data'=>null);	
		}
		else if(!preg_match('/^[0-9]{10}+$/', $mobile_no))
		{
			$response= array('status'=>'201', 'message'=>'Enter valid mobile no.', 'data'=>null);	
		}
		else
		{
			$users = $this->common_model->getsingle('users',array('mobile_no'=>$mobile_no));
			
			$otp="123456";
			
			if($users)
			{
				$this->common_model->updateData('users',array('otp'=>$otp,'device_id'=>$device_id),array('user_id'=>$users->user_id));
				$mobile=$users->mobile_no;
				$response= array('status'=>'200', 'message'=>'OTP Sent Successfully.', 'data'=>"+91 ".$mobile);	
			}
			else
			{
				$ins_data = array(
					'mobile_no' 	 	=> $mobile_no,
					'otp' 				=> $otp,
					'device_id' 		=> $device_id,
					'created_date'		=> date('Y-m-d H:i:s')
				);
				$insert_id = $this->common_model->insertData('users',$ins_data);
				$response= array('status'=>'200', 'message'=>'OTP Sent Successfully.', 'data'=>"+91 ".$mobile_no);	
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function verify_otp_post()
	{
		$mobile_no = $this->post('mobile_no');
		$otp = $this->post('otp');
		
		$checkotp= $this->common_model->getsingle('users',array('mobile_no'=>$mobile_no,'otp'=>$otp));
		
        if($mobile_no=='')
		{
			$response= array('status'=>'201', 'message'=>'Mobile Number Required.', 'data'=>null);	
		}
		else if($otp=='')
		{
			$response= array('status'=>'201', 'message'=>'OTP Required.', 'data'=>null);	
		}
		else if(!$checkotp)
		{
			$response= array('status'=>'201', 'message'=>'OTP Not match.', 'data'=>null);	
		}
		else{
			
			$users= $this->common_model->getsingle('users',array('user_id'=>$checkotp->user_id));
			//echo "<pre>"; print_r($users); die;
			$finalarray=array();
				
			if($users->state=='' || $users->city=='' || $users->address=='')
			{
				$is_profile = 0;
			}
			else
			{
				$is_profile = 1;
			}
				$finalarray['user_id']		= $users->user_id;
				$finalarray['mobile_no']	= $users->mobile_no;
				$finalarray['user_name'] 	= $users->user_name;
				$finalarray['email'] 		= $users->email;
				$finalarray['state'] 		= $users->state;
				$finalarray['city'] 		= $users->city;
				$finalarray['address'] 		= $users->address;
				$finalarray['lat'] 			= $users->lat;
				$finalarray['long'] 		= $users->long;
				$finalarray['user_img'] 	= base_url().'uploads/userprofile/'.$users->user_img;
				$finalarray['device_id'] 	= $users->device_id;
				$finalarray['created_date'] = $users->created_date;
				$finalarray['is_profile']	= $is_profile;
					
			$response= array('status'=>'200', 'message'=>'OTP Matched Successfully.', 'data'=>$finalarray);
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function resend_otp_post()
	{
		$mobile_no = $this->post('mobile_no');
		
		if($mobile_no=='')
		{
			$response= array('status'=>'201', 'message'=>'Mobile Number Required.', 'data'=>null);	
		}		
		else{
			
			$user = $this->common_model->getsingle('users',array('mobile_no'=>$mobile_no));
			
			if($user)
			{
				$otp="123456";
				
				$ins_data = array(
						'otp'			 => $otp,
				);
				
				$resend = $this->common_model->updateData('users',$ins_data,array('mobile_no'=>$mobile_no));
				
				$response= array('status'=>'200', 'message'=>'OTP sent Successfully.', 'data'=>"+91 ".$user->mobile_no);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Mobile Number not exist', 'data'=>null);
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function current_location_update_post()
	{
		$user_id 	= $this->post('user_id');
		$lat = $this->post('lat');
		$long = $this->post('long');
		$address = $this->post('address');
		
		if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}	
		else if($lat=='')
		{
			$response= array('status'=>'201', 'message'=>'Lat Required.', 'data'=>null);	
		}
		else if($long=='')
		{
			$response= array('status'=>'201', 'message'=>'Long Required.', 'data'=>null);	
		}
		/*else if($address=='')
		{
			$response= array('status'=>'201', 'message'=>'Address Required.', 'data'=>null);	
		}*/
		else{
			
			$user = $this->common_model->getsingle('users',array('user_id'=>$user_id));
			
			if($user)
			{
				$ins_data = array(
						'lat'			 => $lat,
						'long'			 => $long,
						'address'		 => $user->address
				);
				
				$resend = $this->common_model->updateData('users',$ins_data,array('user_id'=>$user_id));
				
				$response= array('status'=>'200', 'message'=>'location Updated successfully.', 'data'=>$ins_data);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Mobile Number not exist', 'data'=>null);
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function get_user_profile_post()
	{
		$user_id = $this->post('user_id');
		
		$check= $this->common_model->getsingle('users',array('user_id'=>$user_id));
		
        if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else if(!$check)
		{
			$response= array('status'=>'201', 'message'=>'Record not Exist .', 'data'=>null);	
		}
		else
		{				
				$get_data = array(
						'user_id' 	 				=> $check->user_id,
						'user_name' 	 			=> $check->user_name,
						'mobile_no' 	 		    => $check->mobile_no,
						'email' 	 				=> $check->email,
						'state' 	 		        => $check->state,
						'city' 	 				    => $check->city,
						'address' 	 				=> $check->address,
						'lat' 	 				    => $check->lat,
						'long' 	 				    => $check->long,
						'user_img' 	 				=> $check->user_img?base_url().'uploads/userprofile/'.$check->user_img:'',
						'device_id'                 => $check->device_id,
						'created_date'		    	=> $check->created_date
				);
					
				$response= array('status'=>'200', 'message'=>'User Profile Get Successfully.', 'data'=>$get_data);
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function update_user_profile_post()
	{
		$user_id 	= $this->post('user_id');
		$user_name 	= $this->post('user_name');
		$email 	= $this->post('email');
		$mobile_no 	= $this->post('mobile_no');
		$state 	= $this->post('state');
		$city 	= $this->post('city');
		$address 	= $this->post('address');
		
		$chkmobile = $this->common_model->getsingle('users',array('mobile_no'=>$mobile_no,'user_id!='=>$user_id));
		
		$check= $this->common_model->getsingle('users',array('user_id'=>$user_id));
		
        if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'user_id Required.', 'data'=>null);	
		}
		else if(!$check)
		{
			$response= array('status'=>'201', 'message'=>'Record not Exist .', 'data'=>null);	
		}
		else if($user_name=='')
		{
			$response= array('status'=>'201', 'message'=>'User name Required.', 'data'=>null);	
		}
		else if($mobile_no=='')
		{
			$response= array('status'=>'201', 'message'=>'Mobile no Required.', 'data'=>null);	
		}
		else if($chkmobile)
		{
			$response= array('status'=>'201', 'message'=>'Mobile Number already exist.', 'data'=>null);	
		}
		else if($state=='')
		{
			$response= array('status'=>'201', 'message'=>'State Required.', 'data'=>null);	
		}
		else if($city=='')
		{
			$response= array('status'=>'201', 'message'=>'City Required.', 'data'=>null);	
		}
		else if($address=='')
		{
			$response= array('status'=>'201', 'message'=>'Address Required.', 'data'=>null);	
		}
		else
		{
			$config['upload_path'] = 'uploads/userprofile/';
			$config['allowed_types'] = 'gif|jpg|png';
			$this->load->library('upload', $config);
					if($_FILES['image']['name']!='')
					{	
						if ($this->upload->do_upload('image')) 
						{
							$uploadData = $this->upload->data();
							$image = $uploadData['file_name'];
						}
					}else
					{
						$image = $check->user_img; 
					}
			$ins_data = array(
					'user_name' 	=> $user_name,
					'email' 		=> $email,
					'mobile_no'		=> $mobile_no,
					'state'			=> $state,
					'city'			=> $city,
					'address'		=> $address,
					'user_img'		=> $image,
					'update_date'	=> date('Y-m-d H:i:s')
			);
			$usr = $this->common_model->updateData('users',$ins_data,array('user_id'=> $user_id));
			
			$response= array('status'=>'200', 'message'=>'User Profile Update Successfully.', 'data'=>$ins_data);			
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function home_old_post()
	{
		$slider = $this->common_model->getAllwhere_ob_limit('slider',array('status'=>1),'id','RANDOM','5');
		
		$finalarray=array();
		$finalarray_slider=array();
		foreach($slider as $s)
		{
			$p['image']	=   base_url().'uploads/slider/'.$s->images;	
			$finalarray_slider[]   = $p;
		}
		$finalarray['slider']=$finalarray_slider;
		
		$categories = $user = $this->common_model->getAllwhere_ob_limit('categories',array('status'=>1),'id','RANDOM','6');
		$finalarray1=array();
		foreach($categories as $cat)
		{
			$p1['cat_id']	= $cat->id;
			$p1['category_name'] = $cat->category_name;
			$p1['category_images'] = base_url().'uploads/category/'.$cat->image;	
					
			$finalarray1[]   = $p1;				
		}
		$finalarray['categories']=$finalarray1;
		
		$products = $this->common_model->getAllwhere_ob_limit('products',array('status'=>1),'id','RANDOM','10');
		
		$final_data = array();
		if($products)
		{
			foreach($products as $p)
			{
				$p_images = $this->common_model->getsingle('p_images',array('p_id'=>$p->id));
				$p_type = $this->common_model->getsingle('p_type',array('id'=>$p->p_type));
				//(int)$p->price;
				$ab['p_id'] = $p->id;
				$ab['p_name'] = $p->name;
				$ab['price'] = $p->price/1;
				$ab['p_type'] = $p_type->name;
				$ab['p_type_qty'] = $p->p_type_qty;
				$ab['discount_price'] = $p->discount_price;
				$ab['description'] = $p->description;
				$ab['image']		= base_url('uploads/products/'.$p_images->images);
				
				$final_data[] = $ab;
			}
		}
		$finalarray['products']=$final_data;
		
		$response= array('status'=>'200', 'message'=>'Home get Successfully.', 'data'=>$finalarray);
				
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function home_post()
	{
		$user_id = $this->post('user_id');
		if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else
		{	
		$slider = $this->common_model->getAllwhere_ob_limit('slider',array('status'=>1),'id','RANDOM','5');
		
		$finalarray=array();
		$finalarray_slider=array();
		foreach($slider as $s)
		{
			$p['image']	=   base_url().'uploads/slider/'.$s->images;	
			$finalarray_slider[]   = $p;
		}
		$finalarray['slider']=$finalarray_slider;
		
		$categories = $user = $this->common_model->getAllwhere_ob_limit('categories',array('status'=>1),'id','RANDOM','6');
		$finalarray1=array();
		foreach($categories as $cat)
		{
			$p1['cat_id']	= $cat->id;
			$p1['category_name'] = $cat->category_name;
			$p1['category_images'] = base_url().'uploads/category/'.$cat->image;	
					
			$finalarray1[]   = $p1;				
		}
		$finalarray['categories']=$finalarray1;
		
		    $users = $this->common_model->getsingle('users',array('user_id'=>$user_id));
			$u_lat = $users->lat;
			$u_long = $users->long;
			$setting = $this->common_model->getsingle('setting',array());
			
			$products= $this->common_model->home_product_nearby_filter_limit('id','RANDOM','10',$u_lat,$u_long,'0',$setting->km);
			
			$final_data = array();
			
			if($products)
			{
				foreach($products as $p)
				{
					$p_images = $this->common_model->getsingle('p_images',array('p_id'=>$p->id));
					$p_type = $this->common_model->getsingle('p_type',array('id'=>$p->p_type));
					//(int)$p->price;
					$ab['p_id'] = $p->id;
					$ab['p_name'] = $p->name;
					$ab['price'] = $p->price/1;
					$ab['p_type'] = $p_type->name;
					$ab['p_type_qty'] = $p->p_type_qty;
					$ab['discount_price'] = $p->discount_price;
					$ab['description'] = $p->description;
					$ab['distance']		=	number_format((float)$p->distance, 2, '.', '');
					$ab['image']		= base_url('uploads/products/'.$p_images->images);
					$final_data[] = $ab;
				}
			}
	
		$finalarray['products']=$final_data;
		
		$response= array('status'=>'200', 'message'=>'Home get Successfully.', 'data'=>$finalarray);
		}		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function add_to_cart_post()
	{
		$p_id 	= $this->post('p_id');
		$user_id = $this->post('user_id');
		$qty 	= $this->post('qty');
		
		if($p_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Product id Required.', 'data'=>null);	
		}
		else if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		} 
		else 
		{
				$product = $this->common_model->getsingle('products',array('id'=>$p_id));
				
				if($product->qty < $qty)
				{
					$response= array('status'=>'201', 'message'=>'Product Qty not available.', 'data'=>null);	
				}
				else
				{
					$cart = $this->common_model->getsingle('cart',array('p_id'=>$p_id,'user_id'=>$user_id));
				
					if($cart)
					{
						$qdata = array(
								'qty' 	 		=> $qty
								);
								
						$this->common_model->updateData('cart',$qdata,array('user_id'=>$user_id,'p_id'=>$p_id));
						
						$response= array('status'=>'200', 'message'=>'Update Qty Successfully.', 'data'=>$qdata);
					}
					else
					{
						$indata = array(
								'user_id'		=> $user_id,
								'p_id'			=> $p_id,
								'qty'			=> $qty,
								'price'			=> $product->price,
								'discount_price'=> $product->discount_price,
								'created_date'	=> date('Y-m-d H:i:s')
								);
						$this->common_model->insertData('cart',$indata);
						
						$response= array('status'=>'200', 'message'=>'Added to cart Successfully.', 'data'=>$indata);
					}
			}
		}	
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function update_cart_qty_post()
	{
		$p_id = $this->post('p_id');
		$qty = $this->post('qty');
		$user_id = $this->post('user_id');
		
		$cart = $this->common_model->getsingle('cart',array('p_id'=>$p_id,'user_id'=>$user_id));
		
		if($p_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Product id Required.', 'data'=>null);	
		}
		else if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else if($qty=='' || $qty==0)
		{
			$response= array('status'=>'201', 'message'=>'Qty Required.', 'data'=>null);	
		}
		else
		{	
			if(!$cart)
			{
				$response= array('status'=>'201', 'message'=>'Record not found.', 'data'=>null);
			}
			else
			{
				$qdata = array(
					'qty' 	 		=> $qty
					);
			    $this->common_model->updateData('cart',$qdata,array('p_id'=>$p_id,'user_id'=>$user_id));
				
				$response= array('status'=>'200', 'message'=>'Product Qty Update Successfully.', 'data'=>$qdata);
			}		
		}		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function remove_cart_post()
	{
		$p_id = $this->post('p_id');		
		$user_id = $this->post('user_id');
		
		$cart = $this->common_model->getsingle('cart',array('p_id'=>$p_id,'user_id'=>$user_id));
		
		if($p_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Product id Required.', 'data'=>null);	
		}
		else if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else
		{	
			if(!$cart)
			{
				$response= array('status'=>'201', 'message'=>'Record not found.', 'data'=>null);
			}
			else
			{
				$this->common_model->deleteData('cart',array('p_id'=>$p_id,'user_id'=>$user_id));
				$response= array('status'=>'200', 'message'=>'Remove Product from cart Successfully.', 'data'=>$qdata);
			}		
		}		
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function mycart_post()
	{
		$user_id = $this->post('user_id');
		
		if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else
		{
			$cart = $this->common_model->mycart_data($user_id);
			
			$total=0;
			$discount=0;
			$delivry_charge=0;
			
			$finalarray=array();
			
			foreach($cart as $ct)
			{
			  $products = $this->common_model->getsingle('products',array('id'=>$ct->p_id));
			  $product_imges = $this->common_model->getsingle('p_images',array('p_id'=>$products->id));
			  $p_type = $this->common_model->getsingle('p_type',array('id'=>$ct->p_type));
			  
			  $total 			= $total + $ct->price*$ct->qty*$ct->p_type_qty;  
			  $discount 		= $discount + $ct->discount_price*$ct->qty;
			  $delivry_charge 	= $delivry_charge + $ct->delivery_charge*$ct->qty; 
			 
			  $p['user_id']			=	$ct->user_id;
              $p['p_id']			=	$ct->p_id;			  
			  $p['name']			=	$products->name;
			  $p['p_type']			=	$p_type->name;
			  //$p['p_type_qty']	=	$ct->p_type_qty;
			  $p['price']			=	$ct->price/1;
			  $p['qty']				=	$ct->qty/1;
			  $p['discount_price']	=	$ct->discount_price/1;
			  $p['delivery_charge']	=   $products->delivery_charge/1;
			  $p['description']		=	$products->description;
			  $p['image']		    =	base_url('uploads/products/'.$product_imges->images);
			  $finalarray[]= $p;
			  
			}
			
			$sbttl = ($total-$discount) + $delivry_charge;
			
			$total1=array();
			$total1['total'] = $total-$discount;
			$total1['delivry_charge']= $delivry_charge;
			//$total1['subtotal']= $sbttl;
			//$total1['discount']=$discount;
			$total1['final_total']=$sbttl;
			
			$response= array('status'=>'200', 'message'=>'Cart list get successfully..', 'total'=>$total1, 'data'=>$finalarray);	 
		}
		
		$this->response($response	, 200); // 200 being the HTTP response code
	}
	
	function checkout_post()
	{
		$user_id = $this->post('user_id');
		
		$paymet_type = $this->post('paymet_type');
		
		$razor_payment_id = $this->post('razor_payment_id');
		
		if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else if($paymet_type=='')
		{
			$response= array('status'=>'201', 'message'=>'Payment type Required.', 'data'=>null);	
		}
		else if($paymet_type=='online' && $razor_payment_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Razor payment id Required.', 'data'=>null);
		}
		else
		{	
			$records = $this->common_model->mycart_data($user_id);
			
			$total=0;
			$discount=0;
			$delivry_charge=0;
			if($records)
			{
				$order_no = date('YmdHis').'_'.rand('10000','99999');
				
				foreach($records as $r)
				{
					$total 			= $total + $r->price*$r->qty*$r->p_type_qty;
					$discount 		= $discount + $r->discount_price*$r->qty;
					$delivry_charge 	= $delivry_charge + $r->delivery_charge*$r->qty;
				 
						$ins_data = array(
							'p_id'			=> $r->p_id,
							'user_id'		=> $user_id,
							'qty'			=> $r->qty,
							'price'			=> $r->price,
							'discount_price'=> $r->discount_price,
							'order_no'		=> $order_no,
							'created_date'	=> date('Y-m-d H:i:s')
							);
						$this->common_model->insertData('orders',$ins_data); 
						
						$this->common_model->deleteData('cart',array('id'=>$r->cart_id));
				}

				$sbttl = $total + $delivry_charge;
				$final_total=$sbttl - $discount;
				
				//$rp = rand('1000000','9999999');
				if($paymet_type=='online'){ $rpid = $razor_payment_id; }else{ $rpid = 'COD';}
				$ins_data = array(
					'user_id'				=> $user_id,
					'order_no'				=> $order_no,
					'type'					=> $paymet_type,
					'price'					=> $final_total,
					'razor_payment_id'		=> $rpid,
					'created_date'			=> date('Y-m-d H:i:s')
					);
					$this->common_model->insertData('payment',$ins_data);
					
					$response= array('status'=>'200', 'message'=>'Order Successfully.', 'data'=>$ins_data);
			}
			else
			{
				
				$response= array('status'=>'201', 'message'=>'Record not found in mycart.', 'data'=>$ins_data);
			}
		}			
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function product_list_by_category_post()
	{
		$user_id 	= $this->post('user_id');
		$cat_id 	= $this->post('cat_id');
		$search_key = $this->post('search_key');
		
		if($cat_id=='')
		{
			$response= array('status'=>'201', 'message'=>'Category id Required.', 'data'=>null);	
		}
		else if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else
		{	
			$users = $this->common_model->getsingle('users',array('user_id'=>$user_id));
			$u_lat = $users->lat;
			$u_long = $users->long;
			$setting = $this->common_model->getsingle('setting',array());
			
			$category_wise= $this->common_model->product_nearby_filter($cat_id,$search_key,$u_lat,$u_long,'0',$setting->km);
			
			if($category_wise)
			{
				$finalarray=array();
			
				foreach($category_wise as $lk)
				{
					$product_imges = $this->common_model->getsingle('p_images',array('p_id'=>$lk->id));
					$p_type = $this->common_model->getsingle('p_type',array('id'=>$lk->p_type));
				
					$p['p_id']			=	$lk->id;
					$p['cat_id']		=	$lk->cat_id;
					$p['p_name']		=	$lk->name;
					$p['p_type']		=	$p_type->name;
					$p['p_type_qty']	=	$lk->p_type_qty;
					$p['price']			=	$lk->price;
					$p['discount_price']=	$lk->discount_price;
					$p['description']	=	$lk->description;
					$p['distance']		=	number_format((float)$lk->distance, 2, '.', '');
					$p['image']			=	base_url('uploads/products/'.$product_imges->images);
					
				$finalarray[]=$p;	
				}
				$response= array('status'=>'200', 'message'=>'Product list get successfully..', 'data'=>$finalarray);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Record not found.', 'data'=>null);
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code
	}
	
	function product_list_all_post()
	{
		$user_id = $this->post('user_id');
		$cat_id 	= $this->post('cat_id');
		$search_key = $this->post('search_key');
		
		if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else
		{	
			$users = $this->common_model->getsingle('users',array('user_id'=>$user_id));
			$u_lat = $users->lat;
			$u_long = $users->long;
			$setting = $this->common_model->getsingle('setting',array());
			
			$category_wise= $this->common_model->product_nearby_filter($cat_id,$search_key,$u_lat,$u_long,'0',$setting->km);
			
			if($category_wise)
			{	
				$finalarray=array();
				
					foreach($category_wise as $lk)
					{
						$product_imges = $this->common_model->getsingle('p_images',array('p_id'=>$lk->id));
						$p_type = $this->common_model->getsingle('p_type',array('id'=>$lk->p_type));
						
						$p['p_id']			=	$lk->id;
						$p['cat_id']		=	$lk->cat_id;
						$p['p_name']		=	$lk->name;
						$p['p_type']		=	$p_type->name;
						$p['p_type_qty']	=	$lk->p_type_qty;
						$p['price']			=	$lk->price;
						$p['discount_price']=	$lk->discount_price;
						$p['description']	=	$lk->description;
						$p['distance']		=	number_format((float)$lk->distance, 2, '.', '');
						$p['image']			=	base_url('uploads/products/'.$product_imges->images);
						
						$finalarray[]=$p;	
					}
						$response= array('status'=>'200', 'message'=>'Product list get successfully..', 'data'=>$finalarray);
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Record not found.', 'data'=>null);
			}
				
		}
		$this->response($response	, 200); // 200 being the HTTP response code
	}
	
	function get_categories_post()
	{
		$categories = $this->common_model->getAllwhere_ob_limit('categories',array('status'=>1),'id','RANDOM','6');
		
		$finalarray=array();
		if($categories)
		{
			
			foreach($categories as $cat)
			{
				
			$p['cat_id']	= $cat->id;
			$p['category_name'] = $cat->category_name;
			$p['category_images'] = base_url().'uploads/category/'.$cat->image;	
					
				$finalarray[]=$p;
				
			$response= array('status'=>'200', 'message'=>'Categories Get Successfully.', 'data'=>$finalarray);
			}			
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	function myorders_post()
	{
		$user_id 	= $this->post('user_id');
		if($user_id=='')
		{
			$response= array('status'=>'201', 'message'=>'User id Required.', 'data'=>null);	
		}
		else
		{	
			$myorders = $this->common_model->getAllwhere_ob_limit('payment',array('user_id'=>$user_id),'id','desc','');
		
			$finalarray=array();
			if($myorders)
			{
				foreach($myorders as $orders)
				{
				
				$users = $this->common_model->getsingle('users',array('user_id'=>$orders->user_id));
				
				$p['order_no'] = $orders->order_no;	
				$p['payment_type']	= $orders->type;
				$p['user_id'] = $orders->user_id;
				$p['user_name'] = $users->user_name;				
				$p['price'] = $orders->price;	
				$p['razor_payment_id'] = $orders->razor_payment_id;	
				$p['created_date'] = $orders->created_date;
				
					$finalarray[]=$p;
					
				$response= array('status'=>'200', 'message'=>'My orders Get Successfully.', 'data'=>$finalarray);
				}			
			}
			else
			{
				$response= array('status'=>'201', 'message'=>'Record not found', 'data'=>null);
			}
		}
		$this->response($response	, 200); // 200 being the HTTP response code	
	}
	
	
	
	
}
	

