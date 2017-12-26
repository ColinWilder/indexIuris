<?php
/* 
    Copyright (C) 2016-2017 - University of South Carolina

    License: GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.
*/
/**
 * @file functions.php
* Functions used throughout the website.
*/


require_once('config.php');


//TODO: add error checking!!!
function parseDate($dateString){
  $parts = explode('-',$dateString);
  if (sizeof($parts)==1) {
    return array((int)$parts[0]);
  }
  else if (sizeof($parts)==2){
    $years = array();
    while ($parts[0]<=$parts[1]){
      $years[] = $parts[0];
      $parts[0]++;
    }
    return $years;
  }
  else return 0;
}


/* function indexDocument($doc){
 * indexes a document into solr
 * does not commit
 *
 * @param {array} $doc:
 *   associative array in the following format:
 *   $doc = array(
 *     'field1' => 'value',
 *     'field2' => array('value1','value2'),
 *     'field3' => 1234,
 *     etc
 *   );
 *   the keys correspond to a field in the solr schema;
 *   values are values to be indexed
 * @return {int}: result value of postJsonDataToSolr();
 *
 */
function indexDocument($doc){
  //print 'indexDocument()<br>';
  $data = array(
      'add' => array (
          'doc' => $doc
      )
  );
  $data_string = json_encode($data);
  print 'curl_exec() done <br>';
  //print_r($doc);
  print '<br>';
  return postJsonDataToSolr($data_string, 'update');
}
/* function commitIndex()
 * commits all pending changes in solr
 * @param {none}
 * @return {int}: result value of postJsonDataToSolr();
 */
function commitIndex(){
  $data = array(
      'commit' => new stdClass()
  );
  $data_string = json_encode($data);
  return postJsonDataToSolr($data_string, 'update');
}
/* function delete_all()
 * deletes all documents in solr
 * @param {none}
 * @return {int}: result value of postJsonDataToSolr();
 */
function delete_all(){
  print 'delete_all();<br>';
  $data = array(
      'delete' => array(
            'query' => '*:*'
          ),
      'commit' => new stdClass()
  );
  $data_string = json_encode($data);
  print $data_string;
  return postJsonDataToSolr($data_string, 'update');
}

function deleteRecordFromSolr($id){
  print 'delete_all();<br>';
  $q = 'id:'.$id;
  $data = array(
      'delete' => array(
            'query' => $q
          ),
      'commit' => new stdClass()
  );
  $data_string = json_encode($data);
  print $data_string;
  return postJsonDataToSolr($data_string, 'update');
}


/* function postJsonDataToSolr($data, $action)
 * posts a json-formatted string to solr
 *
 * @param {string} $data:
 *   json-formatted string, may containg any solr commants, or documents
 * @param {string} $action:
 *   solr handler eg. 'update', 'select'
 * @return {bool}:
 *   returns TRUE is sucessful, otherwise FALSE
 *   sets appropriate global $lastError message
 */
function postJsonDataToSolr($data, $action){
  global $solrUrl;
  $url = $solrUrl.$action;
  print $url;
  //validate json data
  if (json_decode($data)==NULL){
    echo '<div class="col-xs-12"><h1 class="text-danger">postJsonData() invalid Json</h1><p><pre>'.json_last_error().'<br>'.json_last_error_msg().'</pre></p></div>';
    $lastError = 'postJsonDataToSolr(): Invalid Json: '.json_last_error().' - '.json_last_error_msg();
    return false;
  }

  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/json',
      'Content-Length: ' . strlen($data))
      );

  $result = curl_exec($ch);
  //print_r($data);
  print_r($result);
  return true;
}




/*
 * function getResultsFromSolr
 * performs search on solr and returns mathing documents
 * @param {array} $query: associative array of search parameters
 *  $query['isFullText'] = (bool)
    $query['queryArray'] = array();
    $query['start'] = (int,0);
    $query['rows'] = (int,20);
 *
 * @return {array} or {FALSE} if an error ocurred
 */
