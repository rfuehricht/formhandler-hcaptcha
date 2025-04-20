<?php

namespace Rfuehricht\FormhandlerHcaptcha\ErrorCheck;


use Psr\Http\Message\ServerRequestInterface;
use Rfuehricht\Formhandler\Validator\ErrorCheck\AbstractErrorCheck;
use TYPO3\CMS\Core\Http\RequestFactory;
use TYPO3\CMS\Core\Utility\HttpUtility;
use Waldhacker\Hcaptcha\Service\ConfigurationService;

/**
 * Validates HCaptcha response
 *
 */
class Hcaptcha extends AbstractErrorCheck
{

    /**
     * @var ConfigurationService
     */
    private ConfigurationService $configurationService;

    /**
     * @var RequestFactory
     */
    private RequestFactory $requestFactory;

    public function injectConfigurationService(ConfigurationService $configurationService): void
    {
        $this->configurationService = $configurationService;
    }

    public function injectRequestFactory(RequestFactory $requestFactory): void
    {
        $this->requestFactory = $requestFactory;
    }

    public function check(string $fieldName, array $values, array $settings = []): string
    {
        $checkFailed = '';
        $response = $this->validateHcaptcha();
        if (empty($response) || (bool)($response['success'] ?? false) === false) {
            return $this->getCheckFailed();
        }
        return $checkFailed;
    }

    /**
     * @return array
     */
    private function validateHcaptcha(): array
    {
        /** @var ServerRequestInterface $request */
        $request = $GLOBALS['TYPO3_REQUEST'];
        /** @var array $parsedBody */
        $parsedBody = $request->getParsedBody();
        $hcaptchaFormFieldValue = $parsedBody['h-captcha-response'] ?? null;
        if ($hcaptchaFormFieldValue === null) {
            return ['success' => false, 'error-codes' => ['invalid-post-form']];
        }

        $ip = '';
        $normalizedParams = $request->getAttribute('normalizedParams');
        if ($normalizedParams) {
            $ip = $normalizedParams->getRemoteAddress();
        }

        $url = HttpUtility::buildUrl(
            [
                'host' => $this->configurationService->getVerificationServer(),
                'query' => http_build_query(
                    [
                        'secret' => $this->configurationService->getPrivateKey(),
                        'response' => $hcaptchaFormFieldValue,
                        'remoteip' => $ip,
                    ]
                ),
            ]
        );

        $response = $this->requestFactory->request($url, 'POST');

        $body = (string)$response->getBody();
        $responseArray = json_decode($body, true);
        return is_array($responseArray) ? $responseArray : [];
    }

}
