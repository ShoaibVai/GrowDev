<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetService
{
    /**
     * Word list for generating memorable verification codes
     */
    private const WORD_BANK = [
        'alpha', 'bravo', 'charlie', 'delta', 'echo', 'foxtrot', 'golf', 'hotel',
        'india', 'juliet', 'kilo', 'lima', 'mike', 'november', 'oscar', 'papa',
        'quebec', 'romeo', 'sierra', 'tango', 'uniform', 'victor', 'whiskey', 'xray',
        'yankee', 'zulu', 'phoenix', 'dragon', 'tiger', 'eagle', 'falcon', 'hawk',
        'wolf', 'bear', 'lion', 'panther', 'cobra', 'viper', 'storm', 'thunder',
        'lightning', 'blaze', 'frost', 'shadow', 'ghost', 'spirit', 'nova', 'comet'
    ];

    /**
     * Generate an encrypted token with word-based verification
     *
     * @param string $email
     * @param string $token
     * @return array
     */
    public function generateSecureToken(string $email, string $token): array
    {
        // Generate verification words (3 random words)
        $verificationWords = $this->generateVerificationWords(3);
        
        // Create payload with all necessary data
        $payload = [
            'email' => $email,
            'token' => $token,
            'words' => $verificationWords,
            'timestamp' => Carbon::now()->timestamp,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        // Serialize and encrypt the payload
        $serialized = serialize($payload);
        $encrypted = Crypt::encryptString($serialized);
        
        // Create URL-safe token
        $urlSafeToken = base64_encode($encrypted);
        
        return [
            'encrypted_token' => $urlSafeToken,
            'verification_words' => $verificationWords,
            'plain_token' => $token,
        ];
    }

    /**
     * Decrypt and verify the token
     *
     * @param string $encryptedToken
     * @param string $providedWords
     * @return array|null
     */
    public function verifySecureToken(string $encryptedToken, ?string $providedWords = null): ?array
    {
        try {
            // Decode from URL-safe format
            $encrypted = base64_decode($encryptedToken);
            
            // Decrypt the token
            $serialized = Crypt::decryptString($encrypted);
            
            // Unserialize the payload
            $payload = unserialize($serialized);
            
            // Verify token hasn't expired (60 minutes)
            $expiresAt = Carbon::createFromTimestamp($payload['timestamp'])->addMinutes(60);
            if (Carbon::now()->greaterThan($expiresAt)) {
                return null;
            }

            // If verification words are provided, validate them
            if ($providedWords !== null) {
                $normalizedProvided = $this->normalizeWords($providedWords);
                $normalizedStored = $this->normalizeWords(implode(' ', $payload['words']));
                
                if ($normalizedProvided !== $normalizedStored) {
                    return null;
                }
            }
            
            return $payload;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Generate random verification words
     *
     * @param int $count
     * @return array
     */
    private function generateVerificationWords(int $count = 3): array
    {
        $words = [];
        $availableWords = self::WORD_BANK;
        
        for ($i = 0; $i < $count; $i++) {
            $index = array_rand($availableWords);
            $words[] = $availableWords[$index];
            unset($availableWords[$index]); // Avoid duplicates
            $availableWords = array_values($availableWords);
        }
        
        return $words;
    }

    /**
     * Normalize words for comparison
     *
     * @param string $words
     * @return string
     */
    private function normalizeWords(string $words): string
    {
        // Convert to lowercase, remove extra spaces, and trim
        return strtolower(trim(preg_replace('/\s+/', ' ', $words)));
    }

    /**
     * Generate a human-readable verification code
     *
     * @param array $words
     * @return string
     */
    public function formatVerificationCode(array $words): string
    {
        return strtoupper(implode('-', $words));
    }

    /**
     * Validate if the token structure is valid
     *
     * @param string $token
     * @return bool
     */
    public function isValidTokenStructure(string $token): bool
    {
        // Check if token is base64 encoded
        $decoded = base64_decode($token, true);
        return $decoded !== false && base64_encode($decoded) === $token;
    }

    /**
     * Create a verification fingerprint
     *
     * @param array $payload
     * @return string
     */
    public function createFingerprint(array $payload): string
    {
        $data = $payload['email'] . 
                $payload['timestamp'] . 
                $payload['ip'] . 
                $payload['user_agent'];
        
        return hash('sha256', $data);
    }

    /**
     * Verify request fingerprint matches token
     *
     * @param array $payload
     * @return bool
     */
    public function verifyFingerprint(array $payload): bool
    {
        $storedIp = $payload['ip'];
        $storedUserAgent = $payload['user_agent'];
        
        $currentIp = request()->ip();
        $currentUserAgent = request()->userAgent();
        
        // IP must match, user agent should match but can be flexible
        return $storedIp === $currentIp && 
               $storedUserAgent === $currentUserAgent;
    }
}
