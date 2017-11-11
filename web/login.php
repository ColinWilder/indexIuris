<?php
/**
 * @file login.php
 * Prints the login page.
 *
 * 7/28/15 - Lichen has PHP v.5.3.3 installed whereas the local machines have PHP v.5.5.x.
 * Since this is the case, some changes had to be made.
 *
 * Replace hash("sha512", {String}) with password_verify()
 */

$dialog = isset($_GET["dialog"]) ? $_GET["dialog"] : "";

if (isset($_POST)) {
  require_once "includes/userFunctions.php";
}

if (isset($_POST["username"], $_POST["password"])) {
  $dialog = login($_POST["username"], $_POST["password"]);

  if ($dialog == "Success") {
    header("Location: account");
  }
}

if (isset($_POST["username"]) && !isset($_POST["password"])) {
  $dialog = sendPasswordReset($_POST["username"]);
}

if (isset($_POST["email"])) {
  $dialog = sendUsername($_POST["email"]);
}

// I literally made these up.
if (isset($_GET["error"])) {
  switch ($_GET["error"]) {
    case 4:
      $dialog = "Expired ID";
      break;
    case 5:
      $dialog = "Invalid Username";
      break;
    case 7:
      $dialog = "Error resetting password.";
      break;
    case 12:
      $dialog = "Invalid ID";
      break;
  }
}

$title = "Login";
$loginRequired = false;
require "includes/header.php";
?>

<div class="container">
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Login</h1>
      <?php if ($dialog !== ""): ?>
        <p class="lead text-danger text-center"><?php print $dialog; ?></p>
        <?php if ($dialog == "This username does not exist."): ?>
          <a href="register">Do you need to register?</a>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (isset($_GET["forgot"]) && $_GET["forgot"] == "password"): ?>
        <p class="lead">Password Retrieval</p>
      <?php elseif (isset($_GET["forgot"]) && $_GET["forgot"] == "username"): ?>
        <p class="lead">Username Retrieval</p>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <?php if (isset($_GET["forgot"])): ?>
      <div class="col-xs-6">
        <form class="form-horizontal" action="login" method="POST">
          <legend>Username Retrieval</legend>
          <fieldset>
            <section class="form-group">
              <label for="email" class="col-xs-2 control-label">Email</label>
              <div class="col-xs-10">
                <input type="email" class="form-control" id="email" name="email" autocomplete="off" required="">
              </div>
            </section>

            <section class="form-group">
              <div class="col-xs-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </section>
          </fieldset>
        </form>
      </div>

      <div class="col-xs-6">
        <form class="form-horizontal" action="login" method="POST">
          <legend>Password Retrieval</legend>
          <fieldset>
            <section class="form-group">
              <label for="username" class="col-xs-2 control-label">Username</label>
              <div class="col-xs-10">
                <input type="text" class="form-control" id="username" name="username" autocomplete="off" required="">
              </div>
            </section>

            <section class="form-group">
              <div class="col-xs-12">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </section>
          </fieldset>
        </form>
      </div>
    <?php else: ?>
      <div class="col-xs-6 center-block">
        <form class="form-horizontal" action="login" method="POST">
          <fieldset>
            <section class="form-group">
              <label for="username" class="col-xs-2 control-label">Username</label>
              <div class="col-xs-10">
                <input type="text" class="form-control" id="username" name="username" autofocus="">
              </div>
            </section>

            <section class="form-group">
              <label for="password" class="col-xs-2 control-label">Password</label>
              <div class="col-xs-10">
                <input type="password" class="form-control" id="password" name="password">
              </div>
            </section>

            <section class="form-group">
              <div class="col-xs-12">
                <a href="login?forgot" class="pull-left" style="display: block; margin-top: 10px;">Forget your username or password?</a>
                <button type="submit" class="btn btn-primary pull-right">Login</button>
              </div>
            </section>

        </fieldset>
      </form>

    </div>
    <?php endif; ?>
  </div>
</div>

<?php require "includes/footer.php"; ?>
