<?php
$dir = __DIR__;
// Busca PNGs e JPGs para converter e WebPs para redimensionar
$files = glob("$dir/*.{png,jpg,jpeg,webp}", GLOB_BRACE);

foreach ($files as $file) {
    $info = getimagesize($file);
    if (!$info) continue;

    $width = $info[0];
    $height = $info[1];
    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    // Se a imagem for maior que 800px de largura, redimensionamos
    if ($width > 800) {
        $newWidth = 800;
        $newHeight = ($height / $width) * $newWidth;
        $dst = imagecreatetruecolor($newWidth, $newHeight);

        // Carrega a imagem original
        if ($extension == 'png') $src = imagecreatefrompng($file);
        elseif ($extension == 'webp') $src = imagecreatefromwebp($file);
        else $src = imagecreatefromjpeg($file);

        // Preserva transparência
        imagealphablending($dst, false);
        imagesavealpha($dst, true);

        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Salva sempre como .webp para performance máxima
        $outputName = $dir . '/' . pathinfo($file, PATHINFO_FILENAME) . '.webp';
        imagewebp($dst, $outputName, 75); // Qualidade 75 é o ponto ideal
        
        imagedestroy($src);
        imagedestroy($dst);
        echo "Otimizada: " . basename($file) . " para 800px\n";
    }
}
