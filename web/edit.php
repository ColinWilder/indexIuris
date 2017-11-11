<?php
/**
 * @file edit.php
 * Prints the edit submission page.
 */

if (!isset($_GET["id"]) && !isset($_POST["id"])) {
  header("Location: submissions");
}

$id = isset($_POST["id"]) ? $_POST["id"] : $_GET["id"];

if (isset($_POST["id"])) {
  require_once "includes/dataFunctions.php";
  saveObjectToDB($_POST, $id);
  header("Location: edit?id=" . $id . "&success");
}

$title = "Edit Submission";
$loginRequired = true;
require "includes/header.php";

global $mysqli;

$statement = $mysqli->prepare("SELECT custom_namespace, rdf_about, archive, title, type, url, origin, provenance, place_of_composition, shelfmark, freeculture, full_text_url, full_text_plain, is_full_text, image_url, source, metadata_xml_url, metadata_html_url, text_divisions, ocr, thumbnail_url, notes, file_format, date_created, date_updated, user_id FROM objects WHERE id = ? LIMIT 1");
$statement->bind_param("s", $id);
$statement->execute();
$statement->store_result();
$statement->bind_result($custom_namespace, $rdf_about, $archive, $submissionTitle, $type, $url, $origin, $provenance, $place_of_composition, $shelfmark, $freeculture, $full_text_url, $full_text_plain, $is_full_text, $image_url, $source, $metadata_xml_url, $metadata_html_url, $text_divisions, $ocr, $thumbnail_url, $notes, $file_format, $date_created, $date_updated, $user_id);
?>
<?php if ($statement->fetch()): ?>
  <?php if ($user_id == $_SESSION["user_id"] || isSuper()): ?>
    <div class="container">
      <div class="row page-header">
        <div class="col-xs-10">
          <h1>Edit <?php print $submissionTitle; ?></h1>
          <p class="lead" style="font-size: 17px;">Last Updated: <time><?php print formatDate($date_updated); ?></time></p>
        </div>
        <div class="col-xs-2 text-center submission-tools">
          <a href="view?id=<?php print $id; ?>" class="btn btn-default">View</a>
          <a href="rdf?id=<?php print $id; ?>" class="btn btn-default">RDF</a>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <form class="form-horizontal" action="edit" method="POST">
            <fieldset>
              <?php
              // 7/29/15 - Current workaround until MySQLnd is installed onto Lichen.
              printResult("custom_namespace", "Custom Namespace", $custom_namespace, "input");
              printResult("rdf_about", "Unique Identifier (URI)", $rdf_about, "input");
              printResult("archive", "Archive", $archive, "input");
              printResult("title", "Title", $submissionTitle, "input");
              printResult("url", "URL", $url, "input");
              printResult("origin", "Origin", $origin, "input");
              printResult("provenance", "Provenance", $provenance, "input");
              printResult("place_of_composition", "Place of Composition", $place_of_composition, "input");
              printResult("shelfmark", "Shelfmark", $shelfmark, "input");
              printResult("freeculture", "Freeculture", $freeculture, "radio");
              printResult("full_text_url", "Full text URL", $full_text_url, "input");
              printResult("full_text_plain", "Full text", $full_text_plain, "textarea");
              printResult("is_full_text", "Fulltext", $is_full_text, "radio");
              printResult("image_url", "Image URL", $image_url, "input");
              printResult("source", "Source", $source, "input");
              printResult("metadata_xml_url", "XML Metadata URL", $metadata_xml_url, "input");
              printResult("metadata_html_url", "HTML Metadata URL", $metadata_html_url, "input");
              printResult("text_divisions", "Divisions of the Text", $text_divisions, "textarea");
              printResult("ocr", "OCR", $ocr, "radio");
              printResult("thumbnail_url", "Thumbnail URL", $thumbnail_url, "input");
              printResult("notes", "Notes", $notes ,"textarea");

              // This is literally the worst.

              /* 7/29/15 - Removed until MySQLnd is installed onto Lichen.
              <?php foreach ($row as $key=>$value): ?>
                <?php switch ($objectsTableInputTypes[$key]): case "text": // Need a reason as to why PHP can be stupid? Any trailing whitespace between switch and the first case throws an error. http://php.net/manual/en/control-structures.alternative-syntax.php ?>
                  <section class="form-group">
                    <label for="<?php print $key; ?>" class="control-label col-xs-2"><?php print $objectsTableColumDisplayNames[$key]; ?></label>
                    <div class="col-xs-10">
                      <input type="text" class="form-control" name="<?php print $key; ?>" id="<?php print $key; ?>" value="<?php print $value; ?>"<?php printRequired($key); ?>>
                    </div>
                  </section>
                  <hr>
                  <?php break; ?>
                  <?php case "textarea": ?>
                  <section class="form-group">
                    <label for="<?php print $key; ?>" class="control-label col-xs-2"><?php print $objectsTableColumDisplayNames[$key]; ?></label>
                    <div class="col-xs-10">
                      <textarea class="form-control" name="<?php print $key ;?>" id="<?php print $key; ?>" rows="4"<?php printRequired($key); ?>><?php print $value; ?></textarea>
                    </div>
                  </section>
                  <hr>
                  <?php break; ?>
                  <?php case "radio": ?>
                  <section class="form-group">
                    <label for="<?php print $key; ?>" class="control-label col-xs-2"><?php print $objectsTableColumDisplayNames[$key]; ?></label>
                    <div class="col-xs-10">
                      <div class="radio">
                        <label><input type="radio" name="<?php print $key; ?>" value="true"<?php print $value == "true" ? " checked=''" : ""; ?>>Yes</label>
                      </div>
                      <div class="radio">
                        <label><input type="radio" name="<?php print $key; ?>" value="false"<?php print $value == "false" ? " checked=''" : ""; ?>>No</label>
                      </div>
                    </div>
                  </section>
                  <hr>
                  <?php break; ?>
                <?php endswitch; ?>
              <?php
              endforeach;
              */
              ?>
              <span class="hide">File Format</span>
              <section>
                <div class="form-group">
                  <label for="fileFormat" class="control-label col-xs-2">File Format</label>
                  <div class="col-xs-10">
                    <select class="form-control" name="file_format" id="fileFormat" required="">
                      <?php foreach (array("html" => "Web Accessible HTML (HTML, PHP, etc.)", "xml" => "Web Accessible XML (TEI, RDF, etc.)", "plaintext" => "Plain Text Document", "pdf" => "PDF", "image" => "Image files (jpg, png, etc.", "other" => "Other") as $value=>$text): ?>
                        <option<?php print $file_format == $value ? ' selected="" ' : " "; ?>value="<?php print $value; ?>"><?php print $text; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </section>

              <hr>

              <span class="hide">Type</span>
              <section>
                <div class="form-group">
                  <label for="type" class="control-label col-xs-2">Type</label>
                  <div class="col-xs-10">
                    <select class="form-control" id="type" name="type" required="">
                      <?php foreach (array("Critical edition", "Digital image", "Drawing", "Facsimile", "Fragment", "Illustration", "Interactive Resource", "Manuscript Codex", "Map", "Microfilm", "Image (b/w)", "Online images (for manuscripts online)", "Online transcription of printed book (html, XML)", "Physical Object [such as a stone tablet, monumental arch, seal]", "Printed book", "Roll", "Scanned image of printed book (pdf)", "Sheet", "Typescript") as $item): ?>
                        <option<?php print $type == $item ? ' selected="" ' : " "; ?>value="<?php print $item; ?>"><?php print $item; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </section>

              <hr>

              <?php
              $temp = $mysqli->prepare("SELECT role, value FROM roles WHERE object_id = ?");
              $temp->bind_param("s", $id);
              $temp->execute();
              $temp->bind_result($role, $value);
              ?>
              <span class="hide">Role</span>
              <section>
                <div class="form-group" style="display: none;">
                  <label for="role" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Role</label>
                  <div class="col-xs-10">
                    <select class="form-control" id="role" name="role[]">
                      <option<?php print $role === "" ? " selected=''" : "";?>></option>
                      <?php foreach ($rolesArray as $item): ?>
                        <option<?php print $item == $role ? " selected=''" : ""; ?>><?php print $item; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>

                <div class="form-group" style="display: none;">
                  <label for="value" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Value</label>
                  <div class="col-xs-10">
                    <input type="text" class="form-control" id="value" name="role_value[]">
                  </div>
                </div>
                <?php
                $counter = 1;
                while ($temp->fetch()): ?>
                  <div class="form-group">
                    <label for="role<?php print $counter; ?>" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Role</label>
                    <div class="col-xs-10">
                      <select class="form-control" id="role<?php print $counter; ?>" name="role[]">
                        <option<?php print $role === "" ? " selected=''" : ""; ?>></option>
                        <?php foreach ($rolesArray as $item): ?>
                          <option<?php print $item == $role ? " selected=''" : ""; ?>><?php print $item; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="value<?php print $counter; ?>" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Value</label>
                    <div class="col-xs-10">
                      <input type="text" class="form-control" id="value<?php print $counter; ?>" name="role_value[]" <?php printValue($value); ?>>
                    </div>
                  </div>
                  <?php
                  $counter++;
                endwhile;
                ?>
                <div class="form-group">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-default pull-right" id="addRoleButton">Add a Role</button>
                  </div>
                </div>
              </section>

              <hr>

              <span class="hide">Language</span>
              <section>
                <div class="form-group" style="display: none;">
                  <label for="language" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Language</label>
                  <div class="col-xs-10">
                    <input type="text" class="form-control" name="language[]" id="language">
                  </div>
                </div>
                <?php
                $temp = $mysqli->prepare("SELECT language FROM languages WHERE object_id = ?");
                $temp->bind_param("i", $id);
                $temp->execute();
                $temp->bind_result($language);

                $counter = 1;
                while ($temp->fetch()): ?>
                  <div class="form-group">
                    <label for="language<?php print $counter; ?>" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Language</label>
                    <div class="col-xs-10">
                      <input type="text" class="form-control" name="language[]" id="language<?php print $counter; ?>" <?php printValue($language); ?>>
                    </div>
                  </div>
                  <?php $counter++; ?>
                <?php endwhile; ?>
                <div class="form-group">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-default pull-right" id="addLanguageButton">Add a Language</button>
                  </div>
                </div>
              </section>

              <hr>

              <?php
              $temp = $mysqli->prepare("SELECT genre FROM genres WHERE object_id = ?");
              $temp->bind_param("s", $id);
              $temp->execute();
              $temp->bind_result($genre);
              ?>
              <span class="hide">Genre</span>
              <section>
                <input type="hidden" name="genre[]" value="">
                <?php
                $counter = 1;
                while ($temp->fetch()): ?>
                  <div class="form-group">
                    <label for="genre<?php print $counter; ?>" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Genre</label>
                    <div class="col-xs-10">
                      <select class="form-control" id="genre<?php print $counter; ?>" name="genre[]">
                        <option<?php print $genre === "" ? " selected=''" : ""; ?>></option>
                        <?php foreach ($genresArray as $item): ?>
                          <option<?php print $item == $genre ? " selected=''" : ""; ?>><?php print $item; ?></option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
                  <?php
                  $counter++;
                endwhile;
                ?>
                <div class="form-group">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-default pull-right" id="addGenreButton">Add a Genre</button>
                  </div>
                </div>
              </section>

              <hr>

              <span class="hide">Alternative Title</span>
              <section>
                <div class="form-group" style="display: none;">
                  <label for="altTitle" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Alt Title</label>
                  <div class="col-xs-10">
                    <input type="text" class="form-control" id="altTitle" name="alternative_title[]">
                  </div>
                </div>

                <?php
                $temp = $mysqli->prepare("SELECT alt_title FROM alt_titles WHERE object_id = ?");
                $temp->bind_param("s", $id);
                $temp->execute();
                $temp->bind_result($altTitle);

                $counter = 1;
                while ($temp->fetch()): ?>
                  <div class="form-group">
                    <label for="altTitle<?php print $counter;?>" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>Alt Title</label>
                    <div class="col-xs-10">
                      <input type="text" class="form-control" id="altTitle<?php print $counter; ?>" name="alternative_title[]" <?php printValue($altTitle); ?>>
                    </div>
                  </div>
                  <?php
                  $counter++;
                endwhile;
                ?>
                <div class="form-group">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-default pull-right" id="addAltTitleButton">Add an Alternative Title</button>
                  </div>
                </div>
              </section>

              <hr>

              <?php
              $temp = $mysqli->prepare("SELECT machine_date, human_date FROM dates WHERE object_id = ? AND type = 'text' LIMIT 1");
              $temp->bind_param("s", $id);
              $temp->execute();
              $temp->store_result();
              $temp->bind_result($machineDate, $humanDate);
              $temp->fetch();
              ?>
              <h4>Date of Text Composition</h4>
              <section class="form-group">
                <label for="machineDateText" class="control-label col-xs-2">Machine Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="machineDateText" name="machine_date_text" <?php print $temp->num_rows == 1 ? printValue($machineDate) : 'value=""'; ?> required="">
                </div>
              </section>
              <section class="form-group">
                <label for="humanDateText" class="control-label col-xs-2">Human Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="humanDateText" name="human_date_text" <?php print $temp->num_rows == 1 ? printValue($humanDate) : 'value=""'; ?> required="">
                </div>
              </section>

              <?php
              $temp = $mysqli->prepare("SELECT machine_date, human_date FROM dates WHERE object_id = ? AND type = 'object' LIMIT 1");
              $temp->bind_param("i", $id);
              $temp->execute();
              $temp->store_result();
              $temp->bind_result($machineDate, $humanDate);
              $temp->fetch();
              ?>
              <h4>Date of Manuscript or Print Publication</h4>
              <section class="form-group">
                <label for="machineDateObject" class="control-label col-xs-2">Machine Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="machineDateObject" name="machine_date_object" <?php print $temp->num_rows == 1 ? printValue($machineDate) : 'value=""'; ?> required="">
                </div>
              </section>
              <section class="form-group">
                <label for="humanDateObject" class="control-label col-xs-2">Human Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="humanDateObject" name="human_date_object" <?php print $temp->num_rows == 1 ? printValue($humanDate) : 'value=""'; ?> required="">
                </div>
              </section>

              <?php
              $temp = $mysqli->prepare("SELECT machine_date, human_date FROM dates WHERE object_id = ? AND type = 'digital' LIMIT 1");
              $temp->bind_param("i", $id);
              $temp->execute();
              $temp->store_result();
              $temp->bind_result($machineDate, $humanDate);
              $temp->fetch();
              ?>
              <h4>Date of Digital Surrogate <em>(Optional)</em></h4>
              <section class="form-group">
                <label for="machineDateDigital" class="control-label col-xs-2">Machine Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="machineDateDigital" name="machine_date_digital" <?php print $temp->num_rows == 1 ? printValue($machineDate) : 'value=""'; ?>>
                </div>
              </section>
              <section class="form-group">
                <label for="humanDateDigital" class="control-label col-xs-2">Human Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="humanDateDigital" name="human_date_digital" <?php print $temp->num_rows == 1 ? printValue($humanDate) : 'value=""'; ?>>
                </div>
              </section>

              <hr>

              <span class="hide">isPartOf</span>
              <section>
                <?php // Hidden isPartOf ?>
                <div class="form-group" style="display: none;">
                  <label for="isPartOf" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>isPartOf</label>
                  <div class="col-xs-10">
                    <input type="hidden" class="form-control" id="isPartOf" name="is_part_of[]">
                    <label class="control-label"><a href="" target="_blank"></a></label>
                  </div>
                </div>
                <?php
                $temp = $mysqli->prepare("SELECT part_id FROM parts WHERE object_id = ? AND type = 'isPartOf'");
                $temp->bind_param("s", $id);
                $temp->execute();
                $temp->store_result();
                $temp->bind_result($partID);

                $counter     = 1;
                $isPartOfIDs = array();
                while ($temp->fetch()):
                  array_push($isPartOfIDs, $partID);
                  ?>
                  <div class="form-group">
                    <label for="isPartOf<?php print $counter; ?>" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>isPartOf</label>
                    <div class="col-xs-10">
                      <input type="hidden" class="form-control" id="isPartOf<?php print $counter; ?>" name="is_part_of[]" <?php printValue($partID); ?>>
                      <?php
                      $select = $mysqli->prepare("SELECT title FROM objects WHERE id = ? LIMIT 1");
                      $select->bind_param("s", $partID);
                      $select->execute();
                      $select->store_result();
                      $select->bind_result($title);
                      $select->fetch();
                      ?>
                      <label class="control-label"><a href="view?id=<?php print $partID; ?>" target="_blank"><?php print $title; ?></a></label>
                    </div>
                  </div>
                  <?php
                  $counter++;
                endwhile;
                ?>

                <div id="isPartOfModal" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add isPartOf</h4>
                        <p>Please select another submission that <strong><?php print $submissionTitle; ?></strong> is a part of.</p>
                      </div>
                      <div class="modal-body">
                        <?php // TODO: Better layout. Possibly some grid style. ?>
                        <div class="col-xs-6 center-block">
                          <ul class="list-unstyled">
                            <?php
                            $temp = $mysqli->prepare("SELECT title, id FROM objects WHERE id != ?");
                            $temp->bind_param("s", $id);
                            $temp->execute();
                            $temp->store_result();
                            $temp->bind_result($objectTitle, $partID);

                            while ($temp->fetch()):
                              $isPart = false;
                            // TODO: Re-enable this after writing JS code to handle part deletions in modal dialog.
                            // foreach ($isPartOfIDs as $currentPartID) {
                            //   $isPart = $currentPartID == $partID;
                            // }
                              if (!$isPart): ?>
                                <li class="list-part">
                                  <?php print $objectTitle; ?>
                                  <button type="button" class="btn btn-xs btn-default pull-right" id="part<?php print $partID; ?>" <?php printValue($partID); ?> title="<?php print printValue($objectTitle, true); ?>">Select</button>
                                </li>
                              <?php endif; ?>
                            <?php endwhile; ?>
                          </ul>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#isPartOfModal">Add isPartOf</button>
                  </div>
                </div>
              </section>

              <hr>

              <span class="hide">hasPart</span>
              <section>
                <?php // Hidden hasPart ?>
                <div class="form-group" style="display: none;">
                  <label for="hasPart" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>hasPart</label>
                  <div class="col-xs-10">
                    <input type="hidden" class="form-control" id="hasPart" name="has_part[]">
                    <label class="control-label"><a href="" target="_blank"></a></label>
                  </div>
                </div>
                <?php
                $temp = $mysqli->prepare("SELECT part_id FROM parts WHERE object_id = ? AND type = 'hasPart'");
                $temp->bind_param("s", $id);
                $temp->execute();
                $temp->store_result();
                $temp->bind_result($partID);

                $counter    = 1;
                $hasPartIDs = array();
                while ($temp->fetch()):
                  array_push($hasPartIDs, $partID);
                  ?>
                  <div class="form-group">
                    <label for="hasPart<?php print $counter; ?>" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>hasPart</label>
                    <div class="col-xs-10">
                      <input type="hidden" class="form-control" id="hasPart<?php print $counter; ?>" name="has_part[]" <?php printValue($partID); ?>>
                      <?php
                      $select = $mysqli->prepare("SELECT title FROM objects WHERE id = ? LIMIT 1");
                      $select->bind_param("s", $partID);
                      $select->execute();
                      $select->store_result();
                      $select->bind_result($title);
                      $select->fetch();
                      ?>
                      <label class="control-label"><a href="view?id=<?php print $partID; ?>" target="_blank"><?php print $title; ?></a></label>
                    </div>
                  </div>
                  <?php
                  $counter++;
                endwhile;
                ?>
                <div id="hasPartModal" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">Add hasPart</h4>
                        <p>Please select another submission that <strong><?php print $submissionTitle; ?></strong> is a part of.</p>
                      </div>
                      <div class="modal-body">
                        <div class="col-xs-6 center-block">
                          <ul class="list-unstyled">
                            <?php
                            $temp = $mysqli->prepare("SELECT title, id FROM objects WHERE id != ?");
                            $temp->bind_param("s", $id);
                            $temp->execute();
                            $temp->store_result();
                            $temp->bind_result($objectTitle, $partID);

                            while ($temp->fetch()):
                              $isPart = false;
                              // TODO: Re-enable this after writing JS code to handle part deletions in modal dialog.
                              // foreach ($hasPartIDs as $currentPartID) {
                              //   $isPart = $currentPartID == $partID;
                              // }
                              if (!$isPart): ?>
                                <li class="list-part">
                                  <?php print $objectTitle; ?>
                                  <button type="button" class="btn btn-xs btn-default pull-right" id="part<?php print $partID; ?>" <?php printValue($partID); ?> title="<?php printValue($objectTitle, true); ?>">Select</button>
                                </li>
                              <?php endif; ?>
                            <?php endwhile; ?>
                          </ul>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-xs-12">
                    <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#hasPartModal">Add hasPart</button>
                  </div>
                </div>
              </section>

              <hr>
              <section class="form-group" style="margin-bottom: 15%">
                <input type="hidden" class="hide" name="id" value="<?php print $id; ?>">
                <div class="col-xs-3 center-block">
                  <button type="submit" class="btn btn-success col-xs-12">Save Changes</button>
                </div>
              </section>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  <?php else: ?>
    <script>alert("You do not have permission to view this record."); window.location = "submissions";</script>
  <?php endif; // if ($user_id == $_SESSION["user_id"]) ?>
