<?php 
	require_once (__ROOT__.'/classes/CSRFTokenHandler.php');
	$lCSRFTokenHandler = new CSRFTokenHandler("owasp-esapi-php/src/", $_SESSION["security-level"], "register-user");

	switch ($_SESSION["security-level"]){
		case "0": // This code is insecure
			// DO NOTHING: This is equivalent to using client side security
			$lEnableJavaScriptValidation = FALSE;
			$lEnableHTMLControls = FALSE;
			$lProtectAgainstMethodTampering = FALSE;
			$lEncodeOutput = FALSE;
			break;
	
		case "1": // This code is insecure
			// DO NOTHING: This is equivalent to using client side security
			$lEnableJavaScriptValidation = TRUE;
			$lEnableHTMLControls = TRUE;
			$lProtectAgainstMethodTampering = FALSE;
			$lEncodeOutput = FALSE;
			break;
	
		case "2":
		case "3":
		case "4":
		case "5": // This code is fairly secure
			/*
			 * Concerning SQL Injection, use parameterized stored procedures. Parameterized
			 * queries is not good enough. You cannot use least privilege with queries.
			 */
			$lEnableJavaScriptValidation = TRUE;
			$lEnableHTMLControls = TRUE;
			$lProtectAgainstMethodTampering = TRUE;
			$lEncodeOutput = TRUE;
			break;
	}// end switch

	$lNewCSRFTokenForNextRequest = $lCSRFTokenHandler->generateCSRFToken();
	$lFormSubmitted = isset($_REQUEST["register-php-submit-button"]);
?>




<?php
	if ($lFormSubmitted){
		
		try {					
			$lValidationFailed = false;
					
	   		if ($lProtectAgainstMethodTampering) {
   				$lUsername = $_POST["username"];
				$lPassword = $_POST["password"];
				$lConfirmedPassword = $_POST["confirm_password"];
				$lUserSignature = $_POST["my_signature"];
				$lPostedCSRFToken = $_POST['csrf-token'];
	   		}else{
	   			$lUsername = $_REQUEST["username"];
				$lPassword = $_REQUEST["password"];
				$lConfirmedPassword = $_REQUEST["confirm_password"];
				$lUserSignature = $_REQUEST["my_signature"];
				$lPostedCSRFToken = $_REQUEST['csrf-token'];
	   		}//end if
	   		
	   		if ($lEncodeOutput){
	   			$lUsernameText = $Encoder->encodeForHTML($lUsername);
	   		}else{
	   			//allow XSS by not encoding
	   			$lUsernameText = $lUsername;
	   		}//end if
	   		
			$LogHandler->writeToLog("Attempting to add account for: " . $lUsername);				
		   	
			if (!$lCSRFTokenHandler->validateCSRFToken($lPostedCSRFToken)){
				throw (new Exception("Security Violation: Cross Site Request Forgery attempt detected.", 500));
			}// end if
					
		   	if (strlen($lUsername) == 0) {
		   		$lValidationFailed = TRUE;
				echo '<h2 class="error-message">Username cannot be blank</h2>';
		   	}// end if
					
		   	if ($lPassword != $lConfirmedPassword ) {
				$lValidationFailed = TRUE;
		   		echo '<h2 class="error-message">Passwords do not match</h2>';
		   	}// end if
						   	
		   	if (!$lValidationFailed){					
		   		$lRowsAffected = $SQLQueryHandler->insertNewUserAccount($lUsername, $lPassword, $lUserSignature);
				echo '<h2 class="success-message">Account created for ' . $lUsernameText .'. '.$lRowsAffected.' rows inserted.</h2>';
				$LogHandler->writeToLog("Added account for: " . $lUsername);
		   	}// end if (!$lValidationFailed)
			
		} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, "Failed to add account");
			$LogHandler->writeToLog("Failed to add account for: " . $lUsername);			
		}// end try
			
	}// end if $lFormSubmitted
?>

