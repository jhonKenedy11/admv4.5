<?php

/**
 * Classe para log de performance
 * @author: Jhon Kenedy
 * @version: 1.0
 * @since: 2026-02-04   
 */

/**Exemplo de uso:
 * $performanceLogger = new PerformanceLogger('performance_log.txt'); 
 * $performanceLogger->separator('=', 80);
 * $performanceLogger->section('INÍCIO DA CONSULTA DE PRODUTOS');
 * $performanceLogger->start('consulta_estoque');
 * $performanceLogger->end('consulta_estoque');
 * $performanceLogger->log('Tempo de execução: ' . $performanceLogger->end('consulta_estoque') . ' ms');
 * $performanceLogger->lineBreak(2);
 */

class PerformanceLogger {
    private $logFile;
    private $startTimes = [];
    
    public function __construct($logFile = 'performance_log.txt') {
        $this->logFile = $logFile;
    }
    
    public function start($functionName) {
        $this->startTimes[$functionName] = microtime(true);
    }
    
    public function end($functionName) {
        if (!isset($this->startTimes[$functionName])) {
            return;
        }
        
        $endTime = microtime(true);
        $executionTime = ($endTime - $this->startTimes[$functionName]) * 1000;
        
        $logMessage = sprintf(
            "[%s] %s - Tempo: %.4f ms\n",
            date('Y-m-d H:i:s'),
            $functionName,
            $executionTime
        );
        
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
        
        unset($this->startTimes[$functionName]);
        
        return $executionTime;
    }
    
    // Adicionar texto personalizado
    public function log($message) {
        $logMessage = sprintf(
            "[%s] %s\n",
            date('Y-m-d H:i:s'),
            $message
        );
        file_put_contents($this->logFile, $logMessage, FILE_APPEND);
    }
    
    // Adicionar separador visual
    public function separator($char = '=', $length = 80) {
        $separator = str_repeat($char, $length) . "\n";
        file_put_contents($this->logFile, $separator, FILE_APPEND);
    }
    
    // Adicionar quebra de linha simples
    public function lineBreak($lines = 1) {
        $breaks = str_repeat("\n", $lines);
        file_put_contents($this->logFile, $breaks, FILE_APPEND);
    }
    
    // Adicionar cabeçalho de seção
    public function section($title) {
        $separator = str_repeat('=', 80) . "\n";
        $header = sprintf(
            "%s %s %s\n%s",
            $separator,
            strtoupper($title),
            date('Y-m-d H:i:s'),
            $separator
        );
        file_put_contents($this->logFile, $header, FILE_APPEND);
    }
    
    public function clearLog() {
        if (file_exists($this->logFile)) {
            unlink($this->logFile);
        }
    }
}