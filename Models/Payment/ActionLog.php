<?php

namespace Modules\Laralite\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use Modules\Laralite\Models\MetaData;
use Modules\Laralite\Models\Payment;

class ActionLog extends Model
{
    use MetaData;

    protected $table = 'payment_action_log';

    public const ACTION_TYPE_CREATE = 'CREATE';
    public const ACTION_TYPE_CONFIRM = 'CONFIRM';
    public const ACTION_TYPE_CANCEL = 'CANCEL';
    public const ACTION_TYPE_REFUND = 'REFUND';

    public const RESULT_STATUS_SUCCEED = 'SUCCEED';
    public const RESULT_STATUS_FAILED = 'FAILED';

    protected $fillable = [
        'payment_id',
        'action_type',
        'result_status',
        'payment_processor_result',
        'meta_data',
    ];

    protected $attributes = [
        'meta_data' => '{
            "reason": null,
        }',
    ];


    protected $casts = [
        'payment_processor_result' => 'array',
    ];

    public function payment(): void
    {
        $this->belongsTo(Payment::class, 'payment_id');
    }
}
