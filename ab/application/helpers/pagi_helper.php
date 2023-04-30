<script type="text/javascript">
   function ConfirmDisable()
{
  var x = confirm("Are you sure you want to disable?");
  if (x)
      return true;
  else
    return false; 
}
  function ConfirmDelete()
{
  var x = confirm("Are you sure you want to delete?");
  if (x)
      return true;
  else
    return false;
}
  function ConfirmEnable()
{
  var x = confirm("Are you sure you want to Enable Again?");
  if (x)
      return true;
  else
    return false;
}
 function ConfirmCandidateAccept()
{
  var x = confirm("Are you sure you want to Accept Registration This Candidate?");
  if (x)
      return true;
  else
    return false;
}
</script>

<?php
function custompagi($page = '', $params='',$ids=''){
	$baseUrl = base_url();
	$CI = &get_instance();
	$CI->load->model('registration/common_model'); 
    $item_per_page = 10;	
	 if($page != ''){
	  $page_number = $page;	 
	 }	else {
	 $page_number = 1;	 
	 }
	 
	 $get_total_rows = $CI->common_model->getTotalprofile($params); 
     $total_pages = ceil($get_total_rows/$item_per_page);	 
	 $page_position = (($page_number-1) * $item_per_page);	  
	 $user = $CI->common_model->getprofile($page_position, $item_per_page, $params);
          echo"
             <script type='text/javascript'>
                var toggle = true;
                function toggleBoxes() 
                {
                    var objList = document.getElementsByName('my_match[]')
                    for(i = 0; i < objList.length; i++)
                    objList[i].checked = toggle;
                    toggle = !toggle;
                }
                
                function validate()
                {
                var chks = document.getElementsByName('my_match[]');
                var hasChecked = false;
                for (var i = 0; i < chks.length; i++)
                {
                if (chks[i].checked)
                {
                hasChecked = true;
                break;
                }
                }
                if (hasChecked == false)
                {
                    alert('Please select at least one Candidate Profile.');
                    return false;
                }
                    return true;
                }
                
               
                function textBoxCreate(value,id)
                {
                    if(document.getElementById(id).checked)
                    {
                    var delid ='delete'+id;
                    var y = document.createElement('INPUT');
                    y.setAttribute('type', 'checkbox');
                    y.setAttribute('style', 'display:none');
                    y.setAttribute('Value',value);
                    y.setAttribute('id',id);
                    y.setAttribute('class',delid);
                    y.setAttribute('Name', 'my_match[]');
                    y.setAttribute('checked', 'checked');
                    document.getElementById('myForm').appendChild(y);
                    }
                    else
                    {
                    var delid ='.delete'+id;
                    $(delid).remove();
                    }
                }
           </script>
            ";
         foreach($ids as $id){
                  echo "<script>document.getElementById(".$id.").checked = true;</script>"; 
                }
            $rs = '<table id="changeContent"><tr><th>#</th><th><input type="checkbox" onclick="toggleBoxes()" />Registration No</th><th>Candidate Name</th><th>Gender</th><th>Degree</th><th>Kundli</th><th>Age</th><th>Status</th><th>Action</th></tr>';
	 
	 if(!empty($user)){
            if($page_number=='1')
                 {
                 $i=1;
                 }
            elseif($page_number>1)
                {
                $i=($page_number-1)*10+1;
                }
	 foreach($user as $u) {             
		$rs .= '<tr>';	 
		$rs .= '<td>'.$i.'</td>';
		$rs .= '<td><input type="checkbox" name="my_match[]" id="'.$i.'" value="'.$u["id"].'" onclick="textBoxCreate(this.value,this.id)">'.$u['registration_no'].'</td>';
		$rs .= '<td>'.$u['candidate_name'].'</td>';
		$rs .= '<td>'.$u['gender'].'</td>';
		$rs .= '<td>'.$u['degree'].'</td>';
		 $rs .= '<td>';
		if($u['dosh'] == "shani"){ 
			 $rs .= "Shani";
			 }
			 elseif($u['dosh'] == "manglik"){
		      $rs .= "Manglik";
			  }
                         elseif($u['dosh'] == "manglik+shani"){
		      $rs .= "Manglik + Shani";
			  }
             elseif($u['dosh'] == "non manglik"){
		      $rs .= "Non manglik";
			  }
                          elseif($u['dosh'] == "Don't Know"){
		      $rs .= "Don't Know";
			  }
		$rs .= '</td>';
		$rs .= '<td>';
		$from = new DateTime($u['dob']);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		$rs .= $age;
		 
		$rs .= ' Years</td>'; 
		 $rs .= '<td>';
		 $status = $baseUrl.'registration/status/'.$u["id"];
	//	 $rs .= '<a href="'.$status.'" > ';
		if($u['profile_status'] == "0"){ 
			 $rs .= "Enable";
			 }
			 elseif($u['profile_status'] == "1"){
		      $rs .= "Disable";
			  }	
	   // $rs .= '</a>';
	    $rs .= '</td>';	    	
		$bs = $baseUrl.'registration/send_email/'.$u["id"];
		$singleProfile = $baseUrl.'registration/singleProfile/'.$u["id"];
		$editProfile = $baseUrl.'registration/editProfile/'.$u["id"];                
		$disableProfile = $baseUrl.'registration/disableProfile/'.$u["id"];
                //$deleteProfile = $baseUrl.'registration/deleteProfile/'.$u["id"];
		$upload = $baseUrl.'upload/index/'.$u["id"];
		$rs .= '<td>';
		$rs .=  '<a href="'.$bs.'" >Send email </a>';
		$rs .=  '|';
        $rs .=  '<a href="'.$singleProfile.'" > View </a>';	
        $rs .=  '|';
        $rs .=  '<a href="'.$editProfile.'" > Edit </a>';
        $rs .=  '|';
        $rs .=  '<a href="'.$disableProfile.'"  onclick="return ConfirmDisable();"> Disable </a>';
        //$rs .=  '<a href="'.$deleteProfile.'" onclick="return ConfirmDelete();"> Delete </a>';
		$rs .=  '|';
		 $rs .=  '<a href="'.$upload.'" > Upload Photos </a>';
		$rs .= '</td>';
		$rs .= '</tr>';
     $i++;         
         } }else{
	   $rs .= '<tr>';
	   $rs .= '<td>';
       $rs .=  'No profiles available matching to your selected search criteria';
	   $rs .= '</td>';
	   $rs .= '</tr>';
	   }
	$rs .= '</table>';
        
    $rs .= '<div align="center">';
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	$rs .= paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
	$rs .='</div>';
        $rs .='</form>';
	return $rs;
}
function custompagi_disable($page = '',$ids=''){  
	$baseUrl = base_url();
	$CI = &get_instance();
	$CI->load->model('registration/common_model'); 
    $item_per_page = 10;	
	 if($page != ''){
	  $page_number = $page;	 
	 }	else {
	 $page_number = 1;	 
	 }
	 
	 $get_total_rows = $CI->common_model->getTotalprofile_disable(); 
     $total_pages = ceil($get_total_rows/$item_per_page);	
	 $page_position = (($page_number-1) * $item_per_page);	  
	 $user = $CI->common_model->getprofile_disable($page_position, $item_per_page);
          echo"
             <script type='text/javascript'>
                var toggle = true;
                function toggleBoxes() 
                {
                    var objList = document.getElementsByName('my_match[]')
                    for(i = 0; i < objList.length; i++)
                    objList[i].checked = toggle;
                    toggle = !toggle;
                }
                
                function validate()
                {
                var chks = document.getElementsByName('my_match[]');
                var hasChecked = false;
                for (var i = 0; i < chks.length; i++)
                {
                if (chks[i].checked)
                {
                hasChecked = true;
                break;
                }
                }
                if (hasChecked == false)
                {
                    alert('Please select at least one Candidate Profile.');
                    return false;
                }
                    return true;
                }
                
               
                function textBoxCreate(value,id)
                {
                    if(document.getElementById(id).checked)
                    {
                    var delid ='delete'+id;
                    var y = document.createElement('INPUT');
                    y.setAttribute('type', 'checkbox');
                    y.setAttribute('style', 'display:none');
                    y.setAttribute('Value',value);
                    y.setAttribute('id',id);
                    y.setAttribute('class',delid);
                    y.setAttribute('Name', 'my_match[]');
                    y.setAttribute('checked', 'checked');
                    document.getElementById('myForm').appendChild(y);
                    }
                    else
                    {
                    var delid ='.delete'+id;
                    $(delid).remove();
                    }
                }
           </script>
            ";
         foreach($ids as $id){
                  echo "<script>document.getElementById(".$id.").checked = true;</script>"; 
                }
        
	 $rs = '<table id="changeContent"><tr><th>#</th><th> <input type="checkbox" onclick="toggleBoxes()" />Registration No</th><th>Candidate Name</th><th>Gender</th><th>Degree</th><th>Kundli</th><th>Age</th><th>Status</th><th>Disable Date</th><th>Action</th></tr>';
	 
	 if(!empty($user)){
            if($page_number=='1')
                 {
                 $i=1;
                 }
            elseif($page_number>1)
                {
                $i=($page_number-1)*10+1;
                }
	 foreach($user as $u) {             
		$rs .= '<tr>';	 
		$rs .= '<td>'.$i.'</td>';
		$rs .= '<td> <input type="checkbox" name="my_match[]" id="'.$i.'" value="'.$u["id"].'" onclick="textBoxCreate(this.value,this.id)"> '.$u['registration_no'].'</td>';
		$rs .= '<td>'.$u['candidate_name'].'</td>';
		$rs .= '<td>'.$u['gender'].'</td>';
		$rs .= '<td>'.$u['degree'].'</td>';
		 $rs .= '<td>';
		if($u['dosh'] == "shani"){ 
			 $rs .= "Shani";
			 }
			 elseif($u['dosh'] == "manglik"){
		      $rs .= "Manglik";
			  }
             elseif($u['dosh'] == "non manglik"){
		      $rs .= "Non manglik";
			  }
                           elseif($u['dosh'] == "manglik+shani"){
		      $rs .= "Manglik + Shani";
			  }
                          elseif($u['dosh'] == "Don't Know"){
		      $rs .= "Don't Know";
			  }
		$rs .= '</td>';
		$rs .= '<td>';
		$from = new DateTime($u['dob']);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		$rs .= $age;
		 
		$rs .= ' Years</td>'; 
		 $rs .= '<td>';
		 $status = $baseUrl.'registration/status/'.$u["id"];
	//	 $rs .= '<a href="'.$status.'" > ';
		if($u['profile_status'] == "0"){ 
			 $rs .= "Enable";
			 }
			 elseif($u['profile_status'] == "1"){
		      $rs .= "Disable";
			  }	
	//    $rs .= '</a>';
	    $rs .= '</td>';
                $dis_date=explode('-',$u['disable_date']);
                $disable_date= $dis_date['2'].'-'.$dis_date['1'].'-'.$dis_date['0'];
                $rs .= '<td>'.$disable_date.'</td>';
                
		$bs = $baseUrl.'registration/send_email/'.$u["id"];
		$singleProfile = $baseUrl.'registration/singleProfile/'.$u["id"];
		$editProfile = $baseUrl.'registration/editProfile/'.$u["id"];                
		$enableProfile = $baseUrl.'registration/enableProfile/'.$u["id"];
                //$deleteProfile = $baseUrl.'registration/deleteProfile/'.$u["id"];
		$upload = $baseUrl.'upload/index/'.$u["id"];
		$rs .= '<td>';
		$rs .=  '<a href="'.$bs.'" >Send email </a>';
		$rs .=  '|';
        $rs .=  '<a href="'.$singleProfile.'" > View </a>';	
        $rs .=  '|';
        $rs .=  '<a href="'.$editProfile.'" > Edit </a>';
        $rs .=  '|';
        $rs .=  '<a href="'.$enableProfile.'"  onclick="return ConfirmEnable();"> Enable Again </a>';
        //$rs .=  '<a href="'.$deleteProfile.'" onclick="return ConfirmDelete();"> Delete </a>';
		$rs .=  '|';
		 $rs .=  '<a href="'.$upload.'" > Upload Photos </a>';
		$rs .= '</td>';
		$rs .= '</tr>';
     $i++;         
         } }else{
	   $rs .= '<tr>';
	   $rs .= '<td>';
       $rs .=  'No Disable profiles available';
	   $rs .= '</td>';
	   $rs .= '</tr>';
	   }
	$rs .= '</table>';
        
    $rs .= '<div align="center">';
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	$rs .= paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
	$rs .='</div>';
        $rs .='</form>';
	return $rs;
}

