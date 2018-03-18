<?php
/**
 * @file dataFunctions.php
 * Functionalities used throughout the website focused mainly on objects.
 */
// Assure that the config file is imported prior.
require_once "config.php";

// Assure error reporting is on.
error_reporting(E_ALL);
ini_set("display_errors", "On");

/**
 * Saves an entire object to the database.
 *
 * @param {$_POST} $data: The posted data from a form.
 * @param {int} $object_id: The unique identification number.
 */
function saveObjectToDB($data, $objectID) {
  global $mysqli;
  $userID = $_SESSION["user_id"];

  $customNamespace  = htmlspecialchars(trim($data["custom_namespace"]));
  $rdfAbout         = htmlspecialchars(trim($data["rdf_about"]));
  $archive          = htmlspecialchars(trim($data["archive"]));
  $title            = htmlspecialchars(trim($data["title"]));
  $type_of_original_artifact             = htmlspecialchars(trim($data["type_of_original_artifact"]));
  $type_of_digital_artifact             = htmlspecialchars(trim($data["type_of_digital_artifact"]));
  $type_of_content             = htmlspecialchars(trim($data["type_of_content"]));
  $url              = htmlspecialchars(trim($data["url"]));
  $origin           = htmlspecialchars(trim($data["origin"]));
  $provenance       = htmlspecialchars(trim($data["provenance"]));
  $compositionPlace = htmlspecialchars(trim($data["place_of_composition"]));
  $shelfmark        = htmlspecialchars(trim($data["shelfmark"]));

  // TODO: Add these fields to the form, pending approval from Colin and Abigail.
  $freeculture      = "true";
  $fullTextURL      = "";
  $fullTextPlain    = "";
  $isFullText       = "";
  $imageURL         = "";

  $source           = htmlspecialchars(trim($data["source"]));

  // TODO: Determine format from input and add to appropriate variable
  $metadataXMLURL   = htmlspecialchars(trim($data["metadata_xml_url"]));
  $metdataHTMLURL   = htmlspecialchars(trim($data["metadata_html_url"]));
  $textDivisions    = htmlspecialchars(trim($data["text_divisions"]));
  $ocr              = isset($data["ocr"]) ? htmlspecialchars(trim($data["ocr"])) : NULL;

  // TODO: Add this field to form, pending approval from Colin and Abigail.
  $thumbnailURL     = "";

  $notes            = $data["notes"];
  $fileFormat       = $data["file_format"];

  $statement = $mysqli->prepare("UPDATE objects SET custom_namespace = ?, rdf_about = ?, archive = ?, title = ?, type_of_original_artifact = ?, url = ?, origin = ?, provenance = ?, place_of_composition = ?, shelfmark = ?, freeculture = ?, full_text_url = ?, full_text_plain = ?, is_full_text = ?, image_url = ?, source = ?, metadata_xml_url = ?, metadata_html_url = ?, text_divisions = ?, ocr = ?, thumbnail_url = ?, notes = ?, file_format = ?, date_updated = NOW(), user_id = ? WHERE id = ?");
  $statement->bind_param("sssssssssssssssssssssssss", $customNamespace, $rdfAbout, $archive, $title, $type_of_original_artifact, $url, $origin, $provenance, $compositionPlace, $shelfmark, $freeculture, $fullTextURL, $fullTextPlain, $isFullText, $imageURL, $source, $metadataXMLURL, $metdataHTMLURL, $textDivisions, $ocr, $thumbnailURL, $notes, $fileFormat, $userID, $objectID);
  $statement->execute();
  $statement->store_result();

  // Add languages.
  submitOneValue($objectID, "languages", $data["language"]);

  // Add alternative titles.
  submitOneValue($objectID, "alt_titles", $data["alternative_title"]);

  // Add genres.
  submitOneValue($objectID, "genres", $data["genre"]);

  // Add date to its table.
  deleteOneValue($objectID, "dates");

  $humanDate   = htmlspecialchars(trim($data["human_date_text"]));
  $machineDate = htmlspecialchars(trim($data["machine_date_text"]));

  if ($humanDate !== "" && $machineDate !== "") {
    $insert = $mysqli->prepare("INSERT INTO dates (object_id, type, machine_date, human_date) VALUES (?, 'text', ?, ?)");
    $insert->bind_param("iss", $objectID, $machineDate, $humanDate);
    $insert->execute();
  }

  $humanDate   = htmlspecialchars(trim($data["human_date_object"]));
  $machineDate = htmlspecialchars(trim($data["machine_date_object"]));

  if ($humanDate !== "" && $machineDate !== "") {
    $insert = $mysqli->prepare("INSERT INTO dates (object_id, type, machine_date, human_date) VALUES (?, 'object', ?, ?)");
    $insert->bind_param("iss", $objectID, $machineDate, $humanDate);
    $insert->execute();
  }

  $humanDate   = htmlspecialchars(trim($data["human_date_digital"]));
  $machineDate = htmlspecialchars(trim($data["machine_date_digital"]));

  if ($humanDate !== "" && $machineDate !== "") {
    $insert = $mysqli->prepare("INSERT INTO dates (object_id, type, machine_date, human_date) VALUES (?, 'digital', ?, ?)");
    $insert->bind_param("iss", $objectID, $machineDate, $humanDate);
    $insert->execute();
  }

  deleteOneValue($objectID, "parts");

  // Add isPartOf to its table.
  foreach ($data["is_part_of"] as $id) {
    $id = htmlspecialchars(trim($id));

    if ($id === "") { continue; }

    $insert = $mysqli->prepare("INSERT INTO parts (object_id, type, part_id) VALUES (?, 'isPartOf', ?)");
    $insert->bind_param("ii", $objectID, $id);
    $insert->execute();
  }

  // Add hasPart to its table.
  foreach ($data["has_part"] as $id) {
    $id = htmlspecialchars(trim($id));

    if ($id === "") { continue; }

    $insert = $mysqli->prepare("INSERT INTO parts (object_id, type, part_id) VALUES (?, 'hasPart', ?)");
    $insert->bind_param("ii", $objectID, $id);
    $insert->execute();
  }

  // Add roles to its table
  deleteOneValue($objectID, "roles");

  for ($i = 0; $i < count($data["role"]); $i++) {
    $role  = htmlspecialchars(trim($data["role"][$i]));
    $value = htmlspecialchars(trim($data["role_value"][$i]));

    if ($value === "" || $role === "") { continue; }

    $insert = $mysqli->prepare("INSERT INTO roles (object_id, role, value) VALUES (?, ?, ?)");
    $insert->bind_param("iss", $objectID, $role, $value);
    $insert->execute();
  }
} // function saveObjectToDB($data, $objectID)

