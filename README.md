# Zitadel PHP Client

[![Release](https://github.com/RedstonePfalz/zitadel-php-client/actions/workflows/release.yml/badge.svg?branch=main)](https://github.com/RedstonePfalz/zitadel-php-client/actions/workflows/release.yml)
[![CI](https://github.com/RedstonePfalz/zitadel-php-client/actions/workflows/push.yml/badge.svg)](https://github.com/RedstonePfalz/zitadel-php-client/actions/workflows/push.yml)


Zitadel PHP Client is a simple PHP library for interacting with Zitadel.

You can use it easily with Composer:
```Bash
composer require redstonepfalz/zitadel-php-client
```

There are two documentations:

1. [A classic PHPDoc documentation](https://redstonepfalz.github.io/zitadel-php-client/phpdoc)
2. [A more detailed, beginner-friendly documentation](https://redstonepfalz.github.io/zitadel-php-client/classic)

## Contributing

If you want to contribute to the repository, please note the following points:
- If you fork this repository and want to perform commits, please make sure to use the ConventionalCommit standard.
- Don't make any changes in the docs-Folder. The content of the docs-Folder is automatically generated during the release process.
- If you want to change the documentation, you can edit the PHPDoc comments in the code to edit the PHPDoc documentation or edit the markdown files in the Writerside-Folder.

The classic documentation is created using JetBrains Writerside.  
The PHPDoc documentation is created using Doxygen. You can configure Doxygen via the Doxyfile.

## Roadmap
The library is still under development. The parts for user and session management are completed, documented and ready to use.

In Development:
- Instance Management

## Credits

- [Zitadel](https://github.com/zitadel/zitadel)
- [PHP-QRCode](https://github.com/chillerlan/php-qrcode)
- [Doxygen](https://github.com/doxygen/doxygen)
- [JetBrains Writerside](https://www.jetbrains.com/writerside)

## License

Zitadel PHP Client is released under the Apache 2.0 License.