function getResultsFromSolr($query){

  $queryString = buildSolrQuery($query);


  $ch = curl_init();
  curl_setopt_array($ch, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $queryString,
  ));

  $jsonResponse = curl_exec($ch);
  if (curl_error($ch)){
    throw new Exception('Unable to connect to search engine.');
  }
  //$jsonResponse = file_get_contents($queryString);

  if (DEBUGGING) {
    print $queryString;
  }

  if ($jsonResponse === false) return false;

  $responseArray = json_decode($jsonResponse,true);

  $searchResults = $responseArray/*["response"]*/;


  return $searchResults;

}

function getBrowseResultsFromSolr($query){
  $queryString = buildSolrQuery($query)."&facet.limit=-1";


  $ch = curl_init();
  curl_setopt_array($ch, array(
      CURLOPT_RETURNTRANSFER => 1,
      CURLOPT_URL => $queryString,
  ));

  $jsonResponse = curl_exec($ch);
  if (curl_error($ch)){
    throw new Exception('Unable to connect to search engine.');
  }
  //$jsonResponse = file_get_contents($queryString);

  if (DEBUGGING) {
    print $queryString;
  }

  if ($jsonResponse === false) return false;

  $responseArray = json_decode($jsonResponse,true);

  $searchResults = $responseArray/*["response"]*/;


  return $searchResults;
}


/*
 * function buildSolrQuery
 * builds url query for json results from solr based on parameters
 * @param {array} $query: associative array of search parameters
 * @return {string} url-formatted solr query for json-formatted results

 */
function buildSolrQuery($query){

  $queryString = 'q=';

  $queryArray = $query['queryArray'];

	$counter=0;
	foreach ($queryArray as $queryPartial){ //$queryPartial = array($_GET['f'][$counter],$_GET['op'][$counter],$query);
		if ($queryPartial[2]=='') continue; //check it's not empty
    //make sure to undo url encoding
    $queryPartial[2] = urldecode($queryPartial[2]);

		if($counter++ !=0){
			$queryString = $queryString.$queryPartial[1]/*op*/.'+';
		}
		if ($queryPartial[0]=='all'){
			$queryString = $queryString.buildQueryForAllFields($queryPartial[2]);
		}
		else if ($queryPartial[0]=='contributor'){//temporary
			$queryString = $queryString.buildQueryForContributors($queryPartial[2]);
		}
		else {
			$queryString = $queryString.$queryPartial[0]/*field*/.':('.urlencode($queryPartial[2]).')%0A';
		}


  }

  //filter queries
  $counter=0;
  foreach ($query['fq'] as $fq){
    $queryString = $queryString.'&fq='.urlencode($query['fq_field'][$counter++]).':'.urlencode($fq);
  }


  global $solrUrl;
  global $solrResultsHighlightTag;

  $queryString = $solrUrl
    .'select?'.$queryString.'&start='.$query['start'].'&rows='.$query['rows']
    .'&wt=json&hl=true&hl.simple.pre='.urlencode('<'.$solrResultsHighlightTag.'>')
    .'&hl.simple.post='.urlencode('</'.$solrResultsHighlightTag.'>')
    .'&hl.fl=*&facet=true&debugQuery=on';
if (DEBUGGING) {
print $queryString;
}
global $facetFields;
foreach ($facetFields as $key=>$val){
  $queryString = $queryString.'&facet.field='.$key;
    /*'&facet.field=publisher_facet&facet.field=publisher_location_facet'
    .'&facet.field=language&facet.field=subject_heading_facet&facet.field=composer_facet'
    .'&facet.field=years&facet.field=arranger_facet&facet.field=illustrator_facet&facet.field=lyricist_facet&stats=true&stats.field=years&indent=true';*/
}

$queryString = $queryString.'&stats=true&stats.field=years&indent=true';
    /*
     * Archive (Digital collection)
Contributing Institution
Type of content
LC Subject Headings
File Format
Language
Copyright (Use Rights)
Date (slider to select range)
     * */

  return $queryString;
}

