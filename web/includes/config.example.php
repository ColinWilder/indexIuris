<?php
/**
 * @file config.php
 * Determines variables to be used throughout the site.
 */

// Assure error reporting is on.
error_reporting(-1);
ini_set("display_errors", "On");

// Start up any sessions.
session_start();

// Set the timezone.
date_default_timezone_set("America/New_York");

// Define variables.
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_BASE", "collex");
define("MD5_KEY", "c1o2l3l4e5x6");
define("ROOT_FOLDER", "http://" . $_SERVER["HTTP_HOST"] . "/indexiuris/");
define("CAPTCHA_SECRET_KEY", "6LcrxgkTAAAAAGwNKxaLOjygIeF-_814mdhhxDkf");

// Global MySQL Database Connection.
global $mysqli;
$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_BASE);

if ($mysqli->connect_error || $mysqli->connect_errno) {
  exit("<h1 class='text-danger'>Database Connection Error (" . $mysqli->connect_errno . "): " . $mysqli->connect_error . "</h1>");
}

$objectsTableColumDisplayNames = array(
  "custom_namespace" => "Custom namespace",
  "rdf_about" => "Unique identifier (URI)",
  "archive" => "Archive",
  "title" => "Title",
  "type" => "Type",
  "url" => "URL",
  "origin" => "Origin",
  "provenance" => "Provenance",
  "place_of_composition" => "Place of Composition",
  "shelfmark" => "Shelfmark",
  "freeculture" => "Freeculture",
  "full_text_url" => "Full text URL",
  "full_text_plain" => "Full text",
  "is_full_text" => "Fulltext",
  "image_url" => "Image URL",
  "source" => "Source",
  "metadata_xml_url" => "XML Metadata URL",
  "metadata_html_url" => "HTML Metadata URL",
  "text_divisions" => "Divisions of the Text",
  "language" => "Language",
  "ocr" => "OCR",
  "thumbnail_url" => "Thumbnail URL",
  "notes" => "Notes",
  "file_format" => "File format",
  "date_created" => "Date created",
  "date_updated" => "Date updated",
  "user_id" => "User ID"
);

$objectsTableInputTypes = array(
  "custom_namespace" => "text",
  "rdf_about" => "text",
  "archive" => "text",
  "title" => "text",
  "type" => "text",
  "url" => "text",
  "origin" => "text",
  "provenance" => "text",
  "place_of_composition" => "text",
  "shelfmark" => "text",
  "freeculture" => "radio",
  "full_text_url" => "text",
  "full_text_plain" => "textarea",
  "is_full_text" => "radio",
  "image_url" => "text",
  "source" => "text",
  "metadata_xml_url" => "text",
  "metadata_html_url" => "text",
  "text_divisions" => "textarea",
  "language" => "text",
  "ocr" => "radio",
  "thumbnail_url" => "text",
  "notes" => "textarea",
  "file_format" => "text",
  "date_created" => "date",
  "date_updated" => "date",
  "user_id" => "user"
);

$rolesArray = array("Author", "Editor", "Publisher", "Translator", "Creator", "Etcher", "Engraver", "Owner", "Artist", "Architect", "Binder", "Book designer", "Book producer", "Calligrapher", "Cartographer", "Collector", "Colorist", "Commentator for written text", "Compiler", "Compositor", "Creator", "Dubious author", "Facsimilist", "Illuminator", "Illustrator", "Lithographer", "Printer", "Printer of plates", "Printmaker", "Repository", "Rubricator", "Scribe", "Sculptor", "Type designer", "Typographer", "Visual Artist", "Wood engraver", "Wood cutter");

$genresArray = array("Account", "Accusation", "Aide", "Amercement", "Appeal", "Assize", "Benefice", "Brief", "Canon", "Casus", "Causa", "Census", "Certificate", "Challenge", "Charge", "Code of laws", "Collection", "Commentary", "Consilium", "Consistory", "Contract", "Corpus", "Council", "Covenant", "Damages", "Defense", "Decretal", "Deposition", "Dicta", "Dispensation", "Distinction", "Edict", "Enfeoffement", "Evidence", "Formula", "Gloss", "Handbook", "Immunity", "Imperial constitution", "Inquest", "Inquisition", "Investigation", "Judgment", "Manumission", "Narrative", "Oath", "Opinion", "Petition", "Plea", "Prescription", "Privilege", "Process", "Proof", "Receipt", "Regulation", "Rescript", "Response", "Statute", "Summa", "Summation", "Synod", "Testament", "Testimony", "Treatise", "Trial", "Textbook", "Verdict", "Voucher", "Will", "Writ");
