<?php
session_start();

if (isset($_POST['confirm']) && isset($_POST['deleteid'])) {
    if ($_POST['confirm'] === 'yes') {
        $deleteId = $_POST['deleteid'];

        // Load the XML file
        $xml = new DOMDocument();
        $xml->load("song.xml");

        // Find the song with the specified code and get its details
        $xpath = new DOMXPath($xml);
        $songToDelete = $xpath->query("/songs/song[@code='$deleteId']")->item(0);
        if ($songToDelete) {
            // Remove the song from the XML file
            $songToDelete->parentNode->removeChild($songToDelete);
            $xml->save("song.xml");
        }
    }
}

// Redirect back to the display page
header("Location: display.php");
exit();
?>
