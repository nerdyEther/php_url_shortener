# URL Shortener

A full-stack URL shortening application built with PHP backend and React frontend. This application allows users to create shortened URLs, track analytics, and manage geo-based redirects.

## Features

- URL shortening with custom alias support
- Analytics tracking for shortened URLs
- Geo-based routing capabilities
- Rate limiting
- Caching mechanism
- Responsive React frontend

## Prerequisites

- PHP 
- MySQL 
- Node.js 
- npm 
- Composer (PHP package manager)

## Project Structure

```
root/
├── client/                 # Frontend React application
│   ├── src/
│   │   ├── App.jsx
│   │   └── ...
│   ├── package.json
│   └── ...
└── server/                # Backend PHP application
    ├── Database.php
    ├── URLShortener.php
    └── index.php
```

## Installation & Setup

### Backend Setup

1. Navigate to the server directory:
   ```bash
   cd server
   ```

2. Create a MySQL database:
   ```sql
   CREATE DATABASE url_shortener;
   ```

3. Update the database configuration in `Database.php`:
   ```php
   $this->pdo = new PDO(
       "mysql:host=localhost;dbname=url_shortener;charset=utf8mb4",
       "your_username",
       "your_password",
       [...]
   );
   ```

4. Start the PHP development server:
   ```bash
   php -S localhost:8000
   ```

### Frontend Setup

1. Navigate to the client directory:
   ```bash
   cd client
   ```

2. Install dependencies:
   ```bash
   npm install

   ```

3. Update the API base URL in `App.jsx` if needed:
   ```javascript
   const API_BASE_URL = 'http://localhost:8000';
   ```

4. Start the development server:
   ```bash
   npm start

   ```

## Usage

1. Access the application at `http://localhost:3000`
2. Enter a URL in the input field
3. Click "Shorten URL" to generate a shortened version
4. Use the copy button to copy the shortened URL to clipboard

## API Endpoints

- `POST /shorten`: Create a shortened URL
  ```json
  {
    "url": "https://example.com",
    "alias": "custom-alias",    // optional
    "expiresAt": "2024-12-31", // optional
    "geoRoutes": {             // optional
      "US": "https://us.example.com",
      "UK": "https://uk.example.com"
    }
  }
  ```

- `GET /analytics?code=<shortcode>`: Get analytics for a shortened URL
- `POST /geo-routes`: Update geo-routes for an existing shortened URL
- `GET /<shortcode>`: Redirect to the original URL

## Rate Limiting

The API implements rate limiting of 100 requests per hour per IP address. This can be configured in `URLShortener.php`:

```php
private const RATE_LIMIT = 100;    // requests per hour
```

