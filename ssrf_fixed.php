<?php

// This is a simple SSRF script that will return the contents of the URL
// passed to it. It is intended to be used as a test for the SSRF
// vulnerability in the "SSRF" challenge.
// show file content
if (isset($_GET['file'])) {
    echo file_get_contents($_GET['file']);
}

// ssrf request ifconfig.pro
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    // make sure the url is only from localhost 
    if (strpos($url, 'localhost') !== false) {
        $url = "http://" . $url;
    } else {
        echo "Not allowed";
        exit();
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
        exit();
    }
}

// ssrf request with post
if (isset($_POST['animals'])) {
    echo $_POST['animals'];
    $file = fopen($_POST['animals'], "r");
    echo fread($file, filesize($_GET['fopen']));
    fclose($file);
}

?>

<html>

<head>
    <title>SSRF</title>
</head>

<body>
    <h1>SSRF</h1>
    <p>Simple SSRF script</p>
    <p>Usage: http://localhost:8080/ssrf.php?url=http://ifconfig.pro </a>
    <p>Usage: http://localhost:8080/ssrf.php?file=/etc/passwd</p>
    <p>Usage: http://localhost:8080/ssrf.php?fopen=/etc/passwd</p>
    <!-- SSRF form action post -->
    <form action="ssrf.php" id="carform" method="post">
        <input type="submit" id="submit_form">
    </form>
</body>

</html>