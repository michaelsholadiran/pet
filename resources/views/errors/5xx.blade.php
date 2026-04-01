@php
    $code = isset($exception) ? (string) $exception->getStatusCode() : '5xx';
    $title = 'Server Error';
    $message = 'Something unexpected happened on our side. Please try again soon.';
@endphp

@include('errors.error-page')
