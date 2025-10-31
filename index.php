<?php
// Paths
$headerPath = 'img/header.png';
$leftPath = 'img/left_part.png';
$rightPath = 'img/right_part.png';
$outputPath = 'result/merged.png';

// Load images
$header = imagecreatefrompng($headerPath);
$left = imagecreatefrompng($leftPath);
$right = imagecreatefrompng($rightPath);

// Get header dimensions
$headerWidth = imagesx($header);
$headerHeight = imagesy($header);

// Resize left part to match header height
$leftWidth = imagesx($left);
$leftHeight = imagesy($left);
$newLeftWidth = $leftWidth * ($headerHeight / $leftHeight);
$newLeftHeight = $headerHeight;

// Create resized left image
$leftResized = imagecreatetruecolor($newLeftWidth, $newLeftHeight);
imagecopyresampled($leftResized, $left, 0, 0, 0, 0, $newLeftWidth, $newLeftHeight, $leftWidth, $leftHeight);

// Copy left resized image to header at position 0,0
imagecopy($header, $leftResized, 0, 0, 0, 0, $newLeftWidth, $newLeftHeight);

// Resize right part to match header height
$rightWidth = imagesx($right);
$rightHeight = imagesy($right);
$newRightWidth = $rightWidth * ($headerHeight / $rightHeight);
$newRightHeight = $headerHeight;

// Create resized right image
$rightResized = imagecreatetruecolor($newRightWidth, $newRightHeight);
imagecopyresampled($rightResized, $right, 0, 0, 0, 0, $newRightWidth, $newRightHeight, $rightWidth, $rightHeight);

// Copy right resized image to header at position (headerWidth - newRightWidth, 0)
imagecopy($header, $rightResized, $headerWidth - $newRightWidth, 0, 0, 0, $newRightWidth, $newRightHeight);

// Save the merged image
imagepng($header, $outputPath);

// Free memory
imagedestroy($header);
imagedestroy($left);
imagedestroy($right);
imagedestroy($leftResized);
imagedestroy($rightResized);

echo "Image merged and saved to $outputPath";
?>