<?php
/*
    Copyright (C) 2016-2017 - University of South Carolina

    License: GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
*/
/* search-results.php
 * this field performs a search based on parameters in the $_GET request in index.php
 * called by: index.php
 */

//is the search full-text?
$searchQuery['isFullText'] = (isset($_GET['full-text-search'])) ? $_GET['full-text-search'] : false;

$searchQuery['queryArray'] = $queryArray;

$searchQuery['start'] = (isset($_GET['start'])) ? $_GET['start'] : 0;

$searchQuery['rows'] = 20;

$searchQuery['fq'] = (isset($_GET['fq'])) ? $_GET['fq']: array();
$searchQuery['fq_field'] = (isset($_GET['fq_field'])) ? $_GET['fq_field']: array();

try{
$solrResponse = getResultsFromSolr($searchQuery); //this is where the magic happens
}
catch (Exception $e) {
	print '<h1 class="text-danger text-center">'.$e->getMessage().'</h1>';
	//TODO: email admin to inform that solr is down
	die();
}
//var_dump ($solrResponse);
$searchResponse = $solrResponse['response'];

$searchFacetCounts = $solrResponse['facet_counts'];
$searchHighlighting = $solrResponse['highlighting'];
$searchYearStats = $solrResponse['stats']['stats_fields']['years'];

$minYear = (isset($_GET['minYear']))? $_GET['minYear']: $searchYearStats['min'];
$maxYear = (isset($_GET['maxYear']))? $_GET['maxYear']: $searchYearStats['max'];
$rMinYear = (isset($_GET['rMinYear']))? $_GET['rMinYear']: $searchYearStats['min'];
$rMaxYear = (isset($_GET['rMaxYear']))? $_GET['rMaxYear']: $searchYearStats['max'];

//print ($maxYear);

$oldFq = (isset($_GET['fq'])) ? $_GET['fq']: array();
$oldFqField = (isset($_GET['fq_field'])) ? $_GET['fq_field']: array();

if (count($oldFq)!=count($oldFqField)){
	$oldFq = array();
	$oldFqField = array();
}

$newQuery = $_GET;
$counter=0;
$newFq = array();
$newFqField = array();
foreach ($oldFqField as $fqField){
	if ($fqField!='years'){
		$newFqField[] = $fqField;
		$newFq[] = $oldFq[$counter++];
	}
}

$newQuery['fq'] = $newFq;
$newQuery['fq_field'] = $newFqField;
$newQuery['minYear'] = $minYear;
$newQuery['maxYear'] = $maxYear;


$currentQuery = '//'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.http_build_query($newQuery);

$jsonResponse;

$searchResults = $searchResponse['docs'];

if ($searchResponse['numFound']==0):?>
<div class="container-fluid">
  <div class="row">
      <div class="col-xs-8 col-xs-offset-2">
        <h1 class="text-primary">No results</h1>
      </div>
  </div>
</div> <!-- container-fluid -->
<?php
require "includes/footer.php";
die();

endif;

//prints next/previous buttons and total results count
printResultsNavigation($searchResponse['start'],$searchResponse['numFound'],$searchQuery['rows']);
?>
<script>
var minYear = <?php print $minYear; ?>;
var maxYear = <?php print $maxYear; ?>;
var rMinYear = <?php print $rMinYear; ?>;
var rMaxYear = <?php print $rMaxYear; ?>;
var currentQuery = <?php print '"'.$currentQuery.'"'; ?>;
</script>
<div class="row">
<?php
/*
 * The following displays the facets column
 */?><div class="col-xs-12 col-md-3">
		<div class="col-xs-12"><h4>Facets:</h4>
			<div class="panel-group" id="accordion">
  <?php
  $counter=1;
  foreach ($facetFields as $facetField => $facetTitle):?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a class="accordion-toggle<?php print in_array($facetField,$searchQuery['fq_field'])? ' accordion-opened':'';?>" data-toggle="collapse" href="#collapse<?php print $counter;?>"><?php print $facetTitle?>&nbsp;</a>
      </h4>
    </div>
    <div id="collapse<?php print $counter;?>" class="panel-collapse collapse<?php print in_array($facetField,$searchQuery['fq_field'])? ' in':'';?>">
      <div class="panel-body">
      	<?php
        $currentFacet = $facetField;
      	$facets = $searchFacetCounts['facet_fields'][$currentFacet];
      	for($i=0; $i<sizeof($facets); $i++):
      		if ($facets[$i+1]==0){
      			$i++;
      			continue;
      	  }
      	$isBreadcrumbSet = false;
      	if (in_array($currentFacet, $searchQuery['fq_field'])):
      	  if (in_array('"'.$facets[$i].'"',$searchQuery['fq'])):
      	    $isBreadcrumbSet = true;
      	?>
      	  <a href="<?php print buildFacetBreadcrumbQuery($currentFacet, $facets[$i]);?>"><strong>(X)</strong></a>
      	<?php
      	  endif;
      	endif;?>
      	<a href="<?php print buildFacetFilterQuery($currentFacet, $facets[$i]);?>"><?php print ($isBreadcrumbSet) ? '<strong><em>' : '';?><?php print ($facets[$i]=='')? "None":$facets[$i];?> (<small><strong><?php print $facets[$i+1]; $i++;?></strong></small>)<?php print ($isBreadcrumbSet) ? '</em></strong>' : '';?></a><br>
      	<?php endfor;?>
      </div>
    </div>
  </div>
  <?php
  $counter++;
  endforeach;?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <span class="accordion-toggle accordion-opened">
        Date range&nbsp;</span>
      </h4>
    </div>
    <div id="collapsez" class="panel-collapse collapse in">
      <div class="panel-body">
        <p>
         <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
        </p>
        <div id="slider-range"></div>
      </div>
    </div>
  </div>

