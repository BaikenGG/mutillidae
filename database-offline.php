<?php
	/* ------------------------------------------------------
	 * INCLUDE CLASS DEFINITION PRIOR TO INITIALIZING
	 * ------------------------------------------------------ */
    require_once 'classes/MySQLHandler.php';

    /* Read ldap configuration file and populate class parameters */
    require_once(__SITE_ROOT__ . '/includes/ldap-config.inc');

	$lErrorMessage = "";
	$lDatabaseHostResolvedIP = "";
	$lDatabasePortScanMessage = "";
	$lDatabasePingResult = "";
	$lDatabaseTracerouteResult = "";
	$lDatabaseHost = MySQLHandler::$mMySQLDatabaseHost;
	$lDatabaseUsername = MySQLHandler::$mMySQLDatabaseUsername;
	$lDatabasePassword = MySQLHandler::$mMySQLDatabasePassword;
	$lDatabaseName = MySQLHandler::$mMySQLDatabaseName;
	$lDatabasePort = MySQLHandler::$mMySQLDatabasePort;

	$lLDAPHostResolvedIP = "";
	$lLDAPPortScanMessage = "";
	$lLDAPPingResult = "";
	$lLDAPTracerouteResult = "";
	$lLDAPHost = LDAP_HOST;
	$lLDAPPort = LDAP_PORT;
	$lLDAPBaseDN = LDAP_BASE_DN;
	$lLDAPBindDN = LDAP_BIND_DN;
	$lLDAPBindPassword = LDAP_BIND_PASSWORD;
	$lSocketErrorCode = "";
	$lSocketErrorMessage = "";

	try{
	    $lDatabaseHostResolvedIP = gethostbyname($lDatabaseHost);
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lDatabasePingResult = shell_exec("ping $lDatabaseHost -c 1");
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lDatabaseTracerouteResult = shell_exec("traceroute $lDatabaseHost 2>&1");
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lTracerouteTCPResult = shell_exec("traceroute --tcp -p $lDatabasePort $lDatabaseHost 2>&1");
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lWaitTimeoutInSeconds = 2;
	    if($fp = fsockopen($lDatabaseHost,$lDatabasePort,$lSocketErrorCode,$lSocketErrorMessage,$lWaitTimeoutInSeconds)){
	        $lDatabasePortScanMessage = "The database is reachable. Connected to database host " . $lDatabaseHost . " on port " . $lDatabasePort;
	    } else {
	        $lDatabasePortScanMessage = "Cound not connect to database host $lDatabaseHost on port $lDatabasePort. The hostname or port may be incorrect, the server offline, the service is down, or a firewall is blocking the connection. $lSocketErrorCode - $lSocketErrorMessage";
	    } // end if

	    if (is_resource($fp)){
	        fclose($fp);
	    } // end if

	}catch (Exception $e){
	    // do nothing
	} //end try

	try {
		MySQLHandler::databaseAvailable();
	} catch (Exception $e) {
		$lErrorMessage = $e->getMessage();
	}// end try MySQLHandler::databaseAvailable()

	try{
	    $lLDAPHostResolvedIP = gethostbyname($lLDAPHost);
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lLDAPPingResult = shell_exec("ping $lLDAPHost -c 1");
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lLDAPTracerouteResult = shell_exec("traceroute $lLDAPHost 2>&1");
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lTracerouteTCPResult = shell_exec("traceroute --tcp -p $lLDAPPort $lLDAPHost 2>&1");
	}catch (Exception $e){
	    // do nothing
	} //end try

	try{
	    $lWaitTimeoutInSeconds = 2;
	    if($fp = fsockopen($lLDAPHost,$lLDAPPort,$lSocketErrorCode,$lSocketErrorMessage,$lWaitTimeoutInSeconds)){
	        $lLDAPPortScanMessage = "The LDAP service is reachable. Connected to LDAP service host " . $lLDAPHost . " on port " . $lLDAPPort;
	    } else {
	        $lLDAPPortScanMessage = "Cound not connect to LDAP service host $lLDAPHost on port $lLDAPPort. The hostname or port may be incorrect, the server offline, the service is down, or a firewall is blocking the connection. $lSocketErrorCode - $lSocketErrorMessage";
	    } // end if

	    if (is_resource($fp)){
	        fclose($fp);
	    } // end if
	}catch (Exception $e){
	    // do nothing
	} //end try

	if (session_status() == PHP_SESSION_NONE){
	    session_start();
	}// end if

	$lSubmitButtonClicked = isSet($_REQUEST["database-offline-php-submit-button"]);

	if ($lSubmitButtonClicked) {
		$_SESSION["UserOKWithDatabaseFailure"] = "TRUE";
		header("Location: index.php", true, 302);
	}//end if

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="styles/css/styles_extras.css">

	
	<title>Database Offline</title>
