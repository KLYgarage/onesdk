<?php declare(strict_types=1);

namespace One;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Client;
use One\Model\Livestreaming;
use One\Model\Headline;
use One\Model\Article;
use One\Model\Model;
use One\Model\Tag;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Publisher class
 * main class to be used that interfacing to the API
 */
class Publisher implements LoggerAwareInterface
{
    public const DEFAULT_MAX_ATTEMPT = 4;

    public const AUTHENTICATION = '/oauth/token';

    public const ARTICLE_CHECK_ENDPOINT = '/api/article';

    public const ARTICLE_ENDPOINT = '/api/publisher/article';

    public const PUBLISHER_ENDPOINT = '/api/article/publisher';

    public const TAG_ENDPOINT = '/api/publisher/tag';

    public const POST_HEADLINE_ENDPOINT = '/api/publisher/headline';

    public const POST_LIVESTREAMING_ENDPOINT = '/api/publisher/livestreaming';

    /**
     *  base url REST API
     * @var array<string>
     */
    private $restServer = 'https://www.one.co.id';


    /**
     * attachment url destination
     * @var array<string>
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
     * @var \GuzzleHttp\Client;
     */
    private $httpClient;

    /**
     * token saver storage
     *
     * @var \Closure
     */
    private $tokenSaver = null;

