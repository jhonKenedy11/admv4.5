<?php
/**
 * @package   astec
 * @name      p_rel_compra_encomenda
 * @version   3.0.00
 * @copyright 2016
 * @link      http://www.admservice.com.br/
 * @author    Jhon Kenedy
 * @date      04/02/2022
 */
if (!defined('ADMpath')) {
    exit;
}

// Legado: relatório movido para o módulo de pedidos (admv4.5)
header('Location: index.php?mod=ped&form=pedido_relatorios');
exit;
