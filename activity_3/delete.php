<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link rel="stylesheet" href="deletes.css"> 
</head>

<body>
    <!-- <h2>Are you sure you want to delete this record?</h2> -->

    <?php
    session_start();

    if (isset($_GET['deleteid'])) {
        $deleteId = $_GET['deleteid'];

        // Load the XML file
        $xml = new DOMDocument();
        $xml->load("song.xml");

        // Find the song with the specified code and get its details
        $xpath = new DOMXPath($xml);
        $songToDelete = $xpath->query("/songs/song[@code='$deleteId']")->item(0);
        if ($songToDelete) {
            $title = $songToDelete->getElementsByTagName("Title")->item(0)->nodeValue;
            $album = $songToDelete->getElementsByTagName("Album")->item(0)->nodeValue;
            $genre = $songToDelete->getElementsByTagName("Genre")->item(0)->nodeValue;
            $singersNode = $songToDelete->getElementsByTagName("Singers")->item(0);
            $singers = "";

            // Iterate over Singer nodes and concatenate them with comma
            foreach ($singersNode->getElementsByTagName("Singer") as $singer) {
                $singers .= $singer->nodeValue . ", ";
            }
            // Remove trailing comma and space
            $singers = rtrim($singers, ", ");

          // Display the song details


        }
    ?>
<div class='container'>
    <h2>Are you sure you want to delete this song?</h2>
    <div class='song-details'>
        <p><strong>Title:</strong> <?php echo $title; ?></p>
        <p><strong>Album:</strong> <?php echo $album; ?></p>
        <p><strong>Genre:</strong> <?php echo $genre; ?></p>
        <p><strong>Singers:</strong> <?php echo $singers; ?></p>
        <form action='' method='post'>
        <button type='submit' name='confirm' value='yes'>Yes</button>
        <button type='submit' name='confirm' value='no'>No</button>
    </form>
    </div>
</div>
    <?php
        // Process the form submission
        if (isset($_POST['confirm'])) {
            if ($_POST['confirm'] === 'yes') {
                // Remove the song from the XML file
                $songToDelete->parentNode->removeChild($songToDelete);
                $xml->save("song.xml");

                // Redirect back to the display page
                header("Location: display.php");
                exit();
            } else {
                // Redirect back to the display page without deleting
                header("Location: display.php");
                exit();
            }
        }
    } else {
        // If deleteid parameter is not set, redirect back to the display page
        header("Location: display.php");
        exit();
    }
    ?>
</body>

</html>
