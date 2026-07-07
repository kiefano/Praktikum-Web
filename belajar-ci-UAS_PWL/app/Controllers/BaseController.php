<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 *
 * Extend this class in any new controllers:
 * ```
 *     class Home extends BaseController
 * ```
 *
 * For security, be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */

    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->ensureDatabaseReady();
    }

    private function ensureDatabaseReady(): void
    {
        if (!class_exists('mysqli')) {
            return;
        }

        try {
            $db = \Config\Database::connect();
            if (!$db->initialize()) {
                return;
            }

            $database = $db->getDatabase();
            if ($database === null || $database === '') {
                return;
            }

            $mysqli = new \mysqli($db->hostname, $db->username, $db->password, null, $db->port);
            if ($mysqli->connect_error) {
                return;
            }

            $mysqli->query("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
            $mysqli->select_db($database);

            $tables = $mysqli->query("SHOW TABLES LIKE 'user'");
            if ($tables && $tables->num_rows === 0) {
                $mysqli->query("CREATE TABLE `user` (
                    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                    `username` VARCHAR(255) NOT NULL,
                    `email` VARCHAR(255) NOT NULL,
                    `password` VARCHAR(255) NOT NULL,
                    `role` VARCHAR(50) NOT NULL,
                    `created_at` DATETIME NULL,
                    `updated_at` DATETIME NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `username` (`username`),
                    UNIQUE KEY `email` (`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

                $mysqli->query("INSERT INTO `user` (`username`, `email`, `password`, `role`, `created_at`, `updated_at`) VALUES ('admin1234', 'admin@example.com', '$2y$10$wYW3m2sM7P5Xw5OZQ4l5CeQ9gUfHQ2Qf8J8U3nI6vUu7yQq7L1xU2', 'admin', NOW(), NOW())");
            }

            $mysqli->close();
        } catch (\Throwable $e) {
            // Ignore database bootstrap failures; the app will still show the login page.
        }
    }
}
