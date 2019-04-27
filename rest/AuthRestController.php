<?php
class AuthRestController extends WP_REST_Controller
{
    private $hashids;
    public function __construct($hashids)
    {
        $this->hashids = $hashids;
    }

    public function register_routes($namespace)
    {

        $path = 'register';

        register_rest_route($namespace, '/register', [
            array(
                'methods'             => 'POST',
                'callback'            => array($this, 'register'),
                'permission_callback' => array($this, 'hasPermission')
            )
        ]);

        register_rest_route($namespace, '/register/validate', [
            array(
                'methods'             => 'POST',
                'callback'            => array($this, 'registerValidate'),
                'permission_callback' => array($this, 'hasPermission')
            )
        ]);
    }

    public function hasPermission($request)
    {
        return true;
    }

    public function register($request)
    {
        $cell = $request->get_param('cell');
        global $wpdb;
        $tablename = $wpdb->prefix . 'cells';

        $rand = wp_rand(1000, 9999);

        $cell_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename WHERE cells_mobile = %s", $cell));
        $id = false;
        $time = current_time('mysql');
        if (null !== $cell_row) {
            $wpdb->query(
                $wpdb->prepare("UPDATE $tablename SET cells_smscode = %s , cells_smsdate = %s, cells_validated = 0 WHERE cells_mobile = %s", $rand, $time, $cell)
            );
            $id = $cell_row->cells_id;
        } else {
            $wpdb->insert(
                $tablename,
                array(
                    'cells_mobile' => $cell,
                    'cells_smscode' => $rand,
                    'cells_register_date' => $time,
                    'cells_smsdate' => $time,
                    'cells_validated' => 0
                ),
                array('%s', '%s')
            );
            $id = $wpdb->insert_id;
        }
        //$hash = $this->hashids->encode($id, $cell, $rand, wp_rand( 1, 100000));
        if ($this->sendSMS($cell, $rand)) {
            $resp = array('success' => true, 'cell' => $cell);
        } else {
            $resp = array('success' => false, 'error' => $this->err_text, 'code' => $this->err_code);
        }

        return new WP_REST_Response($resp, 200);
    }

    public function registerValidate($request)
    {
        $cell = $request->get_param('cell');
        $code = $request->get_param('code');
        global $wpdb;
        $tablename = $wpdb->prefix . 'cells';

        $cell_row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $tablename WHERE cells_mobile = %s and cells_smscode = %s", $cell, $code));

        if (null !== $cell_row) {
            $time = current_time('mysql');
            $wpdb->query(
                $wpdb->prepare("UPDATE $tablename SET cells_lastlogin = %s, cells_validated = 1 WHERE cells_mobile = %s", $time, $cell)
            );
            $id = $cell_row->cells_id;
            $rand = $cell_row->cells_smscode;
            // $token = $this->hashids->encode($id, $cell, $rand, wp_rand( 1, 100000));
            $token = $this->hashids->encode($id, (int)$cell, $rand, wp_rand(1, 100000));
            $resp = array('success' => true, 'token' => $token);
        } else {
            $resp = array('success' => false);
        }

        return new WP_REST_Response($resp, 200);
    }

    private $err_code;
    private $err_text;

    private function sendSMS($number, $text)
    {
        $sms = new SoapClient(BALINO_SMS_URL,
            array(
                'tra
        ce' => 1
            ));
        try {

            $smsid = $sms->send_sms(BALINO_SMS_USER, BALINO_SMS_PASS, BALINO_SMS_NUMBER, $number, $text);
            // print_r( $smsid );
            //echo $my_class->__getLastRequest() . "\n";
            return true;
        } catch (SoapFault $sf) {
            $this->err_code = $sf->faultcode;

            $this->err_text = $sf->faultstring;
            return false;
        }
    }
}
