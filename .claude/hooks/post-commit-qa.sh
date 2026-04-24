#!/bin/bash
# Runs PHPStan after Claude Code executes a git commit command.
# Receives tool input as JSON on stdin.

INPUT=$(cat)
COMMAND=$(echo "$INPUT" | python3 -c "
import sys, json
try:
    d = json.load(sys.stdin)
    print(d.get('tool_input', {}).get('command', ''))
except Exception:
    pass
" 2>/dev/null <<< "$INPUT")

if ! echo "$COMMAND" | grep -q "git commit"; then
    exit 0
fi

echo ""
echo "=== PHPStan (post-commit) ==="
vendor/bin/phpstan analyse --no-progress
