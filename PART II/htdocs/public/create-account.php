<!-- This file process user input and create account for registers -->

<?php require_once('../private/initialize.php') ?>

<!-- If user has already logged in, redirect to index.php-->
<?php if(check_cookie()){redirect_to("index.php");}?>

<!-- Define Title -->
<?php $page_title = 'Creating account'; ?>

<!-- Common header for all pages -->
<?php include(SHARED_PATH . '/header.php') ?>

<?php 
	//request must be POST, preventing direct access via url
	if(request_is_post()) {
		$username = $_POST['username'];
		$pswd = $_POST['pswd'];
		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$gender = $_POST['gender'];
		$martial = $_POST['martial'];
		$street_ad = $_POST['street_ad'];
		$city = $_POST['city'];
		$state = $_POST['state'];
		$zipcode = $_POST['zipcode'];

		//Use root cridential to signin to check if the username is taken
		$check_username_query = "SELECT * FROM customer WHERE username = '$username'";
		$connection = admin_login('WDS_schema');
		$username_result = mysqli_query($connection, $check_username_query);
		$username_taken = (mysqli_num_rows($username_result) > 0);
		mysqli_free_result($username_result);

		if ($username_taken) {
			// If username is taken, retreat to regster.php
			redirect_to('register.php?usernametaken=1');
		} else {
		 	// Else, create new user in database
		 	// Hash the password
		 	$pswdhash = password_hash($pswd, PASSWORD_DEFAULT);
		 	// Find out a available C_ID to use 
		 	$c_id = find_next_PK("c_id", "customer", $connection) or $c_id=10000;

	 		// If customer table is empty, set C_ID = 10000, else set C_ID = the_largest_C_ID + 1
	 		

		 		
	 		$insert_customer_info = "INSERT INTO customer (C_ID, USERNAME, PASSWORD, C_FNAME, C_LNAME, GENDER, MARTIAL_STA , C_STREET_AD, C_CITY, C_STATE, C_ZIPCODE) 
VALUES ($c_id, '$username', '$pswdhash', '$fname', '$lname', '$gender', '$martial', '$street_ad', '$city', '$state', '$zipcode')";
				
				// Polulate the user input
				$insert_result = mysqli_query($connection, $insert_customer_info);
				
		 		// Redirect to Customer Login page if register successess
		 		if ($insert_result){
		 			echo ('<br><div class="text-center">You\'ve successfully registered</div>');
		 			header( "Refresh:2; url=customerlogin.php", true, 303);
		 		} else {
		 			echo ('System Error');
		 			header( "Refresh:2; url=customerlogin.php", true, 303);
		 		}
		} 
	} else {
		// not allowing direct access via url
		redirect_to("index.php");
	}

?>
<!-- Common footer for all pages -->
<?php include(SHARED_PATH . '/footer.php') ?>