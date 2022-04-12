<?php 
	try{
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure
				$lEnableHTMLControls = FALSE;
    			$lFormMethod = "GET";
				$lEnableJavaScriptValidation = FALSE;
				$lProtectAgainstMethodTampering = FALSE;
				$lEncodeOutput = FALSE;
				break;
    		
    		case "1": // This code is insecure
				$lEnableHTMLControls = TRUE;
    			$lFormMethod = "GET";
				$lEnableJavaScriptValidation = TRUE;
				$lProtectAgainstMethodTampering = FALSE;
				$lEncodeOutput = FALSE;
			break;
	    		
			case "2":
			case "3":
			case "4":
    		case "5": // This code is fairly secure
				$lEnableHTMLControls = TRUE;
    			$lFormMethod = "POST";
				$lEnableJavaScriptValidation = TRUE;
				$lProtectAgainstMethodTampering = TRUE;
				$lEncodeOutput = TRUE;
			break;
    	}//end switch

    	$lFormSubmitted = FALSE;
		if (isset($_POST["user-info-php-submit-button"]) || isset($_REQUEST["user-info-php-submit-button"])) {
			$lFormSubmitted = TRUE;
		}// end if
		
		if ($lFormSubmitted){
	    	if ($lProtectAgainstMethodTampering) {
	   			$lUserInfoSubmitButton = $_POST["user-info-php-submit-button"];
				$lUsername = $_POST["username"];
				$lPassword = $_POST["password"];
	    	}else{
    			$lUserInfoSubmitButton = $_REQUEST["user-info-php-submit-button"];
				$lUsername = $_REQUEST["username"];
				$lPassword = $_REQUEST["password"];
	    	}// end if $lProtectAgainstMethodTampering
		}// end if $lFormSubmitted

   	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, $lQueryString);
   	}// end try;
?>

<script type="text/javascript">
	<?php 
	if($lEnableJavaScriptValidation){
		echo "var lValidateInput = \"TRUE\"" . PHP_EOL;
	}else{
		echo "var lValidateInput = \"FALSE\"" . PHP_EOL;
	}// end if		
	?>
			
	function onSubmitOfForm(/*HTMLFormElement*/ theForm){
		try{
			var lUnsafeCharacters = /[`~!@#$%^&*()-_=+\[\]{}\\|;':",./<>?]/;

			if(lValidateInput == "TRUE"){
				if (theForm.username.value.length > 15 || 
					theForm.password.value.length > 15){
						alert('Username too long. We dont want to allow too many characters.\n\nSomeone might have enough room to enter a hack attempt.');
						return false;
				}// end if
				
				if (theForm.username.value.search(lUnsafeCharacters) > -1 || 
					theForm.password.value.search(lUnsafeCharacters) > -1){
						alert('Dangerous characters detected. We can\'t allow these. This all powerful blacklist will stop such attempts.\n\nMuch like padlocks, filtering cannot be defeated.\n\nBlacklisting is l33t like l33tspeak.');
						return false;
				}// end if
			}// end if(lValidateInput)
			
			return true;
		}catch(e){
			alert("Error: " + e.message);
		}// end catch
	}// end function onSubmitOfForm(/*HTMLFormElement*/ theForm)
	
</script>

<link rel="stylesheet" href="styles/css/styles_login.css">


<div class="container" style="height: 50%;">
        <div class="row justify-content-center pt-5 mt-5 m-1">
            <div class="col-md-6 col-sm-8 col-xl-4 col-lg-5 formulario">


<form 	action="./index.php?page=user-info.php"
		method="<?php echo $lFormMethod; ?>" 
		enctype="application/x-www-form-urlencoded"
		onsubmit="return onSubmitOfForm(this);"
>
	<input type="hidden" name="page" value="user-info.php" />	
	<table>
		<tr id="id-bad-cred-tr" style="display: none;">
			<td colspan="2" class="error-message">
				Authentication Error: Bad user name or password
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
					<div class="form-group text-center pt-3">
                        <h1 class="text-light">CONSULTA DE USUARIOS</h1>
                    </div>
		</tr>
		<tr><td></td></tr>
		<tr>
			
			<td>
				<input type="text" name="username" size="20" autofocus="autofocus" class="form-control" placeholder="Ingrese Usuario"
					<?php
						if ($lEnableHTMLControls) {
							echo('minlength="1" maxlength="20" required="required"');
						}// end if
					?>
				/>
			</td>
		</tr>
		<tr>

			<td>
				<input type="password" name="password" size="20" class="form-control" placeholder="Ingrese Contraseña"
					<?php
						if ($lEnableHTMLControls) {
							echo('minlength="1" maxlength="20" required="required"');
						}// end if
					?>
				/>
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input name="user-info-php-submit-button" class="btn btn-block ingresar" type="submit" value="Consultar Detalles" />
			</td>
		</tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center; font-style: italic;">
				No tienes un usuario? <a href="?page=register.php">Registrate</a>
			</td>
		</tr>
	</table>	
</form>
</div>
</div>
</div>
<br>  <br>
<?php
	if ($lFormSubmitted){
		try {
			try {
				$LogHandler->writeToLog("Recieved request to display user information for: " . $lUsername);					
			} catch (Exception $e) {
				//do nothing
			}// end try

	    			
			$lQueryResult = $SQLQueryHandler->getUserAccount($lUsername, $lPassword);
	    	
	   		$lResultsFound = FALSE;
	   		$lRecordsFound = 0;
	   		if (isset($lQueryResult->num_rows)){
				if ($lQueryResult->num_rows > 0) {
	   				$lResultsFound = TRUE;
	   				$lRecordsFound = $lQueryResult->num_rows;
				}//end if
			}//end if

    		/* Print out table header */
			if($lEncodeOutput){
				$lUsername = $Encoder->encodeForHTML($lUsername);
			}// end if

			echo '	
			<table  class="table table-striped table-bordered" style="width:70%; margin-top: 50; margin-bottom: 80;">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Contraseña</th>
                <th>Firma</th>

            </tr>
        </thead>

 
        <div class="report-header">
						Results for &quot;<span style="color:#770000;">'
						.$lUsername.
						'</span>&quot;.'.$lRecordsFound.' records found.
					</div>';

    		/* Print out results */
			if ($lResultsFound){
			    while($row = $lQueryResult->fetch_object()){
			    	try {
						$LogHandler->writeToLog("user-info.php: Displayed user-information for: " . $row->username);				
			    	} catch (Exception $e) {
			    		// do nothing
			    	}//end try
					
					if(!$lEncodeOutput){
						$lUsername = $row->username;
						$lPassword = $row->password;
						$lSignature = $row->mysignature;
					}else{
						$lUsername = $Encoder->encodeForHTML($row->username);
						$lPassword = $Encoder->encodeForHTML($row->password);
						$lSignature = $Encoder->encodeForHTML($row->mysignature);			
					}// end if
					
					echo "<td>{$lUsername}</td>";
					echo "<td>{$lPassword}</td>";
					echo "<td>{$lSignature}</td>";
					echo "</table>";

				}// end while
	
			} else {
				echo '<script>document.getElementById("id-bad-cred-tr").style.display=""</script>';
			}// end if ($lResultsFound)
    	} catch (Exception $e) {
			echo $CustomErrorHandler->FormatError($e, "Error attempting to display user information");
       	}// end try;
    	
	}// end if (isset($_POST)) 
?>