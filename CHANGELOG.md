# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`0.5.1...master`][0.5.1...master].

## [`0.5.1`][0.5.1]

For a full diff see [`0.5.0...0.5.1`][0.5.0...0.5.1].

### Fixed

* Removed an inappropriate `replace` configuration from `composer.json` ([#100]), by [@localheinz]

## [`0.5.0`][0.5.0]

For a full diff see [`0.4.0...0.5.0`][0.4.0...0.5.0].

### Changed

* Renamed vendor namespace `Localheinz` to `Ergebnis` after move to [@ergebnis] ([#88]), by [@localheinz]

  Run

  ```
  $ composer remove localheinz/classy
  ```

  and

  ```
  $ composer require ergebnis/classy
  ```

  to update.

  Run

  ```
  $ find . -type f -exec sed -i '.bak' 's/Localheinz\\Classy/Ergebnis\\Classy/g' {} \;
  ```

  to replace occurrences of `Localheinz\Classy` with `Ergebnis\Classy`.

  Run

  ```
  $ find -type f -name '*.bak' -delete
  ```

  to delete backup files created in the previous step.

### Fixed

* Dropped support for PHP 7.1 ([#77]), by [@localheinz]

[0.5.0]: https://github.com/localheinz/ergebnis/classy/releases/tag/0.5.0
[0.5.1]: https://github.com/localheinz/ergebnis/classy/releases/tag/0.5.0

[0.4.0...0.5.0]: https://github.com/ergebnis/classy/compare/0.4.0...0.5.0
[0.5.0...0.5.1]: https://github.com/ergebnis/classy/compare/0.5.0...0.5.1
[0.5.1...master]: https://github.com/ergebnis/classy/compare/0.5.1...master

[#77]: https://github.com/ergebnis/classy/pull/77
[#88]: https://github.com/ergebnis/classy/pull/88
[#100]: https://github.com/ergebnis/classy/pull/100

[@ergebnis]: https://github.com/ergebnis
[@localheinz]: https://github.com/localheinz
