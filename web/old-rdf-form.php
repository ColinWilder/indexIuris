<?php
/**
 * @file rdf-form.php
 * Prints the Metadata Submission Form.
 */
$title = "Metadata Submission Form";
$loginRequired = true;
require "includes/header.php";

if (!isset($_POST["submitted"])): ?>
<div class="container">
  <div class="row page-header">
    <div class="col-xs-12 text-justify">
      <h1>Dear PI or Project Manager,</h1>
      <p>As we develop <samp>Index Iuris</samp>, we seek to understand the needs and preferences of potential members of the federation.  We would be very grateful if you could take a little time to review this form, experiment with filling it out, and offer your views in the comment boxes provided.  In the end, membership in Index Iuris should not be a burden!</p>
      <p>Thanks so much!</p>
      <p>The Index Iuris team</p>

      <hr>

      <p>The following form, once finalized, will be the fundamental mechanism for integrating the content of member projects into the <samp>Index Iuris</samp> portal to all projects.  The metadata supplied in this form makes possible effective searching, and meaningful display of search results.  Not all the information in this form will be required or displayed, but some of the fields are necessary if we are to conform to best practices and technical requirements.</p>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <form class="form-horizontal" action="<?php print htmlentities($_SERVER['PHP_SELF']); ?>" method="POST">
        <h2>Required fields</h2>
        <hr>
        <fieldset>
          
          
          <legend>Custom Namespace</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p>This field is required, and its format is predetermined for technical reasons. Custom namespace is a short code to identify the project. It is formatted as two pieces of text separated by a colon. The text before the colon identifies the main project or collection; the text after the colon identifies the collection or subcollection.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("CarolingianCanonLawProject:transcription", "AmesFoundation:book", "VirtualCanonLawLibrary:commentary", "VirtualCanonLawLibrary:book", "Pennington:consilia")); ?>
              </ul>

              <div class="form-group">
                <label for="customNamespace" class="control-label col-xs-2">Namespace</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="custom-namespace" id="customNamespace" required="">
                </div>
              </div>
            </div>

            <div class="col-xs-4">
              <label class="control-label col-xs-6">Would you be able to decide what goes after the colon?</label>
              <div class="col-xs-6">
                <div class="radio">
                  <label>
                    <input type="radio" name="custom-namespace-available" value="true">Yes
                  </label>
                </div>
                <div class="radio">
                  <label>
                    <input type="radio" name="custom-namespace-available" value="false">No
                  </label>
                </div>
              </div>
            </div>
          </section>

          <legend>rdf:about</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>rdf:about</samp> is required. <samp>rdf:about</samp> is a URI or a URL that uniquely identifies the record to be indexed.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("http://faculty.cua.edu/pennington/edit301.html", "http://ccl.rch.uky.edu/aboutBod718")); ?>
              </ul>

              <div class="form-group">
                <label for="rdfAbout" class="control-label col-xs-2">rdf:about</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="rdf-about" id="rdfAbout" required="">
                </div>
              </div>
            </div>

            <div class="col-xs-4">
              <label for="rdfComments" class="control-label col-xs-3">Comments:</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-rdf-about" rows="4"></textarea>
              </div>
            </div>
          </section>

          <legend>Archive</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Archive</samp> is required. It should be a clear, short version of the name or identity of the member project. It must be a single word or a string of characters, with no spaces.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("AMES (for Ames Foundation, Harvard Law School project)", "CCL (for the Carolingian Canon Law Project)", "VirtualCanonLawLibrary (for the Virtual Library of Medieval Canon Law at Colby)", "PENNINGTON (for Kenneth Pennington's website)")); ?>
              </ul>

              <div class="form-group">
                <label for="archive" class="control-label col-xs-2">Archive</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="archive" id="archive" required="">
                </div>
              </div>
            </div>
          </section>

          <legend>Title</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Title</samp> is required. Each item to be integrated in Index Iuris must have a title. It is expected that some titles will occur more than once (several items may have the title "Summa") but each item can have only one title (you cannot give both "Corpus iuris civilis" and "Digest" as the title for the same item.) You may, however, specify alternative titles below.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Collectio Dacheriana", "Consilia", "De Legibus et Consuetudinibus Angliae", "Summa \"Animal est substantia\"")); ?>
              </ul>

              <div class="form-group">
                <label for="title" class="control-label col-xs-2">Title</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="title" id="title" required="">
                </div>
              </div>
            </div>
          </section>

          <legend>Alternative title(s) <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Alternative title</samp> is an optional field that can be used for common, "pet" names of a text or manuscript.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("\"The Florence Codex\"", "\"X\"", "\"Concordia Discordantium Canonum\"")); ?>
              </ul>

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
            </div>
          </section>

          <legend>Type</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Type</samp> is required. It describes the type of medium of the item or artifact to be integrated into Index Iuris. This term will be selected from a pre-determined list (controlled vocabulary).</p>

              <div class="form-group">
                <label for="type" class="control-label col-xs-2">Type</label>
                <div class="col-xs-10">
                  <select class="form-control" id="type" name="type" required="">
                    <option selected=""></option>
                    <?php printOptions(array("Critical edition", "Digital image", "Drawing", "Facsimile", "Fragment", "Illustration", "Interactive Resource", "Manuscript Codex", "Map", "Microfilm", "Image (b/w)", "Online images (for manuscripts online)", "Online transcription of printed book (html, XML)", "Physical Object [such as a stone tablet, monumental arch, seal]", "Printed book", "Roll", "Scanned image of printed book (pdf)", "Sheet", "Typescript")); ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label col-xs-10">Would you be able to use the terms in the dropdown to complete the form for the items in your project that you would integrate into Index Iuris? If not, please identify additional terms that should be added to this list.</label>
                <div class="col-xs-2">
                  <div class="radio">
                    <label><input type="radio" name="type-available" value="true">Yes</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="type-available" value="false">No</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="suggestedTypeTerms" class="control-label col-xs-12">Please add terms here, separated by commas:</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="suggested-terms-type" id="suggestedTypeTerms">
                </div>
              </div>
            </div>
          </section>

          <legend>Role</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Role</samp> is used to identify individuals who had a role in the creation/edition of the object. Use this field to list Authors, Editors, etc.</p>
              <p>This field can appear multiple times.</p>
              <div class="form-group">
                <label for="role" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Role</label>
                <div class="col-xs-10">
                  <select class="form-control" id="role" name="role[]" required="">
                    <option selected=""></option>
                    <?php printOptions($rolesArray); ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label for="value" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Value</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" id="value" name="role_value[]" required="">
                </div>
              </div>
              

              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" id="addRoleButton">Add a Role</button>
                </div>
              </div>
            </div>
            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label col-xs-10">Would you be able to use these terms to complete the form for the items in your project that you would want integrated into Index Iuris? If not, please identify additional terms that should be added to this list.</label>
                <div class="col-xs-2">
                  <div class="radio">
                    <label><input type="radio" name="role-available" value="true">Yes</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="role-available" value="false">No</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="suggestedRoleTerms" class="control-label col-xs-12">Please add terms here, separated by commas:</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="suggested-terms-role" id="suggestedRoleTerms">
                </div>
              </div>
            </div>
          </section>

          <legend>Genre</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Genre</samp> differs from "type" in that it describes the textual form, rather than the physical medium or artifact.</p>

              <div class="form-group">
                <label for="genre" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Genre</label>
                <div class="col-xs-10">
                  <select class="form-control" id="genre" name="genre[]">
                    <option selected=""></option>
                    <?php printOptions($genresArray); ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" id="addGenreButton">Add a Genre</button>
                </div>
              </div>
            </div>
            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label col-xs-8">Should this field be required or optional?</label>
                <div class="col-xs-4">
                  <div class="radio">
                    <label><input type="radio" name="genre-required-available" value="true">Required</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="genre-required-available" value="false">Optional</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-xs-8">Should this field have controlled vocabulary, or be free-form?</label>
                <div class="col-xs-4" data-toggle="tooltip" data-placement="top" title="Controlled vocabulary supports check-box searches for all index items of a particular genre (if you want to look only at imperial edicts); free-form allows a wider range of description. Note: we can split the difference, and have auto-suggestions as you type.">
                  <div class="radio">
                    <label><input type="radio" name="genre-controlled-available" value="true">Controlled</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="genre-controlled-available" value="false">Free-form</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="suggestedGenreTerms" class="control-label col-xs-12">Please add terms here, separated by commas:</label>
                <div class="col-xs-12">
                  <input type="text" class="form-control" name="suggested-terms-genre" id="suggestedGenreTerms">
                </div>
              </div>
            </div>
          </section>
          
          <legend>Link to Digital Item</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p>This required field is a valid, web accessible URL that is the address for the specific item to be displayed, such as a manuscript image, a page of a transcription, or a document.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("http://pds.lib.harvard.edu/pds/view/14856910?n=3384", "http://ccl.rch.uky.edu/node/1419", "http://ccl.rch.uky.edu/node/3908", "http://faculty.cua.edu/Pennington/edit323.htm")); ?>
              </ul>

              <div class="form-group">
                <label for="seeAlso" class="control-label col-xs-2">URL</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="seeAlso" id="seeAlso" required="">
                </div>
              </div>
            </div>

            <div class="col-xs-4">
              <label class="control-label col-xs-10">Would you be able to supply a URI or URL for each item to be integrated into Index Iuris?</label>
              <div class="col-xs-2">
                <div class="radio">
                  <label><input type="radio" name="url-available" value="true">Yes</label>
                </div>
                <div class="radio">
                  <label><input type="radio" name="url-available" value="false">No</label>
                </div>
              </div>
            </div>
          </section>
          
          <legend>File format</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p>File Format is a required field for each item, so that we can implement full-text searching whenever possible.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array(".pdf files", ".xml files (TEI P5)", ".html files", ".jpg files")); ?>
              </ul>

              <div class="form-group">
                <label for="fileFormat" class="control-label col-xs-2">Format</label>
                <div class="col-xs-10">
                  <select class="form-control" name="file-format" id="fileFormat" required="">
                    <option selected=""></option>
                    <option value="html">Web accessible HTML (html,php, etc.)</option>
                    <option value="xml">Web accessible XML (TEI, RDF, etc.)</option>
                    <option value="plaintext">Plain text document (txt)</option>
                    <option value="pdf">PDF</option>
                    <option value="image">Image files (jpg, png, etc.)</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              </div>
            </div>
          </section>

          <legend>Dates</legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Date</samp> refers to the date of original composition, to the extent that it is known. This field is required. Index Iuris will use both human readable expressions in its displays, and also machine readable formats to facilitate searching by date range.</p>
              
              <p>Human-readable dates examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("14th century", "not before 1475", "saec. IXin-med", "850; 1122", "c. 1100", "1300-1350", "1st part of manuscript 9th century; 2nd part early 12th century")); ?>
              </ul>
                           
              <p>Machine-readable dates examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("four-digit year, e.g. \"1425\" or \"0850\"", "two four-digit years, separated by a hyphen, indicating a span of time e.g. \"1425-1450\". The conventions for \"beginning, middle, third-quarter, end, etc.\" of centuries are converted to 25 year increments: 0800, 0825, 0850, 0875", "two four-digit years separated by a semi-colon indicate that the text or object was composed or created at two dates. Both should be searchable.")); ?>
              </ul>
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
            </div>

            <div class="col-xs-4">
              <div class="form-group">
                <label class="control-label col-xs-10">Would you be able to complete this field for items in your project to be integrated in Index Iuris, following these models?</label>
                <div class="col-xs-2">
                  <div class="radio">
                    <label><input type="radio" name="date-available" value="true">Yes</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="date-available" value="false">No</label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="dateComments" class="control-label col-xs-3">Comments:</label>
                <div class="col-xs-12">
                  <textarea class="form-control" name="comments-date" id="dateComments" rows="4"></textarea>
                </div>
              </div>
            </div>
          </section>

          <hr>
          <h2>Optional fields</h2>
          <br>
          <legend>Provenance <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Provenance</samp> is actually two fields, both optional (although we recommend completing at least one).</p>
              <p>The first field is <samp>origin</samp>, which can be used for the place where a manuscript was written, or a work published. The second field is <samp>provenance</samp>, which can be used for ownership information, or likely area of use or circulation, or the earliest known information regarding the whereabouts of a manuscript. Note: See below for <samp>place of composition</samp>.</p>

              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Origin: Bologna", "Origin: Northeast France", "Provenance: St. Gall", "Provenance: Durham Cathedral Priory (suppressed 1540); Thomas Allen (d. 1632); George Henry Lee, 3rd Earl of Lichfield (d. 1772); Reverend Thomas Phillips, S.J. (d. 1774); Stonyhurst College; British Library")); ?>
              </ul>
              <p>If there is nothing in the <samp>origin</samp> field, the <samp>provenance</samp> information is displayed in the basic metadata; if there is information in the <samp>origin</samp> field, that is what is displayed in the basic metadata.</p>

              <div class="form-group">
                <label for="origin" class="control-label col-xs-2">Origin</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="origin" id="origin">
                </div>
              </div>

              <div class="form-group">
                <label for="provenance" class="control-label col-xs-2">Provenance</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="provenance" id="provenance">
                </div>
              </div>
            </div>

            <div class="col-xs-4">
              <label for="provenanceComments" class="control-label col-xs-3">Comments:</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-provenance" id="provenanceComments" rows="4"></textarea>
              </div>
            </div>
          </section>
          
          <legend>Place of composition <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Place of composition</samp> is used for the place where a text was composed, if known. This field is optional.</p>

              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Rome", "University of Paris")); ?>
              </ul>

              <div class="form-group">
                <label for="placeComposition" class="control-label col-xs-2">Composition</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="place-of-composition" id="placeComposition">
                </div>
              </div>
            </div>

            <div class="col-xs-4">
              <label for="compositionComments" class="control-label col-xs-3">Comments:</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-place-of-composition" rows="4"></textarea>
              </div>
            </div>
          </section>


          <legend>Shelfmark <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Shelfmark</samp> is required for items that are manuscripts. This is the unique, internationally known identifier for a manuscript. The shelfmark consists of City, Repository (library), fond (internal library collection), number. For incunabula or other rare printings, this field may be used for library identifications of the physical artefact, as well. This field is optional for all other publications or editions.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("Admont en Styrie, Bibliothèque du monastère, 162", "Berlin, Staatsbibliothek Preussischer Kulturbesitz, Lat. fol. 626", "Vaticano, Città del, Biblioteca Apostolica Vaticana, Ottobon. lat. 3295", "Würzburg, Universitätsbibliothek, M.p.th.f.72", "Lexington, University of Kentucky, Margaret I. King Library, Special Collections, KBR197.6 .C36 1525")); ?>
              </ul>

              <div class="form-group">
                <label for="shelfmark" class="control-label col-xs-2">Shelfmark</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="shelfmark" id="shelfmark">
                </div>
              </div>
            </div>
          </section>

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

          <legend>Source <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p>This field should not be confused with provenance, place of origin of object, place of composition or isPartOf. <samp>Source</samp> is used for the title of the larger work, resource, or collection of which the present object is a part. It can be used for the title of a journal, anthology, book, online collection, etc.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("\"The Spoils of the Pope and the Pirates, 1357: the complete legal dossier from the Vatican Archives\"", "The Common and Piepowder Courts of Southampton, 1426-1483", "CEEC: Codices Electronici Ecclesiae Coloniensis")); ?>
              </ul>

              <div class="form-group">
                <label for="source" class="control-label col-xs-2">Source</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="source" id="source">
                </div>
              </div>
            </div>
          </section>

          <legend>IsPartOf <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>IsPartOf</samp> is a useful field for legal texts, which often are compilations of many texts. This field is optional.</p>
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
            <div class="col-xs-4">
              <label for="isPartOfComments" class="control-label col-xs-3">Comments:</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-is-part-of" id="isPartOfComments" rows="4"></textarea>
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

          <legend>hasPart <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>hasPart</samp> is the obverse of <samp>isPartOf</samp>. This field is optional. For texts that contain many other texts, this field can be used to list one or more items included in the larger work.</p>

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
            <div class="col-xs-4">
              <label for="hasPartComments" class="control-label col-xs-3">Comments</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-has-part" id="hasPartComments" rows="4"></textarea>
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

          <legend>Divisions of the text <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p>Divisions of the text is an optional field that is purely for information (that is, it does not affect digital display or processing). Here it is possible to give useful descriptions of how a compilation is structured, organized, or divided.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("The Collection in 74 Titles is divided into \"titles\", each of which is divided into \"canons\"", "The Dionysiana is divided into councils, each of which is preceded by a tabula titulorum and followed by a subscription list.  The body of the conciliar text is divided into canons.", "The Decretum is divided into three parts.  The first part has 101 distinctiones; the second part has 36 causae, the third part, entitled \"De consecration\" contains 5 distinctiones.  Each causa is divided into quaestiones… etc.")); ?>
              </ul>

              <div class="form-group">
                <label for="textDivisions" class="control-label col-xs-2">Divisions</label>
                <div class="col-xs-10">
                  <textarea class="form-control" name="text-divisions" id="textDivisions" rows="4"></textarea>
                </div>
              </div>
            </div>
            <div class="col-xs-4">
              <label for="textDivisionsComments" class="control-label col-xs-3">Comments:</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-text-divisions" id="textDivisionsComments" rows="4"></textarea>
              </div>
            </div>
          </section>

          <legend>Language <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Language</samp> identifies the language of the object. If available, please use language codes from the <a href="https://www.loc.gov/standards/iso639-2/php/code_list.php" target="_blank">ISO 639-2 Language Code List</a>.</p>
              <div class="form-group" style="display: none;">
                <label for="language" class="control-label col-xs-2"><button type="button" class="close hide pull-left">x</button>Language</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="language[]" id="language">
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-12">
                  <button type="button" class="btn btn-default pull-right" id="addLanguageButton">Add a language</button>
                </div>
              </div>
            </div>
          </section>

          <legend>Metadata source code <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>Metadata source code</samp> is an optional field. If your project has metadata that does not duplicate the descriptions in the fields above that should be included in Index Iuris, you may use this field for the URL or URI for the web-accessible XML or HTML metadata.</p>

              <div class="form-group">
                <label for="metadataSourceCode" class="control-label col-xs-2">URL</label>
                <div class="col-xs-10">
                  <input type="text" class="form-control" name="url-source-code" id="metadataSourceCode">
                </div>
              </div>
            </div>
          </section>

          <legend>OCR <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8 text-justify">
              <p><samp>OCR</samp> is an optional field for recording whether the text was generated using OCR. The possible answers are yes or no.</p>

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
            </div>
          </section>

          <legend>Notes <small>(optional)</small></legend>
          <section class="form-group">
            <div class="col-xs-8">
              <p><samp>Notes</samp> is an optional, free-form field for recording information about the item that the contributor deems important.</p>
              <p>Examples:</p>
              <ul class="list-unstyled form-item-example">
                <?php printExamples(array("This set of images is missing fol. 54v, 55r, and 74r.", "Images of the manuscript from which this transcription was made are available at http://reader.digitale-sammlungen.de/de/fs1/object/display/bsb10181604_00005.html", "There is another edition of this text at http://ancientrome.ru/ius/library/gaius/gai.htm", "This edition retains the orthography of the medieval manuscript.")); ?>
              </ul>

              <div class="form-group">
                <label for="notes" class="control-label col-xs-2">Notes</label>
                <div class="col-xs-10">
                  <textarea class="form-control" name="notes" id="notes" rows="4"></textarea>
                </div>
              </div>
            </div>
            <div class="col-xs-4">
              <label for="noteComments" class="control-label col-xs-3">Comments:</label>
              <div class="col-xs-12">
                <textarea class="form-control" name="comments-notes" id="noteComments" rows="4"></textarea>
              </div>
            </div>
          </section>

          <input type="hidden" class="hide" name="submitted" value="true">

          <section class="form-group" style="margin-top: 5%; margin-bottom: 15%;">
            <div class="col-xs-3">
              <button type="submit" class="btn btn-success col-xs-12">Submit</button>
            </div>
          </section>

          <section class="form-group">
            <div class="form-metadata-item"></div>
            <div class="form-poll-item"></div>
          </section>

        </fieldset>
      </form>

    </div>
  </div>
