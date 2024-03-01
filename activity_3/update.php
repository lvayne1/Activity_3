<?php
session_start();

$errorMessage = "";
$selectedField = isset($_POST['field']) ? $_POST['field'] : '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $code = $_POST['code'];
    $Title = isset($_POST['Title']) ? $_POST['Title'] : '';
    $Album = isset($_POST['Album']) ? $_POST['Album'] : '';
    $Genre = isset($_POST['Genre']) ? $_POST['Genre'] : '';
    $Singers = isset($_POST['Singers']) ? $_POST['Singers'] : '';
    $singerTwo = isset($_POST['singerTwo']) ? $_POST['singerTwo'] : '';

    $xml = new DOMDocument();
    $xml->load("song.xml");

    $songs = $xml->getElementsByTagName("song");
    $updated = false;

    foreach ($songs as $song) {
        $songCode = $song->getAttribute("code");
        if ($songCode == $code) {
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
                $song->getElementsByTagName("Singers")[0]->nodeValue = $Singers;
                $updated = true;
            }
            if ($selectedField === 'all' || $selectedField === 'singerTwo') {
                $song->getElementsByTagName("Singertwo")[0]->nodeValue = $singerTwo;
                $updated = true;
            }
            break;
        }
    }

    if ($updated) {
        $xml->save("song.xml");
        $_SESSION['success_message'] = "Update successful!";
        header("Location: display.php");
        exit;
    } else {
        $errorMessage = "No changes were made.";
    }
}

if (isset($_GET['updateid'])) {
    $updateid = $_GET['updateid'];
    $xml = new DOMDocument();
    $xml->load("song.xml");

    $songs = $xml->getElementsByTagName("song");
    foreach ($songs as $song) {
        $code = $song->getAttribute("code");
        if ($code == $updateid) {
            $Title = $song->getElementsByTagName("Title")[0]->nodeValue;
            $Album = $song->getElementsByTagName("Album")[0]->nodeValue;
            $Genre = $song->getElementsByTagName("Genre")[0]->nodeValue;
            $Singers = $song->getElementsByTagName("Singers")[0]->nodeValue;
            $singerTwo = $song->getElementsByTagName("Singertwo")[0]->nodeValue;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Title>Edit Song</Title>
</head>
<body>

    <div class="container">
        <h2>Edit Song:</h2>
        <form action="" method="post">
            <input type="hidden" name="code" value="<?php echo htmlspecialchars($updateid); ?>">
            <div>
                <input type="radio" id="all" name="field" value="all" <?php echo $selectedField === 'all' ? 'checked' : ''; ?>>
                <label for="all">Modify All</label><br>
                <input type="radio" id="Title" name="field" value="Title" <?php echo $selectedField === 'Title' ? 'checked' : ''; ?>>
                <label for="Title">Modify Title</label><br>
                <input type="radio" id="Album" name="field" value="Album" <?php echo $selectedField === 'Album' ? 'checked' : ''; ?>>
                <label for="Album">Modify Album</label><br>
                <input type="radio" id="Genre" name="field" value="Genre" <?php echo $selectedField === 'Genre' ? 'checked' : ''; ?>>
                <label for="Genre">Modify Genre</label><br>
                <input type="radio" id="Singers" name="field" value="Singers" <?php echo $selectedField === 'Singers' ? 'checked' : ''; ?>>
                <label for="Singers">Modify Singers</label><br>
            </div>
            <br>
            <input type="submit" value="Select Field">
        </form>

        <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['field'])): ?>
            <form action="" method="post">
                <input type="hidden" name="code" value="<?php echo htmlspecialchars($updateid); ?>">
                <input type="hidden" name="field" value="<?php echo htmlspecialchars($selectedField); ?>">
                <?php if ($selectedField === 'Title' || $selectedField === 'all'): ?>
                    <div id="TitleRow">
                        <label for="Title">Title:</label><br>
                        <input type="text" id="Title" name="Title" value="<?php echo htmlspecialchars($Title); ?>"><br>
                    </div>
                <?php endif; ?>
                <br><br>
                <?php if ($selectedField === 'Album' || $selectedField === 'all'): ?>
                    <div id="AlbumRow">
                        <label for="Album">Album:</label><br>
                        <input type="text" id="Album" name="Album" value="<?php echo htmlspecialchars($Album); ?>"><br>
                    </div>
                    <br><br>
                <?php endif; ?>
                <?php if ($selectedField === 'Genre' || $selectedField === 'all'): ?>
                    <div id="GenreRow">
                        <label for="Genre">Genre:</label><br>
                        <input type="text" id="Genre" name="Genre" value="<?php echo htmlspecialchars($Genre); ?>"><br>
                    </div>
                    <br><br>
                <?php endif; ?>
                <?php if ($selectedField === 'Singers' || $selectedField === 'all'): ?>
                    <div id="SingersRow">
                        <label for="Singers">Singer One:</label><br>
                        <input type="text" id="Singers" name="Singers" value="<?php echo htmlspecialchars($Singers); ?>"><br>
                    </div>
                    <br><br>
                <?php endif; ?>
                <input type="submit" name="update" value="Update">
            </form>
        <?php endif; ?>

        <br><br><br>
        <div class="go-back">
            <a href="display.php">Go Back</a>
        </div>
    </div>
 
</body>
</html>