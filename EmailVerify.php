<?php
	// Pass an email to this function, it will pass back a string if the email is invalid, false if it is good.
		function Validate_Email($Email)
		{
			$At_Position = strrpos($Email, "@");
			if(!$At_Position) return "no @ symbol found";
			$URL  = substr($Email, $At_Position+1);
			$User = substr($Email, 0, $At_Position);
			if(!$User || strlen($User) > 64)                     return "before @ is too long"; // The username part of an email can't be over 64 chars.
			if(!$URL  || strlen($URL)  > 253)                    return "after @ is too long";  // The combined length of an email can't be over 255 chars.
			if($User[0] == '.' || $User[strlen($User)-1] == '.') return "before @ can't end with a period";
			if(strpos($User, ".."))                              return "before @ has consecutive periods";
			if(!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $URL))      return "after @ has invalid characters";
			if(preg_match('/\\.\\./', $URL))                     return "after @ has consecutive periods";
			if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$User)) && !preg_match('/^"(\\\\"|[^"])+"$/', str_replace("\\\\","",$User)))
																 return "before @ has invalid characters";
			if(!(checkdnsrr($URL,"MX") || checkdnsrr($URL,"A"))) return "after @ isn't a real website";
			return false;
		}

	// Commence testing using different invalid emails.
		$Test_Emails = array("invaid".str_repeat("d", 64)."@email.com",
							 "invalid@email".str_repeat("l", 255).".com",
							 "invalid.@email.com",
							 "inval..id@email.com",
							 "invalid@web$!te.com",
							 "invalid@email..com",
							 "inv[a]lid@email.com",
							 "invalid@jibberishwebsitename.com",
							 "valid@email.com");
		foreach($Test_Emails as $Email){
			$Valid = Validate_Email($Email);
			if($Valid) echo "Error: (".$Email.") ".$Valid."<br>\n";
			else echo "Success: (".$Email.") This is a valid email!<br>\n";
		}
?>