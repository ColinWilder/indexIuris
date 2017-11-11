<?php
/**
 * @file view.php
 * Prints out an individual submission.
 */
if (!isset($_GET["id"])) {
  header("Location: submissions");
}

$title = "View Submission";
$loginRequired = true;
require "includes/header.php";
global $mysqli;

$id = $_GET["id"];
$statement = $mysqli->prepare("SELECT custom_namespace, rdf_about, archive, title, type, url, origin, provenance, place_of_composition, shelfmark, freeculture, full_text_url, full_text_plain, is_full_text, image_url, source, metadata_xml_url, metadata_html_url, text_divisions, ocr, thumbnail_url, notes, file_format, date_created, date_updated, user_id FROM objects WHERE id = ? LIMIT 1");
$statement->bind_param("i", $id);
$statement->execute();
$statement->store_result();
$statement->bind_result($custom_namespace, $rdf_about, $archive, $title, $type, $url, $origin, $provenance, $place_of_composition, $shelfmark, $freeculture, $full_text_url, $full_text_plain, $is_full_text, $image_url, $source, $metadata_xml_url, $metadata_html_url, $text_divisions, $ocr, $thumbnail_url, $notes, $file_format, $date_created, $date_updated, $user_id);
?>
<?php if ($statement->fetch()): ?>
  <?php if ($user_id == $_SESSION["user_id"] || isSuper()): ?>
    <div class="container">
      <div class="row page-header">
        <div class="col-xs-10">
          <h1><?php print $title; ?></h1>
        </div>
        <div class="col-xs-2 text-center submission-tools">
          <a href="edit?id=<?php print $id; ?>" class="btn btn-default">Edit</a>
          <a href="rdf?id=<?php print $id; ?>" class="btn btn-default">RDF</a>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-9">
          <h3><?php print $custom_namespace; ?></h3>
          <h5><?php print $archive; ?></h5>
          <p><?php print $rdf_about; ?></p>
        </div>

        <div class="col-xs-3 text-right">
          <p>Created: <time><?php print formatDate($date_created); ?></time></p>
          <p>Updated: <time><?php print formatDate($date_updated); ?></time></p>
        </div>
      </div>

      <hr>

      <div class="row">
        <div class="col-xs-4">
          <ul class="list-group list-single">
            <?php
            print printListItem("Type", $type);
            print printListItem("Origin", $origin);
            print printListItem("Provenance", $provenance);
            print printListItem("File Format", $file_format);
            print printListItem("Place of Composition", $place_of_composition);
            ?>
          </ul>
        </div>

        <div class="col-xs-5">
          <?php
          print printPanel("Shelfmark", $shelfmark);
          print printPanel("Divisions of the Text", $text_divisions);
          print printPanel("Source", $source);
          print printPanel("Notes", $notes);
          ?>
        </div>

        <div class="col-xs-3">
          <?php
          print printWell("URL", $url);
          /* 7/27/15 - Temporarily removed until submission form gives off these fields.
           * print printWell("Image URL", $image_url);
           * print printWell("Thumbnail URL", $thumbnail_url);
           */
          print printWell("Full Text URL", $full_text_url);
          print printWell("HTML Metadata URL", $metadata_html_url);
          print printWell("XML Metadata URL", $metadata_xml_url);
          ?>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-3">
          <h2>Roles</h2>
          <ul class="list-group">
            <?php
            $temp = $mysqli->prepare("SELECT role, value FROM roles WHERE object_id = ?");
            $temp->bind_param("i", $id);
            $temp->execute();
            $temp->store_result();
            $temp->bind_result($role, $value);
            ?>

            <?php if ($temp->num_rows === 0): ?>
              <li class="list-group-item"><em>None</em></li>
            <?php else: ?>
              <?php while ($temp->fetch()): ?>
                <li class="list-group-item"><strong><?php print $role; ?></strong>: <?php print $value; ?></li>
              <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </div>

        <div class="col-xs-3">
          <h2>Genres</h2>
          <ul class="list-group">
            <?php
            $temp = $mysqli->prepare("SELECT genre FROM genres WHERE object_id = ?");
            $temp->bind_param("i", $id);
            $temp->execute();
            $temp->store_result();
            $temp->bind_result($genre);
            ?>

            <?php if ($temp->num_rows === 0): ?>
              <li class="list-group-item"><em>None</em></li>
            <?php else: ?>
              <?php while ($temp->fetch()): ?>
                <li class="list-group-item"><?php print $genre; ?></li>
              <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </div>

        <div class="col-xs-3">
          <h2>Alternative Titles</h2>
          <ul class="list-group">
            <?php
            $temp = $mysqli->prepare("SELECT alt_title FROM alt_titles WHERE object_id = ?");
            $temp->bind_param("i", $id);
            $temp->execute();
            $temp->store_result();
            $temp->bind_result($altTitle);
            ?>

            <?php if ($temp->num_rows === 0): ?>
              <li class="list-group-item"><em>None</em></li>
            <?php else: ?>
              <?php while ($temp->fetch()): ?>
                <li class="list-group-item"><?php print $altTitle; ?></li>
              <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </div>

        <div class="col-xs-3">
          <h2>Languages</h2>
          <ul class="list-group">
            <?php
            $temp = $mysqli->prepare("SELECT language FROM languages WHERE object_id = ?");
            $temp->bind_param("i", $id);
            $temp->execute();
            $temp->store_result();
            $temp->bind_result($language);
            ?>

            <?php if ($temp->num_rows === 0): ?>
              <li class="list-group-item"><em>None</em></li>
            <?php else: ?>
              <?php while ($temp->fetch()): ?>
                <li class="list-group-item"><?php print $language; ?></li>
              <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-6">
          <h2>Parts</h2>
          <ul class="list-group">
            <?php
            $temp = $mysqli->prepare("SELECT type, part_id FROM parts WHERE object_id = ?");
            $temp->bind_param("i", $id);
            $temp->execute();
            $temp->store_result();
            $temp->bind_result($type, $partID);
            ?>

            <?php if ($temp->num_rows == 0): ?>
              <li class="list-group-item"><em>None</em></li>
            <?php else: ?>
              <?php while ($temp->fetch()): ?>
                <?php
                $select = $mysqli->prepare("SELECT title FROM objects WHERE id = ? LIMIT 1");
                $select->bind_param("s", $partID);
                $select->execute();
                $select->store_result();
                $select->bind_result($partTitle);
                $select->fetch();
                ?>
                <li class="list-group-item"><strong><?php print $type; ?></strong>: <a href="view?id=<?php print $partID; ?>"><?php print $partTitle; ?></a></li>
              <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </div>

        <div class="col-xs-6">
          <h2>Dates</h2>
          <ul class="list-group">
            <?php
            $temp = $mysqli->prepare("SELECT type, machine_date, human_date FROM dates WHERE object_id = ?");
            $temp->bind_param("i", $id);
            $temp->execute();
            $temp->store_result();
            $temp->bind_result($type, $machine, $human);
            ?>

            <?php if ($temp->num_rows === 0): ?>
              <li class="list-group-item"><em>None</em></li>
            <?php else: ?>
              <?php while ($temp->fetch()): ?>

                <?php if ($type == "text"): ?>
                  <h4>Date of Text Composition</h4>
                <?php elseif ($type == "object"): ?>
                  <h4>Date of Manuscript or Print Publication</h4>
                <?php elseif ($type == "digital"): ?>
                  <h4>Date of Digital Surrogate</h4>
                <?php endif; ?>

                <li class="list-group-item"><strong>Human</strong>: <?php print $human; ?></li>
                <li class="list-group-item"><strong>Machine</strong>: <?php print $machine; ?></li>
              <?php endwhile; ?>
            <?php endif; ?>
          </ul>
        </div>
      </div>
    </div>
  <?php else: ?>
    <script>alert("You do not have permission to view this record."); window.location = "submissions";</script>
  <?php endif; // if ($user_id == $_SESSION["user_id"]) ?>
