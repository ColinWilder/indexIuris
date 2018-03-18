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
if (isSuper()):
?>
<div class="container">
  <div class="row page-header">
    <div class="row">
      <div class="col-xs-12">
        <h1>Update all</h1>
        <pre>
        <?php 
          $object_ids = getAllObjectIds();
          var_dump($object_ids);
          foreach ($object_ids as $object_id){
            $doc = getObjectFromDB($object_id);
            $new_doc = array();
            foreach($doc as $key=>$value){
                if ($value==NULL){
                    $new_doc[$key] = "";
                }
                else {
                    $new_doc[$key] = $value;
                }
            }
            $doc = $new_doc;
            $id = $doc['url'];
            $doc['id'] = $id;
            unset($doc['metadata_xml_url']);
            unset($doc['metadata_html_url']);
            unset($doc['image_url']);
            var_dump($doc);
            print(indexDocument($doc)." \n");
          }
          print(commitIndex()." - ");
        ?>
        </pre>
      </div>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; 
else:?>
<h1 class="text-danger">You do not have permission to access this page.</h1>
<?
endif;
?>
