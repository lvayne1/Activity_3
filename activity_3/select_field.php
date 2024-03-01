<?php
session_start();

if (isset($_POST['field'])) {
    $selectedField = $_POST['field'];
    header("Location: modify_field.php?field=" . urlencode($selectedField) . "&code=" . urlencode($_POST['code']));
    exit;
}

if (isset($_GET['updateid'])) {
    $updateid = $_GET['updateid'];
} else {
    // Redirect to display page or show error
    header("Location: display.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <Title>Select Field to Modify</Title>
    <link rel="stylesheet" href="selects.css"> 
</head>
<body>
   
    <div class="container">
        <h2>Select Field to Modify:</h2>
        <form action="" method="post">
        <input type="hidden" name="code" value="<?php echo htmlspecialchars($updateid); ?>">
            <div>
                <input type="radio" id="all" name="field" value="all">
                <label for="all">Modify All</label><br>

                <input type="radio" id="Title" name="field" value="Title">
                <label for="Title">Modify Title</label><br>

                <input type="radio" id="Album" name="field" value="Album">
                <label for="Album">Modify Album</label><br>

                <input type="radio" id="Genre" name="field" value="Genre">
                <label for="Genre">Modify Genre</label><br>
                
                <input type="radio" id="Singers" name="field" value="Singers">
                <label for="Singers">Modify Singers</label><br>
            </div>
            <br>
            <div id="select">
                <button type="submit">Select Field</button>
                <button type="button" onclick="window.location.href='display.php'">Go Back</button>
            </div>
        </form>
    </div>
    
</body>
</html>