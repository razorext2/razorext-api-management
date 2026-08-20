# System Design & Architecture Guide
## RazorAPI — API Management Platform & Gateway System

> Dokumen ini adalah panduan teknis definitif untuk memahami, mengembangkan, dan mem-fork project ini.
> Baca seluruh dokumen sebelum melakukan perubahan arsitektural.

---

## Daftar Isi

1. [Gambaran Sistem](#1-gambaran-sistem)
2. [Tech Stack](#2-tech-stack)
3. [Arsitektur Lapisan (Layer Architecture)](#3-arsitektur-lapisan)
4. [Struktur Direktori Definitif](#4-struktur-direktori-definitif)
5. [Alur Request-Response](#5-alur-request-response)
6. [Pola Arsitektur Handler](#6-pola-arsitektur-handler)
7. [Error Handling System](#7-error-handling-system)
8. [Security Architecture](#8-security-architecture)
9. [RBAC — Roles & Permissions](#9-rbac--roles--permissions)
10. [Event & Real-time System](#10-event--real-time-system)
11. [Activity Logging](#11-activity-logging)
12. [Sistem Setting (Site Configuration)](#12-sistem-setting)
13. [Frontend Architecture](#13-frontend-architecture)
14. [Styling System](#14-styling-system)
15. [Panduan: Do & Don't](#15-panduan-do--dont)
16. [Behavior Rules per Layer](#16-behavior-rules-per-layer)
17. [Panduan Fork Project Ini](#17-panduan-fork-project-ini)

---

## 1. Gambaran Sistem

RazorAPI adalah **API Management Platform** berbasis **Laravel + Livewire SPA** untuk pengelolaan gateway API, manajemen client & API keys, interactive sandbox, analitik data, dan user/role access control. Aplikasi ini didesain sebagai **single-page application** menggunakan Livewire 4 wire:navigate.

```
+-----------------------------------------------------+
|                 Browser (SPA Mode)                  |
|         Livewire wire:navigate -- no full reload    |
+----------------------+------------------------------+
                       | HTTP
+----------------------v------------------------------+
|              Laravel Application Server             |
|              (PHP 8.4 + Octane/FPM)                 |
|                                                     |
|  Middleware Stack -> Router -> Livewire Handler     |
|       v                                             |
|  HandlesErrors -> Business Logic -> Eloquent ORM   |
|       v                                             |
|  Events -> Broadcasting (Pusher) -> WebPush        |
+----------------------+------------------------------+
                       |
         +-------------+-------------+
         v                           v
   MySQL Database              Redis Cache
   (Eloquent ORM)          (Settings, Permissions,
                            GitHub Stats, Sessions)
```

---

## 2. Tech Stack

| Layer              | Package                    | Versi     | Catatan                                  |
|--------------------|----------------------------|-----------|------------------------------------------|
| **Backend**        | Laravel Framework          | ^13.0     | Foundation                               |
| **PHP**            | PHP                        | 8.4       | Fitur: property promotion, enums, fibers |
| **Auth/Session**   | Laravel Breeze             | v2        | Auth scaffolding                         |
| **Livewire**       | Livewire                   | ^4.0      | Full-page SPA, wire:navigate             |
| **RBAC**           | Spatie Permission          | ^8.0      | Role & Permission management             |
| **Tables**         | LiveWire PowerGrid         | ^6.1      | Data tables dengan filter & sort         |
| **Queue Monitor**  | Laravel Horizon            | ^5.47     | Background jobs dashboard                |
| **Performance**    | Laravel Octane             | ^2.6      | Long-running PHP server                  |
| **API Auth**       | Laravel Sanctum            | ^4.0      | Token-based API auth (Push notif)        |
| **Real-time**      | Laravel Echo + Pusher      | v1 / v8   | WebSocket broadcasting                   |
| **CSS**            | Tailwind CSS               | ^3.4      | Utility-first styling                    |
| **Alpine.js**      | Alpine.js                  | ^3.14     | Client-side interactivity                |
| **Alerts**         | SweetAlert2                | ^11       | User feedback (swal event)               |
| **Rich Text**      | Quill                      | ^2.0      | Announcement editor                      |
| **Date Picker**    | Flatpickr                  | ^4.6      | Input date component                     |
| **Select**         | Tom Select                 | ^2.4      | Searchable select (CDN)                  |
| **Testing**        | Pest                       | ^4.0      | Behavior-driven testing                  |
| **Formatter**      | Laravel Pint               | ^1.0      | Code style enforcer                      |

---

## 3. Arsitektur Lapisan

```
+-------------------------------------------------------------+
|  PRESENTATION LAYER                                         |
|  +-----------------------------------------------------+   |
|  |  Blade Views (layouts/app.blade.php)                |   |
|  |  +- Livewire Full-Page Components (Handler/)        |   |
|  |  +- Livewire Reusable Utils (Utils/)                |   |
|  |  +- PowerGrid Tables (PowergridTables/)             |   |
|  |  +- Blade Components (resources/views/components/)  |   |
|  +-----------------------------------------------------+   |
+-------------------------------------------------------------+
         | wire:navigate, wire:click, wire:model
+-------------------------------------------------------------+
|  APPLICATION LAYER                                          |
|  +----------------------+  +---------------------------+   |
|  |  Livewire Forms      |  |  HTTP Controllers         |   |
|  |  (App\Livewire\Forms)|  |  (minimal -- PushCtrl)    |   |
|  +----------------------+  +---------------------------+   |
|  +------------------------------------------------------+  |
|  |  Concerns: HandlesErrors trait                       |  |
|  |  Enums: AnnouncementStatus, ...                      |  |
|  +------------------------------------------------------+  |
+-------------------------------------------------------------+
         | Eloquent
+-------------------------------------------------------------+
|  DOMAIN LAYER                                               |
|  +------------------------------------------------------+  |
|  |  Models: User, Announcement, Setting, LogHistory     |  |
|  |  Events: NewAnnouncementEvent, TableRefreshed        |  |
|  |  Notifications: (DB + WebPush)                       |  |
|  +------------------------------------------------------+  |
+-------------------------------------------------------------+
         |
+-------------------------------------------------------------+
|  INFRASTRUCTURE LAYER                                       |
|  +------------------+  +--------------+  +------------+   |
|  |  MySQL (Eloquent)|  |  Redis Cache |  |  Pusher    |   |
|  +------------------+  +--------------+  +------------+   |
|  +------------------+  +-------------------------------+  |
|  |  Storage (Local) |  |  Queue (Horizon + Redis)      |  |
|  +------------------+  +-------------------------------+  |
+-------------------------------------------------------------+
```

---

## 4. Struktur Direktori Definitif

```
dacin-dashboard/
+-- app/
|   +-- Console/                  # Artisan commands
|   +-- Enums/                    # PHP 8.1+ Backed Enums
|   |   +-- AnnouncementStatus.php
|   +-- Events/                   # Laravel Events (Broadcast)
|   |   +-- NewAnnouncementEvent.php
|   |   +-- TableRefreshed.php
|   +-- Exceptions/
|   |   +-- BusinessException.php  # Exception bisnis (user-facing message)
|   +-- Helpers/
|   |   +-- ErrorLogger.php        # Centralized error logger dengan UUID
|   +-- Http/
|   |   +-- Controllers/
|   |   |   +-- PushController.php  # Khusus Web Push subscription
|   |   |   +-- Api/               # API endpoints (jika ada)
|   |   +-- Middleware/
|   |   |   +-- LogUserActions.php      # Audit trail semua request
|   |   |   +-- TrackUserActivity.php   # Update last_login
|   |   |   +-- EnsureDatabaseConnection.php
|   |   |   +-- RemoveHeadersMiddleware.php # Security headers cleanup
|   |   +-- Requests/              # Form Request untuk API
|   +-- Jobs/                      # Queue jobs
|   +-- Listeners/                 # Event Listeners
|   +-- Livewire/
|   |   +-- Components/            # Livewire reusable components
|   |   +-- Concerns/
|   |   |   +-- HandlesErrors.php  # CRITICAL TRAIT -- wajib di semua Handler
|   |   +-- Forms/                 # Livewire Form Objects
|   |   |   +-- Permissions/Post.php
|   |   |   +-- Roles/Post.php
|   |   +-- Handler/               # Full-page Livewire components per domain
|   |   |   +-- Announcement/
|   |   |   |   +-- Index.php      # Tabel (PowerGrid embed)
|   |   |   |   +-- Create.php     # Form tambah
|   |   |   |   +-- Edit.php       # Form edit
|   |   |   +-- Permissions/
|   |   |   |   +-- Index.php
|   |   |   |   +-- Create.php
|   |   |   |   +-- Update.php
|   |   |   |   +-- Delete.php     # Komponen delete per-row
|   |   |   +-- Roles/
|   |   |   |   +-- Index.php
|   |   |   |   +-- Create.php
|   |   |   |   +-- Update.php
|   |   |   |   +-- Delete.php
|   |   |   +-- Settings/
|   |   |   |   +-- Index.php      # Tab-based settings page
|   |   |   +-- User/
|   |   |       +-- Index.php
|   |   |       +-- Create.php
|   |   |       +-- Edit.php
|   |   +-- PowergridTables/       # Data tables
|   |   |   +-- AnnouncementTable.php
|   |   |   +-- LogTable.php
|   |   |   +-- PermissionsTable.php
|   |   |   +-- RolesTable.php
|   |   |   +-- UserTable.php
|   |   +-- Utils/                 # Widget/utility components
|   |       +-- AnnouncementContainer.php
|   |       +-- Breadcrumb.php
|   |       +-- Greetings.php
|   |       +-- NotificationDropdown.php
|   |       +-- PingChecker.php
|   |       +-- ProfilePictureUploader.php
|   |       +-- UpdateLog.php
|   +-- Models/
|   |   +-- User.php
|   |   +-- Announcement.php
|   |   +-- AnnouncementRead.php
|   |   +-- Setting.php
|   |   +-- LogHistory.php
|   +-- Notifications/
|   +-- Providers/
+-- config/
|   +-- navigation.php             # Config-driven sidebar navigation
+-- database/
|   +-- factories/
|   +-- migrations/
|   +-- seeders/
+-- resources/
|   +-- css/
|   |   +-- app.css                # Entry point CSS
|   |   +-- liquid-glass.css       # Glassmorphism effects
|   +-- js/
|   |   +-- app.js                 # Entry point JS
|   |   +-- main.js                # Runtime: theme, PWA, push
|   |   +-- components/            # Alpine.js component modules
|   |   +-- utils/
|   |       +-- alert.js           # SweetAlert2 helpers
|   |       +-- eventListener.js   # Global Livewire event listeners
|   +-- views/
|       +-- layouts/
|       |   +-- app.blade.php      # SATU master layout
|       +-- livewire/
|           +-- handler/           # Mirror dari app/Livewire/Handler/
+-- routes/
|   +-- web.php                    # Semua route dashboard
|   +-- auth.php                   # Route auth (Breeze)
+-- tests/
    +-- Feature/
    +-- Unit/
```

> **ATURAN KRITIS**: Jangan buat folder baru di luar struktur ini. Jika domain baru dibutuhkan, buat subdirektori di `Handler/NamaDomain/` mengikuti pattern yang ada.

---

## 5. Alur Request-Response

### GET Request (Akses Halaman)
```
User -> Browser
  v HTTP GET /dashboard/users
Router (web.php)
  v middleware(['auth', 'permission:users-list'])
Livewire Handler\User\Index::class
  v mount() -> load initial state
  v render() -> return view('livewire.handler.user.index')
Blade View -> layouts.app.blade.php
  v embed <livewire:powergrid-tables.user-table />
PowerGrid -> datasource() query -> paginated results
  v
Browser renders HTML (no full JS framework overhead)
```

### POST/Action Request (CRUD)
```
User clicks button
  v wire:click="save"
Livewire AJAX request ke /livewire/update
  v
Handler::save()
  v validate()
  v runSafely(callback)
    v [BusinessException] -> dispatch('swal', error) + addError()
    v [Throwable]         -> ErrorLogger::log() -> dispatch('swal', kode error)
    v [Success]           -> DB operation -> dispatch('swal', success) -> redirect
Livewire DOM diff -> update hanya bagian yang berubah
```

---

## 6. Pola Arsitektur Handler

Handler adalah full-page Livewire component yang menjadi pusat logika domain.

### Anatomi Handler Standard

```php
<?php
// app/Livewire/Handler/NamaDomain/Create.php

namespace App\Livewire\Handler\NamaDomain;

use App\Livewire\Concerns\HandlesErrors;
use App\Enums\SomeStatus;          // Gunakan Enum untuk magic values
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    use HandlesErrors; // WAJIB -- jangan pernah skip ini

    // Properties: typed, nullable, dengan default
    public ?string $name = null;
    public array $selectedItems = [];
    public bool $showModal = false;

    // Validation: method rules() atau #[Validate] attribute
    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }

    // Mount: terima route model binding atau ID
    public function mount(): void
    {
        // Inisialisasi state dari DB, session, dsb.
    }

    // Action: SELALU gunakan runSafely()
    public function save(): void
    {
        $this->validate();

        $this->runSafely(function () {
            DB::transaction(function () {
                // ... business logic
            });

            $this->dispatch('swal', title: 'Berhasil', text: '...', icon: 'success');
            $this->redirect(route('domain.index'), navigate: true);
        }, 'Pesan log jika terjadi error', [
            'action' => 'create domain',
            'user_id' => auth()->id(),
        ]);
    }

    // render: HANYA return view. Jangan ada business logic di sini.
    public function render(): View
    {
        return view('livewire.handler.nama-domain.create', [
            'supportingData' => SomeModel::query()->get(),
        ]);
    }
}
```

### Pattern Tiga Komponen untuk CRUD dengan Delete

```
Index.php   -> embed PowerGrid tabel + embed Delete.php per row
Create.php  -> form untuk membuat data baru
Update.php  -> form edit dengan route model binding

Delete.php  -> Livewire komponen kecil per-row
  public int $id;
  public function delete(): void
    -> dispatch('confirmDelete', id: $this->id)
    -> JS interceptor tampilkan SweetAlert confirm
    -> dispatch `confirmDeleteAction.{id}` (scoped per ID!)
  #[On('confirmDeleteAction.{id}')]
  public function confirmDeleteAction(): void
    -> lakukan delete
```

---

## 7. Error Handling System

Project ini memiliki sistem error handling **dua-level** yang terstruktur:

```
           User Action
               |
         runSafely(callback)
               |
     +---------+---------+
     |                   |
BusinessException      Throwable
(Error bisnis --       (Error sistem --
 pesan langsung         pesan generic)
 ke user via swal)          |
                       ErrorLogger::log()
                            |
                       UUID -> Laravel Log
                            |
                       "Kode: {uuid}"
                       ditampilkan ke user
```

### BusinessException -- Kapan Digunakan

```php
// Di dalam service/form/model:
if ($user->isAlreadyDeactivated()) {
    throw new BusinessException('User sudah dinonaktifkan sebelumnya.');
}

// HandlesErrors akan catch ini dan tampilkan pesan langsung ke user
// TIDAK perlu try-catch di Handler
```

### Aturan Error Handling

| Situasi | Cara |
|---|---|
| Error validasi input | `$this->validate()` atau `#[Validate]` -- pesan merah otomatis |
| Error domain/bisnis (expected) | Lempar `BusinessException` -- message terlihat user |
| Error sistem (unexpected) | Biarkan `Throwable` -- `ErrorLogger` log UUID |
| Form class (Livewire Form) | **JANGAN** try-catch. Biarkan exception naik ke `runSafely` |
| DB Transaction | `DB::transaction()` di dalam `runSafely` callback |

---

## 8. Security Architecture

### Lapisan Keamanan

```
Browser
  v
[RemoveHeadersMiddleware]     -- Hapus X-Powered-By, Server header
[throttle:high]               -- Rate limiting untuk landing page
  v
[auth]                        -- Session authentication (Breeze)
  v
[permission:xxx-yyy]          -- Spatie Permission route middleware
  v
Handler Component             -- mount() bisa tambah authorize() untuk policy
  v
runSafely()                   -- Exception boundary
  v
Eloquent ORM                  -- Parameterized queries (no SQL injection)
```

### File Streaming Security

Route `/file/{path}` menerapkan:
1. **Auth required** -- `middleware('auth')`
2. **Path traversal prevention** -- reject `..` sequences
3. **Whitelist karakter** -- hanya `[\w/\-.]+`

```php
Route::middleware('auth')->get('/file/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 403);
    abort_unless(preg_match('#^[\w/\-.]+$#', $path), 400);
    abort_unless(Storage::exists($path), 404);
    return Storage::response($path);
})->where('path', '.*');
```

### Password Policy

```php
Password::min(8)->letters()->mixedCase()->numbers()->uncompromised()
// Minimum: 8 karakter + huruf + uppercase+lowercase + angka + tidak bocor di HaveIBeenPwned
```

### Delete Action Sudo Mode Re-Authentication

Setiap aksi **penghapusan data (delete action)** wajib menerapkan Sudo Mode:
- **Trait**: `App\Livewire\Concerns\RequiresSudoMode`
- **Session Timeout**: 15 menit (`auth.sudo_confirmed_at`)
- **Modal Component**: `<livewire:utils.sudo-modal />`
- **Opsi Verifikasi**: Re-enter Password atau 1-tap **Passkey / Face ID**.

```php
if (! $this->isSudoConfirmed()) {
    $this->dispatch('openSudoModal', targetEventName: "confirmDeleteAction.{$this->id}");
    return;
}
```

### Advanced Session & Device Management

- **Component**: `App\Livewire\Profile\SessionManager`
- **Fitur**: Deteksi perangkat (Desktop/Mobile), OS, Browser via `Jenssegers/Agent`, IP, aktivitas terakhir, dan aksi *"Cabut Sesi"* / *"Keluar Sesi Lainnya"*.

### Passkeys & WebAuthn (Biometric Login & Sudo)

- **Library**: `lbuchs/webauthn` (FIDO2 WebAuthn Server)
- **Table**: `webauthn_credentials` (menyimpan `user_id`, `name` nickname, `credential_id`, `public_key`, `sign_count`)
- **Feature**: User mendaftarkan Passkey di profile (dengan prompt custom nickname), serta login 1-klik tanpa password pada halaman login dan Sudo Mode.

### Delete Event Scoping (Anti Race Condition)

```
JS dispatch: confirmDeleteAction.{id}         <- scoped per ID
PHP listen:  #[On('confirmDeleteAction.{id}')] <- hanya instance yg cocok
```


---

## 9. RBAC -- Roles & Permissions

Menggunakan **Spatie Permission** dengan pola:

### Level Authorization

| Level | Cara | Kapan |
|---|---|---|
| Route | `->middleware('permission:users-list')` | Guard per URL -- WAJIB |
| Component | `$this->authorize('update', $model)` | Jika butuh policy scope |
| Blade | `@can('create-user')` | Sembunyikan UI element |
| Config | `'guard' => ['can', 'permission']` | Sidebar navigation |

### Naming Convention Permission

Format: `{entity}-{action}`
```
users-list, users-create, users-edit
roles-list, roles-create, roles-edit
permissions-list, permissions-create, permissions-edit
announcement-list, announcement-create, announcement-edit
settings-manage
log-list
```

### Sidebar Navigation (Config-Driven)

```php
// config/navigation.php
[
    'label' => 'Users',
    'route' => 'users.index',
    'icon'  => 'user',
    'guard' => ['can', 'users-list'],  // Tersembunyi jika tidak punya permission
],
```

---

## 10. Event & Real-time System

```
Announcement Create -> Event::dispatch(new NewAnnouncementEvent($userId, $announcement))
                                    |
                             Broadcast via Pusher
                                    |
                    Private Channel: announcements.{user_id}
                                    |
                      Browser Echo listener -> Toast notification
                      Browser Push Notification (ServiceWorker)
```

### Events yang Ada

| Event | Channel | Kegunaan |
|---|---|---|
| `NewAnnouncementEvent` | `private:announcements.{userId}` | Real-time notif pengumuman |
| `TableRefreshed` | (dalam app) | Refresh PowerGrid setelah aksi |

---

## 11. Activity Logging

### LogUserActions Middleware

Mencatat **setiap request HTTP** yang valid ke tabel `log_histories`:

```
Request masuk -> auth check -> shouldLog() check
                                    |
              +-------------------- +
              | Diabaikan:          | Dicatat:
              | - livewire/*        | GET  -> 'list'/'form_create'
              | - telescope/*       | POST -> 'create'
              | - horizon/*         | PUT  -> 'update'
              | - ping              | DEL  -> 'delete'
              +---------------------+
                    |
             DB: log_histories
             + laravel.log (secondary)
```

### Format Log Entry

```
entity    = route name prefix  (e.g., "users")
action    = method-mapped verb (e.g., "form_edit")
ip_info   = multi-source IP dengan X-Forwarded-For
```

---

## 12. Sistem Setting

Centralized site configuration tersimpan di DB dengan **layered cache**:

```php
// Read (24 jam cache Redis/file):
Setting::get('site_name', 'Default Name');
Setting::getAllGrouped();

// Write (invalidate cache otomatis):
Setting::set('site_name', $value, 'branding');

// Manual cache clear:
Setting::clearCache();
```

### Group Setting yang Ada

| Group | Keys |
|---|---|
| `branding` | site_name, site_title, sidebar_title, logo_path, favicon_path |
| `seo` | meta_description, meta_keywords, meta_author |
| `footer` | footer_company, footer_url, footer_copyright |
| `contact` | contact_email, whatsapp_number, office_address |
| `media` | logo_path, favicon_path, apple_touch_icon_path |
| `integration` | google_analytics_id |

---

## 13. Frontend Architecture

### Request Lifecycle (SPA)

```
Klik link (wire:navigate)
    v
Livewire: partial page swap (hanya body berubah, scripts tidak re-run)
    v
Event: livewire:navigated
    v
Re-initialize: Flatpickr, TomSelect, dsb.
```

> **ATURAN KRITIS**: Semua inisialisasi JS yang perlu hidup setelah SPA navigasi WAJIB didaftarkan di event `livewire:navigated`, bukan hanya `DOMContentLoaded`.

### Global Event System (JS <-> Livewire)

```js
// eventListener.js -- event yang dikelola JS global:

Livewire.on("confirmDelete", (data) => {
    // Tampilkan SweetAlert -> dispatch confirmDeleteAction.{id}
})

Livewire.on("swal", (data) => {
    // Tampilkan SweetAlert notification
})

Livewire.on("loadingProgress", (data) => {
    // Tampilkan loading overlay (upload progress)
})

Livewire.on("loadingClose", () => {
    // Tutup loading overlay
})
```

### JS Library Rules

| Library | Cara Load | Catatan |
|---|---|---|
| Alpine.js | npm + import di app.js | Wajib |
| SweetAlert2 | npm + window.Swal | Global |
| Flatpickr | npm + import | Date picker |
| Quill | npm + import | Rich text |
| Tom Select | **CDN** saja | Khusus ini CDN |

> **ATURAN**: Jangan CDN untuk library utama. Kecuali Tom Select yang sudah CDN by design.

---

## 14. Styling System

### Token Custom Tailwind

```
primary        = red-600       (brand RazorAPI -- HANYA dekorasi/logo)
dark-primary   = #18181b       (dark mode page background)
dark-secondary = #242427       (dark mode elevated background)
glass-light / glass-dark               (glassmorphism bg)
glass-border-light / glass-border-dark (glassmorphism border)
```

### DynamicBg Pattern (WAJIB untuk semua card/container)

```html
<div :class="dynamicBg
    ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm'
    : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
     class="rounded-xl border p-4 transition-colors">
```

### Gap Limit

- **Antar section besar**: maksimal `gap-4` atau `space-y-4`
- **Elemen kecil internal**: `gap-1.5`, `gap-2`, `gap-3` sesuai kebutuhan

### Warna -- Aturan Penggunaan

| Warna | Kapan | JANGAN |
|---|---|---|
| **Red** (primary) | Brand badge, logo highlight | Tombol save, submit, confirm |
| **Blue** (`blue-600`) | Primary actions (Save, Submit, Create) | |
| **Emerald** (`emerald-600`) | Success, Approve | |
| **Red** (`red-600`) | Danger, Delete, Destructive | |
| **Amber** (`amber-500`) | Warning, Hold | |

### Z-Index Hierarchy

```
z-[200] -> Notifications/Toast
z-[150] -> Mobile drawer
z-[100] -> Modals & Dialogs
z-50    -> Dropdowns, Popovers
z-40    -> Sticky Navbar
z-0-10  -> Base content
```

### Concentric Radius Rule

```
Outer container/modal -> rounded-xl atau rounded-2xl
Inner container       -> rounded-lg
Buttons/inputs        -> rounded-lg atau rounded-md
Badges/pills/avatars  -> rounded-full
```

---

## 15. Panduan: Do & Don't

### DO -- Selalu Lakukan

**Arsitektur:**
- Gunakan `Livewire full-page component` untuk SEMUA halaman dashboard (bukan HTTP Controller)
- Gunakan `HandlesErrors` trait di setiap Handler
- Bungkus operasi DB multi-langkah dengan `DB::transaction()`
- Scope semua Livewire delete event dengan `#[On('event.{id}')]`
- Tambah `middleware('permission:xxx')` di setiap route yang butuh otorisasi
- Gunakan `Route Model Binding` di `mount()` parameter
- Buat Enum untuk semua "magic value" (status int, type string, dsb.)

**Kode:**
- Deklarasikan explicit return type untuk semua public method
- Gunakan typed properties (`public ?string $name = null`)
- Gunakan `Password::min(8)->letters()->mixedCase()->numbers()` untuk password
- Tambah `->limit(200)` untuk query yang load data ke select/dropdown
- Cache data semi-statis dengan `cache()->remember()`
- Selalu `$this->validate()` sebelum operasi DB di `save()`

**Frontend:**
- Pakai `wire:navigate` untuk semua link internal
- Re-initialize JS di event `livewire:navigated`
- Gunakan existing Blade component sebelum membuat baru
- Gunakan `dynamicBg` pattern untuk semua card/container
- Dark mode variant (`dark:*`) di setiap class warna

**Testing:**
- Tulis Pest feature test untuk setiap fitur baru
- Run `vendor/bin/pint --dirty` sebelum commit

---

### DON'T -- Jangan Pernah

**Arsitektur:**
- JANGAN buat HTTP Controller biasa untuk halaman dashboard
- JANGAN store request-specific state di static property (Octane leak)
- JANGAN buat folder baru di `app/` atau `resources/` tanpa diskusi
- JANGAN install dependency baru tanpa persetujuan

**Error Handling:**
- JANGAN try-catch di Livewire Form Object -- biarkan exception naik ke `runSafely`
- JANGAN catch exception tanpa re-throw di form/service class
- JANGAN tampilkan stack trace atau detail teknis ke user

**Delete Pattern:**
- JANGAN listen `#[On('confirmDeleteAction')]` tanpa scope ID -- race condition!
- JANGAN dispatch global `confirmDeleteAction` dari JS -- selalu scope dengan `.{id}`

**Frontend:**
- JANGAN CDN untuk library utama (kecuali Tom Select yang by design)
- JANGAN blok JS inline panjang di Blade view
- JANGAN gunakan jQuery atau $.ajax
- JANGAN buat layout baru untuk satu halaman
- JANGAN aktifkan `WithExport` di PowerGrid -- dinonaktifkan permanen
- JANGAN gunakan Red untuk tombol primary action -- gunakan Blue

**DB/Performance:**
- JANGAN load semua data tanpa limit di dropdown/select -- `->limit(200)`
- JANGAN 17+ query sequential tanpa transaction
- JANGAN update DB di hook `updated()` Livewire -- gunakan `updatedFieldName()` atau `.blur`

**Komponen:**
- JANGAN Blade component tanpa `@props()`
- JANGAN komponen tanpa `$attributes->merge()`
- JANGAN komponen tanpa Goal comment di baris pertama

---

## 16. Behavior Rules per Layer

### Model Layer

```php
// DO
class Announcement extends Model
{
    // Goal comment wajib (jika non-trivial)
    // Factory & seeder wajib
    // Casts untuk type safety
    protected $casts = ['target_roles' => 'array'];
    
    // Query scope untuk filter yang reusable
    public function scopeUnreadForUser($query, ?User $user): Builder { ... }
    
    // Static method untuk business query
    public static function hasUnreadForUser(?User $user): bool { ... }
    
    // Gunakan once() untuk expensive computation dalam satu request
    return once(fn () => self::unreadForUser($user)->exists());
}
```

### Livewire Form Object Layer

```php
// DO -- Form objects hanya validasi + DB call sederhana
class Post extends Form
{
    #[Validate(['name.*' => 'required|min:5|max:32'])]
    public array $name = [0 => null];

    public function store(): void
    {
        // Tidak perlu try-catch. Exception naik ke HandlesErrors.
        foreach ($this->name as $name) {
            Permission::create(['name' => $name]);
        }
    }
}
```

### Handler Layer

```php
// DO -- Handler orchestrate, tidak manipulasi data langsung
public function save(): void
{
    $this->validate();              // 1. Validasi dulu
    
    $this->runSafely(function () { // 2. Semua di dalam runSafely
        DB::transaction(function () { // 3. Transaction untuk multi-query
            $model = Model::create([...]);
            $model->syncRelation([...]);
        });
        
        $this->dispatch('swal', ...); // 4. Feedback
        $this->redirect(..., navigate: true); // 5. Redirect SPA
    }, 'Log message', ['context' => 'array']); // 6. Log context
}
```

### PowerGrid Table Layer

```php
// DO
final class UserTable extends PowerGridComponent
{
    public bool $deferLoading = true;          // Lazy load wajib
    public string $tableName = 'UserTable';   // Unique, PascalCase
    
    public function datasource(): Builder
    {
        return Model::query()->with(['relation']); // Eager load relasi
    }
    
    // Jangan load data berat di fields() -- hanya format
    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('status_formatted', fn ($r) => view('component', [...])->render());
    }
    
    // JANGAN: Jangan aktifkan WithExport
}
```

---

## 17. Panduan Fork Project Ini

Jika Anda mem-fork project ini untuk membangun sistem lain (misal: sistem inventori, CRM), berikut panduan agar tidak kehilangan arah:

### Checklist Fork Awal

```
[ ] Rename namespace App\ jika perlu
[ ] Update config/navigation.php -- hapus menu yang tidak relevan
[ ] Update config/app.php -- nama aplikasi, locale
[ ] Sesuaikan seeder (roles/permissions) dengan domain baru
[ ] Hapus Handler/ yang tidak diperlukan
[ ] PERTAHANKAN: HandlesErrors, ErrorLogger, BusinessException
[ ] PERTAHANKAN: Setting model + cache pattern
[ ] PERTAHANKAN: LogUserActions middleware
[ ] PERTAHANKAN: Scoped delete events di JS + PHP
[ ] Update SKILL.md jika Anda pakai AI agent
```

### Pola yang HARUS Dipertahankan

1. **HandlesErrors + runSafely** -- Ini fondasi UX consistency
2. **Permission middleware per route** -- Security by default
3. **Livewire Handler pattern** -- Konsistensi arsitektur
4. **DB::transaction di multi-query** -- Data integrity
5. **Scoped delete events** -- Anti race condition
6. **Setting model dengan cache** -- Performance + flexibility
7. **LogUserActions middleware** -- Audit trail

### Panduan Tambah Domain Baru

```bash
# 1. Buat Livewire handlers
php artisan make:livewire Handler/Inventori/Index --no-interaction
php artisan make:livewire Handler/Inventori/Create --no-interaction
php artisan make:livewire Handler/Inventori/Edit --no-interaction
php artisan make:livewire Handler/Inventori/Delete --no-interaction

# 2. Buat PowerGrid table
php artisan make:livewire PowergridTables/InventoriTable --no-interaction

# 3. Buat model + factory + migration
php artisan make:model Inventori -mf --no-interaction

# 4. Tambahkan ke PermissionSeeder: inventori-list, inventori-create, inventori-edit

# 5. Daftarkan route (ikuti pattern)
Route::livewire('inventori', Handler\Inventori\Index::class)
    ->name('inventori.index')
    ->middleware('permission:inventori-list');

# 6. Tambah sidebar di config/navigation.php
['label' => 'Inventori', 'route' => 'inventori.index', 'icon' => 'cube', 
 'guard' => ['can', 'inventori-list']]

# 7. Tulis test
php artisan make:test --pest InventoriTest
```

### Struktur Test Minimal per Domain

```php
it('menampilkan halaman index dengan permission', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('inventori-list');
    
    Livewire::actingAs($user)
        ->test(Handler\Inventori\Index::class)
        ->assertStatus(200);
});

it('admin dapat membuat inventori baru', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('inventori-create');
    
    Livewire::actingAs($user)
        ->test(Handler\Inventori\Create::class)
        ->set('name', 'Barang Baru')
        ->call('save')
        ->assertDispatched('swal');
        
    expect(Inventori::where('name', 'Barang Baru')->exists())->toBeTrue();
});
```

---

## Diagram Ringkas: Kapan Pakai Apa

```
Butuh halaman baru?
  -> app/Livewire/Handler/{Domain}/{Action}.php
  -> resources/views/livewire/handler/{domain}/{action}.blade.php

Butuh tabel data?
  -> app/Livewire/PowergridTables/{Domain}Table.php

Butuh komponen reusable kecil (widget)?
  -> app/Livewire/Utils/{NamaWidget}.php

Butuh form validation yang dipakai >1 handler?
  -> app/Livewire/Forms/{Domain}/Post.php

Butuh logic berulang di banyak Livewire?
  -> app/Livewire/Concerns/{NamaTrait}.php

Butuh komponen UI reusable (button, input, card)?
  -> resources/views/components/{kategori}/{nama}.blade.php

Butuh status/tipe tidak berubah?
  -> app/Enums/{NamaEnum}.php

Butuh exception dengan pesan ke user?
  -> throw new BusinessException('Pesan yang ramah');
```

---

*Dokumen ini dibuat berdasarkan analisis codebase aktual per Juli 2026.*
*Update dokumen ini setiap kali ada perubahan arsitektural yang signifikan.*
