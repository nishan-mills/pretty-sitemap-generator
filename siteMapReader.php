<!doctype html>
<html class="no-js" lang="">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="manifest" href="site.webmanifest">
  <link rel="apple-touch-icon" href="icon.png">
  <!-- Place favicon.ico in the root directory -->

  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  <![endif]-->

  <div class="container">
    <h1>Sitemap</h1>

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
      echo "<ul class='accordion'>";
        displayArrayRecursively($Formatted_List_Array , $URLS , $httpProtocol);
      echo "</ul>";

      function displayArrayRecursively($array, $urlArray , $keysString = 'http://')
      {
        if (is_array($array)) {
          foreach ($array as $key => $value) {
            if(!empty($key)){
              $CurrentkeysString = $keysString.$key.'/';
              if(is_array($value)){
                echo "<li class='has-child'><span class='expander'></span>";
              }
              else{
                echo "<li>";
              }
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
  </div>

  <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
  <script>
    window.ga = function () { ga.q.push(arguments) }; ga.q = []; ga.l = +new Date;
    ga('create', 'UA-XXXXX-Y', 'auto'); ga('send', 'pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async defer></script>

  <script src="src/js/vendor/jquery.js"></script>
  <script src="assets/js/source.dev.js"></script>
</body>

</html>