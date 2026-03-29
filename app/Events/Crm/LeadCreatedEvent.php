<?php declare(strict_types=1);

namespace App\Events\Crm;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreatedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * This event carries the lead data so that other parts of the 
     * app (like the email system) can use it later.
     */
    public function __construct(
        public array $lead
    ) {}
}