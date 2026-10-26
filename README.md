# Mock-API (PHP + MySQL)

A PHP/MySQL mock e-commerce API and small documentation website. You may use, modify, and extend this source code for your own projects. **You must create and configure your own database**; this repository does not provide a hosted database or shared database credentials.

## Requirements

- PHP 8.0+ (PHP 8.2 recommended)
- MySQL 5.7+ or MariaDB with JSON column support
- Apache (XAMPP is suitable for local development)
- PHP `mysqli` extension enabled
- Composer, if you need to reinstall PHP dependencies

## 1. Install locally with XAMPP

1. Install and open XAMPP.
2. Start **Apache** and **MySQL**.
3. Copy the project folder into XAMPP's `htdocs` directory. For example:
   `C:\xampp\htdocs\fakestoreapi`
4. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
5. Select **Import**.
6. Choose this repository's `database/database.sql` file and run the import. The SQL creates a database named `fakestoreapi` and the required tables (`products`, `carts`, `users`, and `orders`).
7. Open `config/database.php`. The default local settings are:
   - Host: `localhost`
   - Username: `root`
   - Password: empty (the usual XAMPP default; use your own password if you set one)
   - Database: `fakestoreapi`
8. If your MySQL credentials differ, set the environment variables `DB_HOST`, `DB_USER`, `DB_PASSWORD`, and `DB_NAME`, or change the values in `config/database.php` for your local setup. Never commit production credentials to Git.
9. Visit `http://localhost/fakestoreapi/home` for the home page and `http://localhost/fakestoreapi/docs` for API documentation.

## 2. Create the database manually (alternative)

If you prefer the MySQL command line, open a terminal in the project directory and run:

..bash
mysql -u root -p < database/database.sql


If your local MySQL root account has no password, press Enter when prompted. If `mysql` is not available in your terminal, use phpMyAdmin's Import screen instead.

The SQL file creates the schema, not a production database server. Each user must import it into their own MySQL/MariaDB instance and configure the connection settings.

## 3. Install PHP dependencies

The project uses Composer for the PHP JWT package. If the `vendor/` directory is absent, run this from the project root:

bash
composer install


If Composer is not installed, install it from the official Composer website first. This project is served by Apache/PHP; `npm start` and `npm run dev` are not the commands used to run it.

## 4. Deploy to your own hosting

1. Create a MySQL database and database user in your hosting control panel.
2. Import `database/database.sql` into that database.
3. Configure `DB_HOST`, `DB_USER`, `DB_PASSWORD`, and `DB_NAME` for the hosting environment, or update `config/database.php` with your own private credentials.
4. Upload the PHP source and required Composer `vendor/` dependencies to your PHP web root.
5. Ensure the hosting account runs a compatible PHP version and has `mysqli` enabled.
6. Test the homepage and API endpoints, then verify database-backed operations such as product listing, login, cart, and checkout.
9. Keep secrets out of source control and use HTTPS in production.

The included GitHub Actions workflow expects FTP secrets to be configured in the repository settings. Review `.github/workflows/deploy.yml` and update its target directory to match your hosting setup before enabling deployment.

## 5. API routes

The repository includes PHP endpoints such as:

- `GET /api/products.php` — list products
- `GET /api/product.php` — product endpoint (check the documentation for its required query parameters)
- `GET /api/categories.php` — list categories
- `GET /api/category.php` — category endpoint
- `GET /api/users.php` — users endpoint
- `POST /api/register.php` — register a user
- `POST /api/login.php` — log in
- `GET/POST /api/cart.php` and `/api/carts.php` — cart operations
- `GET/POST /api/profile.php` — profile operations
- `POST /checkout.php` — checkout flow

Use `docs.php` as the source of truth for required parameters, request bodies, authentication, and response formats. Do not expose real user data or use this sample API as-is for a production commerce system without a security review.

## 6. Database tables

The included SQL defines these tables:

- `products`: product title, price, description, category, and image URL
- `users`: login credentials and basic profile/address fields
- `carts`: a user's cart products stored as JSON
- `orders`: a user's order products stored as JSON with a timestamp

The schema is a starting point. If you change column names or table structure, update the related PHP endpoints to match.

## 7. Troubleshooting

- **Database connection failed:** Confirm MySQL is running, the `fakestoreapi` database was imported, and the credentials in `config/database.php` are correct.
- **Unknown database:** Import `database/database.sql` or create the database and import the tables manually.
- **`mysqli` class not found:** Enable the `mysqli` extension in the PHP configuration used by Apache, then restart Apache.
- **JWT/dependency error:** Run `composer install` from the project root.
- **404 on local URLs:** Confirm the folder is inside `htdocs` and the URL matches the folder name.
- **API returns an error:** Check the PHP/Apache error logs and the endpoint's expected HTTP method, parameters, and authentication requirements in `docs.php`.

## Usage

You are free to reuse and adapt the code. You are responsible for creating, hosting, securing, and maintaining your own database and for configuring the application to connect to it.