    /**
     * constructor
     */
    public function __construct(string $clientId, string $clientSecret, array $options = [], bool $development = false)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        if ($development) {
            $this->restServer = 'https://dev.one.co.id';
        }

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
        return $this->setAuthorizationHeader(
            $tokenProducer()
        );
    }

    /**
     * set Token Saver
     */
    public function setTokenSaver(\Closure $tokenSaver): self
    {
        $this->tokenSaver = $tokenSaver;

        return $this;
    }

    public function getTokenSaver(): \Closure
    {
        return $this->tokenSaver;
    }


    /**
     * submitting article here, return new Object cloned from original
     */
    public function submitLivestreaming(Livestreaming $livestreaming): Livestreaming
    {
        $responseLivestreaming = $this->post(
            self::POST_LIVESTREAMING_ENDPOINT,
            $this->normalizePayload(
                $article->getCollection()
            )
        );

        $responseLivestreaming = json_decode($responseLivestreaming, true);
        
        $id = isset($responseLivestreaming['data']['livestreaming_id']) ?
            $responseLivestreaming['data']['livestreaming_id'] : null;

        $id = isset($responseLivestreaming['data'][0]['livestreaming_id']) ?
            $responseLivestreaming['data'][0]['livestreaming_id'] : $id;

        $livestreaming->setId((string) $id);

        return $livestreaming;
    }

    /**
     * submitting headline here, return new Object cloned from original
     */
    public function submitHeadline(Headline $headline): Headline
    {
        $responseHeadline = $this->post(
            self::POST_HEADLINE_ENDPOINT,
            $this->normalizePayload(
                $headline->getCollection()
            )
        );

        $responseHeadline = json_decode($responseHeadline, true);
        $id = isset($responseHeadline['data']['headline_id']) ?
            $responseHeadline['data']['headline_id'] : null;

        $id = isset($responseHeadline['data'][0]['headline_id']) ?
            $responseHeadline['data'][0]['headline_id'] : $id;

        $headline->setId((string) $id);

        return $headline;
    }

    /**
     * submitting article here, return new Object cloned from original
     */
    public function submitArticle(Article $article): Article
    {
        $responseArticle = $this->post(
            self::ARTICLE_ENDPOINT,
            $this->normalizePayload(
                $article->getCollection()
            )
        );

        $responseArticle = json_decode($responseArticle, true);
        $article->setId((string) $responseArticle['data']['id']);

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
     * submit tag here
     */
    public function submitTag(Tag $tag): Tag
    {
        $responseTag = $this->post(
            self::TAG_ENDPOINT,
            $this->normalizePayload(
                $tag->getCollection()
            )
        );

        $responseTag = json_decode($responseTag, true);

        $id = isset($responseTag['data']['tag_id']) ?
            $responseTag['data']['tag_id'] : null;

        $id = isset($responseTag['data'][0]['tag_id']) ?
            $responseTag['data'][0]['tag_id'] : $id;

        $tag->setId((string) $id);

        return $tag;
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
     * get list visual story by publisher
     *
     * @return string json
     */
    public function listStories(int $page = 1): string
    {
        $params = [
            'filter[type_id]' => 4,
            'page' => $page,
            'apps' => 0
        ];

        return $this->get(
            self::PUBLISHER_ENDPOINT.'/'. $this->clientId .'/?'. http_build_query($params)
        );
    }

    /**
     * delete article based on id
     */
    public function deleteArticle(string $idArticle): ?string
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
     */
    final public function post(string $path, array $body = [], array $header = [], array $options = []): string
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
     * @param array $body
     */
    final public function delete(string $path, array $body = [], array $header = [], array $options = []): string
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

    public function getRestServer(): string
    {
        return $this->options->get('rest_server');
    }

    /**
     * assessing and custom option
     */
    private function assessOptions(array $options): void
    {
        $defaultOptions = [
            'rest_server' => $this->restServer,
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

        if (isset($options['recycle_token']) && is_callable($options['recycle_token'])) {
            $this->recycleToken(
                $options['recycle_token']
            );
        }

        if (isset($options['token_saver']) && is_callable($options['token_saver'])) {
            $this->setTokenSaver(
                $options['token_saver']
            );
        }

        $this->httpClient = new Client([
            'base_uri' => $this->options->get('rest_server'),
        ]);
    }

    /**
     * one gate menu for request creation.
     *
     * @param \One\Collection|array $body
     */
    private function requestGate(string $method, string $path, array $header = [], array $body = [], array $options = []): string
    {
        if (empty($this->accessToken)) {
            $this->renewAuthToken();
        }

        $request = new \GuzzleHttp\Psr7\Request(
            $method,
            $path,
            array_merge(
                $this->options->get('default_headers'),
                $header
            ),
            $this->createBodyForRequest(
                $this->prepareMultipartData($body)
            )
        );

        return (string) $this->sendRequest($request);
    }

    private function prepareMultipartData(array $data = []): array
    {
        $result = [];
        foreach ($data as $key => $value) {
            array_push($result, ['name' => $key, 'contents' => $value]);
        }
        return $result;
    }

    /**
     * actually send request created here, separated for easier attempt count and handling exception
     *
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\ClientException
     * @throws \GuzzleHttp\Exception\BadResponseException
     */
    private function sendRequest(RequestInterface $request, int $attempt = 0): \Psr\Http\Message\StreamInterface
    {
        if ($attempt >= $this->options->get('max_attempt')) {
            throw new \Exception('MAX attempt reached for ' . $request->getUri() . ' with payload ' . (string) $request);
        }

        try {
            $response = $this->httpClient->send(
                $request,
                [
                    'allow_redirects' => false,
                    'synchronous' => true,
                    'curl' => [
                        CURLOPT_FORBID_REUSE => true,
                        CURLOPT_MAXCONNECTS => 30,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYSTATUS => false,
                    ],
                ]
            );
            if ($response->getStatusCode() === 200) {
                return $response->getBody();
            }

            return $this->sendRequest($request, $attempt++);
        } catch (ClientException $err) {
            if ($err->getResponse()->getStatusCode() === 401 && $request->getRequestTarget() !== self::AUTHENTICATION) {
                $this->renewAuthToken();
                return $this->sendRequest($request, $attempt++);
            }

            throw $err;
        } catch (\Throwable $err) {
            throw $err;
        }
    }

    /**
     * createBodyForRequest
     */
    private function createBodyForRequest(array $body = []): ?\GuzzleHttp\Psr7\MultiPartStream
    {
        if (empty($body)) {
            return null;
        }
        return new MultipartStream($body);
    }

    /**
     * renewing access_token
     *
     * @throws \Exception
     */
    private function renewAuthToken(): self
    {
        $request = new \GuzzleHttp\Psr7\Request(
            'POST',
            self::AUTHENTICATION,
            $this->options->get('default_headers'),
            $this->createBodyForRequest([
                ['name' => 'grant_type',
                    'contents' => 'client_credentials', ],
                ['name' => 'client_id',
                    'contents' => $this->clientId, ],
                ['name' => 'client_secret',
                    'contents' => $this->clientSecret, ],
            ])
        );

        $token = (string) $this->sendRequest($request);

        $token = json_decode($token, true);

        if (empty($token)) {
            throw new \Exception('Access token request return empty response');
        }

        if (! empty($this->tokenSaver)) {
            $this->getTokenSaver()(
                $token['access_token']
            );
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

