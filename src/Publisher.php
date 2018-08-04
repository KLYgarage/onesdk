<?php declare(strict_types=1);

namespace One;

use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Client;
use One\Model\Article;
use One\Model\Model;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Publisher class
 * main class to be used that interfacing to the API
 */
class Publisher implements LoggerAwareInterface
{
    public const DEFAULT_MAX_ATTEMPT = 4;

    public const REST_SERVER = 'https://dev.one.co.id';

    public const AUTHENTICATION = '/oauth/token';

    public const ARTICLE_CHECK_ENDPOINT = '/api/article';

    public const ARTICLE_ENDPOINT = '/api/publisher/article';

    /**
     * attachment url destination
     * @var array<string[]>
     */
    private $attachmentUrl;

    /**
     * Logger variable, if set log activity to this obejct each time sending request and receiving response
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger = null;

    /**
     * credentials props
     *
     * @var string
     */
    private $clientId;

    /**
     * client secret
     * @var string
     */
    private $clientSecret;

    /**
     * Oauth access token response
     *
     * @var string
     */
    private $accessToken = null;

    /**
     * publisher custom options
     *
     * @var \One\Collection
     */
    private $options;

    /**
     * http transaction Client
     *
     * @var \Guzzle\Http\Client
     */
    private $httpClient;

    /**
     * constructor
     */
    public function __construct(string $clientId, string $clientSecret, array $options = [])
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->assessOptions($options);

