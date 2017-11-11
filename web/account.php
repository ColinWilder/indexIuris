<?php
/**
 * @file account.php
 * Displays user account details
 */

$dialog = "";
if (isset($_POST)) {
  require_once "includes/userFunctions.php";
}

if (isset($_POST["oldPassword"], $_POST["password1"], $_POST["password2"])) {
  if (strcmp(trim($_POST["password1"]), trim($_POST["password2"])) === 0) {
    $dialog = updatePassword($_POST["oldPassword"], $_POST["password1"]);
  } else {
    $dialog = "Your passwords do not match.";
  }
}

if (isset($_POST["oldEmail"], $_POST["email1"], $_POST["email2"])) {
  if (strcmp(trim($_POST["email1"]), trim($_POST["email2"])) === 0) {
    $dialog = updateEmail($_POST["oldEmail"], $_POST["email1"]);
  } else {
    $dialog = "Your emails do not match.";
  }
}

if (isset($_POST["resend"])) {
  exit(json_encode(array("type" => sendEmailVerification($_POST["resend"]))));
}

$title = "Account Details";
$loginRequired = true;
require "includes/header.php";
?>

<div class="container">
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Account Details</h1>
      <?php if ($dialog !== ""): ?>
        <p class="lead text-danger text-center"><?php print $dialog; ?></p>
      <?php endif; ?>
      <?php if (isset($_GET["action"]) && $_GET["action"] == "update"): ?>
        <p class="lead">Update your password.</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">

    <?php if (isset($_GET["update"]) && $_GET["update"] == "password"): ?>
      <div class="col-xs-6">
        <form class="form-horizontal" id="passwordUpdate" action="account" method="POST">
          <fieldset>
            <section class="form-group">
              <label for="old" class="col-xs-4 control-label">Old Password</label>
              <div class="col-xs-8">
                <input type="password" class="form-control" id="old" name="oldPassword" required="">
              </div>
            </section>

            <section class="form-group">
              <label for="password1" class="col-xs-4 control-label">Password</label>
              <div class="col-xs-8">
                <input type="password" class="form-control" id="password1" name="password1" required="">
              </div>
            </section>

            <section class="form-group">
              <label for="password2" class="col-xs-4 control-label">Confirm Password</label>
              <div class="col-xs-8">
                <input type="password" class="form-control" id="password2" name="password2" required="">
              </div>
            </section>

            <section class="form-group">
              <div class="col-xs-8 pull-right">
                <button type="submit" class="btn btn-success">Update Password</button>
              </section>
            </div>
          </fieldset>
        </form>
      </div>
    <?php elseif (isset($_GET["update"]) && $_GET["update"] == "email"): ?>
      <div class="col-xs-6">
        <form class="form-horizontal" action="account" method="POST">
          <fieldset>
            <section class="form-group">
              <label for="email1" class="col-xs-4 control-label">Email</label>
              <div class="col-xs-8">
                <input type="email" class="form-control" id="email1" name="email1" required="">
              </div>
            </section>

            <section class="form-group">
              <label for="email2" class="col-xs-4 control-label">Confirm Email</label>
              <div class="col-xs-8">
                <input type="email" class="form-control" id="email2" name="email2" required="">
              </div>
            </section>

            <section class="form-group">
              <div class="col-xs-8 pull-right">
                <button type="submit" class="btn btn-success">Update Email</button>
              </div>
            </section>
          </fieldset>
        </form>
      </div>
    <?php else: ?>
      <div class="col-xs-3">
        <div class="panel panel-default">
          <div class="panel-heading">Username</div>
          <div class="panel-body"><?php print $_SESSION["username"]; ?></div>
        </div>
      </div>
      <div class="col-xs-3">
        <div class="panel panel-default">
          <div class="panel-heading">Password<a href="account?update=password" class="pull-right" style="color: white;">Edit</a></div>
          <div class="panel-body">*****<!-- Why are you looking here? --></div>
        </div>
      </div>
      <div class="col-xs-3">
        <div class="panel panel-default">
          <div class="panel-heading">Email<a href="account?update=email" class="pull-right" style="color: white;">Edit</a></div>
          <?php
          global $mysqli;
          $statement = $mysqli->prepare("SELECT email, email_verify FROM users WHERE id = ? LIMIT 1");
          $statement->bind_param("i", $_SESSION["user_id"]);
          $statement->execute();
          $statement->store_result();
          $statement->bind_result($email, $verified);
          $statement->fetch();
          ?>
          <div class="panel-body">
            <span><?php print $email; ?></span>
            <?php if ($verified === 0): ?>
              <i class="pull-right glyphicon glyphicon-remove text-warning" data-toggle="tooltip" data-placement="bottom" title="Unverified" style="top: 3px;"></i>
              <button type="button" class="btn btn-xs btn-default" style="margin-top: 2%;" id="verification">Resend Verification</button>
            <?php else: ?>
              <i class="pull-right glyphicon glyphicon-ok text-success" data-toggle="tooltip" data-placement="bottom" title="Verified" style="top: 3px;"></i>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="col-xs-3">
        <div class="panel panel-default">
          <div class="panel-heading">Role</div>
          <div class="panel-body"><?php print $_SESSION["user_role"]; ?></div>
        </div>
      </div>
    </div>

    <div class="row page-header">
      <div class="col-xs-12">
        <h1>Account Stats</h1>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-4">
        <ul class="list-group">
          <li class="list-group-item">
            <?php
            $statement = $mysqli->prepare("SELECT COUNT(user_id) FROM objects WHERE user_id = ?");
            $statement->bind_param("i", $_SESSION["user_id"]);
            $statement->execute();
            $statement->store_result();
            $statement->bind_result($count);
            $statement->fetch();
            ?>
            <span class="badge"><?php print $count; ?></span>
            Submissions Submitted
          </li>
          <li class="list-group-item">
            <?php
            $statement = $mysqli->prepare("SELECT COUNT(user_id) FROM constitution_comments WHERE user_id = ?");
            $statement->bind_param("i", $_SESSION["user_id"]);
            $statement->execute();
            $statement->store_result();
            $statement->bind_result($count);
            $statement->fetch();
            ?>
            <span class="badge"><?php print $count; ?></span>
            Governance Comments
          </li>
        </ul>
      </div>
    <?php endif;?>

  </div>
</div>

<?php require "includes/footer.php"; ?>
