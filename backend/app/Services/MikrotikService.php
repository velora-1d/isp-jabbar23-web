<?php

namespace App\Services;

use App\Models\Router;
use RouterOS\Client;
use RouterOS\Query;
use Illuminate\Support\Facades\Log;

class MikrotikService
{
    protected $client;

    /**
     * Connect to the MikroTik Router
     */
    public function connect(Router $router)
    {
        try {
            $config = [
                'host' => $router->ip_address,
                'user' => $router->username,
                'pass' => $router->password,
                'port' => (int) $router->port,
            ];

            $this->client = new Client($config);
            return true;

        } catch (\Exception $e) {
            Log::error("MikroTik Connection Failed: " . $e->getMessage());
            throw new \Exception("Could not connect to Router: " . $e->getMessage());
        }
    }

    /**
     * Test connection to the router
     */
    public function testConnection(Router $router)
    {
        try {
            $this->connect($router);
            // Try simple command
            $query = new Query('/system/identity/print');
            $response = $this->client->query($query)->read();
            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Enable or Disable PPPoE Secret
     * @param string $username PPPoE Username
     * @param bool $isEnabled True to enable, False to disable
     */
    public function togglePppoeUser($username, $isEnabled)
    {
        if (!$this->client) {
            throw new \Exception("Not connected to any router.");
        }

        try {
            // Find the secret ID first
            $query = (new Query('/ppp/secret/print'))
                ->where('name', $username);

            $secrets = $this->client->query($query)->read();

            if (empty($secrets)) {
                // Secret not found
                Log::warning("MikroTik: PPPoE User '$username' not found.");
                return false;
            }

            $id = $secrets[0]['.id'];
            $command = $isEnabled ? '/ppp/secret/enable' : '/ppp/secret/disable';

            // Execute Enable/Disable
            $query = (new Query($command))
                ->equal('.id', $id);

            $this->client->query($query)->read();

            // If disabling, also kick active connection
            if (!$isEnabled) {
                $this->kickActiveUser($username);
            }

            return true;

        } catch (\Exception $e) {
            Log::error("MikroTik Toggle Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Kick active connection to force reconnect (or force disconnect)
     */
    public function kickActiveUser($username)
    {
        try {
            $query = (new Query('/ppp/active/print'))
                ->where('name', $username);

            $activeSessions = $this->client->query($query)->read();

            if (!empty($activeSessions)) {
                foreach ($activeSessions as $session) {
                    $id = $session['.id'];
                    $removeQuery = (new Query('/ppp/active/remove'))
                        ->equal('.id', $id);
                    $this->client->query($removeQuery)->read();
                }
            }
        } catch (\Exception $e) {
            Log::error("MikroTik Kick Error: " . $e->getMessage());
        }
    }

    /**
     * Get all PPPoE Secrets
     */
    public function getSecrets()
    {
        if (!$this->client) {
            throw new \Exception("Not connected to any router.");
        }

        $query = new Query('/ppp/secret/print');
        return $this->client->query($query)->read();
    }

    /**
     * Get all PPP Profiles
     */
    public function getProfiles()
    {
        if (!$this->client) {
            throw new \Exception("Not connected to any router.");
        }

        $query = new Query('/ppp/profile/print');
        return $this->client->query($query)->read();
    }
}
