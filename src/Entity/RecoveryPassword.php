<?php
declare(strict_types=1);

namespace App\Entity;

use App\Validator as BdmAssert;

class RecoveryPassword
{
    /**
     * @BdmAssert\PasswordRules()
     */
    private $password;

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }


}
