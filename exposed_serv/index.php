<?php

// This is a simple SSRF script that will return the contents of the URL
// passed to it. It is intended to be used as a test for the SSRF
// show file content
if (isset($_GET['file'])) {
  echo file_get_contents($_GET['file']);
}

// ssrf request ifconfig.pro
if (isset($_GET['curl'])) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $_GET['curl']);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $output = curl_exec($ch);
  curl_close($ch);
  echo $output;
}


// ssrf request with fopen
if (isset($_GET['fopen'])) {
  $file = fopen($_GET['fopen'], "r");
  echo fread($file, filesize($_GET['fopen']));
  fclose($file);
}

?>

<html>

<head>
  <title>SSRF</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
  <div class="container">
    <h1 class="text-center">SSRF</h1>
    <div class="card text-center mb-3">
      <div class="card-body">
        <p><b>file_get_contents</b> | http://localhost:8080/?file=http://ifconfig.pro </p>
      </div>
    </div>
    <div class="card text-center mb-3">
      <div class="card-body">
        <p><b>fopen</b> | http://localhost:8080/?fopen=/etc/passwd </p>
      </div>
    </div>
    <div class="card text-center mb-3">
      <div class="card-body">
        <p><b>curl</b> | http://localhost:8080/?curl=http://ifconfig.pro</p>
      </div>
    </div>
  </div>
</body>

</html>