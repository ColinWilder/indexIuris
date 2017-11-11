<?php
/**
 * @file rdf-generator.php
 * function generateRDF generates RDF formatted text given an object id
 */
// Assure that functions is imported.
require_once "functions.php";

global $rolesRDFArray;

$rolesRDFArray = array(
  "Author" => "AUT",
  "Editor" => "EDT",
  "Publisher" => "PBL",
  "Translator" => "TRL",
  "Creator" => "CRE",
  "Etcher" => "ETR",
  "Engraver" => "EGR",
  "Owner" => "OWN",
  "Artist" => "ART",
  "Architect" => "ARC",
  "Binder" => "BND",
  "Book designer" => "BKD",
  "Book producer" => "BKP",
  "Calligrapher" => "CLL",
  "Cartographer" => "CTG",
  "Collector" => "COL",
  "Colorist" => "CLR",
  "Commentator for written text" => "CWT",
  "Compiler" => "COM",
  "Compositor" => "CMT",
  "Dubious author" => "DUB",
  "Facsimilist" => "FAC",
  "Illuminator" => "ILU",
  "Illustrator" => "ILL",
  "Lithographer" => "LTG",
  "Printer" => "PRT",
  "Printer of plates" => "POP",
  "Printmaker" => "PRM",
  "Repository" => "RPS",
  "Rubricator" => "RBR",
  "Scribe" => "SCR",
  "Sculptor" => "SCL",
  "Type designer" => "TYD",
  "Typographer" => "TYG",
  "Wood engraver" => "WDE",
  "Wood cutter" => "WDC",
);

/**
 * Generates an RDF.
 *
 * @param {int} id of object to generate rdf from
 * @return {String}
 */
