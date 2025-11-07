# Stripe Payment API (Laravel 12)

مشروع Laravel 12 API كامل لمعالجة المدفوعات باستخدام **Stripe Checkout** وجلسات الدفع، مع **Webhook** لتسجيل المعاملات في قاعدة البيانات.

---

## المتطلبات

- PHP >= 8.1
- Composer
- Laravel 12
- قاعدة بيانات (MySQL أو SQLite)
- Stripe account (test mode)

---

## خطوات الإعداد

1. **نسخ المشروع**
```
git clone https://github.com/MaiOsamaALMoqayad/Stripe-api.git
cd Stripe-api

## تثبيت الحزم

composer install
npm install
npm run dev
نسخ .env.example إلى .env


cp .env.example .env
1. ** تعديل .env حسب بيئتك **

env
APP_NAME=StripeApp
APP_URL=http://localhost

# قاعدة البيانات
DB_CONNECTION=sqlite
# أو إذا تستخدم MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

# مفاتيح Stripe
STRIPE_KEY=pk_test_****************
STRIPE_SECRET=sk_test_****************
STRIPE_WEBHOOK_SECRET=whsec_****************

## تشغيل الميجريشن لإنشاء الجداول
php artisan migrate

## تشغيل السيرفر المحلي
php artisan serve

## نقاط مهمة
تسجيل المستخدمين وتسجيل الدخول: API جاهزة لتسجيل المستخدمين واسترجاع token لكل مستخدم.
إنشاء جلسة دفع Stripe: Endpoint POST /api/create-checkout-session ينشئ جلسة دفع ويرجع URL لتوجيه المستخدم لصفحة الدفع.
Webhook Stripe: Endpoint POST /api/stripe/webhook يستقبل الأحداث مثل checkout.session.completed ويخزن بيانات الدفع في جدول payments.
تسجيل المدفوعات: كل عملية دفع ناجحة تحفظ في قاعدة البيانات مع معلومات المستخدم، المبلغ، العملة، وحالة الدفع.

تشغيل Webhook محلياً
إذا تريد تجربة Webhook محلياً:


stripe listen --forward-to http://127.0.0.1:8000/api/stripe/webhook
تأكد من تثبيت Stripe CLI على جهازك: Stripe CLI Docs

## ملاحظات أمنية
لا ترفع .env على GitHub.
تأكد من استخدام مفاتيح Stripe الصحيحة في .env.

Endpoint Webhook يجب أن يكون محمي باستخدام STRIPE_WEBHOOK_SECRET.
