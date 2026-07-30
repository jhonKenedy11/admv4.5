<?php
/**
 * DanfeCustom - Wrapper para NFePHP\DA\NFe\Danfe
 * 
 * Resolve o problema do allow_url_fopen=0
 * 
 * @author  Jhon K. S. Mello <jhon.kened11@hotmail.com>
 * @version 1.0.0
 * @since   2026-01-21
 */

use NFePHP\DA\NFe\Danfe;

class DanfeCustom extends Danfe
{
    /**
     * Caminho do arquivo temporário do logo
     * @var string|null
     */
    private $logoTempFile = null;
    
    /**
     * Construtor
     * 
     * @param string $docXML XML da NFe autorizada
     */
    public function __construct($docXML)
    {
        parent::__construct($docXML);
    }
    
    /**
     * Define o logo para a DANFE
     * 
     * @param string $logoPath Caminho do arquivo de logo (JPG ou PNG)
     * @return $this
     */
    public function setLogoPath($logoPath)
    {
        if (!empty($logoPath) && file_exists($logoPath)) {
            $this->processarLogo($logoPath);
        }
        return $this;
    }
    
    /**
     * Processa o logo e injeta na classe pai
     * 
     * @param string $logoPath
     * @return void
     */
    private function processarLogo($logoPath)
    {
        $ext = strtolower(pathinfo($logoPath, PATHINFO_EXTENSION));
        $image = null;
        
        if ($ext === 'jpg' || $ext === 'jpeg') {
            $image = @imagecreatefromjpeg($logoPath);
        } elseif ($ext === 'png') {
            $image = @imagecreatefrompng($logoPath);
        }
        
        if (!$image) {
            error_log("DanfeCustom: Falha ao carregar logo de $logoPath");
            return;
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
        $this->logoTempFile = $tempDir . '/danfe_logo_' . md5($logoPath) . '.jpg';
        
        imagejpeg($image, $this->logoTempFile, 90);
        imagedestroy($image);
        
        try {
            $reflection = new ReflectionClass($this);
            $property = $reflection->getProperty('logomarca');
            $property->setAccessible(true);
            $property->setValue($this, $this->logoTempFile);
        } catch (Exception $e) {
            error_log("DanfeCustom: Erro Reflection - " . $e->getMessage());
        }
    }
    
    /**
     * Destrutor - Limpa arquivo temporário
     */
    public function __destruct()
    {
        if ($this->logoTempFile && file_exists($this->logoTempFile)) {
            @unlink($this->logoTempFile);
        }
    }
}