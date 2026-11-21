<?php

namespace App\Services;

use App\Models\Promotion;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PromotionService
{
    public function list(array $filters = [])
    {
        $query = Promotion::query();

        if (isset($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('code', 'like', '%' . $filters['search'] . '%');
        }

        if (isset($filters['active'])) {
             $query->where('is_active', true);
        }

        return $query->latest()->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Promotion
    {
        return Promotion::create($data);
    }

    public function update(Promotion $promotion, array $data): Promotion
    {
        $promotion->update($data);
        return $promotion;
    }

    public function delete(Promotion $promotion): bool
    {
        return $promotion->delete();
    }

    public function validateCode(string $code, float $amount, ?int $customerId = null): Promotion
    {
        $promotion = Promotion::where('code', $code)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        if (!$promotion) {
            throw ValidationException::withMessages(['code' => 'Kode promo tidak ditemukan atau sudah kadaluarsa.']);
        }

        if ($promotion->usage_limit && $promotion->usage_count >= $promotion->usage_limit) {
            throw ValidationException::withMessages(['code' => 'Kuota kode promo ini sudah habis.']);
        }

        if ($promotion->min_purchase && $amount < $promotion->min_purchase) {
            throw ValidationException::withMessages(['code' => 'Minimal pembelian untuk kode ini adalah Rp ' . number_format($promotion->min_purchase, 0, ',', '.')]);
        }

        // Additional checks (per customer limit, package check) can be added here

        return $promotion;
    }
}
