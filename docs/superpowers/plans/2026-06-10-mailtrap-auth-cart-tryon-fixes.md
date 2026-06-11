# MailTrap + Auth Emails + Cart Fix + 3D Try-On Fix — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Configure MailTrap email delivery, add email templates for verification and password reset, fix the 3D try-on camera reset bug when switching designs, and fix the cart not removing items when quantity reaches 0.

**Architecture:** (1) All mail goes through MailTrap SMTP by updating `.env` and customising the two built-in Laravel/Fortify notifications. (2) The 3D camera bug is caused by Livewire re-executing the Three.js `<script>` on every design-switch re-render — wrapping the canvas+script in `wire:ignore` while keeping the hidden-input sync outside it fixes the issue. (3) The cart bug is caused by the decrement button using `$set` without calling `updateItem()`, so qty=0 never triggers removal — a dedicated `decrementQty()` Livewire method fixes it.

**Tech Stack:** Laravel 11, Livewire 3, Fortify, MailTrap (SMTP), Three.js (existing), PHP 8.2

---

## File Map

| Status | File | Change |
|--------|------|--------|
| Modify | `.env` | Set MailTrap SMTP credentials |
| Create | `resources/views/emails/verify-email.blade.php` | Email verification template |
| Create | `resources/views/emails/reset-password.blade.php` | Password reset template |
| Modify | `app/Models/User.php` | Override `sendEmailVerificationNotification()` and `sendPasswordResetNotification()` |
| Create | `app/Notifications/VerifyEmailNotification.php` | Custom verify-email mailable notification |
| Create | `app/Notifications/ResetPasswordNotification.php` | Custom password-reset mailable notification |
| Modify | `app/Livewire/Cart/CartPage.php` | Add `decrementQty()` method |
| Modify | `resources/views/livewire/cart/cart-page.blade.php` | Fix decrement button to call `decrementQty()` |
| Modify | `resources/views/livewire/virtual-try-on/virtual-try-on-page.blade.php` | Wrap canvas+script in `wire:ignore`, move hidden inputs outside |

---

## Task 1: Configure MailTrap SMTP in `.env`

**Files:**
- Modify: `.env`

- [ ] **Step 1: Update `.env` mail variables**