function buildQueryForContributors($query){
	$queryString = '';
	global $contribtypes;
	foreach ($contribtypes as $field=>$value){
		$queryString = $queryString.$field.':('.urlencode($query).')%0A';
	}
	return $queryString;
}

/* function buildQueryForAllFields($query)
 * builds a solr query when "search all fields" is selected
 * also adds weight to certain fields
 * @param {string} $query:
 *   query value
 * @return {string}: a solr query that will search all fields for $query
 */
function buildQueryForAllFields($query){
  $query = preg_replace('/".*?"(*SKIP)(*FAIL)| (AND|OR|NOT) (*SKIP)(*FAIL)| +(?!$)/', ' AND ', $query);
  $queryString = '';
  global $searchFields;
  foreach ($searchFields as $field){
    $queryString = $queryString.$field.':('.urlencode($query);
    if($field == "exact_words"){
        $queryString = $queryString.')^6%0A';
    }
    else if ($field =="1"){
      $queryString = $queryString.')^4%0A';
    }
    else if ($field =="2"){
      $queryString = $queryString.')^3';
    }
    else if ($field =="3"){
      $queryString = $queryString.')^2%0A';
    }
    else {
      $queryString = $queryString.')%0A';
    }
  }
  return $queryString;
}

/* function buildFacetFilterQuery($facet,$query)
 * builds a facet filter query based of the current search terms
 * and the corresponding facet and query
 *
 * @param {string} $facet:
 *   facet to narrow down by
 * @param {string} $query:
 *   value to filter by
 * @return {string}: href-ready value for a filter query link
 */
function buildFacetFilterQuery($facet,$query){
  $newGet = $_GET;
    //$rows = array("start" => "0");
  //print_r($rows);
  //array_merge($rows,$newGet);
  //print_r($newGet);
  $newGet['start'] = 0;
  //print_r($newGet);
  $newQuery = http_build_query($newGet);

  return $_SERVER['PHP_SELF'].'?'.$newQuery.'&fq[]='.urlencode(($query=='')? '""':('"'.escapeDoubleQuotes($query)).'"').'&fq_field[]='.$facet;
}

function escapeDoubleQuotes($string){
  return preg_replace("/\"/", '\\"', $string);
}


/* function buildFacetBreadcrumbQuery($facet, $query){
 * builds a breadcrumb href to undo a given facet filter query
 *
 * @param {string} $facet:
 *   facet to narrow down by
 * @param {string} $query:
 *   value to filter by
 * @return {string}: href-ready value for a breadbrumb filter query link
 */
function buildFacetBreadcrumbQuery($facet, $query){
  $newGet = array();
  foreach ($_GET as $key => $value){
    $newGet[$key] = $value;
  }
  $new_fq = array();
  $new_fq_field = array();
  $counter=0;
  //debug
  //print_r($newGet);
  foreach ($newGet['fq_field'] as $fq_field){
    //debug
    //print $fq_field.'__'.$newGet['fq'][$counter].'nn'.$query.'--'.'<br>';
    if (!($fq_field==$facet && $newGet['fq'][$counter]=='"'.$query.'"')){
      $new_fq[] = $newGet['fq'][$counter];
      $new_fq_field[] = $fq_field;
    }
    $counter++;
  }

  $newGet['fq_field'] = $new_fq_field;
  $newGet['fq'] = $new_fq;
  //print_r($newGet);
  $newGet['start'] = 0;
  //print_r($newGet);

  //debug
  //print_r($newGet);
  $newQuery = http_build_query($newGet);
  return $_SERVER['PHP_SELF'].'?'.$newQuery;
}


function idHasImage($id){
  $fileList = getImagesForId($id);
  if (empty($fileList)){
    return false;
  }
  else return true;
}







?>

