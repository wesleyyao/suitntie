<?php
/**
 * 一个简单的PHP SMTP 发送邮件类
 */

class Lib_Smtp {
    /**
     * @var string 邮件传输代理用户名
     * @access private
     */
    private $_userName;

    /**
     * @var string 邮件传输代理密码
     * @access private
     */
    private $_password;

    /**
     * @var string 邮件传输代理服务器地址
     * @access private
     */
    private $_sendServer;

    /**
     * @var int 邮件传输代理服务器端口
     * @access private
     */
    private $_port;

    /**
     * @var string 发件人
     * @access protected
     */
    protected $_from;

    /**
     * @var string 收件人
     * @access protected
     */
    protected $_to;

    /**
     * @var string 抄送
     * @access protected
     */
    protected $_cc;

    /**
     * @var string 秘密抄送
     * @access protected
     */
    protected $_bcc;

    /**
     * @var string 主题
     * @access protected
     */
    protected $_subject;

    /**
     * @var string 邮件正文
     * @access protected
     */
    protected $_body;

    /**
     * @var string 附件
     * @access protected
     */
    protected $_attachment;

    /**
     * @var reource socket资源
     * @access protected
     */
    protected $_socket;

    /**
     * @var reource 是否是安全连接
     * @access protected
     */
    protected $_isSecurity;

    /**
     * @var string 错误信息
     * @access protected
     */
    protected $_errorMessage;

    protected $_debug = false;
    /*输出调试信息*/
    private function debug($msg){
        if($this->_debug){
            echo $msg ,'<br>',"\n";
        }
    }
    public function setDebug($val=true){
        $this->_debug=$val;
    }

    /**
     * 设置邮件传输代理，如果是可以匿名发送有邮件的服务器，只需传递代理服务器地址就行
     * @access public
     * @param string $server 代理服务器的ip或者域名
     * @param string $username 认证账号
     * @param string $password 认证密码
     * @param int $port 代理服务器的端口，smtp默认25号端口
     * @param boolean $isSecurity 到服务器的连接是否为安全连接，默认false
     * @return boolean
     */
    public function setServer($server, $username="", $password="", $port=25, $isSecurity=false) {
        $this->_sendServer = $server;
        $this->_port = $port;
        $this->_isSecurity = $isSecurity;
        $this->_userName = empty($username) ? "" : base64_encode($username);
        $this->_password = empty($password) ? "" : base64_encode($password);
        return true;
    }

    /**
     * 设置发件人
     * @access public
     * @param string $from 发件人地址
     * @return boolean
     */
    public function setFrom($from) {
        $this->_from = $from;
        return true;
    }

