#!/usr/bin/env bash
# Applies vendor patches from ./patches after composer install/update.
# Needed because vimeo/psalm dev-master's own internal code (e.g. an
# undeclared $depth property in StatementsAnalyzer.php) triggers PHP 8.4
# "dynamic property"/"undefined property" notices, which Psalm's own strict
# ErrorHandler turns into a fatal crash before analysis can run. This patch
# stops Psalm's ErrorHandler from treating those PHP-engine notices about
# its own internals as fatal. See patches/psalm-fix-error-handler-php84.patch
set -euo pipefail

cd "$(dirname "$0")/.."

PATCH="$(pwd)/patches/psalm-fix-error-handler-php84.patch"
TARGET="vendor/vimeo/psalm/src/Psalm/Internal/ErrorHandler.php"

if [ ! -f "$TARGET" ]; then
    exit 0
fi

if grep -q 'E_DEPRECATED' "$TARGET"; then
    exit 0
fi

(cd vendor/vimeo/psalm && patch -p1 --forward --silent < "$PATCH")
