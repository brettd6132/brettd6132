<?php
$timestamp = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: $timestamp");
header("Last-Modified: $timestamp");
header("Pragma: no-cache");
header("Cache-Control: no-cache, must-revalidate");
header("Content-type: image/svg+xml");
function incrementFile($filename): int {
    if (file_exists($filename)) { $fp = fopen($filename, "r+") or die("Failed to open the file.");
        flock($fp, LOCK_EX);
        $count = fread($fp, filesize($filename)) + 1;
        ftruncate($fp, 0);
        fseek($fp, 0);
        fwrite($fp, $count);
        flock($fp, LOCK_UN);
        fclose($fp); }
    else { $count = 1;
        file_put_contents($filename, $count); }
    return $count; }
function shortNumber($num) { $units = ['', 'K', 'M', 'B', 'T'];
    for ($i = 0; $num >= 1000; $i++) { $num /= 1000; }
    return round($num, 1) . $units[$i]; }
function curl_get_contents($url): string { $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response; }
$message = incrementFile("views.txt");
$params = [
    "label" => "Views",
    "logo" => "github",
    "message" => shortNumber($message),
    "color" => "purple",
    "style" => "for-the-badge"
];
$url = "https://img.shields.io/static/v1?" . http_build_query($params);
echo curl_get_contents($url);
?> 