function custompagi_groom($page = '',$ids=''){  
	$baseUrl = base_url();
	$CI = &get_instance();
	$CI->load->model('registration/common_model'); 
    $item_per_page = 10;	
	 if($page != ''){
	  $page_number = $page;	 
	 }	else {
	 $page_number = 1;	 
	 }
     
	 $get_total_rows = $CI->common_model->getTotalprofile_groom(); 
     $total_pages = ceil($get_total_rows/$item_per_page);	
	 $page_position = (($page_number-1) * $item_per_page);	  
	 $user = $CI->common_model->getprofile_groom($page_position, $item_per_page);
         echo"
             <script type='text/javascript'>
                var toggle = true;
                function toggleBoxes() 
                {
                    var objList = document.getElementsByName('my_match[]')
                    for(i = 0; i < objList.length; i++)
                    objList[i].checked = toggle;
                    toggle = !toggle;
                }
                
                function validate()
                {
                var chks = document.getElementsByName('my_match[]');
                var hasChecked = false;
                for (var i = 0; i < chks.length; i++)
                {
                if (chks[i].checked)
                {
                hasChecked = true;
                break;
                }
                }
                if (hasChecked == false)
                {
                    alert('Please select at least one Candidate Profile.');
                    return false;
                }
                    return true;
                }
                
               
                function textBoxCreate(value,id)
                {
                    if(document.getElementById(id).checked)
                    {
                    var delid ='delete'+id;
                    var y = document.createElement('INPUT');
                    y.setAttribute('type', 'checkbox');
                    y.setAttribute('style', 'display:none');
                    y.setAttribute('Value',value);
                    y.setAttribute('id',id);
                    y.setAttribute('class',delid);
                    y.setAttribute('Name', 'my_match[]');
                    y.setAttribute('checked', 'checked');
                    document.getElementById('myForm').appendChild(y);
                    }
                    else
                    {
                    var delid ='.delete'+id;
                    $(delid).remove();
                    }
                }
           </script>
            ";
         foreach($ids as $id){
                  echo "<script>document.getElementById(".$id.").checked = true;</script>"; 
                }
         
         
            $rs = '<table id="changeContent"><tr><th>#</th><th><input type="checkbox" onclick="toggleBoxes()" />Registration No</th><th>Candidate Name</th><th>Gender</th><th>Degree</th><th>Kundli</th><th>Age</th><th>Status</th><th>Action</th></tr>';
	 if(!empty($user)){
             if($page_number=='1')
                 {
                 $i=1;
                 }
            elseif($page_number>1)
                {
                $i=($page_number-1)*10+1;
                }
		
	 foreach($user as $u) {             
		$rs .= '<tr>';	 
		$rs .= '<td>'.$i.'</td>';
		$rs .= '<td><input type="checkbox" name="my_match[]" id="'.$i.'" value="'.$u["id"].'" onclick="textBoxCreate(this.value,this.id)"> '.$u['registration_no'].'</td>';
		$rs .= '<td>'.$u['candidate_name'].'</td>';
		$rs .= '<td>'.$u['gender'].'</td>';
		$rs .= '<td>'.$u['degree'].'</td>';
		 $rs .= '<td>';
		if($u['dosh'] == "shani"){ 
			 $rs .= "Shani";
			 }
			 elseif($u['dosh'] == "manglik"){
		      $rs .= "Manglik";
			  }
             elseif($u['dosh'] == "non manglik"){
		      $rs .= "Non manglik";
			  }
                           elseif($u['dosh'] == "manglik+shani"){
		      $rs .= "Manglik + Shani";
			  }
                          elseif($u['dosh'] == "Don't Know"){
		      $rs .= "Don't Know";
			  }
		$rs .= '</td>';
		$rs .= '<td>';
		$from = new DateTime($u['dob']);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		$rs .= $age;
		 
		$rs .= ' Years</td>'; 
		 $rs .= '<td>';
		 $status = $baseUrl.'registration/status/'.$u["id"];
	//	 $rs .= '<a href="'.$status.'" > ';
		if($u['profile_status'] == "0"){ 
			 $rs .= "Enable";
			 }
			 elseif($u['profile_status'] == "1"){
		      $rs .= "Disable";
			  }	
	 //   $rs .= '</a>';
	    $rs .= '</td>';	    	
		$bs = $baseUrl.'registration/send_email/'.$u["id"];
		$singleProfile = $baseUrl.'registration/singleProfile/'.$u["id"];
		$editProfile = $baseUrl.'registration/editProfile/'.$u["id"];                
		$disableProfile = $baseUrl.'registration/disable_groom_Profile/'.$u["id"];
                //$deleteProfile = $baseUrl.'registration/deleteProfile/'.$u["id"];
		$upload = $baseUrl.'upload/index/'.$u["id"];
		$rs .= '<td>';
		$rs .=  '<a href="'.$bs.'" >Send email </a>';
		$rs .=  '|';
        $rs .=  '<a href="'.$singleProfile.'" > View </a>';	
        $rs .=  '|';
        $rs .=  '<a href="'.$editProfile.'" > Edit </a>';
        $rs .=  '|';
        $rs .=  '<a href="'.$disableProfile.'"  onclick="return ConfirmDisable();"> Disable </a>';
        //$rs .=  '<a href="'.$deleteProfile.'" onclick="return ConfirmDelete();"> Delete </a>';
		$rs .=  '|';
		 $rs .=  '<a href="'.$upload.'" > Upload Photos </a>';
		$rs .= '</td>';
		$rs .= '</tr>';
     $i++;         
         } }else{
	   $rs .= '<tr>';
	   $rs .= '<td>';
       $rs .=  'No boys profiles available';
	   $rs .= '</td>';
	   $rs .= '</tr>';
	   }
	$rs .= '</table>';
        
    $rs .= '<div align="center">';
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	$rs .= paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
	$rs .='</div>';
        $rs .='</form>';
	return $rs;
}
function custompagi_registrarion_accept($page = ''){  
	$baseUrl = base_url();
	$CI = &get_instance();
	$CI->load->model('registration/common_model'); 
    $item_per_page = 10;	
	 if($page != ''){
	  $page_number = $page;	 
	 }	else {
	 $page_number = 1;	 
	 }
	 
	 $get_total_rows = $CI->common_model->getTotalprofile_registrarion_accept(); 
     $total_pages = ceil($get_total_rows/$item_per_page);	
	 $page_position = (($page_number-1) * $item_per_page);	  
	 $user = $CI->common_model->getprofile_registrarion_accept($page_position, $item_per_page);
         
            $rs = '<table id="changeContent"><tr><th>#</th><th>Registration No</th><th>Candidate Name</th><th>Gender</th><th>Degree</th><th>Kundli</th><th>Age</th><th>Status</th><th>Action</th></tr>';
	 if(!empty($user)){
            if($page_number=='1')
                 {
                 $i=1;
                 }
            elseif($page_number>1)
                {
                $i=($page_number-1)*10+1;
                }
	 foreach($user as $u) {             
		$rs .= '<tr>';	 
		$rs .= '<td>'.$i.'</td>';
		$rs .= '<td>'.$u['registration_no'].'</td>';
		$rs .= '<td>'.$u['candidate_name'].'</td>';
		$rs .= '<td>'.$u['gender'].'</td>';
		$rs .= '<td>'.$u['degree'].'</td>';
		 $rs .= '<td>';
		if($u['dosh'] == "shani"){ 
			 $rs .= "Shani";
			 }
			 elseif($u['dosh'] == "manglik"){
		      $rs .= "Manglik";
			  }
             elseif($u['dosh'] == "non manglik"){
		      $rs .= "Non manglik";
			  }
                           elseif($u['dosh'] == "manglik+shani"){
		      $rs .= "Manglik + Shani";
			  }
                          elseif($u['dosh'] == "Don't Know"){
		      $rs .= "Don't Know";
			  }
		$rs .= '</td>';
		$rs .= '<td>';
		$from = new DateTime($u['dob']);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		$rs .= $age;
		 
		$rs .= ' Years</td>'; 
		 $rs .= '<td>';
		 $status = $baseUrl.'registration/status/'.$u["id"];
	//	 $rs .= '<a href="'.$status.'" > ';
		
		      $rs .= "Non Enable";
			
                          
	 //   $rs .= '</a>';
	    $rs .= '</td>';	    	
		$bs = $baseUrl.'registration/send_email/'.$u["id"];
		$singleProfile = $baseUrl.'registration/singleProfile/'.$u["id"];
		              
		$expectedProfile = $baseUrl.'registration/registration_accept/'.$u["id"];
                
		$rs .= '<td>';
	//	$rs .=  '<a href="'.$bs.'" >Send email </a>';
	//	$rs .=  '|';
        $rs .=  '<a href="'.$singleProfile.'" > View </a>';	
        $rs .=  '|';
       
        $rs .=  '<a href="'.$expectedProfile.'"  onclick="return ConfirmCandidateAccept();"> Accept </a>';
        
		$rs .= '</td>';
		$rs .= '</tr>';
     $i++;         
         } }else{
	   $rs .= '<tr>';
	   $rs .= '<td>';
       $rs .=  'No New Registrations Requests available at the moment';
	   $rs .= '</td>';
	   $rs .= '</tr>';
	   }
	$rs .= '</table>';
        
       
    $rs .= '<div align="center">';
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	$rs .= paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
	$rs .='</div>';
        $rs .='</form>';
	return $rs;
}