</div>
		</div>

</div><!-- facets column-->
<?php
/*
 * The following displays the search results
 */?>
<div class="col-xs-12 col-md-9" id="search-results-column">
<?php

//build breadcrumbs at the top of search results
foreach ($facetFields as $facetField => $facetTitle):

			$currentFacet = $facetField;
			$facets = $searchFacetCounts['facet_fields'][$currentFacet];
			for($i=0; $i<sizeof($facets); $i++):
				if ($facets[$i+1]==0){
					$i++;
					continue;
				}
			  $isBreadcrumbSet = false;
			  if (in_array($currentFacet, $searchQuery['fq_field'])):
				  if (in_array('"'.$facets[$i].'"',$searchQuery['fq'])):
					  $isBreadcrumbSet = true;
			      ?>
				    <a href="<?php print buildFacetBreadcrumbQuery($currentFacet, $facets[$i]);?>"><strong>(X)</strong></a> <span class="text-primary"><?php print ($facets[$i]=='')? "None":$facets[$i];?></span>&nbsp;&nbsp;&nbsp;
			      <?php
				  endif;
			  endif;?>
		  <?php
			  $i++;
		  endfor;?>

<?php
endforeach;
print '<br><br>'; //to separate breadcrumbs from search results

$displaySearchResults = array();

foreach ($searchResults as $result){
	$displayResult = array();
	$url = $result['id']; //Fix this!!!
	$highlightArray = $searchHighlighting[$url];

	//title
	$displayResult['title'] = isset($highlightArray['title']) ? $highlightArray['title'][0] : $result['title'];

	//brief display
	foreach ($briefDisplayFields as $field){
		if (!isset($result[$field])) continue;//TODO: fix this
		$displayResult[$field] = isset($highlightArray[$field]) ? $highlightArray[$field][0] : $result[$field];
	}
                  
	global $solrFieldNames;
                  
	foreach ($highlightArray as $key => $value){
		if (in_array($key,$briefDisplayFields)) continue;
		if (!isset($solrFieldNames[$key])) continue;
		$displayResult[$key] = $value[0];
	}
	$displayResult['url'] = $url;
	$displaySearchResults[] = $displayResult;
	//TODO: fix this
	$result['url'] = $result['id'];
}
?>

