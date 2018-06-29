<?php

namespace One;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use One\Model\Article;
use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;

/**
 * Publisher class
 * main class to be used that interfacing to the API
 */
class Publisher implements LoggerAwareInterface
{
    const DEFAULT_MAX_ATTEMPT = 4;

    const REST_SERVER = 'https://dev.one.co.id';
    const AUTHENTICATION = '/oauth/token';
    const ARTICLE_CHECK_ENDPOINT = '/api/article';
    const ARTICLE_ENDPOINT = '/api/publisher/article';

    const ATTACHMENT_URL = array(
        Article::ATTACHMENT_FIELD_GALLERY => self::ARTICLE_ENDPOINT . '/{article_id}/gallery',
        Article::ATTACHMENT_FIELD_PAGE => self::ARTICLE_ENDPOINT . '/{article_id}/page',
        Article::ATTACHMENT_FIELD_PHOTO => self::ARTICLE_ENDPOINT . '/{article_id}/photo',
        Article::ATTACHMENT_FIELD_VIDEO => self::ARTICLE_ENDPOINT . '/{article_id}/video'
    );

    /**
     * Logger variable, if set log activity to this obejct each time sending request and receiving response
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $logger = null;

    /**
     * credentials props
     *
     * @var string $clientId
     * @var string $clientSecret
     */
    private $clientId;
    private $clientSecret;

    /**
     * Oauth access token response
     *
     * @var string $accessToken
     */
    private $accessToken = null;

