<?php
    if (($_FILES["file_upload"]["type"] == "audio/mid") || ($_FILES["file_upload"]["type"] == "audio/mp3") || ($_FILES["file_upload"]["type"] == "audio/wav")){
        if ($_FILES["file_upload"]["error"] > 0) {
            echo "Error: " . $_FILES["file_upload"]["error"] . "<br/>";
        }
        else {
            echo "Upload: " . $_FILES["file_upload"]["name"] . "<br/>";
            echo "Type: " . $_FILES["file_upload"]["type"] . "<br/>";
            echo "Size: " . ($_FILES["file_upload"]["size"] / 1024) . " Kb<br/>";
            echo "Temp file: " . $_FILES["file_upload"]["tmp_name"] . "<br />";

            if (file_exists("audio/music/" . $_FILES["file_upload"]["name"])) {
                echo $_FILES["file_upload"]["name"] . " already exists. ";
            }
            else {
                move_uploaded_file($_FILES["file_upload"]["tmp_name"],
                    "audio/music/" . $_FILES["file_upload"]["name"]);
                echo "Stored in: " . "audio/music/" . $_FILES["file_upload"]["name"];

                $myfile = fopen("audio/lyric/" . $_FILES["file_upload"]["name"] . ".lrc", "w") or die("Unable to open file!");
                $txt = $_POST["edit_lyric"];
                fwrite($myfile, $txt);
                fclose($myfile);
            }
        }
    }
    else{
        echo "invalid file";
    }
?>