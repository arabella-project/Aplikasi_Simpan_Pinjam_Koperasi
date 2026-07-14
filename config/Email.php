<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig
{
    /**
     * @var string
     */
    public string $fromEmail = 'koperasibpssumsel@gmail.com';

    /**
     * @var string
     */
    public string $fromName = 'Koperasi Pegawai BPS Sumsel';

    /**
     * @var string
     */
    public string $userAgent = 'CodeIgniter';

    /**
     * @var string
     */
    public string $protocol = 'smtp';

    /**
     * @var string
     */
    public string $mailPath = '/usr/sbin/sendmail';

    /**
     * @var string
     */
    public string $SMTPHost = 'smtp.gmail.com';

    /**
     * @var string
     */
    public string $SMTPUser = 'koperasibpssumsel@gmail.com';

    /**
     * @var string
     */
    public string $SMTPPass = 'mxka ssxh nche qyfw'; 

    /**
     * KUNCI PERBAIKAN: Menambahkan Enkripsi SSL yang wajib untuk Port 465
     * @var string
     */
    public string $SMTPCrypto = 'ssl'; 

    /**
     * @var int
     */
    public int $SMTPPort = 465;

    /**
     * Diperpanjang sedikit dari 5 ke 15 agar server lokal/hosting Anda 
     * memiliki waktu bernapas saat melakukan handshake ke server Google
     * @var int
     */
    public int $SMTPTimeout = 15;

    /**
     * @var bool
     */
    public bool $wordWrap = true;

    /**
     * @var int
     */
    public int $wrapChars = 76;

    /**
     * @var string
     */
    public string $mailType = 'html'; 

    /**
     * @var string
     */
    public string $charset = 'UTF-8';

    /**
     * @var bool
     */
    public bool $validate = true;

    /**
     * @var int
     */
    public int $priority = 3;

    /**
     * @var string
     */
    public string $CRLF = "\r\n";

    /**
     * @var string
     */
    public string $newline = "\r\n";

    /**
     * @var bool
     */
    public bool $BCCBatchMode = false;

    /**
     * @var int
     */
    public int $BCCBatchSize = 200;

    /**
     * @var bool
     */
    public bool $DSN = false;
}