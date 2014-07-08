<?php
/**
 * copyright 2011 Stephen Just <stephenjust@users.sf.net>
 *           2014 Daniel Butum <danibutum at gmail dot com>
 * This file is part of stkaddons
 *
 * stkaddons is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * stkaddons is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with stkaddons.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Inspired from https://github.com/brandonwamboldt/utilphp/
 */
class Util
{
    /**
     * A constant representing the number of seconds in a minute
     * @var int
     */
    const SECONDS_IN_A_MINUTE = 60;

    /**
     * A constant representing the number of seconds in an hour
     * @var int
     */
    const SECONDS_IN_A_HOUR = 3600;

    const SECONDS_IN_AN_HOUR = 3600;

    /**
     * A constant representing the number of seconds in a day
     * @var int
     */
    const SECONDS_IN_A_DAY = 86400;

    /**
     * A constant representing the number of seconds in a week
     * @var int
     */
    const SECONDS_IN_A_WEEK = 604800;

    /**
     * A constant representing the number of seconds in a month (30 days),
     * @var int
     */
    const SECONDS_IN_A_MONTH = 2592000;

    /**
     * A constant representing the number of seconds in a year (365 days),
     * @var int
     */
    const SECONDS_IN_A_YEAR = 31536000;

    const SALT_LENGTH = 32;

    const SHA256_LENGTH = 64;

    const MD5_LENGTH = 32;

    /**
     * Returns the first element in an array.
     *
     * @param  array $array
     *
     * @return mixed
     */
    public static function array_first(array $array)
    {
        return reset($array);
    }

    /**
     * Returns the last element in an array.
     *
     * @param  array $array
     *
     * @return mixed
     */
    public static function array_last(array $array)
    {
        return end($array);
    }

    /**
     * Returns the first key in an array.
     *
     * @param  array $array
     *
     * @return int|string
     */
    public static function array_first_key(array $array)
    {
        reset($array);

        return key($array);
    }

    /**
     * Returns the last key in an array.
     *
     * @param  array $array
     *
     * @return int|string
     */
    public static function array_last_key(array $array)
    {
        end($array);

        return key($array);
    }

    /**
     * Output buffer a file and return it's content
     *
     * @param $path
     *
     * @return string
     */
    public static function ob_get_require_once($path)
    {
        ob_start();
        require_once($path);

        return ob_get_clean();
    }

