<?php
namespace Leemarket\Smsintel;

use Bitrix\Main\Error;
use Bitrix\MessageService\Sender\Base;
use Bitrix\MessageService\Sender\Result\SendMessage;

\CModule::IncludeModule("messageservice");

class Sms extends Base
{
    private $login;
    private $password;
    private $client;

    public function __construct() {
        $this->login = '';
        $this->password = '';
        
        $this->client = new Transport($this->login, $this->password);
    }

    public function sendMessage(array $messageFields) {
        if (!$this->canUse()) {
            $result = new SendMessage();
            $result->addError(new Error('Ошибка отправки. СМС-сервис отключен'));
            return $result;
        }

        $phones = array($messageFields['MESSAGE_TO']);
        $parameters = [
            'text' => $messageFields['MESSAGE_BODY'],
            'source' => $messageFields['MESSAGE_FROM']
        ];
        
        if (!$parameters['source'])
		{
			$parameters['source'] = $this->getDefaultFrom();
		}

        $result = new SendMessage();
        $response = $this->client->send($parameters,$phones);
        
        if ($response['code'] != 1) {
            //$result->addErrors($response->getErrors());
            return $result;
        }

        return $result;
    }

    public function getShortName() {
        return 'smsintel.ru';
    }

    public function getId() {
        return 'smsintel';
    }

    public function getName() {
        return 'SMSIntel';
    }

    public function canUse() {
        return true;
    }

    public function getFromList()
	{
		//$from = $this->getOption('from_list');
		//return is_array($from) ? $from : array();
        $from = array();
        $from[] = array(
			'id' => 'LeeMarket',
			'name' => 'LeeMarket'
		);
        $from[] = array(
			'id' => 'farm74',
			'name' => 'farm74'
		);
        return $from;
	}

	public function getDefaultFrom()
	{
		$fromList = $this->getFromList();
		if (count($fromList) > 0)
		{
			return $fromList[0]['id'];
		}
		return null;
	}
    
    /*
    public function sync()
	{
		if ($this->isRegistered())
		{
			$this->loadFromList();
		}
		return $this;
	}
    
    private function loadFromList()
	{
		$sid = $this->getOption('account_sid');
		$result = $this->callExternalMethod(
			HttpClient::HTTP_GET,
			'Accounts/'.$sid.'/IncomingPhoneNumbers'
		);

		if ($result->isSuccess())
		{
			$from = array();
			$resultData = $result->getData();
			if (isset($resultData['incoming_phone_numbers']) && is_array($resultData['incoming_phone_numbers']))
			{
				foreach ($resultData['incoming_phone_numbers'] as $phoneNumber)
				{
					if ($phoneNumber['capabilities']['sms'] === true)
					{
						$from[] = array(
							'id' => $phoneNumber['phone_number'],
							'name' => $phoneNumber['friendly_name']
						);
					}
				}
			}

			$this->setOption('from_list', $from);
		}
	}
    */
}
