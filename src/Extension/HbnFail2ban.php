<?php

namespace HBN\Plugin\System\HbnFail2ban\Extension;

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Event\User\LoginFailureEvent;
use Joomla\Event\SubscriberInterface;
use Joomla\CMS\Authentication\Authentication;
use Joomla\CMS\Authentication\AuthenticationResponse;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Log\Logger\SyslogLogger;
use Joomla\CMS\Log\LogEntry;

class HbnFail2ban extends CMSPlugin implements SubscriberInterface
{
    public static function getSubscribedEvents() : array {
        return [
            'onUserLoginFailure' => 'logFailedLogin'
        ];
    }

    public function logFailedLogin(LoginFailureEvent $event) : void {

        $response = $event->getAuthenticationResponse();

        // do not log canceled auths
        if ($response['status'] == Authentication::STATUS_SUCCESS) {
            return;
        }

        $app = $this->getApplication();
        $ip  = $app->getInput()->server->get('REMOTE_ADDR');

        $syslogOptions = [
            'sys_ident'      => 'jauthlog',
            'sys_add_pid'    => true,
            'sys_use_stderr' => false,
            'sys_facility'   => LOG_AUTH
        ];
        $syslog   = new SyslogLogger($syslogOptions);

        $username = $response['username'];
        $message  = $response['error_message'];
        $admin    = $app->isClient('administrator') ? "ADMIN " : "";

        $logentry = new LogEntry("WARNING {$admin}login failed for {$username}: {$message} [client {$ip}]", Log::WARNING, getenv('USER'));
        $syslog->addEntry($logentry);
    }
}
