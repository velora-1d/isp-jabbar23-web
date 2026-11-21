<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RadiusService
{
    /**
     * Create or Update PPPoE User
     */
    public function syncUser(string $username, string $password, string $groupName = null)
    {
        try {
            DB::transaction(function () use ($username, $password, $groupName) {
                // 1. Password (Cleartext-Password)
                DB::table('radcheck')->updateOrInsert(
                    ['username' => $username, 'attribute' => 'Cleartext-Password'],
                    ['op' => ':=', 'value' => $password]
                );

                // 2. Assign Group (Plan)
                if ($groupName) {
                    // Remove old group first (if multiple groups per user not supported logic)
                    DB::table('radusergroup')->where('username', $username)->delete();
                    
                    DB::table('radusergroup')->insert([
                        'username' => $username,
                        'groupname' => $groupName,
                        'priority' => 1
                    ]);
                }
            });
            
            Log::info("Radius User Synced: {$username}");
            return true;
        } catch (\Exception $e) {
            Log::error("Radius Sync Failed ({$username}): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Suspend User (Isolir)
     * Method: Add 'Auth-Type := Reject' or 'Expiration'
     * We use 'Auth-Type := Reject' for immediate block on next reconnect.
     * To kick online user, we need CoA (implemented separately).
     */
    public function suspendUser(string $username)
    {
        DB::table('radcheck')->updateOrInsert(
            ['username' => $username, 'attribute' => 'Auth-Type'],
            ['op' => ':=', 'value' => 'Reject']
        );
        Log::info("Radius User Suspended: {$username}");
    }

    /**
     * Restore User (Buka Isolir)
     */
    public function restoreUser(string $username)
    {
        DB::table('radcheck')
            ->where('username', $username)
            ->where('attribute', 'Auth-Type')
            ->delete();
        Log::info("Radius User Restored: {$username}");
    }

    /**
     * Delete User completely
     */
    public function deleteUser(string $username)
    {
        DB::transaction(function () use ($username) {
            DB::table('radcheck')->where('username', $username)->delete();
            DB::table('radreply')->where('username', $username)->delete();
            DB::table('radusergroup')->where('username', $username)->delete();
        });
    }
}