/**
 * Dynamically submits parts of an object to the database.
 *
 * @param {int} $id: The ID of the object.
 * @param {String} $type: The table being submitted to.
 *   This must always end with an "s" and the corresponding column must not have a "s" in it.
 * @param {Array} $values: All passed in values.
 */
function submitOneValue($id, $type, $values) {
  global $mysqli;

  deleteOneValue($id, $type);

  $column = substr($type, 0, -1);

  foreach ($values as $value) {
    $value = htmlspecialchars(trim($value));

    if ($value === "") { continue; }

    $statement = $mysqli->prepare("INSERT INTO $type (object_id, $column) VALUES (?, ?)");
    $statement->bind_param("is", $id, $value);
    $statement->execute();
  }
} // function submitOneValue($id, $type, $values)

/**
 * Dynamically deletes a part of an object on the database.
 *
 * @param {int} $id: The ID of the object.
 * @param {String} $type: The table being deleted from.
 */
function deleteOneValue($id, $type) {
  global $mysqli;

  $statement = $mysqli->prepare("DELETE FROM $type WHERE object_id = ?");
  $statement->bind_param("i", $id);
  $statement->execute();
} // function deleteOneValue($id, $type)


function getObjectFromDB($object_id){
  global $mysqli;
  $statement = $mysqli->prepare("SELECT custom_namespace, rdf_about, archive, title, type_of_original_artifact,type_of_digital_artifact,type_of_content, url, origin, provenance, place_of_composition, shelfmark, freeculture, full_text_url, full_text_plain, is_full_text, image_url, source, metadata_xml_url, metadata_html_url, text_divisions, ocr, thumbnail_url, notes, file_format, date_created, date_updated, user_id FROM objects WHERE id = ? LIMIT 1");
  $statement->bind_param("s", $object_id);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($custom_namespace, $rdf_about, $archive, $title, $type_original,$type_digital,$type_content, $url, $origin, $provenance, $place_composition, $shelfmark, $is_freeculture, $full_text_url, $full_text_plain, $is_full_text, $image_url, $source, $metadata_xml_url, $metadata_html_url, $text_divisions, $is_ocr, $thumbnail_url, $notes, $file_format, $date_created, $date_updated, $user_id);

  if (!$statement->fetch()){
    return -1;
  }

  $temp = $mysqli->prepare("SELECT role, value FROM roles WHERE object_id = ?");
  $temp->bind_param("s", $object_id);
  $temp->execute();
  $temp->bind_result($role, $value);

  $roles = array();
  while ($temp->fetch()){
    $roles[$role] = $value;
  }

  $alt_titles = getObjectItemsFromTable($object_id,'alt_titles','alt_title');

  $disciplines = getObjectItemsFromTable($object_id,'disciplines','discipline');

  $genres = getObjectItemsFromTable($object_id,'genres','genre');

  $languages = getObjectItemsFromTable($object_id,'languages','language');

  $subjects = getObjectItemsFromTable($object_id,'subjects','subject');

  $temp = $mysqli->prepare("SELECT type, machine_date, human_date FROM dates WHERE object_id = ?");
  $temp->bind_param("s", $object_id);
  $temp->execute();
  $temp->bind_result($date_type,$date_human,$date_machine);

  $dates = array();
  while ($temp->fetch()){
    $date = array(
      "human" => $date_human,
      "machine" => $date_machine,
      "type" => $date_type
    );
    $dates[] = $date;
  }

  //parts
  $temp = $mysqli->prepare("SELECT type, part_id FROM parts WHERE object_id = ? AND type='isPartOf'");
  $temp->bind_param("s", $object_id);
  $temp->execute();
  $temp->bind_result($part_type,$value);

  $isPartOf = array();
  while ($temp->fetch()){
    $isPartOf[] = $value;
  }
  $temp = $mysqli->prepare("SELECT type, part_id FROM parts WHERE object_id = ? AND type='hasPart'");
  $temp->bind_param("s", $object_id);
  $temp->execute();
  $temp->bind_result($part_type,$value);

  $hasPart = array();
  while ($temp->fetch()){
    $isPartOf[] = $value;
  }

  $object = array(
    "custom_namespace" => $custom_namespace,
    "rdf_about" => $rdf_about,
    "archive" => $archive,
    "title" => $title,
    "type_original" => $type_original,
    "type_digital" => $type_digital,
    "type_content" => $type_content,
    "url" => $url,
    "origin" => $origin,
    "provenance" => $provenance,
    "place_of_composition" => $place_composition,
    "shelfmark" => $shelfmark,
    "is_freeculture" => $is_freeculture,
    "full_text_url" => $full_text_url,
    "full_text" => $full_text_plain,
    "is_full_text" => $is_full_text,
    "image_url" => $image_url,
    "source" => $source,
    "metadata_xml_url" => $metadata_xml_url,
    "metadata_html_url" => $metadata_html_url,
    "text_divisions" => $text_divisions,
    "is_ocr" => $is_ocr,
    "thumbnail_url" => $thumbnail_url,
    "notes" => $notes,
    "file_format" => $file_format,
  );
  return $object;
}

function getObjectItemsFromTable($object_id,$table,$field){
  global $mysqli;
  $temp = $mysqli->prepare("SELECT ".$field." FROM ".$table." WHERE object_id = ?");
  $temp->bind_param("s", $object_id);
  $temp->execute();
  $temp->bind_result($value);

  $values = array();
  while ($temp->fetch()){
    $values[] = $value;
  }
}

function getAllObjectIds(){
  global $mysqli;
  $temp = $mysqli->prepare("SELECT id FROM objects");
  $temp->execute();
  $temp->bind_result($value);

  $values = array();
  while ($temp->fetch()){
    $values[] = $value;
  }
  return $values;
}