<?php
/**
 * @file userFunctions.php
 * Functionalities used throughout the website focused mainly on the user.
 */
// Assure that the config file is imported prior.
require_once "config.php";

// Assure error reporting is on.
error_reporting(E_ALL);
ini_set("display_errors", "On");

/**
 * Registers a user.
 *
 * @param {String} $username
 * @param {String} $password1: The 1st password confirmation.
 * @param {String} $password2: The 2nd password confirmation.
 * @param {String} $email
 * @param {String} $captcha: The CAPTCHA value.
 */
function register($username, $password1, $password2, $email, $captcha) {
  global $mysqli;
  $email     = $mysqli->real_escape_string(trim($email));
  $username  = $mysqli->real_escape_string(trim($username));
  $password1 = trim($password1);
  $password2 = trim($password2);
  $response  = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . CAPTCHA_SECRET_KEY . "&response=" . $captcha));

  if ($response->success != 1) {
    if ($response->{"error-codes"}[0] == "missing-input-response") {
      return "The CAPTCHA response is missing.";
    } else if ($response->{"error-codes"}[0] == "invalid-input-response") {
      return "The CAPTCHA response is invalid or malformed.";
    } else {
      return "Something went wrong while trying to validate the CAPTCHA. Please try again.";
    }
  } // if ($response->success != 1)

  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $statement = $mysqli->prepare("SELECT 1 FROM users WHERE email = ?");
    $statement->bind_param("s", $email);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows == 1) {
      return "This email address is already in use.";
    }
  } else {
    return "Please enter in a valid email.";
  } // if (filter_var($email, FILTER_VALIDATE_EMAIL))

  if (strcmp($password1, $password2) === 0) {
    $password  = hash("sha512", $password1);
    $statement = $mysqli->prepare("SELECT 1 FROM users WHERE username = ?");
    $statement->bind_param("s", $username);
    $statement->execute();
    $statement->store_result();

    if ($statement->num_rows == 1) {
      return "This username is already in use.";
    }

    $statement = $mysqli->prepare("INSERT INTO users (username, password_hash, user_role, email, email_verify) VALUES (?, ?, 'contributor', ?, 0)");
    $statement->bind_param("sss", $username, $password, $email);
    $statement->execute();
    $statement->store_result();

    if ($statement->error) {
      return "There was an error trying to get you registered. (" . $statement->errno . ") - " . $statement->error;
    } else {
      $_SESSION["user_id"]   = $statement->insert_id;
      $_SESSION["username"]  = $username;
      $_SESSION["logged-in"] = true;
      $_SESSION["user_role"] = "contributor";

      sendEmailVerification($email);

      return "Success";
    }
  } else {
    return "Your passwords do not match.";
  } // if (strcmp($password1, $password2) === 0)
} // function register($username, $password1, $password2, $email, $captcha)

/**
 * Logs a user in.
 *
 * @param {String} $username
 * @param {String} $passwrod
 */
function login($username, $password) {
  global $mysqli;
  $username = $mysqli->real_escape_string($username);
  $password = hash("sha512", $password);

  $statement = $mysqli->prepare("SELECT id, password_hash, user_role FROM users WHERE username = ?");
  $statement->bind_param("s", $username);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($id, $pass, $role);

  if ($statement->num_rows == 1) {
    $statement->fetch();

    if (strcmp($password, $pass) === 0) {
      $_SESSION["user_id"]   = $id;
      $_SESSION["username"]  = $username;
      $_SESSION["logged-in"] = true;
      $_SESSION["user_role"] = $role;

      return "Success";
    } else {
      return "Incorrect username and password combination.";
    }
  } else {
    // It's widely considered to not display that the username does not exist on login - only on register.
    // I agree with this because bots will struggle with CAPTCHAs. A CAPTCHA is not necessary on login.
    return "Incorrect username and password combination.";
  }
} // function login($username, $password)

/**
 * Sends a password reset to an email address.
 *
 * @param {String} $username
 * @return {String}
 */
