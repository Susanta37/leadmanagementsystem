<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Lead;
use App\Models\User;

class NewLeadAssigned extends Notification
{
    protected $lead;
    protected $employee;

    public function __construct(Lead $lead, User $employee)
    {
        $this->lead = $lead;
        $this->employee = $employee;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Lead Assigned to You')
            ->line("A new lead '{$this->lead->name}' has been forwarded to you by {$this->employee->name}.")
            ->line("Lead Details:")
            ->line("Name: {$this->lead->name}")
            ->line("Company: " . ($this->lead->company_name ?? 'N/A'))
            ->line("Amount: ₹" . number_format($this->lead->lead_amount, 0))
            ->action('View Lead', route('team_lead.leads.index'))
            ->line('Please review the lead and take appropriate action.');
    }

    public function toArray($notifiable)
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_name' => $this->lead->name,
            'employee_name' => $this->employee->name,
            'message' => "New lead '{$this->lead->name}' forwarded by {$this->employee->name}.",
            'created_at' => now()->toDateTimeString()
        ];
    }
}