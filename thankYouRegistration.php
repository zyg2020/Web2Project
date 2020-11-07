<?php
	if ($_SERVER["REQUEST_METHOD"] === "POST" ) {
		$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$confirmedPassword = filter_input(INPUT_POST, 'confirmedPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$branchOffice = filter_input(INPUT_POST, 'branchOffice', FILTER_SANITIZE_FULL_SPECIAL_CHARS);	
		$email=filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
		


		$errorInfo = [
			'username' => [
				'value' => $username,
				'errorMessage'=> 'Username Required'],
			'password' => [
				'value' => $password,
				'errorMessage'=> 'Password Required'],
			'confirmedPassword' => [
				'value' => $confirmedPassword,
				'errorMessage'=> 'Confirmed Password Required'],
			'name' => [
				'value' => $name,
				'errorMessage'=> 'Name Required'],
			'email' => [
				'value' => $email,
				'errorMessage'=> 'Email Required']];

		if (strlen($password) > 0 && strlen($confirmedPassword) > 0) {
			if ($password !== $confirmedPassword) {
				$errorInfo['confirmedPassword']['value'] = false;
				$errorInfo['confirmedPassword']['errorMessage'] = 'Please input the same password in the second time.';
			}
		}else{
			$errorInfo['confirmedPassword']['errorMessage'] = 'Please fill out password fields';
		}

		$output = [];
		foreach ($errorInfo as $key => $value) {
			if (!$value['value']) {
				array_push($output, [$key=>$value['errorMessage']]);
			}
			
		}
		echo 'post';
	}
?>