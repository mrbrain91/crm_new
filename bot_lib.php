<?php

/********************************************************
*  Function library

********************************************************/



/*
 Function for add users to database
*/








function redirect($address){
	header("location: $address");
}







function login($connect){

	if (isset($_POST['submit_log'])) {
		$username = trim($_POST['username']);

		$username = crypt($username, 'PASSWORD_DEFAULT');

		$pass = trim($_POST['pass']);

		$query = "SELECT * FROM admin_user WHERE username='$username'";
		$result = mysqli_query($connect, $query);

		// $n = mysqli_num_rows($result);

		if (mysqli_num_rows($result) == 0) {
			$text = 'Неправильный логин или пароль';
		}
		else{
			
			$row = mysqli_fetch_assoc($result);

			// crypt($pass, $row['password']) === $row['password']

			// if (crypt($pass, $row['password']) !== $row['password']) {
			
			// 	$text = 'Неправильный логин или пароль';
				
			// }
			// else{
				$_SESSION['usersname'] = $username;
				// $_SESSION['id'] = $row['id'];

				redirect('main.php');
				$text = 'success';

			// }

			

		}
		return $text;


	}
}


function get_last_id($connect){

	$query = "SELECT id FROM users ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		return $rows[0];

}

//orto 

function get_status_in($connect, $id){
	$query = "SELECT * FROM state_in WHERE id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$state = mysqli_fetch_assoc($result);
	// exit();
	return $state;
}
function get_status_out($connect, $id){
	$query = "SELECT * FROM state_out WHERE id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$state = mysqli_fetch_assoc($result);
	// exit();
	return $state;
}



function add_state_in($connect, $name, $status) {

	$sql = "INSERT INTO `state_in` (`name`, `status_id`) VALUES ('".$name."','".$status."');";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_in.php");
	}
}

function add_state_out($connect, $name, $status) {

	$sql = "INSERT INTO `state_out` (`name`, `status_id`) VALUES ('".$name."','".$status."');";
	if(mysqli_query($connect, $sql)) {
		redirect("type_cash_out.php");
	}
}




function str_price($value)
{
	$value = explode('.', number_format($value, 2, '.', ''));
 
	$f = new NumberFormatter('ru', NumberFormatter::SPELLOUT);
	$str = $f->format($value[0]);
 
	// Первую букву в верхний регистр.
	$str = mb_strtoupper(mb_substr($str, 0, 1)) . mb_substr($str, 1, mb_strlen($str));
	
	return $str;
}



//get store item count count for rest 
function get_store_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS store_count FROM order_item_product WHERE store_itm_sts='1' GROUP BY prod_name ORDER BY prod_name asc";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}

//get returned item count for rest 
function get_returned_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS returned_count FROM return_item_tbl WHERE return_itm_sts='0' GROUP BY prod_name ORDER BY prod_name asc";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}


//get_ortder_item count for rest 
function get_order_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS order_count FROM main_ord__item_tbl WHERE order_itm_sts='1' GROUP BY prod_name ORDER BY order_count DESC";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}

// clear count rest table column
function clear_count_rest($connect, $column_name) {
	$sql1 = "SELECT prod_id FROM rest_tbl";
	$res1 = mysqli_query($connect, $sql1);
	while($row1 = mysqli_fetch_array($res1)) {
		$prod_id = $row1['prod_id'];
		$sql = "UPDATE rest_tbl SET $column_name = '0' WHERE prod_id='$prod_id'";
		mysqli_query($connect, $sql);
	}
}

//get_ortder_item count for rest new bron order
function get_new_order_item_count($connect){
	$query = "SELECT prod_name, SUM(count_name) AS order_count FROM main_ord__item_tbl WHERE order_itm_sts='0' GROUP BY prod_name ORDER BY order_count DESC";
	$rs_result = mysqli_query ($connect, $query);
	return $rs_result;
}



// orto

