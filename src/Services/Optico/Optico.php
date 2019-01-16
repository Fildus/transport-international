<?php

namespace App\Services\Optico;

/**
 * Handles interactions with Optico API
 */
class Optico
{
    const VIEW_URL = 'http://www.optico.fr/ospView';
    const CLICK_URL = 'http://www.optico.fr/ospClick';
    const COOKIE_NAME = 'optico_visit_id';

    protected static $apiKey;
    protected $viewParameters = [];
    protected $trackingPhones = [];
    protected $isViewSent = false;
    protected $viewId;

    public function __construct($apiKey = null)
    {
        static::$apiKey = $apiKey;
    }

    /**
     * Send a click request to Optico for given view id and phone
     *
     * @param  int    $viewId
     * @param  string $phone
     *
     * @return array
     */
    public function sendClick($viewId, $phone)
    {
        if (null == $viewId) {
            return null;
        }

        $this->checkApiKey();

        $parameters = [
            'api_key' => '06f46a4bc4c2edd635373639de3c25b8',
            'view_id' => $viewId,
            'phone' => $phone
        ];

        $response = $this->sendRequest(static::CLICK_URL, $parameters);

        if (isset($response['surtaxPhone'])) {
            $this->trackingPhones[$phone] = $response['surtaxPhone'];
        }

        return $response;
    }

    /**
     * Get the tracking phone number for a sent phone number.
     * Returns the same number if a tracking phone was not found.
     *
     * @param  string $phone Original phone
     *
     * @return string
     */
    public function getTrackingPhoneNumber($phone)
    {
        return isset($this->trackingPhones[$phone]) ? $this->trackingPhones[$phone]["surtaxPhoneNumber"] : $phone;
    }

    /**
     * Get the tracking phone code for a sent phone number.
     * Returns an empty string if a tracking phone was not found or has no code.
     *
     * @param  string $phone Original phone
     *
     * @return string
     */
    public function getTrackingPhoneCode($phone)
    {
        return isset($this->trackingPhones[$phone]) ? $this->trackingPhones[$phone]["surtaxPhoneCode"] : '';
    }

    /**
     * Get view id.
     * View request must be sent before having a view id.
     *
     * @return int|null
     */
    public function getViewId()
    {
        $this->checkViewIsSent();

        return $this->viewId;
    }

    /**
     * Send view request to optico
     */
    public function sendView()
    {
        if ($this->isViewSent) {
            throw new \LogicException("View request has already been sent to optico!");
        }

        $parameters = $this->getViewParameters();
        $response = $this->sendRequest(static::VIEW_URL, $parameters);

        if (isset($response['visitId'])) {
            $this->setVisitId($response['visitId']);
        }

        if (isset($response['surtaxPhones'])) {
            foreach ($response['surtaxPhones'] as $originalPhone => $trackingData) {
                $this->trackingPhones[$originalPhone] = $trackingData;
            }
        }

        if (isset($response['viewId'])) {
            $this->viewId = $response['viewId'];
        }

        $this->isViewSent = true;
    }

    /**
     * Set title for current view
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->setViewParameter('page_title', $title);
    }

    /**
     * Set total number of phones for current page
     *
     * @param int $number
     */
    public function setTotalPhonesOnPage($number)
    {
        $this->setViewParameter('nr_phones', (int)$number);
    }


    /**
     * Add phones to get a tracking number
     *
     * @param array $phones
     */
    public function addPhones(array $phones)
    {
        foreach ($phones as $phone) {
            $this->addPhone($phone);
        }
    }

    /**
     * Add phone to get a tracking number
     *
     * e.g. "0490260716"
     *
     * @param string $phone
     */
    public function addPhone($phone)
    {
        if (!$phone) {
            return;
        }

        $phones = isset($this->viewParameters['phones']) ? explode(',', $this->viewParameters['phones']) : [];

        if (!in_array($phone, $phones)) {
            $phones[] = $phone;
        }

        $this->setViewParameter('phones', implode(',', $phones));
        $this->setViewParameter('direct_display', 'true');
    }

    /**
     * Set a parameter for current view
     *
     * @param string $name
     * @param string $value
     *
     * @throws InvalidArgumentException
     */
    public function setViewParameter($name, $value)
    {
        if (!in_array($name, ['visit_id', 'ip', 'user_agent', 'url', 'page_title', 'is_mobile', 'referer_url', 'nr_phones', 'direct_display', 'phones'])) {
            throw new InvalidArgumentException("Unknown view parameter \"$name\"");
        }

        $this->viewParameters[$name] = $value;
    }

