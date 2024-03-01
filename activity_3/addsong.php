<?php
session_start();


$xml = new DOMDocument();
$xml->load("song.xml");

$sc = $_REQUEST['code'];
$st = $_REQUEST['Title'];
$sa = $_REQUEST['Album'];
$sg = $_REQUEST['Genre'];


$songsingers =explode(", ", $_REQUEST['Singers']) ;

// Create a new song element
$newSong = $xml->createElement("song");

// Create attributes
// $codeAttr = $xml->createAttribute("code");
$TitleElem = $xml->createElement("Title", $st);
$AlbumElem = $xml->createElement("Album", $sa);
$GenreElem = $xml->createElement("Genre", $sg);
$SingersElem = $xml->createElement("Singers");

foreach($songsingers as $sings){
    $newsinger = $xml->createElement("Singer",trim($sings));
    $SingersElem->appendChild($newsinger);
}

//   $codeAttr->value = $_POST['code'];
$newSong->setAttribute("code",$sc);
// $newSong->appendChild($codeAttr);
$newSong->appendChild($TitleElem);
$newSong->appendChild($AlbumElem);
$newSong->appendChild($GenreElem);
$newSong->appendChild($SingersElem);

    // Append the new song to the document root
    $xml->getElementsByTagName("songs")[0]->appendChild($newSong);
    // $xml->documentElement->appendChild($newSong);

    // Save the updated XML
    $xml->save("song.xml");

    // Redirect back to display.php
    header("Location: display.php");
    exit;

?>