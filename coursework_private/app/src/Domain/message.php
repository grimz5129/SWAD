<?php

/**
 * message.php
 *
 * Doctrine metadata file for message table
 */
namespace Coursework\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 */
class message {

    /**
     * @ORM\ID
     * @ORM\Column(type="string")
     */
    protected $srcMSISDN;

    /**
     * @ORM\Column(type="string")
     */
    protected $destMSISDN;

    /**
     * @ORM\ID
     * @ORM\Column(type="string")
     */
    protected $receivedDate;

    /**
     * @ORM\Column(type="string")
     */
    protected $bearer;

    /**
     * @ORM\Column(type="string")
     */
    protected $messageRef;


    /**
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $switch1;

    /**
     * @ORM\Column(type="integer")
     */
    protected $switch2;

    /**
     * @ORM\Column(type="integer")
     */
    protected $switch3;

    /**
     * @ORM\Column(type="integer")
     */
    protected $switch4;

    /**
     * @ORM\Column(type="integer")
     */
    protected $fan;

    /**
     * @ORM\Column(type="integer")
     */
    protected $forward;

    /**
     * @ORM\Column(type="integer")
     */
    protected $reverse;

    /**
     * @ORM\Column(type="integer")
     */
    protected $heater;


    /**
     * @ORM\Column(type="decimal")
     */
    protected $temperature;

    /**
     * @ORM\Column(type="integer")
     */
    protected $keypad;


    /**
     * @return mixed
     */
    public function getBearer()
    {
        return $this->bearer;
    }

    /**
     * @return mixed
     */
    public function getDestMSISDN()
    {
        return $this->destMSISDN;
    }

    /**
     * @return mixed
     */
    public function getFan()
    {
        return $this->fan;
    }

    /**
     * @return mixed
     */
    public function getForward()
    {
        return $this->forward;
    }

    /**
     * @return mixed
     */
    public function getHeater()
    {
        return $this->heater;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKeypad()
    {
        return $this->keypad;
    }

    /**
     * @return mixed
     */
    public function getMessageRef()
    {
        return $this->messageRef;
    }

    /**
     * @return mixed
     */
    public function getReceivedDate()
    {
        return $this->receivedDate;
    }

    /**
     * @return mixed
     */
    public function getReverse()
    {
        return $this->reverse;
    }

    /**
     * @return mixed
     */
    public function getSrcMSISDN()
    {
        return $this->srcMSISDN;
    }

    /**
     * @return mixed
     */
    public function getSwitch1()
    {
        return $this->switch1;
    }

    /**
     * @return mixed
     */
    public function getSwitch2()
    {
        return $this->switch2;
    }

    /**
     * @return mixed
     */
    public function getSwitch3()
    {
        return $this->switch3;
    }

    /**
     * @return mixed
     */
    public function getSwitch4()
    {
        return $this->switch4;
    }

    /**
     * @return mixed
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @param mixed $bearer
     */
    public function setBearer($bearer)
    {
        $this->bearer = $bearer;
    }

    /**
     * @param mixed $destMSISDN
     */
    public function setDestMSISDN($destMSISDN)
    {
        $this->destMSISDN = $destMSISDN;
    }

    /**
     * @param mixed $fan
     */
    public function setFan($fan)
    {
        $this->fan = $fan;
    }

    /**
     * @param mixed $forward
     */
    public function setForward($forward)
    {
        $this->forward = $forward;
    }

    /**
     * @param mixed $heater
     */
    public function setHeater($heater)
    {
        $this->heater = $heater;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $keypad
     */
    public function setKeypad($keypad)
    {
        $this->keypad = $keypad;
    }

    /**
     * @param mixed $messageRef
     */
    public function setMessageRef($messageRef)
    {
        $this->messageRef = $messageRef;
    }

    /**
     * @param mixed $receivedDate
     */
    public function setReceivedDate($receivedDate)
    {
        $this->receivedDate = $receivedDate;
    }

    /**
     * @param mixed $reverse
     */
    public function setReverse($reverse)
    {
        $this->reverse = $reverse;
    }

    /**
     * @param mixed $srcMSISDN
     */
    public function setSrcMSISDN($srcMSISDN)
    {
        $this->srcMSISDN = $srcMSISDN;
    }

    /**
     * @param mixed $switch1
     */
    public function setSwitch1($switch1)
    {
        $this->switch1 = $switch1;
    }

    /**
     * @param mixed $switch2
     */
    public function setSwitch2($switch2)
    {
        $this->switch2 = $switch2;
    }

    /**
     * @param mixed $switch3
     */
    public function setSwitch3($switch3)
    {
        $this->switch3 = $switch3;
    }

    /**
     * @param mixed $switch4
     */
    public function setSwitch4($switch4)
    {
        $this->switch4 = $switch4;
    }

    /**
     * @param mixed $temperature
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    }
}