function custompagi_bride($page = '',$ids=''){  
	$baseUrl = base_url();
	$CI = &get_instance();
	$CI->load->model('registration/common_model'); 
    $item_per_page = 10;	
	 if($page != ''){
	  $page_number = $page;	 
	 }	else {
	 $page_number = 1;	 
	 }
	 
	 $get_total_rows = $CI->common_model->getTotalprofile_bride(); 
     $total_pages = ceil($get_total_rows/$item_per_page);	
	 $page_position = (($page_number-1) * $item_per_page);	  
	 $user = $CI->common_model->getprofile_bride($page_position, $item_per_page);
         echo"
             <script type='text/javascript'>
                var toggle = true;
                function toggleBoxes() 
                {
                    var objList = document.getElementsByName('my_match[]')
                    for(i = 0; i < objList.length; i++)
                    objList[i].checked = toggle;
                    toggle = !toggle;
                }
                
                function validate()
                {
                var chks = document.getElementsByName('my_match[]');
                var hasChecked = false;
                for (var i = 0; i < chks.length; i++)
                {
                if (chks[i].checked)
                {
                hasChecked = true;
                break;
                }
                }
                if (hasChecked == false)
                {
                    alert('Please select at least one Candidate Profile.');
                    return false;
                }
                    return true;
                }
               
                function textBoxCreate(value,id)
                {
                    if(document.getElementById(id).checked)
                    {
                    var delid ='delete'+id;
                    var y = document.createElement('INPUT');
                    y.setAttribute('type', 'checkbox');
                    y.setAttribute('style', 'display:none');
                    y.setAttribute('Value',value);
                    y.setAttribute('id',id);
                    y.setAttribute('class',delid);
                    y.setAttribute('Name', 'my_match[]');
                    y.setAttribute('checked', 'checked');
                    document.getElementById('myForm').appendChild(y);
                    }
                    else
                    {
                    var delid ='.delete'+id;
                    $(delid).remove();
                    }
                }
           </script>
            ";
         foreach($ids as $id){
                  echo "<script>document.getElementById(".$id.").checked = true;</script>"; 
                }
         
        
	 $rs = '<table id="changeContent"><tr><th>#</th><th><input type="checkbox" onclick="toggleBoxes()" />Registration No</th><th>Candidate Name</th><th>Gender</th><th>Degree</th><th>Kundli</th><th>Age</th><th>Status</th><th>Action</th></tr>';
	 
	 if(!empty($user)){
            if($page_number=='1')
                 {
                 $i=1;
                 }
            elseif($page_number>1)
                {
                $i=($page_number-1)*10+1;
                }
	 foreach($user as $u) {             
		$rs .= '<tr>';	 
		$rs .= '<td>'.$i.'</td>';
		$rs .= '<td> <input type="checkbox" name="my_match[]" id="'.$i.'" value="'.$u["id"].'" onclick="textBoxCreate(this.value,this.id)"> '.$u['registration_no'].'</td>';
		$rs .= '<td>'.$u['candidate_name'].'</td>';
		$rs .= '<td>'.$u['gender'].'</td>';
		$rs .= '<td>'.$u['degree'].'</td>';
		 $rs .= '<td>';
		if($u['dosh'] == "shani"){ 
			 $rs .= "Shani";
			 }
			 elseif($u['dosh'] == "manglik"){
		      $rs .= "Manglik";
			  }
             elseif($u['dosh'] == "non manglik"){
		      $rs .= "Non manglik";
			  }
                           elseif($u['dosh'] == "manglik+shani"){
		      $rs .= "Manglik + Shani";
			  }
                          elseif($u['dosh'] == "Don't Know"){
		      $rs .= "Don't Know";
			  }
		$rs .= '</td>';
		$rs .= '<td>';
		$from = new DateTime($u['dob']);
		$to   = new DateTime('today');
		$age = $from->diff($to)->y;
		$rs .= $age;
		 
		$rs .= ' Years</td>'; 
		 $rs .= '<td>';
		 $status = $baseUrl.'registration/status/'.$u["id"];
	//	 $rs .= '<a href="'.$status.'" > ';
		if($u['profile_status'] == "0"){ 
			 $rs .= "Enable";
			 }
			 elseif($u['profile_status'] == "1"){
		      $rs .= "Disable";
			  }	
	//    $rs .= '</a>';
	    $rs .= '</td>';	    	
		$bs = $baseUrl.'registration/send_email/'.$u["id"];
		$singleProfile = $baseUrl.'registration/singleProfile/'.$u["id"];
		$editProfile = $baseUrl.'registration/editProfile/'.$u["id"];                
		$disableProfile = $baseUrl.'registration/disable_bride_Profile/'.$u["id"];
                //$deleteProfile = $baseUrl.'registration/deleteProfile/'.$u["id"];
		$upload = $baseUrl.'upload/index/'.$u["id"];
		$rs .= '<td>';
		$rs .=  '<a href="'.$bs.'" >Send email </a>';
		$rs .=  '|';
        $rs .=  '<a href="'.$singleProfile.'" > View </a>';	
        $rs .=  '|';
        $rs .=  '<a href="'.$editProfile.'" > Edit </a>';
        $rs .=  '|';
        $rs .=  '<a href="'.$disableProfile.'"  onclick="return ConfirmDisable();"> Disable </a>';
        //$rs .=  '<a href="'.$deleteProfile.'" onclick="return ConfirmDelete();"> Delete </a>';
		$rs .=  '|';
		 $rs .=  '<a href="'.$upload.'" > Upload Photos</a>';
		$rs .= '</td>';
		$rs .= '</tr>';
     $i++;         
         } }else{
	   $rs .= '<tr>';
	   $rs .= '<td>';
       $rs .=  'No girls profiles available';
	   $rs .= '</td>';
	   $rs .= '</tr>';
	   }
	$rs .= '</table>';
        $rs .= '<div align="center">';
	/* We call the pagination function here to generate Pagination link for us. 
	As you can see I have passed several parameters to the function. */
	$rs .= paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);
	$rs .='</div>';
        $rs .='</form>';
	return $rs;
}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
        $pagination .= '<ul class="pagination">';
        
        $right_links    = $current_page + 3; 
        $previous       = $current_page - 1; //previous link 
        $next           = $current_page + 1; //next link
        $first_link     = true; //boolean var to decide our first link
       
        if($current_page > 1){
	    $previous_link = ($previous==0)?1:$previous;
            $pagination .= '<li class="first"><a href="javascript:void(0)" data-page="1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li><a href="javascript:void(0)" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
             for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
             if($i > 0){
                 $pagination .= '<li><a href="javascript:void(0)" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                } 
              }   
            $first_link = false; //set first link to false
        }
        
        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active">'.$current_page.'</li>';
        }else{ //regular current link
            $pagination .= '<li class="active">'.$current_page.'</li>';
        }
                
        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="javascript:void(0)" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){  
		$next_link = ($i > $total_pages)? $total_pages : $i;
                $pagination .= '<li><a href="javascript:void(0)" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
                $pagination .= '<li class="last"><a href="javascript:void(0)" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
        }
        
        $pagination .= '</ul>'; 
    }
    return $pagination; //return pagination links
}

?>

