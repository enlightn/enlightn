<h1 align="center">Enlightn</h1>

![tests](https://github.com/enlightn/enlightn/workflows/tests/badge.svg?branch=master)
[![LGPLv3 Licensed](https://img.shields.io/badge/license-LGPLv3-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Latest Stable Version](https://poser.pugx.org/enlightn/enlightn/v/stable?format=flat-square)](https://packagist.org/packages/enlightn/enlightn)
[![Total Downloads](https://img.shields.io/packagist/dt/enlightn/enlightn.svg?style=flat-square)](https://packagist.org/packages/enlightn/enlightn)
[![Twitter Follow](https://img.shields.io/twitter/follow/Enlightn_app?label=Follow&style=social)](https://twitter.com/Enlightn_app)


<h2 align="center">A Laravel Tool To Boost Your App's Performance &amp; Security</h2>

![Enlightn](https://cdn.laravel-enlightn.com/images/mockups/enlightn_terminal128.png)

## Introduction

Think of Enlightn as your performance and security consultant. Enlightn will "review" your code and server configurations, and give you actionable recommendations on improving performance, security and reliability!

The Enlightn OSS (open source software) version has 64 automated checks that scan your application code, web server configurations and routes to identify performance bottlenecks, possible security vulnerabilities and code reliability issues.

Enlightn Pro (commercial) is available for purchase on the [Enlightn website](https://www.laravel-enlightn.com/) and has an additional 64 automated checks (total of **128 checks**).

### Performance Checks (37 Automated Checks including 19 Enlightn Pro Checks)

- 🚀 Performance Quick Wins (In-Built In Laravel): Route caching, config caching, etc.
- ⏳ Performance Bottleneck Identification: Middleware bloat, identification of slow, duplicate and N+1 queries, etc.
- 🍽️ Serving Assets: Minification, cache headers, CDN and compression headers.
- 🎛️ Infrastructure Tuning: Opcache, cache hit ratio, unix sockets for single server setups, etc.
- 🛸 Choosing The Right Driver: Choosing the right session, queue and cache drivers for your app.
- 🏆 Good Practices: Separate Redis databases for locks, dont install dev dependencies in production, etc.

### Security Checks (48 Automated Checks including 28 Enlightn Pro Checks)

- :lock: Basic Security: Turn off app debug in production, app key, CSRF protection, login throttling, etc.
- :cookie: Cookie Security and Session Management: Cookie encryption, secure cookie attributes, session timeouts, etc.
- :black_joker: Mass Assignment: Detection of mass assignment vulnerabilities, unguarded models, etc.
- :radioactive: SQL Injection Attacks: Detection of raw SQL injection, column name SQL injection, validation rule injection, etc.
- :scroll: Security Headers: XSS, HSTS, clickjacking and MIME protection headers.
- :file_folder: Unrestricted File Uploads and DOS Attacks: Detection of directory traversal, storage DOS, unrestricted file uploads, etc.
- :syringe: Injection and Phishing Attacks: Detection of command injection, host injection, object injection, open redirection, etc.
- :package: Dependency Management: Backend and frontend vulnerability scanning, stable and up-to-date dependency checks, licensing, etc.

### Reliability Checks (43 Automated Checks including 17 Enlightn Pro Checks)

- 🧐 Code Reliability and Bug Detection: Invalid function calls, method calls, offsets, imports, return statements, syntax errors, etc.
- :muscle: Health Checks: Health checks for cache, DB, directory permissions, migrations, disk space, symlinks, Redis, etc.
- :gear: Detecting Misconfigurations: Cache prefix, queue timeouts, failed job timeouts, Horizon provisioning plans, eviction policy, etc.
- :ghost: Dead Routes and Dead Code: Detection of dead routes and dead/unreachable code.
- :medal_sports: Good Practices: Cache busting, Composer scripts, env variables, avoiding globals and superglobals, etc.

## Documentation

Each of the 128 checks available are well documented. You can find the complete 137 page documentation [here](https://www.laravel-enlightn.com/docs/getting-started/installation.html).

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

You may add the `--report` flag, if you wish to view your reports in the [Enlightn Web UI](https://www.laravel-enlightn.com/docs/getting-started/web-ui.html) besides the terminal:

```bash
php artisan enlightn --report
```

If you wish to run specific analyzer classes, you may specify them as optional arguments:

```bash
php artisan enlightn Enlightn\\Enlightn\\Analyzers\\Security\\CSRFAnalyzer Enlightn\\EnlightnPro\\Analyzers\\Security\\DirectoryTraversalAnalyzer
```

Note that the class names should be fully qualified and escaped with double slashes as above.

## Recommended to Run In Production

If you want to get the full Enlightn experience, it is recommended that you at least run Enlightn once in production. This is because several of Enlightn's checks are environment specific. So they may only be triggered when your app environment is production.

In case you don't want to run on production, you can simulate a production environment by setting your APP_ENV to production, setting up services and config as close to production as possible and running your production deployment script locally. Then run the Enlightn Artisan command.

## View Detailed Error Messages

By default, the `enlightn` Artisan command highlights the file paths, associated line numbers and a message for each failed check. If you wish to display detailed error messages for each line, you may use the `--details` option:

```bash
php artisan enlightn --details
```

## Usage in CI Environments

If you wish to integrate Enlightn with your CI, you can simply trigger the `--ci` option when running Enlightn in your CI/CD tool:

```bash
php artisan enlightn --ci
```

You may add the `--report` flag if you wish to view your CI reports in the [Enlightn Web UI](https://www.laravel-enlightn.com/docs/getting-started/web-ui.html). Remember to add your project credentials to your `config/enlightn.php` file as explained [here](https://www.laravel-enlightn.com/docs/getting-started/web-ui.html#how-to-get-access-free).

```bash
php artisan enlightn --ci --report
```

Enlightn pre-configures which analyzers can be run in CI mode for you. So, the above command excludes analyzers that need a full setup to run (e.g. analyzers using dynamic analysis).

For more information on CI integration, refer the [Enlightn documentation](https://www.laravel-enlightn.com/docs/getting-started/usage.html#usage-in-ci-environments).

## Establishing a Baseline

Sometimes, especially in CI environments, you may want to declare the currently reported list of errors as the "baseline". This means that the current errors will not be reported in subsequent runs and only new errors will be flagged.

To generate the baseline automatically, you may run the `enlightn:baseline` Artisan command:

```bash
php artisan enlightn:baseline
```

If you wish to run this command in CI mode, you can use the `--ci` option:

```bash
php artisan enlightn:baseline --ci
```

For more information on establishing a baseline, refer [the docs](https://www.laravel-enlightn.com/docs/getting-started/usage.html#establishing-a-baseline).

## Web UI

Enlightn offers a beautiful Web UI dashboard where you can view your Enlightn reports triggered from your CI or scheduled command runs.

![Enlightn Web UI Dashboard](https://cdn.laravel-enlightn.com/images/webui_report.png)

The web UI is free for all users and includes the following:
1. Statistics on pass percentages (overall and by category).
2. All failed checks along with code snippets related to the checks (if any).
3. Metrics on number of new and resolved issues (compared with the most recent report running on the same app URL, environment and project).

To get access to the Web UI, all you need to do is signup for free on the Enlightn website and follow the instructions mentioned [here](https://www.laravel-enlightn.com/docs/getting-started/web-ui.html#how-to-get-access-free). 

## Scheduling Enlightn Runs

Besides integrating Enlightn with your CI/CD tool, it's a good practice to schedule an Enlightn run on a regular frequency (such as daily or weekly) like so:

```php
// In your app/Console/Kernel.php file:

/**
 * Define the application's command schedule.
 *
 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
 * @return void
 */
protected function schedule(Schedule $schedule)
{
    $schedule->command('enlightn --report')->runInBackground()->daily()->at('01:00');
}
```

This will allow you to monitor Enlightn's dynamic analysis checks, which are typically excluded from CI. The reports can be viewed on the Enlightn [Web UI](https://www.laravel-enlightn.com/docs/getting-started/web-ui.html).

## GitHub Bot Integration

Enlightn offers a GitHub bot that can prepare a report highlighting failed checks and also add review comments for pull requests on the lines of code that introduce new issues.

![Enlightn GitHub Bot Review Comments](https://cdn.laravel-enlightn.com/images/github-bot.png)

To integrate with the Enlightn GitHub bot, refer [the docs](https://www.laravel-enlightn.com/docs/getting-started/github-bot.html).

## Failed Checks

All checks that fail will include a description of why they failed along with the associated lines of code (if applicable) and a link to the documentation for the specific check.

<img src="https://www.laravel-enlightn.com/docs/images/queue-timeout.png" width="70%" alt="Enlightn Failed Check" />

## Report Card

Finally, after all the checks have run, the `enlightn` Artisan command will output a report card, which contains information on how many and what percentage of checks passed, failed or were skipped.

<img src="https://www.laravel-enlightn.com/docs/images/report_card.png" width="70%" alt="Enlightn Report Card" />

The checks indicated as "Not Applicable" were not applicable to your specific application and were skipped. For instance, the CSRF analyzer is not applicable for stateless applications.

The checks reported under the "Error" row indicate the analyzers that failed with exceptions during the analysis. Normally, this should not happen but if it does, the associated error message will be displayed and may have something to do with your application.

## How Frequently Should I Run Enlightn?

A good practice would be to run Enlightn every time you are deploying code or pushing a new release. It is recommended to integrate Enlightn with your CI/CD tool so that it is triggered for every push or new release.

Besides the automated CI checks, you should also run Enlightn on a regular frequency using a scheduled console command as described above. This will allow you to monitor the dynamic analysis checks, which are typically excluded from CI.

## Featured On

[<img src="https://laravelnews.imgix.net/laravel-news__logo.png" height="100" alt="Laravel News" />](https://laravel-news.com/enlightn) &nbsp;&nbsp;&nbsp; [<img src="https://owasp.org/www-policy/branding-assets/OWASP-Combination-mark-r.png" height="100" alt="OWASP" />](https://cheatsheetseries.owasp.org/cheatsheets/Laravel_Cheat_Sheet.html) &nbsp;&nbsp;&nbsp; [<img src="https://www.nist.gov/sites/default/files/styles/960_x_960_limit/public/images/2017/09/20/645px-nist_logo-svg_1.png" height="80" alt="NIST" />](https://www.nist.gov/itl/ssd/software-quality-group/source-code-security-analyzers)

## Flagship OSS Projects Using Enlightn

[<img src="https://laravel.io/images/laravelio.png" height="60" alt="Laravel.io" />](https://github.com/laravelio/laravel.io) &nbsp;&nbsp;&nbsp; [<img src="https://akaunting.com/public/images/logo.png" height="80" alt="Akaunting" />](https://github.com/akaunting/akaunting)

## OS Compatibility

Only MacOS and Linux systems are supported for Enlightn. Windows is currently not supported.

## Contribution Guide

Thank you for considering contributing to Enlightn! The contribution guide can be found [here](https://www.laravel-enlightn.com/docs/getting-started/contribution-guide.html).

## Support Policy

Our support policy can be found in the [Enlightn documentation](https://www.laravel-enlightn.com/docs/getting-started/support.html).

## License

The Enlightn OSS (on this GitHub repo) is licensed under the [LGPL v3 (or later) license](LICENSE.md).

Enlightn Pro is licensed under a [commercial license](https://www.laravel-enlightn.com/license-agreement).
