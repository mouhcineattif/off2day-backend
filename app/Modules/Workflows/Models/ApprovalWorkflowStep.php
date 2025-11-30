<?php
namespace App\Modules\Workflows\Models;

use App\Core\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalWorkflowStep extends BaseModel
{
    protected $table = 'approval_workflow_steps';

    protected $fillable = [
        'workflow_id',
        'step_order',
        'parallel_group',
        'selector_type',
        'selector_value',
        'allow_override',
        'condition_json',
    ];

    protected $casts = [
        'parallel_group' => 'integer',
        'step_order' => 'integer',
        'allow_override' => 'boolean',
        'condition_json' => 'array',
    ];

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'workflow_id');
    }
}