<script type="text/javascript">
<!--
	<?php 
		if($lEnableJavaScriptValidation){
			echo "var lValidateInput = \"TRUE\"" . PHP_EOL;
		}else{
			echo "var lValidateInput = \"FALSE\"" . PHP_EOL;
		}// end if		
	?>

	function onSubmitOfForm(/*HTMLFormElement*/ theForm){
		try{
			if(lValidateInput == "TRUE"){
				var lUnsafeCharacters = /[`~!@#$%^&*()-_=+\[\]{}\\|;':",./<>?]/;
				if (theForm.username.value.length > 15 || 
					theForm.password.value.length > 15){
						alert('Username too long. We dont want to allow too many characters.\n\nSomeone might have enough room to enter a hack attempt.');
						return false;
				};// end if
				
				if (theForm.username.value.search(lUnsafeCharacters) > -1 || 
					theForm.password.value.search(lUnsafeCharacters) > -1){
						alert('Dangerous characters detected. We can\'t allow these. This all powerful blacklist will stop such attempts.\n\nMuch like padlocks, filtering cannot be defeated.\n\nBlacklisting is l33t like l33tspeak.');
						return false;
				};// end if
			};// end if(lValidateInput)
			
			return true;
		}catch(e){
			alert("Error: " + e.message);
		};// end catch
	};// end function onSubmitOfLoginForm(/*HTMLFormElement*/ theForm)
//-->
</script>
<link rel="stylesheet" href="styles/css/styles_login.css">


<div id="id-registration-form-div" style="height: 100%;" >
	<div class="container">
        <div class="row justify-content-center pt-5 mt-5 m-1">
            <div class="col-md-6 col-sm-8 col-xl-4 col-lg-5 formulario">
	<form	action="index.php?page=register.php" method="post" enctype="application/x-www-form-urlencoded"
			onsubmit="return onSubmitOfForm(this);"
			>
		<input name="csrf-token" type="hidden" value="<?php echo $lNewCSRFTokenForNextRequest; ?>" />
		<table>
			<tr><td>&nbsp;</td></tr>
			<tr>
			<div class="form-group text-center pt-3">
                        <h1 class="text-light">REGISTRO</h1>
                    </div>
			</tr>
		
			<tr>
			
				<td>
					<input type="text" name="username" size="20" autofocus="autofocus" class="form-control" placeholder="Ingrese su Usuario"
						<?php
							if ($lEnableHTMLControls) {
								echo('minlength="1" maxlength="15" required="required"');
							}// end if
						?>
					/>
				</td>
			</tr>
			<tr>
			
				<td>
					<input type="password" name="password" size="10" class="form-control" placeholder="Ingrese su contraseña"
						<?php
							if ($lEnableHTMLControls) {
								echo('minlength="1" maxlength="15" required="required"');
							}// end if
						?>
					/>
					&nbsp;
					
					

				</td>
			</tr>
			<tr>
		
				<td>
					<input type="password" name="confirm_password" class="form-control" placeholder="Confirme su contraseña" size="10"
						<?php
							if ($lEnableHTMLControls) {
								echo('minlength="1" maxlength="15" required="required"');
							}// end if
						?>
					/>
				</td>
			</tr>
			<tr>
				
				<td>
					<textarea rows="3" cols="50" class="form-control" name="my_signature" placeholder="Escriba su firma" style="width:300PX;"   size="0"
						<?php
							if ($lEnableHTMLControls) {
								echo('minlength="1" maxlength="100" required="required"');
							}// end if
						?>
						>
					</textarea>
				</td>
			</tr>			
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="register-php-submit-button"  class="btn btn-block ingresar" type="submit" value="Crear Usuario" />
						<a href="index.php?page=password-generator.php&username=<?php echo $logged_in_user ?>" target="_blank" class="btn btn-block ingresar" style="width:300PX; ">Generar contraseña</a>
				</td>
			
					
		
			</tr>
			<tr><td>&nbsp;</td></tr>
		</table>
	</form>
	</div>
</div>
</div>
</div>
</div>

