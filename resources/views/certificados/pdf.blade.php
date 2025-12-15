<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <style>
    @page { size: A4 landscape; margin: 0; }
    body { margin: 0; padding: 0; font-family: Arial, sans-serif; }
    .page {
      position: relative;
      width: 100%;
      height: 100%;
      page-break-after: always;
      overflow: hidden;
    }
    .page:last-of-type {
      page-break-after: auto;
    }
    .bg {
      position: absolute;
      width: auto;
      height: auto;
    }
    .text-layer {
      position: absolute;
      inset: 12%;
      color: #111;
      font-size: 20px;
      line-height: 1.4;
      white-space: pre-wrap;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>
  @php
    $modelo = $certificado->modelo;
    $frenteFile = $modelo?->imagem_frente ? public_path('storage/'.$modelo->imagem_frente) : null;
    $versoFile  = $modelo?->imagem_verso ? public_path('storage/'.$modelo->imagem_verso) : null;
    $layoutFrente = $modelo->layout_frente ?? [];
    $layoutVerso  = $modelo->layout_verso ?? [];
    $textoFrente = trim($certificado->texto_frente ?? '');
    $textoVerso  = trim($certificado->texto_verso ?? '');
    $frenteInfo = ($frenteFile && file_exists($frenteFile)) ? @getimagesize($frenteFile) : null;
    $versoInfo  = ($versoFile && file_exists($versoFile)) ? @getimagesize($versoFile) : null;

    $toBase64Reduced = function ($filePath, $maxWidth = 2600, $quality = 92) {
        if (! $filePath || ! file_exists($filePath)) {
            return null;
        }
        $info = getimagesize($filePath);
        if (! $info) {
            return null;
        }
        $mime = $info['mime'] ?? 'image/jpeg';
        $createFn = match ($mime) {
            'image/png'  => 'imagecreatefrompng',
            'image/gif'  => 'imagecreatefromgif',
            default      => 'imagecreatefromjpeg',
        };
        if (!function_exists($createFn)) {
            $ext = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'png';
            $data = base64_encode(file_get_contents($filePath));
            return "data:image/{$ext};base64,{$data}";
        }
        $src = @$createFn($filePath);
        if (!$src) {
            $ext = pathinfo($filePath, PATHINFO_EXTENSION) ?: 'png';
            $data = base64_encode(file_get_contents($filePath));
            return "data:image/{$ext};base64,{$data}";
        }
        $origW = imagesx($src);
        $origH = imagesy($src);
        $scale = $origW > $maxWidth ? ($maxWidth / $origW) : 1;
        $newW = (int)($origW * $scale);
        $newH = (int)($origH * $scale);
        $dst = imagecreatetruecolor($newW, $newH);
        // white background for PNG/GIF transparency
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefill($dst, 0, 0, $white);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        ob_start();
        imagejpeg($dst, null, $quality);
        $data = ob_get_clean();
        imagedestroy($src);
        imagedestroy($dst);
        return 'data:image/jpeg;base64,'.base64_encode($data);
    };

    $frenteUrl = $toBase64Reduced($frenteFile);
    $versoUrl  = $toBase64Reduced($versoFile);
  @endphp

  <div class="page">
    @if($frenteUrl)
      <img src="{{ $frenteUrl }}" class="bg" alt="Frente">
    @endif
    @php
      $imgW = max(1, (float)($frenteInfo[0] ?? 2000));
      $imgH = max(1, (float)($frenteInfo[1] ?? 1100));
      $cw = max(1, (float)($layoutFrente['canvas_w'] ?? $imgW));
      $ch = max(1, (float)($layoutFrente['canvas_h'] ?? $imgH));
      $pageW = 842.0;
      $pageH = 595.0;
      $scale = min($pageW / $imgW, $pageH / $imgH);
      $renderW = $imgW * $scale;
      $renderH = $imgH * $scale;
      $offsetX = ($pageW - $renderW) / 2;
      $offsetY = ($pageH - $renderH) / 2;
      $scaleX = $renderW / $cw;
      $scaleY = $renderH / $ch;
      $x = $offsetX + ($layoutFrente['x'] ?? 0) * $scaleX;
      $y = $offsetY + ($layoutFrente['y'] ?? 0) * $scaleY;
      $w = ($layoutFrente['w'] ?? 0) * $scaleX;
      $h = ($layoutFrente['h'] ?? 0) * $scaleY;
      $fs = ($layoutFrente['font_size'] ?? 20) * min($scaleX, $scaleY);
      $ff = $layoutFrente['font_family'] ?? 'Arial';
      $fw = $layoutFrente['font_weight'] ?? 'normal';
      $fst = $layoutFrente['font_style'] ?? 'normal';
      $align = $layoutFrente['align'] ?? 'left';
      $styleFront = [
        "left:{$x}px",
        "top:{$y}px",
        "font-size:{$fs}px",
        "font-family:'{$ff}'",
        "font-weight:{$fw}",
        "font-style:{$fst}",
        "text-align:{$align}",
      ];
      if ($w > 0) $styleFront[] = "width:{$w}px";
      if ($h > 0) $styleFront[] = "height:{$h}px";
    @endphp
    <img src="{{ $frenteUrl }}" class="bg" alt="Frente" style="width:{{ $renderW }}px; height:{{ $renderH }}px; left:{{ $offsetX }}px; top:{{ $offsetY }}px;">
    <div class="text-layer" style="{{ implode(';', $styleFront) }}">
      {!! nl2br(e($textoFrente)) !!}
    </div>
  </div>

  @if($versoUrl || $textoVerso)
  <div class="page">
    @php
      $imgW = max(1, (float)($versoInfo[0] ?? 2000));
      $imgH = max(1, (float)($versoInfo[1] ?? 1100));
      $cw = max(1, (float)($layoutVerso['canvas_w'] ?? $imgW));
      $ch = max(1, (float)($layoutVerso['canvas_h'] ?? $imgH));
      $pageW = 842.0;
      $pageH = 595.0;
      $scale = min($pageW / $imgW, $pageH / $imgH);
      $renderW = $imgW * $scale;
      $renderH = $imgH * $scale;
      $offsetX = ($pageW - $renderW) / 2;
      $offsetY = ($pageH - $renderH) / 2;
      $scaleX = $renderW / $cw;
      $scaleY = $renderH / $ch;
      $x = $offsetX + ($layoutVerso['x'] ?? 0) * $scaleX;
      $y = $offsetY + ($layoutVerso['y'] ?? 0) * $scaleY;
      $w = ($layoutVerso['w'] ?? 0) * $scaleX;
      $h = ($layoutVerso['h'] ?? 0) * $scaleY;
      $fs = ($layoutVerso['font_size'] ?? 20) * min($scaleX, $scaleY);
      $ff = $layoutVerso['font_family'] ?? 'Arial';
      $fw = $layoutVerso['font_weight'] ?? 'normal';
      $fst = $layoutVerso['font_style'] ?? 'normal';
      $align = $layoutVerso['align'] ?? 'left';
      $styleBack = [
        "left:{$x}px",
        "top:{$y}px",
        "font-size:{$fs}px",
        "font-family:'{$ff}'",
        "font-weight:{$fw}",
        "font-style:{$fst}",
        "text-align:{$align}",
      ];
      if ($w > 0) $styleBack[] = "width:{$w}px";
      if ($h > 0) $styleBack[] = "height:{$h}px";
    @endphp
    @if($versoUrl)
      <img src="{{ $versoUrl }}" class="bg" alt="Verso" style="width:{{ $renderW }}px; height:{{ $renderH }}px; left:{{ $offsetX }}px; top:{{ $offsetY }}px;">
    @endif
    <div class="text-layer" style="{{ implode(';', $styleBack) }}">
      {!! nl2br(e($textoVerso)) !!}
    </div>
  </div>
  @endif
</body>
</html>
