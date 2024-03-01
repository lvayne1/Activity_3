<?php
// edit_song.php

// Load XML file
$xml = new DOMDocument();
$xml->load("song.xml");

if(isset($_GET['updateid'])) {
    $updateId = $_GET['updateid'];

    // Find the song with the specified code
    $songs = $xml->getElementsByTagName("song");
    $songToUpdate = null;
    foreach ($songs as $song) {
        if ($song->getAttribute("code") == $updateId) {
            $songToUpdate = $song;
            break;
        }
    }

    if ($songToUpdate) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Update song information based on user input
            if(isset($_POST['title']) && !empty($_POST['title'])) {
                $songToUpdate->getElementsByTagName("Title")[0]->nodeValue = $_POST['title'];
            }
            if(isset($_POST['album']) && !empty($_POST['album'])) {
                $songToUpdate->getElementsByTagName("Album")[0]->nodeValue = $_POST['album'];
            }
            if(isset($_POST['genre']) && !empty($_POST['genre'])) {
                $songToUpdate->getElementsByTagName("Genre")[0]->nodeValue = $_POST['genre'];
            }
            if(isset($_POST['singers']) && !empty($_POST['singers'])) {
                // Clear existing singers
                $preRequisites = $songToUpdate->getElementsByTagName("Singer");
                foreach ($preRequisites as $preReqNode) {
                    $preReqNode->parentNode->removeChild($preReqNode);
                }
                // Add new singers
                $singers = explode(",", $_POST['singers']);
                foreach ($singers as $singer) {
                    $singerNode = $xml->createElement("Singer", trim($singer));
                    $songToUpdate->appendChild($singerNode);
                }
            }

            // Save changes to XML file
            $xml->save("song.xml");

            // Redirect back to initial page
            header("Location: display.php");
            exit();
        }

        // Display form for editing song information
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Song</title>
            <link rel="stylesheet" href="editsong.css">
        </head>
        <body>
            <div class="container">
            
            <form method="post">
            <h2>Edit Song Details</h2>
                <label>Title:</label><br>
                <input type="text" name="title" value="<?php echo htmlspecialchars($songToUpdate->getElementsByTagName("Title")[0]->nodeValue); ?>"><br>
                <label>Album:</label><br>
                <input type="text" name="album" value="<?php echo htmlspecialchars($songToUpdate->getElementsByTagName("Album")[0]->nodeValue); ?>"><br>
                <label>Genre:</label><br>
                <input type="text" name="genre" value="<?php echo htmlspecialchars($songToUpdate->getElementsByTagName("Genre")[0]->nodeValue); ?>"><br>
                <label>Singers:</label><br>
                <input type="text" name="singers" value="<?php
                    $preRequisites = $songToUpdate->getElementsByTagName("Singer");
                    $singers = [];
                    foreach ($preRequisites as $preReqNode) {
                        $singers[] = $preReqNode->nodeValue;
                    }
                    echo implode(", ", $singers);
                ?>"><br>
                 <div id="select">
                    <button type="submit">Submit</button>
                    <button type="button" onclick="window.location.href='display.php'">Go Back</button>
                 </div>
            </form>
            </div>

        </body>
        </html>
        <?php
    } else {
        echo "Song not found.";
    }
} else {
    echo "Song code not provided.";
}
?>
