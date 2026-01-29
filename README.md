# Product Parsing

REST API for parsing product pages.

---

## Features

- Parse product pages by URL (Rozetka, Allo, etc.)
- Extract:
    - Product title (H1)
    - Product images
    - Product comments
- Store images in **Local**
- Analyze comments sentiment (**positive / neutral / negative**)
- Persist parsed data in **PostgreSQL**
- Provide REST API to retrieve stored data

---

## Tech Stack

- **Backend:** PHP 8.4, Symfony 8
- **Database:** PostgreSQL
- **Image Storage:** Local
- **Sentiment Analysis:** Mock
- **API Documentation:** Swagger
- **Containerization:** Docker

---

## Installation & Run (Docker)

### 1. Clone repository

### 2. Environment variables

Create **.env.local** file:

APP_ENV=dev

APP_SECRET=your_secret_key

DATABASE_URL="postgresql://user:password@db:5432/app?serverVersion=16"

### 3. Run containers
   docker-compose up -d --build

### 4. Run migrations
   docker-compose exec php bash
   
   php bin/console doctrine:migrations:migrate


## API Documentation (Swagger)

Swagger is available at:

JSON: /api/doc.json

Example (local):

http://localhost:8080/api/doc.json

### API Endpoints
1. Parse product page

    POST /api/parse
    
    { "url": "https://site.com.ua/product-page" }

2. Get parsed product by ID
    
    GET /api/products/{id}


### Deployed Version

Live API: https://parser-product-api.onrender.com/api/products/1

Swagger: https://parser-product-api.onrender.com/api/doc.json

### Author

Vitalii

## License

This project is created as a test assignment and is not intended for commercial use.
