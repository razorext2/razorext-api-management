<?php

/** Goal: Generate and render dynamic breadcrumbs with truncated segment titles, Caller: layouts/app, Deps: Livewire\Component */

namespace App\Livewire\Utils;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Breadcrumb extends Component
{
    public bool $isNavbar = false;

    public function render(): View
    {
        $segments = request()->segments();
        $crumbs = [];
        $path = '';

        foreach ($segments as $segment) {
            $path .= '/'.$segment;
            $title = ucfirst(str_replace('-', ' ', $segment));
            if (mb_strlen($title) > 10) {
                $title = mb_substr($title, 0, 10).'...';
            }

            $url = null;
            try {
                $subRequest = \Illuminate\Http\Request::create($path, 'GET');
                $matchedRoute = \Illuminate\Support\Facades\Route::getRoutes()->match($subRequest);
                if ($matchedRoute && ! ($matchedRoute->isFallback ?? false)) {
                    $url = url($path);
                }
            } catch (\Throwable) {
                $url = null;
            }

            array_push($crumbs, [
                'title' => $title,
                'url' => $url,
            ]);
        }

        return view('livewire.utils.breadcrumb', ['crumbs' => $crumbs]);
    }
}
