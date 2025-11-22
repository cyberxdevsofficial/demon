DemonClassic - Full Panel (No Wings)
===================================

This is a starter hosting panel inspired by Pterodactyl, but WITHOUT Wings (no node/daemon).
It includes:
- Full web UI (PHP + SQLite)
- Admin dashboard
- User registration/login
- Server CRUD (database-only)
- Themes (light/dark)
- Simple token-based API

Default admin user:
- email: admin@demonclassic.local
- password: admin123

Run with Docker:
1. docker-compose up -d --build
2. Open http://localhost:8080

Notes:
- This is a starter. To fully match Pterodactyl features (actual server runtime), you'd need to integrate Wings or other backends.
