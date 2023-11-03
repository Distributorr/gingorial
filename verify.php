<?php
// PHP code to verify the credential on the server side
// This code uses the WebAuthn Library, which is a PHP library that implements the Web Authentication API
// For more information, see [WebAuthn Library]
require_once 'vendor/autoload.php'; // Load the library using Composer
use Webauthn\PublicKeyCredentialLoader; // Use the PublicKeyCredentialLoader class to load the credential
use Webauthn\AuthenticatorAttestationResponseValidator; // Use the AuthenticatorAttestationResponseValidator class to validate the credential
use Webauthn\TrustPath\EmptyTrustPath; // Use the EmptyTrustPath class to trust the credential without any attestation
use Webauthn\PublicKeyCredentialSource; // Use the PublicKeyCredentialSource class to store the credential
use Webauthn\PublicKeyCredentialSourceRepository; // Use the PublicKeyCredentialSourceRepository class to manage the credential storage

// Create a new credential loader object
$publicKeyCredentialLoader = new PublicKeyCredentialLoader();

// Create a new credential validator object
// You need to provide the following parameters:
// - A list of certificate authorities that you trust, which is empty in this case
// - A logger object, which you can use to log the validation process, which is null in this case
$attestationResponseValidator = new AuthenticatorAttestationResponseValidator([], null);

// Create a new credential source repository object
// This is where you store and retrieve the credential sources, which are the data associated with the credentials
// You can use any storage method you want, such as a database, a file, or a session
// In this example, we use a simple array to store the credential sources in memory
// Note that this is not secure or persistent, and you should use a more robust storage method in production
$credentialSourceRepository = new class implements PublicKeyCredentialSourceRepository {
    private $sources = []; // An array to store the credential sources
    public function findOneByCredentialId(string $publicKeyCredentialId): ?PublicKeyCredentialSource
    {
        // This method returns the credential source that matches the given credential ID, or null if not found
        return $this->sources[$publicKeyCredentialId] ?? null;
    }
    public function findAllForUserEntity(\Webauthn\PublicKeyCredentialUserEntity $publicKeyCredentialUserEntity): array
    {
        // This method returns all the credential sources that belong to the given user entity, which is empty in this case
        return [];
    }
    public function saveCredentialSource(PublicKeyCredentialSource $publicKeyCredentialSource): void
    {
        // This method saves the credential source in the array, using the credential ID as the key
        $this->sources[$publicKeyCredentialSource->getPublicKeyCredentialId()] = $publicKeyCredentialSource;
    }
};

// Load the credential from the HTTP request
// The credential is sent as a JSON object in the request body
$publicKeyCredential = $publicKeyCredentialLoader->load($_POST);

// Validate the credential
// You need to provide the following parameters:
// -
