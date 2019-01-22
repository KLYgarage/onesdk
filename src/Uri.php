<?php declare(strict_types=1);

namespace One;

use InvalidArgumentException;
use Psr\Http\Message\UriInterface;

/**
 * Uri class implementation to ease migration when using other framework vendor. Use psr-7 standard
 */
class Uri implements UriInterface
{
    /**
     * Uri scheme (without "://" suffix)
     *
     * @var string
     */
    protected $scheme = '';

    /**
     * Uri user
     *
     * @var string
     */
    protected $user = '';

    /**
     * Uri password
     *
     * @var string
     */
    protected $password = '';

    /**
     * Uri host
     *
     * @var string
     */
    protected $host = '';

    /**
     * Uri port number
     *
     * @var int|null
     */
    protected $port;

    /**
     * Uri path
     *
     * @var string
     */
    protected $path = '';

    /**
     * Uri query string (without "?" prefix)
     *
     * @var string
     */
    protected $query = '';

    /**
     * Uri fragment string (without "#" prefix)
     *
     * @var string
     */
    protected $fragment = '';

    /**
     * Instance new Uri.
     *
     * @param string $scheme   Uri scheme.
     * @param string $host     Uri host.
     * @param int    $port     Uri port number.
     * @param string $path     Uri path.
     * @param string $query    Uri query string.
     * @param string $fragment Uri fragment.
     * @param string $user     Uri user.
     * @param string $password Uri password.
     */
    public function __construct(
        string $scheme,
        string $host = '',
        ?int $port = null,
        string $path = '/',
        string $query = '',
        string $fragment = '',
        string $user = '',
        string $password = ''
    ) {
        $this->scheme = $this->filterScheme($scheme);
        $this->host = $host;
        $this->port = $this->filterPort($port);
        $this->path = empty($path) ? '/' : $this->filterPath($path);
        $this->query = $this->filterQuery($query);
        $this->fragment = $this->filterQuery($fragment);
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $scheme = $this->getScheme();
        $authority = $this->getAuthority();
        $path = $this->getPath();
        $query = $this->getQuery();
        $fragment = $this->getFragment();

        return ($scheme ? $scheme . ':' : '')
            . ($authority ? '//' . $authority : '')
            . $path
            . ($query ? '?' . $query : '')
            . ($fragment ? '#' . $fragment : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthority()
    {
        $userInfo = $this->getUserInfo();
        $host = $this->getHost();
        $port = $this->getPort();

        return ($userInfo ? $userInfo . '@' : '') . $host . ($port !== null ? ':' . $port : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getUserInfo()
    {
        return $this->user . ($this->password ? ':' . $this->password : '');
    }

    /**
     * {@inheritdoc}
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * {@inheritdoc}
     */
    public function getPort()
    {
        return $this->port !== null && ! $this->hasStandardPort() ? $this->port : null;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * {@inheritdoc}
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * {@inheritdoc}
     */
    public function withScheme($scheme)
    {
        $scheme = $this->filterScheme($scheme);
        $clone = clone $this;
        $clone->scheme = $scheme;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;
        $clone->user = $user;
        $clone->password = $password ?: '';

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = $host;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPort($port)
    {
        $port = $this->filterPort($port);
        $clone = clone $this;
        $clone->port = $port;

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withPath($path)
    {
        $clone = clone $this;
        $clone->path = $this->filterPath($path);

        return $clone;
    }

    /**
     * {@inheritdoc}
     */
    public function withQuery($query)
    {
        return $this->withString($query);
    }

    /**
     * {@inheritdoc}
     */
    public function withFragment($fragment)
    {
        return $this->withString($fragment, 'fragment');
    }

    /**
     * get Base Url
     *
     * @access public
     */
    public function getBaseUrl(): string
    {
        $scheme = $this->getScheme();
        $authority = $this->getAuthority();

        return ($scheme ? $scheme . ':' : '')
            . ($authority ? '//' . $authority : '');
    }

    /**
     * withString function.
     *
     * @access protected
     * @param string $name (default: 'query')
     */
    protected function withString(string $string, string $name = 'query'): self
    {
        $string = ltrim((string) $string, '#');
        $clone = clone $this;
        $clone->{$name} = $this->filterQuery($string);

        return $clone;
    }

    /*
        END OF UriInterface Implementation
    */

    /**
     * filter scheme given to only allow certain scheme, no file:// or ftp:// or other scheme because its http message uri interface
     *
     * @access protected
     * @return string $scheme
     * @throws InvalidArgumentException if not corret scheme is present
     */
    protected function filterScheme(string $scheme): string
    {
        static $valid = [
            '' => true,
            'https' => true,
            'http' => true,
        ];

        $scheme = str_replace('://', '', strtolower($scheme));
        if (! isset($valid[$scheme])) {
            throw new InvalidArgumentException('Uri scheme must be one of: "", "https", "http"');
        }

        return $scheme;
    }

    /**
     * Filter allowable port to minimize risk
     *
     * @access protected
     * @return integer|null $port
     * @throws InvalidArgumentException for incorrect port assigned
     */
    protected function filterPort(?int $port): ?int
    {
        if ((integer) $port >= 0 && (integer) $port <= 65535) {
            return $port;
        }

        throw new InvalidArgumentException('Uri port must be null or an integer between 1 and 65535 (inclusive)');
    }

    /**
     * Path allowed chars filter, no weird path on uri yes?.
     *
     * @access protected
     * @return string of cleared path
     */
    protected function filterPath(string $path): string
    {
        return preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~:@&=\+\$,\(\)\/;%]+|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $path
        );
    }

    /**
     * replace query to clear not allowed chars
     *
     * @access protected
     * @return string of replaced query
     */
    protected function filterQuery(string $query): string
    {
        return preg_replace_callback(
            '/(?:[^a-zA-Z0-9_\-\.~!\$&\'\(\)\*\+,;=%:@\/\?]+|%(?![A-Fa-f0-9]{2}))/',
            function ($match) {
                return rawurlencode($match[0]);
            },
            $query
        );
    }

    /**
     * cek if current uri scheme use standard port
     *
     * @access protected
     */
    protected function hasStandardPort(): bool
    {
        return ($this->scheme === 'http' && $this->port === 80) || ($this->scheme === 'https' && $this->port === 443);
    }
}
