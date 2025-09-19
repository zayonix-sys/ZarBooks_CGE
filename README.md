# ZarBooks_CGE

**Comprehensive Accounting Software** built with **PHP 8.1.25** and **Laravel 9.0**, featuring robust financial management, reporting, and user-friendly interfaces.

---

## 🚀 Key Features

- 📚 **Chart of Accounts** – Organize and manage all accounts systematically.  
- 💳 **Transactions Management** – Record and track recent transactions.  
- 🧾 **Vouchers System**  
  - Debit Vouchers  
  - Credit Vouchers  
  - Journal Vouchers  
- 📒 **General Ledger** – Detailed view of all account activities.  
- 📊 **Trial Balance** – Maintain accurate financial checks and balances.  
- 📈 **Financial Reports**  
  - Income Statement  
  - Balance Sheet  

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.1.25, Laravel 9.0  
- **Database:** MySQL 
- **Frontend:** Blade, Bootstrap
- **Version Control:** Git & GitHub  

---

## 📂 Installation & Setup

Follow these steps to set up the project locally:

### 1. Clone the Repository
```bash
git clone https://github.com/zayonix-sys/ZarBooks_CGE.git
cd ZarBooks_CGE
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Build Frontend Assets
```bash
npm run dev
```
(For production use `npm run build`)

### 4. Copy Environment File
```bash
cp .env.example .env
```

### 5. Configure Environment
Open the `.env` file and set up your database credentials and other environment settings:
```env
APP_NAME="ZarBooks"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=zarbooks_db
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Generate Application Key
```bash
php artisan key:generate
```

### 7. Run Database Migrations & Seeders
```bash
php artisan migrate --seed
```

### 8. Link Storage (for file uploads, logs, etc.)
```bash
php artisan storage:link
```

### 9. Clear & Cache Configurations (optional but recommended)
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

### 10. Start the Development Server
```bash
php artisan serve
```

Your project should now be available at:  
👉 `http://localhost:8000`

---

## 📖 Usage

- Login with default seeded credentials:  
  - **Email:** `admin@example.com`  
  - **Password:** `password`  

- Navigate through:  
  - **Chart of Accounts** → Manage accounts  
  - **Transactions & Vouchers** → Record debit/credit/journal vouchers  
  - **Reports** → Generate trial balance, income statement, and balance sheet  

---

## 🤝 Contributing

Contributions are welcome!  
1. Fork the repository  
2. Create a feature branch (`git checkout -b feature/new-feature`)  
3. Commit changes (`git commit -m "Add new feature"`)  
4. Push to branch (`git push origin feature/new-feature`)  
5. Open a Pull Request  

---

## 📜 License

This project is licensed under the [MIT License](LICENSE).

---

### ⭐ If you find this project useful, please consider giving it a star on GitHub!
