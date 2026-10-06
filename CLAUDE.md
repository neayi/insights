# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this repo is

**Insights** is the Laravel 10 (PHP 8.2) application that holds user accounts and profiles for
Triple Performance. It is the authentication service for the MediaWiki sites (through the wiki-side
`neayi_auth` extension), the SSO provider for the Discourse forums, and stores the users' "context"
(farm/profile data, characteristics) and their interactions with wiki pages (follow, applause, "done").

It is checked out as `insights/` inside the parent `3perf-mw1.43` Docker stack repo, which provides the
containers (see the parent `../CLAUDE.md`). It is its own git repo (github.com/neayi/insights).

## Running things

There is no `docker-compose.yml` in this repo: in local dev, everything runs from the parent stack's
`../docker-compose.yml` (`/home/bertrand/3perf-mw1.43`). Two services mount this directory as `/var/www/html`:
- `insights` — nginx + php-fpm serving `https://insights.dev.tripleperformance.fr` (`dockerfiles/php/`).
- `insights_php` — PHP CLI container for composer/artisan/phpunit.

From this directory, point docker compose at the parent file (paths inside the container are relative to this repo):
```bash
docker compose -f ../docker-compose.yml run --rm --user="$UID:$GID" insights_php php artisan migrate
docker compose -f ../docker-compose.yml run --rm --user="$UID:$GID" insights_php composer install
docker compose -f ../docker-compose.yml run --rm --user="$UID:$GID" insights_php vendor/bin/phpunit tests/Unit
docker compose -f ../docker-compose.yml run --rm --user="$UID:$GID" insights_php vendor/bin/phpunit tests/Unit --filter EditUserTest
```
The helper scripts `migrate.sh` and `test.sh` call plain `docker-compose run`, so they must be run from the parent directory (e.g. `cd .. && insights/migrate.sh`).

**Tests** — two configurations, selected by `APP_ENV`, which changes the DI bindings in `app/Providers/AppServiceProvider.php`:
- `phpunit.xml` (`APP_ENV=testing`): unit tests in `tests/Unit`. All ports (repositories, gateways) are bound to in-memory fakes from `tests/Adapters/`; no DB needed.
- `phpunit-ti-domain-sql.xml` (`APP_ENV=testing-ti`): domain + SQL integration tests (`tests/Integration`) against a real MySQL DB `insight_testing_ti`. Create it and run `php artisan migrate --database mysql-test` first.
  `vendor/bin/phpunit tests/Integration/Repositories -c phpunit-ti-domain-sql.xml`

Note that `AppServiceProvider` only binds the real (SQL/infra) implementations when `APP_ENV` is `local` or `production`; any other env value leaves ports unbound.

**Front assets** — Laravel Mix (`webpack.mix.js`): `npm run dev` / `npm run watch` / `npm run prod`.

**Seeding data from the wiki** — `seed.sh` (`characteristics:import`, `characteristics:department`, `pages:import-all`). `LocalesConfig::getLocaleFromCode()` throws if the `locales_config` table is empty.

## Architecture

Hexagonal/ports-and-adapters layering under `app/Src/UseCases/`:
- `Domain/` — business logic, framework-light. Use cases are plain classes with constructor-injected ports and a single action method (e.g. `Users/EditUser::edit()`). Sub-areas: `Auth`, `Users`, `Context` (with `Model/`, `Dto/`, `Queries/`, `UseCases/`), `Forum`, `System`, `Shared` (gateway interfaces).
- `Domain/Ports/` — repository interfaces (`UserRepository`, `ContextRepository`, `CharacteristicsRepository`, `PageRepository`, `InteractionRepository`, `UserRoleRepository`, `GeoLocationByPostalCode`, `IdentityProvider`).
- `Infra/Sql/` — Eloquent-backed implementations (`*RepositorySql`) and their Eloquent models (`Infra/Sql/Model/`). `Infra/Gateway/` — session auth, Socialite, file storage, picture handling.
- `tests/Adapters/` — in-memory implementations of every port, used by unit tests.

**When adding a port or implementation, register it in all three binding methods of `AppServiceProvider`** (`prodBinding`, `tuBinding`, `tiBinding`) and add an in-memory adapter in `tests/Adapters/`.

Controllers (`app/Http/Controllers`) are thin: use cases/queries are injected as action-method parameters by the container and then called (`->execute()`, `->get()`, …). `app/User.php` is the Eloquent auth user (Sanctum tokens, spatie roles).

Other pieces:
- `app/Src/WikiClient.php` / `ForumApiClient.php` — HTTP clients to the MediaWiki API and Discourse API.
- `app/LocalesConfig.php` — per-language config (wiki URL, forum URL/API keys, forum tag groups), stored in the `locales_config` table. Insights is multi-wiki: many things are resolved per locale (`$user->locale()`).
- `app/Console/Commands` — sync jobs between wiki, Insights and Discourse, scheduled in `app/Console/Kernel.php` (pages import, characteristics import, Discourse user sync, pages→forum sync).
- `app/Src/Utils/Helpers/*.php` — global helper functions, auto-required by `AppServiceProvider`.
- Newsletter providers: `SendinBlueService`, `MailerLiteService` (used by the `AddEmailToNewsletter` listener).

## Integration with the wiki and Discourse

- **Wiki login flow**: the wiki redirects to Insights with `wiki_callback` + `wiki_token`. These are kept in session through login/register/social login/profile wizard (`FlashWikiCallback`, `RedirectIfAuthenticated`, `RegisterController`, `WizardProfileController`), the token is stored on the user, and the user is redirected back to the callback. The wiki then calls `GET /api/user?wiki_token=...` (`Api\OAuthController::userByToken`), which returns the user and a Sanctum token.
- **Wiki API calls** (`routes/api.php`): page interactions/counts use `auth:sanctum` + `wiki.session.id`; avatars, followers, stats and user context are public.
- **Discourse SSO**: `GET {wikiCode}/neayi/discourse/sso` (`Discourse\SsoController`); a user can have one Discourse profile per locale (`DiscourseProfileModel`).

## Conventions

- Read Neayi-specific settings through `config('neayi.*')` (`config/neayi.php`), never `env()` directly outside config files.
- Translations live in `resources/lang/{en,fr}`. The public layout (`resources/views/layouts/neayi/master.blade.php`) includes per-language navbar/footer partials from `resources/views/layouts/neayi/partials/{lang}/`, mirroring the wiki skin's header/footer.
- Domain vocabulary: **Context** = the setting in which a user speaks (farmer, researcher, student…: farm, location, production, structure). **Characteristics** = tags describing a context (farming types, croppings), imported from the wiki and mirrored as Discourse tags. **Interactions** = a user's follow / applause / done (with optional `doneValue`) on a wiki page.
- CI (`.github/workflows/build.yml`) only builds and pushes the Docker image on GitHub releases; tests are not run in CI.
