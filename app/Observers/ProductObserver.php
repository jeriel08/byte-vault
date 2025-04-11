<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Traits\LogsAudit;
use Exception;

class ProductObserver
{
    use LogsAudit;
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->logAudit($product, 'create', 'created', null, json_encode($product->toArray()));
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        //
        // Log each changed column
        foreach ($product->getChanges() as $key => $value) {
            if ($key !== 'updated_at' && $key !== 'created_at') { // Skip timestamp changes
                $this->logAudit($product, 'update', $key, $product->getOriginal($key), $value);
            }
        }
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
        $this->logAudit($product, 'delete', 'deleted', json_encode($product->toArray()), null);
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