<?php else: ?>
  <script>alert("This record does not exist."); window.location = "submissions";</script>
<?php endif; // if ($row) ?>

<?php require "includes/footer.php";

/**
 * Prints a list item.
 *
 * @param {String} $text: The heading.
 * @param {String} $column: The string of an array representing the value of a column.
 */
function printListItem($text, $column) {
  $column = trim($column) === "" ? "<em>Blank</em>" : trim($column);
  ?>
  <li class="list-group-item"><strong><?php print $text; ?></strong>: <?php print $column; ?></li>
  <?php
}

/**
 * Prints a panel.
 *
 * @param {String} $text: The heading.
 * @param {String} $column: The string of an array representing the value of a column.
 */
function printPanel($text, $column) {
  $column = trim($column) === "" ? "<em>Blank</em>" : trim($column);
  ?>
  <div class="panel panel-default">
    <div class="panel-heading"><?php print $text; ?></div>
    <div class="panel-body"><?php print $column; ?></div>
  </div>
  <?php
}
/**
 * Prints a well with an anchor tag.
 *
 * @param {String} $text: The heading.
 * @param {String} $column: The string of an array representing the value of a column.
 */
function printWell($text, $column) {
  $column = trim($column) === "" ? "<em>Blank</em>" : trim($column);
  ?>
  <p><?php print $text; ?>:</p>
  <div class="well well-sm"><?php print $column; ?></div>
  <?php
}