Replace the existing `MAIL_*` block with:

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=<your-mailtrap-username>
MAIL_PASSWORD=<your-mailtrap-password>
MAIL_FROM_ADDRESS="noreply@funshirt.pt"
MAIL_FROM_NAME="${APP_NAME}"
```

> Replace `<your-mailtrap-username>` and `<your-mailtrap-password>` with the credentials from your MailTrap inbox → SMTP Settings → Integrations → Laravel 9+.

- [ ] **Step 2: Clear config cache**

```bash
php artisan config:clear
```

Expected output: `Configuration cache cleared successfully.`

- [ ] **Step 3: Smoke-test mail delivery**

```bash
php artisan tinker --execute="Mail::raw('MailTrap test', fn(\$m) => \$m->to('test@example.com')->subject('Teste MailTrap'));"
```

Expected: no exception. Open MailTrap inbox and confirm the email appears.

- [ ] **Step 4: Commit**

```bash
git add .env
git commit -m "config: configure MailTrap SMTP for all outgoing emails"
```

---

## Task 2: Create Email Templates

**Files:**
- Create: `resources/views/emails/verify-email.blade.php`
- Create: `resources/views/emails/reset-password.blade.php`

- [ ] **Step 1: Create verification email template**

Create `resources/views/emails/verify-email.blade.php`:

```blade
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de Email — FunShirt</title>
</head>
<body style="margin:0;padding:0;background:#0d0d1a;font-family:'Segoe UI',Arial,sans-serif;">
    <div style="max-width:560px;margin:2rem auto;background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
        <!-- Header -->
        <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:2rem;text-align:center;">
            <h1 style="color:#ffffff;margin:0;font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">FunShirt</h1>
            <p style="color:rgba(255,255,255,.75);margin:0.35rem 0 0;font-size:0.9rem;">A tua loja de t-shirts personalizadas</p>
        </div>

        <!-- Body -->
        <div style="padding:2rem;">
            <h2 style="color:#e2e8f0;font-size:1.2rem;font-weight:600;margin:0 0 0.75rem;">Confirma o teu email</h2>
            <p style="color:#94a3b8;font-size:0.9rem;line-height:1.6;margin:0 0 1.5rem;">
                Olá <strong style="color:#e2e8f0;">{{ $name }}</strong>,<br><br>
                Obrigado por te registares na FunShirt! Clica no botão abaixo para confirmar o teu endereço de email e activar a tua conta.
            </p>

            <div style="text-align:center;margin:1.5rem 0;">
                <a href="{{ $url }}"
                   style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;border-radius:8px;padding:0.8rem 2rem;font-size:0.95rem;font-weight:600;letter-spacing:0.01em;">
                    Verificar email
                </a>
            </div>

            <p style="color:#64748b;font-size:0.8rem;line-height:1.5;margin:1.5rem 0 0;">
                Se não criaste uma conta, podes ignorar este email.<br>
                Este link expira em <strong>60 minutos</strong>.<br><br>
                Em alternativa, copia e cola este link no browser:<br>
                <a href="{{ $url }}" style="color:#a78bfa;word-break:break-all;">{{ $url }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div style="border-top:1px solid #1e1e30;padding:1rem 2rem;text-align:center;">
            <p style="color:#475569;font-size:0.75rem;margin:0;">© {{ date('Y') }} FunShirt. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
```

- [ ] **Step 2: Create password reset email template**

Create `resources/views/emails/reset-password.blade.php`:

```blade
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperação de Password — FunShirt</title>
</head>
<body style="margin:0;padding:0;background:#0d0d1a;font-family:'Segoe UI',Arial,sans-serif;">
    <div style="max-width:560px;margin:2rem auto;background:#111120;border:1px solid #1e1e30;border-radius:12px;overflow:hidden;">
        <!-- Header -->
        <div style="background:linear-gradient(135deg,#7c3aed,#6d28d9);padding:2rem;text-align:center;">
            <h1 style="color:#ffffff;margin:0;font-size:1.6rem;font-weight:700;letter-spacing:-0.02em;">FunShirt</h1>
            <p style="color:rgba(255,255,255,.75);margin:0.35rem 0 0;font-size:0.9rem;">A tua loja de t-shirts personalizadas</p>
        </div>

        <!-- Body -->
        <div style="padding:2rem;">
            <h2 style="color:#e2e8f0;font-size:1.2rem;font-weight:600;margin:0 0 0.75rem;">Recupera a tua password</h2>
            <p style="color:#94a3b8;font-size:0.9rem;line-height:1.6;margin:0 0 1.5rem;">
                Olá <strong style="color:#e2e8f0;">{{ $name }}</strong>,<br><br>
                Recebemos um pedido para redefinir a password da tua conta FunShirt. Clica no botão abaixo para criar uma nova password.
            </p>

            <div style="text-align:center;margin:1.5rem 0;">
                <a href="{{ $url }}"
                   style="display:inline-block;background:linear-gradient(135deg,#7c3aed,#6d28d9);color:#ffffff;text-decoration:none;border-radius:8px;padding:0.8rem 2rem;font-size:0.95rem;font-weight:600;letter-spacing:0.01em;">
                    Redefinir password
                </a>
            </div>

            <p style="color:#64748b;font-size:0.8rem;line-height:1.5;margin:1.5rem 0 0;">
                Este link expira em <strong>60 minutos</strong>. Após expirar, terás de solicitar um novo link.<br><br>
                Se não pediste a recuperação de password, podes ignorar este email. A tua password não será alterada.<br><br>
                Em alternativa, copia e cola este link no browser:<br>
                <a href="{{ $url }}" style="color:#a78bfa;word-break:break-all;">{{ $url }}</a>
            </p>
        </div>

        <!-- Footer -->
        <div style="border-top:1px solid #1e1e30;padding:1rem 2rem;text-align:center;">
            <p style="color:#475569;font-size:0.75rem;margin:0;">© {{ date('Y') }} FunShirt. Todos os direitos reservados.</p>
        </div>
    </div>
</body>
</html>
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/emails/verify-email.blade.php resources/views/emails/reset-password.blade.php
git commit -m "feat: add email templates for verification and password reset"
```

---

## Task 3: Custom Email Verification Notification

**Files:**
- Create: `app/Notifications/VerifyEmailNotification.php`
- Modify: `app/Models/User.php`

- [ ] **Step 1: Create custom notification class**

Create `app/Notifications/VerifyEmailNotification.php`:

```php
<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('FunShirt — Verifica o teu email')
            ->view('emails.verify-email', [
                'url'  => $url,
                'name' => $this->notifiable->name,
            ]);
    }
}
```

- [ ] **Step 2: Override `sendEmailVerificationNotification` in User model**

Open `app/Models/User.php`. Add the import and method:

```php
<?php

