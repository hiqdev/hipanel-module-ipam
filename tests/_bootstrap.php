<?php
/**
 * IPAM for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-ipam
 * @package   hipanel-module-ipam
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2021, HiQDev (http://hiqdev.com/)
 */

error_reporting(E_ALL & ~E_NOTICE);

$bootstrap = __DIR__ . '/../src/_bootstrap.php';

require_once file_exists($bootstrap) ? $bootstrap : __DIR__ . '/../vendor/autoload.php';
