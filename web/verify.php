<?php
/**
 * @file verify.php
 * Verifies a user's email address.
 */

if (isset($_GET["id"])) {
  require_once "includes/functions.php";
  global $mysqli;

  $verify = base_convert(mcrypt_decrypt(MCRYPT_BLOWFISH, MD5_KEY, pack("H*", $_GET["id"]), "ecb"), 36, 10);

  $statement = $mysqli->prepare("UPDATE users SET email_verify = TRUE WHERE id = ?");
  $statement->bind_param("i", $verify);
  $statement->execute();
  $statement->store_result();

  if ($statement->affected_rows === 0) {
    print "Failed. " . $statement->error;
  } else {
    header("Location: account");
  }
} else {
  header("Location: account");
}
