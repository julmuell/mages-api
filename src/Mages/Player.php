<?php

namespace Mages;

use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Player extends Model
{
  // private $id;

  // private $username;

  public $incrementing = false;

  public $keyType = 'string';


  function __construct(string $username, string $password, string $alias = null, string $fullname = null)
  {
    $this->id = Uuid::getFactory()->uuid4();

    $this->setUsername($username);
    $this->setPassword($password);
    $this->setAlias($alias);
    $this->setFullname($fullname);
  }

  public function getId()
  {
    return $this->id;
  }

  public function setUsername($username)
  {
    if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
      throw new InvalidArgumentException('Invalid email format');
    }

    $this->username = $username;
  }

  public function getUsername()
  {
    return $this->username;
  }

  public function setAlias($alias)
  {
    $this->alias = $alias;
  }

  public function getAlias() {
    return $this->alias;
  }

  public function setFullname(string $fullname) {
    $this->fullname = $fullname;
  }

  public function getFullname() {
    return $this->fullname;
  }

  public function setPassword(string $password) {
    $this->password = password_hash($password, PASSWORD_BCRYPT);
  }

  public function getPassword() {
    return $this->password;
  }

  public function jsonSerialize()
  {
    return array_diff_key(parent::jsonSerialize(), array_flip((array) 'password'));
  }

  public function equals(Player $player)
  {
    return $this->getId() === $player->getId();
  }

  public function teams()
  {
    return $this->belongsToMany('Mages\Team', 'team_player');
  }
}