function sendPasswordReset($username) {
  global $mysqli;
  $unique = uniqid(uniqid(uniqid("", true), true), true);

  $statement = $mysqli->prepare("UPDATE users SET request_time = NOW(), password_uid = ? WHERE username = ?");
  $statement->bind_param("ss", $unique, $username);
  $statement->execute();

  $statement = $mysqli->prepare("SELECT email FROM users WHERE username = ?");
  $statement->bind_param("s", $username);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($email);

  // Send the email only if the username exists.
  if ($statement->fetch()) {
    $reset = ROOT_FOLDER . "reset?id=" . $unique;

    $message  = "Hello " . $username . ",<br><br>";
    $message .= "<p>You've recently requested to reset your password.</p>";
    $message .= "<p>If you did not initiate this action then please ignore this email. The link will expire in 24 hours from this email.</p><br>";
    $message .= "<p>Otherwise, please <a href='" . $reset . "'>visit this link</a> to reset your password.</p><br>";
    $message .= "<p>" . $reset . "</p>";

    mail($email, "Password Reset", $message, getHeaders("cdh@sc.edu"));
  }

  return "Password reset has been sent.";
} // function sendPasswordReset($username)

/**
 * Sends an email to a user stating what username is attached to
 * the corresponding email.
 *
 * @param {String} $email
 * @return {String}
 */
function sendUsername($email) {
  global $mysqli;

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return "Please enter in a valid email.";
  }

  $email = $mysqli->real_escape_string(trim($email));

  $statement = $mysqli->prepare("SELECT username FROM users WHERE email = ? LIMIT 1");
  $statement->bind_param("s", $email);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($username);

  if ($statement->fetch()) {
    $message  = "Hello " . $username . ",<br><br>";
    $message .= "<p>You've recently requested what your username was.</p>";
    $message .= "<p>It is " . $username . " and it is ready to be used!</p>";
    $message .= "<p>If you did not initiate this action, please ignore this email. This does not pose a threat to your security.</p>";
    $message .= "<br><p>Thank you,<br>The Index Iuris Team</p>";

    if (mail($email, "Username Retrieval", $message, getHeaders("cdh@sc.edu"))) {
      return "Email has been sent.";
    } else {
      return "There was an error trying to send the email, please try again later.";
    }
  } else {
    return "This email address is not in our records.";
  }
} // function sendUsername($email)

/**
 * Sends off a email verification.
 *
 * @param {String} $email: The receiving email.
 * @return {Boolean}
 */
function sendEmailVerification($email) {
  $verify = ROOT_FOLDER . "verify?id=" . bin2hex(mcrypt_encrypt(MCRYPT_BLOWFISH, MD5_KEY, base_convert($_SESSION["user_id"], 10, 36), "ecb"));

  $message  = "Hello " . $_SESSION["username"] . ",<br><br>";
  $message .= "Welcome to Index Iuris!<br><br>";
  $message .= "We would like for you to verify your email address. This email address will only be used for password recovery.<br><br>";
  $message .= "Please visit <a href='" . $verify . "'>this verification link</a> or copy the following link into your browser to verify your email address.<br><br>";
  $message .= $verify . "<br><br>";
  $message .= "Thank you,<br>The Index Iuris Team";

  return mail($email, "Email Verification", $message, getHeaders("cdh@sc.edu"));
} // function sendEmailVerification($email)

/**
 * Updates a user's email address.
 *
 * @param {String} $oldEmail: The old email address.
 * @param {String} $newEmail: The new email address.
 * @return {String}
 */
function updateEmail($newEmail) {
  global $mysqli;

  // Filter the new email.
  if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
    return "Please enter in a valid email.";
  }

  // Assure the emails have been escaped.
  $newEmail = $mysqli->real_escape_string(trim($newEmail));

  // Assure new email does not exist.
  $statement = $mysqli->prepare("SELECT 1 FROM users WHERE email = ?");
  $statement->bind_param("s", $newEmail);
  $statement->execute();
  $statement->store_result();

  if ($statement->num_rows == 1) {
    return "This email address is already in use.";
  }

  // Update new email address.
  $statement = $mysqli->prepare("UPDATE users SET email = ?, email_verify = FALSE WHERE id = ? LIMIT 1");
  $statement->bind_param("si", $newEmail, $_SESSION["user_id"]);
  $statement->execute();
  $statement->store_result();

  sendEmailVerification($newEmail);

  return $statement->affected_rows > 0 ? "Email address updated successfully. Verification sent." : "Failed to update your email address.";
} // function updateEmail($newEmail)

