<?php

namespace App\Services;

use App\Models\User;

class PosService
{
    public static function checkLevelUpgrade(User $user): void
    {
        if ($user->customer_group !== 'poin') return;

        $spent = $user->total_spent;
        $updates = [];

        if ($spent >= 10000000) {
            $updates['customer_group'] = 'level4';
        } elseif ($spent >= 5000000) {
            $updates['customer_group'] = 'level3';
        } elseif ($spent >= 2000000) {
            $updates['customer_group'] = 'level2';
        } elseif ($spent >= 1000000) {
            $updates['customer_group'] = 'level1';
        }

        if (!empty($updates)) {
            $user->update($updates);
        }
    }

    public static function mapGroupToLevel(string $group): int
    {
        return match($group) {
            'level1' => 1,
            'level2' => 2,
            'level3' => 3,
            'level4' => 4,
            default => 1,
        };
    }
}