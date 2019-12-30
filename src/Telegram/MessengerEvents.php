<?php
/**
 * Created by PhpStorm.
 * User: he110
 * Date: 21/08/2019
 * Time: 03:08
 */

namespace He110\CommunicationTools\Telegram;


use He110\CommunicationTools\EventController;
use He110\CommunicationTools\MessengerEventsInterface;
use He110\CommunicationTools\MessengerUser;
use He110\CommunicationTools\Request;
use He110\CommunicationTools\ScreenItems\Button;

class MessengerEvents implements MessengerEventsInterface
{
    /**
     * {@inheritdoc}
     */
    public function onMessage(\Closure $closure)
    {
        $this->addEvent(Request::REQUEST_TYPE_MESSAGE, $closure);
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnoreStart
     */
    public function onMessageRead(\Closure $closure)
    {

        // TODO: Найти способ получить такой event
        $this->addEvent(Request::REQUEST_TYPE_MESSAGE_READ, $closure);
    }
    /** @codeCoverageIgnoreEnd */

    /**
     * {@inheritdoc}
     */
    public function onButtonClick(\Closure $closure)
    {
        $this->addEvent(Request::REQUEST_TYPE_BUTTON_CLICK, $closure);
    }

    /**
     * @param string $type
     * @param \Closure $closure
     */
    private function addEvent(string $type, \Closure $closure): void
    {
        $key = spl_object_hash($this)."_".$type;
        EventController::getInstance()->addEvent($key, $closure);
    }

    /**
     * {@inheritdoc}
     */
    public function checkEvents(): void
    {
        $request = $this->getRequest();
        if ($request->getType()) {
            $key = spl_object_hash($this) . "_" . $request->getType();
            if ($closure = EventController::getInstance()->getEvent($key))
                $closure($request);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest(): Request
    {
        $request = new Request();
        if ($data = json_decode($this->getPhpInput(), true)) {

            if (isset($data["message"]))
                $request = $this->buildMessageRequest($request, $data["message"]);

            elseif (isset($data["callback_query"]))
                $request = $this->buildButtonClickRequest($request, $data["callback_query"]);
        }
        return $request;
    }

    /**
     * @param Request $request
     * @param array $data
     * @return Request
     */
    private function buildMessageRequest(Request &$request, array $data): Request
    {
        $this->setUserFromRequest($request, $data["from"]);

        if (isset($data["text"])) {
            $request->setType(Request::REQUEST_TYPE_MESSAGE);
            $request->setMessage($data["text"]);
        }
        return $request;
    }

    /**
     * @param Request $request
     * @param array $data
     * @return Request
     */
    private function buildButtonClickRequest(Request &$request, array $data): Request
    {
        $this->setUserFromRequest($request, $data["from"]);
        if (isset($data["data"])) {
            $request->setType(Request::REQUEST_TYPE_BUTTON_CLICK);
            $payload = "";
            $type = $this->detectPayloadType($data["data"],$payload);
            switch ($type) {
                case Button::BUTTON_TYPE_CALLBACK:
                    $request->setPayload($payload);
                    break;
                default:
                    $request->setMessage($payload);
                    break;
            }
        }
        return $request;
    }

    /**
     * @param string|null $payload
     * @param null $data
     * @return string
     */
    private function detectPayloadType(string $payload, &$data = null): ?string
    {
        if (substr($payload, 0, 4) === "clb=") {
            $data = substr($payload, 4);
            return Button::BUTTON_TYPE_CALLBACK;
        } elseif (substr($payload, 0, 5) === "text=") {
            $data = substr($payload, 5);
            return Button::BUTTON_TYPE_TEXT;
        } else {
            return null;
        }
    }

    /**
     * @param Request $request
     * @param array $from
     */
    private function setUserFromRequest(Request &$request, array $from): void
    {
        $user = new MessengerUser();
        var_dump($from);
        $user->setFirstName($from["first_name"])
            ->setLastName($from["last_name"])
            ->setUsername($from["username"])
            ->setUserId($from['id'])
            ->setLanguageCode($from["language_code"]);
        $request->setUser($user);
    }

    /**
     * @return string
     *
     * @codeCoverageIgnoreStart
     */
    protected function getPhpInput(): string
    {
        return file_get_contents("php://input");
    }

    /** @codeCoverageIgnoreEnd */
}