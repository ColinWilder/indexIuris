<?php
/**
 * @file register.php
 * Prints out the registration page.
 */

$dialog = "";
if (isset($_POST["username"], $_POST["password1"], $_POST["password2"], $_POST["email"], $_POST["g-recaptcha-response"])) {
  require_once "includes/userFunctions.php";

  $dialog = register($_POST["username"], $_POST["password1"], $_POST["password2"], $_POST["email"], $_POST["g-recaptcha-response"]);

  if (strcmp($dialog, "Success") === 0) {
    header("Location: account");
  }
}

$title = "Register";
$loginRequired = false;
require "includes/header.php";
?>
<div class="container">
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Register</h1>
      <?php if ($dialog !== ""): ?>
        <p class="lead text-danger text-center"><?php print $dialog; ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-6 center-block">
      <form class="form-horizontal" action="<?php print htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <fieldset>
          <section class="form-group">
            <label for="username" class="col-xs-4 control-label">Username</label>
            <div class="col-xs-8">
              <input type="text" class="form-control" id="username" name="username" autofocus="" required="">
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
            <label for="email" class="col-xs-4 control-label">Email Address</label>
            <div class="col-xs-8">
              <input type="email" class="form-control" id="email" name="email" required="">
            </div>
          </section>

          <section class="form-group">
            <div class="col-xs-8 pull-right">
              <div class="g-recaptcha" data-sitekey="6LcrxgkTAAAAAKZk3YRaQzfxOB4qlJ1fyCRxXk8q"></div>
            </div>
          </section>

          <section class="form-group">
            <div class="col-xs-12">
              <button type="submit" class="btn btn-primary pull-right">Register</button>
            </div>
          </section>
        </fieldset>
      </form>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; ?>
