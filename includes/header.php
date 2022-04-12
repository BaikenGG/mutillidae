<?php
    $lSecurityLevel = $_SESSION["security-level"];

    switch ($lSecurityLevel){
        case "0": // This code is insecure
            $lSecurityLevelMessage = "Security Level: ".$lSecurityLevel." (Hosed)";
            break;
        case "1": // This code is insecure
            // DO NOTHING: This is equivalent to using client side security
            $lSecurityLevelMessage = "Security Level: ".$lSecurityLevel." (Client-Side Security)";
            break;

        case "2":
        case "3":
        case "4":
        case "5": // This code is fairly secure
            $lSecurityLevelMessage = "Security Level: ".$lSecurityLevel." (Secure)";
            break;
    }// end switch

	if($_SESSION['loggedin'] == "True"){

	    switch ($lSecurityLevel){
	   		case "0": // This code is insecure
	   		case "1": // This code is insecure
	   			// DO NOTHING: This is equivalent to using client side security
				$logged_in_user = $_SESSION['logged_in_user'];
			break;

	   		case "2":
	   		case "3":
	   		case "4":
	   		case "5": // This code is fairly secure
	   			// encode the entire message following OWASP standards
	   			// this is HTML encoding because we are outputting data into HTML
				$logged_in_user = $Encoder->encodeForHTML($_SESSION['logged_in_user']);
			break;
	   	}// end switch

	   	$lUserID = $_SESSION['uid'];

	   	$lUserAuthorizationLevelText = 'User: ';

	   	if ($_SESSION['is_admin'] == 'TRUE'){
	   		$lUserAuthorizationLevelText = 'User: ';
	   	}// end if

		$lAuthenticationStatusMessage =
			'' .
			$lUserAuthorizationLevelText . "" .
			'<span class="logged-in-user">'.$logged_in_user.'</span>'.
			'<a href="index.php?page=edit-account-profile.php&uid='.$lUserID.'">'.
		
            ' <li class="nav-item active"><img style="margin-top: 10;" src="images/edit-icon-20-20.png" /> </li></a>';
       
	} else {
		$logged_in_user = "anonymous";
		$lAuthenticationStatusMessage = "";
	}// end if($_SESSION['loggedin'] == "True")

	if ($_SESSION["EnforceSSL"] == "True"){
		$lEnforceSSLLabel = "Drop TLS";
	}else {
		$lEnforceSSLLabel = "Enforce TLS";
	}//end if

	$lHintsMessage = "Hints: ".$_SESSION["hints-enabled"];

?>

<html>
<head>
	<link rel="shortcut icon" href="./images/favicon.ico" type="image/x-icon" />

	<link rel="stylesheet" type="text/css" href="styles/global-styles.css" />
	<link rel="stylesheet" type="text/css" href="styles/ddsmoothmenu/ddsmoothmenu.css" />
	<link rel="stylesheet" type="text/css" href="javascript/jQuery/colorbox/colorbox.css" />
	<link rel="stylesheet" type="text/css" href="styles/gritter/jquery.gritter.css" />
	<link rel="stylesheet" type="text/css" href="styles/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="styles/css/styles_dashboard.css">


	<link href="https://fonts.googleapis.com/css?family=Titillium+Web:400,600" rel="stylesheet">
	<link href="https://unpkg.com/ionicons@4.5.5/dist/css/ionicons.min.css" rel="stylesheet">

	<script src="javascript/jQuery/jquery.js"></script>
	<script src="javascript/jQuery/colorbox/jquery.colorbox-min.js"></script>
	<script src="javascript/ddsmoothmenu/ddsmoothmenu.js"></script>
	<script src="javascript/gritter/jquery.gritter.min.js"></script>
	<script src="javascript/hints/hints-menu.js"></script>
	<script src="javascript/inline-initializers/jquery-init.js"></script>
	<script src="javascript/inline-initializers/ddsmoothmenu-init.js"></script>
	<script src="javascript/inline-initializers/populate-web-storage.js"></script>
	<script src="javascript/inline-initializers/gritter-init.js"></script>
	<script src="javascript/inline-initializers/hints-menu-init.js"></script>
</head>
<body>

<div  id="content-wrapper">
     
         <div id="sidebar-container" class="bg-primary border-primary">
         
            <div class="menu list-group-flush">
               <?php require_once 'main-menu.php'; ?>
         
            </div>

         </div>
         <!-- Fin sidebar --> 
         <div id="page-content-wrapper" class="w-100 bg-light-blue">
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
               <!-- nav bar-->
               <div class="container">
                  <!-- div container -->
                  <button class="btn btn-outline-primary" id="menu-toggle" style="margin-top: 10;
    				margin-bottom: 10;">Mostrar / esconder menu</button>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-Label="Toggle navigation"> <span class="navbar-toggler-icon"></span>
                  </button>
                  <!-- --> 
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                     <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item active">
                          
                           <a class="nav-link text-dark"href="index.php?page=home.php&popUpNotificationCode=HPH0">Home</a>
                            <li class="nav-item active">
			
			<?php
				if ($_SESSION['loggedin'] == 'True'){
					echo '<a class="nav-link text-dark" href="index.php?do=logout">Logout</a>';
				} else {
					echo '<a class="nav-link text-dark" href="index.php?page=login.php">Login/Register</a>';
				}// end if
			?>
		</li>
			 <li class="nav-item active">
			
			<?php
				if ($_SESSION['security-level'] == 0){
					echo '<a class="nav-link text-dark" href="index.php?do=toggle-hints&page='.$lPage.'">Toggle Hints</a> ';
				}// end if
			?>
			 <li class="nav-item active">
				</li>
			<a class="nav-link text-dark" href="index.php?do=toggle-security&page=<?php echo $lPage?>">Toggle Security</a>
				</li>
			 <li class="nav-item active">
			
			<a class="nav-link text-dark" href="index.php?do=toggle-enforce-ssl&page=<?php echo $lPage?>"><?php echo $lEnforceSSLLabel; ?></a>
			 	</li>
			 <li class="nav-item active">
			
			<a class="nav-link text-dark" href="set-up-database.php">Reset DB</a>
			 	</li>
			 <li class="nav-item active">
			
			<a class="nav-link text-dark" href="index.php?page=show-log.php">View Log</a>
			 	</li>
			 <li class="nav-item active">
			
			<a class="nav-link text-dark" href="index.php?page=captured-data.php">View Captured Data</a>
                        </li>
	 				<li class="nav-item active">
			<a class="nav-link text-dark" ><?php echo $lAuthenticationStatusMessage ?></a>
			
                        </li>
                     </ul>
                  </div>
               </div>
               <!-- fin div container --> 
            </nav>
          

      <!-- Abrir / cerrar menu --> 
      <script>
       $("#menu-toggle").click(function (e){
        e.preventDefault();
        $("#content-wrapper").toggleClass("toggled");
       });
     </script>
</body>