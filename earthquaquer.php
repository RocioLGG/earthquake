<?php

$xml_data = file_get_contents('http://www.ign.es/ign/RssTools/sismologia.xml');
$xml = simplexml_load_string($xml_data);


$earthquake_data = array();


foreach ($xml->channel->item as $item) {
    $title = explode(" ", (string)$item->title);
    $description = explode(" ", (string)$item->description);
    $partial = substr((string)$item->description, 48);
    $location = substr($partial, 0, (stripos($partial, 'en la') - 1));

    $earthquake_data[] = array(
        'date' => $title[1],
        'time' => $title[2],
        'link' => (string)$item->link,
        'description' => (string)$item->description,
        'magnitude' => $description[7],
        'location' => $location,
        'lat' => (string)$item->children("http://www.w3.org/2003/01/geo/wgs84_pos#")->lat,
        'long' => (string)$item->children("http://www.w3.org/2003/01/geo/wgs84_pos#")->long
    );
}

echo json_encode($earthquake_data, JSON_PRETTY_PRINT);
?>