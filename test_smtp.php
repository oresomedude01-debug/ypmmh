<?php
// Test SMTP connection
$host = 'mail.darularqam.com.ng';
$port = 465;
$timeout = 10;

echo "Testing connection to $host:$port ...\n";

$smtp = @fsockopen("ssl://$host", $port, $errno, $errstr, $timeout);
if ($smtp) {
    $banner = fgets($smtp, 512);
    echo "SUCCESS! Banner: $banner\n";
    fclose($smtp);
} else {
    echo "FAILED! Error $errno: $errstr\n";
}

// Also test port 587
echo "\nTesting connection to $host:587 ...\n";
$smtp2 = @fsockopen($host, 587, $errno2, $errstr2, $timeout);
if ($smtp2) {
    $banner2 = fgets($smtp2, 512);
    echo "SUCCESS on 587! Banner: $banner2\n";
    fclose($smtp2);
} else {
    echo "FAILED on 587! Error $errno2: $errstr2\n";
}
