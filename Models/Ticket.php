<?php

namespace Modules\Laralite\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'unique_id',
        'customer_id',
        'sku',
        'order_id',
        'ticket',
        'admit_quantity',
        'status_log',
        'status'
    ];

    protected $casts = [
        'ticket' => 'object',
        'status_log' => 'array'
    ];

    public const STATUS_GENERATED = 'GENERATED';
    public const STATUS_REDEEMED = 'REDEEMED';
    public const STATUS_UNREDEEMED = 'UNREDEEMED';
    public const STATUS_CANCELLED = 'CANCELLED';

    private $logStatuses = [
        self::STATUS_GENERATED,
        self::STATUS_REDEEMED,
        self::STATUS_CANCELLED,
        self::STATUS_UNREDEEMED,
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    /**
     * @param string $status
     * @param array $data
     */
    public function updateStatusLog(string $status, array $data = []): void
    {
        if (!in_array($status, $this->logStatuses)) {
            throw new \InvalidArgumentException('Invalid status type ' . $status);
        }

        $this->setAttribute('status_log', $this->getAttributeValue('status_log') ?: []);
        $newEntry = [
            'status' => $status,
            'date' => Carbon::now()->toDateTimeString(),
        ];

        if ($data) {
            $newEntry['data'] = $data;
        }

        $updatedLog = $this->getAttributeValue('status_log');
        $updatedLog[] = $newEntry;

        $this->setAttribute('status_log', $updatedLog);
    }

    public static function generateInitialStatusLogEntry(): array
    {
        return [
            [
                'status' => 'GENERATED',
                'date' => Carbon::now()->toDateTimeString(),
            ]
        ];
    }
}
