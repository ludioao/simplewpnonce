<?php
/**
 * Created by PhpStorm.
 * User: ludio
 * Date: 19/08/16
 * Time: 15:07
 */

namespace Ludioao\SimpleWpNonce;

if ( ! defined ('HOUR_IN_SECOND') )
    define( 'HOUR_IN_SECOND', 3600 );

if ( ! defined ( 'DAY_IN_SECOND') )
    define( 'DAY_IN_SECOND', 86400 );

class SimpleWpNonce
{

    protected $currentAction;

    protected $expireTime;


    public function __construct( $currentAction = null, $timeToExpire = DAY_IN_SECOND )
    {
        $this->currentAction = $currentAction;
        $this->expireTime = $timeToExpire;
    }

    /**
     * @return null
     */
    public function getCurrentAction()
    {
        return $this->currentAction;
    }

    /**
     * @return int
     */
    public function getExpireTime()
    {
        return $this->expireTime;
    }

    /**
     * @param null $currentAction
     */
    public function setCurrentAction($currentAction)
    {
        $this->currentAction = $currentAction;
    }

    /**
     * @param int $expireTime
     */
    public function setExpireTime($expireTime)
    {
        $this->expireTime = $expireTime;
    }


    /**
     *
     * Display message and confirm
     * this action is being taken
     */
    public function displayErrorMessage()
    {
        \wp_nonce_ays( $this->getCurrentAction() ) ;
    }



}