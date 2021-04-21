<?php
function ioncube_event_handler($err_code, $params)
{
  echo '<!DOCTYPE html>
  <html lang="en">
   <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>License Error - Appkodes</title>
	
	
	<!---------------------------------------E O JS------------------------------------------>	
   </head>
	
	
   <body>
	<div class="container">
		<div class="license-container">
			<div class="error-info">
				<div class="error-content">
					<div class="key-icon"></div>
					<div class="info-txt"><span>';

  switch ($err_code){
	case ION_CORRUPT_FILE:
	  echo "Some of your file looks corrupted.";
	  break;
	case ION_EXPIRED_FILE:
	  echo "Looks like the files are expired.";
	  break;
	case ION_NO_PERMISSIONS:
	  echo "Permission denied to access the files.";
	  break;
	case ION_CLOCK_SKEW:
	  echo "Looks like server time issue";
	  break;
	case ION_LICENSE_NOT_FOUND:
	  echo "A license could not be found.";
	  break;
	case ION_LICENSE_CORRUPT:
	  echo "Your license file looks corrupted.";
	  break;
	case ION_LICENSE_EXPIRED:
	  echo "Your license file looks expired.";
	  break;
	case ION_LICENSE_PROPERTY_INVALID:
	  echo "License file properties are mismatched.";
	  break;
	case ION_LICENSE_HEADER_INVALID:
	  echo "License file Header is invalid.";
	  break;
	case ION_LICENSE_SERVER_INVALID:
	  echo "Your server is not authorized.";
	  break;
	case ION_UNAUTH_INCLUDING_FILE:
	  echo "Unauthorized file inclusion.";
	  break;
	case ION_UNAUTH_INCLUDED_FILE:
	  echo "Unauthorized file inclusion.";
	  break;
	case ION_UNAUTH_APPEND_PREPEND_FILE:
	  echo "Unauthorized lines found.";
	  break;
	default:
	  echo "An unknown error occurred.";
	  break;
  }

  echo '</span></br>For assistace <a class="mail-color" href="mailto:sales@hitasoft.com" target="_top">sales@hitasoft.com</a>
					</div>
					<a href="https://appkodes.com" target="_blank"><div class="appkodes-logo"></div></a>
					<div class="link"><a href="https://appkodes.com/terms-conditions" target="_blank">Terms & Conditions</a><span>|</span><a href="https://appkodes.com/privacy-policies" target="_blank">Privacy Policies</a></div>
				</div>
				<div class="bottom-text">Powered by Hitasoft</div>
			</div>
		</div>	
	</div></body></html>';
}

?>	
	
	
<style type="text/css">



body, html {height: 100%; margin: 0; padding: 0}
body
{
	font-size:14px;
	color:#444;	
	font-family:Raleway;	
	
}
body {
	background:#eef7fb;	
}
html, body
{
	height:100%;
	
}
a{
	text-decoration:none ! important;
	outline: 0 !important;
	
}
ul,li
{
	list-style-type: none;
	outline: 0;
}

/********************************************  page  *************************************************************/

.container{
	margin:0 auto;
	text-align:center;	
	
}
.license-container{
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	height: 100%;
	position: absolute;
	display: table;
}


/* Vertical centering: make div as large as viewport and use table layout */
.error-info {
    background: #eef7fb;
    padding: 10px;
    display: table-cell;
    vertical-align: middle;
}
.error-content{
	background:#fff url("https://appkodes.com/wp-content/uploads/2017/05/fin.png") no-repeat scroll;
    width: auto;
    margin: 0 auto;
    display: inline-block;
    padding: 0px 50px 50px 50px;
	background-position: center;
	background-position-y: 100px;
}

   /* Horizontal centering of image: set left & right margins to 'auto' */
  
 .info-txt{
	 padding-top:15px;
	 padding-bottom:20px;
	 line-height: 34px;
 }
 .info-txt span{
	 font-size:28px;
	 font-weight: bold;	 
 }
  
.mail-color{
	color:#19a9e5;	
}	
 .info-txt a{
	  font-size:18px;
 }
.key-icon
{
	background: rgba(0, 0, 0, 0) url("https://appkodes.com/wp-content/uploads/2017/05/key.png") no-repeat scroll;
	height:117px;
	width:99px;	
	margin: 0 auto;
}
.link{
	padding-top:20px;
}
 .link a{
 	font-size:12px;
	 color:#444;
	 padding-right:5px;
 }
  .link span{
	 padding:0px 5px;
 }
.appkodes-logo
{
	background: rgba(0, 0, 0, 0) url("https://appkodes.com/wp-content/uploads/2017/05/logo.png") no-repeat scroll;	
	height:60px;		
	background-size: 90%;
	background-position: center;
	max-width:391px;
	margin: 0 auto;
	
}
.bottom-text{
	padding-top:20px;
	width: 410px;
	margin: 0 auto;	
	color:#859297;
	text-align:right;
	font-size:12px;
}
@media(min-width:320px) and (max-width:640px)
{
.error-content{
	padding: 0px 20px 20px 20px;
}
.bottom-text{
	width: auto;	
	text-align: center;
}
}

  </style>