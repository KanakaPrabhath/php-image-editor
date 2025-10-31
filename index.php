<?php
// Increase memory limit to handle larger images (e.g., 2MB files)
ini_set('memory_limit', '512M');

// Paths
$headerPath = 'img/header.png';
$leftPath = 'img/left_part.png';
$rightPath = 'img/right_part.png';
$outputPath = 'result/merged.png';

// Check if files exist
if (!file_exists($headerPath)) {
    die("Error: Header image not found at $headerPath\n");
}
if (!file_exists($leftPath)) {
    die("Error: Left part image not found at $leftPath\n");
}
if (!file_exists($rightPath)) {
    die("Error: Right part image not found at $rightPath\n");
}

// Load images
$header = imagecreatefrompng($headerPath);
if (!$header) {
    die("Error: Failed to load header image\n");
}
$left = imagecreatefrompng($leftPath);
if (!$left) {
    die("Error: Failed to load left part image\n");
}
$right = imagecreatefrompng($rightPath);
if (!$right) {
    die("Error: Failed to load right part image\n");
}

// Preserve transparency
imagealphablending($header, true);
imagesavealpha($header, true);

// Get header dimensions
$headerWidth = imagesx($header);
$headerHeight = imagesy($header);

// Resize left part to match header height
$leftWidth = imagesx($left);
$leftHeight = imagesy($left);
$newLeftWidth = (int) ($leftWidth * ($headerHeight / $leftHeight));
$newLeftHeight = $headerHeight;

// Create resized left image with transparency
$leftResized = imagecreatetruecolor($newLeftWidth, $newLeftHeight);
imagealphablending($leftResized, false);
imagesavealpha($leftResized, true);
imagecopyresampled($leftResized, $left, 0, 0, 0, 0, $newLeftWidth, $newLeftHeight, $leftWidth, $leftHeight);

// Copy left resized image to header at position 0,0
imagecopy($header, $leftResized, 0, 0, 0, 0, $newLeftWidth, $newLeftHeight);

// Resize right part to match header height
$rightWidth = imagesx($right);
$rightHeight = imagesy($right);
$newRightWidth = (int) ($rightWidth * ($headerHeight / $rightHeight));
$newRightHeight = $headerHeight;

// Create resized right image with transparency
$rightResized = imagecreatetruecolor($newRightWidth, $newRightHeight);
imagealphablending($rightResized, false);
imagesavealpha($rightResized, true);
imagecopyresampled($rightResized, $right, 0, 0, 0, 0, $newRightWidth, $newRightHeight, $rightWidth, $rightHeight);

// Copy right resized image to header at position (headerWidth - newRightWidth, 0)
imagecopy($header, $rightResized, $headerWidth - $newRightWidth, 0, 0, 0, $newRightWidth, $newRightHeight);

// Save the merged image
if (!imagepng($header, $outputPath)) {
    die("Error: Failed to save merged image\n");
}

// Free memory
imagedestroy($header);
imagedestroy($left);
imagedestroy($right);
imagedestroy($leftResized);
imagedestroy($rightResized);

echo "Image merged and saved to $outputPath\n";
?>