    /**
     * Get parameters for current view
     *
     * @return array
     */
    protected function getViewParameters()
    {
        $this->checkApiKey();
        $parameters = $this->getDefaultViewParameters();
        $parameters['api_key'] = static::$apiKey;

        return array_merge($parameters, $this->viewParameters);
    }

    /**
     * Check if api key is set
     *
     * @throws LogicException
     */
    private function checkApiKey()
    {
        if (!static::$apiKey) {
            throw new \LogicException("No optico API key is set!");
        }
    }

    /**
     * Check if the view request was sent
     *
     * @throws LogicException   If view was not sent
     */
    private function checkViewIsSent()
    {
        if (!$this->isViewSent) {
            throw new \LogicException("Must send optico view first to have tracking phones!");
        }
    }

    /**
     * Create default view parameters
     *
     * @return array
     */
    protected function getDefaultViewParameters()
    {
        $parameters = [
            'visit_id' => $this->getVisitId(),
            'ip' => $_SERVER["REMOTE_ADDR"],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'url' => "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'page_title' => "NO TITLE",
            'is_mobile' => OpticoUtils::isMobile() ? 'true' : 'false',
            'referer_url' => isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '',
            'nr_phones' => $this->countPhonesOnPage(),
            'direct_display' => 'false',
            'phones' => ''
        ];

        return $parameters;
    }

    /**
     * Count the number of phones that will be sent for a direct display
     * if no number was explicitly given.
     *
     * @return int
     */
    protected function countPhonesOnPage()
    {
        if (isset($this->viewParameters['nr_phones'])) {
            return $this->viewParameters['nr_phones'];
        }

        $phones = isset($this->viewParameters['phones']) ? explode(',', $this->viewParameters['phones']) : [];

        return count($phones);
    }

    /**
     * Set a cookie for given visit id
     *
     * @param int $id
     */
    protected function setVisitId($id)
    {
        setcookie(static::COOKIE_NAME, $id, time() + 30 * 60); // 30 mins for a visit
    }

    /**
     * Get current user optico visit id
     *
     * @return int
     */
    public function getVisitId()
    {
        return isset($_COOKIE[static::COOKIE_NAME]) ? $_COOKIE[static::COOKIE_NAME] : 0;
    }

    /**
     * Make a request to optico
     *
     * @param  string $url
     * @param  array  $parameters
     *
     * @return array
     */
    protected function sendRequest($url, array $parameters)
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $rawResponse = curl_exec($ch);
            $info = curl_getinfo($ch);

            $this->onRequestSend($url, $parameters, $rawResponse, $info);
            $response = json_decode($rawResponse, true);

            if (!is_array($response)) {
                $response = [];
                $this->onUnexpectedResponse($url, $parameters, $rawResponse);
            }

            if (isset($response['errorMessage'])) {
                $this->onOpticoRequestErrorMessage($url, $parameters, $response, $info);
            }

            return $response;
        } catch (Exception $exception) {
            $this->onRequestException($url, $parameters, $exception);

            return [];
        }
    }

    /**
     * Called when a request was sent to Optico
     *
     * @param  string $url
     * @param  array  $parameters
     * @param  string $rawResponse
     * @param  array  $responseCurlInfo
     */
    protected function onRequestSend($url, array $parameters, $rawResponse, array $responseCurlInfo)
    {
    }

    /**
     * Called when something went wrong on Optico's side
     *
     * @param  string $url
     * @param  array  $parameters
     * @param  string $rawResponse
     */
    protected function onUnexpectedResponse($url, array $parameters, $rawResponse)
    {
    }

    /**
     * Called when a request sent to Optico returned an error
     *
     * @param  string $url
     * @param  array  $parameters
     * @param  array  $response
     * @param  array  $responseCurlInfo
     */
    protected function onOpticoRequestErrorMessage($url, array $parameters, array $response, array $responseCurlInfo)
    {
    }

    /**
     * Called when an exception was caught during the sending of a request to Optico
     *
     * @param  string    $url
     * @param  array     $parameters
     * @param  Exception $exception
     */
    protected function onRequestException($url, array $parameters, Exception $exception)
    {
    }
}
