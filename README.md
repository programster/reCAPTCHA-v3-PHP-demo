reCAPTCHA v3 PHP Example
========================

This is a demo codebase for getting reCAPTCHA v3 (enterprise) working in PHP.

## USAGE
Install the required packages by running:

```bash
composer install
```

Create the settings file by copying the example and filling it in:
```bash
cp settings.php.example settings.php
```

Run the codebase locally:

```bash
sudo php -S localhost:80
```

Then navigate to localhost in your browser to try out the demo. If you want to test 
a specific domain, then update your `/etc/hosts` file to point that domain to `127.0.0.1`.
