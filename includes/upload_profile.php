<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_DEPRECATED & ~E_NOTICE);

require_once('db_config.php');

$empId = $_SESSION['EmpID'] ?? 0;
if ($empId == 0) {
    http_response_code(400);
    echo "Invalid user.";
    exit;
}

if (!isset($_FILES['profile_pic'])) {
    http_response_code(400);
    echo "No file uploaded.";
    exit;
}

$file = $_FILES['profile_pic'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    echo "Upload error code: " . $file['error'];
    exit;
}

// Validate MIME type
$allowedTypes = ['image/jpeg','image/png','image/gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
if (!in_array($mimeType, $allowedTypes)) {
    http_response_code(400);
    echo "Invalid file type. Only JPG, PNG, GIF allowed.";
    exit;
}

// Target folder
$targetDir = $_SERVER['DOCUMENT_ROOT'] . '/CHRMIS/uploads/profile_pics/';
if (!file_exists($targetDir)) {
    if (!mkdir($targetDir, 0755, true)) {
        http_response_code(500);
        echo "Failed to create folder.";
        exit;
    }
}

// Delete any existing profile pictures for this user
$existingFiles = glob($targetDir . "profile_{$empId}.*");
foreach ($existingFiles as $f) {
    @unlink($f);
}

// Set filename as PNG
$filename = "profile_{$empId}.png";
$targetFile = $targetDir . $filename;

// Resize image
list($width, $height) = getimagesize($file['tmp_name']);
$maxDim = 300;
$newWidth = $width;
$newHeight = $height;

if ($width > $maxDim || $height > $maxDim) {
    $ratio = $width / $height;
    if ($ratio > 1) {
        $newWidth = $maxDim;
        $newHeight = intval($maxDim / $ratio); // convert to int
    } else {
        $newHeight = $maxDim;
        $newWidth = intval($maxDim * $ratio); // convert to int
    }
}

// Create image resource
switch ($mimeType) {
    case 'image/jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
    case 'image/png': $src = imagecreatefrompng($file['tmp_name']); break;
    case 'image/gif': $src = imagecreatefromgif($file['tmp_name']); break;
}

// Resize
$dst = imagecreatetruecolor($newWidth, $newHeight);
imagealphablending($dst,false);
imagesavealpha($dst,true);
imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
imagepng($dst, $targetFile, 9);

imagedestroy($src);
imagedestroy($dst);

// Update database
$stmt = $pdo->prepare("UPDATE i SET profile_pic=? WHERE EmpNo=?");
$stmt->execute([$filename, $empId]);

// Return web path for AJAX
echo '/CHRMIS/uploads/profile_pics/' . $filename;
