<?php
/**
 * @file view.php
 * Prints out an individual submission.
 */
if (!isset($_GET["id"])) {
  //header("Location: submissions");
}

$title = "Search";
$loginRequired = true;
require "includes/header.php";
require_once "includes/solr.php";
if (isSuper() && isset($_POST['id'])):
?>
<div class="container">
  <div class="row page-header">
    <div class="row">
      <div class="col-xs-12">
        <h1>Delete object</h1>
        <pre>
        <?php 
          permanentlyDeleteObject($_POST['id']);
        ?>
        </pre>
      </div>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; 
else:?>
<h1 class="text-danger">You do not have permission to access this page.</h1>
<?php
endif;
?>
