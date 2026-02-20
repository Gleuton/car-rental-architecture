# Git Hooks

This repo uses custom Git hooks stored in `.githooks`.

## Setup

Run the commands below once per clone:

```sh
git config core.hooksPath .githooks
chmod +x .githooks/pre-commit .githooks/pre-push
```

## What runs

- **pre-commit**: runs Pint to auto-fix formatting on staged PHP files.
- **pre-push**: runs the full quality pipeline locally before pushing:
  1. PHPStan (static analysis)
  2. Tests + Code Coverage (fails if any test fails or coverage drops below 85%)

> The coverage threshold must match the value configured in
> `.github/workflows/quality.yml` (`--min=85`).

