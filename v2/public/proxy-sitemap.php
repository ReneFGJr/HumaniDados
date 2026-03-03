<?php

header("Content-Type: application/xml");
header("Access-Control-Allow-Origin: *");

$url = "https://www.ufrgs.br/proplan/sitemap.xml";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
