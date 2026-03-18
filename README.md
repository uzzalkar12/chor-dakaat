# 🎮 চোর-ডাকাত-পুলিশ-বাবু — Laravel Multiplayer Game

বাংলাদেশের জনপ্রিয় কার্ড গেম — **Laravel 11 + Reverb WebSocket** দিয়ে তৈরি ৪ জনের রিয়েলটাইম অনলাইন গেম।

---

## 📁 প্রজেক্ট স্ট্রাকচার

```
chor-dakaat-game/
├── app/
│   ├── Events/
│   │   ├── GameFinished.php         # গেম শেষ event
│   │   ├── GameRoomUpdated.php      # রুম আপডেট event
│   │   ├── PoliceGuessing.php       # পুলিশ অনুমান event
│   │   ├── RoundCompleted.php       # রাউন্ড শেষ event
│   │   └── RoundStarted.php         # রাউন্ড শুরু event
│   ├── Http/Controllers/
│   │   ├── AuthController.php       # লগইন/রেজিস্ট্রেশন
│   │   └── GameController.php       # গেম কন্ট্রোলার
│   ├── Models/
│   │   ├── GameRoom.php             # গেম রুম মডেল
│   │   ├── GameRoomPlayer.php       # রুম খেলোয়াড় মডেল
│   │   ├── GameRound.php            # গেম রাউন্ড মডেল
│   │   ├── RoundPlayerAssignment.php# রাউন্ড রোল মডেল
│   │   └── User.php                 # ব্যবহারকারী মডেল
│   └── Services/
│       └── GameService.php          # গেম লজিক সার্ভিস
├── database/migrations/             # সব মাইগ্রেশন
├── resources/views/
│   ├── auth/ (login, register)
│   ├── game/
│   │   ├── lobby.blade.php          # লবি পেজ
│   │   ├── room.blade.php           # গেম রুম (মেইন UI)
│   │   └── partials/
│   │       ├── waiting.blade.php    # অপেক্ষার স্ক্রিন
│   │       ├── playing.blade.php    # খেলার স্ক্রিন
│   │       └── finished.blade.php   # শেষ স্কোর
│   └── layouts/app.blade.php        # মেইন লেআউট
├── routes/
│   ├── web.php                      # ওয়েব রাউট
│   └── channels.php                 # Reverb চ্যানেল অথ
└── config/reverb.php                # Reverb কনফিগ
```

---

## ⚡ ইনস্টলেশন গাইড

### ধাপ ১: নতুন Laravel প্রজেক্ট তৈরি করুন

```bash
composer create-project laravel/laravel chor-dakaat-game
cd chor-dakaat-game
```

### ধাপ ২: Reverb ইনস্টল করুন

```bash
composer require laravel/reverb
php artisan reverb:install
```

### ধাপ ৩: প্রজেক্ট ফাইলগুলো কপি করুন

এই প্যাকেজের সব ফাইল আপনার Laravel প্রজেক্টে কপি করুন।

### ধাপ ৪: `.env` কনফিগার করুন

```bash
cp .env.example .env
php artisan key:generate
```

`.env` এ ডেটাবেস সেটআপ করুন:
```env
DB_DATABASE=chor_dakaat_game
DB_USERNAME=root
DB_PASSWORD=your_password

BROADCAST_CONNECTION=reverb
REVERB_APP_ID=chor-dakaat-app
REVERB_APP_KEY=chor-dakaat-key
REVERB_APP_SECRET=chor-dakaat-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
```

### ধাপ ৫: ডেটাবেস তৈরি করুন

```bash
# MySQL-এ ডেটাবেস তৈরি করুন
mysql -u root -p -e "CREATE DATABASE chor_dakaat_game CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# মাইগ্রেশন চালান
php artisan migrate

# ডেমো ইউজার তৈরি করুন (ঐচ্ছিক)
php artisan db:seed
```

### ধাপ ৬: `config/broadcasting.php` আপডেট করুন

```php
'default' => env('BROADCAST_CONNECTION', 'reverb'),
```

### ধাপ ৭: সার্ভার চালু করুন

**৩টি টার্মিনাল উইন্ডো খুলুন:**

```bash
# টার্মিনাল ১: Laravel ওয়েব সার্ভার
php artisan serve

# টার্মিনাল ২: Reverb WebSocket সার্ভার
php artisan reverb:start

# টার্মিনাল ৩: Queue Worker (event broadcasting-এর জন্য)
php artisan queue:work
```

### ধাপ ৮: ব্রাউজারে খুলুন

```
http://localhost:8000
```

---

## 🎯 খেলার নিয়ম

| রোল | পয়েন্ট | বিবরণ |
|-----|---------|-------|
| 🦹 চোর | ৪০ | পুলিশ থেকে লুকিয়ে থাকুন |
| 💀 ডাকাত | ৬০ | পুলিশ থেকে লুকিয়ে থাকুন |
| 👮 পুলিশ | ৮০ | সঠিক ব্যক্তি ধরুন |
| 🎩 বাবু | ১০০ | সর্বদা পাওয়া যায় |

- **বিজোড় রাউন্ড** (১, ৩, ৫...): পুলিশ **চোর** খোঁজে
- **জোড় রাউন্ড** (২, ৪, ৬...): পুলিশ **ডাকাত** খোঁজে
- পুলিশ **সঠিক** ধরলে: পুলিশ ৮০, ধরা ব্যক্তি ০
- পুলিশ **ভুল** করলে: পুলিশ ০ পয়েন্ট

---

## 🔄 রিয়েলটাইম ইভেন্ট ফ্লো

```
হোস্ট রুম তৈরি করে
    ↓
৩ জন রুম কোড দিয়ে জয়েন করে  [GameRoomUpdated broadcast]
    ↓
হোস্ট "গেম শুরু" বাটন ক্লিক করে
    ↓
রোল র‍্যান্ডমলি বিতরণ হয়      [RoundStarted broadcast]
    ↓
পুলিশ অনুমান করে              [RoundCompleted broadcast]
    ↓
পরবর্তী রাউন্ড ...
    ↓
সব রাউন্ড শেষে               [GameFinished broadcast]
```

---

## 🛠️ টেকনোলজি স্ট্যাক

- **Backend**: Laravel 11, PHP 8.2+
- **Realtime**: Laravel Reverb (WebSocket)
- **Database**: MySQL (SQLite-ও কাজ করবে)
- **Frontend**: Vanilla JS + Laravel Echo + Pusher.js client
- **CSS**: Custom design, Bengali fonts (Hind Siliguri, Tiro Bangla)

---

## 📝 ডেমো অ্যাকাউন্ট (seeder চালানোর পর)

| নাম | ইমেইল | পাসওয়ার্ড |
|-----|--------|-----------|
| রাহিম | rahim@test.com | password |
| করিম | karim@test.com | password |
| সুমাইয়া | sumaiya@test.com | password |
| তানিয়া | tania@test.com | password |

# নতুন Laravel প্রজেক্টে কপি করুন, তারপর:
- composer require laravel/reverb
- php artisan reverb:install
- php artisan migrate
- php artisan db:seed

# ৩টি টার্মিনালে চালান:
- php artisan serve
- php artisan reverb:start
- php artisan queue:work


- ngrok start --all
