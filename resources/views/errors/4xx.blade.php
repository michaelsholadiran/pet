@php
    $code = isset($exception) ? (string) $exception->getStatusCode() : '4xx';
    $title = 'Request Error';
    $message = 'There was a problem with this request. Please check the link and try again.';
@endphp

@include('errors.error-page')
