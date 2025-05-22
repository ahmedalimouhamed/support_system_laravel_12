<?php
namespace App\View\Components;

use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }

    public function colors()
    {
        return [
            'open' => 'bg-blue-100 text-blue-800',
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'resolved' => 'bg-green-100 text-green-800',
            'closed' => 'bg-red-100 text-red-800',
        ];
    }

    public function labels()
    {
        return [
            'open' => 'Ouvert',
            'in_progress' => 'En cours',
            'resolved' => 'Résolu',
            'closed' => 'Fermer',
        ];
    }

    public function render()
    {
        return view('components.status-badge', [
            'color' => $this->colors()[$this->status],
            'label' => $this->labels()[$this->status],
        ]);
    }
}
