# Git Hooks

This repo uses custom Git hooks stored in `.githooks`.

## Setup

Run the commands below once per clone:

```sh
git config core.hooksPath .githooks
chmod +x .githooks/pre-commit .githooks/pre-push
```

## What runs

- `pre-commit`: runs Pint to auto-fix formatting.
- `pre-push`: runs PHPStan, unit tests, and feature tests.

