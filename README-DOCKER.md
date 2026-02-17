# Deploying with Docker (local / Render / other hosts)

This repository includes a Dockerfile and `docker-compose.yml` to run the full Laravel app (PHP-FPM + Nginx).

Quick local run:

```bash
docker compose up --build

# then open http://localhost in your browser
```

Notes:
- The Dockerfile builds frontend assets using Node and copies them into the PHP image under `public/build`.
- Make sure to create a `.env` file locally (copy `.env.example`) and set database credentials and `APP_KEY`.
- For production hosting (Render, Fly, DO App Platform), use the included Dockerfile and configure the platform to run the container.
