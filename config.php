<?php
/**
 * @author Ammar Faizi <ammarfaizi2@gmail.com>
 * @package Lemon
 * @since 0.0.1
 */

define("storage", __DIR__."/storage");
define("data", __DIR__."/data");
define("logs", __DIR__."/logs");
define("PHPVIRTUAL_DIR", "/home/ammar/web/webhooks/public/php_virtual/");
define("PHPVIRTUAL_URL", "https://webhooks.redangel.ga/php_virtual");

is_dir(storage) or mkdir(storage);
is_dir(data) or mkdir(data);
is_dir(logs) or mkdir(logs);

/**
 * Pengaturan stack.
 */
define("TELEGRAM_TOKEN", "448907482:AAGAaT7iP-CUC7xoBeXSyC-mrovzwmYka4w");


/**
 * MyAnimeList auth.
 */
define("MAL_USER", "ammarfaizi2");
define("MAL_PASS", "triosemut123");