/**
 * Update a user's password.
 *
 * @param {String} $oldPassword: The old password.
 * @param {String} $newPassword: The new password.
 * @return {String}
 */
function updatePassword($oldPassword, $newPassword) {
  global $mysqli;

  // Note: We do not have to escape the passwords due to the fact that they will be hashed prior to touching the database.

  // Hash the passwords.
  $oldPassword = hash("sha512", trim($oldPassword));
  $newPassword = hash("sha512", trim($newPassword));

  // Assure the old password is correct.
  $statement = $mysqli->prepare("SELECT password_hash FROM users WHERE id = ? LIMIT 1");
  $statement->bind_param("i", $_SESSION["user_id"]);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($storedPass);
  $statement->fetch();

  if (strcmp($storedPass, $oldPassword) !== 0) {
    return "Incorrect old password.";
  }

  // Update new password.
  $statement = $mysqli->prepare("UPDATE users SET password_hash = ? WHERE id = ? LIMIT 1");
  $statement->bind_param("si", $newPassword, $_SESSION["user_id"]);
  $statement->execute();
  $statement->store_result();

  return $statement->affected_rows > 0 ? "Password updated successfully." : "Failed to update your password.";
} // function updatePassword($oldPassword, $newPassword)

/**
 * Send off a piece of mail.
 *
 * @param {String} $name: The contacting user's name.
 * @param {String} $email: The contacting user's email.
 * @param {String} $message: The contacting user's message.
 * @param {String} $captcha: The Google Captcha's client-sided value.
 * @param {String} $receiver: The word directly after "Contact" inside the .modal-header > h4.modal-title
 */
function sendContactMail($name, $email, $message, $captcha, $receiver) {
  $name    = trim($name);
  $email   = trim($email);
  $message = trim($message);
  $captcha = trim($captcha);
  $catcher = "";

  if ($receiver == "Colin") {
    $catcher = "wildercf@mailbox.sc.edu";
  } else if ($receiver == "Abigail") {
    $catcher = "afire2@uky.edu";
  } else {
    return array("type" => "danger", "text" => "Please do not alter the webpage.");
  }

  // Validate server-side due to client-side modifications.
  if ($name === "") {
    return array("type" => "warning", "text" => "Please enter in a name.");
  }

  if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return array("type" => "warning", "text" => "Please enter in a valid email.");
  }

  if ($message === "") {
    return array("type" => "warning", "text" => "Please enter in a message.");
  }

  $response = json_decode(file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . CAPTCHA_SECRET_KEY . "&response=" . $captcha));
  if ($response->success != 1) {
    if ($response->{"error-codes"}[0] == "missing-input-response") {
      return array("type" => "warning", "text" => "The CAPTCHA response is missing.");
    } else if ($response->{"error-codes"}[0] == "invalid-input-response") {
      return array("type" => "danger", "text" => "The CAPTCHA response is invalid or malformed.");
    } else {
      return array("type" => "warning", "text" => "Something went wrong while trying to validate the CAPTCHA. Please try again.");
    }
  }

  if (mail($catcher, "Contact from Index Iuris", $message, getHeaders($email))) {
    return array("type" => "success", "text" => "Thank you " . $name . ", your message has been sent!");
  } else {
    return array("type" => "warning", "text" => "There was an error sending your message. Please try again.");
  }
} // function sendMail($name, $email, $message, $captcha, $receiver)

/**
 * Simplified version of getting mail headers.
 *
 * @param {String} $email: The email the receiver will reply to.
 * @return {String}
 */
function getHeaders($email) {
  return "From: " . $email . "\r\nReply-To: " . $email . "\r\nX-Mailer: PHP/" . phpversion() . "\r\n" . "MIME-Version: 1.0\r\nContent-Type: text/html; charset=UTF-8";
} // function getHeaders($email)
