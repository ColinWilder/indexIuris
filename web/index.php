<?php
/**
 * @file index.php
 * Prints the home page.
 */
$title = "Home";
$loginRequired = false;
require "includes/header.php";
?>

<div class="container">
  <div class="row page-header">
    <div class="col-xs-12">
      <h1>Index Iuris</h1>
      <p><samp>Index Iuris</samp> is a federation of digital projects that offer the original texts for the study of the legal history in western Europe, from Roman law to early modern civil codes, both secular and ecclesiastical.  Most of these texts are in Latin, which remained the language of law well beyond its use in other types of communication; some, however, are in the vernacular.</p>
    </div>
  </div>

  <div class="row" id="alerter" style="display: none;">
    <div class="col-xs-6 center-block">
      <div class="alert alert-warning">
        <h1>Warning</h1>
        <p>We've detected that you're using an outdated version of Internet Explorer. Not all functionalities inside this website will work as intended.</p>
        <p>We recommend you to <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie" target="_blank">upgrade to at least v10.0</a> or switch to another browser.</p>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-xs-12">
      <img src="img/logo.jpg" class="img-responsive pull-left" alt="Index Iuris Logo" style="margin-right: 20px;">

      <p><samp>Index Iuris</samp> allows researchers to search across all the materials contributed by the federation members.  This is especially useful in the study of legal history, because before the modern era, legal texts were often circulated beyond national boundaries, and legislators and jurists sometimes borrowed passages from earlier texts, either within or from outside their own, immediate legal domains.  Especially during the middle ages (ca. 500-1500), when religious and secular powers often co-operated in governance, and when both civil and canon law were taught in the universities to scholars who often sought a degree "in both laws" (doctor iuris utriusque), components of one legal tradition flowed easily into the other.  Yet, at the same time, there was much experimentation in the development of local, regional, royal, and papal law as independent enterprises.</p>

      <p>There is much that we do not know about the textual relationships of different sources and compilations of law; much work remains to be done, as well, on the continuing citation of particular legal maxims or principles.  By bringing together for easy, efficient, and comprehensive searching and assembly as many legal texts as are prepared in digital format, <samp>Index Iuris</samp> provides a research environment to investigate these and other questions.</p>

      <p>We are at present developing a pilot project, using a few member-projects.  Watch this site for prototype features!</p>

      <p>Please contact us if you have or are developing a digital project in legal history that you would be interested in bringing into the <samp>Index Iuris</samp> federation!</p>
    </div>
  </div>
</div>

<?php require "includes/footer.php"; ?>
