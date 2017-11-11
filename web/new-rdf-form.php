<?php
/**
 * @file new-rdf-form.php
 * Prints the Metadata Submission Form.
 */
$title = "New Metadata Submission Form - draft";
$loginRequired = true;
require "includes/header.php";

if (!isset($_POST["submitted"])): ?>
<div class="container">
      <div class="row">
        <div class="col-xs-12">
          <h1 class="rdf-form-title">Metadata submission form</h1>
        </div>
      </div>
      
      <div class="row">
      	<div class="col-xs-12">
      	  <form class="form-horizontal" action="<?php print htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" data-toggle="validator">
      	    <fieldset>
      	      <h3 class="form-legend">Title</h3>
      	      <section class="form-group">
      	      
      	      <div id="titleDescModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Title</h4>
                    <p><samp>Title</samp> is required. Each item to be integrated in Index Iuris must have a title. It is expected that some titles will occur more than once (several items may have the title "Summa") but each item can have only one title (you cannot give both "Corpus iuris civilis" and "Digest" as the title for the same item.) You may, however, specify alternative titles below.</p>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-10 center-block">
                      <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Collectio Dacheriana", "Consilia", "De Legibus et Consuetudinibus Angliae", "Summa \"Animal est substantia\"")); ?>
              </ul>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
      	      
      	      
      	        <div class="col-xs-12 text-justify">
      	          <div class="form-group">
                  <label for="title" class="control-label col-xs-1">Title</label>
                  <div class="col-xs-10">
                    <input type="text" class="form-control" name="title" id="title" required="">
                  </div>
                  <button type="button" data-toggle="modal" data-target="#titleDescModal" class="btn-default">?</button>
                  </div>
      	        </div>
      	      </section>
      	      
      	  
      	  <h3 class="form-legend">Type</h3>
          <section class="form-group">
            <div class="col-xs-4 text-justify">
              <div class="form-group">
                <label for="type_of_content" class="control-label col-xs-12">Type of content</label>
                <div class="row"><div class="col-xs-11">
                  <select class="form-control" id="type_of_content" name="type_of_content" required="">
                    <?php printOptions(array(
                    		"Text",
                    		"Image"
                    )); ?>
                  </select>
                  
                </div>
                <button type="button" data-toggle="modal" data-target="#type1DescModal" class="btn-default">?</button>
                </div>
              </div>
              
              <div id="type1DescModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Type of content</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-10 center-block">
                      <p>What to use this for: This element corresponds to the DCMI Type element and uses DCMI Type Vocabulary. It describes “[the] nature or genre of the content of the resource…” We expect that for most resources in Index Iuris, the TYPE OF CONTENT will be text or image. For that reason, we include only these two terms in the vocabulary for this element. If you believe that the content of your resource should be describe as something other than text or image, please contact the Index Iuris grand hierarchs.</p>
                      <p>What not to use this for: Following Dublin Core guidelines, do not use this element to “describe the physical or digital manifestation of the resource.”</p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
            <div class="col-xs-4 text-justify">
              <div class="form-group">
                <label for="type_of_original_artifact" class="control-label col-xs-12">Type of original physical artifact</label>
                <div class="col-xs-11">
                  <select class="form-control" id="type_of_original_artifact" name="type_of_original_artifact" required="">
                    <?php printOptions(array("Book (printed, post-1500)", "Codex (medieval manuscript codex)", "Facsimile", "Incunabula", "Manuscript", "Microfilm", "Other physical object", "Roll", "Typescript")); ?>
                  </select>
                </div>
                <button type="button" data-toggle="modal" data-target="#type2DescModal" class="btn-default">?</button>
              </div>
              
              <div id="type2DescModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Type of original physical artifact</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-10 center-block">
                      <p>WWhat to use this for: This element corresponds to the Dublin Core Format element. Use it to describe the physical format of the original artifact that has been digitized. Thus if the digital surrogate is an JPEG image of a medieval manuscript, the format should be manuscript. If the digital surrogate is a PDF image of a critical edition (of medieval sources) published in 1854, the format should be book.</p>
                      <p>What not to use this for: Do not use this element to describe the genre of the object. For instance, the type of an original physical object might be book, the digital type a JPEG (for a scan of a page from the book), the content type an image (meaning that what is on the scanned page is an image, rather than text), and the genre a map.</p>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
            <div class="col-xs-4 text-justify">
              <div class="form-group">
                <label for="type_of_digital_artifact" class="control-label col-xs-12">Type of digital artifact</label>
                <div class="col-xs-11">
                  <select class="form-control" id="type_of_digital_artifact" name="type_of_digital_artifact" required="">
                    <?php printOptions(array(
                    		"Digital transcription of text",
                    		"Digital image"                    		
                    )); ?>
                  </select>
                </div>
                <button type="button" data-toggle="modal" data-target="#type3DescModal" class="btn-default">?</button>
              </div>
              
              <div id="type3DescModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Type of digital artifact</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-10 center-block">
                      <p><strong>Digital image: </strong>
                      This parameter should be selected whenever your item includes a digital surrogate, i.e. a digital image of the original physical artifact.
                      </p><p><strong>Digital transcription of text: </strong>
                      This value may be selected regardless of whether a digital surrogate (an image of the original physical artifact) of this item is also present.
                      </p>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          
          <div class="col-xs-6">
          <h3 class="form-legend">Role</h3>
          <section class="form-group">
            <div class="col-xs-12 text-justify">
              <div class="form-group" style="display:none;">
                <label for="role" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Role</label>
                <div class="col-xs-10">
                  <select class="form-control" id="role" name="role[]" required="">
                    <option selected=""></option>
                    <?php printOptions($rolesArray); ?>
                  </select>
                </div>
              </div>

              <div class="form-group" style="display:none;">
                <label for="value" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Value</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="value" name="role_value[]" required="">
                </div>
              </div>
              

              <div class="form-group">
                <div class="col-xs-11">
                  
                  <button type="button" class="btn btn-default pull-left" id="addRoleButton">Add a Role</button>
                  <button type="button" data-toggle="modal" data-target="#roleModal" class="btn-default pull-right">?</button>
                </div>
                
              </div>
              
              <div id="roleModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Role</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-6 center-block">
                      <p><samp>Role</samp> is used to identify individuals who had a role in the creation/edition of the object. Use this field to list Authors, Editors, etc.</p>
              <p>This field can appear multiple times.</p>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
              
            </div>
          </section>
          </div>
          
          <div class="col-xs-6">
          <h3 class="form-legend">Genre</h3>
          <section class="form-group">
            <div class="col-xs-12 text-justify">
              <div class="form-group" style="display:none;">
                <label for="genre" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Genre</label>
                <div class="col-xs-10">
                  <select class="form-control" id="genre" name="genre[]">
                  <option selected=""></option>
                    <?php printOptions($genresArray); ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-xs-11">
                  <button type="button" class="btn btn-default pull-left" id="addGenreButton">Add a Genre</button>
                  <button type="button" data-toggle="modal" data-target="#genreModal" class="btn-default pull-right">?</button>
                </div>
              </div>
              
              <div id="genreModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Genre</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-6 center-block">
                      <p><samp>Genre</samp> differs from "type" in that it describes the textual form, rather than the physical medium or artifact.</p>

                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>
          
          
          <section class="form-group">
            <div class="col-xs-12 text-justify">
            <h3 class="form-legend">Link to Digital Item</h3>
              <div class="form-group">
                <label for="seeAlso" class="control-label col-xs-1">URL</label>
                <div class="col-xs-10">
                  <input type="url" class="form-control" name="seeAlso" id="seeAlso" required="">
                </div>
                <button type="button" data-toggle="modal" data-target="#linkModal" class="btn-default">?</button>
              </div>
              
              <div id="linkModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Link to digital item</h4>
                    <p>This required field is a valid, web accessible URL that is the address for the specific item to be displayed, such as a manuscript image, a page of a transcription, or a document.</p>
             
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12">
                      <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("http://pds.lib.harvard.edu/pds/view/14856910?n=3384", "http://ccl.rch.uky.edu/node/1419", "http://ccl.rch.uky.edu/node/3908", "http://faculty.cua.edu/Pennington/edit323.htm")); ?>
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
                <label for="customNamespace" class="control-label col-xs-1">Custom namespace</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="custom-namespace" id="customNamespace" required="">
                </div>
                <button type="button" data-toggle="modal" data-target="#customNamespaceModal" class="btn-default">?</button>
              </div>
              
              <div id="customNamespaceModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Custom Namespace</h4>
                    
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12">
                      <p>This field is required, and its format is predetermined for technical reasons. Custom namespace is a short code to identify the project. It is formatted as two pieces of text separated by a colon. The text before the colon identifies the main project or collection; the text after the colon identifies the collection or subcollection.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("CarolingianCanonLawProject:transcription", "AmesFoundation:book", "VirtualCanonLawLibrary:commentary", "VirtualCanonLawLibrary:book", "Pennington:consilia")); ?>
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
                <label for="Archive" class="control-label col-xs-1">Archive</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="archive" id="archive" required="">
                </div>
                <button type="button" data-toggle="modal" data-target="#archiveModal" class="btn-default">?</button>
              </div>
              
              <div id="archiveModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Archive</h4>
                    
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12">
                      <p><samp>Archive</samp> is required. It should be a clear, short version of the name or identity of the member project. It must be a single word or a string of characters, with no spaces.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("AMES (for Ames Foundation, Harvard Law School project)", "CCL (for the Carolingian Canon Law Project)", "VirtualCanonLawLibrary (for the Virtual Library of Medieval Canon Law at Colby)", "PENNINGTON (for Kenneth Pennington's website)")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          
          <h3 class="form-legend">Dates <small><button type="button" data-toggle="modal" data-target="#datesModal" class="btn-default">?</button></small></h3>
          
          <section class="form-group">
            <div class="col-xs-12 text-justify">
              <div class="col-xs-4">
              <h4>Date of text composition</h4>
              <div class="form-group">
                <label for="machineDate" class="control-label col-xs-2">Machine Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="date-machine-text" id="machineDate" required="">
                </div>
              </div>
              <div class="form-group">
                <label for="humanDate" class="control-label col-xs-2">Human Date</label>
                <div class="col-xs-10" style="margin-bottom: 20px;">
                  <input type="text" class="form-control" name="date-human-text" id="humanDate" required="">
                </div>
              </div>
              </div>
              <div class="col-xs-4">
              <h4>Date of manuscript or print publication</h4>
              <div class="form-group">
                <label for="machineDate" class="control-label col-xs-2">Machine Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="date-machine-object" id="machineDate" required="">
                </div>
              </div>
              <div class="form-group">
                <label for="humanDate" class="control-label col-xs-2">Human Date</label>
                <div class="col-xs-10" style="margin-bottom: 20px;">
                  <input type="text" class="form-control" name="date-human-object" id="humanDate" required="">
                </div>
              </div>
              </div>
              <div class="col-xs-4">
              <h4>Date of digital surrogate (optional)</h4>
              <div class="form-group">
                <label for="machineDate" class="control-label col-xs-2">Machine Date</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="date-machine-digital" id="machineDate">
                </div>
              </div>
              <div class="form-group">
                <label for="humanDate" class="control-label col-xs-2">Human Date</label>
                <div class="col-xs-10" style="margin-bottom: 20px;">
                  <input type="text" class="form-control" name="date-human-digital" id="humanDate">
                </div>
              </div>
              
              <div id="datesModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Date</h4>
                    <p><samp>Date</samp> refers to the date of original composition, to the extent that it is known. This field is required. Index Iuris will use both human readable expressions in its displays, and also machine readable formats to facilitate searching by date range.</p>
              
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                      <p>Human-readable dates examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("14th century", "not before 1475", "saec. IXin-med", "850; 1122", "c. 1100", "1300-1350", "1st part of manuscript 9th century; 2nd part early 12th century")); ?>
              </ul>
                           
              <p>Machine-readable dates examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("four-digit year, e.g. \"1425\" or \"0850\"", "two four-digit years, separated by a hyphen, indicating a span of time e.g. \"1425-1450\". The conventions for \"beginning, middle, third-quarter, end, etc.\" of centuries are converted to 25 year increments: 0800, 0825, 0850, 0875", "two four-digit years separated by a semi-colon indicate that the text or object was composed or created at two dates. Both should be searchable.")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
              </div>
            </div>

          </section>
          
          <!-- OPTIONAL FIELDS -->
          <div class="row">
          
          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Provenance <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label for="origin" class="control-label col-xs-12">Origin</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="origin" id="origin">
                </div>
              </div>

              <div class="form-group">
                <label for="provenance" class="control-label col-xs-12">Provenance</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="provenance" id="provenance">
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#provenanceModal" class="btn-default">?</button>
              
              <div id="provenanceModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Provenance</h4>
                    <p><samp>Provenance</samp> is actually two fields, both optional (although we recommend completing at least one).</p>
              <p>The first field is <samp>origin</samp>, which can be used for the place where a manuscript was written, or a work published. The second field is <samp>provenance</samp>, which can be used for ownership information, or likely area of use or circulation, or the earliest known information regarding the whereabouts of a manuscript. Note: See below for <samp>place of composition</samp>.</p>
                    
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                    <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Origin: Bologna", "Origin: Northeast France", "Provenance: St. Gall", "Provenance: Durham Cathedral Priory (suppressed 1540); Thomas Allen (d. 1632); George Henry Lee, 3rd Earl of Lichfield (d. 1772); Reverend Thomas Phillips, S.J. (d. 1774); Stonyhurst College; British Library")); ?>
              </ul>
              <p>If there is nothing in the <samp>origin</samp> field, the <samp>provenance</samp> information is displayed in the basic metadata; if there is information in the <samp>origin</samp> field, that is what is displayed in the basic metadata.</p>
                    
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>
          
          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Place of composition <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label for="placeComposition" class="control-label col-xs-12">Place of Composition</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="place-of-composition" id="placeComposition">
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#compositionModal" class="btn-default">?</button>
              
              <div id="compositionModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Place of composition</h4>
                    <p><samp>Place of composition</samp> is used for the place where a text was composed, if known. This field is optional.</p>                    
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                    <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Rome", "University of Paris")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>
          </div>
          
          
          <div class="row">
          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Shelfmark <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label for="shelfmark" class="control-label col-xs-12">Shelfmark</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="shelfmark" id="shelfmark">
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#shelfmarkModal" class="btn-default">?</button>
              
              <div id="shelfmarkModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Shelfmark</h4>
                    <p><samp>Shelfmark</samp> is required for items that are manuscripts. This is the unique, internationally known identifier for a manuscript. The shelfmark consists of City, Repository (library), fond (internal library collection), number. For incunabula or other rare printings, this field may be used for library identifications of the physical artefact, as well. This field is optional for all other publications or editions.</p>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                    <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Admont en Styrie, Bibliothèque du monastère, 162", "Berlin, Staatsbibliothek Preussischer Kulturbesitz, Lat. fol. 626", "Vaticano, Città del, Biblioteca Apostolica Vaticana, Ottobon. lat. 3295", "Würzburg, Universitätsbibliothek, M.p.th.f.72", "Lexington, University of Kentucky, Margaret I. King Library, Special Collections, KBR197.6 .C36 1525")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>

          <?php /*
          <section class="form-group">
            <div class="form-metadata-item">
              <h3>freeculture?</h3>
            </div>
          </section>

          <section class="form-group">
            <div class="form-metadata-item">
              <h3>Full text goes here</h3>
            </div>
          </section>

          <section class="form-group">
            <div class="form-metadata-item">
              <h3>Image goes here</h3>
            </div>
          </section>
          */ ?>

          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Source <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label for="source" class="control-label col-xs-2">Source</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="source" id="source">
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#sourceModal" class="btn-default">?</button>
              
              <div id="sourceModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Source</h4>
                    
                     </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                    <p>This field should not be confused with provenance, place of origin of object, place of composition or isPartOf. <samp>Source</samp> is used for the title of the larger work, resource, or collection of which the present object is a part. It can be used for the title of a journal, anthology, book, online collection, etc.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("\"The Spoils of the Pope and the Pirates, 1357: the complete legal dossier from the Vatican Archives\"", "The Common and Piepowder Courts of Southampton, 1426-1483", "CEEC: Codices Electronici Ecclesiae Coloniensis")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>
          </div>

          <div class="row">
          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- IsPartOf <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group" style="display: none;">
                <label for="isPartOf" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>isPartOf</label>
                <div class="col-xs-10">
                  <input type="hidden" class="form-control" id="isPartOf" name="is_part_of[]">
                  <label class="control-label"><a href="" target="_blank"></a></label>
                </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#isPartOfModal">Add isPartOf</button>
                </div>
              </div>
            </div>
            
            <div id="isPartOfModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Add isPartOf</h4>
                    <p>Please select another submission that <strong>this</strong> is a part of.</p>
                  </div>
                  <div class="modal-body">
                    <?php // TODO: Better layout. Possibly some grid style. ?>
                    <div class="col-xs-6 center-block">
                      <ul class="list-unstyled">
                        <?php
                        $temp = $mysqli->prepare("SELECT title, id FROM objects");
                        $temp->execute();
                        $temp->store_result();
                        $temp->bind_result($objectTitle, $partID);
                        while ($temp->fetch()): ?>
                          <li class="list-part">
                            <?php print $objectTitle; ?>
                            <button type="button" class="btn btn-xs btn-default pull-right" id="part<?php print $partID; ?>" <?php printValue($partID); ?> title="<?php print printValue($objectTitle, true); ?>">Select</button>
                          </li>
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
          </section>
          </div>

          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- hasPart <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group" style="display: none;">
                <label for="hasPart" class="control-label col-xs-2"><button type="button" class="close pull-left">x</button>hasPart</label>
                <div class="col-xs-10">
                  <input type="hidden" class="form-control" name="has_part[]" id="hasPart">
                  <label class="control-label"><a href="" target="_blank"></a></label>
                </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#hasPartModal">Add hasPart</button>
                </div>
              </div>
            </div>


            <div id="hasPartModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Add hasPart</h4>
                    <p>Please select another submission that is a part of <strong>this</strong>.</p>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-6 center-block">
                      <ul class="list-unstyled">
                        <?php
                        $temp = $mysqli->prepare("SELECT title, id FROM objects");
                        $temp->execute();
                        $temp->store_result();
                        $temp->bind_result($objectTitle, $partID);

                        while ($temp->fetch()): ?>
                          <li class="list-part">
                            <?php print $objectTitle; ?>
                            <button type="button" class="btn btn-xs btn-default pull-right" id="part<?php print $partID; ?>" <?php printValue($partID); ?> title="<?php printValue($objectTitle, true); ?>">Select</button>
                          </li>
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
          </section>
          </div>
          </div>

          <div class="row">
          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Divisions of the text <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label for="textDivisions" class="control-label col-xs-2">Divisions</label>
                <div class="col-xs-10">
                  <textarea class="form-control" name="text-divisions" id="textDivisions" rows="4"></textarea>
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#divisionsModal" class="btn-default">?</button>
              
              <div id="divisionsModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Divisions of the Text</h4>
              <p>Divisions of the text is an optional field that is purely for information (that is, it does not affect digital display or processing). Here it is possible to give useful descriptions of how a compilation is structured, organized, or divided.</p>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                    <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("The Collection in 74 Titles is divided into \"titles\", each of which is divided into \"canons\"", "The Dionysiana is divided into councils, each of which is preceded by a tabula titulorum and followed by a subscription list.  The body of the conciliar text is divided into canons.", "The Decretum is divided into three parts.  The first part has 101 distinctiones; the second part has 36 causae, the third part, entitled \"De consecration\" contains 5 distinctiones.  Each causa is divided into quaestiones… etc.")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>

          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Language <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group" style="display: none;">
                <label for="language" class="control-label col-xs-4"><button type="button" class="close hide pull-left">x</button>Language</label>
                <div class="col-xs-8">
                  <input type="text" class="form-control" name="language[]" id="language">
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" id="addLanguageButton">Add a language</button>
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#languageModal" class="btn-default">?</button>
              
              <div id="languageModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Language</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
                    <p><samp>Language</samp> identifies the language of the object. If available, please use language codes from the <a href="https://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank">ISO 639-2 Language Code List</a>.</p>

                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
              
            </div>
          </section>
          </div>
          </div>

          <div class="row">
          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- Metadata source code <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label for="metadataSourceCode" class="control-label col-xs-2">URL</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="url-source-code" id="metadataSourceCode">
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#metadataModal" class="btn-default">?</button>
              
              <div id="metadataModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Metadata source code</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
              <p><samp>Metadata source code</samp> is an optional field. If your project has metadata that does not duplicate the descriptions in the fields above that should be included in Index Iuris, you may use this field for the URL or URI for the web-accessible XML or HTML metadata.</p>

                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>

          <div class="col-xs-6">
          <h3 class="form-legend collapse-form-element">- OCR <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group">
                <label class="control-label col-xs-5">Was this document generated with OCR?</label>
                <div class="col-xs-2">
                  <div class="radio">
                    <label><input type="radio" name="ocr" value="true">Yes</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="ocr" value="false">No</label>
                  </div>
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#ocrModal" class="btn-default">?</button>
              
              <div id="ocrModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-md">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">OCR</h4>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
              <p><samp>OCR</samp> is an optional field for recording whether the text was generated using OCR. The possible answers are yes or no.</p>

                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          </div>
          </div>

          <h3 class="form-legend collapse-form-element">- Notes <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12">
              <div class="form-group">
                <label for="notes" class="control-label col-xs-2">Notes</label>
                <div class="col-xs-10">
                  <textarea class="form-control" name="notes" id="notes" rows="4"></textarea>
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#notesModal" class="btn-default">?</button>
              
              <div id="notesModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Notes</h4>
                    <p><samp>Notes</samp> is an optional, free-form field for recording information about the item that the contributor deems important.</p>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("This set of images is missing fol. 54v, 55r, and 74r.", "Images of the manuscript from which this transcription was made are available at http://reader.digitale-sammlungen.de/de/fs1/object/display/bsb10181604_00005.html", "There is another edition of this text at http://ancientrome.ru/ius/library/gaius/gai.htm", "This edition retains the orthography of the medieval manuscript.")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>

          <input type="hidden" class="hide" name="submitted" value="true">

      	  
      	  <h3 class="form-legend collapse-form-element">- Alternative title(s) <small>(optional)</small></h3>
          <section class="form-group" style="display:none;">
            <div class="col-xs-12 text-justify">
              <div class="form-group" style="display: none;">
                <label for="altTitle" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Alt Title</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="alternative_title[]" id="altTitle">
                </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" id="addAltTitleButton">Add an Alternative Title</button>
                </div>
              </div>
              
              <button type="button" data-toggle="modal" data-target="#altTitleModal" class="btn-default">?</button>
              
              <div id="altTitleModal" class="modal fade" role="dialog">
              <div class="modal-dialog modal-lg">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">Alternative Title</h4>
                    <p><samp>Alternative title</samp> is an optional field that can be used for common, "pet" names of a text or manuscript.</p>
                  </div>
                  <div class="modal-body">
                    <div class="col-xs-12 center-block">
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("\"The Florence Codex\"", "\"X\"", "\"Concordia Discordantium Canonum\"")); ?>
              </ul>
                  </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                  </div>
                </div>
              </div>
            </div>
              
            </div>
          </section>
          
          
          <input type="hidden" class="hide" name="submitted" value="true">

          <section class="form-group" style="margin-top: 5%; margin-bottom: 15%;">
            <div class="col-xs-3">
              <button type="submit" class="btn btn-success col-xs-12">Submit</button>
            </div>
          </section>
          
          
      	    </fieldset>
      	  </form>
      	</div>
      </div>

      <!-- <div class="row">
        <div class="col-xs-6">
          <a href="rdf-form" class="btn btn-primary col-xs-8 center-block">Submit a New Form</a>
        </div>

        <div class="col-xs-6">
          <a href="submissions" class="btn btn-success col-xs-8 center-block">View Submissions</a>
        </div>
      </div> -->
    </div> <!-- container -->
