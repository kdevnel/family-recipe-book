# Git Hooks Documentation

This document provides an overview of the Git hooks configured for this project using CaptainHook.

## Commit Message Hook (`commit-msg`)
- **Enabled**: Yes
- **Actions**:
  - `\CaptainHook\App\Hook\Message\Action\Beams`
    - **Options**:
      - `subjectLength`: 50
      - `bodyLineLength`: 72

## Pre-Push Hook (`pre-push`)
- **Enabled**: No
- **Actions**: None

## Pre-Commit Hook (`pre-commit`)
- **Enabled**: Yes
- **Actions**:
  1. `\CaptainHook\App\Hook\PHP\Action\Linting`
     - Runs PHP linting to ensure code syntax is correct.
  2. `phpstan`
     - Executes PHPStan for static analysis to catch potential issues in the codebase.

## Prepare Commit Message Hook (`prepare-commit-msg`)
- **Enabled**: No
- **Actions**: None

## Post-Commit Hook (`post-commit`)
- **Enabled**: No
- **Actions**: None

## Post-Merge Hook (`post-merge`)
- **Enabled**: No
- **Actions**: None

## Post-Checkout Hook (`post-checkout`)
- **Enabled**: No
- **Actions**: None

## Post-Rewrite Hook (`post-rewrite`)
- **Enabled**: No
- **Actions**: None

## Post-Change Hook (`post-change`)
- **Enabled**: No
- **Actions**: None

---

For more details on configuring hooks, refer to the [CaptainHook documentation](https://captainhookphp.github.io/).
