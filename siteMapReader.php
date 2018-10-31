<?php
	//Site map reader and priting them in a ordered list
	/*
		steps : 
		1. Add a configurable number that can be added in to break URL
		2. Order them inside the URLS
	*/ 

	/* Defining Variables */
	$URLS = array();
	$Formatted_List_Array = array();
	$fileToRead = "sitemaplatestAAA.xml";
	$depthToBreak = 5;
	$httpProtocol = "http://";

	/* Reading the XML file */
	$xml=simplexml_load_file($fileToRead) or die("Error: Cannot create object");
	
	/* Read all the URLS from XML and make them into one array */	
	foreach($xml->children() as $xml_url) {
		array_push($URLS , (string)$xml_url->loc);
	}
	
	/* Below segment Extracted from https://stackoverflow.com/questions/3094294/build-array-tree-from-urls */
	$Formatted_List_Array = array();
	foreach ($URLS as $url)
	{
    $url = str_replace($httpProtocol, '', $url);
    $parts = explode('/', $url , $depthToBreak );
    krsort($parts);

    $line_array = null;
    $part_count = count($parts);
    foreach ($parts as $key => $value)
    {
    	$pathObject = new stdClass();
			$pathObject->isPath = false;
			$pathObject->PathURL = $value;
			$pathObject->subpath = array();
      if ($line_array == null) {
        $line_array = array($value => $value);
      } else {
        $temp_array = $line_array;
        $line_array = array($value => $temp_array);
      }
    }
    $Formatted_List_Array = array_merge_recursive($Formatted_List_Array, $line_array);
	}

/* Redering out the links inside a expandable DropDown */
echo "<ul>";
	displayArrayRecursively($Formatted_List_Array , $URLS , $httpProtocol);
echo "</ul>";

function displayArrayRecursively($array, $urlArray , $keysString = 'http://')
{
  if (is_array($array)) {
    foreach ($array as $key => $value) {
    	if(!empty($key)){
    		$CurrentkeysString = $keysString.$key.'/';
    		echo "<li class='has-child'>";
	    		if (in_array($CurrentkeysString, $urlArray) || 
	    			in_array(substr($CurrentkeysString,  0 , strlen($CurrentkeysString)-1), $urlArray)) {
    				echo '<a href="'.$CurrentkeysString.'">';
    					echo $key;
  					echo "</a>";
				  }
				  else{
				  	echo $key;
				  }
    			echo "<ul>";
      		displayArrayRecursively($value, $urlArray, $CurrentkeysString);
    			echo "</ul>";
  			echo "</li>";
    	}
    }
  }
}
?>