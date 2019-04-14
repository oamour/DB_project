<?php

$value = json_decode(file_get_contents('php://input'));

//DEBUG
/*if($value == null){
	$value = (object)[];
	$value->email = "student_email@gmail.com";
	$value->parent_email = "nickbishop97@gmail.com";
	$value->pass = "testtest";
	$value->pass_confirm = "testtest";
	$value->role = "Both Mentee and Mentor";
	$value->grade = "Sophomore";
	$value->name = "Nick Bishop";
	$value->phone = "7777777777";
	$value->city = "Nahant";
	$value->state = "MA";
}*/

# Submission handler
if($value != NULL){
    $myconnection = mysqli_connect('localhost', 'root', '') 
        or die ('Could not connect: ' . mysql_error());
        
    $mydb = mysqli_select_db ($myconnection, 'db2') or die ('Could not select database');
    
    mysqli_query($myconnection, "START TRANSACTION");
    
    # Receive data from POST header
    $student_email = $value->email;
    $parent_email = $value->parent_email;
    $pass = $value->pass;
    $pass_confirm = $value->pass_confirm;
    $role = $value->role;
    $name = $value->name;
    $phone_num = $value->phone;
    $city = $value->city;
    $state = $value->state;
	
	switch ($value->grade) {
		case "Freshman":
			$grade = 1;
			break;
		case "Sophomore":
			$grade = 2;
			break;
		case "Junior":
			$grade = 3;
			break;
		case "Senior":
			$grade = 4;
			break;
		default:
			$grade = 0;
			break;
	}
    
    $input_valid = 1;

    # Verify student email address
    # $student_email_valid = preg_match("/[^@]+@[^@]+\.[^@]+/", $student_email);
    $email_regex = "/^[^\s@]+@([^\s@]+\.[^\s@]+[^\s@\.]|[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/";
    $student_email_valid = preg_match($email_regex, $student_email);
    if($student_email_valid == 0) {
        $input_valid = 0;
    } else {
        # Check whether email address exists in database
        $result = mysqli_query($myconnection, "SELECT * FROM accounts WHERE username = '" . $student_email . "'");
        if($result->num_rows > 0) {
            $student_email_already_exists = 1;
            $input_valid = 0;
        }
    }
    
    # Verify parent email address
    $parent_email_valid = preg_match($email_regex, $parent_email);
    if($parent_email_valid == 0) {
        $input_valid = 0;
    } else {
        # Check whether parent with given email address exists in database
        $result = mysqli_query($myconnection, "SELECT userID FROM accounts WHERE username = '" . $parent_email . "'");
        
        if($result->num_rows > 0) {
            # Email exists, check that associated account is a parent
            $row = $result->fetch_row();
            $parent_id = $row[0];
            $result = mysqli_query($myconnection, "SELECT isParent FROM users WHERE userID = " . $parent_id);
            
            if($result->num_rows > 0) {
                $row = $result->fetch_row();
                $is_parent = $row[0];
                
                if($is_parent == 0) {
                    $parent_email_is_parent = 1;
                    $input_valid = 0;
                }
            } else {
                # Should never get here
                die ("No userid matching account ID#" . $row[0] . "; " . mysqli_error($myconnection)); 
            }
        } else {
            $parent_email_is_parent = 1;
            $input_valid = 0;
        }
    }
    
    # Verify password (must be at least 8 characters
    if(strlen($pass) < 8) {
        $input_valid = 0;
        $pass_error_code = 1; 
    } elseif($pass != $pass_confirm) {
        $input_valid = 0;
        $pass_error_code = 2;
    }
    
    # Verify phone number
    $phone_num_sanitized = preg_replace("/[^\d]/","", $phone_num);
    $phone_num_matches = preg_match("/^\d{10}$/", $phone_num_sanitized);
    if($phone_num_matches == 0) {
        $input_valid = 0;
    }
    
    # add user to database if valid
    if($input_valid == 1) {
        # create user
        $query = "INSERT INTO users(name, email, phone, gradeLevel, isParent, isStudent, city, state) VALUES('" . $name . "', '" . $student_email . "', '" . $phone_num_sanitized . "', " . $grade . ", 0, 1, '" . $city . "', '" . $state . "')";
        $result = mysqli_query($myconnection, $query);
        $new_user_id = mysqli_insert_id($myconnection);
        
        # create account for user
        if($result != false) {
            $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);
            $query = "INSERT INTO accounts(userID, username, hash) VALUES(" . $new_user_id . ", '" . $student_email . "', '" . $hashed_pass . "')";
            $result = mysqli_query($myconnection, $query);
        }
        
        # create mentee and mentor listings if applicable
        if (($role == "Mentee" or $role == "Both Mentee and Mentor") and $result != false) {
            $query = "INSERT INTO mentees(menteeID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
            $result = mysqli_query($myconnection, $query);
        }
        if (($role == "Mentor" or $role == "Both Mentee and Mentor") and $result != false) {
            $query = "INSERT INTO mentors(mentorID, userID) VALUES(" . $new_user_id . ", " . $new_user_id . ")";
            $result = mysqli_query($myconnection, $query);
        }
        
        # create parent listing
        if(isset($parent_id) and $result != false) {
            $query = "INSERT INTO parentChild(parentID, childID) VALUES(" . $parent_id . ", " . $new_user_id . ")";
            $result = mysqli_query($myconnection, $query);
        }
        
        # if failed to create account, remove user data
        if($result == false) {
            # Close connection rolls back transaction
            mysqli_query($myconnection, "ROLLBACK");
            mysqli_close($myconnection);
			$success = 0;
        } else {
            # User creation succeeded; commit transaction
            mysqli_query($myconnection, "COMMIT");
            mysqli_close($myconnection);
			$success = 1;
        }
    } else {
        mysqli_query($myconnection, "ROLLBACK");
        mysqli_close($myconnection);
    }
  
	# ERROR CODES:
	# 1 - email not valid
	# 2 - email already registered
	# 3 - password does not meet requirements
	# 4 - password confirmation failure
	# 5 - phone number invalid
	# 6 - Database error
	# 7 - Parent email does not exist
	
	$result_json = (object)[];
	if(isset($success) and $success == 1) {
		$result_json->result = "0";
	} elseif (isset($student_email_valid) and $student_email_valid == 0) {
		$result_json->result = "1";
	} elseif (isset($parent_email_is_parent) and $parent_email_is_parent == 1) {
		$result_json->result = "7";
	} elseif (isset($student_email_already_exists) and $student_email_already_exists == 1) {
		$result_json->result = "2";
	} elseif (isset($pass_error_code) and $pass_error_code == 1) {
		$result_json->result = "3";
	} elseif (isset($pass_error_code) and $pass_error_code == 2) {
		$result_json->result = "4";
	} elseif (isset($phone_num_matches) and $phone_num_matches == 0) {
		$result_json->result = "5";
	} elseif (isset($success) and $success == 0) {
		$result_json->result = "6";
	} else {
		$result_json->result = "-1";
	}
	
	header('Content-Type: application/json');
	$encoded = json_encode($result_json);
	
	echo $encoded;
}
?>