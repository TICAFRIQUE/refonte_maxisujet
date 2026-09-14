<?php

namespace App\Services;

use setasign\Fpdi\Fpdi;

/**
 * Tamponne un petit logo MaxiSujets en bas à droite de chaque page d'un PDF
 * avant téléchargement, sans modifier le fichier source stocké. Le PDF
 * d'origine reste inchangé sur le disque ; seule la copie envoyée au
 * navigateur porte le logo.
 */
class PdfWatermarkService
{
    private const LOGO_WIDTH_MM = 14;
    private const MARGIN_MM = 5;

    public function stamp(string $sourcePath): string
    {
        $logoPath = public_path('frontend/img/logo.png');

        if (!is_file($logoPath)) {
            return $sourcePath;
        }

        try {
            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($sourcePath);

            [$logoWidthPx, $logoHeightPx] = getimagesize($logoPath);
            $logoHeightMm = self::LOGO_WIDTH_MM * ($logoHeightPx / $logoWidthPx);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $templateId = $pdf->importPage($pageNo);
                $size = $pdf->getTemplateSize($templateId);

                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($templateId);

                $x = $size['width'] - self::LOGO_WIDTH_MM - self::MARGIN_MM;
                $y = $size['height'] - $logoHeightMm - self::MARGIN_MM;

                if ($x > 0 && $y > 0) {
                    $pdf->Image($logoPath, $x, $y, self::LOGO_WIDTH_MM, $logoHeightMm, 'PNG');
                }
            }

            $outPath = tempnam(sys_get_temp_dir(), 'ms_wm_') . '.pdf';
            $pdf->Output('F', $outPath);

            return $outPath;
        } catch (\Throwable $e) {
            report($e);
            return $sourcePath;
        }
    }
}
