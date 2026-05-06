<?php
declare(strict_types=1);

// runs under https://sercan.com.tr/rider
const BASE_PATH = '/rider';

// App
const APP_NAME = 'Rider Application';
const APP_NOTIFY_EMAIL = 'ops@sercan.com.tr';

// Database
const DB_DSN  = 'mysql:host=localhost;dbname=igriceig_sercancomtr;charset=utf8mb4';
const DB_USER = 'igriceig_sercancomtr';
const DB_PASS = 'g5V&YbWRX[($';

// Mail
// 'log' (fast demo) or 'smtp'
const MAIL_TRANSPORT = 'log';

const MAIL_FROM = 'no-reply@sercan.com.tr';
const MAIL_FROM_NAME = 'Rider Team';

// SMTP (if MAIL_TRANSPORT === 'smtp')
const SMTP_HOST = 'smtp.sercan.com.tr';
const SMTP_PORT = 587;
const SMTP_USER = 'no-reply@sercan.com.tr';
const SMTP_PASS = 'password';
const SMTP_SECURE = 'tls'; // tls|ssl|none