</head>



        <div class="row justify-content-center pt-5 mt-5 m-1" style="width: 50%; margin-top: 50; margin-left: 25%;  display: flex;
  justify-content: center; text-align: center;">
            <div class="col-md-6 col-sm-8 col-xl-4 col-lg-5 formulario">
<div class="form-group text-center pt-3">
                        <h1 class="text-light">SIN CONEXIÓN EN LA BD</h1>
                   <h1>
		<?php echo MySQLHandler::$mMySQLDatabaseHost ?>
	</h1>
</div>

<table>
	<tr>
		<td>
			<ol>
				<li><a class="label" href="set-up-database.php" style="color:white;">Click here</a> to attempt to setup the database. Sometimes this works.</li>
				<li>Be sure the username and password to MySQL is the same as configured in includes/database-config.inc</li>
				<li>Be aware that MySQL disables password authentication for root user upon installation or update in some systems. This may happen even for a minor update. Please check the username and password to MySQL is the same as configured in includes/database-config.inc</li>
				<li>A <a style="font-weight: bold; color:white;" href="https://www.youtube.com/watch?v=sG5Z4JqhRx8" target="_blank">video is available</a> to help reset MySQL root password</li>
				<li>Check the error message below for more hints</li>
				<li>If you think this message is a false-positive, you can opt-out of these warnings below</li>
			</ol>

					<input style="margin-bottom:20;"class="btn btn-block ingresar"  type="submit"  onclick="mostrarDesc();" value="Mostrar Descripción" />
		
		</td>
					
	</tr>

	<tr>
		<td style="width:700px; display:none;" id="Visible">
            <div class="warning-message">Database Diagnostics Information</div>
            <div>&nbsp;</div>
            <div><span class="label">Database Error message: </span><?php echo $lErrorMessage; ?></div>
            <div>&nbsp;</div>
            <div><span class="label">Database host: </span><?php echo $lDatabaseHost; ?></div>
            <div><span class="label">Database post: </span><?php echo $lDatabasePort; ?></div>
            <div><span class="label">Database username: </span><?php echo $lDatabaseUsername; ?></div>
            <div><span class="label">Database password: </span><?php echo $lDatabasePassword; ?></div>
            <div><span class="label">Database name: </span><?php echo $lDatabaseName; ?></div>
            <div>&nbsp;</div>
            <div><span class="label">IP resolved from database hostname: </span><?php echo $lDatabaseHostResolvedIP; ?></div>
            <div>&nbsp;</div>
            <div><span class="label">Ping database results: </span><pre><?php echo $lDatabasePingResult; ?></pre></div>
            <div><span class="label">Traceroute database results: </span><pre><?php echo $lDatabaseTracerouteResult; ?></pre></div>
            <div><span class="label">Port scan database results: </span><?php echo $lDatabasePortScanMessage; ?></div>
		</td>
	</tr>
	<tr>
		<td style="width:700px; display:none; " id="Visible1">
            <div>&nbsp;</div>
            <div class="warning-message">LDAP Diagnostics Information</div>
            <div>&nbsp;</div>
            <div><span class="label">LDAP host: </span><?php echo $lLDAPHost; ?></div>
            <div><span class="label">LDAP post: </span><?php echo $lLDAPPort; ?></div>
            <div><span class="label">LDAP username: </span><?php echo $lLDAPBindDN; ?></div>
            <div><span class="label">LDAP password: </span><?php echo $lLDAPBindPassword; ?></div>
            <div><span class="label">LDAP base DN: </span><?php echo $lLDAPBaseDN; ?></div>
            <div>&nbsp;</div>
            <div><span class="label">IP resolved from LDAP service hostname: </span><?php echo $lLDAPHostResolvedIP; ?></div>
            <div>&nbsp;</div>
            <div><span class="label">Ping LDAP service results: </span><pre><?php echo $lLDAPPingResult; ?></pre></div>
            <div><span class="label">Traceroute LDAP service results: </span><pre><?php echo $lLDAPTracerouteResult; ?></pre></div>
            <div><span class="label">Port scan LDAP service results: </span><?php echo $lLDAPPortScanMessage; ?></div>
		</td>
	</tr>
</table>

<div>
	<form 	action="database-offline.php"
			method="post"
			enctype="application/x-www-form-urlencoded"
			id="idDatabaseOffline">
		<table>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td colspan="2" class="form-header">Opt out of database warnings</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="label">You can opt out of database connection warnings for the remainder of this session</td>
			</tr>
			<tr>

			</tr>
				<td colspan="2"  >
					<input style="text-align:center; margin-top:20;" name="database-offline-php-submit-button" class="btn btn-block ingresar" type="submit" value="Recargar pagina" />
				</td>
		</table>
	</form>
</div>
<script type="text/javascript">
		function mostrarDesc() {
			document.getElementById('Visible').style.display = 'block';
			document.getElementById('Visible1').style.display = 'block';
	
		}
</script>