    /**
     * publisher custom options
     *
     * @var \One\Collection $options
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
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param array $options
     */
    public function __construct($clientId, $clientSecret, $options = array())
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        $this->assessOptions($options);
    }

    /**
     * recycleToken from callback. If use external token storage could leveraged on this
     *
     * @param \Closure $tokenProducer
     * @return self
     */
    public function recycleToken(\Closure $tokenProducer)
    {
        return $this->setAuthorizationHeader($tokenProducer());
    }

    /**
     * assessing and custom option
     *
     * @param array $options
     * @return void
     */
    private function assessOptions($options)
    {
        $defaultOptions = array(
            'rest_server' => self::REST_SERVER,
            'auth_url' => self::AUTHENTICATION,
            'max_attempt' => self::DEFAULT_MAX_ATTEMPT,
            'default_headers' => array(
                "Accept" => "application/json"
            )
        );

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
     * @param string $method
     * @param string $path
     * @param array $header
     * @param array $body
     * @param array $options
     * @return string
     */
    private function requestGate($method, $path, $header = array(), $body = array(), $options = array())
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
     * @param \Guzzle\Http\Message\Request $request
     * @param integer $attempt
     * @return \Guzzle\Http\EntityBodyInterface|string|null
     * @throws \Exception
     * @throws \Guzzle\Http\Exception\ClientErrorResponseException
     * @throws \Guzzle\Http\Exception\BadResponseException
     */
    private function sendRequest(Request $request, $attempt = 0)
    {
        if ($attempt >= $this->options->get('max_attempt')) {
            throw new \Exception("MAX attempt reached for " . $request->getUrl() . " with payload " . (string) $request);
        }

        try {
            $response = $request->send();
            if ($response->getStatusCode() == 200) {
                return $response->getBody();
            }
            if ($response->getStatusCode() == 429) {
                $this->renewAuthToken();
            }

            return $this->sendRequest($request, $attempt++);
        } catch (ClientErrorResponseException $err) {
            if ($err->getResponse()->getStatusCode() == 429) {
                $this->renewAuthToken();
                return $this->sendRequest($err->getRequest(), $attempt++);
            }

            throw $err;
        } catch (\Exception $err) {
            throw $err;
        }
    }

    /**
     * renewing access_token
     *
     * @return self
     * @throws \Exception
     */
    private function renewAuthToken()
    {
        $token = (string) $this->sendRequest(
            $this->httpClient->post(
                self::AUTHENTICATION,
                $this->options->get('default_headers'),
                array(
                    "grant_type" => "client_credentials",
                    "client_id" => $this->clientId,
                    "client_secret" => $this->clientSecret
                )
            )
        );

        $token = json_decode($token, true);

        if (empty($token)) {
            throw new \Exception("Access token request return empty response");
        }

        return $this->setAuthorizationHeader(
            $token['access_token']
        );
    }

    /**
     * set header for OAuth 2.0
     *
     * @param string $accessToken
     * @return self
     */
    private function setAuthorizationHeader($accessToken)
    {
        $this->accessToken = $accessToken;

        $this->options->set(
            'default_headers',
            array_merge(
                $this->options->get('default_headers'),
                array(
                    "Authorization" => "Bearer " . $accessToken
                )
            )
        );

        return $this;
    }

    /**
     * get Attachment Submission url Endpoint at rest API
     *
     * @param string $idArticle
     * @param string $field
     * @return string
     */
    private function getAttachmentEndPoint($idArticle, $field)
    {
        return $this->replaceEndPointId(
            $idArticle,
            self::ATTACHMENT_URL[$field]
        );
    }

    /**
     * get article endpoint for deleting api
     *
     * @param string $identifier
     * @return string
     */
    private function getArticleWithIdEndPoint($identifier)
    {
        return self::ARTICLE_ENDPOINT . "/$identifier";
    }

    /**
     * function that actually replace article_id inside endpoint pattern
     *
     * @param string $identifier
     * @param string $url
     * @return string
     */
    private function replaceEndPointId($identifier, $url)
    {
        return str_replace(
            '{article_id}',
            $identifier,
            $url
        );
    }

    /**
     * normalizing payload. not yet implemented totally, currently just bypass a toArray() function from collection.
     *
     * @param \One\Collection $collection
     * @return array
     */
    private function normalizePayload(Collection $collection)
    {
        return $collection->toArray();
    }

    /**
     * submitting article here, return new Object cloned from original
     * IMMUTABLE
     *
     * @param \One\Model\Article $article
     * @return \One\Model\Article
     */
    public function submitArticle(Article $article)
    {
        $responseArticle = $this->post(
            self::ARTICLE_ENDPOINT,
            $this->normalizePayload(
                $article->getCollection()
            )
        );

        $responseArticle = json_decode($responseArticle, true);
        $article->setId($responseArticle['data']['id']);

        $processedArticle = clone $article;

        foreach (Article::POSSIBLE_ATTACHMENT as $field) {
            if ($article->hasAttachment($field)) {
                foreach ($article->getAttachmentByField($field) as $attachment) {
                    $result = $this->submitAttachment(
                        $this->getAttachmentEndPoint(
                            $article->getId(),
                            $field
                        ),
                        $attachment,
                        $field
                    );

                    $processedArticle->add($field, $result);
                }
            }
        }

        return $processedArticle;
    }

    /**
     * submit each attachment of an article here
     *
     * @param string $idArticle
     * @param \One\Model\Model $attachment
     * @param string $field
     * @return array
     */
    public function submitAttachment($idArticle, Model $attachment, $field)
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
     * @param string $idArticle
     * @return string json
     */
    public function getArticle($idArticle)
    {
        return $this->get(
            self::ARTICLE_CHECK_ENDPOINT . "/$idArticle"
        );
    }

    /**
     * get list article by publisher
     *
     * @return string json
     */
    public function listArticle()
    {
        return $this->get(
            self::ARTICLE_ENDPOINT
        );
    }

    /**
     * delete article based on id
     *
     * @param string $idArticle
     * @return string
     */
    public function deleteArticle($idArticle)
    {
        $articleOnRest = $this->getArticle($idArticle);

        if (!empty($articleOnRest)) {
            $articleOnRest = json_decode($articleOnRest, true);

            if (isset($articleOnRest['data'])) {
                $deleteAbleAttachment = array_diff(
                    Article::POSSIBLE_ATTACHMENT,
                    array(
                        Article::ATTACHMENT_FIELD_PHOTO
                    )
                );

                foreach ($deleteAbleAttachment as $field) {
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
     *
     * @param string $idArticle
     * @param string $field
     * @param string $order
     * @return string
     */
    public function deleteAttachment($idArticle, $field, $order)
    {
        return $this->delete(
            $this->getAttachmentEndPoint($idArticle, $field) . "/$order"
        );
    }

    /**
     * get proxy
     *
     * @param string $path
     * @param array $header
     * @param array $options
     * @return string
     */
    final public function get($path, $header = array(), $options = array())
    {
        return $this->requestGate(
            'GET',
            $path,
            $header,
            array(),
            $options
        );
    }

    /**
     * post proxy
     *
     * @param string $path
     * @param \One\Collection|array $body
     * @param \One\Collection|array $header
     * @param array $options
     * @return string
     */
    final public function post($path, $body, $header = array(), $options = array())
    {
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
     * @param string $path
     * @param \One\Collection|array $body
     * @param \One\Collection|array $header
     * @param array $options
     * @return string
     */
    final public function delete($path, $body = array(), $header = array(), $options = array())
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
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
