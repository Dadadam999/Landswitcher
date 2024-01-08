<?php

declare(strict_types=1);

namespace Plugin\Landswitcher\Manager;

class NonceManager
{
    public function create(): string
    {
        $nonce = bin2hex(random_bytes(32));
        $_SESSION['nonce'] = $nonce;
        return $nonce;
    }

    public function createField(string $fieldName): string
    {
        $nonce = bin2hex(random_bytes(32));
        $_SESSION['nonce'] = $nonce;
        return  "<input type=\"hidden\" id=\"{$fieldName}\" name=\"{$fieldName}\" value=\"{$nonce}\">";
    }

    public function verify(string $receivedNonce): bool
    {
        if (isset($_SESSION['nonce']) && $_SESSION['nonce'] === $receivedNonce) {
            unset($_SESSION['nonce']);
            return true;
        }

        return false;
    }
}
