<?php

namespace App\View\Components;

use Illuminate\View\Component;

class NavLink extends Component
{
    public string $route;
    public string $icon;
    public string $label;
    public string $linkClasses;
    public string $iconClasses;

    public function __construct(array $item)
    {
        $this->route = route($item['route']);
        $this->icon = $item['icon'];
        $this->label = $item['label'];

        $active = request()->routeIs($item['pattern']);

        $this->linkClasses = $active
            ? 'text-[#7c6fa0] bg-[rgba(124,111,160,.06)] border-[rgba(124,111,160,.2)] font-semibold'
            : 'text-[#888] border-transparent hover:text-[#1a1a1a] hover:bg-[rgba(0,0,0,.04)]';

        $this->iconClasses = $active ? 'stroke-[#7c6fa0]' : 'stroke-[#bbb]';
    }

    public function render()
    {
        return view('components.nav-link');
    }
}
