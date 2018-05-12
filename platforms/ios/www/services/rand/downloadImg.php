<?php
//Get the file
$content = file_get_contents("https://i1.sndcdn.com/avatars-000225752432-1ipiy7-t500x500.jpg");

//Store in the filesystem.
$fp = fopen("/rise", "w");
fwrite($fp, $content);
fclose($fp);

