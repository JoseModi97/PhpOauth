# PHP OAuth Example

This project demonstrates authenticating users with Google OAuth2.

## Setup

1. Create a project in [Google Cloud Console](https://console.cloud.google.com/).
2. Enable the **Google+ API** and create OAuth 2.0 credentials of type `Web application`.
3. Set the authorized redirect URI to match the `GOOGLE_REDIRECT_URI` in `config.php`.
4. Copy the client ID and client secret into `config.php`.

Install dependencies via Composer:

```bash
composer install
```

## Running

Serve the project with PHP's built-in server or your web server:

```bash
php -S localhost:8000
```

Visit `http://localhost:8000/login.php` to initiate the login flow.
