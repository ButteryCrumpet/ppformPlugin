<?php
/*
$data = array(
    to =>
    from =>
    CC =>
    Bcc =>
    Body =>
    Subject =>
)
*/

class Mail {

    public $to;
    public $from;
    public $subject;
    public $message;
    public $headers;

    private $errors;
    private $valid = true;

    function __construct($data) {
        $this->to = $this->sanitizeEmail($data['to']);
        $this->sanitizeFrom($data['from']);
        $this->generateHeaders($data);
        $this->subject = $data['subject'];
        $this->message = $data['message'];
    }

    private function sanitizeFrom($from) {
        //checkdnsrr?
        if ($this->injectionSafe($from)) {
            $this->from = $this->sanitizeEmail($from);
            return true;
        }
    }

    private function generateHeaders($data) {
        $Bcc = $this->injectionSafe($data['Bcc']) ? $data['Bcc'] : '';
        $CC = $this->injectionSafe($data['CC']) ? $data['CC'] : '';

        $headers = '';
        $headers .= "MIME-Version: 1.0" . "\r\n";
        //$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: ". $this->from ."\r\n";
        $headers .= "X-Mailer: PHP/". phpversion();
        $headers .= "CC: ". $Cc ."\r\n";
        $headers .= "Bcc: ". $Bcc ."\r\n";

        $this->headers = $headers;
    }

    private function injectionSafe($data) {
        if (preg_match("/[\r\n]/", $data)) {
            $this->throwError("Newlines in headers: ".$data);
            return false;
        }
        return true;
    }

    private function sanitizeEmail($email) {
        if (filter_var($email, FILTER_SANITIZE_EMAIL)) {
            return filter_var($email, FILTER_SANITIZE_EMAIL);
        } else {
            $this->throwError("Invalid Email: ".$email);
            return $email;
        }
    }

    private function throwError($type) {
        $this->valid = false;
        $this->errors[] = $type;
    }

    public function send() {
        if (!$this->valid) {
            print_r($this->errors);
            return false;
        } else {
            $wrapped_message = wordwrap($this->message, 70, "\r\n");
            $sent = mail($this->to, $this->subject, $wrapped_message, $this->headers);
            if ($sent) {
                return true;
            } else {
                return false;
            }
        }
    }
}