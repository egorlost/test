<?php
/**
 * Website: vinlock-twitch-api
 * Created By: Vinlock
 * Date: 5/29/16 7:13 PM
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Vinlock\StreamAPI;


use Vinlock\StreamAPI\StreamObjects\Hitbox;
use Vinlock\StreamAPI\StreamObjects\Twitch;
use Vinlock\StreamAPI\StreamObjects\Goodgame;

abstract class StreamDriver {

    /**
     * Max Number per multi stream request.
     *
     * // Twitch's Max for Multi Streams is 100
     */
    const NUM_PER_MULTI = 100;
    const CLIENT_ID = 'm0c04ljab3wgeo8q5xfh6lp6fwm91kz';

    /**
     * Class providers for each stream service.
     *
     * @var array
     */
    public static $providers = [
        "twitch" => Twitch::class,
        "hitbox" => Hitbox::class,
        "goodgame" => GoodGame::class
    ];

    /**
     * Retrieve an array of streams.
     *
     * @param array $stream_usernames
     * @param string $service
     * @return array
     */
    final public static function getStream(array $stream_usernames, string $service) {
        $streams = array();

        $chunks = array_chunk($stream_usernames, self::NUM_PER_MULTI);

        $client_id = ($service === 'twitch') ? '&client_id=' . self::CLIENT_ID : '';

        foreach ($chunks as $chunk) {
            $list = implode(",", $chunk);

            $json = json_decode(self::curl_get_contents(self::$providers[$service]::STREAM_API . $list . $client_id ), true);

            if($json){
                if(isset($json[self::$providers[$service]::STREAM_KEY])){
                    foreach ($json[self::$providers[$service]::STREAM_KEY] as $stream) {
                        $streamObject = new self::$providers[$service]($stream);
                        array_push($streams, $streamObject);
                    }
                } elseif(!isset($json['error'])){
                    foreach ($json as $stream) {
                        $streamObject = new self::$providers[$service]($stream);
                        array_push($streams, $streamObject);
                    }
                }
            }
        }

        return $streams;
    }

    final public static function byGame(string $game, string $service) {
        $streams = array();

        $game = urlencode($game);
        $stream_key = self::$providers[$service]::STREAM_KEY;

        $json = json_decode(\Requests::get(self::$providers[$service]::GAMES_API.$game."&limit=".self::NUM_PER_MULTI . '&client_id=' . self::CLIENT_ID)->body, TRUE);

        if (!empty($json[$stream_key])) {
            foreach ($json[$stream_key] as $stream) {
                $streamObject = new self::$providers[$service]($stream);
                array_push($streams, $streamObject);
            }
        }
        return $streams;
    }

    final public static function allGames(string $service) {
        $streams = array();

        $stream_key = self::$providers[$service]::GAMES_KEY;

        $json = json_decode(\Requests::get(self::$providers[$service]::ALL_GAMES_API . '?client_id=' . self::CLIENT_ID . '&liveonly=true')->body, TRUE);

        if (!empty($json[$stream_key])) {
            foreach ($json[$stream_key] as $stream) {
                $streamObject = new self::$providers[$service]($stream);
                array_push($streams, $streamObject);
            }
        }

        return $streams;
    }

    /**
     * Get the JSON data with CURL
     *
     * @param $url
     * @return mixed
     */
    final private static function curl_get_contents($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}