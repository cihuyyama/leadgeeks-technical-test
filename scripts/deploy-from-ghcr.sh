#!/bin/bash
# scripts/deploy-from-ghcr.sh
# VPS: pull pre-built image from GHCR + restart container.
# Does NOT build on the VPS (saves CPU/RAM/disk).
#
# Usage:
#   bash ~/leadgeeks-ticket/scripts/deploy-from-ghcr.sh
#   bash ~/leadgeeks-ticket/scripts/deploy-from-ghcr.sh latest
#   bash ~/leadgeeks-ticket/scripts/deploy-from-ghcr.sh sha-abc1234
#   bash ~/leadgeeks-ticket/scripts/deploy-from-ghcr.sh v0.1.0
#
# Prefer compose (same image, volumes, healthcheck):
#   podman compose -f docker-compose.prod.yml pull && up -d
#
# One-time VPS setup:
#   git clone https://github.com/cihuyyama/leadgeeks-technical-test.git ~/leadgeeks-ticket
#   # or copy only this script + create ~/.leadgeeks-ticket-env
#   # GHCR login (from laptop):
#   #   $token = gh auth token
#   #   ssh ... "echo $token | podman login ghcr.io -u cihuyyama --password-stdin"
#   # nginx → 127.0.0.1:3002  (leadgeeks-ticket.iqbalalhabib.my.id)
#   # certbot for TLS

set -euo pipefail

REGISTRY="ghcr.io"
OWNER="cihuyyama"
IMAGE_NAME="leadgeeks-technical-test"
FULL_IMAGE="${REGISTRY}/${OWNER}/${IMAGE_NAME}"

APP_CONTAINER="leadgeeks-ticket"
APP_PORT=3002
VERSION="${1:-latest}"
IMAGE_TAG="${FULL_IMAGE}:${VERSION}"

get_env() {
  local key="$1" file="$2"
  [[ -f "$file" ]] || return 0
  grep -E "^[[:space:]]*(export[[:space:]]+)?${key}=" "$file" 2>/dev/null \
    | head -1 \
    | sed -E "s/^[[:space:]]*(export[[:space:]]+)?${key}=//" \
    | sed 's/\r$//' \
    | sed 's/^"//;s/"$//'
}

ENV_FILE="${HOME}/.leadgeeks-ticket-env"
if [[ -f "$ENV_FILE" ]]; then
  # shellcheck disable=SC1090
  set -a
  # shellcheck disable=SC1090
  source "$ENV_FILE"
  set +a
fi

APP_URL="${APP_URL:-https://leadgeeks-ticket.iqbalalhabib.my.id}"
APP_KEY="${APP_KEY:-}"
APP_ENV="${APP_ENV:-production}"
APP_DEBUG="${APP_DEBUG:-false}"
SEED_ON_START="${SEED_ON_START:-true}"
LOG_CHANNEL="${LOG_CHANNEL:-stderr}"
DB_CONNECTION="${DB_CONNECTION:-sqlite}"
SESSION_DRIVER="${SESSION_DRIVER:-database}"
CACHE_STORE="${CACHE_STORE:-database}"
QUEUE_CONNECTION="${QUEUE_CONNECTION:-database}"
TZ="${TZ:-Asia/Jakarta}"

echo ""
echo "=============================================="
echo "  LeadGeeks Ticket — deploy from GHCR"
echo "=============================================="
echo "  Image:     ${IMAGE_TAG}"
echo "  Container: ${APP_CONTAINER}"
echo "  Port:      ${APP_PORT}"
echo "  APP_URL:   ${APP_URL}"
echo "=============================================="
echo ""

if ! command -v podman >/dev/null 2>&1; then
  echo "ERROR: podman not installed" >&2
  exit 1
fi

echo "[1/5] Checking GHCR auth..."
if ! podman login --get-login "${REGISTRY}" >/dev/null 2>&1; then
  echo "ERROR: Not logged into ${REGISTRY}" >&2
  echo "From laptop:" >&2
  echo "  \$token = gh auth token" >&2
  echo "  ssh ... \"echo \$token | podman login ghcr.io -u ${OWNER} --password-stdin\"" >&2
  exit 1
fi
echo "  OK"

echo "[2/5] Pulling ${IMAGE_TAG}..."
podman pull "${IMAGE_TAG}"
echo "  OK"

echo "[3/5] Stopping old container..."
podman stop "${APP_CONTAINER}" 2>/dev/null || true
podman rm "${APP_CONTAINER}" 2>/dev/null || true
echo "  OK"

echo "[4/5] Starting ${APP_CONTAINER}..."
# Named volumes keep SQLite + storage across deploys
RUN_ARGS=(
  -d
  --name "${APP_CONTAINER}"
  --network host
  --restart unless-stopped
  -e APP_NAME="LeadGeeks IT"
  -e APP_ENV="${APP_ENV}"
  -e APP_DEBUG="${APP_DEBUG}"
  -e APP_URL="${APP_URL}"
  -e LOG_CHANNEL="${LOG_CHANNEL}"
  -e DB_CONNECTION="${DB_CONNECTION}"
  -e SESSION_DRIVER="${SESSION_DRIVER}"
  -e CACHE_STORE="${CACHE_STORE}"
  -e QUEUE_CONNECTION="${QUEUE_CONNECTION}"
  -e SEED_ON_START="${SEED_ON_START}"
  -e PORT="${APP_PORT}"
  -e TZ="${TZ}"
  -v leadgeeks-ticket-data:/app/database
  -v leadgeeks-ticket-storage:/app/storage
)

if [[ -n "${APP_KEY}" ]]; then
  RUN_ARGS+=(-e APP_KEY="${APP_KEY}")
fi

# Image CMD uses PORT env (default 8080); VPS sets PORT=3002
podman run "${RUN_ARGS[@]}" "${IMAGE_TAG}"

echo "  OK"

echo "[5/5] Smoke test http://127.0.0.1:${APP_PORT}/up ..."
sleep 4
for i in 1 2 3 4 5 6 7 8 9 10; do
  if curl -fsS -o /dev/null "http://127.0.0.1:${APP_PORT}/up" 2>/dev/null \
    || curl -fsS -o /dev/null -w "%{http_code}" "http://127.0.0.1:${APP_PORT}/" 2>/dev/null | grep -Eq '200|301|302|304'; then
    echo "  HTTP OK"
    break
  fi
  if [[ "$i" -eq 10 ]]; then
    echo "  WARNING: not responding yet — podman logs ${APP_CONTAINER}" >&2
    podman logs --tail 40 "${APP_CONTAINER}" >&2 || true
    exit 1
  fi
  sleep 3
done

echo ""
echo "=============================================="
echo "  DEPLOYED — ${IMAGE_TAG}"
echo "=============================================="
echo "  Local:  http://127.0.0.1:${APP_PORT}"
echo "  Public: ${APP_URL}"
echo "  Logs:   podman logs -f ${APP_CONTAINER}"
echo ""
