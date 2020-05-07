<?php require_once('../private/initialize.php') ?>

<!-- If user hasn't logged in, redirect to index.php-->
<?php if(!check_cookie()){
	redirect_to("index.php");}?>

<!-- Define Title -->
<?php $c_type = h($_POST["c_type"]);
	switch ($c_type){
		case "A":
			$page_title = 'Auto Insurance Invoice';
			break;
		case "H":
			$page_title = 'Home Insurance Invoice'; 
			break;
		}
?>

<!-- Common header for all pages -->
<?php include(SHARED_PATH . '/header.php') ?>

<?php 


	//request must be POST, preventing direct access via url
	if(!request_is_post()) {
		redirect_to("index.php");
	}
	//$connect to the database
	$connection = admin_login('WDS_schema');

	$c_id = h($_POST["c_id"]);
	$parsed_data=[];
	if ($c_type == 'A'){
		$query = "SELECT c_id, a_inv_id,DATE_FORMAT(a_inv_date,'%Y-%m-%d') as a_inv_date,DATE_FORMAT(a_inv_due_date,'%Y-%m-%d') as a_inv_due_date, a_inv_amount FROM a_invoice where c_id = $c_id";

		

	} else if ($c_type == 'H'){
		$query = "SELECT c_id, h_inv_id,DATE_FORMAT(h_inv_date,'%Y-%m-%d') as h_inv_date,DATE_FORMAT(h_inv_due_date,'%Y-%m-%d') as h_inv_due_date, h_inv_amount FROM h_invoice where c_id = $c_id";

	} else {
		exit('Error 15');
	}
	$result = mysqli_query($connection,$query);
	while($line = mysqli_fetch_assoc($result)){
		array_push($parsed_data,parse_data($line)+payment_button($c_id,$c_type));
	}
	$print_list = ['Customer ID','Invoice ID','Invoice Date','Due Date', 'Amount($)', 'Payments'];
	mysqli_free_result($result);
	print_table($print_list, $parsed_data);
?>




<!-- Common footer for all pages -->
<?php include(SHARED_PATH . '/footer.php') ?>