<?php
/**
 * Created by PhpStorm.
 * User: ludio
 * Date: 19/08/16
 * Time: 15:07
 */

namespace Ludioao\SimpleWpNonce;

/*
 * Custom defines for Wordpress. (:
 *
 * Wordpress define by default Hour and Day in seconds.
 * But we will define again if it not exists.
 */

if ( ! defined ('HOUR_IN_SECONDS') )
    define( 'HOUR_IN_SECONDS', 3600 );

if ( ! defined ( 'DAY_IN_SECONDS') )
    define( 'DAY_IN_SECONDS', 86400 );

if ( !defined ( 'WPNONCE_FIELD') )
    define( 'WPNONCE_FIELD',  '_wpnonce' );


/*
 * Create class.
 */
class SimpleWpNonce
{

    // Get current action
    protected $currentAction;

    // Get current time expire.
    protected $expireTime;


    public function __construct( $currentAction = null, $timeToExpire = DAY_IN_SECONDS )
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
     * Check if is a valid nonce.
     * @return boolean
     */
    public function createNonce()
    {
        $this->setHook();
        $nonce = \wp_create_nonce( $this->getCurrentAction() );
        $this->removeHook();
        return $nonce ;
    }


    /**
     * Check if is a valid nonce.
     * @param $nonce
     * @return boolean
     */
    public function verifyNonce($nonce )
    {
        $this->setHook();
        $isValid = \wp_verify_nonce( $nonce , $this->getCurrentAction() );
        $this->removeHook();

        return $isValid;
    }

    /**
     * Check if expire is the same as default WordPress
     *
     * @return bool
     */
    private function canHook()
    {

        return
            (DAY_IN_SECONDS !== $this->getExpireTime() );
    }

    /**
     *  This function set a hook to nonce_life
     *
     * Nonce_life is
     */
    protected function setHook()
    {
        if ( $this->canHook() )
            \add_filter( 'nonce_life', [ $this, 'set_expire' ], 1 );
    }

    /**
     *
     * This function remove hook from `nonce_life`
     * when nonce is generated or if can hook, in this case, its verified.
     */
    protected function removeHook()
    {
        if ( $this->canHook() )
            \add_filter( 'nonce_life', [ $this, 'set_expire' ], 1 );
    }


    /**
     *
     * Create a nonce url.
     * @param $actionUrl
     * @param string $name
     * @return mixed
     */
    public function createNonceUrl($actionUrl, $name = WPNONCE_FIELD )
    {
        $this->setHook();
        $nonceUrl = \wp_nonce_url( $actionUrl , $this->getCurrentAction() , $name ) ;
        $this->removeHook();
        return $nonceUrl;
    }


    /**
     *
     * Create a nonce field.
     *
     * @param string $name
     * @param bool $referer
     * @param bool $echo
     * @return nonce_field
     */
    public function createNonceField($name = WPNONCE_FIELD, $referer = true, $echo = true )
    {
        $this->setHook();
        $nonceField = \wp_nonce_field( $this->getCurrentAction(), $name, $referer, $echo );
        $this->removeHook();
        return $nonceField;
    }


    /**
     * Check admin referral
     * @param string $queryArg
     * @return mixed
     */
    public function checkAdminReferral ($queryArg = WPNONCE_FIELD )
    {
        $this->setHook();
        $isValid = \check_admin_referer ( $this->getCurrentAction(), $queryArg );
        $this->removeHook();
        return $isValid;
    }


    /**
     * Check jax referrer
     * @param bool $queryArg
     * @param bool $die
     * @return boolean
     */
    public function checkAjaxReferer($queryArg = false, $die = true )
    {
        $this->setHook();
        $isValid = \check_ajax_referer( $this->getCurrentAction() , $queryArg, $die );
        $this->removeHook();

        return $isValid;
    }

    /**
     *
     * Get the time-dependent variable
     * for a nonce creation.
     *
     * @return int
     */
    public function tick()
    {
        return \wp_nonce_tick();
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