<?php else:

global $mysqli;
// 7/29/15 - Insert JSON_PRETTY_PRINT as second parameters into json_encode when PHP is updated to v5.4.x.
$json    = json_encode($_POST);
$userID  = $_SESSION['user_id'];
$format  = "json";
$version = "0.2"; //v0.2 Dec. 2015 - by adrian - Added support for new type fields

$statement = $mysqli->prepare("INSERT INTO submissions (data, data_format, rdf_version, date_submitted, user_id) VALUES (?, ?, ?, NOW(), ?)");
$statement->bind_param("ssss", $json, $format, $version, $userID);
$statement->execute();

$statement = $mysqli->prepare("SELECT id FROM objects WHERE url = ?");
$statement->bind_param("s", $_POST["seeAlso"]);
$statement->execute();
$statement->store_result();

if ($statement->num_rows > 0):
?><script>alert("This url already exists for a record."); window.location = "submissions";</script><?php
  else:
    $customNamespace  = htmlspecialchars(trim($_POST["custom-namespace"]));
    
    $url              = htmlspecialchars(trim($_POST["seeAlso"]));
    $rdfAbout         = htmlspecialchars(trim($_POST["seeAlso"])); //merged with seeAlso. We need to use the same value, so we are meging these two fields. Dec. 1, 2015 - adrian
    
    $archive          = htmlspecialchars(trim($_POST["archive"]));
    $title            = htmlspecialchars(trim($_POST["title"]));
    
    //added support for new type fields - adrian
    $type_of_original_artifact             = htmlspecialchars(trim($_POST["type_of_original_artifact"]));
    $type_of_digital_artifact             = htmlspecialchars(trim($_POST["type_of_digital_artifact"]));
    $type_of_content             = htmlspecialchars(trim($_POST["type_of_content"]));
    
    $origin           = htmlspecialchars(trim($_POST["origin"]));
    $provenance       = htmlspecialchars(trim($_POST["provenance"]));
    $compositionPlace = htmlspecialchars(trim($_POST["place-of-composition"]));
    $shelfmark        = htmlspecialchars(trim($_POST["shelfmark"]));
    
    // TODO: Add these fields to the form, pending approval from Colin and Abigail.
    $freeculture      = "true";
    $fullTextURL      = "";
    $fullTextPlain    = "";
    $isFullText       = "";
    $imageURL         = "";
    
    $source           = htmlspecialchars(trim($_POST["source"]));
    
    // TODO: Determine format from input and add to appropriate variable
    $metadataXMLURL   = htmlspecialchars(trim($_POST["url-source-code"]));
    $metdataHTMLURL   = htmlspecialchars(trim($_POST["url-source-code"]));
    $textDivisions    = htmlspecialchars(trim($_POST["text-divisions"]));
    $ocr              = isset($_POST["ocr"]) ? htmlspecialchars(trim($_POST["ocr"])) : NULL;

    // TODO: Add this field to form, pending approval from Colin and Abigail.
    $thumbnailURL     = "";

    $notes            = htmlspecialchars(trim($_POST["notes"]));
    
    //$fileFormat       = htmlspecialchars(trim($_POST["file-format"]));
    $fileFormat = "";
    
    //added support for new type fields and merged rdf-about with seeAlso - Dec. 1, 2015 - adrian
    $statement = $mysqli->prepare("INSERT INTO objects (custom_namespace, rdf_about, archive, title, type_of_original_artifact, type_of_digital_artifact, type_of_content, url, origin, provenance, place_of_composition, shelfmark, freeculture, full_text_url, full_text_plain, is_full_text, image_url, source, metadata_xml_url, metadata_html_url, text_divisions, ocr, thumbnail_url, notes, file_format, date_created, date_updated, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
    $statement->bind_param("ssssssssssssssssssssssssss", $customNamespace, $rdfAbout, $archive, $title, $type_of_original_artifact, $type_of_digital_artifact, $type_of_content, $url, $origin, $provenance, $compositionPlace, $shelfmark, $freeculture, $fullTextURL, $fullTextPlain, $isFullText, $imageURL, $source, $metadataXMLURL, $metdataHTMLURL, $textDivisions, $ocr, $thumbnailURL, $notes, $fileFormat, $userID);
    $statement->execute();
    $statement->store_result();
    
    $lastID = $statement->insert_id;
    
    //Add languages to table
    foreach ($_POST["language"] as $language) {
    	$language = htmlspecialchars(trim($language));
    
    	if ($language === "") { continue; }
    
    	$insert = $mysqli->prepare("INSERT INTO languages (object_id, language) VALUES (?, ?)");
    	$insert->bind_param("is", $lastID, $language);
    	$insert->execute();
    }
    
    // Add alternative titles to its table.
    foreach ($_POST["alternative_title"] as $altTitle) {
      $altTitle = htmlspecialchars(trim($altTitle));

      if ($altTitle === "") { continue; }

      $insert = $mysqli->prepare("INSERT INTO alt_titles (object_id, alt_title) VALUES (?, ?)");
      $insert->bind_param("is", $lastID, $altTitle);
      $insert->execute();
    }

    // Add genres to its table.
    foreach ($_POST["genre"] as $genre) {
      $genre = htmlspecialchars(trim($genre));

      if ($genre === "") { continue; }

      $insert = $mysqli->prepare("INSERT INTO genres (object_id, genre) VALUES (?, ?)");
      $insert->bind_param("is", $lastID, $genre);
      $insert->execute();
    }

    // Add dates to db
    $humanDate   = htmlspecialchars(trim($_POST["date-human-text"]));
    $machineDate = htmlspecialchars(trim($_POST["date-machine-text"]));
    if ($humanDate !== "" && $machineDate !== "") {
      $insert = $mysqli->prepare("INSERT INTO dates (object_id, type, machine_date, human_date) VALUES (?, 'text', ?, ?)");
      $insert->bind_param("iss", $lastID, $machineDate, $humanDate);
      $insert->execute();
    }
    $humanDate   = htmlspecialchars(trim($_POST["date-human-object"]));
    $machineDate = htmlspecialchars(trim($_POST["date-machine-object"]));
    if ($humanDate !== "" && $machineDate !== "") {
    	$insert = $mysqli->prepare("INSERT INTO dates (object_id, type, machine_date, human_date) VALUES (?, 'object', ?, ?)");
    	$insert->bind_param("iss", $lastID, $machineDate, $humanDate);
    	$insert->execute();
    }
    $humanDate   = htmlspecialchars(trim($_POST["date-human-digital"]));
    $machineDate = htmlspecialchars(trim($_POST["date-machine-digital"]));
    if ($humanDate !== "" && $machineDate !== "") {
    	$insert = $mysqli->prepare("INSERT INTO dates (object_id, type, machine_date, human_date) VALUES (?, 'digital', ?, ?)");
    	$insert->bind_param("iss", $lastID, $machineDate, $humanDate);
    	$insert->execute();
    }
    
    // Add isPartOf to its table.
    $partType = "isPartOf";
    foreach ($_POST["is_part_of"] as $id) {
      $id = htmlspecialchars(trim($id));

      if ($id === "") { continue; }

      $insert = $mysqli->prepare("INSERT INTO parts (object_id, type, part_id) VALUES (?, ?, ?)");
      $insert->bind_param("isi", $lastID, $partType, $id);
      $insert->execute();
    }

    // Add hasPart to its table.
    $partType = "hasPart";
    foreach ($_POST["has_part"] as $id) {
      $id = htmlspecialchars(trim($id));

      if ($id === "") { continue; }

      $insert = $mysqli->prepare("INSERT INTO parts (object_id, type, part_id) VALUES (?, ?, ?)");
      $insert->bind_param("isi", $lastID, $partType, $id);
      $insert->execute();
    }

    // Add roles to its table
    $i = 0;
    $roleValues = array();
    foreach ($_POST["role_value"] as $value) {
      array_push($roleValues, $value);
    }

    foreach ($_POST["role"] as $role) {
      $value = htmlspecialchars(trim($roleValues[$i++]));
      $role  = htmlspecialchars(trim($role));

      if ($value === "" || $role === "") { continue; }

      $insert = $mysqli->prepare("INSERT INTO roles (object_id, role, value) VALUES (?, ?, ?)");
      $insert->bind_param("iss", $lastID, $role, $value);
      $insert->execute();
    }

    // TODO: Subject, Discipline.
    
    endif; // if ($statement->num_rows > 0)
?>

<div class="container">
      <div class="row page-header">
        <div class="col-xs-12">
          <h1 class="text-center">Form Submitted Successfully!</h1>
        </div>
      </div>

      <div class="row">
        <div class="col-xs-6">
          <a href="rdf-form" class="btn btn-primary col-xs-8 center-block">Submit a New Form</a>
        </div>

        <div class="col-xs-6">
          <a href="submissions" class="btn btn-success col-xs-8 center-block">View Submissions</a>
        </div>
      </div>
    </div>
<?php 
endif; // if (!isset($_POST["submitted"]))
require "includes/footer.php";
?>