namespace App\Models;

use App\Notifications\VerifyEmailNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    protected $fillable = [
        'name', 'email', 'password', 'user_type', 'gender', 'blocked', 'photo_url', 'custom'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'blocked'           => 'boolean',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmailNotification());
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'id');
    }

    public function isAdmin(): bool    { return $this->user_type === 'A'; }
    public function isEmployee(): bool { return $this->user_type === 'F'; }
    public function isClient(): bool   { return $this->user_type === 'C'; }
    public function isBlocked(): bool  { return (bool) $this->blocked; }
}
```

- [ ] **Step 3: Test verification email**

```bash
php artisan tinker --execute="
\$user = App\Models\User::where('email_verified_at', null)->first();
if (\$user) { \$user->sendEmailVerificationNotification(); echo 'sent'; } else { echo 'no unverified user'; }
"
```

Expected: `sent` and the email appears in MailTrap inbox with the correct template.

- [ ] **Step 4: Commit**

```bash
git add app/Notifications/VerifyEmailNotification.php app/Models/User.php
git commit -m "feat: use custom email verification notification with MailTrap template"
```

---

## Task 4: Custom Password Reset Notification

**Files:**
- Create: `app/Notifications/ResetPasswordNotification.php`
- Modify: `app/Models/User.php` (add `sendPasswordResetNotification`)

- [ ] **Step 1: Create custom notification class**

Create `app/Notifications/ResetPasswordNotification.php`:

```php
<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('FunShirt — Recuperação de password')
            ->view('emails.reset-password', [
                'url'  => $url,
                'name' => $this->notifiable->name,
            ]);
    }
}
```

- [ ] **Step 2: Override `sendPasswordResetNotification` in User model**

In `app/Models/User.php`, add the import:

```php
use App\Notifications\ResetPasswordNotification;
```

Then add the method inside the class (after `sendEmailVerificationNotification`):

```php
public function sendPasswordResetNotification($token): void
{
    $this->notify(new ResetPasswordNotification($token));
}
```

The full User.php imports block should now be:

```php
use App\Notifications\VerifyEmailNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
```

- [ ] **Step 3: Test reset email**

```bash
php artisan tinker --execute="
\$user = App\Models\User::first();
\$token = app('auth.password.broker')->createToken(\$user);
\$user->sendPasswordResetNotification(\$token);
echo 'sent';
"
```

Expected: `sent` and the email appears in MailTrap with the correct template and a valid reset URL.

- [ ] **Step 4: Commit**

```bash
git add app/Notifications/ResetPasswordNotification.php app/Models/User.php
git commit -m "feat: use custom password reset notification with MailTrap template"
```

---

## Task 5: Fix Cart — Auto-Remove Item When Quantity Reaches 0

**Root cause:** The `−` button calls `$set('editQtys.N', ...)` (which changes the Livewire property) but does NOT call `updateItem()`. The `wire:change` on a `<button>` element does not fire on Livewire `$set`. So when qty reaches 0, `updateItem()` — which would call `CartService::remove()` — is never invoked.

**Files:**
- Modify: `app/Livewire/Cart/CartPage.php`
- Modify: `resources/views/livewire/cart/cart-page.blade.php`

- [ ] **Step 1: Add `decrementQty()` method to CartPage**

Open `app/Livewire/Cart/CartPage.php`. Add this method alongside `updateItem()`:

```php
public function decrementQty(int $index): void
{
    $current = (int) ($this->editQtys[$index] ?? 1);
    $next    = $current - 1;

    if ($next <= 0) {
        app(CartService::class)->remove($index);
        $this->dispatch('cart-updated');
        $this->reinitEdits();
        return;
    }

    $this->editQtys[$index] = $next;
    $this->updateItem($index);
}
```

- [ ] **Step 2: Update the `−` button in cart-page.blade.php**

Find the decrement button in `resources/views/livewire/cart/cart-page.blade.php`:

```html
<button wire:click="$set('editQtys.{{ $item['index'] }}', {{ max(0, ($editQtys[$item['index']] ?? 1) - 1) }})"
        wire:change="updateItem({{ $item['index'] }})"
        style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:4px;width:28px;height:28px;color:#94a3b8;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">−</button>
