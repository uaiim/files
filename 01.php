<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $fileName = $_FILES['image']['name'];
        // Use pathinfo to get the filename without extension
        $fileInfo = pathinfo($fileName);
        $fileNameWithoutExt = $fileInfo['filename']; // This is the name without the extension

        $image = $_FILES['image']['tmp_name'];
        $sizes = [
            [$_POST['width1'], $_POST['height1']],
            [$_POST['width2'], $_POST['height2']],
            [$_POST['width3'], $_POST['height3']],
        ];

        $originalImage = imagecreatefromstring(file_get_contents($image));
        $output = '';

        foreach ($sizes as $index => $size) {
            $width = (int)$size[0];
            $height = (int)$size[1];

            $resizedImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $width, $height, imagesx($originalImage), imagesy($originalImage));

            // Simpan gambar yang sudah di-resize
            $outputPath = "{$fileNameWithoutExt}-{$width}x{$height}.jpg";
            imagejpeg($resizedImage, $outputPath, 100);
            imagedestroy($resizedImage);

            // Tampilkan link ke gambar yang sudah di-resize
            $output .= "<p><a href='$outputPath'>Download Gambar Ukuran " . ($index + 1) . "</a></p>";
        }

        imagedestroy($originalImage);
        echo $output;
    } else {
        echo "Error uploading image.";
    }
} else {
    echo "Invalid request.";
}
?>