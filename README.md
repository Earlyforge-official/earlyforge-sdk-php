# earlyforge/pulse-sdk

PHP client for the **EarlyForge Pulse API** — an authenticated mirror of
the free, anonymous trend data already shown on
[pulse.earlyforge.ai](https://pulse.earlyforge.ai). Same daily snapshot,
same trend count, same fields. Pulse access is free on every EarlyForge
account — no paid plan required.

This is **not** a client for EarlyForge's paid real-time Trends
detection catalog, articles, briefs, or alerts APIs — only for the free
Pulse snapshot endpoints. Zero dependencies: uses `ext-curl` directly
rather than pulling in an HTTP client library for 4 GET endpoints.

Contract: [`docs/api/public-api-v1/openapi.yaml`](../../docs/api/public-api-v1/openapi.yaml).

## Install

Not published to Packagist yet — install from a clone of this repo:

```bash
git clone https://github.com/earlyforge/earlyforge.git
composer config repositories.earlyforge-pulse-sdk path earlyforge/tools/sdk-php-earlyforge
composer require earlyforge/pulse-sdk:@dev
```

(A published `composer require earlyforge/pulse-sdk` package is coming.)

## Quickstart

```php
use EarlyForge\Pulse\PulseClient;

$client = new PulseClient('ef_live_xxxxxxxxxxxxxxxx');

$snapshot = $client->topTrends(null, 'fr');
echo $snapshot['trends'][0]['display_name'];
```

Get a key from **Settings → API Keys** in the EarlyForge console — any
account works, Pulse access is free.

## Methods

### `topTrends(?string $date = null, ?string $locale = null): array`

Global top trends — same snapshot as the Pulse homepage. Returns
`['trends' => [...], 'total' => int, 'cached_at' => string,
'cache_ttl_seconds' => int]`.

### `trendsByRegion(string $region, ?string $date = null, ?string $locale = null): array`

Top trends for an ISO 3166-1 alpha-2 region code (e.g. `"US"`, `"FR"`).
Same shape as `topTrends()`.

### `trendsByCategory(string $category, ?string $date = null, ?string $locale = null): array`

Top trends for a category (e.g. `"politics"`, `"tech"`). Same shape as
`topTrends()`.

### `getTrend(string $trendId): array`

Detail for one trend. Same fields as a trend, plus `canonical_name`,
`sources`, a full `description`, and a `timeline` of `{ts, score,
sources_count}` points.

## Errors

Every non-2xx response throws `EarlyForge\Pulse\PulseApiException` with
`getStatusCode()` and `getDetail()` (mirroring the OpenAPI spec's `401`
missing/invalid/revoked key, `404` unknown trend, and `502` upstream
unavailable responses). Network/cURL failures also throw
`PulseApiException`.

```php
use EarlyForge\Pulse\PulseApiException;

try {
    $client->getTrend('does-not-exist');
} catch (PulseApiException $e) {
    echo $e->getStatusCode() . ': ' . $e->getDetail();
}
```

## Rate limits

No monthly call quota on this surface, for any plan — only the shared
60 requests/60s burst limit applies, same as the anonymous Pulse page.

## Tests

```bash
composer install
composer test
```