```

Replace it with:

```html
<button wire:click="decrementQty({{ $item['index'] }})"
        style="background:#1a1a2e;border:1px solid #1e1e30;border-radius:4px;width:28px;height:28px;color:#94a3b8;cursor:pointer;font-size:1rem;display:flex;align-items:center;justify-content:center;">−</button>
```

- [ ] **Step 3: Prevent negative quantities in the number input**

Also ensure manual text input to 0 removes the item. The `wire:change="updateItem({{ $item['index'] }})"` on the `<input type="number">` already calls `updateItem()`, and `CartService::update()` with `qty <= 0` already calls `remove()`. This path is already correct — no change needed.

- [ ] **Step 4: Run the app and verify**

```bash
php artisan serve
```

Open `/cart`, add an item (from `/catalog`), then click `−` repeatedly. When qty reaches 1 and you click `−` once more:
- The item should disappear immediately
- The total should update
- No page refresh needed

- [ ] **Step 5: Commit**

```bash
git add app/Livewire/Cart/CartPage.php resources/views/livewire/cart/cart-page.blade.php
git commit -m "fix: auto-remove cart item when quantity decremented to 0"
```

---

## Task 6: Fix 3D Try-On — Camera Resets When Switching Designs

**Root cause:** The Three.js initialisation `<script>` sits inside the Livewire component. Every time `selectDesign()` is called, Livewire re-renders the component and the browser re-executes the script, which resets `rotY`, `rotX`, `autoRotate`, and recreates the `renderer`/`camera`/`scene`. The fix is to wrap the canvas and script in `wire:ignore` (so Livewire skips that DOM subtree on re-renders) while keeping the two hidden `<input>` elements — which the polling interval reads — outside `wire:ignore` so they still receive updated values.

**Files:**
- Modify: `resources/views/livewire/virtual-try-on/virtual-try-on-page.blade.php`

- [ ] **Step 1: Locate the center canvas `<div>` and the closing `<script>` + hidden inputs**

The structure at the bottom of the file looks like:

```html
        {{-- CENTER: 3D Viewport --}}
        <div style="background:#0a0a0f;border:1px solid #1e1e30;border-radius:12px;position:relative;overflow:hidden;min-height:0;">
            ...canvas, reset button, drag hint...
        </div>

    </div>{{-- closes grid --}}

    {{-- Three.js --}}
    <script>
        (function () {
            ...all Three.js init code...
        })();
    </script>

    <input type="hidden" id="livewire-color" value="{{ $selectedColor }}">
    <input type="hidden" id="livewire-design" value="{{ $selectedImageId ?? '' }}">
