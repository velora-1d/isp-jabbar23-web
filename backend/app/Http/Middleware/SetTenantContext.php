<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        // For development/internal tool, we might use a header or auth user
        $tenantId = $request->header('X-Tenant-ID') ?: ($request->user()?->tenant_id ?? '00000000-0000-0000-0000-000000000000');

        if ($tenantId) {
            app()->instance('current_tenant_id', $tenantId);
            
            // Set RLS for PostgreSQL as per Rule 9.3
            try {
                DB::statement("SET app.tenant_id = ?", [$tenantId]);
            } catch (\Exception $e) {
                // Fail silently or log if not postgres
            }
        }

        return $next($request);
    }
}
