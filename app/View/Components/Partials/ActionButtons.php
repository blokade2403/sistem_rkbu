<?php

namespace App\View\Components\Partials;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ActionButtons extends Component
{
    public ?string $editUrl;
    public ?string $deleteUrl;

    public function __construct($editUrl = null, $deleteUrl = null)
    {
        $this->editUrl = $editUrl;
        $this->deleteUrl = $deleteUrl;
    }

    public function render()
    {
        return view('components.partials.action-buttons');
    }
}
