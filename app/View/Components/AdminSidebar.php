<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class AdminSidebar extends Component
{
    public array $menuGeral = [];
    public array $menuCatalogo = [];
    public array $menuOperacoes = [];
    public ?string $profileImageSrc = null;
    public ?string $userName = null;

    public function __construct()
    {
        $user = Auth::user();

        if ($user) {
            $this->userName = $user->name;

            if (isset($user->photo_url)) {
                $this->profileImageSrc = str_contains($user->photo_url, '/')
                    ? asset('storage/' . $user->photo_url)
                    : asset('storage/photos/' . $user->photo_url);
            }
        }

        $this->loadMenus();
    }

    private function loadMenus()
    {
        $ic = [
            'dash'   => '<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>',
            'stats'  => '<path d="M18 20V10M12 20V4M6 20v-6"/>',
            'img'    => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5" fill="currentColor" stroke="none"/><path d="M21 15l-5-5L5 21"/>',
            'cat'    => '<path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><circle cx="7" cy="7" r="1" fill="currentColor" stroke="none"/>',
            'col'    => '<circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4" fill="currentColor" stroke="none" opacity=".4"/>',
            'price'  => '<circle cx="12" cy="12" r="10"/><path d="M16 8h-6a2 2 0 000 4h4a2 2 0 010 4H8M12 6v2m0 8v2"/>',
            'orders' => '<path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/>',
            'users'  => '<path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>',
            'staff'  => '<rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>',
        ];

        $this->menuGeral = [
            ['route' => 'admin.dashboard', 'icon' => $ic['dash'], 'label' => 'Dashboard', 'pattern' => 'admin.dashboard'],
            ['route' => 'admin.statistics', 'icon' => $ic['stats'], 'label' => 'Estatísticas', 'pattern' => 'admin.statistics'],
        ];

        $this->menuCatalogo = [
            ['route' => 'admin.catalog', 'icon' => $ic['img'], 'label' => 'Imagens', 'pattern' => 'admin.catalog'],
            ['route' => 'admin.categories', 'icon' => $ic['cat'], 'label' => 'Categorias', 'pattern' => 'admin.categories'],
            ['route' => 'admin.colors', 'icon' => $ic['col'], 'label' => 'Cores', 'pattern' => 'admin.colors'],
            ['route' => 'admin.prices', 'icon' => $ic['price'], 'label' => 'Preços', 'pattern' => 'admin.prices'],
        ];

        $this->menuOperacoes = [
            ['route' => 'admin.orders', 'icon' => $ic['orders'], 'label' => 'Encomendas', 'pattern' => 'admin.orders*'],
            ['route' => 'admin.customers', 'icon' => $ic['users'], 'label' => 'Clientes', 'pattern' => 'admin.customers'],
            ['route' => 'admin.staff', 'icon' => $ic['staff'], 'label' => 'Funcionários', 'pattern' => 'admin.staff'],
        ];
    }

    public function render()
    {
        return view('components.admin-sidebar');
    }
}
