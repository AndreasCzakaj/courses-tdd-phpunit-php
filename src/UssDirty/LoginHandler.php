<?php

declare(strict_types=1);

namespace BinaryStars\Tdd\UssDirty;

use PDO;
use Throwable;

class LoginHandler
{
    public function handle(): void
    {
        header('Content-Type: application/json');
        $c = null;
        try {
            $d = json_decode(file_get_contents('php://input'), true);
            if ($d && is_array($d)) {
                if (isset($d['username']) && is_string($d['username'])) {
                    if (strlen($d['username']) >= 8) {
                        if (strlen($d['username']) <= 20) {
                            if (preg_match('/^[a-zA-Z0-9\-_]+\z/', $d['username'])) {
                                if (isset($d['password']) && is_string($d['password'])) {
                                    if (strlen($d['password']) >= 12 && strlen($d['password']) <= 32) {
                                        if (preg_match('/^[a-zA-Z0-9\-_.,+]+\z/', $d['password'])) {
                                            // check db
                                            $c = new PDO(
                                                'sqlite:' . (getenv('USS_DB') ?: sys_get_temp_dir() . '/uss.sqlite')
                                            );
                                            $s = $c->prepare('SELECT * FROM accounts WHERE username = ?');
                                            $s->execute([$d['username']]);
                                            $r = $s->fetch(PDO::FETCH_NUM);
                                            if ($r) {
                                                if (password_verify($d['password'], $r[2])) {
                                                    if ($r[5] == 'verified') {
                                                        http_response_code(200);
                                                        echo json_encode([
                                                            'accountId' => $r[0],
                                                            'username' => $r[1],
                                                            'email' => $r[3],
                                                        ]);
                                                    } else {
                                                        http_response_code(400);
                                                        echo json_encode(['error' => 'account not verified']);
                                                    }
                                                } else {
                                                    http_response_code(401);
                                                    echo json_encode([
                                                        'error' => 'unknown username or wrong password',
                                                    ]);
                                                }
                                            } else {
                                                http_response_code(401);
                                                echo json_encode(['error' => 'unknown username or wrong password']);
                                            }
                                        } else {
                                            http_response_code(400);
                                            echo json_encode(['error' => 'invalid: password']);
                                        }
                                    } else {
                                        http_response_code(400);
                                        echo json_encode(['error' => 'invalid: password']);
                                    }
                                } else {
                                    http_response_code(400);
                                    echo json_encode(['error' => 'invalid: password']);
                                }
                            } else {
                                http_response_code(400);
                                echo json_encode(['error' => 'invalid: username']);
                            }
                        } else {
                            http_response_code(400);
                            echo json_encode(['error' => 'invalid: username']);
                        }
                    } else {
                        http_response_code(400);
                        echo json_encode(['error' => 'invalid: username']);
                    }
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'invalid: username']);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'invalid: username']);
            }
        } catch (Throwable $e) {
            error_log((string) $e);
            http_response_code(500);
            echo json_encode(['error' => 'try again later']);
        } finally {
            $c = null;
        }
    }
}
