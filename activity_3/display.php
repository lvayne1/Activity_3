<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Havis Crud</title>
     <link rel="stylesheet" href="styles.css"> 
</head>

<body>

<?php
session_start();

if(isset($_POST['search']))
{
    $_SESSION['search'] = $_POST['search'];
}
$search = '';

if(isset($_SESSION['search'])){
    $search= $_SESSION['search'];
}

?>

    <div class="container">
        <a class="add-song-btn" href="song.html">+ Add new Song</a>
        <form class="search-form" action="" method="post">
            <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
    </div>
        <table class="song-table">
            <thead>
                <tr>
                    <th>Code Number</th>
                    <th>Song Code</th>
                    <th>Title</th>
                    <th>Album</th>
                    <th>Genre</th>
                    <th>Singers</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
    <?php
        // Load the XML file
    $xml = new DOMDocument();
    $xml->load("song.xml");
    

    $songs = $xml->getElementsByTagName("song");
   
    $songsArray = iterator_to_array($songs);
    usort($songsArray, function($a, $b) {
        $aCode = strtoupper($a->getAttribute("code")); // Convert to lowercase
        $bCode = strtoupper($b->getAttribute("code")); // Convert to lowercase
        return strcmp($aCode, $bCode); // Case-insensitive comparison
    });
    

    $rownumber = 1;

    foreach ($songsArray as $song) {
        $code = $song->getAttribute("code");
        $title = $song->getElementsByTagName("Title")[0]->nodeValue;
        $album = $song->getElementsByTagName("Album")[0]->nodeValue;
        $genre = $song->getElementsByTagName("Genre")[0]->nodeValue;
        $preRequisites = $song->getElementsByTagName("Singer");
        $preReq = "";

        foreach ($preRequisites as $preReqNode) {
            $preReq .= $preReqNode->nodeValue; // Concatenate node values
            if ($preReqNode !== end($preRequisites)) {
                $preReq .= ", "; // Add comma kung di pa last prerequisite
            }
        }
        
        $preReq = rtrim($preReq, ", ");
        if (stripos($title, $search) !== false ||
            stripos($code, $search) !== false ||
            stripos($album, $search) !== false ||
            stripos($genre, $search) !== false ||
            stripos($rownumber, $search) !== false ||
            stripos($preReq, $search) !== false) {
            echo "<tr>
                    <td>$rownumber</td>
                    <td>$code</td>
                    <td>$title</td>
                    <td>$album</td>
                    <td>$genre</td>
                    <td>$preReq</td>
                    <td class='action-cell'>
                        <a href='select_field.php?updateid=$code'><img class='update-img' src='images/edit-image.png' alt='Update'></a>
                        <a href='delete.php?deleteid=$code'><img class='delete-img' src='images/trash.png' alt='Delete'></a>
                    </td>
                  </tr>";
           
        }
        $rownumber++;
    }

                ?>
        </tbody>
        </table>
</body>
</html>