<?php else: ?>
  <script>alert("This record does not exist."); window.location = "submissions";</script>
<?php endif; // if ($row) ?>

<?php if (isset($_GET["success"])): ?>
  <div class="modal edit-success" id="editSuccess">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body text-center">
          <h2>Success</h2>
          <i class="glyphicon glyphicon-ok text-success"></i>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php require "includes/footer.php";

/**
* Prints if the form field is required or not.
*
* @param {String} $key: The form field name.
*/
function printRequired($key) {
  print in_array($key, array("custom_namespace", "rdf_about", "archive", "title", "type", "file_format")) ? ' required=""' : "";
}

function printResult($name, $label, $value, $type) {
  ?>
  <section class="form-group">
    <label for="<?php print $name; ?>" class="control-label col-xs-2"><?php print $label; ?></label>
    <div class="col-xs-10">
      <?php
      switch ($type) {
        case "input":
        ?><input type="text" class="form-control" name="<?php print $name; ?>" id="<?php print $name; ?>" <?php printValue($value); printRequired($name); ?>><?php
        break;
        case "radio":
        ?>
        <div class="radio">
          <label><input type="radio" name="<?php print $name; ?>" value="true"<?php print $value == "true" ? " checked=''" : ""; ?>>Yes</label>
        </div>
        <div class="radio">
          <label><input type="radio" name="<?php print $name; ?>" value="false"<?php print $value == "false" ? " checked=''" : ""; ?>>No</label>
        </div>
        <?php
        break;
        case "textarea":
        ?><textarea class="form-control" name="<?php print $name; ?>" id="<?php print $name; ?>" rows="4"<?php printRequired($name); ?>><?php printValue($value, true); ?></textarea><?php
        break;
      }
      ?>
    </div>
  </section>
  <hr>
  <?php
}
