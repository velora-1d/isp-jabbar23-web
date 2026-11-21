<?php

namespace App\Services\Network;

use RouterOS\Config;
use RouterOS\Client;
use RouterOS\Query;
use App\Models\Router;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Log;

class PppoeService
{
    /**
     * Sync customer to MikroTik PPPoE Secret.
     */
    public function syncSecret(Customer $customer): bool
    {
        if (!$customer->router || !$customer->pppoe_username) {
            return false;
        }

        $router = $customer->router;

        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728);

            $client = new Client($config);
            
            // Check if secret already exists
            $query = new Query("/ppp/secret/print");
            $query->equal("name", $customer->pppoe_username);
            
            $existing = $client->query($query)->read();

            $params = [
                "name"     => $customer->pppoe_username,
                "password" => $customer->pppoe_password ?? '1234',
                "service"  => "pppoe",
                "profile"  => $customer->pppoe_profile ?? $customer->package?->name ?? 'default',
                "remote-address" => $customer->mikrotik_ip,
                "comment"  => "ID: {$customer->customer_id} - {$customer->name}",
                "disabled" => $this->shouldBeDisabled($customer->status) ? "yes" : "no",
            ];

            if (!empty($existing)) {
                // Update
                $updateQuery = new Query("/ppp/secret/set");
                $updateQuery->equal(".id", $existing[0][".id"]);
                foreach ($params as $key => $value) {
                    $updateQuery->equal($key, $value);
                }
                $client->query($updateQuery)->read();
                Log::info("MikroTik: Updated PPPoE Secret for {$customer->name}");
            } else {
                // Create
                $addQuery = new Query("/ppp/secret/add");
                foreach ($params as $key => $value) {
                    $addQuery->equal($key, $value);
                }
                $client->query($addQuery)->read();
                Log::info("MikroTik: Created PPPoE Secret for {$customer->name}");
            }

            return true;
        } catch (Exception $e) {
            Log::error("MikroTik Error (Sync): " . $e->getMessage());
        }

        return false;
    }

    /**
     * Toggle PPPoE secret status (Enable/Disable).
     */
    public function toggleSecret(Customer $customer, bool $enabled): bool
    {
        if (!$customer->router || !$customer->pppoe_username) {
            return false;
        }

        $router = $customer->router;

        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728);

            $client = new Client($config);
            
            $query = new Query("/ppp/secret/print");
            $query->equal("name", $customer->pppoe_username);
            
            $existing = $client->query($query)->read();

            if (!empty($existing)) {
                $setQuery = new Query("/ppp/secret/set");
                $setQuery->equal(".id", $existing[0][".id"]);
                $setQuery->equal("disabled", $enabled ? "no" : "yes");
                
                $client->query($setQuery)->read();
                
                // If disabled, also kick the current active local session if any
                if (!$enabled) {
                    $activeQuery = new Query("/ppp/active/print");
                    $activeQuery->equal("name", $customer->pppoe_username);
                    
                    $active = $client->query($activeQuery)->read();
                    if (!empty($active)) {
                        $removeQuery = new Query("/ppp/active/remove");
                        $removeQuery->equal(".id", $active[0][".id"]);
                        $client->query($removeQuery)->read();
                    }
                }
                
                return true;
            }
        } catch (Exception $e) {
            Log::error("MikroTik Toggle Error: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Get all PPPoE profiles from the router.
     */
    public function getProfiles(Router $router): array
    {
        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728)
                ->set('timeout', 2);

            $client = new Client($config);
            $query = new Query("/ppp/profile/print");
            
            $profiles = $client->query($query)->read();
            
            return array_map(function($p) {
                return [
                    'id' => $p['name'],
                    'name' => $p['name'] . ($p['local-address'] ?? '' ? " ({$p['local-address']})" : ""),
                ];
            }, $profiles);
        } catch (Exception $e) {
            Log::error("MikroTik Profiles Error: " . $e->getMessage());
            return [['id' => 'default', 'name' => 'default']];
        }
    }

    /**
     * Get all active PPPoE sessions from the router.
     */
    public function getActiveSessions(Router $router): array
    {
        try {
            $config = (new Config())
                ->set('host', $router->ip_address)
                ->set('user', $router->username)
                ->set('pass', $router->password)
                ->set('port', $router->port ?? 8728)
                ->set('timeout', 2);

            $client = new Client($config);
            $query = new Query("/ppp/active/print");
            
            return $client->query($query)->read();
        } catch (Exception $e) {
            Log::error("MikroTik Active Sessions Error: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Determine if the secret should be disabled based on customer status.
     */
    private function shouldBeDisabled(string $status): bool
    {
        return in_array($status, [
            Customer::STATUS_SUSPENDED,
            Customer::STATUS_TERMINATED,
            Customer::STATUS_REGISTERED,
            Customer::STATUS_SURVEY,
            Customer::STATUS_APPROVED,
            Customer::STATUS_SCHEDULED,
            Customer::STATUS_INSTALLING,
        ]);
    }
}
