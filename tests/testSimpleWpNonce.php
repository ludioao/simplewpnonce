<?php

/**
 * Created by PhpStorm.
 * User: ludio
 * Date: 19/08/16
 * Time: 15:46
 */

use Ludioao\SimpleWpNonce\SimpleWpNonce as SimpleWpNonce;
use Brain\Monkey as Monkey;
use Brain\Monkey\Functions as Functions;
use \PHPUnit_Framework_TestCase;

class testSimpleWpNonce extends \PHPUnit_Framework_TestCase
{
    /*
     * Start setup for PhpUnit and Monkey.
     */
    protected function setUp() {
        parent::setUp();
        Monkey::setUpWP();
    }

    protected function tearDown() {
        Monkey::setUpWP();
        parent::tearDown();
    }

    protected $nonceValForTest = 'myNonceTvalue';


    /**
     * Test check admin referral.
     */
    function testCheckAdminReferral() {

        Functions::expect( 'wp_create_nonce' )
            ->once()
            ->with( 'doing_admin_job_for_test' )
            ->andReturn( 'some_value_here_for_test_too' );

        Functions::expect( 'check_admin_referer' )
            ->once()
            ->with( 'doing_admin_job_for_test', '_wpnonce' )
            ->andReturn( 1 );

        $simpleNonce                = new SimpleWpNonce( 'doing_admin_job_for_test' );
        $nonceValue                 = $simpleNonce->createNonce();
        $_REQUEST['_wpnonce']       = $nonceValue;

        $this->assertEquals( $simpleNonce->checkAdminReferral(), 1 );
    }

    /*
     * Test for "Create nonce verify".
     */
    function testNonceCreateVerify() {

        /*
         * Test for some functions.
         */

        Functions::expect( 'wp_create_nonce' )
            ->once()
            ->with( 'doing_create_and_verify_test' )
            ->andReturn( 'something_nonce' );

        /*
         * Wp Verify Nonce.
         *
         * Create a wp verify nonce,
         * we expect true value.
         */
        Functions::expect( 'wp_verify_nonce' )
            ->once()
            ->with( 'something_nonce', 'doing_create_and_verify_test' )
            ->andReturn( 1 );

        /*
         * Wp Verify Nonce
         *
         * Create a wp verify nonce
         * we expect false value.
         *
         * Cause the nonce doesnt exists.
         */
        Functions::expect( 'wp_verify_nonce' )
            ->once()
            ->with( 'something_nonce_error', 'doing_create_and_verify_test' )
            ->andReturn( false );


        /*
         * Instantiante a nonce.
         */
        $simpleNonce         = new SimpleWpNonce( 'doing_create_and_verify_test' );

        // Get the nonce value
        $nonceValue          = $simpleNonce->createNonce();

        // Get the verify nonce. (when True)
        $none_accepted       = $simpleNonce->verifyNonce( $nonceValue );

        // Get the verify nonce. (when False)
        $none_rejected       = $simpleNonce->verifyNonce( $nonceValue . '_error' );

        $this->assertEquals( $none_accepted, 1 );
        $this->assertFalse( $none_rejected, 0 );
    }


    /*
     * Test for a "Create nonce field";
     *
     */
    function testCreateNonceField() {

        /*
         * Test a create for nonce field.
         *
         */


        Functions::expect( 'wp_verify_nonce' )
            ->once()
            ->with( $this->nonceValForTest, 'clean_field' )
            ->andReturn( 1 );


        Functions::expect( 'wp_nonce_field' )
            ->once()
            ->with( 'clean_field', '_wpnonce', false, false )
            ->andReturn( '<input type="hidden" id="_wpnonce" name="_wpnonce" value="'. $this->nonceValForTest .'" />' );

        /*
         * Check a create for nonce.
         */
        $simpleNonce            = new SimpleWpNonce( 'clean_field' );
        $htmlInput              = $simpleNonce->createNonceField( '_wpnonce', false, false );

        /*
         * Simulate HTML element.
         */
        $dom              = new DOMDocument();
        $dom->loadHTML( $htmlInput );

        $inputs    = $dom->getElementsByTagName( 'input' )->item( 0 );
        $nonce_val = $inputs->getAttribute( 'value' );

        /*
         * Get the value attribute.
         */
        $this->assertEquals( $simpleNonce->verifyNonce( $nonce_val ), 1 );
    }



    /*
     * Test for CreateNonceUrl.
     *
     *
     *
     */
    function testCreateNonceUrl() {

        Functions::expect( 'wp_nonce_url' )
            ->once()
            ->with( 'http://inpsyde.com', 'clean_url', '_wpnonce' )
            ->andReturn( 'http://inpsyde.com?_wpnonce=something_for_test_purpose' );


        Functions::expect( 'wp_verify_nonce' )
            ->once()
            ->with( 'something_for_test_purpose', 'clean_url' )
            ->andReturn( 1 );


        $simpleNonce    = new SimpleWpNonce( 'clean_url' );
        $url            = $simpleNonce->createNonceUrl( "http://inpsyde.com" );


        $query          = parse_url( $url );
        /*
         * Parse _wpnonce from URL.
         *
         */
        $q              = array( '_wpnonce' );

        /*
         * Parse string.
         */
        parse_str( $query['query'], $q );

        $valueExpected = str_replace( '"', '', $q['_wpnonce'] );

        $this->assertEquals( $simpleNonce->verifyNonce( $valueExpected ), 1 );
    }



}