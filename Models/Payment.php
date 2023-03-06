<?php

namespace Modules\Laralite\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Laralite\Models\Customer\Subscription;
use Modules\Laralite\Models\Payment\ActionLog;

/**
 * @package Modules\Laralite\Models
 * @mixin Eloquent
 * @property int $id
 * @property string $status
 * @property array $payment_processor_result
 * @property string $ext_id
 * @property string $payable_type
 * @property int $payable_id
 */
class Payment extends Model
{
    use StripeMetaData;

    protected $fillable = [
        'ext_id',
        'meta_data',
        'payment_processor_result',
        'status_id',
        'payable_id',
        'payable_type',
        'type_id',
    ];

    protected $casts = [
        'payment_processor_result' => 'array',
        'meta_data' => 'array',
    ];

    protected $attributes = [
        'meta_data' => '{}',
    ];

    public const STATUS_CREATED = 'CREATED';
    public const STATUS_COMPLETE = 'COMPLETED';
    public const STATUS_REFUNDED = 'REFUNDED';
    public const STATUS_PENDING = 'PENDING';
    public const STATUS_FAILED = 'FAILED';
    public const STATUS_CANCELED = 'CANCELED';
    public const STATUS_3DS_AUTHENTICATION_REQUIRED = '3DS_AUTH_REQUIRED';

    public const TYPE_STANDARD = 'STANDARD';
    public const TYPE_RECURRING = 'RECURRING';
    public const TYPE_CREDIT = 'CREDIT';

    private array $payableTypes = [
        Order::class,
        Subscription::class,
    ];

    /**
     * Get the owning commentable model.
     */
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function setPayableType(string $type): void
    {
        if (!in_array($type, $this->payableTypes, true)) {
            throw new \RuntimeException('Invalid payable type set `' . $type . '`');
        }
        $this->setAttribute('payable_type', $type);
    }

    public function setPayableId(int $id)
    {
        $this->setAttribute('payable_id', $id);
    }

    public function getStripeClientSecret()
    {
        return $this->payment_processor_result['client_secret'] ?? null;
    }

    public function actionLog(): HasMany
    {
        return $this->hasMany(ActionLog::class, 'payment_id', 'id')
            ->orderBy('created_at', 'desc');
    }
}