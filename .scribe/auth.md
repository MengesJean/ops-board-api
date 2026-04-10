# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {session-cookie}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

    OpsBoard authenticates the first-party Next.js SPA via **cookie-based
    sessions** (Laravel Sanctum SPA). To call a protected endpoint:

    1. `GET /sanctum/csrf-cookie` to obtain an `XSRF-TOKEN` cookie.
    2. `POST /api/login` with your credentials and `credentials: 'include'`.
    3. Subsequent calls reuse the `laravel_session` cookie automatically.

    Third-party and mobile clients may alternatively use a Sanctum bearer
    token issued via `Customer::createToken()`.
