<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitorLog extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'url',
        'referer',
        'method',
        'country',
        'city',
        'device_type',
        'browser',
        'os',
        'session_duration',
        'page_views',
        'is_bot',
    ];

    protected $casts = [
        'is_bot' => 'boolean',
        'session_duration' => 'integer',
        'page_views' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Check if user agent is a bot
     */
    public static function isBot($userAgent)
    {
        if (empty($userAgent)) {
            return false;
        }

        $bots = [
            'googlebot', 'bingbot', 'slurp', 'duckduckbot', 'baiduspider',
            'yandexbot', 'sogou', 'exabot', 'facebot', 'ia_archiver',
            'facebookexternalhit', 'twitterbot', 'rogerbot', 'linkedinbot',
            'embedly', 'quora link preview', 'showyoubot', 'outbrain',
            'pinterest', 'slackbot', 'vkShare', 'W3C_Validator', 'whatsapp',
            'flipboard', 'tumblr', 'bitlybot', 'skypeuripreview', 'nuzzel',
            'redditbot', 'applebot', 'flipboard', 'tweetmemeBot', 'pinterestbot',
            'bitlybot', 't.co/', 'calendlybot', 'discordbot', 'telegrambot',
        ];

        $userAgentLower = strtolower($userAgent);
        foreach ($bots as $bot) {
            if (strpos($userAgentLower, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect device type from user agent
     */
    public static function detectDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgentLower = strtolower($userAgent);

        // Check for mobile devices
        if (preg_match('/mobile|android|iphone|ipod|blackberry|opera mini|opera mobi|skyfire|maemo|windows phone|palm|iemobile|symbian|symbianos|fennec/i', $userAgentLower)) {
            return 'mobile';
        }

        // Check for tablets
        if (preg_match('/tablet|ipad|playbook|silk/i', $userAgentLower)) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Detect browser from user agent
     */
    public static function detectBrowser($userAgent)
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgentLower = strtolower($userAgent);

        if (strpos($userAgentLower, 'chrome') !== false && strpos($userAgentLower, 'edg') === false) {
            return 'Chrome';
        }
        if (strpos($userAgentLower, 'edg') !== false) {
            return 'Edge';
        }
        if (strpos($userAgentLower, 'firefox') !== false) {
            return 'Firefox';
        }
        if (strpos($userAgentLower, 'safari') !== false && strpos($userAgentLower, 'chrome') === false) {
            return 'Safari';
        }
        if (strpos($userAgentLower, 'opera') !== false || strpos($userAgentLower, 'opr') !== false) {
            return 'Opera';
        }
        if (strpos($userAgentLower, 'msie') !== false || strpos($userAgentLower, 'trident') !== false) {
            return 'IE';
        }

        return 'Other';
    }

    /**
     * Detect OS from user agent
     */
    public static function detectOS($userAgent)
    {
        if (empty($userAgent)) {
            return 'unknown';
        }

        $userAgentLower = strtolower($userAgent);

        if (strpos($userAgentLower, 'windows') !== false) {
            return 'Windows';
        }
        if (strpos($userAgentLower, 'mac') !== false || strpos($userAgentLower, 'darwin') !== false) {
            return 'macOS';
        }
        if (strpos($userAgentLower, 'linux') !== false) {
            return 'Linux';
        }
        if (strpos($userAgentLower, 'android') !== false) {
            return 'Android';
        }
        if (strpos($userAgentLower, 'iphone') !== false || strpos($userAgentLower, 'ipad') !== false) {
            return 'iOS';
        }

        return 'Other';
    }
}
