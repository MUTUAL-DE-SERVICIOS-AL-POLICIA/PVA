<?php

namespace App\Helpers;

class Ldap
{
  private $config;
  private $connection;

  public function __construct()
  {
    $this->config = array(
      'ldap_host' => env("LDAP_HOST"),
      'ldap_port' => env("LDAP_PORT"),
      'ldap_ssl' => env("LDAP_SSL"),
      'user_id_key' => env("LDAP_ACCOUNT_PREFIX"),
      'base_dn' => env("LDAP_BASEDN"),
      'timeout' => env("LDAP_TIMEOUT")
    );

    $this->config['account_suffix'] = implode(',', [env("LDAP_ACCOUNT_SUFFIX"), $this->config['base_dn']]);

    $this->config['ldap_url'] = 'ldap://' . $this->config['ldap_host'];

    $this->connection = @ldap_connect($this->config['ldap_url'], $this->config['ldap_port']);

    if ($this->config['ldap_ssl'] && $this->connection) {
      ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, 3);
      ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);
    }
  }

  public function verify_open_port()
  {
    return @fsockopen($this->config['ldap_host'], $this->config['ldap_port'], $errno, $errstr, $this->config['timeout']);
  }

  public function get_config()
  {
    return $this->config;
  }

  public function __get($connection)
  {
    return $this->connection;
  }

  public function bind($username, $password)
  {
    if ($this->connection) {
      return @ldap_bind($this->connection, $this->config['user_id_key'] . '=' . $username . ',' . $this->config['account_suffix'], $password);
    }
    return false;
  }

  public function hash_password($password)
  {
    $salt = explode(" ", microtime())[1] * 1000000;
    for ($i = 1; $i <= 10; $i++) {
      $salt .= substr('0123456789abcdef', rand(0, 15), 1);
    }

    return "{SSHA}" . base64_encode(pack("H*", sha1($password . $salt)) . $salt);
  }

  public function update_password($username, $new_password)
  {
    if ($this->connection) {
      $new_password = array('userPassword' => $this->hash_password($new_password));

      $update = @ldap_mod_replace($this->connection, $this->config['user_id_key'] . '=' . $username . ',' . $this->config['account_suffix'], $new_password);

      $this->unbind();

      return $update;
    }
    return false;
  }

  public function unbind()
  {
    @ldap_unbind($this->connection);
    @ldap_close($this->connection);
  }
}