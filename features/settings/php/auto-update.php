<?php

// Get the update server
$ud_server = "http://theamus.com/releases/";
$dl_server = "http://theamus.com/features/";

// Get the update information
$updatePath = $ud_server . '/update/';
$info = file_get_contents($updatePath);
$info = json_decode($info, true);

// Curl the new system files
$curl = curl_init();

// Get/download the file information
curl_setopt($curl, CURLOPT_URL, $dl_server . $info['update_path']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$data = curl_exec($curl);


// Define file/folder information
$tempPath = path(ROOT . '/features/settings/temp/');
$tempFolder = md5(time());
$tempFile = $tempFolder . '.zip';

// Create a temporary zip file with all of the information we just downloaded
$file = fopen($tempPath . $tempFile, 'w+');
fputs($file, $data);
fclose($file);
chmod($tempPath . $tempFile, 0777);
$file = ''; // Reset!
// Check for a successfule file download
if (!file_exists($tempPath . $tempFile)) {
    echo 'Something went wrong downloading the file. :(';
    run_after_ajax('backToCheck');
    die();
}

// Create the temporary folder
mkdir($tempPath . $tempFolder);

// Unzip the downloaded file into a folder
$zip = new ZipArchive;
$res = $zip->open($tempPath . $tempFile);
if ($res === true) {
    $zip->extractTo($tempPath . $tempFolder);
    $zip->close();
} else {
    die("<span class='admin-notifyfailure'>There was an error extracting the update.</span>");
}
$zip = $res = ''; // Reset!
// Get the system settings
$settingsTable = $tDataClass->prefix . '_settings';
$sql['settings'] = 'SELECT * FROM `' . $settingsTable . '`';
$qry['settings'] = $tData->query($sql['settings']);
$settings = $qry['settings']->fetch_assoc();

// Look for update sql files
$version = $settings['version'];
if (file_exists(path($tempPath . $tempFolder . '/update/update-sql.php'))) {
    include path($tempPath . $tempFolder . '/update/update-sql.php');
}

// Get all of the files in this system
$systemFiles = $tFiles->GetFiles(ROOT, ROOT);

// Get all of the files in the update
$updateFiles = $tFiles->GetFiles($tempPath . $tempFolder, $tempPath . $tempFolder);

// Find the files that will be updated
$toUpdate = array_intersect($systemFiles, $updateFiles);

// Remove all of the common files so we can allow an overwrite to happen
foreach ($toUpdate as $remove) {
    unlink($remove);
}

// Extract the update zip to the root of the system now
$zip = new ZipArchive;
$res = $zip->open($tempPath . $tempFile);
$zip->extractTo(ROOT);
$zip->close();

// Delete the zip file
unlink($tempPath . $tempFile);

// Update the file permissions
foreach ($toUpdate as $file) {
    if (file_exists(path(ROOT . '/' . $file))) {
        chmod(path(ROOT . '/' . $file), 0777);
    } else {
        echo 'There was an error while updating. This is bad.';
        run_after_ajax('backToCheck');
        die();
        break;
    }
}

// Clean up the temp files
foreach ($updateFiles as $remove) {
    unlink($tempPath . $tempFolder . '/' . $remove);
}

$tFiles->remove_folder(path(ROOT."/features/settings/temp/".$tempFolder));

echo '<span class="admin-notifysuccess">';
echo 'Okay, that\'s all! You\'re up to date. ';
echo '<a href="#" onclick="return reload();">Please refresh the page.</a>';
echo "</span>";
run_after_ajax('finishUpdate');