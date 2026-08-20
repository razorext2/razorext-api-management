<?php

namespace App\Livewire\Handler\Settings;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use HandlesErrors, WithFileUploads;

    public string $site_name = '';

    public string $site_title = '';

    public string $sidebar_title = '';

    public string $auth_subtitle = '';

    public string $auth_description = '';

    public string $app_version = '';

    public string $meta_description = '';

    public string $meta_keywords = '';

    public string $meta_author = '';

    public string $footer_company = '';

    public string $footer_url = '';

    public string $footer_copyright = '';

    public string $contact_email = '';

    public string $whatsapp_number = '';

    public string $office_address = '';

    public string $google_analytics_id = '';

    public string $social_facebook = '';

    public string $social_instagram = '';

    public string $social_linkedin = '';

    public mixed $new_logo = null;

    public mixed $new_favicon = null;

    public mixed $new_apple_touch_icon = null;

    public ?string $logo_path = null;

    public ?string $favicon_path = null;

    public ?string $apple_touch_icon_path = null;

    public string $activeTab = 'branding';

    public function mount(): void
    {
        $this->site_name = (string) setting('site_name', 'RazorAPI');
        $this->site_title = (string) setting('site_title', 'API Gateway & Management');
        $this->sidebar_title = (string) setting('sidebar_title', 'RazorAPI');
        $this->auth_subtitle = (string) setting('auth_subtitle', 'API Platform');
        $this->auth_description = (string) setting('auth_description', 'Platform manajemen API modern untuk pengelolaan gateway, autentikasi client, dan analitik performa.');
        $this->app_version = (string) setting('app_version', 'v2.4.0');

        $this->meta_description = (string) setting('meta_description', 'RazorAPI - Modern API Gateway & Client Management Platform');
        $this->meta_keywords = (string) setting('meta_keywords', 'api, gateway, management, razorapi, developer, portal');
        $this->meta_author = (string) setting('meta_author', 'RazorAPI');

        $this->footer_company = (string) setting('footer_company', 'RazorAPI™');
        $this->footer_url = (string) setting('footer_url', 'https://razorext.my.id');
        $this->footer_copyright = (string) setting('footer_copyright', 'All Rights Reserved.');

        $this->contact_email = (string) setting('contact_email', 'support@razorext.my.id');
        $this->whatsapp_number = (string) setting('whatsapp_number', '628123456789');
        $this->office_address = (string) setting('office_address', 'Jl. Raya Industri No. 88, Jakarta');
        $this->google_analytics_id = (string) setting('google_analytics_id', '');
        $this->social_facebook = (string) setting('social_facebook', '');
        $this->social_instagram = (string) setting('social_instagram', '');
        $this->social_linkedin = (string) setting('social_linkedin', '');

        $this->logo_path = setting('logo_path');
        $this->favicon_path = setting('favicon_path');
        $this->apple_touch_icon_path = setting('apple_touch_icon_path');
    }

    protected function rules(): array
    {
        return [
            'site_name' => 'required|string|max:100',
            'site_title' => 'required|string|max:150',
            'sidebar_title' => 'nullable|string|max:100',
            'auth_subtitle' => 'nullable|string|max:100',
            'auth_description' => 'nullable|string|max:500',
            'app_version' => 'nullable|string|max:50',

            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'meta_author' => 'nullable|string|max:150',

            'footer_company' => 'nullable|string|max:150',
            'footer_url' => 'nullable|url|max:255',
            'footer_copyright' => 'nullable|string|max:150',

            'contact_email' => 'nullable|email|max:150',
            'whatsapp_number' => 'nullable|string|max:50',
            'office_address' => 'nullable|string|max:500',

            'new_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
            'new_favicon' => 'nullable|file|mimes:ico,png,jpg,svg|max:1024',
            'new_apple_touch_icon' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:1024',
        ];
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function save(): mixed
    {
        $this->validate();

        return $this->runSafely(function () {
            DB::transaction(function () {
                // Text attributes
                Setting::set('site_name', $this->site_name, 'branding');
                Setting::set('site_title', $this->site_title, 'branding');
                Setting::set('sidebar_title', $this->sidebar_title, 'branding');
                Setting::set('auth_subtitle', $this->auth_subtitle, 'branding');
                Setting::set('auth_description', $this->auth_description, 'branding', 'text');
                Setting::set('app_version', $this->app_version, 'branding');

                Setting::set('meta_description', $this->meta_description, 'seo', 'text');
                Setting::set('meta_keywords', $this->meta_keywords, 'seo');
                Setting::set('meta_author', $this->meta_author, 'seo');

                Setting::set('footer_company', $this->footer_company, 'footer');
                Setting::set('footer_url', $this->footer_url, 'footer');
                Setting::set('footer_copyright', $this->footer_copyright, 'footer');

                Setting::set('contact_email', $this->contact_email, 'contact');
                Setting::set('whatsapp_number', $this->whatsapp_number, 'contact');
                Setting::set('office_address', $this->office_address, 'contact', 'text');
                Setting::set('google_analytics_id', $this->google_analytics_id, 'integration');
                Setting::set('social_facebook', $this->social_facebook, 'contact');
                Setting::set('social_instagram', $this->social_instagram, 'contact');
                Setting::set('social_linkedin', $this->social_linkedin, 'contact');

                // Handle File Uploads
                if ($this->new_logo) {
                    $path = $this->new_logo->store('settings', 'public');
                    Setting::set('logo_path', $path, 'media', 'image');
                    $this->logo_path = $path;
                    $this->new_logo = null;
                }

                if ($this->new_favicon) {
                    $path = $this->new_favicon->store('settings', 'public');
                    Setting::set('favicon_path', $path, 'media', 'image');
                    $this->favicon_path = $path;
                    $this->new_favicon = null;
                }

                if ($this->new_apple_touch_icon) {
                    $path = $this->new_apple_touch_icon->store('settings', 'public');
                    Setting::set('apple_touch_icon_path', $path, 'media', 'image');
                    $this->apple_touch_icon_path = $path;
                    $this->new_apple_touch_icon = null;
                }
            });

            Setting::clearCache();

            $this->dispatch('swal',
                title: 'Berhasil!',
                text: 'Atribut website telah berhasil diperbarui.',
                icon: 'success'
            );
        }, 'Gagal memperbarui pengaturan website', [
            'action' => 'update settings',
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.settings.index');
    }
}
