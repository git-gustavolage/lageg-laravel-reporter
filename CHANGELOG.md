# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

### [1.1.1] - 2026-05-14

### Changed

- Refactored driver resolution to support fully dynamic drivers via configuration.
- Reporter manager now resolves drivers directly from `reporter.drivers`.
- Removed hardcoded driver factory methods (`createPdfDriver`, `createXlsxDriver`, `createCsvDriver`).
- Simplified manager registration in the service provider.

### Added

- Support for registering custom drivers dynamically through config.
- Feature tests covering manager resolution, container integration, dynamic drivers, and invalid driver handling.

## [1.1.0] - 2026-03-10

#### Fixed

- Publish config path.
- Report output path uses original filename.

#### Added

- Traits\HasComponents Trait.
- Drivers alias config.
- Contracts\Exportable interface.
- Exporter base class.
- Reporter Drive Manager.
- CHANGELOG.md.

#### Removed

- Trats\ProvidesComponents Trait.
- Contracts\Exporter interface.
- Contracts\Chunkrizable interface.
- Traits\HasComponents trait.
- Jobs\GenerateReportJob job.