// TODO:
function generateRDF($objectID) {
  global $mysqli;
  global $rolesRDFArray;

  $statement = $mysqli->prepare("SELECT custom_namespace, rdf_about, archive, title, type_of_content, type_of_original_artifact, type_of_digital_artifact, url, origin, provenance, place_of_composition, shelfmark, freeculture, full_text_url, full_text_plain, is_full_text, image_url, source, metadata_xml_url, metadata_html_url, text_divisions, ocr, thumbnail_url, notes, file_format, date_created, date_updated, user_id FROM objects WHERE id = ? LIMIT 1");
  $statement->bind_param("i", $objectID);
  $statement->execute();
  $statement->store_result();
  $statement->bind_result($custom_namespace, $rdf_about, $archive, $title, $type_of_content, $type_of_original_artifact, $type_of_digital_artifact, $url, $origin, $provenance, $place_of_composition, $shelfmark, $freeculture, $full_text_url, $full_text_plain, $is_full_text, $image_url, $source, $metadata_xml_url, $metadata_html_url, $text_divisions, $ocr, $thumbnail_url, $notes, $file_format, $date_created, $date_updated, $user_id);

  if ($statement->num_rows != 1) {
    return "RECORD \"" . $objectID . "\" DOES NOT EXIST";
  }

  $statement->fetch();

  $rdf = "";
  if (!file_exists("includes/rdf-header.rdf")) {
    return "Error. Unable to open RDF header.";
  }

  $rdf .= file_get_contents("includes/rdf-header.rdf") . "\n";

  // TODO: add <ii> namespace URI to rdf-header.rdf once this is defined.

  // Get namespace
  $namespace = explode(":",$custom_namespace);

  // TODO: Add actual namespace definition URI once these are defined.
  $rdf .= "\txmlns:" . $namespace[0] . "=\"http://someurl\">\n\n";

  $rdf .= "<" . unescapeHTMLEntities($custom_namespace) . " rdf:about=\"" . unescapeHTMLEntities($rdf_about). "\">\n\t";
  $rdf .= "<rdfs:seeAlso rdf:resource=\"" . unescapeHTMLEntities($url) . "\"/>\n\t<collex:federation>INDEXIURIS</collex:federation>\n";

  if ($archive !== "") {
    $rdf .= "\t<collex:archive>" . unescapeHTMLEntities($archive) . "</collex:archive>\n";
  }

  if ($title !== "") {
    $rdf .= "\t<dc:title>" . unescapeHTMLEntities($title) . "</dc:title>\n";
  }

  if ($type_of_content !== "") {
    $rdf .= "\t<ii:type_content>" . unescapeHTMLEntities($type_of_content) . "</ii:type_content>\n";
  }
  
  if ($type_of_original_artifact !== "") {
    $rdf .= "\t<ii:type_original_artifact>" . unescapeHTMLEntities($type_of_original_artifact) . "</ii:type_original_artifact>\n";
  }
  
  if ($type_of_digital_artifact !== "") {
    $rdf .= "\t<ii:type_digital_artifact>" . unescapeHTMLEntities($type_of_digital_artifact) . "</ii:type_digital_artifact>\n";
  }
 
  if ($origin !== "") {
    $rdf .= "\t<ii:origin>" . unescapeHTMLEntities($origin) . "</ii:origin>\n";
  }

  if ($provenance !== "") {
    $rdf .= "\t<ii:provenance>" . unescapeHTMLEntities($provenance) . "</ii:provenance>\n";
  }

  // Place of Composition
  // TODO: Check namespace name with Colin and Abigail
  if ($place_of_composition !== "") {
    $rdf .= "\t<ii:composition>" . unescapeHTMLEntities($place_of_composition) . "</ii:composition>\n";
  }

  if ($shelfmark !== "") {
    $rdf .= "\t<ii:shelfmark>" . unescapeHTMLEntities($shelfmark) . "</ii:shelfmark>\n";
  }

  if ($freeculture !== "") {
    $rdf .= "\t<collex:freeculture>" . unescapeHTMLEntities($freeculture) . "</collex:freeculture>\n";
  }

  // Full text
  if ($is_full_text == "true") {
    if ($full_text_url !== "") {
      $rdf .= "\t<collex:text rdf:resource=\">" . unescapeHTMLEntities($full_text_url) . "\"/>\n";
    } else {
      $rdf .= "\t<collex:text>" . unescapeHTMLEntities($full_text_plain) . "</collex:text>\n";
    }
  }

  if ($image_url !== "") {
    $rdf .= "\t<collex:image rdf:resource=\"" . unescapeHTMLEntities($image_url) . "\"/>\n";
  }

  if ($source !== "") {
    $rdf .= "\t<dc:source>" . unescapeHTMLEntities($source) . "</dc:source>\n";
  }

  if ($metadata_xml_url !== "") {
    $rdf .= "\t<collex:source_xml>" . unescapeHTMLEntities($metadata_xml_url) . "</collex:source_xml>\n";
  }

  if ($metadata_html_url !== "") {
    $rdf .= "\t<collex:source_html>" . unescapeHTMLEntities($metadata_html_url) . "</collex:source_html>\n";
  }

  // TODO: Check namespace with Colin and Abigail
  if ($text_divisions !== "") {
    $rdf .= "\t<ii:divisions>" . unescapeHTMLEntities($text_divisions) . "</ii:divisions>\n";
  }

  if ($ocr !== "") {
    $rdf .= "\t<collex:ocr>" . unescapeHTMLEntities($ocr) . "</collex:ocr>\n";
  }

  if ($thumbnail_url !== "") {
    $rdf .= "\t<collex:thumbnail rdf:resource=\"" . unescapeHTMLEntities($thumbnail_url) . "\"/>\n";
  }

  if ($notes !== "") {
    $rdf .= "\t<ii:notes>" . unescapeHTMLEntities($notes) . "</ii:notes>\n";
  }

  if ($file_format !== "") {
    $rdf .= "\t<ii:format>" . unescapeHTMLEntities($file_format) . "</ii:format>\n";
  }

  // Language
  $temp = $mysqli->prepare("SELECT language FROM languages WHERE object_id = ?");
  $temp->bind_param("i", $objectID);
  $temp->execute();
  $temp->store_result();
  $temp->bind_result($language);

  while ($temp->fetch()) {
    $rdf .= "\t<dc:language>" . unescapeHTMLEntities($language) . "</dc:language>\n";
  }

  // Role
  $temp = $mysqli->prepare("SELECT role, value FROM roles WHERE object_id = ?");
  $temp->bind_param("i", $objectID);
  $temp->execute();
  $temp->store_result();
  $temp->bind_result($role, $value);

  while ($temp->fetch()) {
    $rdf .= "\t<role:" . $rolesRDFArray[$role] . ">" . unescapeHTMLEntities($value) . "</role:" . $rolesRDFArray[$role] . ">\n";
  }

  // Genre
  $temp = $mysqli->prepare("SELECT genre FROM genres WHERE object_id = ?");
  $temp->bind_param("i", $objectID);
  $temp->execute();
  $temp->store_result();
  $temp->bind_result($genre);

  while ($temp->fetch()) {
    $rdf .= "\t<ii:genre>" . unescapeHTMLEntities($genre) . "</ii:genre>\n";
  }

  // Alternative Titles
  $temp = $mysqli->prepare("SELECT alt_title FROM alt_titles WHERE object_id = ?");
  $temp->bind_param("i", $objectID);
  $temp->execute();
  $temp->store_result();
  $temp->bind_result($altTitle);

  while ($temp->fetch()) {
    $rdf .= "\t<dcterms:alternative>" . unescapeHTMLEntities($altTitle) . "</dcterms:alternative>\n";
  }

  // Parts
  $temp = $mysqli->prepare("SELECT type, part_id FROM parts WHERE object_id = ?");
  $temp->bind_param("i", $objectID);
  $temp->execute();
  $temp->store_result();
  $temp->bind_result($type, $partID);

  while ($temp->fetch()) {
    $part = $mysqli->prepare("SELECT rdf_about FROM objects WHERE id = ? LIMIT 1");
    $part->bind_param("s", $partID);
    $part->execute();
    $part->store_result();
    $part->bind_result($part_rdf_about);
    $part->fetch();

    $rdf .= "\t<dcterms:$type rdf:resource=\"" . unescapeHTMLEntities($part_rdf_about) . "\"/>\n";
  }

  // Date
  $temp = $mysqli->prepare("SELECT type, machine_date, human_date FROM dates WHERE object_id = ?");
  $temp->bind_param("i", $objectID);
  $temp->execute();
  $temp->store_result();
  $temp->bind_result($type, $machine, $human);
  
  while ($temp->fetch()){
  	$rdf .= "\t<dc:date>\n\t  <collex:date>\n\t    <rdfs:label>".unescapeHTMLEntities($human)."</rdfs:label>\n\t    <rdf:value>".unescapeHTMLEntities($machine)."</rdf:value>\n\t  </collex:date>\n\t</dc:date>\n";
  }

  // Discipline - All submissions are Law & History
  $rdf .= "\t<collex:discipline>Law</collex:discipline>\n";
  $rdf .= "\t<collex:discipline>History</collex:discipline>\n";

  // TODO: Subject

  $rdf .= "\t</" . $custom_namespace . ">\n</rdf:RDF>\n";

  return $rdf;
} // function generateRDF($objectID)
