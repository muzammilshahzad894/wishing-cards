<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $design->name }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('frontend/css/cards.css') }}">
    <link rel="stylesheet" href="{{ $design->getTemplateCssUrl() }}">
    <style>
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f0ebe3; padding: 1rem; margin: 0; }
        .card-template { box-shadow: 0 8px 32px rgba(0,0,0,0.12); }
    </style>
</head>
<body>
    @include($design->getTemplateViewPath(), [
        'cardImage' => $cardImage,
        'cardName' => $cardName,
        'greetingText' => $greetingText,
    ])
</body>
</html>
