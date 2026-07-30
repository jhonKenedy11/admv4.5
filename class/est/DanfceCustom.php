<?php
/**
 * DanfceCustom - Wrapper para NFePHP\DA\NFe\Danfce
 *
 * Corrige allow_url_fopen=0: FPDF não abre data:// (logo e QR Code).
 *
 * @author  Jhon K. S. Mello <jhon.kened11@hotmail.com>
 * @version 1.3.0
 * @since   2026-05-27
 */

use Com\Tecnick\Barcode\Barcode;
use NFePHP\DA\Common\DaCommon;
use NFePHP\DA\NFe\Danfce;

class DanfceCustom extends Danfce
{
    /** @var string|null */
    private $logoTempFile = null;

    public function __construct($docXML)
    {
        parent::__construct($docXML);
    }

    public function setLogoPath($logoPath)
    {
        if (!empty($logoPath) && is_file($logoPath)) {
            $this->processarLogo($logoPath);
        }
        return $this;
    }

    public function logoParameters($logo, $logoAlign = null, $mode_bw = false)
    {
        if (!empty($logoAlign)) {
            $this->logoAlign = $logoAlign;
        }
        if (!empty($logo) && is_file($logo)) {
            $this->setLogoPath($logo);
        }
    }

    public function render($logo = '')
    {
        if (!empty($logo) && is_file($logo)) {
            $this->setLogoPath($logo);
            $logo = '';
        }

        try {
            return parent::render($logo);
        } finally {
            $this->limparLogoTemp();
        }
    }

    /**
     * Impede data:// no logomarca (TraitBlocoI usa pdf->image sem suporte a stream).
     */
    protected function adjustImage($logo, $turn_bw = false)
    {
        if (!empty($this->logoTempFile) && is_file($this->logoTempFile)) {
            $this->aplicarLogomarca($this->logoTempFile);
            return $this->logoTempFile;
        }

        if (!empty($logo) && is_file($logo)) {
            $this->setLogoPath($logo);
            if (!empty($this->logoTempFile) && is_file($this->logoTempFile)) {
                return $this->logoTempFile;
            }
            return null;
        }

        if (!empty($this->logomarca) && is_file($this->logomarca)) {
            return $this->logomarca;
        }

        $this->aplicarLogomarca('');
        return null;
    }

    protected function monta($logo = '')
    {
        if (!empty($logo) && is_file($logo)) {
            $this->setLogoPath($logo);
        }
        $this->sanitizarLogomarca();
        parent::monta('');
    }

    /**
     * Logo no cabeçalho — só arquivo em disco (nunca data://).
     */
    protected function blocoI()
    {
        $this->sanitizarLogomarca();
        return parent::blocoI();
    }

    /**
     * QR Code: grava PNG em /tmp em vez de data:// (TraitBlocoVIII original).
     */
    protected function blocoVIII($y)
    {
        $y += 1;

        $maxW = $this->wPrint;
        $w = ($maxW * 1) + 4;
        $barcode = new Barcode();
        $bobj = $barcode->getBarcodeObj(
            'QRCODE,M',
            $this->qrCode,
            -4,
            -4,
            'black',
            [-2, -2, -2, -2]
        )->setBackgroundColor('white');
        $qrcode = $bobj->getPngData();

        $wQr = 50;
        $hQr = 50;
        $yQr = $y;
        $xQr = ($w / 2) - ($wQr / 2);

        $tmpQr = sys_get_temp_dir() . '/danfce_qr_' . md5($this->qrCode) . '.png';
        if (file_put_contents($tmpQr, $qrcode) === false) {
            error_log('DanfceCustom: falha ao gravar QR temporário');
            return $this->bloco8H + $y;
        }

        try {
            $this->pdf->image($tmpQr, $xQr, $yQr, $wQr, $hQr, 'PNG');
        } finally {
            @unlink($tmpQr);
        }

        return $this->bloco8H + $y;
    }

    private function processarLogo($logoPath)
    {
        $image = $this->carregarImagem($logoPath);
        if (!$image) {
            error_log("DanfceCustom: Falha ao carregar logo de $logoPath");
            return;
        }

        imagefilter($image, IMG_FILTER_GRAYSCALE);

        $width = imagesx($image);
        $height = imagesy($image);
        $maxWidth = 240;

        if ($width > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = (int)(($maxWidth / $width) * $height);
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            $white = imagecolorallocate($resized, 255, 255, 255);
            imagefill($resized, 0, 0, $white);
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        $this->logoTempFile = sys_get_temp_dir() . '/danfce_logo_' . md5($logoPath . '@' . @filemtime($logoPath)) . '.jpg';

        if (!imagejpeg($image, $this->logoTempFile, 90)) {
            imagedestroy($image);
            error_log("DanfceCustom: Falha ao gravar JPG em {$this->logoTempFile}");
            $this->logoTempFile = null;
            return;
        }
        imagedestroy($image);

        $this->aplicarLogomarca($this->logoTempFile);
    }

    /**
     * @param string $logoPath
     * @return resource|\GdImage|null
     */
    private function carregarImagem($logoPath)
    {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $image = null;

        if ($ext === 'jpg' || $ext === 'jpeg') {
            $image = @imagecreatefromjpeg($logoPath);
        } elseif ($ext === 'png') {
            $image = @imagecreatefrompng($logoPath);
        }

        if (!$image) {
            $blob = @file_get_contents($logoPath);
            if ($blob !== false) {
                $image = @imagecreatefromstring($blob);
            }
        }

        return $image ?: null;
    }

    private function sanitizarLogomarca()
    {
        if (empty($this->logomarca)) {
            return;
        }
        if (strncmp((string) $this->logomarca, 'data://', 7) === 0 || !is_file($this->logomarca)) {
            if (!empty($this->logoTempFile) && is_file($this->logoTempFile)) {
                $this->aplicarLogomarca($this->logoTempFile);
            } else {
                $this->aplicarLogomarca('');
            }
        }
    }

    private function aplicarLogomarca($path)
    {
        $this->logomarca = $path;

        try {
            $parentProp = new \ReflectionProperty(DaCommon::class, 'logomarca');
            $parentProp->setValue($this, $path);
        } catch (\ReflectionException $e) {
            error_log('DanfceCustom: logomarca DaCommon - ' . $e->getMessage());
        }
    }

    private function limparLogoTemp()
    {
        if ($this->logoTempFile && is_file($this->logoTempFile)) {
            @unlink($this->logoTempFile);
            $this->logoTempFile = null;
        }
    }

    public function __destruct()
    {
        $this->limparLogoTemp();
    }
}