    /**
     * Flatten a multi-dimensional array into a one dimensional array.
     *
     * @param array   $array         The array to flatten
     * @param boolean $preserve_keys Whether or not to preserve array keys.  Keys from deeply nested arrays will
     *                               overwrite keys from shallow nested arrays
     *
     * @return array
     */
    public static function array_flatten(array $array, $preserve_keys = true)
    {
        $flattened = array();

        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $flattened = array_merge($flattened, static::array_flatten($value, $preserve_keys));
            }
            else
            {
                if ($preserve_keys)
                {
                    $flattened[$key] = $value;
                }
                else
                {
                    $flattened[] = $value;
                }
            }
        }

        return $flattened;
    }

    /**
     * Strip all whitespace from the given string.
     *
     * @param  string $string The string to strip
     *
     * @return string
     */
    public static function str_strip_space($string)
    {
        return preg_replace('/\s+/', '', $string);
    }

    /**
     * Check if a string starts with the given string.
     *
     * @param  string $string
     * @param  string $starts_with
     *
     * @return bool
     */
    public static function str_starts_with($string, $starts_with)
    {
        return mb_strpos($string, $starts_with) === 0;
    }

    /**
     * Check if a string ends with the given string.
     *
     * @param  string $string
     * @param  string $ends_with
     *
     * @return bool
     */
    public static function str_ends_with($string, $ends_with)
    {
        return mb_substr($string, -mb_strlen($ends_with)) === $ends_with;
    }

    /**
     * Check if a string contains another string.
     *
     * @param  string $haystack
     * @param  string $needle
     *
     * @return bool
     */
    public static function str_contains($haystack, $needle)
    {
        return mb_strpos($haystack, $needle) !== false;
    }

    /**
     * Check if a string contains another string. This version is case
     * insensitive.
     *
     * @param  string $haystack
     * @param  string $needle
     *
     * @return bool
     */
    public static function str_icontains($haystack, $needle)
    {
        return mb_stripos($haystack, $needle) !== false;
    }

    /**
     * Checks to see if the page is being server over SSL or not
     *
     * @return bool
     */
    public static function isHTTPS()
    {
        if (isset($_SERVER["HTTPS"]) && $_SERVER['HTTPS'] === "on")
        {
            return true;
        }

        return false;
    }

    /**
     * Get url address
     *
     * @param bool $request_params      retrieve the url tih the GET params
     * @param bool $request_script_name retrieve the url with only the script name
     *
     * Possible usage: getCurrentUrl(true, false) - the default, get the full url
     *                 getCurrentUrl(false, true) - get the url without the GET params only the script name
     *                 getCurrentUrl(false, false) - get the url's directory path only
     *
     * @return string
     */
    public static function getCurrentUrl($request_params = true, $request_script_name = false)
    {
        // begin buildup
        $page_url = "http";

        // add for ssl secured connections
        if (static::isHTTPS())
        {
            $page_url .= "s";
        }
        $page_url .= "://";

        // find the end part of the url
        if ($request_params) // full url with requests
        {
            $end_url = $_SERVER["REQUEST_URI"];
        }
        elseif ($request_script_name) // full url without requests
        {
            $end_url = $_SERVER["SCRIPT_NAME"];
        }
        else // url directory path
        {
            $end_url = dirname($_SERVER["SCRIPT_NAME"]) . "/";
        }

        // add host
        $page_url .= $_SERVER["SERVER_NAME"];

        if ((int)$_SERVER["SERVER_PORT"] !== 80)
        {
            $page_url .= ":" . $_SERVER["SERVER_PORT"] . $end_url;
        }
        else
        {
            $page_url .= $end_url;
        }

        return $page_url;
    }

    /**
     * Returns ip address of the client
     *
     * Source : http://stackoverflow.com/questions/1634782/what-is-the-most-accurate-way-to-retrieve-a-users-correct-ip-address-in-php?
     * @return string|bool return the ip of the user or false in case of error
     */
    public static function geClientIp()
    {
        $ip_pool = [
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED'
        ];

        foreach ($ip_pool as $ip)
        {
            if (!empty($_SERVER[$ip]))
            {
                $options = FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE;
                if (filter_var($_SERVER[$ip], FILTER_VALIDATE_IP, $options))
                {
                    return $_SERVER[$ip];
                }
            }
        }

        return !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : false;
    }

    /**
     * Get the current running script path
     *
     * @param bool $basename to return script filename without the path
     *
     * @return string the full path
     */
    public static function getScriptFilename($basename = true)
    {
        if ($basename)
        {
            return basename($_SERVER["SCRIPT_FILENAME"]);
        }

        return $_SERVER["SCRIPT_FILENAME"];
    }

    /**
     * Get the html purifier config with all necessary settings preset
     *
     * @return HTMLPurifier_Config
     */
    public static function getHTMLPurifierConfig()
    {
        $config = HTMLPurifier_Config::createDefault();
        $config->set("Core.Encoding", "UTF-8");
        $config->set("Cache.SerializerPath", CACHE_PATH);
        $config->set(
            "HTML.AllowedElements",
            ["h3", "h4", "h5", "h6", "p", "img", "a", "ol", "li", "ul", "b", "i", "u", "small", "blockquote"]
        );
        $config->set("HTML.MaxImgLength", 480);
        $config->set("CSS.MaxImgLength", "480px");
        $config->set("Attr.AllowedFrameTargets", ["_blank", "_self", "_top", "_parent"]);

        return $config;
    }

    /**
     * Purify a string (html escape) with the default config
     *
     * @param string $string
     *
     * @return string the string purified
     */
    public static function htmlPurify($string)
    {
        return HTMLPurifier::getInstance(static::getHTMLPurifierConfig())->purify($string);
    }

    /**
     * Convert a POST form checkbox to an integer
     *
     * @param string $checkbox
     *
     * @return int
     */
    public static function getCheckboxInt($checkbox)
    {
        if ($checkbox === "on")
        {
            return 1;
        }

        return 0;
    }

    /**
     * Checks if the password is salted
     *
     * @param string $hash_password the hash value of a password
     *
     * @return bool
     */
    public static function isPasswordSalted($hash_password)
    {
        return mb_strlen($hash_password) === (static::SALT_LENGTH + static::SHA256_LENGTH);
    }

    /**
     * Get the salt part of a password
     *
     * @param string $hash_password the hash value of a passowrd
     *
     * @return string
     */
    public static function getSaltFromPassword($hash_password)
    {
        return mb_substr($hash_password, 0, static::SALT_LENGTH);
    }

    /**
     * Generate the hash for a password
     *
     * @param string      $raw_password the plain password
     * @param null|string $salt         optional, give it own salt
     *
     * @return string
     */
    public static function getPasswordHash($raw_password, $salt = null)
    {
        if (!$salt) // generate our own salt
        {
            // when we retrieve it from the database it will be already utf8, because we encoded it as utf8
            $salt = utf8_encode(mcrypt_create_iv(static::SALT_LENGTH, MCRYPT_DEV_URANDOM));
        }

        return $salt . hash("sha256", $salt . $raw_password);
    }

    /**
     * Generates a string of random characters.
     *
     * @param   integer $length             The length of the string to generate
     * @param   boolean $human_friendly     Whether or not to make the string human friendly by removing characters that can be
     *                                      confused with other characters (O and 0, l and 1, etc)
     * @param   boolean $include_symbols    Whether or not to include symbols in the string. Can not be enabled if $human_friendly is true
     * @param   boolean $no_duplicate_chars Whether or not to only use characters once in the string.
     *
     * @return  string
     */
    public static function getRandomString(
        $length,
        $human_friendly = true,
        $include_symbols = false,
        $no_duplicate_chars = false
    ) {
        $nice_chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefhjkmnprstuvwxyz23456789';
        $all_an = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        $symbols = '!@#$%^&*()~_-=+{}[]|:;<>,.?/"\'\\`';
        $string = '';

        // Determine the pool of available characters based on the given parameters
        if ($human_friendly)
        {
            $pool = $nice_chars;
        }
        else
        {
            $pool = $all_an;

            if ($include_symbols)
            {
                $pool .= $symbols;
            }
        }

        // Don't allow duplicate letters to be disabled if the length is
        // longer than the available characters
        assert(
            $no_duplicate_chars && mb_strlen($pool) < $length,
            '$length exceeds the size of the pool and $no_duplicate_chars is enabled'
        );

        // Convert the pool of characters into an array of characters and
        // shuffle the array
        $pool = str_split($pool);
        shuffle($pool);

        // Generate our string
        for ($i = 0; $i < $length; $i++)
        {
            if ($no_duplicate_chars)
            {
                $string .= array_shift($pool);
            }
            else
            {
                $string .= $pool[0];
                shuffle($pool);
            }
        }

        return $string;
    }

    /**
     * Resize an image, and send the new resized image to the user with http headers
     *
     * @param string      $file
     * @param string|null the type of image
     *
     * @return null
     */
    public static function resizeImage($file, $type = null)
    {
        // Determine image size
        switch ($type)
        {
            case 'small':
                $size = 25;
                break;
            case 'medium':
                $size = 75;
                break;
            case 'big':
                $size = 300;
                break;
            default:
                $size = 100;
                break;
        }
        $cache_name = $size . '--' . basename($file);
        $local_path = UP_PATH . $file;

        // Check if image exists, and if it does, check its format
        $orig_file = File::exists($file);
        if ($orig_file === -1)
        {
            if (!file_exists(ROOT . $file))
            {
                header('HTTP/1.1 404 Not Found');

                return;
            }
            else
            {
                $local_path = ROOT . $file;
            }
        }

        // Check if a cached version is available
        if (Cache::fileExists($cache_name) !== array())
        {
            header('Cached-Image: true');
            header('Location: ' . CACHE_LOCATION . $cache_name);

            return;
        }

        // Start processing the original file
        $image_info = @getimagesize($local_path);
        switch ($image_info[2])
        {
            default:
                $source = imagecreatefrompng(IMG_LOCATION . 'notfound.png');
                $format = 'png';
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($local_path);
                $format = 'png';
                break;
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($local_path);
                $format = 'jpg';
                break;
        }

        // Get length and width of original image
        $width_source = imagesx($source);
        $height_source = imagesy($source);
        if ($width_source > $height_source)
        {
            $width_destination = $size;
            $height_destination = $size * $height_source / $width_source;
        }
        else // $width_source <= $height_source
        {
            $height_destination = $size;
            $width_destination = $size * $width_source / $height_source;
        }

        // Create new canvas
        $destination = imagecreatetruecolor($width_destination, $height_destination);

        // Preserve transparency
        imagealphablending($destination, false);
        imagesavealpha($destination, true);
        $transparent_bg = imagecolorallocatealpha($destination, 255, 255, 255, 127);
        imagefilledrectangle($destination, 0, 0, $width_destination, $height_destination, $transparent_bg);

        // Resize image
        imagecopyresampled(
            $destination,
            $source,
            0,
            0,
            0,
            0,
            $width_destination,
            $height_destination,
            $width_source,
            $height_source
        );

        // Display image and cache the result
        header('Cached-Image: false');
        if ($format === 'png')
        {
            header('Content-Type: image/png');
            imagepng($destination, CACHE_PATH . $cache_name, 9);
            imagepng($destination, null, 9);
        }
        else
        {
            header("Content-Type: image/jpeg");
            imagejpeg($destination, CACHE_PATH . $cache_name, 90);
            imagejpeg($destination, null, 90);
        }

        // Create a record of the cached file
        $orig_file_addon = File::getAddon($orig_file);
        Cache::createFile($cache_name, $orig_file_addon, sprintf('w=%d,h=%d', $width_destination, $height_destination));
    }

    /**
     * Get the image label of some text, if the image label for that text does not exist, then create it
     *
     * @param string $text the label text
     *
     * @return string the img tag  that points
     */
    public static function getImageLabel($text)
    {
        $write_dir = UP_PATH . 'temp' . DS;
        $read_dir = DOWNLOAD_LOCATION . 'temp/';

        $text_noaccent = preg_replace('/\W/s', '_', $text); // remove some accents
        $image_name = 'im_' . $text_noaccent . '.png';

        if (!file_exists($write_dir . $image_name))
        {
            $text_size = 11;
            $text_angle = 90;
            $font = FONTS_PATH . 'DejaVuSans.ttf';
            $bbox = imagettfbbox($text_size, $text_angle, $font, $text);

            $min_x = min(array($bbox[0], $bbox[2], $bbox[4], $bbox[6]));
            $max_x = max(array($bbox[0], $bbox[2], $bbox[4], $bbox[6]));
            $min_y = min(array($bbox[1], $bbox[3], $bbox[5], $bbox[7]));
            $max_y = max(array($bbox[1], $bbox[3], $bbox[5], $bbox[7]));

            $width = $max_x - $min_x + 2;
            $height = $max_y - $min_y + 2;

            $image = imagecreatetruecolor($width, $height);

            // set color and transparency
            $bg_color = imagecolorallocate($image, 0, 0, 0);
            imagecolortransparent($image, $bg_color);
            $text_color = imagecolorallocate($image, 2, 2, 2);

            // set text
            imagettftext($image, $text_size, $text_angle, $width, $height, $text_color, $font, $text);

            // create the image in the write dir
            imagepng($image, $write_dir . $image_name);
            imagedestroy($image);
        }

        return '<img src="' . $read_dir . $image_name . '" alt="' . h($text) . '" />';
    }

    /**
     * Get the stk version formatted
     *
     * @param int    $format the version format
     * @param string $file_type
     *
     * @return string
     */
    public static function getVersionFormat($format, $file_type)
    {
        switch ($file_type)
        {

            case 'karts':
                if ($format == 1)
                {
                    return 'Pre-0.7';
                }
                if ($format == 2)
                {
                    return '0.7.0 - 0.8.1';
                }

                return _h('Unknown');
                break;
            case 'tracks':
            case 'arenas':
                if ($format == 1 || $format == 2)
                {
                    return 'Pre-0.7';
                }
                if ($format >= 3 && $format <= 5)
                {
                    return '0.7.0 - 0.8.1';
                }

                return _h('Unknown');
                break;
            default:
                return _h('Unknown');
        }
    }
}
 