<?php

function is_valid_url($str)
{
    return filter_var($str, FILTER_VALIDATE_URL);
}

// $allowed_hosts = array("localhost", "host.com");
// This is a simple SSRF script that will return the contents of the URL
// passed to it. It is intended to be used as a test for the SSRF
// vulnerability in the "SSRF" challenge.
// show file content
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    if (strpos($file, 'ressource') !== false and strpos($file, '/..') == false and strpos($file, '/.') == false) {
        if (!is_valid_url($file) && (strpos($file, '.jpg') !== false or strpos($file, '.png') !== false)) {
            echo '<img src="', $file, '" alt="', '" />';
        } else {
            echo file_get_contents($file);
        }
    } else {
        echo "Not allowed";
    }
}

// ssrf request ifconfig.pro
if (isset($_GET['curl'])) {
    $url = $_GET['curl'];
    // make sure the url is only from localhost
    if (strpos($url, 'localhost') !== false) {
        $url = "http://" . $url;
    } else {
        echo "Not allowed";
    }
    // ssrf request with curl
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    echo $output;
}




// ssrf request with fopen
if (isset($_GET['fopen'])) {
    // sanitize the uri only to a ressource directory

    if (strpos($_GET['fopen'], 'ressource') !== false and strpos($_GET['fopen'], '/..') == false and strpos($_GET['fopen'], '/.') == false) {
        $file = fopen($_GET['fopen'], "r");
        if (strpos($_GET['fopen'], '.jpg') !== false or strpos($_GET['fopen'], '.png') !== false) {
            echo '<img src="', $_GET['fopen'], '" alt="', '" />';
        } else {
            echo fread($file, filesize($_GET['fopen']));
        }
        fclose($file);
    } else {
        echo "Not allowed";
    }
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
                <p><b>file_get_contents</b> | http://localhost:8080/ssrf_protected.php?file=http://ifconfig.pro </p>
            </div>
        </div>
        <div class="card text-center mb-3">
            <div class="card-body">
                <p><b>fopen</b> | http://localhost:8080/ssrf_protected.php?fopen=/etc/passwd </p>
            </div>
        </div>
        <div class="card text-center mb-3">
            <div class="card-body">
                <p><b>curl</b> | http://localhost:8080/ssrf_protected.php?curl=http://ifconfig.pro</p>
            </div>
        </div>
    </div>
</body>

</html>