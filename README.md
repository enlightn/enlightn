<h1 align="center">Enlightn</h1>

![tests](https://github.com/enlightn/enlightn/workflows/tests/badge.svg?branch=master)
[![LGPLv3 Licensed](https://img.shields.io/badge/license-LGPLv3-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Stable Version](https://poser.pugx.org/enlightn/enlightn/v/stable?format=flat-square)](https://packagist.org/packages/enlightn/enlightn)
[![Total Downloads](https://img.shields.io/packagist/dt/enlightn/enlightn.svg?style=flat-square)](https://packagist.org/packages/enlightn/enlightn)


<h2 align="center">A Laravel Tool To Boost Your App's Performance &amp; Security</h2>

![Enlightn](https://cdn.laravel-enlightn.com/images/mockups/terminal.png)

## Introduction

Think of Enlightn as your performance and security consultant. Enlightn will "review" your code and server configurations, and give you actionable recommendations on improving performance, security and reliability!

The Enlightn OSS (open source software) version has 61 automated checks that scan your application code, web server configurations and routes to identify performance bottlenecks, possible security vulnerabilities and code reliability issues.

Enlightn Pro (commercial) is available for purchase on the [Enlightn website](https://www.laravel-enlightn.com/) and has an additional 61 automated checks (total of **122 checks**).

### Performance Checks (34 Automated Checks including 17 Enlightn Pro Checks)

- 🚀 Performance Quick Wins (In-Built In Laravel): Route caching, config caching, etc.
- ⏳ Performance Bottleneck Identification: Middleware bloat, identification of slow, duplicate and N+1 queries, etc.
- 🍽️ Serving Assets: Minification, cache headers, CDN and compression headers.
- 🎛️ Infrastructure Tuning: Opcache, cache hit ratio, unix sockets for single server setups, etc.
- 🛸 Choosing The Right Driver: Choosing the right session, queue and cache drivers for your app.
- 🏆 Good Practices: Separate Redis databases for locks, dont install dev dependencies in production, etc.

### Security Checks (46 Automated Checks including 27 Enlightn Pro Checks)

- :lock: Basic Security: Turn off app debug in production, app key, CSRF protection, login throttling, etc.
- :cookie: Cookie Security and Session Management: Cookie encryption, secure cookie attributes, session timeouts, etc.
- :black_joker: Mass Assignment: Detection of mass assignment vulnerabilities, unguarded models, etc.
- :radioactive: SQL Injection Attacks: Detection of raw SQL injection, column name SQL injection, validation rule injection, etc.
- :scroll: Security Headers: XSS, HSTS, clickjacking and MIME protection headers.
- :file_folder: Unrestricted File Uploads and DOS Attacks: Detection of directory traversal, storage DOS, unrestricted file uploads, etc.
- :syringe: Injection and Phishing Attacks: Detection of command injection, host injection, object injection, open redirection, etc.
- :package: Dependency Management: Backend and frontend vulnerability scanning, stable and up-to-date dependency checks, licensing, etc.

### Reliability Checks (42 Automated Checks including 17 Enlightn Pro Checks)

- 🧐 Code Reliability and Bug Detection: Invalid function calls, method calls, offsets, imports, return statements, syntax errors, etc.
- :muscle: Health Checks: Health checks for cache, DB, directory permissions, migrations, disk space, symlinks, Redis, etc.
- :gear: Detecting Misconfigurations: Cache prefix, queue timeouts, failed job timeouts, Horizon provisioning plans, eviction policy, etc.
- :ghost: Dead Routes and Dead Code: Detection of dead routes and dead/unreachable code.
- :medal_sports: Good Practices: Cache busting, Composer scripts, env variables, avoiding globals and superglobals, etc.

## Documentation

Each of the 122 checks available are well documented. You can find the complete 130 page documentation [here](https://www.laravel-enlightn.com/docs/getting-started/installation.html).

## Installing Enlightn OSS

You may install Enlightn into your project using the Composer package manager:

```bash
composer require enlightn/enlightn
```

After installing Enlightn, you may publish its assets using the vendor:publish Artisan command:

```bash
php artisan vendor:publish --tag=enlightn
```

Note: If you need to install Enlightn Pro, visit the documentation on the Enlightn website [here](https://www.laravel-enlightn.com/docs/getting-started/installation.html#installing-enlightn-pro).

## Running Enlightn

After installing Enlightn, simply run the `enlightn` Artisan command to run Enlightn:

```bash
php artisan enlightn
```

If you wish to run specific analyzer classes, you may specify them as optional arguments:

```bash
php artisan enlightn Enlightn\\Enlightn\\Analyzers\\Security\\CSRFAnalyzer Enlightn\\EnlightnPro\\Analyzers\\Security\\DirectoryTraversalAnalyzer
```

Note that the class names should be fully qualified and escaped with double slashes as above.

## Recommended to Run In Production

If you want to get the full Enlightn experience, it is recommended that you at least run Enlightn once in production. This is because several of Enlightn's checks are environment specific. So they may only be triggered when your app environment is production.

In case you don't want to run on production, you can simulate a production environment by setting your APP_ENV to production, setting up services and config as close to production as possible and running your production deployment script locally. Then run the Enlightn Artisan command.

## Failed Checks

All checks that fail will include a description of why they failed along with the associated lines of code (if applicable) and a link to the documentation for the specific check.

![Enlightn Checks](https://www.laravel-enlightn.com/docs/images/queue-timeout.png)

## Report Card

Finally, after all the checks have run, the `enlightn` Artisan command will output a report card, which contains information on how many and what percentage of checks passed, failed or were skipped.

![Enlightn Report Card](https://www.laravel-enlightn.com/docs/images/report_card.png)

The checks indicated as "Not Applicable" were not applicable to your specific application and were skipped. For instance, the CSRF analyzer is not applicable for stateless applications.

The checks reported under the "Error" row indicate the analyzers that failed with exceptions during the analysis. Normally, this should not happen but if it does, the associated error message will be displayed and may have something to do with your application.

## How Frequently Should I Run Enlightn?

A good practice would be to run Enlightn every time you are deploying code or pushing or a new release. If your application is stable (not many new releases), then you might want to run Enlightn say once a month or so. Remember that Enlightn not only scans your application code but also monitors your application's health.

## OS Compatibility

Only MacOS and Linux systems are supported for Enlightn. Windows is currently not supported.

## Contribution Guide

Thank you for considering contributing to Enlightn! The contribution guide can be found [here](https://www.laravel-enlightn.com/docs/getting-started/contribution-guide.html).

## Support Policy

Our support policy can be found in the [Enlightn documentation](https://www.laravel-enlightn.com/docs/getting-started/support.html).

## License

The Enlightn OSS (on this Github repo) is licensed under the [LGPL v3 (or later) license](LICENSE.md).

Enlightn Pro is licensed under a [commercial license](https://www.laravel-enlightn.com/license-agreement).
