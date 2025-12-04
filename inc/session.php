<?php
/**
 * inc/session.php
 * - 세션 보장 + CSRF + 플래시 메시지 + 유저 헬퍼
 */
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

/** CSRF */
function csrf_token(): string {
  if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf'];
}
function csrf_validate(?string $token): bool {
  return isset($_SESSION['csrf']) && is_string($token) && hash_equals($_SESSION['csrf'], $token);
}

/** Flash */
function flash_set(string $key, string $msg): void { $_SESSION['flash'][$key] = $msg; }
function flash_get(string $key): ?string {
  if (!empty($_SESSION['flash'][$key])) { $m = $_SESSION['flash'][$key]; unset($_SESSION['flash'][$key]); return $m; }
  return null;
}

/** User helpers */
function current_user(): ?array { return $_SESSION['user'] ?? null; }
function is_manager(): bool {
  $lvl = $_SESSION['user']['level'] ?? 9; // 1~2 관리자로 가정
  return (int)$lvl <= 2;
}

/** Masking Helpers */
function mask_id($id) {
    if (empty($id)) return '';
    $len = mb_strlen($id);
    if ($len <= 2) return $id;
    return mb_substr($id, 0, 2) . str_repeat('*', max(0, $len - 2));
}

function mask_name($name) {
    if (empty($name)) return '';
    $len = mb_strlen($name);
    if ($len <= 1) return $name;
    if ($len == 2) return mb_substr($name, 0, 1) . '*';
    return mb_substr($name, 0, 1) . str_repeat('*', max(0, $len - 2)) . mb_substr($name, -1);
}