<div class="panel-group" id="accordion">
<?php
$counter=1;
foreach($displaySearchResults as $result):?>
  <div class="panel panel-default">
    <div class="panel-heading container-fluid panel-heading-results">
      <div class="col-xs-11">
        <h3><a class="results-title" href="item?id=<?php print $result['url']?>"><?php print $result['title']?></a></h3>
      </div>
			<div class="col-xs-8 col-md-2 pull-left">
				<?php
				$imageList = getImagesForId($result['url']);
				if (sizeof($imageList)>0):
				?>
				<a href="item?id=<?php print $result['url']?>"><img class="img-responsive" src="<?php print $imageList[0] ?>"></a>
			  <?php endif; ?>
			</div>
			<div class="col-xs-12 col-md-10 pull-right">
				<table>
      <?php foreach ($briefDisplayFields as $field):
				//check if key exists
				if (!array_key_exists($field,$result)){
					continue;
				}
				//check if blank
				if (is_array($result[$field])){
					$emptyVar = array_filter($result[$field]);//gotta love php 5.3
					if (empty($emptyVar)) continue;
				}
				else if (trim($result[$field])==""){
					continue;
				}?>
				<tr>
        <td style="vertical-align: top; padding-right: 2em;">
          <strong><?php
		  print $solrFieldNames[$field]['field_title'];
			if (is_array($result[$field]) && count($result[$field])>1){
				print 's';
			}
		  ?>:</strong>
		</td>
		<td>
		  <?php
		  $value = $result[$field];
		  if (is_array($value)){
		    foreach ($value as $val){
		  	  print $val.'<br>';
		    }
		  }
		  else{
		    print $value;
		  }
		  ?>
		</td>
</tr>
      <?php endforeach;
				$highlightArray = $searchHighlighting[$result['url']];
				if (!empty($highlightArray)){
					if (array_key_exists('text_t',$highlightArray)){
						?>
						<tr>
							<td><strong><?php print $solrFieldNames['text_t']['field_title'];?>:</strong></td>
							<td><?php print $highlightArray['text_t'][0];?></td>
						</tr>
						<?php
					}
					else if (array_key_exists('subject_heading',$highlightArray)){
						?>
						<tr>
							<td><strong><?php print $solrFieldNames['subject_heading']['field_title'];?>:</strong></td>
							<td><?php print $highlightArray['subject_heading'][0];?></td>
						</tr>
						<?php
					}
					else if (array_key_exists('notes',$highlightArray)){
						?>
						<tr>
							<td><strong><?php print $solrFieldNames['notes']['field_title'];?>:</strong></td>
							<td><?php print $highlightArray['notes'][0];?></td>
						</tr>
						<?php
					}
					else {
						// get the first key of the highlight array
						$keys = array_keys($highlightArray);
						$hKey = array_shift($keys);

						$condition = true;
                                                                                                            
                                                                                                            if ($hKey == "exact_words"){
                                                                                                                #can't highlight this field; move on to the next
                                                                                                                if ($keys !=[]){
                                                                                                                    $hKey = array_shift($keys);
                                                                                                                } else {
                                                                                                                    $condition = false;
                                                                                                                }
                                                                                                            }
                                                
						//we need to check if we have already displayed this field
						//if we have, then we skip
						foreach($briefDisplayFields as $field){
							if ($hKey == $field){
								$condition=false;
								break;						
						}
						}
						if ($condition):
						?>
						<tr>
							<td><strong><?php print $solrFieldNames[$hKey]['field_title'];?>:</strong></td>
							<td><?php print $highlightArray[$hKey][0];?></td>
						</tr>
						<?php
						endif;
					}
				}
				foreach ($highlightArray as $key => $value){
					if (in_array($key,$briefDisplayFields)) continue;
					if (!isset($solrFieldNames[$key])) continue;
					$displayResult[$key] = $value[0];
				}
			?>


		</table>
		</div>
  </div>
</div>
<?php
$counter++;
endforeach;?>
</div><!-- #accordion -->
</div> <!-- search-results-column -->

<?php

/*functions*/
function printResultsNavigation($start,$numFound,$rows){
	?>
	<h3 class="text-right">Showing results <?php print ($start+1)?> to <?php print ($numFound<=$start+$rows ) ?($numFound):($start+$rows );?> of <?php print ($numFound)?></h3>
	<p class="text-right">
	<?php if ($start>0):?>
		<a href="<?php
		$oldQuery = $_GET;
		$oldQuery['start'] = $oldQuery['start']-$rows;
		$newQuery = http_build_query($oldQuery);
		print $_SERVER['PHP_SELF'].'?'.$newQuery?>" class="btn btn-default">Previous</a>
	<?php endif;?>
	<?php if ($numFound>($start+$rows)):?>
		<a href="<?php
		$oldQuery = $_GET;
		$oldQuery['start'] = $oldQuery['start']+$rows;
		$newQuery = http_build_query($oldQuery);
		print $_SERVER['PHP_SELF'].'?'.$newQuery?>" class="btn btn-default">Next</a>
	<?php endif;?>
	</p><?php
}

?>
</div> <!-- row -->
<?php printResultsNavigation($searchResponse['start'],$searchResponse['numFound'],$searchQuery['rows']);?>