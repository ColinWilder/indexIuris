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
?>
<div class="container">
  <div class="row page-header">
    <div class="row">
      <div class="col-xs-12">
        <h1>Search</h1>
      </div>
    </div>
  </div>

  <?php require_once "includes/search-results.php";?>
</div>

<?php require "includes/footer.php"; ?>
