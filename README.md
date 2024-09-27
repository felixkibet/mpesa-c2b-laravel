# MPesa C2B API Integration with Laravel 11

This project demonstrates the integration of the MPesa C2B (Customer to Business) API with a Laravel 11 backend. It includes generating access tokens, registering validation and confirmation URLs, and simulating MPesa transactions.

The project uses Laravel for the backend, Bootstrap 5 for styling, and Axios for making HTTP requests to the backend.

## Features

- Generate MPesa access token
- Register validation and confirmation URLs
- Simulate C2B transactions
- Store transaction confirmations in a database

## Tech Stack

- **Backend**: Laravel 11, PHP 8.x
- **Frontend**: Blade, Bootstrap 5, Axios
- **Database**: MySQL (or any other supported database)
- **Others**: Vite, CSRF protection, Ngrok (for exposing local development URLs)

## Requirements

- PHP >= 8.x
- Composer
- Node.js & NPM (for Vite and Frontend assets)
- Ngrok (for exposing localhost URLs)

## Getting Started

Follow these steps to set up the project locally.

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/mpesa-c2b-laravel.git
cd mpesa-c2b-laravel
```

### 2. Install dependencies

#### Backend (Laravel):

```bash
composer install
```

#### Frontend:

```bash
npm install
npm run build
```

### 3. Configure Environment Variables

Create a `.env` file by copying the `.env.example` file:

```bash
cp .env.example .env
```

Update the following environment variables with your own credentials:

```env
MPESA_CONSUMER_KEY=your_consumer_key_here
MPESA_CONSUMER_SECRET=your_consumer_secret_here
MPESA_PASSKEY=your_passkey_here
MPESA_SHORTCODE=600982
MPESA_MSISDN=254708374149
MPESA_ENV=sandbox #change later to live
MPESA_TEST_URL=https://your_ngrok_url_here
MPESA_CONFIRMATION_URL="${MPESA_TEST_URL}/confirm"
MPESA_VALIDATION_URL="${MPESA_TEST_URL}/validate"
```

You will also need to update your **DB credentials** in the `.env` file:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 4. Run Migrations

Run the migrations to create the necessary database tables:

```bash
php artisan migrate
```

### 5. Expose Localhost with Ngrok

MPesa requires a publicly accessible URL for the callback and registration endpoints. You can use Ngrok to expose your local Laravel app.

Download and install [Ngrok](https://ngrok.com/), then run:

```bash
ngrok http 8000
```

Replace your `.env` callback URLs with the Ngrok URL:

```env
MPESA_TEST_URL=https://your-ngrok-url.ngrok.io/callback
```

### 6. Run the Laravel Application

Start the Laravel development server:

```bash
php artisan serve
```

### 7. Build Frontend Assets

Compile your frontend assets with Vite (you can also use `npm run dev` for hot reloading during development):

```bash
npm run build
```

### 8. Testing the Project

- Open your browser and visit `http://localhost:8000`
- Generate the token, register the URLs, and simulate a transaction

## API Endpoints

- **GET** `/generate-token` - Generate MPesa access token
- **POST** `/register-urls` - Register the validation and confirmation URLs with MPesa
- **POST** `/simulate` - Simulate an MPesa transaction
- **POST** `/validate` - Endpoint for MPesa validation (handled by Safaricom)
- **POST** `/confirm` - Endpoint for MPesa transaction confirmation (handled by Safaricom)

### Example API Request for Simulation

To simulate a transaction:

```bash
POST /register-urls
Content-Type: application/json
Authorization: Bearer {your_generated_token}

{
    "ShortCode": "600981",
    "ResponseType": "Cancelled",
    "ConfirmationURL": "https://7762-41-84-143-146.ngrok-free.app/confirmatio",
    "ValidationURL": "https://7762-41-84-143-146.ngrok-free.app/validation"
}
```

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Contributions

Feel free to contribute by submitting pull requests or opening issues. Any feedback or enhancements are highly welcome.

