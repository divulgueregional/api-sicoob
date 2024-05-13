<?php

namespace Divulgueregional\apisicoob;

class CertificateTools
{
    private $clientId;
    private $certificateContent;
    private $certificatePassword;
    private $certifcateFiles;

    public function __construct($clientId, $certificateContent, $certificatePassword)
    {
        $this->clientId = $clientId;
        $this->certificateContent = $certificateContent;
        $this->certificatePassword = $certificatePassword;

        $this->certifcateFiles = $this->generatePemFiles(
            $clientId,
            $certificateContent,
            $certificatePassword
        );
    }

    public function generatePemFiles(
        $clientId,
        $certificateContent,
        $certificatePassword
    ) {
        $certificateStoreData = [];
        $readedSuccessful = openssl_pkcs12_read(
            $certificateContent,
            $certificateStoreData,
            $certificatePassword
        );

        if (!$readedSuccessful) {
            throw new \Exception("Error: " . openssl_error_string());
        }

        $arrReturn = [];
        $arrReturn['certificate'] = $this->createTempPemFile(
            "{$clientId}",
            $certificateStoreData["cert"]
        );
        $arrReturn['certificateKey'] = $this->createTempPemFile(
            "{$clientId}-private-key",
            $certificateStoreData["pkey"]
        );

        return $arrReturn;
    }

    private function createTempPemFile($fileName, $certificateContent)
    {
        $tempFileName = sys_get_temp_dir() . "/{$fileName}.pem";
        $tempFile = fopen($tempFileName, "w");
        fwrite($tempFile, $certificateContent);
        fclose($tempFile);
        return $tempFileName;
    }

    public function getCertificateFilePath()
    {
        return $this->certifcateFiles['certificate'];
    }

    public function getPrivateKeyFilePath()
    {
        return $this->certifcateFiles['certificateKey'];
    }
}
