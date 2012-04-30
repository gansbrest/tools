<?php

$optionsArr = getopt('a:s:b:p:e:h');

if (isset($optionsArr['h'])) {
  echo "\n
get_s3_url.php is a script to generate full uld based access to the private assets stored on s3.
Based on http://docs.amazonwebservices.com/AmazonS3/latest/dev/RESTAuthentication.html#RESTAuthenticationQueryStringAuth

Available options: 
REQUIRED
-a - Amazon Access Key 
-s - Amazon Secret Key 
-b - s3 bucket name 
-p - relative path to the asset 
-e - expiration time in seconds    
Optional
-h - this help screen \n";

  exit;
}

// Making sure all required options were passed
// we could use :: for that but it's 5.3 only
if (!isset($optionsArr['a']) || !isset($optionsArr['s']) || !isset($optionsArr['b']) || !isset($optionsArr['p']) || !isset($optionsArr['e'])) {
  echo "
Some of the required options are missing.
Use the -h flag to get the list of available options.\n\n";

  exit;
}

$expires = time() + $optionsArr['e'];

$stringToSign = "GET\n" .
  "\n" .
  "\n" .
  $expires . "\n" .
  "/" . $optionsArr['b'] . "/" . $optionsArr['p'];

$signature =
  urlencode(
    base64_encode(
      hash_hmac('sha1', utf8_encode($stringToSign), $optionsArr['s'], TRUE)
    )
  );

$finalUrl = 'https://s3.amazonaws.com/' . $optionsArr['b'] . '/' . $optionsArr['p'] . '?AWSAccessKeyId=' . $optionsArr['a'] . '&Signature=' . $signature . '&Expires=' . $expires;

echo $finalUrl . "\n";
?>