function del_main_ord_item_tbl($connect, $pi){

	$sql = "DELETE FROM main_ord__item_tbl WHERE id='$pi'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function del_store_item_tbl($connect, $pi){

	$sql = "DELETE FROM order_item_product WHERE id='$pi'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto

function del_return_item_tbl($connect, $pi){

	$sql = "DELETE FROM return_item_tbl WHERE id='$pi'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}





// orto

function upd_main_ord_item($connect, $orid, $pi, $p_name, $c_name, $pr__name, $s_name, $t_name){

	
	
	$sql = "UPDATE main_ord__item_tbl 
	SET 
	prod_name = '$p_name', 
	count_name = '$c_name',
	price_name = '$pr__name',
	sale_name = '$s_name',
	total_name = '$t_name'
	WHERE id='$pi' AND order_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function upd_store_item($connect, $orid, $pi, $p_name, $c_name, $pr_name, $d_name, $t_name){
	
	$sql = "UPDATE order_item_product 
	SET 
	prod_name = '$p_name', 
	count_name = '$c_name',
	date_name = '$d_name',
	price_name = '$pr_name',
	total_name = '$t_name'
	WHERE id='$pi' AND order_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_rest_count_store($connect, $prod_id, $count_store){
	$sql = "UPDATE rest_tbl 
	SET 
	count_store = '$count_store'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_rest_count_return($connect, $prod_id, $count_return){
	$sql = "UPDATE rest_tbl 
	SET 
	count_returned_order = '$count_return'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_rest_count_archived($connect, $prod_id, $count_archive){
	$sql = "UPDATE rest_tbl 
	SET 
	count_archived_order = '$count_archive'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_rest_count_new($connect, $prod_id, $count_new){
	$sql = "UPDATE rest_tbl 
	SET 
	count_new_order = '$count_new'
	WHERE prod_id='$prod_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}





// orto

function upd_return_item($connect, $orid, $pi, $p_name, $c_name, $pr__name, $s_name, $t_name){

	$sql = "UPDATE return_item_tbl 
	SET 
	prod_name = '$p_name', 
	count_name = '$c_name',
	price_name = '$pr__name',
	sale_name = '$s_name',
	total_name = '$t_name'
	WHERE id='$pi' AND return_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}



//orto

function get_pi_last_count($connect, $pi){
	
	$sql = "SELECT count_name FROM  main_ord__item_tbl WHERE id = '$pi'";
	$result = mysqli_query($connect, $sql);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		return $rows[0];
}


function get_user($connect, $user_id){
	$query = "SELECT * FROM users_tbl WHERE id='$user_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$user = mysqli_fetch_assoc($result);
	return $user;
}

function get_contractor($connect, $contractor_id){
	$query = "SELECT * FROM counterparties_tbl WHERE id='$contractor_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$contractor = mysqli_fetch_assoc($result);
	return $contractor;
}

function get_prod_name($connect, $prod_id){
	$query = "SELECT * FROM products_tbl WHERE id='$prod_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$prod_name = mysqli_fetch_assoc($result);
	return $prod_name;
}

function get_supplier($connect, $supplier_id){
	$query = "SELECT * FROM supplier_tbl WHERE id='$supplier_id'";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$supplier = mysqli_fetch_assoc($result);
	return $supplier;
}


// orto

function upd_prod_item($connect, $torid, $tpi, $tp_name, $tc_name, $td_name, $tpr__name, $ts_name, $tt_name){

	$sql = "UPDATE order_item_product 
	SET 
	prod_name = '$tp_name', 
	count_name = '$tc_name',
	date_name = '$td_name', 
	price_name = '$tpr__name',
	sale_name = '$ts_name',
	total_name = '$tt_name'
	WHERE id='$tpi' AND order_id='$torid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto

function upd_order_sum($connect, $id, $sum){
	$sql = "UPDATE order_tbl
	SET 
	sum_order = '$sum'
	WHERE id='$id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto

function upd_order_sts($connect, $archive_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '1'
	WHERE id='$archive_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto prixod prinyat qilganda, prixod statusini o'zgartirish

function upd_store_sts($connect, $store_id){
	$sql = "UPDATE order_tbl
	SET 
	status_order = '1'
	WHERE id='$store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function upd_order_sts_res($connect, $restore_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '0'
	WHERE id='$restore_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto
// prixod prinyat move to draft
function upd_store_sts_res($connect, $draft_store_id){
	$sql = "UPDATE order_tbl
	SET 
	status_order = '0'
	WHERE id='$draft_store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function upd_order_sts_del($connect, $delete_id){
	$sql = "UPDATE main_ord_tbl
	SET 
	order_status = '2'
	WHERE id='$delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function upd_return_sts_del($connect, $return_delete_id){
	$sql = "UPDATE return_list
	SET 
	return_status = '1'
	WHERE id='$return_delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto
// prixod chernovek move to otmenen
function upd_store_sts_cencel($connect, $cencel_id_store){
	$sql = "UPDATE order_tbl
	SET 
	status_order = '2'
	WHERE id='$cencel_id_store'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}






// orto

function upd_order_itm_sts($connect, $archive_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '1'
	WHERE order_id='$archive_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto prixod item statuslarini printay qilish

function upd_store_itm_sts($connect, $store_id){
	$sql = "UPDATE order_item_product
	SET 
	store_itm_sts = '1'
	WHERE order_id='$store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function upd_order_itm_sts_res($connect, $restore_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '0'
	WHERE order_id='$restore_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// prixod prinyat move to draft
function upd_store_itm_sts_res($connect, $draft_store_id){
	$sql = "UPDATE order_item_product
	SET 
	store_itm_sts = '0'
	WHERE order_id='$draft_store_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function upd_order_itm_sts_del($connect, $delete_id){
	$sql = "UPDATE main_ord__item_tbl
	SET 
	order_itm_sts = '2'
	WHERE order_id='$delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function upd_return_itm_sts_del($connect, $return_delete_id){
	$sql = "UPDATE return_item_tbl
	SET 
	return_itm_sts = '1'
	WHERE return_id='$return_delete_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto
// prixod chernovek move to otmenen
function upd_store_itm_sts_cencel($connect, $cencel_id_store){
	$sql = "UPDATE order_item_product
	SET 
	store_itm_sts = '2'
	WHERE order_id='$cencel_id_store'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}





// orto

function upd_main_order_sum($connect, $orid, $sum){
	$sql = "UPDATE main_ord_tbl
	SET 
	transaction_amount = '$sum'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto
function upd_store_sum($connect, $orid, $sum){
	$sql = "UPDATE order_tbl
	SET 
	sum_order = '$sum'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto
function upd_return_sum($connect, $orid, $sum){
	$sql = "UPDATE return_list
	SET 
	transaction_amount = '$sum'
	WHERE id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

// orto

function upd_return_debt_sum($connect, $orid, $sum){
	$sql = "UPDATE debts
	SET 
	main_prepayment = '$sum'
	WHERE return_id='$orid'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}






function update_msg_last_id($connect, $get_last_id){

	$sql = "UPDATE cron_message SET idd = '$get_last_id' WHERE id=1";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}



//bot
function add_user_full_name($connect, $username, $chat_id, $name, $old_id, $userFullName){
	$username = trim($username);
	$chat_id = trim($chat_id);
	$name = trim($name);
	$userFullName = trim($userFullName);


	if($chat_id == $old_id)
		return false;
	$t = "INSERT INTO users (username, chat_id, name, userFullName) VALUES ('%s', '%s', '%s', '%s')";
	$query = sprintf($t, mysqli_real_escape_string($connect, $username),
						 mysqli_real_escape_string($connect, $chat_id),
						 mysqli_real_escape_string($connect, $name),
						 mysqli_real_escape_string($connect, $userFullName));
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


function delete_test($connect,$test_id){
	$sql = "DELETE FROM test WHERE id='$test_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function delete_agent($connect,$id_agent){

	$sql = "DELETE FROM agents WHERE id='$id_agent'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}
function delete_object($connect,$id_object){

	$sql = "DELETE FROM main_tbl WHERE id='$id_object'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;	
}

function restore_object($connect,$id_object){
	$sql = "UPDATE main_tbl SET status_object='0' WHERE id='$id_object'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;	
}

function delete_offline_subject($connect,$offline_subject_id){

	$sql = "DELETE FROM offline_subjects WHERE id='$offline_subject_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

//bot
function add_user_phone_number($connect, $chat_id, $phone_number){
	$sql = "UPDATE users SET phone_number = '$phone_number' WHERE chat_id='$chat_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


//bot
function get_update_userFullName($connect, $chat_id, $update_name){
	$sql = "UPDATE users SET userFullName = '$update_name' WHERE chat_id='$chat_id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}




/*
Function INSERT DATA
*/
function add_test($connect, $test_name, $test_date, $test_start_time, $test_over_time){
    $test_name = trim($test_name);
    $test_date = trim($test_date);
    $test_start_time = trim($test_start_time);
    $test_over_time = trim($test_over_time);
    
    
    
    $t = "INSERT INTO test (test_name, test_date, start_time, over_time) VALUES ('%s', '%s', '%s', '%s')";

    $query = sprintf($t, mysqli_real_escape_string($connect, $test_name),
						mysqli_real_escape_string($connect, $test_date),
						mysqli_real_escape_string($connect, $test_start_time),
						mysqli_real_escape_string($connect, $test_over_time));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}







/*
Function INSERT SUBJECT
*/


//orto

function get_id_new_order($connect){
	$query = "SELECT id FROM order_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_data = mysqli_fetch_assoc($result);
	return $last_data['id'];
}

//orto

function get_id_new_return($connect){
	$query = "SELECT id FROM return_list ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_data = mysqli_fetch_assoc($result);
	return $last_data['id'];
}


//orto

function get_id_new_ord($connect){
	$query = "SELECT id FROM main_ord_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_data = mysqli_fetch_assoc($result);
	return $last_data['id'];
}




//orto 

function add_each_pro($connect) {

	$query = "SELECT id FROM order_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		$last_id = $rows[0];

	

	foreach($_POST['prod_name'] as $row => $value){

			$prod_name = $_POST['prod_name'][$row];
			$count_name = $_POST['count_name'][$row];
			$date_name = $_POST['date_name'][$row];                   
			$price_name = $_POST['price_name'][$row];
			// $sale_name = $_POST['sale_name'][$row];
			// $total_name = ($count_name * $price_name) - ($count_name * $price_name * $sale_name) / 100;
			$total_name = ($count_name * $price_name);
			$order_id = $last_id + 1;

			$sql = "INSERT INTO `order_item_product` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$total_name."');";
			mysqli_query($connect, $sql);
    }

}

//orto 

//orto 

function add_user($connect) {
	$name = $_POST['name'];
    $surname = $_POST['surname'];
    $fathername = $_POST['fathername'];
    $login = $_POST['login'];
    $pass = $_POST['pass'];
    $role = $_POST['role'];
    $status = $_POST['status'];

	$sql = "INSERT INTO `users_tbl` (`name`, `surname`, `fathername`, `login`, `pass`, `role`, `status`) VALUES ('".$name."','".$surname."','".$fathername."','".$login."','".$pass."','".$role."','".$status."');";
	if(mysqli_query($connect, $sql)) {
		redirect("users.php");
	}
	
	
}



function add_product_price($connect, $id, $product_price, $product_name){

	$sql = "INSERT INTO `price_item_tbl` (`price_id`, `name`, `cost`) VALUES ('".$id."','".$product_name."','".$product_price."');";
	if(mysqli_query($connect, $sql)) {
		redirect("edit_price.php?id=$id");
	}
	else {
		die(mysqli_error($connect));
	}

}

// dostavshikka credit qoshish-prixod tovar orqali
function add_credit_supplier($connect, $supplier_id, $ord_date, $credit, $store_id, $set_sts){
	$sql = "INSERT INTO `supplier` (`id_supplier`, `order_date`, `credit`, `come_id`, `status`) VALUES ('".$supplier_id."','".$ord_date."','".$credit."','".$store_id."','".$set_sts."');";	
	// $sql = "INSERT INTO `supplier` (`id_supplier`, `order_date`, `credit`, `come_id`, `status`) VALUES ('".$supplier_id."','".$ord_date."','".$credit."','".$store_id.",'".$sts."');";	

	if (mysqli_query($connect, $sql)) {
		redirect("in_store.php");
	}else {
		die(mysqli_error($connect));
	}
}
//

function add_credit($connect, $archive_id, $contractor_id, $debt, $ord_date, $payment_type){

	$sql = "INSERT INTO `debts` (`order_id`, `id_counterpartie`, `debt`, `order_date`, `payment_type`) VALUES ('".$archive_id."','".$contractor_id."','".$debt."','".$ord_date."','".$payment_type."');";
	if(mysqli_query($connect, $sql)) {
		$query = "SELECT  * FROM settlements WHERE id_counterpartie='$contractor_id'";
		$rs = mysqli_query($connect, $query);
		if(mysqli_num_rows($rs)<=0){
			$sql = "INSERT INTO `settlements` (`id_counterpartie`, `debt`) VALUES ('".$contractor_id."','".$debt."');";
			mysqli_query($connect, $sql);
		}else {
			$query = "UPDATE settlements SET debt = debt + '$debt' WHERE id_counterpartie='$contractor_id'";
			mysqli_query($connect, $query);
		}

		redirect("order.php");
	}
	else {
		die(mysqli_error($connect));
	}

}

function add_debt($connect, $archive_id, $contractor_id, $debt, $ord_date, $payment_type){

	$sql = "INSERT INTO `debts` (`order_id`, `id_counterpartie`, `debt`, `order_date`, `payment_type`) VALUES ('".$archive_id."','".$contractor_id."','".$debt."','".$ord_date."','".$payment_type."');";
	if(mysqli_query($connect, $sql)) {
		$query = "SELECT  * FROM settlements WHERE id_counterpartie='$contractor_id'";
		$rs = mysqli_query($connect, $query);
		if(mysqli_num_rows($rs)<=0){
			$sql = "INSERT INTO `settlements` (`id_counterpartie`, `debt`) VALUES ('".$contractor_id."','".$debt."');";
			mysqli_query($connect, $sql);
		}else {
			$query = "UPDATE settlements SET debt = debt + '$debt' WHERE id_counterpartie='$contractor_id'";
			mysqli_query($connect, $query);
		}

		redirect("order.php");
	}
	else {
		die(mysqli_error($connect));
	}

}

function delete_debt($connect, $restore_id){
	$sql = "DELETE FROM `debts` WHERE order_id IN ('$restore_id')";
	if(mysqli_query($connect, $sql)) {
		redirect("archive_order.php");
	}
	else {
		die(mysqli_error($connect));
	}
}

function delete_debt_return($connect, $return_delete_id){
	$sql = "DELETE FROM `debts` WHERE return_id IN ('$return_delete_id')";
	if(mysqli_query($connect, $sql)) {
		redirect("return_list.php");
	}
	else {
		die(mysqli_error($connect));
	}
}

function delete_credit_supplier($connect, $draft_store_id){
	$sql = "DELETE FROM `supplier` WHERE come_id IN ('$draft_store_id')";
	if(mysqli_query($connect, $sql)) {
		redirect("in_store.php");
	}
	else {
		die(mysqli_error($connect));
	}
}

function cash_in_add($connect, $state_id,  $cash_sum, $cash_type, $cash_comment, $cash_date){

	$sql = "INSERT INTO `cashbox` (`types_id`, `sum_in`, `type_payment`, `comment`, `date_cash`) VALUES ('".$state_id."','".$cash_sum."','".$cash_type."','".$cash_comment."','".$cash_date."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("cash_in.php");
	}

}
function cash_out_add($connect, $state_id,  $cash_sum, $cash_type, $cash_comment, $cash_date){

	$sql = "INSERT INTO `cashbox` (`types_id`, `sum_out`, `type_payment`, `comment`, `date_cash`) VALUES ('".$state_id."','".$cash_sum."','".$cash_type."','".$cash_comment."','".$cash_date."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("cash_out.php");
	}

}

function add_main_prepayment($connect, $id_counterpartie, $prepayment_date, $prepayment_sum, $payment_type, $sts){


	$sql = "INSERT INTO `debts` (`id_counterpartie`, `order_date`, `main_prepayment`, `payment_type`, `sts`) VALUES ('".$id_counterpartie."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."','".$sts."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("prepayment_list.php");
	}
}

function add_main_prepayment_return($connect, $id_counterpartie, $return_id, $prepayment_date, $prepayment_sum, $payment_type, $sts){
	$sql = "INSERT INTO `debts` (`id_counterpartie`, `return_id`, `order_date`, `main_prepayment`, `payment_type`, `sts`) VALUES ('".$id_counterpartie."','".$return_id."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."','".$sts."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("return_list.php");
	}

}


function add_debt_supplier($connect, $id_counterpartie, $prepayment_date, $prepayment_sum, $payment_type){

	$sql = "INSERT INTO `supplier` (`id_supplier`, `order_date`, `debt`, `payment_type`) VALUES ('".$id_counterpartie."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."');";
	
	if (mysqli_query($connect, $sql)) {
		redirect("supplier_list.php");
	}

}


function add_prepayment($connect, $id_counterpartie, $prepayment_date, $prepayment_sum, $payment_type){

	$sql = "INSERT INTO `prepayments` (`id_counterpartie`, `prepayment_date`, `prepayment_sum`, `payment_type`) VALUES ('".$id_counterpartie."','".$prepayment_date."','".$prepayment_sum."','".$payment_type."');";
	if(mysqli_query($connect, $sql)) {

		$query = "SELECT  * FROM settlements WHERE id_counterpartie='$id_counterpartie'";
		$rs = mysqli_query($connect, $query);
		if(mysqli_num_rows($rs)<=0){
			$sql = "INSERT INTO `settlements` (`id_counterpartie`, `prepayment`) VALUES ('".$id_counterpartie."','".$prepayment_sum."');";
			mysqli_query($connect, $sql);
		}else {
			$query = "UPDATE settlements SET prepayment = prepayment + '$prepayment_sum' WHERE id_counterpartie='$id_counterpartie'";
			mysqli_query($connect, $query);
		}



		redirect("prepayment_list.php");
	}
	else {
		die(mysqli_error($connect));
	}

}




function add_product($connect, $name, $unit) {
	$sql = "INSERT INTO `products_tbl` (`name`, `unit`) VALUES ('".$name."','".$unit."');";
	if(mysqli_query($connect, $sql)) {
		//get last product id
		$query = "SELECT id FROM products_tbl ORDER BY id DESC LIMIT 1";
		$result = mysqli_query($connect, $query);
		$rows = mysqli_fetch_row($result);
		$last_id = $rows[0];
		//
		// set to rest table
		$sql_last = "INSERT INTO `rest_tbl` (`prod_id`) VALUES ('".$last_id."');";
			if (mysqli_query($connect, $sql_last)) {
				redirect("products.php");
			}	
	
	}
}	


function add_counterparties($connect) {
	$name=$_POST['name'];
	$alternative_name=$_POST['alternative_name'];
	$inn=$_POST['inn'];        
	$nds=$_POST['nds'];
	$raschetny_schet=$_POST['raschetny_schet'];
	$mfo=$_POST['mfo'];
	$address=$_POST['address'];
	$contact=$_POST['contact'];
	$director=$_POST['director'];
	$accountant=$_POST['accountant'];

	$sql = "INSERT INTO `counterparties_tbl` (`name`, `alternative_name`, `inn`, `nds`, `raschetny_schet`, `mfo`, `address`, `contact`, `director`, `accountant`) VALUES ('".$name."','".$alternative_name."','".$inn."','".$nds."','".$raschetny_schet."','".$mfo."','".$address."','".$contact."','".$director."','".$accountant."');";
	if(mysqli_query($connect, $sql)) {
		redirect("counterparties.php");
	}	
}

function add_supplier($connect) {
	$name=$_POST['name'];
	$alternative_name=$_POST['alternative_name'];
	$inn=$_POST['inn'];        
	$nds=$_POST['nds'];
	$raschetny_schet=$_POST['raschetny_schet'];
	$mfo=$_POST['mfo'];
	$address=$_POST['address'];
	$contact=$_POST['contact'];
	$director=$_POST['director'];
	$accountant=$_POST['accountant'];

	$sql = "INSERT INTO `supplier_tbl` (`name`, `alternative_name`, `inn`, `nds`, `raschetny_schet`, `mfo`, `address`, `contact`, `director`, `accountant`) VALUES ('".$name."','".$alternative_name."','".$inn."','".$nds."','".$raschetny_schet."','".$mfo."','".$address."','".$contact."','".$director."','".$accountant."');";
	if(mysqli_query($connect, $sql)) {
		redirect("supplier.php");
	}	
}


// edit page add product function
function edit_page_add($connect, $order_id, $prod_name, $count_name, $date_name, $price_name, $sale_name, $total_name) {

	$sql = "INSERT INTO `main_ord__item_tbl` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$total_name."');";	
	$res = mysqli_query($connect, $sql);

	if(!$res)
		die(mysqli_error($connect));
	return true;
}


//store edit page  add product function
function edit_page_add_store($connect, $id, $prod_name, $count_name, $date_name, $price_name, $total_name) {

	$sql = "INSERT INTO `order_item_product` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `total_name`) VALUES ('".$id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$total_name."');";	
	$res = mysqli_query($connect, $sql);

	if(!$res)
		die(mysqli_error($connect));
	return true;
}


// edit page return add product function
function edit_page_add_ret($connect, $order_id, $prod_name, $count_name, $date_name, $price_name, $sale_name, $total_name) {

	$sql = "INSERT INTO `return_item_tbl` (`return_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$total_name."');";	
	$res = mysqli_query($connect, $sql);

	if(!$res)
		die(mysqli_error($connect));
	return true;
}

// add each order tbl
function add_each_ord($connect) {
	$query = "SELECT id FROM main_ord_tbl ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		$last_id = $rows[0];


	
	if(isset($_POST['submit']) && $_POST['submit'] == 'Принять'){

        foreach($_POST['prod_name'] as $row => $value){

                $prod_name=$_POST['prod_name'][$row];
                $count_name=$_POST['quantity'][$row];
                $date_name=$_POST['main_order_date'];                   
                echo $price_name=$_POST['product_price'][$row];
                $sale_name=$_POST['sale'][$row];
    			$total_name = ($count_name * $price_name) + ($count_name * $price_name * $sale_name) / 100;
				$order_id = $last_id + 1;

             $sql = "INSERT INTO `main_ord__item_tbl` (`order_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `total_name`) VALUES ('".$order_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$total_name."');";
              

			if (mysqli_query($connect, $sql)) {
				echo 'successfully';
			}
			else {
				echo("Error description: " . $mysqli -> error);
			}
    }
	
	
	redirect("order.php");

}


}

// add each return tbl
function add_each_return($connect) {
	$query = "SELECT id FROM return_list ORDER BY id DESC LIMIT 1";
	$result = mysqli_query($connect, $query);
	$rows = mysqli_fetch_row($result);
	if(!$result)
		die(mysqli_error($connect));
		$last_id = $rows[0];
		
	
	if(isset($_POST['submit']) && $_POST['submit'] == 'Принять'){

		foreach($_POST['prod_name'] as $row => $value){

				$prod_name=$_POST['prod_name'][$row];
				$count_name=$_POST['quantity'][$row];
				$date_name=$_POST['return_date'];                   
				$price_name=$_POST['product_price'][$row];
				$sale_name=$_POST['sale'][$row];
				$total_name = ($count_name * $price_name) + ($count_name * $price_name * $sale_name) / 100;
				$return_id = $last_id + 1;

			$sql = "INSERT INTO `return_item_tbl` (`return_id`, `prod_name`, `count_name`, `date_name`, `price_name`, `sale_name`, `total_name`) VALUES ('".$return_id."','".$prod_name."','".$count_name."','".$date_name."','".$price_name."','".$sale_name."','".$total_name."');";
			

			if (mysqli_query($connect, $sql)) {
				echo 'success';
			}
			else {
				echo("Error description: " . $mysqli -> error);
			}
		}
		redirect("return_list.php");
	}
}


function get_sum_id($connect, $id){


	$query = "SELECT SUM(total_name) FROM order_item_product WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}

//main_order
function get_sum_id_main($connect, $orid){

	$query = "SELECT SUM(total_name) FROM main_ord__item_tbl WHERE order_id='$orid'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}




//return 
function get_sum_id_return($connect, $orid){

	$query = "SELECT SUM(total_name) FROM return_item_tbl WHERE return_id='$orid'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}

//store edit
function get_sum_id_store($connect, $orid){

	$query = "SELECT SUM(total_name) FROM order_item_product WHERE order_id='$orid'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}


function get_sum($connect){

	$last_id = get_id_new_order($connect);
	$last_id = $last_id+1;

	$query = "SELECT SUM(total_name) FROM order_item_product WHERE order_id='$last_id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}

function get_sum_main_ord($connect){

	$last_id = get_id_new_ord($connect);
	$last_id = $last_id+1;

	$query = "SELECT SUM(total_name) FROM main_ord__item_tbl WHERE order_id='$last_id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}

function get_sum_return($connect){

	$last_id = get_id_new_return($connect);
	$last_id = $last_id+1;
	

	$query = "SELECT SUM(total_name) FROM return_item_tbl WHERE return_id='$last_id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_data = mysqli_fetch_assoc($result);
	return $sum_data['SUM(total_name)'];
	// return $last_id;
}


//main_order

function sum_count_return($connect, $id){

	$query = "SELECT SUM(count_name) FROM return_item_tbl WHERE return_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
	// return $last_id;
}


//main_order

function sum_count_main($connect, $id){

	$query = "SELECT SUM(count_name) FROM main_ord__item_tbl WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
	// return $last_id;
}

//store

function sum_count_store($connect, $id){

	$query = "SELECT SUM(count_name) FROM order_item_product WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
	// return $last_id;
}

function sum_count($connect, $id){

	$query = "SELECT SUM(count_name) FROM order_item_product WHERE order_id='$id'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$sum_count = mysqli_fetch_assoc($result);
	return $sum_count['SUM(count_name)'];
	// return $last_id;
}

// orto 
function add_prod($connect, $summ_prod) {
	$id_supplier = $_POST['order_supplier'];
	$total_name = $summ_prod;
	$order_date = $_POST['order_date'];
	$order_note = $_POST['order_note'];
	
	$t = "INSERT INTO order_tbl (supplier_id, sum_order, date_order, order_note) VALUES ('%s', '%s', '%s', '%s')";
	
	$query = sprintf($t, mysqli_real_escape_string($connect, $id_supplier),
						mysqli_real_escape_string($connect, $total_name),
						mysqli_real_escape_string($connect, $order_date),
						mysqli_real_escape_string($connect, $order_note));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	elseif($result) {
		redirect('in_store.php');
	}
}

// orto 
function add_main_ord($connect, $summ_prod) {
	
	$main_order_contractor = $_POST['main_order_contractor'];
	$main_order_sale_agent = $_POST['main_order_sale_agent'];
	$main_order_date = $_POST['main_order_date'];
	$main_order_paymen_type = $_POST['main_order_paymen_type'];
	$total_name = $summ_prod;

	
	$t = "INSERT INTO main_ord_tbl (contractor, sale_agent, ord_date, payment_type, transaction_amount) VALUES ('%s', '%s', '%s', '%s', '%s')";
	
	$query = sprintf($t, mysqli_real_escape_string($connect, $main_order_contractor),
						mysqli_real_escape_string($connect, $main_order_sale_agent),
						mysqli_real_escape_string($connect, $main_order_date),
						mysqli_real_escape_string($connect, $main_order_paymen_type),
						mysqli_real_escape_string($connect, $total_name));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// orto 
function add_return($connect, $summ_prod, $ret_id) {
	$return_contractor = $_POST['return_contractor'];
	$return_sale_agent = $_POST['return_sale_agent'];
	$return_date = $_POST['return_date'];
	$return_paymen_type = $_POST['return_paymen_type'];
	$total_name = $summ_prod;
	$return_id = $ret_id;


	
	$t = "INSERT INTO return_list (contractor, sale_agent, return_date, payment_type, transaction_amount) VALUES ('%s', '%s', '%s', '%s', '%s')";
	
	$query = sprintf($t, mysqli_real_escape_string($connect, $return_contractor),
						mysqli_real_escape_string($connect, $return_sale_agent),
						mysqli_real_escape_string($connect, $return_date),
						mysqli_real_escape_string($connect, $return_paymen_type),
						mysqli_real_escape_string($connect, $total_name));
    $result = mysqli_query($connect, $query);
	if($result){
		$sts = 3;
		add_main_prepayment_return($connect, $return_contractor, $return_id, $return_date, $total_name, $return_paymen_type, $sts);
		return true;
	}else{
		die(mysqli_error($connect));
	}
}



//orto


//new 
function add_agent($connect, $agent_name, $agent_phone_number){
    $agent_name = trim($agent_name);
    $agent_phone_number = trim($agent_phone_number);
	$t = "INSERT INTO agents (userName, phoneNumber) VALUES ('%s', '%s')";

    $query = sprintf($t, mysqli_real_escape_string($connect, $agent_name),
						mysqli_real_escape_string($connect, $agent_phone_number));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function add_region($connect, $region_name){
    $region_name = trim($region_name);
	$t = "INSERT INTO regions (name_region) VALUES ('%s')";

    $query = sprintf($t, mysqli_real_escape_string($connect, $region_name));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function add_quarter($connect, $name_of_quarter, $id_region){
    $name_of_quarter = trim($name_of_quarter);
    $id_region = trim($id_region);

	$t = "INSERT INTO quarters (name_quarter, id_region) VALUES ('%s','%s')";

    $query = sprintf($t, mysqli_real_escape_string($connect, $name_of_quarter),
						 mysqli_real_escape_string($connect, $id_region));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function add_street($connect, $name_of_street, $id_region, $id_quarter){
    $name_of_street = trim($name_of_street);
    $id_region = trim($id_region);
    $id_quarter = trim($id_quarter);


	$t = "INSERT INTO streets (name_street, id_region, id_quarter) VALUES ('%s','%s','%s')";

    $query = sprintf($t, mysqli_real_escape_string($connect, $name_of_street),
						 mysqli_real_escape_string($connect, $id_region),
						 mysqli_real_escape_string($connect, $id_quarter));
    $result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


/*
 Function for add text entered by user
*/
//bot
function textlog($connect, $chat_id, $text, $step, $vars = []) {
	$vars = serialize($vars);
	if($chat_id == '') return false;
	$t = "INSERT INTO textlog (chat_id, msg, step, vars) VALUES ('%s', '%s', '%s', '%s')";
	$query = sprintf($t, mysqli_real_escape_string($connect, $chat_id),
							mysqli_real_escape_string($connect, $text), 
							mysqli_real_escape_string($connect, $step), 
							mysqli_real_escape_string($connect, $vars));
	$result = mysqli_query($connect, $query);

	if(!$result)
		die(mysqli_error($connect));
	return true;				
}
/*
Function to get all agents from the database
*/



function name_agent($connect, $id_agent){
	$query = "SELECT userName FROM agents WHERE userId='$id_agent'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$name_agent = mysqli_fetch_assoc($result);
	$name_agent = $name_agent['userName'];
	return $name_agent;
}



function regions_all($connect){
	$query = "SELECT * FROM regions";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$regions_all = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$regions_all[] = $row;
	}
	return $regions_all;
}


function users_all_for_res($connect){
	$query = "SELECT * FROM result WHERE issett_res = '' ORDER BY id DESC LIMIT 100";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);

	if($n > 0){
		$users_all_for_res = array();
		for ($i = 0; $i <$n; $i++){
			$row = mysqli_fetch_assoc($result);
			$users_all_for_res[] = $row;
		}
		return $users_all_for_res;
	}else{
		return 0;
	}
}

function users_all_for_mail($connect){
	$query = "SELECT * FROM users WHERE issett = '' ORDER BY id DESC LIMIT 100";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$users_all_for_mail = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$users_all_for_mail[] = $row;
	}
	return $users_all_for_mail;
}

function agents_all_for_mail($connect){
	$query = "SELECT * FROM agents";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$agents_all_for_mail = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$agents_all_for_mail[] = $row;
	}
	return $agents_all_for_mail;
}


function count_send_msg($connect){
	$query = "SELECT * FROM users WHERE issett = 'yes' ";
	$result = mysqli_query($connect, $query);
	if (!$result) 
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	return $n;
}



function update_mailed_res($connect, $id){
	$sql = "UPDATE result SET issett_res = 'yes' WHERE id='$id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function updateKey($connect, $setKey){
	$query = "UPDATE admin_user SET special_key = '$setKey' WHERE id = 1";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	return true;	
}

function update_mailed($connect, $id){
	$sql = "UPDATE users SET issett = 'yes' WHERE id='$id'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function update_cron_message($connect, $text_cron_message){

	// echo $text_cron_message;
	// exit();

	$sql = "UPDATE cron_message SET cron_message = '$text_cron_message', idd = '0' WHERE id=1";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

function clear_column_issett($connect){
	
	$sql = "UPDATE users SET issett = Null";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}

//ozgarish

function select_cron_time($connect){

	$query = "SELECT cron_time FROM cron_message WHERE id = 1";

	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$time = mysqli_fetch_assoc($result);
	$time = $time['cron_message'];
	return $time;
}

function select_cron_mess($connect){

	$query = "SELECT cron_message FROM cron_message WHERE id = 1";

	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$mes = mysqli_fetch_assoc($result);
	$mes = $mes['cron_message'];
	return $mes;
}




// function count_users_all($connect){

// 	$query = "SELECT * FROM users";
// 	$result = mysqli_query($connect, $query);
// 	$num_row = mysqli_num_rows($result);
// 	return $num_row;
// }



/*
Function to get all subjects from the database
*/


/*Check user id*/

//bot
function get_check_user($connect, $chat_id){
	$query = sprintf("SELECT phone_number FROM users WHERE chat_id=%d", (int)$chat_id);
	$result = mysqli_query($connect, $query);

	$row = mysqli_fetch_assoc($result);


	if(mysqli_num_rows($result)>=1  && !empty($row['phone_number'])){
		return 'is_user';
   }else{
  		 return 'not_user';
    }
}

//bot
function get_check_phone($connect, $chat_id){
	$query = sprintf("SELECT phone_number FROM users WHERE chat_id=%d", (int)$chat_id);
	$result = mysqli_query($connect, $query);

	$row = mysqli_fetch_assoc($result);


	if(empty($row['phone_number'])){
		return false;
   }else{
  		 return true;
    }
}





/**************/

//bot
function get_offline_subject($connect, $offline_subject_name){
	$query = sprintf("SELECT * FROM offline_subjects WHERE offline_subject_name='%s' limit 1", $offline_subject_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$get_offline_subject = mysqli_fetch_assoc($result);
	return $get_offline_subject;
}

function get_offline_subjects_all($connect){
	$query = "SELECT * FROM offline_subjects";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$get_offline_subjects_all = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$get_offline_subjects_all[] = $row;
	}
	return $get_offline_subjects_all;
}


function last_psw($connect){
	$query = "SELECT special_key FROM admin_user WHERE id = 1";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$last_psw = mysqli_fetch_assoc($result);
	$last_psw = $last_psw['special_key'];
	return $last_psw;
}






function subjects_all($connect,$get_id){ 
	$query = "SELECT * FROM subjects where test_id = $get_id";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$subjects_all = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$subjects_all[] = $row;
	}
	return $subjects_all;
}


function select_subject($connect, $subject_id){

	$query = "SELECT filenames FROM subjects WHERE id = $subject_id";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$filename = mysqli_fetch_assoc($result);
	$filenames = $filename['filenames'];
	return $filenames;
}


function select_offline_subject($connect, $offline_subject_id){

	$query = "SELECT offline_filenames FROM offline_subjects WHERE id = $offline_subject_id";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$offline_filenames = mysqli_fetch_assoc($result);
	$offline_filenames = $offline_filenames['offline_filenames'];
	return $offline_filenames;
}



// function delete_file($filename){

// 	unlink("uploads/".$filename."");
// }

//bot
function tests_all($connect){
	$query = "SELECT * FROM test";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$tests_all = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$tests_all[] = $row;
	}
	return $tests_all;
}


function registredMemberCount($connect) {
    $sql = "SELECT COUNT(id) FROM agents";
    $result = mysqli_query($connect,$sql);
    $rows = mysqli_fetch_row($result);
    return $rows[0];
}

function registredObjectCount($connect) {
    $sql = "SELECT COUNT(id) FROM main_tbl";
    $result = mysqli_query($connect,$sql);
    $rows = mysqli_fetch_row($result);
    return $rows[0];
}


function regionsCount($connect) {
    $sql = "SELECT COUNT(id) FROM regions";
    $result = mysqli_query($connect,$sql);
    $rows = mysqli_fetch_row($result);
    return $rows[0];
}
function quartersCount($connect, $id_region) {
    $sql = "SELECT COUNT(id) FROM quarters WHERE id_region = '$id_region'";
    $result = mysqli_query($connect,$sql);
    $rows = mysqli_fetch_row($result);
    return $rows[0];
}
function streetsCount($connect, $id_region, $id_quarter) {
    $sql = "SELECT COUNT(id) FROM streets WHERE id_region = '$id_region' AND id_quarter= '$id_quarter'";
    $result = mysqli_query($connect,$sql);
    $rows = mysqli_fetch_row($result);
    return $rows[0];
}

function get_tests($connect){
	$query = "SELECT * FROM test";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	
	return $n;
}

//bot
function get_last_command($connect, $chat_id){
	$query = sprintf("SELECT * FROM textlog WHERE chat_id=%d order by id desc limit 1", (int)$chat_id);
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$last_data = mysqli_fetch_assoc($result);
	return $last_data;
}

//bot
function get_test_id($connect, $test_name){
	$query = sprintf("SELECT id FROM test WHERE test_name='%s'limit 1", $test_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['id'];
}
//bot
function get_test_date($connect, $test_name){
	$query = sprintf("SELECT test_date FROM test WHERE test_name='%s'limit 1", $test_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['test_date'];
}

//cron

function get_test_date_cron($connect){
	$query = sprintf("SELECT test_date FROM test limit 1");
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['test_date'];
}


function get_test_over_for_cron($connect){
	$query = sprintf("SELECT over_time FROM test limit 1");
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['over_time'];
}




//bot
function get_test_start($connect, $test_name){
	$query = sprintf("SELECT start_time FROM test WHERE test_name='%s'limit 1", $test_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['start_time'];
}





function get_test_over($connect, $test_name){
	$query = sprintf("SELECT over_time FROM test WHERE test_name='%s'limit 1", $test_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['over_time'];
}
//bot
function get_test_over_by_id($connect, $test_id){
	$query = sprintf("SELECT over_time FROM test WHERE id='%s'limit 1", $test_id);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$test_data = mysqli_fetch_assoc($result);
	return $test_data['over_time'];
}
//bot
function get_subjects($connect, $test_id){
	$subjects = [];
	$query = sprintf("SELECT id, subject_name FROM subjects WHERE test_id=%d", (int)$test_id);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	while($row = mysqli_fetch_assoc($result)) {
		$subjects[] = [$row['subject_name']];
	}
	return $subjects;
}



function offline_subjects_all($connect){
	$offline_subjects = [];
	$query = sprintf("SELECT id, offline_subject_name FROM offline_subjects");
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	while($row = mysqli_fetch_assoc($result)) {
		$offline_subjects[] = [$row['offline_subject_name']];
	}
	return $offline_subjects;
}

//bot
function get_subject($connect, $test_id, $subject_name){
	$query = sprintf("SELECT * FROM subjects WHERE subject_name='%s' and test_id=%d limit 1", $subject_name, $test_id);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$subject = mysqli_fetch_assoc($result);
	return $subject;
}

// check if isset this subject
function isset_agent_phone_number($connect, $agent_phone_number){
	$query = sprintf("SELECT * FROM agents WHERE phoneNumber='%s' limit 1", $agent_phone_number);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$phone_number = mysqli_fetch_assoc($result);
	if (isset($phone_number)) {
		return true;
	}else{
		return false;
	}
}

function isset_region_name($connect, $region_name){
	$query = sprintf("SELECT * FROM regions WHERE name_region='%s' limit 1", $region_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$region_name = mysqli_fetch_assoc($result);
	if (isset($region_name)) {
		return true;
	}else{
		return false;
	}
}

function isset_quarter_name($connect, $name_of_quarter, $id_region){
	$query = "SELECT * FROM quarters WHERE name_quarter='$name_of_quarter' AND id_region='$id_region'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$quarter_name = mysqli_fetch_assoc($result);
	if (isset($quarter_name)) {
		return true;
	}else{
		return false;
	}
}

function isset_street_name($connect, $name_of_street, $id_region, $id_quarter){
	$query = "SELECT * FROM streets WHERE name_street='$name_of_street' AND id_region='$id_region' AND id_quarter='$id_quarter'";
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$street_name = mysqli_fetch_assoc($result);
	if (isset($street_name)) {
		return true;
	}else{
		return false;
	}
}


// check if isset this offline subject

function get_subject_for_add_off($connect, $offline_subject_name){
	$query = sprintf("SELECT * FROM offline_subjects WHERE offline_subject_name='%s' limit 1", $offline_subject_name);
	$result = mysqli_query($connect, $query);
	if(!$result) return false;
	$offline_subject = mysqli_fetch_assoc($result);
	if (isset($offline_subject)) {
		return true;
	}else{
		return false;
	}
}


//bot
function get_result_test_id($connect, $user_id){
	$query = sprintf("SELECT * FROM result WHERE user_id=%d", (int)$user_id);
	$result = mysqli_query($connect, $query);

	$row = mysqli_fetch_assoc($result);


	if(mysqli_num_rows($result)>=1  && !empty($row['test_id'])){
		return 'isset_test';
   }else{
  		 return 'not_test';
    }
}


function get_result_all($connect){
	$query = "SELECT * FROM result";
	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$result_all = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$result_all[] = $row;
	}
	return $result_all;
}



function get_result_all_by($connect){
	// $query = "SELECT * FROM result  ORDER BY CAST(`score` AS UNSIGNED) DESC"; 
	$query = "SELECT * FROM result  ORDER BY score DESC"; 

	$result = mysqli_query($connect, $query);
	if(!$result)
		die(mysqli_error($connect));
	$n = mysqli_num_rows($result);
	$result_all = array();
	for ($i = 0; $i <$n; $i++){
		$row = mysqli_fetch_assoc($result);
		$result_all[] = $row;
	}
	return $result_all;
}

function update_object($connect, $id_object,
$phone_number_owner,
$ownerName,
$startingPrice,
$section,
$typeOfHouse,
$locations,
$commercial,
$area,
$region,
$quarter,
$street,
$number_of_rooms,
$floor,
$number_of_floors,
$balcony,
$bath_toilet,
$state_repair,
$type_of_walls,
$layout,
$type_of_building,
$end_face,
$furniture,
$technic,
$conditioner,
$plast_window,
$distance_from_road,
$underground,
$parking,
$elevator,
$roof,
$last_registration,
$registration,
$for_office,
$exclusive_contract,
$advertisement,
$agent_description
){
	$sql = "UPDATE main_tbl SET 
	phone_number_owner = '$phone_number_owner',
	ownerName = '$ownerName',
	startingPrice = '$startingPrice',
	section = '$section',
	typeOfHouse = '$typeOfHouse',
	locations = '$locations',
	commercial = '$commercial',
	area = '$area',
	region = '$region',
	quarter = '$quarter',
	street = '$street',
	number_of_rooms = '$number_of_rooms',
	floor = '$floor',
	number_of_floors = '$number_of_floors',
	balcony = '$balcony',
	bath_toilet = '$bath_toilet',
	state_repair = '$state_repair',
	type_of_walls = '$type_of_walls',
	layout = '$layout',
	type_of_building = '$type_of_building',
	end_face = '$end_face',
	furniture = '$furniture',
	technic = '$technic',
	conditioner = '$conditioner',
	plast_window = '$plast_window',
	distance_from_road = '$distance_from_road',
	underground = '$underground',
	parking = '$parking',
	elevator = '$elevator',
	roof = '$roof',
	last_registration = '$last_registration',
	registration = '$registration',
	for_office = '$for_office',
	exclusive_contract = '$exclusive_contract',
	advertisement = '$advertisement',
	agent_description = '$agent_description'	
	WHERE id='$id_object'";
	$result = mysqli_query($connect, $sql);
	if(!$result)
		die(mysqli_error($connect));
	return true;
}


// function admin_user($connect, $username, $pass){

// 	$query = "SELECT * FROM admin_user WHERE username = $username";
// 	$result = mysqli_query($connect, $query);

// 	if(!$result)
// 		die(mysqli_error($connect));
// 	$n = mysqli_num_rows($result);
// 	$admin_user = array();
// 	for ($i = 0; $i <$n; $i++){
// 		$row = mysqli_fetch_assoc($result);
// 		$users_all[] = $row;
// 	}
// 	return $users_all;

// }


function __cleanData($agent_phone_number) 
{
    return preg_replace('/\D/', '', $agent_phone_number);
}






?>