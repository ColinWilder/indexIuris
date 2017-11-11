<?php
/**
 * @file header.php
 * Prints out the HTML structure.
 */
require_once "functions.php";
require_once "dataFunctions.php";
require_once "userFunctions.php";

$loginRequired = isset($loginRequired) ? $loginRequired : false;
if ($loginRequired && !$loginRequired) {
  header("Location: login");
}

if (isset($_POST["name"], $_POST["email"], $_POST["message"], $_POST["captcha"], $_POST["receiver"])) {
  exit(json_encode(sendContactMail($_POST["name"], $_POST["email"], $_POST["message"], $_POST["captcha"], $_POST["receiver"])));
}

/*
 * Note: Do not copy this file over on top of the current header file on Lichen.
 * There is a Google Analytics script installed on the server but not locally.
 */

?><!DOCTYPE html>
<html lang="en-us">
<head>
  <meta charset="UTF-8">
  <meta name="author" content="Center for Digital Humanities - University of South Carolina">
  <meta name="keywords" content="">
  <meta name="description" content="Index Iuris is a federation of digital projects that offer the original texts for the study of the legal history in western Europe, from Roman law to early modern civil codes, both secular and ecclesiastical.">

  <title><?php print isset($title) ? $title . " - " : ""; ?>Index Iuris</title>

  <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,700">
  <?php foreach (array("bootstrap-cosmo.min.css", "dataTables.bootstrap.css", "collex.css") as $style): ?>
    <link rel="stylesheet" href="css/<?php print $style; ?>">
  <?php endforeach; ?>

  <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body>
  <noscript>
    <style>nav, .container { display: none !important; }</style>
    <p>You need JavaScript enabled to use this site.</p>
  </noscript>

  <nav class="nav navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a href="./" class="navbar-brand"> </a>
      </div>

      <div class="collapse navbar-collapse" id="menu">
        <ul class="nav navbar-nav navbar-right">
          <li<?php print $title == "Home" ? ' class="active"' : ""; ?>><a href="./">Home</a></li>
          <?php if (isLoggedIn()): ?>
            <li<?php print $title == "Metadata Submission Form" ? ' class="active"' : ""; ?>><a href="rdf-form">Metadata Submission</a></li>
            <li<?php print $title == "Governance" ? ' class="active"' : ""; ?>><a href="governance">Governance</a></li>
            <li<?php print $title == "View Submissions" ? ' class="active"' : ""; ?>><a href="submissions">View Submissions</a></li>
            <li<?php print $title == "Comments and Suggested Items" ? ' class="active"' : ""; ?>><a href="comments">Comments</a></li>

            <li class="dropdown<?php print $title == 'Account Details' ? ' active' : ''; ?>">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" ara-expanded="false"><?php print $_SESSION["username"]; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="account">Account Details</a></li>
                <li role="seperator" class="divider"></li>
                <li><a href="logout">Logout</a></li>
              </ul>
            </li>
          <?php else: ?>
            <li<?php print $title == "Login" ? ' class="active"' : ""; ?>><a href="login">Login</a></li>
            <li<?php print $title == "Register" ? ' class="active"' : ""; ?>><a href="register">Register</a></li>
          <?php endif; ?>
		  <li<?php print $title == "Search Page" ? ' class="active"' : ""; ?>><a href="http://lichen.csd.sc.edu/dev/indexiuris/collex/search?">Search</a></li>
          <?php if ($title != "Register"): ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contact <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#" data-target="0">Colin Wilder (University of South Carolina)</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#" data-target="1">Abigail Firey (University of Kentucky)</a></li>
              </ul>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
