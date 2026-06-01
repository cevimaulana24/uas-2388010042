# UAS Administrasi Server - Multi App Deployment

Project ini berisi deployment 2 aplikasi ke AWS EC2 instance `UAS-2388010042`:

- Web Statis: Web CV dari UTS.
- Web Dinamis: PHP Native + MariaDB.

## Arsitektur

```text
User Browser
    |
    | HTTP :80
    v
Nginx Reverse Proxy
    |-- /      -> static-web
    |-- /app/  -> dynamic-app
                    |
                    v
                  MariaDB
```

## Struktur Project

```text
uas-2388010042/
├─ static-web/
│  ├─ Dockerfile
│  └─ src/
├─ dynamic-app/
│  ├─ Dockerfile
│  ├─ public/
│  └─ src/
├─ database/
│  └─ init.sql
├─ nginx/
│  └─ default.conf
├─ docker-compose.yml
├─ .github/
│  └─ workflows/
│     ├─ deploy-static.yml
│     └─ deploy-dynamic.yml
└─ README.md
```

## URL Production

- Web Statis: `http://47.128.217.195/`
- Web Dinamis: `http://47.128.217.195/app/`
- Login Demo: `admin` / `admin123`

## Environment

Salin `.env.example` menjadi `.env` untuk menjalankan lokal:

```bash
cp .env.example .env
```

Variabel yang digunakan:

```text
DB_HOST
DB_PORT
DB_NAME
DB_USER
DB_PASSWORD
MARIADB_ROOT_PASSWORD
STATIC_IMAGE
DYNAMIC_IMAGE
```

## Menjalankan Lokal

```bash
docker compose up -d --build
docker compose ps
```

## GitHub Secrets

Tambahkan secrets berikut di GitHub repository:

```text
DOCKERHUB_USERNAME
DOCKERHUB_TOKEN
EC2_HOST
EC2_USER
EC2_SSH_KEY
STATIC_IMAGE
DYNAMIC_IMAGE
DB_USER
DB_PASSWORD
DB_NAME
MARIADB_ROOT_PASSWORD
```

Nilai contoh:

```text
EC2_HOST=47.128.217.195
EC2_USER=ubuntu
```

## CI/CD

Pipeline menggunakan GitHub Actions:

- `deploy-static.yml`: berjalan jika folder `static-web`, `nginx`, atau `docker-compose.yml` berubah.
- `deploy-dynamic.yml`: berjalan jika folder `dynamic-app`, `database`, `nginx`, atau `docker-compose.yml` berubah.

Alur deployment:

1. Checkout source code.
2. Login ke Docker Hub.
3. Build Docker image.
4. Push image ke Docker Hub.
5. Copy file deployment ke EC2.
6. Pull image terbaru di EC2.
7. Restart container dengan `docker compose up -d`.

## Bukti Pengujian

Isi bagian ini dengan screenshot:

- GitHub Actions berhasil centang hijau.
- `docker compose ps` di EC2.
- Web Statis tampil di browser.
- Web Dinamis tampil di browser.
- Login berhasil.
- Live test auto-update setelah `git push`.
