# 📦 Laravel Notification Service

<p align="center">
  <strong>یک پکیج ساده، تمیز و انعطاف‌پذیر برای ارسال نوتیفیکیشن (ایمیل و پیامک) در پروژه‌های Laravel</strong>
</p>

<p align="center">
  <a href="https://packagist.org/packages/bitarun/laravel-notification-service">
    <img src="https://img.shields.io/packagist/v/bitarun/laravel-notification-service.svg?style=flat-square" alt="Latest Version">
  </a>
  <a href="https://packagist.org/packages/bitarun/laravel-notification-service">
    <img src="https://img.shields.io/packagist/dt/bitarun/laravel-notification-service.svg?style=flat-square" alt="Total Downloads">
  </a>
  <img src="https://img.shields.io/badge/PHP-%3E%3D8.1-777bb4?style=flat-square" alt="PHP Version">
  <img src="https://img.shields.io/badge/Laravel-11%20%7C%2012-ff2d20?style=flat-square" alt="Laravel Version">
  <img src="https://img.shields.io/badge/license-MIT-brightgreen?style=flat-square" alt="License">
</p>

---

## ✨ ویژگی‌ها

- 📧 ارسال **ایمیل** با استفاده از سیستم `Mailable` خود لاراول
- 📱 ارسال **پیامک** از طریق وب‌سرویس SMS
- 🚀 پشتیبانی کامل از **صف (Queue)** — به‌صورت اختیاری و قابل تنظیم
- 🎯 پشتیبانی از ورودی ساده (رشته) یا DTO کامل برای گیرنده
- 🛡️ مدیریت خطای یکپارچه با Exception اختصاصی
- ⚡ رابط کاربری ساده از طریق Facade

---

## 📥 نصب

از طریق Composer نصب کنید:

```bash
composer require bitarun/laravel-notification-service
```

پکیج به‌صورت خودکار (Auto-Discovery) توسط لاراول شناسایی می‌شود و نیازی به ثبت دستی Service Provider نیست.

### انتشار فایل تنظیمات

```bash
php artisan vendor:publish --tag=notification-config
```

این دستور فایل `config/notification.php` را در پروژهٔ شما ایجاد می‌کند.

---

## ⚙️ تنظیمات

پس از انتشار فایل کانفیگ، مقادیر زیر را در فایل `.env` پروژه‌تان تنظیم کنید:

```env
# صف
NOTIFICATION_QUEUE_ENABLED=false
NOTIFICATION_QUEUE_CONNECTION=database
NOTIFICATION_QUEUE_NAME=default

# پیامک
SMS_API_KEY=your-api-key
SMS_URL=https://api.sms.ir/v1/send/verify
SMS_TEMPLATE_ID=your-template-id
```

| کلید | توضیح | پیش‌فرض |
|---|---|---|
| `NOTIFICATION_QUEUE_ENABLED` | آیا نوتیفیکیشن‌ها به‌صورت پیش‌فرض وارد صف شوند؟ | `false` |
| `NOTIFICATION_QUEUE_CONNECTION` | کانکشن صف مورد استفاده | کانکشن پیش‌فرض لاراول |
| `NOTIFICATION_QUEUE_NAME` | نام صف مورد استفاده | `default` |
| `SMS_API_KEY` | کلید API سرویس پیامکی | — |
| `SMS_URL` | آدرس endpoint سرویس پیامکی | `https://api.sms.ir/v1/send/verify` |
| `SMS_TEMPLATE_ID` | شناسهٔ قالب پیامک | — |

---

## 🚀 شروع سریع

### 📧 ارسال ایمیل

```php
use Bitarun\LaravelNotificationService\Facades\Notification;

Notification::sendEmail($user->email, new \App\Mail\WelcomeMail);
```

### 📱 ارسال پیامک

```php
use Bitarun\LaravelNotificationService\Facades\Notification;

Notification::sendSms($user->phone_number, 'کد تایید شما: ۱۲۳۴۵');
```

همین! به همین سادگی 🎉

---

## 🎯 استفاده از DTO گیرنده (اختیاری)

اگر نیاز به اطلاعات بیشتری از گیرنده دارید (مثلاً نام، برای استفاده در قالب ایمیل)، می‌توانید از `NotificationRecipient` استفاده کنید:

```php
use Bitarun\LaravelNotificationService\DTOs\NotificationRecipient;
use Bitarun\LaravelNotificationService\Facades\Notification;

$recipient = new NotificationRecipient(
    email: $user->email,
    name: $user->name
);

Notification::sendEmail($recipient, new \App\Mail\WelcomeMail);
```

> 💡 هر دو روش (رشتهٔ ساده یا DTO کامل) به‌طور یکسان پشتیبانی می‌شوند و می‌توانید بر اساس نیاز خود انتخاب کنید.

---

## 🚦 مدیریت صف (Queue)

به‌صورت پیش‌فرض، رفتار صف بر اساس مقدار `NOTIFICATION_QUEUE_ENABLED` در فایل `.env` تعیین می‌شود. اما می‌توانید این رفتار را به‌صورت مستقیم و per-call نیز override کنید:

### اجبار به ارسال از طریق صف

```php
Notification::queue()->sendEmail($user->email, new \App\Mail\WelcomeMail);
```

### اجبار به ارسال فوری (بدون صف)

```php
Notification::now()->sendSms($user->phone_number, 'این پیام فوری ارسال می‌شود');
```

### رفتار پیش‌فرض (طبق تنظیمات .env)

```php
Notification::sendEmail($user->email, new \App\Mail\WelcomeMail);
```

---

## 🛡️ مدیریت خطا

در صورت بروز خطا در ارسال (مثلاً fail شدن درخواست به سرویس پیامکی)، یک Exception اختصاصی پرتاب می‌شود که می‌توانید آن را مدیریت کنید:

```php
use Bitarun\LaravelNotificationService\Exceptions\SmsSendingFailedException;

try {
    Notification::sendSms($user->phone_number, 'کد تایید: ۱۲۳۴۵');
} catch (SmsSendingFailedException $e) {
    logger()->error('ارسال پیامک ناموفق بود: ' . $e->getMessage());
}
```

> ⚙️ در صورت استفاده از صف، این خطاها به‌صورت خودکار توسط مکانیزم `failed_jobs` لاراول مدیریت می‌شوند.

---

## 📋 پیش‌نیازها

| نیازمندی | نسخه |
|---|---|
| PHP | 8.1 یا بالاتر |
| Laravel | 11.x, 12.x یا بالاتر |

---

## 🤝 مشارکت

پیشنهادات، گزارش باگ و Pull Request‌ها با آغوش باز پذیرفته می‌شوند! لطفاً پیش از ارسال تغییرات بزرگ، یک Issue باز کنید تا در موردش گفتگو کنیم.

---

## 📄 لایسنس

این پکیج تحت [لایسنس MIT](LICENSE) منتشر شده است.

---

<p align="center">
  ساخته‌شده با ❤️ توسط <a href="https://github.com/bitarun">Bitarun</a>
</p>
