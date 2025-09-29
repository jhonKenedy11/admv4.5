<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of httpException
 *
 * @author marcio
 */
abstract class httpException extends \Exception{
    abstract public function header();
}
