<?php
if (isset($_POST) && !empty($_POST)) {

    $target_dir = "uploads/";
    $myNewName = str_replace(array('#', '&', ' ', "'", '`', '�'), '_', substr($_POST['discordName'], 0, 8));
    $target_file = $target_dir . $myNewName . '.sav';
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if file already exists
    if (!$_POST['discordName']) {
        echo "You MUST enter your Name.<br>";
        $uploadOk = 0;
    }
    if (strpos($_POST['discordName'], 'live') !== false) {
        echo "Name MUST NOT contain 'live'.<br>";
        $uploadOk = 0;
    }
    if (strpos($_POST['discordName'], 'slot') !== false) {
        echo "Name MUST NOT contain 'slot'.<br>";
        $uploadOk = 0;
    }
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

// Check file size
    if ($_FILES["fileToUpload"]["size"] > 1500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

// Allow certain file formats
    if ($imageFileType != "sav") {
        echo "Sorry, only .sav files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                //echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.<br>";
                //die('YOUR FILE WAS PLACED IN QUEUE UNTIL I TURN THE COMPUTER ON AGAIN AFTER MY NIGHT!');

                if (isset($_POST['public'])) {

                  // Copy save for public use
                  copy($target_file, 'saves/public/' . $myNewName . '.sav');

                  $NEWUPLOADEDFILE = $myNewName . '.sav';

                  include('converter.php');
                  header('Location: maps/' . $myNewName . '.html');

                } else {

                  $NEWUPLOADEDFILE = $myNewName . '.sav';

                  include('converter.php');
                  header('Location: maps/' . $myNewName . '.html');
                }

            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }
        die();
}

?>
<!DOCTYPE html>
<html lang="en">
<?php
$PageTitle = "Upload";
include_once('includes/head.php');
?>
<head><link rel="stylesheet" href="css/extra.css"></head>
<body>
<!--H1>I AM IN BED NOW. GAMING COMPUTER SHUT DOWN! YOUR UPLOADS WILL BE QUEUED UNTIL I WAKE UP TOMORROW.</H1>
<H2>Due to some technical limitations this mapping can yet not be done plainely on Webservers (yet!)</H2>
<H3>hence we miss some cruicial bits needed to display a map.</H3-->

<header class="header">
    <a class="button" href="/">Go Back</a>
</header>

<main>
    <form class="upload-form" method="post" enctype="multipart/form-data">
        <h1>Upload your savefile</h1>
        <p>Open explorer at %localappdata%\arr\saved\savegames\</p>
        <br>

        <section>
            <h3>1. Select savefile</h3>
              <input type="file" name="fileToUpload" id="fileToUpload">
        </section>
        <!--img src="hint.png" width="700"-->
        <section>
            <h3>2. Enter your name</h3>
            <div class="input-group">
                <label for="discordName">Your Name on Discord or similar:</label>
                <input placeholder="Enter your name" id="discordName" type="text" name="discordName" maxlength="8">
            </div>

        </section>

        <section>
            <h3>3. Make it public</h3>
            <div class="input-group input-group--row">
                <input id="public" type="checkbox" name="public">
                <label for="public">Others are allowed to download this for 2 days</label>

            </div>

        </section>

        <section class="upload-form__background">
            <h3>4. Select your background</h3>
            <fieldset>
                <div>
                    <input type="radio" id="bg" name="background" value="bg">
                    <label for="bg">
                      <img border="2" src="topo/bg.png" width="90" height="90">Old background
                    </label>
                </div>
                <div>
                    <input type="radio" id="bg3" name="background" value="bg3">
                    <label for="bg3">
                      <img border="2" src="topo/bg3.png" width="90" height="90">New background
                    </label>
                </div>
                <div>
                    <input type="radio" id="bg4" name="background" value="bg4">
                    <label for="bg4">
                      <img border="2" src="topo/bg4.png" width="90" height="90">Psawhns background
                    </label>
                </div>
                <div>
                    <input checked type="radio" id="bg5" name="background" value="bg5">
                    <label for="bg5">
                      <img border="2" src="topo/bg4.png" width="90" height="90"> Psawhns background with kanados overlay
                    </label>
                </div>
            </fieldset>
        </section>

        <section>
            <h3>5. Trees</h3>
            <div class="input-group input-group--row">
                <input type="checkbox" id="firstTree" name="firstTree">
                <label for="firstTree">draw orange circles on initially fallen trees</label>
            </div>
            <div class="input-group input-group--row">
                <input type="checkbox" id="userTree" name="userTree">
                <label for="userTree">Draw green circles on fallen trees that could be replanted</label>
            </div>
        </section>

        <section>
            <h3>6. Slope settings</h3>
            <div class="input-group input-group--row">
                <input type="checkbox" id="maxslope" name="maxslope">
                <label for="maxslope">Draw 4 orange circles on worst slope</label>
            </div>

            <div class="input-group">
                <label for="slopeTrigger">Label slopes greater than x%</label>
                <input placeholder="20" type="text" id="slopeTrigger" name="slopeTrigger" value="20" class="iBox">
                <label for="slopeTrigger">label slopes greater than X%</label>
                <input placeholder="1000" type="text" id="slopeTrigger" name="slopeTrigger" value="2">
            </div>

            <div class="input-group">
                <label for="slopeTriggerPrefix">Prefix text with</label>
                <input placeholder=".." type="text" id="slopeTriggerPrefix" name="slopeTriggerPrefix" value="..">
            </div>
            <div class="input-group">
                <label for="slopeTriggerDecimals">Round to X decimals</label>
                <input placeholder="1" type="text" id="slopeTriggerDecimals" name="slopeTriggerDecimals" value="1">
            </div>
          </div>
          </section>
        </section>

        <section>
            <h3>7. Rolling Stock</h3>
            <div class="input-group input-group--row">
                <input type="checkbox" id="empty" name="empty">
                <label for="empty">include unnamed rolling stock to list</label>
            </div>

            <input class="button" type="submit" value="Upload" name="submit">
        </section>
    </form>
</main>

<?php include_once('includes/footer.php') ?>

</body>
</html>
