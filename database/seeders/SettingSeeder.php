<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaults = [
            // Identitas & Branding
            ['key' => 'site_name', 'value' => 'RazorAPI', 'group' => 'branding', 'type' => 'string'],
            ['key' => 'site_title', 'value' => 'API Gateway & Management', 'group' => 'branding', 'type' => 'string'],
            ['key' => 'sidebar_title', 'value' => 'RazorAPI', 'group' => 'branding', 'type' => 'string'],
            ['key' => 'auth_subtitle', 'value' => 'API Platform', 'group' => 'branding', 'type' => 'string'],
            ['key' => 'auth_description', 'value' => 'Platform manajemen API modern untuk pengelolaan gateway, autentikasi client, dan analitik performa.', 'group' => 'branding', 'type' => 'text'],
            ['key' => 'app_version', 'value' => 'v2.4.0', 'group' => 'branding', 'type' => 'string'],

            // SEO & Meta
            ['key' => 'meta_description', 'value' => 'RazorAPI - Modern API Gateway & Client Management Platform', 'group' => 'seo', 'type' => 'text'],
            ['key' => 'meta_keywords', 'value' => 'api, gateway, management, razorapi, developer, portal', 'group' => 'seo', 'type' => 'string'],
            ['key' => 'meta_author', 'value' => 'RazorAPI', 'group' => 'seo', 'type' => 'string'],

            // Footer & Lisensi
            ['key' => 'footer_company', 'value' => 'RazorAPI™', 'group' => 'footer', 'type' => 'string'],
            ['key' => 'footer_url', 'value' => 'https://razorext.my.id', 'group' => 'footer', 'type' => 'string'],
            ['key' => 'footer_copyright', 'value' => 'All Rights Reserved.', 'group' => 'footer', 'type' => 'string'],

            // Media & Assets
            ['key' => 'logo_path', 'value' => null, 'group' => 'media', 'type' => 'image'],
            ['key' => 'favicon_path', 'value' => null, 'group' => 'media', 'type' => 'image'],
            ['key' => 'apple_touch_icon_path', 'value' => null, 'group' => 'media', 'type' => 'image'],

            // Kontak & Integrasi
            ['key' => 'contact_email', 'value' => 'support@razorext.my.id', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'whatsapp_number', 'value' => '628123456789', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'office_address', 'value' => 'Jl. Raya Industri No. 88, Jakarta', 'group' => 'contact', 'type' => 'text'],
            ['key' => 'google_analytics_id', 'value' => '', 'group' => 'integration', 'type' => 'string'],
            ['key' => 'social_facebook', 'value' => '', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'social_instagram', 'value' => '', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'social_linkedin', 'value' => '', 'group' => 'contact', 'type' => 'string'],
        ];

        foreach ($defaults as $data) {
            Setting::firstOrCreate(
                ['key' => $data['key']],
                [
                    'value' => $data['value'],
                    'group' => $data['group'],
                    'type' => $data['type'],
                ]
            );
        }

        Setting::clearCache();
    }
}