</div>
<?php
else:
  include "includes/rdf-generator.php";

  global $mysqli;
  // 7/29/15 - Insert JSON_PRETTY_PRINT as second parameters into json_encode when PHP is updated to v5.4.x.
  $json    = json_encode($_POST);
  $userID  = $_SESSION['user_id'];
  $format  = "json";
  $version = "0.1";

  $statement = $mysqli->prepare("INSERT INTO submissions (data, data_format, rdf_version, date_submitted, user_id) VALUES (?, ?, ?, NOW(), ?)");
  $statement->bind_param("ssss", $json, $format, $version, $userID);
  $statement->execute();

  $statement = $mysqli->prepare("SELECT id FROM objects WHERE url = ?");
  $statement->bind_param("s", $_POST["seeAlso"]);
  $statement->execute();
  $statement->store_result();

  if ($statement->num_rows > 0):
    ?><script>alert("This record already exists."); window.location = "submissions";</script><?php
  else:
    $customNamespace  = htmlspecialchars(trim($_POST["custom-namespace"]));
    $rdfAbout         = htmlspecialchars(trim($_POST["rdf-about"]));
    $archive          = htmlspecialchars(trim($_POST["archive"]));
    $title            = htmlspecialchars(trim($_POST["title"]));
    $type             = htmlspecialchars(trim($_POST["type"]));
    $url              = htmlspecialchars(trim($_POST["seeAlso"]));
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
    $fileFormat       = htmlspecialchars(trim($_POST["file-format"]));

    $statement = $mysqli->prepare("INSERT INTO objects (custom_namespace, rdf_about, archive, title, type, url, origin, provenance, place_of_composition, shelfmark, freeculture, full_text_url, full_text_plain, is_full_text, image_url, source, metadata_xml_url, metadata_html_url, text_divisions, ocr, thumbnail_url, notes, file_format, date_created, date_updated, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW(), ?)");
    $statement->bind_param("ssssssssssssssssssssssss", $customNamespace, $rdfAbout, $archive, $title, $type, $url, $origin, $provenance, $compositionPlace, $shelfmark, $freeculture, $fullTextURL, $fullTextPlain, $isFullText, $imageURL, $source, $metadataXMLURL, $metdataHTMLURL, $textDivisions, $ocr, $thumbnailURL, $notes, $fileFormat, $userID);
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

    $comments = array();
    // TODO: perform some sort of check to make sure all fields are set in $_POST before this loop.
    foreach ($_POST as $key=>$item) {
      if (preg_match("/^comments/", $key) || preg_match("/^suggested/", $key) || preg_match("/-available$/", $key)) {
        $comments[$key] = htmlspecialchars(trim($item));
      }
    }

    $comments_rdf_about            = $comments["comments-rdf-about"];
    $comments_date                 = $comments["comments-date"];
    $comments_provenance           = $comments["comments-provenance"];
    $comments_place_of_composition = $comments["comments-place-of-composition"];
    $comments_is_part_of           = $comments["comments-is-part-of"];
    $comments_has_part             = $comments["comments-has-part"];
    $comments_text_divisions       = $comments["comments-text-divisions"];
    $comments_notes                = $comments["comments-notes"];
    $custom_namespace_available    = isset($comments["custom-namespace-available"]) ? $comments["custom-namespace-available"] : NULL;
    $type_available                = isset($comments["type-available"]) ? $comments["type-available"] : NULL;
    $role_available                = isset($comments["role-available"]) ? $comments["role-available"] : NULL;
    $genre_required_available      = isset($comments["genre-required-available"]) ? $comments["genre-required-available"] : NULL;
    $genre_controled_available     = isset($comments["genre-controlled-available"]) ? $comments["genre-controlled-available"] : NULL;
    $date_available                = isset($comments["date-available"]) ? $comments["date-available"] : NULL;
    $url_available                 = isset($comments["url-available"]) ? $comments["url-available"] : NULL;
    $suggested_terms_type          = $comments["suggested-terms-type"];
    $suggested_terms_role          = $comments["suggested-terms-role"];
    $suggested_terms_genre         = $comments["suggested-terms-genre"];
	
	

   
	
	$statement_rdf = $mysqli->prepare("INSERT INTO comments_rdf_about(comments_rdf_about,user_id) VALUES(?,?)"); 
	$statement_rdf->bind_param("ss",$comments_rdf_about,$userID);
	$statement_rdf->execute();
	$statement_rdf->close();
	
	$statement_date = $mysqli->prepare("INSERT INTO comments_date(comments_date,user_id) VALUES(?,?)"); 
	$statement_date->bind_param("ss",$comments_date,$userID);
	$statement_date->execute();
	$statement_date->close();
	
	$statement_provenance = $mysqli->prepare("INSERT INTO comments_provenance(comments_provenance,user_id) VALUES(?,?)"); 
	$statement_provenance->bind_param("ss",$comments_provenance,$userID);
	$statement_provenance->execute();
	$statement_provenance->close();
	
	$statement_place_of_composition = $mysqli->prepare("INSERT INTO comments_place_of_composition(comments_place_of_composition,user_id) VALUES(?,?)"); 
	$statement_place_of_composition->bind_param("ss",$comments_place_of_composition,$userID);
	$statement_place_of_composition->execute();
	$statement_place_of_composition->close();
	
	$statement_is_part_of = $mysqli->prepare("INSERT INTO comments_is_part_of(comments_is_part_of,user_id) VALUES(?,?)"); 
	$statement_is_part_of->bind_param("ss",$comments_is_part_of,$userID);
	$statement_is_part_of->execute();
	$statement_is_part_of->close();
	
	$statement_has_part = $mysqli->prepare("INSERT INTO comments_has_part(comments_has_part,user_id) VALUES(?,?)"); 
	$statement_has_part->bind_param("ss",$comments_has_part,$userID);
	$statement_has_part->execute();
	$statement_has_part->close();
	
	
	$statement_text_divisions = $mysqli->prepare("INSERT INTO comments_text_divisions(comments_text_divisions,user_id) VALUES(?,?)"); 
	$statement_text_divisions->bind_param("ss",$comments_text_divisions,$userID);
	$statement_text_divisions->execute();
	$statement_text_divisions->close();
	
	
	$statement_notes = $mysqli->prepare("INSERT INTO comments_notes(comments_notes,user_id) VALUES(?,?)"); 
	$statement_notes->bind_param("ss",$comments_notes,$userID);
	$statement_notes->execute();
	$statement_notes->close();
	
    $statement = $mysqli->prepare("INSERT INTO comments ( date_available,custom_namespace_available, type_available, role_available, genre_required_available, genre_controlled_available, url_available, suggested_terms_type, suggested_terms_role, suggested_terms_genre, user_id) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
    $statement->bind_param("sssssssssss", $date_available,$custom_namespace_available, $type_available, $role_available, $genre_required_available, $genre_controled_available, $url_available, $suggested_terms_type, $suggested_terms_role, $suggested_terms_genre, $userID);
    $statement->execute();
	
	
	
	
	

    if ($statement->affected_rows === 0): ?>
    <div class="container">
      <div class="row page-header">
        <h1 class="text-danger text-center">Form Submission Failed</h1>
      </div>

      <div class="row">
        <div class="col-xs-12">
          <p>This isn't good. INSERT shouldn't fail.</p>
          <p>Unless the server is down. No, that can't be it, you're reading this.</p>
          <p>Maybe the MySQL Database is disconnected. No, that can't be it either, we'd get a database connection error earlier...</p>
        </div>
      </div>
    </div>
    <?php else: ?>
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
    endif; // if ($statement->affected_rows === 0)
  endif; // if ($statement->num_rows > 0)
endif; // if (!isset($_POST["submitted"]))

require "includes/footer.php";
