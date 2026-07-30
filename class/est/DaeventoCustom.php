<?php
/**
 * DaeventoCustom - Wrapper para NFePHP\DA\NFe\Daevento
 * 
 * Resolve o problema do allow_url_fopen=0
 * 
 * @author  Jhon K. S. Mello <jhon.kened11@hotmail.com>
 * @version 1.0.0
 * @since   2026-01-21
 */

use NFePHP\DA\NFe\Daevento;

class DaeventoCustom extends Daevento
{
    private $logoTempFile = null;
    
    /**
     * Construtor
     * 
     * @param string $docXML XML do evento
     * @param string $sxml XML da NFe (segundo parâmetro esperado)
     */
    public function __construct($docXML, $sxml = '')
    {
        parent::__construct($docXML, $sxml);
    }
    
    /**
     * Sobrescreve o método monta() para processar logo antes
     */
    protected function monta($logo = '')
    {
        // Se tem logo, processar antes
        if (!empty($logo) && file_exists($logo)) {
            $logo = $this->processarLogoArquivo($logo);
        }
        
        // Chamar o método original
        parent::monta($logo);
    }
    
    /**
     * Processa logo e retorna caminho do arquivo temporário JPG
     */
    private function processarLogoArquivo($logoPath)
    {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $image = null;
        
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $image = @imagecreatefromjpeg($logoPath);
        } elseif ($ext === 'png') {
            $image = @imagecreatefrompng($logoPath);
        }
        
        if (!$image) {
            error_log("DaeventoCustom: Falha ao carregar logo de $logoPath");
            return $logoPath; // Retorna original se falhar
        }
        
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
        
        $tempDir = sys_get_temp_dir();
        $this->logoTempFile = $tempDir . '/daevento_logo_' . uniqid() . '_' . md5($logoPath) . '.jpg';
        
        imagejpeg($image, $this->logoTempFile, 90);
        imagedestroy($image);
        
        return $this->logoTempFile;
    }
    
    public function __destruct()
    {
        if ($this->logoTempFile && file_exists($this->logoTempFile)) {
            @unlink($this->logoTempFile);
        }
    }
}