    /**
     * 设置收件人，多个收件人，调用多次.
     * @access public
     * @param string $to 收件人地址
     * @return boolean
     */
    public function setReceiver($to) {
        if(isset($this->_to)) {
            if(is_string($this->_to)) {
                $this->_to = array($this->_to);
                $this->_to[] = $to;
                return true;
            }
            elseif(is_array($this->_to)) {
                $this->_to[] = $to;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            $this->_to = $to;
            return true;
        }
    }

    /**
     * 设置抄送，多个抄送，调用多次.
     * @access public
     * @param string $cc 抄送地址
     * @return boolean
     */
    public function setCc($cc) {
        if(isset($this->_cc)) {
            if(is_string($this->_cc)) {
                $this->_cc = array($this->_cc);
                $this->_cc[] = $cc;
                return true;
            }
            elseif(is_array($this->_cc)) {
                $this->_cc[] = $cc;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            $this->_cc = $cc;
            return true;
        }
    }

    /**
     * 设置秘密抄送，多个秘密抄送，调用多次
     * @access public
     * @param string $bcc 秘密抄送地址
     * @return boolean
     */
    public function setBcc($bcc) {
        if(isset($this->_bcc)) {
            if(is_string($this->_bcc)) {
                $this->_bcc = array($this->_bcc);
                $this->_bcc[] = $bcc;
                return true;
            }
            elseif(is_array($this->_bcc)) {
                $this->_bcc[] = $bcc;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            $this->_bcc = $bcc;
            return true;
        }
    }

    /**
     * 设置邮件附件，多个附件，调用多次
     * @access public
     * @param string $file 文件地址
     * @return boolean
     */
    public function addAttachment($file) {
        if(!file_exists($file)) {
            $this->_errorMessage = "file " . $file . " does not exist.";
            return false;
        }
        if(isset($this->_attachment)) {
            if(is_string($this->_attachment)) {
                $this->_attachment = array($this->_attachment);
                $this->_attachment[] = $file;
                return true;
            }
            elseif(is_array($this->_attachment)) {
                $this->_attachment[] = $file;
                return true;
            }
            else {
                return false;
            }
        }
        else {
            $this->_attachment = $file;
            return true;
        }
    }

    /**
     * 设置邮件信息
     * @access public
     * @param string $body 邮件主题
     * @param string $subject 邮件主体内容，可以是纯文本，也可是是HTML文本
     * @return boolean
     */
    public function setMail($subject, $body) {
        $this->_subject = $subject;
        $this->_body = base64_encode($body);
        return true;
    }

    /**
     * 发送邮件
     * @access public
     * @return boolean
     */
    public function send() {
        $command = $this->getCommand();

        $this->_isSecurity ? $this->socketSecurity() : $this->socket();

        foreach ($command as $value) {
            $result = $this->_isSecurity ? $this->sendCommandSecurity($value[0], $value[1]) : $this->sendCommand($value[0], $value[1]);
            if($result) {
                continue;
            }
            else{
                return false;
            }
        }

        //其实这里也没必要关闭，smtp命令：QUIT发出之后，服务器就关闭了连接，本地的socket资源会自动释放
        $this->_isSecurity ? $this->closeSecutity() : $this->close();
        return true;
    }

    /**
     * 返回错误信息
     * @return string
     */
    public function error(){
        if(!isset($this->_errorMessage)) {
            $this->_errorMessage = "";
        }
        return $this->_errorMessage;
    }

    /**
     * 返回mail命令
     * @access protected
     * @return array
     */
    protected function getCommand() {
        $separator = "----=_Part_" . md5($this->_from . time()) . uniqid(); //分隔符

        $command = array(
            array("HELO sendmail\r\n", 250)
        );
        if(!empty($this->_userName)){
            $command[] = array("AUTH LOGIN\r\n", 334);
            $command[] = array($this->_userName . "\r\n", 334);
            $command[] = array($this->_password . "\r\n", 235);
        }

        //设置发件人
        $command[] = array("MAIL FROM: <" . $this->_from . ">\r\n", 250);
        $header = "FROM: <" . $this->_from . ">\r\n";

        //设置收件人
        if(is_array($this->_to)) {
            $count = count($this->_to);
            for($i=0; $i<$count; $i++){
                $command[] = array("RCPT TO: <" . $this->_to[$i] . ">\r\n", 250);
                if($i == 0){
                    $header .= "TO: <" . $this->_to[$i] .">";
                }
                elseif($i + 1 == $count){
                    $header .= ",<" . $this->_to[$i] .">\r\n";
                }
                else{
                    $header .= ",<" . $this->_to[$i] .">";
                }
            }
        }
        else{
            $command[] = array("RCPT TO: <" . $this->_to . ">\r\n", 250);
            $header .= "TO: <" . $this->_to . ">\r\n";
        }

        //设置抄送
        if(isset($this->_cc)) {
            if(is_array($this->_cc)) {
                $count = count($this->_cc);
                for($i=0; $i<$count; $i++){
                    $command[] = array("RCPT TO: <" . $this->_cc[$i] . ">\r\n", 250);
                    if($i == 0){
                        $header .= "CC: <" . $this->_cc[$i] .">";
                    }
                    elseif($i + 1 == $count){
                        $header .= ",<" . $this->_cc[$i] .">\r\n";
                    }
                    else{
                        $header .= ",<" . $this->_cc[$i] .">";
                    }
                }
            }
            else{
                $command[] = array("RCPT TO: <" . $this->_cc . ">\r\n", 250);
                $header .= "CC: <" . $this->_cc . ">\r\n";
            }
        }

        //设置秘密抄送
        if(isset($this->_bcc)) {
            if(is_array($this->_bcc)) {
                $count = count($this->_bcc);
                for($i=0; $i<$count; $i++){
                    $command[] = array("RCPT TO: <" . $this->_bcc[$i] . ">\r\n", 250);
                    if($i == 0){
                        $header .= "BCC: <" . $this->_bcc[$i] .">";
                    }
                    elseif($i + 1 == $count){
                        $header .= ",<" . $this->_bcc[$i] .">\r\n";
                    }
                    else{
                        $header .= ",<" . $this->_bcc[$i] .">";
                    }
                }
            }
            else{
                $command[] = array("RCPT TO: <" . $this->_bcc . ">\r\n", 250);
                $header .= "BCC: <" . $this->_bcc . ">\r\n";
            }
        }

        //主题
        $header .= "Subject: " . $this->_subject ."\r\n";
        if(isset($this->_attachment)) {
            //含有附件的邮件头需要声明成这个
            $header .= "Content-Type: multipart/mixed;\r\n";
        }
        elseif(false){
            //邮件体含有图片资源的需要声明成这个
            $header .= "Content-Type: multipart/related;\r\n";
        }
        else{
            //html或者纯文本的邮件声明成这个
            $header .= "Content-Type: multipart/alternative;\r\n";
        }

        //邮件头分隔符
        $header .= "\t" . 'boundary="' . $separator . '"';
        $header .= "\r\nMIME-Version: 1.0\r\n";
        $header .= "\r\n--" . $separator . "\r\n";
        $header .= "Content-Type:text/html; charset=utf-8\r\n";
        $header .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $header .= $this->_body . "\r\n";
        $header .= "--" . $separator . "\r\n";

        //加入附件
        if(isset($this->_attachment) && !empty($this->_attachment)){
            if(is_array($this->_attachment)){
                $count = count($this->_attachment);
                for($i=0; $i<$count; $i++){
                    $header .= "\r\n--" . $separator . "\r\n";
                    $header .= "Content-Type: " . $this->getMIMEType($this->_attachment[$i]) . '; name="' . basename($this->_attachment[$i]) . '"' . "\r\n";
                    $header .= "Content-Transfer-Encoding: base64\r\n";
                    $header .= 'Content-Disposition: attachment; filename="' . basename($this->_attachment[$i]) . '"' . "\r\n";
                    $header .= "\r\n";
                    $header .= $this->readFile($this->_attachment[$i]);
                    $header .= "\r\n--" . $separator . "\r\n";
                }
            }
            else{
                $header .= "\r\n--" . $separator . "\r\n";
                $header .= "Content-Type: " . $this->getMIMEType($this->_attachment) . '; name="' . basename($this->_attachment) . '"' . "\r\n";
                $header .= "Content-Transfer-Encoding: base64\r\n";
                $header .= 'Content-Disposition: attachment; filename="' . basename($this->_attachment) . '"' . "\r\n";
                $header .= "\r\n";
                $header .= $this->readFile($this->_attachment);
                $header .= "\r\n--" . $separator . "\r\n";
            }
        }

        //结束邮件数据发送
        $header .= "\r\n.\r\n";


        $command[] = array("DATA\r\n", 354);
        $command[] = array($header, 250);
        $command[] = array("QUIT\r\n", 221);

        return $command;
    }

    /**
     * 发送命令
     * @access protected
     * @param string $command 发送到服务器的smtp命令
     * @param int $code 期望服务器返回的响应吗
     * @return boolean
     */
    protected function sendCommand($command, $code) {
        //debug('Send command:' . $command . ',expected code:' . $code );
        //发送命令给服务器
        try{
            if(socket_write($this->_socket, $command, strlen($command))){

                //当邮件内容分多次发送时，没有$code，服务器没有返回
                if(empty($code))  {
                    return true;
                }

                //读取服务器返回
                $data = trim(socket_read($this->_socket, 1024));
                //debug( 'response:' . $data);

                if($data) {
                    $pattern = "/^".$code."/";
                    if(preg_match($pattern, $data)) {
                        return true;
                    }
                    else{
                        $this->_errorMessage = "Error:" . $data . "|**| command:";
                        return false;
                    }
                }
                else{
                    $this->_errorMessage = "Error:" . socket_strerror(socket_last_error());
                    return false;
                }
            }
            else{
                $this->_errorMessage = "Error:" . socket_strerror(socket_last_error());
                return false;
            }
        }catch(Exception $e) {
            $this->_errorMessage = "Error:" . $e->getMessage();
        }
    }

    /**
     * 发送命令
     * @access protected
     * @param string $command 发送到服务器的smtp命令
     * @param int $code 期望服务器返回的响应吗
     * @return boolean
     */
    protected function sendCommandSecurity($command, $code) {
        //debug('Send command:' . $command . ',expected code:' . $code );
        try {
            if(fwrite($this->_socket, $command)){
                //当邮件内容分多次发送时，没有$code，服务器没有返回
                if(empty($code))  {
                    return true;
                }
                //读取服务器返回
                $data = trim(fread($this->_socket, 1024));
                //debug( 'response:' . $data );

                if($data) {
                    $pattern = "/^".$code."/";
                    if(preg_match($pattern, $data)) {
                        return true;
                    }
                    else{
                        $this->_errorMessage = "Error:" . $data . "|**| command:";
                        return false;
                    }
                }
                else{
                    return false;
                }
            }
            else{
                $this->_errorMessage = "Error: " . $command . " send failed";
                return false;
            }
        }catch(Exception $e) {
            $this->_errorMessage = "Error:" . $e->getMessage();
        }
    }

    /**
     * 读取附件文件内容，返回base64编码后的文件内容
     * @access protected
     * @param string $file 文件
     * @return mixed
     */
    protected function readFile($file) {
        if(file_exists($file)) {
            $file_obj = file_get_contents($file);
            return base64_encode($file_obj);
        }
        else {
            $this->_errorMessage = "file " . $file . " dose not exist";
            return false;
        }
    }

    /**
     * 获取附件MIME类型
     * @access protected
     * @param string $file 文件
     * @return mixed
     */
    protected function getMIMEType($file) {
        if(file_exists($file)) {
            $mime = mime_content_type($file);
            if(! preg_match("/gif|jpg|png|jpeg/", $mime)){
                $mime = "application/octet-stream";
            }
            return $mime;
        }
        else {
            return false;
        }
    }

    /**
     * 建立到服务器的网络连接
     * @access private
     * @return boolean
     */
    private function socket() {
        //创建socket资源
        $this->_socket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('tcp'));

        if(!$this->_socket) {
            $this->_errorMessage = socket_strerror(socket_last_error());
            return false;
        }

        socket_set_block($this->_socket);//设置阻塞模式

        //连接服务器
        if(!socket_connect($this->_socket, $this->_sendServer, $this->_port)) {
            $this->_errorMessage = socket_strerror(socket_last_error());
            return false;
        }
        $str = socket_read($this->_socket, 1024);
        if(!strpos($str, "220")){
            $this->_errorMessage = $str;
            return false;
        }

        return true;
    }

    /**
     * 建立到服务器的SSL网络连接
     * @access private
     * @return boolean
     */
    private function socketSecurity() {
        $remoteAddr = "tcp://" . $this->_sendServer . ":" . $this->_port;
        $this->_socket = stream_socket_client($remoteAddr, $errno, $errstr, 30);
        if(!$this->_socket){
            $this->_errorMessage = $errstr;
            return false;
        }

        //设置加密连接，默认是ssl，如果需要tls连接，可以查看php手册stream_socket_enable_crypto函数的解释
        stream_socket_enable_crypto($this->_socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);

        stream_set_blocking($this->_socket, 1); //设置阻塞模式
        $str = fread($this->_socket, 1024);
        if(!strpos($str, "220")){
            $this->_errorMessage = $str;
            return false;
        }

        return true;
    }

    /**
     * 关闭socket
     * @access private
     * @return boolean
     */
    private function close() {
        if(isset($this->_socket) && is_object($this->_socket)) {
            $this->_socket->close();
            return true;
        }
        $this->_errorMessage = "No resource can to be close";
        return false;
    }

    /**
     * 关闭安全socket
     * @access private
     * @return boolean
     */
    private function closeSecutity() {
        if(isset($this->_socket) && is_object($this->_socket)) {
            stream_socket_shutdown($this->_socket, STREAM_SHUT_WR);
            return true;
        }
        $this->_errorMessage = "No resource can to be close";
        return false;
    }

    public function channelMail()
    {
        $mail = new Lib_Smtp();
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->setServer("hwsmtp.exmail.qq.com", "wesley@suitntie.cn", "EZr4ihhsPyCe3Jze",465,true); //参数1（qq邮箱使用smtp.qq.com，qq企业邮箱使用smtp.exmail.qq.com），参数2（邮箱登陆账号），参数3（邮箱登陆密码，也有可能是独立密码，就是开启pop3/smtp时的授权码），参数4（默认25，腾云服务器屏蔽25端口，所以用的465），参数5（是否开启ssl，用465就得开启）//$mail->setServer("XXXXX", "joffe@XXXXX", "XXXXX", 465, true);
        $mail->setFrom("wesley@suitntie.cn"); //发送者邮箱
        $mail->setReceiver("wesleyaijx@gmail.com"); //接收者邮箱
        $mail->addAttachment(""); //Attachment 附件，不用可注释
        $mail->setMail("测试一下", "<b>测试</b>"); //标题和内容
        $mail->send();//可以var_dump一下，发送成功会返回true，失败false
    }
}