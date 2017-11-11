<?php
/**
 * @file rdf.php
 * Prints rdf for a given record
 */

if (!isset($_GET["id"])) {
  header("Location: submissions");
}

require "includes/rdf-generator.php";

$id = $_GET["id"];

if (isset($_GET["id"], $_GET["download"])) {
  header("Content-Description: File Transfer");
  header("Content-Disposition: attachment; filename=" . $id . ".rdf");
  print generateRDF($id);
  exit();
}
$title = "View RDF";
$loginRequired = true;
require "includes/header.php";

global $mysqli;
$statement = $mysqli->prepare("SELECT title FROM objects WHERE id = ?");
$statement->bind_param("i", $id);
$statement->execute();
$statement->store_result();
$statement->bind_result($title);
$statement->fetch();
?>

<div class="container">
  <div class="row page-header">
    <div class="col-xs-9">
      <h1>RDF of <?php print $title; ?></h1>
    </div>
    <div class="col-xs- text-center submission-tools">
      <a href="rdf?id=<?php print $id; ?>&amp;download" class="btn btn-default">Download</a>
      <a href="edit?id=<?php print $id; ?>" class="btn btn-default">Edit</a>
      <a href="view?id=<?php print $id; ?>" class="btn btn-default">View</a>
    </div>
  </div>

  <div class="row" style="margin-top: 15px;">
    <div class="col-xs-12">
      <pre><?php print htmlspecialchars(generateRDF($id)); ?></pre>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; ?>
