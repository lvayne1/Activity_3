<?php
session_start();

$errorMessage = "";
$selectedField = isset($_GET['field']) ? $_GET['field'] : '';
$code = isset($_GET['code']) ? $_GET['code'] : '';

// Load current song data
$xml = new DOMDocument();
$xml->load("song.xml");
$currentSong = [];
$songs = $xml->getElementsByTagName("song");
foreach ($songs as $song) {
    if ($song->getAttribute("code") == $code) {
        $Singers = [];
        foreach ($song->getElementsByTagName("Singer") as $Singer) {
            $Singers[] = $Singer->nodeValue;
        }
        $currentSong = [
            'Title' => $song->getElementsByTagName("Title")[0]->nodeValue,
            'Album' => $song->getElementsByTagName("Album")[0]->nodeValue,
            'Genre' => $song->getElementsByTagName("Genre")[0]->nodeValue,
            'Singers' => implode(', ', $Singers),
        ];
        break;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $updated = false;
    $Title = isset($_POST['Title']) ? $_POST['Title'] : '';
    $Album = isset($_POST['Album']) ? $_POST['Album'] : '';
    $Genre = isset($_POST['Genre']) ? $_POST['Genre'] : '';
    $SingersInput = isset($_POST['Singers']) ? $_POST['Singers'] : '';
    foreach ($songs as $song) {
        if ($song->getAttribute("code") == $code) {
            if ($selectedField === 'all' || $selectedField === 'Title') {
                $song->getElementsByTagName("Title")[0]->nodeValue = $Title;
                $updated = true;
            }
            if ($selectedField === 'all' || $selectedField === 'Album') {
                $song->getElementsByTagName("Album")[0]->nodeValue = $Album;
                $updated = true;
            }
            if ($selectedField === 'all' || $selectedField === 'Genre') {
                $song->getElementsByTagName("Genre")[0]->nodeValue = $Genre;
                $updated = true;
            }
            if ($selectedField === 'all' || $selectedField === 'Singers') {
                // Remove existing <Singers> element if present
                $existingSingers = $song->getElementsByTagName("Singers");
                if ($existingSingers->length > 0) {
                    $singersElement = $existingSingers->item(0);
                    // Remove all existing <Singer> elements
                    while ($singersElement->hasChildNodes()) {
                        $singersElement->removeChild($singersElement->firstChild);
                    }
                } else {
                    // Create new <Singers> element if it doesn't exist
                    $singersElement = $xml->createElement("Singers");
                    $song->appendChild($singersElement);
                }
                // Add new <Singer> elements
                $SingersArray = array_filter(array_map('trim', explode(',', $SingersInput)));
                foreach ($SingersArray as $SingerName) {
                    $SingerElement = $xml->createElement("Singer", $SingerName);
                    $singersElement->appendChild($SingerElement);
                }
                $updated = true;
            }
            if ($updated) {
                $xml->save("song.xml");
                $_SESSION['success_message'] = "Update successful!";
                header("Location: display.php");
                exit;
            }
        }
    }
    

    if (!$updated) {
        $errorMessage = "No changes were made.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Title>Modify Song</Title>
    <link rel="stylesheet" href="modify.css">
</head>
<body>
<div class="container">
    <h2>Modify Song</h2>
    <form action="" method="post">
        <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">
        <?php if ($selectedField === 'Title' || $selectedField === 'all'): ?>
            <label for="Title">Title:</label><br>
            <input type="text" id="Title" name="Title" value="<?php echo htmlspecialchars($currentSong['Title']); ?>" required autocomplete="off"><br>
        <?php endif; ?>
        <?php if ($selectedField === 'Album' || $selectedField === 'all'): ?>
            <label for="Album">Album:</label><br>
            <input type="text" id="Album" name="Album" value="<?php echo htmlspecialchars($currentSong['Album']); ?>" required autocomplete="off"><br>
        <?php endif; ?>
        <?php if ($selectedField === 'Genre' || $selectedField === 'all'): ?>
            <label for="Genre">Genre:</label><br>
            <input type="text" id="Genre" name="Genre" value="<?php echo htmlspecialchars($currentSong['Genre']); ?>" required autocomplete="off"><br>
        <?php endif; ?>
        <?php if ($selectedField === 'Singers' || $selectedField === 'all'): ?>
            <label for="Singers">Singers:</label><br>
            <input type="text" id="Singers" name="Singers" value="<?php echo htmlspecialchars($currentSong['Singers']); ?>" required autocomplete="off"><br>
        <?php endif; ?>
        <div class="button-container">
            <button type="submit" name="update" class="button">Update</button>
            <a href="select_field.php" class="go-back-button">Go Back</a>
        </div>

    </form>
    <?php if (!empty($errorMessage)): ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php endif; ?>
</div>

</body>
</html>