        $this->attachmentUrl = [
            Article::ATTACHMENT_FIELD_GALLERY => self::ARTICLE_ENDPOINT . '/{article_id}/gallery',
            Article::ATTACHMENT_FIELD_PAGE => self::ARTICLE_ENDPOINT . '/{article_id}/page',
            Article::ATTACHMENT_FIELD_PHOTO => self::ARTICLE_ENDPOINT . '/{article_id}/photo',
            Article::ATTACHMENT_FIELD_VIDEO => self::ARTICLE_ENDPOINT . '/{article_id}/video',

        ];
    }

    /**
     * recycleToken from callback. If use external token storage could leveraged on this
     */
    public function recycleToken(\Closure $tokenProducer): self
    {
        return $this->setAuthorizationHeader($tokenProducer());
    }

    /**
     * submitting article here, return new Object cloned from original
     */
    public function submitArticle(Article $article): \One\Model\Article
    {
        $responseArticle = $this->post(
            self::ARTICLE_ENDPOINT,
            $this->normalizePayload(
                $article->getCollection()
            )
        );

        $responseArticle = json_decode($responseArticle, true);
        $article->setId($responseArticle['data']['id']);

        foreach ($article->getPossibleAttachment() as $field) {
            if ($article->hasAttachment($field)) {
                foreach ($article->getAttachmentByField($field) as $attachment) {
                    $this->submitAttachment(
                        $article->getId(),
                        $attachment,
                        $field
                    );
                }
            }
        }

        return $article;
    }

    /**
     * submit each attachment of an article here
     */
    public function submitAttachment(string $idArticle, Model $attachment, string $field): array
    {
        return json_decode(
            $this->post(
                $this->getAttachmentEndPoint($idArticle, $field),
                $this->normalizePayload(
                    $attachment->getCollection()
                )
            ),
            true
        );
    }

    /**
     * get article from rest API
     *
     * @return string json
     */
    public function getArticle(string $idArticle): string
    {
        return $this->get(
            self::ARTICLE_CHECK_ENDPOINT . "/${idArticle}"
        );
    }

    /**
     * get list article by publisher
     *
     * @return string json
     */
    public function listArticle(): string
    {
        return $this->get(
            self::ARTICLE_ENDPOINT
        );
    }

    /**
     * delete article based on id
     */
    public function deleteArticle(string $idArticle): string
    {
        $articleOnRest = $this->getArticle($idArticle);

        if (! empty($articleOnRest)) {
            $articleOnRest = json_decode($articleOnRest, true);

            if (isset($articleOnRest['data'])) {
                foreach (Article::getDeleteableAttachment() as $field) {
                    if (isset($articleOnRest['data'][$field])) {
                        foreach ($articleOnRest['data'][$field] as $attachment) {
                            if (isset($attachment[$field . '_order'])) {
                                $this->deleteAttachment($idArticle, $field, $attachment[$field . '_order']);
                            }
                        }
                    }
                }
            }

            return $this->delete(
                $this->getArticleWithIdEndPoint($idArticle)
            );
        }
    }

    /**
     * delete attachment of an article
     */
    public function deleteAttachment(string $idArticle, string $field, string $order): string
    {
        return $this->delete(
            $this->getAttachmentEndPoint($idArticle, $field) . "/${order}"
        );
    }

    /**
     * get proxy
     */
    final public function get(string $path, array $header = [], array $options = []): string
    {
        return $this->requestGate(
            'GET',
            $path,
            $header,
            [],
            $options
        );
    }

    /**
     * post proxy
     *
     * @param \One\Collection|array $body
     * @param array $header
     * @param array $options
     */
    final public function post(string $path, $body, $header = [], $options = []): string
    {
        if ($this->hasLogger()) {
            $this->logger->info('Post to ' . $path);
        }

        return $this->requestGate(
            'POST',
            $path,
            $header,
            $body,
            $options
        );
    }

    /**
     * delete proxy
     *
     * @param \One\Collection|array $body
     * @param array $header
     * @param array $options
     */
    final public function delete(string $path, $body = [], $header = [], $options = []): string
    {
        return $this->requestGate(
            'DELETE',
            $path,
            $header,
            $body,
            $options
        );
    }

    /**
     * @inheritDoc
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * assessing and custom option
     */
    private function assessOptions(array $options): void
    {
        $defaultOptions = [
            'rest_server' => self::REST_SERVER,
            'auth_url' => self::AUTHENTICATION,
            'max_attempt' => self::DEFAULT_MAX_ATTEMPT,
            'default_headers' => [
                'Accept' => 'application/json',
            ],
        ];

        $this->options = new Collection(
            array_merge(
                $defaultOptions,
                $options
            )
        );

        if (isset($options['access_token'])) {
            $this->setAuthorizationHeader($options['access_token']);
        }

        $this->httpClient = new Client(
            $this->options->get('rest_server')
        );
    }

    /**
     * one gate menu for request creation.
     *
     * @param \One\Collection|array $body
     * @param array $options
     */
    private function requestGate(string $method, string $path, array $header = [], $body = [], $options = []): string
    {
        if (empty($this->accessToken)) {
            $this->renewAuthToken();
        }

        return (string) $this->sendRequest(
            $this->httpClient->createRequest(
                $method,
                $path,
                array_merge(
                    $this->options->get('default_headers'),
                    $header
                ),
                $body,
                $options
            )
        );
    }

    /**
     * actually send request created here, separated for easier attempt count and handling exception
     *
     * @return \Guzzle\Http\EntityBodyInterface|string|null
     * @throws \Exception
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Guzzle\Http\Exception\BadResponseException
     */
    private function sendRequest(RequestInterface $request, int $attempt = 0)
    {
        if ($attempt >= $this->options->get('max_attempt')) {
            throw new \Exception('MAX attempt reached for ' . $request->getUrl() . ' with payload ' . (string) $request);
        }

        try {
            $response = $request->send();
            if ($response->getStatusCode() === 200) {
                return $response->getBody();
            }
            if ($response->getStatusCode() === 429) {
                $this->renewAuthToken();
            }

            return $this->sendRequest($request, $attempt++);
        } catch (ClientErrorResponseException $err) {
            if ($err->getResponse()->getStatusCode() === 429) {
                $this->renewAuthToken();
                return $this->sendRequest($err->getRequest(), $attempt++);
            }

            throw $err;
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    /**
     * renewing access_token
     *
     * @throws \Exception
     */
    private function renewAuthToken(): self
    {
        $token = (string) $this->sendRequest(
            $this->httpClient->post(
                self::AUTHENTICATION,
                $this->options->get('default_headers'),
                [
                    'grant_type' => 'client_credentials',
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                ]
            )
        );

        $token = json_decode($token, true);

        if (empty($token)) {
            throw new \Exception('Access token request return empty response');
        }

        return $this->setAuthorizationHeader(
            $token['access_token']
        );
    }

    /**
     * set header for OAuth 2.0
     */
    private function setAuthorizationHeader(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        $this->options->set(
            'default_headers',
            array_merge(
                $this->options->get('default_headers'),
                [
                    'Authorization' => 'Bearer ' . $accessToken,
                ]
            )
        );

        return $this;
    }

    /**
     * get Attachment Submission url Endpoint at rest API
     */
    private function getAttachmentEndPoint(string $idArticle, string $field): string
    {
        return $this->replaceEndPointId(

            $idArticle,
            $this->attachmentUrl[$field]
        );
    }

    /**
     * get article endpoint for deleting api
     */
    private function getArticleWithIdEndPoint(string $identifier): string
    {
        return self::ARTICLE_ENDPOINT . "/${identifier}";
    }

    /**
     * function that actually replace article_id inside endpoint pattern
     */
    private function replaceEndPointId(string $identifier, string $url): string
    {
        return str_replace(
            '{article_id}',
            $identifier,
            $url
        );
    }

    /**
     * normalizing payload. not yet implemented totally, currently just bypass a toArray() function from collection.
     */
    private function normalizePayload(Collection $collection): array
    {
        return $collection->toArray();
    }

    /**
     * Checks if Logger instance exists
     */
    private function hasLogger(): bool
    {
        return isset($this->logger) && $this->logger !== null;
    }
}
