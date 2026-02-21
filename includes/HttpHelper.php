<?php
/**
 * HttpHelper – cURL wrapper for HTTP requests (JSON, form, Basic auth).
 * Used by api/paypal.php and can be reused elsewhere.
 */

class HttpHelper
{
    private array $options = [];
    private array $headers = [];
    private string $contentType = 'application/json';

    public static function make(): self
    {
        return new self();
    }

    public function withBasicAuth(string $username, string $password): self
    {
        $this->options[CURLOPT_USERPWD] = $username . ':' . $password;
        return $this;
    }

    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function withHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function contentType(string $type): self
    {
        $this->contentType = $type;
        $this->withHeader('Content-Type', $type);
        return $this;
    }

    public function timeout(int $seconds): self
    {
        $this->options[CURLOPT_TIMEOUT] = $seconds;
        return $this;
    }

    public function post(string $url, array $data = []): array
    {
        return $this->request($url, 'POST', $data);
    }

    public function get(string $url, array $query = []): array
    {
        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }
        return $this->request($url, 'GET');
    }

    private function request(string $url, string $method, array $data = []): array
    {
        $defaultHeaders = [
            'Accept'       => 'application/json',
            'Content-Type' => $this->contentType,
        ];

        $allHeaders = array_merge($defaultHeaders, $this->headers);

        $ch = curl_init($url);

        $curlOpts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => $method,
            CURLOPT_HTTPHEADER     => $this->formatHeaders($allHeaders),
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
        ];
        // Use + to preserve integer keys (CURLOPT_*); array_merge would renumber them and break curl_setopt_array
        $curlOpts = $curlOpts + $this->options;

        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            if (!empty($data)) {
                if ($this->contentType === 'application/x-www-form-urlencoded') {
                    $body = http_build_query($data);
                } else {
                    $body = json_encode($data);
                }
            } else {
                $body = ($this->contentType === 'application/json') ? '{}' : '';
            }
            if ($body !== '') {
                $curlOpts[CURLOPT_POSTFIELDS] = $body;
            }
        }

        curl_setopt_array($ch, $curlOpts);

        $response   = curl_exec($ch);
        $httpCode   = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err        = curl_error($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $rawHeaders = substr($response, 0, $headerSize);
        $body       = substr($response, $headerSize);

        // When no headers in response (e.g. CURLOPT_HEADER false), headerSize can be 0; if body empty but response looks like JSON, use full response as body
        if ($body === '' && $response !== '' && (strpos(trim($response), '{') === 0 || strpos(trim($response), '[') === 0)) {
            $body   = $response;
            $headerSize = 0;
            $rawHeaders = '';
        }

        curl_close($ch);

        if ($err) {
            return ['success' => false, 'error' => $err, 'http_code' => $httpCode];
        }

        $decoded = json_decode($body, true);
        if ($decoded === null && trim($body) !== '') {
            $decoded = $body;
        } elseif ($decoded === null) {
            $decoded = [];
        }

        $success = $httpCode >= 200 && $httpCode < 300;

        return [
            'success'   => $success,
            'data'      => $decoded,
            'error'     => $success ? null : ((is_array($decoded) ? ($decoded['message'] ?? $decoded['name'] ?? null) : null) ?: 'HTTP ' . $httpCode),
            'http_code' => $httpCode,
            'headers'   => $this->parseHeaders($rawHeaders),
        ];
    }

    private function formatHeaders(array $headers): array
    {
        $formatted = [];
        foreach ($headers as $k => $v) {
            $formatted[] = "$k: $v";
        }
        return $formatted;
    }

    private function parseHeaders(string $raw): array
    {
        $headers = [];
        $lines = explode("\r\n", $raw);
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                [$key, $val] = explode(':', $line, 2);
                $headers[trim($key)] = trim($val);
            }
        }
        return $headers;
    }
}
