<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */


 use Zend\Session\Storage\SessionArrayStorage;
 use Zend\Session\Validator\RemoteAddr;
 use Zend\Session\Validator\HttpUserAgent;

return [
  'session_config' => [
    'cookie_lifetime'     => 60*60*1, // Session cookie will expire in 1 hour.
    'gc_maxlifetime'      => 60*60*24*30, // How long to store session data on server (for 1 month).
  ],
  'session_manager' => [
      'validators' => [
          RemoteAddr::class,
          HttpUserAgent::class,
      ]
  ],
  'session_storage' => [
      'type' => SessionArrayStorage::class
  ],
  'doctrine' => [
    'connection' => [
        'orm_default' => [
            'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
            'params' => [
                'host'     => 'localhost',
                'port'     => '3306',
                'user'     => 'root',
                'password' => '',
                'dbname'   => 'metis',
            ]
        ]
    ]
  ]
];