</div>{{-- closes root component div --}}
```

- [ ] **Step 2: Wrap only the canvas div and the script in `wire:ignore`**

Replace the section from `{{-- Three.js --}}` through (but NOT including) the two hidden inputs so it reads:

```html
    {{-- Three.js – wire:ignore prevents Livewire from re-executing the script on re-render --}}
    <div wire:ignore>
        <script>
            (function () {
                ...all existing Three.js init code, unchanged...
            })();
        </script>
    </div>

    {{-- These inputs are READ by the Three.js polling interval above.            --}}
    {{-- They must be OUTSIDE wire:ignore so Livewire keeps them up-to-date.      --}}
    <input type="hidden" id="livewire-color" value="{{ $selectedColor }}">
    <input type="hidden" id="livewire-design" value="{{ $selectedImageId ?? '' }}">
</div>
```

Also wrap the center canvas `<div>` (which contains `<canvas id="tshirt-canvas">`) in `wire:ignore` so the canvas element itself is not replaced:

```html
        {{-- CENTER: 3D Viewport --}}
        <div wire:ignore style="background:#0a0a0f;border:1px solid #1e1e30;border-radius:12px;position:relative;overflow:hidden;min-height:0;">
            <div style="position:absolute;inset:0;background-image:...pointer-events:none;"></div>
            <canvas id="tshirt-canvas" style="width:100%;height:100%;display:block;cursor:grab;"></canvas>
            <div id="drag-hint" ...>↔ Arrasta para rodar</div>
            <button id="reset-btn" ...>⟳ Resetar</button>
        </div>
```

- [ ] **Step 3: Run the app and verify**

```bash
php artisan serve
```

Open `/try-on`. Click several designs in the left panel:

1. **Camera position preserved:** zoom level and camera angle should not reset between design switches.
2. **Rotation preserved:** if you manually rotated the shirt, the rotation state should persist after selecting a new design.
3. **Design updates:** the printed decal on the shirt should switch to the new design within ~200 ms (the polling interval picks up the updated hidden input).
4. **Reset button still works:** clicking ⟳ should still zero out rotation.
5. **Auto-rotate starts on load:** the shirt should auto-rotate when the page first opens.

- [ ] **Step 4: Commit**

```bash
git add resources/views/livewire/virtual-try-on/virtual-try-on-page.blade.php
git commit -m "fix: preserve 3D camera state when switching designs using wire:ignore"
```

---

## Self-Review Checklist

**Spec coverage:**
- [x] MailTrap SMTP — Task 1
- [x] Email verification template — Task 2 + Task 3
- [x] Password reset template — Task 2 + Task 4
- [x] "Esqueci-me da palavra-passe" link in login — already present in `login.blade.php` (`route('password.request')`)
- [x] Forgot-password page — already exists via Fortify (`forgot-password.blade.php`)
- [x] Reset-password page — already exists via Fortify (`reset-password.blade.php`)
- [x] Secure token (expiry, single-use) — handled by Fortify's built-in `password_reset_tokens` table
- [x] Verify-email page with success/invalid/expired feedback — Fortify's `verify-email.blade.php` already shows status; token validation is built-in (Laravel invalidates used/expired tokens automatically)
- [x] Resend verification email — already present in `verify-email.blade.php` (`route('verification.send')`)
- [x] Unverified users identified — `MustVerifyEmail` contract is already on the User model; `verified` middleware is already on the client route group
- [x] Cart qty=0 removes item — Task 5
- [x] Cart subtotal/total re-calculated — `reinitEdits()` + `cart-updated` event triggers re-render
- [x] No negative quantities — `min:0` validation already in `updateItem()`
- [x] 3D camera position correct on design switch — Task 6
- [x] 3D rotation preserved — Task 6
- [x] 3D model stays centred — `wire:ignore` on canvas preserves the already-running Three.js scene

**Placeholder scan:** None found.

**Type consistency:** `decrementQty(int $index)` in Task 5 step 1 matches `decrementQty({{ $item['index'] }})` in step 2. `$item['index']` is an `int` set in `CartService::enrichedItems()`.
