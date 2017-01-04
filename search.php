<?

$url = "http://ucoz:8082/serverSearch.php";//ending of search.php

if(isset($_POST)){
   $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($_POST));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

  if ($result = curl_exec($curl) ) {//file_get_contents("http://ateliepavlenkos.esy.es/search.php")
     echo $result;
     curl_close($curl);
   }
}

?>
