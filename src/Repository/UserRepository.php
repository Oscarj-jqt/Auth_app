<?php

/**
 * Accès BDD pour utilisateurs
 */

namespace App\Repository;

use App\Domain\User;

class UserRepository
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
        if (!file_exists(dirname($file))) {
            mkdir(dirname($file), 0755, true);
        }
        if (!file_exists($file)) {
            file_put_contents($file, json_encode([]));
        }
    }

    /**
     * @return array<int, array>
     */
    private function readAll(): array
    {
        $json = file_get_contents($this->file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }

    private function writeAll(array $data): void
    {
        file_put_contents($this->file, json_encode(array_values($data), JSON_PRETTY_PRINT));
    }

    public function findByGithubId(string $githubId): ?array
    {
        $all = $this->readAll();
        foreach ($all as $u) {
            if (isset($u['github_id']) && $u['github_id'] == $githubId) {
                return $u;
            }
        }
        return null;
    }

    public function findById(string $id): ?array
    {
        $all = $this->readAll();
        foreach ($all as $u) {
            if (isset($u['id']) && $u['id'] === $id) {
                return $u;
            }
        }
        return null;
    }

    public function save(array $user): array
    {
        $all = $this->readAll();

        // if exists (by id or github_id) update, else add
        $updated = false;
        foreach ($all as &$u) {
            if ((isset($user['id']) && isset($u['id']) && $u['id'] === $user['id'])
                || (isset($user['github_id']) && isset($u['github_id']) && $u['github_id'] === $user['github_id'])) {
                $u = array_merge($u, $user);
                $updated = true;
                break;
            }
        }
        if (!$updated) {
            // ensure id and timestamps
            if (empty($user['id'])) {
                $user['id'] = bin2hex(random_bytes(8));
            }
            $all[] = $user;
        }

        $this->writeAll($all);

        // return saved user (fresh)
        return $this->findById($user['id']) ?? $user;
    }

    public function update(string $id, array $newData): bool
    {
        $all = $this->readAll();
        $updated = false;
        foreach ($all as &$u) {
            if (isset($u['id']) && $u['id'] === $id) {
                $u = array_merge($u, $newData);
                $updated = true;
                break;
            }
        }
        if ($updated) {
            $this->writeAll($all);
        }
        return $updated;
    }
}