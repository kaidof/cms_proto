<?php


declare(strict_types=1);

namespace core\router;

class AuthMiddleware
{
    /**
     * @return void
     * @throws \core\router\NoAuthException
     */
    public function handle()
    {
        if (!isset($_SESSION['user']) || !$_SESSION['user']) {
            logger()->error('AuthMiddleware: User not logged in', $_SESSION);

            throw new \core\router\NoAuthException('AuthMiddleware: User not logged in');
        }
    }
}
