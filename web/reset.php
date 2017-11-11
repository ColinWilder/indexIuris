<?php
/**
 * @file reset.php
 * Reset a user's password.
 */

if (isset($_POST["username"], $_POST["password1"], $_POST["password2"], $_POST["id"])) {
  require_once "includes/config.php";
  global $mysqli;

  $statement = $mysqli->prepare("SELECT username FROM users WHERE password_uid = ? LIMIT 1");
  $statement->bind_param("s", $_POST["id"]);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($username);
  $statement->fetch();

  if (strcmp($_POST["username"], $username) === 0) {
    $password = hash("sha512", $_POST["password1"]);

    $updater = $mysqli->prepare("UPDATE users SET password_hash = ?, request_time = '0000-00-00 00:00:00', password_uid = NULL WHERE username = ? LIMIT 1");
    $updater->bind_param("ss", $password, $username);
    $updater->execute();
    $updater->store_result();

    if ($updater->error) {
      exit(header("Location login?error=7"));
    } else {
      exit(header("Location: login?dialog=Password has been successfully reset."));
    }
  } else {
    exit(header("Location: login?error=5"));
  } // if (strcmp($_POST["username"], $username) === 0)
} // if (isset($_POST["username"], $_POST["password1"], $_POST["password2"], $_POST["id"]))

if (!isset($_GET["id"])) {
  header("Location: login");
} else {
  require_once "includes/config.php";
  global $mysqli;

  $id = $mysqli->real_escape_string($_GET["id"]);

  // Grab time.
  $statement = $mysqli->prepare("SELECT username, request_time FROM users WHERE password_uid = ? LIMIT 1");
  $statement->bind_param("s", $id);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($username, $time);

  // Determine if valid time.
  if ($statement->num_rows === 0 || $time == "0000-00-00 00:00:00") {
    exit(header("Location: login?error=12"));
  }

  $statement->fetch();

  $request = new DateTime($time);
  $current = new DateTime();
  $current->sub(new DateInterval("PT24H"));

  if ($current > $request) {
    $statement = $mysqli->prepare("UPDATE users SET request_time = '0000-00-00 00:00:00', password_uid = NULL WHERE username = ? LIMIT 1");
    $statement->bind_param("s", $username);
    $statement->execute();
    $statement->store_result();

    exit(header("Location: login?error=4"));
  }
} // if (!isset($_GET["id"]))

$title = "Password Reset";
require "includes/header.php";
?>

<div class="container">
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Password Reset</h1>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-6 center-block">
      <form class="form-horizontal" id="passwordUpdate" action="<?php print htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <fieldset>
          <section class="form-group">
            <label for="username" class="control-label col-xs-4">Username</label>
            <div class="col-xs-8">
              <input type="text" class="form-control" id="username" name="username" autofocus="" required="">
            </div>
          </section>

          <section class="form-group">
            <label for="password1" class="control-label col-xs-4">Password</label>
            <div class="col-xs-8">
              <input type="password" class="form-control" id="password1" name="password1" required="">
            </div>
          </section>

          <section class="form-group">
            <label for="password2" class="control-label col-xs-4">Password Confirmation</label>
            <div class="col-xs-8">
              <input type="password" class="form-control" id="password2" name="password2" required="">
            </div>
          </section>

          <section class="form-group">
            <input type="hidden" class="hide" name="id" value="<?php print $_GET['id']; ?>">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-success pull-right">Reset</button>
            </div>
          </section>
        </fieldset>
      </form>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; ?>
